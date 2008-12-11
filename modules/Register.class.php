<?php
class Register extends ModuleTemplate {
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
		$this->modules['Language']->addFile('Register');
		$this->modules['Language']->addFile('BoardRules');

		// Zuerst einige Ueberpruefungen...
		if($this->modules['Auth']->isLoggedIn() == 1) Functions::myHeader(INDEXFILE.'?'.MYSID);
		elseif($this->modules['Config']->getValue('enable_registration') != 1) {
			FuncMisc::printMessage('registration_disabled');
			exit;
		}
		// Gibt es eine Grenze an maximalen Registrierungen/ist diese ueberschritten?
		elseif($this->modules['Config']->getValue('maximum_registrations') != -1 && $this->modules['Config']->getValue('maximum_registrations') <= FuncUsers::getUsersCounter()) {
			FuncMisc::printMessage('too_many_registrations');
			exit;
		}

		$this->modules['Navbar']->addElement($this->modules['Language']->getString('register'),INDEXFILE.'?action=Register&amp;'.MYSID);

		switch(@$_GET['mode']) {
			default:
				$c = Functions::getSGValues($_POST['c'], array('acceptRules'),0);

				$errors = array();

				if(isset($_GET['doit'])) {
					$c = Functions::getSGValues($_POST['c'], array('acceptRules'),0);

					if($this->modules['Config']->getValue('require_accept_boardrules') == 1 && $c['acceptRules'] != 1) $errors[] = $this->modules['Language']->getString('error_accept_board_rules');;

					if(count($errors) == 0) {
						Functions::myHeader(INDEXFILE.'?action=Register&mode=RegisterForm&'.MYSID);
					}
				}

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('board_rules'),INDEXFILE.'?action=Register&amp;'.MYSID);

				$this->modules['Template']->assign('errors',$errors);

				$this->modules['Template']->printPage('RegisterBoardRules.tpl');
				break;

			case 'RegisterForm':
				$error = '';

				//
				// Die Profilfelder laden, die bei der Registrierung angezeigt werden sollen
				//
				$this->modules['DB']->query('SELECT * FROM '.TBLPFX.'profile_fields WHERE "fieldShowRegistration"=1');
				$profileFields = $this->modules['DB']->raw2Array();
				$fieldsCounter = count($profileFields);

				//
				// Jetzt werden eventuelle $_POST-Daten uebernommen
				//
				$p = array();
				foreach($profileFields AS $curField) {
					switch($curField['fieldType']) {
						case PROFILE_FIELD_TYPE_TEXT        : $p['fieldsData'][$curField['fieldID']] = isset($_POST['p']['fieldsData'][$curField['fieldID']]) ? $_POST['p']['fieldsData'][$curField['fieldID']] : ''; break;
						case PROFILE_FIELD_TYPE_TEXTAREA    : $p['fieldsData'][$curField['fieldID']] = isset($_POST['p']['fieldsData'][$curField['fieldID']]) ? $_POST['p']['fieldsData'][$curField['fieldID']] : ''; break;
						case PROFILE_FIELD_TYPE_SELECTSINGLE: $p['fieldsData'][$curField['fieldID']] = isset($_POST['p']['fieldsData'][$curField['fieldID']]) ? intval($_POST['p']['fieldsData'][$curField['fieldID']]) : ''; break;
						case PROFILE_FIELD_TYPE_SELECTMULTI : $p['fieldsData'][$curField['fieldID']] = (isset($_POST['p']['fieldsData'][$curField['fieldID']]) && is_array($_POST['p']['fieldsData'][$curField['fieldID']])) ? $_POST['p']['fieldsData'][$curField['fieldID']] : array(); break;
					}
				}

				$p = array_merge($p,Functions::getSGValues($_POST['p'],array('userName','userEmailAddress','userEmailAddressConfirmation','userPassword','userPasswordConfirmation')));

