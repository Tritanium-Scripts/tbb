<?php
/**
*
* Tritanium Bulletin Board 2 - misc/update_tbb1/update_tbb1.php
* version #2005-01-20-20-45-11
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('startup.php');

if(!isset($_SESSION['s_tbb1_path'])) $_SESSION['s_tbb1_path'] = '';
if(!isset($_SESSION['s_last_post_id'])) $_SESSION['s_last_post_id'] = 1;
if(!isset($_SESSION['s_last_topic_id'])) $_SESSION['s_last_topic_id'] = 1;
if(!isset($_SESSION['s_last_option_id'])) $_SESSION['s_last_option_id'] = 1;

if(!isset($_SESSION['s_members_counter'])) $_SESSION['s_members_counter'] = 0;
if(!isset($_SESSION['s_members_complete_counter'])) $_SESSION['s_members_complete_counter'] = 0;

if(!isset($_SESSION['s_topics_counter'])) $_SESSION['s_topics_counter'] = 0;
if(!isset($_SESSION['s_topics_complete_counter'])) $_SESSION['s_topics_complete_counter'] = 0;

if(!isset($_SESSION['s_status_pre'])) $_SESSION['s_status_pre'] = 0;
if(!isset($_SESSION['s_status_members'])) $_SESSION['s_status_members'] = 0;
if(!isset($_SESSION['s_status_topics'])) $_SESSION['s_status_topics'] = 0;
if(!isset($_SESSION['s_status_suf'])) $_SESSION['s_status_suf'] = 0;

if(!isset($_SESSION['s_db_icq_id'])) $_SESSION['s_db_icq_id'] = 0;
if(!isset($_SESSION['s_db_hp_id'])) $_SESSION['s_db_hp_id'] = 0;


$LNG['Next'] = 'Weiter &#187;';
$LNG['Back'] = '&#171; Zur&uuml;ck';
$LNG['Unknown_user'] = 'Unbekannt';

//
// Eine sehr wichtige Konstante. Sie gibt an, wieviele Dateien, in einem Schritt bearbeitet werden sollen.
// Falls der Server zu langsam ist, kann es helfen die Zahl herunterzusetzen
//
define('FILESPERROUND',200);


//
// Erst mal einige Funktionen, die fuer den Updatevorgang sehr wichtig sind
//

/*** Konvertiert das propietaere Registrierdatums-Format des TBB 1 ins Unix-Timestamp-Format ***/
function convertregdate2time($date) {
	$year = substr($date,0,4);
	$month = substr($date,4,2);
	return mktime(0,0,0,$month,0,$year);
}

/*** Konvertiert das propietaere Datums-Format des TBB 1 ins Unix-Timestamp-Format ***/
function convertdate2time($date) {
	return mktime(substr($date,8,2),substr($date,10,2),0,substr($date,4,2),substr($date,6,2),substr($date,0,4));
}

/*** explode() mit "\t" anwenden ***/
function myexplode($data) {
	return explode("\t",$data);
}

/*** implode() mit "\t" anwenden ***/
function myimplode($data) {
	return implode("\t",$data);
}

/*** Liest eine Datei zeilenweise ein ***/
function myfile($file) {
	if(file_exists($file) == FALSE) return FALSE;


	if(!$fp = fopen($file,'rb')) return FALSE;
	$data = @fread($fp,filesize($file)); flock($fp,LOCK_SH);
	flock($fp,LOCK_UN); fclose($fp);
	$data = str_replace("\r\n","\n",$data);
	$data = explode("\n",$data);
	if(sizeof($data) > 1) {
		end($data);
		unset($data[key($data)]);
	}
	elseif($data[0] == '') return array();

	reset($data);

	return $data;
}

/*** Macht das mutate() aus TBB1 wieder rueckgaengig ***/
function tbb1_demutate($text) {
	$text = str_replace('&amp;','&',$text);
	$text = str_replace('&quot;','"',$text);
	$text = str_replace('&lt;','<',$text);
	$text = str_replace('&gt;','>',$text);
	$text = str_replace('ö','Ã¶',$text);
	$text = str_replace('ä','Ã¤',$text);
	$text = str_replace('ü','Ã¼',$text);
	$text = str_replace('ß','ÃŸ',$text);
	$text = str_replace('Ö','Ã–',$text);
	$text = str_replace('Ä','Ã„',$text);
	$text = str_replace('Ü','Ãœ',$text);
	return $text;
}

/*** Ueberprueft, ob ein Benutzer existiert ***/
function user_exists($user_id) {
	return file_exists($_SESSION['s_tbb1_path'].'/members/'.$user_id.'.xbb');
}


$STEP = isset($_GET['step']) ? intval($_GET['step']) : 1;

$STEPS = array(
	1=>'Einf&uuml;hrung',
	2=>'Wahl des TBB1-Verzeichnis',
	3=>'&Uuml;bernahme der Daten',
	4=>'Abschluss'
);

