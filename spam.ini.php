<?php
// $Id: spam.ini.php,v 1.46 2007/04/01 10:52:59 henoheno Exp $
// Spam-related setting
//
// Reference:
//   Spamdexing http://en.wikipedia.org/wiki/Spamdexing

// NOTE for goodhost and badhost:
//  'example.org' prohibits both 'example.org' AND 'www.example.org'.
//  '*.example.org' prohibits all subdomains and hosts EXCEPT 'www.example.org'.
//  '.example.org' prohibits ALL FQDN related 'example.org'.

$blocklist['goodhost'] = array(
	'IANA-examples' => '#^(?:.*\.)?example\.(?:com|net|org)$#',

	// PukiWiki-official/dev specific
	//'.logue.tk',	// Well-known PukiWiki heavy user, Logue (Paid *.tk domain, Expire on 2008-12-01)
	//'.nyaa.tk',	// (Paid *.tk domain, Expire on 2008-05-19)
	//'.wanwan.tk',	// (Paid *.tk domain, Expire on 2008-04-21) by nyaa.tk

	// Yours
	//''
	//''
	//''

);

// NOTE for badhost (only):
//  'unique_name' = array('a.example.org', 'b.example.org') is treated as a group 'unique_name'.
$blocklist['badhost'] = array(

	// A: Sample setting of
	// Existing URI redirection or masking services

	// A-1: General redirection services -- by HTML meta, HTML frame, JavaScript,
	// web-based proxy, DNS subdomains, etc
	// http://en.wikipedia.org/wiki/URL_redirection
	//
	// as known as cheap URI obscuring services today,
	// for spammers and affiliate users dazed by money.
	//
	//   Messages from forerunners:
	//     o-rly.net
	//       "A URL REDIRECTION SERVICE GONE BAD"
	//       "SORRY, TRULY"
	//     smcurl.com
	//       "Idiots were using smcURL to shrink URLs and
	//        send them out via spam."
	//     tinyclick.com
	//       "...stop offering it's free services because
	//        too many people were taking advantage of it"
	//     xjs.org
	//       "We have been forced to close this facility
	//        due to a minority of knuckle draggers who
	//        abused this web site."
	//
	// Please notify us about this list with reason:
	// http://pukiwiki.sourceforge.jp/dev/?BugTrack2/207
	//
	'0nz.org',
	'0rz.tw',
	'0url.com',
	'0zed.info',
	'*.110mb.com',
	'123.que.jp',
	'12url.org',
	'*.15h.com',
	'*.1dr.biz',
	'1nk.us',
	'1url.org',
	'1url.in',
	'1webspace.org',
	'2ch2.net',
	'2hop4.com',
	'2s.ca',
	'2site.com',
	'2url.org',
	'301url.com',
	'32url.com',
	'.3dg.de',
	'*.4bb.ru',
	'5jp.net',
	'.6url.com',
	'*.6x.to',
	'7ref.com',
	'82m.org',
	'*.8rf.com',
	'98.to',
	'abbrv.co.uk',
	'*.abwb.org',
	'acnw.de',
	'active.ws' => array(
		'*.321.cn',
		'*.4x2.net',
		'active.ws',
		'*.better.ws',
		'*.here.ws',
		'*.mypiece.com',
		'*.official.ws',
		'*.ouch.ws',
		'*.premium.ws',
		'*.such.info',
		'*.true.ws',
		'*.visit.ws',
	),
	'store.adobe.com',	// Stop it
	'aifam.com',
	'amoo.org',
	// "(c) 2007 www.arzy.net", by urladmin at zvxr.com, DNS arzy.net
	'arzy.net' => array(
		'jmp2.net',
		'2me.tw',
	),
	'ataja.es',
	'atk.jp',
	'athomebiz.com',
	'aukcje1.pl',
	'beermapping.com',
	'besturl.in',
	'biglnk.com',
	'bingr.com',
	'bittyurl.com',
	'*.bizz.cc',
	'*.blo.pl',
	'fanznet.jp' => array('blue11.jp'),
	'briefurl.com',
	'brokenscript.com',
	'bucksogen.com' => array(
		'*.bucksogen.com',
		'*.bulochka.org',
		'*.korzhik.org',
		'*.kovrizhka.org',
		'*.pirozhok.org',
		'*.plushka.org',
		'*.pryanik.org',
		'*.sushka.org',
	),
	'budgethosts.org',
	'*.buzznet.com',
	'*.bydl.com',
	'c-o.in' => array(
		'*.c-o.cc',
		'*.c-o.in',
		'*.coz.in',
		'*.cq.bz',
	),
	'c64.ch',
	'c711.com',
	'checkasite.net',
	'*.chicappa.jp',
	'chopurl.com',
	'christopherleestreet.com',
	'*.cjb.net',
	'clipurl.com',
	'*.co.nr',
	'comteche.com' => array(
		'tinyurl.name',
		'tinyurl.us',
	),
	'cool168.com' => array(
		'*.cool158.com',
		'*.cool168.com',
		'*.ko168.com',
		'*.ko188.com',
	),
	'coolurl.de' => array(
		'coolurl.de',
		'dornenboy.de',
		'eyeqweb.com',
		'hardcore-porn.de',
		'maschinen-bluten-nicht.de',
	),
	'cutalink.com',
	'*.da.cx',
	'*.da.ru',
	'dae2.com',
	'dephine.org',
	'desiurl.com',
	'dhurl.com',
	'digbig.com',
	'digipills.com' => array(
		'*.digipills.com',
		'minilien.com',
		'tinylink.com',
	),
	'*.discutbb.com',
	'dl.am' => array(
		'*.cx.la',
		'*.dl.am',
	),
	'*.dmdns.com',
	'doiop.com',
	'Ideas para Nuevos Mercados SL' => array(
		// NOTE: 'i4nm.com' by 'Ideas para Nuevos Mercados SL' (i4nm at i4nm.com)
		// NOTE: 'dominiosfree.com' by 'Ideas para nuevos mercados,sl' (dominiosfree at i4nm.com)
		// NOTE: 'red-es.com' by oscar florez (info at i4nm.com)
		// by edgar bortolin (oscar at i4nm.com)
		// by Edgar Bortolin  (oscar at i4nm.com)
		// by oscar florez (oscar at i4nm.com)
		// by Oscar Florez (oscar at red-es.com)
		// by covadonga del valle (oscar at i4nm.com)
		'*.ar.gd',
		'*.ar.gs',	// ns *.nora.net
		'*.ar.kz',	// by oscar
		'*.ar.nu',	// by Edgar
		'*.ar.tc',	// by oscar
		'*.ar.vg',	// by oscar
		'*.bo.kz',	// by oscar
		'*.bo.nu',	// by covadonga
		'*.bo.tc',	// by oscar
		'*.bo.tf',	// by Oscar
		'*.bo.vg',	// by oscar
		'*.br.gd',
		'*.br.gs',	// ns *.nora.net
		'*.br.nu',	// by edgar
		'*.br.vg',	// by oscar
		'*.ca.gs',	// by oscar
		'*.ca.kz',	// by oscar
		'*.cl.gd',	// by oscar
		'*.cl.kz',	// by oscar
		'*.cl.nu',	// by edgar
		'*.cl.tc',	// by oscar
		'*.cl.tf',	// by Oscar
		'*.cl.vg',	// by oscar
		'*.col.nu',	// by Edgar
		'*.cr.gs',	// ns *.nora.net
		'*.cr.kz',	// by oscar
		'*.cr.nu',	// by edgar
		'*.cr.tc',	// by oscar
		'*.cu.tc',	// by oscar
		'*.do.kz',	// by oscar
		'*.do.nu',	// by edgar
		'*.ec.kz',	// by edgar
		'*.ec.nu',	// by Edgar
		'*.ec.tf',	// by Oscar
		'*.es.kz',	// by oscar
		'*.eu.kz',	// by oscar
		'*.gt.gs',	// ns *.nora.net
		'*.gt.tc',	// by oscar
		'*.gt.tf',	// by Oscar
		'*.gt.vg',	// by Oscar
		'*.hn.gs',	// ns *.nora.net
		'*.hn.tc',	// by oscar
		'*.hn.tf',	// by Oscar
		'*.hn.vg',	// by oscar
		'*.mx.gd',
		'*.mx.gs',	// ns *.nora.net
		'*.mx.kz',	// by oscar
		'*.mx.vg',	// by oscar
		'*.ni.kz',	// by oscar
		'*.pa.kz',	// by oscar
		'*.pe.kz',	// by oscar
		'*.pe.nu',	// by Edgar
		'*.pr.kz',	// by oscar
		'*.pr.nu',	// by edgar
		'*.pt.gs',	// ns *.nora.net
		'*.pt.kz',	// by edgar
		'*.pt.nu',	// by edgar
		'*.pt.tc',	// by oscar
		'*.pt.tf',	// by Oscar
		'*.py.gs',	// ns *.nora.net
		'*.py.nu',	// by edgar
		'*.py.tc',	// by oscar
		'*.py.tf',	// by Oscar
		'*.py.vg',	// by oscar
		'*.sv.tc',	// by oscar
		'*.usa.gs',	// ns *.nora.net
		'*.uy.gs',	// ns *.nora.net
		'*.uy.kz',	// by oscar
		'*.uy.nu',	// by edgar
		'*.uy.tc',	// by oscar
		'*.uy.tf',	// by Oscar
		'*.uy.vg',	// by oscar
		'*.ve.gs',	// by oscar
		'*.ve.tc',	// by oscar
		'*.ve.tf',	// by Oscar
		'*.ve.vg',	// by oscar
		'*.ven.nu',	// by edgar
	),
	'drlinky.com',
	'durl.us',
	'*.dvdonly.ru',
	'*.dynu.ca',
	'dwarf.name',
	'elfurl.com',
	'eny.pl',
	'eTechFocus LLC' => array(	// by eTechFocus LLC (thomask at etechfocus.com)
		'.mywiitime.com',
		'.surfindark.com',		// webmaster at etechfocus.com
		'.surfinshade.com',
		'.surfinshadow.com',
		'.surfinwind.com',
		'.topsecretlive.com',
	),
	'*.eu.org',
	'f2b.be' => array(
		'*.f2b.be',
		'*.freakz.eu',
		'*.n0.be',
		'*.n3t.nl',
		'*.short.be',
		'*.ssr.be',
		'*.tweaker.eu',
	),
	'*.fancyurl.com',
	'fanznet.com' => array(
		'fanznet.com',
		'katou.in',
		'mymap.in',
		'saitou.in',
		'satou.in',
		'susan.in',
	),
	'.fe.pl',			// Redirection and subdomain
	'ffwd.to',
	'url.fibiger.org',
	'fireme.to' => array(
		'fireme.to',
		'nextdoor.to',
		'ontheway.to',
	),
	'flingk.com',
	'fm7.biz',
	'fnbi.jp',
	'*.fnbi.jp',
	'forgeturl.com',
	'*.free.bg',
	'*.freecities.com',
	'freeservers.com' => array(
		'*.4mg.com',
		'*.4t.com',
		'*.8m.com',
		'*.8m.net',
		'*.8k.com',
		'*.faithweb.com',
		'*.freehosting.net',
		'*.freeservers.com',
		'*.gq.nu',
		'*.htmlplanet.com',
		'*.itgo.com',
		'*.iwarp.com',
		'*.s5.com',
		'*.scriptmania.com',
		'*.tvheaven.com',
	),
	'*.freewebpages.com',
	'freewebservices.net' => array(	// Host Department LLC
		'*.about.gs',	// Dead?
		'*.about.tc',
		'*.about.vg',
		'*.aboutus.gs',
		'*.aboutus.ms',
		'*.aboutus.tc',
		'*.aboutus.vg',
		'*.biografi.biz',
		'*.biografi.info',
		'*.biografi.org',
		'*.biografi.us',
		'*.datadiri.biz',
		'*.datadiri.cc',
		'*.datadiri.com',
		'*.datadiri.info',
		'*.datadiri.net',
		'*.datadiri.org',
		'*.datadiri.tv',
		'*.datadiri.us',
		'*.ecv.gs',
		'*.ecv.ms',
		'*.ecv.tc',
		'*.ecv.vg',
		'*.eprofile.us',
		'*.go2net.ws',
		'*.hits.io',
		'*.hostingweb.us',
		'*.hub.io',
		'*.indo.bz',
		'*.indo.cc',
		'*.indo.gs',
		'*.indo.ms',
		'*.indo.tc',
		'*.indo.vg',
		'*.infinitehosting.net',
		'*.infinites.net',
		'*.lan.io',
		'*.max.io',
		'*.mycv.bz',
		'*.mycv.nu',
		'*.mycv.tv',
		'*.myweb.io',
		'*.ourprofile.biz',
		'*.ourprofile.info',
		'*.ourprofile.net',	// Dead?
		'*.ourprofile.org',
		'*.ourprofile.us',
		'*.profil.bz',
		'*.profil.cc',
		'*.profil.cn',
		'*.profil.gs',
		'*.profil.in',
		'*.profil.ms',
		'*.profil.tc',
		'*.profil.tv',
		'*.profil.vg',	// ?
		'*.site.io',
		'*.wan.io',
		'*.web-cam.ws',
		'*.webs.io',
		'*.zip.io',
	),
	'fyad.org',
	'fype.com',
	'*.fx.to',
	'gentleurl.net',
	'get2.us' => array(
		'*.get2.us',
		'*.hasballs.com',
		'*.ismyidol.com',
		'*.spotted.us',
		'*.went2.us',
		'*.wentto.us',
	),
	'glinki.com',
	'*.globalredirect.com',
	'gnu.vu',
	'*.go.cc',
	'goonlink.com',
	'.gourl.org',
	'.greatitem.com',
	'gzurl.com',
	'url.grillsportverein.de',
	'harudake.net' => array('*.hyu.jp'),
	'Hattinger Linux User Group' => array('short.hatlug.de'),
	'hexten.net' => array('lyxus.net'),
	'here.is',
	'hispavista.com' => array(
		'*.hispavista.com',
		'.galeon.com',
	),
	// by Home.pl Sp. J. (info at home.pl), redirections and forums
	'home.pl' => array(
		'*.8l.pl',
		'*.blg.pl',
		'*.czytajto.pl',
		'*.ryj.pl',
		'*.xit.pl',
		'*.xlc.pl',
		'*.hk.pl',
		'*.home.pl',
		'*.of.pl',
	),
	'hort.net',
	'*.hotindex.ru',
	'hotredirect.com' => array(
		'*.coolhere.com',
		'*.homepagehere.com',
		'*.hothere.com',
		'*.mustbehere.com',
		'*.onlyhere.net',
		'*.pagehere.com',
		'*.surfhere.net',
		'*.zonehere.com',
	),
	'hotshorturl.com',
	'hurl.to',
	'*.hux.de',
	'*.i89.us',
	'*.iceglow.com',
	'ie.to',
	'igoto.co.uk',
	'ilook.tw',
	'inetwork.co.il' => array(
		'inetwork.co.il',	// by NiL HeMo (exe at bezeqint.net)
		'.up2.co.il',		// inetwork.co.il related, not classifiable, by roey blumshtein (roeyb76 at 017.net.il)
		'.dcn.co.il,',		// up2.co.il related, not classifiable, by daniel chechik (ns_daniel0 at bezeqint.net)
	),
	'*.infogami.com',
	'ipoo.org',
	'ir.pl' => array(
		'*.aj.pl',
		'*.aliasy.org',
		'*.gu.pl',
		'*.hu.pl',
		'*.ir.pl',
		'*.jo.pl',
		'*.su.pl',
		'*.td.pl',
		'*.uk.pl',
		'*.uy.pl',
		'*.xa.pl',
		'*.zj.pl',
	),
	'irotator.com',
	'iscool.net' => array(
		'*.arecool.net',
		'*.iscool.net',
		'*.isfun.net',
		'*.tux.nu',
	),
	'.iwebtool.com',
	'jeeee.net',
	'jeremyjohnstone.com' => array('url.vg'),
	'jemurl.com',
	'jggj.net',
	'jpan.jp',
	'kat.cc',
	'kickme.to' => array(
		'.1024bit.at',
		'.128bit.at',
		'.16bit.at',
		'.256bit.at',
		'.32bit.at',
		'.512bit.at',
		'.64bit.at',
		'.8bit.at',
		'.adores.it',
		'.again.at',
		'.allday.at',
		'.alone.at',
		'.altair.at',
		'.american.at',
		'.amiga500.at',
		'.ammo.at',
		'.amplifier.at',
		'.amstrad.at',
		'.anglican.at',
		'.angry.at',
		'.around.at',
		'.arrange.at',
		'.australian.at',
		'.baptist.at',
		'.basque.at',
		'.battle.at',
		'.bazooka.at',
		'.berber.at',
		'.blackhole.at',
		'.booze.at',
		'.bosnian.at',
		'.brainiac.at',
		'.brazilian.at',
		'.bummer.at',
		'.burn.at',
		'.c-64.at',
		'.catalonian.at',
		'.catholic.at',
		'.chapel.at',
		'.chills.it',
		'.christiandemocrats.at',
		'.cname.at',
		'.colors.at',
		'.commodore.at',
		'.commodore64.at',
		'.communists.at',
		'.conservatives.at',
		'.conspiracy.at',
		'.cooldude.at',
		'.craves.it',
		'.croatian.at',
		'.cuteboy.at',
		'.dancemix.at',
		'.danceparty.at',
		'.dances.it',
		'.danish.at',
		'.dealing.at',
		'.deep.at',
		'.democrats.at',
		'.digs.it',
		'.divxlinks.at',
		'.divxmovies.at',
		'.divxstuff.at',
		'.dizzy.at',
		'.does.it',
		'.dork.at',
		'.drives.it',
		'.dutch.at',
		'.dvdlinks.at',
		'.dvdmovies.at',
		'.dvdstuff.at',
		'.emulators.at',
		'.end.at',
		'.english.at',
		'.eniac.at',
		'.error403.at',
		'.error404.at',
		'.evangelism.at',
		'.exhibitionist.at',
		'.faith.at',
		'.fight.at',
		'.finish.at',
		'.finnish.at',
		'.forward.at',
		'.freebie.at',
		'.freemp3.at',
		'.french.at',
		'.graduatejobs.at',
		'.greenparty.at',
		'.grunge.at',
		'.hacked.at',
		'.hang.at',
		'.hangup.at',
		'.has.it',
		'.hide.at',
		'.hindu.at',
		'.htmlpage.at',
		'.hungarian.at',
		'.icelandic.at',
		'.independents.at',
		'.invisible.at',
		'.is-chillin.it',
		'.is-groovin.it',
		'.japanese.at',
		'.jive.at',
		'.kickass.at',
		'.kickme.to',
		'.kindergarden.at',
		'.knows.it',
		'.kurd.at',
		'.labour.at',
		'.leech.at',
		'.liberals.at',
		'.linuxserver.at',
		'.liqour.at',
		'.lovez.it',
		'.makes.it',
		'.maxed.at',
		'.means.it',
		'.meltdown.at',
		'.methodist.at',
		'.microcomputers.at',
		'.mingle.at',
		'.mirror.at',
		'.moan.at',
		'.mormons.at',
		'.musicmix.at',
		'.nationalists.at',
		'.needz.it',
		'.nerds.at',
		'.neuromancer.at',
		'.newbie.at',
		'.nicepage.at',
		'.ninja.at',
		'.norwegian.at',
		'.ntserver.at',
		'.owns.it',
		'.paint.at',
		'.palestinian.at',
		'.phoneme.at',
		'.phreaking.at',
		'.playz.it',
		'.polish.at',
		'.popmusic.at',
		'.portuguese.at',
		'.powermac.at',
		'.processor.at',
		'.prospects.at',
		'.protestant.at',
		'.rapmusic.at',
		'.raveparty.at',
		'.reachme.at',
		'.reads.it',
		'.reboot.at',
		'.relaxed.at',
		'.republicans.at',
		'.researcher.at',
		'.reset.at',
		'.resolve.at',
		'.retrocomputers.at',
		'.rockparty.at',
		'.rocks.it',
		'.rollover.at',
		'.rough.at',
		'.rules.it',
		'.rumble.at',
		'.russian.at',
		'.says.it',
		'.scared.at',
		'.seikh.at',
		'.serbian.at',
		'.short.as',
		'.shows.it',
		'.silence.at',
		'.simpler.at',
		'.sinclair.at',
		'.singz.it',
		'.slowdown.at',
		'.socialists.at',
		'.spanish.at',
		'.split.at',
		'.stand.at',
		'.stoned.at',
		'.stumble.at',
		'.supercomputer.at',
		'.surfs.it',
		'.swedish.at',
		'.swims.it',
		'.synagogue.at',
		'.syntax.at',
		'.syntaxerror.at',
		'.techie.at',
		'.temple.at',
		'.thinkbig.at',
		'.thirsty.at',
		'.throw.at',
		'.toplist.at',
		'.trekkie.at',
		'.trouble.at',
		'.turkish.at',
		'.unexplained.at',
		'.unixserver.at',
		'.vegetarian.at',
		'.venture.at',
		'.verycool.at',
		'.vic-20.at',
		'.viewing.at',
		'.vintagecomputers.at',
		'.virii.at',
		'.vodka.at',
		'.wannabe.at',
		'.webpagedesign.at',
		'.wheels.at',
		'.whisper.at',
		'.whiz.at',
		'.wonderful.at',
		'.zor.org',
		'.zx80.at',
		'.zx81.at',
		'.zxspectrum.at',
	),
	'kisaweb.com',
	'krotki.pl',
	'kuerzer.de',
	'*.kupisz.pl',
	'kuso.cc',
	'*.l8t.com',
	'lame.name',
	'lediga.st',
	'liencourt.com',
	'liteurl.com',
	'linkachi.com',
	'linkezy.com',
	'linkfrog.net',
	'linkook.com',
	'linkzip.net',
	'lispurl.com',
	'lnk.in',
	'makeashorterlink.com',
	'mcturl.com',
	'memurl.com',
	'metamark.net' => array('xrl.us'),
	'midgeturl.com',
	'minilink.org' => array('lnk.nu'),
	'miniurl.org',
	'miniurl.pl',
	'mixi.bz',
	'mo-v.jp',
	'monster-submit.com',
	'mooo.jp',
	'murl.net',
	'myactivesurf.net',
	'mytinylink.com',
	'myurl.in',
	'myurl.com.tw',
	'nanoref.com',
	'ne1.net' => array(
		'*.ne1.net',
		'*.r8.org',
	),
	'Nashville Linux Users Group' => array('nlug.org'),
	'not2long.net',
	'*.notlong.com',
	'*.nuv.pl',
	'ofzo.be',
	'*.ontheinter.net',
	'ourl.org',
	'ov2.net',				// frame
	'*.ozonez.com',
	'palurl.com',
	'*.paulding.net',
	'phpfaber.org',
	'pnope.com',
	'prettylink.com',
	'proxid.net' => array(	// also xrelay.net
		'*.asso.ws',
		'*.corp.st',
		'*.euro.tm',
		'*.perso.tc',
		'*.site.tc',
		'*.societe.st',
	),
		'qrl.jp',
	'qurl.net',
	'qwer.org',
	'redirectfree.com' => array(
		'*.red.tc',
		'*.redirectfree.com',
		'*.sky.tc',
		'*.the.vg',
	),
	'redirme.com',
	'redirectme.to',
	'relic.net',
	'rezma.info',
	'rio.st',
	'*.rmcinfo.fr',
	'rubyurl.com',
	'*.runboard.com',
	'runurl.com',
	's-url.net',
	'sg5.co.uk' => array(
		'*.sg5.co.uk',
		'*.sg5.info',
	),
	'shim.net' => array(
		'*.0kn.com',
		'*.2cd.net',
		'*.freebiefinders.net',
		'*.freegaming.org',
		'*.op7.net',
		'*.shim.net',
		'*.v9z.com',
	),
	'shorl.com',
	'shortenurl.com',
	'shorterlink.com',
	'shortlinks.co.uk',
	'shorttext.com',
	'shorturl-accessanalyzer.com',
	'shortify.com' => array(
		'74678439.com',
		'shortify.com',
	),
	'shortlink.co.uk',
	'shorturl.com' => array(
		'*.1sta.com',
		'*.24ex.com',
		'*.2fear.com',
		'*.2fortune.com',
		'*.2freedom.com',
		'*.2hell.com',
		'*.2savvy.com',
		'*.2truth.com',
		'*.2tunes.com',
		'*.2ya.com',
		'*.alturl.com',
		'*.antiblog.com',
		'*.bigbig.com',
		'*.dealtap.com',
		'*.ebored.com',
		'*.echoz.com',
		'*.filetap.com',
		'*.funurl.com',
		'*.headplug.com',
		'*.hereweb.com',
		'*.hitart.com',
		'*.mirrorz.com',
		'*.shorturl.com',
		'*.spyw.com',
		'*.vze.com',
	),
	'shrinkalink.com',
	'shrinkthatlink.com',
	'shrinkurl.us',
	'shrt.org',
	'shrunkurl.com',
	'shurl.org',
	'shurl.net',
	'sid.to',
	'simurl.com',
	'sitelutions.com' => array(
		'*.assexy.as',
		'*.athersite.com',
		'*.athissite.com',
		'*.bestdeals.at',
		'*.byinter.net',
		'*.findhere.org',
		'*.fw.nu',
		'*.isgre.at',
		'*.isthebe.st',
		'*.kwik.to',
		'*.lookin.at',
		'*.lowestprices.at',
		'*.onthenet.as',
		'*.ontheweb.nu',
		'*.pass.as',
		'*.passingg.as',
		'*.redirect.hm',
		'*.rr.nu',
		'*.ugly.as',
	),
	'*.skracaj.pl',
	'skiltechurl.com',
	'skocz.pl',
	'slimurl.jp',
	'slink.in',
	'smallurl.eu',
	'smurl.name',
	'snipurl.com',
	'sp-nov.net',
	'splashblog.com',
	'spod.cx',
	'*.spydar.com',
	'subdomain.gr' => array(
		'*.p2p.gr',
		'*.subdomain.gr',
	),
	's-url.dk' => array('surl.dk'),
	'surl.ws',
	'symy.jp',
	'tdurl.com',
	'tighturl.com',
	'tiniuri.com',
	'*.tiny.cc',
	'tiny.pl',
	'tiny2go.com',
	'tinylink.eu',
	'tinylinkworld.com',
	'tinypic.com',
	'tinyr.us',
	'tinyurl.com' => array(
		'tinyurl.com',
		'preview.tinyurl.com',
		'tinyurl.co.uk',
	),
	'titlien.com',
	'tlurl.com',
	'link.toolbot.com',
	'tnij.org',
	'Tokelau ccTLD' => array('.tk'),
	'toila.net',
	'*.toolbot.com',
	'*.torontonian.com',
	'trimurl.com',
	'ttu.cc',
	'turl.jp',
	'*.tz4.com',
	'u.to' => array(	// ns *.1004web.com, 1004web.com is owned by Moon Jae Bark (utomaster@gmail.com) = u.to master
		'*.1.to',
		'*.4.to',
		'*.5.to',
		'*.82.to',
		'*.s.to',
		'*.u.to',
		'*.ce.to',
		'*.cz.to',
		'*.if.to',
		'*.it.to',
		'*.kp.to',
		'*.ne.to',
		'*.ok.to',
		'*.pc.to',
		'*.tv.to',
		'*.dd.to',
		'*.ee.to',
		'*.hh.to',
		'*.kk.to',
		'*.mm.to',
		'*.qq.to',
		'*.xx.to',
		'*.zz.to',
		'*.ivy.to',
		'*.joa.to',
		'*.ever.to',
		'*.mini.to',
	),
	'uchinoko.in',
	'ulimit.com' => array(
		'*.be.tf',
		'*.best.cd',
		'*.bsd-fan.com',
		'*.c0m.st',
		'*.ca.tc',
		'*.clan.st',
		'*.com02.com',
		'*.en.st',
		'*.euro.st',
		'*.fr.fm',
		'*.fr.st',
		'*.fr.vu',
		'*.gr.st',
		'*.ht.st',
		'*.int.ms',
		'*.it.st',
		'*.java-fan.com',
		'*.linux-fan.com',
		'*.mac-fan.com',
		'*.mp3.ms',
		'*.qc.tc',
		'*.sp.st',
		'*.suisse.st',
		'*.t2u.com',
		'*.unixlover.com',
		'*.zik.mu',
	),
	'*.uni.cc',
	'*.uploadr.com',
	'url.ie',
	'unonic.com' => array(
		'*.at.tf',	// AlpenNIC
		'*.bg.tf',
		'*.ca.tf',
		'*.ch.tf',	// AlpenNIC
		'*.cz.tf',
		'*.de.tf',	// AlpenNIC
		'*.edu.tf',
		'*.eu.tf',
		'*.int.tf',
		'*.net.tf',
		'*.pl.tf',
		'*.ru.tf',
		'*.sg.tf',
		'*.us.tf',
	),
	'url4.net',
	'url-c.com',
	'urlbee.com',
	'urlbounce.com',
	'urlcut.com',
	'urlcutter.com',
	'urlic.com',
	'urlin.it',
	'*.urlproxy.com',
	'urlser.com',
	'urlsnip.com',
	'urlzip.de',
	'urlx.org',
	'utun.jp',
	'*.v27.net',
	'v3.com by fortunecity.com' => array(
		// http://www.v3.com/sub-domain-list.shtml
		'*.all.at',
		'*.back.to',
		'*.beam.at',
		'*.been.at',
		'*.bite.to',
		'*.board.to',
		'*.bounce.to',
		'*.bowl.to',
		'*.break.at',
		'*.browse.to',
		'*.change.to',
		'*.chip.ms',
		'*.connect.to',
		'*.crash.to',
		'*.cut.by',
		'*.direct.at',
		'*.dive.to',
		'*.drink.to',
		'*.drive.to',
		'*.drop.to',
		'*.easy.to',
		'*.everything.at',
		'*.fade.to',
		'*.fanclub.ms',
		'*.firstpage.de',
		'*.fly.to',
		'*.flying.to',
		'*.fortunecity.co.uk',
		'*.fortunecity.com',
		'*.forward.to',
		'*.fullspeed.to',
		'*.fun.ms',
		'*.gameday.de',
		'*.germany.ms',
		'*.get.to',
		'*.getit.at',
		'*.hard-ware.de',
		'*.hello.to',
		'*.hey.to',
		'*.hop.to',
		'*.how.to',
		'*.hp.ms',
		'*.jump.to',
		'*.kiss.to',
		'*.listen.to',
		'*.mediasite.de',
		'*.megapage.de',
		'*.messages.to',
		'*.mine.at',
		'*.more.at',
		'*.more.by',
		'*.move.to',
		'*.musicpage.de',
		'*.mypage.org',
		'*.mysite.de',
		'*.nav.to',
		'*.notrix.at',
		'*.notrix.ch',
		'*.notrix.de',
		'*.notrix.net',
		'*.on.to',
		'*.page.to',
		'*.pagina.de',
		'*.played.by',
		'*.playsite.de',
		'*.privat.ms',
		'*.quickly.to',
		'*.redirect.to',
		'*.rulestheweb.com',
		'*.run.to',
		'*.scroll.to',
		'*.seite.ms',
		'*.shortcut.to',
		'*.skip.to snap.to',
		'*.soft-ware.de',
		'*.start.at',
		'*.stick.by',
		'*.surf.to',
		'*.switch.to',
		'*.talk.to',
		'*.tip.nu',
		'*.top.ms',
		'*.transfer.to',
		'*.travel.to',
		'*.turn.to',
		'*.vacations.to',
		'*.videopage.de',
		'*.virtualpage.de',
		'*.w3.to',
		'*.walk.to',
		'*.warp9.to',
		'*.window.to',
		'*.yours.at',
		'*.zap.to',
		'*.zip.to',
	),
	'vdirect.com' => array(
		'*.emailme.net',
		'*.getto.net',
		'*.inetgames.com',
		'*.netbounce.com',
		'*.netbounce.net',
		'*.oneaddress.net',
		'*.snapto.net',
		'*.vdirect.com',
		'*.vdirect.net',
		'*.webrally.net',
	),
	'vgo2.com',
	'w3t.org',
	'wapurl.co.uk',
	'wb.st' => array(
		'*.team.st',
		'*.wb.st',
	),
	'wbkt.net',
	'webalias.com' => array(
		'*.andmuchmore.com',
		'*.browser.to',
		'*.escape.to',
		'*.fornovices.com',
		'*.fun.to',
		'*.got.to',
		'*.hottestpix.com',
		'*.imegastores.com',
		'*.latest-info.com',
		'*.learn.to',
		'*.moviefever.com',
		'*.mp3-archives.com',
		'*.myprivateidaho.com',
		'*.radpages.com',
		'*.remember.to',
		'*.resourcez.com',
		'*.return.to',
		'*.sail.to',
		'*.sports-reports.com',
		'*.stop.to',
		'*.thrill.to',
		'*.tophonors.com',
		'*.uncutuncensored.com',
		'*.up.to',
		'*.veryweird.com',
		'*.way.to',
		'*.web-freebies.com',
		'.webalias.com',
		'*.webdare.com',
		'*.xxx-posed.com',
	),
	'webmasterwise.com',
	'wittylink.com',
	'wiz.sc',			// tiny.cc related
	'x50.us' => array(
		'*.i50.de',
		'*.x50.us',
	),
	'xhref.com',
	'xn6.net' => array(
		'*.9ax.net',
		'*.xn6.net',
	),
	'.y11.net',
	'yatuc.com',
	'yep.it',
	'yumlum.com',
	'yurel.com',
	'z.la' => array(
		'z.la',
		't.z.la',
	),
	'zaable.com',
	'zapurl.com',
	'zarr.co.uk',
	'zeroweb.org' => array(
		'*.80t.com',
		'*.firez.org',
		'*.fizz.nu',
		'*.ingame.org',
		'*.irio.net',
		'*.v33.org',
		'*.zeroweb.org',
	),
	'zippedurl.com',
	'*.zu5.net',
	'zuso.tw',
	'*.zwap.to',

	// A-2: Dynamic DNS, Dynamic IP services, DNS vulnerabilities, or another DNS cases
	//
	//'ddns.ru' => array('*.bpa.nu'),
	//'*.dnip.net',
	//'*.dyndns.*',
		//'*.dyndns.dk',
		//'*.dyndns.co.za',
		//'*.dyndns.nemox.net',
	//'dydns.com' => array(
	//	'*.ath.cx',
	//	'*.dnsalias.org',
	//	'*.dyndns.org',
	//	'*.homeip.net',
	//	'*.mine.nu',
	//	'*.shacknet.nu',
	//),
	//'*.dynu.com',
	//'*.nerdcamp.net',
	//'*.zenno.info',
	//'.cm',	// 'Cameroon' ccTLD, sometimes used as typo of '.com'
				// and all non-recorded domains redirect to 'agoga.com' now


	// B: Sample setting of:
	// Jacked (taken advantage of) and cleaning-less sites
	//
	// Please notify us about this list with reason:
	// http://pukiwiki.sourceforge.jp/dev/?BugTrack2%2F208

	// B-1: Web spaces
	//
	//   Messages from forerunners:
	//     activefreehost.com
	//       "We regret to inform you that ActiveFreeHost
	//        free hosting service has is now closed (as of
	//        September 18). We have been online for over
	//        two and half years, but have recently decided
	//        to take time for software improvement to fight
	//        with server abuse, Spam advertisement and
	//        fraud."
	//
	'*.0catch.com',		// by bluehost.com
	'100 Best Inc' => array(	// by 100 Best Inc (info at 100best.com)
		'*.0-st.com',
		'*.150m.com',	// by 100 Best, Inc., NS by 0catch.com
		'*.2-hi.com',
		'*.20fr.com',
		'*.20ii.com',
		'*.20is.com',
		'*.20it.com',
		'*.20m.com',	// by jlee at 100bestinc.com
		'*.20me.com',
		'*.20to.com',
		'*.2u-2.com',
		'*.3-st.com',
		'*.fws1.com',
		'*.fw-2.com',
		'*.inc5.com',
		'*.on-4.com',
		'*.st-3.com',
		'*.st20.com',
		'*.up-a.com',
	),
	'20six weblog services' => array(
		'.20six.nl',			// by 20six weblog services (postmaster at 20six.nl)
		'.20six.co.uk',
		'.20six.fr',
		'myblog.de',
		'myblog.es',
	),
	'*.250free.com',	// by Brian Salisbury (domains at 250host.com)
	'2page.de' => array(
		'.dreipage.de',
		'.2page.de',
	),
	'c2k.jp' => array(
		'.081.in',		// by Makoto Okuse (webmaster at 2style.net)
		'.2st.jp',		// by 2style, ns *.click2k.net, *.2style.net
		'.2style.in',	// by Makoto Okuse (webmaster at 2style.net)
		'.2style.jp',	// by click2k, ns *.2style.jp, *.2style.net
		'.2style.net',	// by makoto okuse (webmaster at 2style.net), ns *.click2k.net, *.2style.jp, *.2style.net
		'.betty.jp',	// by 2style, ns *.click2k.net, *.2style.net
		'.bian.in',		// by Makoto Okuse (webmaster at 2style.net)
		'.cabin.jp',	// by 2style, ns *.click2k.net, *.2style.net
		'.click2k.net',	// by makoto okuse (webmaster at 2style.net), ns *.click2k.net, *.2style.net
		'.cult.jp',		// by 2style, ns *.click2k.net, *.2style.net
		'.curl.in',		// by Makoto Okuse (webmaster at 2style.net)
		'.cute.cd',		// by Yuya Fukuda (count at kit.hi-ho.ne.jp), ns *.2style.jp, *.2style.net
		'.ennui.in',	// by Makoto Okuse (webmaster at 2style.net)
		'.houka5.com',	// by makoto okuse (webmaster at 2style.net), ns *.click2k.net, *.2style.net
		'.jinx.in',		// by Makoto Okuse (webmaster at 2style.net)
		'.loose.in',	// by Makoto Okuse (webmaster at 2style.net)
		'.mippi.jp',	// by 2style, ns *.click2k.net, *.2style.net
		'.mist.in',		// by Makoto Okuse (webmaster at 2style.net)
		'.muu.in',		// by Makoto Okuse (webmaster at 2style.net)
		'.naive.in',	// by Makoto Okuse (webmaster at 2style.net)
		'.panic.in',	// by Makoto Okuse (webmaster at 2style.net)
		'.psyco.jp',	// by click2k, ns *.click2k.net, *.2style.net
		'.purety.jp',	// by 2style, ns *.click2k.net, *.2style.net
		'.rapa.jp',		// by 2style, ns *.click2k.net, *.2style.net
		'.side-b.jp',	// by 2style, ns *.click2k.net, *.2style.net
		'.slum.in',		// by Makoto Okuse (webmaster at 2style.net)
		'.sweety.jp',	// by click2k, ns *.click2k.net, *.2style.net
		'.web-box.jp',	// by 2style, ns *.click2k.net, *.2style.net
		'.yea.jp',		// by 2style, ns *.click2k.net, *.2style.net
	),
	'*.30mb.com',		// by 30MB Online (63681 at whois.gkg.net)
	'icedesigns at gmail.com' => array(	// by Boling Jiang (icedesigns at gmail.com)
		'*.0moola.com',
		'*.3000mb.com',
		'.501megs.com',
		'*.teracities.com',
		'*.xoompages.com',
	),
	'*.50megs.com',		// by hostmaster at northsky.com
	'*.5gbfree.com',
	'*.9999mb.com',		// by allan Jerman (prodigy-airsoft at cox.net)
	'aeonity.com',		// by Emocium Solutions (creativenospam at gmail.com)
	'*.aimoo.com',
	'*.alkablog.com',
	'*.alluwant.de',
	'AOL' =>	// http://about.aol.com/international_services
		'/^(?:chezmoi|home|homes|hometown|journals|user)\.' .
		'(aol|americaonline)\.' .
		'(?:ca|co\.uk|com|com\.au|com.mx|de)$/',
		// Rough but works
	'angelfire.com',	// angelfire.lycos.com
	'*.askfaq.org',
	'*.atfreeforum.com',
	'*.asphost4free.com',
	'basenow.com',
	'*.bb-fr.com',
	'*.beeplog.com',
	'bestfreeforums.com',
	'bizcn.com' => '/.*\.w[0-9]+\.bizcn\.com$/', // XiaMen BizCn Computer & Network CO.,LTD
	'*.blog.hr',
	'*.blog-fx.com',
	'blogas.lt',
	'*.blogdrive.com',
	'*.blogg.de',
	'*.bloggingmylife.com',
	'*.bloggles.info',
	'bloggers.nl',
	'*.blogharbor.com',
	'bloguitos.com',
	'blogosfer.com',
	'*.blogslive.net',
	'*.blogspot.com',		// by Google
	'*.blogstream.com',
	'blogyaz.com',
	'*.boardhost.com',
	'bravenet.com' => array(
		'*.bravenet.com',
		'*.bravehost.com',
	),
	'*.by.ru',				// nthost.ru related?
	'*.chueca.com',
	'concepts-mall.com',
	'counterhit.de',
	'createforum.us' => array(
		'*.createforum.us',
		'*.forumportal.us',
		'*.freeportal.us',
		'*.phpbb2.us',
	),
	'*.createmybb.com',
	'cwcity.de' => array(
		'.cwcity.de',
		'.cwsurf.de',
	),
	'dakrats.net',
	'*.dcswtech.com',
	'*.devil.it',
	'*.diaryland.com',
	'dotbb.be',
	'*.dox.hu',				// dns at 1b.hu
	'*.e-host.ws',		// by dns at jomax.net, ns by 0catch.com
	'*.eblog.com.au',
	'*.ekiwi.de',
	'*.eamped.com',			// Admin by Joe Hayes (joe_h_31028 at yahoo.com)
	'.easyfreeforum.com',	// by XT Store Sas, Luca Lo Bascio (marketing at atomicshop.it)
	'*.ebloggy.com',
	'enunblog.com',
	'*.epinoy.com',
	'forum.ezedia.net',
	'*.extra.hu',			// angelo at jasmin.hu
	'*.fanforum.cc',
	'fingerprintmedia.com',
	'*.filelan.com',
	'*.free-25.de',
	'*.free-bb.com',
	'*.free-site-host.com',	// by CGM-Electronics (chris at cgm-electronics.com)
	'freebb.nl',
	'*.freeclans.de',
	'*.freelinuxhost.com',	// by 100webspace.com
	'freeforum.at',			// by Sandro Wilhelmy
	'freewebhostingarea.com' => array(	// or www.freewha.com
		'*.6te.net',
		'*.ueuo.com',
		'*.orgfree.com',
	),
	'*.freewebpage.org',
	'freewebs.com',
	'*.freewebspace.net.au',
	'*.foren-city.de',
	'foroswebgratis.com',
	'*.fory.pl',
	'*.forum-on.de',
	'forumbolt.com',
	'forumnow.com.br',
	'*.forumppl.com',
	'forumprofi' => '#^(?:.*\.)?forumprofi[0-9]*\.de$#',
	'forumup' => '#^^(?:.*\.)?forumup\.' .
		'(?:at|be|ca|ch|co\.nz|co\.uk|co\.za|com|com\.au|com\.mx|cn|' .
		'cz|de|dk|es|eu|fr|gr|hu|in|info|ir|it|jobs|jp|lt|' .
		'lv|org|pl|name|net|nl|ro|ru|se|sk|tv|us|web\.tr)$#',
	'freepowerboards.com',
	'*.freepowerboards.com',
	'*.funpic.de',
	'geocities.com',
	'getbetterhosting.com' => array(
		'*.30mb.com',	// by 30MB Online (63681 at whois.gkg.net), introduced as one alternative of 90megs.com
		'*.90megs.com',	// by Get Better Hosting (admin at getbetterhosting.com)
	),
	'*.guestbook.de',
	'gwebspace.de',
	'gossiping.net',
	'gb-hoster.de',
	'*.goodboard.de',
	'docs.google.com',			// by Google
	'guestbook.at',
	'club.giovani.it',
	'*.gratis-server.de',
	'groups-beta.google.com',	// by Google
	'healthcaregroup.com',
	'*.hit.bg',				// by forumup.com ??
	'*.host-page.com',
	'*.hostingclub.de',
	'*.hut2.ru',
	'ic.cz' => array(
		'*.ezin.cz',		// internetcentrum at gmail.com, ns ignum.com, ignum.cz
		'*.hu.cz',			// internetcentrum at gmail.com
		'*.hustej.net',		// baz at bluedot.cz, dom-reg-joker at ignum.cz
		'*.ic.cz',			// internetcentrum at gmail.com
		'*.kx.cz',			// jan at karabina.cz, info at ignum.cz
		'*.own.cz',			// internetcentrum at gmail.com
		'*.phorum.cz',		// internetcentrum at gmail.com
		'*.tym.cz',			// internetcentrum at gmail.com
		'*.tym.sk',			// jobot at ignum.cz
		'*.wu.cz',			// jan at karabina.cz, info at ignum.cz
		'*.yc.cz',			// ivo at karabina.cz, jan at karabina.cz
		'*.yw.sk',			// jobot at ignum.cz
	),
	'ieurop.net' => array(
		'*.ibelgique.com',
		'*.iespana.es',
		'*.ifrance.com',
		'*.iitalia.com',
		'*.iquebec.com',
		'*.isuisse.com',
	),
	'*.ihateclowns.net',
	'*.iphorum.com',
	'*.blog.ijijiji.com',
	'it168.com',
	'.iwannaforum.com',
	'*.journalscape.com',
	'*.blog.kataweb.it',
	'blogs.kaixo.com',
	'*.kokoom.com',
	'koolpages.com',
	'*.ksiegagosci.info',
	'lide.cz' => array(
		'*.lide.cz',
		'*.sblog.cz',
	),
	'limmon.net',
	'*.livejournal.com',
	'*.logme.nl',
	'ltss.luton.ac.uk',
	'*.lycos.it',
	'angelfire.lycos.com',
	'*.messageboard.nl',
	'mokono GmbH' => array(
		'*.blog.com.es',
		'*.blog.de',
		'*.blog.fr',
		'*.blog.co.uk',
		'*.blog.ca',
		'*.blogs.se',
		'*.blogs.fi',
		'*.blogs.no',
		'*.blogs.dk',
		'*.blogs.ro',
		'*.weblogs.pl',
		'*.weblogs.cz',
		'*.weblogs.hu',
	),
	'monforum.com' => array(
		'*.monforum.com',
		'*.monforum.fr',
	),
	'*.multiforum.nl',		// by Ron Warris (info at phpbbhost.nl)
	'myblog.is',
	'myblogma.com',
	'*.myblogvoice.com',
	'myblogwiki.com',
	'*.myforum.ro',
	'*.myfreewebs.net',
	'*.mysite.com',
	'*.myxhost.com',
	'*.netfast.org',
	'netgears.com' => array(	// by domains at netgears.com
		'*.9k.com',
		'*.741.com',
		'*.freewebsitehosting.com',
		'*.freewebspace.com',
		'*.freewebpages.org',
	),
	'neweconomics.info',
	'*.nm.ru',
	'*.w100.okwit.com',		// by chen jinian (dfym at dfym.cn)
	'onlyfree.de',
	'*.ooblez.com',			// by John Nolande (ooblez at hotmail.com)
	'osemka.pl' => array(	// by Osemka Internet Media (biuro at nazwa.pl)
		'.friko.pl',
		'.jak.pl',
		'.nazwa.pl',
		'.w8w.pl',
		'.za.pl',
		'.skysquad.net',	// by Dorota Brzezinska (info at nazwa.pl)
	),
	'*.parlaris.com',
	'*.pathfinder.gr',
	'*.foros.tv',
	'*.phorum.pl',
	'*.phpbbx.de',
	'*.pochta.ru',
	'*.portbb.com',
	'powerwebmaster.de',
	'proboards' => '#^(?:.*\.)proboards[0-9]*\.com$#',
	'*.probook.de',
	'*.prohosting.com',	// by Nick Wood (admin at dns-solutions.net)
	'*.quickfreehost.com',
	'quizilla.com',
	'*.qupis.com',		// by Xisto Corporation (shridhar at xisto.com)
	'razyboard.com',
	'realhp.de',
	'rgbdesign at gmail.com' => array(	// by RB2 (rgbdesign at gmail.com)
		'*.juicypornhost.com',
		'*.pornzonehost.com',
		'*.xhostar.com',
	),
	'rin.ru' => array(
		'*.sbn.bz',
		'*.wol.bz',
	),
	'Halverston Holdings Limited' => array(	// pochta.ru?lng=en
		// Seems one of affiliates of RBC, RosBusinessConsulting (rbc.ru, rbcnews.com)
		'*.fromru.com',		// by Lapeshkina Tatyana (noc at pochta.ru)
		'*.front.ru',		// (domain at hc.ru)
		'*.hc.ru',			// by (domain at hosting.rbc.ru, domaincredit at hosting.rbc.ru)
		'*.hotbox.ru',		// (domain at hc.ru)
		'*.hotmail.ru',		// (hosting at hc.ru)
		'*.krovatka.su',	// (domain at hc.ru, hosting at hc.ru)
		'*.land.ru',		// (domain at hc.ru)
		'*.mail15.com',		// (hosting at hc.ru)
		'*.mail333.com',	// (hosting at hc.ru)
		'*.newmail.ru',		// (domain at hc.ru, hosting at hc.ru)
		'*.nightmail.ru',	// (domain at hc.ru, hosting at hc.ru)
		'*.pisem.net',		// (hosting at hc.ru)
		'*.pochta.ru',		// (domain at hc.ru)
		'*.pochtamt.ru',	// (domain at hc.ru)
		'*.pop3.ru',		// (domain at hc.ru)
		'*.rbcmail.ru',		// (domain at hc.ru)
		'*.smtp.ru',		// (domain at hc.ru)
	),
	'*.sayt.ws',
	'*.seo-blog.org',
	'*.shoutpost.com',
	'*.siamforum.com',
	'*.siteburg.com',
	'*.sitesfree.com',		// support at livesearching.com
	'*.sitesled.com',
	'skinnymoose.com',		// by Steven Remington (admin at outdoorwebhosting.com)
	'smartrans.com' => array(
		'.3w.to',
		'.aim.to',
		'.djmp.jp',
		'.nihongourl.nu',
		'.urljp.com',
		'.www1.to',
		'.www2.to',
		'.www3.to',
	),
	'*.spazioforum.it',
	'*.spicyblogger.com',
	'*.spotbb.com',
	'*.squarespace.com',
	'stickypond.com',
	'stormloader.com',
	'strikebang.com',
	'*.sultryserver.com',
	'*.t35.com',
	'tabletpcbuzz.com',
	'*.talkthis.com',
	'tbns.net',
	'telasipforums.com',
	'thestudentunderground.org',
	'think.ubc.ca',
	'*.tripod.com',
	'*.vtost.com',
	'*.vidiac.com',
	'volny.cz',
	'*.welover.org',
	'web1000.com' => array(
		// http://www.web1000.com/register_new2.php
		'*.fasthost.tv',
		'*.hothost.tv',
		'*.isgreat.tv',
		'*.issexy.tv',
		'*.isterrible.tv',
		'*.somegood.tv',
		'*.adultnations.com',
		'*.alladultfemale.com',
		'*.alladultmale.com',
		'*.allbisexual.com',
		'*.allbreast.com',
		'*.allfeminism.com',
		'*.allmanpages.com',
		'*.allmendirect.com',
		'*.allphotoalbum.com',
		'*.allsexpages.com',
		'*.allwomanweb.com',
		'*.attorney-site.com',
		'*.bedclip.com',
		'*.bestfamilysite.com',
		'*.bestmusicpages.com',
		'*.bigmoron.com',
		'*.bourgeoisonline.com',
		'*.candyfrom.us',
		'*.cartoonhit.com',
		'*.cat-on-line.com',
		'*.chokesondick.com',
		'*.closeupsof.us',
		'*.cpa-site.com',
		'*.dampgirl.com',
		'*.dampgirls.com',
		'*.deepestfetish.com',
		'*.docspages.com',
		'*.dog-on-line.com',
		'*.dogcountries.com',
		'*.dognations.com',
		'*.doingitwith.us',
		'*.drenchedface.com',
		'*.drenchedlips.com',
		'*.drspages.com',
		'*.edogden.com',
		'*.eroticountry.com',
		'*.fasthost.tv',
		'*.fineststars.com',
		'*.foronlinegames.com',
		'*.forplanetearth.com',
		'*.freeadultparty.com',
		'*.freespeechsite.com',
		'*.gayadultxxx.com',
		'*.gaytaboo.com',
		'*.greatcookery.com',
		'*.greatrecipepages.com',
		'*.greatstreamingvideo.com',
		'*.hatesit.com',
		'*.hothost.tv',
		'*.iscrappy.com',
		'*.isgreat.tv',
		'*.issexy.tv',
		'*.isterrible.tv',
		'*.itinto.us',
		'*.japannudes.net',
		'*.jesussave.us',
		'*.jesussaveus.com',
		'*.labialand.com',
		'*.lettersfrom.us',
		'*.lookingat.us',
		'*.lunaticsworld.com',
		'*.microphallus.com',
		'*.mycatshow.com',
		'*.mydogshow.com',
		'*.myhardman.com',
		'*.mylawsite.net',
		'*.mylovething.com',
		'*.onasoapbox.com',
		'*.onlinepulpit.com',
		'*.petitionthegovernment.com',
		'*.photosfor.us',
		'*.picturetrades.com',
		'*.pleasekiss.us',
		'*.politicalemergency.com',
		'*.power-emergency.com',
		'*.prayingfor.us',
		'*.realbadidea.com',
		'*.realisticpolitics.com',
		'*.reallybites.com',
		'*.reallypumping.us',
		'*.reallyrules.com',
		'*.reallysuckass.com',
		'*.reallysucksass.com',
		'*.realsweetheart.com',
		'*.rottenass.com',
		'*.schoolreference.com',
		'*.sexheroes.com',
		'*.sharewith.us',
		'*.smutstars.com',
		'*.soakinglips.com',
		'*.somegood.tv',
		'*.songsfrom.us',
		'*.stinkingfart.com',
		'*.stinkyhands.com',
		'*.storiesfrom.us',
		'*.taboocountry.com',
		'*.television-series.com',
		'*.thisbelongsto.us',
		'*.totallyboning.us',
		'*.vaginasisters.com',
		'*.verydirtylaundry.com',
		'*.videosfor.us',
		'*.videosfrom.us',
		'*.videosof.us',
		'*.virtualdogshit.com',
		'*.web1000.com',
		'*.webpicturebook.com',
		'*.wronger.com',
		'*.xxxnations.com',
		'*.yourwaywith.us',
	),
	'webblog.ru',
	'weblogmaniacs.com',
	'*.webnow.biz',			// by Hsien I Fan (admin at servcomputing.com), ServComputing Inc. 
	'websitetoolbox.com',
	'welnet.de' => array(
		'welnet.de',
		'welnet4u.de',
	),
	'wh-gb.de',
	'*.wikidot.com',
	'*.wmjblogs.ru',
	'*.wordpress.com',
	'.wsboards.com',		// by Chris Breen (Cbween at gmail.com)
	'xeboards.com',			// by Brian Shea (bshea at xeservers.com)
	'UcoZ Web-Services' => array(
		'*.3dn.ru',
		'*.clan.su',
		'*.moy.su',
		'*.my1.ru',
		'*.p0.ru',
		'*.pp.net.ua',
		'*.ucoz.co.uk',
		'*.ucoz.com',
		'*.ucoz.net',
		'*.ucoz.org',
		'*.ucoz.ru',
		'*.ws.co.ua',
	),
	'*.ya.com',				// 'geo.ya.com', 'blogs.ya.com', 'humano.ya.com', 'audio.ya.com'...
	'YANDEX, LLC.' => array(
		'*.narod.ru',		// noc at yandex.net
		'yandex.ru',		// noc at yandex.net
	),
	'yourfreebb.de',
	'your-websites.com' => array(
		'*.your-websites.net',
		'*.web-space.ws',
	),

	// B-2: Jacked contents, something implanted
	// (e.g. some sort of blog comments, BBSes, forums, wikis)
	'aac.com',
	'*.aamad.org',
	'agnt.org',
	'alwanforthearts.org',
	'*.anchor.net.au',
	'anewme.org',
	'blepharospasm.org',
	'nyweb.bowlnfun.dk',
	'*.buzznet.com',
	'Case Western Reserve University' => array('case.edu'),
	'*.colourware.co.uk',
	'International Christian University' => array('icu.edu.ua'),
	'*.iphpbb.com',
	'board-z.de',
	'*.board-z.de',
	'California State University Stanislaus' => array('writing.csustan.edu'),
	'cannon.co.za',
	'columbosgarden.com',
	'*.communityhost.de',
	'connectionone.org',
	'deforum.org',
	'delayedreaction.org',
	'deproduction.org',
	'dc503.org',
	'dre-centro.pt',
	'forums.drumcore.com',
	'energyglass.com.ua',
	'info.ems-rfid.com',
	'.fhmcsa.org.au',
	'findyourwave.co.uk',
	'plone4.fnal.gov',
	'freeforen.com',
	'funkdoc.com',
	'funnyclipcentral.com',
	'gearseds.com',
	'ghettojava.com',
	'*.goodboard.de',
	'greendayvideo.net' => array(
		'greendayvideo.net',
		'espanol.greendayvideo.net',
	),
	'Harvard Law School' => array('blogs.law.harvard.edu'),
	'helpiammoving.com',
	'homepage-dienste.com',
	'hullandhull.com',
	'huskerink.com',
	'.hyba.info',
	'inda.org',
	'internetincomeclub.com',
	'*.inventforum.com',
	'ipwso.org',
	'ironmind.com',
	'skkustp.itgozone.com',	// hidden JavaScript
	'.jloo.org',
	'Kazan State University' => array(
		'dir.kzn.ru',
		'sys.kcn.ru',
	),
	'test.kernel.org',
	'kevindmurray.com',
	'kroegjesroutes.nl',
	'forum.lixium.fr',
	'macfaq.net',
	'me4x4.com',
	'minsterscouts.org',
	'morallaw.org',
	'morerevealed.com',
	'mamiya.co.uk',
	'vcd.mmvcd.com',
	'Monmouth College' => array('department.monm.edu'),
	'mountainjusticemedia.org',
	'*.mybbland.com',
	'North Carolina A&T State University' => array(
		'ncat.edu',
		'my.ncat.edu',
		'hlc.ncat.edu',
	),
	'users.nethit.pl',
	'nightclubvip.net',
	'njbodybuilding.com',
	'nlen.org',
	'Sacred Heart Catholic Primary School' => array('sacredheartpymble.nsw.edu.au'),
	'offtextbooks.com',
	'ofimatika.com',
	'omikudzi.ru',
	'openchemist.net',
	'pataphysics-lab.com',
	'perl.org.br',
	'pix4online.co.uk',
	'preform.dk',
	'privatforum.de',
	'rehoboth.com',
	'rodee.org',
	'ryanclark.org',
	'*.reallifelog.com',
	'rkphunt.com',
	'sapphireblue.com',
	'saskchamber.com',
	'selikoff.net',
	'serbisyopilipino.org',
	'setbb.com',
	'silver-tears.net',
	'.softpress.com',
	'southbound-inc.com',	// There is a <html>.gif (img to it168.com) 
	'tehudar.ru',
	'Tennessee Tech University' => array('manila.tntech.edu'),
	'thebluebird.ws',
	'theosis.org',
	'troms-slekt.com',
	'theedgeblueisland.com',
	'chat.travlang.com',
	'Truman State University' => array('mathbio.truman.edu'),
	'tuathadedannan.org',
	'txgotg.com',
	'tzaneen.co.za',
	'The University of North Dakota' => array(
		'learn.aero.und.edu',
		'ez.asn.und.edu',
	),
	'The University of Alabama' => array('bama.ua.edu'),
	'urgentclick.com',
	'vacant.org.uk',
	'Villa Julie College' => array('www4.vjc.edu'),
	'West Virginia University Parkersburg' => array('wvup.edu'),
	'wolvas.org.uk',
	'wookiewiki.org',
	'uk.groups.yahoo.com',
	'University of Nebraska Lincoln' => array('ftp.ianr.unl.edu'),
	'yasushi.site.ne.jp',	// One of mixedmedia.net'
	'youthpeer.org',
	'*.zenburger.com',


	// C: Sample setting of:
	// Exclusive spam domains
	//
	// Seems to have flavor of links, pills, gamble, online-games, erotic,
	// affiliates, finance, sending viruses, malicious attacks to browsers,
	// and/or mixed ones
	//
	// Please notify us about this list with reason:
	// http://pukiwiki.sourceforge.jp/dev/?BugTrack2/208

	// C-1: Domain sets (seems to be) born to spam you
	//
	// All buziness-related spam
	//'.biz'
	//
	// by Boris (admin at seekforweb.com, bbmfree at yahoo.com)
	'admin at seekforweb.com' => array(
		'.lovestoryx.com',
		'.loveaffairx.com',
		'.onmore.info',
		'.scfind.info',
		'.scinfo.info',
		'.webwork88.info',
	),
	// by Boris (boss at bse-sofia.bg)
	'boss at bse-sofia.bg' => array(
		'.htewbop.org',
		'.kimm--d.org',
		'.gtre--h.org',
		'.npou--k.org',
		'.bres--z.org',
		'.berk--p.org',
		'.bplo--s.org',
		'.basdpo.org',
		'.jisu--m.org',
		'.kire--z.org',
		'.mertnop.org',
		'.mooa--c.org',
		'.nake--h.org',
		'.noov--b.org',
		'.suke--y.org',
		'.vasdipv.org',
		'.vase--l.org',
		'.vertinds.org',
	),
	// by Thai Dong Changli (pokurim at gamebox.net)
	'Thai Dong Changli' => array(
		'.aqq3.info',
		'.axa00.info',
		'.okweb11.org',
		'.okweb12.org',
		'.okweb13.org',
		'.okweb14.org',
	),
	// by opezdol at gmail.com
	'opezdol' => array(
		'.informazionicentro.info',
		'.notiziacentro.info',
	),
	'something_gen' => array(
		'.adult-chat-world.info',	// 'CamsGen' by Lui Xeng Shou (camsgen at model-x.com),
		'.adult-chat-world.org',	// 'CamsGen' by Lui Xeng Shou (camsgen at model-x.com)
		'.adult-sex-chat.info',		// 'CamsGen' by Lui Xeng Shou (camsgen at model-x.com)
		'.adult-sex-chat.org',		// 'CamsGen' by Lui Xeng Shou (camsgen at model-x.com)
		'.adult-cam-chat.info',		// 'CamsGen' by Lui Xeng Shou (camsgen at model-x.com)
		'.adult-cam-chat.org',		// 'CamsGen' by Lui Xeng Shou (camsgen at model-x.com)
		'.dildo-chat.org',			// 'CamsGen' by Lui Xeng Shou (camsgen at model-x.com)
		'.dildo-chat.info',			// 'CamsGen' by Lui Xeng Shou (camsgen at model-x.com)
		// flirt-online.info is not CamsGen
		'.flirt-online.org',		// 'CamsGen' by Lui Xeng Shou (camsgen at model-x.com)
		'.live-adult-chat.info',	// 'CamsGen' by Lui Xeng Shou (camsgen at model-x.com)
		'.live-adult-chat.org',		// 'CamsGen' by Lui Xeng Shou (camsgen at model-x.com)
		'.sexy-chat-rooms.info',	// 'CamsGen' by Lui Xeng Shou (camsgen at model-x.com)
		'.sexy-chat-rooms.org',		// 'CamsGen' by Lui Xeng Shou (camsgen at model-x.com)
		'.swinger-sex-chat.info',	// 'CamsGen' by Lui Xeng Shou (camsgen at model-x.com)
		'.swinger-sex-chat.org',	// 'CamsGen' by Lui Xeng Shou (camsgen at model-x.com)
		'.nasty-sex-chat.info',		// 'CamsGen' by Lui Xeng Shou (camsgen at model-x.com)
		'.nasty-sex-chat.org',		// 'CamsGen' by Lui Xeng Shou (camsgen at model-x.com)

		'.camshost.info',			// 'CamsGen' by Sergey (buckster at hotpop.com)
		'.camdoors.info',			// 'CamsGen' by Sergey (buckster at hotpop.com)
		'.chatdoors.info',			// 'CamsGen' by Sergey (buckster at hotpop.com)

		'.lebedi.info',				// 'BucksoGen', by Pronin Sergey (buckster at list.ru), 
		'.loshad.info',				// 'BucksoGen', by Pronin Sergey (buckster at list.ru)
		'.porosenok.info',			// 'BucksoGen', by Pronin Sergey (buckster at list.ru)
		'.indyushonok.info',		// 'BucksoGen', by Pronin Sergey (buckster at list.ru)
		'.kotyonok.info',			// 'BucksoGen', by Pronin Sergey (buckster at list.ru)
		'.kozlyonok.info',			// 'BucksoGen', by Pronin Sergey (buckster at list.ru)
		'.magnoliya.info',			// 'BucksoGen', by Pronin Sergey (buckster at list.ru)
		'.svinka.info',				// 'BucksoGen', by Pronin Sergey (buckster at list.ru)
		'.svinya.info',				// 'BucksoGen', by Pronin Sergey (buckster at list.ru)
		'.zherebyonok.info',		// 'BucksoGen', by Pronin Sergey (buckster at list.ru)

		'.adult-cam-chat-sex.info',		// by Lee Chang (nebucha at model-x.com)
		'.adult-chat-sex-cam.info',		// 'CamsGen' by Lee Chang (nebucha at model-x.com)
		'.live-chat-cam-sex.info',		// 'CamsGen' by Lee Chang (nebucha at model-x.com)
		'.live-nude-cam-chat.info',		// 'CamsGen' by Lee Chang (nebucha at model-x.com)
		'.live-sex-cam-nude-chat.info',	// 'CamsGen' by Lee Chang (nebucha at model-x.com)
		'.sex-cam-live-chat-web.info',	// 'CamsGen' by Lee Chang (nebucha at model-x.com)
		'.sex-chat-live-cam-nude.info',	// 'CamsGen' by Lee Chang (nebucha at model-x.com)
		'.sex-chat-porn-cam.info',		// by Lee Chang (nebucha at model-x.com)
	),
	// by Marcello Italianore (mital at topo20.org)
	'Marcello Italianore' => array(
		'.trevisos.org',
		'.topo20.org',
	),
	'wellcams.com' => array(
		'.j8v9.info',		// by Boris Moiseev (borka at 132moiseev.com)
		'.wellcams.com',	// by Sergey Sergiyenko (studioboss at gmail.com)
		'.wellcams.biz',	// by Sergey Sergiyenko (studioboss at gmail.com)
	),
	// by Chinu Hua Dzin (graz at rubli.biz)
	'Chinu Hua Dzin' => array(
		'.besturgent.org',
		'.googletalknow.org',
		'.montypythonltd.org',
		'.supersettlemet.org',
		'.thepythonfoxy.org',
		'.ukgamesyahoo.org',
		'.youryahoochat.org',
	),
	// by Kikimas at mail.net, Redirect to nb717.com etc
	'Kikimas at mail.net' => array(
		'.dbsajax.org',
		'.acgt2005.org',
		'.gopikottoor.com',
		'.koosx.org',
		'.mmgz.org',
		'.zhiyehua.net',
	),
	// by Andrey (vdf at lovespb.com)
	'vdf at lovespb.com' => array(
		'.02psa.info',
		'.1818u.org',
		'.18ew.info',
		'.43sexx.org',
		'.56porn.org',
		'.6discount.info',
		'.78porn.org',		// "UcoZ WEB-SERVICES"
		'.78rus.info',
		'.92ssex.org',		// "ForumGenerator"
		'.93adult.org',		// "ForumGenerator"
		'.aboutsr.info',
		'.aboutzw.info',
		'.all23.info',
		'.allvdf.info',
		'.buy-dge.info',
		'.buypo.info',
		'.canadausa.info',	// "UcoZ WEB-SERVICES"
		'.cv83.info',
		'.cvwifw.info',
		'.dabouts.info',
		'.eplot.info',		// by Beatrice C. Anderson (Beatrice.C.Anderson at spambob.com)
		'.fuck2z.info',		// "UcoZ WEB-SERVICES"-like design
		'.free01sa.info',
		'.frees1.info',
		'.freexz.info',
		'.ifree-search.org',
		'.lovespb.info',
		'.myso2.info',
		'.nb74u.info',
		'.ohhh2.info',
		'.ol43.info',
		'.olala18.info',
		'.oursales.info',
		'.pasian1.info',
		'.pldk.info',
		'.po473.info',
		'.pornr.info',		// "UcoZ WEB-SERVICES"
		'.poz2.info',
		'.qpf8j4d.info',
		'.saleqw.info',
		'.sexof.info',		// "UcoZ WEB-SERVICES"
		'.sexz18.info',
		'.sexy69a.info',
		'.shedikc.info',
		'.spb78.info',
		'.usacanadauk.info',
		'.v782mks.info',
		'.vny0.info',
		'.wifes1.info',
		'.xranvam.info',
		'.zenitcx.info',
		'.zxolala.info',
	),
	// by Nike Borzoff (nike.borzoff at gmail.com), 'vdf at lovespb.com'-related
	'nike.borzoff at gmail.com' => array(
		'.care01.info',
		'.care02.info',
		'.care03.info',
		'.care04.info',
		'.care05.info',
		'.care06.info',
		'.care07.info',
		'.care08.info',
		'.care09.info',
		'.care10.info',
		'.kra1906.info',	// "UcoZ WEB-SERVICES"
		'.klastar01.info',
		'.klastar02.info',
		'.klastar03.info',
		'.klastar04.info',
		'.klastar05.info',
		'.klastar06.info',
		'.klastar07.info',
		'.klastar08.info',
		'.klastar09.info',
		'.klastar10.info',
		'.um20ax01.info',
		'.um20ax02.info',
		'.um20ax03.info',
		'.um20ax04.info',
		'.um20ax05.info',
		'.um20ax06.info',
		'.um20ax07.info',
		'.um20ax08.info',
		'.um20ax09.info',
		'.um20ax10.info',
	),
	'forrass at gmail.com' => array(	// by Ismail (forrass at gmail.com)
		'.zmh01.info',
		'.zmh02.info',
		'.zmh03.info',
		'.zmh04.info',
		'.zmh05.info',
		'.zmh06.info',
		'.zmh07.info',
		'.zmh08.info',
		'.zmh09.info',
		'.zmh10.info',
	),
	// by Varsylenko Vladimir (vvm_kz at rambler.ru)
	// by David C. Lack (David.C.Lack at dodgeit.com)
	// by Kuzma V Safonov (admin at irtes.ru)
	// by Petrov Vladimir (vvm_kz at rambler.ru)
	'Varsylenko Vladimir and family' => array(
		'.abrek.info',				// by Petrov
		'.allsexonline.info',		// by Varsylenko
		'.bequeous.info',			// by Davi
		'.d1rnow.info',				// by Petrov
		'.doxer.info',				// by Petrov
		'.freeforworld.info',		// by Varsylenko
		'.goodworksite.info',		// by Varsylenko
		'.onall.info',				// by Varsylenko
		'.powersiteonline.info',	// by Varsylenko
		'.rentmysite.info',			// by Varsylenko
		'.sexdrink.info',			// by Petrov
		'.siteszone.info',			// by Varsylenko
		'.sfup.info',				// by Petrov
		'.sopius.info',				// by Kuzma
		'.sovidopad.info',			// by Kuzma
		'.superfreedownload.info',	// by Varsylenko
		'.superneeded.info',		// by Varsylenko
		'.srup.info',				// by Petrov
		'.yerap.info',				// by Kuzma
	),
	// by Andrey Zhurikov (zhu1313 at mail.ru)
	'Andrey Zhurikov' => array(
		'.flywebs.com',
		'.hostrim.com',
		'.playbit.com',
	),
	// by Son Dittman (webmaster at dgo3d.info)
	'Son Dittman' => array(
		'.bsb3b.info',
		'.dgo3d.info',
		'.dgo5d.info',
	),
	// by cooler.infomedia at gmail.com
	'cooler.infomedia' => array(
		'.diabetescarelink.com',
		'.firstdebthelp.com',
	),
	// by Nikolajs Karpovs (hostmaster at astrons.com)
	'Nikolajs Karpovs' => array(
		'.pokah.lv',
		'.astrons.com',
	),
	// by Skar (seocool at bk.ru)
	'Skar' => array(
		'.implex3.com',
		'.implex6.info',
		'.softprof.org',
	),
	'caslim.info' => array(
		'.caslim.info',		// by jonn22 (jonnmarker at yandex.ru)
		'.tops.gen.in',		// by Kosare (billing at caslim.info)
	),
	// by Alexandr (foxwar at foxwar.ispvds.com), Hiding google?q=
	'foxwar at foxwar.ispvds.com' => array(
		'.777-poker.biz',
		'.porn-11.com',
	),
	'conto.pl' => array(
		'.8x.pl',		// domena at az.pl 
		'.3x3.pl',		// by biuro at nazwa.pl
		'.conto.pl',	// by biuro at nazwa.pl
		'.guu.pl',		// by conto.pl (domena at az.pl)
		'.xcx.pl',		// domena at az.pl
		'.yo24.pl',		// domena at az.pl
	),
	// Domains by Lin Zhi Qiang (mail at pcinc.cn)
	// NOTE: pcinc.cn -- by Lin Zhi Qiang (lin80 at 21cn.com)
	'Lin Zhi Qiang' => array(
		'.6828teacup.com',
		'.aaa-livedoor.net',
		'.acyberhome.com',
		'.ahatena.com',
		'.asdsdgh-jp.com',
		'.bbs-qrcode.com',
		'.biglobe-ne.com',
		'.blogplaync.com',
		'.cityhokkai.com',
		'.conecojp.net',
		'.din-or.com',
		'.dtg-gamania.com',
		'.fanavier.net',
		'.fcty-net.com',
		'.gamaniaech.com',
		'.game-fc2blog.com',
		'.game-oekakibbs.com',
		'.game-mmobbs.com',
		'.games-nifty.com',
		'.gameslin.net',
		'.gamesragnaroklink.net',
		'.gemnnammobbs.com',
		'.gameurdr.com',
		'.gameyoou.com',
		'.gangnu.com',
		'.geocitygame.com',
		'.geocitylinks.com',
		'.getamped-garm.com',
		'.gogolineage.net',
		'.goodclup.com',
		'.grandchasse.com',
		'.homepage3-nifty.com',
		'.hosetaibei.com',
		'.interzq.com',
		'.jpragnarokonline.com',
		'.jprmthome.com',
		'.kaukoo.com',		// by zeng xianming (expshell at 163.com)
		'.k5dionne.com',
		'.korunowish.com',
		'.kuronowish.net',
		'.linbbs.com', 		// by zeng xianming (qqvod at qq.com). www.linbbs.com is the same ip of www.game-fc2blog.com(222.77.185.101) at 2007/03/11
		'.lineage-bbs.com',
		'.lineage1bbs.com',
		'.lineage321.com',
		'.lineagejp-game.com',
		'.linkcetou.com',
		'.linrmb.com',		// by zeng xianming (qqvod at qq.com)
		'.maplestorfy.com',
		'.mbspro6uic.com',
		'.netgamelivedoor.com',
		'.nothing-wiki.com',
		'.playsese.com',
		'.ptxk.com',		// by zeng xianming (zxmdiy at gmail.com)
		'.ragnarok-bbs.com',
		'.ragnarok-game.com',
		'.ragnarok-sara.com',
		'.ragnaroklink.com',
		'.rmt-lineagecanopus.com',
		'.rmt-navip.com',
		'.rmt-ranloki.com',
		'.ro-bot.net',
		'.rogamesline.com',
		'.roprice.com',
		'.rormb.com',		// by zeng xianming (qqvod at qq.com)
		'.shoopivdoor.com',
		'.slower-qth.com',
		'.watcheimpress.com',
		'.wikiwiki-game.com',
		'.yahoo-gamebbs.com',
	),
	'caddd at 126.com' => array(
		'.chengzhibing.com',	// by chen gzhibing (caddd at 126.com)
		'.jplinux.com',			// by lian liang (caddd at 126.com)
		'.lineageink.com',		// by cai zibing (caddd at 126.com), iframe to goodclup.com
		'.lineagekin.com',		// by cai zibing (caddd at 126.com), iframe to goodclup.com
		'.tooplogui.com',		// by zibing cai (caddd at 126.com)
		'.twsunkom.com',		// by guo zhi wei (caddd at 126.com)
		'.twmsn-ga.com',		// by guo zhi wei (caddd at 126.com), iframe to grandchasse.com
	),
	// by fly bg (nuigiym2 at 163.com)
	'fly bg' => array(
		'.linainfo.net',
		'.lineagalink.com',
		'.lineagecojp.com',
		'.ragnarokonlina.com',
	),
	// by Baer (aakin at yandex.ru)
	'aakin at yandex.ru' => array(
		'.entirestar.com',
		'.superbuycheap.com',
		'.topdircet.com',
	),
	// by jiuhatu kou (newblog9 at gmail.com)
	'newblog9 at gmail.com' => array(
		'.tianmieccp.com',
		'.xianqiao.net',
	),
	// by Michael (m.frenzy at yahoo.com)
	'm.frenzy at yahoo.com' => array(
		'.soft2you.info',
		'.top20health.info',
		'.top20ringtones.info',
		'.top20travels.info',
		'.v09v.info',
		'.x09x.info',
		'.zb-1.com',
	),
	// by Lebedev Sergey (serega555serega555 at yandex.ru)
	'Lebedev Sergey' => array(
		'.bingogoldenpalace.info',
		'.ccarisoprodol.info',
		'.ezxcv.info',
		'.isuperdrug.com',
		'.pharmacif.info',
		'.pornsexteen.biz',
		'.ugfds.info',
		'.vviagra.info',
	),
	// by Anatol (anatolsenator at gmail.com)
	'anatolsenator at gmail.com' => array(
		'.cheapestviagraonline.info',
		'.buyphentermineworld.info'
	),
	'webmaster at mederotica.com' => array(
		'.listsitepro.com',		// by VO Entertainment Inc (webmaster at mederotica.com)
		'.testviagra.org',		// by Chong Li (chongli at mederotica.com)
		'.viagra-best.org',		// by Chong Li (chongli at mederotica.com)
		'.viagra-kaufen.org',	// by Chong Li (chongli at mederotica.com)
	),
	// by Billing Name:Gray (gray at trafic.name)
	'gray at trafic.name' => array(
		'.axeboxew.info',
		'.boluzuhy.info',
		'.ekafoloz.info',
		'.exidiqe.info',
		'.gubiwu.info',
		'.jiuuz.info',
		'.olasep.info',
		'.oueuidop.info',
		'.oviravy.info',
		'.ragibe.info',
		'.udaxu.info',
		'.vubiheq.info',
		'.yvaxat.info',
	),
	'carmodelrank.com etc' => array(
		'.carmodelrank.com',// by Brianna Dunlord (briasmi at yahoo.com)
		'.cutestories.net',	// by Brianna Dunlord (briasmi at yahoo.com)
		'.sturducs.com',
		'.bestother.info',	// by Tim Rennei (TimRennei at yahoo.com), redirect to amaena.com (fake-antivirus)
		'.yaahooo.info',	// by Alice T. Horst (Alice.T.Horst at pookmail.com), redirect to activefreehost.com
	),
	// by Dr. Portillo or Eva Sabina Lopez Castell (aliacsandr85 at yahoo.com)
	'aliacsandr85 at yahoo.com' => array(
		'.xoomer.alice.it',			// "Free Web Hosting"
		'.freebloghost.org',		// "Free Web Hosting" by Dr.
		'.freeprohosting.org',		// "Free Web Hosting" by Dr.
		'.googlebot-welcome.org',	// "Free Web Hosting" by Dr.
		'.icesearch.org',			// "Free Web Hosting" by Eva
		'.phpfreehosting.org',		// "Free Web Hosting" by Dr.
		'.sashawww.info',			// "Free Web Hosting" by Dr.
		'.sashawww-vip-vip.org',	// "Free Web Hosting" by Dr.
		'.topadult10.org',			// "Free Web Hosting" by Eva
		'.xer-vam.org',				// "Ongline Catalog" by Dr.
		'.xxxse.info',				// "Free Web Hosting" by Eva
		'.vvsa.org',				// "Free Web Hosting" by Eva
		'.free-webhosts.com',		// "Free Web Hosting" by Free Webspace
	),
	// Gamble: Roulette, Casino, Poker, Keno, Craps, Baccarat
	'something_gamble' => array(
		'.atonlineroulette.com',			// by Blaise Johns
		'.atroulette.com',					// by Gino Sand
		'.betting-123.com',					// by Joana Caceres
		'.betting-i.biz',					// by Joaquina Angus
		'.casino-challenge.com',			// by Maren Camara
		'.casino-gambling-i.biz',			// by Giselle Nations
		'.casino-italian.com',				// by Holley Yan
		'.casino123.net',					// by Ta Baines
		'.casinohammamet.com',				// by Inger Barhorst
		'.casinoqz.com',					// by Berenice Snow
		'.casinos-777.net',					// by Iona Ayotte
		'.crapsok.com',						// by Devon Adair,
		'.dcasinoa.com',					// by August Hawkinson
		'.e-poker-4u.com',					// by Issac Leibowitz
		'.free-dvd-player.biz',				// by Rosario Kidd
		'.florida-lottery-01.com',			// by Romeo Dillon
		'.gaming-123.com',					// by Jennifer Byrne
		'.kenogo.com',						// by Adriane Bell
		'.mycaribbeanpoker.com',			// by Andy Mullis
		'.onbaccarat.com',					// by Kassandra Dunn
		'.online-experts.biz',				// by Liberty Helmick
		'.onlinepoker-123.com',				// by Andrea Feaster
		'.playpokeronline-123.com',			// by Don Lenard
		'.poker-123.com',					// by Mallory Patrick (Mallory_Patrick at marketing-support.info)
		'.texasholdem123.com',				// by Savion Lasseter
		'.texasholdem-777.com',				// by Savanna Lederman
		'.the-casino-directory-1715.us',	// by Thora Oldenburg
		'.the-craps-100.us',				// by Lorrine Ripley
		'.the-free-online-game-913.us',		// by Kanesha Clem
		'.the-free-poker-1798.us',			// by Elaina Witte
		'.the-las-vegas-gambling-939.us',	// by Jesusita Hageman
		'.the-online-game-poker-1185.us',	// by Merna Bey
		'.the-playing-black-jack.com',		// by Kristine Brinker
		'.the-poker-1082.us',				// by Kristofer Boldt
		'.the-rule-texas-hold-em-2496.us',	// by Melvina Stamper
		'.the-texas-strategy-holdem-1124.us',	// by Neda Frantz
		'.the-video-black-jack.com',		// by Jagger Godin
	),
	// Car / Home / Life / Health / Travel insurance, Loan finance, Mortgage refinance
	'something_insurance' => array(
	
		// 0-9
		'.0q.org',						// by Shamika Curtin, "Online car insurance information"
		'.1-bookmark.com',				// by Sonia Snyder, "Titan auto insurance information"
		'.1day-insurance.com',			// by Kelsie Strouse, "Car insurance costs"
		'.1upinof.com',					// by Diego Johnson, "Car insurance quote online uk resource"
		'.18wkcf.com',					// by Lexy Bohannon
		'.2001werm.org',				// by Raphael Rayburn
		'.2004heeparea1.org',			// by Dinorah Andrews
		'.21nt.net',					// by Jaida Estabrook
		'.3finfo.com',					// by Damian Pearsall
		'.3somes.org',					// by Mauro Tillett
		'.453531.com',					// by Kurt Flannery
		'.4freesay.com',				// by Eloy Jones
		'.5ssurvey.com',				// by Annamarie Kowalski
		'.8-f22.com',					// by Larraine Evers
		'.9q.org',						// by Ami Boynton

		// A
		'.a40infobahn.com',				// by Amit Nguyen
		'.a4h-squad.com',				// by Ross Locklear
		'.aac2000.org',					// by Randi Turner
		'.aaadvertisingjobs.com',		// by Luciano Frisbie
		'.acahost.com',					// by Milton Haberman
		'.aconspiracyofmountains.com',	// by Lovell Gaines
		'.acornwebdesign.co.uk',		// by Uriel Dorian
		'.activel-learning-site.com',	// by Mateo Conn
		'.ad-makers.com',				// by Shemeka Arsenault
		'.ada-information.org',			// by Josef Osullivan
		'.adelawarerefinance.com',		// by Particia Mcmillan, "Delaware refinance advisor"
		'.adult-personal-ads-e-site.info',	// by Nery Ainsworth
		'.aequityrefinance.com',		// by Jadwiga Duckworth
		'.aerovac-hotpress.com',		// by Trey Marlow
		'.agfbiosensors.com',			// by Lionel Dempsey
		'.ahomeloanrefinance.com',		// by Leslie Kinser
		'.affordablerealestate.net',	// by Season Otoole
		'.ahomerefinancingloans.com',	// by Julie Buck, "Home refinancing loans guide"
		'.ahouserefinance.com',			// by Young Alley
		'.akirasworld.com',				// by Piper Sullivan
		'.alderik-production.com',		// by Joan Stiles
		'.alltechdata.com',				// by Dom Laporte
		'.amconllc.com',				// by Syble Benjamin
		'.amobilehomerefinancing.com',	// by Clyfland Buckley, "Mobile home refinancing"
		'.amortgagerefinancepennsylvania.com',	// by Richard Battle, "Mortgage refinance pennsylvania articles"
		'.angelandcrown.net',			// by Claretta Najera
		'.ankoralina.com',				// by Eladia Demers
		'.antiquegoldmine.com',			// by Keena Marlow
		'.aquinosotros.com',			// by Nanci Prentice
		'.architectionale.com',			// by Wilbur Cornett
		'.arcreditcards.com',			// by Ecgbeorht Stokes, "Ameriquest credit cards articles"
		'.arefinancebadcredit.com',		// by Isaac Mejia, "Refinance bad credit"
		'.arefinancehome.com',			// by Duane Doran
		'.arefinancinghome.com',		// by Ike Laney
		'.athletic-shoes-e-shop.info',	// by Romelia Money
		'.auction-emall-site.info',		// by Dayle Denman
		'.auctions-site.info',			// by Cammie Chiu, "Online loan mortgage info"
		'.auto-buy-rite.com',			// by Asuncion Buie
		'.axxinet.net',					// by Roberta Gasper
		'.azimutservizi.com',			// by Ethelene Brook
		'.azstudies.org',				// by Bernardina Walden

		// B
		'.babtrek.com',					// by Simonette Mcbrayer
		'.babycujo.com',				// by Francisco Akers
		'.bakeddelights.com',			// by Dave Evenson
		'.bbcart.com',					// by Lucio Hamlin
		'.berlin-hotel-4u.com',			// by Grisel Tillotson
		'.best-digital-phone.us',		// by Meghann Crockett
		'.bjamusements.com',			// by Lurlene Butz
		'.blursgsu.com',				// by Weston Killian
		'.bookwide.net',				// by Tequila Zacharias
		'.boreholes.org',				// by Flora Reed
		'.breathingassociaiton.org',	// by Alfred Crayton
		'.birdingnh.com',				// by Donald Healy
		'.bisdragons.org',				// by Lupe Cassity
		'.blcschools.net',				// by Alycia Jolly
		'.bronte-foods.com',			// by Kary Pfeiffer
		'.buckscountyneighbors.org',	// by Maile Gaffney
		'.buffalofudge.com',			// by Mable Whisenhunt
		'.burlisonforcongress.com',		// by Luann King
		'.byairlinecreditcard.com',		// by Cali Stevenson, "Airline credit card search"
		'.byplatinumcard.com',			// by Pearl Cross, "Discover platinum card info"

		// C
		'.cabanes-web.com',				// by Vaughn Latham
		'.cardko.com',					// by Terris Cain, "Chase visa card search"
		'.cardpose.com',				// by Deerward Gross, "Gm mastercard articles"
		'.checaloya.com',				// by Susana Coburn
		'.calvarychapelrgvt.org',		// by Karan Kittle
		'.cameras-esite.info',			// by Karlee Frisch
		'.cancerkidsforum.org',			// by Samson Constantino
		'.ccchoices.org',				// by Kenia Cranford
		'.ccupca.org',					// by Evonne Serrano
		'.celebratemehome.com',			// by Soraya Tower
		'.centerfornourishingthefuture.org',	// by Elisa Wilt
		'.chelseaartmmuseum.org',		// by Kayla Vanhorn
		'.choose-shoes.net',			// by Geoffrey Setser
		'.churla.com',					// by Ollie Wolford
		'.circuithorns.co.uk',			// by Nathanial Halle
		'.clanbov.com',					// by Donell Hozier
		'.cnm-ok.org',					// by Thalia Moye
		'.coalitioncoalition.org',		// by Ned Macklin
		'.consoleaddicts.com',			// by Dorla Hoy
		'.counterclockwise.net',		// by Melynda Hartzell
		'.codypub.com',					// by Mercedes Coffman
		'.comedystore.net',				// by Floy Donald
		'.covsys.co.uk',				// by Abby Jacey
		'.cpusa364-northsacramento.com',	// by Dannette Lejeune
		'.craftybidders.com',			// by Dannie Lazo
		'.credit-card-finder.net',		// by Mellie Deherrera
		'.credit-cards-4u.info',		// by Antonina Hil, "Credit cards info"
		'.creditcardstot.com',			// by Bobby Alvarado, "Shell credit cards"
		'.ctwine.org',					// by Hailey Knox

		// D
		'.dazyation.com',				// by Louis Strasser
		'.deepfoam.org',				// by Ethelyn Southard
		'.debt-fixing.com',				// by Dagny Rickman
		'.dgmarketingwebdesign.com',	// by Nubia Lea
		'.domainadoption.com',			// by Breann Pappas
		'.diannbomkamp.com',			// by Russel Croteau
		'.dictionary-spanish.us',		// by Jacki Gilbreath
		'.dictionary-yahoo.us',			// by Lili Mitchem
		'.digital-camera-review-esite.info',	// by Milagros Jowers
		'.digital-cameras-esite.info',	// by Milan Jolin
		'.dnstechnet.net',				// by Tamera Oman
		'.drivenbydata.org',			// by Katherine Noyes
		'.dtmf.net',					// by Micki Slayton
		'.domainsfound.com',			// by Blossom Lively

		// E
		'.ecstacyabuse.net',			// by Alana Knight
		'.e-digital-camera-esite.info',	// by Romaine Cress
		'.eda-aahperd.org',				// by Kaliyah Hammonds
		'.eldorabusecenter.org',		// by Annabella Oneal
		'.emicorporation.com',			// by (Deangelo_Mikayla at marketing-support.info)
		'.encaponline.com',				// by Patrick Keel
		'.ez-shopping-online.com',		// by Gail Bartlett

		// F
		'.faithfulwordcf.com',			// by Bart Weeks
		'.fammedassoc.com',				// by Joshua Nelson
		'.federalministryoffinance.net',	// by Jeffry Mcmillan
		'.f00k.org',					// by Leslie Chapman
		'.foreignrealtions.org',		// by Krystal Hawley
		'.fortwebsite.org',				// by Kristina Motley
		'.fotofirstdigital.com',		// by Tad Whitfield
		'.foundationcommons.org',		// by Caryn Eskew
		'.fraisierest-alexandre.com',	// by Dwayne Douglas
		'.freaky-cheats.com',			// by Al Klein
		'.free--spyware.com',			// by Nikki Contreras
		'.french-home-finance-consultant.info',	// by Santana Melton
		'.fuel-tax-software-advisor.info',	// by Derrick Snyder

		// G
		'.gaintrafficfast.com',			// by Lila Meekins
		'.gaygain.org',					// by Shell Davila
		'.gcaaa.com',					// by Vallie Jaworski
		'.generalsysteme.com',			// by Cale Vogel
		'.generation4games.co.uk',		// by Sonya Graham
		'.german-dictionary.us',		// by Rex Daniel
		'.gilmerrec.com',				// by Leighann Guillory
		'.glenthuntly-athletics.com',	// by Julee Hair
		'.glorybaskets.com',			// by Lynette Lavelle
		'.goconstructionloan.com',		// by Willis Monahan
		'.gohireit.com',				// by Bertha Metzger
		'.godcenteredpeople.com',		// by Jaycee Coble

		// H
		'.healthinsuranceem.com',		// by Justin Munson
		'.hearthorizon.info',			// by Kory Session
		'.hegerindustrial.com',			// by Toni Wesley
		'.herzequip.com',				// by Princess Dunkle
		'.hglcms.org',					// by Gladwin Ng
		'.hipanoempresa.com',			// by Shannon Staub
		'.hitempfurnaces.com',			// by Rebbeca Jaeger
		'.horse-racing-result.com',		// by Rodney Reynolds
		'.hueckerfamily.com',			// by Hershel Sell

		// I
		'.ilove2win.com',				// by Lamont Dickerson
		'.ilruralassistgrp.org',		// by Moises Hauser
		'.imageonsolutions.com',		// by Porsche Dubois
		'.infoanddatacenter.com',		// by Eva Okelley
		'.islamfakta.org',				// by Goldie Boykin
		'.ithomemortgage.com',			// by Adelaide Towers
		'.iyoerg.com',					// by Madyson Gagliano

		// J
		'.jeffaxelsen.com',				// by Daphne William
		'.jeffreyf.net',				// by Vito Platt
		'.johnmartinsreality.com',		// by Pamela Larry
		'.johnsilvers.net',				// by Silver Battaglia

		// K
		'.kcgerbil.org',				// by Marisa Thayer
		'.kdc-phoenix.com',				// by Salma Shoulders
		'.kingscreditcard.com',			// by Sean Parsons, "Credit card info"
		'.kosove.org',					// by Darwin Schneider

		// L
		'.leading-digital-camera-esite.info',	// by Charles Moore, "Online home loan articles"
		'.letsgokayaking.net',			// by Winnie Adair
		'.libertycabs.com',				// by Adela Bonds
		'.liquor-store-cellar.info',	// by Hugh Pearson
		'.locomojo.net',				// by Marco Harmon
		'.lodatissimo.com',				// by Adrian Greeson
		'.lsawc.org',					// by Lara Han
		'.lycos-test.net',				// by Rigoberto Oakley

		// M
		'.macro-society.com',			// by Venessa Hodgson
		'.marthasflavorfest.com',		// by Ahmad Lau
		'.martin-rank.com',				// by Cathleen Crist
		'.maryandfrank.org',			// by Theodore Apodaca
		'.masterkwonhapkido.com',		// by Misty Graham
		'.maxrpm-demo.com',				// by Cristal Cho
		'.mechanomorphic.com',			// by Stanford Crow
		'.mepublishing.net',			// by Karly Fleenor
		'.meyerlanguageservices.co.uk',	// by Breana Kennedy
		'.metwahairports.com',			// by Nan Kitchen
		'.middle-eastnews.com',			// by Tybalt Altmann
		'.mikepelchy.com',				// by Sherly Pearson
		'.milpa.org',					// by Nelly Aguilera
		'.modayun.com',					// by Camilla Velasco
		'.moonstoneerp.com',			// by Garret Salmon
		'.morosozinho.com',				// by Lenore Tovar
		'.morphadox.com',				// by Hung Zielinski
		'.moscasenlared.com',			// by Tera Gant
		'.sdjavasig.com',				// by Gia Swisher
		'.mpeg-radio.com',				// by Sincere Beebe
		'.mrg-now-yes.com',				// by Sparkle Gallegos
		'.mtseniorcenter.org',			// by Frederic Ortega
		'.mysteryclips.com',			// by Edward Ashford

		// N
		'.naavs.org',					// by Yuridia Gandy
		'.naval-aviation.org',			// by Roselle Campo
		'.navigare-ischia.com',			// by Arielle Coons
		'.ncredc.org',					// by Brenda Nye
		'.neonmotorsports.com',			// by Giovanna Vue
		'.nf-ny.com',					// by Yadira Hibbard
		'.ngfdyqva.com',				// by Emiliano Samples
		'.nicozone.com',				// by Blaine Shell
		'.nmbusinessroundtable.org',	// by Chantel Mccourt
		'.npawny.org',					// by Willard Murphy
		'.nysdoed.org',					// by Elric Delgadillo
		'.nyswasteless.org',			// by Shaylee Moskowitz
		'.nytech-ir.com',				// by Adrien Beals

		// O
		'.oadmidwest.com',				// by Gavin Kaplan
		'.oarauto.com',					// by Susann Merriman
		'.onairmilescard.com',			// by Tomoko Hart, "Air miles card information"
		'.onbusinesscard.com',			// by Farris Lane, "Gm business card"
		'.oncashbackcreditcard.com',	// by Ida Willis, "Cash back credit card articles"
		'.onimagegoldcard.com',			// by Roxanna Sims, "Imagine gold mastercard information"
		'.online-pills-24x7.biz',		// by Aide Hallock
		'.online-shopping-site-24x7.info',	// by Stacy Ricketts
		'.onlinehomeloanrefinance.com',	// by Chaz Lynch
		'.onlinehomeloanfinancing.com',	// by Humbert Eldridge
		'.onunicarehealthinsurance.com',	// by  Lawerence Paredes
		'.otterbayweb.com',				// by Maxwell Irizarry

		// P
		'.painting-technique.us',		// by Bryanna Tooley
		'.pakamrcongress.com',			// by Bryce Summerville
		'.patabney.com',				// by Kailyn Slone
		'.parde.org',					// by Ellie Yates
		'.participatingprofiles.com',	// by Jaelynn Meacham
		'.partnershipconference.org',	// by Alla Floyd
		'.pet-stars.com',				// by Carmon Luevano
		'.planning-law.org',			// by Trista Holcombe
		'.ppawa.com',					// by Evonne Scarlett
		'.precisionfilters.net',		// by Faustina Fell

		// Q
		'.qacards.com',					// by Perye Estrada, "Citi visa cards"
		'.quick-debt-consolidation.net',	// by Lala Marte
		'.quicktvr.com',				// by Vernell Crenshaw

		// R
		'.radicalsolutions.org',		// by Reece Medlin
		'.randallburgos.com',			// by Bradly Villa
		'.rcassel.com',					// by Janiah Gallant
		'.rearchitect.org',				// by Marcus Gaudet
		'.rent-an-mba.com',				// by Valentina Mcdermott
		'.reprisenashville.com',		// by Hester Khan
		'.reptilemedia.com',			// by Alicia Patel
		'.resellers2000.com',			// by Dedra Kennedy
		'.reverse-billing.com',			// by Lazaro Gluck
		'.richcapaldi.com',				// by Kya Haggard
		'.richformissouri.com',			// by Alanna Elston
		'.robstraley.com',				// by Leida Bartell
		'.rollingprairie-candlecompany.com',	// by Leigha Aker
		'.rpgbbs.com',					// by Leonel Peart
		'.ruralbusinessonline.org',		// by Lynsey Watters
		'.ruwomenscenter.org',			// by Vince Mclemore
		'.ryanjowens.com',				// by Janine Smythe

		// S
		'.sagarmathatv.org',			// by Liam Funke
		'.sakyathubtenling.org',		// by Liane Falgout
		'.sandiegolawyer.net',			// by Linnie Sommervill
		'.sandishaven.com',				// by Lino Soloman
		'.scienkeen.com',				// by Liza Navarra
		'.seimenswestinghouse.com',		// by Teresa Benedetto
		'.severios.com',				// by Isa Steffen
		'.sexual-hot-girls.com',		// by Viviana Bolton
		'.shakespearelrc.com',			// by Luciana Weaver
		'.shashran.org',				// by Adriel Humphries
		'.shoes-shop.us',				// by Austen Higginbotham
		'.skagitvalleybassanglers.com',	// by Necole Thiele
		'.skinsciencesalon.com',		// by Nena Rook
		'.smartalternative.net',		// by Nicki Lariviere
		'.sml338.org',					// by Nickole Krol
		'.smogfee.com',					// by Sienna Kimble
		'.sneakers-e-shop.info',		// by Nikki Fye
		'.spacewavemedia.com',			// by Thanh Gast
		'.softkernel.com',				// by Nicol Hummer
		'.stjoanmerrillville.com',		// by Hunter Beckham
		'.strelinger.com',				// by Arron Highsmith
		'.striking-viking.com',			// by Kylie Endsley
		'.sunnydeception.org',			// by Amaya Llora
		'.sunzmicro.com',				// by Goddard Arreola
		'.sv-iabc.org',					// by Braden Buck
		'.sykotick.com',				// by Pierce Knecht

		// T
		'.tbody.net',					// by Ormond Roman
		'.the-pizzaman.com',			// by Mario Ramsey
		'.the-shoes.us',				// by Alejandro Gaffney
		'.theborneocompany.com',		// by Bryanna Tooley
		'.theflashchannel.com',			// by Terrilyn Tam, "Loan financing info"
		'.thehomeschool.net',			// by September Concepcio
		'.thenewlywed.com',				// by Allegra Marra
		'.tigerspice.com',				// by Denis Mosser
		'.tnaa.net',					// by Jasmine Andress
		'.top-finance-sites.com',		// by Maryann Doud
		'.tradereport.org',				// by Bettie Sisk
		'.transmodeling.com',			// by Martine Button
		'.travel-01.net',				// by Jay Kim, "Refinance on line"
		'.tsaoc.com',					// by Heriberto Mcfall
		'.tsunamidinner.com',			// by Nannie Richey

		// U
		'.uhsaaa.com',					// by Risa Herbert
		'.ultradeepfield.org',			// by Bobby Ragland
		'.umkclaw.info',				// by Cammy Kern
		'.unitedsafetycontainer.com',	// by Shreya Heckendora
		'.usa-wolf.com',				// by Jacklyn Morrill
		'.usjobfair.com',				// by Lorina Burchette

		// V
		'.vacancesalouer.com',			// by Loris Bergquist
		'.vagents.com',					// by Lorna Beaudette, "Refinancing home loan info"
		'.valleylibertarians.org',		// by Lena Massengale
		'.vanderbiltevents.com',		// by Gannon Krueger
		'.vanwallree.com',				// by Michelina Donahue
		'.vcertificates.com',			// by Hyun Lamp
		'.vonormytexas.us',				// by Suzette Waymire

		// W
		'.washingtondc-areahomes.net',	// by Ailene Broome
		'.web-hosting-forum.net',		// by Deedra Breen, "Mortgage information"
		'.wolsaoh.org',					// by Daniela English
		'.worldpropertycatalog.com',	// by Aray Baxter

		// Y
		'.yankee-merchants.com',		// by Jackson Hinojosa
		'.yourbeachhouse.com',			// by Dedrian Ryals
		'.yourdomainsource.com',		// by Deems Weingarten

		// Z
		'.zkashan.com',					// by Evan Light
		'.zockclock.com',				// by Dorothea Guthrie
	),
	// Drugs / Pills / Diet
	'something_drugs' => array(
		'.adult-dvd-rental-top-shop.info',	// by Gregoria Keating
		'.abdelghani-shady.com',		// by Elly Alton
		'.bangbangfilm.com',			// by Davin Chou
		'.centroantequera.com',			// by Keon Kwiatkowski
		'.champagne-cellar.info',		// by Kandis Rizzo
		'.chix0r.org',					// by Christoper Baird
		'.discout-watches-deals.info',	// by Taunya Limon, Insurance -> Drugs?
		'.fantasticbooks-shop.com',		// by Kermit Ashley
		'.fast-cash-01.com',			// by Edgar Oliver
		'.ficeb.info',					// by Vaughn Jacobson, "Phentermine news"
		'.fn-nato.com',					// by Donny Dunlap
		'.gqyinran.com',				// by Alejandro Parks
		'.juris-net.com',				// by Rachelle Bravo
		'.leftpencey.com',				// by Aileen Ashby
		'.miamicaribbeancarnival.com',	// by Herminia Barrios
		'.nike-shoes-e-shop.info',		// by Machelle Groce, "Phentermine"
		'.palaceroyale.net',			// by Brycen Stebbins
		'.pocket-watches-deals.info',	// by Dorinda Stromberg
		'.regresiones.net',				// by Lauralee Smtih, "Online phentermine updates"
		'.yukissushi.com',				// by Donell Hozier
	),
	'something_others' => array(
		'.consulting-cu.com',		// by Albina Rauch, 404 not found
		'.dvd-rentals-top-shop.info',	// by Lashunda Pettway, 404 not found
	),
	// by Cortez Shinn (info at goorkkjsaka.info), or Rico Laplant (info at nnjdksfornms.info)
	'Cortez and family' => array(
		'.dronadaarsujf.info',	// by Cortez
		'.fromnananaref.info',	// by Cortez
		'.goorkkjsaka.info',	// by Cortez
		'.jkdfjjkkdfe.info',	// by Rico
		'.jkllloldkjsa.info',	// by Cortez
		'.nnjdksfornms.info',	// by Rico
		'.mcmdkkksaoka.info',	// by Cortez
		'.srattaragfon.info',	// by Cortez
		'.yreifnnonoom.info',	// by Rico
		'.zjajjsvgeuds.info',	// by Cortez
	),
	// by Harvey Pry (admin at ematuranza.com)
	'Harvey Pry' => array(
		'.ancorlontano.com',
		'.dentroallago.com',
		'.digiovinezza.com',
		'.ematuranza.com',
		'.ilfango.com',
		'.nullarimane.com',
		'.questaimmensa.com',
		'.tentailvolo.com',
		'.unatenerezza.com',
		'.volgondilettose.com',
	),
	// by Cornelius Boyers (admin at edeuj84.info)
	'Cornelius Boyers' => array(
		'.bid99df.info',
		'.bj498uf.info',
		'.edeuj84.info',
		'.f4mfid.info',
		'.g4vf03a.info',
		'.j09j4r.info',
		'.jv4r8hv.info',
		'.k43sd3.info',
		'.k4r84d.info',
		'.k4rvda.info',
		'.k4v0df.info',
		'.k903os.info',
		'.k9df93d.info',
		'.kv94fd.info',
		'.ksjs93.info',
		'.l0ks03.info',
		'.l9u3jc.info',
		'.lv043a.info',
		'.nh94h9.info',
		'.m94r9d.info',
		'.s87fvd.info',
		'.v3k0d.info',
		'.v4r8j4.info',
		'.vf044s.info',
		'.vj49rs.info',
		'.vk498j.info',
		'.u03jow.info',
	),
	'Nikhil and Brian' => array(
		'.ihfjeswouigf.info',		// by Brian Dieckman (info at iudndjsdhgas.info), / was not found
		'.iudndjsdhgas.info',		// by Brian Dieckman (info at iudndjsdhgas.info), / was not found
		'.iufbsehxrtcd.info',		// by Brian Dieckman (info at iudndjsdhgas.info), / was not found
		'.jiatdbdisut.info',		// by Brian Dieckman (info at iudndjsdhgas.info), / was not found
		'.jkfierwoundhw.info',		// by Brian Dieckman (info at iudndjsdhgas.info), / was not found
		'.kfjeoutweh.info',			// by Brian Dieckman (info at iudndjsdhgas.info), / was not found
		'.ncjsdhjahsjendl.info',	// by Brian Dieckman (info at iudndjsdhgas.info), / was not found
		'.oudjskdwibfm.info',		// by Brian Dieckman (info at iudndjsdhgas.info), / was not found
		'.cnewuhkqnfke.info',		// by Nikhil Swafford (info at jhcjdnbkrfo.info), / was not found
		'.itxbsjacun.info',			// by Nikhil Swafford (info at jhcjdnbkrfo.info), / was not found
		'.jahvjrijvv.info',			// by Nikhil Swafford (info at jikpbtjiougje.info), / was not found
		'.jhcjdnbkrfo.info',		// by Nikhil Swafford (info at jhcjdnbkrfo.info), / was not found
		'.najedncdcounrd.info',		// by Nikhil Swafford (info at jhcjdnbkrfo.info), / was not found
		'.mcsjjaouvd.info',			// by Nikhil Swafford (info at jhcjdnbkrfo.info), / was not found
		'.oujvjfdndl.info',			// by Nikhil Swafford (info at jhcjdnbkrfo.info), / was not found
		'.uodncnewnncds.info',		// by Nikhil Swafford (info at jhcjdnbkrfo.info), / was not found
		'.jikpbtjiougje.info',		// by Julio Mccaughey (info at jikpbtjiougje.info), / was not found
		'.cijkalvcjirem.info',		// by Gerardo Figueiredo (info at jikpbtjiougje.info), / was not found
		'.nkcjfkvnvpow.info',		// by Gerardo Figueiredo (info at jikpbtjiougje.info), / was not found
		'.nmiiamfoujvnme.info',		// by Gerardo Figueiredo (info at jikpbtjiougje.info), / was not found
		'.nxuwnkajgufvl.info',		// by Gerardo Figueiredo (info at jikpbtjiougje.info), / was not found
		'.mkjajkfoejvnm.info',		// by Gerardo Figueiredo (info at jikpbtjiougje.info), / was not found
	),
	'something_noapp' => array(
		'.auctioncarslisting.com',	// "No application configured at this url." by John Davis
		'.buy-cheap-hardware.com',	// "No application configured at this url." by Tim Morison (domains at sunex.ru)
		'.carsgarage.net',			// "No application configured at this url." by Zonen Herms, and Jimmy Todessky (seomate at gmail.com)
		'.digitshopping.net',		// "No application configured at this url." by Zonen Herms, and Jimmy Todessky (seomate at gmail.com)
		'.your-insurance.biz',		// "No application configured at this url." by Jimmy Todessky (seomate at gmail.com)
	),
	// by Henry Ford (wealth777 at gmail.com)
	'Henry Ford' => array(
		'.brutal-forced.com',
		'.library-bdsm.com',
		'.rape-fantasy.us',
	),
	// by Croesus International Inc. (olex at okhei.net)
	'Croesus International Inc.' => array(
		'.purerotica.com',
		'.richsex.com',
		'.servik.net',
		'.withsex.com',
	),
	'dreamteammoney.com' => array(
		'.dreamteammoney.com',	// dtmurl.com related
		'.dtmurl.com',			// by dreamteammoney.com, redirection service
	),
	// KLIK VIP Search and familiy
	'KLIK VIP Search' => array(
		'.cheepmed.org',		// "KLIK VIP Search" by petro (petrotsap1 at gmail.com)
		'.fastearning.net',		// "KlikVIPsearch.com" by Matthew  Parry        (fastearning at mail.ru)
		'.klikvipsearch.com',	// "KLIKVIPSEARCH.COM" by Adrian Monterra (support at searchservices.info)
		'.looked-for.info',		// "MFeed Search" now, by johnson (edu2006alabama at hotmail.com)
		'.mnepoxuy.info',		// "KlikVIPsearch.com" by DEREK MIYAMOTO (grosmeba at ukr.net)
		'.searchservices.info',	// 403 Forbidden now, by Adrian Monterra (support at searchservices.info)
		'.visabiz.net',			// "Visabiz-Katalog-Home" now, by Natalja Estrina (m.estrin at post.skynet.lt)
	),
	// by Andrey Kozlov (vasyapupkin78 at bk.ru)
	'vasyapupkin78 at bk.ru' => array(
		'.antivirus1.info',
		'.antivirus2.info',
	),
	// by Ashiksh Wasam (wasam at vangers.net)
	'wasam at vangers.net' => array(
		'.blogduet.org',
		'.carelf.info',
		'.cspell.org',
		'.dspark.org',
		'.dtonic.org',
		'.mcharm.info',
		'.mslook.info',
		'.uzing.org',
	),
	// by Thomas Vaals (tvaals at vangers.net)
	'tvaals at vangers.net' => array(
		'.cheapns.org',
		'.my-top.net',
		'.sfind.net',
		'.sspot.net',
		'.suvfind.info',
	),
	// by Kadil Kasekwam (kadilk at vangers.net)
	'kadilk at vangers.net' => array(
		'.allbar.info',
		'.allersearch.org',
		'.dynall.org',
		'.fastopia.org',
		'.rblast.org',
		'.rette.org',
		'.suvlook.org',
	),
	// by Kasturba Nagari (kasturba at vangers.net)
	'kasturba at vangers.net' => array(
		'.finddesk.org',
		'.rcatalog.org',
		'.sbitzone.org',
	),
	// by Bipik Joshu (bipik at vangers.net)
	'bipik at vangers.net' => array(
		'.e2007.info',
		'.cmoss.info',
	),
	'marion at vangers.net' => array(
		'.trumber.com',	// by Mariano Ciaramolo (marion at vangers.net)
	),
	// by Jonn Gardens (admin at SearchHealtAdvCorpGb.com -- no such domain)
	'SearchHealtAdvCorpGb.com' => array(
		'.canadianmedsworld.info',
		'.tabsdrugstore.info',
		'.tabsstore.info',
		'.topcholesterol.info',
	),
	'be.cx' => array(
		'.be.cx',
		'.ca.cx',
	),
	// by John  Brown (john780321 at yahoo.com)
	'john780321 at yahoo.com' => array(
		'.bestdiscountpharmacy.biz',	// 2007-01-27, 61.144.122.45
		'.drugs4all.us',				// 2007-03-09, 202.67.150.250
	),
	// by Treman Eagles (tremagl.freet at gmail.com), redirect to searchadv.com
	'tremagl.freet at gmail.com' => array(
		'.bertela.info',
		'.forblis.info',
		'.frenallo.info',
		'.goyahoo.info',
		'.herbak.info',
		'.kiokast.info',
		'.nerenok.info',
		'.pestgets.info',
		'.snukker.info',
		'.thegetspons.info',
	),
	// by 2xxc (2xxc at 2xxc.net), 404 not found
	'2xxc at 2xxc.net' => array(
		'.bobop.info',
		'.kwwwe.info',
		'.piikz.info',
		'.moosm.info',
		'.vvvw.info',
	),
	// support at 51g.net, iframe www.lovetw.webnow.biz
	'support at 51g.net' => array(
		'.ftplin.com',		// by Yongchun Liao
		'.jplin.com',		// by Yongchun Liao
		'.jplineage.com',	// by Yongchun Liao
		'.jplingood.com',
		'.linenew.com',
		'.lyftp.com',		// Yongchun Liao (weboy at 51g.net)
		'.yzlin.com',
	),
	// by Betty J. Pelletier (Betty.J.Pelletier at pookmail.com)
	'Betty.J.Pelletier at pookmail.com' => array(
		'.1111mb.com',
		'.2sex18.com',
		'.69porn1.com',
	),
	'ECTechnology' => array(
		'.atmouse.co.kr',		// by EG gisul (kpgak at hanmail.net)
		'.auto-mouse.com',		// "Copyright �$ 2007 www.automouse.jp" by ECTechnology (help at atmouse.co.kr)
		'.automouse.jp',
	),
	'lineinfo-jp.com' => array(
		'.japangame1.com',		// by lyqz at 21cn.com
		'.lineinfo-jp.com',		// by lyqz at 21cn.com, www.lineinfo-jp.com is 61.139.126.10
		'.livedoor1.com',		// by lyqz at 21cn.com
		'.ragnarokonline1.com',	// by lyqz at 21cn.com
		'.zhangweijp.com',		// by qiu wang hao (qq.lilac at eyou.com), *.exe, hidden JavaScripts, the same IP of www.lineinfo-jp.com
	),
	// by yangjianhe (kingrou at hotmail.com)
	'kingrou at hotmail.com' => array(
		'.youshini.com',		// Two iframe to 453787.com's *.exe
		'.453787.com',
	),
	'anpaul541000 at 163.com' => array(
		'.cetname.com',			// by su qiuqing
		'.jpgamer.net',			// by su qiuqing
		'.jpplay.net',			// by su qiuqing, iframe www.lovetw.webnow.biz
		'.lovejptt.com',		// by su qiuqing
		'.pangzigame.com',		// by qiuqingshan
		'.playncsoft.net',		// by su qiuqing
	),
	// by guo yong (abc00613 at 163.com), seems not used now
	'abc00613 at 163.com' => array(
		'.avtw1068.com',
		'.dj5566.org',	// by yongchao li (abc00613 at 163.com)
		'.djkkk66990.com',
	),
	// by Perez Thomas (thomas.jsp at libertysurf.fr)
	'thomas.jsp at libertysurf.fr' => array(
		'.cmonfofo.com',
		'.discutbb.com',
	),
	// by Dorothy D. Adams (Dorothy.D.Adams at mailinator.com)
	'Dorothy.D.Adams at mailinator.com' => array(
		'.preca.info',
		'.skiaggi.info',
		'.scrianal.info',
		'.tageom.info',
	),
	// by Hayato Hikari (hikari at t-dm.co.jp)
	'inpros.net' => array(
		'.inpros.biz',			// by Hayato Hikari (hikari at t-dm.co.jp), redirect to inpros.net
		'.inpros.net',			// by Hayato Hikari (hikari at t-dm.co.jp)
		'.gametradeonline.jp',	// by Hayato Hikari, RMT
	),
	// by zhenfei chen (szczffhh_sso at 21cn.net)
	'szczffhh_sso at 21cn.net' => array(
		'.ec51.com',
		'.ec51.net',
	),
	// by Mahat Ashat (abbevillelaties at yahoo.fr), JavaScript may mocks "ACCOUNT TERMINATE", or "Domain deleted Reason: ABUSE" but ...
	'abbevillelaties at yahoo.fr etc' => array(
		'.ringtones-rate.com',	
		'.ringtones-dir.net',	// by Alex Maklayt (maklayt at ringtones-dir.net), hidden JavaScript
		'.special-ringtones.net',
	),
	// by Brzezinski Bartosz (gibson at skysquad.net), redirect to find.fm
	'gibson or gibs0n at skysquad.net' => array(
		'.1sgsc.info',
		'.3h4r89h.info',
		'.3v44dd.info',
		'.6rfuh6.info',
		'.84hd8.info',
		'.94bui89.info',
		'.agysb3.info',
		'.asdjhs.info',
		'.bcvnrth.info',
		'.bheb4r.info',
		'.bhiuno.info',
		'.biug7g.info',
		'.bjb5f4.info',
		'.bob8g7g.info',
		'.br89bdd.info',
		'.bsa3h.info',
		'.bsieb8.info',
		'.basbiubf.info',
		'.bobwwfs2.info',
		'.ciuv9t.info',
		'.dbmdx4.info',
		'.dbrjms.info',
		'.dbtcm.info',
		'.dff9ghu.info',
		'.dfshbb.info',
		'.dgd4ffdh.info',
		'.dh3ge.info',
		'.duc86jh.info',
		'.ergth45.info',
		'.f78bf7ffb.info',
		'.gbdfbo4.info',
		'.ger45.info',
		'.gnvnrrg.info',
		'.h47he7.info',
		'.h488hbd4.info',
		'.hd72b94.info',
		'.he74b7.info',
		'.hfujfnr.info',
		'.husdhd42.info',
		'.hbwje.info',
		'.itg87gji.info',
		'.iugiougiuh.info',
		'.jhd4f4aa.info',
		'.jshd73.info',
		'.krhpgd.info',
		'.lyihjn.info',
		'.nfyjnfj.info',
		'.oihbv.info',
		'.os44fvs.info',
		'.sdfsd3.info',
		'.sdiug4.info',
		'.sdkufhh.info',
		'.sdugb4f.info',
		'.skdbf.info',
		'.sipiv78.info',
		'.sudbfb.info',
		'.tymbbmy.info',
		'.uilhjk.info',
		'.vi87vub.info',
		'.vfuyf87f.info',
		'.viyvvj877.info',
		'.w7fc8eu.info',
		'.wefg43g.info',
		'.xbrch78e.info',
		'.ywsfu.info',
		'.zxcbiv.info',
	),
	// by Marek Luto Marek Luto (info at infooracle.com)
	'info at infooracle.com' => array(
		'.abofios.info',
		'.amlekfn.info',
		'.amlkdoie.info',
		'.amkslewq.info',
		'.alemfu.info',
		'.aloweks1.info',
		'.alposd3.info',
		'.bamhpb.info',
		'.bhjkb.info',
		'.bjqnj.info',
		'.cvcxcbhpr.info',
		'.czoypaiat.info',
		'.dbpmgc.info',
		'.dgvogrxs.info',
		'.dldksf.info',
		'.dlor6za.com',
		'.dmkoiew.info',
		'.eewrefr.info',
		'.eladne.info',
		'.elksem.info',
		'.elwpod.info',
		'.emlwkdnr.info',
		'.esgmyqk.info',
		'.fauqv.info',
		'.fgxkgy.info',
		'.fhryns.info',
		'.fj38n4g.info',
		'.fjnesal.info',
		'.fmkfoe.info',
		'.fqkcfldtr.info',
		'.fwcigpdwz.info',
		'.fyhik.info',
		'.glrkje.info',
		'.gwkslfq.info',
		'.gwjracvh.info',
		'.hihopepe.info',
		'.hwlyggbkw.info',
		'.hmwbfw.info',
		'.hthyeb.info',
		'.iaofkyaw.info',
		'.uldkxuiw.info',
		'.is7c6w4.info',
		'.ivuddhdk.info',
		'.jgfndjem.info',
		'.jgmdlek.info',
		'.jkrnvmpad.info',
		'.jqujn.info',
		'.jvgmmba.info',
		'.kbaur.info',
		'.kgjindptv.info',
		'.kleo7s9.info',
		'.lezfgam.info',
		'.lfaasy.info',
		'.ljpdjki.info',
		'.lmnpis.info',
		'.lpzcu2f.info',
		'.lrptn.info',
		'.lursqt.info',
		'.mgkabviil.info',
		'.mhtknjyt.info',
		'.mksuuku.info',
		'.mkyky.info',
		'.mloaisn.com',
		'.mlsiknd.info',
		'.mthqz.info',
		'.nnooq.info',
		'.nohhylvc.info',
		'.nuprndsye.info',
		'.nsoelam.info',
		'.nykobczv.info',
		'.nzuhli.info',
		'.odyqzgylr.info',
		'.oidiau.info',
		'.oitzkw.info',
		'.okdmrpz.info',
		'.ooinziti.info',
		'.ortqr.info',
		'.osmkpnekv.info',
		'.ozkzfih.info',
		'.p3ix8wc.com',
		'.piwyt.info',
		'.pfkijrm.info',
		'.pjktcragi.info',
		'.pleoz.info',
		'.plvqm73.info',
		'.pqyrem.info',
		'.qipgqd.info',
		'.qlewixu.com',
		'.qmlskme.info',
		'.qtuff.info',
		'.quoga.info',
		'.quqz.info',
		'.qzxuw.info',
		'.rcaidegp.info',
		'.rlkmdi.info',
		'.rnsoiov.info',
		'.rnwlams.info',
		'.rprgkgqld.info',
		'.rubqvxrn.info',
		'.spqxstl.info',
		'.syckoqjql.info',
		'.tbirb.info',
		'.thalc34.info',
		'.tiabq.info',
		'.tszzpjr.info',
		'.tyjdyn.info',
		'.twgugpns.info',
		'.uaezrqp.info',
		'.udlkasu.info',
		'.uejncyf.info',
		'.ukvflb.info',
		'.ugsuv.info',
		'.ukhgpcp.info',
		'.urprzn.info',
		'.uuhememkw.info',
		'.yalc7en.info',
		'.ybuid.info',
		'.yhdkgfob.info',
		'.ymenq.info',
		'.ynlyb.info',
		'.vieatlubk.info',
		'.vltcaho.info',
		'.wlamsiek.info',
		'.wlerp.info',
		'.wlmtshzi.info',
		'.wmlkams.info',
		'.wprqd.info',
		'.wpyspszi.info',
		'.xdscc.info',
		'.xdvy.info',
		'.xeypku.info',
		'.xsrxh.info',
		'.xwjyrpfe.info',
		'.yxcqw.info',
		'.zhbktrh.info',
		'.zspepn.info',
		'.zsxtz.info',
	),
	'survi at poczta.fm and smiles' => array(
		'.pperd.info',		// "main site :>" by Domagala Andrzej (survi at poczta.fm)
		'.ppert.info',
		'.pperta.info',
		'.pperts.info',
		'.pprtuis.info',
		'.13iuey.info',		// ":>"
		'.13jkhs.info',
		'.13lksa.info',
		'.13rxtx.info',
		'.13slkd.info',
		'.13zaer.info',
	),
	// by Jan Kalka (admin at esemeski.com)
	'admin at esemeski.com' => array(
		'.kxils.info',
		'.kuaph.info',
		'.lncdc.info',
		'.lsqpd.info',
		'.mczed.info',
		'.npous.info',
		'.obgju.info',
	),
	// by LiquidNet Ltd. Redirect to www.japan.jp
	'fateback.com' => array(
		'.bebto.com',
		'.fateback.com',
	),
	'agava.com' => array(	// by domains at agava.com
		'.h18.ru',
		'.hut1.ru',
	),
	'wlmx009 at hotmail.com' => array(
		'.123lineage.com',
		'.ff11-info.com',
		'.lastlineage.com',
		'.lineage2-ol.com',
		'.lineage2006.com',
		'.lineagefirst.com',
	),
	'zettahost.com' => array(
		'.atspace.biz',			// sales at zettahost.com
		'.atspace.com',			// abuse at zettahost.com
		'.atspace.name',		// NS atspace.com
	),
	'hlq9814 at 163.com' => array(
		'.ragnarox.mobi',		// by lin bao (hlq9814 at 163.com), *.exe download
		'.rokonline-jp.com',	// by hang long (hlq9814 at 163.com)
	),
	'zettahost.com' => array(
		'.awardspace.com',	// by abuse at awardspace.com, no DirectoryIndex, 70.86.228.149
		'.awardspace.us',	// by Dimitar Dimitrov (sales at zettahost.com), 70.86.228.149
	),
	'77ch.jp' => array(
		'.77ch.jp',
		'.gamorimori.net',	// by ryo takami (infomation at 77ch.jp)
	),
	'serchportal at mail.ru' => array(	// by Namu Adin (serchportal at mail.ru)
		'.getse.info',
		'.knopki.info',
		'.zhopki.info',
	),
	'soniqhost.com' => array(	// by Stanley Gutowski (support at soniqhost.com)
		'*.444mb.com',		// Free hosting
		'urlnip.com',		// Redirection
	),
	'www.ru' => array(		// by Angela (abuse at www.ru)
		'.1fasttimesatnau.info',
		'.1freecybersex.info',
		'.1freexxxcomics.info',
		'.1fuckingmywife.info',
		'.1pornpreview.info',
		'www.ru',					// by (service at demos.ru), redirection
	),
	'65.90.250.10' => array(	// IP seems 65.90.250.10
		'.adultschat.info',
		'.livecamonline.info',
		'.webcam4u.info',
		'.younghot.info',
	),
	'hostorgadmin at googlemail.com' => array(	// Byethost Internet Ltd. (hostorgadmin at googlemail.com)
		'.1sthost.org',
		'.22web.net',
		'.4sql.net',
		'.php0h.com',
		'.php1h.com',
		'.php2h.com',		// by Andrew Millar (asmillar at sir-millar.com), ns also *.byet.org
		'.phpnet.us',
		'.prophp.org',		// pro-php.org, 
		'.byethost.com',
		//'byethost1.com'
		'.byethost2.com',
		'.byethost3.com',
		'.byethost4.com',
		'.byethost5.com',
		'.byethost6.com',
		'.byethost7.com',
		'.byethost8.com',
		'.byethost9.com',
		'.byethost10.com',
		'.byethost11.com',
		'.byethost12.com',
		'.byethost13.com',
		'.byethost14.com',
		'.byethost15.com',
		'.byethost16.com',
		'.byethost17.com',
		'.byethost18.com',
		//'*.byethost19.com',	// by Wan-Fu China, Ltd. (business at wanfuchina.com)
		'.ifastnet.com',
		'.kwikphp.com',
		'.mega-file.net',
		'.my-php.net',
		'.my-place.us',
		'.my-webs.org',
		'.netfast.org',
		'.prohosts.org',
		'.sprinterweb.net',
		'.swiftphp.com',
		'.xlphp.net',
	),
	'lemnaru ionut' => array(
		// by lemnaru ionut (webmaster at bestgirlssex.info), ns *.hostgator.com
		'.analmoviesite.info',
		'.bestgirlssex.info',
		'.boxvagina.info',
		'.cyberlivegirls.info',
		'.hotredgirls.info',
		'.forsexlove.info',
		'.hotnudezone.info',
		'.hotredpussy.info',
		'.lesbians-live.info',
		'.lesbians-on-cam.info',
		'.onlinegirlssite.info',
		'.sexloveonline.info',
		'.teensexcard.info',
		'.teensexdirect.info',
		'.topnudesite.info',
		'.vaginafree.info',	
		'.webcam-show.info',
		'.webcamshow.info',
		'.youngsexchat.info',
		'.yourcumshot.info',	
	),
	'lustiq at p5com.com' => array(
		'.wonkalook.com',		// ns *.willywonka.co.in, 85.255.117.226
		'.willywonka.co.in',	// by Nick Priest (lustiq at p5com.com), 85.255.117.226
	),
	

	// C-2: Lonely domains (buddies not found yet)
	'.0721-4404.com',
	'.0nline-porno.info',	// by Timyr (timyr at narod.ru)
	'.1-click-clipart.com',	// by Big Resources, Inc. (hostmaster at bigresources.com)
	'.122mb.com',			// by Alexandru Rusnac (alrusnac at hotmail.com)
	'.180haifa.com',		// by Andrey Letov (andreyletov at yahoo.com)
	'.19cellar.info',		// by Eduardo Guro (boomouse at gmail.com)
	'.1gb.cc',				// by Hakan us (hakanus at mail.com)
	'.1gb.in',				// by Sergius Mixman (lancelot.denis at gmail.com)
	'.0annie.info',
	'.3-hosting.net',
	'.50webs.com',			// by LiquidNet Ltd. (support at propersupport.com), redirect to mpage.jp
	'.6i6.de',
	'.advancediet.com',		// by Shonta Mojica (hostadmin at advancediet.com)
	'.adultpersonalsclubs.com',	// by Peter (vaspet34 at yahoo.com)
	'.alfanetwork.info',	// by dante (dantequick at gmail.com)
	'.allworlddirect.info',	// Forbidden
	'.amoreitsex.com',
	'.areaseo.com',			// by Antony Carpito (xcentr at lycos.com)
	'.baurish.info',
	'.bestop.name',
	'.bloggerblast.com',	// by B. Kadrie (domains at starwhitehosting.com)
	'.businessplace.biz',	// by Grenchenko Ivan Petrovich (eurogogi at yandex.ru)
	'.covertarena.co.uk',	// by Wayne Huxtable
	'.d999.info',			// by Peter Vayner (peter.vayner at inbox.ru)
	'.dlekei.info',			// by Maxima Bucaro (webmaster at tts2f.info)
	'.drug-shop.us',			// by Alexandr (matrixpro at mail.ru)
	'.drugcleansing.net',	// by David Zajwzran (stocking.club at gmail.com)
	'.drugs-usa.info',		// by Edward SanFilippo (Edward.SanFilippo at gmail.com), redirect to activefreehost.com
	'.easyshopusa.com',		// by riter (riter at nm.ru)
	'.edu.ph',				// "philippine network foundation inc"
	'.ex-web.net',			// RMT by ex co,ltd (rmt at ex-web.net)
	'.extracheapmeds.com',	// "freexxxmovies" by John Smith (89 at bite.to)
	'.fantasy-handjob-ra.com',	// by Hose Pedro (hosepedro at gmail.com)
	'.fastppc.info',			// by peter conor (fastppc at msn.com)
	'*.filthserver.com',	// sales at onlinemarketingservices.biz
	'.find-stuff.org',		// by Alice Freedman (admin at ip-labs.ru), / 404 Not Found
	'.forum24.se',
	'.free-finding.com',	// by Ny hom (nyhom at yahoo.com)
	'.free-rx.net',			// by Neo-x (neo-xxl at yandex.ru), redirect to activefreehost.com
	'.freehostia.com',
	'.goldcoastonlinetutoring.com',	// by Robert Tanenbaum (buildbt at lycos.com)
	'.game4enjoy.net',		// by huang jinglong (fenlin231 at sina.com)
	'.ganecity.com',		// by shao tian (huangjinqiang at sina.com)
	'.gm-exchange.jp',		// RMT
	'.good1688.com',		// by Wen Chien Lunz (wzk1219 at yahoo.com.tw), one of them frame to , and whoop.to
	'.google-pharmacy.com',		// by alex (mdisign1997 at yahoo.com), hiding with urlx.org etc
	'.google-yahoo-msn.org',	// by Equipe Tecnica Ajato (rambap at yandex.ru)
	'.greatsexdate.com',	// by Andreas Crablo (crablo at hotmail.com)
	'.guild-wars-online.com',	// by Fuzhou Tianmeng Touzi Zixun Co.,Ltd (welkin at skyunion.com)
	'.happyhost.org',		// by Paul Zamnov (paul at zamnov.be)
	'.hloris.com',			// by Wilshi Jamil (ixisus at front.ru)
	'.honda168.net',		// by tan tianfu (xueyihua at gmail.com), seems not used now
	'.hostuju.cz',			// ns banan.cz, banan.it
	'.hot4buy.org',			// by Hot Maker (jot at hot4buy.org)
	'.hotnetinfo.info',		// by Lisa Edwards (lisaedwards at ledw.th)
	'.hotscriptonline.info',	// by Psy Search (admin at psysearch.com)
	'.incbuy.info',			// by Diego T. Murphy (Diego.T.Murphy at incbuy.info)
	'.infocart.jp',			// Trying to earn money easily by selling 'earn-money-easiliy' tips
	'.infradoc.com',
	'.investorvillage.com',	// by natalija puchkova (internet at internet.lv)
	'.ismarket.com',			// Google-hiding. intercage.com related IP
	'.italialiveonline.info',	// by Silvio Cataloni (segooglemsn at yahoo.com), redirect to activefreehost.com
	'.italy-search.org',		// by Alex Yablin (zaharov-alex at yandex.ru)
	'.itsexosit.net',
	'.itxxxit.net',
	'.jimmys21.com',		// by Klen Kudryavii (telvid at shaw.ca)
	'.jimka-mmsa.com',		// by Alex Covax (c0vax at mail.ru), seems not used yet
	'.joynu.com',			// by lei wang (93065 at qq.com), hidden JavaScript
	'.kingtools.de',
	'.library-blogs.net',	// by Peter Scott (pscontent at gmail.com)
	'.ls.la',				// by Milton McLellan (McLellanMilton at yahoo.com)
	'.manseekingwomanx.com',	// by Bill Peterson (coccooc at fastmail.fm)
	'.micro36.com',			// by Teng Zhang (web at 6jy.com), content from lineage.jp, post with 'lineage1bbs.com'
	'.milfxxxpass.com',		// by Morozov Pavlik (rulets at gmail.com)
	'.myfgj.info',			// by Filus (softscript at gmail.com)
	'.mujiki.com',			// by Mila Contora (ebumsn at ngs.ru)
	'.mxsupportmailer.com',
	'.ngfu2.info',			// by Tara Lagrant (webmaster at ngfu2.info)
	'.ok10000.com',			// by zipeng hu (ldcs350003 at hotmail.com)
	'.onlinetert.info',		// by Jarod Hyde (grigorysch at gmail.com)
	'.onlin-casino.com',	// by Lomis Konstantinos (businessline3000 at gmx.de)
	'.onlineviagra.de',
	'.ornit.info',			// by Victoria C. Frey (Victoria.C.Frey at pookmail.com)
	'.ozomw.info',
	'.pahuist.info',		// by Yura (yuralg2005 at yandex.ru)
	'.pelican-bulletin.info',	// by Elizabeth K. Perry (redmonk at mail.ru)
	'.perevozka777.ru',		// by witalik at gmail.com
	'.pharmacy-style.com',	// by Anrey Markov (ernestppc at yahoo.com)
	'.pills-storage.com',	// by 
	'.plusintedia.com',		// by g yk (abc00623 at 163.com), seems not used now
	'popki.ind.in',			// by Aleksandr Krasnik (supermaster at pisem.net)
	'.porkyhost.com',		// 79965 at whois.gkg.net
	'.porno-babe.info',		// by Peter (asdas at mail.ru), redirect to Google
	'.pornesc.com',			// by Xpeople (suppij atmail.ru)
	'.portaldiscount.com',	// by Mark Tven (bestsaveup at gmail.com)
	'.prama.info',			// by Juan.Kang at mytrashmail.com
	',pulsar.net',			// by TheBuzz Int. (theboss at tfcclion.com)
	'.qoclick.net',			// by DMITRIY SOLDATENKO
	'.relurl.com',			// tiny-like. by Grzes Tlalka (grzes1111 at interia.pl)
	'.replicaswatch.org',	// by Replin (admin at furnitureblog.org)
	'.searchadv.com',		// by Jaan Randolph (searchadv at gmail.com)
	'.roin.info',			// by Evgenius (roinse at yandex.ru)
	'.seek-www.com',		// by Adam Smit (pingpong at mail.md)
	'.sessocities.net',		// by info at secureserver3.com
	'.sexamoreit.com',
	'.sexforit.com',
	'.sexmaniacs.org',		// by Yang Chong (chong at x-india.com)
	'.sirlook.com',
	'.sticy.info',			// by Richard D. Mccall (richardmccall at yahoo.com)
	'.superrwm.info',		// by Dark Dux (duxdark at yahoo.com)
	'.thehostcity.com',		// Domains by Proxy
	'.thephentermineonline.info',	// by Kipola (smesh1155 at gmail.com)
	'.thetinyurl.com',		// by Beth J. Carter (Beth.J.Carter at thetinyurl.com)
	'.topmeds10.com',
	'*.tv-reklama.info',	// by Kozlov Maxim (m_koz at mail.ru)
	'.twabout.com',			// by qiu wenbing (qiuwenbing at 126.com), content from l2mpt.net
	'.uaro.info',			// by Neru Pioner (neru at smtp.ru)
	'.unctad.net',			// by gfdogfd at lovespb.com
	'.vacant.org.uk',
	'.viagrabuyonline.org',	// by Sipil (kopper1970 at gmail.com)
	'.vip-pets.info',		// by Mike Slowman (mk_slowman at yahoo.com)
	'.virtualsystem.de',
	'.vdxhost.com',
	'.webnow.biz',			// by Hsien I Fan (admin at servcomputing.com)
	'.wer3.info',			// by Martin Gundel (Martin at mail.com), 404 not found
	'.withsex.net',			// by C.W.Jang (jangcw1204 at naver.com)
	'.whoop.to',			// RMT
	'.worldinsurance.info',	// by Alexander M. Brown (Alex_Brown at yahoo.com), fake-antivirus
	'.xpacificpoker.com',	// by Hubert Hoffman (support at xpacificpoker.com)
	'.xphost.org',			// by alex alex (alrusnac at hotmail.com)
	'.xamorexxx.net',
	'.xsessox.com',
	'.webtools24.net',		// by Michael Helminger (info at ishelminger.de)
	'.womasia.info',		// by Mark Fidele (markfidele at yahoo.com)
	'.zlocorp.com',			// by tonibcrus at hotpop.com, spammed well with "http ://zlocorp.com/"
	'.zyguo.info',			// ns globoxhost.net

	// C-3: Not classifiable (information wanted)
	//
	// Something incoming to pukiwiki related sites
	'nana.co.il related' => array(
		'.planetnana.co.il',
		'.nana.co.il',
	),
	'.mylexus.info',		// by Homer Simpson (simhomer12300 at mail.com), Redirect to Google

	// D: Sample setting of
	// "third party in good faith"s
	//
	// Hosts shown inside of the implanted contents,
	// not used via spam, but maybe useful to detect these contents
	//
	// 'RESERVED',

	// Z: Yours
	//
	//'',
	//'',
	//'',

);
?>
