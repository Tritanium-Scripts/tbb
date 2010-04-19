<?

/* smilies.php - Zeigt die Smilies an, um sie in Beiträgen zu verwenden (c) 2001-2002 Tritanium Scripts */

require_once("auth.php");

$sm_file = myfile("vars/smilies.var"); $sm_file_size = sizeof($sm_file);
for ($i = 0; $i < $sm_file_size;$i++) {
	$akt_sm = myexplode($sm_file[$i]);
	echo "<a href=\"javascript:setsmile(' $akt_sm[1] ')\" onmouseover=\"status='Smilie einfügen';return true\" onfocus=\"this.blur()\"><img src=\"$akt_sm[2]\" border=0></a> ";
	if (($i + 1) % 6 == 0) echo "<br>"; // Alle 6 Smilies eine neue Zeile einfügen (später kann man das auch selber bestimmen...)
}

?>