<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: deleted.inc.php,v 1.2 2003/04/01 08:05:26 panda Exp $
//
//������줿�ڡ���(BACKUP_DIR�ˤ��äơ�DATA_DIR�ˤʤ��ե�����)�ΰ�����ɽ������

function plugin_deleted_init()
{
	if (LANG == 'ja')
	{
		$messages = array(
			'_deleted_plugin_title' => '����ڡ����ΰ���',
			'_deleted_plugin_title_withfilename' => '����ڡ����ե�����ΰ���',
		);
	}
	else
	{
		$messages = array(
			'_deleted_plugin_title' => 'deleted pages',
			'_deleted_plugin_title_withfilename' => 'deleted pages (with filename)',
		);
	}
	set_plugin_messages($messages);
}

function plugin_deleted_action()
{
	global $get;
	global $_deleted_plugin_title,$_deleted_plugin_title_withfilename;

	$retval = array();

	$retval['msg'] = $_deleted_plugin_title;
	if ($withfilename = array_key_exists('file',$get))
	{
		$retval['msg'] = $_deleted_plugin_title_withfilename;
	}
	$backup_pages = get_existpages(BACKUP_DIR,BACKUP_EXT);
	$exist_pages = get_existpages();
	$deleted_pages = array_diff($backup_pages,$exist_pages);
	$retval['body'] = page_list($deleted_pages,'backup',$withfilename);
	
	return $retval;
}
?>
