<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: tracker.inc.php,v 1.9 2003/08/20 10:56:35 arino Exp $
//

function plugin_tracker_convert()
{
	global $script,$vars;
	
	$page = $vars['page'];
	
	$config_name = 'default';
	$options = array();
	if (func_num_args())
	{
		$args = func_get_args();
		switch (count($args))
		{
			case 3:
				$options = array_splice($args,2);
			case 2:
				$_page = get_fullname($args[1],$page);
				if (is_pagename($_page))
				{
					$page = $_page;
				}
			case 1:
				$config_name = $args[0];
		}
	}
	
	$config = new Config('plugin/tracker/'.$config_name);
	
	if (!$config->read())
	{
		return "<p>config file '".htmlspecialchars($config_name)."' not found.</p>";
	}
	
	$config->config_name = $config_name;
	
	$fields = plugin_tracker_get_fields($page,$config);
	
	$retval = convert_html(plugin_tracker_get_source($config->page.'/form'));
	
	foreach (array_keys($fields) as $name)
	{
		$retval = str_replace("[$name]",$fields[$name]->get_tag(),$retval);
	}
	return <<<EOD
<form enctype="multipart/form-data" action="$script" method="post">
$retval
</form>
EOD;
}
function plugin_tracker_action()
{
	global $script,$post,$vars,$now;
	
	$config_name = array_key_exists('_config',$post) ? $post['_config'] : '';
	
	$config = new Config('plugin/tracker/'.$config_name);
	if (!$config->read())
	{
		return "<p>config file '".htmlspecialchars($config_name)."' not found.</p>";
	}
	$config->config_name = $config_name;
	$source = $config->page.'/page';
	
	$refer = array_key_exists('_refer',$post) ? $post['_refer'] : '';
	
	if (!is_pagename($refer))
	{
		return array(
			'msg'=>'cannot write',
			'body'=>'page name ('.htmlspecialchars($refer).') is not valid.'
		);
	}
	if (!is_page($source))
	{
		return array(
			'msg'=>'cannot write',
			'body'=>'page template ('.htmlspecialchars($source).') is not exist.'
		);
	}
	// �ڡ���̾�����
	$base = $post['_refer'];
	$num = 0;
	$name = (array_key_exists('_name',$post)) ? $post['_name'] : '';
	if (array_key_exists('_page',$post))
	{
		$page = $real = $post['_page'];
	}
	else
	{
		$real = is_pagename($name) ? $name : ++$num;
		$page = get_fullname('./'.$real,$base);
	}
	if (!is_pagename($page))
	{
		$page = $base;
	}
	
	while (is_page($page))
	{
		$real = ++$num;
		$page = "$base/$real";
	}
	// �ڡ����ǡ���������
	$postdata = plugin_tracker_get_source($source);
	
	// ����Υǡ���
	$_post = array_merge($post,$_FILES);
	$_post['_date'] = $now;
	$_post['_page'] = $page;
	$_post['_name'] = $name;
	$_post['_real'] = $real;
	// $_post['_refer'] = $_post['refer'];
	
	$fields = plugin_tracker_get_fields($page,$config);
	
	foreach (array_keys($fields) as $key)
	{
		if (!array_key_exists($key,$_post))
		{
			continue;
		}
		$value = $fields[$key]->format_value($_post[$key]);
		foreach (array_keys($postdata) as $num)
		{
			if (trim($postdata[$num]) == '')
			{
				continue;
			}
			$postdata[$num] = str_replace(
				"[$key]",
				($postdata[$num]{0} == '|' or $postdata[$num]{0} == ':') ?
					str_replace('|','&#x7c;',$value) : $value,
				$postdata[$num]
			);
		}
	}
	
	// �񤭹���
	page_write($page,join('',$postdata));
	
	$r_page = rawurlencode($page);
	
	header("Location: $script?$r_page");
	exit;
}
// �ե�����ɥ��֥������Ȥ��ۤ���
function plugin_tracker_get_fields($page,&$config)
{
	global $now,$_tracker_messages;
	
	$fields = array();
	// ͽ���
	foreach (array(
		'_date'=>'text',    // �������
		'_update'=>'date',  // �ǽ�����
		'_past'=>'past',    // �в�(passage)
		'_page'=>'page',    // �ڡ���̾
		'_name'=>'text',    // ���ꤵ�줿�ڡ���̾
		'_real'=>'text',    // �ºݤΥڡ���̾
		'_refer'=>'page',   // ���ȸ�(�ե�����Τ���ڡ���)
		'_submit'=>'submit' // �ɲåܥ���
		) as $field=>$class)
	{
		$class = 'Tracker_field_'.$class;
		$fields[$field] = &new $class(array($field,$_tracker_messages["btn$field"],'','20',''),$page,$config);
	}
	
	foreach ($config->get('fields') as $field)
	{
		// 0=>����̾ 1=>���Ф� 2=>���� 3=>���ץ���� 4=>�ǥե������
		$class = 'Tracker_field_'.$field[2];
		if (!class_exists($class))
		{ // �ǥե����
			$class = 'Tracker_field_text';
			$field[2] = 'text';
			$field[3] = '20';
		}
		$fields[$field[0]] = &new $class($field,$page,$config);
	}
	return $fields;
}
// �ե�����ɥ��饹
class Tracker_field
{
	var $name;
	var $title;
	var $values;
	var $default_value;
	var $page;
	var $config;
	var $data;
	
