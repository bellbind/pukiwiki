<?php
// $Id: trackback.php,v 1.6 2003/07/14 08:09:17 arino Exp $
/*
 * PukiWiki TrackBack �ץ����
 * (C) 2003, Katsumi Saito <katsumi@jo1upk.ymt.prug.or.jp>
 * License: GPL
 *
 * http://localhost/pukiwiki/pukiwiki.php?FrontPage �����Τ˻��ꤷ�ʤ���
 * TrackBack ID �μ����ϤǤ��ʤ�
 *
 * tb_count($page, $ext)	TrackBack Ping �ǡ����Ŀ����� // pukiwiki.skin.LANG.php
 * tb_send($page,$data)		TrackBack Ping ���� // file.php
 * tb_ScanLink($data)		convert_html() �Ѵ���̤� <a> �������� URL ���
 * tb_PageInfo($page)		�ڡ����������
 * tb_xml_msg($rc,$msg)		XML ��̽���
 * tb_save()			TrackBack Ping �ǡ�����¸(����)
 * tb_delete($page)		TrackBack Ping �ǡ������ // edit.inc.php
 * tb_get($file)		TrackBack Ping �ǡ�������
 * tb_put($file,$data)		TrackBack Ping �ǡ�������
 * tb_mode_rss($file)		?__mode=rss ����
 * tb_mode_view($id)		?__mode=view ����
 * tb_body($file)		TrackBack Ping ���ٹ��Խ�
 * tb_sort_by_date_d($p1, $p2)	�ǡ��������ս�ʹ߽�ˤ�����
 * tb_id2page($id)		TrackBack ID ����ڡ���̾����
 * tb_http($url, $method="GET", $headers="", $post=array(""))
 *				GET, POST, HEAD �ʤɤλ����������� �쥹�ݥ󥹥����ɼ���
 * tb_PutID($page)		ʸ����� trackback:ping �������ि��Υǡ��������� // pukiwiki.php
 * tb_GetID($url)		ʸ��� GET ���������ޤ줿 TrackBack ID �����
 * tb_xml_GetId($data)		�����ޤ줿�ǡ������� TrackBack ID �����
 * tb_startElementHandler_GetId($parser, $name, $attribs)
 *				xml_set_element_handler�ؿ��ǥ��åȤ��� startElementHandler
 * tb_xg_dummy($parser, $name)	xml_set_element_handler�ؿ��ǥ��åȤ��� EndElementHandler
 * == Referer �б�ʬ ==
 * ref_save($page)		Referer �ǡ�����¸(����)
 * ref_put($url,$file,$data)	Referer �ǡ�������
 * get_referer($local=FALSE)	Referer �ѿ����᤹
 *
 */

if (!defined('TRACKBACK_DIR')) {
  define('TRACKBACK_DIR','./trackback/');
}

// TrackBack Ping �ǡ����Ŀ�����
// ��ĥ�� .ref �ξ��ϡ�Referer �Ŀ�
function tb_count($page, $ext=".txt") {

  $page_enc = md5($page);
  $file = TRACKBACK_DIR.$page_enc.$ext;

  // TRACKBACK_DIR ��¸�ߤȽ񤭹��߲�ǽ���γ�ǧ
  if (file_exists($file) === false) {
    return 0;
  }
  return count( file($file) );
}

// TrackBack Ping ����
function tb_send($page,$data) {
  global $script, $trackback;

  if (!$trackback) return;
  set_time_limit( 0 ); // �����¹Ի�������(php.ini ���ץ���� max_execution_time )

  $link = tb_ScanLink($data);
  if (!is_array($link)) return; // ���̵���Ͻ�λ
  $r_page = rawurlencode($page);

  // ��ʸ��ξ���
  $putdata = array();
  $putdata["title"] = $page; // �����ȥ�ϥڡ���̾
  $putdata["url"] = $script."?".$r_page; // �������˺��١�rawurlencode �����
  $putdata["excerpt"] = tb_PageInfo($page);
  $putdata["blog_name"] = "PukiWiki/TrackBack 0.1";
  $putdata["charset"] = SOURCE_ENCODING; // ����¦ʸ��������(̤����)

  foreach ($link as $x) {
  // URL ���� TrackBack ID ���������
    $tbid = tb_GetID($x);
    if (empty($tbid)) continue; // TrackBack ���б����Ƥ��ʤ�
    list($resp,$header,$data,$query) = tb_http($tbid,"POST","",$putdata);
    // FIXME: ���顼������ԤäƤ⡢���㡢�ɤ����롩�����ʤ�...
  }

}

