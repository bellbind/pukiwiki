NAME

    PukiWiki - ���R�Ƀy�[�W��ǉ��E�폜�E�ҏW�ł���Web�y�[�W�\�zPHP�X�N���v�g

        PukiWiki 1.4
        Copyright (C) 2001,2002,2003 PukiWiki Developers Team.
        License is GNU/GPL.
        Based on "PukiWiki" 1.3 by sng
        http://pukiwiki.org/

SYNOPSIS

        http://pukiwiki.org/

DESCRIPTION

    PukiWiki�͎Q���҂����R�Ƀy�[�W��ǉ��E�폜�E�ҏW�ł���
    Web�y�[�W�Q�����PHP�X�N���v�g�ł��B
    Web�œ��삷��f���Ƃ�����Ǝ��Ă��܂����A
    Web�f�����P�Ƀ��b�Z�[�W��ǉ����邾���Ȃ̂ɑ΂��āA
    PukiWiki�́AWeb�y�[�W�S�̂����R�ɕύX���邱�Ƃ��ł��܂��B

    PukiWiki�́A����_�����YukiWiki�̎d�l���Q�l�ɂ��ēƎ��ɍ���܂����B
    1.3�܂ł�sng���񂪍쐬���A1.3.1b�ȍ~��PukiWiki Developers Team�ɂ����
    �J�����������Ă��܂��B

    PukiWiki��PHP�ŏ����ꂽPHP�X�N���v�g�Ƃ��Ď�������Ă��܂��̂ŁA
    PHP�����삷��Web�T�[�o�Ȃ�Δ�r�I�e�Ղɐݒu�ł��܂��B

    PukiWiki�̓t���[�\�t�g�ł��B �����R�ɂ��g�����������B

�ݒu���@

  ����

    PukiWiki�̍ŐV�ł́A http://pukiwiki.org/ �������ł��܂��B

  �C���X�g�[��

    1.  �A�[�J�C�u�������܂��B

    2.  �K�v�ɉ����Đݒ�t�@�C��(*.ini.php)�̓��e���m�F���܂��B
        1.11 ����ݒ�t�@�C�����ʃt�@�C����pukiwiki.ini.php�ɂȂ�܂����B
        1.4 ����ݒ�t�@�C������������܂����B

        *   �S�̐ݒ�            pukiwiki.ini.php

        *   �G�[�W�F���g�ʐݒ�
                I-MODE,AirH"    i_mode.ini.php
                J-PHONE         jphone.ini.php
                ���̑�          default.ini.php

    3.  �A�[�J�C�u�̓��e���T�[�o�ɓ]�����܂��B
        �t�@�C���̓]�����[�h�ɂ��Ă͎������Q�Ƃ��Ă��������B

    4.  pukiwiki.ini.php���Ŏw�肵���ȉ��̃f�B���N�g�����쐬���܂��B
          �f�[�^�̊i�[�f�B���N�g��               (�f�t�H���g��wiki)
          �����t�@�C���̊i�[�f�B���N�g��         (�f�t�H���g��diff)
          �o�b�N�A�b�v�t�@�C���i�[��f�B���N�g�� (�f�t�H���g��backup)
          �L���b�V���t�@�C���i�[�f�B���N�g��     (�f�t�H���g��cache)

        �f�B���N�g�����Ƀt�@�C��������ꍇ�ɂ́A���̃t�@�C���̑�����
        666�ɕύX���Ă��������B

    5.  attach.inc.php���Ŏw�肵���Y�t�t�@�C���f�B���N�g�����쐬���܂��B
        (�f�t�H���g�� attach)

    6.  counter.inc.php���Ŏw�肵���J�E���^�[�t�@�C���f�B���N�g�����쐬���܂��B
        (�f�t�H���g�� counter)

    7.  �T�[�o��̃t�@�C������уf�B���N�g���̃p�[�~�b�V�������m�F���܂��B
        �t�@�C���̃p�[�~�b�V�����ɂ��Ă͎������Q�Ƃ��Ă��������B

    8.  pukiwiki.php�Ƀu���E�U����A�N�Z�X���܂��B

  �p�[�~�b�V����

    �f�B���N�g��   �p�[�~�b�V����
    attach         777
    backup         777
    cache          777
    counter        777
    diff           777
    face           755
    image          755
    plugin         755
    skin           755
    wiki           777

    �t�@�C��       �p�[�~�b�V���� �]�����[�h
    *.php          644            ASCII
    *.lng          644            ASCII
    pukiwiki.png   644            BINARY

    cache/*        666            ASCII
    face/*         644            BINARY
    image/*        644            BINARY
    plugin/*       644            ASCII
    skin/*         644            ASCII
    wiki/*         666            ASCII

  �f�[�^�̃o�b�N�A�b�v���@

    �f�[�^�t�@�C���f�B���N�g���ȉ����o�b�N�A�b�v���܂��B
    (�f�t�H���g�f�B���N�g������ wiki)

    �K�v�ɉ����đ��̃f�B���N�g���̓��e���o�b�N�A�b�v���܂��B
    (�f�t�H���g�f�B���N�g������ attach,backup,counter,cache,diff)

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

    [[���`���[��]] �y�[�W���Q�Ƃ��Ă��������B

InterWiki

    1.11 ����InterWiki����������܂����B

    InterWiki �Ƃ́AWiki�T�[�o�[���Ȃ���@�\�ł��B
    �ŏ��͂������������ InterWiki �Ƃ������O�Ȃ̂������ł����A
    ���́AWiki�T�[�o�[�����ł͂Ȃ��āA�����ȃT�[�o�[�������܂��B
    �Ȃ��Ȃ��֗��ł��B�����Ȃ�� InterWiki �Ƃ������O�͂��܂�@�\��
    �\���Ă��Ȃ����ƂɂȂ�܂��B
    ���̋@�\�� Tiki ����قڊ��S�ɈڐA���Ă��܂��B

    �ڍׂ� [[InterWiki�e�N�j�J��]] �y�[�W���Q�Ƃ��Ă��������B

RDF/RSS

    1.2.1����ARecentChanges��RDF/RSS���o�͂ł���悤�ɂȂ�܂����B
    ���p�ł��邩�͂킩��Ȃ��ł����A���������Ɏg����΁A�Ǝv���Ă܂��B

    *   RSS 0.91 �̏o�͕��@�̗�

        *   http://pukiwiki/index.php?cmd=rss

    *   RSS 1.0 �̏o�͕��@�̗�

        *   http://pukiwiki.org/index.php?cmd=rss10


PukiWiki/1.3.x�Ƃ̔�݊��_

    1.  [[WikiName]]��WikiName�͓����y�[�W���w���܂��B
    2.  ��`���X�g�̏������Ⴂ�܂��B :�`: -> :�`|
    3.  ���X�g����p���́A���ʃ��x���̃��X�g����p�����܂��邱�Ƃ��ł��܂��B
        (1.3.x�ł́A���X�g�͓���̂݁A���p���ɂ͈��p������܂ł��܂���ł����B)

�X�V����

    *   2003-**-** 1.4 by PukiWiki Developers Team

        1.4�n�ŏ��̃����[�X

TODO

    http://pukiwiki.org/?BugTrack

���

    PukiWiki 1.4
    Copyright (C) 2001,2002,2003 PukiWiki Developers Team. License is GNU/GPL.
    Based on "PukiWiki" 1.3 by sng
    http://pukiwiki.org/

    ����A�ӌ��A�o�O�񍐂� http://pukiwiki.org/ �܂ł��肢���܂��B

�z�z����

    PukiWiki�́A GNU General Public License�ɂČ��J���܂��B

    PukiWiki�̓t���[�\�t�g�ł��B �����R�ɂ��g�����������B

�ӎ�

    PukiWiki Develpers Team�̊F����APukiWiki���[�U�̊F����Ɋ��ӂ��܂��B

    PukiWiki ���J�������Asng����Ɋ��ӂ��܂��B

    YukiWiki �̃N���[�����������Ă�������������_����Ɋ��ӂ��܂��B

    �{�Ƃ�WikiWiki�������Cunningham & Cunningham, Inc.�� ���ӂ��܂��B

�Q�ƃ����N

    *   PukiWiki�z�[���y�[�W http://pukiwiki.org/

    *   sng�̃z�[���y�[�W http://factage.com/sng/

    *   ����_����̃z�[���y�[�W http://www.hyuki.com/

    *   YukiWiki�z�[���y�[�W http://www.hyuki.com/yukiwiki/

    *   Tiki http://todo.org/cgi-bin/jp/tiki.cgi

    *   �{�Ƃ�WikiWiki http://c2.com/cgi/wiki?WikiWikiWeb

    *   �{�Ƃ�WikiWiki�̍��(Cunningham & Cunningham, Inc.) http://c2.com/

