<?php
// $Id: ls2.inc.php,v 1.1 2002/12/05 05:02:27 panda Exp $
/*
Last-Update:2002-10-29 rev.8

*�ץ饰���� ls2
�۲��Υڡ����θ��Ф�(*,**,***)�ΰ�����ɽ������

*Usage
 #ls2(�ѥ�����[,�ѥ�᡼��])

*�ѥ�᡼��
-�ѥ�����(�ǽ�˻���)~
��ά����Ȥ��⥫��ޤ�ɬ��
-title~
 ���Ф��ΰ�����ɽ������
-include~
 ���󥯥롼�ɤ��Ƥ���ڡ����θ��Ф���Ƶ�Ū����󤹤�
-link~
 action�ץ饰�����ƤӽФ���󥯤�ɽ��
-reverse~
 �ڡ������¤ӽ��ȿž�����߽�ˤ���

*/

//���Ф����󥫡��ν�
//define('LS2_CONTENT_HEAD','#content:'); // html.php 1.35����
define('LS2_CONTENT_HEAD','#content_1_'); // html.php 1.36�ʹ�

//���Ф����󥫡��γ����ֹ�
//define('LS2_ANCHOR_ORIGIN',''); // html.php 1.35����
define('LS2_ANCHOR_ORIGIN',0); // html.php 1.36�ʹ�

function plugin_ls2_init() {
	global $_ls2_anchor;
	if (!isset($_ls2_anchor)) { $_ls2_anchor = 0; }
	$messages = array('_ls2_messages'=>array(
		'err_nopages' => '<p>\'$1\' �ˤϡ������ؤΥڡ���������ޤ���</p>',
		'msg_title' => '\'$1\'�ǻϤޤ�ڡ����ΰ���',
		'msg_go' => '<span class="small">...</span>',
	));
  set_plugin_messages($messages);
}
function plugin_ls2_action() {
	global $vars;
	global $_ls2_messages;

	$params = array();
	foreach (array('title','include','reverse') as $key)
		$params[$key] = array_key_exists($key,$vars);
	$prefix = array_key_exists('prefix',$vars) ? $vars['prefix'] : '';
	$body = ls2_show_lists($prefix,$params);

	return array(
		'body'=>$body,
		'msg'=>str_replace('$1',htmlspecialchars($prefix),$_ls2_messages['msg_title'])
	);
}

function plugin_ls2_convert() {
	global $script,$vars;
	global $_ls2_messages;

	$prefix = '';
	if (func_num_args()) {
		$args = func_get_args();
		$prefix = array_shift($args);
	} else {
		$args = array();
	}
	if ($prefix == '')
		$prefix = strip_bracket($vars['page']).'/';

	$params = array('link'=>FALSE,'title'=>FALSE,'include'=>FALSE,'reverse'=>FALSE,'_args'=>array(),'_done'=>FALSE);
	array_walk($args, 'ls2_check_arg', &$params);
	$title = (count($params['_args']) > 0) ?
		join(',', $params['_args']) :
		str_replace('$1',htmlspecialchars($prefix),$_ls2_messages['msg_title']);

	if ($params['link']) {
		$tmp = array();
		$tmp[] = 'plugin=ls2&amp;prefix='.$prefix;
		if (isset($params['title'])) { $tmp[] = 'title=1'; }
		if (isset($params['include'])) { $tmp[] = 'include=1'; }
		return '<p><a href="'.$script.'?'.join('&amp;',$tmp).'">'.$title.'</a></p>'."\n";
	}
	return ls2_show_lists($prefix,$params);
}
function ls2_show_lists($prefix,&$params) {
	global $_ls2_messages;

	$pages = ls2_get_child_pages($prefix);
	if ($params['reverse']) $pages = array_reverse($pages);

	foreach ($pages as $page) { $params[$page] = 0; }

	if (count($pages) == 0) { return str_replace('$1',htmlspecialchars($prefix),$_ls2_messages['err_nopages']); }

	$ret = '<ul>';
	foreach ($pages as $page) { $ret .= ls2_show_headings($page,$params); }
	$ret .= '</ul>'."\n";
	return $ret;
}

function ls2_show_headings($page,&$params,$include = FALSE) {
	global $script, $user_rules;
	global $_ls2_anchor, $_ls2_messages;
	
	$ret = '';
	$rules = '/\(\(((?:(?!\)\)).)*)\)\)/';
	$is_done = (isset($params[$page]) and $params[$page] > 0); //�ڡ�����ɽ���ѤߤΤȤ�True
	if (!$is_done) { $params[$page] = ++$_ls2_anchor; }

	$name = strip_bracket($page);
	$title = $name.' '.get_pg_passage($page,FALSE);
	$href = $script.'?cmd=read&amp;page='.rawurlencode($page);
	$ret .= '<li>';
	if ($include) { $ret .= 'include '; }
	$ret .= '<a id="list_'.$params[$page].'" href="'.$href.'" title="'.$title.'">'.$name.'</a>';
	if ($params['title'] and $is_done) {
		$ret .= '<a href="#list_'.$params[$page].'">+</a></li>'."\n";
		return $ret;
	}
	$anchor = LS2_ANCHOR_ORIGIN;
	$_ret = '';
	foreach (get_source($page) as $line) {
		if ($params['title'] and preg_match('/^(\*+)(.*)$/',$line,$matches)) {
			list($special) = inline2(array(preg_replace($rules,'',htmlspecialchars($matches[2]))));
			$left = (strlen($matches[1]) - 1) * 16;
			$_ret .= '<li style="margin-left:'.$left.'px">'.$special.
				'<a href="'.$href.LS2_CONTENT_HEAD.$anchor.'">'.$_ls2_messages['msg_go'].'</a></li>'."\n";
			$anchor++;
		}
		else if ($params['include'] and preg_match('/^#include\((.+)\)/',$line,$matches) and is_page($matches[1]))
			$_ret .= ls2_show_headings($matches[1],$params,TRUE);
	}
	if ($_ret != '') { $ret .= "<ul>$_ret</ul>\n"; }
	$ret .= '</li>'."\n";
	return $ret;
}
function ls2_get_child_pages($prefix) {
	global $vars;
	
	$pattern = '[['.$prefix;
	
	$pages = array();
	foreach (get_existpages() as $_page)
		if (strpos($_page,$pattern) === 0)
			$pages[$_page] = strip_bracket($_page);
	natcasesort($pages);

	return array_keys($pages);
}
//���ץ�������Ϥ���
function ls2_check_arg($val, $key, &$params) {
	if ($val == '') { $params['_done'] = TRUE; return; }
	if (!$params['_done']) {
		foreach (array_keys($params) as $key) {
			if (strpos($key, strtolower($val)) === 0) {
				$params[$key] = TRUE;
				return;
			}
		}
		$params['_done'] = TRUE;
	}
	$params['_args'][] = $val;
}

?>
