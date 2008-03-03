<?

/* ad_login.php - Zum einloggen als Admin (c) 2001-2002 Tritanium Scripts */

require_once("functions.php");
require_once("loadset.php");
require_once("auth.php");
ad();

$showformular = 1; // Das "Formular" wird erst mal auf jeden Fall angezeigt
$fehler = "";
$write = ""; // Schreiben erst mal nicht freigeben

if(myfile_exists("vars/alarm.var")) die("Das Forum wurde aus Sicherheitsgründen deaktiviert!");
elseif($user_logged_in == 1 && $user_data[status] == 1) {
	header("Location: adminpanel.php?$HSID"); exit;
}
elseif($user_logged_in == 1) {
	mylog("3","%1 hat versucht sich doppelt einzuloggen (IP: %2)");
	header("Location: index.php?$HSID"); exit;
}
if($mode == "verify") {
	$useranzahl = myfile("vars/last_user_id.var"); $useranzahl = $useranzahl[0] + 1; // Anzahl der User herausfinden
	$login_pw = mycrypt(mysslashes($login_pw)); // Loginpasswort wird zur weiteren Verwendung verschlüsselt
	$login_name2 = mutate(strtolower($login_name));
	for($i = 1; $i < $useranzahl; $i++) {
		if($akt_user = myfile("members/$i.xbb")) { // Userdatei laden
			if($login_name2 == strtolower(killnl($akt_user[0]))) { // Loginnamen mit dem Namen aus der aktuellen Userdatei vergleichen; Zur Einfachheit werden beide zu Kleinbuchstaben umgewandelt
				if(killnl($akt_user[4]) == 5) { // User gelöscht
					$fehler = $lng['login']['error']['Unknown_user'];
					mylog("3","Login mit User \"".killnl($akt_user[0])."\" (ID: ".killnl($akt_user[1]).") fehlgeschlagen. Grund: User wurde gelöscht (IP: %2)");
					break;
				}
				elseif(killnl($akt_user[4]) != 1) {
					$fehler = $lng['ad_login']['error']['user_no_admin'];
					mylog("8","Administration: Login mit User \"".killnl($akt_user[0])."\" (ID: ".killnl($akt_user[1]).") fehlgeschlagen. Grund: User ist keine Administrator (IP: %2)");
				}
				elseif($login_pw != killnl($akt_user[2])) { // Falsches Passwort
					$fehler = $lng['login']['error']['Wrong_password'];
					mylog("3","Login mit User \"".killnl($akt_user[0])."\" (ID: ".killnl($akt_user[1]).") fehlgeschlagen. Grund: falsches Passwort");
					break;
				}
				else {
					$akt_user_id = killnl($akt_user[1]);
					$session_user_pw = $login_pw; $session_user_id = $akt_user_id; // Daten für Session vorbereiten
					session_register("session_user_pw","session_user_id");
					if($stayli == "yes") setcookie("cookie_xbbuser",$akt_user_id."\t$login_pw",time()+(3600*24*365),'/'); // Cookie wird gesetzt, ist für ein Jahr wirksam
					else setcookie("cookie_xbbuser",$akt_user_id."\t$login_pw","",'/'); // Andernfalls wird ein "Sicherheitscookie" gesetzt, das nur für diesen Besuch gültig ist (ist aber egal, ob der User Cookies akzeptiert)
					$showformular = 0; // Das Formular braucht nicht mehr angezeigt zu werden (Ist das überhaupt nötig??)

					// Hier beginnt WIO-Code (Löscht die Gast-ID)
					if($config['wio'] == 1) {
						$wio_file = myfile("vars/wio.var"); $wio_file_size = sizeof($wio_file);
						for($j = 0; $j < $wio_file_size; $j++) {
							$aktueller_wio = myexplode($wio_file[$j]);
							if($aktueller_wio[1] == $session_upbwio) {
								$write = "ok"; $wio_file[$j] = ""; $do_wio = "no"; // Gast ID löschen, schreiben freigeben, WhoIsOnline für login.php abschalten
								break;
							}
						}

						if($write == "ok") myfwrite("vars/wio.var",$wio_file,"w");
						if($bewio == "yes") $session_upbwio = "no";
						else $session_upbwio = $akt_user_id;
						session_register("session_upbwio");
					}
					// Hier endet WIO-Code

					mylog("7","\"".killnl($akt_user[0])."\" (ID: ".killnl($akt_user[1]).") wurde eingeloggt (IP: %2)");

					header("Location: adminpanel.php?$HSID");
					exit; // sicherstellen, dass danach nichts mehr ausgeführt wird
				}
				break; // Schleife beenden
			}
		}
	}
	if($fehler == "") {
		$fehler = "Unbekannter Benutzername";
		mylog("3","Login mit User \"".killnl($akt_user[0])."\" fehlgeschlagen. Grund: User existiert nicht (IP: %2)");
	}
}

if($showformular == 1) {
	include("pageheader.php");
	echo adnavbar($lng['Login']);
	?>
		<form action="ad_login.php?mode=verify<?=$MYSID2?>" method="post">
		<table class="tbl" border="0" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>" width="<?=$twidth?>">
		 <tr><th colspan=2 class="thnorm" align="left"><span class="thnorm">Einloggen</span></th></tr>
		 <tr><td colspan=2 class="kat"><span class="kat"><?=$lng['login']['Logindata']?></span></td></tr>
		 <? if($fehler != "") echo "<tr><td colspan=\"2\" class=\"td1\"><span class=\"error\">$fehler</span></td></tr>"; ?>
		 <tr>
		  <td width="20%" class="td1"><span class="norm"><?=$lng['login']['Username']?>:</span></td>
		  <td width="80%" class="td1"><input type="text" name="login_name" value="<?=$login_name?>"></td>
		 </tr>
		 <tr>
		  <td width="20%" class="td1"><span class="norm"><?=$lng['login']['Password']?>:</span></td>
		  <td width="80%" class="td1"><input type="password" name="login_pw">
	<?
		if($config['activate_mail'] == 1) {
			if($login_name != '') {
				$send_name = "&send_name=".urlencode(demutate($login_name));
			}
			else $send_name = '';
			echo "<span class=\"small\">&nbsp;(<a class=\"small\" href=\"index.php?faction=sendpw$send_name$MYSID2\">".$lng['login']['Password_forgotten']."</a>)</span>";
		}
	?>
		  </td>
		 </tr>
		 <tr><td class="kat" colspan="2"><span class="kat"><?=$lng['Options']?></span></td></tr>
		 <tr><td class="td1" colspan="2"><input type="checkbox" name="stayli" value="yes" onfocus="this.blur()">&nbsp;<span class="norm">In Zukunft automatisch einloggen?</span><? if($config['wio'] == 1) echo "<br><input type=checkbox name=bewio value=yes onfocus=this.blur()>&nbsp;<span class=\"norm\">Vor WhoIsOnline verstecken?</span>"; ?></td></tr>
		</table><br><input type="submit" value="<?=$lng['Login']?>"></form></center>
	<?
}

if($do_wio != "no") wio_set("login");

include('pagetail.php');
// E
?>