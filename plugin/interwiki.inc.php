<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: interwiki.inc.php,v 1.5 2003/05/16 05:53:38 arino Exp $
//
// InterWikiName��Ƚ�̤ȥڡ�����ɽ��

function plugin_interwiki_action()
{
	global $script,$vars,$interwiki,$WikiName,$InterWikiName;
	global $_title_invalidiwn,$_msg_invalidiwn;
	
	$retvars = array();
	
	if (!preg_match("/^$InterWikiName$/",$vars['page'],$match))
	{
		$retvars['msg'] = $_title_invalidiwn;
		$retvars['body'] = str_replace(
			array('$1','$2'),
			array(htmlspecialchars($name),make_pagelink('InterWikiName')),
			$_msg_invalidiwn
		);
		return $retvars;
	}
	$name = $match[2];
	$param = $match[3];
	
	$url = $opt = '';
	foreach (get_source($interwiki) as $line)
	{
		if (preg_match('/\[((?:(?:https?|ftp|news):\/\/|\.\.?\/)[!~*\'();\/?:\@&=+\$,%#\w.-]*)\s([^\]]+)\]\s?([^\s]*)/',$line,$match)
			and $match[2] == $name)
		{
			$url = $match[1];
			$opt = $match[3];
			
			if (!is_url($url))
			{
//				$url = substr($script,0,strrpos($script,'/')).substr($url,strspn($url,'.'));
				$q_name = preg_quote(basename($_SERVER['SCRIPT_NAME']));
				$url = preg_replace("/$q_name$/",'',$script).$match[1];
			}
			break;
		}
	}
	
	if ($url == '')
	{
		$retvars['msg'] = $_title_invalidiwn;
		$retvars['body'] = str_replace(
			array('$1','$2'),
			array(htmlspecialchars($name),make_pagelink('InterWikiName')),
			$_msg_invalidiwn
		);
		return $retvars;
	}
	
	// ʸ�����󥳡��ǥ���
	if ($opt == 'yw')
	{
		// YukiWiki��
		if (!preg_match("/$WikiName/",$param))
		{
			$param = '[['.mb_convert_encoding($param,'SJIS',SOURCE_ENCODING).']]';
		}
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
		// $match[3] = $match[3];
	}
	else if ($opt != '')
	{
		// �����ꥢ�����Ѵ�
		if ($opt == 'sjis')
		{
			$opt = 'SJIS';
		}
		else if ($opt == 'euc')
		{
			$opt = 'EUC-JP';
		}
		else if ($opt == 'utf8')
		{
			$opt = 'UTF-8';
		}

		// ���ꤵ�줿ʸ�������ɤإ��󥳡��ɤ���URL���󥳡���
		$param = rawurlencode(mb_convert_encoding($param,$opt,'auto'));
	}
	
	if (strpos($url,'$1') !== FALSE)
	{
		$url = str_replace('$1',$param,$url);
	}
	else
	{
		$url .= $param;
	}
	
	header("Location: $url");
	die();
}
?>
