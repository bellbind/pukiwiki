<?php
// pukiwiki.php - Yet another WikiWikiWeb clone.
//
// PukiWiki 1.4.* 
//  Copyright (C) 2002 by PukiWiki Developers Team
//  http://pukiwiki.org/
//
// PukiWiki 1.3.* 
//  Copyright (C) 2002 by PukiWiki Developers Team
//  http://pukiwiki.org/
//
// PukiWiki 1.3 (Base)
//  Copyright (C) 2001,2002 by sng.
//  <sng@factage.com>
//  http://factage.com/sng/pukiwiki/
//
// Special thanks
//  YukiWiki by Hiroshi Yuki
//  <hyuki@hyuki.com>
//  http://www.hyuki.com/yukiwiki/
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// $Id: pukiwiki.php,v 1.19 2003/02/15 13:25:15 panda Exp $
/////////////////////////////////////////////////


/////////////////////////////////////////////////
// �ץ����ե������ɤ߹���
require('func.php');
require('file.php');
require('plugin.php');
require('html.php');
require('backup.php');

require('convert_html.php');
require('make_link.php');
require('diff.php');

/////////////////////////////////////////////////
// �ץ����ե������ɤ߹���
require('init.php');

if (defined('LINK_DB')) {
	require(LINK_DB.'.php');
}

/////////////////////////////////////////////////
// �ᥤ�����

$base = $defaultpage;
$retvars = array();

// Plug-in action
if (!empty($vars['plugin'])) {
	if (!exist_plugin_action($vars['plugin'])) {
		$msg = "plugin={$vars['plugin']} is not implemented.";
		$retvars = array('msg'=>$msg,'body'=>$msg);
	}
	else {
		$retvars = do_plugin_action($vars['plugin']);
		$base = array_key_exists('refer',$vars) ? $vars['refer'] : '';
	}
}
// Command action
else if (!empty($vars['cmd'])) {
	if (!exist_plugin_action($vars['cmd'])) {
		$msg = "cmd={$vars['cmd']} is not implemented.";
		$retvars = array('msg'=>$msg,'body'=>$msg);
	}
	else {
		$retvars = do_plugin_action($vars['cmd']);
		$base = $vars['page'];
	}
}

$title = htmlspecialchars(strip_bracket($base));
$page = make_search($base);

if (array_key_exists('msg',$retvars) and $retvars['msg'] != '') {
	$title = str_replace('$1',$title,$retvars['msg']);
	$page = str_replace('$1',$page,$retvars['msg']);
}

if (array_key_exists('body',$retvars) and $retvars['body'] != '') {
	$body = $retvars['body'];
}
else {
	if ($base == '' or !is_page($base)) {
		$base = $defaultpage;
		$title = htmlspecialchars(strip_bracket($base));
		$page = make_search($base);
	}
	
	$vars['cmd'] = 'read';
	$vars['page'] = $base;
	$body = convert_html(get_source($base));
}

// ** ���Ͻ��� **
catbody($title,$page,$body);

// ** ��λ **
?>