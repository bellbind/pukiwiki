<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: init.php,v 1.35 2003/02/28 03:36:59 panda Exp $
//

/////////////////////////////////////////////////
// ������� (ʸ�����󥳡��ɡ�����)
define('SOURCE_ENCODING','EUC-JP');
define('LANG','ja');
mb_internal_encoding(SOURCE_ENCODING);
mb_http_output(SOURCE_ENCODING);

/////////////////////////////////////////////////
// �������(����ե�����ξ��)
define('INI_FILE','./pukiwiki.ini.php');

/////////////////////////////////////////////////
// ������� (�С������/���)
define('S_VERSION','1.4pre5');
define('S_COPYRIGHT','
<strong>"PukiWiki" '.S_VERSION.'</strong> Copyright &copy; 2001,2002,2003
<a href="http://pukiwiki.org">PukiWiki Developers Team</a>.
License is <a href="http://www.gnu.org/">GNU/GPL</a>.<br />
Based on "PukiWiki" 1.3 by <a href="http://factage.com/sng/">sng</a>
');

/////////////////////////////////////////////////
// ������� (�������ѿ�)
foreach (array('HTTP_USER_AGENT','PHP_SELF','SERVER_NAME','SERVER_SOFTWARE','SERVER_ADMIN') as $key) {
	define($key,array_key_exists($key,$HTTP_SERVER_VARS) ? $HTTP_SERVER_VARS[$key] : '');
}

/////////////////////////////////////////////////
// ������� (�����Х��ѿ�)
// �����Ф�������ѿ�
$vars = array();
// ����
$foot_explain = array();
// ��Ϣ����ڡ���
$related = array();

/////////////////////////////////////////////////
// �������(����)
define('LOCALZONE',date('Z'));
define('UTIME',time() - LOCALZONE);
define('MUTIME',getmicrotime());

/////////////////////////////////////////////////
// ����ե������ɤ߹���
if (!file_exists(LANG.'.lng')||!is_readable(LANG.'.lng')) {
	die_message(LANG.'.lng(language file) is not found.');
}
require(LANG.'.lng');

/////////////////////////////////////////////////
// ����ե������ɤ߹���
if (!file_exists(INI_FILE)||!is_readable(INI_FILE)) {
	die_message(INI_FILE.' is not found.');
}
require(INI_FILE);

/////////////////////////////////////////////////
// �������($script)
if (!isset($script) or $script == '') {
	$script  = ($_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://');
	$script .=  $_SERVER['SERVER_NAME'];
	$script .= ($_SERVER['SERVER_PORT'] == 80 ? '' : ':'.$_SERVER['SERVER_PORT']);
	$parse_url = parse_url($_SERVER['REQUEST_URI']);
	$script .= (isset($parse_url['path']) ? $parse_url['path'] : $_SERVER['SCRIPT_NAME']);
}
if (php_sapi_name() == 'cgi' && !preg_match("/^http:\/\/[-a-zA-Z0-9\@:;_.]+\//",$script)) {
	die_message("please set '\$script' in ".INI_FILE);
}

/////////////////////////////////////////////////
// ����ե������ɤ߹���(UserAgent)
foreach ($agents as $agent) {
	if (preg_match($agent['pattern'],HTTP_USER_AGENT,$matches)) {
		$agent['matches'] = $matches;
		$user_agent = $agent;
		break;
	}
}
define('UA_INI_FILE',$user_agent['name'].'.ini.php');

if (!file_exists(UA_INI_FILE)||!is_readable(UA_INI_FILE)) {
	die_message(UA_INI_FILE.' is not found.');
}
require(UA_INI_FILE);

/////////////////////////////////////////////////
// ����ե�������ѿ������å�
if(!is_writable(DATA_DIR)) {
	die_message('DATA_DIR is not found or not writable.');
}
if(!is_writable(DIFF_DIR)) {
	die_message('DIFF_DIR is not found or not writable.');
}
if($do_backup && !is_writable(BACKUP_DIR)) {
	die_message('BACKUP_DIR is not found or not writable.');
}
if(!is_writable(CACHE_DIR)) {
	die_message('CACHE_DIR is not found or not writable.');
}
$wrong_ini_file = '';
if (!isset($rss_max)) $wrong_ini_file .= '$rss_max ';
if (!isset($page_title)) $wrong_ini_file .= '$page_title ';
if (!isset($note_hr)) $wrong_ini_file .= '$note_hr ';
if (!isset($related_link)) $wrong_ini_file .= '$related_link ';
if (!isset($show_passage)) $wrong_ini_file .= '$show_passage ';
if (!isset($rule_related_str)) $wrong_ini_file .= '$rule_related_str ';
if (!isset($load_template_func)) $wrong_ini_file .= '$load_template_func ';
if (!defined('LANG')) $wrong_ini_file .= 'LANG ';
if (!defined('PLUGIN_DIR')) $wrong_ini_file .= 'PLUGIN_DIR ';
if ($wrong_ini_file) {
	die_message('The setting file runs short of information.<br>The version of a setting file may be old.<br><br>These option are not found : '.$wrong_ini_file);
}
if (!is_page($defaultpage)) {
	touch(get_filename($defaultpage));
}
if (!is_page($whatsnew)) {
	touch(get_filename($whatsnew));
}
if (!is_page($interwiki)) {
	touch(get_filename($interwiki));
}

/////////////////////////////////////////////////
// �����ͤ�����
if (get_magic_quotes_gpc()) {
	$get = $post = $cookie = array();
	foreach($HTTP_GET_VARS as $key => $value) {
		if (!is_array($value)) {
			$get[$key] = stripslashes($value);
		}
	}
	foreach($HTTP_POST_VARS as $key => $value) {
		$post[$key] = stripslashes($value);
	}
	foreach($HTTP_COOKIE_VARS as $key => $value) {
		$cookie[$key] = stripslashes($value);
	}
}
else {
	$post = is_array($HTTP_POST_VARS) ? $HTTP_POST_VARS : array();
	$get = is_array($HTTP_GET_VARS) ? $HTTP_GET_VARS : array();
	$cookie = is_array($HTTP_COOKIE_VARS) ? $HTTP_COOKIE_VARS : array();
}

// �������餯���ѿ��򥵥˥�����
$get    = sanitize_null_character($get);
$post   = sanitize_null_character($post);
$cookie = sanitize_null_character($cookie);

// �ݥ��Ȥ��줿ʸ���Υ����ɤ��Ѵ�
// original by nitoyon (2003/02/20)
$encode = mb_detect_encoding(join('',array_merge($post,$get)));
if ($encode != 'ASCII' and $encode != SOURCE_ENCODING) {
	foreach(array_keys($get) as $key) {
		$get[$key] = mb_convert_encoding($get[$key],SOURCE_ENCODING,$encode);
	}
	foreach(array_keys($post) as $key) {
		$post[$key] = mb_convert_encoding($post[$key],SOURCE_ENCODING,$encode);
	}
}

if (!empty($get['page'])) {
	$get['page']  = strip_bracket($get['page']);
}
if (!empty($post['page'])) {
	$post['page'] = strip_bracket($post['page']);
}
if (!empty($post['msg'])) {
	$post['msg']  = str_replace("\r",'',$post['msg']);
}

$vars = array_merge($post,$get);
if (!array_key_exists('page',$vars)) {
	$get['page'] = $post['page'] = $vars['page'] = '';
}

// �����ߴ��� (?md5=...)
if (array_key_exists('md5',$vars) and $vars['md5'] != '') {
	$vars['cmd'] = 'md5';
}

// cmd��plugin����ꤵ��Ƥ��ʤ����ϡ�QUERY_STRING��ڡ���̾��InterWikiName�Ǥ���Ȥߤʤ�
if (!array_key_exists('cmd',$vars)  and !array_key_exists('plugin',$vars))
{
	if ($HTTP_SERVER_VARS['QUERY_STRING'] != '')
	{
		$arg = $HTTP_SERVER_VARS['QUERY_STRING'];
	}
	else if (array_key_exists(0,$HTTP_SERVER_VARS['argv']))
	{
		$arg = $HTTP_SERVER_VARS['argv'][0];
	}
	else
	{
		//�ʤˤ���ꤵ��Ƥ��ʤ��ä�����$defaultpage��ɽ��
		$arg = $defaultpage;
	}		
	$arg = rawurldecode($arg);
	$arg = strip_bracket($arg);
	$arg = sanitize_null_character($arg);

	$get['cmd'] = $post['cmd'] = $vars['cmd'] = 'read';
	$get['page'] = $post['page'] = $vars['page'] = $arg;
}

/////////////////////////////////////////////////
// �������($WikiName,$BracketName�ʤ�)
// $WikiName = '[A-Z][a-z]+(?:[A-Z][a-z]+)+';
// $WikiName = '\b[A-Z][a-z]+(?:[A-Z][a-z]+)+\b';
$WikiName = '(?<![[:alnum:]])(?:[[:upper:]][[:lower:]]+){2,}(?![[:alnum:]])';
// $BracketName = ':?[^\s\]#&<>":]+:?';
$BracketName = '(?!\s):?[^\r\n\t\f\[\]<>#&":]+:?(?<!\s)';
// InterWiki
$InterWikiName = "(\[\[)?(\[*[^\s\]]+?\]*):(\[*[^>\]]+?\]*)(?(1)\]\])";
// ���
$NotePattern = '/\(\(((?:(?>(?:(?!\(\()(?!\)\)(?:[^\)]|$)).)+)|(?R))*)\)\)/ex';

/////////////////////////////////////////////////
// �������(����¾�Υ����Х��ѿ�)
// ���߻���
$now = format_date(UTIME);
// skin���DTD������ڤ��ؤ���Τ˻��ѡ�paint.inc.php�к�
// FALSE:XHTML 1.1
// TRUE :XHTML 1.0 Transitional
$html_transitional = FALSE;
// �ե������ޡ�����$line_rules�˲ä���
if ($usefacemark) {
	$line_rules = array_merge($line_rules,$facemark_rules);
}
// �桼������롼��
$user_rules = array_merge($str_rules,$line_rules);
?>
