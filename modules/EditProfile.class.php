<?php

class EditProfile extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'Config',
		'DB',
		'Language',
		'Template',
		'PageParts'
	);

	public function executeMe() {
		if($this->modules['Auth']->isLoggedIn() != 1) die('Kein Zugriff: Nicht eingeloggt');

		$this->modules['Language']->addFile('EditProfile');
		$this->modules['PageParts']->setInEditProfile(TRUE);

		//add_navbar_items(array($this->modules['Language']->getString('User_administration'],"index.php?action=editprofile&amp;$mYSID"));

		switch(@$_GET['Mode']) {
			default:
				$p = Functions::getSGValues($_POST['p'],array('UserEmail','UserSignature'),'',Functions::addSlashes($this->modules['Auth']->getUserData()));
				$p = array_merge($p,Functions::getSGValues($_POST['p'],array('UserOldPassword','UserNewPassword','UserNewPasswordConfirmation'),''));

				$error = '';

				if(isset($_GET['Doit'])) {
					if(Functions::verifyEmail($p['UserEmail']) == FALSE) $error = $this->modules['Language']->getString('error_bad_email');
					elseif(trim($p['UserNewPassword']) != '' && Functions::getSaltedHash($p['UserOldPassword'],$this->modules['Auth']->getValue('UserPasswordSalt')) != $this->modules['Auth']->getValue('user_pw')) $error = $this->modules['Language']->getString('error_wrong_password');
					elseif(trim($p['UserNewPassword']) != '' && $p['UserNewPassword'] != $p['UserNewPasswordConfirmation']) $error = $this->modules['Language']->getString('error_pws_no_match');
					else {
						$this->modules['DB']->query("
							UPDATE
								".TBLPFX."users
							SET
								UserEmail='".$p['UserEmail']."',
								UserSignature='".$p['UserSignature']."'
							WHERE
								UserID='".USERID."'
						");

						if(trim($p['UserNewPassword']) != '') {
							$newPasswordSalt = Functions::getRandomString(10);
							$newPasswordEncrypted = Functions::getSaltedHash($p['UserNewPassword'],$newPasswordSalt);

							$this->modules['DB']->query("
								UPDATE
									".TBLPFX."users
								SET
									UserPassword='".$newPasswordEncrypted."',
									UserPasswordSalt='".$newPasswordSalt."'
								WHERE UserID='".USERID."'
							");
							$this->modules['Auth']->setSessionUserPassword($newPasswordEncrypted);
						}

						$nAVBAR->addElements('left',array($this->modules['Language']->getString('Profile_saved'),''));

						include_once('pheader.php');
						show_message($this->modules['Language']->getString('Profile_saved'),$this->modules['Language']->getString('message_profile_saved'));
						include_once('ptail.php'); exit;
					}
				}

				$this->modules['PageParts']->printStdHeader();

				$this->modules['Template']->assign(array(
					'p'=>Functions::HTMLSpecialChars(Functions::StripSlashes($p)),
					'Error'=>$error
				));

				$this->modules['Template']->display('EditProfileGeneral.tpl');

				$this->modules['PageParts']->printStdTail();
				break;

			case 'ExtendedProfile':
				$error = '';

				// Erst werden die einzelnen Profilfelder geladen
				$this->modules['DB']->query("SELECT * FROM ".TBLPFX."profile_fields");
				$profileFields = $this->modules['DB']->raw2Array();


				// Jetzt werde die eventuell vorhandenen Profildaten geladen
				$fieldsData = array();
				$this->modules['DB']->query("SELECT FieldID,FieldValue FROM ".TBLPFX."profile_fields_data WHERE UserID='".USERID."'");
				while($curData = $this->modules['DB']->fetchArray())
					$fieldsData[$curData['FieldID']] = $curData['FieldValue'];


				// Jetzt werden eventuelle $_POST-Daten uebernommen
				$p = array();
				foreach($profileFields AS $curField) {
					switch($curField['FieldType']) {
						case PROFILE_FIELD_TYPE_TEXT:         $p['FieldsData'][$curField['FieldID']] = isset($_POST['p']['FieldsData'][$curField['FieldID']]) ? $_POST['p']['FieldsData'][$curField['FieldID']] : (isset($fieldsData[$curField['FieldID']]) ? addslashes($fieldsData[$curField['FieldID']]) : ''); break;
						case PROFILE_FIELD_TYPE_TEXTAREA:     $p['FieldsData'][$curField['FieldID']] = isset($_POST['p']['FieldsData'][$curField['FieldID']]) ? $_POST['p']['FieldsData'][$curField['FieldID']] : (isset($fieldsData[$curField['FieldID']]) ? addslashes($fieldsData[$curField['FieldID']]) : ''); break;
						case PROFILE_FIELD_TYPE_SELECTSINGLE: $p['FieldsData'][$curField['FieldID']] = isset($_POST['p']['FieldsData'][$curField['FieldID']]) ? intval($_POST['p']['FieldsData'][$curField['FieldID']]) : (isset($fieldsData[$curField['FieldID']]) ? $fieldsData[$curField['FieldID']] : ''); break;
						case PROFILE_FIELD_TYPE_SELECTMULTI:  $p['FieldsData'][$curField['FieldID']] = (isset($_POST['p']['FieldsData'][$curField['FieldID']]) == TRUE && is_array($_POST['p']['FieldsData'][$curField['FieldID']]) == TRUE) ? $_POST['p']['FieldsData'][$curField['FieldID']] : (isset($fieldsData[$curField['FieldID']]) ? explode(',',$fieldsData[$curField['FieldID']]) : array()); break;
					}
				}

				if(isset($_GET['Doit'])) {
					$field_missing = FALSE;
					while(list(,$cur_field) = each($profile_fields)) {
						if($cur_field['field_is_required'] == 1 && ($cur_field['field_type'] != 3 && $p_fields_data[$cur_field['field_id']] === '' || $cur_field['field_type'] == 3 && count($p_fields_data[$cur_field['field_id']]) == 0)) {
							$field_missing = TRUE;
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

						$this->modules['DB']->query("DELETE FROM ".TBLPFX."profile_fields_data WHERE user_id='$uSER_ID' AND field_id IN ('".implode(',',$delete_ids)."')");
					}
				}

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
							$curField['_FieldSelectedIDs'] = $p['FieldsData'][$curField['FieldID']];
							$curField['_FieldOptions'] = unserialize($curField['FieldData']);
							break;
					}
					if($curField['FieldIsRequired'] == 0) $groupsData[1]['GroupFields'][] = $curField;
					else $groupsData[0]['GroupFields'][] = $curField;
				}

				$this->modules['Template']->assign(array(
					'p'=>$p,
					'GroupsData'=>$groupsData
				));
				$this->modules['PageParts']->printPage('EditProfileExtendedProfile.tpl');
				break;

			case 'ProfileSettings':
				$p = array();
				$p['UserTimeZone'] = isset($_POST['p']['UserTimeZone']) ? $_POST['p']['UserTimeZone'] : $this->modules['Auth']->getValue('UserTimeZone');
				$p['UserHideEmail'] = isset($_POST['p']['UserHideEmail']) ? intval($_POST['p']['UserHideEmail']) : $this->modules['Auth']->getValue('UserHideEmail');
				$p['UserReceiveEmails'] = isset($_POST['p']['UserReceiveEmails']) ? intval($_POST['p']['UserReceiveEmails']) : $this->modules['Auth']->getValue('UserReceiveEmails');

				$timeZones = Functions::getTimeZones(TRUE);

				if(in_array($p['UserHideEmail'],array(0,1)) == FALSE) $p['UserHideEmail'] = 0;
				if(in_array($p['UserReceiveEmails'],array(0,1)) == FALSE) $p['UserReceiveEmails'] = 1;
				if(!isset($timeZones[$p['UserTimeZone']])) $p['UserTimeZone'] = 'gmt';

				if(isset($_GET['doit'])) {
					$this->modules['DB']->query("
						UPDATE
							".TBLPFX."users
						SET
							UserHideEmail='".$p['UserHideEmail']."',
							UserReceiveEmails='".$p['UserReceiveEmails']."',
							UserTimeZone='".$p['UserTimeZone']."'
						WHERE UserID='".USERID."'
					");

					include_once('pheader.php');
					show_message($this->modules['Language']->getString('Settings_saved'),$this->modules['Language']->getString('message_settings_successfully_saved'));
					include_once('ptail.php'); exit;
				}

				$this->modules['Template']->assign(array(
					'p'=>$p,
					'TimeZones'=>$timeZones
				));
				$this->modules['PageParts']->printPage('EditProfileProfileSettings.tpl');
				break;

			case 'TopicSubscriptions':
				$topicID = isset($_GET['TopicID']) ? intval($_GET['TopicID']) : 0;
				$topicIDs = (isset($_POST['TopicIDs']) && is_array($_POST['TopicIDs']) == TRUE) ? $_POST['TopicIDs'] : array();

				if(isset($_GET['Doit'])) {
					if($topicID != 0)
						$this->modules['DB']->query("DELETE FROM ".TBLPFX."topics_subscriptions WHERE UserID='".USERID."' AND TopicID='$topicID'");

					if(count($topicIDs) > 0)
						$this->modules['DB']->query("DELETE FROM ".TBLPFX."topics_subscriptions WHERE UserID='".USERID."' AND TopicID IN('".implode("','",$topicIDs)."')");
				}

				$this->modules['DB']->query("SELECT t2.TopicTitle,t1.TopicID FROM (".TBLPFX."topics_subscriptions AS t1, ".TBLPFX."topics AS t2) WHERE t1.UserID='".USERID."' AND t2.TopicID=t1.TopicID");
				$subscriptionsData = $this->modules['DB']->raw2Array();

				//add_navbar_items(array($this->modules['Language']->getString('Topic_subscriptions'),''));

				$this->modules['Template']->assign(array(
					'SubscriptionsData'=>$subscriptionsData
				));
				$this->modules['PageParts']->printPage('EditProfileTopicSubscriptions.tpl');
				break;

			case 'Avatar':
				$p['AvatarAddress'] = isset($_POST['p']['AvatarAddress']) ? $_POST['p']['AvatarAddress'] : addslashes($this->modules['Auth']->getValue('UserAvatarAddress'));

				if(isset($_GET['Doit']))
					$this->modules['DB']->query("UPDATE ".TBLPFX."users SET UserAvatarAddress='".$p['AvatarAddress']."' WHERE UserID='".USERID."'");

				$this->modules['DB']->query("SELECT AvatarAddress FROM ".TBLPFX."avatars");
				$avatarsData = $this->modules['DB']->raw2Array();
				$avatarsCounter = count($avatarsData);

				$this->modules['Template']->assign(array(
					'AvatarsData'=>$avatarsData,
					'AvatarsCounter'=>$avatarsCounter,
					'p'=>$p
				));
				$this->modules['PageParts']->printPage('EditProfileAvatar.tpl');
				break;

			case 'Memo':
				$p = array();
				$p['UserMemo'] = isset($_POST['p']['UserMemo']) ? $_POST['p']['UserMemo'] : addslashes($this->modules['Auth']->getValue('UserMemo'));

				$memoWasUpdated = FALSE;

				if(isset($_GET['doit'])) {
					$this->modules['DB']->query("UPDATE ".TBLPFX."users SET user_memo='$p_user_memo' WHERE user_id='$uSER_ID'");
					$memoWasUpdated = TRUE;
				}

				//add_navbar_items(array($this->modules['Language']->getString('Memo'),"index.php?action=editprofile&amp;mode=memo&amp;$mYSID"));

				$this->modules['Template']->assign(array(
					'p'=>Functions::HTMLSpecialChars(Functions::stripSlashes($p)),
					'MemoWasUpdated'=>$memoWasUpdated
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