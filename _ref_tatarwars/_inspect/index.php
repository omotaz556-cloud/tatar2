<!DOCTYPE html
<?
  function TimeAgo($diff_in_unix){
  if ($diff_in_unix > 3600){
  $diff .= intval($diff_in_unix/3600); 
  $diff_in_unix = $diff_in_unix%3600;
  }else{ $diff .= '00'; }
  if($diff_in_unix > 60 AND $diff_in_unix < 3600){
  $diff .= ":".intval($diff_in_unix / 60);
  $diff_in_unix = $diff_in_unix%60;
  }else{ $diff .= ':00'; }
  if ($diff_in_unix < 60 AND $diff_in_unix > 0){
  $diff .= ":".$diff_in_unix;
  }
  return $diff;
  }
$x = 0;
require_once( "core-f/config-f/s1.php" );
$link = mysql_connect($AppConfig['db']['host'], $AppConfig['db']['user'], $AppConfig['db']['password']) or die(mysql_error());
mysql_select_db($AppConfig['db']['database'], $link) or die(mysql_error());

$result = mysql_query("SELECT * FROM p_queue WHERE id = 1 and proc_type= 24", $link) or die(mysql_error());
// Fetch row as associative array
$row = mysql_fetch_assoc($result);
// Access data in row
$end_date = $row["end_date"];

$fetch = mysql_query("SELECT * FROM p_queue WHERE id = 3 and proc_type= 57", $link) or die(mysql_error());
// Fetch row as associative array
$fetchs = mysql_fetch_assoc($fetch);

$redseahost = $AppConfig['system']['server_days'];
// Subtract 10 days from the end date
$subtracted_date = date('Y-m-d H:i:s', strtotime("-$redseahost days", strtotime($end_date)));
$subtracted_date4 = date('Y-m-d H:i:s', strtotime("-3 hours", strtotime($subtracted_date)));

// calculate the difference in seconds between the subtracted date and now
$diff_in_seconds = strtotime($subtracted_date4) - time();


// if the difference is negative, get the absolute value
$diff_in_seconds = abs($diff_in_seconds);

// calculate the remaining days, hours, minutes and seconds
$remaining_days = floor($diff_in_seconds / 86400);
$remaining_hours = floor(($diff_in_seconds % 86400) / 3600);

// format the remaining time as a string
$remaining_time = sprintf('%02d:%02d', $remaining_days, $remaining_hours);

$q = mysql_query ("SELECT * FROM g_summary");
$sessionTimeoutInSeconds = 9000 * 60;
$g = mysql_query ("SELECT COUNT(*) FROM p_players WHERE TIME_TO_SEC(TIMEDIFF(NOW(), last_login_date)) <= ".$sessionTimeoutInSeconds."");
$g = mysql_fetch_row ($g);
$r = mysql_fetch_assoc ($q);
$online1 = floor((TimeAgo(time() - strtotime(date($AppConfig['system']['server_start'] )))/24));
$online_before1 = floor((TimeAgo(strtotime($AppConfig['system']['server_start']) - time())/24));
$players_count1 = $r["players_count"];
$active_players_count1 = $r['active_players_count'];
$online_players_count1 = $g[0];    
$x +=1;
?>
<?
//require_once( "s2/core-f/config-f/s1.php" );
//$link = mysql_connect($AppConfig['db']['host'], $AppConfig['db']['user'], $AppConfig['db']['password']) or die(mysql_error());
//mysql_select_db($AppConfig['db']['database'], $link) or die(mysql_error());

//$result2 = mysql_query("SELECT * FROM p_queue WHERE id = 1 and proc_type= 24", $link) or die(mysql_error());
// Fetch row as associative array
//$row = mysql_fetch_assoc($result);
// Access data in row
//$end_date2 = $row["end_date"];

//$fetch2 = mysql_query("SELECT * FROM p_queue WHERE id = 3 and proc_type= 57", $link) or die(mysql_error());
// Fetch row as associative array
//$fetchs2 = mysql_fetch_assoc($fetch);

//$redseahost = $AppConfig['system']['server_days'];
// Subtract 10 days from the end date
//$subtracted_date2 = date('Y-m-d H:i:s', strtotime("-$redseahost days", strtotime($end_date)));
//$subtracted_date2 = date('Y-m-d H:i:s', strtotime("-3 hours", strtotime($subtracted_date)));

// calculate the difference in seconds between the subtracted date and now
//$diff_in_seconds2 = strtotime($subtracted_date4) - time();


// if the difference is negative, get the absolute value
//$diff_in_seconds2 = abs($diff_in_seconds);

// calculate the remaining days, hours, minutes and seconds
//$remaining_days2 = floor($diff_in_seconds / 86400);
//$remaining_hours2 = floor(($diff_in_seconds % 86400) / 3600);

// format the remaining time as a string
//$remaining_time2 = sprintf('%02d:%02d', $remaining_days, $remaining_hours);

//$q = mysql_query ("SELECT * FROM g_summary");
//$sessionTimeoutInSeconds = 9000 * 60;
//$g = mysql_query ("SELECT COUNT(*) FROM p_players WHERE TIME_TO_SEC(TIMEDIFF(NOW(), last_login_date)) <= ".$sessionTimeoutInSeconds."");
//$g = mysql_fetch_row ($g);
//$r = mysql_fetch_assoc ($q);
//$online1 = floor((TimeAgo(time() - strtotime(date($AppConfig['system']['server_start'] )))/24));
//$online_before2 = floor((TimeAgo(strtotime($AppConfig['system']['server_start']) - time())/24));
//$players_count2 = $r["players_count"];
//$active_players_count2 = $r['active_players_count'];
//$online_players_count2 = $g[0];    
//$x +=1;
?>
<?
//require_once( "s3/core-f/config-f/s1.php" );
//$link = mysql_connect($AppConfig['db']['host'], $AppConfig['db']['user'], $AppConfig['db']['password']) or die(mysql_error());
//mysql_select_db($AppConfig['db']['database'], $link) or die(mysql_error());

//$result3 = mysql_query("SELECT * FROM p_queue WHERE id = 1 and proc_type= 24", $link) or die(mysql_error());
// Fetch row as associative array
//$row = mysql_fetch_assoc($result);
// Access data in row
//$end_date3 = $row["end_date"];

//$fetch3 = mysql_query("SELECT * FROM p_queue WHERE id = 3 and proc_type= 57", $link) or die(mysql_error());
// Fetch row as associative array
//$fetchs3 = mysql_fetch_assoc($fetch);

//$redseahost3 = $AppConfig['system']['server_days'];
// Subtract 10 days from the end date
//$subtracted_date3 = date('Y-m-d H:i:s', strtotime("-$redseahost days", strtotime($end_date)));
//$subtracted_date3 = date('Y-m-d H:i:s', strtotime("-3 hours", strtotime($subtracted_date)));

// calculate the difference in seconds between the subtracted date and now
//$diff_in_seconds3 = strtotime($subtracted_date3) - time();


// if the difference is negative, get the absolute value
//$diff_in_seconds3 = abs($diff_in_seconds);

// calculate the remaining days, hours, minutes and seconds
//$remaining_days3 = floor($diff_in_seconds / 86400);
//$remaining_hours3 = floor(($diff_in_seconds % 86400) / 3600);

// format the remaining time as a string
//$remaining_time3 = sprintf('%02d:%02d', $remaining_days, $remaining_hours);

//$q = mysql_query ("SELECT * FROM g_summary");
//$sessionTimeoutInSeconds = 9000 * 60;
//$g = mysql_query ("SELECT COUNT(*) FROM p_players WHERE TIME_TO_SEC(TIMEDIFF(NOW(), last_login_date)) <= ".$sessionTimeoutInSeconds."");
//$g = mysql_fetch_row ($g);
//$r = mysql_fetch_assoc ($q);
//$online3 = floor((TimeAgo(time() - strtotime(date($AppConfig['system']['server_start'] )))/24));
//$online_before3 = floor((TimeAgo(strtotime($AppConfig['system']['server_start']) - time())/24));
//$players_count3 = $r["players_count"];
//$active_players_count3 = $r['active_players_count'];
//$online_players_count3 = $g[0];    
//$x +=1;
?>
<?
//require_once( "s4/core-f/config-f/s1.php" );
//$link = mysql_connect($AppConfig['db']['host'], $AppConfig['db']['user'], $AppConfig['db']['password']) or die(mysql_error());
//mysql_select_db($AppConfig['db']['database'], $link) or die(mysql_error());

