<?php global $page_title; header("Content-Type: text/html; charset=euc-jp") ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=euc-jp">
	<meta http-equiv="content-style-type" content="text/css">
	<meta http-equiv="content-script-type" content="text/javascript">
<?php if (! ( ($vars['cmd']==''||$vars['cmd']=='read') && $is_page) ) { ?>
	<meta name="robots" content="noindex,nofollow" />
<?php } ?>

	<title><?php echo $page_title ?> - <?php echo $title?></title>
	<link rel="stylesheet" href="skin/default.ja.css" type="text/css" media="screen" charset="shift_jis">
	<script language=javascript src="skin/default.js"></script>
</head>
<body>
<div>
	<table border="0">
		<tr>
		<td rowspan="2">
			<a href="http://pukiwiki.org/"><img src="pukiwiki.png" width="80" height="80" border="0" alt="[PukiWiki]" /></a><br />
		</td>
		<td rowspan="2" style="width:20px">
		</td>
		<td valign="bottom">
			<strong style="font-size:30px"><?php echo $page ?></strong><br />
		</td></tr>
		<tr><td valign="top">
			<?php if($is_page) { ?>
			<a style="font-size:8px" href="<?php echo $script ?>?<?php echo rawurlencode($vars["page"]) ?>"><?php echo $script ?>?<?php echo rawurlencode($vars["page"]) ?></a><br />
			<?php } ?>
		</td></tr>
	</table>
	<br />
	<?php if($is_page) { ?>
		[ <a href="<?php echo "$script?".rawurlencode($vars[page]) ?>">�����</a> ]
		&nbsp;
		[ <a href="<?php echo $script ?>?plugin=newpage">����</a>
		| <a href="<?php echo $link_edit ?>">�Խ�</a>
		| <a href="<?php echo $link_diff ?>">��ʬ</a>
		| <a href="<?php echo $script ?>?plugin=attach&amp;pcmd=upload&amp;page=<?php echo rawurlencode($vars[page]) ?>">ź��</a>
		]
		&nbsp;
	<?php } ?>
	[ <a href="<?php echo $link_top ?>">�ȥå�</a>
	| <a href="<?php echo $link_list ?>">����</a>
	<?php if(arg_check("list")) { ?>
		| <a href="<?php echo $link_filelist ?>">�ե�����̾����</a>
	<?php } ?>
	| <a href="<?php echo $link_search ?>">ñ�측��</a>
	| <a href="<?php echo $link_whatsnew ?>">�ǽ�����</a>
	<?php if($do_backup) { ?>
		| <a href="<?php echo $link_backup ?>">�Хå����å�</a>
	<?php } ?>
	| <a href="<?php echo "$script?".rawurlencode("[[�إ��]]") ?>">�إ��</a>
	]<br />
	<?php echo $hr ?>
	<?php if($is_page) { ?>
		<table cellspacing="1" cellpadding="0" border="0" width="100%">
			<tr>
			<td valign="top" style="width:120px;word-break:break-all;">
				<?php echo convert_html(@join("",@file(get_filename(encode("MenuBar"))))) ?>
			</td>
			<td style="width:10px">
			</td>
			<td valign="top">
	<?php } ?>
	<?php echo $body ?>
	<?php if($is_page) { ?>
			</td>
			</tr>
		</table>
	<?php } ?>
	<?php echo $hr ?>
	<?php
		if(file_exists(PLUGIN_DIR."attach.inc.php") && $is_read)
		{
			require_once(PLUGIN_DIR."attach.inc.php");
			$attaches = attach_filelist();
			if($attaches)
			{
				print $attaches;
				print $hr;
			}
		}
	?>
	<div style="text-align:right">
		<?php if($is_page) { ?>
			<a href="<?php echo "$script?".rawurlencode($vars[page]) ?>"><img src="./image/reload.gif" width="20" height="20" border="0" alt="�����" /></a>
			&nbsp;
			<a href="<?php echo $script ?>?plugin=newpage"><img src="./image/new.gif" width="20" height="20" border="0" alt="����" /></a>
			<a href="<?php echo $link_edit ?>"><img src="./image/edit.gif" width="20" height="20" border="0" alt="�Խ�" /></a>
			<a href="<?php echo $link_diff ?>"><img src="./image/diff.gif" width="20" height="20" border="0" alt="��ʬ" /></a>
			&nbsp;
		<?php } ?>
		<a href="<?php echo $link_top ?>"><img src="./image/top.gif" width="20" height="20" border="0" alt="�ȥå�" /></a>
		<a href="<?php echo $link_list ?>"><img src="./image/list.gif" width="20" height="20" border="0" alt="����" /></a>
		<a href="<?php echo $link_search ?>"><img src="./image/search.gif" width="20" height="20" border="0" alt="����" /></a>
		<a href="<?php echo $link_whatsnew ?>"><img src="./image/recentchanges.gif" width="20" height="20" border="0" alt="�ǽ�����" /></a>
		<?php if($do_backup) { ?>
			<a href="<?php echo $link_backup ?>"><img src="./image/backup.gif" width="20" height="20" border="0" alt="�Хå����å�" /></a>
		<?php } ?>
		&nbsp;
		<a href="<?php echo "$script?".rawurlencode("[[�إ��]]") ?>"><img src="./image/help.gif" width="20" height="20" border="0" alt="�إ��" /></a>
		&nbsp;
		<a href="<?php echo $script ?>?cmd=rss"><img src="./image/rss.gif" width="36" height="14" border="0" alt="�ǽ�������RSS" /></a>
	</div>
	<?php if($fmt) { ?>
		 <span class="small">Last-modified: <?php echo date("D, d M Y H:i:s T",$fmt) ?></span> <?php echo get_pg_passage($vars["page"]) ?><br />
	<?php } ?>
	<?php if($related) { ?>
		 <span class="small">Link: <?php echo $related ?></span><br />
	<?php } ?>
	<br />
	<address>
		Modified by <a href="<?php echo $modifierlink ?>"><?php echo $modifier ?></a><br /><br />
		<?php echo S_COPYRIGHT ?><br />
		Powered by PHP <?php echo PHP_VERSION ?><br /><br />
		HTML convert time to <?php echo $taketime ?> sec.
	</address>
</div>
</body>
</html>