// convert_html() �Ѵ���̤� <a> �������� URL ���
function tb_ScanLink($data) {
  $link = array();
  $string = convert_html($data);

  // �롼��
  while (preg_match("'(href=)(\"|\')(http:?[^\"^\'^\>]+)'si", $string, $regs)) {
    $link[] = $regs[3];
    $string = str_replace($regs[3], "", $string);
  }
  return $link;
}

// �ڡ����������
function tb_PageInfo($page) {
  // ���ѥڡ����ΰ����ʤ�
  if (empty($page)) return "";

  // ���פ�����
  $excerpt = '';
  $ctr_len = 0;
  $body = get_source($page);
  foreach ($body as $x) {
    if ($x[0] == '/' && $x[1] == '/') continue; // PukiWiki �Ȥ��Ƥϡ������ȹ�
    $excerpt .= trim($x);
    $ctr_len += strlen(trim($x));
    if ($ctr_len > 255) break; // 255 ��Ķ�ᤷ�������ǽ�λ
  }
  return $excerpt;
}

// XML ��̽���
function tb_xml_msg($rc,$msg) {
  $x = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n".
    "<response>\n".
    "<error>".$rc."</error>\n";
  if (!empty($msg)) $x .= "<message>".$msg."</message>\n";
  $x .= "</response>\n";
  header('Content-Type: text/xml');
  print $x;
  die();
}

// TrackBack Ping �ǡ�����¸(����)
function tb_save() {
  global $script,$vars,$post;

  // TrackBack Ping �ˤ����� URL �ѥ�᡼����ɬ�ܤǤ��롣
  if (empty($post["url"])) {
    tb_xml_msg(1,"It is an indispensable parameter. URL is not set up.");
  }

  // URL �����������å� (����������Ƚ������֤����꤬�Ǥ�)
  list($res,$hed,$dat,$query) = tb_http($post["url"],"HEAD");
  if ($res !== 200) {
    tb_xml_msg(1,"URL is fictitious.");
  }

  // TRACKBACK_DIR ��¸�ߤȽ񤭹��߲�ǽ���γ�ǧ
  if (file_exists(TRACKBACK_DIR) === false) {
    die(TRACKBACK_DIR.": No such directory");
  }
  if (is_writable(TRACKBACK_DIR) === false) {
    die(TRACKBACK_DIR.": Permission denied");
  }

  // Query String ������
  if (empty($vars["tb_id"])) {
    tb_xml_msg(1,"TrackBack Ping URL is inaccurate.");
  }

  // �ڡ���¸�ߥ����å�
  $page = tb_id2page($vars["tb_id"]);
  if ($page == $vars["tb_id"]) {
    tb_xml_msg(1,"TrackBack ID is invalid.");
  }

  // TrackBack Ping �Υǡ������ɤ߹���
  $rc = tb_put(TRACKBACK_DIR.$vars["tb_id"].".txt",tb_get(TRACKBACK_DIR.$vars["tb_id"].".txt"));

  tb_xml_msg($rc,"");
}

// TrackBack Ping �ǡ������
function tb_delete($page) { @unlink(TRACKBACK_DIR.md5($page).".txt"); }

// TrackBack Ping �ǡ�������
function tb_get($file) {
  if (!file_exists($file)) {
    return false;
  }

  $rc    = array();
  $ctr   = 0;

  $fp = @fopen ($file,"r");
  while ($data = @fgetcsv($fp, 8192, ",")) {
    $rc[$ctr++] = $data;
  }
  @fclose ($fp);
  return $rc;
}

