<?

/* pageheader.php - Den Seitenkopf anzeigen und Einstellungen vornehmen (c) 2001-2002 Tritanium Scripts */

require_once("auth.php");

// Konfiguration der Startzeit (um die Ladezeit der Seite später festlegen zu können)
	if($config['show_site_creation_time'] == 1) {
		$mtime = explode(" ",microtime());
		$starttime = $mtime[1] + $mtime[0];
	}
// Ende der Konfiguration der Startzeit

$gzip_compressed = 0;
if($config['use_gzip_compression'] == 1) {
	if(phpversion() >= "4.0.4") {
		if(extension_loaded("zlib")) {
			ob_start("ob_gzhandler");
			$gzip_compressed = 1;
		}
	}
}

if($gzip_compressed == 0 && $config['activate_ob'] == 1) {
	ob_start();
}

// Konfiguration der Session-IDs
	if((@get_cfg_var("session.use_trans_sid") != 1 && SID != '') || $config['append_sid_url'] == 1 || $config['activate_ob'] == 1 || $gzip_compressed == 1) { // Falls den URLs die SID nicht automatisch angehängt wird
		$SID = SID;
		$MYSID1 = "?$HSID"; // Sessionname mit ?
		$MYSID2 = "&$HSID"; // Sessionname mit &
	}
	else {
		$SID = "";
		$MYSID1 = "";
		$MYSID2 = "";
	}
// Ende der Konfiguration der Session-IDs


// Konfiguration der Tool-Leiste und des "Eingeloggt"-Status
	if($config['wio'] == 1) $showwio = " | <a class=\"tbar\" href=\"index.php?faction=wio$MYSID2\">".$lng['WhoIsOnline']."</a>"; else $showwio = "";
	if($config['activate_mlist'] == 1) $showmlist = " | <a class=\"tbar\" href=\"index.php?faction=mlist$MYSID2\">".$lng['Memberlist']."</a>"; else $showmlist = "";

	if($user_logged_in == 1) {
		$user_status = sprintf($lng['pheader']['Logged_in_as'],$user_data['nick']);
		$tools = "<a class=\"tbar\" href=\"index.php?faction=profile&mode=edit&profile_id=$user_id$MYSID2\">".$lng['pheader']['My_Profile']."</a> | <a class=\"tbar\" href=\"index.php?faction=faq$MYSID2\">".$lng['pheader']['FAQ']."</a> | <a class=\"tbar\" href=\"index.php?faction=pm&mode=overview$MYSID2\">".$lng['pheader']['Private_Messages']."</a> | <a class=\"tbar\" href=\"index.php?faction=search$MYSID2\">".$lng['pheader']['Search']."</a>$showwio$showmlist | <a class=\"tbar\" href=\"index.php?faction=logout$MYSID2\">".$lng['pheader']['Logout']."</a>";
	}
	else {
		$user_status = $lng['pheader']['Not_logged_in'];
		$tools = "<a class=\"tbar\" href=\"index.php?faction=register$MYSID2\">".$lng['pheader']['Register']."</a> | <a class=\"tbar\" href=\"index.php?faction=faq$MYSID2\">".$lng['pheader']['FAQ']."</a> | <a class=\"tbar\" href=\"index.php?faction=search$MYSID2\">".$lng['pheader']['Search']."</a>$showwio$showmlist | <a class=\"tbar\" href=\"index.php?faction=login$MYSID2\">".$lng['Login']."</a>";
	}
// Ende der Konfiguration der Tool-Leiste und des "Eingeloggt"-Status

// Konfiguration der (Keine Ahnung, wie ich das nennen soll...)
	if(!isset($thread) && isset($forum_id) && $ad != 1) $upb_info = "<a href=\"index.php?faction=newtopic&forum_id=$forum_id$MYSID2\"><img src=\"images/newtopic.gif\" border=\"0\"></a>&nbsp;<a href=\"index.php?faction=newpoll&forum_id=$forum_id$MYSID2\"><img src=\"images/newpoll.gif\" border=\"0\"></a>";
	elseif(isset($thread) && isset($forum_id) && $ad != 1) $upb_info = "<a href=\"index.php?faction=newtopic&forum_id=$forum_id$MYSID2\"><img src=images/newtopic.gif border=0></a>&nbsp;<a href=\"index.php?faction=newpoll&forum_id=$forum_id$MYSID2\"><img src=\"images/newpoll.gif\" border=\"0\"></a>&nbsp;<a href=\"index.php?faction=reply&thread_id=$thread&forum_id=$forum_id$MYSID2\" onfocus=\"this.blur()\"><img border=0 src=images/newreply.gif></a>";
	elseif(isset($pmbox_id) && !isset($pm_id) && $ad != 1) $upb_info = "<a href=\"index.php?faction=pm&pmbox_id=$pmbox_id&mode=send$MYSID2\">".$lng['pheader']['pm_links']['New']."</a>";
	elseif(isset($pmbox_id) && isset($pm_id) && $ad != 1) $upb_info = "<a href=\"index.php?faction=pm&pmbox_id=$pmbox_id&mode=send$MYSID2\">".$lng['pheader']['pm_links']['New']."</a> | <a href=\"index.php?faction=pm&pmbox_id=$pmbox_id&mode=reply&pm_id=$pm_id$MYSID2\">".$lng['pheader']['pm_links']['Reply']."</a> | <a href=\"index.php?faction=pm&pmbox_id=$pmbox_id&mode=kill&pm_id=$pm_id$MYSID2\">".$lng['pheader']['pm_links']['Delete']."</a>";
	else $upb_info = $config['forum_name'];
// Ende der Konfiguration der (Immer noch keine Ahnung, wie ich das nennen soll...)

?>
<html>
<head>
<title><?=$config['forum_name']?></title>

<link rel="stylesheet" href="<?=$config['temp_css_file']?>" type="text/css" />

<script language="JavaScript">
	window.defaultStatus = " ";
</script>
</head>
<body>
<div id="main">
<br><center><table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<?
if($config['forum_logo'] != '') {
?>
 <td align="center" valign="middle" width="50%"><a href="index.php<?=$MYSID1?>" onfocus="this.blur()"><img src="<?=$config['forum_logo']?>" border=0></a></td>
 <td align="center" valign="middle" width="50%"><span class="finfo"><?=$upb_info?></span><br><span class="tbar"><?=$tools?></span></td>
<?
}
else {
 ?> <td align="center" valign="middle" width="100%"><span class="finfo"><?=$upb_info?></span><br><span class="tbar"><?=$tools?></span></td> <?
}

echo "</tr></table></center><br>";

?>