<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: init.php,v 1.49 2003/06/13 01:04:54 arino Exp $
//

/////////////////////////////////////////////////
// ������� (���顼���ϥ�٥�)
// (E_WARNING | E_NOTICE)��������Ƥ��ޤ���
error_reporting(E_ERROR | E_PARSE);

/////////////////////////////////////////////////
// ������� (ʸ�����󥳡��ɡ�����)
define('SOURCE_ENCODING','EUC-JP');
define('LANG','ja');
mb_internal_encoding(SOURCE_ENCODING);
mb_http_output(SOURCE_ENCODING);
mb_detect_order('ASCII,JIS,EUC,UTF-8,SJIS');
// mb_detect_order('ASCII,JIS,UTF-8,EUC,SJIS'); // UTF-8��ͥ�褹����

/////////////////////////////////////////////////
// �������(����ե�����ξ��)
define('INI_FILE','./pukiwiki.ini.php');

/////////////////////////////////////////////////
// ������� (�С������/���)
define('S_VERSION','1.4rc2');
define('S_COPYRIGHT','
<strong>"PukiWiki" '.S_VERSION.'</strong> Copyright &copy; 2001,2002,2003
<a href="http://pukiwiki.org">PukiWiki Developers Team</a>.
License is <a href="http://www.gnu.org/">GNU/GPL</a>.<br />
Based on "PukiWiki" 1.3 by <a href="http://factage.com/sng/">sng</a>
');

/////////////////////////////////////////////////
// ������� (�������ѿ�)
foreach (array('HTTP_USER_AGENT','PHP_SELF','SERVER_NAME','SERVER_SOFTWARE','SERVER_ADMIN') as $key) {
	define($key,array_key_exists($key,$_SERVER) ? $_SERVER[$key] : '');
}

/////////////////////////////////////////////////
// ������� (�����Х��ѿ�)
// �����Ф�������ѿ�
$vars = array();
// ����
$foot_explain = array();
// ��Ϣ����ڡ���
$related = array();
// <head>����ɲä��륿��
$head_tags = array();
// �ǽ���������
$lastmod_time = 0;

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
	die_message('The setting file runs short of information.<br />The version of a setting file may be old.<br /><br />These option are not found : '.$wrong_ini_file);
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
// �������餯���ѿ��򥵥˥�����
$get    = sanitize($_GET);
$post   = sanitize($_POST);
$cookie = sanitize($_COOKIE);

// �ݥ��Ȥ��줿ʸ���Υ����ɤ��Ѵ�
mb_convert_variables(SOURCE_ENCODING,'',$get,$post);

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
	if ($_SERVER['QUERY_STRING'] != '')
	{
		$arg = $_SERVER['QUERY_STRING'];
	}
	else if (array_key_exists(0,$_SERVER['argv']))
	{
		$arg = $_SERVER['argv'][0];
	}
	else
	{
		//�ʤˤ���ꤵ��Ƥ��ʤ��ä�����$defaultpage��ɽ��
		$arg = $defaultpage;
	}		
	$arg = rawurldecode($arg);
	$arg = strip_bracket($arg);
	$arg = sanitize($arg);

	$get['cmd'] = $post['cmd'] = $vars['cmd'] = 'read';
	$get['page'] = $post['page'] = $vars['page'] = $arg;
}

/////////////////////////////////////////////////
// �������($WikiName,$BracketName�ʤ�)
// $WikiName = '[A-Z][a-z]+(?:[A-Z][a-z]+)+';
// $WikiName = '\b[A-Z][a-z]+(?:[A-Z][a-z]+)+\b';
// $WikiName = '(?<![[:alnum:]])(?:[[:upper:]][[:lower:]]+){2,}(?![[:alnum:]])';
// $WikiName = '(?<!\w)(?:[A-Z][a-z]+){2,}(?!\w)';
// BugTrack/304�����н�
$WikiName = '(?:[A-Z][a-z]+){2,}(?!\w)';
// $BracketName = ':?[^\s\]#&<>":]+:?';
$BracketName = '(?!\s):?[^\r\n\t\f\[\]<>#&":]+:?(?<!\s)';
// InterWiki
$InterWikiName = "(\[\[)?((?:(?!\s|:|\]\]).)+):(.+)(?(1)\]\])";
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
if ($usefacemark)
{
	$line_rules += $facemark_rules;
}
// ���λ��ȥѥ�����
//$entity_pattern = '[a-zA-Z0-9]{2,8};';
$entity_pattern  = '(?=[a-zA-Z0-9]{2,8};)';
$entity_pattern .= trim(join('',file(CACHE_DIR.'entities.dat')));
$line_rules['&amp;(#[0-9]+|#x[0-9a-f]+|'.$entity_pattern.');'] = '&$1;';
// �����ƥ�ǻ��Ѥ���ѥ�����
$line_rules["\r"] = "<br />\n"; /* �����˥�����ϲ��� */
$line_rules['#related'] = '<del>#related</del>';
?>
