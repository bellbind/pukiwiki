<?php
/////////////////////////////////////////////////
// tarfile.inc.php
//       by teanan / Interfair Laboratory 2004.

// [��������]
// 2004-09-21 version 0.0 [������]
// ���Ȥꤢ���� wiki �ǥ��쥯�ȥ꤬tar.gz�Ǽ��Ф���褦�ˤʤ�ޤ�����
// 2004-09-22 version 0.1 [������]
// ����tar.gz/��.tar��������б�
// ��attach,backup�ǥ��쥯�ȥ�ΥХå����åפ��б�
// ���ե�����̾��ڡ���̾���Ѵ����뵡ǽ���ɲ�(wiki/attach/backup)
// ���ե���������μ�����ˡ���ѹ�(glob��opendir)
// 2004-09-22 version 0.2
// ���ե�����Υ��åץ���(�ꥹ�ȥ�)���б�(tar/tar.gz)
//   (�оݤ� wiki,attach�ǥ��쥯�ȥ�Τ�)
// 2004-09-22 version 1.0
// ��LongLink(100�Х��Ȥ�Ķ�����ե�����̾)���б�
// ���ꥹ�ȥ����ե�����ι�������򸵤��᤹�褦�˽���

/////////////////////////////////////////////////
// �ե�����̾��ڡ���̾�ˤ���ݤ����ܸ��ʸ��������
define('PLUGIN_DUMP_FILENAME_ENCORDING','SJIS');

// ���祢�åץ��ɥ�����
define('PLUGIN_DUMP_MAX_FILESIZE',1024);	// Kbyte

/////////////////////////////////////////////////
// ��ĥ��
define('PLUGIN_DUMP_SFX_TAR' , '.tar');
define('PLUGIN_DUMP_SFX_GZIP', '.tar.gz');

// TAR�ե����������
define('TAR_HDR_LEN',           512);	// �إå����礭��
define('TAR_BLK_LEN',           512);	// ñ�̥֥�å�Ĺ��
define('TAR_HDR_NAME_OFFSET',     0);	// �ե�����̾�Υ��ե��å�
define('TAR_HDR_NAME_LEN',      100);	// �ե�����̾�κ���Ĺ��
define('TAR_HDR_MODE_OFFSET',   100);	// mode�ؤΥ��ե��å�
define('TAR_HDR_UID_OFFSET',    108);	// uid�ؤΥ��ե��å�
define('TAR_HDR_GID_OFFSET',    116);	// gid�ؤΥ��ե��å�
define('TAR_HDR_SIZE_OFFSET',   124);	// �������ؤΥ��ե��å�
define('TAR_HDR_SIZE_LEN',       12);	// ��������Ĺ��
define('TAR_HDR_MTIME_OFFSET',  136);	// �ǽ���������Υ��ե��å�
define('TAR_HDR_MTIME_LEN',      12);	// �ǽ����������Ĺ��
define('TAR_HDR_CHKSUM_OFFSET', 148);	// �����å�����Υ��ե��å�
define('TAR_HDR_CHKSUM_LEN',      8);	// �����å������Ĺ��
define('TAR_HDR_TYPE_OFFSET',   156);	// �ե����륿���פؤΥ��ե��å�

// �������
define('TAR_STATS_INIT',    0);		// �������
define('TAR_STATS_OPEN',   10);		// �ɤ߼��
define('TAR_STATS_CREATE', 20);		// �񤭹���

define('TAR_DATA_MODE',      '100666 ');	// �ե�����ѡ��ߥå����
define('TAR_DATA_UGID',      '000000 ');	// uid / gid
define('TAR_DATA_CHKBLANKS', '        ');

// GNU��ĥ����(��󥰥ե�����̾�б�)
define('TAR_DATA_LONGLINK', '././@LongLink');
define('TAR_HDR_FILE', '0');
define('TAR_HDR_LINK', 'L');

// ���������֤μ���
define('ARCFILE_GZIP', 0);
define('ARCFILE_TAR',  1);

