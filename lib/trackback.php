<?php
// $Id: trackback.php,v 1.3 2004/12/11 15:44:45 henoheno Exp $
/*
 * PukiWiki TrackBack �ץ����
 * (C) 2003-2004 PukiWiki Developer Team
 * (C) 2003, Katsumi Saito <katsumi@jo1upk.ymt.prug.or.jp>
 * License: GPL
 *
 * http://localhost/pukiwiki/pukiwiki.php?FrontPage �����Τ˻��ꤷ�ʤ���
 * TrackBack ID �μ����ϤǤ��ʤ�
 *
 * tb_get_id($page)        TrackBack Ping ID�����
 * tb_id2page($tb_id)      TrackBack Ping ID ����ڡ���̾�����
 * tb_get_filename($page)  TrackBack Ping �ǡ����ե�����̾�����
 * tb_count($page)         TrackBack Ping �ǡ����Ŀ�����  // pukiwiki.skin.LANG.php
 * tb_send($page, $data)   TrackBack Ping ����  // file.php
 * tb_delete($page)        TrackBack Ping �ǡ������  // edit.inc.php
 * tb_get($file, $key = 1) TrackBack Ping �ǡ�������
 * tb_get_rdf($page)       ʸ����������ि���rdf��ǡ��������� // pukiwiki.php
 * tb_get_url($url)        ʸ���GET���������ޤ줿TrackBack Ping URL�����
 * class TrackBack_XML     XML����TrackBack Ping ID��������륯�饹
 * == Referer �б�ʬ ==
 * ref_save($page)         Referer �ǡ�����¸(����) // pukiwiki.php
 */

define('PLUGIN_TRACKBACK_VERSION', 'PukiWiki/TrackBack 0.2');

// TrackBack Ping ID�����
function tb_get_id($page)
{
	return md5($page);
}

// TrackBack Ping ID ����ڡ���̾�����
function tb_id2page($tb_id)
{
	static $pages, $cache = array();

	if (isset($cache[$tb_id])) return $cache[$tb_id];

	if (! isset($pages)) $pages = get_existpages();

	foreach ($pages as $page) {
		$_tb_id = tb_get_id($page);
		$cache[$_tb_id] = $page;
		unset($pages[$page]);
		if ($_tb_id == $tb_id) return $page;
	}

	return FALSE; // Not found
}

// TrackBack Ping �ǡ����ե�����̾�����
function tb_get_filename($page, $ext = '.txt')
{
	return TRACKBACK_DIR . encode($page) . $ext;
}

// TrackBack Ping �ǡ����Ŀ�����
function tb_count($page, $ext = '.txt')
{
	$filename = tb_get_filename($page, $ext);
	return file_exists($filename) ? count(file($filename)) : 0;
}

// Send TrackBack Ping
// $plus  = Newly added lines
// $minus = Removed lines
function tb_send($page, $plus, $minus = '')
{
	global $script, $trackback;

	if (! $trackback) return;

	// Disable 'max execution time' (php.ini: max_execution_time)
	if (ini_get('safe_mode') == '0') set_time_limit(0);

	// Get URLs from <a>(anchor) tag from convert_html()
	$links = array();
	$plus  = convert_html($plus); // WARNING: heavy and may cause side-effect
	preg_match_all('#href="(https?://[^"]+)"#', $plus, $links, PREG_PATTERN_ORDER);

	// Reject own URL (= URL started from '$script')
	$links = preg_grep('|^' . preg_quote($script) . '\?.|',
		array_unique($links[1]),   PREG_GREP_INVERT);

	// Reject from minus list
	if ($minus != '') {
		$minus = convert_html($minus); // WARNING: heavy and may cause side-effect
		$links_m = array();
		preg_match_all('#href="(https?://[^"]+)"#', $minus, $links_m, PREG_PATTERN_ORDER);
		$links_m = preg_grep('|^' . preg_quote($script) . '\?.|',
			array_unique($links_m[1]), PREG_GREP_INVERT);
		foreach($links_m as $m_link)
			$links = preg_grep('|^' . preg_quote($m_link) . '$|', $links, PREG_GREP_INVERT);
	}

	// No link, END
	if (! is_array($links) || empty($links)) return;

	$r_page  = rawurlencode($page);
	$excerpt = strip_htmltag(convert_html(get_source($page)));

	// ��ʸ��ξ���
	$putdata = array(
		'title'     => $page, // Title = It's page name
		'url'       => "$script?$r_page", // �������˺��١�rawurlencode �����
		'excerpt'   => mb_strimwidth(preg_replace("/[\r\n]/", ' ', $excerpt), 0, 255, '...'),
		'blog_name' => PLUGIN_TRACKBACK_VERSION,
		'charset'   => SOURCE_ENCODING // ����¦ʸ��������(̤����)
	);

	foreach ($links as $link) {
		// URL ���� TrackBack ID ���������
		$tb_id = tb_get_url($link);
		if (empty($tb_id)) continue; // Trackback is not supported

		$result = http_request($tb_id, 'POST', '', $putdata);
		// FIXME: ���顼������ԤäƤ⡢���㡢�ɤ����롩�����ʤ�...
	}
}

// TrackBack Ping �ǡ������
function tb_delete($page)
{
	$filename = tb_get_filename($page);
	if (file_exists($filename))
		@unlink($filename);
}

