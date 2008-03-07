<?php

class PrivateMessages extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'BBCode',
		'Cache',
		'Config',
		'Constants',
		'DB',
		'Language',
		'Navbar',
		'Template'
	);

	public function printTail() {
		$this->modules['Template']->display('PrivateMessagesTail.tpl');
	}

	public function printHeader() {
        $this->modules['DB']->queryParams('SELECT "folderName", "folderID" FROM '.TBLPFX.'pms_folders WHERE "userID"=$1 ORDER BY "folderName"', array(USERID));
		$headerFoldersData = $this->modules['DB']->raw2Array();

		array_unshift($headerFoldersData, // Fuegt an den Anfang die Standardordner hinzu...
			array('folderID'=>0,'folderName'=>$this->modules['Language']->getString('Inbox')),
			array('folderID'=>1,'folderName'=>$this->modules['Language']->getString('Outbox'))
		);
		reset($headerFoldersData);

		$this->modules['Template']->assign('headerFoldersData',$headerFoldersData);

		$this->modules['Template']->display('PrivateMessagesHeader.tpl');
	}

	public function initializeMe() {
		$this->modules['Template']->registerSubFrame(array($this,'printHeader'),array($this,'printTail'));
	}

	public function executeMe() {
		if($this->modules['Auth']->isLoggedIn() != 1) die('Kein Zugriff: Nicht eingeloggt');
		elseif($this->modules['Config']->getValue('enable_pms') != 1) {
			$this->modules['Navbar']->addElement($this->modules['Language']->getString('Function_deactivated'),'');
			FuncMisc::printMessage('function_deactivated');
			exit;
		}

		$this->modules['Language']->addFile('PrivateMessages');
		$this->modules['Navbar']->addElement($this->modules['Language']->getString('Private_messages'),INDEXFILE.'?action=PrivateMessages&amp;'.MYSID);

		$inboxFolderData = array('folderID'=>0,'folderName'=>$this->modules['Language']->getString('Inbox'));
		$outboxFolderData = array('folderID'=>1,'folderName'=>$this->modules['Language']->getString('Outbox'));

		switch(@$_GET['mode']) {
			default:
				$folderID = isset($_GET['folderID']) ? intval($_GET['folderID']) : 0;
				$page = isset($_GET['page']) ? $_GET['page'] : 1;

				if($folderID == 0) $folderData = $inboxFolderData;
				elseif($folderID == 1) $folderData = $outboxFolderData;
				else {
                    $this->modules['DB']->queryParams('SELECT "folderID", "folderName" FROM '.TBLPFX.'pms_folders WHERE "userID"=$1 AND "folderID"=$2', array(USERID, $folderID));
					if($this->modules['DB']->getAffectedRows() == 0) {
						$folderData = $inboxFolderData;
						$folderID = 0;
					}
					else $folderData = $this->modules['DB']->fetchArray();
				}

                $this->modules['DB']->queryParams('SELECT COUNT(*) FROM '.TBLPFX.'pms WHERE "PMToID"=$1 AND "FolderID"=$2', array(USERID, $folderID));
				list($pmsCounter) = $this->modules['DB']->fetchArray();

				$pmsPerPage = 20;
				//$this->modules['Config']->getValue('pms_per_page') = 10;

				$pageListing = Functions::createPageListing($pmsCounter,$pmsPerPage,$page,"<a href=\"".INDEXFILE."?action=PrivateMessages&amp;mode=ViewFolder&amp;folderID=$folderID&amp;page=%1\$s&amp;".MYSID."\">%2\$s</a>");
				//$start = $page*$this->modules['Config']->getValue('pms_per_page')-$this->modules['Config']->getValue('pms_per_page');
				$start = $page*$pmsPerPage-$pmsPerPage;

				// PM-Daten laden
                $this->modules['DB']->queryParams('
                    SELECT
                        t1."pmID",
                        t1."pmSubject",
                        t1."pmMessageText",
                        t1."pmSendTimestamp",
                        t1."pmFromID",
                        t1."pmType",
                        t1."pmIsRead",
                        t1."pmIsReplied",
                        t1."pmGuestNick",
                        t2."userNick" AS "pmFromNick"
                    FROM
                        '.TBLPFX.'pms AS t1
                    LEFT JOIN '.TBLPFX.'users AS t2 ON t1."pmFromID"=t2."userID"
                    WHERE
                        "pmToID"=$1
                        AND "folderID"=$2
                    ORDER BY
                        "pmSendTimestamp" DESC
                    LIMIT $3, $4
                ', array(
                    USERID,
                    $folderID,
                    $start,
                    $pmsPerPage
                ));

				$pmsData = array();
				while($curPM = $this->modules['DB']->fetchArray()) {
					$curSenderNick = ($curPM['pmFromID'] == 0) ? $curPM['pmGuestNick'] : $curPM['pmFromNick'];
					$curPM['_pmSender'] = ($curPM['pmType'] == 0) ? sprintf($this->modules['Language']->getString('from_x'),$curSenderNick) : sprintf($this->modules['Language']->getString('to_x'),$curSenderNick);
					$curPM['_pmSendDateTime'] = Functions::toDateTime($curPM['pmSendTimestamp']);

					$curPM['_pmMessageTextShort'] = (Functions::strlen($curPM['pmMessageText']) > 100) ? Functions::HTMLSpecialChars(Functions::substr($curPM['pmMessageText'],0,100)).'...' : Functions::HTMLSpecialChars($curPM['pmMessageText']);

					$pmsData[] = $curPM;
				}

                $this->modules['DB']->queryParams('SELECT * FROM '.TBLPFX.'pms_folders WHERE "userID"=$1 ORDER BY "folderName" ASC', array(USERID));
				$foldersData = array_merge(array($inboxFolderData,$outboxFolderData),$this->modules['DB']->raw2Array());

				while(list($curKey) = each($foldersData))
					$foldersData[$curKey]['_moveText'] = sprintf($this->modules['Language']->getString('Move_messages_to'),$foldersData[$curKey]['folderName']);

				$this->modules['Navbar']->addElement(Functions::HTMLSpecialChars($folderData['folderName']),INDEXFILE.'?action=PrivateMessages&amp;folderID='.$folderID.'&amp;'.MYSID);
				$this->modules['Navbar']->setRightArea($pageListing);

				$this->modules['Template']->assign(array(
					'pmsData'=>$pmsData,
					'folderID'=>$folderID,
					'page'=>$page,
					'foldersData'=>$foldersData
				));

				$this->modules['Template']->printPage('PrivateMessagesViewFolder.tpl');
			break;

			case 'NewPM':
				$this->modules['Navbar']->addElement($this->modules['Language']->getString('New_private_message'),INDEXFILE.'?action=PrivateMessages&amp;Mode=NewPM&amp;'.MYSID);

				$p = Functions::getSGValues($_POST['p'],array('recipients','pmSubject','pmMessageText'),'');

				$c = array();
				$c['enableSmilies'] = $c['enableBBCode'] = $c['showSignature'] = $c['saveOutbox'] = 1;
				$c['enableHtmlCode'] = $c['requestReadReceipt'] = 0;

				$error = '';

				if(isset($_GET['doit'])) {
					$c['enableSmilies'] = (isset($_POST['c']['enableSmilies']) && $this->modules['Config']->getValue('allow_pms_smilies') == 1) ? 1 : 0;
					$c['showSignature'] = (isset($_POST['c']['showSignature']) && $this->modules['Config']->getValue('enable_sig') == 1) ? 1 : 0;
					$c['enableBBCode'] = (isset($_POST['c']['enableBBCode']) && $this->modules['Config']->getValue('allow_pms_bbcode') == 1) ? 1 : 0;
					$c['saveOutbox'] = (isset($_POST['c']['saveOutbox']) && $this->modules['Config']->getValue('enable_outbox') == 1) ? 1 : 0;
					$c['requestReadReceipt'] = (isset($_POST['c']['requestReadReceipt']) && $this->modules['Config']->getValue('allow_pms_rconfirmation') == 1) ? 1 : 0;

					$recipients = explode(',',$p['recipients']);
					$recipientsID = $recipientsNick = array();
					while(count($recipients) > 0) {
						$curRecipient = trim(array_pop($recipients));
						if(!preg_match('/^[0-9]{1,}$/si',$curRecipient))
							$recipientsNick[] = $curRecipient;
						else $recipientsID[] = $curRecipient;
					}

                    $this->modules['DB']->queryParams('SELECT "userID" FROM '.TBLPFX.'users WHERE "userID" IN $1 OR "userNick" IN $2 GROUP BY "userID"', array($recipientsID, $recipientsNick));
					$recipients = $this->modules['DB']->raw2FVArray();

					if(count($recipients) == 0) $error = $this->modules['Language']->getString('error_no_recipient');
					elseif(trim($p['pmSubject']) == '') $error = $this->modules['Language']->getString('error_no_subject');
					elseif(trim($p['pmMessageText']) == '') $error = $this->modules['Language']->getString('error_no_message');
					else {
						foreach($recipients AS $curRecipient) {
                            $this->modules['DB']->queryParams('
                                INSERT INTO
                                    '.TBLPFX.'pms
                                SET
                                    "folderID"=0,
                                    "pmFromID"=$1,
                                    "pmToID"=$2,
                                    "pmIsRead"=0,
                                    "pmType"=0,
                                    "pmSubject"=$3,
                                    "pmMessageText"=$4,
                                    "pmSendTimestamp"=$5,
                                    "pmEnableBBCode"=$6,
                                    "pmEnableSmilies"=$7,
                                    "pmEnableHtmlCode"=$8
                                    "pmShowSignature"=$9,
                                    "pmRequestReadReceipt"=$10
                            ', array(

                                USERID,
                                $curRecipient,


                                $p['pmSubject'],
                                $p['pmMessageText'],
                                time(),
                                $c['enableBBCode'],
                                $c['enableSmilies'],
                                $c['enableHtmlCode'],
                                $c['showSignature'],
                                $c['requestReadReceipt']
                            ));

							if($c['saveOutbox'] == 1) {
                                $this->modules['DB']->queryParams('
                                    INSERT INTO
                                        '.TBLPFX.'pms
                                    SET
                                        "folderID"=1,
                                        "pmFromID"=$1,
                                        "pmToID"=$2,
                                        "pmIsRead"=1,
                                        "pmType"=1,
                                        "pmSubject"=$3,
                                        "pmMessageText"=$4,
                                        "pmSendTimestamp"=$5,
                                        "pmEnableBBCode"=$6,
                                        "pmEnableSmilies"=$7,
                                        "pmEnableHtmlCode"=$8,
                                        "pmShowSignature"=$9,
                                        "pmRequestReadReceipt"=$10
                                ', array(

                                    $curRecipient,
                                    USERID,


                                    $p['pmSubject'],
                                    $p['pmMessageText'],
                                    time(),
                                    $c['enableBBCode'],
                                    $c['enableSmilies'],
                                    $c['enableHtmlCode'],
                                    $c['showSignature'],
                                    $c['requestReadReceipt']
                                ));
							}
						}
						Functions::myHeader(INDEXFILE."?action=PrivateMessages&".MYSID);
					}
				}

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
				$postPicsBox = Functions :: getPostPicsBox();

				$this->modules['Template']->assign(array(
					'show'=>$show,
					'error'=>$error,
					'smiliesBox'=>$smiliesBox,
					'postPicsBox'=>$postPicsBox,
					'p'=>$p,
					'c'=>$c
				));

				$this->modules['Template']->printPage('PrivateMessagesNewPM.tpl');
			break;

			case 'popNewPMReceived':
				// TODO: Alles
				include_once('pop_pheader.php');
				$pms_tpl->parseCode(TRUE);
				include_once('pop_ptail.php');
			break;

			case 'MarkPMsRead':
				$pmIDs = (isset($_POST['pmIDs']) && is_array($_POST['pmIDs'])) ? $_POST['pmIDs'] : array();

				$returnFolderID = isset($_GET['returnFolderID']) ? intval($_GET['returnFolderID']) : 0;
				$returnPage = isset($_GET['returnPage']) ? intval($_GET['returnPage']) : 1;

				if(count($pmIDs) != 0)
                    $this->modules['DB']->queryParams('UPDATE '.TBLPFX.'pms SET "pmIsRead"=1 WHERE "pmID" IN $1 AND "pmToID"=$2 AND "pmRequestReadReceipt"<>1', array($pmIDs, USERID));

				Functions::myHeader(INDEXFILE."?action=PrivateMessages&mode=ViewFolder&folderID=$returnFolderID&page=$returnPage&".MYSID);
			break;

			case 'DeletePMs':
				$pmID = isset($_GET['pmID']) ? intval($_GET['pmID']) : 0;
				$pmIDs = (isset($_POST['pmIDs']) && is_array($_POST['pmIDs'])) ? $_POST['pmIDs'] : array();

				$returnFolderID = isset($_GET['returnFolderID']) ? intval($_GET['returnFolderID']) : 0;
				$returnPage = isset($_GET['returnPage']) ? intval($_GET['returnPage']) : 1;

				if($pmID != 0)
                    $this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'pms WHERE "pmID"=$1 AND "pmToID"=$2', array($pmID, USERID));

				if(count($pmIDs) != 0)
                    $this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'pms WHERE "pmID" IN $1 AND "pmToID"=$2', array($pmIDs, USERID));

				Functions::myHeader(INDEXFILE."?action=PrivateMessages&mode=ViewFolder&folderID=$returnFolderID&page=$returnPage&".MYSID);
			break;

			case 'ViewPM':
				$pmID = isset($_GET['pmID']) ? intval($_GET['pmID']) : 0;
				$returnPage = isset($_GET['returnPage']) ? intval($_GET['returnPage']) : 1;

				// PM-Daten laden
                $this->modules['DB']->queryParams('
                    SELECT
                        t1."pmSubject",
                        t1."pmRequestReadReceipt",
                        t1."pmSendTimestamp",
                        t1."folderID",
                        t1."pmType",
                        t1."pmToID",
                        t1."pmFromID",
                        t1."pmIsRead",
                        t1."pmIsReplied",
                        t1."pmMessageText",
                        t2."userNick" AS "pmFromNick",
                        t4."folderName" AS "pmFolderName"
                    FROM '.TBLPFX.'pms AS t1
                    LEFT JOIN '.TBLPFX.'users AS t2 ON t1."PMFromID"=t2."UserID"
                    LEFT JOIN '.TBLPFX.'pms_folders AS t4 ON (t4."FolderID"=t1."FolderID" AND t4."UserID"=$1)
                    WHERE t1."pmID"=$2
                ', array(
                    USERID,
                    $pmID
                ));
				if($this->modules['DB']->getAffectedRows() == 0) die('Kann PM-Daten nicht laden!');
				$pmData = $this->modules['DB']->fetchArray();

				if($pmData['pmToID'] != USERID) die('Kein Zugriff auf diese Nachricht!'); // ...User Zugriff auf PM hat...
				if($pmData['pmIsRead'] != 1) {  // ...die PM schon gelesen ist...
                    $this->modules['DB']->queryParams('UPDATE '.TBLPFX.'pms SET "pmIsRead"=1 WHERE "pmID"=$1', array($pmID));
					if($pmData['pmRequestReadReceipt'] == 1 && $this->modules['Config']->getValue('allow_pms_rconfirmation') == 1 && $pmData['pmFromID'] != 0) // ...und eine Lesebestaetigung angefordert wurde
						// TODO: Betreff und Nachricht in der Sprache des Empfaengers
                        $this->modules['DB']->queryParams('
                            INSERT INTO
                                '.TBLPFX.'pms
                            SET
                                "folderID"=0,
                                "pmFromID"=$1,
                                "pmToID"=$2,
                                "pmIsRead"=0,
                                "pmType"=0,
                                "pmSubject"=$3,
                                "pmSendTimestamp"=$4,
                                "pmEnableBBcode"=0,
                                "pmEnableSmilies"=0,
                                "pmEnableHtmlCode"=0,
                                "pmShowSignature"=0,
                                "pmRequestReadReceipt"=0,
                                "pmMessageText"=$5
                        ', array(
                            USERID,
                            $this->modules['Language']->getString('read_confirmation_subject'),
                            time(),
                            sprintf($this->modules['Language']->getString('read_confirmation_message',$pmData['pmFromNick'],$pmData['pmSubject']))
                        ));
				}

				$p = array();
				$p['pmSubject'] = isset($_POST['p']['pmSubject']) ? $_POST['p']['pmSubject'] : $pmData['pmSubject'];
				$p['pmMessageText'] = isset($_POST['p']['pmMessageText']) ? $_POST['p']['pmMessageText'] : '';

				if(!isset($_GET['doit']) && Functions::strtolower(Functions::substr($p['pmSubject'],0,3)) != 're:') $p['pmSubject'] = 'Re: '.$p['pmSubject']; // Falls noch kein Re: da ist, anfuegen

				$c = array();
				$c['enableSmilies'] = $c['enableBBCode'] = $c['showSignature'] = $c['saveOutbox'] = 1;
				$c['enableHtmlCode'] = $c['requestReadReceipt'] = 0;

				$error = '';

				if(isset($_GET['doit']) && $pmData['pmType'] == 0 && $pmData['pmFromID'] != 0) {
					$c['enableSmilies'] = (isset($_POST['c']['EnableSmilies']) && $this->modules['Config']->getValue('allow_pms_smilies') == 1) ? 1 : 0;
					$c['showSignature'] = (isset($_POST['c']['ShowSignature']) && $this->modules['Config']->getValue('enable_sig') == 1) ? 1 : 0;
					$c['enableBBCode'] = (isset($_POST['c']['EnableBBCode']) && $this->modules['Config']->getValue('allow_pms_bbcode') == 1) ? 1 : 0;
					$c['saveOutbox'] = (isset($_POST['c']['SaveOutbox']) && $this->modules['Config']->getValue('enable_outbox') == 1) ? 1 : 0;
					$c['requestReadReceipt'] = (isset($_POST['c']['RequestReadReceipt']) && $this->modules['Config']->getValue('allow_pms_rconfirmation') == 1) ? 1 : 0;

					if(trim($p['pmSubject']) == '') $error = $this->modules['Language']->getString('error_no_subject');
					elseif(trim($p['pmMessageText']) == '') $error = $this->modules['Language']->getString('error_no_message');
					else {
                        $this->modules['DB']->queryParams('
                            INSERT INTO
                                '.TBLPFX.'pms
                            SET
                                "folderID"=0,
                                "pmFromID"=$1,
                                "pmToID"=$2,
                                "pmIsRead"=0,
                                "pmType"=0,
                                "pmSubject"=$3,
                                "pmMessageText"=$4,
                                "pmSendTimestamp"=$5,
                                "pmEnableBBCode"=$6,
                                "pmEnableSmilies"=$7,
                                "pmEnableHtmlCode"=$8,
                                "pmShowSignature"=$9,
                                "pmRequestReadReceipt"=$10
                        ', array(

                            USERID,
                            $pmData['pmFromID'],


                            $p['pmSubject'],
                            $p['pmMessageText'],
                            time(),
                            $c['enableBBCode'],
                            $c['enableSmilies'],
                            $c['enableHtmlCode'],
                            $c['showSignature'],
                            $c['requestReadReceipt']
                        ));

						if($c['saveOutbox'] == 1) {
                            $this->modules['DB']->queryParams('
                                INSERT INTO
                                    '.TBLPFX.'pms
                                SET
                                    "folderID"=1,
                                    "pmFromID"=$1,
                                    "pmToID"=$2,
                                    "pmIsRead"=1,
                                    "pmType"=1,
                                    "pmSubject"=$3,
                                    "pmMessageText"=$4,
                                    "pmSendTimestamp"=$5,
                                    "pmEnableBBCode"=$6,
                                    "pmEnableSmilies"=$7,
                                    "pmEnableHtmlCode"=$8,
                                    "pmShowSignature"=$9,
                                    "pmRequestReadReceipt"=$10
                            ', array(

                                $pmData['pmFromID'],
                                USERID,


                                $p['pmSubject'],
                                $p['pmMessageText'],
                                time(),
                                $c['enableBBCode'],
                                $c['enableSmilies'],
                                $c['enableHtmlCode'],
                                $c['showSignature'],
                                $c['requestReadReceipt']
                            ));
						}

						if($pmData['pmIsReplied'] != 1)
                            $this->modules['DB']->queryParams('UPDATE '.TBLPFX.'pms SET "pmIsReplied"=1 WHERE "pmID"=$1', array($pmID));

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
					$postPicsBox = Functions::getPostPicsBox();

					$this->modules['Template']->assign(array(
						'show'=>$show,
						'error'=>$error,
						'smiliesBox'=>$smiliesBox,
						'p'=>$p,
						'c'=>$c,
						'postPicsBox'=>$postPicsBox
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

				$this->modules['Template']->printPage('PrivateMessagesViewPM.tpl');
				break;

			case 'ManageFolders':
                $this->modules['DB']->queryParams('SELECT * FROM '.TBLPFX.'pms_folders WHERE "userID"=$1 ORDER BY "folderName" ASC', array(USERID));
				$foldersData = $this->modules['DB']->raw2Array();

				$this->modules['Template']->assign(array(
					'foldersData'=>$foldersData
				));

				$this->modules['Template']->printPage('PrivateMessagesManageFolders.tpl');
				break;

			case 'AddFolder':
				$p = Functions::getSGValues($_POST['p'],array('folderName'),'');

				$error = '';

				if(isset($_GET['doit'])) {
					if($p['folderName'] == '') $error = $this->modules['Language']->getString('error_invalid_folder_name');
					else {
                        $this->modules['DB']->queryParams('SELECT MAX("folderID") AS "maxFolderID" FROM '.TBLPFX.'pms_folders WHERE "userID"=$1', array(USERID));
						list($maxFolderID) = $this->modules['DB']->fetchArray();

						if($maxFolderID < 1) $maxFolderID = 1;

                        $this->modules['DB']->queryParams('
                            INSERT INTO
                                '.TBLPFX.'pms_folders
                            SET
                                "folderID"=$1,
                                "userID"=$2,
                                "folderName"=$3
                        ', array(
                            $maxFolderID+1,
                            USERID,
                            $p['folderName']
                        ));

						Functions::myHeader(INDEXFILE."?action=PrivateMessages&mode=ManageFolders&".MYSID);
					}
				}

				$this->modules['Template']->assign(array(
					'p'=>$p,
					'error'=>$error
				));

				$this->modules['Template']->printPage('PrivateMessagesAddFolder.tpl');
				break;

			case 'EditFolder':
				$folderID = isset($_GET['folderID']) ? intval($_GET['folderID']) : 0;

                $this->modules['DB']->queryParams('SELECT * FROM '.TBLPFX.'pms_folders WHERE "userID"=$1 AND "folderID"=$2', array(USERID, $folderID));
				($this->modules['DB']->getAffectedRows() != 1) ? die('Kann Daten nich laden: PM-Ordner') : $folderData = $this->modules['DB']->fetchArray();

				$p = Functions::getSGValues($_POST['p'],array('folderName'),'',$folderData);

				$error = '';

				if(isset($_GET['doit'])) {
					if($p['folderName'] == '') $error = $this->modules['Language']->getString('error_invalid_folder_name');
					else {
                        $this->modules['DB']->queryParams('
                            UPDATE
                                '.TBLPFX.'pms_folders
                            SET
                                "FolderName"=$1
                            WHERE
                                "UserID"=$2
                                AND "FolderID"=$3
                        ', array(
                            $p['folderName'],
                            USERID,
                            $folderID
                        ));

						Functions::myHeader(INDEXFILE."?action=PrivateMessages&mode=ManageFolders&".MYSID);
					}
				}

				$this->modules['Template']->assign(array(
					'folderID'=>$folderID,
					'p'=>$p,
					'error'=>$error
				));

				$this->modules['Template']->printPage('PrivateMessagesEditFolder.tpl');
				break;

			case 'DeleteFolder':
				$folderID = isset($_GET['folderID']) ? intval($_GET['folderID']) : 0;
				$moveFolderID = isset($_POST['moveFolderID']) ? intval($_POST['moveFolderID']) : -1;

                $this->modules['DB']->queryParams('SELECT * FROM '.TBLPFX.'pms_folders WHERE "userID"=$1 AND "folderID"=$2', array(USERID, $folderID));
				($this->modules['DB']->getAffectedRows() != 1) ? die('Kann Daten nich laden: PM-Ordner') : $folderData = $this->modules['DB']->fetchArray();

                $this->modules['DB']->queryParams('SELECT COUNT(*) AS "folderPMsCounter" FROM '.TBLPFX.'pms WHERE "pmToID"=$1 AND "folderID"=$2', array(USERID, $folderID));
				list($folderPMsCounter) = $this->modules['DB']->fetchArray();

				$foldersData = array($inboxFolderData,$outboxFolderData);
                $this->modules['DB']->queryParams('SELECT * FROM '.TBLPFX.'pms_folders WHERE "userID"=$1 AND "folderID"<>$2 ORDER BY "folderName" ASC', array(USERID, $folderID));
				$foldersData = array_merge($foldersData,$this->modules['DB']->raw2Array());

				$error = '';

				if(isset($_GET['doit']) || $folderPMsCounter == 0) {
					$validFolder = FALSE;
					foreach($foldersData AS $curFolder) {
						if($curFolder['folderID'] == $moveFolderID) {
							$validFolder = TRUE;
							break;
						}
					}

					if($moveFolderID != -1 && !$validFolder) $error = $this->modules['Language']->getString('Invalid_selection');
					else {
						if($moveFolderID == -1) $this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'pms WHERE "pmToID"=$1 AND "folderID"=$2', array(USERID, $folderID));
						else $this->modules['DB']->queryParams('UPDATE '.TBLPFX.'pms SET "folderID"=$1 WHERE "pmToID"=$2 AND "folderID"=$3', array($moveFolderID, USERID, $folderID));

                        $this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'pms_folders WHERE "userID"=$1 AND "folderID"=$2', array(USERID, $folderID));

						Functions::myHeader(INDEXFILE."?action=PrivateMessages&mode=ManageFolders&".MYSID);
					}
				}

				while(list($curKey) = each($foldersData))
					$foldersData[$curKey]['_moveText'] = sprintf($this->modules['Language']->getString('Move_messages_to'),$foldersData[$curKey]['folderName']);

				$this->modules['Template']->assign(array(
					'foldersData'=>$foldersData,
					'folderID'=>$folderID
				));

				$this->modules['Template']->printPage('PrivateMessagesDeleteFolder.tpl');
				break;

			case 'MovePMs':
				$pmIDs = (isset($_POST['pmIDs']) && is_array($_POST['pmIDs'])) ? $_POST['pmIDs'] : array();
				$targetFolderID = isset($_GET['targetFolderID']) ? intval($_GET['targetFolderID']) : 0;
				$returnFolderID = isset($_GET['returnFolderID']) ? intval($_GET['returnFolderID']) : 0;
				$returnPage = isset($_GET['returnPage']) ? intval($_GET['returnPage']) : 1;

				/**
				 * Check if target folder is valid
				 */
				if($targetFolderID != 0 && $targetFolderID != 1) {
                    $this->modules['DB']->queryParams('SELECT "folderID" FROM '.TBLPFX.'pms_folders WHERE "folderID"=$1 AND "userID"=$2', array($targetFolderID, USERID));
					if($this->modules['DB']->getAffectedRows() == 0) Functions::myHeader(INDEXFILE."?action=PrivateMessages&mode=ViewFolder&folderID=$returnFolderID&page=$returnPage&".MYSID);
				}

                $this->modules['DB']->queryParams('UPDATE '.TBLPFX.'pms SET "folderID"=$1 WHERE "pmID" IN $2 AND "pmToID"=$3', array($targetFolderID, $pmIDs, USERID));

				Functions::myHeader(INDEXFILE."?action=PrivateMessages&mode=ViewFolder&folderID=$returnFolderID&page=$returnPage&".MYSID);
				break;
		}
	}
}

?>