<?php

class EditProfile extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'Config',
		'Constants',
		'DB',
		'Language',
		'Navbar',
		'Template'
	);

	public function printHeader() {
		$this->modules['Template']->display('EditProfileHeader.tpl');
	}

	public function printTail() {
		$this->modules['Template']->display('EditProfileTail.tpl');
	}

	public function initializeMe() {
		$this->modules['Template']->registerSubFrame(array($this,'printHeader'),array($this,'printTail'));
	}

	public function executeMe() {
		if($this->modules['Auth']->isLoggedIn() != 1) die('Kein Zugriff: Nicht eingeloggt');

		$this->modules['Language']->addFile('EditProfile');

		$this->modules['Navbar']->addElement($this->modules['Language']->getString('User_administration'),INDEXFILE."?action=EditProfile&amp;".MYSID);

		switch(@$_GET['mode']) {
			default:
				$p = Functions::getSGValues($_POST['p'],array('userEmailAddress','userSignature'),'',$this->modules['Auth']->getUserData());
				$p = array_merge($p,Functions::getSGValues($_POST['p'],array('userOldPassword','userNewPassword','userNewPasswordConfirmation'),''));

				$error = '';

				if(isset($_GET['doit'])) {
					if(!Functions::verifyEmailAddress($p['userEmailAddress'])) $error = $this->modules['Language']->getString('error_bad_email');
					elseif(trim($p['userNewPassword']) != '' && Functions::getSaltedHash($p['userOldPassword'],$this->modules['Auth']->getValue('userPasswordSalt')) != $this->modules['Auth']->getValue('user_pw')) $error = $this->modules['Language']->getString('error_wrong_password');
					elseif(trim($p['userNewPassword']) != '' && $p['userNewPassword'] != $p['userNewPasswordConfirmation']) $error = $this->modules['Language']->getString('error_pws_no_match');
					else {
                        $this->modules['DB']->queryParams('
                            UPDATE
                                '.TBLPFX.'users
                            SET
                                "userEmailAddress"=$1,
                                "userSignature"=$2
                            WHERE
                                "userID"=$3
                        ', array(
                            $p['userEmailAddress'],
                            $p['userSignature'],
                            USERID
                        ));

						if(trim($p['userNewPassword']) != '') {
							$newPasswordSalt = Functions::getRandomString(10);
							$newPasswordEncrypted = Functions::getSaltedHash($p['userNewPassword'],$newPasswordSalt);

                            $this->modules['DB']->queryParams('
                                UPDATE
                                    '.TBLPFX.'users
                                SET
                                    "userPassword"=$1,
                                    "userPasswordSalt"=$2
                                WHERE
                                    "userID"=$3
                            ', array(
                                $newPasswordEncrypted,
                                $newPasswordSalt,
                                USERID
                            ));
							$this->modules['Auth']->setSessionUserPassword($newPasswordEncrypted);
						}

						$this->modules['Navbar']->addElements(array($this->modules['Language']->getString('Profile_saved'),''));
						FuncMisc::printMessage('profile_saved'); exit;
					}
				}

				$this->modules['Template']->assign(array(
					'p'=>Functions::HTMLSpecialChars(Functions::StripSlashes($p)),
					'error'=>$error
				));

				$this->modules['Template']->printPage('EditProfileGeneral.tpl');
				break;

			case 'ExtendedProfile':
				$error = '';

				// Erst werden die einzelnen Profilfelder geladen
				$this->modules['DB']->query('SELECT * FROM '.TBLPFX.'profile_fields');
				$profileFields = $this->modules['DB']->raw2Array();


				// Jetzt werde die eventuell vorhandenen Profildaten geladen
				$fieldsData = array();
				$this->modules['DB']->queryParams('SELECT "fieldID", "fieldValue" FROM '.TBLPFX.'profile_fields_data WHERE "userID"=$1', array(USERID));
				while($curData = $this->modules['DB']->fetchArray())
					$fieldsData[$curData['fieldID']] = $curData['fieldValue'];


				// Jetzt werden eventuelle $_POST-Daten uebernommen
				$p = array();
				foreach($profileFields AS $curField) {
					switch($curField['fieldType']) {
						case PROFILE_FIELD_TYPE_TEXT:         $p['fieldsData'][$curField['fieldID']] = isset($_POST['p']['FieldsData'][$curField['fieldID']]) ? $_POST['p']['FieldsData'][$curField['fieldID']] : (isset($fieldsData[$curField['fieldID']]) ? $fieldsData[$curField['fieldID']] : ''); break;
						case PROFILE_FIELD_TYPE_TEXTAREA:     $p['fieldsData'][$curField['fieldID']] = isset($_POST['p']['FieldsData'][$curField['fieldID']]) ? $_POST['p']['FieldsData'][$curField['fieldID']] : (isset($fieldsData[$curField['fieldID']]) ? $fieldsData[$curField['fieldID']] : ''); break;
						case PROFILE_FIELD_TYPE_SELECTSINGLE: $p['fieldsData'][$curField['fieldID']] = isset($_POST['p']['FieldsData'][$curField['fieldID']]) ? intval($_POST['p']['FieldsData'][$curField['fieldID']]) : (isset($fieldsData[$curField['fieldID']]) ? $fieldsData[$curField['fieldID']] : ''); break;
						case PROFILE_FIELD_TYPE_SELECTMULTI:  $p['fieldsData'][$curField['fieldID']] = (isset($_POST['p']['FieldsData'][$curField['fieldID']]) == TRUE && is_array($_POST['p']['FieldsData'][$curField['fieldID']]) == TRUE) ? $_POST['p']['FieldsData'][$curField['fieldID']] : (isset($fieldsData[$curField['fieldID']]) ? explode(',',$fieldsData[$curField['fieldID']]) : array()); break;
					}
				}

				if(isset($_GET['doit'])) {
					$fieldIsMissing = FALSE;
					while(list(,$cur_field) = each($profile_fields)) {
						if($cur_field['field_is_required'] == 1 && ($cur_field['field_type'] != 3 && $p_fields_data[$cur_field['field_id']] === '' || $cur_field['field_type'] == 3 && count($p_fields_data[$cur_field['field_id']]) == 0)) {
							$fieldIsMissing = TRUE;
							break;
						}
					}
					reset($profile_fields);

					if($field_missing == TRUE) $error = $this->modules['Language']->getString('error_required_fields_missing');
					else {
						$delete_ids = array();

						while(list(,$cur_field) = each($profile_fields)) {
							$cur_value = ($cur_field['field_type'] == 3) ? implode(',',$p_fields_data[$cur_field['field_id']]) : $p_fields_data[$cur_field['field_id']];

							if($cur_value === '' && isset($fields_data[$cur_field['field_id']]) == TRUE) $delete_ids[] = $cur_field['field_id'];
							elseif($cur_value === '' && isset($fields_data[$cur_field['field_id']]) == FALSE) {}
							elseif(isset($fields_data[$cur_field['field_id']]) == TRUE) $this->modules['DB']->queryParams('UPDATE '.TBLPFX.'profile_fields_data SET "field_value"=$1 WHERE "user_id"=$2 AND "field_id"=$3', array($cur_value, $uSER_ID, $cur_field['field_id']));
							else $this->modules['DB']->queryParams('INSERT INTO '.TBLPFX.'profile_fields_data ("field_id", "user_id", "field_value") VALUES ($1, $2, $3)', array($cur_field['field_id'], $uSER_ID, $cur_value));
						}
						reset($profile_fields);

                        $this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'profile_fields_data WHERE "user_id"=$1 AND "fieldID" IN $2', array(USERID, $deleteIDs));
					}
				}

				$groupsData = array(
					array('groupName'=>$this->modules['Language']->getString('Required_information'),'groupType'=>1,'groupFields'=>array()),
					array('groupName'=>$this->modules['Language']->getString('Other_information'),'groupType'=>0,'groupFields'=>array())
				);

				foreach($profileFields AS $curField) {
					switch($curField['fieldType']) {
						case PROFILE_FIELD_TYPE_TEXT:
						case PROFILE_FIELD_TYPE_TEXTAREA:
							$curField['_fieldValue'] = Functions::HTMLSpecialChars(Functions::StripSlashes($p['fieldsData'][$curField['fieldID']]));
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

				$this->modules['Template']->assign(array(
					'p'=>$p,
					'groupsData'=>$groupsData,
					'error'=>$error
				));
				$this->modules['Template']->printPage('EditProfileExtendedProfile.tpl');
				break;

			case 'ProfileSettings':
				$p = Functions::getSGValues($_POST['p'],array('userTimeZone','userHideEmailAddress','userReceiveEmails'),'',$this->modules['Auth']->getUserData());

				$timeZones = Functions::getTimeZones(TRUE);

				if(!in_array($p['userHideEmailAddress'],array(0,1))) $p['userHideEmailAddress'] = 0;
				if(!in_array($p['userReceiveEmails'],array(0,1))) $p['userReceiveEmails'] = 1;
				if(!isset($timeZones[$p['userTimeZone']])) $p['userTimeZone'] = 'gmt';

				if(isset($_GET['doit'])) {
                    $this->modules['DB']->queryParams('
                        UPDATE
                            '.TBLPFX.'users
                        SET
                            "userHideEmailAddress"=$1,
                            "userReceiveEmails"=$2,
                            "userTimeZone"=$3
                        WHERE
                            "userID"=$4
                    ', array(
                        $p['userHideEmailAddress'],
                        $p['userReceiveEmails'],
                        $p['userTimeZone'],
                        USERID
                    ));

					$this->modules['Navbar']->addElements(array($this->modules['Language']->getString('Profile_saved'),''));
					FuncMisc::printMessage('profile_saved'); exit;
				}

				$this->modules['Template']->assign(array(
					'p'=>$p,
					'timeZones'=>$timeZones
				));
				$this->modules['Template']->printPage('EditProfileProfileSettings.tpl');
				break;

			case 'TopicSubscriptions':
				$topicID = isset($_GET['topicID']) ? intval($_GET['topicID']) : 0;
				$topicIDs = (isset($_POST['topicIDs']) && is_array($_POST['topicIDs']) == TRUE) ? $_POST['topicIDs'] : array();

				if(isset($_GET['doit'])) {
					if($topicID != 0)
                        $this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'topics_subscriptions WHERE "userID"=$1 AND "topicID"=$2', array(USERID, $topicID));

					if(count($topicIDs) > 0)
                        $this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'topics_subscriptions WHERE "userID"=$1 AND "topicID" IN $2', array(USERID, $topicIDs));
				}

                $this->modules['DB']->queryParams('SELECT t2."topicTitle", t1."topicID" FROM ('.TBLPFX.'topics_subscriptions AS t1, '.TBLPFX.'topics AS t2) WHERE t1."userID"=$1 AND t2."topicID"=t1."topicID"', array(USERID));
				$subscriptionsData = $this->modules['DB']->raw2Array();

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('Topic_subscriptions'),'');

				$this->modules['Template']->assign(array(
					'subscriptionsData'=>$subscriptionsData
				));
				$this->modules['Template']->printPage('EditProfileTopicSubscriptions.tpl');
				break;

			case 'Avatar':
				$p['avatarAddress'] = isset($_POST['p']['avatarAddress']) ? $_POST['p']['avatarAddress'] : $this->modules['Auth']->getValue('userAvatarAddress');

				if(isset($_GET['Doit']))
                    $this->modules['DB']->queryParams('UPDATE '.TBLPFX.'users SET "userAvatarAddress"=$1 WHERE "userID"=$2', array($p['avatarAddress'], USERID));

				$this->modules['DB']->query('SELECT "avatarAddress" FROM '.TBLPFX.'avatars');
				$avatarsData = $this->modules['DB']->raw2Array();
				$avatarsCounter = count($avatarsData);

				$this->modules['Template']->assign(array(
					'avatarsData'=>$avatarsData,
					'avatarsCounter'=>$avatarsCounter,
					'p'=>$p
				));
				$this->modules['Template']->printPage('EditProfileAvatar.tpl');
				break;

			case 'Memo':
				$p = array();
				$p['userMemo'] = isset($_POST['p']['userMemo']) ? $_POST['p']['userMemo'] : $this->modules['Auth']->getValue('userMemo');

				$this->modules['Navbar']->addelement($this->modules['Language']->getString('Memo'),INDEXFILE.'?action=EditProfile&amp;mode=Memo&amp;'.MYSID);

				if(isset($_GET['doit'])) {
                    $this->modules['DB']->queryParams('UPDATE '.TBLPFX.'users SET "userMemo"=$1 WHERE "userID"=$2', array($p['userMemo'], USERID));
					FuncMisc::printMessage('memo_updated'); exit;
				}

				$this->modules['Template']->assign(array(
					'p'=>Functions::HTMLSpecialChars(Functions::stripSlashes($p))
				));
				$this->modules['Template']->printPage('EditProfileMemo.tpl');
				break;

			case 'UploadAvatar':
				if($this->modules['Config']->getValue('enable_avatar_upload') != 1) {
					FuncMisc::printMessage('avatar_upload_disabled',array(),TRUE);
					exit;
				}

				$error = '';

				if(isset($_GET['doit'])) {
					if(!isset($_FILES['avatarFile']) || $_FILES['avatarFile']['name'] == '') $error = $this->modules['Language']->getString('error_invalid_file');
					elseif($_FILES['avatarFile']['size'] > $this->modules['Config']->getValue('max_avatar_file_size')*1024) $error = $this->modules['Language']->getString('error_file_too_big');
					else {
						preg_match("/^(.*)\.([^.]*)/i",Functions::strtolower($_FILES['avatarFile']['name']),$fileExtension);
						$fileExtension = $fileExtension[2];

						$validFileExtensions = array(
							'jpg',
							'jpeg',
							'bmp',
							'png',
							'gif'
						);

						if(!in_array($fileExtension,$validFileExtensions)) $error = $this->modules['Language']->getString('error_invalid_file_extension');
						else {
							/**
							 * Check if the user already uploaded an avatar
							 */
							foreach($validFileExtensions AS $curExtension) {
								if(file_exists('uploads/avatars/'.USERID.'.'.$curExtension)) {
									unlink('uploads/avatars/'.USERID.'.'.$curExtension);
									break;
								}
							}

							/**
							 * Move new avatar to correct dir
							 */
							$localAvatarFileName = 'uploads/avatars/'.USERID.'.'.$fileExtension;
							move_uploaded_file($_FILES['avatarFile']['tmp_name'],$localAvatarFileName);
							chmod($localAvatarFileName,0777);
							$this->modules['DB']->queryParams('
								UPDATE '.TBLPFX.'users SET
									"userAvatarAddress"=$1
								WHERE
									"userID"=$2
							',array(
								$localAvatarFileName,
								USERID
							));

							$avatarSelectedText = sprintf($this->modules['Language']->getString('avatar_selected_text'),'<img src="'.$localAvatarFileName.'" width="'.$this->modules['Config']->getValue('avatar_image_width').'" height="'.$this->modules['Config']->getValue('avatar_image_height').'" alt=""/>');
							FuncMisc::printMessage(array($this->modules['Language']->getString('Avatar_selected'),$avatarSelectedText),array(),TRUE);
						}
					}
				}

				$this->modules['Template']->assign(array(
					'error'=>$error
				));

				$this->modules['Template']->printPopupPage('EditProfileUploadAvatar.tpl');
				break;
		}
	}
}

?>