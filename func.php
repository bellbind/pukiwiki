<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: func.php,v 1.13 2003/02/11 04:51:58 panda Exp $
//

// ʸ����InterWikiName���ɤ���
function is_interwiki($str)
{
	global $InterWikiName;

	return preg_match("/^$InterWikiName$/",$str);
}
// ʸ���󤬥ڡ���̾���ɤ���
function is_pagename($str)
{
	global $BracketName,$WikiName;
	
	$is_pagename = (!is_interwiki($str) and preg_match("/^(?!\.{0,}\/)$BracketName(?<!\/)$/",$str));
	
	if (defined('ENCODING')) {
		if (ENCODING == 'UTF-8') {
			$is_pagename = ($is_pagename and preg_match('/^(?:[\x00-\x7F]|(?:[\xC0-\xDF][\x80-\xBF])|(?:[\xE0-\xEF][\x80-\xBF][\x80-\xBF]))+$/',$str)); // UTF-8
		}
		else if (ENCODING == 'EUC-JP') {
			$is_pagename = ($is_pagename and preg_match('/^(?:[\x00-\x7F]|(?:[\x8E\xA1-\xFE][\xA1-\xFE])|(?:\x8F[\xA1-\xFE][\xA1-\xFE]))+$/',$str)); // EUC-JP
		}
	}
	
	return $is_pagename;
}
// �ڡ�����¸�ߤ��뤫
function is_page($page,$reload=FALSE)
{
	global $InterWikiName;
	static $is_page;
	
	if (!isset($is_page))
		$is_page = array();
	
	if ($reload or !array_key_exists($page,$is_page))
		$is_page[$page] = file_exists(get_filename($page));
	
	return $is_page[$page];
}
// �ڡ������Խ���ǽ��
function is_editable($page)
{
	global $cantedit;
	static $is_editable;
	
	if (!isset($is_editable))
		$is_editable = array();
	
	if (!array_key_exists($page,$is_editable))
		$is_editable[$page] = (is_pagename($page) and !is_freeze($page) and !in_array($page,$cantedit));
	
	return $is_editable[$page];
}

// �ڡ�������뤵��Ƥ��뤫
function is_freeze($page)
{
	global $function_freeze;

	if (!$function_freeze or !is_page($page)) {
		return FALSE;
	}

	list($lines) = get_source($page);
	return (rtrim($lines) == '#freeze');
}

// �Խ��Բ�ǽ�ʥڡ������Խ����褦�Ȥ����Ȥ�
function check_editable()
{
	global $script,$get,$_title_cannotedit,$_msg_unfreeze;
	
	edit_auth();
	
	if (is_editable($get['page'])) {
		return;
	}
	
	$body = $title = str_replace('$1',htmlspecialchars(strip_bracket($get['page'])),$_title_cannotedit);
	$page = str_replace('$1',make_search($get['page']),$_title_cannotedit);

	if(is_freeze($get['page'])) {
		$body .= "(<a href=\"$script?cmd=unfreeze&amp;page=".rawurlencode($get['page'])."\">$_msg_unfreeze</a>)";
	}
	
	catbody($title,$page,$body);
	exit;
}

// �Խ�����ǧ��
function edit_auth()
{
	global $get,$edit_auth,$edit_auth_users,$_msg_auth,$_title_cannotedit;

	if ($edit_auth and
		(!isset($_SERVER['PHP_AUTH_USER']) or
		 !array_key_exists($_SERVER['PHP_AUTH_USER'],$edit_auth_users) or
		 $edit_auth_users[$_SERVER['PHP_AUTH_USER']] != $_SERVER['PHP_AUTH_PW']))
	{
		header('WWW-Authenticate: Basic realm="'.$_msg_auth.'"');
		header('HTTP/1.0 401 Unauthorized');
		// press cancel.
		$body = $title = str_replace('$1',htmlspecialchars(strip_bracket($get['page'])),$_title_cannotedit);
		$page = str_replace('$1',make_search($get['page']),$_title_cannotedit);
		
		catbody($title,$page,$body);
		exit;
	}
}

// ��ư�ƥ�ץ졼��
function auto_template($page)
{
	global $auto_template_func,$auto_template_rules;
	
	if (!$auto_template_func) {
		return '';
	}

	$body = '';
	foreach ($auto_template_rules as $rule => $template) {
		if (preg_match("/$rule/",$page,$matches)) {
			$template_page = preg_replace("/$rule/",$template,$page);
			$body = join('',get_source($template_page));
			for ($i = 0; $i < count($matches); $i++) {
				$body = str_replace("\$$i",$matches[$i],$body);
			}
			break;
		}
	}
	return $body;
}

