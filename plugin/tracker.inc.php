<?php
// PukiWiki - Yet another WikiWikiWeb clone
// $Id: tracker.inc.php,v 1.40 2007/09/02 14:43:26 henoheno Exp $
// Copyright (C) 2003-2005, 2007 PukiWiki Developers Team
// License: GPL v2 or (at your option) any later version
//
// Issue tracker plugin (See Also bugtrack plugin)

// #tracker_list: Excluding pattern
define('PLUGIN_TRACKER_LIST_EXCLUDE_PATTERN','#^SubMenu$|/#');	// 'SubMenu' and using '/'
//define('PLUGIN_TRACKER_LIST_EXCLUDE_PATTERN','#(?!)#');		// Nothing excluded

// #tracker_list: Show error rows (can't capture columns properly)
define('PLUGIN_TRACKER_LIST_SHOW_ERROR_PAGE', 1);

function plugin_tracker_convert()
{
	global $script, $vars;

	if (PKWK_READONLY) return ''; // Show nothing

	$base = $refer = $vars['page'];

	$config_name = 'default';
	$form = 'form';
	$options = array();
	if (func_num_args()) {
		$args = func_get_args();
		switch (count($args))
		{
			case 3:
				$options = array_splice($args, 2);
			case 2:
				$args[1] = get_fullname($args[1], $base);
				$base    = is_pagename($args[1]) ? $args[1] : $base;
			case 1:
				$config_name = ($args[0] != '') ? $args[0] : $config_name;
				list($config_name, $form) = array_pad(explode('/', $config_name, 2), 2, $form);
		}
	}

	$config = new Config('plugin/tracker/' . $config_name);

	if (! $config->read()) {
		return '<p>config file \'' . htmlspecialchars($config_name) . '\' not found.</p>';
	}

	$config->config_name = $config_name;

	$fields = plugin_tracker_get_fields($base, $refer, $config);

	$form = $config->page . '/' . $form;
	if (! is_page($form)) {
		return '<p>config file \'' . make_pagelink($form) . '\' not found.</p>';
	}

	$retval  = convert_html(plugin_tracker_get_source($form));
	$hiddens = '';

	foreach (array_keys($fields) as $name) {
		$replace = $fields[$name]->get_tag();
		if (is_a($fields[$name], 'Tracker_field_hidden')) {
			$hiddens .= $replace;
			$replace  = '';
		}
		$retval = str_replace('[' . $name . ']', $replace, $retval);
	}

	return <<<EOD
<form enctype="multipart/form-data" action="$script" method="post">
<div>
$retval
$hiddens
</div>
</form>
EOD;
}

