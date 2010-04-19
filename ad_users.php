<?php
/**
*
* Tritanium Bulletin Board 2 - ad_users.php
* version #2005-01-20-20-45-11
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

switch(@$_GET['mode']) {
	default:
		$tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_users']);

		//$DB->query
//		$ad_users_tpl->blocks['nolockedusers']->parse_code();

		include_once('ad_pheader.php');
		$tpl->parse_code(TRUE);
		include_once('ad_ptail.php');
	break;

	case 'adduser':
		$p_user_name = isset($_POST['p_user_name']) ? $_POST['p_user_name'] : '';
		$p_user_email = isset($_POST['p_user_email']) ? $_POST['p_user_email'] : '';
		$p_user_pw1 = isset($_POST['p_user_pw1']) ? $_POST['p_user_pw1'] : '';
		$p_user_pw2 = isset($_POST['p_user_pw2']) ? $_POST['p_user_pw2'] : '';

		$p_notify_user = 1;

		$error = '';

		if(isset($_GET['doit'])) {
			$p_notify_user = isset($_POST['p_notify_user']) ? 1 : 0;

			if(trim($p_user_name) == '' || verify_nick($p_user_name) == FALSE) $error = $LNG['error_bad_nick'];
			elseif(unify_nick($p_user_name) == FALSE) $error = $LNG['error_nick_already_in_use'];
			elseif(trim($p_user_email) == '' || verify_email($p_user_email) == FALSE) $error = $LNG['error_bad_email'];
			elseif(trim($p_user_pw1) == '') $error = $LNG['error_no_pw'];
			elseif($p_user_pw1 != $p_user_pw2) $error = $LNG['error_pws_no_match'];
			else {
				$p_user_pwc = mycrypt($p_user_pw1);
				$DB->query("INSERT INTO ".TBLPFX."users (user_nick,user_email,user_pw,user_regtime,user_tz) VALUES ('$p_user_name','$p_user_email','$p_user_pwc','".time()."','".$CONFIG['standard_tz']."')");

				if($p_notify_user == 1 && $CONFIG['enable_email_functions'] == 1) {
					$email_tpl = new template($LANGUAGE_PATH.'/emails/email_welcome.tpl');
					mymail($CONFIG['board_name'].' <'.$CONFIG['board_email_address'].'>',$p_user_email,sprintf($LNG['email_subject_welcome'],$CONFIG['board_name']),$email_tpl->parse_code());
				}

				include_once('ad_pheader.php');
				show_message($LNG['User_added'],sprintf($LNG['message_new_user_added'],$p_user_name),FALSE);
				include_once('ad_ptail.php'); exit;
			}
		}

		$c = ' checked="checked"';
		$checked = array(
			'notify'=>($p_notify_user == 1) ? $c : ''
		);

		$ad_users_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_users_adduser']);

		if($error != '') $ad_users_tpl->blocks['errorrow']->parse_code();

		include_once('ad_pheader.php');
		$ad_users_tpl->parse_code(TRUE);
		include_once('ad_ptail.php');
	break;

	case 'searchusers':
		$p_user_id = isset($_POST['p_user_id']) ? $_POST['p_user_id'] : '';
		$p_user_name = isset($_POST['p_user_name']) ? $_POST['p_user_name'] : '';
		$p_user_email = isset($_POST['p_user_email']) ? $_POST['p_user_email'] : '';

		$sql_query = array();
		if(trim($p_user_id) != '' || trim($p_user_name) != '' || trim($p_user_email) != '') {
			if($p_user_id != '')
				$sql_query[] = "user_id LIKE '".str_replace('*','%',$p_user_id)."'";
			if($p_user_name != '')
				$sql_query[] = "user_nick LIKE '".str_replace('*','%',$p_user_name)."'";
			if($p_user_email != '')
				$sql_query[] = "user_email LIKE '".str_replace('*','%',$p_user_email)."'";
		}

		$ad_users_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_users_searchusers']);

		if(count($sql_query) > 0) {
			$DB->query("SELECT user_id,user_nick,user_email FROM ".TBLPFX."users WHERE ".implode(' AND ',$sql_query));
			if($DB->affected_rows > 0) {
				if($DB->affected_rows == 1) {
					$result = $DB->fetch_array();
					header("Location: administration.php?faction=ad_users&mode=edituser&user_id=".$result['user_id']."&$MYSID"); exit;
				}
				while($akt_result = $DB->fetch_array())
					$ad_users_tpl->blocks['resultrow']->parse_code(FALSE,TRUE);
			}
		}

		include_once('ad_pheader.php');
		$ad_users_tpl->parse_code(TRUE);
		include_once('ad_ptail.php');
	break;

	case 'edituser':
		$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

		if(!$user_data = get_user_data($user_id)) die('Benutzer existiert nicht!');

		$p_user_email = isset($_POST['p_user_email']) ? $_POST['p_user_email'] : addslashes($user_data['user_email']);
		$p_user_signature = isset($_POST['p_user_signature']) ? $_POST['p_user_signature'] : addslashes($user_data['user_signature']);
		$p_user_avatar_address = isset($_POST['p_user_avatar_address']) ? $_POST['p_user_avatar_address'] : addslashes($user_data['user_avatar_address']);

		$p_user_is_admin = $user_data['user_is_admin'];
		$p_user_is_supermod = $user_data['user_is_supermod'];

		$error = '';

		if(isset($_GET['doit'])) {
			$p_user_is_admin = isset($_POST['p_user_is_admin']) ? 1 : 0;
			$p_user_is_supermod = isset($_POST['p_user_is_supermod']) ? 1 : 0;

			if(trim($p_user_email) == '' || verify_email($p_user_email) == FALSE) $error = $LNG['error_bad_email'];
			else {
				if($user_id == $USER_ID)
					$p_user_is_admin = 1;

				$DB->query("UPDATE ".TBLPFX."users SET user_is_admin='$p_user_is_admin', user_is_supermod='$p_user_is_supermod', user_email='$p_user_email', user_signature='$p_user_signature', user_avatar_address='$p_user_avatar_address' WHERE user_id='$user_id'");


				include_once('ad_pheader.php');
				show_message($LNG['User_edited'],sprintf($LNG['message_user_edited'],$user_data['user_nick']),FALSE);
				include_once('ad_ptail.php'); exit;
			}
		}

		$c = ' checked="checked"';
		$checked = array(
			'isadmin'=>($p_user_is_admin == 1) ? $c : '',
			'issupermod'=>($p_user_is_supermod == 1) ? $c : ''
		);

		$ad_users_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_users_edituser']);

		if($user_data['user_is_locked'] != 0) { // Falls der Benutzer gesperrt ist...
			$DB->query("SELECT lock_type,lock_start_time,lock_dur_time FROM ".TBLPFX."users_locks WHERE user_id='$user_id'"); // ...Sperre laden
			$lock_data = $DB->fetch_array();

			if($lock_data['lock_dur_time'] != 0 && $lock_data['lock_start_time']+$lock_data['lock_dur_time'] < time()) { // Falls Sperre nicht mehr aktiv ist
				$DB->query("DELETE FROM ".TBLPFX."users_locks WHERE user_id='$user_id'"); // Sperre loeschen
				$DB->query("UPDATE ".TBLPFX."users SET user_is_locked='0' WHERE user_id='$user_id'"); // Benutzer auf "entsperrt" setzen
				$ad_users_tpl->blocks['lockuserform']->parse_code();
			}
			else { // Falls Sperre noch aktiv ist
				if($lock_data['lock_dur_time'] == 0) {
					$remaining_lock_time = $LNG['locked_forever'];
				}
				else {
					$remaining_lock_time = split_time($lock_data['lock_start_time']+$lock_data['lock_dur_time']-time());

					$remaining_months = sprintf($LNG['x_months'],$remaining_lock_time['months']);
					$remaining_weeks = sprintf($LNG['x_weeks'],$remaining_lock_time['weeks']);
					$remaining_days = sprintf($LNG['x_days'],$remaining_lock_time['days']);
					$remaining_hours = sprintf($LNG['x_hours'],$remaining_lock_time['hours']);
					$remaining_minutes = sprintf($LNG['x_minutes'],$remaining_lock_time['minutes']);
					$remaining_seconds = sprintf($LNG['x_seconds'],$remaining_lock_time['seconds']);

					$remaining_lock_time = "$remaining_months, $remaining_weeks, $remaining_days, $remaining_hours, $remaining_minutes, $remaining_seconds";
				}

				$ad_users_tpl->blocks['lockeduserform']->parse_code();
			}
		}
		else
			$ad_users_tpl->blocks['lockuserform']->parse_code();

		include_once('ad_pheader.php');
		$ad_users_tpl->parse_code(TRUE);
		include_once('ad_ptail.php');
	break;

	case 'deleteuser':
		$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
		$p_delete_posts = isset($_POST['p_delete_posts']) ? 1 : 0;
		$p_ban_nick_email = isset($_POST['p_ban_nick_email']) ? 1 : 0;

		if(!$user_data = get_user_data($user_id)) die('Benutzer existiert nicht!');

		$DB->query("DELETE FROM ".TBLPFX."users WHERE user_id='$user_id'"); // Userdaten
		$DB->query("DELETE FROM ".TBLPFX."pms_folders WHERE user_id='$user_id'"); // PMs-Ordner
		$DB->query("UPDATE ".TBLPFX."pms SET user_id='0', pm_guest_nick='".$user_data['user_nick']."' WHERE pm_from_id='$user_id'"); // PM-Nachrichten in fremden Ordnern
		$DB->query("DELETE FROM ".TBLPFX."pms WHERE pm_to_id='$user_id'"); // Eigene PMs
		$DB->query("DELETE FROM ".TBLPFX."polls_votes WHERE voter_id='$user_id'"); // Umfrageteilnahmen
		$DB->query("DELETE FROM ".TBLPFX."groups_members WHERE member_id='$user_id'"); // Gruppenmitgliedschaften
		$DB->query("DELETE FROM ".TBLPFX."forums_auth WHERE auth_type='0' AND auth_id='$user_id'"); // Forenzugriffe
		$DB->query("DELETE FROM ".TBLPFX."topics_subscriptions WHERE user_id='$user_id'"); // Themenabonnements


		//
		// Themen/Beitraege
		//
		if($p_delete_posts == 1) { // Falls alles geloescht werden soll...
			$affected_forum_ids = array(); // Beinhaltet spaeter alle Foren-IDs, in denen Beitraege geloescht wurden
			$affected_topic_ids = array(); // Beinhaltet spaeter alle Themen-IDs, in denen Beitraege geloescht wurden

			//
			// Erst muessen die Themen geloescht werden, die der User erstellt hat
			// Dazu werden erst mal die Themen-IDs bestimmt
			//
			$DB->query("SELECT topic_id FROM ".TBLPFX."topics WHERE poster_id='$user_id'");
			$topic_ids = $DB->raw2fvarray();
			$topic_idsi = implode("','",$topic_ids);


			//
			// Jetzt muessen die Beitragszahlen der User entsprechend gesenkt werden
			//
			$DB->query("SELECT COUNT(*) AS posts_counter,poster_id FROM ".TBLPFX."posts WHERE topic_id IN ('$topic_idsi') GROUP BY poster_id");
			$posts_counter = $DB->raw2array();
			while(list(,$akt_posts_counter) = each($posts_counter))
				$DB->query("UPDATE ".TBLPFX."users SET user_posts=user_posts-".$akt_posts_counter['posts_counter']." WHERE user_id='".$akt_posts_counter['poster_id']."'");


			//
			// Und nun die Beitragszahlen der entsprechenden Foren
			//
			$DB->query("SELECT COUNT(*) AS posts_counter,forum_id FROM ".TBLPFX."posts WHERE topic_id IN ('$topic_idsi') GROUP BY forum_id");
			$posts_counter = $DB->raw2array();
			while(list(,$akt_posts_counter) = each($posts_counter)) {
				$DB->query("UPDATE ".TBLPFX."forums SET forum_posts_counter=forum_posts_counter-".$akt_posts_counter['posts_counter']." WHERE forum_id='".$akt_posts_counter['forum_id']."'");
				$affected_forum_ids = $akt_posts_counter['forum_id'];
			}


			//
			// Jetzt die Themenzahlen der entsprechenden Foren
			//
			$DB->query("SELECT COUNT(*) AS topics_counter,forum_id FROM ".TBLPFX."topics WHERE topic_id IN ('$topic_idsi') GROUP BY forum_id");
			$topics_counter = $DB->raw2array();
			while(list(,$akt_topics_counter) = each($topics_counter))
				$DB->query("UPDATE ".TBLPFX."forums SET forum_topics_counter=forum_topics_counter-".$akt_topics_counter['topics_counter']." WHERE forum_id='".$akt_topics_counter['forum_id']."'");


			//
			// Jetzt werden die Themen-Abonnnements, die Themen und die Beitraege geloescht
			//
			$DB->query("DELETE FROM ".TBLPFX."topics_subscriptions WHERE topic_id IN ('$topic_idsi')");
			$DB->query("DELETE FROM ".TBLPFX."posts WHERE topic_id IN ('$topic_idsi')");
			$DB->query("DELETE FROM ".TBLPFX."topics WHERE topic_id IN ('$topic_idsi')");


			//
			// Jetzt noch die Umfragen, dazu die Umfrageoptionen und die Abstimmungen
			//
			$DB->query("SELECT poll_id FROM ".TBLPFX."polls WHERE topic_id IN ('$topic_idsi')");
			$poll_ids = $DB->raw2fvarray();
			$poll_idsi = implode("','",$poll_ids);

			$DB->query("DELETE FROM ".TBLPFX."polls WHERE poll_id IN ('$poll_idsi')");
			$DB->query("DELETE FROM ".TBLPFX."polls_options WHERE poll_id IN ('$poll_idsi')");
			$DB->query("DELETE FROM ".TBLPFX."polls_votes WHERE poll_id IN ('$poll_idsi')");


			//
			// Als letztes die einzelnen Beitraege des Users, dazu erst die Beitragszahlen der entsprechenden Foren, dann die Beitragszahlen der einzelnen Themen, dann die Beitraege selbst
			//
			$DB->query("SELECT COUNT(*) AS posts_counter,forum_id FROM ".TBLPFX."posts WHERE poster_id='$user_id' GROUP BY forum_id");
			$forum_posts_counter = $DB->raw2array();
			while(list(,$akt_posts_counter) = each($forum_posts_counter)) {
				$DB->query("UPDATE ".TBLPFX."forums SET forum_posts_counter=forum_posts_counter-".$akt_posts_counter['posts_counter']." WHERE forum_id='".$akt_posts_counter['forum_id']."'");
				$affected_forum_ids[] = $akt_posts_counter['forum_id'];
			}

			$DB->query("SELECT COUNT(*) AS replies_counter,topic_id FROM ".TBLPFX."posts WHERE poster_id='$user_id' GROUP BY topic_id");
			$replies_counter = $DB->raw2array();
			while(list(,$akt_replies_counter) = each($replies_counter)) {
				$DB->query("UPDATE ".TBLPFX."topics SET topic_replies_counter=topic_replies_counter-".$akt_replies_counter['replies_counter']." WHERE topic_id='".$akt_replies_counter['topic_id']."'");
				$affected_topic_ids[] = $akt_replies_counter['topic_id'];
			}

			$DB->query("DELETE FROM ".TBLPFX."posts WHERE poster_id='$user_id'");


			//
			// Jetzt noch Foren und Themen mit dem letzten Beitrag updaten
			//
			$affected_forum_ids = array_unique($affected_forum_ids);
			while(list(,$akt_forum_id) = each($affected_forum_ids))
				update_forum_last_post($akt_forum_id);

			$affected_topic_ids = array_unique($affected_topic_ids);
			while(list(,$akt_topic_id) = each($affected_topic_ids))
				update_topic_last_post($akt_topic_id);
		}
		else { // ...oder auch nicht
			$DB->query("UPDATE ".TBLPFX."posts SET poster_id='0', post_guest_nick='".$user_data['user_nick']."' WHERE poster_id='$user_id'");
			$DB->query("UPDATE ".TBLPFX."topics SET poster_id='0', topic_guest_nick='".$user_data['user_nick']."' WHERE poster_id='$user_id'");
		}
	break;

	case 'lockuser':
		$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

		if(!$user_data = get_user_data($user_id)) die('Kann Benutzerdaten nicht laden!');

		$p_lock_type = isset($_POST['p_lock_type']) ? $_POST['p_lock_type'] : 0;
		$p_lock_time = isset($_POST['p_lock_time']) ? $_POST['p_lock_time'] : 0;

		$DB->query("INSERT INTO ".TBLPFX."users_locks (user_id,lock_type,lock_start_time,lock_dur_time) VALUES ('$user_id','$p_lock_type','".time()."','".($p_lock_time*3600)."')");
		$DB->query("UPDATE ".TBLPFX."users SET user_is_locked='$p_lock_type' WHERE user_id='$user_id'");

		header("Location: administration.php?faction=ad_users&mode=edituser&user_id=$user_id&$MYSID"); exit;
	break;

	case 'unlockuser':
		$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

		if(!$user_data = get_user_data($user_id)) die('Kann Benutzerdaten nicht laden!');

		$DB->query("DELETE FROM ".TBLPFX."users_locks WHERE user_id='$user_id'");
		$DB->query("UPDATE ".TBLPFX."users SET user_is_locked='0' WHERE user_id='$user_id'");

		header("Location: administration.php?faction=ad_users&mode=edituser&user_id=$user_id&$MYSID"); exit;
	break;
}

?>