<?php
// PukiWiki - Yet another WikiWikiWeb clone
// $Id: spam.inc.php,v 1.6 2007/07/29 14:25:39 henoheno Exp $
// Copyright (C) 2003-2005, 2007 PukiWiki Developers Team
// License: GPL v2 or (at your option) any later version
//
// lib/spam.php related maintenance tools

function plugin_spam_init(){}

// Menu and dispatch
function plugin_spam_action()
{
	global $vars;

	if (PKWK_READONLY) die_message('PKWK_READONLY prohibits this');

	// Dispatch
	$mode = isset($vars['mode']) ? $vars['mode'] : '';
	if ($mode == 'pages') {
		return plugin_spam_pages();
	}
	// TODO:
	// Checking own backup/*.gz, backup/*.txt for determine the clearance
	// Check text
	// Check attach

	$msg    = 'Spam tools: Menu';
	$script = get_script_uri() . '?plugin=spam';
	$body   = 'Choose one: ' . "\n" .
		'<a href="'. $script . '&mode=pages' . '">Pages</a>' . "\n"
		;
	return array('msg'=>$msg, 'body'=>nl2br($body));
}

// mode=pages: Check existing pages
function plugin_spam_pages()
{
	require_once(LIB_DIR . 'spam.php');
	require_once(LIB_DIR . 'spam_pickup.php');

	global $vars, $post, $_msg_invalidpass;

	$script  = get_script_uri() . '?plugin=spam&mode=pages';
	$start   = isset($post['start']) ? $post['start'] : NULL;
	$s_start = ($start === NULL) ? '' : htmlspecialchars($start);
	$form    = <<<EOD
<p>Checking existing pages (badhost only)</p>
<form action="$script" method="post">
 <div>
  Start from: <input type="start" name="start" size="40" value="$s_start" /><br/>
  Pass: <input type="password" name="pass"  size="12" /><br/>
  <input type="submit"   name="ok"   value="check" />
 </div>
</form>
EOD;

	$pass = isset($post['pass']) ? $post['pass'] : NULL;
	if ($pass !== NULL && pkwk_login($pass)) {
		// Check and report

		$method = array(
			'_comment'     => '_default',
			//'quantity'     =>  8,
			//'non_uniquri'  =>  3,
			//'non_uniqhost' =>  3,
			//'area_anchor'  =>  0,
			//'area_bbcode'  =>  0,
			//'uniqhost'     => TRUE,
			'badhost'      => TRUE,
			//'asap'         => TRUE, // Stop as soon as possible (quick but less-info)
		);

		echo $form;

		$pages = get_existpages();
		sort($pages, SORT_STRING);

		$ob    = ob_get_level();
		$count = 0;
		foreach($pages as $pagename)
		{
			++$count;
			if ($count % 50 == 0) {
				flush();
				if ($ob) @ob_flush();
			}
			if ($start !== '') {
				if ($start == $pagename) {
					$start = '';
				} else {
					continue;
				}
			}

			$progress = check_uri_spam(get_source($pagename, TRUE, TRUE), $method);
			if (empty($progress['is_spam'])) {
				echo htmlspecialchars($pagename);
				echo '<br/>' . "\n";
			} else {
				echo '<font color="red"><strong>' . htmlspecialchars($pagename) . '</strong></font>';
				echo ':<br/>' . "\n";
				$tmp = summarize_detail_badhost($progress);
				if ($tmp != '') {
					echo '&nbsp; DETAIL_BADHOST: ' . 
						str_replace('  ', '&nbsp; ', nl2br(htmlspecialchars($tmp). "\n"));
				}
			}
		}
		echo '<br/>' . "\n";
		echo '----' . '<br/>' . "\n";
		echo $count . ' pages';

		exit;
	}

	$msg   = 'Spam tools: Pages';
	$body  = ($pass === NULL) ? '' : "<p><strong>$_msg_invalidpass</strong></p>\n";
	$body .= $form;
	return array('msg'=>$msg, 'body'=>$body);
}

?>
