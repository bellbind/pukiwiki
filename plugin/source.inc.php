<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: source.inc.php,v 1.8 2003/03/13 06:00:23 panda Exp $
//
// �ڡ�����������ɽ��

function plugin_source_init()
{
	$messages = array(
		'_source_messages'=>array(
			'msg_title' => '$1�Υ�����',
			'msg_notfound' => '$1�����Ĥ���ޤ���',
			'err_notfound' => '�ڡ����Υ�������ɽ���Ǥ��ޤ���'
		)
	);
	set_plugin_messages($messages);
}

function plugin_source_action()
{
	global $vars;
	global $_source_messages;
	
	$vars['refer'] = $vars['page'];
	
	if (!is_page($vars['page']))
	{
		return array(
			'msg'=>$_source_messages['msg_notfound'],
			'body'=>$_source_messages['err_notfound']
		);
	}
	return array(
		'msg'=>$_source_messages['msg_title'],
		'body' =>
			'<pre id="source">'.
			htmlspecialchars(join('',get_source($vars['page']))).
			'</pre>'
	);
}
?>
