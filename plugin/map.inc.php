<?php
/*
Last-Update:2002-10-29 rev.11
 http://home.arino.jp/?map.inc.php

�ץ饰���� map

�����ȥޥå�(�Τ褦�ʤ��)��ɽ��

Usage : http://.../pukiwiki.php?plugin=map

�ѥ�᡼��

&refer=�ڡ���̾
 �����Ȥʤ�ڡ��������

���ΥС������Ǥ�cmd=reload����ꤷ�Ƥ�ư����Ѥ��ޤ���
%%&cmd=reload%%
%% ����å�����˴������ڡ�������Ϥ��ʤ���%%

&reverse=true
 ����ڡ������ɤ������󥯤���Ƥ��뤫�������

&url=true
 �ϥ��ѡ����(http:/ftp:/mail:)��ɽ�����롣
*/

function plugin_map_action()
{
	global $vars,$defaultpage,$whatsnew;
	global $Pages,$Anchor,$Level,$Dirty,$retval;
	
	//reverse=true?
	$reverse = array_key_exists('reverse',$vars);
	
	//���Ȥʤ�ڡ���̾�����
	$refer = array_key_exists('refer',$vars) ? $vars['refer'] : '';
	
	//$retval['msg']��$1���ִ������뤿���$vars['refer']��񤭴����Ƥ��롣
	if ($refer == '') {
		$vars['refer'] = $refer = $defaultpage;
	}
	
	//����ͤ�����
	if ($reverse) {
		$retval['msg'] = 'Relation map (link from)';
	}
	else {
		$retval['msg'] = 'Relation map, from $1';
	}
	$retval['body'] = '';
	
	//�ڡ��������
	$pages = get_existpages();
	
	//RecentChanges�Ͻ�����
	$n = array_search($whatsnew,$pages);
	if ($n !== FALSE) {
		unset($pages[$n]);
	}
	
	//��¸����ڡ����ο�
	$count = count($pages);
	
	if ($count == 0) {
		$retval['body'] = 'no pages.';
		return $retval;
	}
	
	//�ڡ�����
	$retval['body'] .= "<p>\ntotal: $count page(s) on this site.\n</p>\n";
	
	//get_link���̤����Ȥǡ����֥������Ȥ�����Ȥ���
	$obj = new link_wrapper($refer);
	$pages = $obj->get_link('[['.join(']] [[',$pages).']]');
	
	//�ڡ�����°�������
	$anchor = 0;
	$_pages = array();
	foreach (array_keys($pages) as $key) {
		$_obj =& $pages[$key];
		$_obj->_exist = TRUE;
		$_obj->_ctime = get_filetime($_obj->name);
		$_obj->_anchor = ++$anchor;
		$_obj->_link = $_obj->toString($refer);
		$_obj->_level = 0;
		$_obj->_special = htmlspecialchars($_obj->name);
		$_obj->_links = array('url'=>array(),'WikiName'=>array(),'InterWikiName'=>array());
		
		$_pages[$_obj->name] =& $pages[$key];
	}
	$pages = $_pages; unset($_pages);
	
	//�ڡ�����Υ�󥯤����
	foreach (array_keys($pages) as $page) {
		$obj->page = $page; // link_wrapper�λȤ��ޤ路
		$data = $obj->get_link(join('',preg_grep('/^[^\s#]/',get_source($page))));
		$pages[$page]->_count = count($data);
		foreach ($data as $link) {
			if ($link->type == 'WikiName') {
				if (array_key_exists($link->name,$pages)) {
					$pages[$page]->_links['WikiName'][$link->name] =& $pages[$link->name];
				}
				else {
					$link->_exist = FALSE;
					$link->_link = $link->toString();
					$pages[$page]->_links['WikiName'][$link->name] = $link;
				}
			}
			else {
				$link->is_image = FALSE; //����
				$link->_link = $link->toString();
				$pages[$page]->_links[$link->type][$link->name] = $link;
			}
		}
	}
	//�¤��ؤ�
//	uksort($Pages,'myWikiNameComp');
	
	if ($reverse) { //������
		//���
		foreach (array_keys($pages) as $page) {
			foreach ($pages[$page]->_links['WikiName'] as $from) {
				if ($page != $from->name) {
					$pages[$from->name]->_from[] = $page;
				}
			}
		}
		
		foreach (array_keys($pages) as $page) {
			usort($pages[$page]->_from);
		}
		
		$retval['body'] .= showReverse($pages,TRUE);
		$retval['body'] .= "<hr />\n<p>no link from anywhere in this site.</p>\n";
		$retval['body'] .= showReverse($pages,FALSE);
	}
	else { //������
		//����
		$retval['body'] .= "<ul>\n".showNode($pages,$refer)."</ul>\n";
		
		//not related
		$retval['body'] .= "<hr />\n<p>not related from $refer</p>\n";
		foreach (array_keys($pages) as $page) {
			if ($pages[$page]->_exist and $pages[$page]->_level == 0) {
				$retval['body'] .= "<ul>\n" . showNode($pages,$page) . "</ul>\n";
			}
		}
	}
	
	//��λ
	return $retval;
}
function showReverse(&$pages,$not)
{
	$body = '';
	
	foreach (array_keys($pages) as $page) {
		if ((!$pages[$page]->_exist) or (count($pages[$page]->_from) xor $not)) {
			continue;
		}
		$body .= ' <li>'.$pages[$page]->_link;
		if (count($pages[$page]->_from)) {
			if ($not) {
				$body .= ' is link from';
			}
			$body .= "\n  <ul>\n";
			foreach ($pages[$page]->_from as $from) {
				$body .= "   <li>{$pages[$from]->_link}</li>\n";
			}
			$body .= '  </ul>';
		}
		$body .= "</li>\n";
	}
	return ($body == '') ? '' : "<ul>\n$body</ul>\n";
}

