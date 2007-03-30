<?php

class Register extends ModuleTemplate {
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

	public function executeMe() {
		$this->Modules['Language']->addFile('Register');
		$this->Modules['Language']->addFile('BoardRules');

		// Zuerst einige Ueberpruefungen...
		if($this->Modules['Auth']->isLoggedIn() == 1) Functions::myHeader(INDEXFILE.'?'.MYSID);
		elseif($this->Modules['Config']->getValue('enable_registration') != 1) { // Ist die Registrierung ueberhaupt aktiviert?
			$this->Modules['Navbar']->addElement($this->Modules['Language']->getString('Registration_disabled'),INDEXFILE.'?Action=Register&amp'.MYSID);
			$this->Modules['PageParts']->printMessage('registration_disabled');
			exit;
		}
		elseif($this->Modules['Config']->getValue('maximum_registrations') != -1 && $this->Modules['Config']->getValue('maximum_registrations') <= Functions::getUsersCounter()) { // Gibt es eine Grenze an maximalen Registrierungen/ist diese ueberschritten?
			$this->Modules['Navbar']->addElement($this->Modules['Language']->getString('Too_many_registrations'),INDEXFILE.'?Action=Register&amp'.MYSID);
			$this->Modules['PageParts']->printMessage('too_many_registrations');
			exit;
		}

		$this->Modules['Navbar']->addElement($this->Modules['Language']->getString('Register'),INDEXFILE."?Action=Register&amp;".MYSID);

		switch(@$_GET['Mode']) {
			default:
				if(isset($_GET['Doit'])) {
					Functions::myHeader(INDEXFILE.'?Action=Register&Mode=RegisterForm&'.MYSID);
				}

				$this->Modules['Navbar']->addElement($this->Modules['Language']->getString('Board_rules'),INDEXFILE."?Action=Register&amp;".MYSID);

				$this->Modules['PageParts']->printPage('RegisterBoardRules.tpl');
				break;

			case 'RegisterForm':
				$Error = '';

				//
				// Die Profilfelder laden, die bei der Registrierung angezeigt werden sollen
				//
				$this->Modules['DB']->query("SELECT * FROM ".TBLPFX."profile_fields WHERE FieldShowRegistration='1'");
				$ProfileFields = $this->Modules['DB']->Raw2Array();
				$FieldsCounter = count($ProfileFields);

				//
				// Jetzt werden eventuelle $_POST-Daten uebernommen
				//
				$p = array();
				foreach($ProfileFields AS $curField) {
					switch($curField['FieldType']) {
						case PROFILE_FIELD_TYPE_TEXT        : $p['FieldsData'][$curField['FieldID']] = isset($_POST['p']['FieldsData'][$curField['FieldID']]) ? $_POST['p']['FieldsData'][$curField['FieldID']] : ''; break;
						case PROFILE_FIELD_TYPE_TEXTAREA    : $p['FieldsData'][$curField['FieldID']] = isset($_POST['p']['FieldsData'][$curField['FieldID']]) ? $_POST['p']['FieldsData'][$curField['FieldID']] : ''; break;
						case PROFILE_FIELD_TYPE_SELECTSINGLE: $p['FieldsData'][$curField['FieldID']] = isset($_POST['p']['FieldsData'][$curField['FieldID']]) ? intval($_POST['p']['FieldsData'][$curField['FieldID']]) : ''; break;
						case PROFILE_FIELD_TYPE_SELECTMULTI : $p['FieldsData'][$curField['FieldID']] = (isset($_POST['p']['FieldsData'][$curField['FieldID']]) == TRUE && is_array($_POST['p']['FieldsData'][$curField['FieldID']]) == TRUE) ? $_POST['p']['FieldsData'][$curField['FieldID']] : array(); break;
					}
				}

				$p = array_merge($p,Functions::getSGValues($_POST['p'],array('UserName','UserEmail','UserEmailConfirmation','UserPassword','UserPasswordConfirmation')));

				//
				// Falls das Formular abgeschickt wurde
				//
				if(isset($_GET['Doit'])) {
					$FieldIsMissing = FALSE;
					foreach($ProfileFields AS $curField) {
						if($curField['FieldIsRequired'] == 1 && ($curField['FieldType'] != PROFILE_FIELD_TYPE_SELECTMULTI && $p['FieldsData'][$curField['FieldID']] === '' || $curField['FieldType'] == PROFILE_FIELD_TYPE_SELECTMULTI && count($p['FieldsData'][$curField['FieldID']]) == 0)) {
							$FieldIsMissing = TRUE;
							break;
						}
					}

					$FieldIsInvalid = FALSE;
					foreach($ProfileFields AS $curField) {
						if(($curField['FieldType'] == PROFILE_FIELD_TYPE_TEXT || $curField['FieldType'] == PROFILE_FIELD_TYPE_TEXTAREA) && $curField['FieldRegexVerification'] != '' && !preg_match($curField['FieldRegexVerification'],$p['FieldsData'][$curField['FieldID']])) {
							$FieldIsInvalid = TRUE;
							break;
						}
					}

					if($p['UserName'] == '' || !Functions::verifyUserName($p['UserName'])) $Error = $this->Modules['Language']->getString('error_bad_nick'); // Hat der Nick ein falsches Format?
					elseif(!Functions::unifyUserName($p['UserName'])) $Error = $this->Modules['Language']->getString('error_nick_already_in_use'); // Wird der Nick schon verwendet?
					elseif($p['UserEmail'] == '' || !Functions::verifyEmail($p['UserEmail'])) $Error = $this->Modules['Language']->getString('error_bad_email'); // Hat die Emailadresse das richtige Format?
					elseif(!Functions::unifyEmail($p['UserEmail'])) $Error = $this->Modules['Language']->getString('error_emailaddress_already_in_use');
					elseif($p['UserEmail'] != $p['UserEmailConfirmation']) $Error = $this->Modules['Language']->getString('error_emails_no_match'); // Stimmen die Emailadressen ueberein?
					elseif(trim($p['UserPassword']) == '' && ($this->Modules['Config']->getValue('verify_email_address') != 1 || $this->Modules['Config']->getValue('enable_email_functions') != 1)) $Error = $this->Modules['Language']->getString('error_no_pw'); // Wurde ein Passwort angegeben?
					elseif($p['UserPassword'] != $p['UserPasswordConfirmation'] && ($this->Modules['Config']->getValue('verify_email_address') != 1 || $this->Modules['Config']->getValue('enable_email_functions') != 1)) $Error = $this->Modules['Language']->getString('error_pws_no_match'); // Stimmen die Passworter ueberein?
					elseif($FieldIsMissing) $Error = $this->Modules['Language']->getString('error_required_fields_missing'); // Fehlt ein benoetigtes Feld?
					elseif($FieldIsInvalid) $Error = $this->Modules['Language']->getString('error_bad_information'); // Hat ein Feld ein falsches Format?
					else {
						// Falls noch kein User existiert, wird man automatisch als Admin registriert
						$UserIsAdmin = (Functions::getUsersCounter() == 0) ? 1 : 0;

						// Im Folgenden wird ueberprueft, ob der User Admin ist. Ist er kein Admin,
						// wird ueberprueft, ob er seine Emailadresse irgendwie verifizieren muss
						$UserStatus = 1; // bedeutet, der User ist freigeschaltet
						$UserHash = '';
						if($UserIsAdmin != 1) {
							if($this->Modules['Config']->getValue('verify_email_address') == 1 && $this->Modules['Config']->getValue('enable_email_functions') == 1)
								$p['UserPassword'] = Functions::getRandomString(8);
							elseif($this->Modules['Config']->getValue('verify_email_address') == 2 && $this->Modules['Config']->getValue('enable_email_functions') == 1) {
								$UserStatus = USER_STATUS_INACTIVE; // bedeutet, der User ist noch _nicht_ freigeschaltet
								$UserHash = Functions::getRandomString(32,TRUE); // ist spaeter der Verifizierungscode
							}
						}

						$UserPasswordSalt = Functions::getRandomString(10);
						$UserPasswordEncrypted = Functions::getSaltedHash($p['UserPassword'],$UserPasswordSalt); // Passwort fuer Datenbank verschluesseln

						/*$this->Modules['DB']->query("
							INSERT INTO
								".TBLPFX."users
							SET
								UserStatus='".$UserStatus."',
								UserIsAdmin='".$UserIsAdmin."',
								UserHash='".$UserHash."',
								UserNick='".$p['UserName']."',
								UserEmail='".$p['UserEmail']."',
								UserPassword='".$UserPasswordEncrypted."',
								UserPasswordSalt='".$UserPasswordSalt."',
								UserRegistrationTimestamp='".time()."',
								UserTimeZone='".$this->Modules['Config']->getValue('standard_tz')."'
						");*/

						$UserID = $this->Modules['DB']->getInsertID();

						/*foreach($ProfileFields AS $curField) {
							$curValue = ($curField['FieldType'] == PROFILE_FIELD_TYPE_SELECTMULTI) ? implode(',',$p['FieldsData'][$curField['FieldID']]) : $p['FieldsData'][$curField['FieldID']];
							$this->Modules['DB']->query("
								INSERT INTO
									".TBLPFX."profile_fields_data
								SET
									FieldID='".$curField['FieldID']."',
									UserID='".$UserID."'
									FieldValue='".$curValue."'
							");
						}*/

						$_SESSION['LastPlaceUrl'] = INDEXFILE.'?'.MYSID;

						echo nl2br($this->Modules['Template']->fetch($this->Modules['Language']->getLD().'mails/RegistrationWelcome.mail'));  exit;

						if($UserIsAdmin != 1 && $this->Modules['Config']->getValue('enable_email_functions') == 1) {
							$this->Modules['Template']->assign(array(
								'UserNick'=>$p['UserName'],
								'UserID'=>$UserID,
								'UserEmail'=>$p['UserEmail'],
								'UserPassword'=>$p['UserPassword']
							));
							Functions::myMail(
								$this->Modules['Config']->getValue('board_name').' <'.$this->Modules['Config']->getValue('board_email_address').'>',
								$p['UserEmail'],
								sprintf($this->Modules['Language']->getString('email_subject_welcome'),$this->Modules['Config']->getValue('board_name')),
								$this->Modules['Template']->fetch($this->Modules['Language']->getLD().'mails/RegistrationWelcome.mail')
							);


							if($CONFIG['verify_email_address'] == 2) {
								$activation_link = $CONFIG['board_address'].'/index.php?action=activateaccount&account_id='.$p_user_nick.'&activation_code='.$p_user_hash.'&doit=1';
								$email_tpl->loadTpl($LANGUAGE_PATH.'/emails/email_account_activation.tpl');
								mymail('"'.$CONFIG['board_name'].'" <'.$CONFIG['board_email_address'].'>',$p_user_email,sprintf($LNG['email_subject_account_activation'],$CONFIG['board_name']),$email_tpl->parseCode());
							}
						}

						//update_latest_user($new_user_id,$p_user_nick);

						$this->Modules['Navbar']->addElement($this->Modules['Language']->getString('Registration_successful'),INDEXFILE."?Action=Register&amp;".MYSID);

						$this->Modules['PageParts']->printMessage('registration_successful',array('login'));
						include_once('pheader.php');
						show_message($LNG['Registration_successful'],$LNG['message_registration_successful'].'<br />'.sprintf($LNG['click_here_login'],"<a href=\"index.php?action=login&amp;$MYSID\">",'</a>'));
						include_once('ptail.php'); exit;
					}
				}

				//
				// Die Spezial-Profilfelder
				//
				if($FieldsCounter > 0) {
					$GroupsData = array(
						array('GroupName'=>$this->Modules['Language']->getString('Required_information'),'GroupType'=>1,'GroupFields'=>array()),
						array('GroupName'=>$this->Modules['Language']->getString('Other_information'),'GroupType'=>0,'GroupFields'=>array())
					);

					foreach($ProfileFields AS $curField) {
						switch($curField['FieldType']) {
							case PROFILE_FIELD_TYPE_TEXT:
							case PROFILE_FIELD_TYPE_TEXTAREA:
								$curField['_FieldValue'] = Functions::HTMLSpecialChars(Functions::StripSlashes($p['FieldsData'][$curField['FieldID']]));
								break;

							case PROFILE_FIELD_TYPE_SELECTSINGLE:
							case PROFILE_FIELD_TYPE_SELECTMULTI:
								$curField['_FieldSelectedIDs'] = $p['ProfileFields'][$curField['FieldID']];
								$curField['_FieldOptions'] = unserialize($curField['FieldData']);
								break;
						}
						if($curField['FieldIsRequired'] == 0) $GroupsData[1]['GroupFields'][] = $curField;
						else $GroupsData[0]['GroupFields'][] = $curField;
					}
				}

				$this->Modules['Template']->assign(array(
					'Error'=>$Error,
					'p'=>$p,
					'GroupsData'=>$GroupsData,
					'FieldsCounter'=>$FieldsCounter
				));
				$this->Modules['PageParts']->printPage('RegisterRegisterForm.tpl');
			break;
		}
	}
}

?>