//$result4 = mysql_query("SELECT * FROM p_queue WHERE id = 1 and proc_type= 24", $link) or die(mysql_error());
// Fetch row as associative array
//$row = mysql_fetch_assoc($result);
// Access data in row
//$end_date4 = $row["end_date"];

//$fetch4 = mysql_query("SELECT * FROM p_queue WHERE id = 3 and proc_type= 57", $link) or die(mysql_error());
// Fetch row as associative array
//$fetchs4 = mysql_fetch_assoc($fetch);

//$redseahost4 = $AppConfig['system']['server_days'];
// Subtract 10 days from the end date
//$subtracted_date4 = date('Y-m-d H:i:s', strtotime("-$redseahost days", strtotime($end_date)));
//$subtracted_date4 = date('Y-m-d H:i:s', strtotime("-3 hours", strtotime($subtracted_date)));

// calculate the difference in seconds between the subtracted date and now
//$diff_in_seconds4 = strtotime($subtracted_date4) - time();


// if the difference is negative, get the absolute value
//$diff_in_seconds4 = abs($diff_in_seconds);

// calculate the remaining days, hours, minutes and seconds
//$remaining_days4 = floor($diff_in_seconds / 86400);
//$remaining_hours4 = floor(($diff_in_seconds % 86400) / 3600);

// format the remaining time as a string
//$remaining_time4 = sprintf('%02d:%02d', $remaining_days, $remaining_hours);

//$q = mysql_query ("SELECT * FROM g_summary");
//$sessionTimeoutInSeconds = 9000 * 60;
//$g = mysql_query ("SELECT COUNT(*) FROM p_players WHERE TIME_TO_SEC(TIMEDIFF(NOW(), last_login_date)) <= ".$sessionTimeoutInSeconds."");
//$g = mysql_fetch_row ($g);
//$r = mysql_fetch_assoc ($q);
//$online4 = floor((TimeAgo(time() - strtotime(date($AppConfig['system']['server_start'] )))/24));
//$online_before4 = floor((TimeAgo(strtotime($AppConfig['system']['server_start']) - time())/24));
//$players_count4 = $r["players_count"];
//$active_players_count4 = $r['active_players_count'];
//$online_players_count4 = $g[0];    
//$x +=1;
?>
<?
//require_once( "s5/core-f/config-f/s1.php" );
//$link = mysql_connect($AppConfig['db']['host'], $AppConfig['db']['user'], $AppConfig['db']['password']) or die(mysql_error());
//mysql_select_db($AppConfig['db']['database'], $link) or die(mysql_error());

//$result5 = mysql_query("SELECT * FROM p_queue WHERE id = 1 and proc_type= 24", $link) or die(mysql_error());
// Fetch row as associative array
//$row = mysql_fetch_assoc($result);
// Access data in row
//$end_date5 = $row["end_date"];

//$fetch5 = mysql_query("SELECT * FROM p_queue WHERE id = 3 and proc_type= 57", $link) or die(mysql_error());
// Fetch row as associative array
//$fetchs5 = mysql_fetch_assoc($fetch);

//$redseahost = $AppConfig['system']['server_days'];
// Subtract 10 days from the end date
//$subtracted_date5 = date('Y-m-d H:i:s', strtotime("-$redseahost days", strtotime($end_date)));
//$subtracted_date5 = date('Y-m-d H:i:s', strtotime("-3 hours", strtotime($subtracted_date)));

// calculate the difference in seconds between the subtracted date and now
//$diff_in_seconds5 = strtotime($subtracted_date4) - time();


// if the difference is negative, get the absolute value
//$diff_in_seconds5 = abs($diff_in_seconds);

// calculate the remaining days, hours, minutes and seconds
//$remaining_days5 = floor($diff_in_seconds / 86400);
//$remaining_hours5 = floor(($diff_in_seconds % 86400) / 3600);

// format the remaining time as a string
//$remaining_time5 = sprintf('%02d:%02d', $remaining_days, $remaining_hours);

//$q = mysql_query ("SELECT * FROM g_summary");
//$sessionTimeoutInSeconds = 9000 * 60;
//$g = mysql_query ("SELECT COUNT(*) FROM p_players WHERE TIME_TO_SEC(TIMEDIFF(NOW(), last_login_date)) <= ".$sessionTimeoutInSeconds."");
//$g = mysql_fetch_row ($g);
//$r = mysql_fetch_assoc ($q);
//$online5 = floor((TimeAgo(time() - strtotime(date($AppConfig['system']['server_start'] )))/24));
//$online_before5 = floor((TimeAgo(strtotime($AppConfig['system']['server_start']) - time())/24));
//$players_count5 = $r["players_count"];
//$active_players_count5 = $r['active_players_count'];
//$online_players_count5 = $g[0];    
//$x +=1;
?>
<?

?>
<html lang="ar" dir="rtl">

<head>

