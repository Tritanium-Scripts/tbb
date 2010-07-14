<?

/* functions.php - beinhaltet nützliche (oder auch weniger nützliche) Funktionen (c) 2001-2002 Tritanium Scripts */

require_once('datapath.php');

// \r\n eines Strings löschen
function killnl($text) {
	return str_replace("\n","",str_replace("\r\n","",$text));
}

// Datumstring lesbar machen
function makedatum($text) {

	global $config,$lng;

	$x = substr($config['gmt_offset'],1,2)*3600 + substr($config['gmt_offset'],3,2)*60;
	if(substr($config['gmt_offset'],0,1) == "-") $x = -1*$x;

	$text = mktime(substr($text,8,2),substr($text,10,2),0,substr($text,4,2),substr($text,6,2),substr($text,0,4)) + $x + date("Z");
	$text = gmstrftime("%Y%m%d%H%M",$text);

	$jahr = substr($text,0,4);
	$monat = substr($text,4,2);
	switch($monat) {
		case "01":
			$monat = $lng['months'][0];
			break;
		case "02":
			$monat = $lng['months'][1];
			break;
		case "03":
			$monat = $lng['months'][2];
			break;
		case "04":
			$monat = $lng['months'][3];
			break;
		case "05":
			$monat = $lng['months'][4];
			break;
		case "06":
			$monat = $lng['months'][5];
			break;
		case "07":
			$monat = $lng['months'][6];
			break;
		case "08":
			$monat = $lng['months'][7];
			break;
		case "09":
			$monat = $lng['months'][8];
			break;
		case "10":
			$monat = $lng['months'][9];
			break;
		case "11":
			$monat = $lng['months'][10];
			break;
		case "12":
			$monat = $lng['months'][11];
			break;
	}
	$tag = substr($text,6,2);
	$stunde = substr($text,8,2);
	$minute = substr($text,10,2);
	$text = "$tag. $monat $jahr <span class=\"time\">$stunde:$minute</span>";
	return $text;
}

// Registrierungsdatum lesbar machen
function makeregdatum($String) {
	global $lng;
	$jahr = substr($String,0,4);
	$monat = substr($String,4,2);
	switch($monat) {
		case "01":
			$monat = $lng['months'][0];
			break;
		case "02":
			$monat = $lng['months'][1];
			break;
		case "03":
			$monat = $lng['months'][2];
			break;
		case "04":
			$monat = $lng['months'][3];
			break;
		case "05":
			$monat = $lng['months'][4];
			break;
		case "06":
			$monat = $lng['months'][5];
			break;
		case "07":
			$monat = $lng['months'][6];
			break;
		case "08":
			$monat = $lng['months'][7];
			break;
		case "09":
			$monat = $lng['months'][8];
			break;
		case "10":
			$monat = $lng['months'][9];
			break;
		case "11":
			$monat = $lng['months'][10];
			break;
		case "12":
			$monat = $lng['months'][11];
			break;
	}
	$reg_datum = "$monat $jahr";
	return $reg_datum;
}

// Benutzerdaten in Array laden (Version 1.1)
function get_user_data($user_id) {
	if(!$user_file = myfile("members/$user_id.xbb")) return FALSE;
	if(killnl($user_file[4]) == 5) return FALSE;
	$user_data[0] = killnl($user_file[0]); $user_data['nick'] = &$user_data[0];
	$user_data[1] = killnl($user_file[1]); $user_data['id'] = &$user_data[1];
	$user_data[2] = killnl($user_file[2]); $user_data['pw'] = &$user_data[2];
	$user_data[3] = killnl($user_file[3]); $user_data['email'] = &$user_data[3];
	$user_data[4] = killnl($user_file[4]); $user_data['status'] = &$user_data[4];
	$user_data[5] = killnl($user_file[5]); $user_data['posts'] = &$user_data[5];
	$user_data[6] = killnl($user_file[6]); $user_data['regdatum'] = &$user_data[6];
	$user_data[7] = killnl($user_file[7]); $user_data['signatur'] = &$user_data[7];
	$user_data[8] = killnl($user_file[8]); $user_data['faccess'] = &$user_data[8];
	$user_data[9] = killnl($user_file[9]); $user_data['hp'] = &$user_data[9];
	$user_data[10] = killnl($user_file[10]); $user_data['pic'] = &$user_data[10];
	$user_data[11] = killnl($user_file[11]); $user_data['updatestatus'] = &$user_data[11];
	$user_data[12] = killnl($user_file[12]); $user_data['name'] = &$user_data[12];
	$user_data[13] = killnl($user_file[13]); $user_data['icq'] = &$user_data[13];
	$user_data[14] = killnl($user_file[14]); $user_data['moptions'] = &$user_data[14];
	$user_data[15] = killnl($user_file[15]); $user_data['group'] = &$user_data[15];

	$mail_options = explode(",",$user_data[14]); $user_data['forummails'] = $mail_options[0]; $user_data['showemail'] = $mail_options[1]; // Zusatz: Mailoptionen

	return $user_data;
}

// Forumdaten in Array laden (Version 1.1)
function get_forum_data($forum_id) {
	if(!$forums_file = myfile("vars/foren.var")) return FALSE;
	for($i = 0; $i < sizeof($forums_file); $i++) {
		$akt_forum = myexplode($forums_file[$i]);
		if($akt_forum[0] == $forum_id) {
			$forum_data[0] = $akt_forum[0]; $forum_data['id'] = &$forum_data[0];
			$forum_data[1] = $akt_forum[1]; $forum_data['name'] = &$forum_data[1];
			$forum_data[2] = $akt_forum[2]; $forum_data['descr'] = &$forum_data[2];
			$forum_data[3] = $akt_forum[3]; $forum_data['topics'] = &$forum_data[3];
			$forum_data[4] = $akt_forum[4]; $forum_data['posts'] = &$forum_data[4];
			$forum_data[5] = $akt_forum[5]; $forum_data['catid'] = &$forum_data[5];
			$forum_data[6] = $akt_forum[6]; $forum_data['smstatus'] = &$forum_data[6];
			$forum_data[7] = $akt_forum[7]; $forum_data['options'] = &$forum_data[7];
			$forum_data[8] = $akt_forum[8]; // $forum_data[] = &$forum_data[8];
			$forum_data[9] = $akt_forum[9]; $forum_data['ltopic'] = &$forum_data[9];
			$forum_data[10] = $akt_forum[10]; $forum_data['rights_data'] = &$forum_data[10];
			$forum_data[11] = $akt_forum[11]; $forum_data['mods'] = &$forum_data[11];

			$forum_options = explode(',',$forum_data[7]);
			$forum_data['htmlcode'] = $forum_options[1]; $forum_data['upbcode'] = $forum_options[0]; // Zusatz: UPB- und HTML Codestatus

			$forum_data['rights'] = explode(',',$forum_data[10]); // Zusatz: Allgemeine Rechte des Forums

			break;
		}
	}
	if(!isset($forum_data)) return FALSE;
	return $forum_data;
}