//�ĥ꡼������������������
function showNode(&$pages,$page,$level = 0)
{
	global $script,$vars;
	$body = '';
	
	$show_url = array_key_exists('url',$vars);
	
	if ($pages[$page]->_level != $level) { // �ޤ�ɽ�������ʳ��ǤϤʤ�
		$body .= ' <li>'.$pages[$page]->_link;
		if ($pages[$page]->_count > 0)
			$body .= ' <a href="#rel'.$pages[$page]->_anchor.'" title="'.$pages[$page]->_special.'">...</a>';
		$body .= "</li>\n";
		return $body;
	}
	$pages[$page]->_level = -1; //ɽ���Ѥ�
	$body .= ' <li>';
	
	if ($pages[$page]->_count > 0) {
		$refer= '&amp;refer='.rawurlencode($pages[$page]->name);
		$url = array_key_exists('url',$vars) ? '&amp;url=true' : '';
		$id = ($pages[$page]->_anchor == 0) ? '' : 'id="rel'.$pages[$page]->_anchor.'"';
		$body .= "<a $id href=\"$script?plugin=map$refer$url\" title=\"change refer\"><sup>+</sup></a>\n";
	}
	
	$body .= $pages[$page]->_link."\n";
	
	//��졼���������
	if ($pages[$page]->_count > 0) {
		$_rel = showWikiNodes($pages,$page,$level);
		if ($show_url) {
			$_rel .= showHyperLinks($pages[$page]).showInterWikiName($pages[$page]);
		}
		if ($_rel != '') {
			$body .= "<ul>\n$_rel</ul>\n";
		}
	}
	
	return $body."</li>\n";
}
//WikiName,BracketName�ν���
function showWikiNodes(&$pages,$page,$level)
{
	$body = '';
	$_level = $level + 1;
	
	foreach ($pages[$page]->_links['WikiName'] as $_obj)
		if (array_key_exists($_obj->name,$pages) and $pages[$_obj->name]->_level == 0)
			$pages[$_obj->name]->_level = $_level; //ɽ����ͽ��
	
	foreach ($pages[$page]->_links['WikiName'] as $_obj) {
		if ($_obj->_exist)
			$body .= showNode($pages,$_obj->name,$_level);
		else
			$body .= " <li>{$_obj->_link}</li>";
	}
	return $body;
}
//HyperLink�����
function showHyperLinks(&$obj)
{
	$body = '';
	
	foreach ($obj->_links['url'] as $_obj)
		$body .= " <li>{$_obj->_link}</li>\n";
	
	return $body;
}
//InterWikiName�����
function showInterWikiName(&$obj)
{
	$body = '';
	
	foreach ($obj->_links['InterWikiName'] as $_obj)
		$body .= " <li>{$_obj->_link}</li>\n";
	
	return $body;
}

function myWikiNameComp($a,$b)
{
	global $Pages;
	
	return strnatcasecmp($Pages[$a]['Char'],$Pages[$b]['Char']);
}

?>
