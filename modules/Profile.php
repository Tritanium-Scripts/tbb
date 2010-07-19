<?php
/**
 * Manages an user profile incl. sending mails and vCard download.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class Profile implements Module
{
	/**
	 * Detected errors during profile actions.
	 *
	 * @var array Error messages
	 */
	private $errors = array();

	/**
	 * Mode to view or edit a profile.
	 *
	 * @var string View / edit mode
	 */
	private $mode;

	/**
	 * Translates a mode to its template file.
	 *
	 * @var array Mode and template counterparts
	 */
	private static $modeTable = array('' => 'ViewProfile', 'profile' => 'ViewProfile', 'edit' => 'EditProfile', 'formmail' => 'SendMail', 'vCard' => 'vCard', 'EditProfileConfirmDelete' => 'EditProfileConfirmDelete', 'viewAchievements' => 'ViewAchievements');

	/**
	 * Contains the requested user data to display or edit.
	 *
	 * @var bool|array User data or false if inapplicable
	 */
	private $userData;

	/**
	 * Loads user data and sets mode.
	 *
	 * @param string $mode Profile mode
	 * @return Profile New instance of this class
	 */
	function __construct($mode)
	{
		$this->mode = $mode;
		$this->userData = Functions::getUserData(($userID = Functions::getValueFromGlobals($this->mode == 'formmail' ? 'target_id' : 'profile_id')) == '' ? Main::getModule('Auth')->getUserID() : $userID);
	}

	/**
	 * Displays or edits the user profile.
	 */
	public function execute()
	{
		//Global guest or deleted check
		if(empty($this->userData) || $this->userData[4] == '5')
			Main::getModule('Template')->printMessage('user_does_not_exist');
		switch($this->mode)
		{
//EditProfile
			case 'edit':
			if(!Main::getModule('Auth')->isLoggedIn())
				Main::getModule('Template')->printMessage('profile_need_login');
			elseif(!Main::getModule('Auth')->isAdmin() || Main::getModule('Auth')->getUserID() != $this->userData[1])
				Main::getModule('Template')->printMessage('profile_no_access');
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('my_profile'), INDEXFILE . '?faction=profile&amp;profile_id=' . $this->userData[1] . SID_AMPER);
			if(Functions::getValueFromGlobals('change') == '1')
			{
				//Delete acc?
				if(Functions::getValueFromGlobals('delete') != '')
				{
					//Confirmed?
					if(Functions::getValueFromGlobals('confirm') == '1')
					{
						//Time to say goodbye
						unlink('members/' . $this->userData[1] . '.xbb');
						unlink('members/' . $this->userData[1] . '.pm');
						Functions::file_put_contents('vars/member_counter.var', Functions::file_get_contents('vars/member_counter.var')-1);
						//In case not an admin has deleted the user from "his own profile" (approx 99,9% of all cases, lol)
						if($this->userData[1] == Main::getModule('Auth')->getUserID())
						{
							//Perform a logout "light"
							unset($_SESSION['userID'], $_SESSION['userHash']);
							//Notify other modules
							Main::getModule('WhoIsOnline')->delete($this->userData[1]);
							Main::getModule('Auth')->loginChanged();
						}
						Main::getModule('Template')->printMessage('account_deleted');
					}
					//Get confirmation
					else
					{
						Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('delete_account'));
						$this->mode = 'EditProfileConfirmDelete';
					}
				}
				//Normal edit
				else
				{
					//Check and update settings
					if(($this->userData[3] = Functions::getValueFromGlobals('new_mail')) == '')
						$this->errors[] = Main::getModule('Language')->getString('please_enter_your_mail');
					elseif(!FunctionsBasic::isValidMail($this->userData[3]))
						$this->errors[] = Main::getModule('Language')->getString('please_enter_a_valid_mail');
					$this->userData[7] = Functions::nl2br(Functions::getValueFromGlobals('new_signatur'));
					$this->userData[9] = Functions::getValueFromGlobals('new_hp');
					$this->userData[10] = Functions::getValueFromGlobals('new_pic');
					$this->userData[12] = Functions::getValueFromGlobals('new_realname');
					if(($this->userData[13] = Functions::getValueFromGlobals('new_icq')) != '' && !is_numeric($this->userData[13]))
						$this->errors[] = Main::getModule('Language')->getString('please_enter_a_valid_icq_number');
					$this->userData[14][0] = Functions::getValueFromGlobals('new_mail1') == '1' ? '1' : '0';
					$this->userData[14][1] = Functions::getValueFromGlobals('new_mail2') == '1' ? '1' : '0';
					$this->userData[17] = Functions::getValueFromGlobals('specialRank');
					$this->userData[18] = Functions::getValueFromGlobals('steamProfile');
					$this->userData[19] = Functions::explodeByTab(Functions::str_replace(array("\n", "\r"), array("\t", ''), Functions::getValueFromGlobals('steamGames')));
					if(!empty($this->userData[19]) && empty($this->userData[18]))
						$this->errors[] = Main::getModule('Language')->getString('please_enter_your_steam_profile_name');
					if(($newPass = Functions::getValueFromGlobals('new_pw1')) != Functions::getValueFromGlobals('new_pw2'))
						$this->errors[] = Main::getModule('Language')->getString('new_passwords_do_not_match');
					//Write updates?
					if(empty($this->errors))
					{
						//Prepare for writing
						$this->userData[14] = implode(',', $this->userData[14]);
						$this->userData[19] = implode("\t", $this->userData[19]);
						if(!empty($newPass))
						{
							//Hash new password and update session and cookie logins
							$this->userData[2] = Functions::getHash($newPass);
							//Only refresh if user is not admin and editing other profiles or current user updating himself
							if(!Main::getModule('Auth')->isAdmin() || Main::getModule('Auth')->getUserID() == $this->userData[1])
							{
								$_SESSION['userHash'] = $this->userData[2];
								if(isset($_COOKIE['cookie_xbbuser']))
									setcookie('cookie_xbbuser', $this->userData[1] . "\t" . $this->userData[2], time()+3600*24*365, Main::getModule('Config')->getCfgVal('path_to_forum'));
							}
						}
						//Update to file
						Functions::file_put_contents('members/' . $this->userData[1] . '.xbb', implode("\n", $this->userData));
						//And done
						Main::getModule('Logger')->log('% edited profile from ID: ' . $this->userData[1], LOG_EDIT_PROFILE);
						Main::getModule('Template')->printMessage('profile_saved', INDEXFILE . '?faction=profile&amp;mode=edit&amp;profile_id=' . $this->userData[1] . SID_AMPER, INDEXFILE . SID_QMARK);
					}
				}
			}
			//Check for forum updates since user's last login (redir'd directly from Login module)
			if($this->userData[11] == '1' && Main::getModule('Auth')->getUserID() == $this->userData[1])
			{
				//Tell user via errors
				$this->errors[] = Main::getModule('Language')->getString('forum_was_updated_since_last_visit');
				//Change update flag
				$this->userData[11] = '0';
				//Implode back other data
				$this->userData[14] = implode(',', $this->userData[14]);
				$this->userData[19] = implode("\t", $this->userData[19]);
				Functions::file_put_contents('members/' . $this->userData[1] . '.xbb', implode("\n", $this->userData));
				//Reload
				$this->userData = Functions::getUserData($this->userData[1]);
			}
			//Prepare rank, regDate + sig
			$this->userData[2] = Functions::getRankImage($this->userData[4], $this->userData[5]); //Reuse "password slot"
			$this->userData[4] = Functions::getStateName($this->userData[4], $this->userData[5]);
			$this->userData[6] = Functions::formatDate($this->userData[6] . (Functions::strlen($this->userData[6]) == 6 ? '01000000' : ''));
			$this->userData[7] = Functions::br2nl($this->userData[7]);
			//Delete not needed data or: the template doesn't need to know these
			unset($this->userData[8], $this->userData[11], $this->userData[15], $this->userData[16]);
			break;

