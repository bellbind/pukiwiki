<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
//  $Id: attach.inc.php,v 1.18 2003/03/03 07:07:28 panda Exp $
//

/*
 �ץ饰���� attach

 changed by Y.MASUI <masui@hisec.co.jp> http://masui.net/pukiwiki/
 modified by PANDA <panda@arino.jp> http://home.arino.jp/
*/

// upload dir(must set end of /)
if (!defined('UPLOAD_DIR'))
{
	define('UPLOAD_DIR','./attach/');
}

// max file size for upload on PHP(PHP default 2MB)
ini_set('upload_max_filesize','2M');

// max file size for upload on script of PukiWiki(default 1MB)
define('MAX_FILESIZE',1000000);

// �����Ԥ�����ź�եե�����򥢥åץ��ɤǤ���褦�ˤ���
define('ATTACH_UPLOAD_ADMIN_ONLY',FALSE); // FALSE or TRUE
// �����Ԥ�����ź�եե���������Ǥ���褦�ˤ���
define('ATTACH_DELETE_ADMIN_ONLY',FALSE); // FALSE or TRUE

// ���åץ���/������˥ѥ���ɤ��׵᤹��(ADMIN_ONLY��ͥ��)
define('ATTACH_PASSWORD_REQUIRE',FALSE); // FALSE or TRUE

// file icon image
if (!defined('FILE_ICON'))
{
	define('FILE_ICON','<img src="./image/file.png" width="20" height="20" alt="file" style="border-width:0px" />');
}

//-------- init
function plugin_attach_init()
{
	$messages = array(
		'_attach_messages'=>array(
			'msg_uploaded' => '$1 �˥��åץ��ɤ��ޤ���',
			'msg_deleted'  => '$1 ����ե�����������ޤ���',
			'msg_freezed'  => 'ź�եե��������뤷�ޤ�����',
			'msg_unfreezed'=> 'ź�եե��������������ޤ�����',
			'msg_upload'   => '$1 �ؤ�ź��',
			'msg_info'     => 'ź�եե�����ξ���',
			'msg_confirm'  => '<p>%s �������ޤ���</p>',
			'msg_list'     => 'ź�եե��������',
			'msg_listpage' => '$1 ��ź�եե��������',
			'msg_listall'  => '���ڡ�����ź�եե��������',
			'msg_file'     => 'ź�եե�����',
			'msg_maxsize'  => '���åץ��ɲ�ǽ����ե����륵������ %s �Ǥ���',
			'msg_count'    => ' <span class="small">%s��</span>',
			'msg_password' => '�ѥ����',
			'msg_adminpass'=> '�����ԥѥ����',
			'msg_delete'   => '���Υե�����������ޤ���',
			'msg_freeze'   => '���Υե��������뤷�ޤ���',
			'msg_unfreeze' => '���Υե��������������ޤ���',
			'msg_isfreeze' => '���Υե��������뤵��Ƥ��ޤ���',
			'msg_require'  => '(�����ԥѥ���ɤ�ɬ�פǤ�)',
			'msg_filesize' => '������',
			'msg_date'     => '��Ͽ����',
			'msg_dlcount'  => '����������',
			'err_noparm'   => '$1 �ؤϥ��åץ��ɡ�����ϤǤ��ޤ���',
			'err_exceed'   => '$1 �ؤΥե����륵�������礭�����ޤ�',
			'err_exists'   => '$1 ��Ʊ���ե�����̾��¸�ߤ��ޤ�',
			'err_notfound' => '$1 �ˤ��Υե�����ϸ��Ĥ���ޤ���',
			'err_noexist'  => 'ź�եե����뤬����ޤ���',
			'err_password' => '�ѥ���ɤ����פ��ޤ���',
			'err_adminpass'=> '�����ԥѥ���ɤ����פ��ޤ���',
			'btn_upload'   => '���åץ���',
			'btn_info'     => '�ܺ�',
			'btn_submit'   => '�¹�'
		)
	);
	set_plugin_messages($messages);
}

