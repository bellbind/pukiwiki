<?
// $Id: comment.inc.php,v 1.4 2002/07/01 07:07:54 masui Exp $

global $name_cols, $comment_cols, $msg_format, $name_format;
global $msg_format, $now_format, $comment_format;
global $comment_ins, $comment_mail, $comment_no;


/////////////////////////////////////////////////
// �����Ȥ�̾���ƥ����ȥ��ꥢ�Υ�����
$name_cols = 15;
/////////////////////////////////////////////////
// �����ȤΥƥ����ȥ��ꥢ�Υ�����
$comment_cols = 70;
/////////////////////////////////////////////////
// �����Ȥ������ե����ޥå�
$name_format = '[[$name]]';
$msg_format = '$msg';
$now_format = 'SIZE(10):$now';
/////////////////////////////////////////////////
// �����Ȥ������ե����ޥå�(����������)
$comment_format = '$msg -- $name $now';
/////////////////////////////////////////////////
// �����Ȥ������������ 1:����� 0:��θ�
$comment_ins = 1;
/////////////////////////////////////////////////
// �����Ȥ���Ƥ��줿��硢���Ƥ�᡼���������
$comment_mail = FALSE;

// initialize
$comment_no = 0;

function plugin_comment_action()
{
	global $post,$vars,$script,$cols,$rows,$del_backup,$do_backup,$update_exec,$now;
	global $name_cols,$comment_cols,$name_format,$msg_format,$now_format,$comment_format,$comment_ins;
	global $_title_collided,$_msg_collided,$_title_updated;
	global $_msg_comment_collided,$_title_comment_collided;

	$_comment_format = $comment_format;
	if($post["nodate"]=="1") {
		$_comment_format = str_replace('$now','',$_comment_format);
	}
	if($post["msg"]=="") {
		$retvars["msg"] = $name;
		$post["page"] = $post["refer"];
		$vars["page"] = $post["refer"];
		$retvars["body"] = convert_html(join("",file(get_filename(encode($post["refer"])))));
		return $retvars;
	}
	if($post["msg"])
	{
		$post["msg"] = preg_replace("/\n/","",$post["msg"]);

		$postdata = "";
		$postdata_old  = file(get_filename(encode($post["refer"])));
		$comment_no = 0;

		if($post["name"])
		{
			$name = str_replace('$name',$post["name"],$name_format);
		}
		if($post["msg"])
		{
			if(preg_match("/^(-{1,2})(.*)/",$post["msg"],$match))
			{
				$head = $match[1];
				$post["msg"] = $match[2];
			}
			
			$comment = str_replace('$msg',str_replace('$msg',$post["msg"],$msg_format),$_comment_format);
			$comment = str_replace('$name',$name,$comment);
			$comment = str_replace('$now',str_replace('$now',$now,$now_format),$comment);
			$comment = $head.$comment;
		}

		foreach($postdata_old as $line)
		{
			if(!$comment_ins) $postdata .= $line;
			if(preg_match("/^#comment/",$line))
			{
				if($comment_no == $post["comment_no"] && $post[msg]!="")
				{
					$postdata .= "-$comment\n";
				}
				$comment_no++;
			}
			if($comment_ins) $postdata .= $line;
		}

		$postdata_input = "-$comment\n";
	}

	$title = $_title_updated;
	if(md5(@join("",@file(get_filename(encode($post["refer"]))))) != $post["digest"])
	{
		$title = $_title_comment_collided;
		$body = $_msg_comment_collided . make_link($post["refer"]);
	}
	
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

	$retvars["msg"] = $title;
	$retvars["body"] = $body;
	
	$post["page"] = $post["refer"];
	$vars["page"] = $post["refer"];
	
	return $retvars;
}
function plugin_comment_convert()
{
	global $script,$comment_no,$vars,$name_cols,$comment_cols,$digest;
	global $_btn_comment,$_btn_name,$_msg_comment,$vars;

	$options = func_get_args();
	
	$nametags = "$_btn_name<input type=\"text\" name=\"name\" size=\"$name_cols\" />\n";
	if(is_array($options) && in_array("noname",$options)) {
		$nametags = $_msg_comment;
	}

	$nodate = '0';
	if(is_array($options) && in_array("nodate",$options)) {
		$nodate = '1';
	}

	if((arg_check("read")||$vars["cmd"] == ""||arg_check("unfreeze")||arg_check("freeze")||$vars["write"]||$vars["comment"]))
		$button = "<input type=\"submit\" name=\"comment\" value=\"$_btn_comment\" />\n";

	$string = "<br /><form action=\"$script\" method=\"post\">\n"
		 ."<div>\n"
		 ."<input type=\"hidden\" name=\"comment_no\" value=\"$comment_no\" />\n"
		 ."<input type=\"hidden\" name=\"refer\" value=\"$vars[page]\" />\n"
		 ."<input type=\"hidden\" name=\"plugin\" value=\"comment\" />\n"
		 ."<input type=\"hidden\" name=\"nodate\" value=\"$nodate\" />\n"
		 ."<input type=\"hidden\" name=\"digest\" value=\"$digest\" />\n"
		 ."$nametags"
		 ."<input type=\"text\" name=\"msg\" size=\"$comment_cols\" />\n"
		 .$button
		 ."</div>\n"
		 ."</form>";

	$comment_no++;

	return $string;
}
?>
