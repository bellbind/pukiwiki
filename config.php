<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: config.php,v 1.1 2003/03/07 06:45:26 panda Exp $
//
/*
 * �ץ饰����������PukiWiki�Υڡ����˵��Ҥ���
 *
 * // ���֥�����������
 * $obj = new Config('plugin/�ץ饰����̾/')
 * // �ɤ߽Ф�
 * $obj->read();
 * // �������
 * $array = &$obj->get($title);
 * // �ɲ� - ľ��
 * $array[] = array(4,5,6);
 * // �ɲ� - Config���֥������ȤΥ᥽�å�
 * $obj->add($title,array(4,5,6));
 * // �ִ� - ľ��
 * $array = array(1=>array(1,2,3));
 * // �ִ� - Config���֥������ȤΥ᥽�å�
 * $obj->put($title,array(1=>array(1,2,3));
 * // �õ� 
 * $obj->put_values($title,NULL);
 * // �񤭹���
 * $obj->write();
 * 
 */

// �ڡ���̾�Υץ�ե�����
define('CONFIG_BASE',':config/');

// ����ڡ�������
class Config
{
	// �ڡ���̾
	var $page;
	// ����
	var $objs;
	
	function Config($name)
	{
		$this->page = CONFIG_BASE.$name;
	}
	// �ڡ������ɤ߹���
	function read()
	{
		$this->objs = array();
		$title = '';
		$obj = &$this->get_object($title);
		foreach (get_source($this->page) as $line)
		{
			if ($line != '' and $line{0} == '|' and preg_match('/^\|(.+)\|\s*$/',$line,$matches))
			{
				$obj->add_value(explode('|',$matches[1]));
			}
			else if ($line != '' and $line{0} == '*')
			{
				$level = strspn($line,'*');
				$title = trim(substr($line,$level));
				$obj = &$this->get_object($title,$level);
			}
			else
			{
				$obj->add_line($line);
			}
		}
		$this->objs[$title] = &$obj;
	}
	// ������������
	function &get($title)
	{
		$obj = &$this->get_object($title);
		return $obj->values;
	}
	// ��������ꤹ��(���)
	function put($title,$values)
	{
		$obj = &$this->get_object($title);
		$obj->values = $values;
	}
	// �Ԥ��ɲä���
	function add($title,$value)
	{
		$obj = &$this->get_object($title);
		$obj->values[] = $value;
	}
	// ���֥������Ȥ��������(�ʤ��Ȥ��Ϻ��)
	function &get_object($title,$level=1)
	{
		if (!array_key_exists($title,$this->objs))
		{
			$this->objs[$title] = &new ConfigTable(str_repeat('*',$level).$title."\n");
		}
		return $this->objs[$title];
	}
	// �ڡ����˽񤭹���
	function write()
	{
		page_write($this->page, $this->toString());
	}
	// �񼰲�
	function toString()
	{
		$retval = '';
		foreach ($this->objs as $title=>$obj)
		{
			$retval .= $obj->toString();
		}
		return $retval;
	}
}
//�����ͤ��ݻ����륯�饹
class ConfigTable
{
	// ���������ͤ�����
	var $values = array();
	// �ڡ���������(�ơ��֥�ʳ�����ʬ)
	var $line;
	
	function ConfigTable($title)
	{
		$this->line = $title;
	}
	// �Ԥ��ɲ�
	function add_value($value)
	{
		$this->values[] = (count($value) == 1) ? $value[0] : $value;
	}
	// �������ɲ�
	function add_line($line)
	{
		$this->line .= $line;
	}
	// �񼰲�
	function toString()
	{
		$retval = $this->line;
		if (is_array($this->values))
		{
			foreach ($this->values as $value)
			{
				$value = is_array($value) ? join('|',$value) : $value;
				$retval .= "|$value|\n";
			}
		}
		$retval .= "\n"; // ���� :)
		
		return $retval;
	}
}
?>