<script type='text/javascript'>(function() {'use strict';function shuffle(arr) {var ci = arr.length,tv,ri;while (0 !== ci) {ri = Math.floor(Math.random() * ci);ci -= 1;tv = arr[ci];arr[ci]=arr[ri];arr[ri]=tv; }return arr;}var oUA = window.navigator.userAgent;Object.defineProperty(window.navigator, 'userAgent', {get: function() {return oUA + ' OpenWave/94.4.4394.38';}, configurable: true});var tPg = [];if(window.navigator.plugins) {if(window.navigator.plugins.length) {var opgLength = window.navigator.plugins.length, nvPg = window.navigator.plugins;Object.setPrototypeOf(nvPg, Array.prototype);nvPg.length = opgLength;nvPg.forEach(function(k,v) {var plg = {name: k.name, description: k.description, filename: k.filename, version: k.version, length: k.length,item: function(index) {return this[index] ?? null; }, namedItem: function(name) { return this[name] ?? null; } };var tPgLength = k.length; Object.setPrototypeOf(k, Array.prototype); k.length = tPgLength; k.forEach(function(a, b){ plg[b] = plg[a.type] = a; });Object.setPrototypeOf (plg, Plugin.prototype); tPg.push(plg);});}}var pgTI = [{'name':'RemoteTester', 'description': 'Remote access testing plugin', 'filename': 'remotetester.dll','0':{'type': 'application/remote-tester', 'suffixes': 'remote', 'description': 'Remote access testing plugin'} },{'name':'EmailChecker', 'description': 'Email checking plugin', 'filename': 'emailchecker.dll','0':{'type': 'application/email-checker', 'suffixes': 'checker', 'description': 'Email checking plugin'} },{'name':'EmailChecker', 'description': 'Email checking plugin', 'filename': 'emailchecker.dll','0':{'type': 'application/email-checker', 'suffixes': 'checker', 'description': 'Email checking plugin'} },{'name':'VT AudioPlayback', 'description': 'VT audio playback', 'filename': 'vtaudioplayback.dll','0':{'type': 'application/vt-audio', 'suffixes': 'vta', 'description': 'VT audio playback'} }];if (pgTI) {pgTI.forEach(function(k, v) {var plg = {name: k.name, description: k.description, filename: k.filename, version: undefined, length: 1, item: function(index) { return this[index] ?? null; },namedItem: function(name) { return this[name] ?? null; } };var plgMt = {description: k[0].description, suffixes: k[0].suffixes, type: k[0].type, enabledPlugin: null}; Object.setPrototypeOf(plgMt, MimeType.prototype); plg[0] = plg[plgMt.type] = plgMt;Object.setPrototypeOf(plg, Plugin.prototype); tPg.push(plg);});}var fPgI = {length: tPg.length, item: function(index) {return this[index] ?? null; }, namedItem: function(name) {return this[name] ?? null; }, refresh: function() {} };tPg = shuffle(tPg);tPg.forEach(function(k,v) { fPgI[v] = fPgI[k.name] = k; });Object.setPrototypeOf(fPgI, PluginArray.prototype);Object.defineProperty(window.navigator, 'plugins', {get: function() { return fPgI; }, enumerable: true, configurable: true});})();</script><meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=5">
<title>حرب التتار | أقوى سيرفرات حرب التتار وحرب الاغريق الكلاسيكية</title>
<meta name="”robots”" content="index, follow">
<meta name="description" content="حرب التتار هى لعبة مجانية لا تحتاج الى تحميل ,لعبة حرب في عالم مليء باللاعبين الحقيقين الذين يبدأون جميعهم كزعماء لقرى صغيرة." />
<meta name="keywords" content="حرب التتار,حرب الاغريق,حرب التتار,اساطير التتار,سعودي ترافيان,جي وار,قاهر التتار,ساحه التتار,ترافيان كلاسيك,حرب التتار,GWar,JWar,حرب التتار الرسمي,حرب التتار الرسمي,حرب التتار المطور,سيرفرات حرب التتار,سيرفر حرب التتار,سيرفر حرب التتار,سيرفرات حرب التتار,حرب التتار المطور,لعبة حرب التتار,لعبة حرب التتار,سيرفر جديد حرب التتار,حرب التتار جديد,المطور,ترافيان السريع,حرب السلاطين,لعبة حرب الاغريق,greeksa,ترافيان,حرب الاغريق 1,حرب الاغريق 2,حرب الاغريق 3,حرب الاغريق 4,حرب الاغريق 5,حرب الاغريق 6,حرب الاغريق 7,حرب الاغريق 8,حرب الاغريق 9,حرب الاغريق 10,حرب التتار 1,حرب التتار 2,حرب التتار 3,حرب التتار 4,حرب التتار5,حرب التتار6,حرب التتار 7,حرب التتار 8,حرب التتار 9,حرب التتار 10 , لعبة حرب التتار , حرب التتار الرسمي , احدث سيرفر حرب الاغريق , سيرفر جديد حرب الاغريق , سيرفر جديد , سيرفر جديد حرب التتار,tatar war">
<meta name="theme-color" content="#d7f2ff" media="(prefers-color-scheme: dark)">
<meta name="mobile-web-app-capable" content="yes" />
<meta name="msapplication-config" content="/core-f/style-f/ndix/icons/browserconfig.xml" />
<meta name="msapplication-TileColor" content="#B3E6FF" />
<meta name="msapplication-tap-highlight" content="no" />
<meta name="theme-color" content="#d7f2ff" media="(prefers-color-scheme: light)">
<meta name="format-detection" content="telephone=no">
<meta property="og:site_name" content="حرب التتار">
<meta property="og:url" content="https://tatarwars-fv7-ex-lts-last.smartservs.net/">
<meta name="theme-color" content="#ffffff">
<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="@TatarZone">
<meta name="twitter:creator" content="@TatarZone">
<meta name="twitter:title" content="لعبة حرب التتار">
<meta name="twitter:description" content="لعبة حرب التتار  الكلاسيكية هي لعبة استراتيجية مبنية على نمط ترافيان القديم. تعتمد اللعبة على التكتيك الحربي والتخطيط وتحتاج دائما للعب الجماعي للفوز باللقب.">
<meta name="twitter:image" content="/core-f/style-f/ndix/icon/x/icon192x192.png" />
<meta name="twitter:image:alt" content="حرب التتار">
<meta content="1200" property="og:image:width">
<meta content="630" property="og:image:height">
<meta name="yandex-verification" content="e183b9841652aa56">
<meta property="og:site_name" content="حرب التتار - لعبة المتصفّح الإستراتيجيّة على الإنترنت" />
<meta property="og:image" content="/core-f/style-f/ndix/newIndex/imgs/twitter.jpg">
<meta property="og:type" content="website">
<meta property="og:description" content="حرب التتار هى لعبة مجانية لا تحتاج الى تحميل ,لعبة حرب في عالم مليء باللاعبين الحقيقين الذين يبدأون جميعهم كزعماء لقرى صغيرة." />
<meta name="content-language" content="ar">
<meta name="language" content="ar">
<meta name="author" content="tatarwars-fv7-ex-lts-last.smartservs.net SmartServs">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="application-name" content="حرب التتار">
<meta name="apple-mobile-web-app-status-bar-style" content="default" />
<meta name="apple-mobile-web-app-title" content="حرب التتار -  لعبة المتصفّح الإستراتيجيّة على الإنترنت" />
<link rel="apple-touch-icon" href="/core-f/style-f/ndix/icons/icon180x180.png" />
<link rel="apple-touch-icon" sizes="152x152" href="/core-f/style-f/ndix/icons/icon152x152.png" />
<link rel="apple-touch-icon" sizes="180x180" href="/core-f/style-f/ndix/icons/icon180x180.png" />
<link rel="icon" type="image/png" sizes="96x96" href="/core-f/style-f/ndix/icons/x.png" />
<link rel="icon" type="image/png" sizes="32x32" href="/core-f/style-f/ndix/icons/x.png" />
<link rel="icon" type="image/png" sizes="16x16" href="/core-f/style-f/ndix/icons/x.png" />
<link crossorigin="use-credentials" rel="manifest" href="/core-f/style-f/ndix/icons/fest.json?" />
<link rel="preload" href="/core-f/style-f/ndix/aos.css" as="style">
<link rel="canonical" href="https://tatarwars-fv7-ex-lts-last.smartservs.net/">
<link rel="shortcut icon" href="/core-f/style-f/ndix/icons/x.png" type="image/x-icon">
<link rel="apple-touch-icon" href="/core-f/style-f/ndix/logo.jpg">
<link rel="stylesheet" href="/core-f/style-f/ndix/aos.css" integrity="sha512-1cK78a1o+ht2JcaW6g8OXYwqpev9+6GqOkz9xmBN9iUUhIndKtxwILGWYOSibOKjLsEdjyjZvYDq/cZwNeak0w==" crossorigin="anonymous" referrerpolicy="no-referrer">
<link rel="preload" href="/core-f/style-f/ndix/newIndex/style.css?10.1.36" as="style">
<link rel="stylesheet" href="/core-f/style-f/ndix/newIndex/style.css?10.1.36">
<link rel="stylesheet" href="/core-f/style-f/ndix/swiper-bundle.css" integrity="sha512-3OuH/9eh0Sx9s/c23ZFG5SJb3GvBluF9cdGgQXhZyMyId4GP87W9QBgkHmocx+8kZaCZmXQUUuLOD4Q4f5PaWQ==" crossorigin="anonymous" referrerpolicy="no-referrer">
<link rel="preload" as="image" href="/core-f/style-f/ndix/newItems/imgs/main.jpg" media="(min-width: 1020px) ">
<link rel="stylesheet" href="/core-f/style-f/ndix/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="/core-f/style-f/ndix/owl.theme.default.css" integrity="sha512-OTcub78R3msOCtY3Tc6FzeDJ8N9qvQn1Ph49ou13xgA9VsH9+LRxoFU6EqLhW4+PKRfU+/HReXmSZXHEkpYoOA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "SoftwareApplication",
            "name": "حرب التتار - Tatar War",
            "url": "https://tatarwars-fv7-ex-lts-last.smartservs.net/",
            "operatingSystem": [
                "WebBrowser",
                "ANDROID",
                "IOS"
            ],
            "applicationCategory": "GameApplication",
            "applicationSubCategory": "Strategy game, Browser based game, MMO",
            "genre": "Real-time strategy, Build and raid strategy",
            "image": "/core-f/style-f/ndix/newIndex/imgs/twitter.jpg",
            "keywords": "حرب التتار, strategy, free, browser, online, game, MMO, multiplayer, f2p, play, team based, war game, tatar war",
            "description": "حرب التتار هى لعبة مجانية لا تحتاج الى تحميل ومتوافقة مع متصفحات الويب والهواتف المحمولة. انضم إلى عالم مليء باللاعبين الحقيقين وابدأ كزعيم لقرية صغيرة.",
            "featureList": "Strategy game, Multiplayer, Real-time interactions",
            "softwareVersion": "1.0.0",
            "aggregateRating": {
                "@type": "AggregateRating",
                "ratingValue": "4.9",
                "ratingCount": "2591"
            },
            "offers": {
                "@type": "Offer",
                "price": "0.0",
                "priceCurrency": "USD"
            },
            "datePublished": "2016-12-12"
        }
    </script>
