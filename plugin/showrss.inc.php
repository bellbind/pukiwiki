<?php
/**
 *
 * showrss �ץ饰����
 * 
 * �饤���󥹤� PukiWiki ���Τ�Ʊ���� GNU General Public License (GPL) �Ǥ���
 * http://www.gnu.org/licenses/gpl.txt
 *
 * pukiwiki�ѤΥץ饰����Ǥ���
 * pukiwiki1.3.2�ʾ��ư���Ȼפ��ޤ���
 * 
 * ���ΤȤ���ư����뤿��ˤ�PHP �� xml extension ��ɬ�ܤǤ���PHP���Ȥ߹��ޤ�Ƥʤ����Ϥ��ä��ʤ����顼���Ф�Ȼפ��ޤ���
 * ����ɽ�� or ʸ����ؿ��Ǥʤ�Ȥ��ʤ�ʤ���ʤ����ʤ�Ǥ������פäƤɤ줯�餤����Τ��狼�餤�Τ���α�Ǥ���
 * mbstring �⤢��ۤ��������Ǥ���
 * 
 * �ʤ����ϡ� jcode.phps ����礳�äȤ����ä� mb_convert_encoding �Ȥ����ؿ���������Ƥ����ФȤꤢ��������äݤ��Ѵ��Ǥ��뤫��Ǥ���
 * http://www.spencernetwork.org/
 * 
 * ��Ϣ����:
 * do3ob wiki   ->   http://do3ob.com/
 * email        ->   hiro_do3ob@yahoo.co.jp
 * 
 * �����       ->   http://do3ob.s20.xrea.com/
 *
 * version: $Id: showrss.inc.php,v 1.2 2003/01/27 05:38:47 panda Exp $
 * 
 */

// ����å��嵡ǽ��Ȥ����ϰʲ��ǻ��ꤹ��ǥ��쥯�ȥ����������֤��Ƥ�������
if (!defined('CACHE_DIR')) {
	define('CACHE_DIR', './cache/');
}

// RSS��� "&lt; &gt; &amp;" �ʤɤ� ��ö "< > &" ���᤹����      �� "&amp;" �� "&amp;amp;" �ˤʤä��㤦���к�
if (!defined('SHOWRSS_VALUE_UNESCAPE')) {
	define('SHOWRSS_VALUE_UNESCAPE', true);
}

// ���θ��ä���"< > &"�ʤɤ�"&lt; &gt; &amp;"�ˤ��뤫��        �� XSS�к���
if (!defined('SHOWRSS_VALUE_ESCAPE')) {
	define('SHOWRSS_VALUE_ESCAPE'  , true);
}


function plugin_showrss_init() {

	global $_plugin_showrss_tmpl;

	$_plugin_showrss_tmpl = array();
	$_plugin_showrss_tmpl['default'] = array(
		'main' => '<p>{list}</p>',
		'list' => "<a href=\"{link}\" title=\"{description}\">{title}</a><br />\n",
		'lastmodified' => "<p style=\"font-size:10px\"><strong>Last-Modified:{timestamp}</strong></p>\n"
	);
	$_plugin_showrss_tmpl['menubar'] = array(
		'main' => "<ul class=\"recent_list\">\n{list}</ul>\n",
		'list' => " <li><a href=\"{link}\" title=\"{title} ({description})\">{title}</a></li>\n",
		'lastmodified' => "<p style=\"font-size:10px\"><strong>Last-Modified:{timestamp}</strong></p>\n"
	);
	$_plugin_showrss_tmpl['recent'] = array(
		'main' => "<ul class=\"recent_list\">\n{list}</ul>\n",
		'list' => " <li><a href=\"{link}\" title=\"{title} ({description})\">{title}</a></li>\n",
		'lastmodified' => "<p style=\"font-size:10px\"><strong>Last-Modified:{timestamp}</strong></p>\n"
	);
}