				if(isset($_GET['doit'])) {
					$fieldIsMissing = FALSE;
					foreach($profileFields AS $curField) {
						if($curField['fieldIsRequired'] == 1 && ($curField['fieldType'] != PROFILE_FIELD_TYPE_SELECTMULTI && $p['fieldsData'][$curField['fieldID']] === '' || $curField['fieldType'] == PROFILE_FIELD_TYPE_SELECTMULTI && count($p['fieldsData'][$curField['fieldID']]) == 0)) {
							$fieldIsMissing = TRUE;
							break;
						}
					}

					$fieldIsInvalid = FALSE;
					foreach($profileFields AS $curField) {
						if(($curField['fieldType'] == PROFILE_FIELD_TYPE_TEXT || $curField['fieldType'] == PROFILE_FIELD_TYPE_TEXTAREA) && $curField['fieldRegexVerification'] != '' && $p['fieldsData'][$curField['fieldID']] != '' && !preg_match($curField['fieldRegexVerification'],$p['fieldsData'][$curField['fieldID']])) {
							$fieldIsInvalid = TRUE;
							break;
						}
					}

					if($p['userName'] == '' || !Functions::verifyUserName($p['userName'])) $error = $this->modules['Language']->getString('error_bad_nick');
					elseif(!Functions::unifyUserName($p['userName'])) $error = $this->modules['Language']->getString('error_nick_already_in_use');
					elseif($p['userEmailAddress'] == '' || !Functions::verifyEmailAddress($p['userEmailAddress'])) $error = $this->modules['Language']->getString('error_bad_email');
					elseif(!Functions::unifyEmailAddress($p['userEmailAddress'])) $error = $this->modules['Language']->getString('error_emailaddress_already_in_use');
					elseif($p['userEmailAddress'] != $p['userEmailAddressConfirmation']) $error = $this->modules['Language']->getString('error_emails_no_match');
					elseif(trim($p['userPassword']) == '' && ($this->modules['Config']->getValue('verify_email_address') != 1 || $this->modules['Config']->getValue('enable_email_functions') != 1)) $error = $this->modules['Language']->getString('error_no_pw');
					elseif($p['userPassword'] != $p['userPasswordConfirmation'] && ($this->modules['Config']->getValue('verify_email_address') != 1 || $this->modules['Config']->getValue('enable_email_functions') != 1)) $error = $this->modules['Language']->getString('error_pws_no_match');
					elseif($fieldIsMissing) $error = $this->modules['Language']->getString('error_required_fields_missing');
					elseif($fieldIsInvalid) $error = $this->modules['Language']->getString('error_bad_information');
					else {
						// Falls noch kein User existiert, wird man automatisch als Admin registriert
						$userIsAdmin = (FuncUsers::getUsersCounter() == 0) ? 1 : 0;

						// Im Folgenden wird ueberprueft, ob der User Admin ist. Ist er kein Admin,
						// wird ueberprueft, ob er seine Emailadresse irgendwie verifizieren muss
						$userIsActivated = 1; // bedeutet, der User ist freigeschaltet
						$userHash = '';
						if($userIsAdmin != 1) {
							if($this->modules['Config']->getValue('verify_email_address') == 1 && $this->modules['Config']->getValue('enable_email_functions') == 1)
								$p['userPassword'] = Functions::getRandomString(8);
							elseif($this->modules['Config']->getValue('verify_email_address') == 2 && $this->modules['Config']->getValue('enable_email_functions') == 1) {
								$userIsActivated = 0; // bedeutet, der User ist noch _nicht_ freigeschaltet
								$userHash = Functions::getRandomString(32,TRUE); // ist spaeter der Verifizierungscode
							}
						}

						$userPasswordSalt = Functions::getRandomString(10);
						$userPasswordEncrypted = Functions::getSaltedHash($p['userPassword'],$userPasswordSalt); // Passwort fuer Datenbank verschluesseln

						$this->modules['DB']->queryParams('
							INSERT INTO '.TBLPFX.'users SET
								"userIsActivated"=$1,
								"userIsAdmin"=$2,
								"userHash"=$3,
								"userNick"=$4,
								"userEmailAddress"=$5,
								"userPassword"=$6,
								"userPasswordSalt"=$7,
								"userRegistrationTimestamp"=$8,
								"userTimeZone"=$9,
								"userNotifyNewPM"=1
						',array(
							$userIsActivated,
							$userIsAdmin,
							$userHash,
							$p['userName'],
							$p['userEmailAddress'],
							$userPasswordEncrypted,
							$userPasswordSalt,
							time(),
							$this->modules['Config']->getValue('standard_tz')
						));

						$userID = $this->modules['DB']->getInsertID();

						foreach($profileFields AS $curField) {
							$curValue = ($curField['fieldType'] == PROFILE_FIELD_TYPE_SELECTMULTI) ? implode(',',$p['fieldsData'][$curField['fieldID']]) : $p['fieldsData'][$curField['fieldID']];
							$this->modules['DB']->queryParams('
								INSERT INTO '.TBLPFX.'profile_fields_data SET
									"fieldID"=$1,
									"userID"=$2,
									"fieldValue"=$3
							',array(
								$curField['fieldID'],
								$userID,
								$curValue
							));
						}

