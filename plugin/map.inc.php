<?
//
// Last-Update:2002-08-05 rev.9
//  http://home.arino.jp/?map.inc.php
//
// �ץ饰���� map
//
// �����ȥޥå�(�Τ褦�ʤ��)��ɽ��
//
// Usage : http://.../pukiwiki.php?plugin=map
//
// �ѥ�᡼��
//
// &refer=�ڡ���̾
//  �����Ȥʤ�ڡ��������
//
// &cmd=reload
//  ����å�����˴������ڡ�������Ϥ��ʤ���
//
// &reverse=true
//  ����ڡ������ɤ������󥯤���Ƥ��뤫�������
//
// &url=true
//  �ϥ��ѡ����(http:/ftp:/mail:)��ɽ�����롣

function plugin_map_action() {
	global $InterWikiName,$WikiName,$BracketName,$defaultpage;
	global $myWikiName, $myInterWikiName, $myBracketName;
	global $vars;
	global $myTypes,$Pages,$Anchor,$Level,$Dirty,$retval;

	//reverse=true?
	$reverse = ($vars["reverse"] == "true");

	//���Ȥʤ�ڡ���̾�����
	$refer = $vars["refer"];

	//$retval["msg"]��$1���ִ������뤿���$vars["refer"]��񤭴����Ƥ��롣
	//�褤���Ͽ������������ܡ�
	if ($refer == "") { $vars["refer"] = $refer = $defaultpage; }

	//����ͤ�����
	if ($reverse) {
		$retval["msg"] = "Relation map (link from)";
	} else {
		$retval["msg"] = "Relation map, from $1";
	}
	$retval["body"] = "";

	//�ѥ�����ʸ������Խ������֥ѥ��������Ф��ʤ��褦�ˤ���
	$myWikiName = preg_replace("/\(/", "(?:", $WikiName);
	$myInterWikiName = preg_replace("/\(/", "(?:", $InterWikiName);
	$myBracketName = preg_replace("/\(/", "(?:", $BracketName);

	//�ѥ�������б�������̡�preg_match_all�Υѥ�᡼���ν���ȹ�碌�롣
	$myTypes = array("url", "url", "url", "url", "url", "InterWiki", "Wiki", "Wiki");

	//����å�����ɤ�
	$Dirty = 1;
	$file = PLUGIN_DIR.encode(basename(__FILE__)).".txt";
	if ($vars["cmd"] != "reload" and is_readable($file)) {
		$data = file($file);
		$Pages = unserialize($data[0]);
		$Anchor = unserialize($data[1]);
		$count = unserialize($data[2]);
		$Dirty = 0;
	}

	//$Pages�Υ��꡼�󥢥å�
	foreach (array_keys($Pages) as $key) {
		$Pages[$key]["Level"] = $Pages[$key]["Exist"] = 0;
		$Pages[$key]["From"] = array();
	}

	//�ڡ��������
	$pages = get_existpages();
	if (count($pages) == 0) {
		$retval["body"] = "no pages.";
		return $retval;
	}
	foreach ($pages as $page) { parsePage($page); }

	if ($Dirty) { //�Ѳ�����
		//¸�ߤ��ʤ��ڡ�������
		foreach (array_keys($Pages) as $key) {
			if ($Pages[$key]["Exist"] == 0) { unset($Pages[$key]); }
		}

		//��¸����ڡ����ο�
		$count = count($Pages);

		//WikiName��Ÿ��
		foreach (array_keys($Pages) as $key) {
			if (!$Pages[$key]["Expand"]) {
				$arr = array();
				foreach ($Pages[$key]["Wiki"] as $name) { $arr[] = expandName($key, $name); }
				$arr = array_unique($arr);
			} else {
				$arr = $Pages[$key]["Wiki"];
			}
			makeNotExistPage($arr);
			if (!$Pages[$key]["Expand"]) {
				usort($arr, "myWikiNameComp");
				$Pages[$key]["Wiki"] = $arr;
				$Pages[$key]["Expand"] = 1;
			}
		}
	}

	//�ڡ�����
	$retval["body"] .= "<p>\ntotal: $count page(s) on this site.\n</p>\n";

	//�¤��ؤ�
	uksort($Pages,"myWikiNameComp");

	if ($reverse) { //������
		//���
		foreach (array_keys($Pages) as $key) {
			foreach ($Pages[$key]["Wiki"] as $name) {
				if ($key == $name) { continue; }
				$Pages[$name]["From"][] = $key;
			}
		}

		foreach (array_keys($Pages) as $key) { usort($Pages[$key]["From"]); }

		$retval["body"] .= showReverse(TRUE);
		$retval["body"] .= "<hr>\n<p>no link from anywhere in this site.</p>\n";
		$retval["body"] .= showReverse(FALSE);
	} else { //������
		//����
		$Level = 0;
		$retval["body"] .= "<ul>\n";
		printNode($refer);
		$retval["body"] .= "</ul>\n";

		//not related
		$retval["body"] .= "<hr>\n<p>\nnot related from ".$Pages[$refer]["Link"].".\n</p>\n";
		foreach (array_keys($Pages) as $key) {
			if ($Pages[$key]["Level"] == 0 and $Pages[$key]["Exist"] > 0) {
				$retval["body"] .= "<ul>\n";
				printNode($key);
				$retval["body"] .= "</ul>\n";
			}
		}
	}
	//��¸
	if ($Dirty) {
		$data = serialize($Pages)."\n".serialize($Anchor)."\n".serialize($count);
		file_write(PLUGIN_DIR, basename(__FILE__), $data);
	}

	//��λ
	return $retval;
}
function showReverse($not) {
	global $Pages;
	foreach (array_keys($Pages) as $key) {
		if ($Pages[$key]["Exist"] != 1) { continue; }
		if (count($Pages[$key]["From"]) xor $not) { continue; }
		$body .= " <li>".$Pages[$key]["Link"];
		if (count($Pages[$key]["From"])) {
			if ($not) { $body .= " is link from"; }
			$body .= "\n  <ul>\n";
			foreach ($Pages[$key]["From"] as $name) { $body .= "   <li>".$Pages[$name]["Link"]."</li>\n"; }
			$body .= "  </ul>";
		}
		$body .= "</li>\n";
	}
	return ($body == "") ? "" : "<ul>\n$body</ul>\n";
}
//̤�����Υڡ����Υ���ȥ����
function makeNotExistPage($arr) {
	global $Pages;
	foreach ($arr as $expand) {
		if ($Pages[$expand]["Exist"] == 0) { //¸�ߤ��ʤ��ڡ���
			$Pages[$expand]["Strip"] = strip_bracket($expand);
			$Pages[$expand]["Exist"] = -1; //¸�ߤ��ʤ������ɤ����ǻ��Ѥ���Ƥ���
			$Pages[$expand]["Link"] = make_link($expand);
			$Pages[$expand]["Char"] = ((ord($Pages[$expand]["Strip"]) < 127) ? "0" : "1").$Pages[$expand]["Strip"]; //��������^^;
		}
	}
}
//�ڡ������ɤ�ǡ�WikiName����ȴ���Ф�
function parsePage($page) {
	global $whatsnew;
	global $myInterWikiName,$myWikiName,$myBracketName;
	global $Pages, $myTypes, $Anchor, $Dirty;

	//RecentChanges�Ͻ����� (��̣;-)
	if ($page == $whatsnew) { return; }

	//����å��������å�
	if ($Pages[$page]["File"] == "") { $Pages[$page]["File"] = DATA_DIR.encode($page).".txt"; }
	$mtime = filemtime($Pages[$page]["File"]);
	if ($mtime <= $Pages[$page][CTime]) {
		$Pages[$page]["Exist"] = 1;
		return;
	}
	$Dirty = 1;
	unset($Pages[$page]);
	$Pages[$page]["Strip"] = strip_bracket($page);
	$Pages[$page]["Exist"] = 1;
	$Pages[$page][CTime] = $mtime;
	$Pages[$page]["Anchor"] = ++$Anchor;
	$Pages[$page]["Link"] = make_link($page);
	$Pages[$page]["Raw"] = rawurlencode($page);
	$Pages[$page]["Special"] = htmlspecialchars($page);
	$Pages[$page]["Char"] = ((ord($Pages[$page]["Strip"]) < 127) ? "0" : "1").$Pages[$page]["Strip"]; //��������^^;

	$data = get_source($page);
	foreach($data as $line) {
		if (preg_match("/^[\s#]/", $line)) { continue; }
		preg_match_all("/
		(?:
			(\[\[(?:[^\]]+)\:(?:https?|ftp|news)(?:\:\/\/[-_.!~*'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)\]\])
			|
			(\[(?:https?|ftp|news)(?:\:\/\/[-_.!~*'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)\s(?:[^\]]+)\])
			|
			((?:https?|ftp|news)(?:\:\/\/[-_.!~*'()a-zA-Z0-9;\/?:\@&=+\$,%#]+))
			|
			([[:alnum:]\-_.]+@[[:alnum:]\-_]+\.[[:alnum:]\-_\.]+)
			|
			(\[\[(?:[^\]]+)\:(?:[[:alnum:]\-_.]+@[[:alnum:]\-_]+\.[[:alnum:]\-_\.]+)\]\])
			|
			($myInterWikiName)
			|
			($myBracketName)
			|
			($myWikiName)
		)/x",$line, $matches);
		array_shift($matches); //���ޥ�����
		for ($i = 0; $i < count($myTypes); $i++) {
			foreach ($matches[$i] as $name) {
				if ($name == "") { continue; }
				$Pages[$page][$myTypes[$i]][] = $name;
			}
		}
	}
	foreach ($Pages[$page] as $key => $val) {
		if (is_array($val)) {
			$Pages[$page][$key] = array_unique($val);
			$Pages[$page]["Count"] += count($Pages[$page][$key]);
		}
	}
}

//�ĥ꡼������������������
function printNode($name) {
	global $script,$vars;
	global $Level, $Pages;
	global $retval;

	if ($Pages[$name]["Level"] != $Level) {
		$retval["body"] .= " <li>".$Pages[$name]["Link"];
		if ($Pages[$name]["Count"] > 0) {
			$retval["body"] .= " <a href=\"#rel".$Pages[$name]["Anchor"]."\" title=\"".$Pages[$name]["Special"]."\">...</a>";
		}
		$retval["body"] .= "</li>\n";
		return;
	}

	$retval["body"] .= " <li>";

	if ($Pages[$name]["Count"] > 0) {
		$url = rawurlencode($vars["url"]);
		$retval["body"] .= "<a href=\"$script?plugin=map&amp;url=$url&amp;refer=".$Pages[$name]["Raw"]."\" title=\"change refer\"><sup>+</sup></a>\n";
	}
	if ($Pages[$name]["Anchor"] != 0) {
		$retval["body"] .= "<a name=\"rel".$Pages[$name]["Anchor"]."\"></a>";
	}
	$retval["body"] .= $Pages[$name]["Link"]."\n";
	if ($Pages[$name]["Exist"] > 0) {
		$retval["body"] .= getPassage($Pages[$name][CTime])."\n";
	}

	$Pages[$name]["Level"] = -1; //ɽ���Ѥ�

	//��졼���������
	if ($Pages[$name]["Count"] > 0) {
		$retval["body"] .= "<ul>\n";
		showWikiNodes($name);
		showHyperLinks($name);
		showInterWikiName($name);
		$retval["body"] .= "</ul>\n";
	}

	$retval["body"] .= "</li>\n";
}
//WikiName,BracketName�ν���
function showWikiNodes($page) {
	global $Pages, $Level;
	$Level++;
	foreach($Pages[$page]["Wiki"] as $name) {
		if ($Pages[$name]["Level"] == 0) {
			$Pages[$name]["Level"] = $Level; //ɽ����ͽ��
		}
	}
	foreach($Pages[$page]["Wiki"] as $name) { printNode($name); }
	$Level--;
}
//HyperLink�����
function showHyperLinks($page) {
	global $Pages,$retval,$vars;

	if ($vars["url"] == '') return;
	foreach ($Pages[$page][url] as $name) {
		$name = htmlspecialchars($name);
		if (preg_match("/^http:\/\/(\S+?)(\.jpg|\.jpeg|\.gif|\.png)$/si", $name)) {
			$link = preg_replace("/&([\"\<])/", "$1", make_link($name."&"));
		} else {
			$link = make_link($name);
		}
		$retval["body"] .= " <li>$link</li>\n";
	}
}
//InterWikiName�����
function showInterWikiName($page) {
	global $Pages,$retval;
	foreach ($Pages[$page][InterWiki] as $name) { $retval["body"] .= " <li>".make_link($name)."</li>\n"; }
}

function myWikiNameComp($a,$b) {
	global $Pages;
	return strnatcasecmp($Pages[$a]["Char"],$Pages[$b]["Char"]);
}

//file.php����ȴ���Ƥ���
function getPassage($date) {
	$date = UTIME - $date;
	if(ceil($date /= 60) < 60)
		$label = ceil($date)."m";
	else if(ceil($date /= 60) < 24)
		$label = ceil($date)."h";
	else
		$label = ceil($date / 24)."d";

	return "<small>(".$label.")</small>";
}
// html.php����ȴ���Ƥ��� :)
function expandName($page, $name) {
	global $WikiName,$BracketName,$defaultpage;
	global $Pages;
	$vars["page"] = $page;

	if(preg_match("/^(.+?)>(.+)$/",strip_bracket($name),$match)) // [[title>page]]
	{
		$page = $match[1];
		$name = $match[2];
		if(!preg_match("/^($BracketName)|($WikiName)$/",$page))
			$page = "[[$page]]";
		if(!preg_match("/^($BracketName)|($WikiName)$/",$name))
			$name = "[[$name]]";
	}
	if(preg_match("/^\[\[\.\/([^\]]*)\]\]/",$name,$match)) // [[./page]]
	{
		if(!$match[1])
			$name = $vars["page"];
		else
			$name = "[[".strip_bracket($vars["page"])."/$match[1]]]";
	}
	else if(preg_match("/^\[\[\..\/([^\]]+)\]\]/",$name,$match)) // [[(../)+page]]
	{
		for($i=0;$i<substr_count($name,"../");$i++)
			$name = preg_replace("/(.+)\/([^\/]+)$/","$1",strip_bracket($vars["page"]));

		if(!preg_match("/^($BracketName)|($WikiName)$/",$name))
			$name = "[[$name]]";
		
		if($vars["page"]==$name)
			$name = "[[$match[1]]]";
		else
			$name = "[[".strip_bracket($name)."/$match[1]]]";
	}
	else if($name == "[[../]]")
	{
		$name = preg_replace("/(.+)\/([^\/]+)$/","$1",strip_bracket($vars["page"]));
		
		if(!preg_match("/^($BracketName)|($WikiName)$/",$name))
			$name = "[[$name]]";
		if($vars["page"]==$name)
			$name = $defaultpage;
	}
	return $name;
}
?>
