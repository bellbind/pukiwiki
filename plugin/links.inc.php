<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: links.inc.php,v 1.13 2003/03/10 12:35:19 panda Exp $
//

function plugin_links_action()
{
	global $vars,$adminpass,$whatsnew;
	
	set_time_limit(0);
	
	if ($vars['page'] != '' and $vars['page'] != $whatsnew)
	{
		plugin_links_updatedata($vars['page']);
		return;
	}
	if (md5($vars['adminpass']) != $adminpass)
	{
		return array('msg'=>'update database',
			'body'=>'<p>administrator password require.</p>');
	}
	return plugin_links_initdata();
}
function &plugin_links_get_objects($page)
{
	static $obj;
	
	if (!isset($obj))
	{
		$obj = new InlineConverter(array('url','mailto','interwiki','page','auto'));
	}
	
	return $obj->get_objects(join('',preg_grep('/^(?!\/\/|\s)./',get_source($page))),$page);
}
function plugin_links_initdata()
{
	global $whatsnew;
	
	if (defined('LINK_DB'))
	{
		// �ǡ����١����ν����
		$pages = get_existpages();
		db_exec('DELETE FROM page;');
		db_exec('DELETE FROM link;');
		foreach ($pages as $page)
		{
			if ($page == $whatsnew)
			{
				continue;
			}
			$time = get_filetime($page);
			$a_page = addslashes($page);
			db_exec("INSERT INTO page (name,lastmod) VALUES ('$a_page',$time);");
		}
		$rows = db_query('SELECT id,name FROM page;');
		$pages = array();
		foreach ($rows as $row)
		{
			$pages[$row['name']] = $row['id'];
		}
		
		foreach ($pages as $page=>$id)
		{
			$links = plugin_links_get_objects($page);
			foreach ($links as $_obj)
			{
				if ($_obj->type != 'pagename')
				{
					continue;
				}
				$_page = $_obj->name;
				if (!array_key_exists($_page,$pages))
				{
					$a_page = addslashes($_page);
					db_exec("INSERT INTO page (name) VALUES ('$a_page');");
					$rows = db_query("SELECT id,name FROM page WHERE name='$a_page';");
					$row = $rows[0];
					$pages[$row['name']] = $row['id'];
				}	
					
				$ref_id = $pages[$_page];
				if ($ref_id and $ref_id != $id)
				{
					db_exec("INSERT INTO link (page_id,ref_id) VALUES ($id,$ref_id);");
				}
			}
		}
	}
	else // if (!defined('LINK_DB'))
	{
		// �ǡ����١����ν����
		foreach (get_existfiles(CACHE_DIR,'.ref') as $cache)
		{
			unlink($cache);
		}
		foreach (get_existfiles(CACHE_DIR,'.rel') as $cache)
		{
			unlink($cache);
		}
		$pages = get_existpages();
		$ref = array(); // ���ȸ�
		foreach ($pages as $page)
		{
			if ($page == $whatsnew)
			{
				continue;
			}
			$time = get_filetime($page);
			$rel = array(); // ������
			$links = plugin_links_get_objects($page);
			foreach ($links as $_obj)
			{
				if (!isset($_obj->type) or $_obj->type != 'pagename')
				{
					continue;
				}			
				$_page = $_obj->name;
				if ($_page != $page)
				{
					$rel[$_page] = 1;
					$ref[$_page][$page] = $time;
				}
			}
			if (count($rel))
			{
				$fp = fopen(CACHE_DIR.encode($page).'.rel','w')
					or die_message('cannot write '.htmlspecialchars(CACHE_DIR.encode($page).'.rel'));
				fputs($fp,join("\t",array_keys($rel)));
				fclose($fp);
			}
		}
		
		foreach ($ref as $page=>$arr)
		{
			if (count($arr) == 0)
			{
				continue;
			}
			$fp = fopen(CACHE_DIR.encode($page).'.ref','w')
				or die_message('cannot write '.htmlspecialchars(CACHE_DIR.encode($page).'.ref'));
			foreach ($arr as $_page=>$time)
			{
				fputs($fp,"$_page\t$time\n");
			}
			fclose($fp);
		}
	}
	return array('msg'=>'update database','body'=>'<p>done.</p>');
}
function plugin_links_updatedata($page)
{
	global $whatsnew;
	
	if (defined('LINK_DB'))
	{
		$is_page = is_page($page);
		$time = ($is_page) ? get_filetime($page) : 0;
		$a_page = addslashes($page);
		
		$rows = db_query("SELECT id FROM page WHERE name='$a_page';");
		if (count($rows) == 0)
		{
			if (!$is_page)
			{
				return;
			}
			db_exec("INSERT INTO page (name,lastmod) VALUES ('$a_page',$time);");
			$rows = db_query("SELECT id FROM page WHERE name='$a_page';");
			$id = $rows[0]['id'];
		}
		else // if (count($rows) > 0)
		{
			$id = $rows[0]['id'];
			// $page�����Ȥ��Ƥ���ڡ���������
			db_exec("DELETE FROM link WHERE page_id=$id;");
			if (!$is_page)
			{
				$_rows = db_query("SELECT * FROM link WHERE ref_id=$id;");
				if (count($_rows) == 0)
				{
					// $page�򻲾Ȥ��Ƥ���ڡ������ʤ��Τǡ����Υ쥳���ɤ���
					db_exec("DELETE FROM page WHERE id=$id;");
					return;
				}
			}
			// �ڡ����ι�������򥻥å�
			db_exec("UPDATE page SET lastmod=$time WHERE id=$id;");
		}
		
		// cache
		$pages = array();
		
		$links = plugin_links_get_objects($page);
		foreach ($links as $_obj)
		{
			if (!isset($_obj->type) or $_obj->type != 'pagename' or $_obj->name == $page)
			{
				continue;
			}			
			$_page = $_obj->name;
			if (!array_key_exists($_page,$pages))
			{
				$a_page = addslashes($_page);
				$rows = db_query("SELECT id,name FROM page WHERE name='$a_page';");
				if (count($rows) == 0)
				{
					db_exec("INSERT INTO page (name,lastmod) VALUES ('$a_page',0);");
					$rows = db_query("SELECT id,name FROM page WHERE name='$a_page';");
				}
				$pages[$rows[0]['name']] = TRUE;
				$ref_id =$rows[0]['id'];
				db_exec("INSERT INTO link (page_id,ref_id) VALUES ($id,$ref_id);");
			}
		}
		// ï����⻲�Ȥ���ʤ��ʤä���¸�ߤ��ʤ��ڡ����פ�õ�
		// MySQL3�ϡ����䤤��碌�פ�̤�б���?
//		db_exec("DELETE FROM page WHERE id in (SELECT id FROM page LEFT JOIN link ON id=ref_id WHERE lastmod=0 AND page_id IS NULL);");
		$rows = db_query("SELECT id FROM page LEFT JOIN link ON id=ref_id WHERE lastmod=0 AND page_id IS NULL;");
		$_arr = array();
		foreach ($rows as $row)
		{
			$_arr[] = $row['id'];
		}
		if (count($_arr))
		{
			db_exec("DELETE FROM page WHERE id in (".join(',',$_arr).");");
		}
	}
	else // if (!defined('LINK_DB'))
	{
		$time = is_page($page) ? get_filetime($page) : 0;
		
		$rel_old = array();
		$rel_file = CACHE_DIR.encode($page).'.rel';
		if (file_exists($rel_file))
		{
			$lines = file($rel_file);
			if (array_key_exists(0,$lines))
			{
				$rel_old = explode("\t",rtrim($lines[0]));
			}
			unlink($rel_file);
		}
		$rel_new = array(); // ������
		$links = plugin_links_get_objects($page);
		foreach ($links as $_obj)
		{
			if (!isset($_obj->type) or $_obj->type != 'pagename')
			{
				continue;
			}			
			$_page = $_obj->name;
			if ($_page != $page)
			{
				$rel_new[$_page] = 1;
			}
		}
		
		// .rel:$page�����Ȥ��Ƥ���ڡ����ΰ���
		if (count($rel_new))
		{
			$rel_new = array_keys($rel_new);
			$fp = fopen($rel_file,'w')
				or die_message('cannot write '.htmlspecialchars($rel_file));
			fputs($fp,join("\t",$rel_new));
			fclose($fp);
		}
		
		// .ref:$_page�򻲾Ȥ��Ƥ���ڡ����ΰ���
		$add = array_diff($rel_new,$rel_old);
		foreach ($add as $_page)
		{
			$ref_file = CACHE_DIR.encode($_page).'.ref';
			$ref = "$page\t$time\n";
			if (file_exists($ref_file))
			{
				foreach (file($ref_file) as $line)
				{
					list($ref_page,$time) = explode("\t",rtrim($line));
					if ($ref_page != $page)
					{
						$ref .= $line;
					}
				}
			}
			$fp = fopen($ref_file,'w')
				 or die_message('cannot write '.htmlspecialchars($ref_file));
			fputs($fp,$ref);
			fclose($fp);
		}
		
		$del = array_diff($rel_old,$rel_new);
		foreach ($del as $_page)
		{
			$ref_file = CACHE_DIR.encode($_page).'.ref';
			if (file_exists($ref_file))
			{
				$ref = '';
				foreach (file($ref_file) as $line)
				{
					list($ref_page,$time) = explode("\t",rtrim($line));
					if ($ref_page != $page)
					{
						$ref .= $line;
					}
				}
				unlink($ref_file);
				if ($ref != '')
				{
					$fp = fopen($ref_file,'w')
						or die_message('cannot write '.htmlspecialchars($ref_file));
					fputs($fp,$ref);
					fclose($fp);
				}
			}
		}
	}
}
?>
