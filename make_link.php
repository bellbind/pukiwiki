<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: make_link.php,v 1.35 2003/05/12 10:31:03 arino Exp $
//

// ��󥯤��ղä���
function make_link($string,$page = '')
{
	global $vars;
	static $converter;
	
	if (!isset($converter))
	{
		$converter = new InlineConverter();
	}
	$_converter = $converter; // copy
	return $_converter->convert($string, ($page != '') ? $page : $vars['page']);
}
//����饤�����Ǥ��ִ�����
class InlineConverter
{
	var $converters; // as array()
	var $pattern;
	var $pos;
	var $result;
	
	function InlineConverter($converters=NULL,$excludes=NULL)
	{
		if ($converters === NULL)
		{
			$converters = array(
				'plugin',        // ����饤��ץ饰����
				'note',          // ��� 
				'url',           // URL
				'url_interwiki', // URL (interwiki definition)
				'mailto',        // mailto:
				'interwikiname', // InterWikiName
				'autolink',      // AutoLink
				'bracketname',   // BracketName
				'wikiname',      // WikiName
			);
		}
		if ($excludes !== NULL)
		{
			$converters = array_diff($converters,$excludes);
		}
		$this->converters = array();
		$patterns = array();
		$start = 3; // $this->pattern�Υ��֥ѥ�����2�Ĥ�ޤ�
		
		foreach ($converters as $name)
		{
			$classname = "Link_$name";
			$converter = new $classname($start);
			$pattern = $converter->get_pattern();
			if ($pattern === FALSE)
			{
				continue;
			}
			$patterns[] = "(\n$pattern\n)";
			$this->converters[$start] = $converter;
			$start += $converter->get_count();
			$start++;
		}
		$this->pattern = '(.*?)('.join('|',$patterns).')'; // $start += 2
	}
	function convert($string,$page)
	{
		$this->page = $page;
		$this->result = '';
		
		$string = preg_replace_callback("/{$this->pattern}/x",array(&$this,'replace'),$string);
		
		return $this->result.htmlspecialchars($string);
	}
	function replace($arr)
	{
		$obj = $this->get_converter($arr);
		
		$this->result .= ($obj !== NULL and $obj->set($arr,$this->page) !== FALSE) ?
			htmlspecialchars($arr[1]).$obj->toString() : htmlspecialchars($arr[0]);
		
		return ''; //�����Ѥߤ���ʬ��ʸ���󤫤�������
	}
	function get_objects($string,$page)
	{
		preg_match_all("/{$this->pattern}/x",$string,$matches,PREG_SET_ORDER);
		
		$arr = array();
		foreach ($matches as $match)
		{
			$obj = $this->get_converter($match);
			if ($obj->set($match,$page) !== FALSE)
			{
				$arr[] = $obj; // copy
			}
		}
		return $arr;
	}
	function &get_converter(&$arr)
	{
		foreach (array_keys($this->converters) as $start)
		{
			if ($arr[$start] != '')
			{
				return $this->converters[$start];
			}
		}
		return NULL;
	}
}
//����饤�����ǽ���Υ١������饹
class Link
{
	var $start;   // ��̤���Ƭ�ֹ�(0���ꥸ��)
	var $text;    // �ޥå�����ʸ��������

	var $type;
	var $page;
	var $name;
	var $alias;

	// constructor
	function Link($start)
	{
		$this->start = $start;
	}
	// �ޥå��˻��Ѥ���ѥ�������֤�
	function get_pattern()
	{
	}
	// ���Ѥ��Ƥ����̤ο����֤� ((?:...)�����)
	function get_count()
	{
	}
	// �ޥå������ѥ���������ꤹ��
	function set($arr,$page)
	{
	}
	// ʸ������Ѵ�����
	function toString()
	{
	}
	
	//private
	// �ޥå��������󤫤顢��ʬ��ɬ�פ���ʬ��������Ф�
	function splice($arr)
	{
		$count = $this->get_count() + 1;
		$arr = array_splice($arr,$this->start,$count);
		while (count($arr) < $count)
		{
			$arr[] = '';
		}
		$this->text = $arr[0];
		return $arr;
	}
	// ���ܥѥ�᡼�������ꤹ��
	function setParam($page,$name,$type = '',$alias = '')
	{
		static $converter = NULL;
		
		$this->page = $page;
		$this->name = $name;
		$this->type = $type;
		if ($type != 'InterWikiName' and preg_match('/\.(gif|png|jpe?g)$/i',$alias))
		{
			$alias = "<img src=\"$alias\" alt=\"$name\" />";
		}
		else if ($alias != '')
		{
			if ($converter === NULL)
			{
				$converter = new InlineConverter(array('plugin'));
			}
			$alias = make_line_rules($converter->convert($alias,$page));
		}
		$this->alias = $alias;
		
		return TRUE;
	}
}
// ����饤��ץ饰����
class Link_plugin extends Link
{
	var $param,$body;
	