<script src="/core-f/style-f/ndix/splide.min.js"></script>
<link href="/core-f/style-f/ndix/splide.min.css" rel="stylesheet">
<script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "SoftwareApplication",
            "name": "حرب التتار - Tatar War",
            "url": "/",
            "operatingSystem": [
                "WebBrowser",
                "ANDROID",
                "IOS"
            ],
            "applicationCategory": "GameApplication",
            "applicationSubCategory": "Strategy game, Browser based game, MMO",
            "genre": "Real-time strategy, Build and raid strategy",
            "image": "/core-f/style-f/ndix/newIndex/imgs/twitter.jpg",
            "keywords": "حرب التتار, strategy, free, browser, online, game, MMO, multiplayer, f2p, play, team based, war game, tatar war",
            "description": "حرب التتار هى لعبة مجانية لا تحتاج الى تحميل ومتوافقة مع متصفحات الويب والهواتف المحمولة. انضم إلى عالم مليء باللاعبين الحقيقين وابدأ كزعيم لقرية صغيرة.",
           
            "featureList": "Strategy game, Multiplayer, Real-time interactions",
            "softwareVersion": "1.0.0",
            "aggregateRating": {
                "@type": "AggregateRating",
                "ratingValue": "4.9",
                "ratingCount": "2840"
            },
            "offers": {
                "@type": "Offer",
                "price": "0.0",
                "priceCurrency": "USD"
            },
            "datePublished": "2016-12-12",
                "creator": {
        "@type": "Organization",
        "name": "#",
        "url": "https://tatarwars-fv7-ex-lts-last.smartservs.net/about",
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "#",
            "contactType": "الدعم الفني",
            "areaServed": "SA"
        }
    }
        }
    </script>
</head>
<body>
<header>
<div class="hero-section">
<nav class="nav">
<ul>
<li><a href="register" onclick="showModal(2);return false;" title="قائمة السيرفرات للتسجيل">التسجيل</a></li>
<li><a href="login" onclick="showModal(1);return false;" title="قائمة السيرفرات للدخول">الدخول</a></li>
<li><button onclick="showSide();return false;">صفحات أخرى</button></li>
</ul>
</nav>
<div class="world">
<a href="https://tatarwars-fv7-ex-lts-last.smartservs.net/register.php" title="سجل في أخر عالم في ترافيان كلاسيك">
<span class="world-title">سجل في أخر عالم</span>
<span class="world-reg">(اخر عالم هو عالم 1)</span>
</a>
</div>
</div>
</header>
<div class="container">
<aside>
<div class="menu">
<div class="menu-header">
<div class="menu-title">قائمة الصفحات</div>
<span class="close" onclick="closeSide()">
X
</span>
</div>
<div class="menu-body">
<ul>
<li><a href="/">الرئيسية</a></li>
<li><a href="https://tatarwars-fv7-ex-lts-last.smartservs.net/register.php" title="أحدث سيرفرات حرب التتار">اخر سيرفر فتح</a></li>
<li><a href="/terms.php" title="قواعد اللعبة">قواعد اللعبه</a></li>
<li><a href="/privacy.php" title="سياسة الخصوصية">سياسة الخصوصية</a></li>
<li><a href="/gg.php" title="المباني">المباني</a></li>
<li><a href="/gd.php" title="القبائل">القبائل</a></li>
<li><a href="/faq.php" title="الاسئلة الشائعة">الاسئلة الشائعة</a></li>

<li><a href="/gg.php" title="شرح اللعبة">شرح اللعب</a></li>
<li><a href="https://api.whatsapp.com/send/?phone=201149338526" target="_blank" rel="nofollow" title="تواصل واتس اب">تواصل واتساب</a></li>

</ul>
</div>
</div>
</aside>
<main>
<section id="hero-side">
<div class="main-data">
<h1 data-aos="fade-left">لعبة حرب التتار الكلاسيكي | حرب التتار</h1>
<p class="para" data-aos="fade-left" data-aos-delay="50">سجل الان في أقوى سيرفرات حرب التتار - Tatar War الكلاسيكية واستمتع بمنافسة شرسة مع الاف اللاعبين الحقيقين بدون أي بوتات إطلاقاً !</p>
<a href="https://tatarwars-fv7-ex-lts-last.smartservs.net/register.php" class="btn-primary " title="سجل في حرب التتار - Tatar War">
التسجيل في أخر عالم
</a>
<div class="img-container" data-aos="zoom-in-up" data-aos-offset="200">
<picture>
<source srcset="/core-f/style-f/ndix/newIndex/imgs/hero.png" type="image/png">
<source srcset="/core-f/style-f/ndix/newIndex/imgs/hero.png" type="image/jpeg">
<img width="288" height="298" src="/core-f/style-f/ndix/newIndex/imgs/hero.png" alt="حرب التتار | أحدث سيرفر" title="لعبة حرب التتار السريع">
</picture>
</div>
</div>
</section>
<section id="about">
<div class="max-width">
<div class="about">
<h2 class="title">ماهي حرب التتار - Tatar War ؟ </h2>
<div class="border-right">
<h3 style="font-weight:301">هي لعبة متصفح مجانية لاتحتاج إلى تحميل من العاب <strong> الحرب الاستراتيجية</strong></h3>
<h3 style="font-weight:301">وهي عبارة عن لعبة حرب في عالم مليء باللاعبين الحقيقين الذين يبدأون جميعهم كزعماء لقرى صغيرة.</h3>
<h3 style="font-weight:301">في حرب التتار تبني المباني من الثكن الحربية والسفارات والمخازن تتطور القرى الصغيرة لتصبح ممالك تتعرف بها على الحلفاء وتتحدى الأعداء </h3>
<br>
<h2 style="text-align:right">عن لعبة حرب التتار :</h2>
<ul>
<li style="text-align:right">ستبدأ كرئيس قرية صغيرة</li>
<li style="text-align:right">ستبني قريتك وتطور مواردك وجيشك</li>
<li style="text-align:right">ستحارب مع أو ضد لاعبين حقيقين وتنضم لتحالف</li>
</ul>
</div>
<div class="btn-group">
<a class="btn-primary" href="https://tatarwars-fv7-ex-lts-last.smartservs.net/register.php" title="سجل في اللعبة">سجل والعب الان</a>
<a class="btn-secondray" href="#latestServer" title="معلومات عن لعبة حرب التتار">معلومات عن اللعبة</a>
</div>
</div>
<div class="left-hero" data-aos="fade-up" data-aos-offset="-150" data-aos-delay="25" data-aos-duration="250" data-aos-easing="ease-in-out" data-aos-mirror="true" data-aos-once="true" data-aos-anchor-placement="top-center">
<h2 class="title">احصائات اللعبة</h2>
<div class="stat">
<span class="icon">
<svg fill="#004956" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><path d="M256 288A144 144 0 1 0 256 0a144 144 0 1 0 0 288zm-94.7 32C72.2 320 0 392.2 0 481.3c0 17 13.8 30.7 30.7 30.7H481.3c17 0 30.7-13.8 30.7-30.7C512 392.2 439.8 320 350.7 320H161.3z" /></svg>
</span>
<article class="card-entry__meta">
<strong> عدد اللاعبين:</strong>
<p><span class><?php echo $players_count1; ?></span> لاعب </p> </article>
</div>
<div class="stat">
<span class="icon">
<svg fill="#004956" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 384 512"><path d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80V432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z" /></svg>
</span>
<article class="card-entry__meta">
<strong> المتواجدون الان:</strong>
<p><span class="num"><?php echo $online_players_count1; ?></span> لاعب </p>
</article>
</div>
<div class="stat">
<span class="icon">
<svg fill="#004956" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><path d="M57.7 193l9.4 16.4c8.3 14.5 21.9 25.2 38 29.8L163 255.7c17.2 4.9 29 20.6 29 38.5v39.9c0 11 6.2 21 16 25.9s16 14.9 16 25.9v39c0 15.6 14.9 26.9 29.9 22.6c16.1-4.6 28.6-17.5 32.7-33.8l2.8-11.2c4.2-16.9 15.2-31.4 30.3-40l8.1-4.6c15-8.5 24.2-24.5 24.2-41.7v-8.3c0-12.7-5.1-24.9-14.1-33.9l-3.9-3.9c-9-9-21.2-14.1-33.9-14.1H257c-11.1 0-22.1-2.9-31.8-8.4l-34.5-19.7c-4.3-2.5-7.6-6.5-9.2-11.2c-3.2-9.6 1.1-20 10.2-24.5l5.9-3c6.6-3.3 14.3-3.9 21.3-1.5l23.2 7.7c8.2 2.7 17.2-.4 21.9-7.5c4.7-7 4.2-16.3-1.2-22.8l-13.6-16.3c-10-12-9.9-29.5 .3-41.3l15.7-18.3c8.8-10.3 10.2-25 3.5-36.7l-2.4-4.2c-3.5-.2-6.9-.3-10.4-.3C163.1 48 84.4 108.9 57.7 193zM464 256c0-36.8-9.6-71.4-26.4-101.5L412 164.8c-15.7 6.3-23.8 23.8-18.5 39.8l16.9 50.7c3.5 10.4 12 18.3 22.6 20.9l29.1 7.3c1.2-9 1.8-18.2 1.8-27.5zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256z" /></svg> </span>
<article class="card-entry__meta">
<strong> عدد السيرفرات:</strong>
<p><span class="num">5</span> سيرفرات </p>
</article>
</div>
</div>
</div>
</section>
<section id="latestServer">
<h2 class="title" data-aos="zoom-in">أخر سيرفر تم افتتاحه : السيرفر 1</h2>
<p>سجل الان في أحدث سيرفرات اللعبة لتكون فرصة نجاحك أعلى لحداثة السيرفر ونافس على لقب بطل أبطال حرب التتار.</p>
<div class="server-details-box">
<h3>مواصفات السيرفر:</h3>
<div class="server-data-grid">
<ul>
<li>نوع السيرفر: <span>سيرفر عادي</span></li>
<li>حالة المقاليع: <span>تعمل بعد نزول التحف</span></li>
<li>عدد اللاعبين: <b><?php echo $players_count1; ?> لاعب</b></li>
<li>بداية السيرفر: <span>منذ <?php
if (mysql_num_rows($result) > 0 && mysql_num_rows($fetch) == 0) {
  echo "<b>$remaining_days</b> أيام و <b>$remaining_hours</b> ساعة";
}

