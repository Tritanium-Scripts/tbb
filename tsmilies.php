<?

/* tsmilies.php - Zeigt die Smilies in der Topic-Übersicht an (c) 2001-2002 Tritanium Scripts */

require_once("auth.php");

$tsm_file = myfile("vars/tsmilies.var"); $tsm_file_size = sizeof($tsm_file);
for($i = 0; $i < $tsm_file_size; $i++) {
	$akt_tsm = myexplode($tsm_file[$i]);
	if ($i == 0) $checked[tsmilie] = " checked"; else $checked[tsmilie] = "";
	echo "<input type=\"radio\" name=\"tsmilie\" value=".$akt_tsm[0]." onfocus=\"this.blur()\"$checked[tsmilie]>&nbsp;<img border=0 src=\"".$akt_tsm[1]."\">&nbsp;&nbsp; ";
	if(($i + 1) % 7 == 0) echo "<br>"; // Alle 7 TSmilies eine neue Zeile anfangen
}

?>