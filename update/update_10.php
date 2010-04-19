<?

/* update_10.php - Updatet Version 1.0b auf die aktuelle Version (c) 2001-2002 Tritanium Scripts */

function myfwrite($file,$towrite,$mode) {
	$set_chmod = 0;
	if(!file_exists($file)) $set_chmod = 1; // Falls Datei nicht existiet, später 777 "chmoden"
	$fp = fopen($file,$mode.'b') or die("Dateifehler: Datei: $file; Modus: $mode"); flock($fp,LOCK_EX);
	if(!is_array($towrite)) { // Falls Variable ein Array ist, anders schreiben
		fwrite($fp,$towrite);
	}
	else {
		for($i = 0; $i < sizeof($towrite); $i++) {
			fwrite($fp,$towrite[$i]);
		}
	}
	flock($fp,LOCK_UN); fclose($fp);
	if($set_chmod == 1) chmod($file,0777);
}

function chchar($data) {
	if($file = file($data)) {
		for($i = 0; $i < sizeof($file); $i++) {
			$file[$i] = str_replace("þ","\t",$file[$i]);
		}
		myfwrite($data,$file,"w");
	}
}

function killnl($text) {
	return str_replace("\n","",str_replace("\r\n","",$text));
}


echo "<html><head><title>UPB Update</title></head><body>";


echo "Überprüfe Dateien... ";
if(!file_exists("language")) die("FEHLER: Uploaden sie erst den Ordner &quot;language&quot;");
if(!file_exists("vars")) die("Bitte erstellen sie erst den Ordner &quot;vars&quot; und geben ihm die Rechte 777!");
if(!file_exists("logs")) die("Bitte erstellen die erst den Ordner &quot;logs&quot; und geben ihm die Rechte 777!");
if(!file_exists("polls")) die("Bitte erstellen sie erst den Ordner &quot;polls&quot; und geben ihm die Rechte 777!");
if(!file_exists("templates")) echo("Warnung: Der Ordner \"templates\" existiert nicht!<br>");
if(!file_exists("styles")) echo("Warnung: Der Ordner \"styles\" existiert nicht!<br>");
if(!file_exists("foren/.htaccess")) die("Bitte uploaden sie erst die Datei \".htaccess\" in den Ordner \"foren\"!");
if(!file_exists("members/.htaccess")) die("Bitte uploaden sie erst die Datei \".htaccess\" in den Ordner \"members\"!");
if(!file_exists("logs/.htaccess")) die("Bitte uploaden sie erst die Datei \".htaccess\" in den Ordner \"logs\"!");
if(!file_exists("vars/.htaccess")) die("Bitte uploaden sie erst die Datei \".htaccess\" in den Ordner \"vars\"!");
if(!file_exists("polls/.htaccess")) die("Bitte uploaden sie erst die Datei \".htaccess\" in den Ordner \"polls\"!");

// Spezial Schreibprüfung
	if(!copy("foren/ip.xbb","vars/ip.var")) die("Fehler: kann in /vars keine Dateien erstellen! Bitte setzten sie die Rechte des Ordners auf 777!");
	if(!chmod("vars/ip.var",0777)) die("Fehler: kann in /vars die Rechte der Dateien nicht auf 777 setzten!");
	unlink("foren/ip.xbb");
// Ende davon

echo "Überprüfung abgeschlossen<br>";

echo "Verschiebe Einstellungsdateien... ";
copy("foren/foren.xbb","vars/foren.var") or die("Kopierfehler!"); unlink("foren/foren.xbb"); chmod("vars/foren.var",0777);
copy("foren/forens.xbb","vars/forens.var") or die("Kopierfehler!"); unlink("foren/forens.xbb"); chmod("vars/forens.var",0777);
copy("foren/wio.xbb","vars/wio.var") or die("Kopierfehler!"); unlink("foren/wio.xbb"); chmod("vars/wio.var",0777);
copy("foren/kg.xbb","vars/kg.var") or die("Kopierfehler!"); unlink("foren/kg.xbb"); chmod("vars/kg.var",0777);
copy("foren/kgs.xbb","vars/kgs.var") or die("Kopierfehler!"); unlink("foren/kgs.xbb"); chmod("vars/kgs.var",0777);
copy("foren/news.xbb","vars/news.var") or die("Kopierfehler!"); unlink("foren/news.xbb"); chmod("vars/news.var",0777);
copy("foren/rank.xbb","vars/rank.var") or die("Kopierfehler!"); unlink("foren/rank.xbb"); chmod("vars/rank.var",0777);
copy("foren/ranks.xbb","vars/ranks.var") or die("Kopierfehler!"); unlink("foren/ranks.xbb"); chmod("vars/ranks.var",0777);
copy("foren/smilies.xbb","vars/smilies.var") or die("Kopierfehler!"); unlink("foren/smilies.xbb"); chmod("vars/smilies.var",0777);
copy("foren/smiliess.xbb","vars/smiliess.var") or die("Kopierfehler!"); unlink("foren/smiliess.xbb"); chmod("vars/smiliess.var",0777);
copy("foren/tsmilies.xbb","vars/tsmilies.var") or die("Kopierfehler!"); unlink("foren/tsmilies.xbb"); chmod("vars/tsmilies.var",0777);
copy("foren/tsmiliess.xbb","vars/tsmiliess.var") or die("Kopierfehler!"); unlink("foren/tsmiliess.xbb"); chmod("vars/tsmiliess.var",0777);
echo "erfolgreich!<br>";

