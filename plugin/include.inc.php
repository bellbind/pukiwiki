<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: include.inc.php,v 1.6 2003/03/03 07:07:28 panda Exp $
//

/*
 include.inc.php
 �ڡ����򥤥󥯥롼�ɤ���
*/

function plugin_include_convert()
{
	global $script,$vars,$get,$post,$hr,$WikiName,$BracketName;
	global $include_list; //�����ѥڡ���̾������
	
	if (!isset($include_list))
	{
		$include_list = array($vars['page']=>TRUE);
	}
	
	if (func_num_args() == 0)
	{
		return;
	}
	
	list($page) = func_get_args();
	$page = strip_bracket($page);
	
	if (!is_page($page) or isset($include_list[$page]))
	{
		return '';
	}
	$include_list[$page] = TRUE;
	
	$_page = $vars['page'];
	$get['page'] = $post['page'] = $vars['page'] = $page;
	$body = convert_html(get_source($page));
	$get['page'] = $post['page'] = $vars['page'] = $_page;
	
	$s_page = htmlspecialchars($page);
	$r_page = rawurlencode($page);
	$link = "<a href=\"$script?cmd=edit&amp;page=$r_page\">$s_page</a>";
	if ($page == 'MenuBar')
	{
		$body = <<<EOD
<span align="center"><h5 class="side_label">$link</h5></span>
<small>$body</small>
EOD;
	}
	else
	{
		$body = "<h1>$link</h1>\n$body\n";
	}
	
	return $body;
}
?>
