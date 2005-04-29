<?php
// PukiWiki - Yet another WikiWikiWeb clone.
// $Id: rules.ini.php,v 1.5 2005/04/29 09:47:40 henoheno Exp $
// Copywrite (C) 2003-2005 PukiWiki Developers Team
// License: GPL v2 or (at your option) any later version
//
// PukiWiki setting file

/////////////////////////////////////////////////
// �����ִ��롼�� (���������ִ�)
// $usedatetime = 1�ʤ������ִ��롼�뤬Ŭ�Ѥ���ޤ�
// ɬ�פΤʤ����� $usedatetime��0�ˤ��Ƥ���������
$datetime_rules = array(
	'&amp;_now;'	=> format_date(UTIME),
	'&amp;_date;'	=> get_date($date_format),
	'&amp;_time;'	=> get_date($time_format),
);

/////////////////////////////////////////////////
// �桼������롼��(��¸�����ִ�)
//  ����ɽ���ǵ��Ҥ��Ƥ���������?(){}-*./+\$^|�ʤ�
//  �� \? �Τ褦�˥������Ȥ��Ƥ���������
//  �����ɬ�� / ��ޤ�Ƥ�����������Ƭ����� ^ ��Ƭ�ˡ�
//  ��������� $ ����ˡ�
//
$str_rules = array(
	'now\?' 	=> format_date(UTIME),
	'date\?'	=> get_date($date_format),
	'time\?'	=> get_date($time_format),
	'&now;' 	=> format_date(UTIME),
	'&date;'	=> get_date($date_format),
	'&time;'	=> get_date($time_format),
	'&page;'	=> array_pop(explode('/', $vars['page'])),
	'&fpage;'	=> $vars['page'],
	'&t;'   	=> "\t",
);

?>