// action���
define('PLUGIN_DUMP_CREATE', 'act_download');	// Create & download
define('PLUGIN_DUMP_RESTORE',   'act_upload');	// Upload & restore


/////////////////////////////////////////////////
// �ץ饰��������
function plugin_tarfile_action()
{
	global $adminpass;
	global $vars, $post;

	$pass = isset($post['pass']) ? $post['pass'] : NULL;
	$act  = isset($post['act'])  ? $post['act']  : NULL;

	$body = '';

//	if (pkwk_login($pass))	// for pukiwiki-1.4.4
	if ($pass !== NULL) {
		if((md5($pass) == $adminpass) && ($act !== NULL) ) {
			switch($act){
			case PLUGIN_DUMP_CREATE:
				$body = plugin_tarfile_download();
				break;
			case PLUGIN_DUMP_RESTORE:
				$retcode = plugin_tarfile_upload();
				$body .= $retcode['msg'];
				if($retcode['code'] == true) {
					// ���ｪλ
					$msg = '���åץ��ɤ���λ���ޤ���';
					return array('msg' => $msg, 'body' => $body);
				}
				break;
			}
		} else {
			$body = ($pass === NULL) ? '' : "<p><strong>�ѥ���ɤ��㤤�ޤ���</strong></p>\n";
		}
	}

	// ���ϥե������ɽ��
	$body .= plugin_tarfile_disp_form();
	
	return array('msg' => 'tarfile', 'body' => $body);
}

/////////////////////////////////////////////////
// �ե�����Υ��������
function plugin_tarfile_download()
{
	global $post;

	// ���������֤μ���
	$arc_kind = ($post['pcmd'] == 'tar') ? ARCFILE_TAR : ARCFILE_GZIP;

	// �ڡ���̾���Ѵ�����
	$namedecode = isset($post['namedecode']) ? true : false;

	// �Хå����åץǥ��쥯�ȥ�
	$bk_wiki   = isset($post['bk_wiki'])   ? true : false;
	$bk_attach = isset($post['bk_attach']) ? true : false;
	$bk_backup = isset($post['bk_backup']) ? true : false;

	$tar = new compact_tarlib();

	// �ե��������������
	if($tar->create(CACHE_DIR, $arc_kind))
	{
		$filecount = 0;		// �ե������
		if ($bk_wiki)   $filecount .= $tar->add(DATA_DIR,   '^[0-9A-F]+\.txt', $namedecode);
		if ($bk_attach) $filecount .= $tar->add(UPLOAD_DIR, '^[0-9A-F_]+',     $namedecode);
		if ($bk_backup) $filecount .= $tar->add(BACKUP_DIR, '^[0-9A-F]+\.gz',  $namedecode);
		$tar->close();

		if($filecount > 0) {
			// ���������
			download_tarfile($tar->filename, $arc_kind);
			@unlink($tar->filename);
			exit;	// ���ｪλ
		} else {
			@unlink($tar->filename);
			return '<p><strong>�ե����뤬�ߤĤ���ޤ���Ǥ�����</strong></p>';
		}
	}
	else
	{
		die_message('�ƥ�ݥ��ե�����������˼��Ԥ��ޤ�����');
	}
}

