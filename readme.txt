NAME

    PukiWiki - ��ͳ�˥ڡ������ɲá�������Խ��Ǥ���Web�ڡ�������PHP������ץ�

        PukiWiki 1.4
        Copyright (C) 2001,2002,2003 PukiWiki Developers Team.
        License is GNU/GPL.
        Based on "PukiWiki" 1.3 by sng
        http://pukiwiki.org/

SYNOPSIS

        http://pukiwiki.org/

DESCRIPTION

        PukiWiki�ϻ��üԤ���ͳ�˥ڡ������ɲá�������Խ��Ǥ���
        Web�ڡ���������PHP������ץȤǤ���
        Web��ư���Ǽ��ĤȤ���äȻ��Ƥ��ޤ�����
        Web�Ǽ��Ĥ�ñ�˥�å��������ɲä�������ʤΤ��Ф��ơ�
        PukiWiki�ϡ�Web�ڡ������Τ�ͳ���ѹ����뤳�Ȥ��Ǥ��ޤ���

        PukiWiki�ϡ����������YukiWiki�λ��ͤ򻲹ͤˤ����ȼ��˺���ޤ�����
        1.3�ޤǤ�sng���󤬺�������1.3.1b�ʹߤ�PukiWiki Developers Team�ˤ�ä�
        ��ȯ��³�����Ƥ��ޤ���

        PukiWiki��PHP�ǽ񤫤줿PHP������ץȤȤ��Ƽ¸�����Ƥ��ޤ��Τǡ�
        PHP��ư���Web�����Фʤ�����Ū�ưפ����֤Ǥ��ޤ���

        PukiWiki�ϥե꡼���եȤǤ��� ����ͳ�ˤ��Ȥ�����������

