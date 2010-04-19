<?

/* ext_lposts.php - Zeigt die letzen Beiträge an (c) 2001-2002 Tritanium Scripts */

// Kopieren sie diese Datei in das Verzeichnis ihrer Website und fügen sie an der
// gewünschten Stelle in ihrem PHP Code ein: include("ext_lposts.php");
// Allerdings müssen sie zumindest noch die folgende Einstellung vornehmen:

// (relativer) Pfad zum Forum
$ext_path_to_forum = "/path/to/forum";

// Die Anzahl der letzten Posts, die angezeigt werden soll (maximal 10!)
$lposts_number = 5;


/* Ab hier brauchen sie nichts mehr zu ändern */

require_once("$ext_path_to_forum/ext_functions.php");
require_once("$ext_path_to_forum/loadset.php");

if($lposts_number > 10) $lposts_number = 10; // Hier keine andere Zahl für 10 einsetzen! Es werden dann auch nicht mehr angezeigt!

$lposts = file("$ext_path_to_forum/vars/lposts.var"); $lposts = explode("\t",$lposts[0]);
$lposts_size = sizeof($lposts);	if($lposts_size > $lposts_number) $lposts_size = $lposts_number;

for($i = 0; $i < $lposts_size; $i++) {
	$akt_lpost = explode(",",$lposts[$i]);
	if(!file_exists("$ext_path_to_forum/foren/$akt_lpost[0]-$akt_lpost[1].xbb")) $post_link = "Gelöscht";
	else $post_link = "<a target=\"_blank\" href=\"".$config['address_to_forum']."/index.php?mode=viewthread&forum_id=$akt_lpost[0]&thread=$akt_lpost[1]\">".get_thread_name("$ext_path_to_forum/foren/$akt_lpost[0]-$akt_lpost[1].xbb")."</a>";
	echo $post_link." von <a target=\"_blank\" href=\"".$config['address_to_forum']."/index.php?upb=profile&profile_id=$akt_lpost[2]\">".get_user_name("$ext_path_to_forum/members/$akt_lpost[2].xbb")."</a> am ".makedatum($akt_lpost[3])."<br>";
}

?>