<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: ref.inc.php,v 1.25 2004/08/18 13:28:25 henoheno Exp $
//

/*

* �ץ饰���� ref
�ڡ�����ź�դ��줿�ե������Ÿ������
URL��Ÿ������

* Usage
 #ref(filename[,page][,parameters][,title])

* �ѥ�᡼��
- filename~
ź�եե�����̾�����뤤��URL

'�ڡ���̾/ź�եե�����̾'����ꤹ��ȡ����Υڡ�����ź�եե�����򻲾Ȥ���

- page~
�ե������ź�դ����ڡ���̾(��ά��)~

- Left|Center|Right
���ΰ��ֹ�碌

- Wrap|Nowrap
�ơ��֥륿���ǰϤ�/�Ϥޤʤ�

- Around
�ƥ����Ȥβ�����

- noicon
���������ɽ�����ʤ�

- nolink
���ե�����ؤΥ�󥯤�ĥ��ʤ�

- noimg
������Ÿ�����ʤ�

- zoom
�Ĳ�����ݻ�����

- 999x999
�����������(��x�⤵)

- 999%
�����������(����Ψ)

- ����¾��ʸ����
img��alt/href��title�Ȥ��ƻ���~
�ڡ���̾��ѥ�᡼���˸�����ʸ�������Ѥ���Ȥ��ϡ�#ref(hoge.png,,zoom)�Τ褦��
�����ȥ�����˥���ޤ�;ʬ�������

*/

// File icon image
if (! defined('FILE_ICON')) {
	define('FILE_ICON',
	'<img src="' . IMAGE_DIR . 'file.png" width="20" height="20"' .
	' alt="file" style="border-width:0px" />');
}

// Default alignment
define('REF_DEFAULT_ALIGN', 'left'); // 'left','center','right'

// Force wrap on default
define('REF_WRAP_TABLE', FALSE); // TRUE,FALSE

// URL������˲�����������������뤫
define('REF_URL_GETIMAGESIZE', FALSE);

function plugin_ref_inline()
{
	// Not reached, because of "$aryargs[] = & $body" at plugin.php
	// if (! func_num_args()) return '&amp;ref(): No arguments;';

	$params = plugin_ref_body(func_get_args());

	if (isset($params['_error']) && $params['_error'] != '') {
		return '&amp;ref(): ' . $params['_error'] . ';';
	} else {
		return $params['_body'];
	}
}

function plugin_ref_convert()
{
	if (! func_num_args()) return '<p>#ref(): No arguments</p>';

	$params = plugin_ref_body(func_get_args());

	if (isset($params['_error']) && $params['_error'] != '') {
		return "<p>#ref(): {$params['_error']}</p>";
	}

	if ((REF_WRAP_TABLE && ! $params['nowrap']) || $params['wrap']) {
		// �Ȥ����
		// margin:auto
		//	Mozilla 1.x  = x (wrap,around�������ʤ�)
		//	Opera 6      = o
		//	Netscape 6   = x (wrap,around�������ʤ�)
		//	IE 6         = x (wrap,around�������ʤ�)
		// margin:0px
		//	Mozilla 1.x  = x (wrap�Ǵ󤻤������ʤ�)
		//	Opera 6      = x (wrap�Ǵ󤻤������ʤ�)
		//	Netscape 6   = x (wrap�Ǵ󤻤������ʤ�)
		//	IE6          = o
		$margin = ($params['around'] ? '0px' : 'auto');
		$margin_align = ($params['_align'] == 'center') ? '' : ";margin-{$params['_align']}:0px";
		$params['_body'] = <<<EOD
<table class="style_table" style="margin:$margin$margin_align">
 <tr>
  <td class="style_td">{$params['_body']}</td>
 </tr>
</table>
EOD;
	}

	if ($params['around']) {
		$style = ($params['_align'] == 'right') ? 'float:right' : 'float:left';
	} else {
		$style = "text-align:{$params['_align']}";
	}

	// div�����
	return "<div class=\"img_margin\" style=\"$style\">{$params['_body']}</div>\n";
}