// Themendaten in Array laden (Version 1.1)
function get_topic_data($forum_id,$topic_id) {
	if(!$topic_file = myfile("foren/$forum_id-$topic_id.xbb")) return FALSE;
	$topic = myexplode($topic_file[0]);
	$topic_data[0] = $topic[0]; $topic_data['status'] = &$topic_data[0];
	$topic_data[1] = $topic[1]; $topic_data['title'] = &$topic_data[1];
	$topic_data[2] = $topic[2]; $topic_data['creator_id'] = &$topic_data[2];
	$topic_data[3] = $topic[3]; $topic_data['pic'] = &$topic_data[3];
	$topic_data[4] = $topic[4]; $topic_data['smstatus'] = &$topic_data[4];
	$topic_data[5] = $topic[5]; $topic_data['lpost'] = &$topic_data[5];
	$topic_data[6] = $topic[6]; $topic_data['poll_id'] = &$topic_data[6];
	// $topic_data[7] = $topic[7]; $topic_data[''] = &$topic_data[7];
	// $topic_data[8] = $topic[8]; $topic_data[''] = &$topic_data[8];
	// $topic_data[9] = $topic[9]; $topic_data[''] = &$topic_data[9];
	// $topic_data[10] = $topic[10]; $topic_data[''] = &$topic_data[10];
	// $topic_data[11] = $topic[11]; $topic_data[''] = &$topic_data[11];

	$topic_data['posts'] = sizeof($topic_file)-1; // Zusatz: Anzahl der Antworten
	$lpost = myexplode($topic_file[sizeof($topic_file)-1]); $topic_data['lpost_id'] = $lpost[0]; // Zusatz: ID des letzten (neusten) Beitrags

	return $topic_data;
}

// PM-Daten in Array laden (Version 1.1)
function get_pm_data($pmbox_id,$pm_id) {
	if(!$pms = myfile("members/$pmbox_id.pm")) return FALSE;
	for($i = 0; $i < sizeof($pms); $i++) {
		$akt_pm = myexplode($pms[$i]);
		if($akt_pm[0] == $pm_id) {
			$pm_data[0] = $akt_pm[0]; $pm_data['id'] = &$pm_data[0];
			$pm_data[1] = $akt_pm[1]; $pm_data['title'] = &$pm_data[1];
			$pm_data[2] = $akt_pm[2]; $pm_data['pm'] = &$pm_data[2];
			$pm_data[3] = $akt_pm[3]; $pm_data['creator_id'] = &$pm_data[3];
			$pm_data[4] = $akt_pm[4]; $pm_data['ctime'] = &$pm_data[4];
			$pm_data[5] = $akt_pm[5]; $pm_data['upbcode'] = &$pm_data[5];
			$pm_data[6] = $akt_pm[6]; $pm_data['smilies'] = &$pm_data[6];
			$pm_data[7] = $akt_pm[7]; $pm_data['rstatus'] = &$pm_data[7];
			break;
		}
	}
	return $pm_data;
}

// Postdaten in Array laden
function get_post_data($forum_id,$topic_id,$post_id) {
	if(!$topic_file = myfile("foren/$forum_id-$topic_id.xbb")) return FALSE;
	for($i = 1; $i < sizeof($topic_file); $i++) {
		$akt_post = myexplode($topic_file[$i]);
		if($akt_post[0] == $post_id) {
			$post_data[0] = $akt_post[0]; $post_data['id'] = &$post_data[0];
			$post_data[1] = $akt_post[1]; $post_data['creator_id'] = &$post_data[1];
			$post_data[2] = $akt_post[2]; $post_data['ctime'] = &$post_data[2];
			$post_data[3] = $akt_post[3]; $post_data['post'] = &$post_data[3];
			$post_data[4] = $akt_post[4]; $post_data['creator_ip'] = &$post_data[4];
			$post_data[5] = $akt_post[5]; $post_data['signatur'] = &$post_data[5];
			$post_data[6] = $akt_post[6]; $post_data['pic'] = &$post_data[6];
			$post_data[7] = $akt_post[7]; $post_data['smilies'] = &$post_data[7];
			$post_data[8] = $akt_post[8]; $post_data['upbcode'] = &$post_data[8];
			$post_data[9] = $akt_post[9]; $post_data['htmlcode'] = &$post_data[9];
			$post_data[10] = $akt_post[10]; // $post_data[] = &$post_data[10];
			$post_data[11] = $akt_post[11]; // $post_data[] = &$post_data[11];
			break;
		}
	}
	return $post_data;
}

// Benutzernamen aus Benutzerdatei extrahieren
function get_user_name($user_id) {
	if(strncmp($user_id,'0',1) == 0) $user_name = substr($user_id,1,strlen($user_id));
	elseif(!$user_daten = myfile("members/$user_id.xbb")) $user_name = "Gelöscht";
	else $user_name = killnl($user_daten[0]);
	return $user_name;
}

// Benutzerpw aus Benutzerdatei extrahieren
function get_user_pw ($user_id) {
	$userdatei_daten = myfile("members/$user_id.xbb");
	$user_pw = killnl($userdatei_daten[2]);
	return $user_pw;
}

// Echter Benutzerstatus aus Benutzerdatei extrahieren
function get_real_user_status($user_id) {
	if(!$userdatei_daten = myfile("members/$user_id.xbb")) $user_status = 5;
	else $user_status = killnl($userdatei_daten[4]);
	return $user_status;
}

// Benutzerstatus aus Benutzerdatei extrahieren
function get_user_status($user_id) {
	global $config;
	$userdatei_daten = myfile("members/$user_id.xbb");
	$user_prestatus = killnl($userdatei_daten[4]);
	switch($user_prestatus) {
		case "1":
			$user_status = $config['var_admin'];
		break;

		case "2":
			$user_status = $config['var_mod'];
		break;

		case "3":
			$up = killnl($userdate_daten[5]);
			$ranks = myfile("vars/rank.var"); $ranks_anzahl = sizeof($ranks);
			for ($i = 0; $i < $ranks_anzahl; $i++) {
				$aktueller_rank = myexplode($ranks[$i]);
				if ($up >= $aktueller_rank[2] && $up <= $aktueller_rank[3]) { $user_status = $aktueller_rank[1]; break; }
			}
		break;

		case "4":
			$user_status = $config['var_banned'];
		break;

		case "5":
			$user_status = $config['var_killed'];
		break;
	}
	return $user_status;
}

// BenutzerEmail aus Benutzerdatei extrahieren
function get_user_email($user_id) {
	$userdatei_daten = myfile("members/$user_id.xbb");
	return killnl($userdatei_daten[3]);
}

// Signatur-UPB-Code (Version 1.1)
function upbcode_signatur($text) {
	if(substr_count($text,"[") > 0 && substr_count($text,"]") > 0) {
		$text = preg_replace("#\[color=(\#[0-9a-f]{6}|[a-z]+)\](.*?)\[/color\]#si",'<font color="\1">\2</font>',$text);
		$text = preg_replace("#\[u\](.*?)\[/u\]#si",'<u>\1</u>',$text);
		$text = preg_replace("#\[code\](.*?)\[/code\]#si",'<code>\1</code>',$text);
		$text = preg_replace("#\[s\](.*?)\[/s\]#si",'<s>\1</s>',$text);
		$text = preg_replace("#\[i\](.*?)\[/i\]#si",'<i>\1</i>',$text);
		$text = preg_replace("#\[b\](.*?)\[/b\]#si",'<b>\1</b>',$text);
		$text = preg_replace("#\[center\](.*?)\[/center\]#si",'<center>\1</center>',$text);
		$text = preg_replace("#\[marquee\](.*?)\[/marquee\]#si",'<marquee>\1</marquee>',$text);
		$text = preg_replace("#\[url\]([a-z]+?://)([^\[]*)\[/url\]#si",'<a href="\1\2" target="_blank">\1\2</a>',$text);
		$text = preg_replace("#\[url\]([^\[]*)\[/url\]#si",'<a href="http://\1" target="_blank">\1</a>',$text);
		$text = preg_replace("#\[url=([a-z]+?://)([^\]]*)\](.*?)\[/url\]#si",'<a href="\1\2" target="_blank">\3</a>',$text);
		$text = preg_replace("#\[url=([^\]]*)\](.*?)\[/url\]#si",'<a href="http://\1" target="_blank">\2</a>',$text);
		$text = preg_replace("#\[email\]([^\[]+@[^\[]+)\[/email\]#si",'<a href="mailto:\1">\1</a>',$text);
		$text = preg_replace('=\[img\](http:[^\[]*|[^\[:]*)\[/img\]=si','<img src="$1" border="0">',$text);
	}
	return $text;
}

