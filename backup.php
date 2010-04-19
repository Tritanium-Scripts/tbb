<?

require_once("functions.php");

$zlib = 0;

/*if(phpversion() >= "4.0.4") {
	if(extension_loaded("zlib")) {
		$zlib = 1;
		$suffix = "-compressed";
	}
}*/

switch($mode) {

	case "re":
		$dp = opendir("backup");
		while($akt_file = readdir($dp)) {
			if($akt_file != '.' && $akt_file != '..') {
				$akt_file1 = explode("-",$akt_file);
				if($akt_file1[1] == $backup) {
					$fp = fopen("backup/$akt_file",'r'); flock($fp,LOCK_SH);
					$akt_data = fread($fp,filesize("backup/$akt_file")); flock($fp,LOCK_UN); fclose($fp);
					if($akt_file1[3] == "compressed.bck") {
						if($zlib != 1) die("Kein ZLIB verfügbar!");
						$akt_data = gzuncompress($akt_data);
					}

					$akt_data = explode("<>\r\r\r<>",$akt_data);
					for($i = 0; $i < sizeof($akt_data); $i++) {
						$akt_data[$i] = explode("<>\r\r<>",$akt_data[$i]);
						myfwrite($akt_data[$i][0],$akt_data[$i][1],'w');
					}
				}
			}
		}
		closedir($dp);
	break;

	case "overview":
		$backups = array();
		$dp = opendir("backup");
		while($akt_file = readdir($dp)) {
			if($akt_file != '.' && $akt_file != '..') {
				$akt_file = explode("-",$akt_file);
				if(!in_array($akt_file[1],$backups)) {
					$backups[] = $akt_file[1];
				}
			}
		}
		closedir($dp);
		for($i = 0; $i < sizeof($backups); $i++) {
			echo "<a href=\"backup.php?mode=re&backup=$backups[$i]\">$backups[$i]</a><br>";
		}
	break;

	case "create":
		$datum = mydate();

		$folders = array("foren","members","vars");
		for($i = 0; $i < sizeof($folders); $i++) {
			$akt_folder = $folders[$i];
			if($dp = opendir($akt_folder)) {
				$data = array();
				while($akt_file = readdir($dp)) {
					if($akt_file != '.' && $akt_file != '..') {
						$akt_fp = fopen("$akt_folder/$akt_file",'r'); flock($akt_fp,LOCK_SH);
						$data[] = array("$akt_folder/$akt_file",fread($akt_fp,filesize("$akt_folder/$akt_file")));
						flock($akt_fp,LOCK_UN); fclose($akt_fp);
					}
				}
				for($j = 0; $j < sizeof($data); $j++) {
					$data[$j] = implode("<>\r\r<>",$data[$j]);
				}
				$data = implode("<>\r\r\r<>",$data);

				if($zlib == 1) $data = gzcompress($data,9);
				myfwrite("backup/backup-$datum-$akt_folder$suffix.bck",$data,'w');
				echo round(strlen($data)/1024,2).'<br>';
				closedir($dp);
			}
		}
	break;
}
?>