switch($STEP) {
	default:
		install_print_pheader();

		?>
			 <form method="post" action="update_tbb1.php?step=2&amp;<?php echo $MYSID; ?>">
			 <table border="0" cellpadding="2" cellspacing="0" width="100%" style="border:1px black dashed;">
			 <tr><th colspan="2" class="th1"><span class="th1"><?php echo $STEPS[$STEP]; ?></span></th></tr>
			 <tr><td><span class="fontnorm">Willkommen beim Update des Tritanium Bulletin Board 1.2.3 auf Tritanium Bulletin Board 2!<br />Dieses Script wird Sie durch den Konvertierungs- und Updatevorgang leiten. Bitte beachten Sie die Hinweise in der Readme-Datei (readme.html). Im folgenden Schritt wird das Installationsverzeichnis Ihres TBB 1 bestimmt. Bitte klicken Sie dazu auf &quot;weiter&quot;.</span></td></tr>
			 </table>
			 <br />
			 <table border="0" cellpadding="2" cellspacing="0" width="100%" style="border:1px black dashed;">
			 <tr><td align="right"><input class="form_bold_button" type="submit" value="<?php echo $LNG['Next']; ?>" /></td></tr>
			 </table>
			 </form>
		<?php

		install_print_ptail();
	break;

	case '2':
		$p_tbb1_path = isset($_POST['p_tbb1_path']) ? $_POST['p_tbb1_path'] : $_SESSION['s_tbb1_path'];
		$error = '';

		if(isset($_GET['doit'])) {
			if(file_exists($p_tbb1_path.'/foren') == TRUE && file_exists($p_tbb1_path.'/members') == TRUE && file_exists($p_tbb1_path.'/polls') == TRUE && file_exists($p_tbb1_path.'/vars') == TRUE) {
				$_SESSION['s_tbb1_path'] = $p_tbb1_path;
				if(isset($_POST['p_button_back'])) header("Location: update_tbb1.php?step=1&$MYSID");
				else header("Location: update_tbb1.php?step=3&$MYSID");
				exit;
			}
			else $error = '<span class="fontred">Unter dem angegebenen Pfad konnte keine Installation des Tritanium Bulletin Board 1 gefunden werden</span><br />';
		}

		install_print_pheader();

		?>
			 <form method="post" action="update_tbb1.php?step=2&amp;doit=1&amp;<?php echo $MYSID; ?>">
			 <table border="0" cellpadding="2" cellspacing="0" width="100%" style="border:1px black dashed;">
			 <tr><th colspan="2" class="th1"><span class="th1"><?php echo $STEPS[$STEP]; ?></span></th></tr>
			 <tr><td><span class="fontnorm">Bitte geben Sie hier den relativen oder absoluten Pfad zu Ihrer TBB 1-Installation an und klicken dann auf &quot;weiter&quot;. Falls eine g&uuml;ltige Installation vorliegt, werden sie zum n&auml;chsten Schritt, der Konvertierung der Daten, weitergeleitet.<br /><br /><?php echo $error; ?>Pfad zum TBB 1:&nbsp;<input class="form_text" size="60" name="p_tbb1_path" value="<?php echo $p_tbb1_path; ?>" /></span>&nbsp;<span class="fontsmall">(relativer oder absoluter Pfad; ohne / am Ende!)</span></td></tr>
			 </table>
			 <br />
			 <table border="0" cellpadding="2" cellspacing="0" width="100%" style="border:1px black dashed;">
			 <tr><td align="right"><input class="form_button" type="submit" name="p_button_back" value="<?php echo $LNG['Back']; ?>" />&nbsp;&nbsp;&nbsp;<input class="form_bold_button" type="submit" value="<?php echo $LNG['Next']; ?>" /></td></tr>
			 </table>
			 </form>
		<?php

		install_print_ptail();
	break;

	case '3':
		if(isset($_GET['doit'])) {
			if(isset($_POST['p_button_back'])) {
				header("Location: update_tbb1.php?step=2&$MYSID");
				exit;
			}

			switch(@$_GET['substep']) {
				//
				// Der Ordner "vars"
				//
				case '1':
					$_SESSION['s_status_pre'] = 0;
					$_SESSION['s_status_members'] = 0;
					$_SESSION['s_status_topics'] = 0;
					$_SESSION['s_status_suf'] = 0;
					$_SESSION['s_topics_counter'] = 0;
					$_SESSION['s_members_counter'] = 0;
					$_SESSION['s_members_complete_counter'] = 0;
					$_SESSION['s_topics_complete_counter'] = 0;

					//
					// Gruppen
					//
					$group_ids = array();
					$groups_data = myfile($_SESSION['s_tbb1_path'].'/vars/groups.var');
					$DB->query("DELETE FROM ".TBLPFX."groups");
					$DB->query("DELETE FROM ".TBLPFX."groups_members");

					while(list(,$akt_group) = each($groups_data)) {
						$akt_group = myexplode($akt_group);

						$DB->query("INSERT INTO ".TBLPFX."groups (group_id,group_name) VALUES ('".$akt_group[0]."','".mysql_escape_string(tbb1_demutate($akt_group[1]))."')");
						$group_ids[] = $akt_group[0];

						$akt_group_members = ($akt_group[3] == '') ? array() : explode(',',$akt_group[3]);

						while(list(,$akt_member) = each($akt_group_members))
							if(user_exists($akt_member) == TRUE)
								$DB->query("INSERT INTO ".TBLPFX."groups_members (group_id,member_id) VALUES ('".$akt_group[0]."','$akt_member')");
					}

					//
					// Als erstes werden die Foren konvertiert
					//
					$forums_data = myfile($_SESSION['s_tbb1_path'].'/vars/foren.var');
					$DB->query("DELETE FROM ".TBLPFX."forums");
					$DB->query("DELETE FROM ".TBLPFX."forums_auth");

					$akt_order_id = 0;

					while(list(,$akt_forum) = each($forums_data)) {
						$akt_order_id++;

						$akt_forum = myexplode($akt_forum);

						$akt_forum_auth = explode(',',$akt_forum[10]);
						$akt_forum_mods = ($akt_forum[11] == '') ? array() : explode(',',$akt_forum[11]);
						$akt_forum_codes = explode(',',$akt_forum[7]);

						if($akt_forum[5] == -1) $akt_forum[5] = 0;
						else $akt_forum[5]++;

						$akt_forum_show_latest_posts = ($akt_forum_auth[0] == 1 || $akt_forum_auth[6] == 1) ? 1 : 0;

						$DB->query("INSERT INTO ".TBLPFX."forums (forum_id,cat_id,order_id,forum_name,forum_description,forum_topics_counter,forum_posts_counter,forum_enable_bbcode,forum_enable_htmlcode,forum_enable_smilies,forum_show_latest_posts,auth_members_view_forum,auth_members_post_topic,auth_members_post_reply,auth_members_post_poll,auth_members_edit_posts,auth_guests_view_forum,auth_guests_post_topic,auth_guests_post_reply,auth_guests_post_poll) VALUES ('".$akt_forum[0]."','".$akt_forum[5]."','".$akt_order_id."','".mysql_escape_string(tbb1_demutate($akt_forum[1]))."','".mysql_escape_string(tbb1_demutate($akt_forum[2]))."','".$akt_forum[3]."','".$akt_forum[4]."','".$akt_forum_codes[0]."','".$akt_forum_codes[1]."','1','$akt_forum_show_latest_posts','".$akt_forum_auth[0]."','".$akt_forum_auth[1]."','".$akt_forum_auth[2]."','".$akt_forum_auth[3]."','".$akt_forum_auth[4]."','".$akt_forum_auth[6]."','".$akt_forum_auth[7]."','".$akt_forum_auth[8]."','".$akt_forum_auth[9]."')");

						while(list(,$akt_mod) = each($akt_forum_mods))
							$DB->query("INSERT INTO ".TBLPFX."forums_auth (forum_id,auth_type,auth_id,auth_view_forum,auth_post_topic,auth_post_reply,auth_post_poll,auth_edit_posts,auth_is_mod) VALUES ('".$akt_forum[0]."','0','".$akt_mod."','1','1','1','1','1','1')");

						if($akt_forum_rights = myfile($_SESSION['s_tbb1_path'].'/foren/'.$akt_forum[0].'-rights.xbb')) {
							while(list(,$akt_right) = each($akt_forum_rights)) {
								$akt_right = myexplode($akt_right);
								$akt_right_type = ($akt_right[1] == 1) ? 0 : 1;
								if($akt_right_type == 0 && user_exists($akt_right[2]) == TRUE || $akt_right_type == 1 && in_array($akt_right[2],$group_ids) == TRUE)
									$DB->query("INSERT INTO ".TBLPFX."forums_auth (forum_id,auth_type,auth_id,auth_view_forum,auth_post_topic,auth_post_reply,auth_post_poll,auth_edit_posts,auth_is_mod) VALUES ('".$akt_forum[0]."','$akt_right_type','".$akt_right[2]."','".$akt_right[3]."','".$akt_right[4]."','".$akt_right[5]."','".$akt_right[6]."','".$akt_right[7]."','0')");
							}
						}

						$_SESSION['s_topics_counter'] += file_to_str($_SESSION['s_tbb1_path'].'/foren/'.$akt_forum[0].'-ltopic.xbb');
					}


					//
					// Die Kategorien
					//
					$cats_data = myfile($_SESSION['s_tbb1_path'].'/vars/kg.var');
					$DB->query("DELETE FROM ".TBLPFX."cats");
					$DB->query("INSERT INTO ".TBLPFX."cats (cat_id,cat_l,cat_r,cat_name) VALUES (1,1,2,'ROOT')");

					while(list(,$akt_cat) = each($cats_data)) {
						$akt_cat = myexplode($akt_cat);
						$akt_cat[0]++;

						$new_cat_id = cats_add_cat_data();
						$DB->query("UPDATE ".TBLPFX."cats SET cat_id='".$akt_cat[0]."', cat_name='".mysql_escape_string(tbb1_demutate($akt_cat[1]))."' WHERE cat_id='$new_cat_id'");
					}


					//
					// Die Raenge
					//
					$ranks_data = myfile($_SESSION['s_tbb1_path'].'/vars/rank.var');
					$DB->query("DELETE FROM ".TBLPFX."ranks");

					while(list(,$akt_rank) = each($ranks_data)) {
						$akt_rank = myexplode($akt_rank);

						$akt_rank_gfx = array();
						for($i = 0; $i < $akt_rank[4]; $i++)
							$akt_rank_gfx[] = 'images/rankpics/ystar.gif';
						$akt_rank_gfx = implode(';',$akt_rank_gfx);

						$DB->query("INSERT INTO ".TBLPFX."ranks (rank_id,rank_type,rank_name,rank_gfx,rank_posts) VALUES ('".$akt_rank[0]."','0','".mysql_escape_string(tbb1_demutate($akt_rank[1]))."','".$akt_rank_gfx."','".$akt_rank[2]."')");
					}


					//
					// Smilies/Themenbilder
					//
					$smilies_data = myfile($_SESSION['s_tbb1_path'].'/vars/smilies.var');
					$tpics_data = myfile($_SESSION['s_tbb1_path'].'/vars/tsmilies.var');
					$DB->query("DELETE FROM ".TBLPFX."smilies");

					while(list(,$akt_tpic) = each($tpics_data)) {
						$akt_tpic = myexplode($akt_tpic);

						$DB->query("INSERT INTO ".TBLPFX."smilies (smiley_id,smiley_type,smiley_gfx) VALUES ('".$akt_tpic[0]."','1','".$akt_tpic[1]."')");
					}

					while(list(,$akt_smiley) = each($smilies_data)) {
						$akt_smiley = myexplode($akt_smiley);

						$DB->query("INSERT INTO ".TBLPFX."smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('0','".$akt_smiley[2]."','".$akt_smiley[1]."','1')");
					}


					$DB->query("DELETE FROM ".TBLPFX."users");
					$DB->query("DELETE FROM ".TBLPFX."pms");
					$DB->query("DELETE FROM ".TBLPFX."profile_fields");
					$DB->query("DELETE FROM ".TBLPFX."profile_fields_data");

					$DB->query("INSERT INTO ".TBLPFX."profile_fields (field_name,field_type,field_regex_verification) VALUES ('".$LNG['ICQ']."','0','/^[0-9]{1,}\$/si')");
					$_SESSION['s_db_icq_id'] = $DB->insert_id;

					$DB->query("INSERT INTO ".TBLPFX."profile_fields (field_name,field_type) VALUES ('".$LNG['Homepage']."','0')");
					$_SESSION['s_db_hp_id'] = $DB->insert_id;

					$_SESSION['s_members_counter'] = file_to_str($_SESSION['s_tbb1_path'].'/vars/last_user_id.var');
					$_SESSION['s_status_pre'] = 100;

					install_print_conversion_status("update_tbb1.php?step=3&doit=1&substep=2&$MYSID"); exit;
				break;


				//
				// Der Ordner "members" (Userdaten und private Nachrichten)
				//
				case '2':
					$start = isset($_GET['start']) ? intval($_GET['start']) : 1;

					$end = file_to_str($_SESSION['s_tbb1_path'].'/vars/last_user_id.var') + 1;

					$j = 0;
					for($i = $start; $i < $end; $i++) {
						$_SESSION['s_members_complete_counter']++;

						if(file_exists($_SESSION['s_tbb1_path'].'/members/'.$i.'.xbb') == FALSE) continue;

						$akt_user_data = myfile($_SESSION['s_tbb1_path'].'/members/'.$i.'.xbb');

						if(preg_match('/^[0-9]{1,}$/si',$akt_user_data[0])) {
							do {
								$akt_user_data[0] = '_'.$akt_user_data[0];
								$DB->query("SELECT user_id FROM ".TBLPFX."users WHERE user_nick='".$akt_user_data[0]."'");
							} while($DB->affected_rows > 0);
						}

						$akt_user_data[6] = convertregdate2time($akt_user_data[6]);
						$akt_user_data[2] = md5(get_rand_string(8));
						$akt_user_is_admin = ($akt_user_data[4] == 1) ? 1 : 0;

						$DB->query("INSERT INTO ".TBLPFX."users (user_id,user_status,user_is_admin,user_nick,user_email,user_pw,user_posts,user_regtime,user_signature,user_tz) VALUES ('".$akt_user_data[1]."','1','".$akt_user_is_admin."','".mysql_escape_string(tbb1_demutate($akt_user_data[0]))."','".mysql_escape_string($akt_user_data[3])."','".$akt_user_data[2]."','".$akt_user_data[5]."','".$akt_user_data[6]."','".mysql_escape_string(tbb1_demutate(br2nl($akt_user_data[7])))."','gmt')");

						if($akt_user_data[13] != '') $DB->query("INSERT INTO ".TBLPFX."profile_fields_data (field_id,user_id,field_value) VALUES ('".$_SESSION['s_db_icq_id']."','".$akt_user_data[1]."','".$akt_user_data[13]."')"); // ICQ-Nummer
						if($akt_user_data[9] != '') $DB->query("INSERT INTO ".TBLPFX."profile_fields_data (field_id,user_id,field_value) VALUES ('".$_SESSION['s_db_hp_id']."','".$akt_user_data[1]."','".mysql_escape_string($akt_user_data[9])."')"); // Homepage

						if($akt_user_pms = myfile($_SESSION['s_tbb1_path'].'/members/'.$i.'.pm')) {
							while(list(,$akt_pm) = each($akt_user_pms)) {
								$akt_pm = myexplode($akt_pm);

								if(count($akt_pm) < 5) continue;

								$akt_pm_time = convertdate2time($akt_pm[4]);
								$akt_pm_from_id = 0;
								$akt_pm_guest_nick = '';

								if(user_exists($akt_pm[3]) == FALSE)
									$akt_pm_guest_nick = $LNG['Unknown_user'];
								else
									$akt_pm_from_id = $akt_pm[3];

								$akt_pm_read_status = ($akt_pm[7] == 1) ? 0 : 1;

								$DB->query("INSERT INTO ".TBLPFX."pms (pm_from_id,pm_to_id,pm_subject,pm_text,pm_read_status,pm_send_time,pm_enable_bbcode,pm_enable_smilies,pm_guest_nick) VALUES ('".$akt_pm_from_id."','".$akt_user_data[1]."','".mysql_escape_string(tbb1_demutate($akt_pm[1]))."','".mysql_escape_string(tbb1_demutate(br2nl($akt_pm[2])))."','$akt_pm_read_status','$akt_pm_time','".$akt_pm[5]."','".$akt_pm[6]."','$akt_pm_guest_nick')");
							}
						}


						if(++$j * 2 >= FILESPERROUND) {
							$_SESSION['s_status_members'] = round($_SESSION['s_members_complete_counter']/$_SESSION['s_members_counter'],2)*100;;
							install_print_conversion_status("update_tbb1.php?step=3&doit=1&substep=2&start=".($i+1)."&$MYSID"); exit;
						}
					}

					$_SESSION['s_status_members'] = 100;

					$DB->query("DELETE FROM ".TBLPFX."posts");
					$DB->query("DELETE FROM ".TBLPFX."topics");
					$DB->query("DELETE FROM ".TBLPFX."topics_subscriptions");
					$DB->query("DELETE FROM ".TBLPFX."polls");
					$DB->query("DELETE FROM ".TBLPFX."polls_options");

					$_SESSION['s_last_post_id'] = 1;
					$_SESSION['s_last_topic_id'] = 1;
					$_SESSION['s_last_option_id'] = 1;


					install_print_conversion_status("update_tbb1.php?step=3&doit=1&substep=3&$MYSID"); exit;
				break;


				//
				// Themen, Beitraege und Umfragen
				//
				case '3':
					$DB->query("SELECT forum_id FROM ".TBLPFX."forums ORDER BY forum_id ASC");
					$forum_ids = $DB->raw2fvarray();
					$forums_counter = count($forum_ids);

					$forum_id = isset($_GET['forum_id']) ? intval($_GET['forum_id']) : $forum_ids[0];

					for($i = 0; $i < $forums_counter; $i++) {
						if($forum_ids[$i] != $forum_id) continue;

						$akt_forum_id = &$forum_ids[$i];

						$start = isset($_GET['start']) ? intval($_GET['start']) : 1;
						$end = file_to_str($_SESSION['s_tbb1_path'].'/foren/'.$akt_forum_id.'-ltopic.xbb') + 1;

						$files_counter = 1;
						for($j = $start; $j < $end; $j++) {
							$_SESSION['s_topics_complete_counter']++;

							if(file_exists($_SESSION['s_tbb1_path'].'/foren/'.$akt_forum_id.'-'.$j.'.xbb') == FALSE) continue;

							//echo $akt_forum_id.'-'.$j.'.';

							$akt_topic_data = myfile($_SESSION['s_tbb1_path'].'/foren/'.$akt_forum_id.'-'.$j.'.xbb');

							$akt_topic_info = myexplode($akt_topic_data[0]);

							if(count($akt_topic_info) < 8) continue;

							$akt_topic_count = count($akt_topic_data);

							$akt_topic_first_post_id = $akt_topic_last_post_id = $akt_topic_pic = 0;
							$akt_topic_status = ($akt_topic_info[0] == 1) ? 0 : 1;
							$akt_topic_replies_counter = $akt_topic_count-2;
							$akt_topic_id = $_SESSION['s_last_topic_id']++;
							$akt_topic_title = mysql_escape_string(tbb1_demutate($akt_topic_info[1]));
							$akt_topic_poster_id = 0;

							$akt_topic_guest_nick = '';
							$akt_topic_post_time = 0;


							if(strncmp($akt_topic_info[2],'0',1) == 0)
								$akt_topic_guest_nick = substr($akt_topic_info[2],1,strlen($akt_topic_info[2]));
							elseif(file_exists($_SESSION['s_tbb1_path'].'/members/'.$akt_topic_info[2].'.xbb') == FALSE)
								$akt_topic_guest_nick = $LNG['Unknown_user'];
							else
								$akt_topic_poster_id = $akt_topic_info[2];

							$DB->query("INSERT INTO ".TBLPFX."topics (topic_id,forum_id,poster_id,topic_status,topic_replies_counter,topic_views_counter,topic_title,topic_guest_nick) VALUES ('$akt_topic_id','$akt_forum_id','$akt_topic_poster_id','$akt_topic_status','$akt_topic_replies_counter','".$akt_topic_info[6]."','$akt_topic_title','$akt_topic_guest_nick')");

							if($akt_topic_info[4] == 1 && $akt_topic_poster_id != 0)
								$DB->query("INSERT INTO ".TBLPFX."topics_subscriptions (topic_id,user_id) VALUES ('$akt_topic_id','$akt_topic_poster_id')");

							for($k = 1; $k < $akt_topic_count; $k++) {
								$akt_post_data = myexplode($akt_topic_data[$k]);

								if(count($akt_post_data) < 10) continue;

								if(count($akt_post_data) > 13) {
									$x = 4;

									do {
										$akt_post_data[3] .= $akt_post_data[$x];
										unset($akt_post_data[$x++]);
									} while(count($akt_post_data) > 13);

									$temp = array();

									while(list(,$akt_value) = each($akt_post_data))
										$temp[] = $akt_value;

									$akt_post_data = &$temp;
									unset($temp);
								}

								$akt_post_id = $_SESSION['s_last_post_id']++;
								$akt_post_time = convertdate2time($akt_post_data[2]);
								$akt_post_guest_nick = '';
								$akt_post_poster_id = 0;

								if(strncmp($akt_post_data[1],'0',1) == 0)
									$akt_post_guest_nick = substr($akt_post_data[1],1,strlen($akt_post_data[1]));
								elseif(file_exists($_SESSION['s_tbb1_path'].'/members/'.$akt_post_data[1].'.xbb') == FALSE)
									$akt_post_guest_nick = $LNG['Unknown_user'];
								else
									$akt_post_poster_id = $akt_post_data[1];

								if($k == 1) {
									$akt_topic_first_post_id = $akt_post_id;
									$akt_topic_pic = $akt_post_data[6];
									$akt_post_title = $akt_topic_title;
									$akt_topic_post_time = $akt_post_time;
								}
								else {
									$akt_post_title = 'Re: '.$akt_topic_title;
								}
								if($k == $akt_topic_replies_counter+1) $akt_topic_last_post_id = $akt_post_id;

								$DB->query("INSERT INTO ".TBLPFX."posts (post_id,topic_id,forum_id,poster_id,post_time,post_ip,post_pic,post_enable_bbcode,post_enable_smilies,post_enable_html,post_show_sig,post_guest_nick,post_title,post_text) VALUES ('$akt_post_id','$akt_topic_id','$akt_forum_id','$akt_post_poster_id','$akt_post_time','".$akt_post_data[4]."','".$akt_post_data[6]."','".$akt_post_data[8]."','".$akt_post_data[7]."','".$akt_post_data[9]."','1','$akt_post_guest_nick','$akt_post_title','".mysql_escape_string(br2nl(tbb1_demutate($akt_post_data[3])))."')");
							}

							$DB->query("UPDATE ".TBLPFX."topics SET topic_first_post_id='$akt_topic_first_post_id', topic_last_post_id='$akt_topic_last_post_id', topic_pic='$akt_topic_pic', topic_post_time='$akt_topic_post_time' WHERE topic_id='$akt_topic_id'");;


							if($akt_topic_info[7] != '' && file_exists($_SESSION['s_tbb1_path'].'/polls/'.$akt_topic_info[7].'-1.xbb') == TRUE) {
								$akt_poll_data = myfile($_SESSION['s_tbb1_path'].'/polls/'.$akt_topic_info[7].'-1.xbb');
								$akt_poll_count = count($akt_poll_data);
								$akt_poll_info = myexplode($akt_poll_data[0]);

								$akt_poll_guest_nick = '';
								$akt_poll_poster_id = 0;
								$akt_poll_start_time = convertdate2time($akt_poll_info[2]);
								$akt_poll_end_time = $akt_poll_start_time + 604800;

								if($akt_poll_info[1] == 0 || file_exists($_SESSION['s_tbb1_path'].'/members/'.$akt_poll_info[1].'.xbb') == FALSE)
									$akt_poll_guest_nick = $LNG['Unknown_user'];
								else
									$akt_poll_poster_id = $akt_poll_info[1];

								$DB->query("INSERT INTO ".TBLPFX."polls (topic_id,poster_id,poll_title,poll_votes,poll_guest_nick,poll_start_time,poll_end_time) VALUES ('$akt_topic_id','$akt_poll_poster_id','".mysql_escape_string(tbb1_demutate($akt_poll_info[3]))."','".$akt_poll_info[4]."','$akt_poll_guest_nick','$akt_poll_start_time','$akt_poll_end_time')");
								$new_poll_id = $DB->insert_id;
								$DB->query("UPDATE ".TBLPFX."topics SET topic_poll='1' WHERE topic_id='$akt_topic_id'");

								for($k = 1; $k < $akt_poll_count; $k++) {
									$akt_option_data = myexplode($akt_poll_data[$k]);
									$akt_option_id = $_SESSION['s_last_option_id']++;
									$DB->query("INSERT INTO ".TBLPFX."polls_options (option_id,poll_id,option_title,option_votes) VALUES ('$akt_option_id','".$new_poll_id."','".mysql_escape_string(tbb1_demutate($akt_option_data[1]))."','".$akt_option_data[2]."')");
								}

								$akt_poll_votes = file_to_str($_SESSION['s_tbb1_path'].'/polls/'.$akt_topic_info[7].'-2.xbb');
								if($akt_poll_votes != '') {
									$akt_poll_votes= explode(',',$akt_poll_votes);
									while(list(,$akt_poll_vote) = each($akt_poll_votes)) {
										if(file_exists($_SESSION['s_tbb1_path'].'/members/'.$akt_poll_vote.'.xbb') == TRUE)
											$DB->query("INSERT INTO ".TBLPFX."polls_votes (poll_id,voter_id) VALUES ('".$new_poll_id."','$akt_poll_vote')");
									}
								}

								$files_counter += 2;
							}


							if($files_counter++ >= FILESPERROUND) {
								$_SESSION['s_status_topics'] = round($_SESSION['s_topics_complete_counter']/$_SESSION['s_topics_counter'],2)*100;
								install_print_conversion_status("update_tbb1.php?step=3&doit=1&substep=3&forum_id=$akt_forum_id&start=".($j+1)."&$MYSID"); exit;
							}
						}

						if($i != $forums_counter - 1) {
							$_SESSION['s_status_topics'] = round($_SESSION['s_topics_complete_counter']/$_SESSION['s_topics_counter'],2)*100;
							install_print_conversion_status("update_tbb1.php?step=3&doit=1&substep=3&forum_id=".$forum_ids[$i+1]."&$MYSID"); exit;
						}
					}

					$_SESSION['s_status_topics'] = 100;

					install_print_conversion_status("update_tbb1.php?step=3&doit=1&substep=4&$MYSID"); exit;
				break;

				//
				// Letzte Beitraege der Foren updaten und andere Dinge
				//
				case '4':
					$DB->query("SELECT forum_id FROM ".TBLPFX."forums ORDER BY forum_id ASC");
					$forum_ids = $DB->raw2fvarray();

					while(list(,$akt_forum_id) = each($forum_ids))
						update_forum_last_post($akt_forum_id);

					cache_set_all_data();
					cache_set_newest_user_data();

					$DB->query("DELETE FROM ".TBLPFX."config WHERE config_name='dataversion'");
					$DB->query("INSERT INTO ".TBLPFX."config (config_name,config_value) VALUES ('dataversion','".SCRIPTVERSION."')");

					$_SESSION['s_status_suf'] = 100;

					install_print_conversion_status("update_tbb1.php?step=4&$MYSID"); exit;
				break;
			}
		}

		install_print_pheader();

		?>
			 <form method="post" action="update_tbb1.php?step=3&amp;doit=1&amp;substep=1&amp;<?php echo $MYSID; ?>">
			 <table border="0" cellpadding="2" cellspacing="0" width="100%" style="border:1px black dashed;">
			 <tr><th colspan="2" class="th1"><span class="th1"><?php echo $STEPS[$STEP]; ?></span></th></tr>
			 <tr><td><span class="fontnorm">Unter dem Pfad &quot;<?php echo $_SESSION['s_tbb1_path']; ?>&quot; wurde eine passende Installation des Tritanium Bulletin Board 1 gefunden. Um den Update- und Konvertierungsvorgang zu starten, klicken Sie bitte auf &quot;weiter&quot;. Um den Pfad zu korrigieren, klicken Sie bitte auf &quot;zur&uuml;ck&quot.<br /><br />Bitte beachten Sie, dass der Updatevorgang je nach Gr&ouml;&szlig;e des Forums einige Zeit in Anspruch nehmen kann.</span></td></tr>
			 </table>
			 <br />
			 <table border="0" cellpadding="2" cellspacing="0" width="100%" style="border:1px black dashed;">
			 <tr><td align="right"><input class="form_button" type="submit" name="p_button_back" value="<?php echo $LNG['Back']; ?>" />&nbsp;&nbsp;&nbsp;<input class="form_bold_button" type="submit" value="<?php echo $LNG['Next']; ?>" /></td></tr>
			 </table>
			 </form>
		<?php

		install_print_ptail();
	break;

	case '4':
		install_print_pheader();

		?>
			 <table border="0" cellpadding="2" cellspacing="0" width="100%" style="border:1px black dashed;">
			 <tr><th colspan="2" class="th1"><span class="th1"><?php echo $STEPS[$STEP]; ?></span></th></tr>
			 <tr><td><span class="fontnorm">Herzlichen Glückwunsch! Ihre alten Daten wurden f&uuml;r das Tritanium Bulletin Board 2 konvertiert und geupdatet. Bitte beachten Sie die Hinweise in der Dokumentation, denken Sie insbesondere daran, dass alle User ein neues Passwort anfordern werden m&uuml;ssen.<br />Sie k&ouml;nnen das Forum &uuml;ber <a href="index.php">index.php</a> erreichen.</span></td></tr>
			 </table>
		<?php

		install_print_ptail();

		session_destroy(); exit;
	break;
}