// Benutzer Homepage aus Benutzerdatei extrahieren
function get_user_hp($user_id) {
	if(!$userdatei_daten = myfile("members/$user_id.xbb")) $user_hp = "";
	elseif(killnl($userdatei_daten[9]) == "") $user_hp = "";
	else $user_hp = addhttp(killnl($userdatei_daten[9]));
	return $user_hp;
}

// BenutzerUpdatestatus aus Benutzerdatei extrahieren (Version 1.1)
function get_user_updatestatus($user_id) {
	$userdatei_daten = myfile("members/$user_id.xbb");
	return killnl($userdatei_daten[11]);
}

// Benutzer Bild aus Benutzerdatei extrahieren
function get_user_pic($pic) {
	global $config;
	if($pic != "") {
		$pic = addhttp($pic);
		if($config['use_getimagesize'] == 1) {
			if(!$pic_size = @getimagesize($pic)) $pic = "<img width=\"".$config['avatar_width']."\" height=\"".$config['avatar_height']."\" src=\"$pic\">";
			else {
				if($pic_size[0] > $config['avatar_width'] && $pic_size[1] > $config['avatar_height']) $pic = "<img width=\"".$config['avatar_width']."\" height=\"".$config['avatar_height']."\" src=\"$pic\">";
				elseif($pic_size[0] > $config['avatar_width'] && $pic_size[1] <= $config['avatar_height']) $pic = "<img width=\"".$config['avatar_width']."\" src=\"$pic\">";
				elseif($pic_size[0] <= $config['avatar_width'] && $pic_size[1] > $config['avatar_height']) $pic = "<img height=\"".$config['avatar_height']."\" src=\"$pic\">";
				else $pic = "<img src=$pic>";
			}
		}
		else $pic = "<img width=\"".$config['avatar_width']."\" height=\"".$config['avatar_height']."\" src=\"$pic\">";
	}
	return $pic;
}

// Letzten Topic eines Forums herausfinden
function make_last_post($forum_id,$data,$forum_status) {
	global $MYSID1,$MYSID2,$config,$lng,$user_logged_in,$aktuelles_forum;
	$last_post_daten = explode(',',$data);
	$akt_forum_rights = explode(',',$aktuelles_forum[10]);
	if($last_post_daten[0] == "") $last_post = $lng['readforum']['overview']['No_last_post'];
	else {
		$right = 0;
		if($user_logged_in != 1) {
			if($akt_forum_rights[6] == 1) $right = 1;
		}
		elseif(check_right($forum_id,0) == 1) $right = 1;

		if($right != 1) $last_post = makedatum($last_post_daten[2]);
		else {
			if(!$topic_file = myfile("foren/$forum_id-$last_post_daten[0].xbb")) $topic_link = $lng['Deleted'];
			else {
				$topic_info = myexplode($topic_file[0]); if($config['censored'] == 1) $topic_info[1] = censor($topic_info[1]);
				if(strlen($topic_info[1]) > 22) $topic_info[1] = substr($topic_info[1],0,19)."...";
				$topic_link = "<a href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$last_post_daten[0]&z=last$MYSID2\">$topic_info[1]</a>";
			}
			$pic = get_tsmadress($last_post_daten[3]);
			$last_post = "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\"><tr><td align=\"center\" width=\"10%\"><img border=\"0\" src=\"$pic\"></td><td width=\"90%\"><span class=\"small\">$topic_link (von ".get_user_link($last_post_daten[1]).')<br>am '.makedatum($last_post_daten[2])."</span></td></tr></table>";
		}
	}
	return $last_post;
}

// Moderatoren eines Forums herausfinden
function get_forum_mods($data) {
	global $MYSID1,$MYSID2;
	$mods = '';
	if($data != '') {
		$forum_mods = explode(",",$data);
		for($i = 0; $i < sizeof($forum_mods); $i++) {
			if($user_file = myfile("members/$forum_mods[$i].xbb")) {
				$mods[] = "<a href=\"index.php?faction=profile&profile_id=$forum_mods[$i]$MYSID2\" onfocus=\"this.blur()\">".killnl($user_file[0])."</a>";
			}
		}
		$mods = implode(", ",$mods);
	}
	return $mods;
}

// Herausfinden, ob ein User Moderator eines bestimmten Forums ist
function test_mod($forum_id) {
	global $user_id;
	$is_mod = 0;
	if($user_id != 0) {
		$foren_file = myfile("vars/foren.var"); $foren_anzahl = sizeof($foren_file);
		for($i = 0; $i < $foren_anzahl; $i++) {
			$aktuelles_forum = myexplode( $foren_file[$i]);
			if($aktuelles_forum[0] == $forum_id) {
				$forum_mods = explode(",", $aktuelles_forum[11]); $mods_anzahl = sizeof($forum_mods);
				for($y = 0; $y < $mods_anzahl; $y++) {
					if($forum_mods[$y] == $user_id) {
						$is_mod = 1;
						break;
					}

				}
				break;
			}
		}
	}
	return $is_mod;
}


// Neue Linien in <br>s umwandeln
function nlbr($text) {
	return str_replace("\r\n", "<br>", $text);
}

// <br>s in neue Linien umwandeln
function brnl($text) {
	return str_replace("<br>", "\r\n", $text);
}

// Forumname herausfinden
function get_forum_name($text) {
	$forendaten = myfile("vars/foren.var"); $forenanzahl = sizeof($forendaten);
	for($i = 0; $i < $forenanzahl; $i++) {
		$aktuelles_forum = myexplode( $forendaten[$i]);
		if ($aktuelles_forum[0] == $text) {
			$forumname = $aktuelles_forum[1];
			break;
		}
	}
	return $forumname;
}

// Forumstatus herausfinden
function get_forum_status($forum_id) {
	$forendaten = myfile("vars/foren.var"); $forenanzahl = sizeof($forendaten);
	for ($i = 0; $i < $forenanzahl; $i++) {
		$aktuelles_forum = myexplode( $forendaten[$i]);
		if ($aktuelles_forum[0] == $forum_id) {
			$forumstatus = $aktuelles_forum[8];
			break;
		}
	}
	return $forumstatus;
}

// Topicname herausfinden
function get_thread_name($forum_id,$topic_id) {
	if(!$topic_file = myfile("foren/$forum_id-$topic_id.xbb")) $topic_name = "Gelöscht";
	else {
		$topic_info = myexplode($topic_file[0]);
		$topic_name = $topic_info[1];
	}
	return $topic_name;
}

