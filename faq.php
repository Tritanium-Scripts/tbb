<?

/* faq.php - Zeigt das FAQ an (c) 2001-2002 Tritanium Scripts */

require_once("auth.php");
require_once("language/german/lng_faq.php");

echo navbar("F.A.Q.");

// Als erstes die Smilie-Tabelle vorbereiten
	$sm_file = myfile("vars/smilies.var");
	$smilies = "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\">";

	for($i = 0; $i < sizeof($sm_file); $i++) {
		$akt_sm = myexplode($sm_file[$i]);
		$smilies .= "<tr><td class=\"td1\"><span class=\"norm\">$akt_sm[1]</span></td><td class=\"td1\"><img border=\"0\" src=\"$akt_sm[2]\"></td></tr>";
	}

	$smilies .= "</table>";

// Nun wird der Tabellenkopf ausgegeben
	?>
		<table class="tbl" border="0" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>" width="<?=$twidth?>">
		<tr><th class="thnorm"><span class="thnorm"><?=$lng['faq']['F.A.Q.']?></span></th></tr>
		<tr><td class="kat"><span class="kat"><?=$lng['faq']['Overview']?></span></td></tr>
		<tr><td class="td1"><span class="norm">
	<?

// Nun kommen die einzelnen Fragen
	for($i = 0; $i < sizeof($lng['faq']['faq']); $i++) {
		echo "<a href=\"#$i\">".$lng['faq']['faq'][$i][0].'</a><br>';
	}
	echo "</span></td></tr>";

// Nun kommen die Antworten
	for($i = 0; $i < sizeof($lng['faq']['faq']); $i++) {
		echo "<tr><td class=\"kat\"><span class=\"kat\"><a name=\"$i\">".$lng['faq']['faq'][$i][0]."</a></span></td></tr>";
		echo "<tr><td class=\"td1\"><span class=\"norm\">".str_replace('{SMILIES}',$smilies,$lng['faq']['faq'][$i][1])."</span></td></tr>";
	}

echo "</table></center>";

wio_set("faq") // WhoIsOnline Konfiguration

?>