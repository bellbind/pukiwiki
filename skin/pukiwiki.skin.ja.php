<?php header('Content-Type: text/html; charset=EUC-JP') ?>
<?php echo '<?xml version="1.0" encoding="EUC-JP"?>' ?>

<?php global $html_transitional; if ($html_transitional) { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<?php } else { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja">
<?php } ?>
<head>
 <meta http-equiv="content-type" content="application/xhtml+xml; charset=EUC-JP" />
 <meta http-equiv="content-style-type" content="text/css" />

<?php if (!$is_read) { ?>
 <meta name="robots" content="NOINDEX,NOFOLLOW" />
<?php } ?>

 <title><?php global $page_title; echo "$page_title - $title" ?></title>
 <link rel="stylesheet" href="skin/default.ja.css" type="text/css" media="screen" charset="Shift_JIS" />
</head>
<body>

<div id="header">
 <a href="<?php global $modifierlink; echo $modifierlink ?>"><img id="logo" src="pukiwiki.png" width="80" height="80" alt="[PukiWiki]" /></a>
 <h1 class="title"><?php echo $page ?></h1>

<?php if ($is_page) { ?>
 <a href="<?php echo "$script?$r_page" ?>"><span class="small"><?php echo "$script?$r_page" ?></span></a>
<?php } ?>

</div>


<div id="navigator">

<?php if ($is_page) { ?>
 [ <a href="<?php echo "$script?$r_page" ?>">�����</a> ]
 &nbsp;
 [ <a href="<?php echo "$script?plugin=newpage" ?>">����</a>
 | <a href="<?php echo $link_edit ?>">�Խ�</a>

<?php   if ($is_read and $function_freeze) { ?>
<?php     if ($is_freeze) { ?>
 | <a href="<?php echo $link_unfreeze ?>">�����</a>
<?php     } else { ?>
 | <a href="<?php echo $link_freeze ?>">���</a>
<?php     } ?>
<?php   } ?>

 | <a href="<?php echo $link_diff ?>">��ʬ</a>

<?php   if ((bool)ini_get('file_uploads')) { ?>
 | <a href="<?php echo $link_upload ?>">ź��</a>
<?php   } ?>

 ]
 &nbsp;
<?php } ?>

 [ <a href="<?php echo $link_top ?>">�ȥå�</a>
 | <a href="<?php echo $link_list ?>">����</a>

<?php if (arg_check('list')) { ?>
 | <a href="<?php echo $link_filelist ?>">�ե�����̾����</a>
<?php } ?>

 | <a href="<?php echo $link_search ?>">ñ�측��</a>
 | <a href="<?php echo $link_whatsnew ?>">�ǽ�����</a>

<?php global $do_backup; if ($do_backup) { ?>
 | <a href="<?php echo $link_backup ?>">�Хå����å�</a>
<?php } ?>

 | <a href="<?php echo $link_help ?>">�إ��</a>
 ]
<?php echo $hr ?>
</div>


<?php if (arg_check('read') and is_page('MenuBar')) { ?>
<table border="0" width="100%">
<tr><td valign="top" style="width:120px;word-break:break-all;padding:4px;">
<div id="menubar"><?php echo preg_replace('/<ul[^>]*>/','<ul>',convert_html(get_source('MenuBar'))) ?></div>
</td><td valign="top" style="padding-left:10px;">
<div><?php echo $body ?></div>
</td></tr>
</table>
<?php } else { ?>
<div id="body"><?php echo $body ?></div>
<?php } ?>


<?php if ($notes) { ?>
<div id="note">
<?php echo $notes ?>
</div>
<?php } ?>


<?php if ($attaches) { ?>
<div id="attach">
<?php echo $hr ?>
<?php echo $attaches ?>
</div>
<?php } ?>


<div id="toolbar">

<?php echo $hr ?>

<?php if ($is_page) { ?>
 <a href="<?php echo "$script?$r_page" ?>"><img src="./image/reload.png" width="20" height="20" alt="�����" /></a>
 &nbsp;
 <a href="<?php echo $script ?>?plugin=newpage"><img src="./image/new.png" width="20" height="20" alt="����" /></a>
 <a href="<?php echo $link_edit ?>"><img src="./image/edit.png" width="20" height="20" alt="�Խ�" /></a>
 <a href="<?php echo $link_diff ?>"><img src="./image/diff.png" width="20" height="20" alt="��ʬ" /></a>
<?php   if ((bool)ini_get('file_uploads')) { ?>
 <a href="<?php echo $link_upload ?>"><img src="./image/file.png" width="20" height="20" alt="ź��" /></a>
<?php   } ?>
 &nbsp;
<?php } ?>

 <a href="<?php echo $link_top ?>"><img src="./image/top.png" width="20" height="20" alt="�ȥå�" /></a>
 <a href="<?php echo $link_list ?>"><img src="./image/list.png" width="20" height="20" alt="����" /></a>
 <a href="<?php echo $link_search ?>"><img src="./image/search.png" width="20" height="20" alt="����" /></a>
 <a href="<?php echo $link_whatsnew ?>"><img src="./image/recentchanges.png" width="20" height="20" alt="�ǽ�����" /></a>

<?php if ($do_backup) { ?>
 <a href="<?php echo $link_backup ?>"><img src="./image/backup.png" width="20" height="20" alt="�Хå����å�" /></a>
<?php } ?>

 &nbsp;
 <a href="<?php echo $link_help ?>"><img src="./image/help.png" width="20" height="20" alt="�إ��" /></a>
 &nbsp;
 <a href="<?php echo $link_rss ?>"><img src="./image/rss.png" width="36" height="14" alt="�ǽ�������RSS" /></a>
</div>


<?php if ($lastmodified) { ?>
<div id="lastmodified">
 Last-modified: <?php echo $lastmodified ?>
</div>
<?php } ?>


<?php if ($related) { ?>
<div id="related">
 Link: <?php echo $related ?>
</div>
<?php } ?>


<div id="footer">
 Modified by <a href="<?php global $modifierlink; echo $modifierlink ?>"><?php global $modifier; echo $modifier ?></a>
 <br /><br />
 <?php echo S_COPYRIGHT ?>
 <br />
 Powered by PHP <?php echo PHP_VERSION ?>
 <br /><br />
 HTML convert time to <?php echo $taketime ?> sec.
</div>

</body>
</html>