������ˡ

    ����

        PukiWiki�κǿ��Ǥϡ� http://pukiwiki.org/ ��������Ǥ��ޤ���

    ���󥹥ȡ���

    1.  ���������֤�򤭤ޤ���

    2.  ɬ�פ˱���������ե�����(*.ini.php)�����Ƥ��ǧ���ޤ���
        1.11 ��������ե����뤬�̥ե������pukiwiki.ini.php�ˤʤ�ޤ�����
        1.4 ��������ե����뤬ʬ�䤵��ޤ�����

        *   ��������          : pukiwiki.ini.php

        *   �����������������
                I-MODE,AirH"  : i_mode.ini.php
                J-PHONE       : jphone.ini.php
                ����¾        : default.ini.php

        *   �桼������롼��  : rules.ini.php

    3.  ���������֤����Ƥ򥵡��Ф�ž�����ޤ���
        �ե������ž���⡼�ɤˤĤ��Ƥϼ���򻲾Ȥ��Ƥ���������

    4.  pukiwiki.ini.php��ǻ��ꤷ���ʲ��Υǥ��쥯�ȥ��������ޤ���

        �ǡ����γ�Ǽ�ǥ��쥯�ȥ�               (�ǥե���Ȥ�wiki)
        ��ʬ�ե������Ǽ�ǥ��쥯�ȥ�           (�ǥե���Ȥ�diff)
        �Хå����åץե������Ǽ�ǥ��쥯�ȥ�   (�ǥե���Ȥ�backup)
        ����å���ե������Ǽ�ǥ��쥯�ȥ�     (�ǥե���Ȥ�cache)
        ź�եե������Ǽ�ǥ��쥯�ȥ�           (�ǥե���Ȥ�attach)
        �����󥿥ե������Ǽ�ǥ��쥯�ȥ�       (�ǥե���Ȥ�counter)
        TrackBack�ե������Ǽ�ǥ��쥯�ȥ�      (�ǥե���Ȥ�trackback)

        �ǥ��쥯�ȥ���˥ե����뤬������ˤϡ����Υե������°����
        666���ѹ����Ƥ���������

    5.  �����о�Υե����뤪��ӥǥ��쥯�ȥ�Υѡ��ߥå������ǧ���ޤ���
        �ե�����Υѡ��ߥå����ˤĤ��Ƥϼ���򻲾Ȥ��Ƥ���������

    6.  pukiwiki.php�˥֥饦�����饢���������ޤ���

    �ѡ��ߥå����

        �ǥ��쥯�ȥ�   �ѡ��ߥå����
        attach         777
        backup         777
        cache          777
        counter        777
        diff           777
        face           755
        image          755
        plugin         755
        skin           755
        trackback      777
        wiki           777

        �ե�����       �ѡ��ߥå���� ž���⡼��
        *.php          644            ASCII
        *.lng          644            ASCII
        pukiwiki.png   644            BINARY

        cache/*        666            ASCII
        face/*         644            BINARY
        image/*        644            BINARY
        plugin/*       644            ASCII
        skin/*         644            ASCII
        wiki/*         666            ASCII

�ǡ����ΥХå����å���ˡ

        �ǡ����ե�����ǥ��쥯�ȥ�ʲ���Хå����åפ��ޤ���
        (�ǥե���ȥǥ��쥯�ȥ�̾�� wiki)

        ɬ�פ˱�����¾�Υǥ��쥯�ȥ�����Ƥ�Хå����åפ��ޤ���
        (�ǥե���ȥǥ��쥯�ȥ�̾�� attach,backup,counter,cache,diff,trackback)

�������ڡ����κ����

    1.  �ޤ���Ŭ���ʥڡ������㤨��FrontPage�ˤ����ӡ�
        �ڡ����β��ˤ�����Խ��ץ�󥯤򤿤ɤ�ޤ���

    2.  ����ȥƥ��������Ϥ��Ǥ�����֤ˤʤ�Τǡ� ������NewPage�Τ褦��ñ��
        ����ʸ����ʸ�����ߤ��Ƥ����ʸ����� ��񤤤ơ���¸�פ��ޤ���

    3.  ��¸����ȡ�FrontPage�Υڡ������񤭴���ꡢ
        ���ʤ����񤤤�NewPage�Ȥ���ʸ����θ��� ?
        �Ȥ�����󥯤�ɽ������ޤ��� ���� ?
        �Ϥ��Υڡ������ޤ�¸�ߤ��ʤ����Ȥ򼨤����Ǥ���

    4.  ���� ? �򥯥�å�����ȿ������ڡ���NewPage���Ǥ��ޤ��Τǡ�
        ���ʤ��ι�����ʸ�Ϥ򤽤ο������ڡ����˽񤤤���¸���ޤ���

    5.  NewPage�ڡ������Ǥ����FrontPage�� ? �Ͼä��ơ���󥯤Ȥʤ�ޤ���

�ƥ����������Υ롼��

        [[�����롼��]] �ڡ����򻲾Ȥ��Ƥ���������

InterWiki

        1.11 ����InterWiki����������ޤ�����

        InterWiki �Ȥϡ�Wiki�����С���Ĥʤ��뵡ǽ�Ǥ���
        �ǽ�Ϥ������ä���� InterWiki �Ȥ���̾���ʤΤ������Ǥ�����
        ���ϡ�Wiki�����С������ǤϤʤ��ơ������ʥ����С�������ޤ���
        �ʤ��ʤ������Ǥ��������ʤ�� InterWiki �Ȥ���̾���Ϥ��ޤ굡ǽ��
        ɽ���Ƥ��ʤ����Ȥˤʤ�ޤ���
        ���ε�ǽ�� Tiki ����ܴۤ����˰ܿ����Ƥ��ޤ���

        �ܺ٤� [[InterWiki�ƥ��˥���]] �ڡ����򻲾Ȥ��Ƥ���������

RDF/RSS

        1.2.1���顢RecentChanges��RDF/RSS����ϤǤ���褦�ˤʤ�ޤ�����
        ���ѤǤ��뤫�Ϥ狼��ʤ��Ǥ��������貿���˻Ȥ���С��ȻפäƤޤ���

    *   RSS 0.91 �ν�����ˡ����

        *   http://pukiwiki/index.php?cmd=rss

    *   RSS 1.0 �ν�����ˡ����

        *   http://pukiwiki.org/index.php?cmd=rss10


PukiWiki/1.3.x�Ȥ���ߴ���

    1.  [[WikiName]]��WikiName��Ʊ���ڡ�����ؤ��ޤ���
    2.  ����ꥹ�Ȥν񼰤��㤤�ޤ��� :���: -> :���|
    3.  �ꥹ�Ȥ����ʸ�ϡ����̥�٥�Υꥹ�Ȥ����ʸ����ޤ��뤳�Ȥ��Ǥ��ޤ���
        (1.3.x�Ǥϡ��ꥹ�Ȥ�Ʊ��Τߡ�������ˤϰ��Ѥ�����ޤǤ��ޤ���Ǥ�����)

��������

    *   2003-11-17 1.4.2 by PukiWiki Developers Team
        BugTrack/487 autolink��ʸ������
            [[cvs:func.php]](v1.4:r1.54)
        BugTrack/488 mbstring̵���ξ��֤�AutoLink�����ꤹ��ȥڡ�����������
            [[cvs:mbstring.php]](v1.4:r1.9)
        �ؿ�̾�����󥹥ȥ饯���Ⱦ���
            [[cvs:convert_html.php]](v1.4:r1.57)
        tracker_list()����2�����ǥڡ���̾�����л��꤬�Ȥ���褦��
        tracker()����1��������ά���줿�Ȥ���'default'��Ȥ�
            [[cvs:plugin/tracker.inc.php]](v1.4:r1.18)
        ���顼������Ĵ��
            [[cvs:plugin/template.inc.php]](v1.4:r1.16)
        �ѿ�̾�ְ㤤
            [[cvs:plugin/rename.inc.php]](v1.4:r1.9)

    *   2003-11-10 1.4.1 by PukiWiki Developers Team

        BugTrack/478    �ꥹ�Ȥλ����Ǥ�������������Ϥ���ʤ�
        BugTrack/479    CGI��PHP�ξ�硢HTTPS�����ѤǤ��ʤ�
        BugTrack/480    online.inc.php ��Υǥ��쥯�ȥ����������
        BugTrack/482    AutoLink��ư���Ĵ��
        BugTrack/483    ������HTML����ƥ��ƥ���񤯤���᤬����ʤ�
        BugTrack/485    lookup��InterWikiName�Ρָ���������¹Ԥ����
                        &�Ǥʤ�&amp;������
        BugTrack/486    header�ǥ���å���̵����
        tracker.inc.php radio/select/checkbox�ǡ�����褬�ҤȤĤ�����
                        ����ʤ��ä��Ȥ��ϡ��ͤ����Ȥ���
        backup.php      data�����ξ���warning�޻�

    *   2003-11-03 1.4 by PukiWiki Developers Team

        1.4�Ϻǽ�Υ�꡼��

TODO

    http://pukiwiki.org/?BugTrack

���

    PukiWiki 1.4
    Copyright (C) 2001,2002,2003 PukiWiki Developers Team. License is GNU/GPL.
    Based on "PukiWiki" 1.3 by sng
    http://pukiwiki.org/

    ���䡢�ո����Х����� http://pukiwiki.org/ �ޤǤ��ꤤ���ޤ���

���۾��

    PukiWiki�ϡ� GNU General Public License�ˤƸ������ޤ���

    PukiWiki�ϥե꡼���եȤǤ��� ����ͳ�ˤ��Ȥ�����������

�ռ�

    PukiWiki Develpers Team�γ�����PukiWiki�桼���γ�����˴��դ��ޤ���

    PukiWiki ��ȯ������sng����˴��դ��ޤ���

    YukiWiki �Υ����󲽤���Ĥ��Ƥ�����������������˴��դ��ޤ���

    �ܲȤ�WikiWiki���ä�Cunningham & Cunningham, Inc.�� ���դ��ޤ���

���ȥ��

    *   PukiWiki�ۡ���ڡ��� http://pukiwiki.org/

    *   sng�Υۡ���ڡ��� http://factage.com/sng/

    *   ��������Υۡ���ڡ��� http://www.hyuki.com/

    *   YukiWiki�ۡ���ڡ��� http://www.hyuki.com/yukiwiki/

    *   Tiki http://todo.org/cgi-bin/jp/tiki.cgi

    *   �ܲȤ�WikiWiki http://c2.com/cgi/wiki?WikiWikiWeb

    *   �ܲȤ�WikiWiki�κ��(Cunningham & Cunningham, Inc.) http://c2.com/
