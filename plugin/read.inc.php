<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: read.inc.php,v 1.5 2004/07/01 14:38:19 henoheno Exp $
//
// �ڡ�����ɽ����InterWikiName�β��
function plugin_read_action()
{
	global $get,$post,$vars;
	global $_title_edit,$_title_invalidwn,$_msg_invalidiwn;

	$page = isset($vars['page']) ? $vars['page'] : '';

	// WikiName��BracketName�������ڡ�����ɽ��
	if (is_page($page)) {
		check_readable($page,true,true);
		header_lastmod($page);
		return array('msg'=>'','body'=>'');
	}

	// InterWikiName�����
	if (is_interwiki($page))
		return do_plugin_action('interwiki');

	// �ڡ���̾�Ȥ���ͭ�������ڡ�����¸�ߤ��ʤ��Τǡ��Խ��ե������ɽ��
	if (is_pagename($page)) {
		$get['cmd'] = $post['cmd'] = $vars['cmd'] = 'edit';
		return do_plugin_action('edit');
	}

	// ̵���ʥڡ���̾
	return array(
		'msg'=>$_title_invalidwn,
		'body'=>str_replace('$1',htmlspecialchars($page),
			str_replace('$2','WikiName',$_msg_invalidiwn))
	);
}
?>