//-------- convert
function plugin_attach_convert()
{
	global $vars;
	
	if (!ini_get('file_uploads'))
	{
		return 'file_uploads disabled';
	}
	
	$nolist = $noform = FALSE;
	
	if (func_num_args() > 0)
	{
		foreach (func_get_args() as $arg)
		{
			$arg = strtolower($arg);
			$nolist |= ($arg == 'nolist');
			$noform |= ($arg == 'noform');
		}
	}
	$ret = '';
	if (!$nolist)
	{
		$obj = &new AttachPages($vars['page']);
		$ret .= $obj->to_string($vars['page'],TRUE);
	}
	if (!$noform)
	{
		$ret .= attach_form($vars['page']);
	}
	
	return $ret;
}

//-------- action
function plugin_attach_action()
{
	global $vars,$HTTP_POST_FILES;
	
	if (array_key_exists('openfile',$vars))
	{
		$vars['pcmd'] = 'open';
		$vars['file'] = $vars['openfile'];
	}
	if (array_key_exists('delfile',$vars))
	{
		$vars['pcmd'] = 'delete';
		$vars['file'] = $vars['delfile'];
	}
	if (array_key_exists('attach_file',$HTTP_POST_FILES) and
		is_uploaded_file($HTTP_POST_FILES['attach_file']['tmp_name']))
	{
		return attach_upload();
	}
	
	$age = array_key_exists('age',$vars) ? $vars['age'] : 0;
	$pcmd = array_key_exists('pcmd',$vars) ? $vars['pcmd'] : '';
	
	switch ($pcmd)
	{
		case 'info':    return attach_info();
		case 'delete':  return attach_delete();
		case 'open':    return attach_open($vars['refer'],$vars['file'],$age);
		case 'list':    return attach_list();
		case 'freeze':  return attach_freeze(TRUE);
		case 'unfreeze':return attach_freeze(FALSE);
		case 'upload':  return attach_showform();
	}
	if ($vars['page'] == '' or !is_page($vars['page']))
	{
		return attach_list();
	}
	
	return attach_showform();
}
//-------- call from skin
function attach_filelist()
{
	global $vars,$_attach_messages;
	
	plugin_attach_init();
	
	$obj = &new AttachPages($vars['page'],0);

	if (!array_key_exists($vars['page'],$obj->pages))
	{
		return '';
	}
	return $_attach_messages['msg_file'].': '.$obj->to_string($vars['page'],TRUE)."\n";
}
//-------- ����
//�ե����륢�åץ���
function attach_upload()
{
	global $vars,$adminpass,$HTTP_POST_FILES;
	global $_attach_messages;
	
	if ($HTTP_POST_FILES['attach_file']['size'] > MAX_FILESIZE)
	{
		return array('msg'=>$_attach_messages['err_exceed']);
	}
	if (is_freeze($vars['refer']) || !is_editable($vars['refer']))
	{
		return array('msg'=>$_attach_messages['err_noparm']);
	}
	if (ATTACH_UPLOAD_ADMIN_ONLY and md5($vars['pass']) != $adminpass)
	{
		return array('msg'=>$_attach_messages['err_adminpass']);
	}
	
	$obj = &new AttachFile($vars['refer'],$HTTP_POST_FILES['attach_file']['name']);	
	
	if ($obj->exist)
	{
		return array('msg'=>$_attach_messages['err_exists']);
	}
	move_uploaded_file($HTTP_POST_FILES['attach_file']['tmp_name'],$obj->filename);
	
	if (is_page($vars['refer']))
	{
		touch(get_filename($vars['refer']));
	}
	
	$obj->getstatus();
	$obj->status['pass'] = array_key_exists('pass',$vars) ? md5($vars['pass']) : '';
	$obj->putstatus();

	return array('msg'=>$_attach_messages['msg_uploaded']);
}
//�ܺ٥ե������ɽ��
function attach_info($err='')
{
	global $script,$vars;
	global $_attach_messages;
	
	$retval = array();

	$obj = &new AttachFile($vars['refer'],$vars['file'],$vars['age']);
	$obj->getstatus();
	
	$s_file = htmlspecialchars($vars['file']);
	$s_refer = htmlspecialchars($vars['refer']);
	$r_refer = rawurlencode($vars['refer']);

	$retval['msg'] = sprintf($_attach_messages['msg_info'],$s_file);
	$retval['body'] = ($err == '') ? '' : '<p>'.$_attach_messages[$err].'</p>';

	$retval['body'] .= <<<EOD
  <span class="small">
   [<a href="$script?plugin=attach&amp;pcmd=list&amp;refer=$r_refer">{$_attach_messages['msg_list']}</a>]
   [<a href="$script?plugin=attach&amp;pcmd=list">{$_attach_messages['msg_listall']}</a>]
  </span><br />
EOD;
	
	if ($obj->status['freeze'])
	{
		$msg_freezed = '<dd>'.$_attach_messages['msg_isfreeze'].'</dd>';
		$msg_delete = '';
		$msg_freeze  = '<input type="hidden" name="pcmd" value="unfreeze" />'.$_attach_messages['msg_unfreeze'];
	}
	else
	{
		$msg_freezed = '';
		$msg_delete = '<input type="radio" name="pcmd" value="delete" />'.$_attach_messages['msg_delete'];
		if (ATTACH_DELETE_ADMIN_ONLY)
		{
			$msg_delete .= $_attach_messages['msg_require'];
		}
		$msg_delete .= '<br />';
		$msg_freeze = '<input type="radio" name="pcmd" value="freeze" />'.$_attach_messages['msg_freeze'];
	}
	$info = $obj->to_string(TRUE,FALSE);
	$type = attach_mime_content_type($obj->filename);
	$age = (array_key_exists('age',$vars) and is_numeric($vars['age'])) ? $vars['age'] : 0;
	$retval['body'] .= <<< EOD
<dl>
 <dt>$info</dt>
 <dd>{$_attach_messages['msg_filesize']}:{$obj->size_str} ({$obj->size} bytes)</dd>
 <dd>Content-type:$type</dd>
 <dd>{$_attach_messages['msg_date']}:{$obj->time_str}</dd>
 <dd>{$_attach_messages['msg_dlcount']}:{$obj->status['count'][$age]}</dd>
  $msg_freezed
</dl>
EOD;
	if ($obj->age)
	{
		return $retval;
	}
	$retval['body'] .= <<< EOD
<hr>
<form action="$script" method="post">
 <div>
  <input type="hidden" name="plugin" value="attach" />
  <input type="hidden" name="refer" value="$s_refer" />
  <input type="hidden" name="file" value="$s_file" />
  $msg_delete
  $msg_freeze{$_attach_messages['msg_require']}<br />
  {$_attach_messages['msg_password']}: <input type="password" name="pass" size="8" />
  <input type="submit" value="{$_attach_messages['btn_submit']}" />
 </div>
</form>
EOD;
	
	return $retval;
}
//���
function attach_delete()
{
	global $vars,$adminpass;
	global $_attach_messages;
	
	if (is_freeze($vars['refer']) or !is_editable($vars['refer']))
	{
		return array('msg' => $_attach_messages['err_noparm']);
	}
	
	$obj = &new AttachFile($vars['refer'],$vars['file']);
	
	if (!$obj->exist)
	{
		return array('msg' => $_attach_messages['err_notfound']);
	}
	
	$obj->getstatus();
	
	if ($obj->status['freeze'])
	{
		return attach_info('msg_isfreeze');
	}
	
	if (md5($vars['pass']) != $adminpass)
	{
		if (ATTACH_DELETE_ADMIN_ONLY)
		{
			return attach_info('err_adminpass');
		}
		else if (ATTACH_PASSWORD_REQUIRE and md5($vars['pass']) != $obj->status['pass'])
		{
			return attach_info('err_password');
		}
	}
	//�Хå����å�
	do
	{
		$age = ++$obj->status['age'];
	}
	while (file_exists($obj->basename.'.'.$age));
	
	rename($obj->basename,$obj->basename.'.'.$age);
	$obj->status['count'][$age] = $obj->status['count'][0];
	$obj->status['count'][0] = 0;
	$obj->putstatus();
	
	if (is_page($vars['refer']))
	{
		touch(get_filename($vars['refer']));
	}
	
	return array('msg' => $_attach_messages['msg_deleted']);
}
//���
function attach_freeze($freeze)
{
	global $vars,$adminpass;
	global $_attach_messages;
	
	if (is_freeze($vars['refer']) or !is_editable($vars['refer']))
	{
		return array('msg' => $_attach_messages['err_noparm']);
	}
	
	$obj = &new AttachFile($vars['refer'],$vars['file']);
	
	if (!$obj->exist)
	{
		return array('msg' => $_attach_messages['err_notfound']);
	}
	if (md5($vars['pass']) != $adminpass)
	{
		return attach_info('err_adminpass');
	}
	
	$obj->getstatus();
	$obj->status['freeze'] = $freeze;
	$obj->putstatus();
	
	return array('msg' => $_attach_messages[$freeze ? 'msg_freezed' : 'msg_unfreezed']);
}
//���������
function attach_open($page,$file,$age=0)
{
	global $_attach_messages;
	
	$obj = &new AttachFile($page,$file,$age);
	
	if (!$obj->exist)
	{
		return array('msg' => $_attach_messages['err_notfound']);
	}
	
	$obj->getstatus();
	$obj->status['count'][$age]++;
	$obj->putstatus();
	
	$type = attach_mime_content_type($obj->filename);
	$name = htmlspecialchars($obj->file);
	
	// for japanese (???)
	if (function_exists('mb_convert_encoding'))
	{
		$name = mb_convert_encoding($name,'SJIS','auto');
	}
	
	header('Content-Disposition: inline; filename="'.$name.'"');
	header('Content-Length: '.$obj->size);
	header('Content-Type: '.$type);
	
	@readfile($obj->filename);
	exit; 
}
//��������
function attach_list()
{
	global $vars;
	global $_attach_messages;
	
	$refer = array_key_exists('refer',$vars) ? $vars['refer'] : '';
	
	$obj = &new AttachPages($refer);
	
	$msg = $_attach_messages[$refer == '' ? 'msg_listall' : 'msg_listpage'];
	$body = ($refer == '' or array_key_exists($refer,$obj->pages)) ?
		$obj->to_string($refer,FALSE) :
		$_attach_messages['err_noexist'];
	return array('msg'=>$msg,'body'=>$body);
}
//���åץ��ɥե������ɽ��
function attach_showform()
{
	global $vars;
	global $_attach_messages;
	
	$vars['refer'] = $vars['page'];
	$body = ini_get('file_uploads') ? attach_form($vars['page']) : 'file_uploads disabled.';
	
	return array('msg'=>$_attach_messages['msg_upload'],'body'=>$body);
}