	function Link_plugin($start)
	{
		parent::Link($start);
	}
	function get_pattern()
	{
		return <<<EOD
&(\w+) # (1) plugin name
(?:
 \(
  ([^)]*)  # (2) parameter
 \)
)?
(?:
 \{
  (.*)     # (3) body
 \}
)?
;
EOD;
	}
	function get_count()
	{
		return 3;
	}
	function set($arr,$page)
	{
		$arr = $this->splice($arr);
		
		$name = $arr[1];
		$this->param = $arr[2];
		$this->body = ($arr[3] == '') ? '' : make_link($arr[3]);
		
		if (!exist_plugin_inline($name))
		{
			return FALSE;
		}
		
		return parent::setParam($page,$name,'plugin');
	}
	function toString()
	{
		return $this->make_inline($this->name,$this->param,$this->body);
	}
	function make_inline($func,$param,$body)
	{
		//&hoge(){...}; &fuga(){...}; ��body��'...}; &fuga(){...'�Ȥʤ�Τǡ������ʬ����
		$after = '';
		if (preg_match("/^ ((?!};).*?) }; (.*?) & (\w+) (?: \( ([^()]*) \) )? { (.+)$/x",$body,$matches))
		{
			$body = $matches[1];
			$after = $matches[2].$this->make_inline($matches[3],$matches[4],$matches[5]);
		}
		
		// �ץ饰����ƤӽФ�
		if (exist_plugin_inline($func))
		{
			$str = do_plugin_inline($func,$param,$body);
			if ($str !== FALSE) //����
			{
				return $str.$after;
			}
		}
		
		// �ץ饰����¸�ߤ��ʤ������Ѵ��˼���
		return htmlspecialchars($this->text);
	}
}
// ���
class Link_note extends Link
{
	function Link_note($start)
	{
		parent::Link($start);
	}
	function get_pattern()
	{
		return <<<EOD
\(\(    # open paren
 (      # (1) note body
  (?:
   (?>  # once-only 
    (?:
     (?!\(\()(?!\)\)(?:[^\)]|$)).
    )+
   )
   |
   (?R) # or recursive of me
  )*
 )
\)\)
EOD;
	}
	function get_count()
	{
		return 1;
	}
	function set($arr,$page)
	{
		global $foot_explain;
		static $note_id = 0;
		
		$arr = $this->splice($arr);
		
		$id = ++$note_id;
		$note = make_line_rules(make_link($arr[1]));
		
		$foot_explain[$id] = <<<EOD
<a id="notefoot_$id" href="#notetext_$id" class="note_super">*$id</a>
<span class="small">$note</span>
<br />
EOD;
		$name = "<a id=\"notetext_$id\" href=\"#notefoot_$id\" class=\"note_super\">*$id</a>";
		
		return parent::setParam($page,$name);
	}
	function toString()
	{
		return $this->name;
	}
}
// url
class Link_url extends Link
{
	function Link_url($start)
	{
		parent::Link($start);
	}
	function get_pattern()
	{
		$s1 = $this->start + 1;
		return <<<EOD
(\[\[         # (1) open bracket
 ([^\]]+)     # (2) alias
 (?:&gt;|>|:) # '&gt;' or '>' or ':'
)?
(             # (3) url
 (?:https?|ftp|news):\/\/[!~*'();\/?:\@&=+\$,%#\w.-]+
)
(?($s1)\]\])  # close bracket
EOD;
	}
	function get_count()
	{
		return 3;
	}
	function set($arr,$page)
	{
		$arr = $this->splice($arr);
		
		$name = $arr[3];
		$alias = htmlspecialchars(($arr[2] == '') ? $arr[3] : $arr[2]);
		return parent::setParam($page,$name,'url',$alias);
		
	}
	function toString()
	{
		return "<a href=\"{$this->name}\">{$this->alias}</a>";
	}
}
// url (InterWiki definition type)
class Link_url_interwiki extends Link
{
	function Link_url_interwiki($start)
	{
		parent::Link($start);
	}
	function get_pattern()
	{
		return <<<EOD
\[       # open bracket
(        # (1) url
 (?:(?:https?|ftp|news):\/\/|\.\.?\/)[!~*'();\/?:\@&=+\$,%#\w.-]*
)
\s
([^\]]+) # (2) alias
\]       # close bracket
EOD;
	}
	function get_count()
	{
		return 2;
	}
	function set($arr,$page)
	{
		$arr = $this->splice($arr);
		
		$name = $arr[1];
		$alias = htmlspecialchars($arr[2]);

		return parent::setParam($page,$name,'url',$alias);
	}
	function toString()
	{
		return "<a href=\"{$this->name}\">{$this->alias}</a>";
	}
}
//mailto:
class Link_mailto extends Link
{
	var $is_image,$image;
	