// Add new page
function plugin_tracker_action()
{
	global $post, $vars, $now;

	if (PKWK_READONLY) die_message('PKWK_READONLY prohibits editing');

	$base  = isset($post['_base'])   ? $post['_base']  : '';
	$refer = isset($post['_refer'])  ? $post['_refer'] : $base;
	if (! is_pagename($refer)) {
		return array(
			'msg'  => 'cannot write',
			'body' => 'page name (' . htmlspecialchars($refer) . ') is not valid.'
		);
	}

	// $page name to add will be decided here
	$name = isset($post['_name']) ? $post['_name'] : '';
	$num  = 0;
	if (isset($post['_page'])) {
		$page = $real = $post['_page'];
	} else {
		$real = is_pagename($name) ? $name : ++$num;
		$page = get_fullname('./' . $real, $base);
	}
	if (! is_pagename($page)) $page = $base;
	while (is_page($page)) {
		$real = ++$num;
		$page = $base . '/' . $real;
	}

	// Loading configuration
	$config_name = isset($post['_config']) ? $post['_config'] : '';
	$config = new Config('plugin/tracker/' . $config_name);
	if (! $config->read()) {
		return '<p>config file \'' . htmlspecialchars($config_name) . '\' not found.</p>';
	}
	$config->config_name = $config_name;
	$template_page = $config->page . '/page';
	if (! is_page($template_page)) {
		return array(
			'msg'  => 'cannot write',
			'body' => 'page template (' . htmlspecialchars($template_page) . ') is not exist.'
		);
	}

	// Default
	$_post = array_merge($post, $_FILES);
	$_post['_date'] = $now;
	$_post['_page'] = $page;
	$_post['_name'] = $name;
	$_post['_real'] = $real;
	// $_post['_refer'] = $_post['refer'];

	// Creating an empty page, before attaching files
	pkwk_touch_file(get_filename($page));

	// Load $fields
	$fields = plugin_tracker_get_fields($page, $refer, $config);
	$from = $to = array();
	foreach (array_keys($fields) as $field) {
		$from[] = '[' . $field . ']';
		$to[]   = isset($_post[$field]) ? $fields[$field]->format_value($_post[$field]) : '';
	}
	unset($fields);

	// Load $template
	$template = plugin_tracker_get_source($template_page);

	// Repalace every [$field]s to real values in the $template
	$replace = $replace_e = array();
	foreach (array_keys($template) as $num) {
		if (trim($template[$num]) == '') continue;
		$letter = $template[$num]{0};
		if ($letter == '|' || $letter == ':') {
			// Escape for some TextFormattingRules: <table> and <dr>
			$replace_e[$num] = $template[$num];
		} else {
			$replace[$num]   = $template[$num];
		}
	}
	foreach (str_replace($from,   $to,   $replace  ) as $num => $line) {
		$template[$num] = $line;
	}
	// Escape for some TextFormattingRules: <table> and <dr>
	if ($replace_e) {
		$to_e = array();
		foreach($to as $value) {
			if (strpos($value, '|') !== FALSE) {
				// Escape for some TextFormattingRules: <table> and <dr>
				$to_e[] = str_replace('|', '&#x7c;', $value);
			} else{
				$to_e[] = $value;	
			}
		}
		foreach (str_replace($from, $to_e, $replace_e) as $num => $line) {
			$template[$num] = $line;
		}
	}

	// Write $template, without touch
	page_write($page, join('', $template));

	pkwk_headers_sent();
	header('Location: ' . get_script_uri() . '?' . rawurlencode($page));
	exit;
}

/*
function plugin_tracker_inline()
{
	global $vars;

	if (PKWK_READONLY) return ''; // Show nothing

	$args = func_get_args();
	if (count($args) < 3) return FALSE;

	$body = array_pop($args);
	list($config_name, $field) = $args;

	$config = new Config('plugin/tracker/' . $config_name);

	if (! $config->read()) {
		return 'config file \'' . htmlspecialchars($config_name) . '\' not found.';
	}

	$config->config_name           = $config_name;
	$fields                        = plugin_tracker_get_fields($vars['page'], $vars['page'], $config);
	$fields[$field]->default_value = $body;

	return $fields[$field]->get_tag();
}
*/

// Construct field objects
function plugin_tracker_get_fields($base, $refer, & $config)
{
	global $now, $_tracker_messages;

	$fields = array();

	foreach (
		array(
			// Reserved words
			'_date'  =>'text',	// Post date
			'_update'=>'date',	// Last modified date
			'_past'  =>'past',	// Elapsed time (passage)
			'_page'  =>'page',	// Page name
			'_name'  =>'text',	// Page name specified by poster
			'_real'  =>'real',	// Page name (Real)
			'_refer' =>'page',	// Page name refer from this (Page who has forms)
			'_base'  =>'page',
			'_submit'=>'submit'
		) as $field => $class)
	{
		$class = 'Tracker_field_' . $class;
		$fields[$field] = & new $class(
			array($field, $_tracker_messages['btn' . $field], '', '20', ''),
			$base,
			$refer,
			$config
		);
	}

	foreach ($config->get('fields') as $field) {
		// $field[0]: Field name
		// $field[1]: Field name (for display)
		// $field[2]: Field type
		// $field[3]: Option
		// $field[4]: Default value
		$class = 'Tracker_field_' . $field[2];
		if (! class_exists($class)) {
			// Default
			$class    = 'Tracker_field_text';
			$field[2] = 'text';
			$field[3] = '20';
		}
		$fields[$field[0]] = & new $class($field, $base, $refer, $config);
	}
	return $fields;
}

