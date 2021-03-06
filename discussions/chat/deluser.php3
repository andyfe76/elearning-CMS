<?
// Get the names and values for vars sent to this script
if (isset($HTTP_GET_VARS))
{
	while(list($name,$value) = each($HTTP_GET_VARS))
	{
		$$name = $value;
	};
};

// Get the names and values for vars posted from the form bellow
if (isset($HTTP_POST_VARS))
{
	while(list($name,$value) = each($HTTP_POST_VARS))
	{
		$$name = $value;
	};
};

// Fix a security hole
if (isset($L) && !is_dir('./localization/'.$L)) exit();
	
require("./config/config.lib.php3");
require("./localization/languages.lib.php3");
require("./localization/".$L."/localized.chat.php3");
require("./lib/release.lib.php3");
require("./lib/database/".C_DB_TYPE.".lib.php3");
require("./lib/login.lib.php3");

// Special cache instructions for IE5+
$CachePlus	= "";
if (ereg("MSIE [56789]", (isset($HTTP_USER_AGENT)) ? $HTTP_USER_AGENT : getenv("HTTP_USER_AGENT"))) $CachePlus = ", pre-check=0, post-check=0, max-age=0";
$now		= gmdate('D, d M Y H:i:s') . ' GMT';

header("Expires: $now");
header("Last-Modified: $now");
header("Cache-Control: no-cache, must-revalidate".$CachePlus);
header("Pragma: no-cache");
header("Content-Type: text/html; charset={$Charset}");

// avoid server configuration for magic quotes
set_magic_quotes_runtime(0);

if ($perms == "admin") $Msg = L_ERR_USR_12;

if (isset($FORM_SEND) && stripslashes($submit_type) == L_REG_20)
{
	$DbLink = new DB2;
	$DbLink->query("DELETE FROM ".C_REG_TBL." WHERE username='$AUTH_USERNAME'");
	$Msg = L_REG_21;
	$DbLink->close();
};

// For translations with an explicit charset (not the 'x-user-defined' one)
if (!isset($FontName)) $FontName = "";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML dir="<?php echo(($Charset == "windows-1256") ? "RTL" : "LTR"); ?>">

<HEAD>
<TITLE><?php echo(APP_NAME); ?></TITLE>
<LINK REL="stylesheet" HREF="config/style.css.php3?<?php echo("Charset=${Charset}&medium=${FontSize}&FontName=${FontName}"); ?>" TYPE="text/css">
<style type="text/css">
* {font-size: 8pt}
</style>
</HEAD>

<BODY>
<CENTER>
<BR>
<FORM ACTION="deluser.php3" METHOD="POST" AUTOCOMPLETE="OFF" NAME="DelUsrForm">
<INPUT type="hidden" name="FORM_SEND" value="1">
<INPUT type="hidden" name="AUTH_USERNAME" value="<?echo(htmlspecialchars(stripslashes($AUTH_USERNAME)))?>">
<INPUT type="hidden" name="AUTH_PASSWORD" value="<?echo(htmlspecialchars(stripslashes($AUTH_PASSWORD)))?>">
<?php
if(isset($Error))
{
	echo("<P><SPAN CLASS=\"error\">$Error</SPAN></P>");
}
?>
<INPUT TYPE="hidden" NAME="L" VALUE="<?php echo($L); ?>">
<TABLE BORDER=0 CELLPADDING=3 CLASS="table">
<TR>
	<TD ALIGN="CENTER">
		<TABLE BORDER=0>
		<TR>
			<TH CLASS="tabtitle"><?php echo(L_REG_13); ?></TH>
		</TR>
		<TR>
			<TD VALIGN="TOP" ALIGN="CENTER">
				<?php echo(isset($Msg)? $Msg:L_REG_19); ?>
			</TD>
		</TR>
		</TABLE>
		<?if (!isset($Msg)) {?>
		<P>
		<TABLE border=0>
		<TR>
			<TD><INPUT TYPE="submit" NAME="submit_type" VALUE="<?php echo(L_REG_20); ?>"></TD>
			<TD><INPUT TYPE="submit" NAME="submit_type" VALUE="<?php echo(L_REG_22); ?>" onClick="self.close(); return false;"></TD>
		</TR>
		</TABLE>
		<?} else {?>
		<P>
		<INPUT TYPE="submit" NAME="submit_type" VALUE="<?php echo(L_REG_25); ?>" onClick="self.close(); return false;">
		<?}?>
	</TD>
</TR>
</TABLE>
</CENTER>
</BODY>

</HTML>
<?php

?>