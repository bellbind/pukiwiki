<?
/////////////////////////////////////////////////
// �ƥ����ȥ��ꥢ�Υ�����
define("INSERT_COLS",70);
/////////////////////////////////////////////////
// �ƥ����ȥ��ꥢ�ιԿ�
define("INSERT_ROWS",5);
/////////////////////////////////////////////////
// ����������� 1:����� 0:��θ�
define("INSERT_INS",1);

function plugin_insert_action()
{
	global $post,$vars,$script,$cols,$rows,$del_backup,$do_backup;
	global $_title_collided,$_msg_collided,$_title_updated;

	if($post["msg"])
	{
		$postdata = "";
		$postdata_old  = file(get_filename(encode($post["refer"])));
		$insert_no = 0;

		if($post[msg])
		{
			$insert = $post[msg];
		}

		foreach($postdata_old as $line)
		{
			if(!INSERT_INS) $postdata .= $line;
			if(preg_match("/^#insert$/",$line))
			{
				if($insert_no == $post["insert_no"] && $post[msg]!="")
				{
					$postdata .= "$insert\n";
				}
				$insert_no++;
			}
			if(INSERT_INS) $postdata .= $line;
		}

		$postdata_input = "$insert\n";
	}
	else
		return;

	if(md5(@join("",@file(get_filename(encode($post["refer"]))))) != $post["digest"])
	{
		$title = $_title_collided;

		$body = "$_msg_collided\n";

		$body .= "<form action=\"$script?cmd=preview\" method=\"post\">\n"
			."<input type=\"hidden\" name=\"refer\" value=\"".$post["refer"]."\">\n"
			."<input type=\"hidden\" name=\"digest\" value=\"".$post["digest"]."\">\n"
			."<textarea name=\"msg\" rows=\"$rows\" cols=\"$cols\" wrap=\"virtual\" id=\"textarea\">$postdata_input</textarea><br>\n"
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
function plugin_insert_convert()
{
	global $script,$insert_no,$vars,$digest;
	global $_btn_insert,$vars;

	if((arg_check("read")||$vars["cmd"] == ""||arg_check("unfreeze")||arg_check("freeze")||$vars["write"]||$vars["insert"]))
		$button = "<input type=\"submit\" name=\"insert\" value=\"$_btn_insert\">\n";

	$string = "<form action=\"$script\" method=\"post\">\n"
		 ."<input type=\"hidden\" name=\"insert_no\" value=\"$insert_no\">\n"
		 ."<input type=\"hidden\" name=\"refer\" value=\"$vars[page]\">\n"
		 ."<input type=\"hidden\" name=\"plugin\" value=\"insert\">\n"
		 ."<input type=\"hidden\" name=\"digest\" value=\"$digest\">\n"
		 ."<textarea name=\"msg\" rows=\"".INSERT_ROWS."\" cols=\"".INSERT_COLS."\">\n</textarea><br>\n"
		 .$button
		 ."</form>";

	$insert_no++;

	return $string;
}
?>
