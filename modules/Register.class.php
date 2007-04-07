<?php

class Register extends ModuleTemplate {
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
		$this->modules['Language']->addFile('Register');
		$this->modules['Language']->addFile('BoardRules');

		// Zuerst einige Ueberpruefungen...
		if($this->modules['Auth']->isLoggedIn() == 1) Functions::myHeader(INDEXFILE.'?'.MYSID);
		elseif($this->modules['Config']->getValue('enable_registration') != 1) {
			$this->modules['Navbar']->addElement($this->modules['Language']->getString('Registration_disabled'),INDEXFILE.'?Action=Register&amp'.MYSID);
			$this->modules['PageParts']->printMessage('registration_disabled');
			exit;
		}
		elseif($this->modules['Config']->getValue('maximum_registrations') != -1 && $this->modules['Config']->getValue('maximum_registrations') <= Functions::getUsersCounter()) { // Gibt es eine Grenze an maximalen Registrierungen/ist diese ueberschritten?
			$this->modules['Navbar']->addElement($this->modules['Language']->getString('Too_many_registrations'),INDEXFILE.'?Action=Register&amp'.MYSID);
			$this->modules['PageParts']->printMessage('too_many_registrations');
			exit;
		}

		$this->modules['Navbar']->addElement($this->modules['Language']->getString('Register'),INDEXFILE."?Action=Register&amp;".MYSID);

		switch(@$_GET['Mode']) {
			default:
				if(isset($_GET['Doit'])) {
					Functions::myHeader(INDEXFILE.'?Action=Register&Mode=RegisterForm&'.MYSID);
				}

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('Board_rules'),INDEXFILE."?Action=Register&amp;".MYSID);

				$this->modules['PageParts']->printPage('RegisterBoardRules.tpl');
				break;

			case 'RegisterForm':
				$error = '';

				//
				// Die Profilfelder laden, die bei der Registrierung angezeigt werden sollen
				//
				$this->modules['DB']->query("SELECT * FROM ".TBLPFX."profile_fields WHERE FieldShowRegistration='1'");
				$profileFields = $this->modules['DB']->raw2Array();
				$fieldsCounter = count($profileFields);