echo "Update Memberdaten... ";
$membersfile = file("members/members.xbb"); $membersfile = $membersfile[0] + 1;
for($j = 0; $j < $membersfile; $j++) {
	if($aktmember = file("members/$j.xbb")) {
		if(str_replace("\n","",str_replace("\r\n","",$aktmember[4])) == "5") {
			unlink("members/$j.xbb");
			unlink("members/$j.pm");
		}
		else {
			$aktmember_size = sizeof($aktmember);
			if($aktmember_size < 17) {
				$dif = 17 - $aktmember_size;
				for($i = 0; $i < $dif; $i++) {
					$num = $aktmember_size + $i;
					$aktmember[$num] = "\r\n";
				}
			}

			$aktmember[11] = "1\r\n";
			$aktmember[14] = "1,1\r\n";

			$fp = fopen("members/$j.xbb","w") or die("Datei-Fehler!"); flock($fp,LOCK_EX); $new_size = sizeof($aktmember);
			for($i = 0; $i < $new_size; $i++) {
				fwrite($fp, $aktmember[$i]);
			}
			flock($fp,LOCK_UN); fclose($fp);
		}
	}
}
echo "erfolgreich!<br>";

echo "Erstelle Zensurdatei... ";
$fp = fopen("vars/cwords.var","w") or die("Dateifehler!"); fclose($fp); chmod("vars/cwords.var",0777);
echo "erfolgreich!<br>";

echo "Erstelle Datei für die letzten 10 Beiträge... ";
$fp = fopen("vars/lposts.var","w") or die("Dateifehler!"); fclose($fp); chmod("vars/lposts.var",0777);
echo "erfolgreich!<br>";

echo "Erstelle Datei für die \"heutigen\" Beiträge... ";
$fp = fopen("vars/todayposts.var","w") or die("Dateifehler!"); fclose($fp); chmod("vars/todayposts.var",0777);
echo "erfolgreich!<br>";

echo "Update allgemeine Daten... ";
chchar('vars/cwords.var');
chchar('vars/ip.var');
chchar('vars/kg.var');
chchar('vars/lposts.var');
chchar('vars/news.var');
chchar('vars/rank.var');
chchar('vars/smilies.var');
chchar('vars/todayposts.var');
chchar('vars/tsmilies.var');
chchar('vars/wio.var');
chchar('vars/foren.var');
echo "erfolgreich!<br>";

echo "Update Foren/Themen... ";
$forums_file = file('vars/foren.var');
for($i = 0; $i < sizeof($forums_file); $i++) {
	$akt_forum = explode("\t",$forums_file[$i]);

	myfwrite("foren/$akt_forum[0]-rights.xbb","","w");

	$akt_forum_topics = file("foren/$akt_forum[0]-threads.xbb");

	for($j = 0; $j < sizeof($akt_forum_topics); $j++) {
		$akt_forum_topics[$j] = killnl($akt_forum_topics[$j]);
		chchar("foren/$akt_forum[0]-$akt_forum_topics[$j].xbb");
		$akt_topic = file("foren/$akt_forum[0]-$akt_forum_topics[$j].xbb");
		$akt_topic_data = explode("\t",$akt_topic[0]);
		if($akt_topic_data[0] == 'open') {
			$akt_topic_data[0] = '1';
			$akt_topic[0] = implode("\t",$akt_topic_data);
		}
		$akt_topic[0] = killnl($akt_topic[0])."\t\t\t\t\t\t\n";
		myfwrite("foren/$akt_forum[0]-$akt_forum_topics[$j].xbb",$akt_topic,"w");
	}
	switch($akt_forum[8]) {
		case '2': // Normal
			$akt_forum[10] = "1,1,1,1,1,1,,,,,,,,,";
		break;
		case '3': // Privat
			$akt_forum[10] = ",,,,,,,,,,,,,,";
		break;
		case '4': // Readonly
			$akt_forum[10] = "1,,,,,,1,,,,,,,,";
		break;
	}
	$akt_forum[8] = '';
	$forums_file[$i] = implode("\t",$akt_forum);
}
myfwrite('vars/foren.var',$forums_file,'w');
echo "erfolgreich!<br>";

echo "Update Userdaten... ";
$members = file('members/members.xbb'); $members = $members[0]+1;
for($i = 1; $i < $members; $i++) {
	if(file_exists("members/$i.pm")) chchar("members/$i.pm");
}
echo "erfolgreich!<br>";

echo "Erstelle Gruppendatei... ";
myfwrite("vars/groups.var","","w"); // Gruppendatei erstellen
echo "erfolgreich!<br>";

echo "Erstelle Umfragendatei... ";
myfwrite("polls/polls.xbb","0","w"); // Polldatei erstellen
echo "erfolgreich!<br>";

echo "Verschiebe Daten... ";
copy("members/members.xbb","vars/last_user_id.var"); chmod("vars/last_user_id.var",0777); unlink("members/members.xbb");
$lid_file = file("vars/last_user_id.var");
$counter = 0;
for($i = 1; $i < $lid_file[0]+1; $i++) {
	if(file_exists("members/$i.xbb")) $counter++;
}
myfwrite("vars/member_counter.var",$counter,'w');
echo "erfolgreich!<br>";


echo "<br><br><br>Das Forum wurde erfolgreich aktualisiert!";

?>

</body></html>