//SendMail
			case 'formmail':
			Main::getModule('NavBar')->addElement(array(
				array(sprintf(Main::getModule('Language')->getString('view_profile_from_x'), $this->userData[0]), INDEXFILE . '?faction=profile&amp;profile_id=' . $this->userData[1] . SID_AMPER),
				array(Main::getModule('Language')->getString('send_mail'), INDEXFILE . '?faction=formmail&amp;target_id=' . $this->userData[1] . SID_AMPER)));
			if(Main::getModule('Config')->getCfgVal('activate_mail') != 1)
				Main::getModule('Template')->printMessage('function_deactivated');
			elseif(!Main::getModule('Auth')->isLoggedIn() && Main::getModule('Config')->getCfgVal('formmail_mbli') == 1)
				Main::getModule('Template')->printMessage('login_only', INDEXFILE . '?faction=register' . SID_AMPER, INDEXFILE . '?faction=login' . SID_AMPER);
			elseif($this->userData[14][0] != '1')
				Main::getModule('Template')->printMessage('user_no_form_mails');
			//Process e-mail
			$senderMail = Functions::getValueFromGlobals('sender_email');
			$senderName = Functions::getValueFromGlobals('sender_name');
			$subject = Functions::getValueFromGlobals('subject');
			$message = Functions::getValueFromGlobals('message');
			if(Functions::getValueFromGlobals('send') == 'yes')
			{
				//Check input
				if(!Main::getModule('Auth')->isLoggedIn())
				{
					if(empty($senderMail))
						$this->errors[] = Main::getModule('Language')->getString('please_enter_your_mail');
					elseif(!FunctionsBasic::isValidMail($senderMail))
						$this->errors[] = Main::getModule('Language')->getString('please_enter_a_valid_mail');
					if(empty($senderName))
						$this->errors[] = Main::getModule('Language')->getString('please_enter_your_name');
				}
				else
				{
					$senderMail = Main::getModule('Auth')->getUserMail();
					$senderName = Main::getModule('Auth')->getUserNick();
				}
				//Send it
				if(empty($this->errors))
					Main::getModule('Template')->printMessage(Functions::sendMessage($this->userData[3], 'mail_from_user', $this->userData[0], $senderMail, $senderMail, $subject, $message, INDEXFILE . '?faction=login') ? 'mail_sent' : 'sending_mail_failed');
			}
			//Recipient data (assigned automatically via reusing $this->userData)
			$this->userData = array_slice($this->userData, 0, 2) + array('recipientName' => &$this->userData[0],
				'recipientID' => &$this->userData[1]);
			//Sender data
			Main::getModule('Template')->assign(array('senderName' => $senderName,
				'senderMail' => $senderMail,
				'subject' => $subject,
				'message' => $message));
			break;

