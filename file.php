<?
// PukiWiki - Yet another WikiWikiWeb clone.
// $Id: file.php,v 1.2 2002/07/18 15:24:08 masui Exp $
/////////////////////////////////////////////////

// �����������
function get_source($page)
{	
  if(page_exists($page)) {
     return file(get_filename(encode($page)));
  }

  return array();
}

// �ڡ�����¸�ߤ��뤫��
function page_exists($page)
{
	return file_exists(get_filename(encode($page)));
}

// �ե�����ؤν���
function file_write($dir,$page,$str)
{
	global $post,$update_exec;

	if($str == "")
	{
		@unlink($dir.encode($page).".txt");
	}
	else
	{
		if($post["notimestamp"] && is_page($page))
		{
			$timestamp = @filemtime($dir.encode($page).".txt");
		}
		$fp = fopen($dir.encode($page).".txt","w");
		if($fp===FALSE) die_message("cannot write page file or diff file or other".htmlspecialchars($page)."<br>maybe permission is not writable or filename is too long");
		while(!flock($fp,LOCK_EX));
		fputs($fp,$str);
		flock($fp,LOCK_UN);
		fclose($fp);
		if($timestamp)
			touch($dir.encode($page).".txt",$timestamp);
	}
	
	if(!$timestamp)
		put_lastmodified();

	if($update_exec)
	{
		system($update_exec." > /dev/null &");
	}
}

// �ǽ������ڡ����ι���
function put_lastmodified()
{
	global $script,$maxshow,$whatsnew,$date_format,$time_format,$weeklabels,$post,$non_list;

	if($post["notimestamp"]) return;

	$files = get_existpages();
	foreach($files as $page) {
		if($page == $whatsnew) continue;
		if(preg_match("/$non_list/",$page)) continue;

		if(file_exists(get_filename(encode($page))))
			{
			$page_url = rawurlencode($page);
			$lastmodtime = filemtime(get_filename(encode($page)));
			$lastmod = date($date_format,$lastmodtime)
				 . " (" . $weeklabels[date("w",$lastmodtime)] . ") "
				 . date($time_format,$lastmodtime);
			$putval[$lastmodtime][] = "-$lastmod - $page";
		}
	}
	
	$cnt = 1;
	krsort($putval);
	$fp = fopen(get_filename(encode($whatsnew)),"w");
	if($fp===FALSE) die_message("cannot write page file ".htmlspecialchars($whatsnew)."<br>maybe permission is not writable or filename is too long");
	flock($fp,LOCK_EX);
	foreach($putval as $pages)
	{
		foreach($pages as $page)
		{
			fputs($fp,$page."\n");
			$cnt++;
			if($cnt > $maxshow) break;
		}
		if($cnt > $maxshow) break;
	}
	flock($fp,LOCK_EX);
	fclose($fp);
}

// �ե�����̾������(���󥳡��ɤ���Ƥ���ɬ��ͭ��)
function get_filename($pagename)
{
	return DATA_DIR.$pagename.".txt";
}

// �ڡ�����¸�ߤ��뤫���ʤ���
function is_page($page,$reload=false)
{
	global $InterWikiName,$_ispage;

	if(($_ispage[$page] === true || $_ispage[$page] === false) && !$reload) return $_ispage[$page];

	if(preg_match("/($InterWikiName)/",$page))
		$_ispage[$page] = false;
	else if(!page_exists($page))
		$_ispage[$page] = false;
	else
		$_ispage[$page] = true;
	
	return $_ispage[$page];
}

// �ڡ������Խ���ǽ��
function is_editable($page)
{
	global $BracketName,$WikiName,$InterWikiName,$cantedit,$_editable;

	if($_editable === true || $_editable === false) return $_editable;

	if(preg_match("/^$InterWikiName$/",$page))
		$_editable = false;
	elseif(!preg_match("/^$BracketName$/",$page) && !preg_match("/^$WikiName$/",$page))
		$_editable = false;
	else if(in_array($page,$cantedit))
		$_editable = false;
	else
		$_editable = true;
	
	return $_editable;
}

// �ڡ�������뤵��Ƥ��뤫
function is_freeze($page)
{
	global $_freeze;

	if(!is_page($page)) return false;
	if($_freeze === true || $_freeze === false) return $_freeze;

	$lines = get_source($page);
	
	if($lines[0] == "#freeze\n")
		$_freeze = true;
	else
		$_freeze = false;
	
	return $_freeze;
}

// ���ꤵ�줿�ڡ����ηв����
function get_pg_passage($page,$sw=true)
{
	global $_pg_passage,$show_passage;

	if(!$show_passage) return "";

	if(isset($_pg_passage[$page]))
	{
		if($sw)
			return $_pg_passage[$page]["str"];
		else
			return $_pg_passage[$page]["label"];
	}
	if($pgdt = @filemtime(get_filename(encode($page))))
	{
		$pgdt = UTIME - $pgdt;
		if(ceil($pgdt / 60) < 60)
			$_pg_passage[$page]["label"] = "(".ceil($pgdt / 60)."m)";
		else if(ceil($pgdt / 60 / 60) < 24)
			$_pg_passage[$page]["label"] = "(".ceil($pgdt / 60 / 60)."h)";
		else
			$_pg_passage[$page]["label"] = "(".ceil($pgdt / 60 / 60 / 24)."d)";
		
		$_pg_passage[$page]["str"] = "<small>".$_pg_passage[$page]["label"]."</small>";
	}
	else
	{
		$_pg_passage[$page]["label"] = "";
		$_pg_passage[$page]["str"] = "";
	}

	if($sw)
		return $_pg_passage[$page]["str"];
	else
		return $_pg_passage[$page]["label"];
}

// Last-Modified �إå�
function header_lastmod($page)
{
	global $lastmod;
	
	if($lastmod && is_page($page))
	{
		header("Last-Modified: ".gmdate("D, d M Y H:i:s", filemtime(get_filename(encode($page))))." GMT");
	}
}

// ���ڡ���̾�������
function get_existpages()
{
	$aryret = array();

	if ($dir = @opendir(DATA_DIR))
	{
		while($file = readdir($dir))
		{
			if($file == ".." || $file == "." || strstr($file,".txt")===FALSE) continue;
			$page = decode(trim(preg_replace("/\.txt$/"," ",$file)));
			array_push($aryret,$page);
		}
		closedir($dir);
	}
	
	return $aryret;
}
?>
