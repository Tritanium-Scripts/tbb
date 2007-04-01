<?php

class EditProfile extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'Config',
		'Constants',
		'DB',
		'Language',
		'Navbar',
		'Template',
		'PageParts'
	);

	public function executeMe() {
		if($this->modules['Auth']->isLoggedIn() != 1) die('Kein Zugriff: Nicht eingeloggt');

		$this->modules['Language']->addFile('EditProfile');
		$this->modules['PageParts']->setInEditProfile(TRUE);

		//add_navbar_items(array($this->modules['Language']->getString('User_administration'],"index.php?action=editprofile&amp;$mYSID"));

		switch(@$_GET['mode']) {
			default:
				$p = Functions::getSGValues($_POST['p'],array('userEmail','userSignature'),'',Functions::addSlashes($this->modules['Auth']->getUserData()));
				$p = array_merge($p,Functions::getSGValues($_POST['p'],array('userOldPassword','userNewPassword','userNewPasswordConfirmation'),''));

				$error = '';

				if(isset($_GET['Doit'])) {
					if(!Functions::verifyEmail($p['userEmail'])) $error = $this->modules['Language']->getString('error_bad_email');
					elseif(trim($p['userNewPassword']) != '' && Functions::getSaltedHash($p['userOldPassword'],$this->modules['Auth']->getValue('userPasswordSalt')) != $this->modules['Auth']->getValue('user_pw')) $error = $this->modules['Language']->getString('error_wrong_password');
					elseif(trim($p['userNewPassword']) != '' && $p['userNewPassword'] != $p['userNewPasswordConfirmation']) $error = $this->modules['Language']->getString('error_pws_no_match');
					else {
						$this->modules['DB']->query("
							UPDATE
								".TBLPFX."users
							SET
								userEmail='".$p['userEmail']."',
								userSignature='".$p['userSignature']."'
							WHERE
								userID='".USERID."'
						");

						if(trim($p['UserNewPassword']) != '') {
							$newPasswordSalt = Functions::getRandomString(10);
							$newPasswordEncrypted = Functions::getSaltedHash($p['UserNewPassword'],$newPasswordSalt);

							$this->modules['DB']->query("
								UPDATE
									".TBLPFX."users
								SET
									userPassword='".$newPasswordEncrypted."',
									userPasswordSalt='".$newPasswordSalt."'
								WHERE userID='".USERID."'
							");
							$this->modules['Auth']->setSessionUserPassword($newPasswordEncrypted);
						}

						$this->modules['Navbar']->addElements('left',array($this->modules['Language']->getString('Profile_saved'),''));

						include_once('pheader.php');
						show_message($this->modules['Language']->getString('Profile_saved'),$this->modules['Language']->getString('message_profile_saved'));
						include_once('ptail.php'); exit;
					}
				}

				$this->modules['Template']->assign(array(
					'p'=>Functions::HTMLSpecialChars(Functions::StripSlashes($p)),
					'error'=>$error
				));

				$this->modules['PageParts']->printPage('EditProfileGeneral.tpl');
				break;

			case 'ExtendedProfile':
				$error = '';

				// Erst werden die einzelnen Profilfelder geladen
				$this->modules['DB']->query("SELECT * FROM ".TBLPFX."profile_fields");
				$profileFields = $this->modules['DB']->raw2Array();


				// Jetzt werde die eventuell vorhandenen Profildaten geladen
				$fieldsData = array();
				$this->modules['DB']->query("SELECT fieldID,fieldValue FROM ".TBLPFX."profile_fields_data WHERE userID='".USERID."'");
				while($curData = $this->modules['DB']->fetchArray())
					$fieldsData[$curData['fieldID']] = $curData['fieldValue'];


				// Jetzt werden eventuelle $_POST-Daten uebernommen
				$p = array();
				foreach($profileFields AS $curField) {
					switch($curField['fieldType']) {
						case PROFILE_FIELD_TYPE_TEXT:         $p['fieldsData'][$curField['fieldID']] = isset($_POST['p']['FieldsData'][$curField['fieldID']]) ? $_POST['p']['FieldsData'][$curField['fieldID']] : (isset($fieldsData[$curField['fieldID']]) ? addslashes($fieldsData[$curField['fieldID']]) : ''); break;
						case PROFILE_FIELD_TYPE_TEXTAREA:     $p['fieldsData'][$curField['fieldID']] = isset($_POST['p']['FieldsData'][$curField['fieldID']]) ? $_POST['p']['FieldsData'][$curField['fieldID']] : (isset($fieldsData[$curField['fieldID']]) ? addslashes($fieldsData[$curField['fieldID']]) : ''); break;
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
							elseif(isset($fields_data[$cur_field['field_id']]) == TRUE) $this->modules['DB']->query("UPDATE ".TBLPFX."profile_fields_data SET field_value='$cur_value' WHERE user_id='$uSER_ID' AND field_id='".$cur_field['field_id']."'");
							else $this->modules['DB']->query("INSERT INTO ".TBLPFX."profile_fields_data (field_id,user_id,field_value) VALUES ('".$cur_field['field_id']."','$uSER_ID','$cur_value')");
						}
						reset($profile_fields);

						$this->modules['DB']->query("DELETE FROM ".TBLPFX."profile_fields_data WHERE user_id='".USERID."' AND fieldID IN ('".implode(',',$deleteIDs)."')");
					}
				}

				$groupsData = array(
					array('GroupName'=>$this->modules['Language']->getString('Required_information'),'groupType'=>1,'groupFields'=>array()),
					array('GroupName'=>$this->modules['Language']->getString('Other_information'),'groupType'=>0,'groupFields'=>array())
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
				$this->modules['PageParts']->printPage('EditProfileExtendedProfile.tpl');
				break;

			case 'ProfileSettings':
				$p = array();
				$p['userTimeZone'] = isset($_POST['p']['userTimeZone']) ? $_POST['p']['userTimeZone'] : $this->modules['Auth']->getValue('userTimeZone');
				$p['userHideEmail'] = isset($_POST['p']['userHideEmail']) ? intval($_POST['p']['userHideEmail']) : $this->modules['Auth']->getValue('userHideEmail');
				$p['userReceiveEmails'] = isset($_POST['p']['userReceiveEmails']) ? intval($_POST['p']['userReceiveEmails']) : $this->modules['Auth']->getValue('userReceiveEmails');

				$timeZones = Functions::getTimeZones(TRUE);

				if(in_array($p['userHideEmail'],array(0,1)) == FALSE) $p['userHideEmail'] = 0;
				if(in_array($p['userReceiveEmails'],array(0,1)) == FALSE) $p['userReceiveEmails'] = 1;
				if(!isset($timeZones[$p['userTimeZone']])) $p['userTimeZone'] = 'gmt';

				if(isset($_GET['doit'])) {
					$this->modules['DB']->query("
						UPDATE
							".TBLPFX."users
						SET
							userHideEmail='".$p['userHideEmail']."',
							userReceiveEmails='".$p['userReceiveEmails']."',
							userTimeZone='".$p['userTimeZone']."'
						WHERE userID='".USERID."'
					");

					// TODO richtige meldung
					die('geupdatet');
					exit;
					//include_once('pheader.php');
					//show_message($this->modules['Language']->getString('Settings_saved'),$this->modules['Language']->getString('message_settings_successfully_saved'));
					//include_once('ptail.php'); exit;
				}

				$this->modules['Template']->assign(array(
					'p'=>$p,
					'timeZones'=>$timeZones
				));
				$this->modules['PageParts']->printPage('EditProfileProfileSettings.tpl');
				break;

			case 'TopicSubscriptions':
				$topicID = isset($_GET['topicID']) ? intval($_GET['topicID']) : 0;
				$topicIDs = (isset($_POST['topicIDs']) && is_array($_POST['topicIDs']) == TRUE) ? $_POST['topicIDs'] : array();

				if(isset($_GET['doit'])) {
					if($topicID != 0)
						$this->modules['DB']->query("DELETE FROM ".TBLPFX."topics_subscriptions WHERE userID='".USERID."' AND topicID='$topicID'");

					if(count($topicIDs) > 0)
						$this->modules['DB']->query("DELETE FROM ".TBLPFX."topics_subscriptions WHERE userID='".USERID."' AND topicID IN('".implode("','",$topicIDs)."')");
				}

				$this->modules['DB']->query("SELECT t2.topicTitle,t1.topicID FROM (".TBLPFX."topics_subscriptions AS t1, ".TBLPFX."topics AS t2) WHERE t1.userID='".USERID."' AND t2.topicID=t1.topicID");
				$subscriptionsData = $this->modules['DB']->raw2Array();

				//add_navbar_items(array($this->modules['Language']->getString('Topic_subscriptions'),''));

				$this->modules['Template']->assign(array(
					'subscriptionsData'=>$subscriptionsData
				));
				$this->modules['PageParts']->printPage('EditProfileTopicSubscriptions.tpl');
				break;

			case 'Avatar':
				$p['avatarAddress'] = isset($_POST['p']['avatarAddress']) ? $_POST['p']['avatarAddress'] : addslashes($this->modules['Auth']->getValue('userAvatarAddress'));

				if(isset($_GET['Doit']))
					$this->modules['DB']->query("UPDATE ".TBLPFX."users SET userAvatarAddress='".$p['avatarAddress']."' WHERE userID='".USERID."'");

				$this->modules['DB']->query("SELECT avatarAddress FROM ".TBLPFX."avatars");
				$avatarsData = $this->modules['DB']->raw2Array();
				$avatarsCounter = count($avatarsData);

				$this->modules['Template']->assign(array(
					'avatarsData'=>$avatarsData,
					'avatarsCounter'=>$avatarsCounter,
					'p'=>$p
				));
				$this->modules['PageParts']->printPage('EditProfileAvatar.tpl');
				break;

			case 'Memo':
				$p = array();
				$p['userMemo'] = isset($_POST['p']['userMemo']) ? $_POST['p']['userMemo'] : addslashes($this->modules['Auth']->getValue('userMemo'));

				$memoWasUpdated = FALSE;

				if(isset($_GET['doit'])) {
					$this->modules['DB']->query("UPDATE ".TBLPFX."users SET userMemo='".$p['userMemo']."' WHERE userID='".USERID."'");
					$memoWasUpdated = TRUE;
				}

				//add_navbar_items(array($this->modules['Language']->getString('Memo'),"index.php?action=editprofile&amp;mode=memo&amp;$mYSID"));

				$this->modules['Template']->assign(array(
					'p'=>Functions::HTMLSpecialChars(Functions::stripSlashes($p)),
					'memoWasUpdated'=>$memoWasUpdated
				));
				$this->modules['PageParts']->printPage('EditProfileMemo.tpl');
				break;

			case 'UploadAvatar':
				if($this->modules['Config']->getValue('enable_avatar_upload') != 1) {
					include_once('pop_pheader.php');
					show_message($this->modules['Language']->getString('Avatar_upload_disabled'),$this->modules['Language']->getString('message_avatar_upload_disabled'));
					include_once('pop_ptail.php'); exit;
				}

				$error = '';

				if(isset($_GET['doit'])) {
					if(isset($_FILES['p_avatar_file']) == FALSE || $_FILES['p_avatar_file']['name'] == '') $error = $this->modules['Language']->getString('error_invalid_file');
					elseif($_FILES['p_avatar_file']['size'] > $cONFIG['max_avatar_file_size']*1024) $error = $this->modules['Language']->getString('error_file_too_big');
					else {
						preg_match("/^(.*)\.([^.]*)/i",strtolower($_FILES['p_avatar_file']['name']),$file_extension);
						$file_extension = $file_extension[2];

						$good_file_extensions = array(
							'jpg',
							'jpeg',
							'bmp',
							'png',
							'gif'
						);

						if(in_array($file_extension,$good_file_extensions) != TRUE) $error = $this->modules['Language']->getString('error_invalid_file_extension');
						else {

							//
							// Erst muss ueberprueft werden, ob der User nicht schon ein Avatar hochgeladen hat, und falls ja diesen loeschen
							//
							while(list(,$akt_extension) = each($good_file_extensions)) { // Die Dateiendungen durchgehen
								if(file_exists('upload/avatars/'.$uSER_ID.'.'.$akt_extension) == TRUE) { // Falls eine Datei mit der aktuellen Dateiendung existiert...
									unlink('upload/avatars/'.$uSER_ID.'.'.$akt_extension); // ...diese loeschen...
									break; // ...und die Schleife beenden, da der User maximal ein Avatar haben kann
								}
							}

							$remote_avatar_file_name = 'upload/avatars/'.$uSER_ID.'.'.$file_extension;


							//
							// Jetzt kann der Avatar verschoben werden...
							//
							move_uploaded_file($_FILES['p_avatar_file']['tmp_name'],$remote_avatar_file_name); // Datei verschieben
							chmod('upload/avatars/'.$uSER_ID.'.'.$file_extension,0777); // Datei aenderbar/loeschbar machen
							$this->modules['DB']->query("UPDATE ".TBLPFX."users SET user_avatar_address='$remote_avatar_file_name' WHERE user_id='$uSER_ID'"); // Neuen Avatar in der Datenbank aktualisieren

							$tpl = new Template($tEMPLATE_PATH.'/'.$tCONFIG['templates']['editprofile_avatarresult']);

							$avatar_address = $remote_avatar_file_name;
							$avatar_selected_text = sprintf($this->modules['Language']->getString('avatar_selected_text'),'<img src="'.$remote_avatar_file_name.'" width="'.$cONFIG['avatar_image_width'].'" height="'.$cONFIG['avatar_image_height'].'" border="0" alt="" />');

							include_once('pop_pheader.php');
							$tpl->parseCode(TRUE);
							include_once('pop_ptail.php'); exit;
						}
					}
				}

				//$tpl = new Template($tEMPLATE_PATH.'/'.$tCONFIG['templates']['editprofile_uploadavatar']);

				$this->modules['PageParts']->printPopupPage('EditProfileUploadAvatar.tpl');
				break;

			case 'selectavatar':
				$avatar_address = isset($_GET['avatar_address']) ? $_GET['avatar_address'] : '';

				if(isset($_GET['doit'])) {
					$this->modules['DB']->query("UPDATE ".TBLPFX."users SET user_avatar_address='$avatar_address' WHERE user_id='$uSER_ID'");

					$tpl = new Template($tEMPLATE_PATH.'/'.$tCONFIG['templates']['editprofile_avatarresult']);

					$avatar_selected_text = sprintf($this->modules['Language']->getString('avatar_selected_text'),'<img src="'.$avatar_address.'" width="'.$cONFIG['avatar_image_width'].'" height="'.$cONFIG['avatar_image_height'].'" border="0" alt="" />');

					include_once('pop_pheader.php');
					$tpl->parseCode(TRUE);
					include_once('pop_ptail.php'); exit;
				}

				$tpl = new Template($tEMPLATE_PATH.'/'.$tCONFIG['templates']['editprofile_selectavatar']);

				$this->modules['DB']->query("SELECT avatar_address FROM ".TBLPFX."avatars");
				$avatars_data = $this->modules['DB']->raw2array();
				$avatars_counter = count($avatars_data);

				if($avatars_counter > 0) {
					for($i = 0; $i < $avatars_counter; $i++) {
						$akt_avatar = &$avatars_data[$i];
						$akt_encoded_avatar_address = urlencode($akt_avatar['avatar_address']);

						$tpl->blocks['avatarrow']->blocks['avatarcol']->parseCode(FALSE,TRUE);

						if(($i+1) % 5 == 0 && $i != $avatars_counter-1) {
							$tpl->blocks['avatarrow']->parseCode(FALSE,TRUE);
							$tpl->blocks['avatarrow']->blocks['avatarcol']->resetTpl();
						}
					}
					$tpl->blocks['avatarrow']->parseCode(FALSE,TRUE);
				}


				include_once('pop_pheader.php');
				$tpl->parseCode(TRUE);
				include_once('pop_ptail.php');
				break;
		}
	}
}

?>