// Field classes
class Tracker_field
{
	var $name;
	var $title;
	var $values;
	var $default_value;
	var $page;
	var $refer;
	var $config;
	var $data;
	var $sort_type = SORT_REGULAR;
	var $id        = 0;

	function Tracker_field($field, $page, $refer, & $config)
	{
		global $post;
		static $id = 0;

		$this->id     = ++$id;
		$this->name   = $field[0];
		$this->title  = $field[1];
		$this->values = explode(',', $field[3]);
		$this->default_value = $field[4];
		$this->page   = $page;
		$this->refer  = $refer;
		$this->config = & $config;
		$this->data   = isset($post[$this->name]) ? $post[$this->name] : '';
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

	function get_value($value)
	{
		return $value;
	}
}

class Tracker_field_text extends Tracker_field
{
	var $sort_type = SORT_STRING;

	function get_tag()
	{
		return '<input type="text"' .
				' name="'  . htmlspecialchars($this->name)          . '"' .
				' size="'  . htmlspecialchars($this->values[0])     . '"' .
				' value="' . htmlspecialchars($this->default_value) . '" />';
	}
}

class Tracker_field_page extends Tracker_field_text
{
	var $sort_type = SORT_STRING;

	function format_value($value)
	{
		$value = strip_bracket($value);
		if (is_pagename($value)) $value = '[[' . $value . ']]';
		return parent::format_value($value);
	}
}

class Tracker_field_real extends Tracker_field_text
{
	var $sort_type = SORT_REGULAR;
}

class Tracker_field_title extends Tracker_field_text
{
	var $sort_type = SORT_STRING;

	function format_cell($str)
	{
		make_heading($str);
		return $str;
	}
}

class Tracker_field_textarea extends Tracker_field
{
	var $sort_type = SORT_STRING;

	function get_tag()
	{
		return '<textarea' .
			' name="' . htmlspecialchars($this->name)      . '"' .
			' cols="' . htmlspecialchars($this->values[0]) . '"' .
			' rows="' . htmlspecialchars($this->values[1]) . '">' .
						htmlspecialchars($this->default_value) .
			'</textarea>';
	}

	function format_cell($str)
	{
		$str = preg_replace('/[\r\n]+/', '', $str);
		if (! empty($this->values[2]) && strlen($str) > ($this->values[2] + 3)) {
			$str = mb_substr($str, 0, $this->values[2]) . '...';
		}
		return $str;
	}
}

class Tracker_field_format extends Tracker_field
{
	var $sort_type = SORT_STRING;
	var $styles    = array();
	var $formats   = array();

	function Tracker_field_format($field, $page, $refer, & $config)
	{
		parent::Tracker_field($field, $page, $refer, $config);

		foreach ($this->config->get($this->name) as $option) {
			list($key, $style, $format) =
				array_pad(array_map(create_function('$a', 'return trim($a);'), $option), 3, '');
			if ($style  != '') $this->styles[$key]  = $style;
			if ($format != '') $this->formats[$key] = $format;
		}
	}

	function get_tag()
	{
		return '<input type="text"' .
			' name="' . htmlspecialchars($this->name)      . '"' .
			' size="' . htmlspecialchars($this->values[0]) . '" />';
	}

	function get_key($str)
	{
		return ($str == '') ? 'IS NULL' : 'IS NOT NULL';
	}

	function format_value($str)
	{
		if (is_array($str)) {
			return join(', ', array_map(array($this, 'format_value'), $str));
		}

		$key = $this->get_key($str);
		return isset($this->formats[$key]) ? str_replace('%s', $str, $this->formats[$key]) : $str;
	}

	function get_style($str)
	{
		$key = $this->get_key($str);
		return isset($this->styles[$key]) ? $this->styles[$key] : '%s';
	}
}

class Tracker_field_file extends Tracker_field_format
{
	var $sort_type = SORT_STRING;