				//
				// Jetzt werden eventuelle $_POST-Daten uebernommen
				//
				$p = array();
				foreach($profileFields AS $curField) {
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
				if(isset($_GET['doit'])) {
					$fieldIsMissing = FALSE;
					foreach($profileFields AS $curField) {
						if($curField['FieldIsRequired'] == 1 && ($curField['FieldType'] != PROFILE_FIELD_TYPE_SELECTMULTI && $p['FieldsData'][$curField['FieldID']] === '' || $curField['FieldType'] == PROFILE_FIELD_TYPE_SELECTMULTI && count($p['FieldsData'][$curField['FieldID']]) == 0)) {
							$fieldIsMissing = TRUE;
							break;
						}
					}

					$fieldIsInvalid = FALSE;
					foreach($profileFields AS $curField) {
						if(($curField['FieldType'] == PROFILE_FIELD_TYPE_TEXT || $curField['FieldType'] == PROFILE_FIELD_TYPE_TEXTAREA) && $curField['FieldRegexVerification'] != '' && !preg_match($curField['FieldRegexVerification'],$p['FieldsData'][$curField['FieldID']])) {
							$fieldIsInvalid = TRUE;
							break;
						}
					}

					if($p['UserName'] == '' || !Functions::verifyUserName($p['UserName'])) $error = $this->modules['Language']->getString('error_bad_nick'); // Hat der Nick ein falsches Format?
					elseif(!Functions::unifyUserName($p['UserName'])) $error = $this->modules['Language']->getString('error_nick_already_in_use'); // Wird der Nick schon verwendet?
					elseif($p['UserEmail'] == '' || !Functions::verifyEmail($p['UserEmail'])) $error = $this->modules['Language']->getString('error_bad_email'); // Hat die Emailadresse das richtige Format?
					elseif(!Functions::unifyEmail($p['UserEmail'])) $error = $this->modules['Language']->getString('error_emailaddress_already_in_use');
					elseif($p['UserEmail'] != $p['UserEmailConfirmation']) $error = $this->modules['Language']->getString('error_emails_no_match'); // Stimmen die Emailadressen ueberein?
					elseif(trim($p['UserPassword']) == '' && ($this->modules['Config']->getValue('verify_email_address') != 1 || $this->modules['Config']->getValue('enable_email_functions') != 1)) $error = $this->modules['Language']->getString('error_no_pw'); // Wurde ein Passwort angegeben?
					elseif($p['UserPassword'] != $p['UserPasswordConfirmation'] && ($this->modules['Config']->getValue('verify_email_address') != 1 || $this->modules['Config']->getValue('enable_email_functions') != 1)) $error = $this->modules['Language']->getString('error_pws_no_match'); // Stimmen die Passworter ueberein?
					elseif($fieldIsMissing) $error = $this->modules['Language']->getString('error_required_fields_missing'); // Fehlt ein benoetigtes Feld?
					elseif($fieldIsInvalid) $error = $this->modules['Language']->getString('error_bad_information'); // Hat ein Feld ein falsches Format?
					else {
						// Falls noch kein User existiert, wird man automatisch als Admin registriert
						$userIsAdmin = (Functions::getUsersCounter() == 0) ? 1 : 0;

						// Im Folgenden wird ueberprueft, ob der User Admin ist. Ist er kein Admin,
						// wird ueberprueft, ob er seine Emailadresse irgendwie verifizieren muss
						$userStatus = 1; // bedeutet, der User ist freigeschaltet
						$userHash = '';
						if($userIsAdmin != 1) {
							if($this->modules['Config']->getValue('verify_email_address') == 1 && $this->modules['Config']->getValue('enable_email_functions') == 1)
								$p['UserPassword'] = Functions::getRandomString(8);
							elseif($this->modules['Config']->getValue('verify_email_address') == 2 && $this->modules['Config']->getValue('enable_email_functions') == 1) {
								$userStatus = USER_STATUS_INACTIVE; // bedeutet, der User ist noch _nicht_ freigeschaltet
								$userHash = Functions::getRandomString(32,TRUE); // ist spaeter der Verifizierungscode
							}
						}

						$userPasswordSalt = Functions::getRandomString(10);
						$userPasswordEncrypted = Functions::getSaltedHash($p['UserPassword'],$userPasswordSalt); // Passwort fuer Datenbank verschluesseln

						/*$this->modules['DB']->query("
							INSERT INTO
								".TBLPFX."users
							SET
								UserStatus='".$userStatus."',
								UserIsAdmin='".$userIsAdmin."',
								UserHash='".$userHash."',
								UserNick='".$p['UserName']."',
								UserEmail='".$p['UserEmail']."',
								UserPassword='".$userPasswordEncrypted."',
								UserPasswordSalt='".$userPasswordSalt."',
								UserRegistrationTimestamp='".time()."',
								UserTimeZone='".$this->modules['Config']->getValue('standard_tz')."'
						");*/

						$userID = $this->modules['DB']->getInsertID();

						/*foreach($profileFields AS $curField) {
							$curValue = ($curField['FieldType'] == PROFILE_FIELD_TYPE_SELECTMULTI) ? implode(',',$p['FieldsData'][$curField['FieldID']]) : $p['FieldsData'][$curField['FieldID']];
							$this->modules['DB']->query("
								INSERT INTO
									".TBLPFX."profile_fields_data
								SET
									FieldID='".$curField['FieldID']."',
									UserID='".$userID."'
									FieldValue='".$curValue."'
							");
						}*/

						$_SESSION['LastPlaceUrl'] = INDEXFILE.'?'.MYSID;

						echo nl2br($this->modules['Template']->fetch($this->modules['Language']->getLD().'mails/RegistrationWelcome.mail'));  exit;

						if($userIsAdmin != 1 && $this->modules['Config']->getValue('enable_email_functions') == 1) {
							$this->modules['Template']->assign(array(
								'UserNick'=>$p['UserName'],
								'UserID'=>$userID,
								'UserEmail'=>$p['UserEmail'],
								'UserPassword'=>$p['UserPassword']
							));
							Functions::myMail(
								$this->modules['Config']->getValue('board_name').' <'.$this->modules['Config']->getValue('board_email_address').'>',
								$p['UserEmail'],
								sprintf($this->modules['Language']->getString('email_subject_welcome'),$this->modules['Config']->getValue('board_name')),
								$this->modules['Template']->fetch($this->modules['Language']->getLD().'mails/RegistrationWelcome.mail')
							);


							if($cONFIG['verify_email_address'] == 2) {
								$activation_link = $cONFIG['board_address'].'/index.php?action=activateaccount&account_id='.$p_user_nick.'&activation_code='.$p_user_hash.'&doit=1';
								$email_tpl->loadTpl($lANGUAGE_PATH.'/emails/email_account_activation.tpl');
								mymail('"'.$cONFIG['board_name'].'" <'.$cONFIG['board_email_address'].'>',$p_user_email,sprintf($lNG['email_subject_account_activation'],$cONFIG['board_name']),$email_tpl->parseCode());
							}
						}

						//update_latest_user($new_user_id,$p_user_nick);

						$this->modules['Navbar']->addElement($this->modules['Language']->getString('Registration_successful'),INDEXFILE."?Action=Register&amp;".MYSID);

						$this->modules['PageParts']->printMessage('registration_successful',array('login'));
						include_once('pheader.php');
						show_message($lNG['Registration_successful'],$lNG['message_registration_successful'].'<br />'.sprintf($lNG['click_here_login'],"<a href=\"index.php?action=login&amp;$mYSID\">",'</a>'));
						include_once('ptail.php'); exit;
					}
				}

				//
				// Die Spezial-Profilfelder
				//
				if($fieldsCounter > 0) {
					$groupsData = array(
						array('GroupName'=>$this->modules['Language']->getString('Required_information'),'GroupType'=>1,'GroupFields'=>array()),
						array('GroupName'=>$this->modules['Language']->getString('Other_information'),'GroupType'=>0,'GroupFields'=>array())
					);

					foreach($profileFields AS $curField) {
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
						if($curField['FieldIsRequired'] == 0) $groupsData[1]['GroupFields'][] = $curField;
						else $groupsData[0]['GroupFields'][] = $curField;
					}
				}

				$this->modules['Template']->assign(array(
					'Error'=>$error,
					'p'=>$p,
					'GroupsData'=>$groupsData,
					'FieldsCounter'=>$fieldsCounter
				));
				$this->modules['PageParts']->printPage('RegisterRegisterForm.tpl');
			break;
		}
	}
}

?>