//-------- �����ӥ�
//mime-type�η���
function attach_mime_content_type($filename)
{
	$type = 'application/octet-stream'; //default
	$config = ':config/plugin/attach/mime-type';
	
	if (!file_exists($filename))
	{
		return $type;
	}
	$size = getimagesize($filename);
	if (is_array($size))
	{
		switch ($size[2])
		{
			case 1:
				return 'image/gif';
			case 2:
				return 'image/jpeg';
			case 3:
				return 'image/png';
			case 4:
				return 'application/x-shockwave-flash';
		}
	}
	
	if (!is_page($config))
	{
		return $type;
	}
	
	if (!preg_match('/_([0-9A-Z]+)$/',$filename,$matches))
	{
		return $type;
	}
	$filename = decode($matches[1]);
	
	foreach (get_source($config) as $line)
	{
		if (!preg_match('/\|(.+)\|/',$line,$matches))
		{
			continue;
		}
		$cells = explode('|',$matches[1]);
		$_type = trim($cells[0]);
		$exts = preg_split('/\s+|,/',trim($cells[1]),-1,PREG_SPLIT_NO_EMPTY);
		
		foreach ($exts as $ext)
		{
			if (preg_match("/\.$ext$/i",$filename))
			{
				return $_type;
			}
		}
	}
	
	return $type;
}
//���åץ��ɥե�����
function attach_form($page)
{
	global $script,$vars;
	global $_attach_messages;
	
	$r_page = rawurlencode($page);
	$s_page = htmlspecialchars($page);
	$navi = <<<EOD
  <span class="small">
   [<a href="$script?plugin=attach&amp;pcmd=list&amp;refer=$r_page">{$_attach_messages['msg_list']}</a>]
   [<a href="$script?plugin=attach&amp;pcmd=list">{$_attach_messages['msg_listall']}</a>]
  </span><br />
EOD;

	if (!(bool)ini_get('file_uploads'))
	{
		return $navi;
	}
	
	$maxsize = MAX_FILESIZE;
	$msg_maxsize = sprintf($_attach_messages['msg_maxsize'],number_format($maxsize/1000)."KB");

	$pass = '';
	if (ATTACH_PASSWORD_REQUIRE or ATTACH_UPLOAD_ADMIN_ONLY)
	{
		$title = $_attach_messages[ATTACH_UPLOAD_ADMIN_ONLY ? 'msg_adminpass' : 'msg_password'];
		$pass = '<br />'.$title.': <input type="password" name="pass" size="8" />';
	}
	return <<<EOD
<form enctype="multipart/form-data" action="$script" method="post">
 <div>
  <input type="hidden" name="plugin" value="attach" />
  <input type="hidden" name="pcmd" value="post" />
  <input type="hidden" name="refer" value="$s_page" />
  <input type="hidden" name="max_file_size" value="$maxsize" />
  $navi
  <span class="small">
   $msg_maxsize
  </span><br />
  {$_attach_messages['msg_file']}: <input type="file" name="attach_file" />
  $pass
  <input type="submit" value="{$_attach_messages['btn_upload']}" />
 </div>
</form>
EOD;
}
//-------- ���饹
//�ե�����
class AttachFile
{
	var $page,$file,$age,$basename,$filename,$logname;
	var $time = 0;
	var $size = 0;
	var $time_str = '';
	var $size_str = '';
	var $status = array('count'=>array(0),'age'=>'','pass'=>'','freeze'=>FALSE);
	
