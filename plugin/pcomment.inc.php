<?php
// $Id: pcomment.inc.php,v 1.5 2002/12/07 09:43:43 panda Exp $
/*
Last-Update:2002-09-12 rev.15

*�ץ饰���� pcomment
���ꤷ���ڡ����˥����Ȥ�����

*Usage
 #pcomment([�ڡ���̾][,ɽ�����륳���ȿ�][,���ץ����])

*�ѥ�᡼��
-�ڡ���̾~
 ��Ƥ��줿�����Ȥ�Ͽ����ڡ�����̾��
-ɽ�����륳���ȿ�~
 ���Υ����Ȥ򲿷�ɽ�����뤫(0������)

*���ץ����
-above~
 �����Ȥ�ե�����ɤ�����ɽ��(��������������)
-below~
 �����Ȥ�ե�����ɤθ��ɽ��(��������������)
-reply~
 2��٥�ޤǤΥ����Ȥ˥�ץ饤��Ĥ���radio�ܥ����ɽ��

*/
// �ڡ���̾�Υǥե����(%s��$vars['page']������)
define('PCMT_PAGE','[[������/%s]]');
//
// �ڡ����Υ��ƥ���(����������������)
define('PCMT_CATEGORY','[[:Comment]]');
//
// ɽ�����륳���ȿ��Υǥե����
define('PCMT_NUM_COMMENTS',10);
//
// �����Ȥ�̾���ƥ����ȥ��ꥢ�Υ�����
define('PCMT_COLS_NAME',15);
//
// �����ȤΥƥ����ȥ��ꥢ�Υ�����
define('PCMT_COLS_COMMENT',70);
//
// ����������� 1:���� 0:��Ƭ
define('PCMT_INSERT_INS',1);
//
//�����Ȥ������ե����ޥå�
define('PCMT_FORMAT_NAME','[[%s]]');
define('PCMT_FORMAT_MSG','%s');
define('PCMT_FORMAT_DATE','SIZE(10){%s}');
// \x08�ϡ���Ƥ��줿ʸ������˸���ʤ�ʸ���Ǥ���Фʤ�Ǥ⤤����
define("PCMT_FORMAT","\x08MSG\x08 -- \x08NAME\x08 \x08DATE\x08");

function plugin_pcomment_init() {
	$_plugin_pcmt_messages = array(
		'_pcmt_btn_name' => '��̾��: ',
		'_pcmt_btn_comment' => '�����Ȥ�����',
		'_pcmt_msg_comment' => '������: ',
		'_pcmt_msg_recent' => '�ǿ���%d���ɽ�����Ƥ��ޤ���',
		'_pcmt_msg_all' => '�����ȥڡ����򻲾�',
		'_pcmt_msg_none' => '�����ȤϤ���ޤ���',
		'_title_pcmt_collided' => '$1 �ǡڹ����ξ��ۤ͡������ޤ���',
		'_msg_pcmt_collided' => '���ʤ������Υڡ������Խ����Ƥ���֤ˡ�¾�οͤ�Ʊ���ڡ����򹹿����Ƥ��ޤä��褦�Ǥ���<br />
�����Ȥ��ɲä��ޤ��������㤦���֤���������Ƥ��뤫�⤷��ޤ���<br />',
	);
  set_plugin_messages($_plugin_pcmt_messages);
}
function plugin_pcomment_action() {
	global $post;

	$retval = '';
	if($post['msg']) { $retval = pcmt_insert(); }
	return $retval;
}

