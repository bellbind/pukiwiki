<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: keitai.ini.php,v 1.3 2004/07/10 14:34:42 henoheno Exp $
//
// PukiWiki setting file (Cell phones, PDAs and other thin clients)

/////////////////////////////////////////////////
// max_size (SKIN�ǻ���)

$max_size = 5;	// SKIN�ǻ���, KByte (default)

$matches = array();

// Browser-name only
$ua_name  = $user_agent['name'];
$ua_vers  = $user_agent['vers'];
$ua_agent = $user_agent['agent'];
switch ($ua_name) {

	// NetFront / Compact NetFront
	//   DoCoMo Net For MOBILE: ��⡼���б�HTML�ιͤ���: �桼�������������
	//   http://www.nttdocomo.co.jp/mc-user/i/tag/imodetag.html
	//   DDI POCKET: ����饤��ʥå�: AirH"PHONE�ѥۡ���ڡ����κ�����ˡ
	//   http://www.ddipocket.co.jp/p_s/products/airh_phone/homepage.html
	case 'NetFront':
	case 'CNF':
	case 'DoCoMo':
	case 'Opera': // Performing CNF compatible
		if (preg_match('#\b[cC]([0-9]+)\b#', $ua_agent, $matches))
			$max_size = $matches[1];	// Cache max size
		break;

	// Vodafone ���ѻ���: �桼��������������ȤˤĤ���
	// http://www.dp.j-phone.com/dp/tool_dl/web/useragent.php
	case 'J-PHONE':
		if (preg_match('#\bProfile/#', $ua_agent)) {
			$max_size = 12; // �ѥ��å��б���
		} else {
			$max_size =  6; // �ѥ��å����б���
		}
		break;

}

// Browser-name + version
switch ("$ua_name/$ua_vers") {
	case 'DoCoMo/2.0':	$max_size = min($max_size, 30); break;
}
unset($matches, $ua_name, $ua_vers, $ua_agent);


/////////////////////////////////////////////////
// ������ե�����ξ��
define('SKIN_FILE',SKIN_DIR.'keitai.skin.'.LANG.'.php');

/////////////////////////////////////////////////
// �����Ȥ���ڡ������ɤ߹��ߤ�ɽ��������
$load_template_func = 0;

/////////////////////////////////////////////////
// ����ʸ�����ʬ������
$search_word_color = 0;

/////////////////////////////////////////////////
// �����ڡ�����Ƭʸ������ǥå�����Ĥ���
$list_index = 0;

/////////////////////////////////////////////////
// �ꥹ�ȹ�¤�κ��ޡ�����
$_ul_left_margin = 0;   // �ꥹ�ȤȲ��̺�ü�Ȥδֳ�(px)
$_ul_margin = 16;       // �ꥹ�Ȥγ��ش֤δֳ�(px)
$_ol_left_margin = 0;   // �ꥹ�ȤȲ��̺�ü�Ȥδֳ�(px)
$_ol_margin = 16;       // �ꥹ�Ȥγ��ش֤δֳ�(px)
$_dl_left_margin = 0;   // �ꥹ�ȤȲ��̺�ü�Ȥδֳ�(px)
$_dl_margin = 16;        // �ꥹ�Ȥγ��ش֤δֳ�(px)
$_list_pad_str = '';

/////////////////////////////////////////////////
// cols: �ƥ����ȥ��ꥢ�Υ����� rows: �Կ�

$cols = 22; $rows = 5;	// i_mode
$cols = 24; $rows = 20; // jphone

/////////////////////////////////////////////////
// �硦�����Ф������ܼ�������󥯤�ʸ��
$top = '';

/////////////////////////////////////////////////
// ��Ϣ�ڡ���ɽ���Υڡ���̾�ζ��ڤ�ʸ��
$related_str = "\n ";

/////////////////////////////////////////////////
// �����롼��Ǥδ�Ϣ�ڡ���ɽ���Υڡ���̾�ζ��ڤ�ʸ��
$rule_related_str = "</li>\n<li>";

/////////////////////////////////////////////////
// ��ʿ���Υ���
$hr = '<hr>';

/////////////////////////////////////////////////
// ʸ��������ľ����ɽ�����륿��
$note_hr = '<hr>';

/////////////////////////////////////////////////
// ��Ϣ�����󥯤���ɽ������(��ô��������ޤ�)
$related_link = 0;

/////////////////////////////////////////////////
// WikiName,BracketName�˷в���֤��ղä���
$show_passage = 0;

/////////////////////////////////////////////////
// ���ɽ���򥳥�ѥ��Ȥˤ���
$link_compact = 1;

/////////////////////////////////////////////////
// �ե������ޡ�������Ѥ���
$usefacemark = 0;

/////////////////////////////////////////////////
// accesskey (SKIN�ǻ���)
$accesskey = 'accesskey';

/////////////////////////////////////////////////
// �桼������롼��
//
//  ����ɽ���ǵ��Ҥ��Ƥ���������?(){}-*./+\$^|�ʤ�
//  �� \? �Τ褦�˥������Ȥ��Ƥ���������
//  �����ɬ�� / ��ޤ�Ƥ�����������Ƭ����� ^ ��Ƭ�ˡ�
//  ��������� $ ����ˡ�
///////////////////////////////////////////////////
// �桼������롼��(����С��Ȼ����ִ�)
$line_rules = array(
"COLOR\(([^\(\)]*)\){([^}]*)}" => '<font color="$1">$2</font>',
"SIZE\(([^\(\)]*)\){([^}]*)}" => '$2',
"COLOR\(([^\(\)]*)\):((?:(?!COLOR\([^\)]+\)\:).)*)" => '<font color="$1">$2</font>',
"SIZE\(([^\(\)]*)\):((?:(?!SIZE\([^\)]+\)\:).)*)" => '$2',
"%%%(?!%)((?:(?!%%%).)*)%%%" => '<ins>$1</ins>',
"%%(?!%)((?:(?!%%).)*)%%" => '<del>$1</del>',
"'''(?!')((?:(?!''').)*)'''" => '<em>$1</em>',
"''(?!')((?:(?!'').)*)''" => '<strong>$1</strong>',
'&amp;br;' => '<br>',
);

/////////////////////////////////////////////////
// $script��û��
if (preg_match('#([^/]+)$#',$script,$matches)) {
	$script = $matches[1];
}
?>