	function get_tag()
	{
		return '<input type="file"' .
			' name="' . htmlspecialchars($this->name)      . '"' .
			' size="' . htmlspecialchars($this->values[0]) . '" />';
	}

	function format_value($str)
	{
		if (isset($_FILES[$this->name])) {

			require_once(PLUGIN_DIR . 'attach.inc.php');

			$result = attach_upload($_FILES[$this->name], $this->page);
			if ($result['result']) {
				// Upload success
				return parent::format_value($this->page . '/' . $_FILES[$this->name]['name']);
			}
		}

		// Filename not specified, or Fail to upload
		return parent::format_value('');
	}
}

class Tracker_field_radio extends Tracker_field_format
{
	var $sort_type = SORT_NUMERIC;

	function get_tag()
	{
		$retval = '';

		$id = 0;
		$s_name = htmlspecialchars($this->name);
		foreach ($this->config->get($this->name) as $option) {
			++$id;
			$s_id = '_p_tracker_' . $s_name . '_' . $this->id . '_' . $id;
			$s_option = htmlspecialchars($option[0]);
			$checked  = trim($option[0]) == trim($this->default_value) ? ' checked="checked"' : '';

			$retval .= '<input type="radio"' .
				' name="'  . $s_name   . '"' .
				' id="'    . $s_id     . '"' .
				' value="' . $s_option . '"' .
				$checked . ' />' .
				'<label for="' . $s_id . '">' . $s_option . '</label>' . "\n";
		}

		return $retval;
	}

	function get_key($str)
	{
		return $str;
	}

	function get_value($value)
	{
		static $options = array();
		if (! isset($options[$this->name])) {
			$options[$this->name] = array_flip(array_map(create_function('$arr', 'return $arr[0];'), $this->config->get($this->name)));
		}
		return isset($options[$this->name][$value]) ? $options[$this->name][$value] : $value;
	}
}

class Tracker_field_select extends Tracker_field_radio
{
	var $sort_type = SORT_NUMERIC;

	function get_tag($empty = FALSE)
	{
		$s_name = htmlspecialchars($this->name);
		$s_size = (isset($this->values[0]) && is_numeric($this->values[0])) ?
			' size="' . htmlspecialchars($this->values[0]) . '"' :
			'';
		$s_multiple = (isset($this->values[1]) && strtolower($this->values[1]) == 'multiple') ?
			' multiple="multiple"' :
			'';

		$retval = '<select name="' . $s_name . '[]"' . $s_size . $s_multiple . '>' . "\n";
		if ($empty){
			$retval .= ' <option value=""></option>' . "\n";
		}
		$defaults = array_flip(preg_split('/\s*,\s*/', $this->default_value, -1, PREG_SPLIT_NO_EMPTY));
		foreach ($this->config->get($this->name) as $option) {
			$s_option = htmlspecialchars($option[0]);
			$selected = isset($defaults[trim($option[0])]) ? ' selected="selected"' : '';
			$retval  .= ' <option value="' . $s_option . '"' . $selected . '>' . $s_option . '</option>' . "\n";
		}
		$retval .= '</select>';

		return $retval;
	}
}

class Tracker_field_checkbox extends Tracker_field_radio
{
	var $sort_type = SORT_NUMERIC;

	function get_tag($empty=FALSE)
	{
		$retval = '';

		$id = 0;
		$s_name   = htmlspecialchars($this->name);
		$defaults = array_flip(preg_split('/\s*,\s*/', $this->default_value, -1, PREG_SPLIT_NO_EMPTY));
		foreach ($this->config->get($this->name) as $option)
		{
			++$id;
			$s_id     = '_p_tracker_' . $s_name . '_' . $this->id . '_' . $id;
			$s_option = htmlspecialchars($option[0]);
			$checked  = isset($defaults[trim($option[0])]) ? ' checked="checked"' : '';

			$retval .= '<input type="checkbox"' .
				' name="' . $s_name . '[]"' .
				' id="' . $s_id . '"' .
				' value="' . $s_option . '"' .
				$checked . ' />' .
				'<label for="' . $s_id . '">' . $s_option . '</label>' . "\n";
		}

		return $retval;
	}
}

class Tracker_field_hidden extends Tracker_field_radio
{
	var $sort_type = SORT_NUMERIC;