// Themenanzahl eines Forums um 1 erhöhen
function increase_topic_number($text) {
	$foren_datei = myfile("vars/foren.var"); $foren_anzahl = sizeof($foren_datei);
	for($i = 0; $i < $foren_anzahl; $i++) {
		$aktuelles_forum = myexplode( $foren_datei[$i]);
		if($text == $aktuelles_forum[0]) {
			$aktuelles_forum[3]++;
			$foren_datei[$i] = myimplode($aktuelles_forum);
			$save = 1;
			break;
		}
	}
	if($save == 1) myfwrite("vars/foren.var",$foren_datei,"w");
	else echo "Forum existiert nicht!";
}

// Themenanzahl eines Forums um 1 verringern
function decrease_topic_number($text) {
	$foren_datei = myfile("vars/foren.var"); $foren_anzahl = sizeof($foren_datei);
	for($i = 0; $i < $foren_anzahl; $i++) {
		$aktuelles_forum = myexplode($foren_datei[$i]);
		if($text == $aktuelles_forum[0]) {
			$aktuelles_forum[3] = $aktuelles_forum[3] - 1;
			$foren_datei[$i] = myimplode($aktuelles_forum);
			$save = 1; break;
		}
	}
	if ($save == 1) myfwrite("vars/foren.var",$foren_datei,"w");
	else echo "Forum existiert nicht!";
}

// Beitragsanzahl eines Forums um 1 erhöhen
function increase_posts_number($String) {
	$foren_datei = myfile("vars/foren.var"); $foren_anzahl = sizeof($foren_datei);
	for ($i = 0; $i < $foren_anzahl; $i++) {
		$aktuelles_forum = myexplode( $foren_datei[$i]);
		if ($String == $aktuelles_forum[0]) {
			$aktuelles_forum[4] = $aktuelles_forum[4] + 1;
			$foren_datei[$i] = myimplode($aktuelles_forum);
			$save = 1; break;
		}
		$foren_datei[$i] = myimplode($aktuelles_forum);
	}
	if ($save == 1) myfwrite("vars/foren.var",$foren_datei,"w");
	else echo "Forum existiert nicht!";
}

// Beitragsanzahl eines Forums um x erhöhen
function increase_posts_numberx($forum_id,$posts) {
	$foren_datei = myfile("vars/foren.var"); $foren_anzahl = sizeof($foren_datei);
	for ($i = 0; $i < $foren_anzahl; $i++) {
		$aktuelles_forum = myexplode($foren_datei[$i]);
		if ($forum_id == $aktuelles_forum[0]) {
			$aktuelles_forum[4] = $aktuelles_forum[4] + $posts;
			$foren_datei[$i] = myimplode($aktuelles_forum);
			$save = 1; break;
		}
		$foren_datei[$i] = myimplode($aktuelles_forum);
	}
	if ($save == 1) myfwrite("vars/foren.var",$foren_datei,"w");
	else echo "Forum existiert nicht!";
}

// Beitragsanzahl eines Forums um x veringern
function decrease_posts_number ($String, $post_anzahl) {
	$foren_datei = myfile("vars/foren.var"); $foren_anzahl = sizeof($foren_datei);
	for ($i = 0; $i < $foren_anzahl; $i++) {
		$aktuelles_forum = myexplode($foren_datei[$i]);
		if ($String == $aktuelles_forum[0]) {
			$aktuelles_forum[4] = $aktuelles_forum[4] - $post_anzahl;
			$foren_datei[$i] = myimplode($aktuelles_forum);
			$save = 1; break;
		}
		$foren_datei[$i] = myimplode($aktuelles_forum);
	}
	if ($save == 1) myfwrite("vars/foren.var",$foren_datei,"w");
	else echo "Forum existiert nicht!";
}

// Neusten Beitrag eines Forums updaten
function update_last_post ($forum_id,$datum,$creator_id,$topic_id,$pic) {
	$foren_datei = myfile("vars/foren.var"); $foren_anzahl = sizeof($foren_datei);
	for($i = 0; $i < $foren_anzahl; $i++) {
		$aktuelles_forum = myexplode($foren_datei[$i]);
		if ($forum_id == $aktuelles_forum[0]) {
			$aktuelles_forum[9] = "$topic_id,$creator_id,$datum,$pic";
			$aktuelles_forum[6] = time();
			$foren_datei[$i] = myimplode($aktuelles_forum);
			$save = 1; break;
		}
	}
	if ($save == 1) myfwrite("vars/foren.var",$foren_datei,"w");
	else echo "Forum existiert nicht!";
}

// Ein beliebiges Post herausfinden
function get_post($forum_id,$topic_id,$post_id) {
	global $user_id,$user_logged_in;
	$post = '';
	$forum_data = get_forum_data($forum_id);
	$right = 0;
	if($user_logged_in != 1) {
		if($forum_data['rights'][6] == 1) $right = 1;
	}
	elseif(check_right($forum_id,0) == 1) $right = 1;

	if($right == 1) {
		$post_file = myfile("foren/$forum_id-$topic_id.xbb"); $post_file_anzahl = sizeof($post_file);
		for ($i = 1; $i < $post_file_anzahl; $i++) {
			$aktuellerpost_daten = myexplode($post_file[$i]);
			if($aktuellerpost_daten[0] == $post_id) {
				$post = brnl($aktuellerpost_daten[3]);
				break;
			}
		}
	}
	return $post;
}

// IP des Erstellers eines beliebigen Posts herausfinden
function get_post_ip($forum_id, $topic_id, $post_id) {
	$post_file = myfile("foren/$forum_id-$topic_id.xbb");
	for ($i = 1; $i < sizeof($post_file); $i++) {
		$akt_post = myexplode($post_file[$i]);
		if ($akt_post[0] == $post_id) {
			$post_ip = $akt_post[4];
			break;
		}
	}
	return $post_ip;
}

// Ein Topic zum aktuellsten machen
function rank_topic($forum_id, $topic_id) {
	$topic_file = myfile("foren/$forum_id-threads.xbb"); $topic_anzahl = sizeof($topic_file);
	for($i = 0; $i < $topic_anzahl; $i++) {
		if($topic_id == killnl($topic_file[$i])) {
			if($i != $topic_anzahl-1) { // Das Topic schon das aktuellste ist, brauch nicht weitergemacht zu werden
				$topic_file[$i] = "";
				$topic_file[$topic_anzahl] = $topic_id."\r\n";
				$save = 1;
			}
			break;
		}
	}
	if($save == 1) myfwrite("foren/$forum_id-threads.xbb",$topic_file,"w");
}

// Herausfinden, ob ein Username schon existiert
function check_name($user_name,$except) {
	$user_exists = 0; $user_name = strtolower($user_name);
	$members = myfile("vars/last_user_id.var"); $members = $members[0] + 1;
	for($i = 1; $i < $members; $i++) {
		if($aktueller_member = myfile("members/$i.xbb")) {
			if(strtolower(killnl($aktueller_member[0])) == $user_name && killnl($aktueller_member[4]) != 5 && killnl($aktueller_member[1]) != $except) {
				$user_exists = 1;
				break;
			}
		}
	}
	return $user_exists;
}

// Herausfinden, ob eine Emailadresse schon existiert
function check_mail($email,$except) {
	$email_exists = 0; $email = strtolower($email);
	$members = myfile("vars/last_user_id.var"); $members = $members[0] + 1;
	for($i = 1; $i < $members; $i++) {
		if($akt_user = myfile("members/$i.xbb")) {
			if(strtolower(killnl($akt_user[3])) == $email && killnl($akt_user[4]) != 5 && killnl($akt_user[1]) != $except) {
				$email_exists = 1;
				break;
			}
		}
	}
	return $email_exists;
}