	function AttachFile($page,$file,$age=0)
	{
		$this->page = $page;
		$this->file = $file;
		$this->age = $age;
		
		$this->basename = UPLOAD_DIR.encode($page).'_'.encode($file);
		$this->filename = $this->basename . ($age ? '.'.$age : '');
		$this->logname = $this->basename.'.log';
		$this->exist = file_exists($this->filename);
		$this->time = $this->exist ? filemtime($this->filename) - LOCALZONE : 0;
	}
	// �ե�����������
	function getstatus()
	{
		if (!$this->exist)
		{
			return;
		}
		// ���ե��������
		if (file_exists($this->logname))
		{
			$data = file($this->logname);
			foreach ($this->status as $key=>$value)
			{
				$this->status[$key] = chop(array_shift($data));
			}
			$this->status['count'] = explode(',',$this->status['count']);
		}
		$this->time_str = get_date('Y/m/d H:i:s',$this->time);
		$this->size = filesize($this->filename);
		$this->size_str = sprintf('%01.1f',round($this->size)/1000,1).'KB';
	}		
	//���ơ�������¸
	function putstatus()
	{
		$this->status['count'] = join(',',$this->status['count']);
		$fp = fopen($this->logname,'wb')
			or die_message('cannot write '.$this->logname);
		flock($fp,LOCK_EX);
		foreach ($this->status as $key=>$value)
		{
			fwrite($fp,$value."\n");
		}
		flock($fp,LOCK_UN);
		fclose($fp);
	}
	function datecomp($a,$b)
	{
		return ($a->time == $b->time) ? 0 : (($a->time > $b->time) ? -1 : 1);
	}
	function to_string($showicon,$showinfo)
	{
		global $script,$date_format,$time_format,$weeklabels;
		global $_attach_messages;
		
		$this->getstatus();
		$param  = '&amp;file='.rawurlencode($this->file).'&amp;refer='.rawurlencode($this->page).
			($this->age ? '&amp;age='.$this->age : '');
		$title = $this->time_str.' '.$this->size_str;
		$label = ($showicon ? FILE_ICON : '').htmlspecialchars($this->file);
		if ($this->age)
		{
			$label .= ' (backup No.'.$this->age.')';
		}
		$info = $count = '';
		if ($showinfo)
		{
			$_title = str_replace('$1',rawurlencode($this->file),$_attach_messages['msg_info']);
			$info = "\n<span class=\"small\">[<a href=\"$script?plugin=attach&amp;pcmd=info$param\" title=\"$_title\">{$_attach_messages['btn_info']}</a>]</span>";
			$count = ($showicon and !empty($this->status['count'][$this->age])) ?
				sprintf($_attach_messages['msg_count'],$this->status['count'][$this->age]) : '';
		}
		return "<a href=\"$script?plugin=attach&amp;pcmd=open$param\" title=\"$title\">$label</a>$count$info";
	}
}

