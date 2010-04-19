<?

/* index.php - Zeigt einfach nur das Forum an (c) 2001-2002 Tritanium Scripts */

require_once("functions.php");
require_once("loadset.php");
require_once("auth.php");
require_once($config['lng_folder']."/lng_main.php");

$allowed = 1; // Zugang wird erst einmal gewährt
if(!isset($faction) && isset($upb)) $faction = $upb;
elseif(!isset($faction)) $faction= '';

if($session_upbconnection != "1") { // Überprüfen, ob User bei dieser Session schon im Forum war
	$session_upbconnection = 1;
	session_register("session_upbconnection"); // Session-Variable setzen
	mylog("6","User connected (IP: %2)");
}

if($config['use_diskfreespace'] == 1) {
	if(round(diskfreespace(".")/1024) <= $config['warn_admin_fds']*1024) { // Überprüfen, ob genügend Speicherplatz vorhanden ist
		$fds_file = myfile("vars/fds.var");
		if($fds_file[0] != "1" && $fds_file[0] != "2") {
			$datum = makesdatum(mydate());

			$search = array("{DATE}","{ADMINLOGINLINK}");
			$replace = array($datum,$config['address_to_forum']."/ad_login.php");
			$email_file = myfread($config['lng_folder'].'/mails/discfreespace_warning.dat');
			$email_file = str_replace($search,$replace,$email_file);

			mylog("1","Speicherplatzwarnung; Admin benachrichtigt"); // loggen
			myfwrite("vars/fds.var",1,"w");
			mymail($config['admin_email'],$lng['mail_subjects']['discfreespace_warning'],$email_file);
		}

		if(round(diskfreespace(".")/1024) <= $config['close_forum_fds']*1024) { // Überprüfen, ob Alarm aktiviert werden muss
			$config['uc'] = 1; // Wartungsmodus aktivieren
			if($fds_file[0] != "2") {
				$datum = makesdatum(mydate());

				$search = array("{DATE}","{ADMINLOGINLINK}");
				$replace = array($datum,$config['address_to_forum']."/ad_login.php");
				$email_file = myfread($config['lng_folder'].'/mails/discfreespace_alarm.dat');
				$email_file = str_replace($search,$replace,$email_file);
				mymail($config['admin_email'],$lng['mail_subjects']['discfreespace_alarm'],$email_file);

				mylog("1","Speicherplatzalarm; Admin benachrichtigt; Forum geschlossen"); // loggen
				myfwrite("vars/fds.var",2,"w");
			}
		}
	}
}

if($config['uc'] == 1 && $user_data['status'] != 1) {
	$config['forum_logo'] == '' ? $show_logo = '' : $show_logo = "<img src=\"".$config['forum_logo']."\"><br>";
	?>
		<html><head><title>Under Construction</title></head><body>
		<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
		<tr><td align="center" valign="middle"><?=$show_logo.$config['uc_message']?></td></tr>
		</table></body></html>
	<?
	$allowed = 0; // Zugang nicht erlaubt
}

elseif(check_ip_access($REMOTE_ADDR,-1) != 1) {
	$endtime = get_ip_sperre_endtime($REMOTE_ADDR,-1);
	if($endtime == -1) $endtime = $lng['banned_forever'];
	else {
		$endtime = ceil(($endtime - time()) / 60);
		$endtime = sprintf($lng['banned_for_x_minutes'],$endtime);
	}

	include("pageheader.php");
	echo navbar($lng['No_access']);
	echo get_message("ip_banned_everywhere",'<br>'.$endtime);
	include("pagetail.php");

	$allowed = 0;
}

elseif($config['must_be_logged_in'] == 1 && $user_logged_in != 1 && $faction != "register" && $faction != "login" && $faction != "regeln" & $faction != "sendpw") {
	include("pageheader.php");
	echo navbar($lng['No_access']);
	echo get_message('members_only','<br>'.sprintf($lng['links']['register_or_login'],"<a href=\"index.php?faction=register$MYSID2\">",'</a>',"<a href=\"index.php?faction=login$MYSID2\">",'</a>'));
	include("pagetail.php");
	$allowed = 0;
}