/////////////////////////////////////////////////
// �ե�����Υ��åץ���
function plugin_tarfile_upload()
{
	global $post;

	$code = false;
	$msg  = '';

	// ���������֤μ���
	$arc_kind = ($post['pcmd'] == 'tar') ? ARCFILE_TAR : ARCFILE_GZIP;

	$filename = $_FILES['upload_file']['name'];


	// ���åץ��ɥե�����
	$uploadfile = tempnam(CACHE_DIR, 'upload' );
	if (move_uploaded_file($_FILES['upload_file']['tmp_name'], $uploadfile))
	{
		// tar�ե������Ÿ������
		$tar = new compact_tarlib();
		if($tar->open($uploadfile, $arc_kind))
		{
			// DATA_DIR (wiki/*.txt)
			$quote_wiki  = preg_quote(DATA_DIR, '/');
			$quote_wiki .= '((?:[0-9A-F])+)(\.txt){0,1}';

			// UPLOAD_DIR (attach/*)
			$quote_attach  = preg_quote(UPLOAD_DIR,'/');
			$quote_attach .= '((?:[0-9A-F]{2})+)_((?:[0-9A-F])+)';

			$pattern = "((^$quote_wiki)|(^$quote_attach))";
	
			$files = $tar->extract($pattern);
			if(! empty($files)) {
				$msg  = '<p><strong>Ÿ�������ե��������</strong><ul>';
				foreach($files as $name) {
					$msg .= "<li>$name</li>\n";
				}
				$msg .= '</ul></p>';
				$code = true;
			} else {
				$msg = '<p>Ÿ���Ǥ���ե����뤬����ޤ���Ǥ�����</p>';
				$code = false;
			}
			$tar->close();
		}
		else
		{
			$msg = '<p>�ե����뤬�ߤĤ���ޤ���Ǥ�����</p>';
			$code = false;
		}
		// ��������λ�����饢�åץ��ɤ����ե�����Ϻ������
		@unlink($uploadfile);
	}
	else
	{
		die_message('�ե����뤬�ߤĤ���ޤ���Ǥ�����');
	}

	return array('code' => $code , 'msg' => $msg);
}

/////////////////////////////////////////////////
// tar�ե�����Υ��������
function download_tarfile($name, $arc_kind)
{
	// �ե�����̾
	$filename = strftime("tar%Y%m%d", time()) . $file_ext;

	if($arc_kind == ARCFILE_GZIP) {
		$filename .= PLUGIN_DUMP_SFX_GZIP;
	} else {
		$filename .= PLUGIN_DUMP_SFX_TAR;
	}

	$size = filesize($name);

	header('Content-Disposition: attachment; filename="' . $filename . '"');
	header('Content-Length: ' . $size);
	header('Content-Type: application/octet-stream');
	header('Pragma: no-cache');
	@readfile($name);
}

/////////////////////////////////////////////////
// ���ϥե������ɽ��
function plugin_tarfile_disp_form()
{
	global $script, $defaultpage;

	$act_down  = PLUGIN_DUMP_CREATE;
	$act_up    = PLUGIN_DUMP_RESTORE;
	$maxsize   = PLUGIN_DUMP_MAX_FILESIZE * 1024;
	$maxsizekb = PLUGIN_DUMP_MAX_FILESIZE;

	$data = <<<EOD
<span class="small">
TAR�ե�����Хå����å� / �ꥹ�ȥ��ץ饰����
</span>
<br /><br />
<h3>Tar�ե�����Υ��������</h3>
<form action="$script" method="post">
 <div>
  <input type="hidden" name="cmd"  value="tarfile" />
  <input type="hidden" name="page" value="$defaultpage" />
  <input type="hidden" name="act"  value="$act_down" />

<p><strong>���������֤η���</strong>
<br />
  <input type="radio" name="pcmd" value="tgz" checked="checked" /> ��.tar.gz ����<br />
  <input type="radio" name="pcmd" value="tar" /> ��.tar ����
</p>
<p><strong>�Хå����åץǥ��쥯�ȥ�</strong>
<br />
  <input type="checkbox" name="bk_wiki" checked="checked" /> wiki<br />
  <input type="checkbox" name="bk_attach" /> attach<br />
  <input type="checkbox" name="bk_backup" /> backup
</p>
<p><strong>���ץ����</strong>
<br />
  <input type="checkbox" name="namedecode" /> �ե�����̾��ڡ���̾���Ѵ� (���ꥹ�ȥ��˻Ȥ����ȤϤǤ��ʤ��ʤ�ޤ�)<br />
</p>
<p><strong>�����ԥѥ����</strong>
  <input type="password" name="pass" size="12" />
  <input type="submit"   name="ok"   value="OK" />
</p>
 </div>
</form>

<h3>�ǡ����Υꥹ�ȥ�</h3>
<form enctype="multipart/form-data" action="$script" method="post">
 <div>
  <input type="hidden" name="cmd"  value="tarfile" />
  <input type="hidden" name="page" value="$defaultpage" />
  <input type="hidden" name="act"  value="$act_up" />
  <input type="hidden" name="max_file_size" value="$maxsize" />
<p><strong>[����] Ʊ��̾���Υǡ����ե�����Ͼ�񤭤���ޤ��Τǡ���ʬ����դ���������</strong></p>
<p><span class="small">
���åץ��ɲ�ǽ�ʺ���ե����륵�����ϡ�$maxsizekb KByte �ޤǤǤ���<br />
</span>
  �ե�����: <input type="file" name="upload_file" size="40" />
</p>
<p><strong>���������֤η���</strong>
<br />
  <input type="radio" name="pcmd" value="tgz" checked="checked" /> ��.tar.gz ����<br />
  <input type="radio" name="pcmd" value="tar" /> ��.tar ����
</p>
<p><strong>�����ԥѥ����</strong>
  <input type="password" name="pass" size="12" />
  <input type="submit"   name="ok"   value="OK" />
</p>
 </div>
</form>
EOD;

	return $data;
}

