<?php
/*

Last-Update:2002-12-03 rev.3

���񼰤˰ܹԤ���ץ饰����

usage:
http://..../pukiwiki.php?plugin=convertpage

�ʤˤ򤹤뤫��
��./wiki/�ǥ��쥯�ȥ�˥Хå����åץե�����(�ڡ���̾.bak)����ޤ���
��Ϣ³�����Ƭ>�򡢺ǽ�ΤҤȤĤ�Ĥ��Ƽ������ޤ���
���ͥ��Ȳ�ǽ�ʥ֥�å����Ǥμ��ιԤ˶��԰ʳ���¾���Ǥ��и������Ȥ��ˡ�
  ���Ԥ�֤ˤϤ��ߤ����ʬ�Ǥ��ޤ���
����Ƭ-/+��³���ƥ�������и������Ȥ��ˡ����ڡ������������ޤ���
��dl��::����:|�˽������ޤ���
�����Ԥ����ι����������������ޤ���

*/

define('CONVERTPAGE_LOGPAGE',':ConvertLog');

function plugin_convertpage_init()
{
	$messages = array('_convert_messages'=>array(
		'title_convertpage' => 'PukiWiki �񼰥���С���',
		'msg_invalidparam' => '�ѥ�᡼���������Ǥ�',
		'msg_convert' => '�ڡ������Ƥ��Ѵ�(wiki/*.txt->wiki/*.bak)',
		'msg_revert' => '�ѹ��򸵤��᤹(wiki/*.bak -> wiki/*.txt)',
		'msg_clean' => '�Хå����åפ���(wiki/*.bak)',
		'msg_adminpass' => '�����ԥѥ����',
		'err_alreadypage' => '���顼:���Ǥ� '.make_link(CONVERTPAGE_LOGPAGE) . ' ��¸�ߤ��ޤ���',
		'err_alreadybak' => '���顼:wiki/$1.bak �ե����뤬���Ǥ�¸�ߤ��ޤ���',
		'err_makebak' => '���顼:wiki/$1.bak�ե����뤬���ޤ���',
		'btn_submit' => '�¹�',
	));
	set_plugin_messages($messages);
}

function plugin_convertpage_action()
{
	global $script,$post,$vars,$adminpass;
	global $_convert_messages;
	
	if (empty($vars['action']) or empty($post['adminpass']) or md5($post['adminpass']) != $adminpass)
	{
		$body = <<<EOD
<form method="POST" action="$script">
 <div>
  <input type="hidden" name="plugin" value="convertpage" />
  <input type="radio" name="action" value="convert" />
  {$_convert_messages['msg_convert']}<br />
  <input type="radio" name="action" value="revert" />
  {$_convert_messages['msg_revert']}<br />
  <input type="radio" name="action" value="clean" />
  {$_convert_messages['msg_clean']}<br />
  {$_convert_messages['msg_adminpass']}
  <input type="password" name="adminpass" size="20" value="" /><br />
  <input type="submit" value="{$_convert_messages['btn_submit']}" />
 </div>
</form>
EOD;
		return array(
			'msg'=>$_convert_messages['title_convertpage'],
			'body'=>$body
		);
	}
	else if ($vars['action'] == 'convert')
	{
		return convertpage_convert();
	}
	else if ($vars['action'] == 'revert')
	{
		return convertpage_revert();
	}
	else if ($vars['action'] == 'clean')
	{
		return convertpage_clean();
	}
	
	return array(
		'msg'=>$_convert_messages['title_convertpage'],
		'body'=>$_convert_messages['msg_invalidparam']
	);
}