// TrackBack Ping �ǡ�������
function tb_put($file,$data) {
  global $script,$vars,$post;

  // ʸ���������Ѵ�
  if (empty($post["charset"])) $post["charset"] = "auto";
  $post["title"]     = mb_convert_encoding($post["title"],SOURCE_ENCODING,$post["charset"]);
  $post["excerpt"]   = mb_convert_encoding($post["excerpt"],SOURCE_ENCODING,$post["charset"]);
  $post["blog_name"] = mb_convert_encoding($post["blog_name"],SOURCE_ENCODING,$post["charset"]);

  if (!($fp = fopen($file,"w"))) return 1;
  @flock($fp, LOCK_EX);

  // ����ޤ����äƤ��ɤ��褦�ˡ�(�ʤ󤫰㤦�Ȼפ��ʤ�)
  $post["url"]       = rawurlencode($post["url"]);
  $post["title"]     = rawurlencode($post["title"]);
  $post["excerpt"]   = rawurlencode($post["excerpt"]);
  $post["blog_name"] = rawurlencode($post["blog_name"]);

  $sw_put = 0; // ������
  if ($data !== false) {
    foreach ($data as $x) {
      if ($x[1] == $post["url"]) {
        $sw_put = 1;
        $x[0] = UTIME;
        $x[2] = $post["title"];
        $x[3] = $post["excerpt"];
        $x[4] = $post["blog_name"];
      }
      // UTIME, url, title, excerpt, blog_name
      fwrite($fp, $x[0].",".$x[1].",".$x[2].",".$x[3].",".$x[4]."\n");
    }
  }

  // �������Ƥ��ʤ����ϡ������ɲä���
  if (!$sw_put) {
    fwrite($fp, UTIME.",".$post["url"].",".$post["title"].",".$post["excerpt"].",".$post["blog_name"]."\n");
  }

  @flock($fp, LOCK_UN);
  @fclose($fp);

  return 0;
}

// ?__mode=rss ����
function tb_mode_rss($file) {

  $data = tb_get($file);
  // ɽ���ǡ����ʤ�
  if ($data === false) {
    tb_xml_msg(1,"TrackBack Ping data does not exist.");
  }

  $rc = <<<EOD
<?xml version="1.0" encoding="utf-8" ?>
<response>
<error>0</error>
<rss version="0.91">
<channel>

EOD;

  $sw_item = 0;
  foreach ($data as $x) {
    if ($sw_item) $rc .= "<item>\n";
    $x[1] = rawurldecode($x[1]);
    $x[2] = rawurldecode($x[2]);
    $x[3] = rawurldecode($x[3]);
    // UTIME, url, title, excerpt, blog_name
    $rc .= <<<EOD
<title>$x[2]</title>
<link>$x[1]</link>
<description>$x[3]</description>

EOD;
    if ($sw_item) {
      $rc .= "</item>\n";
    } else {
      $rc .= "<language>ja-Jp</language>\n";
      $sw_item = 1;
    }
  }

  $rc .= <<<EOD
</channel>
</rss>
</response>

EOD;

  $rc = mb_convert_encoding($rc,"utf-8",SOURCE_ENCODING);
  header('Content-Type: text/xml');
  echo $rc;
}

// ?__mode=view ����
function tb_mode_view($tbid) {
  global $script, $page_title;
  global $_tb_title, $_tb_header, $_tb_entry, $_tb_refer;

  // TrackBack ID ����ڡ���̾�����
  $page   = tb_id2page($tbid);
  $r_page = rawurlencode($page);
  $file   = TRACKBACK_DIR.$tbid.".txt";

  $tb_title = sprintf($_tb_title,$page);
  $tb_refer = sprintf($_tb_refer,"<a href=\"$script?$r_page\">'$page'</a>","<a href=\"$script\">$page_title</a>");

  $msg = <<<EOD
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
 <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
<title>$tb_title</title>
<link rel="stylesheet" href="skin/trackback.css" type="text/css" />
</head>
<body>
<div id="banner-commentspop">$_tb_header</div>
<div class="blog">

<div class="trackback-url">
$_tb_entry<br />
$script?plugin=tb&tb_id=$tbid <br /><br />
$tb_refer
</div>
EOD;

  $msg .= tb_body($file);
  $msg .= <<<EOD
</div>
</body>
</html>
EOD;

  $msg = mb_convert_encoding($msg,"UTF-8",SOURCE_ENCODING);
  echo $msg;
  die();
}