// Die Postanzahl eines Users um 1 erhöhen
function increase_user_posts($user_id) {
	if($user_id != 0) {
		if($user_daten = myfile("members/$user_id.xbb")) {
			$user_daten[5] = killnl($user_daten[5])+1; $user_daten[5] .= "\r\n";
			myfwrite("members/$user_id.xbb",$user_daten,"w");
		}
	}
}

// Überprüfen, ob ein User ein Forum betreten darf
function check_user_access($user_id,$forum_id) {
	$access = 0; $user_daten = myfile("members/$user_id.xbb");
	if(test_mod($forum_id,$user_id) == 1 || killnl($user_daten[4]) == 1) $access = 1;
	else {
		$user_daten_access = explode(",", killnl($user_daten[8]));
		for ($i = 0; $i < sizeof($user_daten_access); $i++) {
			if ($user_daten_access[$i] == $forum_id) {
				$access = 1;
				break;
			}
		}
	}
	return $access;
}

// Einen String HTML-kompatibel machen
function mutate($text) {
	$text = htmlspecialchars(mysslashes($text));
	return $text;
}

// Backslashes entfernen
function mysslashes($text) {
	$text = str_replace("\\\"","\"",$text);
	$text = str_replace("\\\\","\\",$text);
	$text = str_replace("\\'","'",$text);
	return $text;
}

// mutate() wieder teilweise rückgängig machen
function demutate($text) {
	$text = str_replace("&amp;","&",$text);
	$text = str_replace("&quot;","\"",$text);
	$text = str_replace("&lt;","<",$text);
	$text = str_replace("&gt;",">",$text);
	return $text;
}

// Text-Smilies in Grafiken umwandeln
function make_smilies($text) {
	$sm_file = myfile("vars/smilies.var");
	for($i = 0; $i < sizeof($sm_file); $i++) {
		$akt_sm = myexplode($sm_file[$i]);
		$text = str_replace($akt_sm[1],'<img border=0 src="'.$akt_sm[2].'">',$text);
	}
	return $text;
}


// Ranking Bild bestimmen
function get_rank_pic($user_status,$up) {
	global $config,$cache;
	$rank_pic = "";
	switch($user_status) {
		case "1":
			for($i = 0; $i < $config['stars_admin']; $i++) {
				$rank_pic .= '<img src=images/rank/star3.gif>';
			}
		break;

		case "2":
			for($i = 0; $i < $config['stars_mod']; $i++) {
				$rank_pic .= '<img src=images/rank/star3.gif>';
			}
		break;

		default:
			if($up < 0) $rank_pic = "";
			else {
				if(!$cache[ranks]) $cache[ranks] = myfile("vars/rank.var");
				for($i = 0; $i < sizeof($cache[ranks]); $i++) {
					$akt_rank = myexplode($cache[ranks][$i]);
					if($up >= $akt_rank[2] && $up <= $akt_rank[3]) {
						for($j = 0; $j < $akt_rank[4]; $j++) {
							$rank_pic .= '<img src=images/rank/star3.gif>';
						}
						break;
					}
				}
			}
		break;
	}
	return $rank_pic;
}

// Kategorie Name herausfinden
function get_kg_name($kg_id,$kg_file) {
	for($i = 0; $i < sizeof($kg_file); $i++) {
		$akt_kg = myexplode( $kg_file[$i]);
		if($akt_kg[0] == $kg_id) {
			$kg_name = $akt_kg[1];
			break;
		}
	}
	if (!$kg_name) $kg_name = "Kategorie existiert nicht!";
	return $kg_name;
}

// PM Titel herausfinden
function get_pm_name($user_id,$pm_id) {
	$pm = myfile("members/$user_id.pm"); $pm_anzahl = sizeof($pm);
	for($i = 0; $i < $pm_anzahl; $i++) {
		$aktuelle_pm = myexplode($pm[$i]);
		if($aktuelle_pm[0] == $pm_id) {
			$pm_name = $aktuelle_pm[1];
			break;
		}
	}
	return $pm_name;
}

// PM als ungelesen markieren
function make_read($user_id,$pm_id) {
	$pm = myfile("members/$user_id.pm"); $pm_anzahl = sizeof($pm);
	for($i = 0; $i < $pm_anzahl; $i++) {
		$aktuelle_pm = myexplode($pm[$i]);
		if ($aktuelle_pm[0] == $pm_id) {
			$aktuelle_pm[7] = 0;
			$save = "yes";
			$pm[$i] = myimplode($aktuelle_pm);
			break;
		}
	}
	if($save == "yes") myfwrite("members/$user_id.pm",$pm,"w");
}

// UPB-Code
function upbcode($text) {
	if(substr_count($text,"[") > 0 && substr_count($text,"]") > 0) {
		$text = preg_replace("#\[color=(\#[0-9a-f]{6}|[a-z]+)\](.*?)\[/color\]#si",'<font color="\1">\2</font>',$text);
		$text = preg_replace("#\[u\](.*?)\[/u\]#si",'<u>\1</u>',$text);
		$text = preg_replace("#\[code\](.*?)\[/code\]#si",'<code>\1</code>',$text);
		$text = preg_replace("#\[s\](.*?)\[/s\]#si",'<s>\1</s>',$text);
		$text = preg_replace("#\[i\](.*?)\[/i\]#si",'<i>\1</i>',$text);
		$text = preg_replace("#\[b\](.*?)\[/b\]#si",'<b>\1</b>',$text);
		$text = preg_replace("#\[center\](.*?)\[/center\]#si",'<center>\1</center>',$text);
		$text = preg_replace("#\[marquee\](.*?)\[/marquee\]#si",'<marquee>\1</marquee>',$text);
		$text = preg_replace("#\[url\]([a-z]+?://)([^\[]*)\[/url\]#si",'<a href="\1\2" target="_blank">\1\2</a>',$text);
		$text = preg_replace("#\[url\]([^\[]*)\[/url\]#si",'<a href="http://\1" target="_blank">\1</a>',$text);
		$text = preg_replace("#\[url=([a-z]+?://)([^\]]*)\](.*?)\[/url\]#si",'<a href="\1\2" target="_blank">\3</a>',$text);
		$text = preg_replace("#\[url=([^\]]*)\](.*?)\[/url\]#si",'<a href="http://\1" target="_blank">\2</a>',$text);
		$text = preg_replace("#\[email\]([^\[]+@[^\[]+)\[/email\]#si",'<a href="mailto:\1">\1</a>',$text);
		$text = preg_replace('=\[img\](http:[^\[]*|[^\[:]*)\[/img\]=si','<img src="$1" border="0">',$text);
		do {
			$text = preg_replace("#\[quote\](.*)\[/quote\]#si",'<center><table border="0" cellspacing="0" cellpadding="0" width="85%"><tr><td><span class="small"><b>Zitat:</b></span><hr noshade><center><table border="0" cellspacing="0" cellpadding="0" width="95%"><tr><td><span class="quote">\1</span></td></tr></table></center><hr noshade></td></tr></table></center>',$text);
		} while(preg_match("#\[quote\].*\[/quote\]#si",$text));
	}
	return $text;
}