// ����
function do_search($word,$type='AND',$non_format=FALSE)
{
	global $script,$vars,$whatsnew;
	global $_msg_andresult,$_msg_orresult,$_msg_notfoundresult;
	
	$database = array();
	$retval = array();

	$b_type = ($type == 'AND'); // AND:TRUE OR:FALSE
	$keys = preg_split('/\s+/',preg_quote($word,'/'),-1,PREG_SPLIT_NO_EMPTY);
	
	$_pages = get_existpages();
	$pages = array();
	
	foreach ($_pages as $page) {
		if ($page == $whatsnew or ($non_format and $page == $vars['page'])) {
			continue;
		}
		
		$source = get_source($page);
		array_unshift($source,$page); // �ڡ���̾�⸡���оݤ�
		
		$b_match = FALSE;
		foreach ($keys as $key) {
			$tmp = preg_grep("/$key/",$source);
			$b_match = (count($tmp) > 0);
			if ($b_match xor $b_type) {
				break;
			}
		}
		if ($b_match) {
			$pages[$page] = get_filetime($page);
		}
	}
	if ($non_format) {
		return $pages;
	}
	$r_word = rawurlencode($word);
	$s_word = htmlspecialchars($word);
	if (count($pages) == 0) {
		return str_replace('$1',$s_word,$_msg_notfoundresult);
	}
	ksort($pages);
	$retval = "<ul>\n";
	foreach ($pages as $page=>$time) {
		$r_page = rawurlencode($page);
		$s_page = htmlspecialchars($page);
		$passage = get_passage($time);
		$retval .= " <li><a href=\"$script?cmd=read&amp;page=$r_page&amp;word=$r_word\">$s_page</a>$passage</li>\n";
	}
	$retval .= "</ul>\n";
	
	$retval .= str_replace('$1',$s_word,str_replace('$2',count($pages),
		str_replace('$3',count($_pages),$b_type ? $_msg_andresult : $_msg_orresult)));
	
	return $retval;
}

// �ץ����ؤΰ����Υ����å�
function arg_check($str)
{
	global $vars;
	
	return array_key_exists('cmd',$vars) and (strpos($vars['cmd'],$str) === 0);
}

// �ڡ���̾�Υ��󥳡���
function encode($key)
{
	return strtoupper(join('',unpack('H*0',$key)));
}

// �ڡ���̾�Υǥ�����
function decode($key)
{
	return $key == '' ? '' : pack('H*',$key);
}

// [[ ]] �������
function strip_bracket($str)
{
	if (preg_match('/^\[\[(.*)\]\]$/',$str,$match)) {
		$str = $match[1];
	}
	return $str;
}

// �ڡ��������κ���
function page_list($pages, $cmd = 'read', $withfilename = FALSE)
{
	global $script,$list_index,$top;
	global $_msg_symbol,$_msg_other;
	
	// �����ȥ�������ꤹ�롣 ' ' < '[a-zA-Z]' < 'zz'�Ȥ�������
	$symbol = ' ';
	$other = 'zz';
	
	$retval = '';
	
	$list = array();
	foreach($pages as $page) {
		$r_page = rawurlencode($page);
		$s_page = htmlspecialchars($page,ENT_QUOTES);
		$e_page = encode($page);
		$passage = get_pg_passage($page);
		
		$str = "   <li><a href=\"$script?cmd=$cmd&amp;page=$r_page\">$s_page</a>$passage";
		
		if ($withfilename) {
			$str .= "\n    <ul><li>$e_page</li></ul>\n   ";
		}
		$str .= "</li>";
		
		$head = (preg_match('/^([A-Za-z])/',$page,$matches)) ? $matches[1] :
			(preg_match('/^([ -~0-9])/',$page,$matches) ? $symbol : $other);
		
		$list[$head][$page] = $str;
	}
	ksort($list);
	
	$cnt = 0;
	$arr_index = array();
	$retval .= "<ul>\n";
	foreach ($list as $head=>$pages) {
		if ($head === $symbol) {
			$head = $_msg_symbol;
		}
		else if ($head === $other) {
			$head = $_msg_other;
		}
		
		if ($list_index) {
			$cnt++;
			$arr_index[] = "<a id=\"top_$cnt\" href=\"#head_$cnt\"><strong>$head</strong></a>";
			$retval .= " <li><a id=\"head_$cnt\" href=\"#top_$cnt\"><strong>$head</strong></a>\n  <ul>\n";
		}
		ksort($pages);
		$retval .= join("\n",$pages);
		if ($list_index) {
			$retval .= "\n  </ul>\n </li>\n";
		}
	}
	$retval .= "</ul>\n";
	if ($list_index and $cnt > 0) {
		$top = array();
		while (count($arr_index) > 0) {
			$top[] = join(" | \n",array_splice($arr_index,0,16))."\n";
		}
		$retval = "<div id=\"top\" style=\"text-align:center\">\n".
			join("<br />",$top)."</div>\n".$retval;
	}
	return $retval;
}

// �ƥ����������롼���ɽ������
function catrule()
{
	global $rule_page;
	
	if (!is_page($rule_page)) {
		return "<p>sorry, $rule_page unavailable.</p>";
	}
	return convert_html(get_source($rule_page));
}

// ���顼��å�������ɽ������
function die_message($msg)
{
	$title = $page = 'Runtime error';
	
	$body = <<<EOD
<h3>Runtime error</h3>
<strong>Error message : $msg</strong>
EOD;
	
	if(defined('SKIN_FILE') && file_exists(SKIN_FILE) && is_readable(SKIN_FILE)) {
	  catbody($title,$page,$body);
	}
	else {
		header('Content-Type: text/html; charset=euc-jp');
		print <<<__TEXT__
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title>$title</title>
  <meta http-equiv="content-type" content="text/html; charset=euc-jp">
 </head>
 <body>
 $body
 </body>
</html>
__TEXT__;
	}
	die();
}

