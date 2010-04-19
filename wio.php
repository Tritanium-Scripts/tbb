<?

/* wio.php - Zeigt an, wer und wo wer momentan im Forum aktiv ist (c) 2001-2002 Tritanium Scripts */

require_once("auth.php");

if($config['wio'] == 1) { // Nur ausführen, wenn WIO aktiviert ist

wio_koe(); // Alte Einträge löschen

if($faction != "wio") {

$wio_file = myfile("vars/wio.var"); $wio_file_size = sizeof($wio_file);
	$n1 = 0; $n2 = 0; $online_member = array();
	for($i = 0; $i < $wio_file_size; $i++) {
		$aktueller_wio = myexplode($wio_file[$i]);
		$neue_zeit = $aktueller_wio[0] + ($config['wio_timeout'] * 60);
		if ($neue_zeit >= time()) {
			if (substr($aktueller_wio[1],0,5) == "guest") $n1++;
			else { $online_member[$n2] = "<a class=\"small\" href=\"index.php?faction=profile&profile_id=$aktueller_wio[1]$MYSID2\">".get_user_name($aktueller_wio[1])."</a>"; $n2++; }
		}
		if($n1 == 0) $guests = $lng['wio']['No_guests']; elseif($n1 == 1) $guests = $lng['wio']['1_Guest']; else $guests = $n1.' '.$lng['wio']['Guests'];
		if($online_member[0] == "") $members = $lng['wio']['No_members']; else $members = $lng['wio']['Members'].': '.implode($online_member,", ");
	}
	?>
		<br><center><table class="tbl" width=<?=$twidth?> border=0 cellspacing=<?=$tspacing?> cellpadding=<?=$tpadding?>>
		<tr><td class="thnorm"><span class="thnorm"><?=$lng['WhoIsOnline']?></span></td></tr>
		<tr><td class="td1"><span class="small"><?=sprintf($lng['wio']['text'],$config['wio_timeout'])?><br><?=$members?><br><?=$guests?></td></tr>
		</table></center>
	<?
}
else {
	include("pageheader.php");
	wio_set("wio");
	echo navbar($lng['WhoIsOnline']);
	?>
		<table class="tbl" width=<?=$twidth?> border=0 cellspacing=<?=$tspacing?> cellpadding=<?=$tpadding?>>
		<tr><td class="thnorm" colspan="2"><span class="thnorm"><?=$lng['WhoIsOnline']?></span></td></tr>
	<?

	$wio_file = myfile("vars/wio.var"); $wio_file_size = sizeof($wio_file);
	for ($i = 0; $i < $wio_file_size; $i++) {
		$wiodat = myexplode($wio_file[$i]);
		if($wiodat[0] + ($config['wio_timeout'] * 60) >= time()) {
			$wiodat_where = explode(",",$wiodat[2]);

			if($wiodat_where[0] == "index") $wio_text = sprintf($lng['wio']['where']['index'],"<a href=\"index.php$MYSID1\">",'</a>');
			elseif($wiodat_where[0] == "forum") $wio_text = sprintf($lng['wio']['where']['forum'],"<a href=\"index.php?mode=viewforum&forum_id=$wiodat_where[1]$MYSID2\">",'</a>');
			elseif($wiodat_where[0] == "view_topic") $wio_text = sprintf($lng['wio']['where']['view_topic'],"<a href=\"index.php?mode=viewthread&forum_id=$wiodat_where[1]&thread=$wiodat_where[2]$MYSID2\">",'</a>');
			elseif($wiodat_where[0] == "edit") $wio_text = $lng['wio']['where']['edit_topic'];
			elseif($wiodat_where[0] == "faq") $wio_text = sprintf($lng['wio']['where']['faq'],"<a href=\"index.php?faction=faq$MYSID2\">",'</a>');
			elseif($wiodat_where[0] == "login") $wio_text = $lng['wio']['where']['login'];
			elseif($wiodat_where[0] == "logout") $wio_text = $lng['wio']['where']['logout'];
			elseif($wiodat_where[0] == "newtopic") $wio_text = $lng['wio']['where']['new_topic'];
			elseif($wiodat_where[0] == "pm") $wio_text = $lng['wio']['where']['pm'];
			elseif($wiodat_where[0] == "regeln") $wio_text = $lng['wio']['where']['rules'];
			elseif($wiodat_where[0] == "search") $wio_text = $lng['wio']['where']['search'];
			elseif($wiodat_where[0] == "wio") $wio_text = $lng['wio']['where']['wio'];
			elseif($wiodat_where[0] == "reply") $wio_text = $lng['wio']['where']['reply'];
			elseif($wiodat_where[0] == "register") $wio_text = $lng['wio']['where']['register'];
			elseif($wiodat_where[0] == "profile") $wio_text = $lng['wio']['where']['profile'];
			elseif($wiodat_where[0] == "topic") $wio_text = $lng['wio']['where']['edit_topic'];
			elseif($wiodat_where[0] == "ad") $wio_text = $lng['wio']['where']['ad'];
			elseif($wiodat_where[0] == "newpoll") $wio_text = $lng['wio']['where']['newpoll'];

			if (substr($wiodat[1],0,5) == "guest") $wio_who = $lng['wio']['Guest'].substr($wiodat[1],5,5);
			else $wio_who = get_user_name($wiodat[1]);

			?>
				<tr><td class="td1"><span class="norm"><?=$wio_who?></span></td><td class="td2"><span class="norm"><?=$wio_text?></span></td></tr>
			<?
		}
	}
	echo "</table></center>";
	include("pagetail.php");
}

}

?>