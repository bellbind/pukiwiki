<?php
/*
 * PukiWiki BugTrack�ץ饰����
 *
 * CopyRight 2002 Y.MASUI GPL2
 * http://masui.net/pukiwiki/ masui@masui.net
 * 
 * �ѹ�����:
 *  2002.06.17: ���Ϥ�
 *
 * $Id: bugtrack.inc.php,v 1.3 2002/12/19 09:51:08 panda Exp $
 */

function plugin_bugtrack_init()
{
  global $script;
  $_plugin_bugtrack_messages = array(
    '_bugtrack_plugin_priority_list' => array("�۵�","����","����","��"),
    '_bugtrack_plugin_state_list' => array("���","���","CVS�Ԥ�","��λ","��α","�Ѳ�"),
    '_bugtrack_plugin_state_sort' => array("���","CVS�Ԥ�","��α","��λ","���","�Ѳ�"),
    '_bugtrack_plugin_state_bgcolor' => array("#ccccff","#ffcc99","#ccffcc","#ccffcc","#ffccff","#cccccc","#ff3333"),
    
    '_bugtrack_plugin_title' => "\$1 Bugtrack Plugin",
    '_bugtrack_plugin_base' => "�ڡ���",
    '_bugtrack_plugin_summary' => "���ޥ�",
    '_bugtrack_plugin_priority' => "ͥ����",
    '_bugtrack_plugin_state' => "����",
    '_bugtrack_plugin_name' => "��Ƽ�",
    '_bugtrack_plugin_date' => "�����",
    '_bugtrack_plugin_body' => "��å�����",
    '_bugtrack_plugin_category' => "���ƥ��꡼",
    '_bugtrack_plugin_pagename' => "�ڡ���̾",
    '_bugtrack_plugin_pagename_comment' => "<font size=\"1\">����Τޤޤ��ȼ�ưŪ�˥ڡ���̾�������ޤ���</font>",
    '_bugtrack_plugin_version_comment' => "<font size=\"1\">����Ǥ⹽���ޤ���</font>",
    '_bugtrack_plugin_version' => "�С������",
    '_bugtrack_plugin_submit' => "�ɲ�"
    );
  set_plugin_messages($_plugin_bugtrack_messages);
}


function plugin_bugtrack_action()
{
  global $command,$vars,$_bugtrack_plugin_default_category,$script,$post;
  
  if($post['mode']=='submit') {
    $ret['msg'] = $_bugtrack_plugin_title_submitted;
    $page = plugin_bugtrack_write($post['base'], $post['pagename'], $post['summary'], $post['name'], $post['priority'], $post['state'], $post['category'], $post['version'], $post['body']);
    header("Location: $script?".rawurlencode($page));
    die;
  }
  else {
    $ret['msg'] = $_bugtrack_plugin_title;
    $ret["body"] = plugin_bugtrack_print_form($vars['category']);
  }
  
  return $ret;
}

