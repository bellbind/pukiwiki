<?
/*
 * PukiWiki versionlist�ץ饰����
 *
 * CopyRight 2002 S.YOSHIMURA GPL2
 * http://masui.net/pukiwiki/ yosimura@excellence.ac.jp
 *
 * $Id: versionlist.inc.php,v 1.1 2002/12/05 05:02:27 panda Exp $
 */

function plugin_versionlist_convert()
{
  global $vars, $script;
  $SCRIPT_DIR = array("./","./plugin/");
  /* õ���ǥ��쥯�ȥ����ꡣ�����ϡ�pukiwiki.ini.php ���� */

  if(func_num_args())
    $aryargs = func_get_args();
  else
    $aryargs = array();

  $lst = $comment = '';

  foreach($SCRIPT_DIR as $sdir){
    if ($dir = @dir($sdir)){
      while($file = $dir->read()){
        if($file == ".." || $file == ".") continue;
        if(!preg_match('/\.php$/i',$file)) continue;
        
        $comment = '';
        $filenp = $sdir . $file;
        $fd = fopen($filenp,'r');
        while(!feof ($fd)){
          if(preg_match('/Id:(.+),v (\d+\.\d+)/',fgets($fd,1024),$match)){
            $comment = trim($match[1] . " -&gt; " .  $match[2]) ;
            break;
          }else {
            continue;
          }
        }
        fclose($fd);
        if($comment != '')
          $lst .= "<li>$filenp =&gt; $comment\n";
      }
    }
    $dir->close();
  }
  if($lst=='') {
    return '';
  }

  return "<ul>$lst</ul>";
}
?>