	function get_tag($empty=FALSE)
	{
		return '<input type="hidden"' .
			' name="'  . htmlspecialchars($this->name)          . '"' .
			' value="' . htmlspecialchars($this->default_value) . '" />' . "\n";
	}
}

class Tracker_field_submit extends Tracker_field
{
	function get_tag()
	{
		$s_title  = htmlspecialchars($this->title);
		$s_page   = htmlspecialchars($this->page);
		$s_refer  = htmlspecialchars($this->refer);
		$s_config = htmlspecialchars($this->config->config_name);

		return <<<EOD
<input type="submit" value="$s_title" />
<input type="hidden" name="plugin" value="tracker" />
<input type="hidden" name="_refer" value="$s_refer" />
<input type="hidden" name="_base" value="$s_page" />
<input type="hidden" name="_config" value="$s_config" />
EOD;
	}
}

class Tracker_field_date extends Tracker_field
{
	var $sort_type = SORT_NUMERIC;

	function format_cell($timestamp)
	{
		return format_date($timestamp);
	}
}

class Tracker_field_past extends Tracker_field
{
	var $sort_type = SORT_NUMERIC;

	function format_cell($timestamp)
	{
		return get_passage($timestamp, FALSE);
	}

	function get_value($value)
	{
		return UTIME - $value;
	}
}

///////////////////////////////////////////////////////////////////////////

function plugin_tracker_list_convert()
{
	global $vars;

	$config = 'default';
	$page   = $refer = $vars['page'];
	$field  = '_page';
	$order  = '';
	$list   = 'list';
	$limit  = NULL;
	if (func_num_args()) {
		$args = func_get_args();
		switch (count($args)) {
		case 4:
			$limit = is_numeric($args[3]) ? $args[3] : $limit;
		case 3:
			$order = $args[2];
		case 2:
			$args[1] = get_fullname($args[1], $page);
			$page    = is_pagename($args[1]) ? $args[1] : $page;
		case 1:
			$config = ($args[0] != '') ? $args[0] : $config;
			list($config, $list) = array_pad(explode('/', $config, 2), 2, $list);
		}
	}
	return plugin_tracker_getlist($page, $refer, $config, $list, $order, $limit);
}

function plugin_tracker_list_action()
{
	global $script, $vars, $_tracker_messages;

	$page   = $refer = $vars['refer'];
	$s_page = make_pagelink($page);
	$config = $vars['config'];
	$list   = isset($vars['list'])  ? $vars['list']  : 'list';
	$order  = isset($vars['order']) ? $vars['order'] : '_real:SORT_DESC';

	return array(
		'msg' => $_tracker_messages['msg_list'],
		'body'=> str_replace('$1', $s_page, $_tracker_messages['msg_back']).
			plugin_tracker_getlist($page, $refer, $config, $list, $order)
	);
}

function plugin_tracker_getlist($page, $refer, $config_name, $list, $order = '', $limit = NULL)
{
	$config = new Config('plugin/tracker/' . $config_name);

	if (! $config->read()) {
		return '<p>config file \'' . htmlspecialchars($config_name) . '\' is not exist.</p>';
	}

	$config->config_name = $config_name;

	if (! is_page($config->page . '/' . $list)) {
		return '<p>config file \'' . make_pagelink($config->page . '/' . $list) . '\' not found.</p>';
	}

	$list = & new Tracker_list($page, $refer, $config, $list);
	$list->sort($order);
	return $list->toString($limit);
}

// Listing class
class Tracker_list
{
	var $page;
	var $config;
	var $list;
	var $fields;
	var $pattern;
	var $pattern_fields;
	var $rows;
	var $order;

