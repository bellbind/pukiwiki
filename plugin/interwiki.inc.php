<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: interwiki.inc.php,v 1.2 2003/02/18 04:30:03 panda Exp $
//
// InterWikiName��Ƚ�̤ȥڡ�����ɽ��
function plugin_interwiki_action()
{
	global $script,$vars,$interwiki,$WikiName,$InterWikiName;
	global $_title_invalidiwn,$_msg_invalidiwn;
	
	$retvars = array();
	
	if (!preg_match("/^$InterWikiName$/",$vars['page'],$match)) {
		$retvars['msg'] = $_title_invalidiwn;
		$retvars['body'] = str_replace('$1',htmlspecialchars($name),str_replace('$2',"<a href=\"$script?InterWikiName\">InterWikiName</a>",$_msg_invalidiwn));
		return $retvars;
	}
	$name = $match[2];
	$param = $match[3];
	
	$url = $opt = '';
	$source = get_source($interwiki);
	foreach($source as $line) {
		//                <1 url -------------------------------------------------------------->  <2 name>     <3 opt >
		if (preg_match('/\[((?:https?|ftp|news)(?:\:\/\/[[:alnum:]\+\$\;\?\.%,!#~\*\/\:@&=_\-]+))\s([^\]]+)\]\s?([^\s]*)/',$line,$match) and $match[2] == $name) {
			$url = $match[1];
			$opt = $match[3];
			break;
		}
	}
	
	if ($url == '') {
		$retvars['msg'] = $_title_invalidiwn;
		$retvars['body'] = str_replace('$1',htmlspecialchars($name),str_replace('$2',"<a href=\"$script?InterWikiName\">InterWikiName</a>",$_msg_invalidiwn));
		return $retvars;
	}
	
	$b_mb = function_exists('mb_convert_encoding');
	
	// ʸ�����󥳡��ǥ���
	if ($opt == 'yw')
	{
		// YukiWiki��
		if (!preg_match("/$WikiName/",$param))
			$param = $b_mb ? '[['.mb_convert_encoding($param,'SJIS',SOURCE_ENCODING).']]' : FALSE;
	}
	else if ($opt == 'moin')
	{
		// moin��
		$param = str_replace('%','_',rawurlencode($param));
	}
	else if ($opt == '' or $opt == 'std')
	{
		// ����ʸ�����󥳡��ǥ��󥰤Τޤ�URL���󥳡���
		$param = rawurlencode($param);
	}
	else if ($opt == 'asis' or $opt == 'raw')
	{
		// URL���󥳡��ɤ��ʤ�
//				$match[3] = $match[3];
	}
	else if ($opt != '')
	{
		// �����ꥢ�����Ѵ�
		if ($opt == 'sjis')
			$opt = 'SJIS';
		else if ($opt == 'euc')
			$opt = 'EUC-JP';
		else if ($opt == 'utf8')
			$opt = 'UTF-8';

		// ����¾�����ꤵ�줿ʸ�������ɤإ��󥳡��ɤ���URL���󥳡���
		$param = $b_mb ? rawurlencode(mb_convert_encoding($param,$opt,'auto')) : FALSE;
	}

	if ($param === FALSE)
	{
		$retvars['msg'] = 'Not support mb_convert_encoding.';
		$retvars['body'] = 'This server\'s PHP does not have "mb_jstring" module. Cannot convert encoding.';
		return $retvars;
	}

	if (strpos($url,'$1') !== FALSE)
		$url = str_replace('$1',$param,$url);
	else
		$url .= $param;

	header("Location: $url");
	die();
}
?>