<?php
// $Id: memo.inc.php,v 1.5 2002/12/19 11:33:49 panda Exp $

/////////////////////////////////////////////////
// �ƥ����ȥ��ꥢ�Υ�����
define("MEMO_COLS",80);
/////////////////////////////////////////////////
// �ƥ����ȥ��ꥢ�ιԿ�
define("MEMO_ROWS",5);

function plugin_memo_action()
{
	global $post,$vars,$script,$cols,$rows,$del_backup,$do_backup;
	global $_title_collided,$_msg_collided,$_title_updated;

	$post["msg"] = preg_replace("/(\x0D\x0A)/","\n",$post["msg"]);
	$post["msg"] = preg_replace("/(\x0D)/","\n",$post["msg"]);
	$post["msg"] = preg_replace("/(\x0A)/","\n",$post["msg"]);

	if($post["msg"])
	{
		$post["msg"] = str_replace("\n","\\n",$post["msg"]);

		$postdata = "";
		$postdata_old  = file(get_filename(encode($post["refer"])));
		$memo_no = 0;

		$memo_body = $post["msg"];

		foreach($postdata_old as $line)
		{
			if(preg_match("/^#memo\(?.*\)?$/",$line))
			{
				if($memo_no == $post["memo_no"] && $post["msg"]!="")
				{
					$postdata .= "#memo($memo_body)\n";
					$line = "";
				}
				$memo_no++;
			}
			$postdata .= $line;
		}

		$postdata_input = "$memo_body\n";
	}
	else
		return;
	
	if(md5(@join("",@file(get_filename(encode($post["refer"]))))) != $post["digest"])
	{
		$title = $_title_collided;
		
		$body = "$_msg_collided\n";

		$body .= "<form action=\"$script?cmd=preview\" method=\"post\">\n"
			."<div>\n"
			."<input type=\"hidden\" name=\"refer\" value=\"".$post["refer"]."\" />\n"
			."<input type=\"hidden\" name=\"digest\" value=\"".$post["digest"]."\" />\n"
			."<textarea name=\"msg\" rows=\"$rows\" cols=\"$cols\" wrap=\"virtual\" id=\"textarea\">$postdata_input</textarea><br />\n"
			."</div>\n"
			."</form>\n";
	}
	else
	{
		$postdata = user_rules_str($postdata);

		// ��ʬ�ե�����κ���
		if(is_page($post["refer"]))
			$oldpostdata = join("",file(get_filename(encode($post["refer"]))));
		else
			$oldpostdata = "\n";
		if($postdata)
			$diffdata = do_diff($oldpostdata,$postdata);
		file_write(DIFF_DIR,$post["refer"],$diffdata);

		// �Хå����åפκ���
		if(is_page($post["refer"]))
			$oldposttime = filemtime(get_filename(encode($post["refer"])));
		else
			$oldposttime = time();

		// �Խ����Ƥ�����񤫤�Ƥ��ʤ��ȥХå����åפ�������?���ʤ��Ǥ���͡�
		if(!$postdata && $del_backup)
			backup_delete(BACKUP_DIR.encode($post["refer"]).".txt");
		else if($do_backup && is_page($post["refer"]))
			make_backup(encode($post["refer"]).".txt",$oldpostdata,$oldposttime);

		// �ե�����ν񤭹���
		file_write(DATA_DIR,$post["refer"],$postdata);

		// is_page�Υ���å���򥯥ꥢ���롣
		is_page($post["refer"],true);

		$title = $_title_updated;
	}
	$retvars["msg"] = $title;
	$retvars["body"] = $body;
	
	$post["page"] = $post["refer"];
	$vars["page"] = $post["refer"];
	
	return $retvars;
}
function plugin_memo_convert()
{
	global $script,$vars,$digest;
	global $_btn_memo_update,$vars;
	static $memo_no = 0;

	if(func_num_args())
		$aryargs = func_get_args();

	$data = str_replace("\\n","\n",$aryargs[0]);

	if((arg_check("read")||$vars["cmd"] == ""||arg_check("unfreeze")||arg_check("freeze")||$vars["write"]||$vars["memo"]))
		$button = "<input type=\"submit\" name=\"memo\" value=\"$_btn_memo_update\" />\n";

	$s_page = htmlspecialchars($vars['page']);
	
	$string = "<form action=\"$script\" method=\"post\" class=\"memo\">\n"
		 ."<div>\n"
		 ."<input type=\"hidden\" name=\"memo_no\" value=\"$memo_no\" />\n"
		 ."<input type=\"hidden\" name=\"refer\" value=\"$s_page\" />\n"
		 ."<input type=\"hidden\" name=\"plugin\" value=\"memo\" />\n"
		 ."<input type=\"hidden\" name=\"digest\" value=\"$digest\" />\n"
		 ."<textarea name=\"msg\" rows=\"".MEMO_ROWS."\" cols=\"".MEMO_COLS."\">\n$data</textarea><br />\n"
		 .$button
		 ."</div>\n"
		 ."</form>";

	$memo_no++;

	return $string;
}
?>