if($allowed == 1) {
	switch($faction) {

		// reply.php
		case "reply":
			setcookie("upbwhere","index.php?faction=reply&forum_id=$forum_id&thread_id=$thread_id");
			include("pageheader.php");
			include("reply.php");
			include("pagetail.php");
		break;

		// newtopic.php
		case "newtopic":
			setcookie("upbwhere","index.php?faction=newtopic&forum_id=$forum_id");
			include("pageheader.php");
			include("newtopic.php");
			include("pagetail.php");
		break;

		// editpoll.php
		case "editpoll":
			include('editpoll.php');
		break;

		// vote.php
		case "vote":
			include('vote.php');
		break;

		// newtopic.php
		case "newpoll":
			setcookie("upbwhere","index.php?faction=newpoll&forum_id=$forum_id");
			include("newpoll.php");
			include("pagetail.php");
		break;

		// edit.php
		case "edit":
			include("pageheader.php");
			include("edit.php");
			include("pagetail.php");
		break;

		// profile.php
		case "profile":
			include("profile.php");
			include("pagetail.php");
		break;

		// login.php
		case "login":
			include("login.php");
			include("pagetail.php");
		break;

		// logout.php
		case "logout":
			include("logout.php");
		break;

		// faq.php
		case "faq":
			include("pageheader.php");
			include("faq.php");
			include("pagetail.php");
		break;

		// register.php
		case "register":
			include("register.php");
			include("pagetail.php");
		break;

		// pm.php
		case "pm":
			include("pm.php");
			include("pagetail.php");
		break;

		// regeln.php
		case "regeln":
			include("pageheader.php");
			include("regeln.php");
			include("pagetail.php");
		break;

		// search.php
		case "search":
			include("pageheader.php");
			include("search.php");
			include("pagetail.php");
		break;

		// topic.php
		case "topic":
			include("pageheader.php");
			include("topic.php");
			include("pagetail.php");
		break;

		// wio.php
		case "wio":
			include("wio.php");
		break;

		// viewip.php
		case "viewip":
			include("pageheader.php");
			include("viewip.php");
			include("pagetail.php");
		break;

		// viewip.php
		case "mlist":
			include("pageheader.php");
			include("listmember.php");
			include("pagetail.php");
		break;

		// sendpw.php
		case "sendpw":
			include("sendpw.php");
			include("pagetail.php");
		break;

		// formmail.php
		case "formmail":
			include("pageheader.php");
			include("formmail.php");
			include("pagetail.php");
		break;

		// readforum.php
		default:
			if(check_ip_access($REMOTE_ADDR,$forum_id) != 1) {
				$endtime = get_ip_sperre_endtime($REMOTE_ADDR,$forum_id);
				if($endtime == -1) $endtime = $lng['banned_forever'];
				else {
					$endtime = ceil(($endtime - time()) / 60);
					$endtime = sprintf($lng['banned_for_x_minutes'],$endtime);
				}

				include("pageheader.php");
				echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">".get_forum_name($forum_id)."</a>\t".$lng['templates']['ip_banned_one_forum'][0]);
				echo get_message("ip_banned_one_forum",'<br>'.$endtime);
				include("pagetail.php");
			}
			else {
				if($mode == "viewforum") {
					setcookie("upbwhere","index.php?mode=viewforum&forum_id=$forum_id"); // Redirect-Cookie für nach dem Einloggen
					setcookie("forum,$forum_id",time(),time()+(3600*24*365),$config['path_to_forum']); // Cookie, um letzten Besuch festzustellen
				}
				elseif($mode == "viewthread") {
					setcookie("upbwhere","index.php?mode=viewthread&forum_id=$forum_id&thread=$thread"); // Redirect-Cookie für nach dem Einloggen
					setcookie("topic,$forum_id,$thread",time(),time()+(3600*24*365),$config['path_to_forum']); // Cookie, um letzten Besuch festzustellen

					$temp_var = "session.tview.$forum_id.$thread";
					if($$temp_var != 1) {
						$$temp_var = 1;
						increase_topic_views($forum_id,$thread,1);
						session_register($temp_var); // Hier wird die Variable mit dem Namen des Inhalts von $temp_var registriert und nicht die Variable $temp_var selbst!
					}

				}
				else setcookie("upbwhere","index.php");

				include("pageheader.php");
				include("readforum.php");
				include("pagetail.php");
			}
		break;

		// credits.php *g*
		case "credits":
			include("credits.php");
		break;
	}
}

?>