<?
// PukiWiki - Yet another WikiWikiWeb clone.
// $Id: init.php,v 1.7 2002/07/04 05:47:37 masui Exp $
/////////////////////////////////////////////////

// ����ե�����ξ��
define("INI_FILE","./pukiwiki.ini.php");

//** ������� **

define("S_VERSION","1.3.1b1");
define("S_COPYRIGHT","<b>\"PukiWiki\" ".S_VERSION."</b> Copyright &copy; 2001,2002 <a href=\"http://pukiwiki.org\">PukiWiki Developers Team</a>, <a href=\"mailto:sng@factage.com\">sng</a>. License is <a href=\"http://www.gnu.org/\">GNU/GPL</a>.");
define("UTIME",time());
define("HTTP_USER_AGENT",$HTTP_SERVER_VARS["HTTP_USER_AGENT"]);
define("PHP_SELF",$HTTP_SERVER_VARS["PHP_SELF"]);
define("SERVER_NAME",$HTTP_SERVER_VARS["SERVER_NAME"]);

define("MUTIME",getmicrotime());

if($script == "") {
	$script = 'http://'.getenv('SERVER_NAME').(getenv('SERVER_PORT')==80?'':(':'.getenv('SERVER_PORT'))).getenv('SCRIPT_NAME');
}

$WikiName = '([A-Z][a-z]+([A-Z][a-z]+)+)';
$BracketName = '\[\[(\[*[^\s\]]+?\]*)\]\]';
$InterWikiName = '\[\[(\[*[^\s\]]+?\]*):(\[*[^>\]]+?\]*)\]\]';
$InterWikiNameNoBracket = '(\[*[^\s\]]+?\]*):(\[*[^>\]]+?\]*)';

//** �����ͤ����� **

$cookie = $HTTP_COOKIE_VARS;

if(get_magic_quotes_gpc())
{
	foreach($HTTP_GET_VARS as $key => $value) {
		$get[$key] = stripslashes($HTTP_GET_VARS[$key]);
	}
	foreach($HTTP_POST_VARS as $key => $value) {
		$post[$key] = stripslashes($HTTP_POST_VARS[$key]);
	}
	foreach($HTTP_COOKIE_VARS as $key => $value) {
		$cookie[$key] = stripslashes($HTTP_COOKIE_VARS[$key]);
	}
}
else {
	$post = $HTTP_POST_VARS;
	$get = $HTTP_GET_VARS;
}

if($post["msg"])
{
	$post["msg"] = preg_replace("/((\x0D\x0A)|(\x0D)|(\x0A))/","\n",$post["msg"]);
}
if($get["page"]) $get["page"] = rawurldecode($get["page"]);
if($post["word"]) $post["word"] = rawurldecode($post["word"]);
if($get["word"]) $get["word"] = rawurldecode($get["word"]);

$vars = array_merge($post,$get);
$arg = rawurldecode(getenv("QUERY_STRING"));

//** ������� **

$update_exec = "";

// ����ե�������ɹ�
@require(INI_FILE);
@require(LANG.".lng");

// �ѿ��Υ����å�
if(!preg_match("/^http:\/\/[-a-zA-Z0-9\@:;_.]+\//",$script))
	die_message("please set '\$script' in ".INI_FILE);


// ����ե�������ѿ������å�
$wrong_ini_file = "";
if(!isset($rss_max)) $wrong_ini_file .= '$rss_max ';
if(!isset($page_title)) $wrong_ini_file .= '$page_title ';
if(!isset($note_hr)) $wrong_ini_file .= '$note_hr ';
if(!isset($related_link)) $wrong_ini_file .= '$related_link ';
if(!isset($show_passage)) $wrong_ini_file .= '$show_passage ';
if(!isset($rule_related_str)) $wrong_ini_file .= '$rule_related_str ';
if(!isset($load_template_func)) $wrong_ini_file .= '$load_template_func ';
if(!defined("LANG")) $wrong_ini_file .= 'LANG ';
if(!defined("PLUGIN_DIR")) $wrong_ini_file .= 'PLUGIN_DIR ';

if(!is_writable(DATA_DIR))
	die_message("DATA_DIR is not found or not writable.");
if(!is_writable(DIFF_DIR))
	die_message("DIFF_DIR is not found or not writable.");
if($do_backup && !is_writable(BACKUP_DIR))
	die_message("BACKUP_DIR is not found or not writable.");
if(!file_exists(INI_FILE))
	die_message("INI_FILE is not found.");
if($wrong_ini_file)
	die_message("The setting file runs short of information.<br>The version of a setting file may be old.<br><br>These option are not found : $wrong_ini_file");
//if(ini_get("register_globals") !== "0")
//	die_message("Wrong PHP4 setting in 'register_globals',set value 'Off' to httpd.conf or .htaccess.");
if(!file_exists(SKIN_FILE))
	die_message("SKIN_FILE is not found.");
if(!file_exists(LANG.".lng"))
	die_message(LANG.".lng(language file) is not found.");

if(!file_exists(get_filename(encode($defaultpage))))
	touch(get_filename(encode($defaultpage)));
if(!file_exists(get_filename(encode($whatsnew))))
	touch(get_filename(encode($whatsnew)));
if(!file_exists(get_filename(encode($interwiki))))
	touch(get_filename(encode($interwiki)));

$ins_date = date($date_format,UTIME);
$ins_time = date($time_format,UTIME);
$ins_week = "(".$weeklabels[date("w",UTIME)].")";

$now = "$ins_date $ins_week $ins_time";

?>