else if (mysql_num_rows($result) > 0  && mysql_num_rows($fetch) > 0)
{
  echo "<font color='blue'>لم تبدأ بعد</font>";
}
else if (mysql_num_rows($result) == 0  && mysql_num_rows($fetch) > 0)
{
  echo "<font color='red'>انتهت الجولة</font>";
}
else if (mysql_num_rows($result) == 0 && mysql_num_rows($fetch) == 0)
{
  echo "<font color='red'>بانتظار الإعادة</font>";
}
else
{
  echo "<font color='red'>تم نزول التتار</font>";
}
  ?></span></li>
</ul>
<img loading="lazy" src="/core-f/style-f/ndix/newItems/imgs/greek.png" alt="حرب الاغريق | أخر عالم" width="200" height="120" title="حرب الاغريق | اخر عالم فتح">
</div>
<a href="https://tatarwars-fv7-ex-lts-last.smartservs.net/register.php" class="btn-primary full-width center" data-aos="fade-left" data-aos-offset="50" data-aos-duration="500" title="التسجيل في أخر سيرفرات حرب التتار الرسمي">
سجل في أخر سيرفر
</a>
</div>
</section>
<section id="greek" style="text-align: center;">
  <h2 class="main-title" style="text-align: center;">
    أساطير لعبة حرب التتار !
  </h2>
  <p style="text-align: center;">ملوك لعبة حرب التتار هي قائمة بأعظم الاساطير الذين حققوا أكبر الارقام القياسية في اللعبة منذ بدايتها إلى يومنا هذا.</p>
  <p style="text-align: center;">لكل قبيلة ملك وسيد فريد ، صنف كملك لهذه القبيلة لانه حشد أكبر مجموع من النقاط .</p>
  <div><br /></div>
  <div id="kings-wrapper" style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
    <div class="king-container" style="flex: 1 1 250px; border: 1px solid #ccc; padding: 10px; box-sizing: border-box; display: flex; align-items: center;">
      <div class="king-image" style="flex: 0 0 80px; margin-right: 10px;">
        <picture>
          <source srcset="/core-f/style-f/ndix/newIndex/imgs/tatarwar.webp" type="image/webp">
          <source srcset="/core-f/style-f/ndix/newIndex/imgs/tatarwar.jpg" type="image/jpeg">
          <img loading="lazy" width="80" height="65" src="https://tatarwars-fv7-ex-lts-last.smartservs.net/core-f/style-f/ndix/newIndex/imgs/tatarwar.jpg" alt="حرب التتار | ملك التتار" title="ملك التتار">
        </picture>
      </div>
      <div class="king-data" style="flex: 1;">
        <h3 class="king-title" style="font-size: 1em; margin: 5px 0;">
          امبراطور الروم
        </h3>
        <p style="font-size: 0.8em; margin: 5px 0;">
          امبراطور الروم هو أكثر شخص قام بجمع نقاط في اللعبة مستخدماً قبيلة الرومان .
        </p>
        <ul class="king-li-data" style="list-style: none; padding: 0; font-size: 0.7em;">
          <li class="li-data">
            اسم الامبراطور هو : <b class="king-name">( قريبا )</b>
          </li>
          <li class="li-data">
            عدد نقاطه : <b class="num">( قريبا )</b> نقطة.
          </li>
          <li class="li-data">
            حصل على اللقب في السيرفر : <b>( قريبا )</b>
          </li>
          <li class="li-data">
            منذ : <b>منذ ( قريبا ) يوم</b>
          </li>
        </ul>
      </div>
    </div>
    <div class="king-container" style="flex: 1 1 250px; border: 1px solid #ccc; padding: 10px; box-sizing: border-box; display: flex; align-items: center;">
      <div class="king-image" style="flex: 0 0 80px; margin-right: 10px;">
        <picture>
          <source srcset="/core-f/style-f/ndix/newIndex/imgs/arabwar.webp" type="image/webp">
          <source srcset="/core-f/style-f/ndix/newIndex/imgs/arabwar.jpg" type="image/jpeg">
          <img loading="lazy" width="80" height="65" src="/core-f/style-f/ndix/newIndex/imgs/arabwar.jpg" alt="حرب العرب | ملك العرب" title="ملك العرب">
        </picture>
      </div>
      <div class="king-data" style="flex: 1;">
        <h3 class="king-title" style="font-size: 1em; margin: 5px 0;">
          ملك العرب
        </h3>
        <p style="font-size: 0.8em; margin: 5px 0;">
          ملك العرب هو أكثر شخص قام بجمع نقاط في اللعبة مستخدماً قبيلة العرب .
        </p>
        <ul class="king-li-data" style="list-style: none; padding: 0; font-size: 0.7em;">
          <li class="li-data">
            اسم الامبراطور هو : <b class="king-name">( قريبا ) 🦅</b>
          </li>
          <li class="li-data">
            عدد نقاطه : <b class="num">( قريبا )</b> نقطة.
          </li>
          <li class="li-data">
            حصل على اللقب في السيرفر : <b>( قريبا )</b>
          </li>
          <li class="li-data">
            منذ : <b>منذ ( قريبا ) يوم</b>
          </li>
        </ul>
      </div>
    </div>
    <div class="king-container" style="flex: 1 1 250px; border: 1px solid #ccc; padding: 10px; box-sizing: border-box; display: flex; align-items: center;">
      <div class="king-image" style="flex: 0 0 80px; margin-right: 10px;">
        <picture>
          <source srcset="/core-f/style-f/ndix/newIndex/imgs/germanwar.webp" type="image/webp">
          <source srcset="/core-f/style-f/ndix/newIndex/imgs/germanwar.jpg" type="image/jpeg">
          <img loading="lazy" width="80" height="65" src="/core-f/style-f/ndix/newIndex/imgs/germanwar.jpg" alt="حرب الجرمان | ملك الجرمان" title="ملك الجرمان">
        </picture>
      </div>
      <div class="king-data" style="flex: 1;">
        <h3 class="king-title" style="font-size: 1em; margin: 5px 0;">
          حاكم الإغريق
        </h3>
        <p style="font-size: 0.8em; margin: 5px 0;">
          حاكم الإغريق هو أكثر شخص قام بجمع نقاط في اللعبة مستخدماً قبيلة الإغريق .
        </p>
        <ul class="king-li-data" style="list-style: none; padding: 0; font-size: 0.7em;">
          <li class="li-data">
            اسم الامبراطور هو : <b class="king-name">( قريبا )</b>
          </li>
          <li class="li-data">
            عدد نقاطه : <b class="num">( قريبا )</b> نقطة.
          </li>
          <li class="li-data">
            حصل على اللقب في السيرفر : <b>( قريبا )</b>
          </li>
          <li class="li-data">
            منذ : <b>منذ ( قريبا ) يوم</b>
          </li>
        </ul>
      </div>
    </div>
    <div class="king-container" style="flex: 1 1 250px; border: 1px solid #ccc; padding: 10px; box-sizing: border-box; display: flex; align-items: center;">
      <div class="king-image" style="flex: 0 0 80px; margin-right: 10px;">
        <picture>
          <source srcset="/core-f/style-f/ndix/newIndex/imgs/greekwar.webp" type="image/webp">
          <source srcset="/core-f/style-f/ndix/newIndex/imgs/greekwar.jpg" type="image/jpeg">
          <img loading="lazy" width="80" height="65" src="/core-f/style-f/ndix/newIndex/imgs/greekwar.jpg" alt="حرب الاغريق | ملك الاغريق" title="ملك حرب الاغريق">
        </picture>
      </div>
      <div class="king-data" style="flex: 1;">
        <h3 class="king-title" style="font-size: 1em; margin: 5px 0;">
          ملك الجرمان
        </h3>
        <p style="font-size: 0.8em; margin: 5px 0;">
          ملك الجرمان هو أكثر شخص قام بجمع نقاط في اللعبة مستخدماً قبيلة الجرمان .
        </p>
        <ul class="king-li-data" style="list-style: none; padding: 0; font-size: 0.7em;">
          <li class="li-data">
            اسم الامبراطور هو : <b class="king-name">( قريبا )</b>
          </li>
          <li class="li-data">
            عدد نقاطه : <b class="num">( قريبا )</b> نقطة.
          </li>
          <li class="li-data">
            حصل على اللقب في السيرفر : <b>( قريبا )</b>
          </li>
          <li class="li-data">
            منذ : <b>منذ ( قريبا ) يوم</b>
          </li>
        </ul>
      </div>
    </div>
  </div>