//vCard
			case 'vCard':
			Main::getModule('Logger')->log('%s downloaded vCard from user ' . $this->userData[0] . ' (ID: ' . $this->userData[1] . ')', LOG_USER_TRAFFIC);
			Main::getModule('WhoIsOnline')->setLocation('vCard,' . $this->userData[1]);
			$vCard = "BEGIN:VCARD\nVERSION:3.0\nN:;;;;\nFN:" . $this->userData[12] . "\nNICKNAME:" . $this->userData[0] . "\n" . ($this->userData[14][1] == '1' ? 'EMAIL;TYPE=INTERNET:' . $this->userData[3] . "\n" : '') . 'URL:' . $this->userData[9] . "\nCLASS:" . (Main::getModule('Config')->getCfgVal('must_be_logged_in') == 1 ? 'PRIVATE' : 'PUBLIC') . "\nX-GENERATOR:Tritanium Bulletin Board " . VERSION_PUBLIC . "\n" . (!empty($this->userData[13]) ? 'X-ICQ:' . $this->userData[13] . "\n" : '') . 'END:VCARD';
			header('Content-Disposition: attachment; filename=' . $this->userData[0] . '.vcf');
			header('Content-Length: ' . strlen($vCard));
			header('Content-Type: text/x-vCard; charset=' . Main::getModule('Language')->getString('vcard_encoding') . '; name=' . $this->userData[0] . '.vcf');
			exit($vCard);
			break;

