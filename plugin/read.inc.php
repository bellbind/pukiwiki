<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: read.inc.php,v 1.6 2004/08/04 13:35:40 henoheno Exp $
//
// �ڡ�����ɽ����InterWikiName�β��

function plugin_read_action()
{
	global $get, $post, $vars;
	global $_title_invalidwn, $_msg_invalidiwn;

	$page = isset($vars['page']) ? $vars['page'] : '';

	if (is_page($page)) {
		// �ڡ�����ɽ��
		check_readable($page, true, true);
		header_lastmod($page);
		return array('msg'=>'', 'body'=>'');

	} else if (is_interwiki($page)) {
		return do_plugin_action('interwiki'); // InterWikiName�����

	} else if (is_pagename($page)) {
		$get['cmd'] = $post['cmd'] = $vars['cmd'] = 'edit';
		return do_plugin_action('edit'); // ¸�ߤ��ʤ��Τǡ��Խ��ե������ɽ��

	} else {
		// ̵���ʥڡ���̾
		return array(
			'msg'=>$_title_invalidwn,
			'body'=>str_replace('$1', htmlspecialchars($page),
				str_replace('$2', 'WikiName', $_msg_invalidiwn))
		);
	}
}
?>
