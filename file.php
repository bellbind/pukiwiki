<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: file.php,v 1.4 2003/01/27 05:38:41 panda Exp $
//

// �����������
function get_source($page)
{
	return is_page($page) ?
		crlf_rtrim(file(get_filename($page))) : array();
}

// �ڡ����ι������������
function get_filetime($page)
{
	return filemtime(get_filename($page)) - LOCALZONE;
}

// �ڡ����Υե�����̾������
function get_filename($page)
{
	return DATA_DIR.encode($page).'.txt';
}

// �ڡ����ν���
function page_write($page,$postdata)
{
	$postdata = user_rules_str($postdata);
	
	// ��ʬ�ե�����κ���
	$oldpostdata = is_page($page) ? join('',get_source($page)) : "\n";
	$diffdata = do_diff($oldpostdata,$postdata);
	file_write(DIFF_DIR,$page,$diffdata);
	
	// �Хå����åפκ���
	make_backup($page,$postdata == '');
	
	// �ե�����ν񤭹���
	file_write(DATA_DIR,$page,$postdata);
	
	// is_page�Υ���å���򥯥ꥢ���롣
	is_page($page,true);
	
	// link�ǡ����١����򹹿�
	links_update($page);
}

// �ե�����ؤν���
function file_write($dir,$page,$str)
{
	global $post,$update_exec;
	
	$page = strip_bracket($page);
	$timestamp = FALSE;
	$file = $dir.encode($page).'.txt';
	
	if ($dir == DATA_DIR and $str == '' and file_exists($file)) {
		unlink($file);
	}
	else {
		$str = preg_replace("/\r/",'',$str);
		$str = rtrim($str)."\n";
		
		if (!empty($post['notimestamp']) and file_exists($file)) {
			$timestamp = filemtime($file) - LOCALZONE;
		}
		
		$fp = fopen($file,'w')
			or die_message('cannot write page file or diff file or other'.htmlspecialchars($page).'<br>maybe permission is not writable or filename is too long');
		flock($fp,LOCK_EX);
		fputs($fp,$str);
		flock($fp,LOCK_UN);
		fclose($fp);
		if ($timestamp) {
			touch($file,$timestamp + LOCALZONE);
		}
	}
	
	if (!$timestamp) {
		put_lastmodified();
	}
	
	if ($update_exec and $dir == DATA_DIR) {
		system($update_exec.' > /dev/null &');
	}
}

// �ǽ������ڡ����ι���
function put_lastmodified()
{
	global $script,$post,$maxshow,$whatsnew,$non_list;

	$pages = array();
	foreach(get_existpages() as $page) {
		if ($page == $whatsnew or preg_match("/$non_list/",$page)) {
			continue;
		}
		
		$time = get_filetime($page);
		$s_page = htmlspecialchars($page);
		$pages[$s_page] = $time;
	}
	
	arsort($pages); //����߽�ǥ�����
	
	$fp = fopen(get_filename($whatsnew),'w')
		or die_message('cannot write page file '.htmlspecialchars($whatsnew).'<br>maybe permission is not writable or filename is too long');
	
	flock($fp,LOCK_EX);
	
	foreach($pages as $s_page => $time) {
		fputs($fp, "//$time $s_page\n");
	}
	
	$pages = array_splice($pages,0,$maxshow);
	
	foreach($pages as $s_page => $time) {
		$lastmod = format_date($time);
		fputs($fp, "-$lastmod - [[$s_page]]\n");
	}
	
	fputs($fp,"#norelated\n"); // :)
	flock($fp,LOCK_UN);
	fclose($fp);
}

// ���ꤵ�줿�ڡ����ηв����
function get_pg_passage($page,$sw=TRUE)
{
	global $show_passage;
	static $pg_passage;
	
	if (!$show_passage) {
		return '';
	}
	
	if (!isset($pg_passage)) {
		$pg_passage = array();
	}
	
	if (!array_key_exists($page,$pg_passage)) {
		$pg_passage[$page] = (is_page($page) and $time = get_filetime($page)) ? get_passage($time) : '';
	}
	
	return $sw ? "<small>{$pg_passage[$page]}</small>" : $pg_passage[$page];
}

// Last-Modified �إå�
function header_lastmod()
{
	global $lastmod;
	
	if ($lastmod and is_page($page)) {
		header('Last-Modified: '.date('D, d M Y H:i:s',get_filetime($page)).' GMT');
	}
}

// ���ڡ���̾�������
function get_existpages($dir = DATA_DIR)
{
	$aryret = array();
	
	$dir = @opendir($dir) or die();
	
	while ($file = readdir($dir)) {
		if (preg_match('/^([0-9A-F]+)/',$file,$matches)) {
			$aryret[] = decode($matches[1]);
		}
	}
	
	closedir($dir);
	
	return $aryret;
}

function links_update($page)
{
	global $vars;

	// link�ǡ����١����򹹿�
	if (defined('LINK_DB') and exist_plugin_action('links')) {
		// ����äȸ�©
		$tmp = $vars['page'];
		$vars['page'] = $page;
		do_plugin_action('links');
		$vars['page'] = $tmp;
	}
}
//����ڡ����δ�Ϣ�ڡ���������
function links_get_related($page)
{
	global $vars,$related;
	static $links;
	
	if (!isset($links)) {
		$links = array();
	}
	
	if (array_key_exists($page,$links)) {
		return $links[$page];
	}
	
	// ��ǽ�ʤ�make_link()������������Ϣ�ڡ����������
	$links[$page] = ($page == $vars['page']) ? $related : array();
	
	$a_page = addslashes($page);
	
	if (defined('LINK_DB')) {
		$sql = <<<EOD
SELECT refpage.name,refpage.lastmod FROM page left join link on page.id = page_id left join page as refpage on ref_id = refpage.id where page.name = '$a_page'
UNION
SELECT DISTINCT refpage.name,refpage.lastmod FROM page left join link on page.id = ref_id left join page as refpage on page_id = refpage.id where page.name = '$a_page';
EOD;
		$rows = db_query($sql);
		
		foreach ($rows as $row) {
			if (empty($row['name']) or substr($row['name'],0,1) == ':') {
				continue;
			}
			$links[$page][$row['name']] = $row['lastmod'];
		}
	}
	else {
		$links[$page] = array_merge($links[$page],do_search($page,'OR',1));
	}
	
	return $links[$page];
}
?>
