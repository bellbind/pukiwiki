<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: rss.inc.php,v 1.4 2003/06/09 07:56:58 arino Exp $
//
// RecentChanges �� RSS �����
function plugin_rss_action()
{
	global $rss_max,$page_title,$whatsnew;

	$self = 'http://'.SERVER_NAME.PHP_SELF.'?';
	
	$page_title_utf8 = mb_convert_encoding($page_title,'UTF-8',SOURCE_ENCODING);
	
	$items = '';
	
	if (!file_exists(CACHE_DIR.'recent.dat'))
	{
		return '';
	}
	$recent = file(CACHE_DIR.'recent.dat');
	$lines = array_splice($recent,0,$rss_max);
	foreach ($lines as $line)
	{
		list($time,$page) = explode("\t",rtrim($line));
		$r_page = rawurlencode($page);
		$title = mb_convert_encoding($page,'UTF-8',SOURCE_ENCODING);
		$desc = get_date('D, d M Y H:i:s T',$time);
		$items .= <<<EOD
<item>
 <title>$title</title>
 <link>$self$r_page</link>
 <description>$desc</description>
</item>

EOD;
	}
	
	header('Content-type: application/xml');
	
	print <<<EOD
<?xml version="1.0" encoding="UTF-8"?>

<!DOCTYPE rss PUBLIC "-//Netscape Communications//DTD RSS 0.91//EN"
            "http://my.netscape.com/publish/formats/rss-0.91.dtd">

<rss version="0.91">

<channel>
<title>$page_title_utf8</title>
<link>$self$whatsnew</link>
<description>PukiWiki RecentChanges</description>
<language>ja</language>

$items
</channel>
</rss>
EOD;
	exit;
}
?>