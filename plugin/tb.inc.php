<?php
// $Id: tb.inc.php,v 1.1 2003/06/05 06:20:49 arino Exp $
/*
 * PukiWiki TrackBack �ץ����
 * (C) 2003, Katsumi Saito <katsumi@jo1upk.ymt.prug.or.jp>
 * License: GPL
 *
*/

function plugin_tb_action() {
  global $script,$vars,$post,$trackback;

  // ���Ĥ��Ƥ��ʤ��Τ˸ƤФ줿�����б�
  if (!$trackback) {
    // �ǥե���Ȥϡ�PukiWiki ��ɽ��
    header("Location: $script");
    die();
  }

  // POST: TrackBack Ping ����¸����
  if (!empty($post["url"])) {
    tb_save();
    die();
  }

  switch ($vars["__mode"]) {
    case "rss":
      tb_mode_rss(TRACKBACK_DIR.$vars["tb_id"].".txt");
      break;
    case "view":
      tb_mode_view($vars["tb_id"]);
      break;
  }

  // �ǥե���Ȥϡ�PukiWiki ��ɽ��
  header("Location: $script");
  die();
}
?>
