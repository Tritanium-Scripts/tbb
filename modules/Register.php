<?php
/**
 * Manages registrations of new user.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class Register implements Module
{
	/**
	 * Detected errors during registration.
	 *
	 * @var array Error messages
	 */
	private $errors = array();

	/**
	 * Amount of registered members.
	 *
	 * @var int Amount of members
	 */
	private $memberCounter;

	/**
	 * Mode to execute.
	 *
	 * @var string Current registration mode
	 */
	private $mode;

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
		$this->memberCounter = intval(Functions::file_get_contents('vars/member_counter.var'));
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
		elseif($this->memberCounter >= Main::getModule('Config')->getCfgVal('max_registrations') && Main::getModule('Config')->getCfgVal('max_registrations') != -1)
			Main::getModule('Template')->printMessage('no_more_registrations');
		//If user is already logged in
		elseif(Main::getModule('Auth')->isLoggedIn())
		{
			Main::getModule('Logger')->log('%s tried to register again', LOG_REGISTRATION);
			header('Location: ' . INDEXFILE . SID_QMARK);
			Main::getModule('Template')->printMessage('already_registered', INDEXFILE . SID_QMARK);
		}
		switch($this->mode)
		{
			case 'createuser':
			$newUser = array_combine(self::$newUserKeys, array(Functions::getValueFromGlobals('newuser_name'),
				Functions::getValueFromGlobals('newuser_email'),
				Functions::getValueFromGlobals('newuser_hp'),
				Functions::getValueFromGlobals('newuser_realname'),
				Functions::getValueFromGlobals('newuser_icq'),
				Functions::nl2br(Functions::getValueFromGlobals('newuser_signatur'))));
			//A lot of checking...
			if(empty($newUser['nick']))
				$this->errors[] = Main::getModule('Language')->getString('please_enter_an_user_name');
			elseif(Functions::strlen($newUser['nick']) > 15)
				$this->errors[] = Main::getModule('Language')->getString('the_user_name_is_too_long');
			elseif(Functions::unifyUserName($newUser['nick']))
				$this->errors[] = Main::getModule('Language')->getString('the_user_name_already_exists');
			if(Main::getModule('Config')->getCfgVal('create_reg_pw') != 1)
			{
				if(($newPass = Functions::getValueFromGlobals('newuser_pw1')) == '')
					$this->errors[] = Main::getModule('Language')->getString('please_enter_a_password');
				elseif($newPass != Functions::getValueFromGlobals('newuser_pw2'))
					$this->errors[] = Main::getModule('Language')->getString('passwords_do_not_match');
				else
					$newPass = Functions::getHash($newPass);
			}
			else
				$newPass = Functions::getRandomPass();
			if(empty($newUser['mail']))
				$this->errors[] = Main::getModule('Language')->getString('please_enter_your_mail');
			elseif(!Functions::isValidMail($newUser['mail']))
				$this->errors[] = Main::getModule('Language')->getString('please_enter_a_valid_mail');
			if(!empty($newUser['icq']) && !is_numeric($newUser['icq']))
				$this->errors[] = Main::getModule('Language')->getString('please_enter_a_valid_icq_number');
			if(Functions::getValueFromGlobals('regeln') != 'yes')
				$this->errors[] = Main::getModule('Language')->getString('you_have_to_accept_board_rules');
			if(empty($this->errors))
			{
				//Detect new ID
				$newUserID = Functions::file_get_contents('vars/last_user_id.var')+1;
				//Prepare contents of new member file
				$newMemberFile = array($newUser['nick'],
					$newUserID,
					Main::getModule('Config')->getCfgVal('create_reg_pw') != 1 ? $newPass : Functions::getHash($newPass),
					$newUser['mail'],
					//First user is admin
					$newUserID == 1 ? '1' : '3',
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
					'');
				//Register as new member only, if no mail validation is required
				if(Main::getModule('Config')->getCfgVal('create_reg_pw') != 2)
				{
					Functions::file_put_contents('members/' . $newUserID . '.xbb', implode("\n", $newMemberFile));
					Functions::file_put_contents('members/' . $newUserID . '.pm', '');
					Functions::file_put_contents('vars/last_user_id.var', $newUserID);
					Functions::file_put_contents('vars/member_counter.var', $this->memberCounter+1);
					//Send random pass, if needed
					if(Main::getModule('Config')->getCfgVal('create_reg_pw') == 1)
						Functions::sendMessage($newMemberFile[3], 'new_registration_pass', Main::getModule('Config')->getCfgVal('forum_name'), $newMemberFile[0], $newPass, Main::getModule('Config')->getCfgVal('address_to_forum') . '/' . INDEXFILE);
					Main::getModule('Logger')->log('New registration: ' . $newMemberFile[0] . ' (ID: ' . $newMemberFile[1] . ')', LOG_REGISTRATION);
					if(Main::getModule('Config')->getCfgVal('mail_admin_new_registration') == 1)
						Functions::sendMessage(Main::getModule('Config')->getCfgVal('admin_email'), 'admin_new_registration', $newMemberFile[0], $newMemberFile[1]);
					Main::getModule('Template')->printMessage('registration_successful');
				}
				//Save data only temporarily until confirmation
				else
				{
					Functions::file_put_contents('members/temp' . $newMemberFile[16] . '.xbb', implode("\n", $newMemberFile));
					Functions::sendMessage($newMemberFile[3], 'new_registration_mail', Main::getModule('Config')->getCfgVal('forum_name'), $newMemberFile[0], Main::getModule('Config')->getCfgVal('address_to_forum') . '/' . INDEXFILE);
					Main::getModule('Template')->printMessage('registration_successful');
				}
			}
			break;

			case 'register':
			default:
			$newUser = array_combine(self::$newUserKeys, array('', '', '', '', '', ''));
			break;
		}
		Main::getModule('Template')->printPage('Register', array('newUser' => $newUser,
			'errors' => $this->errors));
	}
}
?>