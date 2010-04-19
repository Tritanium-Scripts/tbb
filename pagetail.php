<?

/* pagetail.php - Zeigt das Copyrightinfo und Seitenaufbauzeit an (c) 2001-2002 Tritanium Scripts */

require_once("auth.php");

/*****************************************************************************************
*  An alle, die diesen Code verändern: Bitte löscht/verändert nicht die Copyrightinfos!  *
*   @ all who modify this code: please do not remove/modify the copyright information!   *
*****************************************************************************************/

echo "<br>";

if($user_logged_in == 1 && $ad != 1) include("spmbox.php"); // Falls der User eingeloggt ist und man nicht in der Administr. ist, kann die kleine PM-Box angezeigt werden
if($user_data['status'] == 1 && $ad != 1) echo "<br><center><span class=\"norm\"><a class=\"norm\" href=\"adminpanel.php$MYSID1\">".$lng['Administration']."</a></span></center>"; // Falls User Admin ist, kann ein Link zur Administration angezeigt werden

?>

<center><br><span class="norm"><a class="norm" href="mailto:<?=$config['site_contact']?>"><?=$lng['pagetail']['Contact']?></a> | <a class="norm" href="<?=$config['site_address']?>"><?=$config['site_name']?></a> | <a class="norm" href="index.php?faction=regeln<?=$MYSID2?>"><?=$lng['pagetail']['Boardrules']?></a><br>

<br><br><div class="copyr">Tritanium Bulletin Board 1.2.1<br>&copy; 2001/2002 <a class="copyr" href="http://www.tritanium-scripts.de" target="_blank" onfocus="this.blur()">Tritanium Scripts</a></div>

<?


if($config['show_site_creation_time'] == 1) {
	// Konfiguration der Endzeit (wird unten benötigt, um Ladezeit der Seite anzuzeigen)
		$mtime = explode(" ",microtime());
		$mtime = $mtime[1] + $mtime[0];
	// Ende der Konfiguration der Endzeit
	if($gzip_compressed == 1) $gzip_stat = $lng['pagetail']['gzip']['enabled'];
	else $gzip_stat = $lng['pagetail']['gzip']['disabled'];
	echo '<br><br><span class="stat">';
	echo sprintf($lng['pagetail']['site_creation_time'],round($mtime-$starttime,6));  // Zeigt die Ladezeit an
	echo '<br>';
	echo sprintf($lng['pagetail']['files_processed'],$file_counter); // Zeigt die Anzahl der verarbeiteten Dateien an
	echo '<br>';
	echo sprintf($lng['pagetail']['gzip']['text'],$gzip_stat); // Zeigt den GZIP-Status an
	echo '</span></center>';
}

echo "</div></body></html>";

if($gzip_compressed == 1 || $config['activate_ob'] == 1) ob_end_flush();

?>