// TrackBack Ping ���ٹ��Խ�
function tb_body($file) {
  global $_tb_header_Excerpt, $_tb_header_Weblog, $_tb_header_Tracked, $_tb_date;

  $data = tb_get($file);
  if ($data === false) return;

  // �ǿ��Ǥ�������
  usort($data, 'tb_sort_by_date_d');

  $rc = "";
  foreach ($data as $x) {
    // UTIME, url, title, excerpt, blog_name
    $x[0] = date($_tb_date, $x[0]+LOCALZONE); // May  2, 2003 11:25 AM
    $x[1] = rawurldecode($x[1]); // URL
    $x[2] = rawurldecode($x[2]); // title
    $x[3] = rawurldecode($x[3]); // excerpt
    $x[4] = rawurldecode($x[4]); // blog_name
    $rc .= <<<EOD
<div class="trackback-body">
<span class="trackback-post"><a href="$x[1]" target="new">$x[2]</a><br />
<strong>$_tb_header_Excerpt</strong> $x[3]<br />
<strong>$_tb_header_Weblog</strong> $x[4]<br />
<strong>$_tb_header_Tracked</strong> $x[0]</span>
</div>
EOD;
  }

  return $rc;
}

// �ǡ��������ս�ʹ߽�ˤ�����
function tb_sort_by_date_d($p1, $p2) {
  return ($p2['0'] - $p1['0']);
}

// TrackBack ID ����ڡ���̾����
function tb_id2page($id) {
  global $tb_pages;

  if (!is_array($tb_pages)) {
    $tb_pages = get_existpages();
    natcasesort($tb_pages);
  }

  foreach ($tb_pages as $x) {
    if ($id == md5(rawurlencode($x))) return rawurldecode($x);
  }
  return $id; // ���Ĥ���ʤ����

}

/*
 * $url     : http://����Ϥޤ�URL( http://user:pass@host:port/path?query )
 * $method  : GET, POST, HEAD�Τ����줫(�ǥե���Ȥ�GET)
 * $headers : Ǥ�դ��ɲåإå�
 * $post    : POST�λ�����������ǡ������Ǽ��������("�ѿ�̾"=>"��")
 */