// �ե����륳��ƥ�
class AttachFiles
{
	var $page;
	var $files = array();
	
	function AttachFiles($page)
	{
		$this->page = $page;
	}
	function add($file,$age)
	{
		$this->files[$file][$age] = &new AttachFile($this->page,$file,$age);
	}
	// �ե�������������
	function to_string($flat)
	{
		if ($flat)
		{
			return $this->to_flat();
		}	
		$ret = '';
		$files = array_keys($this->files);
		sort($files);
		foreach ($files as $file)
		{
			$_files = array();
			foreach (array_keys($this->files[$file]) as $age)
			{
				$_files[$age] = $this->files[$file][$age]->to_string(FALSE,TRUE);
			}
			if (!array_key_exists(0,$_files))
			{
				$_files[0] = htmlspecialchars($file);
			}
			ksort($_files);
			$_file = $_files[0];
			unset($_files[0]);
			$ret .= " <li>$_file\n";
			if (count($_files))
			{
				$ret .= "<ul>\n<li>".join("</li>\n<li>",$_files)."</li>\n</ul>\n";
			}
			$ret .= " </li>\n";
		}
		return make_pagelink($this->page)."\n<ul>\n$ret</ul>\n";
	}
	// �ե�������������(inline)
	function to_flat()
	{
		$ret = '';
		$files = array();
		foreach (array_keys($this->files) as $file)
		{
			if (array_key_exists(0,$this->files[$file]))
			{
				$files[$file] = &$this->files[$file][0];
			}
		}
		uasort($files,array('AttachFile','datecomp'));
		foreach (array_keys($files) as $file)
		{
			$ret .= $files[$file]->to_string(TRUE,TRUE).' ';
		}
		
		return $ret;
	}
}
// �ڡ�������ƥ�
class AttachPages
{
	var $pages = array();
	
