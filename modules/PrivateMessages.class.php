<?php

class PrivateMessages extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'Cache',
		'Config',
		'Constants',
		'DB',
		'Language',
		'Navbar',
		'PageParts',
		'Template'
	);

	function executeMe() {
		if($this->modules['Auth']->isLoggedIn() != 1) die('Kein Zugriff: Nicht eingeloggt');
		elseif($this->modules['Config']->getValue('enable_pms') != 1) {
			// TODO: Richtige Meldung
			//add_navbar_items(array($this->modules['Language']->getString('Function_deactivated'),''));

			//include_once('pheader.php');
			//show_message($lNG['Function_deactivated'],$lNG['message_function_deactivated']);
			//include_once('ptail.php'); exit;
			die('Funktion nicht verfuegbar');
		}

		$this->modules['PageParts']->setInPrivateMessages(TRUE);
		$this->modules['Language']->addFile('PrivateMessages');
		$this->modules['Navbar']->addElement($this->modules['Language']->getString('Private_messages'),INDEXFILE.'?Action=PrivateMessages&amp;'.MYSID);

		$inboxFolderData = array('folderID'=>0,'folderName'=>$this->modules['Language']->getString('Inbox'));
		$outboxFolderData = array('folderID'=>1,'folderName'=>$this->modules['Language']->getString('Outbox'));

		switch(@$_GET['mode']) {
			default:
				$folderID = isset($_GET['folderID']) ? intval($_GET['folderID']) : 0;
				$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

				if($folderID == 0) $folderData = $inboxFolderData;
				elseif($folderID == 1) $folderData = $outboxFolderData;
				else {
					$this->modules['DB']->query("SELECT folderID, folderName FROM ".TBLPFX."pms_folders WHERE userID='".USERID."' AND folderID='$folderID'");
					if($this->modules['DB']->getAffectedRows() == 0) {
						$folderData = $inboxFolderData;
						$folderID = 0;
					}
					else $folderData = $this->modules['DB']->fetchArray();
				}

				$this->modules['DB']->query("SELECT COUNT(*) FROM ".TBLPFX."pms WHERE PMToID='".USERID."' AND FolderID='$folderID'");
				list($pmsCounter) = $this->modules['DB']->fetchArray();

				$pmsPerPage = 20;
				//$this->modules['Config']->getValue('pms_per_page') = 10;

				$pageListing = Functions::createPageListing($pmsCounter,$pmsPerPage,$page,"<a href=\"".INDEXFILE."?action=PrivateMessages&amp;mode=ViewFolder&amp;folderID=$folderID&amp;page=%1\$s&amp;".MYSID."\">%2\$s</a>");
				$start = $page*$this->modules['Config']->getValue('pms_per_page')-$this->modules['Config']->getValue('pms_per_page');


				// PM-Daten laden
				$this->modules['DB']->query("
					SELECT
						t1.pmID,
						t1.pmSubject,
						t1.pmSendTimestamp,
						t1.pmFromID,
						t1.pmType,
						t1.pmIsRead,
						t1.pmIsReplied,
						t1.pmGuestNick,
						t2.userNick AS pmFromNick
					FROM
						".TBLPFX."pms AS t1
					LEFT JOIN ".TBLPFX."users AS t2 ON t1.pmFromID=t2.userID
					WHERE
						pmToID='".USERID."'
						AND folderID='$folderID'
					ORDER BY
						pmSendTimestamp DESC
					LIMIT $start,$pmsPerPage
				");

				$pmsData = array();
				while($curPM = $this->modules['DB']->fetchArray()) {
					$curSenderNick = ($curPM['pmFromID'] == 0) ? $curPM['pmGuestNick'] : $curPM['pmFromNick'];
					$curPM['_pmSender'] = ($curPM['pmType'] == 0) ? sprintf($this->modules['Language']->getString('from_x'),$curSenderNick) : sprintf($this->modules['Language']->getString('to_x'),$curSenderNick);
					$curPM['_pmSendDateTime'] = Functions::toDateTime($curPM['pmSendTimestamp']);
					$pmsData[] = $curPM;
				}

				$this->modules['Navbar']->addElement(Functions::HTMLSpecialChars($folderData['folderName']),INDEXFILE.'?action=PrivateMessages&amp;folderID='.$folderID.'&amp;'.MYSID);

				$this->modules['Template']->assign(array(
					'pageListing'=>$pageListing,
					'pmsData'=>$pmsData,
					'folderID'=>$folderID,
					'page'=>$page
				));

				$this->modules['PageParts']->printPage('PrivateMessagesViewFolder.tpl');
			break;

			case 'NewPM':
				$this->modules['Navbar']->addElement($this->modules['Language']->getString('New_private_message'),INDEXFILE.'?Action=PrivateMessages&amp;Mode=NewPM&amp;'.MYSID);

				$p = Functions::getSGValues($_POST['p'],array('Recipients','PMSubject','PMMessageText'),'');

				$c = array();
				$c['EnableSmilies'] = $c['EnableBBCode'] = $c['ShowSignature'] = $c['SaveOutbox'] = 1;
				$c['EnableHtmlCode'] = $c['RequestReadReceipt'] = 0;

				$error = '';

				if(isset($_GET['Doit'])) {
					$c['EnableSmilies'] = (isset($_POST['c']['EnableSmilies']) && $this->modules['Config']->getValue('allow_pms_smilies') == 1) ? 1 : 0;
					$c['ShowSignature'] = (isset($_POST['c']['ShowSignature']) && $this->modules['Config']->getValue('enable_sig') == 1) ? 1 : 0;
					$c['EnableBBCode'] = (isset($_POST['c']['EnableBBCode']) && $this->modules['Config']->getValue('allow_pms_bbcode') == 1) ? 1 : 0;
					$c['SaveOutbox'] = (isset($_POST['c']['SaveOutbox']) && $this->modules['Config']->getValue('enable_outbox') == 1) ? 1 : 0;
					$c['RequestReadReceipt'] = (isset($_POST['c']['RequestReadReceipt']) && $this->modules['Config']->getValue('allow_pms_rconfirmation') == 1) ? 1 : 0;

					$recipients = explode(',',$p['Recipients']);
					while(list($curKey) = each($recipients)) {
						$recipients[$curKey] = trim($recipients[$curKey]);
						if(!$recipients[$curKey] = Functions::getUserID($recipients[$curKey])) unset($recipients[$curKey]);
					}
					reset($recipients);

					if(count($recipients) == 0) $error = $this->modules['Language']->getString('error_no_recipient');
					elseif(trim($p['PMSubject']) == '') $error = $this->modules['Language']->getString('error_no_subject');
					elseif(trim($p['PMMessageText']) == '') $error = $this->modules['Language']->getString('error_no_message');
					else {
						foreach($recipients AS $curRecipient) {
							$this->modules['DB']->query("INSERT INTO ".TBLPFX."pms SET
								FolderID='0',
								PMFromID='".USERID."',
								PMToID='".$curRecipient."',
								PMIsRead='0',
								PMType='0',
								PMSubject='".$p['PMSubject']."',
								PMMessageText='".$p['PMMessageText']."',
								PMSendTimestamp='".time()."',
								PMEnableBBCode='".$c['EnableBBCode']."',
								PMEnableSmilies='".$c['EnableSmilies']."',
								PMEnableHtmlCode='".$c['EnableHtmlCode']."',
								PMShowSignature='".$c['ShowSignature']."',
								PMRequestReadReceipt='".$c['RequestReadReceipt']."'
							");

							if($c['SaveOutbox'] == 1) {
								$this->modules['DB']->query("INSERT INTO ".TBLPFX."pms SET
									FolderID='1',
									PMFromID='".$curRecipient."',
									PMToID='".USERID."',
									PMIsRead='1',
									PMType='1',
									PMSubject='".$p['PMSubject']."',
									PMMessageText='".$p['PMMessageText']."',
									PMSendTimestamp='".time()."',
									PMEnableBBCode='".$c['EnableBBCode']."',
									PMEnableSmilies='".$c['EnableSmilies']."',
									PMEnableHtmlCode='".$c['EnableHtmlCode']."',
									PMShowSignature='".$c['ShowSignature']."',
									PMRequestReadReceipt='".$c['RequestReadReceipt']."'
								");
							}
						}
						Functions::myHeader(INDEXFILE."?Action=PrivateMessages&".MYSID);
					}
				}

				$show = array();
				$show['EnableSmilies'] = $this->modules['Config']->getValue('allow_pms_smilies') == 1;
				$show['ShowSignature'] = $this->modules['Config']->getValue('enable_sig') == 1 && $this->modules['Config']->getValue('allow_pms_signature') == 1;
				$show['EnableBBCode'] = $this->modules['Config']->getValue('allow_pms_bbcode') == 1;
				$show['EnableHtmlCode'] = $this->modules['Config']->getValue('allow_pms_htmlcode') == 1;
				$show['SaveOutbox'] = $this->modules['Config']->getValue('enable_outbox') == 1;
				$show['RequestReadReceipt'] = $this->modules['Config']->getValue('allow_pms_rconfirmation') == 1;

				$smilies = array(); $smiliesBox = '';
				if($show['EnableSmilies']) {
					$smilies = $this->modules['Cache']->getSmiliesData('write');
					$smiliesBox = Functions::getSmiliesBox();
				}
				$pPicsBox = Functions :: getPPicsBox();

				$this->modules['Template']->assign(array(
					'Show'=>$show,
					'Error'=>$error,
					'SmiliesBox'=>$smiliesBox,
					'p'=>$p,
					'c'=>$c
				));

				$this->modules['PageParts']->printPage('PrivateMessagesNewPM.tpl');
			break;

			case 'newpmreceived':
				$pms_tpl = new Template($tEMPLATE_PATH.'/'.$tCONFIG['templates']['pms_newpmreceived']);

				include_once('pop_pheader.php');
				$pms_tpl->parseCode(TRUE);
				include_once('pop_ptail.php');
			break;

			case 'markread':
				$pm_ids = isset($_POST['pm_ids']) ? $_POST['pm_ids'] : array();

				$return_z = isset($_GET['return_z']) ? $_GET['return_z'] : 1; // Die Seite, zu der zurueckgekehrt werden soll
				$return_f = isset($_GET['return_f']) ? $_GET['return_f'] : 0; // Der Ordner, in den zurueckgekehrt werden soll

				if(count($pm_ids) != 0) {
					$pm_ids = implode("','",$pm_ids);
					$this->modules['DB']->query("UPDATE ".TBLPFX."pms SET pm_read_status='1' WHERE pm_id IN ('$pm_ids') AND pm_to_id='$uSER_ID' AND pm_request_rconfirmation<>'1'");
				}

				header("Location: index.php?action=pms&mode=viewfolder&folder_id=$return_f&z=$return_z&$mYSID"); exit;
			break;

			case 'deletepms':
				$pm_id = isset($_GET['pm_id']) ? $_GET['pm_id'] : 0;
				$pm_ids = isset($_POST['pm_ids']) ? $_POST['pm_ids'] : array();

				$return_z = isset($_GET['return_z']) ? $_GET['return_z'] : 1; // Die Seite, zu der zurueckgekehrt werden soll
				$return_f = isset($_GET['return_f']) ? $_GET['return_f'] : 0; // Der Ordner, in den zurueckgekehrt werden soll

				if($pm_id != 0)
					$this->modules['DB']->query("DELETE FROM ".TBLPFX."pms WHERE pm_id='$pm_id' AND pm_to_id='$uSER_ID'");

				if(count($pm_ids) != 0) {
					$pm_ids = implode("','",$pm_ids);
					$this->modules['DB']->query("SELECT pm_id FROM ".TBLPFX."pms WHERE pm_id IN ('$pm_ids') AND pm_to_id='$uSER_ID'");

					$pm_ids = array();
					while(list($akt_pm_id) = $this->modules['DB']->fetch_array())
						$pm_ids[] = $akt_pm_id;

					$pm_ids = implode("','",$pm_ids);
					$this->modules['DB']->query("DELETE FROM ".TBLPFX."pms WHERE pm_id IN ('$pm_ids')");
				}

				header("Location: index.php?action=pms&mode=viewfolder&folder_id=$return_f&z=$return_z&$mYSID"); exit;
			break;

			case 'ViewPM':
				$pmID = isset($_GET['pmID']) ? intval($_GET['pmID']) : 0;
				$returnPage = isset($_GET['returnPage']) ? intval($_GET['returnPage']) : 1;

				// PM-Daten laden
				$this->modules['DB']->query("
					SELECT
						t1.pmSubject,
						t1.pmRequestReadReceipt,
						t1.pmSendTimestamp,
						t1.folderID,
						t1.pmType,
						t1.pmToID,
						t1.pmFromID,
						t1.pmIsRead,
						t1.pmIsReplied,
						t1.pmMessageText,
						t2.userNick AS pmFromNick,
						t4.folderName AS pmFolderName
					FROM ".TBLPFX."pms AS t1
					LEFT JOIN ".TBLPFX."users AS t2 ON t1.PMFromID=t2.UserID
					LEFT JOIN ".TBLPFX."pms_folders AS t4 ON (t4.FolderID=t1.FolderID AND t4.UserID='".USERID."')
					WHERE t1.pmID='$pmID'
				");
				if($this->modules['DB']->getAffectedRows() == 0) die('Kann PM-Daten nicht laden!');
				$pmData = $this->modules['DB']->fetchArray();

				// Ueberpruefen ob...
				if($pmData['pmToID'] != USERID) die('Kein Zugriff auf diese Nachricht!'); // ...User Zugriff auf PM hat...
				if($pmData['pmIsRead'] != 1) {  // ...die PM schon gelesen ist...
					$this->modules['DB']->query("UPDATE ".TBLPFX."pms SET pmIsRead='1' WHERE pmID='$pmID'");
					if($pmData['pmRequestReadReceipt'] == 1 && $this->modules['Config']->getValue('allow_pms_rconfirmation') == 1 && $pmData['pmFromID'] != 0) // ...und eine Lesebestaetigung angefordert wurde
						// TODO: MySQL-Abfrage
						$this->modules['DB']->query("
							INSERT INTO ".TBLPFX."pms SET
								(folder_id,pm_from_id,pm_to_id,pm_read_status,pm_type,pm_subject,pm_send_time,pm_enable_bbcode,pm_enable_smilies,pm_enable_htmlcode,pm_show_sig,pm_request_rconfirmation,pm_text) VALUES ('0','$uSER_ID','".$pm_data['pm_from_id']."','0','0','".$lNG['read_confirmation_subject']."','".time()."','0','0','0','0','0','".mysql_escape_string(sprintf($lNG['read_confirmation_message'],$pm_data['pm_from_nick'],$pm_data['pm_subject']))."')
						");
				}

				$p = array();
				$p['pmSubject'] = isset($_POST['p']['pmSubject']) ? $_POST['p']['pmSubject'] : $pmData['pmSubject'];
				$p['pmMessageText'] = isset($_POST['p']['pmMessageText']) ? $_POST['p']['pmMessageText'] : '';

				if(!isset($_GET['doit']) && strtolower(substr($p['pmSubject'],0,3)) != 're:') $p['pmSubject'] = 'Re: '.$p['pmSubject']; // Falls noch kein Re: da ist, anfuegen

				$c = array();
				$c['enableSmilies'] = $c['enableBBCode'] = $c['showSignature'] = $c['saveOutbox'] = 1;
				$c['enableHtmlCode'] = $c['requestReadReceipt'] = 0;

				$error = '';

				if(isset($_GET['doit']) && $pmData['pmType'] == 0 && $pmData['pmFromID'] != 0) {
					/*/$c['EnableSmilies'] = (isset($_POST['c']['EnableSmilies']) && $this->modules['Config']->getValue('allow_pms_smilies') == 1) ? 1 : 0;
					$c['ShowSignature'] = (isset($_POST['c']['ShowSignature']) && $this->modules['Config']->getValue('enable_sig') == 1) ? 1 : 0;
					$c['EnableBBCode'] = (isset($_POST['c']['EnableBBCode']) && $this->modules['Config']->getValue('allow_pms_bbcode') == 1) ? 1 : 0;
					$c['SaveOutbox'] = (isset($_POST['c']['SaveOutbox']) && $this->modules['Config']->getValue('enable_outbox') == 1) ? 1 : 0;
					$c['RequestReadReceipt'] = (isset($_POST['c']['RequestReadReceipt']) && $this->modules['Config']->getValue('allow_pms_rconfirmation') == 1) ? 1 : 0;
					/**/
					/*/$recipients = explode(',',$p['Recipients']);
					while(list($curKey) = each($recipients)) {
						$recipients[$curKey] = trim($recipients[$curKey]);
						if(!$recipients[$curKey] = Functions::getUserID($recipients[$curKey])) unset($recipients[$curKey]);
					}
					reset($recipients);/**/

					//if(count($recipients) == 0) $error = $this->modules['Language']->getString('error_no_recipient');
					if(trim($p['PMSubject']) == '') $error = $this->modules['Language']->getString('error_no_subject');
					elseif(trim($p['PMMessageText']) == '') $error = $this->modules['Language']->getString('error_no_message');
					else {
						$this->modules['DB']->query("INSERT INTO ".TBLPFX."pms SET
							folderID='0',
							pmFromID='".USERID."',
							pmToID='".$pmData['pmFromID']."',
							pmIsRead='0',
							pmType='0',
							pmSubject='".$p['pmSubject']."',
							pmMessageText='".$p['pmMessageText']."',
							pmSendTimestamp='".time()."',
							pmEnableBBCode='".$c['enableBBCode']."',
							pmEnableSmilies='".$c['enableSmilies']."',
							pmEnableHtmlCode='".$c['enableHtmlCode']."',
							pmShowSignature='".$c['showSignature']."',
							pmRequestReadReceipt='".$c['requestReadReceipt']."'
						");

						if($c['SaveOutbox'] == 1) {
							$this->modules['DB']->query("INSERT INTO ".TBLPFX."pms SET
								folderID='1',
								pmFromID='".$pmData['pmFromID']."',
								pmToID='".USERID."',
								pmIsRead='1',
								pmType='1',
								pmSubject='".$p['pmSubject']."',
								pmMessageText='".$p['pmMessageText']."',
								pmSendTimestamp='".time()."',
								pmEnableBBCode='".$c['enableBBCode']."',
								pmEnableSmilies='".$c['enableSmilies']."',
								pmEnableHtmlCode='".$c['enableHtmlCode']."',
								pmShowSignature='".$c['showSignature']."',
								pmRequestReadReceipt='".$c['requestReadReceipt']."'
							");
						}

						if($pmData['PMIsReplied'] != 1)
							$this->modules['DB']->query("UPDATE ".TBLPFX."pms SET pmIsReplied='1' WHERE pmID='$pmID'");

						Functions::myHeader(INDEXFILE."?action=PrivateMessages&".MYSID);
					}
				}


				if($pmData['pmType'] == 0) {
					$show = array();
					$show['enableSmilies'] = $this->modules['Config']->getValue('allow_pms_smilies') == 1;
					$show['showSignature'] = $this->modules['Config']->getValue('enable_sig') == 1 && $this->modules['Config']->getValue('allow_pms_signature') == 1;
					$show['enableBBCode'] = $this->modules['Config']->getValue('allow_pms_bbcode') == 1;
					$show['enableHtmlCode'] = $this->modules['Config']->getValue('allow_pms_htmlcode') == 1;
					$show['saveOutbox'] = $this->modules['Config']->getValue('enable_outbox') == 1;
					$show['requestReadReceipt'] = $this->modules['Config']->getValue('allow_pms_rconfirmation') == 1;

					$smilies = array(); $smiliesBox = '';
					if($show['enableSmilies']) {
						$smilies = $this->modules['Cache']->getSmiliesData('write');
						$smiliesBox = Functions::getSmiliesBox();
					}
					$pPicsBox = Functions :: getPPicsBox();

					$this->modules['Template']->assign(array(
						'show'=>$show,
						'error'=>$error,
						'smiliesBox'=>$smiliesBox,
						'p'=>$p,
						'c'=>$c
					));
				}

				$pmData['_pmSendDateTime'] = Functions::toDateTime($pmData['pmSendTimestamp']);
				$pmData['_pmSender'] = ($pmData['pmType'] == 0) ? sprintf($this->modules['Language']->getString('from_x'),$pmData['pmFromNick']) : sprintf($this->modules['Language']->getString('to_x'),$pmData['PMFromNick']);

				if($pmData['folderID'] == 0) $pmData['pmFolderName'] = $this->modules['Language']->getString('Inbox');
				elseif($pmData['folderID'] == 1) $pmData['pmFolderName'] = $this->modules['Language']->getString('Outbox');

				$pmData['_pmSubject'] = Functions::HTMLSpecialChars($pmData['pmSubject']);
				$pmData['_pmMessageText'] = nl2br(Functions::HTMLSpecialChars($pmData['pmMessageText']));

				$this->modules['Template']->assign(array(
					'pmID'=>$pmID,
					'pmData'=>$pmData
				));

				$this->modules['Navbar']->addElements(
					array(Functions::HTMLSpecialChars($pmData['pmFolderName']),INDEXFILE.'?action=PrivateMessages&amp;folderID='.$pmData['folderID'].'&amp;'.MYSID),
					array($this->modules['Language']->getString('View_private_message'),INDEXFILE.'?action=PrivateMessages&amp;pmID='.$pmID.'&amp;'.MYSID)
				);

				$this->modules['PageParts']->printPage('PrivateMessagesViewPM.tpl');
				break;

			case 'ManageFolders':
				$this->modules['DB']->query("SELECT * FROM ".TBLPFX."pms_folders WHERE userID='".USERID."' ORDER BY folderName ASC");
				$foldersData = $this->modules['DB']->raw2Array();

				$this->modules['Template']->assign(array(
					'foldersData'=>$foldersData
				));

				$this->modules['PageParts']->printPage('PrivateMessagesManageFolders.tpl');
				break;

			case 'AddFolder':
				$p = Functions::getSGValues($_POST['p'],array('FolderName'),'');

				$error = '';

				if(isset($_GET['Doit'])) {
					if($p['FolderName'] == '') $error = $this->modules['Language']->getString('error_invalid_folder_name');
					else {
						$this->modules['DB']->query("SELECT MAX(FolderID) AS MaxFolderID FROM ".TBLPFX."pms_folders WHERE UserID='".USERID."'");
						list($maxFolderID) = $this->modules['DB']->fetchArray();

						if($maxFolderID < 1) $maxFolderID = 1;

						$this->modules['DB']->query("
							INSERT INTO
								".TBLPFX."pms_folders
							SET
								FolderID='".($maxFolderID+1)."',
								UserID='".USERID."',
								FolderName='".$p['FolderName']."'
						");

						Functions::myHeader(INDEXFILE."?Action=PrivateMessages&Mode=ManageFolders&".MYSID);
					}
				}

				$this->modules['Template']->assign(array(
					'p'=>$p,
					'Error'=>$error
				));

				$this->modules['PageParts']->printPage('PrivateMessagesAddFolder.tpl');
				break;

			case 'EditFolder':
				$folderID = isset($_GET['FolderID']) ? intval($_GET['FolderID']) : 0;

				$this->modules['DB']->query("SELECT * FROM ".TBLPFX."pms_folders WHERE UserID='".USERID."' AND FolderID='".$folderID."'");
				($this->modules['DB']->getAffectedRows() != 1) ? die('Kann Daten nich laden: PM-Ordner') : $folderData = $this->modules['DB']->fetchArray();

				$p = Functions::getSGValues($_POST['p'],array('FolderName'),'',$folderData);

				$error = '';

				if(isset($_GET['Doit'])) {
					if($p['FolderName'] == '') $error = $this->modules['Language']->getString('error_invalid_folder_name');
					else {
						$this->modules['DB']->query("SELECT MAX(FolderID) AS MaxFolderID FROM ".TBLPFX."pms_folders WHERE UserID='".USERID."'");
						list($maxFolderID) = $this->modules['DB']->fetchArray();

						if($maxFolderID < 1) $maxFolderID = 1;

						$this->modules['DB']->query("
							UPDATE
								".TBLPFX."pms_folders
							SET
								FolderName='".$p['FolderName']."'
							WHERE
								UserID='".USERID."'
								AND FolderID='".$folderID."'
						");

						Functions::myHeader(INDEXFILE."?Action=PrivateMessages&Mode=ManageFolders&".MYSID);
					}
				}

				$this->modules['Template']->assign(array(
					'FolderID'=>$folderID,
					'p'=>$p,
					'Error'=>$error
				));

				$this->modules['PageParts']->printPage('PrivateMessagesEditFolder.tpl');
				break;

			case 'DeleteFolder':
				$folderID = isset($_GET['FolderID']) ? intval($_GET['FolderID']) : 0;
				$moveFolderID = isset($_POST['MoveFolderID']) ? intval($_POST['MoveFolderID']) : -1;

				$this->modules['DB']->query("SELECT * FROM ".TBLPFX."pms_folders WHERE UserID='".USERID."' AND FolderID='".$folderID."'");
				($this->modules['DB']->getAffectedRows() != 1) ? die('Kann Daten nich laden: PM-Ordner') : $folderData = $this->modules['DB']->fetchArray();

				$this->modules['DB']->query("SELECT COUNT(*) AS FolderPMsCounter FROM ".TBLPFX."pms WHERE PMToID='".USERID."' AND FolderID='$folderID'");
				list($folderPMsCounter) = $this->modules['DB']->fetchArray();

				$foldersData = array($inboxFolderData,$outboxFolderData);
				$this->modules['DB']->query("SELECT * FROM ".TBLPFX."pms_folders WHERE UserID='".USERID."' AND FolderID<>'".$folderID."' ORDER BY FolderName ASC");
				$foldersData = array_merge($foldersData,$this->modules['DB']->raw2Array());

				$error = '';

				if(isset($_GET['Doit']) || $folderPMsCounter == 0) {
					$validFolder = FALSE;
					foreach($foldersData AS $curFolder) {
						if($curFolder['FolderID'] == $moveFolderID) {
							$validFolder = TRUE;
							break;
						}
					}

					if($moveFolderID != -1 && !$validFolder) $error = $this->modules['Language']->getString('Invalid_selection');
					else {
						if($moveFolderID == -1) $this->modules['DB']->query("DELETE FROM ".TBLPFX."pms WHERE PMToID='".USERID."' AND FolderID='".$folderID."'");
						else $this->modules['DB']->query("UPDATE ".TBLPFX."pms SET FolderID='".$moveFolderID."' WHERE PMToID='".USERID."' AND FolderID='".$folderID."'");

						$this->modules['DB']->query("DELETE FROM ".TBLPFX."pms_folders WHERE UserID='".USERID."' AND FolderID='".$folderID."'");

						// TODO: Richtige Meldung ausgeben
						Functions::myHeader(INDEXFILE."?Action=PrivateMessages&Mode=ManageFolders&".MYSID);
					}
				}

				while(list($curKey) = each($foldersData))
					$foldersData[$curKey]['_MoveText'] = sprintf($this->modules['Language']->getString('Move_messages_to'),$foldersData[$curKey]['FolderName']);

				$this->modules['Template']->assign(array(
					'FoldersData'=>$foldersData,
					'FolderID'=>$folderID
				));

				$this->modules['PageParts']->printPage('PrivateMessagesDeleteFolder.tpl');
				break;
		}
	}
}

?>