// T-Smilie-Adresse herausfinden
function get_tsmadress($tsm_id) {
	$tsm_file = myfile("vars/tsmilies.var");
	for($i = 0; $i < sizeof($tsm_file); $i++) {
		$akt_tsm = myexplode($tsm_file[$i]);
		if ($akt_tsm[0] == $tsm_id) {
			$tsm_adress = $akt_tsm[1];
			break;
		}
	}
	if(!$tsm_adress) $tsm_adress = 'images/tsmilies/1.gif';
	return $tsm_adress;
}

// PM senden
function sendpm($target_id,$remote_id,$betreff,$pm,$use_upbcode,$use_smilies) {
	$target_file = myfile("members/$target_id.pm"); $target_file = array_reverse($target_file); $last_pm = myexplode($target_file[0]); $new_pm_id = $last_pm[0] + 1;
	$towrite = "$new_pm_id\t$betreff\t$pm\t$remote_id\t".mydate()."\t$use_smilies\t$use_upbcode\t1\t\r\n";
	myfwrite("members/$target_id.pm",$towrite,"a");
}

// Gegebenenfalls http:// zu einem String dazumachen
function addhttp($text) {
	if(substr($text,0,7) != "http://") $text = "http://".$text;
	return $text;
}

// NIX (Mir ist grade langweilig)
function nix() {
}

// Überprüfen, ob ein User noch Moderator eines Forums außer dem angegeben ist
function check_if_mod($forum_id,$user_id) {
	$is_mod = 0;
	$foren_file = myfile("vars/foren.var"); $foren_file_size = sizeof($foren_file);
	for($i = 0; $i < $foren_file_size; $i++) {
		$aktuelles_forum = myexplode($foren_file[$i]);
		if($aktuelles_forum[0] != $forum_id || $forum_id == -1) {
			$aktuelles_forum_mods = explode(",",$aktuelles_forum[11]);
			for($j = 0; $j < sizeof($aktuelles_forum[11]); $j++) {
				if($user_id == $aktuelles_forum_mods[$j]) {
					$is_mod = 1;
					break 2;
				}
			}
		}
	}
	return $is_mod;
}

// Userdatei ändern
function change_user_db($user_id,$db_pos,$db_wert) {
	if(!$user_file = myfile("members/$user_id.xbb")) return FALSE;
	// $user_file_size = sizeof($user_file);
	$user_file[$db_pos] = $db_wert."\r\n";
	myfwrite("members/$user_id.xbb",$user_file,"w");
}

// time() des letzten Beitrags eines Themas aktualisieren
function update_topic_time($forum_id,$topic_id) {
	$topic_file = myfile("foren/$forum_id-$topic_id.xbb");
	$topic_status = myexplode($topic_file[0]); $topic_status[5] = time(); $topic_file[0] = myimplode($topic_status);
	myfwrite("foren/$forum_id-$topic_id.xbb",$topic_file,"w");
}

// Statuszahl in echten Status umwandeln
function morph_status($status,$posts) {
	global $config,$cache;
	switch($status) {
		case "1":
			$status = $config['var_admin'];
		break;

		case "2":
			$status = $config['var_mod'];
		break;

		case "3":
			if(!$cache[ranks]) {
				$cache[ranks] = myfile("vars/rank.var");
			}
			for($i = 0; $i < sizeof($cache[ranks]); $i++) {
				$akt_rank = myexplode($cache[ranks][$i]);
				if($posts >= $akt_rank[2] && $posts <= $akt_rank[3]) { $status = $akt_rank[1]; break; }
			}
		break;

		case "4":
			$status = $config['var_banned'];
		break;

		case "5":
			$status = $config['var_killed'];
		break;
	}
	return $status;
}

// Überprüfen, ob IP Zugang zu einem Forum hat hat
function check_ip_access($ip,$forum_id) {
	$ips = myfile("vars/ip.var"); $access = 1;
	for($i = 0; $i < sizeof($ips); $i++) {
		$akt_ip = myexplode($ips[$i]);
		if($akt_ip[0] == $ip && $akt_ip[2] == $forum_id && ($akt_ip[1] > time() || $akt_ip[1] == -1)) {
			$access = 0; break;
		}
	}
	return $access;
}

// Findet das Ende der Sperrzeit einer IP für das gesamte Forum heraus
function get_ip_sperre_endtime($ip,$forum_id) {
	$endtime = 0;
	$ips = myfile("vars/ip.var"); $access = 1;
	for($i = 0; $i < sizeof($ips); $i++) {
		$akt_ip = myexplode($ips[$i]);
		if($akt_ip[0] == $ip && $akt_ip[2] == $forum_id && ($akt_ip[1] > time() || $akt_ip[1] == -1)) {
			$endtime = $akt_ip[1];
		}
	}
	return $endtime;
}

// Die User-ID anhand eines Nicks herausfinden
function get_user_id_nick($nick) {
	$nick = strtolower($nick);
	$member_anzahl = myfile("vars/last_user_id.var"); $member_anzahl = $member_anzahl[0] + 1;
	for($i = 1; $i < $member_anzahl; $i++) {
		if($akt_member = myfile("members/$i.xbb")) {
			if(strtolower(killnl($akt_member[0])) == $nick && killnl($akt_member[4]) != 5) {
				$user_id = killnl($akt_member[1]);
				break;
			}
		}
	}
	if(!isset($user_id)) return FALSE;
	else return $user_id;
}

// Die User-ID anhand einer Emailadresse herausfinden
function get_user_id_email($email) {
	$user_id = -1; $email = strtolower($email);
	if($email != "gast") {
		$member_anzahl = myfile("vars/last_user_id.var"); $member_anzahl = $member_anzahl[0] + 1;
		for($i = 1; $i < $member_anzahl; $i++) {
			$akt_member = myfile("members/$i.xbb");
			if(strtolower(killnl($akt_member[3])) == $email && killnl($akt_member[4]) != 5) {
				$user_id = killnl($akt_member[1]);
				break;
			}
		}
	}
	return $user_id;
}

// Zufallszahl berechnen (Version 1.1)
function get_rand_num($length) {
	srand((double)microtime()*1000000); // Zufallsgenerator aktivieren
	$x = "";
	for($i = 0; $i < $length; $i++) $x .= rand(0,9);
	return $x;
}

// Zufallsstring berechnen (Version 1.1)
function get_rand_string($length) {
	mt_srand((double)microtime()*1000000);
	return substr(md5(mt_rand()),0,$length);
}

// Einen Text zensieren (Version 1.1)
function censor($text) {
	$cwords = myfile("vars/cwords.var");
	for($i = 0; $i < sizeof($cwords); $i++) {
		$akt_cword = myexplode($cwords[$i]);
		$text = eregi_replace($akt_cword[1],$akt_cword[2],$text);
	}
	return $text;
}

// Datum formatieren (Version 1.1)
function mydate() {
	return gmdate("YmdHis");
}

// Eine Datei komplett als String (nicht als Array!) einlesen (Version 1.2)
function myfread($file) {
	global $file_counter;
	if(!$fp = fopen($file,'rb')) {
		mylog("1","Datei $file konnte nicht eingelesen werden!");
		return FALSE;
	}
	else {
		flock($fp,LOCK_SH);
		$data = fread($fp,filesize($file));
		flock($fp,LOCK_UN); fclose($fp);
		$file_counter++;
		return $data;
	}
}