function tb_http($url, $method="GET", $headers="", $post=array(""))
{
  $rc = array();
  $url_arry = parse_url($url);

  // query
  if (isset($url_arry['query'])) {
    $url_arry['query'] = "?".$url_arry['query'];
  } else {
    $url_arry['query'] = "";
  }
  // fragment
  if (isset($url_arry['fragment'])) {
    $url_arry['fragment'] = "#".$url_arry['fragment'];
  } else {
    $url_arry['fragment'] = "";
  }

  if (!isset($url_arry['port'])) $url_arry['port'] = 80;

  $query = $method." ".
    $url_arry['path'].$url_arry['query'].$url_arry['fragment'].
    " HTTP/1.0\r\n";
  $query .= "Host: ".$url_arry['host']."\r\n";
  $query .= "User-Agent: PukiWiki/TrackBack 0.1\r\n";

  // Basic ǧ����
  if (isset($url_arry['user']) && isset($url_arry['pass'])) {
    $query .= "Authorization: Basic ".
      base64_encode($url_arry['user'].":".$url_arry['pass'])."\r\n";
  }

  $query .= $headers;

  // POST ���ϡ�urlencode �����ǡ����Ȥ���
  if (strtoupper($method) == "POST") {
    while (list($name, $val) = each($post)) {
      $POST[] = $name."=".urlencode($val);
    }
    $data = implode("&", $POST);
    $query .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $query .= "Content-Length: ".strlen($data)."\r\n";
    $query .= "\r\n";
    $query .= $data;
  } else {
    $query .= "\r\n";
  }

  $fp = fsockopen($url_arry['host'], $url_arry['port'], $errno, $errstr, 30);
  if (!$fp) {
    if ($errno == 0) {
      $rc[0] = 406; // Not Acceptable
      $rc[1] = ""; // Header
      $rc[2] = ""; // Data
      $rc[3] = $query; // Query String
      return $rc;
    }
    // Proxy ��ͳ�ξ��ϡ����Ԥ���errno �� 0 �Ȥʤ롣
    // Warning: fsockopen() [http://www.php.net/function.fsockopen]:
    // php_network_getaddresses: gethostbyname failed in C:\var\www\html\pukiwiki\trackback.php on line 457
    // Warning: fsockopen() [http://www.php.net/function.fsockopen]:
    // unable to connect to xxxx.xx.xx:80 in C:\var\www\html\pukiwiki\trackback.php on line 457
    // ����������������λ���ޤ�����
    // (0)
    $rc[0] = $errno; // ���顼�ֹ�
    $rc[1] = ""; // Header
    $rc[2] = $errstr; // ���顼��å�����
    $rc[3] = $query; // Query String
    return $rc;
    // die("trackback.php: $errstr ($errno)\n");
  }

  fputs($fp, $query);

  $response = "";
  while (!feof($fp)) {
    $response .= fgets($fp, 4096);
  }

  fclose($fp);
  $resp  = split("\r\n\r\n", $response, 2);
  $rccd  = strtok($resp[0]," ");
  $rc[0] = strtok(" "); // Response Code
  $rc[0] = $rc[0] * 1; // ���������Ѵ�
  $rc[1] = $resp[0]; // Header
  $rc[2] = $resp[1]; // Data
  $rc[3] = $query; // Query String
  return $rc;
}

// ʸ����� trackback:ping �������ि��Υǡ���������
function tb_PutId($page) {
  global $script,$trackback;

  if (!$trackback) return "";

  $r_page = rawurlencode($page);
  $page_enc = md5($r_page);
  // $dcdate = substr_replace(get_date('Y-m-d\TH:i:sO',$time),':',-2,0);
  // dc:date="$dcdate"

  $rc = <<<EOD
<!--
<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
         xmlns:dc="http://purl.org/dc/elements/1.1/"
         xmlns:trackback="http://madskills.com/public/xml/rss/module/trackback/">
<rdf:Description
    rdf:about="$script?$r_page"
    dc:identifier="$script?$r_page"
    dc:title="$page"
    trackback:ping="$script?plugin=tb&amp;tb_id=$page_enc" />
</rdf:RDF>
-->
EOD;
  return $rc;
}

// ʸ��� GET ���������ޤ줿 TrackBack ID �����
function tb_GetID($url) {
  global $tb_get_url, $tb_get_id;

  $tb_get_url = $url;
  $tb_get_id  = "";

  // 0: Response Code, 1:Header, 2:Data, 3:Query String
  $data = tb_http($url);
  if ($data[0] !== 200) return "";

  // �롼��
  while (preg_match("'(<rdf:RDF .*?>)(.*?)(</rdf:RDF>)'si",$data[2],$regs)) {
    tb_xml_GetId($regs[1].$regs[2].$regs[3]);
    if (!empty($tb_get_id)) return $tb_get_id;
    $data[2] = str_replace($regs[1].$regs[2].$regs[3], "", $data[2]);
  }
  return "";
}

// �����ޤ줿�ǡ������� TrackBack ID �����
function tb_xml_GetId($data) {
  // XML �ѡ������������
  $xml_parser = xml_parser_create();
  if (!$xml_parser) return;

  // start ����� end ���ǤΥϥ�ɥ�����ꤹ��
  xml_set_element_handler($xml_parser, "tb_startElementHandler_GetId", "tb_xg_dummy");
  xml_parse($xml_parser, $data, 0);
  xml_parser_free($xml_parser);
  return;
}