function plugin_bugtrack_print_form($base,$category)
{
  global $_bugtrack_plugin_priority_list,$_bugtrack_plugin_state_list;
  global $_bugtrack_plugin_priority, $_bugtrack_plugin_state, $_bugtrack_plugin_name;
  global $_bugtrack_plugin_date, $_bugtrack_plugin_category, $_bugtrack_plugin_body;
  global $_bugtrack_plugin_summary, $_bugtrack_plugin_submit, $_bugtrack_plugin_version;
  global $_bugtrack_plugin_pagename, $_bugtrack_plugin_pagename_comment;
  global $_bugtrack_plugin_version_comment;
  global $script;

  $select_priority = '';
  for($i=0; $i<count($_bugtrack_plugin_priority_list); ++$i) {
    if($i<count($_bugtrack_plugin_priority_list)-1) {
      $selected = '';
    }
    else {
      $selected = ' selected';
    }
    $select_priority .= '<option name="'.$_bugtrack_plugin_priority_list[$i].'"'.$selected.'>'.$_bugtrack_plugin_priority_list[$i]."</option>";
  }
  
  $select_state = '';
  for($i=0; $i<count($_bugtrack_plugin_state_list); ++$i) {
    $select_state .= '<option name="'.$_bugtrack_plugin_state_list[$i].'">'.$_bugtrack_plugin_state_list[$i]."</option>";
  }
  
  if(count($category)==0) {
    $encoded_category = "<input name=\"category\" type=\"text\">";
  }
  else {
    $encoded_category = "<select name=\"category\">";
    for($i=0; $i<count($category); ++$i) {
      $encoded_category .= '<option name="'.$category[$i].'">'.$category[$i]."</option>";
    }
    $encoded_category .= "</select>";
  }
  
  $body = "<table border=\"0\"><form action=\"$script\" method=\"post\">
<tr><th nowrap>$_bugtrack_plugin_name</th><td><input name=\"name\" size=\"20\" type=\"text\"></td></tr>
<tr><th nowrap>$_bugtrack_plugin_category</th><td>$encoded_category</td></tr>
<tr><th nowrap>$_bugtrack_plugin_priority</th><td><select name=\"priority\">$select_priority</select></td></tr>
<tr><th nowrap>$_bugtrack_plugin_state</th><td><select name=\"state\">$select_state</select></td></tr>
<tr><th nowrap>$_bugtrack_plugin_pagename</th><td><input name=\"pagename\" size=\"20\" type=\"text\">$_bugtrack_plugin_pagename_comment</td></tr>
<tr><th nowrap>$_bugtrack_plugin_version</th><td><input name=\"version\" size=\"10\" type=\"text\">$_bugtrack_plugin_version_comment</td></tr>
<tr><th nowrap>$_bugtrack_plugin_summary</th><td><input name=\"summary\" size=\"60\" type=\"text\"></td></tr>
<tr><th nowrap>$_bugtrack_plugin_body</th><td><textarea name=\"body\" cols=\"60\" rows=\"6\"></textarea></td></tr>
<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"$_bugtrack_plugin_submit\">
<input type=\"hidden\" name=\"plugin\" value=\"bugtrack\">
<input type=\"hidden\" name=\"mode\" value=\"submit\">
<input type=\"hidden\" name=\"base\" value=\"$base\">
</td></tr>
</form></table>";
  
  return $body;
}

function plugin_bugtrack_template($base, $summary, $name, $priority, $state, $category, $version, $body)
{
  global $_bugtrack_plugin_priority, $_bugtrack_plugin_state, $_bugtrack_plugin_name;
  global $_bugtrack_plugin_date, $_bugtrack_plugin_category, $_bugtrack_plugin_base;
  global $_bugtrack_plugin_body, $_bugtrack_plugin_version;
  global $script, $WikiName;

  if(!preg_match("/^$WikiName$$/",$name)) {
    $name = "[[$name]]";
  }
  
  if(!preg_match("/^$WikiName$$/",$base)) {
    $base = "[[$base]]";
  }

   return 
"*$summary

-$_bugtrack_plugin_base: $base
-$_bugtrack_plugin_name: $name
-$_bugtrack_plugin_priority: $priority
-$_bugtrack_plugin_state: $state
-$_bugtrack_plugin_category: $category
-$_bugtrack_plugin_date: now?
-$_bugtrack_plugin_version: $version

**$_bugtrack_plugin_body
$body
----

#comment";
}

function plugin_bugtrack_write($base, $pagename, $summary, $name, $priority, $state, $category, $version, $body)
{
  global $WikiName;
  
  $base = strip_bracket($base);
  $pagename = strip_bracket($pagename);
  
  $postdata = plugin_bugtrack_template($base, $summary, $name, $priority, $state, $category, $version, $body);
  $postdata = user_rules_str($postdata);

  $i = 0;
  do {
    $i++;
    $page = "[[$base/$i]]";
  } while(is_page($page));
  
  if($pagename == '') {
    file_write(DATA_DIR,$page,$postdata);
  }
  else {
    if(!preg_match("/^$WikiName$$/",$pagename)) {
      $pagename = "[[$pagename]]";
    }
    if (is_page($pagename))
      $pagename = $page;
    else
      file_write(DATA_DIR,$page,"move to $pagename");
    file_write(DATA_DIR,$pagename,$postdata);
  }

  // is_page�Υ���å���򥯥ꥢ���롣
  is_page($post["refer"],true);
  
  return $page;
}