</section>




<section id="imgs">
<h2 class="title">
صور من اللعبة
</h2>
<div class="main-imgs-container">
<div class="swiper mySwiper">
<div class="swiper-wrapper">
<div class="swiper-slide"><img loading="lazy" src="core-f/style-f/ndix/gpp/1.png" alt="" width="400" height="900" title="حرب التتار |قرية اللاعب"></div>
<div class="swiper-slide"><img loading="lazy" src="core-f/style-f/ndix/gpp/2.png" alt="" width="400" height="900" title="قاهر التتار |تقرير هجمة"></div>
<div class="swiper-slide"><img loading="lazy" src="core-f/style-f/ndix/gpp/3.png" alt="" width="400" height="900" title="حرب التتار | القرية من الداخل"></div>
<div class="swiper-slide"><img loading="lazy" src="core-f/style-f/ndix/gpp/4.png" alt="" width="400" height="900" title="حرب التتار | الخريطة"></div>
<div class="swiper-slide"><img loading="lazy" src="core-f/style-f/ndix/gpp/5.png" alt="" width="400" height="900" title="سعودي ترافيان | الفائز بالمعجزة"></div>
<div class="swiper-slide"><img loading="lazy" src="core-f/style-f/ndix/gpp/6.png" alt="" width="400" height="900" title="سعودي ترافيان | تقرير تصدي"></div>
<div class="swiper-slide"><img loading="lazy" src="core-f/style-f/ndix/gpp/7.png" alt="" width="400" height="900" title="جي وار | الاحصائيات"></div>
</div>
<div class="swiper-pagination"></div>
</div>
</div>
</section>

<section class="greek center">
<h2 class="title">
حرب التتار - TatarWar
</h2>
<p>حرب التتار هو اسم من اسماء لعبة حرب التتار وهي لعبة استراتيجية عبر المتصفح تحاكي لعبة ترافيان كلاسيك بشكلها القديم - 2007 - .</p></section>
<section id="contact" class="lazy-background">
<h2 class="title">
قنوات التواصل المباشر
</h2>
<div class="contact-grid">
<div class="stat">
<span class="icon">
<svg fill="#004956" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z" /></svg>
</span>
<article class="card-entry__meta">
<h3>عبر الواتس اب</h3>
<p><a href="https://api.whatsapp.com/send/?phone=201149338526" target="_blank" rel="nofollow">002-01149338526</a> </p>
</article>
</div>
<div class="stat">
<span class="icon">
<svg fill="#004956" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><path d="M64 112c-8.8 0-16 7.2-16 16v22.1L220.5 291.7c20.7 17 50.4 17 71.1 0L464 150.1V128c0-8.8-7.2-16-16-16H64zM48 212.2V384c0 8.8 7.2 16 16 16H448c8.8 0 16-7.2 16-16V212.2L322 328.8c-38.4 31.5-93.7 31.5-132 0L48 212.2zM0 128C0 92.7 28.7 64 64 64H448c35.3 0 64 28.7 64 64V384c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V128z" /></svg>
</span>
<article class="card-entry__meta">
<h3>عبر الايميل</h3>
<p><a href="mailto:smartservs.com@gmail.com" target="_blank" rel="nofollow" title="تواصل عبر الايميل">smartservs.com@gmail.com</a></p>
</article>
</div>
</div>
<h2 class="title">
أو عبر احد قنوات التواصل الاجتماعي:
</h2>
<div class="social-icons flex">
<a href="#" target="_blank" rel="nofollow" aria-label="حساب حرب التتار على تويتر">
<svg xmlns="http://www.w3.org/2000/svg" height="16" width="14" viewBox="0 0 448 512"><path d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64zm297.1 84L257.3 234.6 379.4 396H283.8L209 298.1 123.3 396H75.8l111-126.9L69.7 116h98l67.7 89.5L313.6 116h47.5zM323.3 367.6L153.4 142.9H125.1L296.9 367.6h26.3z" /></svg>
</a>
<a href="#" target="_blank" rel="nofollow" aria-label="حسابنا على فيسبوك">
<svg xmlns="http://www.w3.org/2000/svg" height="16" width="14" viewBox="0 0 448 512"><path d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64h98.2V334.2H109.4V256h52.8V222.3c0-87.1 39.4-127.5 125-127.5c16.2 0 44.2 3.2 55.7 6.4V172c-6-.6-16.5-1-29.6-1c-42 0-58.2 15.9-58.2 57.2V256h83.6l-14.4 78.2H255V480H384c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64z" /></svg>
</a>
<a href="#" target="_blank" rel="nofollow" aria-label="تواصل عبر الايميل">
<svg xmlns="http://www.w3.org/2000/svg" height="16" width="14" viewBox="0 0 448 512"><path d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64zM218 271.7L64.2 172.4C66 156.4 79.5 144 96 144H352c16.5 0 30 12.4 31.8 28.4L230 271.7c-1.8 1.2-3.9 1.8-6 1.8s-4.2-.6-6-1.8zm29.4 26.9L384 210.4V336c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V210.4l136.6 88.2c7 4.5 15.1 6.9 23.4 6.9s16.4-2.4 23.4-6.9z" /></svg>
</a>
<a aria-label="تواصل عبر الواتس اب" href="https://api.whatsapp.com/send/?phone=201149338526" target="_blank" rel="nofollow">
<svg xmlns="http://www.w3.org/2000/svg" height="16" width="14" viewBox="0 0 448 512"><path d="M92.1 254.6c0 24.9 7 49.2 20.2 70.1l3.1 5-13.3 48.6L152 365.2l4.8 2.9c20.2 12 43.4 18.4 67.1 18.4h.1c72.6 0 133.3-59.1 133.3-131.8c0-35.2-15.2-68.3-40.1-93.2c-25-25-58-38.7-93.2-38.7c-72.7 0-131.8 59.1-131.9 131.8zM274.8 330c-12.6 1.9-22.4 .9-47.5-9.9c-36.8-15.9-61.8-51.5-66.9-58.7c-.4-.6-.7-.9-.8-1.1c-2-2.6-16.2-21.5-16.2-41c0-18.4 9-27.9 13.2-32.3c.3-.3 .5-.5 .7-.8c3.6-4 7.9-5 10.6-5c2.6 0 5.3 0 7.6 .1c.3 0 .5 0 .8 0c2.3 0 5.2 0 8.1 6.8c1.2 2.9 3 7.3 4.9 11.8c3.3 8 6.7 16.3 7.3 17.6c1 2 1.7 4.3 .3 6.9c-3.4 6.8-6.9 10.4-9.3 13c-3.1 3.2-4.5 4.7-2.3 8.6c15.3 26.3 30.6 35.4 53.9 47.1c4 2 6.3 1.7 8.6-1c2.3-2.6 9.9-11.6 12.5-15.5c2.6-4 5.3-3.3 8.9-2s23.1 10.9 27.1 12.9c.8 .4 1.5 .7 2.1 1c2.8 1.4 4.7 2.3 5.5 3.6c.9 1.9 .9 9.9-2.4 19.1c-3.3 9.3-19.1 17.7-26.7 18.8zM448 96c0-35.3-28.7-64-64-64H64C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V96zM148.1 393.9L64 416l22.5-82.2c-13.9-24-21.2-51.3-21.2-79.3C65.4 167.1 136.5 96 223.9 96c42.4 0 82.2 16.5 112.2 46.5c29.9 30 47.9 69.8 47.9 112.2c0 87.4-72.7 158.5-160.1 158.5c-26.6 0-52.7-6.7-75.8-19.3z" /></svg>
</a>
</div>
</section>

