<?php
/**
 * Manages the login process.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class Login implements Module
{
	/**
	 * Detected errors during login process.
	 *
	 * @var array Error messages
	 */
	private $errors = array();

	/**
	 * Entered login name.
	 *
	 * @var string Login name
	 */
	private $loginName;

	/**
	 * Entered password.
	 *
	 * @var string Password
	 */
	private $loginPass;

	/**
	 * Contains subaction of this module.
	 *
	 * @var string Mode as subaction
	 */
	private $mode;

	function __construct($fAction)
	{
		$this->loginName = Functions::getValueFromGlobals('login_name');
		$this->loginPass = Functions::getValueFromGlobals('login_pw');
		$this->mode = $fAction;
	}

	/**
	 * Performs the login.
	 */
	public function execute()
	{
		//If user is already logged in
		if(Main::getModule('Auth')->isLoggedIn() && $this->mode == 'login')
		{
			Main::getModule('Logger')->log('%s tried to log in again', LOG_FAILED_LOGIN);
			header('Location: ' . INDEXFILE . SID_QMARK);
			Main::getModule('Template')->printMessage('already_logged_in', INDEXFILE . SID_QMARK);
		}
		//Check login data
		if($this->mode == 'verify')
		{
			if(empty($this->loginName))
				$this->errors[] = Main::getModule('Language')->getString('please_enter_a_name');
			if(empty($this->loginPass))
				$this->errors[] = Main::getModule('Language')->getString('please_enter_a_password');
			else
			{
				//Prerequisite were met, prepare data
				$this->loginName = strtolower($this->loginName);
				$this->loginPass = Functions::getHash($this->loginPass);
				//Start crawling by ignoring XBB files with leading zeros (=skip guest)
				foreach(glob(DATAPATH . 'members/[!0]*.xbb') as $curMember)
				{
					$curMember = Functions::file($curMember);
					if($this->loginName == strtolower($curMember[0]))
						if($curMember[4] == '5')
						{
							$this->errors[] = Main::getModule('Language')->getString('user_not_found');
							Main::getModule('Logger')->log('Login with deleted user "' . $this->loginName . '" failed', LOG_LOGIN_LOGOUT);
							break;
						}
						elseif($curMember[2] != $this->loginPass)
						{
							$this->errors[] = Main::getModule('Language')->getString('wrong_password');
							Main::getModule('Logger')->log('Login with wrong password for user "' . $this->loginName . '" failed', LOG_LOGIN_LOGOUT);
							break;
						}
						else
						{
							//todo...
							header('Location: ' . INDEXFILE . SID_QMARK);
						}
				}
				$this->errors[] = Main::getModule('Language')->getString('user_not_found');
				Main::getModule('Logger')->log('Login with unknown user "' . $this->loginName . '" failed', LOG_LOGIN_LOGOUT);
			}
		}
		//Show formular (again)
		Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('login'));
		Main::getModule('Template')->printPage('Login', array('loginName' => $this->loginName,
			'errors' => $this->errors));
	}
}
?>