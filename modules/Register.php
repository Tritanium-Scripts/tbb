<?php
/**
 * Manages registrations of new user.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010, 2011 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.6
 */
class Register implements Module
{
	/**
	 * If passwords for new user should be created automatically.
	 *
	 * @var bool User may not choose an own password
	 */
	private $createRegPass;

	/**
	 * Detected errors during registration.
	 *
	 * @var array Error messages
	 */
	private $errors = array();

	/**
	 * Amount of registered members.
	 *
	 * @var LockObject Amount of members
	 */
	private $memberCounter;

	/**
	 * Mode to execute.
	 *
	 * @var string Current registration mode
	 */
	private $mode;

	/**
	 * Translates a mode to its template file.
	 *
	 * @var array Mode and template counterparts
	 */
	private static $modeTable = array('' => 'Register', 'register' => 'Register', 'createuser' => 'Register', 'verifyAccount' => 'RegisterVerification');

	/**
	 * Provides named keys for new user data.
	 *
	 * @var array Named keys
	 */
	private static $newUserKeys = array('nick', 'mail', 'homepage', 'realName', 'icq', 'signature');

	/**
	 * Sets member counter and mode.
	 *
	 * @param string $mode Registration mode
	 * @return Register New instance of this class
	 */
	function __construct($mode)
	{
		$this->createRegPass = Main::getModule('Config')->getCfgVal('create_reg_pw') == 1;
		$this->memberCounter = Functions::getLockObject('vars/member_counter.var');
		$this->mode = $mode;
	}