function plugin_showrss_convert() {

	global $_plugin_showrss_tmpl;

	$local_tmpl = $_plugin_showrss_tmpl; // timestamp�ղ���

	if (!extension_loaded('xml')) {
		// xml ��ĥ��ǽ��ͭ���Ǥʤ���硣
		// http://www18.tok2.com/home/koumori27/xml/phpsax/phpsax_menu.html ����Ѥ����Ʊ�����ȤǤ����������ɥˡ������뤫�ʡ�
		return plugin_showrss_private_error_message('xml extension is not loaded');
	}

	if (func_num_args() == 0) {
		// �������ʤ����ϥ��顼
		return plugin_showrss_private_error_message('wrong parameter');
	}
	
	$array = func_get_args();
	$rssurl = $tmplname = $usecache = $usetimestamp = '';
	
	switch (func_num_args()) {
	case 4:
		$usetimestamp = $array[3];
	case 3:
		$usecache = $array[2];
	case 2:
		$tmplname = $array[1];
	case 1:
		$rssurl = $array[0];
	}

	// ������ӽ�
	$rssurl       = trim($rssurl);
	$tmplname     = trim($tmplname);
	$usetimestamp = trim($usetimestamp);

	if ($tmplname == '' or (is_array($local_tmpl[$tmplname]) === false)) {
		$tmplname = 'default';
	}

	// RSS �ѥ����ͥ����å�
	if (plugin_showrss_private_check_url($rssurl) == false) {
		// url(������ե�����ѥ�)�������ʾ��
		return plugin_showrss_private_error_message("syntax error '$rssurl'");
	}

	if ($usecache > 0) {
		if (file_exists(CACHE_DIR) === false) {
			// ����å����Ȥ����Ȼפä����ɥ���å���ǥ��쥯�ȥ꤬¸�ߤ��ʤ���
			return plugin_showrss_private_error_message("don't exist:" . CACHE_DIR);
		}

		if (is_writable(CACHE_DIR) === false) {
			// ����å���ǥ��쥯�ȥ�Ͻ񤭹��߲�ǽ����
			return plugin_showrss_private_error_message("don't have permission to access :" . CACHE_DIR);
		}

		$expire = 60 * 60 * $usecache;
		if (($filename = plugin_showrss_private_cache_rss($rssurl, $expire)) !== false && filesize($filename) !== 0) {
			// ����å�����н�Ǥ������� url �򥭥�å���˽񤭴����롣
			$rssurl = $filename;
		}
		else {
			// ����å���������˼��Ԥ������ϲ���ʤ��ä��Τ��Ȥ����񤦡����� �㥨�顼�������٤���
			$usecache = 0;
		}
	}

	// �����ॹ����פĤ��������ɡ��⡼���礤���ޡ��Ȥ˽񤭤����ʡ���
	$timestamp = '';
	if ($usetimestamp > 0) {
		if ($usecache > 0) {
			$timestamp = filemtime($rssurl);
		}
		else {
			$timestamp = time();
		}
		$timestamp = date('Y/m/d H:i:s', $timestamp);
		$timestamp = str_replace('{timestamp}', $timestamp, $local_tmpl[$tmplname]['lastmodified']);
	}

	$parsed_rss_array = plugin_showrss_private_get_rss_array($rssurl);

	if (is_string($parsed_rss_array)) {
		// ����ͤ�ʸ������ȥ��顼��å�����
		return plugin_showrss_private_error_message($parsed_rss_array);
	}

	if (function_exists('mb_convert_encoding')) {
		// ���󥳡��ɤǤ������ENCODING�ˡ�
		foreach ($parsed_rss_array as $index => $parsed_rss) {
			foreach ($parsed_rss as $parsed_rss_key => $parsed_rss_value) {
				$parsed_rss_array[$index][$parsed_rss_key] = mb_convert_encoding($parsed_rss_value, ENCODING, 'auto');
			}
		}
	}
	return plugin_showrss_private_make_html($tmplname, $local_tmpl, $parsed_rss_array) . $timestamp;
}

// �ʲ���showrss �ץ饤�١��Ȥʴؿ��Ȥ�

// ���顼��å������ʴʰס�
function plugin_showrss_private_error_message($msg) {
	return '<strong>showrss:</strong>' . $msg;
}

// url�����å�
// ������ե�����ξ��� showrss??????.tmp �ߤ����ʥե�����̾����ʤ��ȥ��顼�ˤʤ�ޤ���
// ereg("showrss[a-z0-9_-]+\\.tmp") ������˥ޥå������OK!
function plugin_showrss_private_check_url($rssurl) {
	// parse_url�򤫤ޤ�������
	$parsed = parse_url(strtolower(trim($rssurl)));

	// scheme��http,https,ftp�ʤ�̵����OK
	$scheme = array('http', 'https', 'ftp');
	if (in_array($parsed['scheme'], $scheme)) {
		return true;
	}
	elseif (isset($parsed['scheme']) == true) {
		// ����ʳ���scheme�ϤȤꤢ�������顼�ˤ��Ƥߤ롣
		return false;
	}

	$filename = basename($parsed['path']);
	if (ereg("showrss[a-z0-9_\\.-]+\\.tmp", $filename)) {
		return true;
	}

	// ���٤Ƥξ��˰��óݤ���ʤ����� false
	return false;
}
// �ƥ�ץ졼�Ȥ�Ĥ��ä�rss���󤫤�html����
function plugin_showrss_private_make_html($tmplname, $showrss_tmpl, $parsed_rss_array) {

	// �ƥ�ץ졼����ͭ�δؿ��������硢�����Ĥ�Ȥ���
	if (function_exists("plugin_showrss_private_make_html_" . $tmplname) === true) {
		$makehtml = "plugin_showrss_private_make_html_" . $tmplname;
	}
	else {
		$makehtml = "plugin_showrss_private_make_html_default";
	}
	return $makehtml($tmplname, $showrss_tmpl, $parsed_rss_array);
}

