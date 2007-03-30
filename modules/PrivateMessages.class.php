<?php

class PrivateMessages extends ModuleTemplate {
	protected $RequiredModules = array(
		'Auth',
		'Cache',
		'Config',
		'DB',
		'Language',
		'Navbar',
		'PageParts',
		'Template'
	);

	function executeMe() {
		if($this->Modules['Auth']->isLoggedIn() != 1) die('Kein Zugriff: Nicht eingeloggt');
		elseif($this->Modules['Config']->getValue('enable_pms') != 1) {
			add_navbar_items(array($LNG['Function_deactivated'],''));

			include_once('pheader.php');
			show_message($LNG['Function_deactivated'],$LNG['message_function_deactivated']);
			include_once('ptail.php'); exit;
		}

		$this->Modules['PageParts']->setInPrivateMessages(TRUE);
		$this->Modules['Language']->addFile('PrivateMessages');
		$this->Modules['Navbar']->addElement($this->Modules['Language']->getString('Private_messages'),INDEXFILE.'?Action=PrivateMessages&amp;'.MYSID);

		$InboxFolderData = array('FolderID'=>0,'FolderName'=>$this->Modules['Language']->getString('Inbox'));
		$OutboxFolderData = array('FolderID'=>1,'FolderName'=>$this->Modules['Language']->getString('Outbox'));

		switch(@$_GET['Mode']) {
			default:
				$FolderID = isset($_GET['FolderID']) ? intval($_GET['FolderID']) : 0;
				$Page = isset($_GET['Page']) ? intval($_GET['Page']) : 1;

				if($FolderID == 0) $FolderData = $InboxFolderData;
				elseif($FolderID == 1) $FolderData = $OutboxFolderData;
				else {
					$this->Modules['DB']->query("SELECT FolderID, FolderName FROM ".TBLPFX."pms_folders WHERE UserID='".USERID."' AND FolderID='$FolderID'");
					if($this->Modules['DB']->getAffectedRows() == 0) {
						$FolderData = $InboxFolderData;
						$FolderID = 0;
					}
					else $FolderData = $this->Modules['DB']->fetchArray();
				}

				$this->Modules['DB']->query("SELECT COUNT(*) FROM ".TBLPFX."pms WHERE PMToID='".USERID."' AND FolderID='$FolderID'");
				list($PMsCounter) = $this->Modules['DB']->fetchArray();

				$PMsPerPage = 20;
				//$this->Modules['Config']->getValue('pms_per_page') = 10;

				$PageListing = Functions::createPageListing($PMsCounter,$PMsPerPage,$Page,"<a href=\"".INDEXFILE."?Action=PrivateMessages&amp;Mode=ViewFolder&amp;FolderID=$FolderID&amp;Page=%1\$s&amp;".MYSID."\">%2\$s</a>");
				$Start = $Page*$this->Modules['Config']->getValue('pms_per_page')-$this->Modules['Config']->getValue('pms_per_page');


				// PM-Daten laden
				$this->Modules['DB']->query("
					SELECT
						t1.PMID,
						t1.PMSubject,
						t1.PMSendTimestamp,
						t1.PMFromID,
						t1.PMType,
						t1.PMIsRead,
						t1.PMIsReplied,
						t1.PMGuestNick,
						t2.UserNick AS PMFromNick
					FROM
						".TBLPFX."pms AS t1
					LEFT JOIN ".TBLPFX."users AS t2 ON t1.PMFromID=t2.UserID
					WHERE
						PMToID='".USERID."'
						AND FolderID='$FolderID'
					ORDER BY
						PMSendTimestamp DESC
					LIMIT $Start,$PMsPerPage
				");

				$PMsData = array();
				while($curPM = $this->Modules['DB']->fetchArray()) {
					$curSenderNick = ($curPM['PMFromID'] == 0) ? $curPM['PMGuestNick'] : $curPM['PMFromNick'];
					$curPM['_PMSender'] = ($curPM['PMType'] == 0) ? sprintf($this->Modules['Language']->getString('from_x'),$curSenderNick) : sprintf($this->Modules['Language']->getString('to_x'),$curSenderNick);
					$curPM['_PMSendDateTime'] = Functions::toDateTime($curPM['PMSendTimestamp']);
					$PMsData[] = $curPM;
				}

				$this->Modules['Navbar']->addElement(Functions::HTMLSpecialChars($FolderData['FolderName']),INDEXFILE.'?Action=PrivateMessages&amp;FolderID='.$FolderID.'&amp;'.MYSID);

				$this->Modules['Template']->assign(array(
					'PageListing'=>$PageListing,
					'PMsData'=>$PMsData
				));

				$this->Modules['PageParts']->printPage('PrivateMessagesDefault.tpl');
			break;

			case 'NewPM':
				$this->Modules['Navbar']->addElement($this->Modules['Language']->getString('New_private_message'),INDEXFILE.'?Action=PrivateMessages&amp;Mode=NewPM&amp;'.MYSID);

				$p = Functions::getSGValues($_POST['p'],array('Recipients','PMSubject','PMMessageText'),'');

				$c = array();
				$c['EnableSmilies'] = $c['EnableBBCode'] = $c['ShowSignature'] = $c['SaveOutbox'] = 1;
				$c['EnableHtmlCode'] = $c['RequestReadReceipt'] = 0;

				$Error = '';

				if(isset($_GET['Doit'])) {
					$c['EnableSmilies'] = (isset($_POST['c']['EnableSmilies']) && $this->Modules['Config']->getValue('allow_pms_smilies') == 1) ? 1 : 0;
					$c['ShowSignature'] = (isset($_POST['c']['ShowSignature']) && $this->Modules['Config']->getValue('enable_sig') == 1) ? 1 : 0;
					$c['EnableBBCode'] = (isset($_POST['c']['EnableBBCode']) && $this->Modules['Config']->getValue('allow_pms_bbcode') == 1) ? 1 : 0;
					$c['SaveOutbox'] = (isset($_POST['c']['SaveOutbox']) && $this->Modules['Config']->getValue('enable_outbox') == 1) ? 1 : 0;
					$c['RequestReadReceipt'] = (isset($_POST['c']['RequestReadReceipt']) && $this->Modules['Config']->getValue('allow_pms_rconfirmation') == 1) ? 1 : 0;

					$Recipients = explode(',',$p['Recipients']);
					while(list($curKey) = each($Recipients)) {
						$Recipients[$curKey] = trim($Recipients[$curKey]);
						if(!$Recipients[$curKey] = Functions::getUserID($Recipients[$curKey])) unset($Recipients[$curKey]);
					}
					reset($Recipients);

					if(count($Recipients) == 0) $Error = $this->Modules['Language']->getString('error_no_recipient');
					elseif(trim($p['PMSubject']) == '') $Error = $this->Modules['Language']->getString('error_no_subject');
					elseif(trim($p['PMMessageText']) == '') $Error = $this->Modules['Language']->getString('error_no_message');
					else {
						foreach($Recipients AS $curRecipient) {
							$this->Modules['DB']->query("INSERT INTO ".TBLPFX."pms SET
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
								$this->Modules['DB']->query("INSERT INTO ".TBLPFX."pms SET
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

				$Show = array();
				$Show['EnableSmilies'] = $this->Modules['Config']->getValue('allow_pms_smilies') == 1;
				$Show['ShowSignature'] = $this->Modules['Config']->getValue('enable_sig') == 1 && $this->Modules['Config']->getValue('allow_pms_signature') == 1;
				$Show['EnableBBCode'] = $this->Modules['Config']->getValue('allow_pms_bbcode') == 1;
				$Show['EnableHtmlCode'] = $this->Modules['Config']->getValue('allow_pms_htmlcode') == 1;
				$Show['SaveOutbox'] = $this->Modules['Config']->getValue('enable_outbox') == 1;
				$Show['RequestReadReceipt'] = $this->Modules['Config']->getValue('allow_pms_rconfirmation') == 1;

				$Smilies = array(); $SmiliesBox = '';
				if($Show['EnableSmilies']) {
					$Smilies = $this->Modules['Cache']->getSmiliesData('write');
					$SmiliesBox = Functions::getSmiliesBox();
				}
				$PPicsBox = Functions :: getPPicsBox();

				$this->Modules['Template']->assign(array(
					'Show'=>$Show,
					'Error'=>$Error,
					'SmiliesBox'=>$SmiliesBox,
					'p'=>$p,
					'c'=>$c
				));

				$this->Modules['PageParts']->printPage('PrivateMessagesNewPM.tpl');
			break;

			case 'newpmreceived':
				$pms_tpl = new Template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['pms_newpmreceived']);

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
					$this->Modules['DB']->query("UPDATE ".TBLPFX."pms SET pm_read_status='1' WHERE pm_id IN ('$pm_ids') AND pm_to_id='$USER_ID' AND pm_request_rconfirmation<>'1'");
				}

				header("Location: index.php?action=pms&mode=viewfolder&folder_id=$return_f&z=$return_z&$MYSID"); exit;
			break;

			case 'deletepms':
				$pm_id = isset($_GET['pm_id']) ? $_GET['pm_id'] : 0;
				$pm_ids = isset($_POST['pm_ids']) ? $_POST['pm_ids'] : array();

				$return_z = isset($_GET['return_z']) ? $_GET['return_z'] : 1; // Die Seite, zu der zurueckgekehrt werden soll
				$return_f = isset($_GET['return_f']) ? $_GET['return_f'] : 0; // Der Ordner, in den zurueckgekehrt werden soll

				if($pm_id != 0)
					$this->Modules['DB']->query("DELETE FROM ".TBLPFX."pms WHERE pm_id='$pm_id' AND pm_to_id='$USER_ID'");

				if(count($pm_ids) != 0) {
					$pm_ids = implode("','",$pm_ids);
					$this->Modules['DB']->query("SELECT pm_id FROM ".TBLPFX."pms WHERE pm_id IN ('$pm_ids') AND pm_to_id='$USER_ID'");

					$pm_ids = array();
					while(list($akt_pm_id) = $this->Modules['DB']->fetch_array())
						$pm_ids[] = $akt_pm_id;

					$pm_ids = implode("','",$pm_ids);
					$this->Modules['DB']->query("DELETE FROM ".TBLPFX."pms WHERE pm_id IN ('$pm_ids')");
				}

				header("Location: index.php?action=pms&mode=viewfolder&folder_id=$return_f&z=$return_z&$MYSID"); exit;
			break;

			case 'ViewPM':
				$PMID = isset($_GET['PMID']) ? intval($_GET['PMID']) : 0;
				$ReturnPage = isset($_GET['ReturnPage']) ? intval($_GET['ReturnPage']) : 1;

				// PM-Daten laden
				$this->Modules['DB']->query("
					SELECT
						t1.PMSubject,
						t1.PMRequestReadReceipt,
						t1.PMSendTimestamp,
						t1.FolderID,
						t1.PMType,
						t1.PMToID,
						t1.PMFromID,
						t1.PMIsRead,
						t1.PMIsReplied,
						t1.PMMessageText,
						t2.UserNick AS PMFromNick,
						t4.FolderName AS PMFolderName
					FROM ".TBLPFX."pms AS t1
					LEFT JOIN ".TBLPFX."users AS t2 ON t1.PMFromID=t2.UserID
					LEFT JOIN ".TBLPFX."pms_folders AS t4 ON (t4.FolderID=t1.FolderID AND t4.UserID='".USERID."')
					WHERE t1.PMID='$PMID'
				");
				if($this->Modules['DB']->getAffectedRows() == 0) die('Kann PM-Daten nicht laden!');
				$PMData = $this->Modules['DB']->fetchArray();

				// Ueberpruefen ob...
				if($PMData['PMToID'] != USERID) die('Kein Zugriff auf diese Nachricht!'); // ...User Zugriff auf PM hat...
				if($PMData['PMIsRead'] != 1) {  // ...die PM schon gelesen ist...
					$this->Modules['DB']->query("UPDATE ".TBLPFX."pms SET PMIsRead='1' WHERE PMID='$PMID'");
					if($PMData['PMRequestReadReceipt'] == 1 && $this->Modules['Config']->getValue('allow_pms_rconfirmation') == 1 && $PMData['PMFromID'] != 0) // ...und eine Lesebestaetigung angefordert wurde
						$this->Modules['DB']->query("
							INSERT INTO ".TBLPFX."pms SET
								(folder_id,pm_from_id,pm_to_id,pm_read_status,pm_type,pm_subject,pm_send_time,pm_enable_bbcode,pm_enable_smilies,pm_enable_htmlcode,pm_show_sig,pm_request_rconfirmation,pm_text) VALUES ('0','$USER_ID','".$pm_data['pm_from_id']."','0','0','".$LNG['read_confirmation_subject']."','".time()."','0','0','0','0','0','".mysql_escape_string(sprintf($LNG['read_confirmation_message'],$pm_data['pm_from_nick'],$pm_data['pm_subject']))."')
						");
				}

				$p = array();
				$p['PMSubject'] = isset($_POST['p']['PMSubject']) ? $_POST['p']['PMSubject'] : $PMData['PMSubject'];
				$p['PMMessageText'] = isset($_POST['p']['PMMessageText']) ? $_POST['p']['PMMessageText'] : '';

				if(!isset($_GET['Doit']) && strtolower(substr($p['PMSubject'],0,3)) != 're:') $p['PMSubject'] = 'Re: '.$p['PMSubject']; // Falls noch kein Re: da ist, anfuegen

				$c = array();
				$c['EnableSmilies'] = $c['EnableBBCode'] = $c['ShowSignature'] = $c['SaveOutbox'] = 1;
				$c['EnableHtmlCode'] = $c['RequestReadReceipt'] = 0;

				$Error = '';

				if(isset($_GET['Doit']) && $PMData['PMType'] == 0 && $PMData['PMFromID'] != 0) {
					$c['EnableSmilies'] = (isset($_POST['c']['EnableSmilies']) && $this->Modules['Config']->getValue('allow_pms_smilies') == 1) ? 1 : 0;
					$c['ShowSignature'] = (isset($_POST['c']['ShowSignature']) && $this->Modules['Config']->getValue('enable_sig') == 1) ? 1 : 0;
					$c['EnableBBCode'] = (isset($_POST['c']['EnableBBCode']) && $this->Modules['Config']->getValue('allow_pms_bbcode') == 1) ? 1 : 0;
					$c['SaveOutbox'] = (isset($_POST['c']['SaveOutbox']) && $this->Modules['Config']->getValue('enable_outbox') == 1) ? 1 : 0;
					$c['RequestReadReceipt'] = (isset($_POST['c']['RequestReadReceipt']) && $this->Modules['Config']->getValue('allow_pms_rconfirmation') == 1) ? 1 : 0;

					/*$Recipients = explode(',',$p['Recipients']);
					while(list($curKey) = each($Recipients)) {
						$Recipients[$curKey] = trim($Recipients[$curKey]);
						if(!$Recipients[$curKey] = Functions::getUserID($Recipients[$curKey])) unset($Recipients[$curKey]);
					}
					reset($Recipients);*/

					//if(count($Recipients) == 0) $Error = $this->Modules['Language']->getString('error_no_recipient');
					if(trim($p['PMSubject']) == '') $Error = $this->Modules['Language']->getString('error_no_subject');
					elseif(trim($p['PMMessageText']) == '') $Error = $this->Modules['Language']->getString('error_no_message');
					else {
						$this->Modules['DB']->query("INSERT INTO ".TBLPFX."pms SET
							FolderID='0',
							PMFromID='".USERID."',
							PMToID='".$PMData['PMFromID']."',
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
							$this->Modules['DB']->query("INSERT INTO ".TBLPFX."pms SET
								FolderID='1',
								PMFromID='".$PMData['PMFromID']."',
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

						if($PMData['PMIsReplied'] != 1)
							$this->Modules['DB']->query("UPDATE ".TBLPFX."pms SET PMIsReplied='1' WHERE PMID='$PMID'");

						Functions::myHeader(INDEXFILE."?Action=PrivateMessages&".MYSID);
					}
				}


				if($PMData['PMType'] == 0) {
					$Show = array();
					$Show['EnableSmilies'] = $this->Modules['Config']->getValue('allow_pms_smilies') == 1;
					$Show['ShowSignature'] = $this->Modules['Config']->getValue('enable_sig') == 1 && $this->Modules['Config']->getValue('allow_pms_signature') == 1;
					$Show['EnableBBCode'] = $this->Modules['Config']->getValue('allow_pms_bbcode') == 1;
					$Show['EnableHtmlCode'] = $this->Modules['Config']->getValue('allow_pms_htmlcode') == 1;
					$Show['SaveOutbox'] = $this->Modules['Config']->getValue('enable_outbox') == 1;
					$Show['RequestReadReceipt'] = $this->Modules['Config']->getValue('allow_pms_rconfirmation') == 1;

					$Smilies = array(); $SmiliesBox = '';
					if($Show['EnableSmilies']) {
						$Smilies = $this->Modules['Cache']->getSmiliesData('write');
						$SmiliesBox = Functions::getSmiliesBox();
					}
					$PPicsBox = Functions :: getPPicsBox();

					$this->Modules['Template']->assign(array(
						'Show'=>$Show,
						'Error'=>$Error,
						'SmiliesBox'=>$SmiliesBox,
						'p'=>$p,
						'c'=>$c
					));
				}

				$PMData['_PMSendDateTime'] = Functions::toDateTime($PMData['PMSendTimestamp']);
				$PMData['_PMSender'] = ($PMData['PMType'] == 0) ? sprintf($this->Modules['Language']->getString('from_x'),$PMData['PMFromNick']) : sprintf($this->Modules['Language']->getString('to_x'),$PMData['PMFromNick']);

				if($PMData['FolderID'] == 0) $PMData['PMFolderName'] = $this->Modules['Language']->getString('Inbox');
				elseif($PMData['FolderID'] == 1) $PMData['PMFolderName'] = $this->Modules['Language']->getString('Outbox');

				$PMData['_PMSubject'] = Functions::HTMLSpecialChars($PMData['PMSubject']);
				$PMData['_PMMessageText'] = nl2br(Functions::HTMLSpecialChars($PMData['PMMessageText']));

				$this->Modules['Template']->assign(array(
					'PMID'=>$PMID,
					'PMData'=>$PMData
				));

				$this->Modules['Navbar']->addElements(
					array(Functions::HTMLSpecialChars($PMData['PMFolderName']),INDEXFILE.'?Action=PrivateMessages&amp;FolderID='.$PMData['FolderID'].'&amp;'.MYSID),
					array($this->Modules['Language']->getString('View_private_message'),INDEXFILE.'?Action=PrivateMessages&amp;PMID='.$PMID.'&amp;'.MYSID)
				);

				$this->Modules['PageParts']->printPage('PrivateMessagesViewPM.tpl');
				break;

			case 'ManageFolders':
				$this->Modules['DB']->query("SELECT * FROM ".TBLPFX."pms_folders WHERE UserID='".USERID."' ORDER BY FolderName ASC");
				$FoldersData = $this->Modules['DB']->Raw2Array();

				$this->Modules['Template']->assign(array(
					'FoldersData'=>$FoldersData
				));

				$this->Modules['PageParts']->printPage('PrivateMessagesManageFolders.tpl');
				break;

			case 'AddFolder':
				$p = Functions::getSGValues($_POST['p'],array('FolderName'),'');

				$Error = '';

				if(isset($_GET['Doit'])) {
					if($p['FolderName'] == '') $Error = $this->Modules['Language']->getString('error_invalid_folder_name');
					else {
						$this->Modules['DB']->query("SELECT MAX(FolderID) AS MaxFolderID FROM ".TBLPFX."pms_folders WHERE UserID='".USERID."'");
						list($MaxFolderID) = $this->Modules['DB']->fetchArray();

						if($MaxFolderID < 1) $MaxFolderID = 1;

						$this->Modules['DB']->query("
							INSERT INTO
								".TBLPFX."pms_folders
							SET
								FolderID='".($MaxFolderID+1)."',
								UserID='".USERID."',
								FolderName='".$p['FolderName']."'
						");

						Functions::myHeader(INDEXFILE."?Action=PrivateMessages&Mode=ManageFolders&".MYSID);
					}
				}

				$this->Modules['Template']->assign(array(
					'p'=>$p,
					'Error'=>$Error
				));

				$this->Modules['PageParts']->printPage('PrivateMessagesAddFolder.tpl');
				break;

			case 'EditFolder':
				$FolderID = isset($_GET['FolderID']) ? intval($_GET['FolderID']) : 0;

				$this->Modules['DB']->query("SELECT * FROM ".TBLPFX."pms_folders WHERE UserID='".USERID."' AND FolderID='".$FolderID."'");
				($this->Modules['DB']->getAffectedRows() != 1) ? die('Kann Daten nich laden: PM-Ordner') : $FolderData = $this->Modules['DB']->fetchArray();

				$p = Functions::getSGValues($_POST['p'],array('FolderName'),'',$FolderData);

				$Error = '';

				if(isset($_GET['Doit'])) {
					if($p['FolderName'] == '') $Error = $this->Modules['Language']->getString('error_invalid_folder_name');
					else {
						$this->Modules['DB']->query("SELECT MAX(FolderID) AS MaxFolderID FROM ".TBLPFX."pms_folders WHERE UserID='".USERID."'");
						list($MaxFolderID) = $this->Modules['DB']->fetchArray();

						if($MaxFolderID < 1) $MaxFolderID = 1;

						$this->Modules['DB']->query("
							UPDATE
								".TBLPFX."pms_folders
							SET
								FolderName='".$p['FolderName']."'
							WHERE
								UserID='".USERID."'
								AND FolderID='".$FolderID."'
						");

						Functions::myHeader(INDEXFILE."?Action=PrivateMessages&Mode=ManageFolders&".MYSID);
					}
				}

				$this->Modules['Template']->assign(array(
					'FolderID'=>$FolderID,
					'p'=>$p,
					'Error'=>$Error
				));

				$this->Modules['PageParts']->printPage('PrivateMessagesEditFolder.tpl');
				break;

			case 'DeleteFolder':
				$FolderID = isset($_GET['FolderID']) ? intval($_GET['FolderID']) : 0;
				$MoveFolderID = isset($_POST['MoveFolderID']) ? intval($_POST['MoveFolderID']) : -1;

				$this->Modules['DB']->query("SELECT * FROM ".TBLPFX."pms_folders WHERE UserID='".USERID."' AND FolderID='".$FolderID."'");
				($this->Modules['DB']->getAffectedRows() != 1) ? die('Kann Daten nich laden: PM-Ordner') : $FolderData = $this->Modules['DB']->fetchArray();

				$this->Modules['DB']->query("SELECT COUNT(*) AS FolderPMsCounter FROM ".TBLPFX."pms WHERE PMToID='".USERID."' AND FolderID='$FolderID'");
				list($FolderPMsCounter) = $this->Modules['DB']->fetchArray();

				$FoldersData = array($InboxFolderData,$OutboxFolderData);
				$this->Modules['DB']->query("SELECT * FROM ".TBLPFX."pms_folders WHERE UserID='".USERID."' AND FolderID<>'".$FolderID."' ORDER BY FolderName ASC");
				$FoldersData = array_merge($FoldersData,$this->Modules['DB']->Raw2Array());

				$Error = '';

				if(isset($_GET['Doit']) || $FolderPMsCounter == 0) {
					$ValidFolder = FALSE;
					foreach($FoldersData AS $curFolder) {
						if($curFolder['FolderID'] == $MoveFolderID) {
							$ValidFolder = TRUE;
							break;
						}
					}

					if($MoveFolderID != -1 && !$ValidFolder) $Error = $this->Modules['Language']->getString('Invalid_selection');
					else {
						if($MoveFolderID == -1) $this->Modules['DB']->query("DELETE FROM ".TBLPFX."pms WHERE PMToID='".USERID."' AND FolderID='".$FolderID."'");
						else $this->Modules['DB']->query("UPDATE ".TBLPFX."pms SET FolderID='".$MoveFolderID."' WHERE PMToID='".USERID."' AND FolderID='".$FolderID."'");

						$this->Modules['DB']->query("DELETE FROM ".TBLPFX."pms_folders WHERE UserID='".USERID."' AND FolderID='".$FolderID."'");

						// TODO: Richtige Meldung ausgeben
						Functions::myHeader(INDEXFILE."?Action=PrivateMessages&Mode=ManageFolders&".MYSID);
					}
				}

				while(list($curKey) = each($FoldersData))
					$FoldersData[$curKey]['_MoveText'] = sprintf($this->Modules['Language']->getString('Move_messages_to'),$FoldersData[$curKey]['FolderName']);

				$this->Modules['Template']->assign(array(
					'FoldersData'=>$FoldersData,
					'FolderID'=>$FolderID
				));

				$this->Modules['PageParts']->printPage('PrivateMessagesDeleteFolder.tpl');
				break;
		}
	}
}

?>