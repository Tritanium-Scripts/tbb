<?php

class ViewProfile extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'Cache',
		'Config',
		'DB',
		'Language',
		'Navbar',
		'Template'
	);

	public function executeMe() {
		$this->modules['Language']->addFile('ViewProfile');

		$profileID = isset($_GET['profileID']) ? intval($_GET['profileID']) : 0;

		if(!$profileData = FuncUsers::getUserData($profileID)) die('Cannot load data: Profile');

		$userIsMod = Functions::checkModStatus(USERID);

		$this->modules['Navbar']->addElements(array($this->modules['Language']->getString('View_profile'),INDEXFILE."?action=ViewProfile&amp;profileID=$profileID&amp".MYSID));
		$this->modules['Template']->assign(array(
			'userIsMod'=>$userIsMod,
			'profileID'=>$profileID
		));

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
					$profileRankPic = '<img src="'.$this->modules['Config']->getValue('admin_rank_pic').'" alt="" border="0"/>';
				}
				elseif($profileData['userIsSupermod'] == 1) {
					$profileRankText = $this->modules['Language']->getString('Supermoderator');
					$profileRankPic = '<img src="'.$this->modules['Config']->getValue('supermod_rank_pic').'" alt="" border="0"/>';
				}
				else {
					foreach($ranksData[0] AS $curRank) {
						if($curRank['rankPosts'] > $profileData['userPostsCounter']) break;

						$profileRankText = $curRank['rankName'];
						$profileRankPic = $curRank['rankGfx'];
					}
					//reset($ranksData[0]);
				}

				$profileData['_profileRankText'] = $profileRankText;
				$profileData['_profileRankPic'] = $profileRankPic;
				$profileData['_profileRegisterDate'] = Functions::toDateTime($profileData['userRegistrationTimestamp']);

				$show = array('notesTable'=>FALSE);

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
						$this->modules['DB']->query("
							SELECT
								*
							FROM
								".TBLPFX."profile_notes
							WHERE
								profileID='$profileID' AND userID='".USERID."'
							ORDER BY
								noteTimestamp DESC
						");
					}

					$notesData = array();
					while($curNote = $this->modules['DB']->fetchArray()) {
						$curNote['_noteDate'] = Functions::toDateTime($curNote['noteTimestamp']);
						$curNote['_noteText'] = nl2br(Functions::HTMLSpecialChars($curNote['noteText']));
						$notesData[] = $curNote;
					}

					$this->modules['Template']->assign('notesData',$notesData);

					$show['notesTable'] = TRUE;
				}

				$this->modules['Template']->assign(array(
					'profileData'=>$profileData,
					'show'=>$show
				));

				$this->modules['Template']->printPage('ViewProfileView.tpl');
				break;

			case 'AddNote':
				if($this->modules['Auth']->getValue('userAuthProfileNotes') != 1 && $this->modules['Auth']->getValue('userIsAdmin') != 1 && $this->modules['Auth']->getValue('userIsSupermod') != 1 && !$userIsMod) die('Access denied: add profile note');

				$p = Functions::getSGValues($_POST['p'],array('noteText'),'');
				$c = Functions::getSGValues($_POST['c'],array('noteIsPublic'),0);

				if(isset($_GET['doit'])) {
					// Oeffentlich darf man nur als Admin oder Mod posten...
					if($this->modules['Auth']->getValue('userIsAdmin') != 1 && $this->modules['Auth']->getValue('userIsSupermod') != 1 && !$userIsMod)
						$c['noteIsPublic'] = 0;

					$this->modules['DB']->query("
						INSERT INTO
							".TBLPFX."profile_notes
						SET
							userID='".USERID."',
							profileID='".$profileID."',
							noteTimestamp='".time()."',
							noteIsPublic='".$c['noteIsPublic']."',
							noteText='".$p['noteText']."'
						");

					Functions::myHeader(INDEXFILE."?action=ViewProfile&profileID=$profileID&".MYSID);
				}

				$this->modules['Template']->assign(array(
					'p'=>$p,
					'c'=>$c
				));

				$this->modules['Navbar']->addElements(array($this->modules['Language']->getString('Add_note'),INDEXFILE."?action=ViewProfile&amp;profileID=$profileID&amp;".MYSID));

				$this->modules['Template']->printPage('ViewProfileAddNote.tpl');
				break;

			case 'EditNote':
				$noteID = isset($_GET['noteID']) ? intval($_GET['noteID']) : 0;
				if(!$noteData = Functions::getProfileNoteData($noteID)) die('Cannot load data: profile note');
				if($this->modules['Auth']->getValue('userIsAdmin') != 1 && $noteData['userID'] != USERID) die('Access denied: edit profile note');

				$p = Functions::getSGValues($_POST['p'],array('noteText'),'',Functions::addSlashes($noteData));
				$c = Functions::getSGValues($_POST['c'],array('noteIsPublic'),'',Functions::addSlashes($noteData));

				if(isset($_GET['doit'])) {
					// Oeffentlich darf man nur als Admin oder Mod posten...
					if($this->modules['Auth']->getValue('userIsAdmin') != 1 && $this->modules['Auth']->getValue('userIsSupermod') != 1 && !$userIsMod)
						$c['noteIsPublic'] = 0;

					$this->modules['DB']->query("
						UPDATE
							".TBLPFX."profile_notes
						SET
							noteIsPublic='".$c['noteIsPublic']."',
							noteText='".$p['noteText']."'
						WHERE
							noteID='$noteID'
					");

					Functions::myHeader(INDEXFILE."?action=ViewProfile&profileID=$profileID&".MYSID);
				}

				$this->modules['Template']->assign(array(
					'p'=>$p,
					'c'=>$c,
					'noteID'=>$noteID
				));

				$this->modules['Navbar']->addElements(array($this->modules['Language']->getString('Edit_note'),INDEXFILE."?action=ViewProfile&amp;profileID=$profileID&amp;".MYSID));

				$this->modules['Template']->printPage('ViewProfileEditNote.tpl');
				break;

			case 'DeleteNote':
				$noteID = isset($_GET['noteID']) ? intval($_GET['noteID']) : 0;
				if(!$noteData = Functions::getProfileNoteData($noteID)) die('Cannot load data: profile note');
				if($this->modules['Auth']->getValue('userIsAdmin') != 1 && $noteData['userID'] != USERID) die('Access denied: delete profile note');

				$this->modules['DB']->query("
					DELETE FROM
						".TBLPFX."profile_notes
					WHERE
						noteID='$noteID'
				");

				Functions::myHeader(INDEXFILE."?action=ViewProfile&profileID=$profileID&".MYSID);
				break;

			case 'SendEmail':
				if($this->modules['Auth']->isLoggedIn() == 0 || $this->modules['Config']->getValue('enable_email_formular') == 0) die('Access denied: profile: send email');

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('Send_email'),INDEXFILE."?action=ViewProfile&amp;profileID=$profileID&amp;mode=SendEmail&amp;".MYSID);

				$p = Functions::getSGValues($_POST['p'],array('emailSubject','emailMessage'),'');

				$error = '';

				if(isset($_GET['doit'])) {
					$p = Functions::stripSlashes($p);

					if(trim($p['emailSubject']) == '') $error = $this->modules['Language']->getString('error_no_subject');
					elseif(trim($p['emailMessage']) == '') $error = $this->modules['Language']->getString('error_no_message');
					else {
						Functions::myMail($this->modules['Auth']->getValue('userNick').' <'.$this->modules['Auth']->getValue('userEmailAddress').'>',$profileData['userNick'].' <'.$profileData['userEmailAddress'].'>',$p['emailSubject'],$p['emailMessage']);

						// TODO: correct message
						die('Email sent');

						//add_navbar_items(array($LNG['Email_sent'],''));

						//include_once('pheader.php');
						//show_message($LNG['Email_sent'],$LNG['message_email_sent'].'<br />'.sprintf($LNG['click_here_back_profile'],"<a href=\"index.php?action=viewprofile&amp;profile_id=$profile_id&amp;$MYSID\">",'</a>'));
						//include_once('ptail.php'); exit;
					}
				}

				$this->modules['Template']->assign(array(
					'p'=>Functions::HTMLSpecialChars($p),
					'error'=>$error,
					'profileData'=>$profileData
				));

				$this->modules['Template']->printPage('ViewProfileSendEmail.tpl');
				break;
		}
	}
}

?>