// ���߻����ޥ������äǼ���
function getmicrotime()
{
	list($usec, $sec) = explode(' ',microtime());
	return ((float)$sec + (float)$usec);
}

// ����������
function get_date($format,$timestamp = NULL)
{
	$time = ($timestamp === NULL) ? UTIME : $timestamp;
	$time += ZONETIME;
	
	$format = preg_replace('/(?<!\\\)T/',preg_replace('/(.)/','\\\$1',ZONE),$format);
	
	return date($format,$time);
}

// ����ʸ�������
function format_date($val, $paren = FALSE) {
	global $date_format,$time_format,$weeklabels;
	
	$val += ZONETIME;
	
	$ins_date = date($date_format,$val);
	$ins_time = date($time_format,$val);
	$ins_week = '('.$weeklabels[date('w',$val)].')';
	
	$ins = "$ins_date $ins_week $ins_time";
	return $paren ? "($ins)" : $ins;
}

// �в����ʸ�������
function get_passage($time)
{
	$time = UTIME - $time;
	
	if (ceil($time / 60) < 60) {
		$str = '('.ceil($time / 60).'m)';
	}
	else if (ceil($time / 60 / 60) < 24) {
		$str = '('.ceil($time / 60 / 60).'h)';
	}
	else {
		$str = '('.ceil($time / 60 / 60 / 24).'d)';
	}
	
	return $str;
}

//<input type="(submit|button|image)"...>�򱣤�
function drop_submit($str)
{
	return preg_replace(
		'/<input([^>]+)type="(submit|button|image)"/i',
		'<input$1type="hidden"',
		$str
	);
}

// ���顼�ϥ�ɥ�ؿ�
function myErrorHandler($no, $str, $file, $line, $variable)
{
	global $vars;
	
	$messages = array(
		1=>'ERROR',
		2=>'WARNING',
		4=>'PARSE',
		8=>'NOTICE',
		16=>'CORE_ERROR',
		32=>'CORE_WARNING',
		64=>'COMPILE_ERROR',
		128=>'COMPILE_WARNING',
		256=>'USER_ERROR',
		512=>'USER_WARNING',
		1024=>'USER_NOTICE'
	);
	$fatal_errors = array(E_ERROR,E_CORE_ERROR,E_COMPILE_ERROR,E_USER_ERROR);
	
	$error = '-'.get_date("Y-m-d H:i:s");
	
	if ($vars['page'] != '') {
		$error .= " [[{$vars['page']}]]";
	}

	foreach ($messages as $_no=>$mes) {
		if ($no & $_no) {
			$error .=  " ''$mes''";
		}
	}
	
	$error .= "($no)";
	$error .= "~\n$str on $file line $line\n";
	$fp = fopen('./wiki/'.encode(':ErrorLog').'.txt','a') or exit -1;
	fwrite($fp,$error);
	fclose($fp);
	
	if (in_array($no,$fatal_errors)) {
		exit -1;
	}
}

//�ѿ�����פ��֤�
function dumpvar($var)
{
	ob_start();
	print_r($var);
	$body = ob_get_contents();
	ob_end_clean();
	return $body;
}

//AutoLink�Υѥ��������������
function get_autolink_pattern()
{
	global $WikiName,$autolink,$nowikiname;
	
	if (!$autolink) {
		return $nowikiname ? '(?!)' : $WikiName;
	}
	
	$pages = get_existpages();
	$arr = array();
	foreach ($pages as $page) {
		if (preg_match("/^$WikiName$/",$page) ? $nowikiname : strlen($page) >= $autolink) {
			// /x��ȤäƤ���Τǡ�����⥨�������פ��ʤ���Фʤ�ʤ�
			$pattern = '(?:'.str_replace(' ','\ ',preg_quote($page,'/')).')';
			$arr[$pattern] = strlen($pattern);
		}
	}
	arsort($arr,SORT_NUMERIC);
	$arr = array_keys($arr);
	if (!$nowikiname) {
		array_push($arr,"(?:$WikiName)");
	}
	return join('|',$arr);
}

//is_a
//(PHP 4 >= 4.2.0)
//
//is_a --  Returns TRUE if the object is of this class or has this class as one of its parents 

if (!function_exists('is_a')) {
	function is_a($class, $match)
	{
		if (empty($class)) {
			return false;
		}
		$class = is_object($class) ? get_class($class) : $class;
		if (strtolower($class) == strtolower($match)) {
			return true;
		}
		return is_a(get_parent_class($class), $match);
	}
}

//array_fill
//(PHP 4 >= 4.2.0)
//
//array_fill -- Fill an array with values

if (!function_exists('array_fill')) {
	function array_fill($start_index,$num,$value) {
		$ret = array();
		
		while ($num-- > 0)
			$ret[$start_index++] = $value;
		
		return $ret;
	}
}
?>