	/**
	 * Performs new registrations.
	 */
	public function execute()
	{
		Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('register'), INDEXFILE . '?faction=register' . SID_AMPER);
		if(Main::getModule('Config')->getCfgVal('activate_registration') != 1)
			Main::getModule('Template')->printMessage('registration_deactivated');
		elseif($this->memberCounter->getFileContent() >= Main::getModule('Config')->getCfgVal('max_registrations') && Main::getModule('Config')->getCfgVal('max_registrations') != -1)
			Main::getModule('Template')->printMessage('no_more_registrations');
		//If user is already logged in
		elseif(Main::getModule('Auth')->isLoggedIn())
		{
			Main::getModule('Logger')->log('%s tried to register again', LOG_REGISTRATION);
			header('Location: ' . INDEXFILE . SID_QMARK);
			Main::getModule('Template')->printMessage('already_registered', Functions::getMsgBackLinks());
		}
		switch($this->mode)
		{
//Register
			case 'createuser':
			$newUser = array_combine(self::$newUserKeys, array(trim(Functions::getValueFromGlobals('newuser_name')),
				trim(Functions::getValueFromGlobals('newuser_email')),
				trim(Functions::getValueFromGlobals('newuser_hp')),
				htmlspecialchars(trim(Functions::getValueFromGlobals('newuser_realname'))),
				trim(Functions::getValueFromGlobals('newuser_icq')),
				htmlspecialchars(trim(Functions::nl2br(Functions::getValueFromGlobals('newuser_signatur', false))))));
			//A lot of checking...
			if(empty($newUser['nick']))
				$this->errors[] = Main::getModule('Language')->getString('please_enter_an_user_name');
			elseif(Functions::strlen($newUser['nick']) > 15)
				$this->errors[] = Main::getModule('Language')->getString('the_user_name_is_too_long');
			elseif(Functions::unifyUserName($newUser['nick']))
				$this->errors[] = Main::getModule('Language')->getString('the_user_name_already_exists');
			else
				$newUser['nick'] = htmlspecialchars($newUser['nick']);
			if(!$this->createRegPass)
			{
				//In case of not creating a pass for new user, check the given one, too
				if(($newPass = Functions::getValueFromGlobals('newuser_pw1')) == '')
					$this->errors[] = Main::getModule('Language')->getString('please_enter_a_password');
				elseif($newPass != Functions::getValueFromGlobals('newuser_pw2'))
					$this->errors[] = Main::getModule('Language')->getString('passwords_do_not_match');
				else
					//If ok, hash it - the original pw is not longer needed to know
					$newPass = Functions::getHash($newPass);
			}
			else
				//In case of creating a pass for new user, get it here, but don't hash it yet
				$newPass = Functions::getRandomPass();
			if(empty($newUser['mail']))
				$this->errors[] = Main::getModule('Language')->getString('please_enter_your_mail');
			elseif(!Functions::isValidMail($newUser['mail']))
				$this->errors[] = Main::getModule('Language')->getString('please_enter_a_valid_mail');
			elseif(Functions::unifyUserMail($newUser['mail']))
				$this->errors[] = Main::getModule('Language')->getString('the_mail_address_already_exists');
			if(!empty($newUser['icq']) && !is_numeric($newUser['icq']))
				$this->errors[] = Main::getModule('Language')->getString('please_enter_a_valid_icq_number');
			if(Functions::getValueFromGlobals('regeln') != 'yes')
				$this->errors[] = Main::getModule('Language')->getString('you_have_to_accept_board_rules');
			if(empty($this->errors))
			{
				//Detect new ID
				$lockObj = Functions::getLockObject('vars/last_user_id.var');
				$newUserID = $lockObj->getFileContent()+1;
				//Prepare contents of new member file
				$newMemberFile = array($newUser['nick'],
					$newUserID,
					!$this->createRegPass ? $newPass : Functions::getHash($newPass),
					$newUser['mail'],
					$newUserID == 1 ? '1' : '3', //First user is admin
					'0',
					date('YmdHis'),
					$newUser['signature'],
					'',
					$newUser['homepage'],
					'',
					'0',
					$newUser['realName'],
					$newUser['icq'],
					'1,1',
					'',
					//New TBB 1.5 values
					time(),
					'',
					'',
					'',
					'',
					'');
				//Register as new member only, if no mail validation is required
				if(Main::getModule('Config')->getCfgVal('confirm_reg_mail') != 1)
				{
					Functions::file_put_contents('members/' . $newUserID . '.xbb', implode("\n", $newMemberFile));
					Functions::file_put_contents('members/' . $newUserID . '.pm', '');
					$lockObj->setFileContent($newUserID);
					$this->memberCounter->setFileContent($this->memberCounter->getFileContent()+1);
					//Send reg mail (and random pass, if needed)
					Functions::sendMessage($newMemberFile[3], 'new_registration', $newMemberFile[0], Main::getModule('Config')->getCfgVal('forum_name'), $newMemberFile[1], $newMemberFile[3], $this->createRegPass ? $newPass : Main::getModule('Language')->getString('already_set_by_yourself'), Main::getModule('Config')->getCfgVal('address_to_forum') . '/' . INDEXFILE);
					Main::getModule('Logger')->log('New registration: ' . $newMemberFile[0] . ' (ID: ' . $newMemberFile[1] . ')', LOG_REGISTRATION);
					//Notify admin
					if(Main::getModule('Config')->getCfgVal('mail_admin_new_registration') == 1)
						Functions::sendMessage(Main::getModule('Config')->getCfgVal('admin_email'), 'admin_new_registration', Main::getModule('Config')->getCfgVal('forum_name'), $newMemberFile[0], $newMemberFile[1], $newMemberFile[3]);
					if($this->createRegPass)
						Main::getModule('Template')->printMessage('registration_successful_pass', $newMemberFile[0]);
					else
						Main::getModule('Template')->printMessage('registration_successful_plain', $newMemberFile[0], INDEXFILE . '?faction=login' . SID_AMPER);
				}
				//Save data only temporarily until mail addy is confirmed
				else
				{
					Functions::file_put_contents('members/temp' . $newMemberFile[16] . '.xbb', implode("\n", $newMemberFile));
					Functions::sendMessage($newMemberFile[3], 'validate_new_registration', $newMemberFile[0], Main::getModule('Config')->getCfgVal('forum_name'), Main::getModule('Config')->getCfgVal('address_to_forum') . '/' . INDEXFILE . '?faction=register&mode=verifyAccount&code=' . md5('temp' . $newMemberFile[16]), Main::getModule('Config')->getCfgVal('address_to_forum') . '/' . INDEXFILE . '?faction=register&mode=verifyAccount', md5('temp' . $newMemberFile[16]));
					Main::getModule('Logger')->log('New registration waiting for mail validation: ' . $newMemberFile[0] . ' (preliminary ID: temp' . $newMemberFile[16] . ')', LOG_REGISTRATION);
					Main::getModule('Template')->printMessage('registration_successful_mail', $newMemberFile[0]);
				}
			}
			break;

//RegisterVerification
			case 'verifyAccount':
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('activate_account'), INDEXFILE . '?faction=register&amp;mode=verifyAccount' . SID_AMPER);
			if(($code = Functions::getValueFromGlobals('code')) != '')
			{
				foreach(glob(DATAPATH . 'members/temp*.xbb') as $curPreMember)
					if($code == md5(basename($curPreMember, '.xbb')))
					{
						//Get temporarily data of verfied member
						$newMemberFile = Functions::file($curPreMember, null, null, false);
						//Generate password, if needed
						if($this->createRegPass)
							$newMemberFile[2] = Functions::getHash($newPass = Functions::getRandomPass());
						//Update last seen
						$newMemberFile[16] = time();
						//Detect new ID
						$lockObj = Functions::getLockObject('vars/last_user_id.var');
						$newUserID = $lockObj->getFileContent()+1;
						//Apply to new member: update current one
						$newMemberFile[1] = $newUserID;
						//Write confirmed data with new ID (and new pass, if needed)
						Functions::file_put_contents('members/' . $newUserID . '.xbb', implode("\n", $newMemberFile));
						Functions::file_put_contents('members/' . $newUserID . '.pm', '');
						$lockObj->setFileContent($newUserID);
						$this->memberCounter->setFileContent($this->memberCounter->getFileContent()+1);
						//Delete old temporarily data
						Functions::unlink($curPreMember);
						//Send default reg mail (and random pass, if needed)
						Functions::sendMessage($newMemberFile[3], 'new_registration', $newMemberFile[0], Main::getModule('Config')->getCfgVal('forum_name'), $newMemberFile[1], $newMemberFile[3], $this->createRegPass ? $newPass : Main::getModule('Language')->getString('already_set_by_yourself'), Main::getModule('Config')->getCfgVal('address_to_forum') . '/' . INDEXFILE);
						Main::getModule('Logger')->log('New registration verified: ' . $newMemberFile[0] . ' (ID: ' . $newMemberFile[1] . ')', LOG_REGISTRATION);
						//Notify admin
						if(Main::getModule('Config')->getCfgVal('mail_admin_new_registration') == 1)
							Functions::sendMessage(Main::getModule('Config')->getCfgVal('admin_email'), 'admin_new_registration', Main::getModule('Config')->getCfgVal('forum_name'), $newMemberFile[0], $newMemberFile[1], $newMemberFile[3]);
						if($this->createRegPass)
							Main::getModule('Template')->printMessage('registration_successful_pass', $newMemberFile[0]);
						else
							Main::getModule('Template')->printMessage('registration_successful_plain', $newMemberFile[0], INDEXFILE . '?faction=login' . SID_AMPER);
					}
				$this->errors[] = Main::getModule('Language')->getString('no_account_for_code_found');
			}
			elseif(isset($_POST['verify']))
				$this->errors[] = Main::getModule('Language')->getString('please_enter_your_code');
			$newUser = array('code' => $code);
			break;

//Register
			case 'register':
			default:
			$newUser = array_combine(self::$newUserKeys, array('', '', '', '', '', ''));
			break;
		}
		Main::getModule('Template')->printPage(FunctionsBasic::handleMode($this->mode, self::$modeTable, __CLASS__), array('newUser' => $newUser,
			'errors' => $this->errors,
			'rulesLink' => INDEXFILE . '?faction=regeln' . SID_AMPER));
	}
}
?>