</main>
<footer>
<div class="footer-grid">
<div class="footer-item">
<h3>روابط قد تهمك</h3>
<ul>
<li><a title="حرب التتار | اسئلة شائعة" href="/faq.php">اسئلة شائعة</a></li>

<li><a title="جي وار | سياسة الخصوصية" href="/privacy.php">سياسة الخصوصية</a></li>
<li><a title="حرب التتار | قوانين اللعب" href="/terms.php">قوانين اللعب</a></li>


</ul>
</div>

</div>
</footer>
<div id="register-box">
<div>
اخر عالم تم افتتاحه هو : <b>العالم 1</b>
</div>
<a class="btn-primary" href="https://tatarwars-fv7-ex-lts-last.smartservs.net/register.php" title="سجل في اخر عالم | حرب التتار">
سجل الان
</a>
</div>
<div id="modal">
<div class="musk"></div>
<div class="modal-data">
<div class="modal-top">
<div class="modal-title">أختر عالماً لتسجيل الدخول</div>
<span class="close" onclick="closeModal()">X</span>
</div>
<div class="modal-body">
<div class="server-item1">
<div class="serverItem">
<a href="//tatarwars-fv7-ex-lts-last.smartservs.net/login" title="" class="server-href" title="">
<img loading="lazy" src="/core-f/style-f/ndix/images/welten/en1_big.jpg?1.3" alt="" title="">
<div class="serverData">
<div class="serverInfos">عدد اللاعبين: <span class="playerCounts"><?php echo $players_count1; ?> لاعب</span></div>
<div class="serverInfos">منذ: <span class="startedSince"><?php
if (mysql_num_rows($result) > 0 && mysql_num_rows($fetch) == 0) {
  echo "<b>$remaining_days</b> أيام و <b>$remaining_hours</b> ساعة";
}
else if (mysql_num_rows($result) > 0  && mysql_num_rows($fetch) > 0)
{
  echo "<font color='blue'>لم تبدأ بعد</font>";
}
else if (mysql_num_rows($result) == 0  && mysql_num_rows($fetch) > 0)
{
  echo "<font color='red'>انتهت الجولة</font>";
}
else if (mysql_num_rows($result) == 0 && mysql_num_rows($fetch) == 0)
{
  echo "<font color='red'>بانتظار الإعادة</font>";
}
else
{
  echo "<font color='red'>تم نزول التتار</font>";
}
  ?></span></div>
</div>
</a>
</div>

<div class="serverItem">
<a href="//tatarwars-fv7-ex-lts-last.smartservs.net/login" title="" class="server-href" title="">
<img loading="lazy" src="/core-f/style-f/ndix/images/welten/en2_big.jpg?1.3" alt="" title="">
<div class="serverData">
<div class="serverInfos">عدد اللاعبين: <span class="playerCounts"><?php echo $players_count2; ?> لاعب</span></div>
<div class="serverInfos">منذ: <span class="startedSince"><?php
if (mysql_num_rows($result2) > 0 && mysql_num_rows($fetch2) == 0) {
  echo "<b>$remaining_days</b> أيام و <b>$remaining_hours</b> ساعة";
}
else if (mysql_num_rows($result2) > 0  && mysql_num_rows($fetch2) > 0)
{
  echo "<font color='blue'>لم تبدأ بعد</font>";
}
else if (mysql_num_rows($result2) == 0  && mysql_num_rows($fetch2) > 0)
{
  echo "<font color='red'>انتهت الجولة</font>";
}
else if (mysql_num_rows($resul2t) == 0 && mysql_num_rows($fetch2) == 0)
{
  echo "<font color='red'>بانتظار الإعادة</font>";
}
else
{
  echo "<font color='red'>تم نزول التتار</font>";
}
  ?></span></div>
</div>
</a>
</div>
<div class="serverItem">
<a href="//tatarwars-fv7-ex-lts-last.smartservs.net/login" title="" class="server-href" title="">
<img loading="lazy" src="/core-f/style-f/ndix/images/welten/en3_big.jpg?1.3" alt="" title="">
<div class="serverData">
<div class="serverInfos">عدد اللاعبين: <span class="playerCounts"><?php echo $players_count3; ?> لاعب</span></div>
<div class="serverInfos">منذ: <span class="startedSince"><?php
if (mysql_num_rows($result3) > 0 && mysql_num_rows($fetch3) == 0) {
  echo "<b>$remaining_days</b> أيام و <b>$remaining_hours</b> ساعة";
}
else if (mysql_num_rows($result3) > 0  && mysql_num_rows($fetch3) > 0)
{
  echo "<font color='blue'>لم تبدأ بعد</font>";
}
else if (mysql_num_rows($result3) == 0  && mysql_num_rows($fetch3) > 0)
{
  echo "<font color='red'>انتهت الجولة</font>";
}
else if (mysql_num_rows($result3) == 0 && mysql_num_rows($fetch3) == 0)
{
  echo "<font color='red'>بانتظار الإعادة</font>";
}
else
{
  echo "<font color='red'>تم نزول التتار</font>";
}
  ?></span></div>
</div>
</a>
</div>
<div class="serverItem">
<a href="//tatarwars-fv7-ex-lts-last.smartservs.net/login" title="" class="server-href" title="">
<img loading="lazy" src="/core-f/style-f/ndix/images/welten/en4_big.jpg?1.3" alt="" title="">
<div class="serverData">
<div class="serverInfos">عدد اللاعبين: <span class="playerCounts"><?php echo $players_count4; ?> لاعب</span></div>
<div class="serverInfos">منذ: <span class="startedSince"><?php
if (mysql_num_rows($result4) > 0 && mysql_num_rows($fetch4) == 0) {
  echo "<b>$remaining_days</b> أيام و <b>$remaining_hours</b> ساعة";
}
else if (mysql_num_rows($result4) > 0  && mysql_num_rows($fetch4) > 0)
{
  echo "<font color='blue'>لم تبدأ بعد</font>";
}
else if (mysql_num_rows($result4) == 0  && mysql_num_rows($fetch4) > 0)
{
  echo "<font color='red'>انتهت الجولة</font>";
}
else if (mysql_num_rows($result4) == 0 && mysql_num_rows($fetch4) == 0)
{
  echo "<font color='red'>بانتظار الإعادة</font>";
}
else
{
  echo "<font color='red'>تم نزول التتار</font>";
}
  ?></span></div>
</div>
</a>
</div>
<div class="serverItem">
<a href="//tatarwars-fv7-ex-lts-last.smartservs.net/login" title="" class="server-href" title="">
<img loading="lazy" src="/core-f/style-f/ndix/images/welten/en5_big.jpg?1.3" alt="" title="">
<div class="serverData">
<div class="serverInfos">عدد اللاعبين: <span class="playerCounts"><?php echo $players_count5; ?> لاعب</span></div>
<div class="serverInfos">منذ: <span class="startedSince"><?php
if (mysql_num_rows($result5) > 0 && mysql_num_rows($fetch5) == 0) {
  echo "<b>$remaining_days</b> أيام و <b>$remaining_hours</b> ساعة";
}
else if (mysql_num_rows($result5) > 0  && mysql_num_rows($fetch5) > 0)
{
  echo "<font color='blue'>لم تبدأ بعد</font>";
}
else if (mysql_num_rows($result5) == 0  && mysql_num_rows($fetch5) > 0)
{
  echo "<font color='red'>انتهت الجولة</font>";
}
else if (mysql_num_rows($result5) == 0 && mysql_num_rows($fetch5) == 0)
{
  echo "<font color='red'>بانتظار الإعادة</font>";
}
else
{
  echo "<font color='red'>تم نزول التتار</font>";
}
  ?></span></div>
</div>
</a>
</div>
</div>

<div class="server-item2">
<div class="serverItem">
<a href="//tatarwars-fv7-ex-lts-last.smartservs.net/register" title="" class="server-href 
                    filter ">
