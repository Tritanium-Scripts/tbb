<?php

class ViewProfile extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'Cache',
		'Config',
		'DB',
		'Language',
		'Navbar',
		'PageParts',
		'Template'
	);

	public function executeMe() {
		$profileID = isset($_GET['profileID']) ? intval($_GET['profileID']) : 0;

		if(!$profileData = Functions::getUserData($profileID)) die('Cannot load data: Profile');

		$this->modules['Navbar']->addElements('left',array($this->modules['Language']->getString('View_profile'),INDEXFILE."?action=ViewProfile&amp;profileID=$profileID&amp".MYSID));

		switch(@$_GET['mode']) {
			default:
				$ranksData = $this->modules['Cache']->getRanksData();

				$profileRankText = $profileRankPic = '';

				//
				// Rangbild und Rangtext des Users festlegen
				//
				if($profileData['rankID'] != 0) {
					$profileRankText = $ranksData[1][$profileData['rankID']]['rankName'];
					$profileRankPic = $ranksData[1][$profileData['rankID']]['rankGfx'];
				}
				elseif($profileData['userIsAdmin'] == 1) {
					$profileRankText = $this->modules['Language']->getString('Administrator');
					$profileRankPic = '<img src="'.$CONFIG['admin_rank_pic'].'" alt="" border="0"/>';
				}
				elseif($profileData['userIsSupermod'] == 1) {
					$profileRankText = $this->modules['Language']->getString('Supermoderator');
					$profileRankPic = '<img src="'.$CONFIG['supermod_rank_pic'].'" alt="" border="0"/>';
				}
				else {
					foreach($ranksData[0] AS $curRank) {
						if($curRank['rankPosts'] > $profileData['userPostsCounter']) break;

						$profileRankText = $curRank['rankName'];
						$profileRankPic = $curRank['rankGfx'];
					}
					//reset($ranksData[0]);
				}

				$profileRegisterDate = Functions::toDateTime($profileData['userRegistrationTimestamp']);

				$userIsMod = Functions::checkModStatus(USERID);
				if($this->modules['Auth']->getValue('userAuthProfileNotes') == 1 || $this->modules['Auth']->getValue('userIsAdmin') == 1 || $this->modules['Auth']->getValue('userIsSupermod') == 1 || $userIsMod) {
					if($this->modules['Auth']->getValue('userIsAdmin') == 1 || $this->modules['Auth']->getValue('userIsSupermod') == 1 || $userIsMod) {
						$this->modules['DB']->query("
							SELECT
								t1.*,
								t2.userNick
							FROM
								".TBLPFX."profile_notes AS t1
							LEFT JOIN ".TBLPFX."users AS t2 ON t1.userID=t2.userID
							WHERE
								t1.profileID='$profileID'
								AND (t1.userID='".USERID."' OR t1.noteIsPublic='1')
							ORDER BY t1.noteTimestamp DESC
						");
					}
					else {
						$DB->query("SELECT * FROM ".TBLPFX."profile_notes WHERE profile_id='$profile_id' AND user_id='$USER_ID' ORDER BY note_time DESC");
					}

					$notesData = array();
					while($curNote = $this->modules['DB']->fetchArray()) {
						$curNote['_noteData'] = Functions::toDateTime($curNote['noteTimestamp']);
						$curNote['_noteText'] = nl2br(Functions::HTMLSpecialChars($curNote['noteText']));
						//$tpl->Blocks['notestable']->Blocks['noterow']->parseCode(FALSE,TRUE);
					}

					//$tpl->Blocks['notestable']->parseCode();
				}

				$this->modules['Template']->assign(array(
					'profileData'=>$profileData
				));

				$this->modules['PageParts']->printPage('ViewProfileView.tpl');
				//include_once('pheader.php');
				//$tpl->parseCode(TRUE);
				//include_once('ptail.php');
				break;

			case 'addnote':
				$userIsMod = Functions::checkModStatus(USERID);
				if($this->modules['Auth']->getValue('userAuthProfileNotes') != 1 && $this->modules['Auth']->getValue('userIsAdmin') != 1 && $this->modules['Auth']->getValue('userIsSupermod') != 1 && !$userIsMod) die('Kein Zugriff');

				$p_note_text = isset($_POST['p_note_text']) ? $_POST['p_note_text'] : '';
				$p_note_is_public = 0;

				if(isset($_GET['doit'])) {
					$p_note_is_public = isset($_POST['p_note_is_public']) ? 1 : 0;

					// Oeffentlich darf man nur als Admin oder Mod posten...
					if($this->modules['Auth']->getValue('userIsAdmin') != 1 && $this->modules['Auth']->getValue('userIsSupermod') != 1 && !$userIsMod) $p_note_is_public = 0;

					$DB->query("INSERT INTO ".TBLPFX."profile_notes (user_id,profile_id,note_time,note_is_public,note_text) VALUES ('$USER_ID','$profile_id','".time()."','$p_note_is_public','$p_note_text')");

					header("Location: index.php?action=viewprofile&profile_id=$profile_id&{$MYSID}"); exit;
				}

				$tpl = new Template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['viewprofile_addnote']);

				$NAVBAR->addElements('left',array($LNG['Add_note'],"index.php?action=viewprofile&amp;profile_id=$profile_id&amp;{$MYSID}"));

				include_once('pheader.php');
				$tpl->parseCode(TRUE);
				include_once('ptail.php');
			break;

			case 'editnote':
				$note_id = isset($_GET['note_id']) ? intval($_GET['note_id']) : 0;
				if(!$note_data = get_profile_note_data($note_id)) die('Kann Daten nicht laden: Profilnotiz');
				if($this->modules['Auth']->getValue('userIsAdmin') != 1 && $note_data['user_id'] != $USER_ID) die('Kein Zugriff');
				$userIsMod = Functions::checkModStatus(USERID);

				$p_note_text = isset($_POST['p_note_text']) ? $_POST['p_note_text'] : addslashes($note_data['note_text']);
				$p_note_is_public = $note_data['note_is_public'];

				if(isset($_GET['doit'])) {
					$p_note_is_public = isset($_POST['p_note_is_public']) ? 1 : 0;

					// Oeffentlich darf man nur als Admin oder Mod posten...
					if($this->modules['Auth']->getValue('userIsAdmin') != 1 && $this->modules['Auth']->getValue('userIsSupermod') != 1 && !$userIsMod) $p_note_is_public = 0;

					$DB->query("UPDATE ".TBLPFX."profile_notes SET note_is_public='$p_note_is_public', note_text='$p_note_text' WHERE note_id='$note_id'");

				}

				$tpl = new Template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['viewprofile_editnote']);

				$NAVBAR->addElements('left',array($LNG['Edit_note'],"index.php?action=viewprofile&amp;profile_id=$profile_id&amp;{$MYSID}"));

				include('pheader.php');
				$tpl->parseCode(TRUE);
				include('ptail.php');
			break;

			case 'deletenote':
				$note_id = isset($_GET['note_id']) ? intval($_GET['note_id']) : 0;
				if(!$note_data = get_profile_note_data($note_id)) die('Kann Daten nicht laden: Profilnotiz');
				if($this->modules['Auth']->getValue('userIsAdmin') != 1 && $note_data['user_id'] != $USER_ID) die('Kein Zugriff');

				$DB->query("DELETE FROM ".TBLPFX."profile_notes WHERE note_id='$note_id'");

				header("Location: index.php?action=viewprofile&profile_id=$profile_id&{$MYSID}"); exit;
			break;

			case 'sendmail':
				if($USER_LOGGED_IN == 0 || $CONFIG['enable_email_formular'] == 0) die('Das geht wohl so nicht...!');

				add_navbar_items(array($LNG['Send_email'],"index.php?action=viewprofile&amp;profile_id=$profile_id&ampmode=sendmail&amp;$MYSID"));

				$p_mail_subject = isset($_POST['p_mail_subject']) ? $_POST['p_mail_subject'] : '';
				$p_mail_message = isset($_POST['p_mail_message']) ? $_POST['p_mail_message'] : '';

				$error = '';

				if(isset($_GET['doit'])) {
					$p_mail_message = mysslashes($p_mail_message);
					$p_mail_subject = mysslashes($p_mail_subject);

					if(trim($p_mail_subject) == '') $error = $LNG['error_no_subject'];
					elseif(trim($p_mail_message) == '') $error = $LNG['error_no_message'];
					else {
						mymail($this->modules['Auth']->getValue('user_nick').' <'.$this->modules['Auth']->getValue('user_email').'>',$profileData['user_nick'].' <'.$profileData['user_email'].'>',$p_mail_subject,$p_mail_message);
						add_navbar_items(array($LNG['Email_sent'],''));

						include_once('pheader.php');
						show_message($LNG['Email_sent'],$LNG['message_email_sent'].'<br />'.sprintf($LNG['click_here_back_profile'],"<a href=\"index.php?action=viewprofile&amp;profile_id=$profile_id&amp;$MYSID\">",'</a>'));
						include_once('ptail.php'); exit;
					}
				}

				$p_mail_message = myhtmlentities($p_mail_message);
				$p_mail_subject = myhtmlentities($p_mail_subject);

				$tpl = new Template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['viewprofile_sendmail']);

				include_once('pheader.php');
				$tpl->parseCode(TRUE);
				include_once('ptail.php');
			break;
		}
	}
}

?>