// Etwas in eine Datei schreiben (Version 1.1)
function myfwrite($file,$towrite,$mode) {
	global $cache,$file_counter,$config;
	$set_chmod = 0;
	if(!myfile_exists($file)) $set_chmod = 1; // Falls Datei nicht existiet, später 777 "chmoden"
	$fp = fopen($config['datapath'].'/'.$file,$mode.'b') or die(mylog("1","Dateifehler: Datei: $file; Modus: $mode")); flock($fp,LOCK_EX);
	if(!is_array($towrite)) { // Falls Variable ein Array ist, anders schreiben
		fwrite($fp,$towrite);
	}
	else {
		for($i = 0; $i < sizeof($towrite); $i++) {
			fwrite($fp,$towrite[$i]);
		}
	}
	flock($fp,LOCK_UN); fclose($fp);
	if($set_chmod == 1) {
		@chmod($config['datapath'].'/'.$file,0777);
	}

	// Das Folgende ist extrem wichtig. Es updatet den Cache, damit später nicht veraltete Dinge angezeigt werden
	if($mode == "w") $cache['files'][$file] = $towrite; // Falls der Schreibmodus "w" war, kann man einfach das zu Schreibende holen
	else { // Andernfalls muss man eben die Datei neu einlesen
		$file_counter++;
		$cache['files'][$file] = @file($config['datapath'].'/'.$file);
	}
}


// WIO-Funktion: Alte Einträge löschen (Version 1.1)
function wio_koe() { // Wieso hab ich das wio_koe genannt??? Falls jemand weiß, was koe bedeutet, soll er mir bitte an xcrew@barrysworld.com mailen ;)
	global $config;
	$wio_file = myfile("vars/wio.var"); $wio_file_size = sizeof($wio_file); $x = 0;
	for($i = 0; $i < $wio_file_size; $i++) {
		$aktueller_wio = myexplode($wio_file[$i]);
		if($aktueller_wio[0] + ($config['wio_timeout'] * 60) < time()) {
			$wio_file[$i] = ""; $x++;
		}
	}
	if($x > 0) myfwrite("vars/wio.var",$wio_file,"w");
}

// WIO-Funktion: Aktuellen "Status" setzten (Version 1.1)
function wio_set($data) {
	global $config,$session_upbwio,$special_id;

	if($config['wio'] == 1) {
		wio_koe();
		$write = "";
		if($session_upbwio != "no") { // Nur fortfahren, wenn der User sich nicht "versteckt"
			$wio_file = myfile("vars/wio.var");
			for($i = 0; $i < sizeof($wio_file); $i++) { // Erst wird geschaut, ob der User schon im WIO steht
				$akt_wio = myexplode($wio_file[$i]);
				if($akt_wio[1] == $session_upbwio || $akt_wio[1] == $special_id) {
					$write = "yes"; $akt_wio[2] = $data; $akt_wio[0] = time();
					$wio_file[$i] = myimplode($akt_wio); break;
				}
			}

			if($write == "yes") myfwrite("vars/wio.var",$wio_file,"w");
			else { // Wenn der User nicht im WIO steht, wird er neu hinzugefügt
				$towrite = time()."\t$special_id\t$data\t\n";
				myfwrite("vars/wio.var",$towrite,"a");
			}
		}
	}
}

// Navigationsleiste anzeigen (Version 1.1)
function navbar($data) {
	global $twidth,$config,$user_status,$MYSID1,$MYSID2;
	$data = str_replace("\t","&nbsp;&#187;&nbsp;",$data);
	if(@func_get_arg(1) == "no") $temp_user_status = "";
	else $temp_user_status = "<td class=\"navbar\" align=\"right\"><span class=\"navbar\">$user_status</span></td>";
	return "<br><center><table class=\"navbar\" width=\"$twidth\" border=0 cellspacing=0 cellpadding=0><tr><td class=\"navbar\"><span class=\"navbar\">&nbsp;<a class=\"navbar\" href=\"index.php$MYSID1\">".$config['forum_name']."</a>&nbsp;&#187;&nbsp;$data</span></td>$temp_user_status</tr></table><br>";
}

// Administrations-Navigationsleiste anzeigen (Version 1.1)
function adnavbar($data) {
	global $config,$user_status,$MYSID1,$MYSID2;
	$data = str_replace("\t","&nbsp;&#187;&nbsp;",$data);
	return "<br><center><table class=\"navbar\" width=\"100%\" border=0 cellspacing=0 cellpadding=0><tr><td class=\"navbar\"><span class=\"navbar\">&nbsp;<a class=\"navbar\" href=\"index.php$MYSID1\">".$config['forum_name']."</a>&nbsp;&#187;&nbsp;<a href=\"adminpanel.php$MYSID1\">Administration</a>&nbsp;&#187;&nbsp;$data</span></td><td class=\"navbar\" align=\"right\"><span class=\"navbar\">$user_status</span></td></tr></table><br>";
}

// Dinge loggen (Version 1.1)
function mylog($mode,$data) {
	global $config,$user_data,$user_logged_in,$REMOTE_ADDR;
	$x = explode(",",$config['log_options']);
	for($i = 0; $i < sizeof($x); $i++) {
		if($x[$i] == $mode) {
			if($user_logged_in == 1) $log_name = "\"$user_data[nick]\" (ID: $user_data[id])";
			else $log_name = "(nicht eingeloggt)";
			$date1 = gmdate("dmY");
			$data = str_replace("%1",$log_name,$data);
			$data = str_replace("%2",$REMOTE_ADDR,$data);
			$data = date("r")." ".$data."\r\n";
			myfwrite("logs/$date1.log",$data,"a");
			break;
		}
	}
}

// Wendet den file() Befehl an, und zählt gleichzeitig mit, wie oft er eingesetzt wurde (Version 1.1)
function myfile($file) {
	global $file_counter,$cache,$config;
	if(!isset($cache['files'][$file]) || $config['use_file_caching'] != 1) {
		$cache['files'][$file] = @file($config['datapath'].'/'.$file);
		$file_counter++;
	}
	return $cache['files'][$file];
}

// Trifft alle Einstellungen für die Administration (Version 1.1)
function ad() {
	global $ad,$twidth,$lng,$config,$twidth_old;
	$twidth_old = $twidth;
	if(!isset($lng)) {
		include($config['lng_folder']."/lng_main.php");
		include($config['lng_folder']."/lng_admin.php");
	}
	$ad = 1; $twidth = "100%";
}

// Erhöht die Anzahl der Views eines Topics (Version 1.1)
function increase_topic_views($forum_id,$topic_id,$number) {
	if($topic_file = myfile("foren/$forum_id-$topic_id.xbb")) {
		$topic_info = myexplode($topic_file[0]);
		$topic_info[6] += $number;
		$topic_file[0] = myimplode($topic_info);
		myfwrite("foren/$forum_id-$topic_id.xbb",$topic_file,"w");
	}
}

// Sendet eine Mail vom Forum (Version 1.1)
function mymail($target,$subject,$message) {
	global $config;
	if($config['activate_mail'] == 1) {
		@mail($target,$subject,$message,"From: \"".$config['forum_name']."\" <".$config['forum_email'].">\nX-Mailer: PHP/".phpversion());
		mylog("9","%1: Mail an $target gesendet (IP: %2)");
	}
}

// Formatiert ein Datum kurz (Version 1.1)
function makesdatum($text) {
	global $config;
	$text = mktime(substr($text,8,2),substr($text,10,2),0,substr($text,4,2),substr($text,6,2),substr($text,0,4)) + (substr($config['gmt_offset'],1,2) * 3600) + (substr($config['gmt_offset'],3,2) * 60) + date("Z");
	$text = gmstrftime("%Y%m%d%H%M",$text);
	$jahr = substr($text,0,4);
	$monat = substr($text,4,2);
	$tag = substr($text,6,2);
	$stunde = substr($text,8,2);
	$minute = substr($text,10,2);
	$text = "$tag.$monat.$jahr $stunde:$minute";
	return $text;
}

