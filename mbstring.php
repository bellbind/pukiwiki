<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: mbstring.php,v 1.1 2003/07/03 05:31:39 arino Exp $
//

/*
 * PHP��mbstring extension�����ѤǤ��ʤ��Ȥ������شؿ�
 *
 * ������ˡ
 *
 * jcode_1.34.zip (http://www.spencernetwork.org/)�����ꤷ�ơ�
 * mbstring.php��Ʊ���Ȥ���˥ǥ��쥯�ȥ��դ���Ÿ�����Ƥ���������
 * 
 * -+--- mbstring.php          -r--
 *  +-+- jcode_1.34/           dr-x
 *    +--- readme.txt          -r--
 *    +--- jcode.phps          -r--
 *    +--- jcode_wrapper.php   -r--
 *    +--- code_table.ucs2jis  -r--
 *    +--- code_table.jis2ucs  -r--
 *
 */

require_once('jcode_1.34/jcode_wrapper.php');

if (!function_exists('jcode_convert_encoding'))
{
	die_message('Multibyte functions cannot be used. Please read "mbstring.php" for an additional installation procedure.');
}

// mb_convert_encoding -- ʸ�����󥳡��ǥ��󥰤��Ѵ�����
function mb_convert_encoding($str,$to_encoding,$from_encoding='')
{
	// ��ĥ: ������������褦��
	// mb_convert_variable�к�
	if (is_array($str))
	{
		foreach ($str as $key=>$value)
		{
			$str[$key] = mb_convert_encoding($value,$to_encoding,$from_encoding);
		}
		return $str;
	}
	return jcode_convert_encoding($str,$to_encoding,$from_encoding);
}

// mb_convert_variables -- �ѿ���ʸ�������ɤ��Ѵ�����
function mb_convert_variables($to_encoding,$from_encoding,&$get,&$post)
{
	// ��: ����Ĺ�����ǤϤʤ���init.php����ƤФ��2�����Τߤ򥵥ݡ���
	// ��ľ�˼�������ʤ顢���Ѱ������ե���󥹤Ǽ�������ˡ��ɬ��
	$encoding = mb_detect_encoding(join_array(' ',array_merge($get,$post)));   
	if ($encoding != 'ASCII' and $encoding != SOURCE_ENCODING)
	{
		foreach ($get as $key=>$value)
		{
			$get[$key] = mb_convert_encoding($value,$to_encoding,$encode);
		}
		foreach ($post as $key=>$value)
		{
			$post[$key] = mb_convert_encoding($value,$to_encoding,$encode);
		}
	}
	return $encoding;
}

// ����ؿ�:�����Ƶ�Ū��join����
function join_array($glue,$pieces)
{
	$arr = array();
	foreach ($pieces as $piece)
	{
		$arr[] = is_array($piece) ? join_array($glue,$piece) : $piece;
	}
	return join($glue,$arr);
}

// mb_detect_encoding -- ʸ�����󥳡��ǥ��󥰤򸡽Ф���
function mb_detect_encoding($str,$encoding_list='')
{
	static $codes = array(0=>'ASCII',1=>'EUC-JP',2=>'SJIS',3=>'JIS',4=>'UTF-8');
	
	// ��: $encoding_list�ϻ��Ѥ��ʤ���
	$code = AutoDetect($str);
	if (!array_key_exists($code,$codes))
	{
		$code = 0; // oh ;(
	}
	return $codes[$code];
}

// mb_detect_order --  ʸ�����󥳡��ǥ��󥰸��н��������/���� 
function mb_detect_order($encoding_list=NULL)
{
	static $list = array();
	
	// ��: ¾�δؿ��˱ƶ���ڤܤ��ʤ����Ƥ�Ǥ�̵��̣��
	if ($encoding_list === NULL)
	{
		return $list;
	}
	$list = is_array($encoding_list) ? $encoding_list : explode(',',$encoding_list);
	return TRUE;
}

// mb_encode_mimeheader -- MIME�إå���ʸ����򥨥󥳡��ɤ���
function mb_encode_mimeheader($str,$charset='ISO-2022-JP',$transfer_encoding='B',$linefeed="\r\n")
{
	// ��: $transfer_encoding�˴ؤ�餺base64���󥳡��ɤ��֤�
	$str = mb_convert_encoding($str,$charset,'auto');
	return '=?'.$charset.'?B?'.$str;
}

// mb_http_output -- HTTP����ʸ�����󥳡��ǥ��󥰤�����/����
function mb_http_output($encoding='')
{
	// ��: ���⤷�ʤ�
	return SOURCE_ENCODING;
}

// mb_internal_encoding --  ����ʸ�����󥳡��ǥ��󥰤�����/����
function mb_internal_encoding($encoding='')
{
	// ��: ���⤷�ʤ�
	return SOURCE_ENCODING;
}

// mb_language --  �����Ȥθ��������/���� 
function mb_language($language=NULL)
{
	static $mb_language = FALSE;
	if ($language === NULL)
	{
		return $mb_language;
	}
	// ��: ���TRUE���֤�
	$mb_language = $language;
	return TRUE;
}

// mb_strimwidth -- ���ꤷ������ʸ�����ݤ��
function mb_strimwidth($str,$start,$width,$trimmarker='',$encoding='')
{
	$substr = mb_substr($str,$start,$width);
	if (strlen($str) > strlen($substr))
	{
		// ��: �����strlen($substr.$trimmarker) == $width�Ȥʤ�٤�
		$substr .= $trimmarker;
	}
	return $substr;
}

// mb_strlen -- ʸ�����Ĺ��������
function mb_strlen($str,$encoding='')
{
	// ��: $encoding����Ѥ��ʤ�
	return jstrlen($str);
}

// mb_substr -- ʸ����ΰ���������
function mb_substr($str,$start,$length=0,$encoding='')
{
	// ��: $encoding����Ѥ��ʤ�
	return jsubstr($str,$start,$length);
}
?>
