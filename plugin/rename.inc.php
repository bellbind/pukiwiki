<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: rename.inc.php,v 1.3 2003/03/03 07:07:28 panda Exp $
//

/*
*�ץ饰���� rename
�ڡ�����̾�����Ѥ���

*Usage
 http:.../pukiwiki.php?plugin=rename(&refer=�ڡ���̾)

*�ѥ�᡼��
-&refer=�ڡ���̾~
 �ڡ��������

*/

define('RENAME_LOGPAGE','RenameLog');

function plugin_rename_init()
{
	$messages = array(
		'_rename_messages'=>array(
			'err' => '<p>���顼:%s</p>',
			'err_nomatch' => '�ޥå�����ڡ���������ޤ���',
			'err_notvalid' => '��͡����Υڡ���̾������������ޤ���',
			'err_adminpass' => '�����ԥѥ���ɤ�����������ޤ���',
			'err_notpage' => '%s�ϥڡ���̾�ǤϤ���ޤ���',
			'err_norename' => '%s���͡��ह�뤳�ȤϤǤ��ޤ���',
			'err_already' => '�ڡ��������Ǥ�¸�ߤ��ޤ���:%s',
			'err_already_below' => '�ʲ��Υե����뤬���Ǥ�¸�ߤ��ޤ���',
			'msg_title' => '�ڡ���̾���ѹ�',
			'msg_page' => '�ѹ����ڡ��������',
			'msg_regex' => '����ɽ�����ִ�',
			'msg_related' => '��Ϣ�ڡ���',
			'msg_do_related' => '��Ϣ�ڡ������͡��ह��',
			'msg_rename' => '%s��̾�����ѹ����ޤ���',
			'msg_oldname' => '���ߤ�̾��',
			'msg_newname' => '������̾��',
			'msg_adminpass' => '�����ԥѥ����',
			'msg_arrow' => '��',
			'msg_exist_none' => '���Υڡ�����������ʤ�',
			'msg_exist_overwrite' => '���Υե�������񤭤���',
			'msg_confirm' => '�ʲ��Υե�������͡��ष�ޤ���',
			'msg_result' => '�ʲ��Υե�������񤭤��ޤ�����',
			'btn_submit' => '�¹�',
			'btn_next' => '����'
		)
	);
	set_plugin_messages($messages);
}