function install_print_pheader($referlink = '') {

global $STEP,$STEPS,$LNG;

if($referlink != '') $referlink = '<meta http-equiv="refresh" content="0; URL='.$referlink.'" />';
//$referlink = '';


?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
 <title>Updatescript TBB 1->TBB 2</title>
 <?php echo $referlink; ?>
 <style type="text/css">
 <!--
  body {
   background-color:#F0F8FF;
  }

  table.tbl {
   background-color:#000000;
  }

  td.tdwhite {
   background-color:#FFFFFF;
  }

  th.thwhite {
   background-color:#FFFFFF;
  }

  .fontnorm {
   font-family:verdana,arial;
   color:#000000;
   font-size:10pt;
  }

  .fontred {
   font-family:verdana,arial;
   color:#FF0000;
   font-size:10pt;
   font-weight:bold;
  }

  .fontgreen {
   font-family:verdana,arial;
   color:#008000;
   font-size:10pt;
   font-weight:bold;
  }

  .fontorange {
   font-family:verdana,arial;
   color:#FFA500;
   font-size:10pt;
   font-weight:bold;
  }

  .fontgray {
   font-family:verdana,arial;
   color:#808080;
   font-size:10pt;
  }

  .fontsmall {
   font-family:verdana,arial;
   color:#000000;
   font-size:8pt;
  }

  input.formbutton {
   border:1px #000000 solid;
   font-size:10px;
   font-family:verdana,arial;
  }

  a:link {
   color:#0000CD;
  }

  a:visited {
   color:#0000CD;
  }

  a:hover {
   color:red;
  }

  input {
   border:1px black solid;
  }

  th.th0 {
   background-color:#000080;
   padding:5px;
  }
  .th0 {
   color:#FFFFFF;
   font-size:14pt;
   font-family:verdana,arial;
  }

  th.th1 {
   background-color:#4682B4;
  }
  .th1 {
   color:white;
   font-size:10pt;
   font-family:verdana;
   font-weight:bold;
  }

  td.error {
   background-color:#FFD1D1;
   border:1px #FF0000 solid;
  }
  .error {
   font-family:verdana;
   font-size:10pt;
   color:#FF0000;
  }

  input.form_bold_button {
   font-family:verdana,arial;
   font-size:8pt;
   font-weight:bold;
  }

  input.form_button {
   font-family:verdana,arial;
   font-size:8pt;
  }

  table.standard_table {
  	border:1px #000000 dashed;
  }
 -->
 </style>
</head>
<body>
<table style="background-color:#000000;" border="0" cellpadding="0" cellspacing="1" width="100%">
<tr><th class="th0"><span class="th0">Updatescript TBB 1->TBB 2</span></tr></th>
<tr><td style="background-color:white;">
<table border="0" cellpadding="3" cellspacing="5" width="100%">
<tr>
 <td width="20%" valign="top">
  <table border="0" cellpadding="2" cellspacing="0" width="100%" style="border:1px black dashed;">
  <tr><th class="th1"><span class="th1">&Uuml;bersicht</span></th></tr>
<?php


	while(list($akt_key,$akt_value) = each($STEPS)) {
		if($STEP == $akt_key)
			echo '	<tr><td><span class="fontnorm"><b>&#187; '.$akt_value.'</b></span></td></tr>';
		else
			echo '	<tr><td><span class="fontgray">'.$akt_value.'</span></td></tr>';
	}

	reset($STEPS);

?>
  </table>
 </td>
 <td width="80%" valign="top">
<?php
}

