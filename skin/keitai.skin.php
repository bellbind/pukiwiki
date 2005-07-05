<?php
// PukiWiki - Yet another WikiWikiWeb clone.
// $Id: keitai.skin.php,v 1.11 2005/07/05 13:40:54 henoheno Exp $
// Copyright (C) 2003-2005 PukiWiki Developers Team
// License: GPL v2 or (at your option) any later version
//
// Skin for Embedded devices

// ----
// Prohibit direct access
if (! defined('UI_LANG')) die('UI_LANG is not set');

global $max_size, $accesskey, $menubar;
$link = $_LINK;
$rw = ! PKWK_READONLY;

// Output HTTP headers
pkwk_headers_sent();
if(TRUE) {
	// Force Shift JIS encode for Japanese embedded browsers and devices
	header('Content-Type: text/html; charset=Shift_JIS');
	$title = mb_convert_encoding($title, 'SJIS', SOURCE_ENCODING);
	$body  = mb_convert_encoding($body,  'SJIS', SOURCE_ENCODING);
} else {
	header('Content-Type: text/html; charset=' . CONTENT_CHARSET);
}

// Make 1KByte spare (for header, etc)
$max_size = --$max_size * 1024;

// Replace IMG tags (= images) with character strings
// With ALT option
$body = preg_replace('#(<div[^>]+>)?(<a[^>]+>)?<img[^>]*alt="([^"]+)"[^>]*>(?(2)</a>)(?(1)</div>)#i', '[$3]', $body);
// Without ALT option
$body = preg_replace('#(<div[^>]+>)?(<a[^>]+>)?<img[^>]+>(?(2)</a>)(?(1)</div>)#i', '[img]', $body);

// Check content volume, Page numbers, divided by this skin
$pageno = (isset($vars['p']) && is_numeric($vars['p'])) ? $vars['p'] : 0;
$pagecount = ceil(strlen($body) / $max_size);
$lastpage = $pagecount - 1;

// Too large contents to edit
if (((isset($vars['cmd'])    && $vars['cmd']    == 'edit') ||
     (isset($vars['plugin']) && $vars['plugin'] == 'edit')) &&
   $pagecount > 1)
   	die('Unable to edit: Too large contents for your device');

// Top navigation (text) bar
$navi = array();
$navi[] = '<a href="' . $link['top']  . '" ' . $accesskey . '="0">0.Top</a>';
if ($rw) {
	$navi[] = '<a href="' . $link['new']  . '" ' . $accesskey . '="1">1.New</a>';
	$navi[] = '<a href="' . $link['edit'] . '" ' . $accesskey . '="2">2.Edit</a>';
	if ($is_read && $function_freeze) {
		if (! $is_freeze) {
			$navi[] = '<a href="' . $link['freeze']   . '" ' . $accesskey . '="3">3.Freeze</a>';
		} else {
			$navi[] = '<a href="' . $link['unfreeze'] . '" ' . $accesskey . '="3">3.Unfreeze</a>';
		}
	}
}
$navi[] = '<a href="' . $script . '?' . $menubar . '" ' . $accesskey . '="4">4.Menu</a>';
$navi[] = '<a href="' . $link['recent'] . '" ' . $accesskey . '="5">5.Recent</a>';

// Previous / Next block
if ($pagecount > 1) {
	$prev = $pageno - 1;
	$next = $pageno + 1;
	if ($pageno > 0) {
		$navi[] = '<a href="' . $script . '?cmd=read&amp;page=' . $r_page .
			'&amp;p=' . $prev . '" ' . $accesskey . '="7">7.Prev</a>';
	}
	$navi[] = $next . '/' . $pagecount . ' ';
	if ($pageno < $lastpage) {
		$navi[] = '<a href="' . $script . '?cmd=read&amp;page=' . $r_page .
			'&amp;p=' . $next . '" ' . $accesskey . '="8">8.Next</a>';
	}
}

$navi = join(' | ', $navi);
$body = substr($body, $pageno * $max_size, $max_size);

// Output
?><html><head><title><?php
	echo $title
?></title></head><body><?php
	echo $navi
?><hr><?php
	echo $body
?></body></html>