/////////////////////////////////////////////////
// tar�ǡ����κ���/Ÿ���饤�֥��

class compact_tarlib
{
	var $filename;
	var $fp;
	var $status;
	var $dummydata;
	var $arc_kind;

	// ���󥹥ȥ饯��
	function compact_tarlib( $name = '' ) {
		$this->fp       = false;
		$this->filename = $name;
		$this->status   = TAR_STATS_INIT;
		$arc_kind       = ARCFILE_GZIP;
	}
	
	////////////////////////////////////////////////////////////
	//
	// �ؿ�  : tar�ե�����򳫤�
	// ����  : tar�ե�����̾
	// �֤���: ture .. ���� , false .. ����
	//
	////////////////////////////////////////////////////////////
	function open( $name = '', $kind = ARCFILE_GZIP )
	{
		if( $name != '' ) $this->filename = $name;

		if($kind == ARCFILE_GZIP) {
			$this->arc_kind = ARCFILE_GZIP;
			$this->fp = gzopen( $this->filename, 'rb');
		} else {
			$this->arc_kind = ARCFILE_TAR;
			$this->fp =  fopen( $this->filename, 'rb');
		}

		if( $this->fp == false ) return false;	// ����ե�����ʤ�

		$this->status = TAR_STATS_OPEN;
		rewind($this->fp);

		return true;
	}

	////////////////////////////////////////////////////////////
	//
	// �ؿ�  : tar�ե�������������
	// ����  : tar�ե�������������ѥ�
	// �֤���: ture .. ���� , false .. ����
	//
	////////////////////////////////////////////////////////////
	function create( $odir, $kind = ARCFILE_GZIP )
	{
		$tname = tempnam( $odir, 'tar' );

		if($kind == ARCFILE_GZIP) {
			$this->arc_kind = ARCFILE_GZIP;
			$this->fp = gzopen($tname, 'wb');
		} else {
			$this->arc_kind = ARCFILE_TAR;
			$this->fp = @fopen($tname, 'wb');
		}
		if( $this->fp==false ) return false;	// ��������

		// ����������������ե�����̾�򵭲����Ƥ���
		$this->filename = $tname;
		$this->status   = TAR_STATS_CREATE;
		
		// ���ߡ��ǡ���
		$this->dummydata = join('', array_fill(0, TAR_BLK_LEN, "\0"));
		rewind($this->fp);

		return true;
	}

