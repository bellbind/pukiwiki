<?php
// $Id: paint.inc.php,v 1.1 2002/12/05 05:02:27 panda Exp $
/*
Last-Update:2002-10-30 rev.20

*�ץ饰���� paint
��������

*Usage
 #paint(width,height)

*�ѥ�᡼��
-width,height~
 �����Х������ȹ⤵

*/

// upload dir(must set end of /) attach.inc.php�ȹ�碌��
define('PAINT_UPLOAD_DIR','./attach/');
//
// character encoding
define('PAINT_ENCODING','EUC-JP');
//
// ����������� 1:����� 0:��θ�
define('PAINT_INSERT_INS',0);
//
// �ǥե���Ȥ������ΰ�����ȹ⤵
define('PAINT_DEFAULT_WIDTH',80);
define('PAINT_DEFAULT_HEIGHT',60);
//
// �����ΰ�����ȹ⤵��������
define('PAINT_MAX_WIDTH',320);
define('PAINT_MAX_HEIGHT',240);
//
// ���ץ�å��ΰ�����ȹ⤵ 50x50̤�����̥�����ɥ�������
define('PAINT_APPLET_WIDTH',800);
define('PAINT_APPLET_HEIGHT',300);
//
//�����Ȥ������ե����ޥå�
define('PAINT_FORMAT_NAME','[[%s]]');
define('PAINT_FORMAT_MSG','%s');
define('PAINT_FORMAT_DATE','SIZE(10){%s}');
//��å�������������
define('PAINT_FORMAT',"\x08MSG\x08 -- \x08NAME\x08 \x08DATE\x08");
//��å��������ʤ����
define('PAINT_FORMAT_NOMSG',"\x08NAME\x08 \x08DATE\x08"); 

function plugin_paint_init() {
	$messages = array('_paint_messages'=>array(
		'field_name'    => '��̾��',
		'field_filename'=> '�ե�����̾',
		'field_comment' => '������',
		'btn_submit'    => 'paint',
		'msg_max'       => '(���� %d x %d)',
		'msg_title'     => 'Paint and Attach to $1',
		'msg_title_collided' => '$1 �ǡڹ����ξ��ۤ͡������ޤ���',
		'msg_collided'  => '���ʤ����������Խ����Ƥ���֤ˡ�¾�οͤ�Ʊ���ڡ����򹹿����Ƥ��ޤä��褦�Ǥ���<br />
�����ȥ����Ȥ��ɲä��ޤ��������㤦���֤���������Ƥ��뤫�⤷��ޤ���<br />',
	));
  set_plugin_messages($messages);
}
function plugin_paint_action() {
	global $script,$vars,$HTTP_POST_FILES;
	global $_paint_messages;

	//����ͤ�����
	$retval['msg'] = $_paint_messages['msg_title'];
	$retval['body'] = '';

	if(is_uploaded_file($HTTP_POST_FILES['attach_file']['tmp_name'])) {
		//BBSPaiter.jar�ϡ�shift-jis�����Ƥ����äƤ��롣���ݤʤΤǥڡ���̾�ϥ��󥳡��ɤ��Ƥ�������������褦�ˤ�����
		$vars['page'] = $vars['refer'] = decode($vars['refer']);

		$filename = $vars['filename'];
		if(function_exists('mb_convert_encoding'))
			$filename = mb_convert_encoding($filename,PAINT_ENCODING,'auto');

		//�ե�����̾�ִ�
		$attachname = preg_replace('/^[^\.]+/', $filename, $HTTP_POST_FILES['attach_file']['name']);
		//���Ǥ�¸�ߤ�����硢 �ե�����̾��'_0','_1',...���դ��Ʋ���(��©)
		$count = '_0';
		while (file_exists(PAINT_UPLOAD_DIR.encode($vars['refer']).'_'.encode($attachname))) {
			$attachname = preg_replace('/^[^\.]+/', $filename.$count++, $HTTP_POST_FILES['attach_file']['name']);
		}

		$HTTP_POST_FILES['attach_file']['name'] = $attachname;

		$retval = do_plugin_action('attach');
		$retval = insert_ref($HTTP_POST_FILES['attach_file']['name']);
	} else {
		if (!function_exists('mb_convert_encoding'))
			$message = 'cannot use KANJI in filename.';

		$link = '<p>'.make_link($vars['refer']).'</p>';;
		$raw_refer = rawurlencode($vars['refer']);

		$w = PAINT_APPLET_WIDTH; $h = PAINT_APPLET_HEIGHT;

		//XSS�ȼ������� - ���������褿�ѿ��򥨥�������
		$f_w = (is_numeric($vars['width']) and $vars['width'] > 0) ? $vars['width'] : PAINT_DEFAULT_WIDTH;
		$f_h = (is_numeric($vars['height']) and $vars['height'] > 0) ? $vars['height'] : PAINT_DEFAULT_HEIGHT;
		$f_refer = encode($vars['refer']);	// BBSPainter.jar��shift-jis���Ѵ�����Τ����
		$f_digest = htmlspecialchars($vars['digest']);
		$f_no = htmlspecialchars($vars['paint_no']) + 0;

		if ($f_w > PAINT_MAX_WIDTH) { $f_w = PAINT_MAX_WIDTH; }
		if ($f_h > PAINT_MAX_HEIGHT) { $f_h = PAINT_MAX_HEIGHT; }

		$retval['body'] = <<<EOD
 <div>
 $link
 $message
 <applet codebase="." archive="BBSPainter.jar" code="Main.class" width="$w" height="$h">
 <param name="size" value="$f_w,$f_h" />
 <param name="action" value="$script" />
 <param name="image" value="attach_file" />
 <param name="form1" value="filename={$_paint_messages['field_filename']}=!" />
 <param name="form2" value="yourname={$_paint_messages['field_name']}" />
 <param name="comment" value="msg={$_paint_messages['field_comment']}" />
 <param name="param1" value="plugin=paint" />
 <param name="param2" value="refer=$f_refer" />
 <param name="param3" value="digest=$f_digest" />
 <param name="param4" value="max_file_size=1000000" />
 <param name="param5" value="paint_no=$f_no" />
 <param name="enctype" value="multipart/form-data" />
 <param name="return.URL" value="$script?$raw_refer" />
 </applet>
 </div>
EOD;
	}
	return $retval;
}