	function AttachPages($page='',$age=NULL)
	{

		$dir = opendir(UPLOAD_DIR)
			or die('directory '.UPLOAD_DIR.' is not exist or not readable.');
		
		$page_pattern = ($page == '') ? '[0-9A-F]+' : preg_quote(encode($page),'/');
		$age_pattern = ($age === NULL) ?
			'(?:\.([0-9]+))?' : ($age ?  "\.($age)" : '');
		$pattern = "/^({$page_pattern})_([0-9A-F]+){$age_pattern}$/";
		
		while ($file = readdir($dir))
		{
			if (!preg_match($pattern,$file,$matches))
			{
				continue;
			}
			$_page = decode($matches[1]);
			$_file = decode($matches[2]);
			$_age = array_key_exists(3,$matches) ? $matches[3] : 0;
			if (!array_key_exists($_page,$this->pages))
			{
				$this->pages[$_page] = &new AttachFiles($_page);
			}
			$this->pages[$_page]->add($_file,$_age);
		}
		closedir($dir);
	}
	function to_string($page='',$flat=FALSE)
	{
		if ($page != '')
		{
			if (!array_key_exists($page,$this->pages))
			{
				return '';
			}
			return $this->pages[$page]->to_string($flat);
		}
		$ret = '';
		$pages = array_keys($this->pages);
		sort($pages);
		foreach ($pages as $page)
		{
			$ret .= '<li>'.$this->pages[$page]->to_string($flat)."</li>\n";
		}
		return "\n<ul>\n".$ret."</ul>\n";
		
	}
}		
?>