// �ǥե���ȤΥƥ�ץ졼���֤������ؿ�
function plugin_showrss_private_make_html_default($tmplname, $showrss_tmpl, $parsed_rss_array) {
	$linklist = '';
	// �ִ���
	foreach ($parsed_rss_array as $index => $parsed_rss) {
		$linkhtml = $showrss_tmpl[$tmplname]["list"];
		foreach ($parsed_rss as $parsed_rss_key => $parsed_rss_value) {

			switch ($parsed_rss_key) {
			case "link":
				// ��󥯤ξ��
				// XSS �к��� "  > �Ȥ��Ѵ���
				break;
			case "description":
				if ($unixtime = strtotime(trim($parsed_rss_value))) {
					$parsed_rss_value = plugin_showrss_private_make_update_label($unixtime);
				}
				break;
			default:
				// �ʤ�
			}
			$parsed_rss_value = plugin_showrss_private_escape($parsed_rss_value);

			$linkhtml = str_replace("{" . $parsed_rss_key . "}", trim($parsed_rss_value), $linkhtml);
		}
		$linklist .= $linkhtml;
	}
	$linklist = str_replace("{list}", $linklist, $showrss_tmpl[$tmplname]["main"]);
	return $linklist;
}

// recent�����֤�������ؿ�
function plugin_showrss_private_make_html_recent($tmplname, $showrss_tmpl, $parsed_rss_array) {

	$last = $linklist = $temp = '';
	// �ִ���
	foreach ($parsed_rss_array as $index => $parsed_rss) {

		if (strtotime($parsed_rss['description']) !== false ) {
			if (date('Y-m-d', strtotime($parsed_rss['description'])) !== $last) {
				if ($temp != '') {
					$linklist .= "<p><strong>$last</strong></p>";
					$linklist .= str_replace('{list}', $temp, $showrss_tmpl[$tmplname]['main']);
					$temp = '';
				}
				$last = date('Y-m-d', strtotime($parsed_rss['description']));
			}
		}

		$linkhtml = $showrss_tmpl[$tmplname]["list"];
		foreach ($parsed_rss as $parsed_rss_key => $parsed_rss_value) {

			switch ($parsed_rss_key) {
			case "link":
				// ��󥯤ξ��
				// XSS �к��� "  > �Ȥ��Ѵ���
				break;
			case "description":
				if ($unixtime = strtotime(trim($parsed_rss_value))) {
					$parsed_rss_value = plugin_showrss_private_make_update_label($unixtime);
				}
				break;
			default:
				// �ʤ�
			}
			$parsed_rss_value = plugin_showrss_private_escape($parsed_rss_value);

			$linkhtml = str_replace("{" . $parsed_rss_key . "}", trim($parsed_rss_value), $linkhtml);
		}
		$temp .= $linkhtml;
	}
	if ($last != '')
		$linklist .= "<p><strong>$last</strong></p>";
	if ($temp != '')
		$linklist .= str_replace("{list}", $temp, $showrss_tmpl[$tmplname]["main"]);
	return $linklist;
}

// xss�к��äݤ��褦��
function plugin_showrss_private_escape($target) {

	if (SHOWRSS_VALUE_UNESCAPE) {
		$target = strtr($target, array_flip(get_html_translation_table(ENT_COMPAT)));
	}

	if (SHOWRSS_VALUE_ESCAPE) {
		$target = htmlspecialchars($target);
	}
	return $target;
}

