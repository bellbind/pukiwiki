<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: plugin.php,v 1.3 2003/01/27 05:38:44 panda Exp $
//

// �ץ饰�����Ѥ�̤������ѿ�������
function set_plugin_messages($messages)
{
	foreach ($messages as $name=>$val) {
		global $$name;
		
		if(!isset($$name)) {
			$$name = $val;
		}
	}
}

//�ץ饰����(action)��¸�ߤ��뤫
function exist_plugin_action($name)
{
	if (!file_exists(PLUGIN_DIR.$name.'.inc.php')) {
		return FALSE;
	}
	
	require_once(PLUGIN_DIR.$name.'.inc.php');
	return function_exists('plugin_'.$name.'_action');
}

//�ץ饰����(convert)��¸�ߤ��뤫
function exist_plugin_convert($name) {
	if (!file_exists(PLUGIN_DIR.$name.'.inc.php')) {
		return FALSE;
	}
	
	require_once(PLUGIN_DIR.$name.'.inc.php');
	return function_exists('plugin_'.$name.'_convert');
}

//�ץ饰����(inline)��¸�ߤ��뤫
function exist_plugin_inline($name)
{
	if (!file_exists(PLUGIN_DIR.$name.'.inc.php')) {
		return FALSE;
	}
	
	require_once(PLUGIN_DIR.$name.'.inc.php');
	return function_exists('plugin_'.$name.'_inline');
}

//�ץ饰����ν������¹�
function do_plugin_init($name)
{
	$funcname = 'plugin_'.$name.'_init';
	if (!function_exists($funcname)) {
		return FALSE;
	}
	
	$func_check = '_funccheck_'.$funcname;
	global $$func_check;
	
	if ($$func_check) {
		return TRUE;
	}
	$$func_check = TRUE;
	return @call_user_func($funcname);
}

//�ץ饰����(action)��¹�
function do_plugin_action($name)
{
	if(!exist_plugin_action($name)) {
		return array();
	}
	
	do_plugin_init($name);
	return @call_user_func('plugin_'.$name.'_action');
}

//�ץ饰����(convert)��¹�
function do_plugin_convert($name,$args)
{
	$aryargs = ($args !== '') ? explode(',',$args) : array();

	do_plugin_init($name);
	$retvar = call_user_func_array('plugin_'.$name.'_convert',$aryargs);
	
	if($retvar === FALSE) {
		return "#${name}(${args})";
	}
	
	return $retvar;
}

//�ץ饰����(inline)��¹�
function do_plugin_inline($name,$args,$body)
{
	$aryargs = ($args !== '') ? explode(',',$args) : array();
	$aryargs[] =& $body;

	do_plugin_init($name);
	$retvar = call_user_func_array('plugin_'.$name.'_inline',$aryargs);
	
	if($retvar === FALSE) {
		return "#${name}(${args})";
	}
	
	return $retvar;
}
?>