// TrackBack Ping �ǡ�������
function tb_get($file, $key = 1)
{
	if (! file_exists($file)) return array();

	$result = array();
	$fp = @fopen($file, 'r');
	set_file_buffer($fp, 0);
	flock($fp, LOCK_EX);
	rewind($fp);
	while ($data = @fgetcsv($fp, 8192, ',')) {
		// $data[$key] = URL
		$result[rawurldecode($data[$key])] = $data;
	}
	flock($fp, LOCK_UN);
	fclose ($fp);

	return $result;
}

// ʸ����� trackback:ping �������ि��Υǡ���������
function tb_get_rdf($page)
{
	global $trackback;

	if (! $trackback) return '';

	$r_page = rawurlencode($page);
	$tb_id  = tb_get_id($page);
	// $dcdate = substr_replace(get_date('Y-m-d\TH:i:sO', $time), ':', -2, 0);
	// dc:date="$dcdate"

	$_script = get_script_uri(); // Get absolute path

	return <<<EOD
<!--
<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
  xmlns:dc="http://purl.org/dc/elements/1.1/"
  xmlns:trackback="http://madskills.com/public/xml/rss/module/trackback/">
 <rdf:Description
   rdf:about="$_script?$r_page"
   dc:identifier="$_script?$r_page"
   dc:title="$page"
   trackback:ping="$_script?tb_id=$tb_id" />
</rdf:RDF>
-->
EOD;
}

// ʸ���GET���������ޤ줿TrackBack Ping url�����
function tb_get_url($url)
{
	// �ץ������ͳ����ɬ�פ�����ۥ��Ȥˤ�ping���������ʤ�
	$parse_url = parse_url($url);
	if (empty($parse_url['host']) || via_proxy($parse_url['host']))
		return '';

	$data = http_request($url);
	if ($data['rc'] !== 200) return '';

	if (! preg_match_all('#<rdf:RDF[^>]*>(.*?)</rdf:RDF>#si', $data['data'],
	    $matches, PREG_PATTERN_ORDER))
		return '';

	$obj = new TrackBack_XML();
	foreach ($matches[1] as $body) {
		$tb_url = $obj->parse($body, $url);
		if ($tb_url !== FALSE) return $tb_url;
	}

	return '';
}

// �����ޤ줿�ǡ������� TrackBack Ping url��������륯�饹
class TrackBack_XML
{
	var $url;
	var $tb_url;

	function parse($buf, $url)
	{
		// �����
		$this->url    = $url;
		$this->tb_url = FALSE;

		$xml_parser = xml_parser_create();
		if ($xml_parser === FALSE) return FALSE;

		xml_set_element_handler($xml_parser, array(& $this, 'start_element'),
			array(& $this, 'end_element'));

		if (! xml_parse($xml_parser, $buf, TRUE)) {
/*			die(sprintf('XML error: %s at line %d in %s',
				xml_error_string(xml_get_error_code($xml_parser)),
				xml_get_current_line_number($xml_parser),
				$buf
			));
*/
			return FALSE;
		}

		return $this->tb_url;
	}

	function start_element($parser, $name, $attrs) {
		if ($name !== 'RDF:DESCRIPTION') return;

		$about = $url = $tb_url = '';
		foreach ($attrs as $key=>$value) {
			switch ($key) {
			case 'RDF:ABOUT':
				$about = $value;
				break;
			case 'DC:IDENTIFER':
			case 'DC:IDENTIFIER':
				$url = $value;
				break;
			case 'TRACKBACK:PING':
				$tb_url = $value;
				break;
			}
		}
		if ($about == $this->url || $url == $this->url) {
			$this->tb_url = $tb_url;
		}
	}

	function end_element($parser, $name) {}
}

// Referer �ǡ�����¸(����)
function ref_save($page)
{
	global $referer;

	if (! $referer || empty($_SERVER['HTTP_REFERER'])) return;

	$url = $_SERVER['HTTP_REFERER'];

	// URI ��������ɾ��
	// ����������ξ��Ͻ������ʤ�
	$parse_url = parse_url($url);
	if (empty($parse_url['host']) || $parse_url['host'] == $_SERVER['HTTP_HOST'])
		return;

	// TRACKBACK_DIR ��¸�ߤȽ񤭹��߲�ǽ���γ�ǧ
	if (! is_dir(TRACKBACK_DIR))      die(TRACKBACK_DIR.': No such directory');
	if (! is_writable(TRACKBACK_DIR)) die(TRACKBACK_DIR.': Permission denied');

	// Referer �Υǡ����򹹿�
	if (ereg("[,\"\n\r]", $url))
		$url = '"' . str_replace('"', '""', $url) . '"';

	$filename = tb_get_filename($page, '.ref');
	$data     = tb_get($filename, 3);
	$d_url    = rawurldecode($url);
	if (! isset($data[$d_url])) {
		// 0:�ǽ���������, 1:�����Ͽ����, 2:���ȥ�����, 3:Referer �إå�, 4:���Ѳ��ݥե饰(1��ͭ��)
		$data[$d_url] = array(UTIME, UTIME, 0, $url, 1);
	}
	$data[$d_url][0] = UTIME;
	$data[$d_url][2]++;

	$fp = fopen($filename, 'w');
	if ($fp === FALSE) return 1;	
	set_file_buffer($fp, 0);
	flock($fp, LOCK_EX);
	rewind($fp);
	foreach ($data as $line) {
		fwrite($fp, join(',', $line) . "\n");
	}
	flock($fp, LOCK_UN);
	fclose($fp);

	return 0;
}
?>
