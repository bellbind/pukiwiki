<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: new.inc.php,v 1.1 2003/04/13 04:47:58 arino Exp $
//

// ����ɽ���δ���(����)
define('NEW_LIMIT',3);

// ������ΤȤ�ɽ�����륿��
define('NEW_FORMAT','<span class="new">%s</span>');

// ɽ���ե����ޥå�
define('NEW_MESSAGE','<span class="comment_date">%s</span>');

// �ǥե���Ȥ�ɽ��ʸ����
define('NEW_STR','New');

function plugin_new_inline()
{
	if (func_num_args() < 1)
	{
		return FALSE;
	}
	
	$args = func_get_args();
	
	$date = array_pop($args);
	if (($timestamp = strtotime($date)) === -1)
	{
		return FALSE;
	}
	$timestamp -= LOCALZONE;

	$str = NEW_STR;
	$limit = NEW_LIMIT;
	
	switch (count($args))
	{
		case 2:
			$str = $args[1];
		case 1:
			if (is_numeric($args[0]))
			{
				$limit = $args[0];
			}
	}
	$limit *= 60 * 60 * 24;
	
	$retval = htmlspecialchars($date);
	
	if ((UTIME - $timestamp) <= $limit)
	{
		$retval .= sprintf(NEW_FORMAT,htmlspecialchars($str));
	}
	return sprintf(NEW_MESSAGE,$retval);
}
?>
