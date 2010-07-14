<?

/* ext_wio.php - Zeigt WIO an (c) 2001/2002 2001-2002 Tritanium Scripts */

// Kopieren sie diese Datei in das Verzeichnis ihrer Website und fügen sie an der
// gewünschten Stelle in ihrem PHP Code ein: include("ext_wio.php");
// Allerdings müssen sie zumindest noch die folgende Einstellung vornehmen:
// Achtung: Funktioniert nicht bei modifiziertem $config['datapath'] (datapath.php)!!

// (relativer) Pfad zum Forum
$ext_path_to_forum = "/path/to/forum";

// Die Anzahl der letzten Posts, die angezeigt werden soll (maximal 10!)
$lposts_number = 5;


/* Ab hier brauchen sie nichts mehr zu ändern */

require_once("$ext_path_to_forum/ext_functions.php");
require_once("$ext_path_to_forum/loadset.php");

$wio_file = file("$ext_path_to_forum/vars/wio.var"); $wio_file_size = sizeof($wio_file);
$n1 = 0; $n2 = 0;
for($i = 0; $i < $wio_file_size; $i++) {
	$aktueller_wio = explode("\t",$wio_file[$i]);
	$neue_zeit = $aktueller_wio[0] + ($config['wio_timeout'] * 60);
	if ($neue_zeit >= time()) {
		if (substr($aktueller_wio[1],0,5) == "guest") $n1++;
		else { $online_member[$n2] = "<a href=\"".$config['address_to_forum']."/index.php?upb=profile&profile_id=$aktueller_wio[1]\">".get_user_name("$ext_path_to_forum/members/$aktueller_wio[1].xbb")."</a>"; $n2++; }
	}
	if($n1 == 0) $guests = "Keine Gäste"; elseif($n1 == 1) $guests = "1 Gast"; else $guests = "$n1 Gäste";
	if($online_member[0] == "") $members = "Keine Mitglieder"; else $members = "Mitglieder: " . implode($online_member,", ");
}
echo "In den letzten ".$config['wio_timeout']." Minuten waren im Forum aktiv:<br>$members<br>$guests";

?>