	function Tracker_field($field,$page,&$config)
	{
		global $post;
		
		$this->name = $field[0];
		$this->title = $field[1];
		$this->values = explode(',',$field[3]);
		$this->default_value = $field[4];
		$this->page = $page;
		$this->config = &$config;
		$this->data = array_key_exists($this->name,$post) ? $post[$this->name] : '';
	}
	function get_tag()
	{
	}
	function get_style($str)
	{
		return '%s';
	}
	function format_value($value)
	{
		return $value;
	}
	function format_cell($str)
	{
		return $str;
	}
	function compare($str1,$str2)
	{
		return strnatcasecmp($str1,$str2);
	}
}
class Tracker_field_text extends Tracker_field
{
	function get_tag()
	{
		$s_name = htmlspecialchars($this->name);
		$s_size = htmlspecialchars($this->values[0]);
		$s_value = htmlspecialchars($this->default_value);
		return "<input type=\"text\" name=\"$s_name\" size=\"$s_size\" value=\"$s_value\" />";
	}
}
class Tracker_field_page extends Tracker_field_text
{
	function format_value($value)
	{
		global $WikiName;
		
		$value = strip_bracket($value);
		if (is_pagename($value))
		{
			$value = "[[$value]]";
		}
		return parent::format_value($value);
	}
}
class Tracker_field_title extends Tracker_field_text
{
	function format_cell($str)
	{
		make_heading($str);
		return $str;
	}
}
class Tracker_field_textarea extends Tracker_field
{
	function get_tag()
	{
		$s_name = htmlspecialchars($this->name);
		$s_cols = htmlspecialchars($this->values[0]);
		$s_rows = htmlspecialchars($this->values[1]);
		$s_value = htmlspecialchars($this->default_value);
		return "<textarea name=\"$s_name\" cols=\"$s_cols\" rows=\"$s_rows\">$s_value</textarea>";
	}
	function format_cell($str)
	{
		$str = preg_replace('/[\r\n]+/','',$str);
		if (!empty($this->values[2]) and strlen($str) > ($this->values[2] + 3))
		{
			$str = mb_substr($str,0,$this->values[2]).'...';
		}
		return $str;
	}
}
class Tracker_field_format extends Tracker_field
{
	var $styles = array();
	var $formats = array();
	