// xml_set_element_handler�ؿ��ǥ��åȤ��� startElementHandler
function tb_startElementHandler_GetId($parser, $name, $attribs) {
  global $tb_get_url, $tb_get_id;

  if ($name !== "RDF:DESCRIPTION") return;

  $tbid = $tburl = $tbabout = "";
  foreach ($attribs as $key=>$value) {
    // print "KEY=".$key." VAL=".$value."\n";
    if ($key == "RDF:ABOUT") {
      $tbabout = $value;
      continue;
    }
    if ($key == "DC:IDENTIFER" || $key == "DC:IDENTIFIER") {
      $tburl = $value;
      continue;
    }
    if ($key == "TRACKBACK:PING") {
      $tbid = $value;
      continue;
    }
  }

  // print "URL:".$tb_get_url."=".$tburl."\n";
  // print "TBID:".$tbid."\n";
  if ($tb_get_url == $tburl || $tb_get_url == $tbabout) $tb_get_id = $tbid;
}

// xml_set_element_handler�ؿ��ǥ��åȤ��� EndElementHandler
function tb_xg_dummy($parser, $name) { return; }

// Referer �ǡ�����¸(����)
function ref_save($page) {
  global $referer;

  if (!$referer) return;
  $url = get_referer();
  if (empty($url)) return;

  // URI ��������ɾ��
  $url_arry = parse_url($url);
  if (!isset($url_arry['host'])) return;

  // TRACKBACK_DIR ��¸�ߤȽ񤭹��߲�ǽ���γ�ǧ
  if (file_exists(TRACKBACK_DIR) === false) {
    die(TRACKBACK_DIR.": No such directory");
  }
  if (is_writable(TRACKBACK_DIR) === false) {
    die(TRACKBACK_DIR.": Permission denied");
  }

  $filename = TRACKBACK_DIR.md5(rawurlencode($page)).".ref";
  // Referer �Υǡ������ɤ߹���
  $rc = ref_put($url,$filename,tb_get($filename));
  return;
}

// Referer �ǡ�������
function ref_put($url,$file,$data) {

  if (!($fp = fopen($file,"w"))) return 1;
  @flock($fp, LOCK_EX);

  // ����ޤ����äƤ��ɤ��褦�ˡ�(�ʤ󤫰㤦�Ȼפ��ʤ�)
  $url = rawurlencode($url);

  $sw_put = 0; // ������
  if ($data !== false) {
    foreach ($data as $x) {
      if (!$x[4]) continue; // ���Ѳ��ݥե饰�� ����ξ��ϡ��ǡ�����ΤƤ�
      // Referer �إå������פ�����ϡ��ǡ����򹹿�����
      if ($x[3] == $url) {
        $sw_put = 1;
	$x[0] = UTIME; // �ǽ���������
	$x[2]++;       // ���ȥ�����
      }
      // 0:�ǽ���������, 1:�����Ͽ����, 2:���ȥ�����, 3:Referer �إå�, 4:���Ѳ��ݥե饰(1��ͭ��)
      fwrite($fp, $x[0].",".$x[1].",".$x[2].",".$x[3].",".$x[4]."\n");
    }
  }

  // �������Ƥ��ʤ����ϡ������ɲä���
  if (!$sw_put) {
    // 0:�ǽ���������, 1:�����Ͽ����, 2:���ȥ�����, 3:Referer �إå�, 4:���Ѳ��ݥե饰(1��ͭ��)
    fwrite($fp, UTIME.",".UTIME.",1,".$url.",1\n");
  }

  @flock($fp, LOCK_UN);
  @fclose($fp);

  return 0;
}

// Referer �ѿ����᤹
function get_referer($local=FALSE)
{
	$HTTP_REFERER = $_SERVER['HTTP_REFERER'];
	// �������Ȥ�ͭ���ξ��ϡ����Τޤ��᤹
	if ($local)
	{
		return $HTTP_REFERER;
	}
	$HTTP_HOST = 'http://'.$_SERVER['HTTP_HOST'];
	// ����������ξ��ϡ��õ�
	if (strpos($HTTP_REFERER,$HTTP_HOST) === 0)
	{
		$HTTP_REFERER = '';
	}
	return $HTTP_REFERER;
}
?>