//ViewAchievements
			case 'viewAchievements':
			Main::getModule('NavBar')->addElement(array(
				array(sprintf(Main::getModule('Language')->getString('view_profile_from_x'), $this->userData[0]), INDEXFILE . '?faction=profile&amp;profile_id=' . $this->userData[1] . SID_AMPER),
				array(Main::getModule('Language')->getString('steam_achievements'), INDEXFILE . '?faction=profile&amp;profile_id=' . $this->userData[1] . '&amp;mode=viewAchievements&amp;game=' . ($game = Functions::getValueFromGlobals('game')) . SID_AMPER)));
			if(Main::getModule('Config')->getCfgVal('achievements') != 1)
				Main::getModule('Template')->printMessage('function_deactivated');
			elseif(!class_exists('DOMDocument', false))
				Main::getModule('Template')->printMessage('function_not_supported');
			elseif(empty($this->userData[18]))
				Main::getModule('Template')->printMessage('no_steam_games');
			elseif(!in_array($game, $this->userData[19]))
				Main::getModule('Template')->printMessage('steam_game_not_found');
			$dom = new DOMDocument;
			if(!@$dom->loadXML(file_get_contents('http://steamcommunity.com/' . (is_numeric($this->userData[18]) ? 'profiles/' : 'id/') . $this->userData[18] . '/stats/' . $game . '/?tab=achievements&l=' . Main::getModule('Language')->getString('steam_language') . '&xml=1')))
				$this->errors[] = Main::getModule('Language')->getString('loading_achievements_failed');
			elseif($dom->getElementsByTagName('error')->length == 0)
			{
				$achievementsClosed = $achievementsOpen = array();
				//Get achievements, sorted by open/close state
				foreach(($achievements = $dom->getElementsByTagName('achievement')) as $curAchievement)
					if($curAchievement->attributes->getNamedItem('closed')->nodeValue == '1')
						$achievementsClosed[] = array('icon' => $curAchievement->getElementsByTagName('iconClosed')->item(0)->nodeValue,
							'name' => htmlspecialchars($curAchievement->getElementsByTagName('name')->item(0)->nodeValue),
							'description' => htmlspecialchars($curAchievement->getElementsByTagName('description')->item(0)->nodeValue));
					else
						$achievementsOpen[] = array('icon' => $curAchievement->getElementsByTagName('iconOpen')->item(0)->nodeValue,
							'name' => htmlspecialchars($curAchievement->getElementsByTagName('name')->item(0)->nodeValue),
							'description' => htmlspecialchars($curAchievement->getElementsByTagName('description')->item(0)->nodeValue));
				Main::getModule('Template')->assign(array('name' => $dom->getElementsByTagName('gameName')->item(0)->nodeValue,
					'logo' => $dom->getElementsByTagName('gameLogo')->item(0)->nodeValue,
					'icon' => $dom->getElementsByTagName('gameIcon')->item(0)->nodeValue,
					'numTotal' => $achievements->length,
					'numClosed' => ($done = count($achievementsClosed)),
					'numOpen' => count($achievementsOpen),
					//Calculate progess
					'percentClosed' => $achievements->length != '0' ? ($done / $achievements->length)*100 : 0,
					'achievementsClosed' => $achievementsClosed,
					'achievementsOpen' => $achievementsOpen));
			}
			else
				foreach($dom->getElementsByTagName('error') as $curError)
					$this->errors[] = $curError->nodeValue;
			break;