// Erstellt aus einem gmdate("...")-String einen time()-String (Version 1.1)
function get_time_string($data) {
	return mktime(substr($data,8,2),substr($data,10,2),0,substr($data,4,2),substr($data,6,2),substr($data,0,4)) + date("Z");
}

// Updatet die letzten 10 Posts (Version 1.1)
function update_last_posts($forum_id,$topic_id,$user_id,$datum) {
	$lposts = myfile("vars/lposts.var");
	if($lposts[0] == "") $lposts = "$forum_id,$topic_id,$user_id,$datum"."\t\t\t\t\t\t\t\t\t";
	else {
		$lposts = myexplode($lposts[0]);
		// Das Folgende ist sehr umständlich gemacht. Das geht bestimmt noch einfacher...
		$lposts[9] = $lposts[8]; $lposts[8] = $lposts[7];
		$lposts[7] = $lposts[6]; $lposts[6] = $lposts[5];
		$lposts[5] = $lposts[4]; $lposts[4] = $lposts[3];
		$lposts[3] = $lposts[2]; $lposts[2] = $lposts[1];
		$lposts[1] = $lposts[0];
		$lposts[0] = "$forum_id,$topic_id,$user_id,$datum";
		$lposts = myimplode($lposts);
	}
	myfwrite("vars/lposts.var",$lposts,"w");
}

// Updatet die heutigen Posts (Version 1.1)
function update_today_posts($forum_id,$topic_id,$user_id,$datum) {
	$todayposts = myfile("vars/todayposts.var");
	if($todayposts[0] == "") $todayposts = gmdate("Yd")."\t$forum_id,$topic_id,$user_id,$datum";
	else {
		$todayposts = myexplode($todayposts[0]);
		if($todayposts[0] == gmdate("Yd")) $todayposts = $todayposts[0]."\t$todayposts[1]"."|$forum_id,$topic_id,$user_id,$datum";
		else $todayposts = gmdate("Yd")."\t$forum_id,$topic_id,$user_id,$datum";
	}
	myfwrite("vars/todayposts.var",$todayposts,"w");
}

// Verschlüsselt einen String (Version 1.1)
function mycrypt($text) {
	return crypt($text,"Xb");
}

// Einen Link zu einem User mit seinem Namen machen (Version 1.1)
function get_user_link($user_id) {
	global $lng,$MYSID2;
	if(strncmp($user_id,'0',1) == 0) $user_link = substr($user_id,1,strlen($user_id));
	elseif($user_data = myfile("members/$user_id.xbb")) $user_link = "<a href=\"index.php?faction=profile&profile_id=$user_id$MYSID2\">".killnl($user_data[0])."</a>";
	else $user_link = $lng['Deleted'];
	return $user_link;
}

// Meldung erstellen (Version 1.2)
function get_message($data) {
	global $lng,$twidth,$tpadding,$tspacing;
	if(func_num_args() > 1) { // Falls noch ein Zusatz zur Nachricht kommt
		$lng['templates'][$data][1] .= func_get_arg(1);
	}
	if(func_num_args() > 2) {
		$temp = func_get_arg(2);
		$lng['templates'][$data][1] = sprintf($lng['templates'][$data][1],$temp);
	}
	return "<table class=\"tbl\" width=\"$twidth\" cellpadding=\"$tpadding\" cellspacing=\"$tspacing\" border=0><tr><th class=\"thnorm\"><span class=\"thnorm\">".$lng['templates'][$data][0]."</span></th></tr><tr><td class=\"td1\" align=\"center\"><span class=\"norm\"><br>".$lng['templates'][$data][1]."<br><br></span></td></tr></table>";
}

// String exploden (Version 1.2)
function myexplode($data) {
	return explode("\t",$data);
}

// Array imploden (Version 1.2)
function myimplode($data) {
	return implode("\t",$data);
}

// Gruppenname herrausfinden (Version 1.2)
function get_group_data($group_id) {
	$group_data = FALSE;
	$groups_file = myfile("vars/groups.var");
	for($i = 0; $i < sizeof($groups_file); $i++) {
		$akt_group = myexplode($groups_file[$i]);
		if($akt_group[0] == $group_id) {
			$group_data = array();
			$group_data['name'] = $akt_group[1];
			$group_data['pic'] = $akt_group[2];
			break;
		}
	}
	return $group_data;
}

// file_exists mit dem datapath (Version 1.2)
function myfile_exists($file) {
	global $config;
	return file_exists($config['datapath'].'/'.$file);
}

// mutate() für array elemente
function array_mutate(&$item) {
	$item = mutate($item);
}

// Holt die allgemeinen Rechte eines Forums (Version 1.2)
function get_forum_rights($forum_id) {
	global $cache;
	if(isset($cache['forumrights'][$forum_id])) return $cache['forumrights'][$forum_id]; // Erst testen, ob nicht was im Cache ist
	else {
		$forums_file = myfile('vars/foren.var');
		for($i = 0; $i < sizeof($forums_file); $i++) {
			$akt_forum = myexplode($forums_file[$i]);
			if($akt_forum[0] == $forum_id) {
				$cache['forumrights'][$forum_id] = explode(',',$akt_forum[10]);
				return $cache['forumrights'][$forum_id];
				break;
			}
		}
	}
}

// Findet heraus, ob User irgendwas betreten darf (Die Funktion ist noch irgendwie komisch...) (Version 1.2)
function check_right($forum_id,$what) {
	global $user_id,$user_data,$user_logged_in,$cache;
	if(isset($cache['rights'][$forum_id][$what])) return $cache['rights'][$forum_id][$what];
	else {
		$right = 0;
		if($user_logged_in == 1) { // Nur testen, wenn User auch eingeloggt ist, da das andere vom Script schon getestet sein sollte
			$forum_rights = get_forum_rights($forum_id);
			if($user_data['status'] == 1) $right = 1; // User ist Admin => Zugriff
			elseif(test_mod($forum_id) == 1) $right = 1; // User ist Mod => Zugriff
			else {
				$forum_special_rights = myfile("foren/$forum_id-rights.xbb");
				if($forum_rights[$what] == 1) { // Jetzt muss noch eventuell bewiesen werden, dass der User kein Zugriff hat
					$right = 1;
					for($i = 0; $i < sizeof($forum_special_rights); $i++) {
						$akt_right = myexplode($forum_special_rights[$i]);
						if(($akt_right[1] == 1 && $akt_right[2] == $user_id) || ($akt_right[1] == 2 && $user_data[15] == $akt_right[2])) {
							if($akt_right[$what+3] != 1) $right = 0;
							else $right = 1;
							break;
						}
					}
				}
				else { // Jetzt muss noch eventuell bewiesen werden, dass der User doch Zugriff hat
					$right = 0;
					for($i = 0; $i < sizeof($forum_special_rights); $i++) {
						$akt_right = myexplode($forum_special_rights[$i]);
						if(($akt_right[1] == 1 && $akt_right[2] == $user_id) || ($akt_right[1] == 2 && $user_data[15] == $akt_right[2])) {
							if($akt_right[$what+3] == 1) $right = 1;
							else $right = 0;
							break;
						}
					}
				}
			}
		}
		return $right;
	}
}

?>