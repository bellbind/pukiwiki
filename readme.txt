NAME
    PukiWiki - ���R�Ƀy�[�W��ǉ��E�폜�E�ҏW�ł���Web�y�[�W�\�zPHP�X�N���v�g

        Copyright (C) 2001,2002 by sng.
        sng <sng@factage.com>
        http://factage.com/sng/

SYNOPSIS
        http://factage.com/sng/pukiwiki/pukiwiki.php

DESCRIPTION
    PukiWiki�͎Q���҂����R�Ƀy�[�W��ǉ��E�폜�E�ҏW�ł���
    Web�y�[�W�Q�����PHP�X�N���v�g�ł��B
    Web�œ��삷��f���Ƃ�����Ǝ��Ă��܂����A
    Web�f�����P�Ƀ��b�Z�[�W��ǉ����邾���Ȃ̂ɑ΂��āA
    PukiWiki�́AWeb�y�[�W�S�̂����R�ɕύX���邱�Ƃ��ł��܂��B

    PukiWiki�́A����_�����YukiWiki�̎d�l���Q�l�ɂ��ēƎ��ɍ���܂����B

    PukiWiki��PHP�ŏ����ꂽPHP�X�N���v�g�Ƃ��Ď�������Ă��܂��̂ŁA
    PHP�����삷��Web�T�[�o�Ȃ�Δ�r�I�e�Ղɐݒu�ł��܂��B

    PukiWiki�̓t���[�\�t�g�ł��B �����R�ɂ��g�����������B

�ݒu���@
  ����

    PukiWiki�̍ŐV�ł́A http://factage.com/sng/php/ �������ł��܂��B

  �t�@�C���ꗗ

        readme.txt        �h�L�������g
        pukiwiki.php      PukiWiki�{��
        pukiwiki.ini.php  PukiWiki�̐ݒ�t�@�C��
        pukiwiki.skin.php PukiWiki�̃f�B�t�H���g�X�L���t�@�C��
        pukiwiki.gif      ���S

  �C���X�g�[��

    1.  �A�[�J�C�u�������B

    2.  �K�v�ɉ�����pukiwiki.ini.php�̐ݒ���m�F���܂��B
        1.11 ����ݒ�t�@�C�����ʃt�@�C����pukiwiki.ini.php�ɂȂ�܂����B

    3.  pukiwiki.php��pukiwiki.gif�𓯂��Ƃ���ɐݒu���܂��B

    4.  �����pukiwiki.php�Ɠ����Ƃ����pukiwiki.ini.php��pukiwiki.skin.ja.php�A        ��������pukiwiki.skin.en.php�𓯂��Ƃ���ɐݒu���܂��B

    5.  pukiwiki.php���Ŏw�肵���f�[�^�t�@�C���f�B���N�g����
        ���� 777 �ō쐬����B(�f�B�t�H���g�� wiki )

    6.  pukiwiki.php���Ŏw�肵�������t�@�C���f�B���N�g����
        ���� 777 �ō쐬����B(�f�B�t�H���g�� diff )

    7.  �����o�b�N�A�b�v�@�\(�f�B�t�H���g�ł� off)���g���ꍇ�A
        pukiwiki.php���Ŏw�肵�������t�@�C���f�B���N�g����
        ���� 777 �ō쐬����B(�f�B�t�H���g�� diff )

    8.  pukiwiki.php�Ƀu���E�U����A�N�Z�X���܂��B

  �p�[�~�b�V����

            �t�@�C��             �p�[�~�b�V����      �]�����[�h
            pukiwiki.php         644                 ASCII
            pukiwiki.ini.php     644                 ASCII
            pukiwiki.skin.en.php 644                 ASCII
            pukiwiki.skin.ja.php 644                 ASCII
            en.lng               644                 ASCII
            ja.lng               644                 ASCII
            pukiwiki.gif         644                 BINARY

            �f�B���N�g��         �p�[�~�b�V����
            wiki                 777
            diff                 777
            backup               777
            plug-in              777

   �f�[�^�̃o�b�N�A�b�v���@

            �f�[�^�t�@�C���f�B���N�g���ȉ����o�b�N�A�b�v����΂悢�B
            (�f�B�t�H���g�f�B���N�g������ wiki )

�V�����y�[�W�̍���
    1.  �܂��A�K���ȃy�[�W�i�Ⴆ��FrontPage�j��I�сA
        �y�[�W�̉��ɂ���u�ҏW�v�����N�����ǂ�܂��B

    2.  ����ƃe�L�X�g���͂��ł����ԂɂȂ�̂ŁA ������NewPage�̂悤�ȒP��
        �i�啶�����������݂��Ă���p������j �������āu�ۑ��v���܂��B

    3.  �ۑ�����ƁAFrontPage�̃y�[�W�����������A
        ���Ȃ���������NewPage�Ƃ���������̌��� ?
        �Ƃ��������N���\������܂��B ���� ?
        �͂��̃y�[�W���܂����݂��Ȃ����Ƃ�������ł��B

    4.  ���� ? ���N���b�N����ƐV�����y�[�WNewPage���ł��܂��̂ŁA
        ���Ȃ��̍D���ȕ��͂����̐V�����y�[�W�ɏ����ĕۑ����܂��B

    5.  NewPage�y�[�W���ł����FrontPage�� ? �͏����āA�����N�ƂȂ�܂��B

�e�L�X�g���`�̃��[��
    *   �A�����������s�̓t�B������ĕ\������܂��B

    *   ��s�͒i��`<p>'�̋�؂�ƂȂ�܂��B

    *   HTML�̃^�O�͏����܂���B

    *   ''�{�[���h''�̂悤�ɃV���O���N�H�[�g��ł͂��ނƁA
        �{�[���h`<b>'�ɂȂ�܂��B

    *   '''�C�^���b�N'''�̂悤�ɃV���O���N�H�[�g�O�ł͂��ނƁA
        �C�^���b�N`<i>'�ɂȂ�܂��B

    *   ----�̂悤�Ƀ}�C�i�X4������ƁA ������`<hr>'�ɂȂ�܂��B

    *   �s��*�ł͂��߂�ƁA �匩�o��`<h2>'�ɂȂ�܂��B

    *   �s��**�ł͂��߂�ƁA �����o��`<h3>'�ɂȂ�܂��B

    *   �s��***�ł͂��߂�ƁA �����o��`<h3>'�ɂȂ�܂��B

    *   #contents ���s���ɏ����ƁA�匩�o���Ə����o���̖ڎ����쐬����܂��B 

    *   �s���}�C�i�X-�ł͂��߂�ƁA �ӏ�����`<ul>'�ɂȂ�܂��B
        �}�C�i�X�̐���������ƃ��x����������܂��i3���x���܂Łj

            -����1
            --����1-1
            --����1-2
            -����2
            -����3
            --����3-1
            ---����3-1-1
            ---����3-1-2
            --����3-2

    *   �R�������g���ƁA �p��Ɖ�����̃��X�g`<dl>'�������܂��B

            :�p��1:���낢�돑���������1
            :�p��2:���낢�돑���������2
            :�p��3:���낢�돑���������3

    *   �s������ | �ŕ��������؂�ƕ\�g�݂ɂȂ�܂��B

            |''Category:A''|''Category:B''|''Category:C''|
            |Objective|for AI|Other|
            |Java|LISP|Assembla|

    *   �����N

        *   LinkToSomePage��FrontPage�̂悤�ɁA
            �p�P��̍ŏ��̈ꕶ����啶���ɂ������̂�
            ��ȏ�A���������̂�PukiWiki�̃y�[�W���ƂȂ�A
            ���ꂪ���͒��Ɋ܂܂��ƃ����N�ɂȂ�܂��B

        *   ��d�̑傩����[[ ]]�ł���������������A
            PukiWiki�̃y�[�W���ɂȂ�܂��B
            �傩�����̒��ɂ̓X�y�[�X���܂߂Ă͂����܂���B
            ���{����g���܂��B

        *   �܂��A[[factage:http://factage.com/]] �̂悤�ɂ���� factage �̕�����
            http://factage.com/ �ւ̃����N���\��܂��B

        *   [[�T�[�o��:WikiName]] �̂悤�ɂ���� InterWikiName �ɂȂ�܂��B

        *   http://factage.com/sng/ �̂悤��URL�͎����I�Ƀ����N�ɂȂ�܂��B

        *   sng@factage.com �̂悤�ȃ��[���A�h���X�������I�Ƀ����N�ɂȂ�܂��B

    *   �s�����X�y�[�X��^�u�Ŏn�܂��Ă���ƁA
        ����͐��`�ς݂̒i��`<pre>'�Ƃ��Ĉ����܂��B
        �v���O�����̕\���ȂǂɎg���ƕ֗��ł��B

    *   �s�� > �ł͂��߂�ƁA ���p��`<blockquote>'�������܂��B
        >�̐��������ƃC���f���g���[���Ȃ�܂��i3���x���܂Łj�B

            >����
            >����
            >>����Ȃ���p
            >����

    *   �s�� // �Ŏn�߂�ƃR�����g�A�E�g`<!-- -->'�������܂��B

    *   #comment ���s���ɏ����ƃR�����g��}���ł���t�H�[�������ߍ��܂�܂��B

    *   #related �������ƁA���݂̃y�[�W�����܂ޕʂ̃y�[�W(�֘A�y�[�W)�ւ̃����N��\�����܂��B 

    * #norelated ���s���ɏ����ƁA���̃y�[�W�̈�ԉ��ɕ\�������֘A�y�[�W���\���ɂ��܂��B 

    * #calendar_read(200202) ���s���ɏ����ƁA���̓��t�̃y�[�W��\������J�����_�[���\������܂��B���ʓ��͔N����\���܂����A�ȗ�����ƌ��݂̔N�����g�p����܂��B(���L����) 

    * #calendar_edit(200202) ���s���ɏ����ƁA���̓��t�̃y�[�W��ҏW����J�����_�[���\������܂��B���ʓ��͔N����\���܂����A�ȗ�����ƌ��݂̔N�����g�p����܂��B(���L����) 

    *   ���̑��Apukiwiki.php ��ҏW���邱�Ƃɂ�葼�̃��[�����X�N���v�g�ݒu�҂���`�ł��܂��B

InterWiki
    1.11 ����InterWiki����������܂����B

    InterWiki �Ƃ́AWiki�T�[�o�[���Ȃ���@�\�ł��B�ŏ��͂������������ InterWiki �Ƃ���
    ���O�Ȃ̂������ł����A���́AWiki�T�[�o�[�����ł͂Ȃ��āA�����ȃT�[�o�[�������܂��B
    �Ȃ��Ȃ��֗��ł��B�����Ȃ�� InterWiki �Ƃ������O�͂��܂�@�\��\���Ă��Ȃ����Ƃ�
    �Ȃ�܂��B ���̋@�\�� Tiki ����قڊ��S�ɈڐA���Ă��܂��B

  �T�[�o�[���X�g�ւ̒ǉ�
    InterWikiName �̃y�[�W�Ɉȉ��̂悤�ɃT�[�o�̒�`������B 

    *   [URL �T�[�o��] �^�C�v
    *   [http://factage.com/sng/pukiwiki/pukiwiki.php?read&page= sng] pw


  InterWikiName�̒ǉ� 
    �T�[�o��:WikiName��BracketName�ō���InterWikiName�̊��� 

    *   [[�T�[�o��:WikiName]]
    *   [[sng:FrontPage]]

  WikiName�̑}���ʒu 
    �v�����悤�Ƃ���URL�ւ�WikiName�̑}���ʒu�� $1 �Ŏw�肷�邱�Ƃ��ł��܂��B
    �ȗ�����Ƃ��K�ɂ������܂��B 

    *   [http://factage.com/sng/pukiwiki/pukiwiki.php?backup&page=$1&age=1 sng] pw


  �����R�[�h�ϊ��^�C�v 
    PukiWiki�y�[�W�ȊO�ɂ���΂��܂��B���{���URL�Ɋ܂މ\��������̂ł��̏ꍇ��
    �G���R�[�f�B���O�̎w����^�C�v�Ƃ��Ďw��ł��܂��B 

    *   [http://factage.com/sng/pukiwiki/pukiwiki.php?read&page=$1 sng] pw


    *   std �ȗ���
        *   ���������G���R�[�f�B���O(�W����SJIS)�̂܂�URL�G���R�[�h���܂��B 

    *   raw asis
        *   URL�G���R�[�h���Ȃ��ł��̂܂܎g�p�B 

    *   sjis
        *   �������SJIS�ɕϊ����AURL�G���R�[�h���܂��B(mb_string��SJIS�ւ̃G�C���A�X�ł�) 

    *   euc
        *   ���������{��EUC�ɕϊ����AURL�G���R�[�h���܂��B(mb_string��EUC-JP�ւ̃G�C���A�X�ł�) 

    *   utf8
        *   �������UTF-8�ɕϊ����AURL�G���R�[�h���܂��B(mb_string��UTF-8�ւ̃G�C���A�X�ł�) 

    *   yw
        *   YukiWiki�n�ւ̃G���R�[�f�B���O�B 

    *   moin
        *   MoinMoin�p�ɕϊ����܂��B 

    *   ���̑��APHP4��mb_string�ŃT�|�[�g����Ă���ȉ��̃G���R�[�h�������g�p�ł��܂��B 

        *   UCS-4, UCS-4BE, UCS-4LE, UCS-2, UCS-2BE, UCS-2LE, UTF-32, UTF-32BE, UTF-32LE, UCS-2LE, UTF-16, UTF-16BE, UTF-16LE, UTF-8, UTF-7, ASCII, EUC-JP, SJIS, eucJP-win, SJIS-win, ISO-2022-JP, JIS, ISO-8859-1, ISO-8859-2, ISO-8859-3, ISO-8859-4, ISO-8859-5, ISO-8859-6, ISO-8859-7, ISO-8859-8, ISO-8859-9, ISO-8859-10, ISO-8859-13, ISO-8859-14, ISO-8859-15, byte2be, byte2le, byte4be, byte4le, BASE64, 7bit, 8bit, UTF7-IMAP 


  YukiWiki�n�ւ̃G���R�[�f�B���O 

    *   WikiName�̂��̂ւ͂��̂܂�URL�G���R�[�h�B 
    *   BracketName�̂��̂�[[ ]]��t������URL�G���R�[�h�B 

RDF/RSS
    1.2.1����ARecentChanges��RDF/RSS���o�͂ł���悤�ɂȂ�܂����B
    ���p�ł��邩�͂킩��Ȃ��ł����A���������Ɏg����΁A�Ǝv���Ă܂��B

  RSS 0.91 �̏o�͕��@�̗�

    *   http://factage.com/sng/pukiwiki/pukiwiki.php?rss

  RSS 1.0 �̏o�͕��@�̗�

    *   http://factage.com/sng/pukiwiki/pukiwiki.php?rss10

�X�V����
    *   2002-03-18 1.3

        ���镶�����WikiName/BracketName�ւ̃����N��\��B(�G�C���A�X�@�\)
        �^���f�B���N�g���\�z�B./ �� ../ �Ȃǂ�BracketName�Ƃ��Ďg�p���邱�ƂŎ����B 
        �J�����_�[�@�\�ŁAprefix���w��ł���悤�ɂ���B
        Tiki:TikiPluginSandBox�ɂ���悤�ȑΘb�^InterWiki(lookup)�B
        �����ꉻ�ɑΉ��ł���悤�ɁA�e�탁�b�Z�[�W�Ȃǂ�ҏW�\�ɂ���B 
        �y�[�W�ɓY�t�t�@�C����Y�t���邱�Ƃ��ł���B
        �ꕔ�̐��`���[�����v���O�C��������B
        Win32�ł�����ɓ��삷��悤�ɏC��

    *   2002-02-15 1.2.12

        �o�b�N�A�b�v�̋����̕ύX 
        ���ݕ\�����Ă���y�[�W�݂̂̃o�b�N�A�b�v�ꗗ��\������ 
        ���ݕ\�����Ă���y�[�W�Ƀo�b�N�A�b�v���Ȃ���΁A���ׂẴy�[�W�̂��̂�\�� 
        �o�b�N�A�b�v�������A�O��̃o�b�N�A�b�v�Ƃ̍����� 
        �t�@�C�����ꗗ�̒ǉ� 
        �^�C���X�^���v��ύX���Ȃ��`�F�b�N�{�b�N�X�̒ǉ� 
        �X�V�̏Փ˂̃`�F�b�N��MD5�Ń`�F�b�N�T�����g���悤�ɕύX 
        �R�����g�}�����A�s���ł͂Ȃ�#comment�����ɑ}�����Ă��܂��o�O���C�� 
        pat����̗v�]�ɂ��A�\�g�݃��[����ǉ� 
        pat����̗v�]�ɂ��HTML�R�����g�A�E�g���[����ǉ� 
        kawara?����̗v�]�ɂ�茩�o��������₵�� 
        #norelated ���s���ɏ����Ɗ֘A�y�[�W��\�����Ȃ����[����ǉ� 
        �֘A�y�[�W�̋�؂蕶���𐮌`���[���p�ƕ����� 

    *   2002-02-09 1.2.11 �֘A�����N�펞�\���@�\�A�o�ߎ��ԕ\���@�\�A�Z�L�����e�B�΍�A�R�}���h�� cmd= �ɏC���B���̑��o�O�C���B 

    *   2002-02-09 1.2.1 �o�O�C���A�������ARDF/RSS(1.0,0.91)�̎����B

    *   2002-02-07 1.2.0 �ݒ�t�@�C�����O���ցAInterWiki���ځA�֘A�y�[�W���[���A���߃��[���Ahttp�����N���[���A�o�O�C���B

    *   2002-02-05 1.10 �X�L���@�\�A�R�����g�}���A���o���ڎ��쐬�A���̑��o�O�C���B

    *   2002-02-01 1.07 �ǉ��@�\�A���[�U��`���[���A�P��AND/OR�����̎����B

    *   2001-01-22 1.06 �y�[�W�ҏW���G���[�̏C���B�y�[�W�^�C�g����[[]]����菜���悤�ɁB

    *   2001-12-12 1.05 �����A���S���Y���̏C���A�����o�b�N�A�b�v�@�\�ǉ��B

    *   2001-12-10 1.01 ���[���A�h���X�����N�̕s���̏C��(thanks to s.sawada)

    *   2001-12-05 1.00 �������J�B�������ʂ���̃n�C���C�g�\���@�\�̍폜�B

    *   2001-11-29 0.96 �܂��܂��������̃o�O�̏C���B�����̒ǉ��B�܂��܂������A�Ƃ肠�����B 

    *   2001-11-28 0.94 �������̃o�O�̏C���B���t�E�����}�����[���̒ǉ��B 

    *   2001-11-27 0.93 �R�[�h�̐����B�������ʂ���̃y�[�W�\�����n�C���C�g�\���B 

    *   2001-11-26 0.92 �f�[�^�t�@�C������ YukiWiki �Ƌ��ʂ̕ϊ����@�ɂ����B 

    *   2001-11-25 0.91 �����ɂ��ĒP�ꌟ���@�\���ǉ��B�����͌��\�����肻���B 

    *   2001-11-25 0.90 �ꉞ���J�BYukiWiki �̌����ƍ����͂܂��B

TODO
        - �\��Ȃ��A���ꂩ���������� YukiWiki �̋@�\���ڐA

���
        Copyright (C) 2001,2002 by sng.
        sng <sng@factage.com>
        http://factage.com/sng/
        http://factage.com/sng/pukiwiki/

    ����A�ӌ��A�o�O�񍐂� sng@factage.com �Ƀ��[�����Ă��������B

�z�z����
    PukiWiki�́A GNU General Public License�ɂČ��J���܂��B

    PukiWiki�̓t���[�\�t�g�ł��B �����R�ɂ��g�����������B

�ӎ�
    YukiWiki �̃N���[�����������Ă�������������_����Ɋ��ӂ��܂��B

    �{�Ƃ�WikiWiki�������Cunningham & Cunningham, Inc.�� ���ӂ��܂��B

�Q�ƃ����N
    *   sng�̃z�[���y�[�W http://factage.com/sng/

    *   PukiWiki�z�[���y�[�W http://factage.com/sng/pukiwiki/

    *   ����_����̃z�[���y�[�W http://www.hyuki.com/

    *   YukiWiki�z�[���y�[�W http://www.hyuki.com/yukiwiki/

    *   Tiki http://todo.org/cgi-bin/jp/tiki.cgi

    *   �{�Ƃ�WikiWiki http://c2.com/cgi/wiki?WikiWikiWeb

    *   �{�Ƃ�WikiWiki�̍��(Cunningham & Cunningham, Inc.) http://c2.com/