<img loading="lazy" src="/core-f/style-f/ndix/images/welten/en1_big.jpg?1.3" alt="" title="">
<div class="serverData">
<div class="serverInfos">اللاعبون: <span class="playerCounts"><?php echo $players_count1; ?> لاعب</span></div>
<div class="serverInfos">البداية : <span class="startedSince"><?php
if (mysql_num_rows($result) > 0 && mysql_num_rows($fetch) == 0) {
  echo "<b>$remaining_days</b> أيام و <b>$remaining_hours</b> ساعة";
}
else if (mysql_num_rows($result) > 0  && mysql_num_rows($fetch) > 0)
{
  echo "<font color='blue'>لم تبدأ بعد</font>";
}
else if (mysql_num_rows($result) == 0  && mysql_num_rows($fetch) > 0)
{
  echo "<font color='red'>انتهت الجولة</font>";
}
else if (mysql_num_rows($result) == 0 && mysql_num_rows($fetch) == 0)
{
  echo "<font color='red'>بانتظار الإعادة</font>";
}
else
{
  echo "<font color='red'>تم نزول التتار</font>";
}
  ?></span></div>
</div>
</a>
</div>
<div class="serverItem">
<a href="//tatarwars-fv7-ex-lts-last.smartservs.net/register" title="" class="server-href 
                    filter ">
<img loading="lazy" src="/core-f/style-f/ndix/images/welten/en2_big.jpg?1.3" alt="" title="">
<div class="serverData">
<div class="serverInfos">اللاعبون: <span class="playerCounts"><?php echo $players_count2; ?> لاعب</span></div>
<div class="serverInfos">البداية : <span class="startedSince"><?php
if (mysql_num_rows($result2) > 0 && mysql_num_rows($fetch2) == 0) {
  echo "<b>$remaining_days</b> أيام و <b>$remaining_hours</b> ساعة";
}
else if (mysql_num_rows($result2) > 0  && mysql_num_rows($fetch2) > 0)
{
  echo "<font color='blue'>لم تبدأ بعد</font>";
}
else if (mysql_num_rows($result2) == 0  && mysql_num_rows($fetch2) > 0)
{
  echo "<font color='red'>انتهت الجولة</font>";
}
else if (mysql_num_rows($result2) == 0 && mysql_num_rows($fetch2) == 0)
{
  echo "<font color='red'>بانتظار الإعادة</font>";
}
else
{
  echo "<font color='red'>تم نزول التتار</font>";
}
  ?></span></div>
</div>
</a>
</div>
<div class="serverItem">
<a href="//tatarwars-fv7-ex-lts-last.smartservs.net/register" title="" class="server-href 
                    filter ">
<img loading="lazy" src="/core-f/style-f/ndix/images/welten/en3_big.jpg?1.3" alt="" title="">
<div class="serverData">
<div class="serverInfos">اللاعبون: <span class="playerCounts"><?php echo $players_count1; ?> لاعب</span></div>
<div class="serverInfos">البداية : <span class="startedSince"><?php
if (mysql_num_rows($result3) > 0 && mysql_num_rows($fetch3) == 0) {
  echo "<b>$remaining_days</b> أيام و <b>$remaining_hours</b> ساعة";
}
else if (mysql_num_rows($result3) > 0  && mysql_num_rows($fetch3) > 0)
{
  echo "<font color='blue'>لم تبدأ بعد</font>";
}
else if (mysql_num_rows($result3) == 0  && mysql_num_rows($fetch3) > 0)
{
  echo "<font color='red'>انتهت الجولة</font>";
}
else if (mysql_num_rows($result3) == 0 && mysql_num_rows($fetch3) == 0)
{
  echo "<font color='red'>بانتظار الإعادة</font>";
}
else
{
  echo "<font color='red'>تم نزول التتار</font>";
}
  ?></span></div>
</div>
</a>
</div>
<div class="serverItem">
<a href="//tatarwars-fv7-ex-lts-last.smartservs.net/register" title="" class="server-href 
                    filter ">
<img loading="lazy" src="/core-f/style-f/ndix/images/welten/en4_big.jpg?1.3" alt="" title="">
<div class="serverData">
<div class="serverInfos">اللاعبون: <span class="playerCounts"><?php echo $players_count4; ?> لاعب</span></div>
<div class="serverInfos">البداية : <span class="startedSince"><?php
if (mysql_num_rows($result4) > 0 && mysql_num_rows($fetch4) == 0) {
  echo "<b>$remaining_days</b> أيام و <b>$remaining_hours</b> ساعة";
}
else if (mysql_num_rows($result4) > 0  && mysql_num_rows($fetch4) > 0)
{
  echo "<font color='blue'>لم تبدأ بعد</font>";
}
else if (mysql_num_rows($result4) == 0  && mysql_num_rows($fetch4) > 0)
{
  echo "<font color='red'>انتهت الجولة</font>";
}
else if (mysql_num_rows($result4) == 0 && mysql_num_rows($fetch4) == 0)
{
  echo "<font color='red'>بانتظار الإعادة</font>";
}
else
{
  echo "<font color='red'>تم نزول التتار</font>";
}
  ?></span></div>
</div>
</a>
</div>
<div class="serverItem">
<a href="//tatarwars-fv7-ex-lts-last.smartservs.net/register" title="" class="server-href 
                    filter ">
<img loading="lazy" src="/core-f/style-f/ndix/images/welten/en5_big.jpg?1.3" alt="" title="">
<div class="serverData">
<div class="serverInfos">اللاعبون: <span class="playerCounts"><?php echo $players_count5; ?> لاعب</span></div>
<div class="serverInfos">البداية : <span class="startedSince"><?php
if (mysql_num_rows($result5) > 0 && mysql_num_rows($fetch5) == 0) {
  echo "<b>$remaining_days</b> أيام و <b>$remaining_hours</b> ساعة";
}
else if (mysql_num_rows($result5) > 0  && mysql_num_rows($fetch5) > 0)
{
  echo "<font color='blue'>لم تبدأ بعد</font>";
}
else if (mysql_num_rows($result5) == 0  && mysql_num_rows($fetch5) > 0)
{
  echo "<font color='red'>انتهت الجولة</font>";
}
else if (mysql_num_rows($result5) == 0 && mysql_num_rows($fetch5) == 0)
{
  echo "<font color='red'>بانتظار الإعادة</font>";
}
else
{
  echo "<font color='red'>تم نزول التتار</font>";
}
  ?></span></div>
</div>
</a>
</div>
</a>
</div>

</div>
</div>
</div>
</div>
</div>
<script data-cfasync="false" src="/core-f/style-f/ndix/email-decode.min.js"></script><script src="/core-f/style-f/ndix/swiper-bundle.min.js" integrity="sha512-0N/5ZOjfsh3niel+5dRD40HQkFOWaxoVzqMVAHnmAO2DC3nY/TFB7OYTaPRAFJ571IRS/XRsXGb2XyiFLFeu1g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="/core-f/style-f/ndix/aos.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="/core-f/style-f/ndix/js.js?1.6"></script>
<script src="/core-f/style-f/ndix/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="/core-f/style-f/ndix/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    document.addEventListener( 'DOMContentLoaded', function() {

        var splide = new Splide( '.splide', {
            drag   : true,
            autoWidth: false,
            pauseOnHover: false,
            pagination: false,
            arrows: true,
            pauseOnFocus: false,
            direction:"rtl",
            perPage: 2,
            classes: {
                arrows: 'splide__arrows your-class-arrows',
                arrow : 'splide__arrow your-class-arrow',
                prev  : 'splide__arrow--prev your-class-prev',
                next  : 'splide__arrow--next your-class-next',
            },
            padding: {
                left: '50px',
                right: '50px'
            },
            breakpoints: {
                1024: {
                    perPage: 2,

                },
                640: {
                    perPage: 1,
                    padding: {
                        left: '36px',
                        right: '36px'
                    }

                },
            },
            autoScroll: {
                pauseOnHover: false,
                pauseOnFocus: false,
                speed: 1.3,
            },
        } );
        splide.mount();
        $(".custom-carousel").owlCarousel({
             stagePadding: 60,
            autoplay:false,
            autoWidth: true,
            lazyLoad: true,
            loop: true,
            rtl:true,
            dots:false,
            animateIn:'flipInX'
});
        $(document).ready(function () {
            $(".custom-carousel .item").click(function () {
                $(".custom-carousel .item").not($(this)).removeClass("active");
                $(this).toggleClass("active");

            });
        });

    } );
</script>
</body>
</html>