	////////////////////////////////////////////////////////////
	//
	// �ؿ�  : tar�ե�������Ĥ���
	// ����  : �ʤ�
	// �֤���: �ʤ�
	//
	////////////////////////////////////////////////////////////
	function close()
	{
		if($this->status = TAR_STATS_CREATE)
		{
			// �Х��ʥ꡼�����1024�Х��Ƚ���
			flock($this->fp, LOCK_EX);
			fwrite($this->fp, $this->dummydata, TAR_HDR_LEN);
			fwrite($this->fp, $this->dummydata, TAR_HDR_LEN);
			flock($this->fp, LOCK_UN);

			// �ե�������Ĥ���
			if($this->arc_kind == ARCFILE_GZIP) {
				gzclose($this->fp);
			} else {
				 fclose($this->fp);
			}
		}
		else if($this->status = TAR_STATS_OPEN)
		{
			if($this->arc_kind == ARCFILE_GZIP) {
				gzclose($this->fp);
			} else {
				 fclose($this->fp);
			}
		}

		$this->status = TAR_STATS_INIT;
	}

	////////////////////////////////////////////////////////////
	//
	// �ؿ�  : ���ꤷ���ǥ��쥯�ȥ��tar�ե������Ÿ������
	// ����  : Ÿ������ե�����ѥ�����(����ɽ��)
	// �֤���: Ÿ�������ե�����̾�ΰ���
	// ��­  : ARAI�����attach�ץ饰����ѥå��򻲹ͤˤ��ޤ���
	//
	////////////////////////////////////////////////////////////
	function extract( $pattern )
	{
		if($this->status != TAR_STATS_OPEN)
			return ''; // open����Ƥ��ʤ�
		
		$files = array();
		$longname = '';

		while(1) {
			$buff = fread($this->fp,TAR_HDR_LEN);
			if(strlen($buff) != TAR_HDR_LEN ) break;

			// �ե�����̾
			if($longname != '') {
				$name     = $longname;	// LongLink�б�
				$longname = '';
			} else {
				$name = '';
				for ($i = 0; $i < TAR_HDR_NAME_LEN; $i++ ) {
					if($buff{$i + TAR_HDR_NAME_OFFSET} != '\0') {
						$name .= $buff{$i + TAR_HDR_NAME_OFFSET};
					} else {
						break;
					}
				}
			}

			$name = trim($name);
			if($name == '') break;	// Ÿ����λ

			// �����å������������Ĥġ��֥�󥯤��ִ����Ƥ���
			$checksum = '';
			$chkblanks = TAR_DATA_CHKBLANKS;
			for ($i = 0; $i < TAR_HDR_CHKSUM_LEN; $i++ )
			{
				$checksum .= $buff{$i + TAR_HDR_CHKSUM_OFFSET};
				$buff{$i + TAR_HDR_CHKSUM_OFFSET} = $chkblanks{$i};
			}
			list($checksum) = sscanf('0' . trim($checksum), "%i");

			// �����å�����η׻�
			$sum = 0;
			for($i = 0; $i < TAR_BLK_LEN; $i++ ) {
				$sum += 0xff & ord($buff{$i});
			}

			if($sum != $checksum) break;	// �����å����२�顼
				
			// ������
			$size = '';
			for ($i = 0; $i < TAR_HDR_SIZE_LEN; $i++ ) {
				$size .= $buff{$i + TAR_HDR_SIZE_OFFSET};
			}

			list($size) = sscanf('0' . trim($size), "%i");

			// ceil
			// �ǡ����֥�å���512byte�ǥѥǥ��󥰤���Ƥ���
			$pdsz = ceil($size / TAR_BLK_LEN) * TAR_BLK_LEN;

			// �ǽ���������
			$strmtime = '';
			for ($i = 0; $i < TAR_HDR_MTIME_LEN; $i++ ) {
				$strmtime .= $buff{$i + TAR_HDR_MTIME_OFFSET};
			}
			list($mtime) = sscanf('0' . trim($strmtime), "%i");

			// �����ץե饰
			$type = $buff{TAR_HDR_TYPE_OFFSET};

			if($name == TAR_DATA_LONGLINK)
			{
				// LongLink
				$buff = fread( $this->fp, $pdsz );
				$longname = substr($buff, 0, $size);
			}
			else if (preg_match("/$pattern/", $name) )
//			if ($type == 0 && preg_match("/$pattern/", $name) )
			{
				$buff = fread($this->fp, $pdsz);

				// ����Ʊ���ե����뤬������Ͼ�񤭤����
				if($fpw = @fopen($name, 'wb')) {
					fwrite($fpw, $buff, $size);
					fclose($fpw);
					chmod($name, 0666); // ǰ�Τ���ѡ��ߥå��������ꤷ�Ƥ���
					touch($name, $mtime); // �ǽ���������ν���
					$files[] = $name;
				}
			}
			else
			{
				// �ե�����ݥ��󥿤�ʤ��
				@fseek($this->fp, $pdsz, SEEK_CUR);
			}
		}
		return $files;
	}