function plugin_rename_action()
{
	global $vars,$adminpass,$whatsnew,$WikiName,$BracketName;
	global $_rename_messages;

	// XSS
	foreach (array('refer','page','src','dst','method','related') as $var)
	{
		$s_vars[$var] = array_key_exists($var,$vars) ? htmlspecialchars($vars[$var]) : '';
	}

	set_time_limit(60);

	$method = empty($vars['method']) ? '' : $vars['method'];
	if ($method == 'regex')
	{
		if ($vars['src'] == '')
		{
			return rename_phase1($s_vars);
		}
		$pages = get_existpages();
		$arr0 = preg_grep('/'.str_replace(array('(',')'),'',$vars['src']).'/',$pages);
		if (!is_array($arr0) or count($arr0) == 0)
		{
			return rename_phase1($s_vars,'nomatch');
		}
		$arr1 = preg_replace("/{$vars['src']}/",$vars['dst'],$arr0);
		$arr2 = preg_grep("/^($WikiName|$BracketName)$/",$arr1);
		if (count($arr2) != count($arr1))
		{
			return rename_phase1($s_vars,'notvalid');
		}
		return rename_regex($s_vars,$arr0,$arr1);
	}
	else // $vars['method'] = 'page'
	{
		if (!array_key_exists('refer',$vars) or $vars['refer'] == '')
		{
			return rename_phase1($s_vars);
		}
		if (!is_page($vars['refer']))
		{
			return rename_phase1($s_vars,'notpage',$s_vars['refer']);
		}
		if ($vars['refer'] == $whatsnew)
		{
			return rename_phase1($s_vars,'norename',$s_vars['refer']);
		}
		if ($vars['page'] == '' or $vars['page'] == $vars['refer'])
		{
			return rename_phase2($s_vars);
		}
		if (!preg_match("/^$WikiName|$BracketName$/",$vars['page']))
		{
			return rename_phase2($s_vars,'notvalid');
		}
		return rename_refer($s_vars);
	}
}
// ���顼��å���������
function rename_err($err,$page='')
{
	global $_rename_messages;
	
	if ($err == '')
	{
		return '';
	}
	$body = $_rename_messages["err_$err"];
	if (is_array($page))
	{
		$tmp = '';
		foreach ($page as $_page)
		{
			$tmp .= "<br />$_page";
		}
		$page = $tmp;
	}
	if ($page != '')
	{
		$body = sprintf($body,$page);
	}
	$msg = sprintf($_rename_messages['err'],$body);
	return $msg;
}
// From->To��å���������
function rename_arrow($from,$to)
{
	global $_rename_messages;
	
	return "$from{$_rename_messages['msg_arrow']}$to";
}
//����ʳ�:�ڡ���̾�ޤ�������ɽ��������
function &rename_phase1(&$s_vars,$err='',$page='')
{
	global $script,$vars,$_rename_messages;
	
	if (!array_key_exists('refer',$vars))
	{
		return FALSE;
	}
	$msg = rename_err($err,$page);

	$radio_regex = $radio_page = '';
	if (array_key_exists('method',$vars) and $vars['method'] == 'regex')
	{
		$radio_regex =' checked'; 
	}
	else
	{
		$radio_page = ' checked'; 
	}
	$select_refer = rename_getselecttag($vars['refer']);

	$ret['msg'] = $_rename_messages['msg_title'];
	$ret['body'] = <<<EOD
$msg
<form action="$script" method="post">
 <div>
  <input type="hidden" name="plugin" value="rename" />
  <input type="radio" name="method" value="page"$radio_page />
  {$_rename_messages['msg_page']}:$select_refer<br />
  <input type="radio" name="method" value="regex"$radio_regex />
  {$_rename_messages['msg_regex']}:<br />
  From:<br />
  <input type="text" name="src" size="80" value="{$s_vars['src']}" /><br />
  To:<br>
  <input type="text" name="dst" size="80" value="{$s_vars['dst']}" /><br />
  <input type="submit" value="{$_rename_messages['btn_next']}" /><br />
 </div>
</form>
EOD;
	return $ret;
}
//�����ʳ�:������̾��������
function &rename_phase2(&$s_vars,$err='')
{
	global $script,$vars,$_rename_messages;
	
	$msg = rename_err($err);

	$related = rename_getrelated($vars['refer']);
	sort($related);
	if (count($related) > 0)
	{
		$rename_related = $_rename_messages['msg_do_related'].
			'<input type="checkbox" name="related" value="1" checked="checked" /><br />';
	}
	if ($s_vars['page'] == '')
	{
		$s_vars['page'] = $s_vars['refer'];
	}
	$msg_rename = sprintf($_rename_messages['msg_rename'],make_link($vars['refer']));

	$ret['msg'] = $_rename_messages['msg_title'];
	$ret['body'] = <<<EOD
$msg
<form action="$script" method="post">
 <div>
  <input type="hidden" name="plugin" value="rename" />
  <input type="hidden" name="refer" value="{$s_vars['refer']}" />
  $msg_rename<br />
  {$_rename_messages['msg_newname']}:<input type="text" name="page" size="80" value="{$s_vars['page']}" /><br />
  $rename_related
  <input type="submit" value="{$_rename_messages['btn_next']}" /><br />
 </div>
</form>
EOD;

	if (count($related) > 0)
	{
		$ret['body'].= "<hr /><p>{$_rename_messages['msg_related']}</p><ul>";
		foreach ($related as $name) { $ret['body'].= '<li>'.make_link($name).'</li>'; }
		$ret['body'].= '</ul>';
	}
	return $ret;
}
//�ڡ���̾�ȴ�Ϣ����ڡ�������󤷡�phase3��
function &rename_refer(&$s_vars)
{
	global $vars;

	$pages[encode($vars['refer'])] = encode($vars['page']);
	if ($vars['related'])
	{
		$from = strip_bracket($vars['refer']);
		$to =   strip_bracket($vars['page']);
		$related = rename_getrelated($vars['refer']);
		foreach ($related as $page)
		{
			$pages[encode($page)] = encode(str_replace($from,$to,$page));
		}
	}
	return rename_phase3($s_vars,$pages);
}
//����ɽ���ǥڡ������ִ�
function &rename_regex(&$s_vars,&$arr_from,&$arr_to)
{
	global $vars;

	$exists = array();
	foreach ($arr_to as $page)
	{
		if (is_page($page))
		{
			$exists[] = $page;
		}
	}
	if (count($exists) > 0) {
		return rename_phase1($s_vars,'already',$exists);
	}

	$pages = array();
	foreach ($arr_from as $refer)
	{
		$pages[encode($refer)] = encode(array_shift($arr_to));
	}
	return rename_phase3($s_vars,$pages);
}
function &rename_phase3(&$s_vars,&$pages)
{
	global $script,$vars,$_rename_messages,$adminpass;

	$files = rename_get_files($pages);

	$exists = array();
	foreach ($files as $_page=>$arr)
	{
		foreach ($arr as $old=>$new)
		{
			if (is_readable($new)) {
				$exists[$_page][$old] = $new;
			}
		}
	}

	if ($vars['pass'] != '')
	{
		if (md5($vars['pass']) == $adminpass)
		{
			return rename_proceed($s_vars,$pages,$files,$exists);
		}
		else
		{
			$msg = rename_err('adminpass');
		}
	}

	if ($s_vars['method'] == "regex")
	{
		$msg .= $_rename_messages['msg_regex']."<br />";
		$input .= "<input type=\"hidden\" name=\"method\" value=\"regex\" />";
		$input .= "<input type=\"hidden\" name=\"src\" value=\"{$s_vars['src']}\" />";
		$input .= "<input type=\"hidden\" name=\"dst\" value=\"{$s_vars['dst']}\" />";
	}
	else
	{
		$msg .= $_rename_messages['msg_page']."<br />";
		$input .= "<input type=\"hidden\" name=\"method\" value=\"page\" />";
		$input .= "<input type=\"hidden\" name=\"refer\" value=\"{$s_vars['refer']}\" />";
		$input .= "<input type=\"hidden\" name=\"page\" value=\"{$s_vars['page']}\" />";
		$input .= "<input type=\"hidden\" name=\"related\" value=\"{$s_vars['related']}\" />";
	}

	if (count($exists) > 0)
	{
		$msg .= $_rename_messages['err_already_below']."<ul>";
		foreach ($exists as $page=>$arr)
		{
			$msg .= "<li>".rename_arrow(make_link(decode($page)),decode($pages[$page]))."<ul>\n";
			foreach ($arr as $ofile=>$nfile)
			{
				$msg .= "<li>".rename_arrow($ofile,$nfile)."</li>\n";
			}
			$msg .= "</ul></li>\n";
		}
		$msg .= "</ul><hr />";

		$input .= '<input type="radio" name="exist" value="0" checked />'.$_rename_messages['msg_exist_none'].'<br />';
		$input .= '<input type="radio" name="exist" value="1" />'.$_rename_messages['msg_exist_overwrite'].'<br />';
	}
	$ret['msg'] = $_rename_messages['msg_title'];
	$ret['body'] .= <<<EOD
<p>$msg</p>
<form action="$script" method="post">
 <div>
  <input type="hidden" name="plugin" value="rename" />
  $input
  {$_rename_messages['msg_adminpass']}
  <input type="password" name="pass" value="" />
  <input type="submit" value="{$_rename_messages['btn_submit']}" />
 </div>
</form>
<p>{$_rename_messages['msg_confirm']}</p>
EOD;

	ksort($pages);
	$ret['body'] .= "<ul>\n";
	foreach ($pages as $old=>$new)
	{
		$ret['body'] .= "<li>".rename_arrow(make_link(decode($old)),decode($new))."</li>\n";
	}
	$ret['body'] .= "</ul>\n";
	return $ret;
}