	function Link_mailto($start)
	{
		parent::Link($start);
	}
	function get_pattern()
	{
		$s1 = $this->start + 1;
		return <<<EOD
(?:\[\[([^\]]+)(?:&gt;|>|:))? # (1) alias
 ([\w.-]+@[\w-]+\.[\w.-]+)    # (2) mailto
(?($s1)\]\])                  # close bracket if (1)
EOD;
	}
	function get_count()
	{
		return 2;
	}
	function set($arr,$page)
	{
		$arr = $this->splice($arr);
		
		$name = $arr[2];
		$alias = htmlspecialchars(($arr[1] == '') ? $arr[2] : $arr[1]);
		
		return parent::setParam($page,$name,'mailto',$alias);
	}
	function toString()
	{
		return "<a href=\"mailto:{$this->name}\">{$this->alias}</a>";
	}
}
//InterWikiName
class Link_interwikiname extends Link
{
	var $anchor = '';
	
	function Link_interwikiname($start)
	{
		parent::Link($start);
	}
	function get_pattern()
	{
		$s1 = $this->start + 1;
		$s3 = $this->start + 3;
		$s5 = $this->start + 5;
		return <<<EOD
\[\[                    # open bracket
(?:
 (\[\[)?                # (1) open bracket
 ([^\[\]]+)             # (2) alias
 (?:&gt;|>)             # '&gt;' or '>'
)?
(?:
 (\[\[)?                # (3) open bracket
 (\[*?[^\s\]]+?\]*?)    # (4) InterWiki
 (                      # (5)
  (?($s1)\]\]           #  close bracket if (1)
  |(?($s3)\]\])         #   or (3)
  )
 )?
 (?<!&gt;)
 (\:(?:(?<!&gt;|>).)*?) # (6) param
 (?($s5) |              # if !(5)
  (?($s1)\]\]           #  close bracket if (1)
  |(?($s3)\]\])         #   or (3)
  )
 )
)?
\]\]                    # close bracket
EOD;
	}
	function get_count()
	{
		return 6;
	}
	function set($arr,$page)
	{
		$arr = $this->splice($arr);
		
		$param = $arr[6];
		if (preg_match('/^([^#]+)(#[A-Za-z][\w-]*)$/',$param,$matches))
		{
			$this->anchor = $matches[2];
			$param = $matches[1];
		}
		$name = rawurlencode('[['.$arr[4].$param.']]');
		$alias = htmlspecialchars(($arr[2] != '') ? $arr[2] : $arr[4].$arr[6]);
		
		return parent::setParam($page,$name,'InterWikiName',$alias);
	}
	function toString()
	{
		global $script; //,$interwiki_target;
		
		return "<a href=\"$script?{$this->name}{$this->anchor}\">{$this->alias}</a>";
	}
}
// BracketName
class Link_bracketname extends Link
{
	var $anchor,$refer;
	
	function Link_bracketname($start)
	{
		parent::Link($start);
	}
	function get_pattern()
	{
		global $WikiName,$BracketName;
		
		$s1 = $this->start + 1;
		$s3 = $this->start + 3;
		$s7 = $this->start + 7;
		return <<<EOD
\[\[                     # open bracket
(?:
 (\[\[)?                 # (1) open bracket
 ([^\[\]]+)              # (2) alias
 (?:&gt;|>)              # '&gt;' or '>'
)?
(\[\[)?                  # (3) open bracket
(                        # (4) PageName
 ($WikiName)             # (5) WikiName
 |
 ($BracketName)          # (6) BracketName
)?
(                        # (7)
 (?($s1)\]\]             #  close bracket if (1)
  |(?($s3)\]\])          #   or (3)
 )
)
(\#(?:[a-zA-Z][\w-]*)?)? # (8) anchor
(?($s7)|                 # if !(7)
 (?($s1)\]\]             #  close bracket if (1)
  |(?($s3)\]\])          #   or (3)
 )
)
\]\]                     # close bracket
EOD;
	}
	function get_count()
	{
		return 8;
	}
	function set($arr,$page)
	{
		global $WikiName,$BracketName;
		
		$arr = $this->splice($arr);
		
		$alias = $arr[2];
		$name = $arr[4];
		$this->anchor = $arr[8];
		
		if ($name == '' and $this->anchor == '')
		{
			return FALSE;
		}
		if ($name != '' and preg_match("/^$WikiName$/",$name))
		{
			return parent::setParam($page,$name,'pagename',$alias);
		}
		if ($alias == '')
		{
			$alias = $name.$this->anchor;
		}
		if ($name == '' and $this->anchor == '')
		{
			return FALSE;
		}
		
		$name = get_fullname($name,$page);
		
		if ($name != '' and !preg_match("/^($WikiName)|($BracketName)$/",$name))
		{
			return FALSE;
		}
		return parent::setParam($page,$name,'pagename',$alias);
	}
	function toString()
	{
		return make_pagelink(
			$this->name,
			$this->alias,
			$this->anchor,
			$this->page
		);
	}
}
// WikiName
class Link_wikiname extends Link
{
	function Link_wikiname($start)
	{
		parent::Link($start);
	}
	function get_pattern()
	{
		global $WikiName,$nowikiname;
		
		return $nowikiname ? FALSE : "($WikiName)";
	}
	function get_count()
	{
		return 1;
	}
	function set($arr,$page)
	{
		$arr = $this->splice($arr);
		$name = $alias = $arr[0];
		return parent::setParam($page,$name,'pagename',$alias);
	}
	function toString()
	{
		return make_pagelink(
			$this->name,
			$this->alias,
			'',
			$this->page
		);
	}
}
// AutoLink
class Link_autolink extends Link
{
	var $forceignorepages = array();
	