//�Ѵ�
function convertpage_convert()
{
	global $post,$vars,$whatsnew;
	global $_convert_messages;
	
	set_time_limit(0); // �����ڤ��ɻ�
	
	$pages = get_existpages();
	
	if (is_page(CONVERTPAGE_LOGPAGE))
		return array(
			'msg' =>$_convert_messages['title_convertpage'],
			'body'=>$_convert_messages['err_alreadypage']
		);
	
	// *.bak�ե����뤬¸�ߤ������Ѵ�����ߤ���
	foreach ($pages as $page) {
		$file = get_filename($page);
		$bak = str_replace('.txt','.bak',$file);
		if (file_exists($bak)) {
			$body = str_replace('$1',$page,$_convert_messages['err_alreadybak']); // $1���ִ�
			return array(
				'msg' =>$_convert_messages['title_convertpage'],
				'body'=>$body
			);
		}
	}
	
	// *.bak�ե�������������
	foreach ($pages as $page) {
		$file = get_filename($page);
		$bak = str_replace('.txt','.bak',$file);
		$stat = stat($file);
		if ($stat['size'] == 0)
			continue;
		
		if (!copy($file,$bak)) {
			$body = str_replace('$1',$page,$_convert_messages['err_makebak']); // $1���ִ�
			return array(
				'msg' =>$_convert_messages['title_convertpage'],
				'body'=>$body
			);
		}
	}
	$convert = array(); //���������ե�����
	
	// �Ѵ�
	foreach ($pages as $page)
	{
		$data = get_source($page);
		
		page_convert($page,$data,$convert);
		
		//�ڡ����򹹿�
		$time = get_filetime($page);
		unlink(get_filename($page));
		$page = strip_bracket($page);
		page_write($page,$data);
		touch(get_filename($page),$time);
	}
	
	// ���
	$count = count($convert);
	$postdata = join('',get_source(CONVERTPAGE_LOGPAGE));
	$postdata .= $_convert_messages['title_convertpage']."\n\n";
	$postdata .= "���������ڡ�����:$count\n";
	if (count($convert) > 0) {
		$postdata .= "\n----\n���������ڡ����ϰʲ��ΤȤ���Ǥ���\n";
		$postdata .= join("\n",$convert);
	}
	
	file_write(DATA_DIR,CONVERTPAGE_LOGPAGE,$postdata);
	
	$vars['page'] = CONVERTPAGE_LOGPAGE;
	return array('msg' =>'','body'=>'');
}
function page_convert($page,&$data,&$convert)
{
	$bq = $last_bq = 0;
	$block = $last_block = '';
	$result = array();
	$modify = array();
	
	foreach ($data as $line)
	{
		//��Ƭ�񼰤�����å�
		$head = substr($line,0,1);
		$block = '';
		if (strpos('-+:>',$head) !== FALSE) { //���ιԤ򿩤��֥�å�
			$block = $head;
		}
		
		//�ͥ��Ȳ�ǽ�ʥ֥�å����Ǥ�ľ��ιԤ��ɤ���
		if (
			$last_block != '' and               //���ιԤ�"���ιԤ򿩤��֥�å����Ǥ�
			$block != $last_block and           //���ιԤȸ��߹Ԥμ��ब��ä�
			($line != "\n" and $line != "\r\n") //���߹Ԥ����ԤǤʤ����
		)
		{
			$result[] = "\n"; //���Ԥ�Ϥ���
			$modify['nest'] = '';
		}
		
		//��Ƭ+/-��ľ��Υ�����򥹥ڡ����ǥ���������
		if (preg_match("/^([\-\+]{1,3})(~.*)$/", $line, $matches)) { //�ޥå����ʤ��ä���̵��
			$line = "{$matches[1]} {$matches[2]}\n";
			$modify['tilde'] = '--modify (+/-)...~. ';
		}
		
		//�֥�å��������Ȥν���
		if ($head == '>' and preg_match("/^(>{1,3})(.*)$/",$line,$matches)) { //�ޥå����ʤ��ä���̵��
			$bq = strlen($matches[1]);
			if ($bq == $last_bq) {
				$line = "{$matches[2]}\n";
				$modify['bq'] = '--modify blockquote.';
			}
		}
		else {
			$bq = 0;
		}
		
		//����ꥹ�Ȥν���
		if ($head == ':' and preg_match("/^:([^:]+):(.*)/",$line,$matches)) { //�ޥå����ʤ��ä���̵��
			$line = ":{$matches[1]}|{$matches[2]}\n";
			$modify['dl'] = '--modify dl.';
		}
		
		$result[] = $line;

		$last_bq = $bq;
		$last_block = $block;
	}
	if (count($modify)) {
		$convert[] = "-[[$page]]\n".join("\n",$modify);
	}
	
	$data = join('',$result);
	//�������Υ��������
	$data = preg_replace("/~(\n\n)/",'$1',$data);
}
?>