	////////////////////////////////////////////////////////////
	//
	// �ؿ�  : tar�ե�������ɲä���
	// ����  : $dir    .. �ǥ��쥯�ȥ�̾
	//         $mask   .. �ɲä���ե�����(����ɽ��)
	//         $decode .. �ڡ���̾���Ѵ��򤹤뤫
	// �֤���: ���������ե������
	//
	////////////////////////////////////////////////////////////
	function add($dir, $mask, $decode = false)
	{
		$retvalue = 0;
		
		if ($this->status != TAR_STATS_CREATE)
			return ''; // �ե����뤬��������Ƥ��ʤ�

		unset($files);

		//  ���ꤵ�줿�ѥ��Υե�����Υꥹ�Ȥ��������
		$dp = @opendir($dir) or
			die_message($dir . ' is not found or not readable.');
		while ($filename = readdir($dp)) {
			if(preg_match("/$mask/", $filename)) {
				$files[] = $dir . $filename;
			}
		}
		closedir($dp);
		
		sort($files);

		foreach($files as $name)
		{
			// Tar�˳�Ǽ����ե�����̾
			if($decode == true )
			{
				// �ե�����̾��ڡ���̾���Ѵ��������
				$dirname  = dirname(trim($name)) . '/';
				$filename = basename(trim($name));
				if(preg_match("/^((?:[0-9A-F]{2})+)_((?:[0-9A-F]{2})+)/", $filename, $matches))
				{
					// attach�ե�����̾
					$filename = decode($matches[1]).'/'.decode($matches[2]);
				}
				else
				{
					$pattern = '^((?:[0-9A-F]{2})+)((\.txt|\.gz)*)$';
					if (preg_match("/$pattern/", $filename, $matches)) {
						$filename = decode($matches[1]).$matches[2];
						// ��ʤ������ɤ��ִ����Ƥ���
						$filename = str_replace(':','_',$filename);
						$filename = str_replace('\\','_',$filename);
					}
				}
				$filename = $dirname . $filename;
				if(function_exists('mb_convert_encoding')) {
					// �ե�����̾��ʸ�������ɤ��Ѵ�
					$filename = mb_convert_encoding($filename, PLUGIN_DUMP_FILENAME_ENCORDING);
				}
			}
			else
			{
				$filename = $name;
			}

			// �ǽ���������
			$mtime = filemtime( $name );

			// �ե�����̾Ĺ�Υ����å�
			if(strlen($filename) > TAR_HDR_NAME_LEN) {
				// LongLink�б�
				$size = strlen($filename);
				// LonkLink�إå�����
				$tar_data = $this->make_header(TAR_DATA_LONGLINK, $size, $mtime, TAR_HDR_LINK);
				// �ե��������
	 			$this->write_data(join('', $tar_data), $filename, $size);
			}

			// �ե����륵���������
			$size = filesize( $name );
			if ($size == FALSE) {
				die_message($name . ' is not found or not readable.');
				continue;	// �ե����뤬�ʤ�
			}

			// �إå�����
			$tar_data = $this->make_header($filename, $size, $mtime, TAR_HDR_FILE);

			// �ե�����ǡ����μ���
			$fpr = @fopen($name , 'rb');
			$data = fread($fpr, $size);
			fclose( $fpr );

			// �ե��������
			$this->write_data(join('', $tar_data), $data, $size);
			++$retvalue;
		}
		return $retvalue;
	}
	