function install_print_ptail() {
?> </td>
</tr>
</table>
</td></tr>
</table>
<br /><br /><div align="center"><table class="tbl" cellspacing="1" cellpadding="3"><tr><td class="tdwhite"><span class="fontsmall">Tritanium Bulletin Board 2 Updatescript<br />&copy; 2003-2005 <a href="http://www.tritanium-scripts.com" target="_blank">Tritanium Scripts</a></span></td></tr></table></div>
</body>
</html><?php
}

function install_print_conversion_status($referlink = '') {
	global $STEP,$STEPS,$MYSID;

	install_print_pheader($referlink);

	?>
		 <table border="0" cellpadding="2" cellspacing="0" width="100%" style="border:1px black dashed;">
		 <tr><th colspan="2" class="th1"><span class="th1"><?php echo $STEPS[$STEP]; ?></span></th></tr>
		 <tr><td><span class="fontnorm">Der Konvertierungs- und Updatevorgang l&auml;ft. Bitte tun Sie in diesem Browserfenster nichts.<br /><br />Der momentane Status ist:</span><br /><br />
		 <table border="0" cellpadding="0" cellspacing="0">
		 <tr>
		  <td><span class="fontnorm">Allgemeine Daten:</span></td>
		  <td><img src="images/dot.gif" height="12" width="<?php echo $_SESSION['s_status_pre']*2; ?>" /> <span class="fontnorm"><?php echo $_SESSION['s_status_pre']; ?>%</span></td>
		 </tr>
		 <tr>
		  <td><span class="fontnorm">Mitgliederdaten:</span></td>
		  <td><img src="images/dot.gif" height="12" width="<?php echo $_SESSION['s_status_members']*2; ?>" /> <span class="fontnorm"><?php echo $_SESSION['s_status_members']; ?>%</span></td>
		 </tr>
		 <tr>
		  <td><span class="fontnorm">Themendaten:</span></td>
		  <td><img src="images/dot.gif" height="12" width="<?php echo $_SESSION['s_status_topics']*2; ?>" /> <span class="fontnorm"><?php echo $_SESSION['s_status_topics']; ?>%</span></td>
		 </tr>
		 <tr>
		  <td><span class="fontnorm">Sonstige Daten:</span></td>
		  <td><img src="images/dot.gif" height="12" width="<?php echo $_SESSION['s_status_suf']*2; ?>" /> <span class="fontnorm"><?php echo $_SESSION['s_status_suf']; ?>%</span></td>
		 </tr>
		 </table>
		 </td></tr>
		 </table>
	<?php

	install_print_ptail();
}

?>