	function Link_autolink($start)
	{
		parent::Link($start);
	}
	function get_pattern()
	{
		global $autolink;
		static $auto,$forceignorepages;
		
		if (!$autolink or !file_exists(CACHE_DIR.'autolink.dat'))
		{
			return FALSE;
		}
		if (!isset($auto)) // and/or !isset($forceignorepages)
		{
			@list($auto,$forceignorepages) = file(CACHE_DIR.'autolink.dat');
			$forceignorepages = explode("\t",$forceignorepages);
		}
		$this->forceignorepages = $forceignorepages;
		return "($auto)";
	}
	function get_count()
	{
		return 1;
	}
	function set($arr,$page)
	{
		global $WikiName;
		
		$arr = $this->splice($arr);
		$name = $alias = $arr[0];
		// ̵��ꥹ�Ȥ˴ޤޤ�Ƥ��롢���뤤��¸�ߤ��ʤ��ڡ�����ΤƤ�
		if (in_array($name,$this->forceignorepages) or !is_page($name))
		{
			return FALSE;
		}
		return parent::setParam($page,$name,'pagename',$alias);
	}
	function toString()
	{
		return make_pagelink(
			$this->name,
			$this->alias,
			'',
			$this->page
		);
	}
}
// �ڡ���̾�Υ�󥯤����
function make_pagelink($page,$alias='',$anchor='',$refer='')
{
	global $script,$vars,$show_title,$show_passage,$link_compact,$related;
	
	$s_page = htmlspecialchars(strip_bracket($page));
	$s_alias = ($alias == '') ? $s_page : $alias;
	
	if ($page == '')
	{
		return "<a href=\"$anchor\">$s_alias</a>";
	}
	
	$r_page = rawurlencode($page);
	$r_refer = ($refer == '') ? '' : '&amp;refer='.rawurlencode($refer);
	
	if (!array_key_exists($page,$related) and $page != $vars['page'] and is_page($page))
	{
		$related[$page] = get_filetime($page);
	}
	
	if (is_page($page))
	{
		$passage = get_pg_passage($page,FALSE);
		$title = $link_compact ? '' : " title=\"$s_page$passage\"";
		return "<a href=\"$script?$r_page$anchor\"$title>$s_alias</a>";
	}
	else
	{
		return $link_compact ?
			"$s_alias<a href=\"$script?cmd=edit&amp;page=$r_page$r_refer\">?</a>" :
			"<span class=\"noexists\">$s_alias<a href=\"$script?cmd=edit&amp;page=$r_page$r_refer\">?</a></span>";
	}
}
// ���л��Ȥ�Ÿ��
function get_fullname($name,$refer)
{
	global $defaultpage;
	
	if ($name == './')
	{
		return $refer;
	}
	
	if (substr($name,0,2) == './')
	{
		$arrn = preg_split('/\//',$name,-1,PREG_SPLIT_NO_EMPTY);
		$arrn[0] = $refer;
		return join('/',$arrn);
	}
	
	if (substr($name,0,3) == '../')
	{
		$arrn = preg_split('/\//',$name,-1,PREG_SPLIT_NO_EMPTY);
		$arrp = preg_split('/\//',$refer,-1,PREG_SPLIT_NO_EMPTY);
		
		while (count($arrn) > 0 and $arrn[0] == '..')
		{
			array_shift($arrn);
			array_pop($arrp);
		}
		$name = count($arrp) ? join('/',array_merge($arrp,$arrn)) :
			(count($arrn) ? "$defaultpage/".join('/',$arrn) : $defaultpage);
	}
	return $name;
}
?>