function plugin_pcomment_convert() {
	global $script,$vars,$BracketName;
	global $_pcmt_btn_name, $_pcmt_btn_comment, $_pcmt_msg_comment, $_pcmt_msg_all, $_pcmt_msg_recent;

	//�����
	$ret = '';

	//�ѥ�᡼���Ѵ�
	$args = func_get_args();
	array_walk($args, 'pcmt_check_arg', &$params);
	unset($args);

	//ʸ��������
	list($page, $count) = $params['arg'];
	if ($page == '') { $page = sprintf(PCMT_PAGE,strip_bracket($vars['page'])); }

	$_page = get_fullname($page,$vars['page']);
	if (!preg_match("/^$BracketName$/",$_page))
		return 'invalid page name.';

	if ($count == 0 and $count !== '0') { $count = PCMT_NUM_COMMENTS; }

	//���������
	$dir = PCMT_INSERT_INS;
	if ($params['above']) { $dir = 1; }
	if ($params['below']) { $dir = 0; } //ξ�����ꤵ�줿�鲼�� (^^;

	//�����Ȥ����
	list($comments, $digest) = pcmt_get_comments($_page,$count,$dir,$params['reply']);

	//�ե������ɽ��
	if($params['noname']) {
		$title = $_pcmt_msg_comment;
		$name = '';
	} else {
		$title = $_pcmt_btn_name;
		$name = '<input type="text" name="name" size="'.PCMT_COLS_NAME.'" />';
	}

	$radio = $params['reply'] ? '<input type="radio" name="reply" value="0" checked />' : '';
	$comment = '<input type="text" name="msg" size="'.PCMT_COLS_COMMENT.'" />';

	//XSS�ȼ������� - ���������褿�ѿ��򥨥�������
	$f_page = htmlspecialchars($page);
	$f_refer = htmlspecialchars($vars['page']);
	$f_nodate = htmlspecialchars($params['nodate']);

	$form = <<<EOD
  <div>
  <input type="hidden" name="digest" value="$digest" />
  <input type="hidden" name="plugin" value="pcomment" />
  <input type="hidden" name="refer" value="$f_refer" />
  <input type="hidden" name="page" value="$f_page" />
  <input type="hidden" name="nodate" value="$f_nodate" />
  <input type="hidden" name="dir" value="$dir" />
  $radio $title $name $comment
  <input type="submit" value="$_pcmt_btn_comment" />
  </div>
EOD;
	$link = $_page;
	if (!is_page($_page)) {
		$recent = $_pcmt_msg_none;
	} else {
		if ($_pcmt_msg_all != '')
			$link = preg_replace('/^(\[\[)?/',"$1$_pcmt_msg_all>[[","$_page]]");
		$recent = '';
		if ($count > 0) { $recent = sprintf($_pcmt_msg_recent,$count); }
	}
	$link = make_link($link);
	return $dir ?
		"<div><p>$recent $link</p>\n<form action=\"$script\" method=\"post\">$comments$form</form></div>" :
		"<div><form action=\"$script\" method=\"post\">$form$comments</form>\n<p>$recent $link</p></div>";
}