	function Tracker_field_format($field,$page,&$config)
	{
		parent::Tracker_field($field,$page,$config);
		
		foreach ($this->config->get($this->name) as $option)
		{
			list($key,$style,$format) = array_pad(array_map(create_function('$a','return trim($a);'),$option),3,'');
			if ($style != '')
			{
				$this->styles[$key] = $style;
			}
			if ($format != '')
			{
				$this->formats[$key] = $format;
			} 
		}
	}
	function get_tag()
	{
		$s_name = htmlspecialchars($this->name);
		$s_size = htmlspecialchars($this->values[0]);
		return "<input type=\"text\" name=\"$s_name\" size=\"$s_size\" />";
	}
	function get_key($str)
	{
		return ($str == '') ? 'IS NULL' : 'IS NOT NULL';
	}
	function format_value($str)
	{
		if (is_array($str))
		{
			return join(', ',array_map(array($this,'format_value'),$str));
		}
		$key = $this->get_key($str);
		return array_key_exists($key,$this->formats) ? str_replace('%s',$str,$this->formats[$key]) : $str;
	}
	function get_style($str)
	{
		$key = $this->get_key($str);
		return array_key_exists($key,$this->styles) ? $this->styles[$key] : '%s';
	}
}
class Tracker_field_file extends Tracker_field_format
{
	function get_tag()
	{
		$s_name = htmlspecialchars($this->name);
		$s_size = htmlspecialchars($this->values[0]);
		return "<input type=\"file\" name=\"$s_name\" size=\"$s_size\" />";
	}
	function format_value($str)
	{
		if (array_key_exists($this->name,$_FILES))
		{
			require_once(PLUGIN_DIR.'attach.inc.php');
			$result = attach_upload($_FILES[$this->name],$this->page);
			if ($result['result']) // ���åץ�������
			{
				return parent::format_value($this->page.'/'.$_FILES[$this->name]['name']);
			}
		}
		// �ե����뤬���ꤵ��Ƥ��ʤ��������åץ��ɤ˼���
		return parent::format_value('');
	}
}
class Tracker_field_radio extends Tracker_field_format
{
	function get_tag()
	{
		$s_name = htmlspecialchars($this->name);
		$retval = '';
		foreach ($this->config->get($this->name) as $option)
		{
			$s_option = htmlspecialchars($option[0]);
			$checked = trim($option[0]) == trim($this->default_value) ? ' checked="checked"' : '';
			$retval .= "<input type=\"radio\" name=\"$s_name\" value=\"$s_option\"$checked />$s_option\n";
		}
		
		return $retval;
	}
	function get_key($str)
	{
		return $str;
	}
	function compare($str1,$str2)
	{
		static $options;
		
		if (!isset($options))
		{
			$options = array_flip(array_map(create_function('$arr','return $arr[0];'),$this->config->get($this->name)));
		}
		$n1 = array_key_exists($str1,$options) ? $options[$str1] : $str1;
		$n2 = array_key_exists($str2,$options) ? $options[$str2] : $str2;
		return ($n1 == $n2) ? 0 : ($n1 > $n2 ? -1 : 1);
	}
}
class Tracker_field_select extends Tracker_field_radio
{
	function get_tag($empty=FALSE)
	{
		$s_name = htmlspecialchars($this->name);
		$s_size = (array_key_exists(0,$this->values) and is_numeric($this->values[0])) ?
			' size="'.htmlspecialchars($this->values[0]).'"' : '';
		$s_multiple = (array_key_exists(1,$this->values) and strtolower($this->values[1]) == 'multiple') ?
			' multiple="multiple"' : '';
		$retval = "<select name=\"{$s_name}[]\"$s_size$s_multiple>\n";
		if ($empty)
		{
			$retval .= " <option value=\"\"></option>\n";
		}
		$defaults = array_flip(preg_split('/\s*,\s*/',$this->default_value,-1,PREG_SPLIT_NO_EMPTY));
		foreach ($this->config->get($this->name) as $option)
		{
			$s_option = htmlspecialchars($option[0]);
			$selected = array_key_exists(trim($option[0]),$defaults) ? ' selected="selected"' : '';
			$retval .= " <option value=\"$s_option\"$selected>$s_option</option>\n";
		}
		$retval .= "</select>";
		
		return $retval;
	}
}
class Tracker_field_checkbox extends Tracker_field_radio
{
	function get_tag($empty=FALSE)
	{
		$s_name = htmlspecialchars($this->name);
		$defaults = array_flip(preg_split('/\s*,\s*/',$this->default_value,-1,PREG_SPLIT_NO_EMPTY));
		$retval = '';
		foreach ($this->config->get($this->name) as $option)
		{
			$s_option = htmlspecialchars($option[0]);
			$checked = array_key_exists(trim($option[0]),$defaults) ?
				' checked="checked"' : '';
			$retval .= "<input type=\"checkbox\" name=\"{$s_name}[]\" value=\"$s_option\"$checked />$s_option\n";
		}
		
		return $retval;
	}
}
class Tracker_field_hidden extends Tracker_field_radio
{
	function get_tag($empty=FALSE)
	{
		$s_name = htmlspecialchars($this->name);
		$s_default = htmlspecialchars($this->default_value);
		$retval = "<input type=\"hidden\" name=\"$s_name\" value=\"$s_default\" />\n";
		
		return $retval;
	}
}
class Tracker_field_submit extends Tracker_field
{
	function get_tag()
	{
		$s_title = htmlspecialchars($this->title);
		$s_page = htmlspecialchars($this->page);
		$s_config = htmlspecialchars($this->config->config_name);
		
		return <<<EOD
<input type="submit" value="$s_title" />
<input type="hidden" name="plugin" value="tracker" />
<input type="hidden" name="_refer" value="$s_page" />
<input type="hidden" name="_config" value="$s_config" />
EOD;
	}
}
class Tracker_field_date extends Tracker_field
{
	function format_cell($timestamp)
	{
		return format_date($timestamp);
	}
	function compare($time1,$time2)
	{
		return $time1 - $time2;
	}
}
class Tracker_field_past extends Tracker_field
{
	function format_cell($timestamp)
	{
		return get_passage($timestamp,FALSE);
	}
	function compare($time1,$time2)
	{
		return $time1 - $time2;
	}
}
///////////////////////////////////////////////////////////////////////////
// ����ɽ��
function plugin_tracker_list_convert()
{
	global $vars;
	
	$config = 'default';
	$page = $vars['page'];
	$field = '_page';
	$order = -1;
	$limit = NULL;
	if (func_num_args())
	{
		$args = func_get_args();
		switch (count($args))
		{
			case 5:
				$limit = is_numeric($args[4]) ? $args[4] : $limit;
			case 4:
				$order = (strpos('ascending',strtolower($args[3])) === 0) ? 1 : -1;
			case 3:
				$field = ($args[2] != '') ? $args[2] : $field;
			case 2:
				$page = is_pagename($args[1]) ? $args[1] : $page;
			case 1:
				$config = ($args[0] != '') ? $args[0] : $config;
		}
	}
	return plugin_tracker_getlist($page,$config,$field,$order,$limit);
}
function plugin_tracker_list_action()
{
	global $script,$vars,$_tracker_messages;
	
	$page = $vars['refer'];
	$s_page = make_pagelink($page);
	$config = $vars['config'];
	$field = array_key_exists('field',$vars) ? $vars['field'] : '_page';
	$order = (array_key_exists('order',$vars) and is_numeric($vars['order'])) ?
		$vars['order'] : -1;
		
	return array(
		'msg' => $_tracker_messages['msg_list'],
		'body'=> str_replace('$1',$s_page,$_tracker_messages['msg_back']).
			plugin_tracker_getlist($page,$config,$field,$order)
	);
}
function plugin_tracker_getlist($page,$config_name,$field=NULL,$order=1,$limit=NULL)
{
	$config = new Config('plugin/tracker/'.$config_name);
	
	if (!$config->read())
	{
		return "<p>config file '".htmlspecialchars($config_name)."' is not exist.";
	}
	$config->config_name = $config_name;
	$list = &new Tracker_list($page,$config);
	$list->sort($field,$order);
	return $list->toString($limit);
}