//ViewProfile
			case 'profile':
			default:
			Main::getModule('NavBar')->addElement(sprintf(Main::getModule('Language')->getString('view_profile_from_x'), $this->userData[0]), INDEXFILE . '?faction=profile&amp;profile_id=' . $this->userData[1] . SID_AMPER);
			//Check mail options
			if($this->userData[14][1] != '1')
				$this->userData[3] = false;
			$this->userData[14] = $this->userData[14][0] == '1';
			//Prepare rank
			$this->userData[2] = Functions::getRankImage($this->userData[4], $this->userData[5]); //Reuse "password slot"
			$this->userData[4] = Functions::getStateName($this->userData[4], $this->userData[5]);
			//Group stuff
			if(!empty($this->userData[15]))
			{
				$group = Functions::getGroupData($this->userData[15]);
				$this->userData[15] = $group[1];
				//Use the group's avatar if user has none
				if(empty($this->userData[10]))
					$this->userData[10] = $group[2];
			}
			//Prepare avatar
			if(!empty($this->userData[10]))
			{
				$this->userData[10] = Functions::addHTTP($this->userData[10]);
				list($this->userData['avatarWidth'], $this->userData['avatarHeight']) = array(Main::getModule('Config')->getCfgVal('avatar_width'), Main::getModule('Config')->getCfgVal('avatar_height'));
				if(Main::getModule('Config')->getCfgVal('use_getimagesize') == 1 && ($avatar = @getimagesize($this->userData[10])) != false)
				{
					if($this->userData['avatarWidth'] > $avatar[0])
						$this->userData['avatarWidth'] = $avatar[0];
					if($this->userData['avatarHeight'] > $avatar[1])
						$this->userData['avatarHeight'] = $avatar[1];
				}
			}
			//Joined x weeks ago
			$this->userData[8] = intval(($this->userData[11] = time()-Functions::getTimestamp($this->userData[6])) / 604800); //Reuse "forum access perms slot"
			//Posts per day
			$this->userData[11] = intval($this->userData[11] / 86400); //Reuse "forum update slot"
			if($this->userData[11] != 0)
				$this->userData[11] = $this->userData[5] / $this->userData[11];
			//Format date + signature
			$this->userData[6] = Functions::formatDate($this->userData[6] . (Functions::strlen($this->userData[6]) == 6 ? '01000000' : ''));
			$this->userData[7] = Main::getModule('BBCode')->parse($this->userData[7]);
			//Load steam games for user, if any (and class to handle XML data is available)
			if(Main::getModule('Config')->getCfgVal('achievements') == 1 && !empty($this->userData[18]) && !empty($this->userData[19][0]) && class_exists('DOMDocument', false))
			{
				$dom = new DOMDocument;
				if(!@$dom->loadXML(file_get_contents('http://steamcommunity.com/' . (is_numeric($this->userData[18]) ? 'profiles/' : 'id/') . $this->userData[18] . '/games/?tab=achievements&l=' . Main::getModule('Language')->getString('steam_language') . '&xml=1')))
					$this->userData[19] = array();
				else
				{
					$this->userData[18] = array('profileID' => $this->userData[18],
						'profileName' => $dom->getElementsByTagName('steamID')->item(0)->nodeValue);
					$games = array();
					//Extract all steam games from user
					foreach($dom->getElementsByTagName('game') as $curSteamGame)
					{
						$curStatLink = $curSteamGame->getElementsByTagName('statsLink');
						//Only consider games with stats
						if($curStatLink->length == 0)
							continue;
						//Filter relevant games according to provided game id
						if(in_array(($curGame = basename($curStatLink->item(0)->nodeValue)), $this->userData[19]))
							$games[] = array($curGame,
								$curSteamGame->getElementsByTagName('logo')->item(0)->nodeValue,
								$curSteamGame->getElementsByTagName('name')->item(0)->nodeValue);
					}
					$this->userData[19] = $games;
				}
			}
			break;
		}
		//Append profile ID for WIO location
		Main::getModule('Template')->printPage(self::$modeTable[$this->mode], array('userData' => $this->userData,
			'errors' => $this->errors), null, ',' . $this->userData[1]);
	}
}
?>