	////////////////////////////////////////////////////////////
	//
	// �ؿ�  : tar�Υإå��������������
	// ����  : $filename .. �ե�����̾
	//         $size     .. �ǡ���������
	//         $mtime    .. �ǽ�������
	//         $typeflag .. TypeFlag (file/link)
	// �����: tar�إå�����
	//
	////////////////////////////////////////////////////////////
	function make_header($filename, $size, $mtime, $typeflag)
	{
		$tar_data = array_fill(0, TAR_HDR_LEN, "\0");
		
		// �ե�����̾����¸
		for($i = 0; $i < strlen($filename); $i++ )
		{
			if ($i < TAR_HDR_NAME_LEN) {
				$tar_data[$i + TAR_HDR_NAME_OFFSET] = $filename{$i};
			} else {
				break;	// �ե�����̾��Ĺ����
			}
		}

		// mode
		$modeid = TAR_DATA_MODE;
		for($i = 0; $i < strlen($modeid); $i++ ) {
			$tar_data[$i + TAR_HDR_MODE_OFFSET] = $modeid{$i};
		}

		// uid / gid
		$ugid = TAR_DATA_UGID;
		for($i = 0; $i < strlen($ugid); $i++ ) {
			$tar_data[$i + TAR_HDR_UID_OFFSET] = $ugid{$i};
			$tar_data[$i + TAR_HDR_GID_OFFSET] = $ugid{$i};
		}

		// ������
		$strsize = sprintf('%11o', $size);
		for($i = 0; $i < strlen($strsize); $i++ ) {
			$tar_data[$i + TAR_HDR_SIZE_OFFSET] = $strsize{$i};
		}

		// �ǽ���������
		$strmtime = sprintf('%o', $mtime);
		for($i = 0; $i < strlen($strmtime); $i++ ) {
			$tar_data[$i + TAR_HDR_MTIME_OFFSET] = $strmtime{$i};
		}

		// �����å�����׻��ѤΥ֥�󥯤�����
		$chkblanks = TAR_DATA_CHKBLANKS;
		for($i = 0; $i < strlen($chkblanks); $i++ ) {
			$tar_data[$i + TAR_HDR_CHKSUM_OFFSET] = $chkblanks{$i};
		}

		// �����ץե饰
		$tar_data[TAR_HDR_TYPE_OFFSET] = $typeflag;

		// �����å�����η׻�
		$sum = 0;
		for($i = 0; $i < TAR_BLK_LEN; $i++ ) {
			$sum += 0xff & ord($tar_data[$i]);
		}
		$strchksum = sprintf('%7o',$sum);
		for($i = 0; $i < strlen($strchksum); $i++ ) {
			$tar_data[$i + TAR_HDR_CHKSUM_OFFSET] = $strchksum{$i};
		}
		return $tar_data;
	}
	
	////////////////////////////////////////////////////////////
	//
	// �ؿ�  : tar�ǡ����Υե��������
	// ����  : $header .. tar�إå�����
	//         $body   .. tar�ǡ���
	//         $size   .. �ǡ���������
	// �����: �ʤ�
	//
	////////////////////////////////////////////////////////////
	function write_data($header, $body, $size)
	{
		// �ե��뤹�륵������׻����Ƥ���
		$fixsize = ceil($size / TAR_BLK_LEN) * TAR_BLK_LEN;
		$fixsize -= $size;

		flock($this->fp, LOCK_EX);
		fwrite($this->fp, $header, TAR_HDR_LEN); // �إå�����
		fwrite($this->fp, $body, $size); // �ǡ�������

		// ������Ĵ��(���ޤä���ʬ��0�ǥե���)
		fwrite($this->fp, $this->dummydata, $fixsize); // Padding

		flock($this->fp, LOCK_UN);
	}
}
?>
