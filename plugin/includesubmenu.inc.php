<?php
// $Id: includesubmenu.inc.php,v 1.1 2002/12/05 05:02:27 panda Exp $

function plugin_includesubmenu_convert()
{
  global $vars,$script;
  $ShowPageName = FALSE;
  if(func_num_args()) {
    $aryargs = func_get_args();
    if ($aryargs[0] == "showpagename") $ShowPageName = TRUE;
  }else{
    $ShowPageName = FALSE;
  }

  $SubMenuPageName = "";

  $tmppage = strip_bracket($vars["page"]);
  //�����ؤ�SubMenu�ڡ���̾
  $SubMenuPageName1 = "[[" . $tmppage . "/SubMenu]]";

  //Ʊ���ؤ�SubMenu�ڡ���̾
  $LastSlash= strrpos($tmppage,"/");
  if ($LastSlash === false){
    $SubMenuPageName2 = "SubMenu";
  }else{
    $SubMenuPageName2 = "[[".substr($tmppage,0,$LastSlash)."/SubMenu]]";
  }
  //echo "$SubMenuPageName1 <br>";
  //echo "$SubMenuPageName2 <br>";
  //�����ؤ�SubMenu�����뤫�����å�
  //����С���������
  if (page_exists($SubMenuPageName1)){
    //�����ؤ�SubMenuͭ��
    $SubMenuPageName=$SubMenuPageName1;
  }elseif(page_exists($SubMenuPageName2)){
    //Ʊ���ؤ�SubMenuͭ��
    $SubMenuPageName=$SubMenuPageName2;
  }else{
    //SubMenu̵��
    return "";
  }
  
  $link = "<a href=\"$script?cmd=edit&page=".rawurlencode($SubMenuPageName)."\">".strip_bracket($SubMenuPageName)."</a>";

  $body = @join("",@file(get_filename(encode($SubMenuPageName))));
  $body = convert_html($body);
  
  if ($ShowPageName == TRUE) {
    $head = "<h1>$link</h1>\n";
    $body = "$head\n$body\n";
  }
  return $body;
}
?>
