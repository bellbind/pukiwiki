<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: init.php,v 1.84 2004/07/03 12:57:56 henoheno Exp $
//

/////////////////////////////////////////////////
// ������� (���顼���ϥ�٥�)
error_reporting(E_ERROR | E_PARSE);	// (E_WARNING | E_NOTICE)��������Ƥ��ޤ�
//error_reporting(E_ALL);

/////////////////////////////////////////////////
// ������� (ʸ�����󥳡��ɡ�����)
define('LANG','ja');	// Select 'ja' or 'en'
define('SOURCE_ENCODING','EUC-JP');

// mbstring extension ��Ϣ
mb_language('Japanese');
mb_internal_encoding(SOURCE_ENCODING);
ini_set('mbstring.http_input', 'pass');
mb_http_output('pass');
mb_detect_order('auto');

/////////////////////////////////////////////////
// �������(����ե�����ξ��)
define('LANG_FILE', LANG.'.lng');
define('INI_FILE','./pukiwiki.ini.php');

/////////////////////////////////////////////////
// ������� (�С������/���)
define('S_VERSION','1.4.3');
define('S_COPYRIGHT','
<strong>"PukiWiki" '.S_VERSION.'</strong> Copyright &copy; 2001-2004
<a href="http://pukiwiki.org">PukiWiki Developers Team</a>.
License is <a href="http://www.gnu.org/">GNU/GPL</a>.<br />
Based on "PukiWiki" 1.3 by <a href="http://factage.com/sng/">sng</a>
');

/////////////////////////////////////////////////
// ������� (�������ѿ�)
foreach (array('SCRIPT_NAME', 'SERVER_ADMIN', 'SERVER_NAME',
	'SERVER_PORT', 'SERVER_SOFTWARE') as $key) {
	define($key, isset($_SERVER[$key]) ? $_SERVER[$key] : '');
	unset(${$key}, $_SERVER[$key], $HTTP_SERVER_VARS[$key]);
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

/////////////////////////////////////////////////
// �������(����)
define('LOCALZONE',date('Z'));
define('UTIME',time() - LOCALZONE);
define('MUTIME',getmicrotime());

/////////////////////////////////////////////////
// �ե������ɤ߹���
$die = '';
foreach(array('LANG_FILE', 'INI_FILE') as $file){
	if (!file_exists(constant($file)) || !is_readable(constant($file))) {
		$die = "${die}File is not found. ($file)\n";
	} else {
		require(constant($file));
	}
}
if ($die) { die_message(nl2br("\n\n" . $die)); }

/////////////////////////////////////////////////
// INI_FILE: $script: �������
if (!isset($script) or $script == '') {
	$script = get_script_uri();
	if ($script === FALSE or (php_sapi_name() == 'cgi' and !is_url($script,TRUE))) {
		die_message('get_script_uri() failed: Please set $script at INI_FILE manually.');
	}
}

/////////////////////////////////////////////////
// INI_FILE: $agents, $user_agent:  UserAgent�μ���

$ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
foreach ($agents as $agent) {
	if (preg_match($agent['pattern'], $ua, $matches)) {
		$user_agent = $agent;	// array to array
		$user_agent['matches'] = $matches;
		break;
	}
}
define('UA_NAME',    $user_agent['name']);
define('UA_MATCHES', $user_agent['matches']);
$ua = 'HTTP_USER_AGENT';
unset($agents, ${$ua}, $_SERVER[$ua], $HTTP_SERVER_VARS[$ua], $ua);

// UserAgent�̤�����ե������ɤ߹���
define('UA_INI_FILE' , UA_NAME . '.ini.php');
if (!file_exists(UA_INI_FILE) || !is_readable(UA_INI_FILE)) {
	die_message('UA_INI_FILE for "' . UA_NAME . '" not found.');
} else {
	require(UA_INI_FILE);
}
unset($user_agent);	// Unset after reading UA_INI_FILE

/////////////////////////////////////////////////
// �ǥ��쥯�ȥ�Υ����å�

$die = '';
foreach(array('DATA_DIR', 'DIFF_DIR', 'BACKUP_DIR', 'CACHE_DIR') as $dir){
	if(!is_writable(constant($dir))) {
		$die = "${die}Directory is not found or not writable ($dir)\n";
	}
}

// ����ե�������ѿ������å�
$temp = '';
foreach(array('rss_max', 'page_title', 'note_hr', 'related_link', 'show_passage',
	'rule_related_str', 'load_template_func') as $var){
	if (!isset(${$var})) { $temp .= "\$$var\n"; }
}
if ($temp) {
	if ($die) { $die .= "\n"; }	// A breath
	$die = "${die}Variable(s) not found: (Maybe the old *.ini.php?)\n" . $temp;
}

$temp = '';
foreach(array('LANG', 'PLUGIN_DIR') as $def){
	if (!defined($def)) $temp .= "$def\n";
}
if ($temp) {
	if ($die) { $die .= "\n"; }	// A breath
	$die = "${die}Define(s) not found: (Maybe the old *.ini.php?)\n" . $temp;
}

if($die){ die_message(nl2br("\n\n" . $die)); }
unset($die, $temp);

/////////////////////////////////////////////////
// ɬ�ܤΥڡ�����¸�ߤ��ʤ���С����Υե�������������

foreach(array($defaultpage, $whatsnew, $interwiki) as $page){
	if (!is_page($page)) { touch(get_filename($page)); }
}

/////////////////////////////////////////////////
// �������餯���ѿ�������å�

// Prohibit $_GET['msg'] attack
if (isset($_GET['msg'])) die_message('Sorry, already reserved: msg=');

$get    = sanitize($_GET);
$post   = sanitize($_POST);
$cookie = sanitize($_COOKIE);

// Expire risk
unset($_GET, $_POST, $HTTP_GET_VARS, $HTTP_POST_VARS, $_REQUEST, $_COOKIE);	//, 'SERVER', 'ENV', 'SESSION', ...

/////////////////////////////////////////////////
// ʸ�������ɤ��Ѵ�

// <form> ���������줿ʸ�� (�֥饦�������󥳡��ɤ����ǡ���) �Υ����ɤ��Ѵ�
// post �Ͼ�� <form> �ʤΤǡ�ɬ���Ѵ�
if (array_key_exists('encode_hint',$post))
{
	// html.php ����ǡ�<form> �� encode_hint ��Ź���Ǥ���Τǡ�ɬ�� encode_hint ������Ϥ���
	// encode_hint �Τߤ��Ѥ��ƥ����ɸ��Ф��롣
	// ���Τ򸫤ƥ����ɸ��Ф���ȡ������¸ʸ���䡢̯�ʥХ��ʥꥳ���ɤ������������ˡ�
	// �����ɸ��Ф˼��Ԥ��붲�줬���뤿�ᡣ
	$encode = mb_detect_encoding($post['encode_hint']);
	mb_convert_variables(SOURCE_ENCODING,$encode,$post);
}
else if (array_key_exists('charset',$post))
{
	// TrackBack Ping�˴ޤޤ�Ƥ��뤳�Ȥ�����
	// ���ꤵ�줿���ϡ��������Ƥ��Ѵ����ߤ�
	if (mb_convert_variables(SOURCE_ENCODING,$post['charset'],$post) !== $post['charset'])
	{
		// ���ޤ������ʤ��ä����ϥ����ɸ��Ф�������Ѵ����ʤ���
		mb_convert_variables(SOURCE_ENCODING,'auto',$post);
	}
}
else if (count($post) > 0)
{
	// encode_hint ��̵���Ȥ������Ȥϡ�̵���Ϥ���
	// �ǥХå��Ѥˡ���ꤢ�������ٹ��å�������Ф��Ƥ����ޤ���
// 	echo "<p>Warning: 'encode_hint' field is not found in the posted data.</p>\n";
	// �����ޤȤ�ơ������ɸ��С��Ѵ�
	mb_convert_variables(SOURCE_ENCODING,'auto',$post);
}

// get �� <form> ����ξ��ȡ�<a href="http;//script/?query> �ξ�礬����
if (array_key_exists('encode_hint',$get))
{
	// <form> �ξ��ϡ��֥饦�������󥳡��ɤ��Ƥ���Τǡ������ɸ��С��Ѵ���ɬ�ס�
	// encode_hint ���ޤޤ�Ƥ���Ϥ��ʤΤǡ�����򸫤ơ������ɸ��Ф����塢�Ѵ����롣
	// ��ͳ�ϡ�post ��Ʊ��
	$encode = mb_detect_encoding($get['encode_hint']);
	mb_convert_variables(SOURCE_ENCODING,$encode,$get);
}	
// <a href...> �ξ��ϡ������С��� rawurlencode ���Ƥ���Τǡ��������Ѵ�������

// QUERY_STRING�����
// cmd��plugin����ꤵ��Ƥ��ʤ����ϡ�QUERY_STRING��ڡ���̾��InterWikiName�Ǥ���Ȥߤʤ���
// �ޤ���URI �� urlencode �����˼��Ǥ������Ϥ��������н褹���
$arg = '';
if (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING']) {
	$arg = $_SERVER['QUERY_STRING'];
} else if (isset($_SERVER['argv']) && count($_SERVER['argv'])) {
	$arg = $_SERVER['argv'][0];
}

// unset QUERY_STRINGs
foreach (array('QUERY_STRING', 'argv', 'argc') as $key) {
	unset(${$key}, $_SERVER[$key], $HTTP_SERVER_VARS[$key]);
}
// $_SERVER['REQUEST_URI'] is used by func.php NOW
unset($REQUEST_URI, $HTTP_SERVER_VARS['REQUEST_URI']);

// ���˥����� (\0 ����)
$arg = sanitize($arg);

// URI ���Ǥξ�硢�������Ѵ�����get[] �˾��
// mb_convert_variables�ΥХ�(?)�к� ������Ϥ��ʤ��������
$arg = array($arg);
mb_convert_variables(SOURCE_ENCODING,'auto',$arg);
$arg = $arg[0];

foreach (explode('&',$arg) as $tmp_string)
{
	if (preg_match('/^([^=]+)=(.+)/',$tmp_string,$matches)
		and mb_detect_encoding($matches[2]) != 'ASCII')
	{
		$get[$matches[1]] = $matches[2];
	}
}

/////////////////////////////////////////////////
// GET + POST = $vars

$vars = array_merge($get, $post);

// ���ϥ����å�: cmd, plugin ��ʸ����ϱѿ����ʳ����ꤨ�ʤ�
foreach(array('cmd', 'plugin') as $var){
	if (array_key_exists($var, $vars) &&
	    ! preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $vars[$var])) {
		unset($get[$var], $post[$var], $vars[$var]);
	}
}

// ����: page, strip_bracket()
if (array_key_exists('page', $vars)) {
	$get['page'] = $post['page'] = $vars['page']  = strip_bracket($vars['page']);
} else {
	$get['page'] = $post['page'] = $vars['page'] = '';
}

// ����: msg, ���Ԥ������
if (isset($vars['msg'])) {
	$get['msg'] = $post['msg'] = $vars['msg'] = str_replace("\r",'',$vars['msg']);
}

// �����ߴ��� (?md5=...)
if (array_key_exists('md5', $vars) and $vars['md5'] != '') {
	$get['cmd'] = $post['cmd'] = $vars['cmd'] = 'md5';
}

// TrackBack Ping
if (array_key_exists('tb_id', $vars) and $vars['tb_id'] != '') {
	$get['cmd'] = $post['cmd'] = $vars['cmd'] = 'tb';
}

// cmd��plugin����ꤵ��Ƥ��ʤ����ϡ�QUERY_STRING��ڡ���̾��InterWikiName�Ǥ���Ȥߤʤ�
if (!array_key_exists('cmd',$vars)  and !array_key_exists('plugin',$vars))
{
	if ($arg == '')
	{
		//�ʤˤ���ꤵ��Ƥ��ʤ��ä�����$defaultpage��ɽ��
		$arg = $defaultpage;
	}		
	$arg = rawurldecode($arg);
	$arg = strip_bracket($arg);
	$arg = sanitize($arg);

	$get['cmd']  = $post['cmd']  = $vars['cmd']  = 'read';
	$get['page'] = $post['page'] = $vars['page'] = $arg;
}

// ���ϥ����å�: 'cmd=' prohibits nasty 'plugin='
if (isset($vars['cmd']) && isset($vars['plugin']))
	unset($get['plugin'], $post['plugin'], $vars['plugin']);


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
// �������(�桼������롼���ɤ߹���)
require('rules.ini.php');

/////////////////////////////////////////////////
// �������(����¾�Υ����Х��ѿ�)

// ���߻���
$now = format_date(UTIME);

// skin���DTD������ڤ��ؤ���Τ˻��ѡ�paint.inc.php�к�
$html_transitional = FALSE;
// FALSE:XHTML 1.1
// TRUE :XHTML 1.0 Transitional

// �ե������ޡ�����$line_rules�˲ä���
if ($usefacemark) { $line_rules += $facemark_rules; }
unset($facemark_rules);

// ���λ��ȥѥ����󤪤�ӥ����ƥ�ǻ��Ѥ���ѥ������$line_rules�˲ä���
//$entity_pattern = '[a-zA-Z0-9]{2,8}';
$entity_pattern = trim(join('',file(CACHE_DIR.'entities.dat')));

$line_rules = array_merge(array(
	'&amp;(#[0-9]+|#x[0-9a-f]+|' . $entity_pattern . ');' => '&$1;',
	"\r"          => "<br />\n",	/* �����˥�����ϲ��� */
	'#related$'   => '<del>#related</del>',
	'^#contents$' => '<del>#contents</del>'
), $line_rules);

?>