	function Tracker_list($page, $refer, & $config, $list)
	{
		$this->page    = $page;
		$this->config  = & $config;
		$this->list    = $list;
		$this->fields  = plugin_tracker_get_fields($page, $refer, $config);
		$this->pattern = '';
		$this->pattern_fields = array();
		$this->rows    = array();
		$this->order   = array();

		$pattern = plugin_tracker_get_source($config->page . '/page', TRUE);

		// Convert block-plugins to fields
		// Incleasing and decreasing around #comment etc, will be covererd with [_block_xxx]
		$pattern = preg_replace('/^\#([^\(\s]+)(?:\((.*)\))?\s*$/m', '[_block_$1]', $pattern);

		// Generate regexes
		$pattern = preg_split('/\\\\\[(\w+)\\\\\]/', preg_quote($pattern, '/'), -1, PREG_SPLIT_DELIM_CAPTURE);
		while (! empty($pattern)) {
			$this->pattern .= preg_replace('/\s+/', '\\s*', '(?>\\s*' . trim(array_shift($pattern)) . '\\s*)');
			if (! empty($pattern)) {
				$field = array_shift($pattern);
				$this->pattern_fields[] = $field;
				$this->pattern         .= '(.*)';
			}
		}

		// Listing
		$pattern     = $page . '/';
		$pattern_len = strlen($pattern);
		foreach (get_existpages() as $_page) {
			if (strpos($_page, $pattern) === 0) {
				$name = substr($_page, $pattern_len);
				if (preg_match(PLUGIN_TRACKER_LIST_EXCLUDE_PATTERN, $name)) continue;
				$this->add($_page, $name);
			}
		}
	}

	function add($page, $name)
	{
		static $done = array();

		if (isset($done[$page])) return;

		$done[$page] = TRUE;

		$source  = plugin_tracker_get_source($page);

		// Compat: 'move to [[page]]' (bugtrack plugin)
		$matches = array();
		if (! empty($source) && preg_match('/move\sto\s(.+)/', $source[0], $matches)) {
			$to_page = strip_bracket(trim($matches[1]));
			if (! is_page($to_page)) {
				return;	// Invalid
			} else {
				return $this->add($to_page, $name);	// Rescan
			}
		}

		// Default
		$this->rows[$name] = array(
			'_page'   => '[[' . $page . ']]',
			'_refer'  => $this->page,
			'_real'   => $name,
			'_update' => get_filetime($page),
			'_past'   => get_filetime($page),
			'_match'  => FALSE,
		);

		// Redefine
		$matches = array();
		$this->rows[$name]['_match'] =
			preg_match('/' . $this->pattern . '/s', implode('', $source), $matches);
		unset($source);

		if ($this->rows[$name]['_match']) {
			array_shift($matches);
			foreach ($this->pattern_fields as $key => $field) {
				$this->rows[$name][$field] = trim($matches[$key]);
			}
		}
	}

	function sort($order)
	{
		if ($order == '') return;

		$names       = array_flip(array_keys($this->fields));
		$this->order = array();

		foreach (explode(';', $order) as $item) {
			list($key, $dir) = array_pad(explode(':', $item), 1, 'ASC');
			if (! isset($names[$key])) continue;

			switch (strtoupper($dir)) {
			case 'SORT_ASC':
			case 'ASC':
			case SORT_ASC:
				$dir = SORT_ASC;
				break;
			case 'SORT_DESC':
			case 'DESC':
			case SORT_DESC:
				$dir = SORT_DESC;
				break;
			default:
				continue;
			}
			$this->order[$key] = $dir;
		}
		$keys   = array();
		$params = array();
		foreach ($this->order as $field => $order) {
			if (! isset($names[$field])) continue;

			foreach ($this->rows as $row) {
				$keys[$field][] = isset($row[$field])?
					$this->fields[$field]->get_value($row[$field]) :
					'';
			}
			$params[] = $keys[$field];
			$params[] = $this->fields[$field]->sort_type;
			$params[] = $order;
		}
		$params[] = & $this->rows;

		call_user_func_array('array_multisort', $params);
	}