function plugin_ref_body($args)
{
	global $script, $vars, $WikiName, $BracketName;

	$params = array(); // �����
	$page = isset($vars['page']) ? $vars['page'] : '';

	// ź�եե�����̾�����
	$name = array_shift($args);

	// ���ΰ������ڡ���̾���ɤ���
	if (! empty($args) &&
		preg_match("/^($WikiName|\[\[$BracketName\]\])$/", $args[0]))
	{
		$_page = get_fullname(strip_bracket($args[0]), $page);
		if (is_pagename($_page)) {
			$page = $_page;
			array_shift($args);
		}
	}

	// �ѥ�᡼��
	$params = array(
		'left'   => FALSE, // ����
		'center' => FALSE, // �����
		'right'  => FALSE, // ����
		'wrap'   => FALSE, // TABLE�ǰϤ�
		'nowrap' => FALSE, // TABLE�ǰϤޤʤ�
		'around' => FALSE, // ������
		'noicon' => FALSE, // ���������ɽ�����ʤ�
		'nolink' => FALSE, // ���ե�����ؤΥ�󥯤�ĥ��ʤ�
		'noimg'  => FALSE, // ������Ÿ�����ʤ�
		'zoom'   => FALSE, // �Ĳ�����ݻ�����
		'_size'  => FALSE, // ���������ꤢ��
		'_w'     => 0,       // ��
		'_h'     => 0,       // �⤵
		'_%'     => 0,     // ����Ψ
		'_args'  => array(),
		'_done'  => FALSE,
		'_error' => ''
	);

	if (! empty($args)) {
		foreach ($args as $arg) {
			ref_check_arg($arg, $params);
		}
	}

/*
 $name���Ȥ˰ʲ����ѿ�������
 $url,$url2 : URL
 $title :�����ȥ�
 $is_image : �����ΤȤ�TRUE
 $info : �����ե�����ΤȤ�getimagesize()��'size'
         �����ե�����ʳ��Υե�����ξ���
         ź�եե�����ΤȤ� : �ե�����κǽ��������ȥ�����
         URL�ΤȤ� : URL���Τ��
*/
	$file = $title = $url = $url2 = $info = '';
	$width = $height = 0;
	$matches = array();

	if (is_url($name)) {	// URL
		$url = $url2 = htmlspecialchars($name);
		$title = htmlspecialchars(preg_match('/([^\/]+)$/', $name, $matches) ? $matches[1] : $url);

		$is_image = (! $params['noimg'] && preg_match("/\.(gif|png|jpe?g)$/i",$name));

		if (REF_URL_GETIMAGESIZE && $is_image && (bool)ini_get('allow_url_fopen')) {
			$size = @getimagesize($name);
			if (is_array($size)) {
				$width  = $size[0];
				$height = $size[1];
				$info   = $size[3];
			}
		}

	} else { // ź�եե�����
		if (! is_dir(UPLOAD_DIR)) {
			$params['_error'] = 'No UPLOAD_DIR';
			return $params;
		}

		// �ڡ�������Υ����å�
		if (preg_match('/^(.+)\/([^\/]+)$/', $name, $matches)) {
			if ($matches[1] == '.' || $matches[1] == '..') {
				$matches[1] .= '/';
			}
			$page = get_fullname($matches[1],$page);
			$name = $matches[2];
		}
		$title = htmlspecialchars($name);
		$file = UPLOAD_DIR . encode($page) . '_' . encode($name);

		if (! is_file($file)) {
			$params['_error'] = 'File not found';
			return $params;
		}

		$is_image = (! $params['noimg'] && preg_match('/\.(gif|png|jpe?g)$/i', $name));

		$url = $script . '?plugin=attach' . '&amp;refer=' . rawurlencode($page) .
			'&amp;openfile=' . rawurlencode($name); // Show its filename at the last

		if ($is_image) {
			// Swap $url
			$url2 = $url;
			$url  = $file;

			$width = $height = 0;
			$size = @getimagesize($file);
			if (is_array($size)) {
				$width  = $size[0];
				$height = $size[1];
			}
		} else {
			$info = get_date('Y/m/d H:i:s', filemtime($file) - LOCALZONE) .
				' ' . sprintf('%01.1f', round(filesize($file)/1024, 1)) . 'KB';
		}
	}

	// ��ĥ�ѥ�᡼��������å�
	if (! empty($params['_args'])) {
		$_title = array();
		foreach ($params['_args'] as $arg) {
			if (preg_match('/^([0-9]+)x([0-9]+)$/', $arg, $matches)) {
				$params['_size'] = TRUE;
				$params['_w'] = $matches[1];
				$params['_h'] = $matches[2];

			} else if (preg_match('/^([0-9.]+)%$/', $arg, $matches) && $matches[1] > 0) {
				$params['_%'] = $matches[1];

			} else {
				$_title[] = $arg;
			}
		}

		if (! empty($_title)) {
			$title = htmlspecialchars(join(',', $_title));
			if ($is_image) $title = make_line_rules($title);
		}
	}

	// ����������Ĵ��
	if ($is_image) {
		// ���ꤵ�줿����������Ѥ���
		if ($params['_size']) {
			if ($width == 0 && $height == 0) {
				$width  = $params['_w'];
				$height = $params['_h'];
			} else if ($params['zoom']) {
				$_w = $params['_w'] ? $width  / $params['_w'] : 0;
				$_h = $params['_h'] ? $height / $params['_h'] : 0;
				$zoom = max($_w, $_h);
				if ($zoom) {
					$width  = (int)($width  / $zoom);
					$height = (int)($height / $zoom);
				}
			} else {
				$width  = $params['_w'] ? $params['_w'] : $width;
				$height = $params['_h'] ? $params['_h'] : $height;
			}
		}
		if ($params['_%']) {
			$width  = (int)($width  * $params['_%'] / 100);
			$height = (int)($height * $params['_%'] / 100);
		}
		if ($width && $height) $info = "width=\"$width\" height=\"$height\" ";
	}

	// ���饤�����Ƚ��
	$params['_align'] = REF_DEFAULT_ALIGN;
	foreach (array('right', 'left', 'center') as $align) {
		if ($params[$align])  {
			$params['_align'] = $align;
			break;
		}
	}

	if ($is_image) { // ����
		$params['_body'] = "<img src=\"$url\" alt=\"$title\" title=\"$title\" $info/>";
		if (! $params['nolink'] && $url2)
			$params['_body'] = "<a href=\"$url2\" title=\"$title\">{$params['_body']}</a>";
	} else {
		$icon = $params['noicon'] ? '' : FILE_ICON;
		$params['_body'] = "<a href=\"$url\" title=\"$info\">$icon$title</a>";
	}

	return $params;
}

//-----------------------------------------------------------------------------
// ���ץ�������Ϥ���
function ref_check_arg($val, & $params)
{
	if ($val == '') {
		$params['_done'] = TRUE;
		return;
	}

	if (! $params['_done']) {
		foreach (array_keys($params) as $key) {
			if (strpos($key, strtolower($val)) === 0) {
				$params[$key] = TRUE;
				return;
			}
		}
		$params['_done'] = TRUE;
	}

	$params['_args'][] = $val;
}
?>
