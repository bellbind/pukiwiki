<?php
// PukiWiki - Yet another WikiWikiWeb clone.
// $Id: func.php,v 1.9 2002/12/02 02:49:42 panda Exp $
/////////////////////////////////////////////////

// ����
function do_search($word,$type="AND",$non_format=0)
{
	global $script,$whatsnew,$vars;
	global $_msg_andresult,$_msg_orresult,$_msg_notfoundresult;
	
	$database = array();
	$retval = array();
	$cnt = 0;

	$arywords = preg_split("/\s+/",$word,-1,PREG_SPLIT_NO_EMPTY);
	$result_word = $word;
	
	$files = get_existpages();
	foreach($files as $name)
	{
		$cnt++;
		if($name == $whatsnew) continue;
		if($name == $vars["page"] && $non_format) continue;
		$lines = get_source($name);
		$line = join("\n",$lines);
		
		$hit = 0;
		if(strpos($result_word," ") !== FALSE)
		{
			foreach($arywords as $word)
			{
				if($type=="AND")
				{
					if(strpos($line,$word) === FALSE)
					{
						$hit = 0;
						break;
					}
					else
					{
						$hit = 1;
					}
				}
				else if($type=="OR")
				{
					if(strpos($line,$word) !== FALSE)
						$hit = 1;
				}
			}
			if($hit==1 || strpos($name,$word)!==FALSE)
			{
				$page_url = rawurlencode($name);
				$word_url = rawurlencode($word);
				$name2 = strip_bracket($name);
				$str = get_pg_passage($name);
				$retval[$name2] = "<li><a href=\"$script?$page_url\">".htmlspecialchars($name2, ENT_QUOTES)."</a>$str</li>";
			}
		}
		else
		{
			if(stristr($line,$word) || stristr($name,$word))
			{
				$page_url = rawurlencode($name);
				$word_url = rawurlencode($word);
				$name2 = strip_bracket($name);
				$link_tag = "<a href=\"$script?$page_url\">".htmlspecialchars($name2, ENT_QUOTES)."</a>";
				$link_tag .= get_pg_passage($name,false);
				if($non_format)
				{
					$tm = @filemtime(get_filename(encode($name)));
					$retval[$tm] = $link_tag;
				}
				else
				{
					$retval[$name2] = "<li>$link_tag</li>";
				}
			}
		}
	}

	if($non_format)
		return $retval;

	$retval = list_sort($retval);

	if(count($retval) && !$non_format)
	{
		$retvals = "<ul>\n" . join("\n",$retval) . "</ul>\n<br />\n";
		
		if($type=="AND")
			$retvals.= str_replace('$1',htmlspecialchars($result_word),str_replace('$2',count($retval),str_replace('$3',$cnt,$_msg_andresult)));
		else
			$retvals.= str_replace('$1',htmlspecialchars($result_word),str_replace('$2',count($retval),str_replace('$3',$cnt,$_msg_orresult)));

	}
	else
		$retvals .= str_replace('$1',htmlspecialchars($result_word),$_msg_notfoundresult);
	return $retvals;
}

// �ץ����ؤΰ����Υ����å�
function arg_check($str)
{
	global $arg,$vars;

	return preg_match("/^".$str."/",$vars["cmd"]);
}

// �ڡ����ꥹ�ȤΥ�����
function list_sort($values)
{
	if(!is_array($values)) return array();
	
	// ksort�Τߤ��ȡ�[[���ܸ�]]��[[��ʸ��]]����ʸ���Τߡ��˽���¤��ؤ�����
	ksort($values);

	$vals1 = array();
	$vals2 = array();
	$vals3 = array();

	// ��ʸ���Τߡ�[[��ʸ��]]��[[���ܸ�]]���ν���¤��ؤ���
	foreach($values as $key => $val)
	{
		if(preg_match("/\[\[[^\w]+\]\]/",$key))
			$vals3[$key] = $val;
		else if(preg_match("/\[\[[\W]+\]\]/",$key))
			$vals2[$key] = $val;
		else
			$vals1[$key] = $val;
	}
	return array_merge($vals1,$vals2,$vals3);
}

// �ڡ���̾�Υ��󥳡���
function encode($key)
{
	$enkey = '';
	$arych = preg_split("//", $key, -1, PREG_SPLIT_NO_EMPTY);
	
	foreach($arych as $ch)
	{
		$enkey .= sprintf("%02X", ord($ch));
	}

	return $enkey;
}

// �ե�����̾�Υǥ�����
function decode($key)
{
	$dekey = '';
	
	for($i=0;$i<strlen($key);$i+=2)
	{
		$ch = substr($key,$i,2);
		$dekey .= chr(intval("0x".$ch,16));
	}
	return $dekey;
}

