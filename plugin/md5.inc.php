<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: md5.inc.php,v 1.1 2003/01/27 05:38:46 panda Exp $
//
//  MD5�ѥ���ɤؤ��Ѵ�
function plugin_md5_action()
{
	global $vars;
	
	return array(
		'msg'=>'Make password of MD5',
		'body'=> $vars['md5'].' : '.md5($vars['md5'])
	);
}
?>
