<?

/* update_11.php - updatet Version 1.1 auf die aktuelle Version (c) 2001-2002 Tritanium Scripts */

echo "<html><head><title>UPB Update</title></head><body>";

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


echo "Überprüfe Dateien... ";
if(!file_exists("language")) die("FEHLER: Uploaden sie erst den Ordner &quot;language&quot;");
if(!file_exists("polls")) die("FEHLER: Erstellen sie erst den Ordner &quot;polls&quot; und geben sie ihm die Rechte 777!");
if(!file_exists("polls/.htaccess")) die("FEHLER: Uploaden sie erst die Datei .htaccess aus dem Ordner /update in den Ordner &quot;polls&quot;");
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