function plugin_paint_convert() {
	global $script,$vars,$digest;
	global $_paint_messages;
	static $paint_no = 0;

	//�����
	$ret = '';
	
	$paint_no++;

	//ʸ��������
	$args = func_get_args();
	if (count($args) >= 2) {
		$width = array_shift($args);
		$height = array_shift($args);
	}
	if (!is_numeric($width) or $width == 0) $width = PAINT_DEFAULT_WIDTH;
	if (!is_numeric($height) or $height == 0) $height = PAINT_DEFAULT_HEIGHT;

	//XSS�ȼ������� - ���������褿�ѿ��򥨥�������
	$f_page = htmlspecialchars($vars['page']);

	$max = sprintf($_paint_messages['msg_max'],PAINT_MAX_WIDTH,PAINT_MAX_HEIGHT);

	$ret = <<<EOD
  <form action="$script" method="post">
  <div>
  <input type="hidden" name="paint_no" value="$paint_no" />
  <input type="hidden" name="digest" value="$digest" />
  <input type="hidden" name="plugin" value="paint" />
  <input type="hidden" name="refer" value="$f_page" />
  <input type="text" name="width" size="3" value="$width" accesskey="w" />
  x
  <input type="text" name="height" size="3" value="$height" accesskey="h" />
  $max
  <input type="submit" value="{$_paint_messages['btn_submit']}" />
  </div>
  </form>
EOD;
	return $ret;
}

function insert_ref($filename) {
	global $script,$vars,$now,$do_backup;
	global $_paint_messages;

	$ret['msg'] = $_paint_messages['msg_title'];

	$msg = sprintf(PAINT_FORMAT_MSG, rtrim($vars['msg']));

	if ($vars['yourname'] != '') {
		$name = sprintf(PAINT_FORMAT_NAME, $vars['yourname']);
	}
	$date = sprintf(PAINT_FORMAT_DATE, $now);

	if(function_exists('mb_convert_encoding')) {
		$msg = mb_convert_encoding($msg, PAINT_ENCODING, 'auto');
		$name = mb_convert_encoding($name, PAINT_ENCODING, 'auto');
	}

	$msg = trim($msg);
	$msg = ($msg == '') ?
		PAINT_FORMAT_NOMSG :
		str_replace("\x08MSG\x08", $msg, PAINT_FORMAT);
	$msg = str_replace("\x08NAME\x08",$name, $msg);
	$msg = str_replace("\x08DATE\x08",$date, $msg);
	//�֥�å��˿����ʤ��褦�ˡ�#img��ľ����\n��2�Ľ񤤤Ƥ�����
	$msg = "#ref($filename,wrap,around)\n".trim($msg)."\n\n#img(,clear)\n";

	$postdata_old = get_source($vars['refer']);
	$postdata = '';
	$paint_no = 0; //'#paint'�νи����
	foreach ($postdata_old as $line)
	{
		if (!PAINT_INSERT_INS) $postdata .= $line;
		if (preg_match('/^#paint/',$line) and (++$paint_no == $vars['paint_no'])) {
				$postdata .= $msg;
		}
		if (PAINT_INSERT_INS) $postdata .= $line;
	}
	
	// �����ξ��ͤ򸡽�
	if (md5(join('',$postdata_old)) != $vars['digest']) {
		$ret['msg'] = $_paint_messages['msg_title_collided'];
		$ret['body'] = $_paint_messages['msg_collided'];
	}

	paint_page_write($vars['refer'],$postdata);

	return $ret;
}
// �ڡ����ν���
function paint_page_write($page,$postdata) {
	global $do_backup,$del_backup;
	
	$encode = encode($page);
	$postdata = user_rules_str($postdata);
	
	// ��ʬ�ե�����κ���
	$oldpostdata = is_page($page) ? join('',get_source($page)) : "\n";
	$diffdata = ($postdata != '') ? do_diff($oldpostdata,$postdata) : '';
	file_write(DIFF_DIR,$page,$diffdata);
	
	// �Хå����åפκ���
	$oldposttime = is_page($page) ? filemtime(get_filename($encode)) : time();
	
	//������Ƥ����ΤȤ���$del_backup��TRUE�ʤ�ХХå����åפ���
	if ($del_backup and $postdata == '')
		backup_delete(BACKUP_DIR.$encode.'.txt');
	//�Хå����åפ�Ԥ�����ΤȤ������Ǥˤ���ڡ�����Хå����å�
	else if ($do_backup and is_page($page))
		make_backup($encode.'.txt',$oldpostdata,$oldposttime);
	
	// �ե�����ν񤭹���
	file_write(DATA_DIR,$page,$postdata);
	
	// is_page�Υ���å���򥯥ꥢ���롣
	is_page($page,true);
}
?>