// InterWikiName List �β��(����:����������)
function open_interwikiname_list()
{
	global $interwiki;
	
	$retval = array();
	$aryinterwikiname = get_source($interwiki);

	$cnt = 0;
	foreach($aryinterwikiname as $line)
	{
		if(preg_match("/\[((https?|ftp|news)(\:\/\/[[:alnum:]\+\$\;\?\.%,!#~\*\/\:@&=_\-]+))\s([^\]]+)\]\s?([^\s]*)/",$line,$match))
		{
			$retval[$match[4]]["url"] = $match[1];
			$retval[$match[4]]["opt"] = $match[5];
		}
	}

	return $retval;
}

// [[ ]] �������
function strip_bracket($str)
{
	global $strip_link_wall;
	
	if($strip_link_wall)
	{
	  if(preg_match("/^\[\[(.*)\]\]$/",$str,$match)) {
	    $str = $match[1];
	  }
	}
	return $str;
}

// �ƥ����������롼���ɽ������
function catrule()
{
	global $rule_body;
	return $rule_body;
}

// ���顼��å�������ɽ������
function die_message($msg)
{
	$title = $page = "Runtime error";

	$body = "<h3>Runtime error</h3>\n";
	$body .= "<strong>Error message : $msg</strong>\n";

	if(defined(SKIN_FILE) && file_exists(SKIN_FILE) && is_readable(SKIN_FILE)) {
	  catbody($title,$page,$body);
	}
	else {
	  header("Content-Type: text/html; charset=euc-jp");
	  print <<<__TEXT__
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>$title</title>
<meta http-equiv="content-type" content="text/html; charset=euc-jp">
</head>
<body>$body</body>
</html>
__TEXT__;
	}
	die();
}

// ���߻����ޥ������äǼ���
function getmicrotime()
{
	list($usec, $sec) = explode(" ",microtime());
	return ((float)$sec + (float)$usec);
}

// ��ʬ�κ���
function do_diff($strlines1,$strlines2)
{
	$lines1 = split("\n",$strlines1);
	$lines2 = split("\n",$strlines2);
	
	$same_lines = $diff_lines = $del_lines = $add_lines = $retdiff = array();
	
	if(count($lines1) > count($lines2)) { $max_line = count($lines1)+2; }
	else                                { $max_line = count($lines2)+2; }

	//$same_lines = array_intersect($lines1,$lines2);

	$diff_lines2 = array_diff($lines2,$lines1);
	$diff_lines = array_merge($diff_lines2,array_diff($lines1,$lines2));

	foreach($diff_lines as $line)
	{
		$index = array_search($line,$lines1);
		if($index > -1)
		{
			$del_lines[$index] = $line;
		}
		
		//$index = array_search($line,$lines2);
		//if($index > -1)
		//{
		//	$add_lines[$index] = $line;
		//}
	}

	$cnt=0;
	foreach($lines2 as $line)
	{
		$line = rtrim($line);
		
		while($del_lines[$cnt])
		{
			$retdiff[] = "- ".$del_lines[$cnt];
			$del_lines[$cnt] = "";
			$cnt++;
		}
		
		if(in_array($line,$diff_lines))
		{
			$retdiff[] = "+ $line";
		}
		else
		{
			$retdiff[] = "  $line";
		}		

		$cnt++;
	}
	
	foreach($del_lines as $line)
	{
		if(trim($line))
			$retdiff[] = "- $line";
	}

	return join("\n",$retdiff);
}


// ��ʬ�κ���
function do_update_diff($oldstr,$newstr)
{
	$oldlines = split("\n",$oldstr);
	$newlines = split("\n",$newstr);
	
	$retdiff = $props = array();
	$auto = true;
	
	foreach($newlines as $newline) {
	  $flg = false;
	  $cnt = 0;
	  foreach($oldlines as $oldline) {
	    if($oldline == $newline) {
	      if($cnt>0) {
		for($i=0; $i<$cnt; ++$i) {
		  array_push($retdiff,array_shift($oldlines));
		  array_push($props,'! ');
		  $auto = false;
		}
	      }
	      array_push($retdiff,array_shift($oldlines));
	      array_push($props,'');
	      $flg = true;
	      break;
	    }
	    $cnt++;
	  }
	  if(!$flg) {
	    array_push($retdiff,$newline);
	    array_push($props,'+ ');
	  }
	}
	foreach($oldlines as $oldline) {
	  array_push($retdiff,$oldline);
	  array_push($props,'! ');
	  $auto = false;
	}
	if($auto) {
	  return array(join("\n",$retdiff),$auto);
	}

	$ret = '';
	foreach($retdiff as $line) {
	  $ret .= array_shift($props) . $line . "\n";
	}
	return array($ret,$auto);
}
?>
