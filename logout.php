<?

/* logout.php - Loggt einen User aus (c) 2001-2002 Tritanium Scripts */

require_once("auth.php");

wio_set("logout"); // WIO konfigurieren
$write = ""; // Schreiben erst mal nicht erlauben

if($user_logged_in == 1) { // Das ganze findet nur statt, wenn der User eingeloggt ist

	mylog("7","%1 wurde ausgeloggt (IP: %2)");

	setcookie("cookie_xbbuser",'',time()-1000,'/'); // Userdatencookie "löschen"

	session_unregister("session_user_pw"); session_unregister("session_user_id");

	// Hier beginnt WIO-Code (User-ID löschen, falls WIO aktiv ist)
	if($config['wio'] == 1) {
		$wio_file = myfile("vars/wio.var"); $wio_file_size = sizeof($wio_file);
		for ($i = 0; $i < $wio_file_size; $i++) {
			$aktueller_wio = myexplode($wio_file[$i]);
			if($aktueller_wio[1] == $special_id) {
				$write = "ok"; $wio_file[$i] = ""; // UserID löschen und schreiben freigeben
				break;
			}
		}
		if($write == "ok") myfwrite("vars/wio.var",$wio_file,"w");
		session_unregister("session_upbwio"); // WIO-SVAR "löschen"
	}
	// Hier endet WIO-Code
}

header("Location: index.php?$HSID");

?>