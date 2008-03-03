<?

/* ext_functions.php - Funktionen für die externen Scripte (c) 2001-2002 Tritanium Scripts */

// \r\n eines Strings löschen
function killnl($text) {
	return str_replace("\n","",str_replace("\r\n","",$text));
}

// Datumstring lesbar machen
function makedatum($text) {

	global $config;

	$x = substr($config['gmt_offset'],1,2)*3600 + substr($config['gmt_offset'],3,2)*60;
	if(substr($config['gmt_offset'],0,1) == "-") $x = -1*$x;

	$text = mktime(substr($text,8,2),substr($text,10,2),0,substr($text,4,2),substr($text,6,2),substr($text,0,4)) + $x + date("Z");
	$text = gmstrftime("%Y%m%d%H%M",$text);

	$jahr = substr($text,0,4);
	$monat = substr($text,4,2);
	switch ($monat) {
		case "01":
			$monat = "Januar";
			break;
		case "02":
			$monat = "Februar";
			break;
		case "03":
			$monat = "März";
			break;
		case "04":
			$monat = "April";
			break;
		case "05":
			$monat = "Mai";
			break;
		case "06":
			$monat = "Juni";
			break;
		case "07":
			$monat = "Juli";
			break;
		case "08":
			$monat = "August";
			break;
		case "09":
			$monat = "September";
			break;
		case "10":
			$monat = "Oktober";
			break;
		case "11":
			$monat = "November";
			break;
		case "12":
			$monat = "Dezember";
			break;
	}
	$tag = substr($text,6,2);
	$stunde = substr($text,8,2);
	$minute = substr($text,10,2);
	$text = "$tag. $monat $jahr $stunde:$minute";
	return $text;
}

// Topicname herausfinden
function get_thread_name($file) {
	if(!$topic_file = file($file)) $topic_name = "Gelöscht";
	else {
		$topic_info = myexplode($topic_file[0]);
		$topic_name = $topic_info[1];
	}
	return $topic_name;
}

// Benutzernamen aus Benutzerdatei extrahieren
function get_user_name($file) {
	if(strncmp($user_id,'0',1) == 0) $user_name = substr($user_id,1,strlen($user_id));
	elseif(!$user_daten = @file($file)) $user_name = "Gelöscht";
	else $user_name = killnl($user_daten[0]);
	return $user_name;
}

// Fakefunktion (bzw. ich hab grade keine Lust für einen anderen Namen...)
function myfile($file) {
	global $ext_path_to_forum;
	return @file($ext_path_to_forum.'/'.$file);
}

// String exploden (Version 1.2)
function myexplode($data) {
	return explode("\t",$data);
}

// file_exists mit dem datapath (Version 1.2)
function myfile_exists($file) {
	global $config,$ext_path_to_forum;
	return file_exists($ext_path_to_forum.'/'.$file);
}

// !
?>