function &rename_get_files(&$pages)
{
	$files = array();
	$dirs = array(BACKUP_DIR,DIFF_DIR,DATA_DIR);
	if (exist_plugin_convert('attach'))
	{
		$dirs[] = UPLOAD_DIR; 
	}
	if (exist_plugin_convert('counter'))
	{
		$dirs[] = COUNTER_DIR;
	}
	// and more ...

	foreach ($dirs as $path)
	{
		if (!$dir = opendir($path))
		{
			continue;
		}
		while ($file = readdir($dir))
		{
			if ($file == '.' or $file == '..')
			{
				continue; 
			}
			foreach ($pages as $from=>$to)
			{
				$pattern = '/^'.str_replace('/','\/',$from).'([._].+)$/';
				if (!preg_match($pattern,$file,$matches))
				{
					continue; 
				}
				$newfile = $to.$matches[1];
				$files[$from][$path.$file] = $path.$newfile;
			}
		}
	}
	return $files;
}

function rename_proceed(&$s_vars,&$pages,&$files,&$exists)
{
	global $script,$vars,$_rename_messages,$now;

	if (!$vars['exist'])
	{
		foreach ($exists as $key=>$arr)
		{
			unset($files[$key]); 
		}
	}

	set_time_limit(0);
	foreach ($files as $page=>$arr)
	{
		foreach ($arr as $old=>$new)
		{
			if ($exists[$page][$old])
			{
				unlink($new); 
			}
			rename($old,$new);
		}
	}

	$postdata = get_source(RENAME_LOGPAGE);
	$postdata[] = '*'.$now."\n";
	if ($vars['method'] == 'regex')
	{
		$postdata[] = '-'.$_rename_messages['msg_regex']."\n";
		$postdata[] = '--From:'.$s_vars['src']."\n";
		$postdata[] = '--To:'.$s_vars['dst']."\n";
	}
	else
	{
		$postdata[] = '-'.$_rename_messages['msg_page']."\n";
		$postdata[] = '--From:'.$s_vars['refer']."\n";
		$postdata[] = '--To:'.$s_vars['page']."\n";
	}
	if (count($exists) > 0)
	{
		$postdata[] = "\n".$_rename_messages['msg_result']."\n";
		foreach ($exists as $page=>$arr)
		{
			$postdata[] = '-'.rename_arrow(decode($page),decode($pages[$page]))."\n";
			foreach ($arr as $ofile=>$nfile)
			{
				$postdata[] = '--'.rename_arrow($ofile,$nfile)."\n";
			}
		}
		$postdata[] = "----\n";
	}
	foreach ($pages as $old=>$new)
	{
		$postdata[] = '-'.rename_arrow(decode($old),decode($new))."\n";
	}

	// �����ξ��ͤϥ����å����ʤ���

	// �ե�����ν񤭹���
	page_write(RENAME_LOGPAGE, join('',$postdata));

	//������쥯��
	$page = rawurlencode(($vars['page'] == '') ? RENAME_LOGPAGE : $vars['page']);
	header("Location: $script?$page");
	die();
}

function &rename_getrelated($page)
{
	$related = array();
	$pages = get_existpages();
	$pattern = '/(?:^|\/)'.str_replace('/','\/',strip_bracket($page)).'(?:\/|$)/';
	foreach ($pages as $name)
	{
		if ($name == $page)
		{
			continue; 
		}
		if (preg_match($pattern,$name))
		{
			$related[] = $name;
		}
	}
	return $related;
}
function &rename_getselecttag($page)
{
	global $whatsnew;

	$pages =array();
	foreach (get_existpages() as $_page)
	{
		if ($_page == $whatsnew)
		{
			continue; 
		}
		$selected = ($_page == $page) ? ' selected' : '';
		$pages[$_page] = "<option value=\"$_page\"$selected>$_page</option>";
	}
	ksort($pages);
	$list = join("\n ",$pages);
	return <<<EOD
<select name="refer">
 <option value=""></option>
 $list
</select>
EOD;
	return $ret;
}
?>