// �������饹
class Tracker_list
{
	var $page;
	var $config;
	var $fields;
	var $pattern;
	var $pattern_fields;
	var $rows;
	var $sort_field = '_page';
	var $sort_order = -1;
	var $sort_obj = NULL;
	
	function Tracker_list($page,&$config)
	{
		$this->page = $page;
		$this->config = &$config;
		$this->fields = plugin_tracker_get_fields($page,$config);
		
		$pattern = join('',plugin_tracker_get_source($config->page.'/page'));
		// �֥�å��ץ饰�����ե�����ɤ��ִ�
		// #comment�ʤɤ������ʸ��������������ä����ˡ�[_block_xxx]�˵ۤ����ޤ���褦�ˤ���
		$pattern = preg_replace('/^\#([^\(\s]+)(?:\((.*)\))?\s*$/m','[_block_$1]',$pattern);
		
		// �ѥ����������
		$this->pattern = '';
		$this->pattern_fields = array();
		$pattern = preg_split('/\\\\\[(\w+)\\\\\]/',preg_quote($pattern,'/'),-1,PREG_SPLIT_DELIM_CAPTURE);
		while (count($pattern))
		{
			$this->pattern .= preg_replace('/\s+/','\\s*','(?>\\s*'.trim(array_shift($pattern)).'\\s*)');
			if (count($pattern))
			{
				$field = array_shift($pattern);
				$this->pattern_fields[] = $field;
				$this->pattern .= '(.*)';
			}
		}
		// �ڡ��������ȼ�����
		$this->rows = array();
		$pattern = "$page/";
		$pattern_len = strlen($pattern);
		foreach (get_existpages() as $_page)
		{
			if (strpos($_page,$pattern) === 0)
				//and is_numeric($num = substr($_page,$pattern_len)))
			{
				$name = substr($_page,$pattern_len);
				$this->add($_page,$name);
			}
		}
	}
	function add($page,$name)
	{
		global $WikiName,$BracketName;
		
		// ̵�¥롼���ɻ�
		if (array_key_exists($name,$this->rows))
		{
			return;
		}
		
		$source = plugin_tracker_get_source($page);
		if (preg_match("/move\s*to\s*($WikiName|\[\[$BracketName\]\])/",$source[0],$matches))
		{
			return $this->add(strip_bracket($matches[1]),$name);
		}
		$source = join('',preg_replace('/^(\*{1,3}.*)\[#[A-Za-z][\w-]+\](.*)$/','$1$2',$source));
		
		// �ǥե������
		$this->rows[$name] = array(
			'_page'  => "[[$page]]",
			'_refer' => $this->page,
			'_real'  => $name,
			'_update'=> get_filetime($page),
			'_past'  => get_filetime($page)
		);
		if (preg_match("/{$this->pattern}/s",$source,$matches))
		{
			array_shift($matches);
			foreach ($this->pattern_fields as $key=>$field)
			{
				$this->rows[$name][$field] = trim($matches[$key]);
			}
		}
	}
	function sort($field=NULL,$order=1)
	{
		$this->sort_order = $order;
		if ($field == '_page')
		{
			($order == -1) ? krsort($this->rows) : ksort($this->rows);
			return;
		}
		$fields = array_flip(array_keys($this->fields));
		
		if (!array_key_exists($field,$fields))
		{
			return;
		}
		$this->sort_field = $field;
		$this->sort_obj = &$this->fields[$field];
		usort($this->rows,array(&$this,'compare'));
	}
	function compare($arr1,$arr2)
	{
		return $this->sort_order * $this->sort_obj->compare(
			$arr1[$this->sort_field],$arr2[$this->sort_field]
		);
	}
	function replace_item($arr)
	{
		$params = explode(',',$arr[1]);
		$name = array_shift($params);
		if ($name == '')
		{
			$str = '';
		}
		else if (array_key_exists($name,$this->items))
		{
			$str = $this->items[$name];
			if (array_key_exists($name,$this->fields))
			{
				$str = $this->fields[$name]->format_cell($str);
			}
		}
		else
		{
			return $this->pipe ? str_replace('|','&#x7c;',$arr[0]) : $arr[0];
		}
		$style = count($params) ? $params[0] : $name;
		if (array_key_exists($style,$this->items)
			and array_key_exists($style,$this->fields))
		{
			$str = sprintf($this->fields[$style]->get_style($this->items[$style]),$str);
		}
		return $this->pipe ? str_replace('|','&#x7c;',$str) : $str;
	}
	function replace_title($arr)
	{
		global $script;
		
		if (!array_key_exists($arr[1],$this->fields))
		{
			return $arr[0];
		}
		
		$order = 1;
		$arrow = '';
		if ($arr[1] == $this->sort_field)
		{
			$order = -$this->sort_order;
			$arrow = ($order == -1) ? '&darr;':'&uarr;';
		}
		
		$title = $this->fields[$arr[1]]->title;
		$r_page = rawurlencode($this->page);
		$r_config = rawurlencode($this->config->config_name);
		$r_field = rawurlencode($arr[1]);
		
		return "[[$title$arrow>$script?plugin=tracker_list&refer=$r_page&config=$r_config&field=$r_field&order=$order]]";
	}
	function toString($limit=NULL)
	{
		global $_tracker_messages;
		
		$source = '';
		$body = array();
		
		if ($limit !== NULL and count($this->rows) > $limit)
		{
			$source = str_replace(
				array('$1','$2'),
				array(count($this->rows),$limit),
				$_tracker_messages['msg_limit'])."\n";
			$this->rows = array_splice($this->rows,0,$limit);
		}
		if (count($this->rows) == 0)
		{
			return '';
		}
		foreach (plugin_tracker_get_source($this->config->page.'/list') as $line)
		{
			if (preg_match('/^\|(.+)\|[hHfFcC]$/',$line))
			{
				$source .= preg_replace_callback('/\[([^\[\]]+)\]/',array(&$this,'replace_title'),$line);
			}
			else
			{
				$body[] = $line;
			}
		}
		foreach ($this->rows as $key=>$row)
		{
			$this->items = $row;
			foreach ($body as $line)
			{
				if (trim($line) == '')
				{
					$source .= $line;
					continue;
				}
				$this->pipe = ($line{0} == '|' or $line{0} == ':');
				$source .= preg_replace_callback('/\[([^\[\]]+)\]/',array(&$this,'replace_item'),$line);
			}
		}
		return convert_html($source);
	}
}
function plugin_tracker_get_source($page)
{
	$source = get_source($page);
	// ���Ф��θ�ͭID������
	$source = preg_replace('/^(\*{1,3}.*)\[#[A-Za-z][\w-]+\](.*)$/m','$1$2',$source);
	// #freeze����
	return preg_replace('/^#freeze\s*$/m','',$source);
}
?>