function pcmt_insert($page) {
	global $post,$vars,$script,$now,$do_backup,$BracketName;
	global $_title_updated;

	$page = $post['page'];
	if (!preg_match("/^$BracketName$/",$page))
		return array('msg'=>'invalid page name.','body'=>'cannot add comment.','collided'=>TRUE);

	$ret['msg'] = $_title_updated;

	//ʸ���������
	$msg = user_rules_str($post['msg']);

	//�����ȥե����ޥåȤ�Ŭ��
	$msg = sprintf(PCMT_FORMAT_MSG, rtrim($post['msg']));
	$name = ($post['name'] == '') ? '' :  sprintf(PCMT_FORMAT_NAME, $post['name']);
	$date = ($post['nodate'] == '1') ? '' : sprintf(PCMT_FORMAT_DATE, $now);
	if ($date != '' or $name != '') { 
		$msg = str_replace("\x08MSG\x08", $msg,  PCMT_FORMAT);
		$msg = str_replace("\x08NAME\x08",$name, $msg);
		$msg = str_replace("\x08DATE\x08",$date, $msg);
	}
	if ($post['reply'] or !is_page($page)) {
		$msg = preg_replace('/^\-+/','',$msg);
	}
	$msg = rtrim($msg);

	if (!is_page($page)) {
		$new = PCMT_CATEGORY.' '.htmlspecialchars($post['refer'])."\n\n-$msg\n";
	} else {
		//�ڡ������ɤ߽Ф�
		$data = file(get_filename(encode($page)));
		$old = join('',$data);

		$reply = $post['reply'];
		// �����ξ��ͤ򸡽�
		if (md5($old) != $post['digest']) {
			$ret['msg'] = $_title_paint_collided;
			$ret['body'] = $_msg_paint_collided;
			$reply = 0; //��ץ饤�Ǥʤ�����
		}

		// �ڡ���������Ĵ��
		if (substr($data[count($data) - 1],-1,1) != "\n") { $data[] = "\n"; }

		//����������
		$level = 1;
		if ($post['dir'] == '1') {
			$pos = count($data) - 1;
			$step = -1;
		} else {
			$pos = -1;
			foreach ($data as $line) {
				if (preg_match('/^\-/',$line)) break;
				$pos++;
			}
			$step = 1;
		}
		//��ץ饤��Υ����Ȥ򸡺�
		if ($reply > 0) {
			while ($pos >= 0 and $pos < count($data)) {
				if (preg_match('/^(\-{1,2})(?!\-)/',$data[$pos], $matches) and --$reply == 0) {
					$level = strlen($matches[1]) + 1; //���������٥�
					break;
				}
				$pos += $step;
			}
			while (++$pos < count($data)) {
				if (preg_match('/^(\-{1,2})(?!\-)/',$data[$pos], $matches)) {
					if (strlen($matches[1]) < $level) { break; }
				}
			}
		} else {
			$pos++;
		}
		//��Ƭʸ��
		$head = str_repeat('-',$level);
		//�����Ȥ�����
		array_splice($data,$pos,0,"$head$msg\n");
		$new = join('',$data);

		// ��ʬ�ե�����κ���
		file_write(DIFF_DIR,$page,do_diff($old,$new));

		// �Хå����åפκ���
		if ($do_backup) {
			$oldtime = filemtime(get_filename(encode($page)));
			make_backup(encode($page).'.txt', $old, $oldtime);
		}
	}

	// �ե�����ν񤭹���
	file_write(DATA_DIR, $page, $new);

	// is_page�Υ���å���򥯥ꥢ���롣
	is_page($page, true);

	$vars['page'] = $post['page'] = $post['refer'];

	return $ret;
}
//���ץ�������Ϥ���
function pcmt_check_arg($val, $key, &$params) {
	$valid_args = array('noname','nodate','below','above','reply');
	foreach ($valid_args as $valid) {
		if (strpos($valid, strtolower($val)) === 0) {
			$params[$valid] = 1;
			return;
		}
	}
	$params['arg'][] = $val;
}
function pcmt_get_comments($page,$count,$dir,$reply) {
	$data = @file(get_filename(encode($page)));

	if (!is_array($data)) { return array('',0); }

	$digest = md5(join('',$data));

	//�����Ȥ���ꤵ�줿��������ڤ���
	if ($dir) { $data = array_reverse($data); }
	$num = $cnt = 0;
	$cmts = array();
	foreach ($data as $line) {
		if ($count > 0 and $dir and $cnt == $count) { break; }
		if (preg_match('/^(\-{1,2})(?!\-)(.*)$/', $line, $matches)) {
			if ($count > 0 and strlen($matches[1]) == 1 and ++$cnt > $count) { break; }
			if ($reply) {
				++$num;
				$cmts[] = "$matches[1]\x01$num\x02$matches[2]\n";
			} else {
				$cmts[] = $line;
			}
		} else {
			$cmts[] = $line;
		}
	}
	$data = $cmts;
	if ($dir) { $data = array_reverse($data); }
	unset($cmts);

	//�����Ȥ�����Υǡ������������
	while (count($data) > 0 and substr($data[0],0,1) != '-') { array_shift($data); }

	//html�Ѵ�
	$comments = convert_html(join('', $data));
	unset($data);

	//�����Ȥ˥饸���ܥ���ΰ���Ĥ���
	if ($reply) {
		$comments = preg_replace("/\x01(\d+)\x02/",'<input type="radio" name="reply" value="$1" />', $comments);
	}
	return array($comments,$digest);
}
?>