// rss�����������
function plugin_showrss_private_get_rss_array($rss) {
	global $_plugin_showrss_insideitem,$_plugin_showrss_tag,$_plugin_showrss_title,
	$_plugin_showrss_description,$_plugin_showrss_link,$_plugin_showrss_parsed;

	// �����
	$_plugin_showrss_insideitem = false;
	$_plugin_showrss_tag = $_plugin_showrss_title = $_plugin_showrss_description = $_plugin_showrss_link = "";
	$_plugin_showrss_parsed = array();

	$xml_parser = xml_parser_create();
	xml_set_element_handler($xml_parser, "plugin_showrss_private_start_element", "plugin_showrss_private_end_element");
	xml_set_character_data_handler($xml_parser, "plugin_showrss_private_character_data");
	if (!($fp = @fopen($rss,"r"))) return("can't open $rss");
	while ($data = fread($fp, 4096))
		if (!xml_parse($xml_parser, $data, feof($fp))) {
			return(sprintf("XML error: %s at line %d in %s",
				       xml_error_string(xml_get_error_code($xml_parser)),
				       xml_get_current_line_number($xml_parser), $rss));
		}
	fclose($fp);
	xml_parser_free($xml_parser);
	return $_plugin_showrss_parsed;
}


// �������֤�pukiwiki�����Ѵ���
function plugin_showrss_private_make_update_label($time, $utime = UTIME) {
	$time = $utime - $time;

	if (ceil($time / 60) < 60)
		$result = ceil($time / 60)."m";
	else if (ceil($time / 60 / 60) < 24)
		$result = ceil($time / 60 / 60)."h";
	else
		$result = ceil($time / 60 / 60 / 24)."d";

	return $result;
}

// xml parser�Υϥ�ɥ�ؿ�
function plugin_showrss_private_start_element($parser, $name, $attrs) {
	global $_plugin_showrss_insideitem, $_plugin_showrss_tag, $_plugin_showrss_title, $_plugin_showrss_description, $_plugin_showrss_link;
	if ($_plugin_showrss_insideitem) {
		$_plugin_showrss_tag = $name;
	}
	else if ($name == "ITEM") {
		$_plugin_showrss_insideitem = true;
	}
}
// xml parser�Υϥ�ɥ�ؿ�
function plugin_showrss_private_end_element($parser, $name) {
	global $_plugin_showrss_insideitem, $_plugin_showrss_tag, $_plugin_showrss_title, $_plugin_showrss_description, $_plugin_showrss_link, $_plugin_showrss_parsed;
	if ($name == "ITEM") {

		$_plugin_showrss_parsed[] = array(
			"link"  =>  $_plugin_showrss_link,
			"title" =>  $_plugin_showrss_title,
			"description" => $_plugin_showrss_description
			);


		$_plugin_showrss_title = "";
		$_plugin_showrss_description = "";
		$_plugin_showrss_link = "";
		$_plugin_showrss_insideitem = false;
	}
}

// xml parser �Υϥ�ɥ�ؿ�
function plugin_showrss_private_character_data($parser, $data) {
	global $_plugin_showrss_insideitem, $_plugin_showrss_tag, $_plugin_showrss_title, $_plugin_showrss_description, $_plugin_showrss_link;
	if ($_plugin_showrss_insideitem) {
		switch ($_plugin_showrss_tag) {
		case "TITLE":
			$_plugin_showrss_title .= $data;
			break;
		case "DESCRIPTION":
			$_plugin_showrss_description .= $data;
			break;
		case "LINK":
			$_plugin_showrss_link .= $data;
			break;
		}
	}
}

// -- ����å������ -- //

// ����å���򥳥�ȥ���
function plugin_showrss_private_cache_rss($target, $expire) {
	// �����ڤ�Υ���å���򥯥ꥢ
	plugin_showrss_private_cache_garbage_collection(CACHE_DIR, $expire);
	// ����å��夬����м�������
	if (($result = plugin_showrss_private_cache_fetch($target, CACHE_DIR, $expire)) !== false) {
		return $result;
	}

	$data = implode('', file($target));

	if (($filename = plugin_showrss_private_cache_save($data, $target, CACHE_DIR)) === false) {
		return false;
	}

	return $filename;

}

// ����å��夬���뤫Ĵ�٤롣¸�ߤ�����ե�����̾
function plugin_showrss_private_cache_fetch($target, $dir) {

	$filename = $dir . encode($target) . ".tmp";

	if (!is_readable($filename)) {
		return false;
	}

	return $filename;
}

// ����å������¸
function plugin_showrss_private_cache_save($data, $target, $dir) {
	$filename = $dir . encode($target) . ".tmp";
	// lock����ʤ����ʡ�
	$fp = fopen($filename, "w");
	fwrite($fp, $data);
	fclose($fp);
	return $filename;
}

// �����ڤ�Υե��������
function plugin_showrss_private_cache_garbage_collection($dir, $expire) {

	$dh = dir($dir);
	while (($filename = $dh->read()) !== false) {
		if ($filename === '.' || $filename === '..') {
			continue;
		}

		$last = time() - filemtime($dir . $filename);

		if ($last > $expire) {
			unlink($dir . $filename);
		}
	}

	$dh->close();

}

?>