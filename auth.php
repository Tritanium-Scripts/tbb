<?

/* auth.php - Stellt fest, ob ein User eingeloggt ist (c) 2001-2002 Tritanium Scripts */

session_start(); // startet bzw. setzt Session fort

// Falls register_globals nicht aktiviert ist (abwärtskompatiblität)
if(@get_cfg_var("register_globals") != 1) {
	while($akt_var = each($HTTP_GET_VARS)) {
		$$akt_var[0] = $akt_var[1];
	}
	while($akt_var = each($HTTP_POST_VARS)) {
		$$akt_var[0] = $akt_var[1];
	}
	while($akt_var = each($HTTP_ENV_VARS)) {
		$$akt_var[0] = $akt_var[1];
	}
	while($akt_var = each($HTTP_SERVER_VARS)) {
		$$akt_var[0] = $akt_var[1];
	}
	while($akt_var = each($HTTP_COOKIE_VARS)) {
		$$akt_var[0] = $akt_var[1];
	}
	while($akt_var = each($HTTP_SESSION_VARS)) {
		$$akt_var[0] = $akt_var[1];
	}
}

require_once("loadset.php");

$user_logged_in = 0; // User wird erst mal als ausgeloggt gekenntzeichnet
$user_id = 0; // User ist auf jeden Fall Gast
unset($user_data);
unset($cache);

nix(); // Sicherheitsfunktion (zu kompliziert zu erklären)

if(!isset($file_counter)) $file_counter = 0;

if(isset($session_user_id)) { // Falls in der Session Userinformationen gefunden werden
	$session_user_data = get_user_data($session_user_id);
	if($session_user_pw == $session_user_data['pw']) {
		$user_logged_in = 1;
		$user_id = $session_user_id;
		$user_data = $session_user_data;
	}
}

if($user_logged_in == 0 && isset($cookie_xbbuser)) { // Falls keine Session-Userdaten existieren wird nach einem Cookie gesucht
	$cookie_userdaten = myexplode($cookie_xbbuser); // Cookiedaten "trennen"
	if(trim($cookie_userdaten[1]) != "" && $cookie_userdaten[1] != "0") { // Überprüfen, ob im Cookie überhaupt was drinsteht, und ob nicht der Gast drinsteht
		if($cookie_userdaten2 = get_user_data($cookie_userdaten[0])) { // Userdaten des Users, der im Cookie angegeben wird, laden
			if($cookie_userdaten2[pw] == $cookie_userdaten[1]) {  // Falls PWs übereinstimmen, kann "autorisiert" werden
				$user_logged_in = 1;
				$user_id = $cookie_userdaten[0];
				$user_data = $cookie_userdaten2;
				$session_user_id = $cookie_userdaten[0];
				$session_user_pw = $cookie_userdaten[1];
				session_register('session_user_id','session_user_pw');
			}
		}
	}
}

$HSID = session_name()."=".session_id(); // Dies ist für den header() Befehl, da PHP hier anscheinend keine SID trotz trans.sid (oder so ähnlich ;) anhängen will

// Hier beginnt WIO-Code
	if($user_logged_in != 1) { // Wenn User eingeloggt ist, ist das ganze nicht nötig
		$set_new_cookie = 1; // Cookie wird auf jeden Fall mal gesetzt
		if(isset($session_upbwio)) { // Es wird überprüft, ob schon ein WIO-Cookie vorhanden ist
			if(strlen($session_upbwio) < 16) { // Cookie wird nur bei einer Länge kleiner 16 Zeichen akzeptiert (Bringt das eigentlich was?)
				$set_new_cookie = 0; // Cookie braucht nicht mehr gesetzt zu werden
				$special_id = $session_upbwio; // Spezial-ID wird zugewiesen (für WIO nötig)
			}
		}
		if($set_new_cookie == 1) {
			$special_id = "guest".get_rand_num(10); // Gast-ID (10-stellig) erstellen
			$session_upbwio = $special_id;
			session_register("session_upbwio"); // Session-Variable mit Gast-ID setzen
		}
	}
	else {
		$special_id = $user_id; // Falls User eingeloggt ist, ist die Spezial-ID die User-ID
		if(!$session_upbwio) {
			$session_upbwio = $special_id;
			session_register("session_upbwio");
		}
	}
// Hier endet WIO-Code

if(isset($var_css_file)) {
	if(!isset($HTTP_SESSION_VARS['session_css_file'])) {
		$HTTP_SESSION_VARS['session_css_file'] = 'styles/'.$var_css_file;
		session_register('session_css_file');
	}
	else $HTTP_SESSION_VARS['session_css_file'] = 'styles/'.$var_css_file;
	$config['temp_css_file'] = $HTTP_SESSION_VARS['session_css_file'];
}
elseif(isset($HTTP_SESSION_VARS['session_css_file'])) $config['temp_css_file'] = $HTTP_SESSION_VARS['session_css_file'];
else $config['temp_css_file'] = $config['css_file'];


// N
?>