<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: stripbracket.inc.php,v 1.1 2003/01/27 05:38:47 panda Exp $
//

/*
 stripbracket �ץ饰����
 �ǡ����ե������'[[ ]]'�������
 �ե�����Υ����ʡ���PHP�μ¹Լ�(apache,www-data�ʤ�)�ˤ���
 (�ե�����򥳥ԡ����Ƥ������ :) )

*/

function plugin_stripbracket_action() {
	$result = '';
	
	$dirs = array('attach','backup','counter','diff','wiki');
	
	umask(0133);
	
	foreach ($dirs as $dir) {
		if (!$dp = @opendir($dir)) {
			continue;
		}
		while ($file = readdir($dp)) {
			if (preg_match('/^5B5B([^_]+)5D5D(.+)$/',$file,$matches)) {
				$newfile = $matches[1].$matches[2];
				$page = decode($matches[1]);
				if (file_exists("$dir/$newfile")) {
					$result .= "-$page file $dir/$newfile already exists.\n";
					continue;
				}
			}
			else {
				$newfile = $file;
			}
			// get owner
			copy("$dir/$file","$dir/__TEMP__");
			touch("$dir/__TEMP__",filemtime("$dir/$file"));
			unlink("$dir/$file");
			rename("$dir/__TEMP__","$dir/$newfile");
		}
		closedir($dir);
	}
	return array('msg'=>'stripbracket result','body'=>convert_html($result));
}
?>
