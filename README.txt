NAME

    PukiWiki - ��ͳ�˥ڡ������ɲá�������Խ��Ǥ���Web�ڡ������ۥ�����ץ�

        PukiWiki 1.4.6
        Copyright (C)
          2001-2005 PukiWiki Developers Team
          2001-2002 yu-ji (Based on PukiWiki 1.3 by yu-ji)
        License: GPL version 2 or (at your option) any later version

SYNOPSIS

        http://pukiwiki.org/
        http://pukiwiki.sourceforge.jp/dev/
        http://sourceforge.jp/projects/pukiwiki/

DESCRIPTION

        PukiWiki(�פ�������)�ϼ�ͳ�˥ڡ������ɲá�������Խ��Ǥ���
        �ڡ��������뤳�Ȥ��Ǥ���Web���ץꥱ�������(WikiWikiWeb)
        �Ǥ���Web�Ǽ��Ĥ�ñ�˥�å��������ɲä�������ʤΤ��Ф��ơ�
        ����ƥ�����Τ�ͳ���ѹ����뤳�Ȥ��Ǥ��ޤ���

        �ä�PukiWiki��PHP����ǽ񤫤줿PHP������ץȤǤ��Τǡ�PHP��
        ư���Web�����Фʤ���ưפ����֤Ǥ��ޤ���

        PukiWiki�ϡ��������󤬺��줿YukiWiki�λ��ͤ򻲹ͤ��ȼ���
        ��ȯ����ޤ�����PukiWiki �С������1.3�ޤǤ�yu-ji���󤬸Ŀ�
        �������1.3.1b �ʹߤ� PukiWiki Developers Team �ˤ�äƳ�ȯ
        ��³�����Ƥ��ޤ���

        PukiWiki�ϡ�yu-ji�����ޤ� PukiWiki Develpers Team �䤽�ι׸�
        �Ԥ����Ƽ�������ʪ��GPL�С������2(�ޤ��� _���ʤ��������_ 
        ����ʹߤ�GPL)��Ŭ�Ѥ��Ƥ���֥ե꡼���եȥ�����(��ͳ�ʥ��ե�
        ������)�פǤ����ǿ��Ǥ�PukiWiki���������Ȥ�������Ǥ��ޤ���

������ˡ

        PukiWiki��PHP������ץȤʤΤǡ�(�㤨��Perl�Τ褦��)������ץ�
        �˼¹Ը����դ���ɬ�פϤ���ޤ���CGI��ư�Ǥʤ��ΤǤ���С�
        ������ץȤΰ���ܤ�������ɬ�פ⤢��ޤ���

        Web�����С��ؤΥ����륢����������ǽ�Ǥ���С�PukiWiki�Υ���
        �����֤򤽤Τޤޥ����С���ž�����������С���ǲ���
        (tar pzxf pukiwiki*.tar.gz) ��������ǥѡ��ߥå����������
        �Ԥ��ޤ���

        �ʲ��ˡ������˥��饤�����PC�Ǻ�Ȥ�Ԥ�������򵭤��ޤ���

    1.  PukiWiki�Υ��������֤�Ÿ�����ޤ���

    2.  ɬ�פ˱���������ե�����(*.ini.php)�����Ƥ��ǧ���ޤ���
        ������ץȤ�������ܸ��(����С�����Ū��)EUC-JP�ǡ��ޤ�����
        �����ɤ�LF�ǵ��Ҥ���Ƥ��ޤ��Τǡ����ܸ�ʸ�������ɤȲ��ԥ�����
        �μ�ưȽ�̤��Ǥ�������򸵤Τޤ���¸�Ǥ���ƥ����ȥ��ǥ�����
        ���Ѥ��Ʋ�������

        * ��������
          ����               : pukiwiki.ini.php
          �桼�����         : rules.ini.php

        * �桼���������������������
          �������ä����PDA  : keitai.ini.php
                               (�� i_mode.ini.php/jphone.ini.php)
          �ǥ����ȥå�PC     : default.ini.php

    3.  �ե������FTP�ʤɤǥ����Ф�ž�����ޤ���
        �������ޤǤδ֤�ʸ�������ɤ���ԥ����ɤ�����Ƥ��ʤ��Τ�
          ����С�ž���⡼�ɤ����ơ֥Х��ʥ�פǹԤ����Ȥ��Ǥ���
          �Ϥ��Ǥ�

    4.  �����о�Υե����뤪��ӥǥ��쥯�ȥ�Υѡ��ߥå������ǧ���ޤ���

        �ǥ��쥯�ȥ�   �ѡ��ߥå����
        attach         777	ź�եե������Ǽ�ǥ��쥯�ȥ�
        backup         777	�Хå����åץե������Ǽ�ǥ��쥯�ȥ�
        cache          777	����å���ե������Ǽ�ǥ��쥯�ȥ�
        counter        777	�����󥿥ե������Ǽ�ǥ��쥯�ȥ�
        diff           777	��ʬ�ե������Ǽ�ǥ��쥯�ȥ�
        image          755	�����ե�����
        image/face     755 	(�����ե�����)�ե������ޡ���  
        lib            755	�饤�֥��
        plugin         755	�ץ饰����
        skin           755	������CSS��JavaScirpt�ե�����
        trackback      777	TrackBack�ե������Ǽ�ǥ��쥯�ȥ�
        wiki           777	�ǡ����γ�Ǽ�ǥ��쥯�ȥ�

        �ե�����       �ѡ��ߥå���� �ǡ����μ���(����)
        .htaccess      644            ASCII
        .htpasswd      644            ASCII
        */.htaccess    644            ASCII

        �ե�����       �ѡ��ߥå���� �ǡ����μ���(����)
        *.php          644            ASCII
        */*.php        644            ASCII
        attach/*       666            BINARY (���󥹥ȡ������¸�ߤ���)
        backup/*.gz    666            BINARY (���󥹥ȡ������¸�ߤ���)
        backup/*.txt   666            ASCII  (¿���δĶ��Ǥ�¸�ߤ���)
        cache/*        666            ASCII  (�����Υץ饰����ϥХ��ʥ�ե��������¸����)
        counter/*      666            ASCII  (���󥹥ȡ������¸�ߤ���)
        diff/*.txt     666            ASCII  (���󥹥ȡ������¸�ߤ���)
        wiki/*.txt     666            ASCII
        image/*        644            BINARY
        image/face/*   644            BINARY
        lib/*          644            ASCII
        plugin/*       644            ASCII
        skin/*         644            ASCII

    5.  �����С������֤��� index.php ���뤤�� pukiwiki.php �ˡ�Web
        �֥饦�����饢���������ޤ���

    6.  ɬ�פ˱����ơ�����������ǥ������Ĵ�����Ʋ�������


�ǡ����ΥХå����å���ˡ

        �ǡ����ե�����ǥ��쥯�ȥ�ʲ���Хå����åפ��ޤ���
        (�ǥե���ȥǥ��쥯�ȥ�̾�� wiki)

        ɬ�פ˱�����¾�Υǥ��쥯�ȥ�����Ƥ�Хå����åפ��ޤ���
        (�ǥե���ȥǥ��쥯�ȥ�̾�� attach, backup, counter, cache,
         diff, trackback)


�������ڡ����κ����

        �ֿ����ץ�󥯤��鿷�����ڡ������������ʳ��ˡ��ڡ��������
        �񤤤���礫�餽�Υڡ���̾�Υڡ�����������뤳�Ȥ��Ǥ��ޤ���

    1.  �ޤ���Ŭ���ʥڡ������㤨��FrontPage�ˤ����ӡ�
        �ڡ����ξ岼�ˤ�����Խ��ץ�󥯤򤿤ɤ�ޤ���

    2.  ����ȥƥ��������Ϥ��Ǥ�����֤ˤʤ�Τǡ� ������NewPage�Τ褦��ñ��
        ����ʸ����ʸ�����ߤ��Ƥ����ʸ����ˤ䡢 [[�������ڡ���̾]] ���ͤ�
        ��ŤΥ֥饱�åȤǰϤ������񤤤ơ���¸�פ��ޤ���

    3.  ��¸����ȡ�FrontPage�Υڡ������񤭴���ꡢ
        ���ʤ����񤤤�NewPage�Ȥ���ñ���ֿ������ڡ���̾�פȤ�������
        �θ��� '?' �Ȥ��������ʥ�󥯤�ɽ������ޤ��� ���Υ��
        �Ϥ��Υڡ������ޤ�¸�ߤ��ʤ����Ȥ򼨤����Ǥ���

    4.  ���� '?' �򥯥�å�����ȿ������ڡ������Ǥ��ޤ��Τǡ�
        ���ʤ��ι�����ʸ�Ϥ򤽤ο������ڡ����˽񤤤���¸���ޤ���

    5.  �������ڡ������Ǥ����FrontPage�Τ����θ�礫�� '?' �Ͼä��ơ�
        ���̤Υϥ��ѡ���󥯤Ȥʤ�ޤ���

�ƥ����������Υ롼��

        [[�إ��]][[�����롼��]] �Υڡ����򻲾Ȥ��Ƥ���������

InterWiki�ˤĤ���

        InterWiki �Ȥϡ�Wiki�����С���Ĥʤ��뵡ǽ�Ǥ���
        �ǽ�Ϥ������ä���� InterWiki �Ȥ���̾���ʤΤ������Ǥ�����
        ���ϡ�Wiki�����С������ǤϤʤ��ơ������ʥ����С�������ޤ���
        �ʤ��ʤ������Ǥ��������ʤ�� InterWiki �Ȥ���̾���Ϥ��ޤ굡ǽ��
        ɽ���Ƥ��ʤ����Ȥˤʤ�ޤ���
        ���ε�ǽ�� Tiki ����ܴۤ����˰ܿ����Ƥ��ޤ���

        �ܺ٤� [[InterWiki�ƥ��˥���]] �Υڡ����򻲾Ȥ��Ƥ���������

RDF/RSS�ν���

        1.2.1���顢RecentChanges��RDF/RSS����ϤǤ���褦�ˤʤ�ޤ�����
        1.4.5���顢RSS 2.0 ����ϤǤ���褦�ˤʤ�ޤ�����

        * ������ˡ����
          RSS 0.91 http://path/to/pukiwiki/index.php?plugin=rss
          RSS 1.0  http://path/to/pukiwiki/index.php?plugin=rss&ver=1.0
          RSS 2.0  http://path/to/pukiwiki/index.php?plugin=rss&ver=2.0

FAQ

        PukiWiki.org�Τ��줾��Υڡ���������å����Ʋ�������

        FAQ        http://pukiwiki.org/?FAQ
        ����Ȣ     http://pukiwiki.org/?%E8%B3%AA%E5%95%8F%E7%AE%B1
        ³������Ȣ http://pukiwiki.org/?%E7%B6%9A%E3%83%BB%E8%B3%AA%E5%95%8F%E7%AE%B1

BUG

        �Х����� dev�����ȤޤǤ��ꤤ���ޤ���
        (�桹��PukiWiki��PukiWiki�ΥХ��ȥ�å��󥰤�ԤäƤ��ޤ�)

        dev:BugTrack2
        http://pukiwiki.sourceforge.jp/dev/?BugTrack2

�ռ�

    PukiWiki Develpers Team�γ�����PukiWiki�桼���γ�����˴��դ��ޤ���
    PukiWiki ��ȯ������yu-ji(��sng)����˴��դ��ޤ���
    YukiWiki �Υ����󲽤���Ĥ��Ƥ�����������������˴��դ��ޤ���
    �ܲȤ�WikiWiki���ä�Cunningham & Cunningham, Inc.�� ���դ��ޤ���

���ȥ��

    * PukiWiki�ۡ���ڡ���      http://pukiwiki.org/
    * yu-ji����Υۡ���ڡ���   http://factage.com/yu-ji/
    * ��������Υۡ���ڡ���  http://www.hyuki.com/
    * YukiWiki�ۡ���ڡ���      http://www.hyuki.com/yukiwiki/
    * Tiki                      http://todo.org/cgi-bin/tiki/tiki.cgi
    * �ܲ�WikiWikiWeb           http://c2.com/cgi/wiki?WikiWikiWeb
    * WikiWikiWeb�κ��(Cunningham & Cunningham, Inc.) http://c2.com/
    
