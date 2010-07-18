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
	private static $modeTable = array('' => 'ViewProfile', 'profile' => 'ViewProfile', 'edit' => 'EditProfile', 'formmail' => 'SendMail', 'vCard' => 'vCard');

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
			elseif(!Main::getModule('Auth')->isAdmin() || Main::getModule('Auth')->getUserID != $this->userData[1])
				Main::getModule('Template')->printMessage('profile_no_access');
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
			$errors = array();
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
						$errors[] = Main::getModule('Language')->getString('please_enter_your_mail');
					elseif(!FunctionsBasic::isValidMail($senderMail))
						$errors[] = Main::getModule('Language')->getString('please_enter_a_valid_mail');
					if(empty($senderName))
						$errors[] = Main::getModule('Language')->getString('please_enter_your_name');
				}
				else
				{
					$senderMail = Main::getModule('Auth')->getUserMail();
					$senderName = Main::getModule('Auth')->getUserNick();
				}
				//Send it
				if(empty($errors))
					Main::getModule('Template')->printMessage(Functions::sendMessage($this->userData[3], 'mail_from_user', $this->userData[0], $senderMail, $senderMail, $subject, $message, INDEXFILE . '?faction=login') ? 'mail_sent' : 'sending_mail_failed');
			}
			//Recipient data (assigned automatically)
			$this->userData = array_slice($this->userData, 0, 2) + array('recipientName' => &$this->userData[0],
				'recipientID' => &$this->userData[1]);
			//Sender data
			Main::getModule('Template')->assign(array('senderName' => $senderName,
				'senderMail' => $senderMail,
				'subject' => $subject,
				'message' => $message,
				'errors' => $errors));
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
			$this->userData[10] = Functions::addHTTP($this->userData[10]);
			//Joined x weeks ago
			$this->userData[8] = intval(($this->userData[11] = time()-Functions::getTimestamp($this->userData[6])) / 604800); //Reuse "forum access perms slot"
			//Posts per day
			$this->userData[11] = intval($this->userData[11] / 86400); //Reuse "forum update slot"
			if($this->userData[11] != 0)
				$this->userData[11] = $this->userData[5] / $this->userData[11];
			//Format date + signature
			$this->userData[6] = Functions::formatDate($this->userData[6] . (Functions::strlen($this->userData[6]) == 6 ? '01000000' : ''));
			$this->userData[7] = Main::getModule('BBCode')->parse($this->userData[7]);
			break;
		}
		//Append profile ID for WIO location
		Main::getModule('Template')->printPage(self::$modeTable[$this->mode], 'userData', $this->userData, ',' . $this->userData[1]);
	}
}
?>