	// Used with preg_replace_callback()  at toString()
	function replace_item($arr)
	{
		$params = explode(',', $arr[1]);
		$name   = array_shift($params);
		if ($name == '') {
			$str = '';
		} else if (isset($this->items[$name])) {
			$str = $this->items[$name];
			if (isset($this->fields[$name])) {
				$str = $this->fields[$name]->format_cell($str);
			}
		} else {
			return $this->pipe ? str_replace('|', '&#x7c;', $arr[0]) : $arr[0];
		}

		$style = empty($params) ? $name : $params[0];
		if (isset($this->items[$style]) && isset($this->fields[$style])) {
			$str = sprintf($this->fields[$style]->get_style($this->items[$style]), $str);
		}

		return $this->pipe ? str_replace('|', '&#x7c;', $str) : $str;
	}

	// Used with preg_replace_callback() at toString()
	function replace_title($arr)
	{
		global $script;

		$field = $sort = $arr[1];
		if (! isset($this->fields[$field])) return $arr[0];

		if ($sort == '_name' || $sort == '_page') $sort = '_real';

		$dir   = SORT_ASC;
		$arrow = '';
		$order = $this->order;
		if (is_array($order) && isset($order[$sort])) {
			// BugTrack2/106: Only variables can be passed by reference from PHP 5.0.5
			$order_keys = array_keys($order); // with array_shift();

			$index   = array_flip($order_keys);
			$pos     = 1 + $index[$sort];
			$b_end   = ($sort == array_shift($order_keys));
			$b_order = ($order[$sort] == SORT_ASC);
			$dir     = ($b_end xor $b_order) ? SORT_ASC : SORT_DESC;
			$arrow   = '&br;' . ($b_order ? '&uarr;' : '&darr;') . '(' . $pos . ')';

			unset($order[$sort], $order_keys);
		}
		$title  = $this->fields[$field]->title;
		$r_page = rawurlencode($this->page);
		$r_config = rawurlencode($this->config->config_name);
		$r_list = rawurlencode($this->list);
		$_order = array($sort . ':' . $dir);
		if (is_array($order)) {
			foreach ($order as $key => $value) {
				$_order[] = $key . ':' . $value;
			}
		}
		$r_order = rawurlencode(join(';', $_order));

		return '[[' . $title . $arrow . '>' .
				$script . '?plugin=tracker_list&refer=' . $r_page .
				'&config=' . $r_config .
				'&list=' . $r_list . '&order=' . $r_order . ']]';
	}

	function toString($limit = NULL)
	{
		global $_tracker_messages;

		$source = array();
		$count = count($this->rows);
		if ($limit !== NULL && $count > $limit) {
			$source[] = str_replace(
				array('$1',  '$2'),
				array($count, $limit),
				$_tracker_messages['msg_limit']) . "\n";
			$this->rows = array_splice($this->rows, 0, $limit);
		}
		if (empty($this->rows)) return '';

		$body   = array();
		foreach (plugin_tracker_get_source($this->config->page . '/' . $this->list) as $line) {
			if (preg_match('/^\|(.+)\|[hHfFcC]$/', $line)) {
				$source[] = preg_replace_callback('/\[([^\[\]]+)\]/', array(& $this, 'replace_title'), $line);
			} else {
				$body[] = $line;
			}
		}
		foreach ($this->rows as $row) {
			if (! PLUGIN_TRACKER_LIST_SHOW_ERROR_PAGE && ! $row['_match']) continue;

			$this->items = $row;
			foreach ($body as $line) {
				if (ltrim($line) == '') {
					$source[] = $line;
				} else {
					$this->pipe = ($line{0} == '|' || $line{0} == ':');
					$source[] = preg_replace_callback('/\[([^\[\]]+)\]/', array(& $this, 'replace_item'), $line);
				}
			}
		}

		return convert_html(implode('', $source));
	}
}

function plugin_tracker_get_source($page, $join = FALSE)
{
	$source = get_source($page, TRUE, $join);

	// Remove fixed-heading anchors
	$source = preg_replace('/^(\*{1,3}.*)\[#[A-Za-z][\w-]+\](.*)$/m', '$1$2', $source);

	// Remove #freeze-es
	return preg_replace('/^#freeze\s*$/im', '', $source);
}
?>
