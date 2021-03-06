<?php
if (!isset($ChatPath)) $ChatPath = "";

// Available languages
$AvailableLanguages = array();
$languageDirectories = dir('./'.$ChatPath.'localization/');
while($name = $languageDirectories->read())
{
	if(is_dir('./'.$ChatPath.'localization/'.$name)
		&& file_exists('./'.$ChatPath.'localization/'.$name.'/regex.txt')
		&& file_exists('./'.$ChatPath.'localization/'.$name.'/localized.chat.php3')
		&& file_exists('./'.$ChatPath.'localization/'.$name.'/flag.gif'))
	{
		list($key) = file('./'.$ChatPath.'localization/'.$name.'/regex.txt');
		$AvailableLanguages[$key] = $name;
	};
};
$languageDirectories->close();
if (!function_exists("krsort")) include("./${ChatPath}localization/sort_languages.php3");
krsort($AvailableLanguages);

function Detect($Str,$From)
{
	global $AvailableLanguages;
	global $L;

	$NotFound = true;
	reset($AvailableLanguages);
	while($NotFound && list($key, $name) = each($AvailableLanguages))
	{
		if (($From == 1 && eregi("^(".$key.")$",$Str)) || ($From == 2 && eregi("(\(|\[|;[[:space:]])(".$key.")(;|\]|\))",$Str)))
		{
			$L = $AvailableLanguages[$key];
			$NotFound = false;
		};
	};
};

// finds the appropriate language file
if (isset($HTTP_COOKIE_VARS["CookieLang"]))
	$CookieLang = $HTTP_COOKIE_VARS["CookieLang"];
if (!isset($HTTP_ACCEPT_LANGUAGE))
	$HTTP_ACCEPT_LANGUAGE = getenv("HTTP_ACCEPT_LANGUAGE");
if (!isset($HTTP_USER_AGENT))
	$HTTP_USER_AGENT = getenv("HTTP_USER_AGENT");

if ((isset($L) && $L != "" && is_dir('./'.$ChatPath.'localization/'.$L)) || C_MULTI_LANG == 0)
{
}	
elseif (isset($CookieLang)
		&& is_dir('./'.$ChatPath.'localization/'.$CookieLang)
		&& file_exists('./'.$ChatPath.'localization/'.$CookieLang.'/localized.chat.php3'))
{
	$L = $CookieLang;
}
elseif ($HTTP_ACCEPT_LANGUAGE != "")
{	
	$Accepted = explode(",", $HTTP_ACCEPT_LANGUAGE);
	Detect($Accepted[0],1);
}
elseif ($HTTP_USER_AGENT != "")
{	
	Detect($HTTP_USER_AGENT,2);
};

//if no language detected set default one
if (!isset($L)) $L = C_LANGUAGE;

//put language in a cookie
setcookie("CookieLang", $L, time() + 60*60*24*365);		// cookie expires in one year
?>