function plugin_bugtrack_convert()
{
	global $script,$weeklabels,$vars,$command,$WikiName,$BracketName;
	global $_bugtrack_plugin_default_category;

	$args = func_get_args();
	$base = $vars['page'];
        $category = array();
	if(func_num_args() > 0)
	  {
	    $base = $args[0];
            $category = $args;
            array_shift($category);
	  }
	
	return plugin_bugtrack_print_form($base,$category);
}


function plugin_bugtrack_pageinfo($page) {
  global $WikiName, $InterWikiName, $BracketName;

  $source = get_source($page);
  if(preg_match("/move\s*to\s*($WikiName|$InterWikiName|$BracketName)/",$source[0],$match)) {
    return(plugin_bugtrack_pageinfo($match[1]));
  }

  $body = join("\n",$source);
  $summary = $name = $priority = $state = $category = 'test';
  $itemlist = array();
  foreach(array('summary','name','priority','state','category') as $item) {
    $itemname = '_bugtrack_plugin_'.$item;
    global $$itemname;
    $itemname = $$itemname;
    if(preg_match("/-\s*$itemname\s*:\s*(.*)\s*/",$body,$matches)) {
      if($item == "name") {
	$$item = htmlspecialchars(strip_bracket($matches[1]));
      }
      else {
	$$item = htmlspecialchars($matches[1]);
      }
    }
  }

  global $_bugtrack_plugin_summary;
  if(preg_match("/\*([^\n]+)/",$body,$matches)) {
    $summary = htmlspecialchars($matches[1]);
  }
  
  return(array($page, $summary, $name, $priority, $state, $category));
}

function plugin_bugtrack_list_convert()
{
  global $vars, $script;
  global $_bugtrack_plugin_priority, $_bugtrack_plugin_state, $_bugtrack_plugin_name;
  global $_bugtrack_plugin_date, $_bugtrack_plugin_category, $_bugtrack_plugin_summary;
  global $_bugtrack_plugin_state_sort,$_bugtrack_plugin_state_list,$_bugtrack_plugin_state_bgcolor;
  
  $page = $vars['page'];
  if(func_num_args()) {
    $aryargs = func_get_args();
    $page = $aryargs[0];
  }
  
  $data = array();
  $states = array();
  $filepattern = encode('[['.strip_bracket($page).'/');
  $filepattern_len = strlen($filepattern);
  if ($dir = @opendir(DATA_DIR))
    {
      while($file = readdir($dir))
	{
	  if($file == ".." || $file == ".") continue;
	  if(substr($file,0,$filepattern_len)!=$filepattern) continue; 
	  $page = decode(trim(preg_replace("/\.txt$/"," ",$file)));
	  $line = plugin_bugtrack_pageinfo($page);
	  array_push($data,$line);
	  list($page, $summary, $name, $priority, $state, $category) = $line;
	  array_push($states,$state);
	}
      closedir($dir);
    }
  array_unique($states);
  $table = array();

  for($i=0; $i<=count($_bugtrack_plugin_state_list)+1; ++$i) {
    $table[$i] = array();
  }
  foreach($data as $line) {
    list($page, $summary, $name, $priority, $state, $category) = $line;
    $page_link = make_link($page);
    $state_no = array_search($state,$_bugtrack_plugin_state_sort);
    if($state_no===NULL) {
      $state_no = count($_bugtrack_plugin_state_list);
    }
    $bgcolor = $_bugtrack_plugin_state_bgcolor[$state_no];
    array_push($table[$state_no],"<tr bgcolor=\"$bgcolor\"><td nowrap>$page_link</td><td nowrap>$state</td><td nowrap>$priority</td><td nowrap>$category</td><td nowrap>$name</td><td>$summary</td></tr>");
  }
  
  $table_html = "<tr><th></th><th>$_bugtrack_plugin_state</th><th>$_bugtrack_plugin_priority</th><th>$_bugtrack_plugin_category</th><th>$_bugtrack_plugin_name</th><th>$_bugtrack_plugin_summary</th></tr>\n";
  for($i=0; $i<=count($_bugtrack_plugin_state_list); ++$i) {
    $table_html .= join("\n",$table[$i]);
  }
  return "<table border=1>$table_html</table>";
}

?>