						$_SESSION['lastPlaceUrl'] = INDEXFILE.'?'.MYSID;

						if($userIsAdmin != 1 && $this->modules['Config']->getValue('enable_email_functions') == 1) {
							$this->modules['Template']->assign(array(
								'userNick'=>$p['userName'],
								'userID'=>$userID,
								'userEmailAddress'=>$p['userEmailAddress'],
								'userPassword'=>$p['userPassword']
							));
							Functions::myMail(
								$this->modules['Config']->getValue('board_name').' <'.$this->modules['Config']->getValue('board_email_address').'>',
								$p['userEmailAddress'],
								sprintf($this->modules['Language']->getString('email_subject_welcome'),$this->modules['Config']->getValue('board_name')),
								$this->modules['Template']->fetch('RegistrationWelcome.mail',$this->modules['Language']->getLD().'mails')
							);


							if($this->modules['Config']->getValue('verify_email_address') == 2) {
								$this->modules['Template']->assign(array(
									'userNick'=>$p['userName'],
									'activationLink'=>$this->modules['Config']->getValue('board_address').'/'.INDEXFILE.'?action=Login&mode=ActivateAccount&accountID='.$p['userName'].'&activationCode='.$userHash.'&doit=1',
									'activationCode'=>$userHash
								));
								Functions::myMail(
									$this->modules['Config']->getValue('board_name').' <'.$this->modules['Config']->getValue('board_email_address').'>',
									$p['userEmailAddress'],
									sprintf($this->modules['Language']->getString('email_subject_account_activation'),$this->modules['Config']->getValue('board_name')),
									$this->modules['Template']->fetch('RegistrationAccountVerification.mail',$this->modules['Language']->getLD().'mails')
								);
							}
						}

						FuncUsers::updateLatestUser($userID,$p['userName']);
						FuncUsers::updateUsersCounter();

						switch($this->modules['Config']->getValue('verify_email_address')) {
							case '0': FuncMisc::printMessage('registration_successful',array(sprintf($this->modules['Language']->getString('message_link_click_here_login'),'<a href="'.INDEXFILE.'?action=Login&amp;'.MYSID.'">','</a>'))); break;
							case '1': FuncMisc::printMessage('registration_successful_verification_password',array(sprintf($this->modules['Language']->getString('message_link_click_here_login'),'<a href="'.INDEXFILE.'?action=Login&amp;'.MYSID.'">','</a>'))); break;
							case '2': FuncMisc::printMessage('registration_successful_verification_email',array(sprintf($this->modules['Language']->getString('message_link_click_here_login'),'<a href="'.INDEXFILE.'?action=Login&amp;'.MYSID.'">','</a>'))); break;
						}
						exit;
					}
				}

				//
				// Die Spezial-Profilfelder
				//
				$groupsData = array();
				if($fieldsCounter > 0) {
					$groupsData = array(
						array('groupName'=>$this->modules['Language']->getString('required_information'),'groupType'=>1,'groupFields'=>array()),
						array('groupName'=>$this->modules['Language']->getString('other_information'),'groupType'=>0,'groupFields'=>array())
					);

					foreach($profileFields AS $curField) {
						switch($curField['fieldType']) {
							case PROFILE_FIELD_TYPE_TEXT:
							case PROFILE_FIELD_TYPE_TEXTAREA:
								$curField['_fieldValue'] = Functions::HTMLSpecialChars($p['fieldsData'][$curField['fieldID']]);
								break;

							case PROFILE_FIELD_TYPE_SELECTSINGLE:
							case PROFILE_FIELD_TYPE_SELECTMULTI:
								$curField['_fieldSelectedIDs'] = $p['fieldsData'][$curField['fieldID']];
								$curField['_fieldOptions'] = unserialize($curField['fieldData']);
								break;
						}
						if($curField['fieldIsRequired'] == 0) $groupsData[1]['groupFields'][] = $curField;
						else $groupsData[0]['groupFields'][] = $curField;
					}
				}

				$this->modules['Template']->assign(array(
					'error'=>$error,
					'p'=>$p,
					'groupsData'=>$groupsData,
					'fieldsCounter'=>$fieldsCounter
				));
				$this->modules['Template']->printPage('RegisterRegisterForm.tpl');
			break;
		}
	}
}