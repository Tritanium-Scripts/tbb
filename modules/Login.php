<?php
/**
 * Manages the login, request of new password and logout.
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

	/**
	 * Translates a mode to its template file.
	 *
	 * @var array Mode and template counterparts
	 */
	private static $modeTable = array('' => 'Login', 'login' => 'Login', 'verify' => 'Login', 'sendpw' => 'RequestPassword');

	/**
	 * Prepares and sets login name, login password and mode.
	 *
	 * @param string $fAction Mode to execute
	 * @return Login New instance of this class
	 */
	function __construct($fAction)
	{
		$this->loginName = Functions::latin9ToEntities(htmlspecialchars(trim(Functions::getValueFromGlobals('login_name'))));
		$this->loginPass = Functions::getValueFromGlobals('login_pw');
		$this->mode = $fAction;
	}

	/**
	 * Performs login, sending new password or logout.
	 */
	public function execute()
	{
		Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('login'), INDEXFILE . '?faction=login' . SID_AMPER);
		//If user is already logged in
		if(Main::getModule('Auth')->isLoggedIn() && $this->mode == 'login')
		{
			Main::getModule('Logger')->log('%s tried to log in again', LOG_FAILED_LOGIN);
			header('Location: ' . INDEXFILE . SID_QMARK);
			Main::getModule('Template')->printMessage('already_logged_in', Functions::getMsgBackLinks());
		}
		//Obsolete check?
		if(Functions::file_exists('vars/alarm.var'))
			Main::getModule('Template')->printMessage('board_disabled');
//Login
		switch($this->mode)
		{
			//Check login data
			case 'verify':
			if(empty($this->loginName))
				$this->errors[] = Main::getModule('Language')->getString('please_enter_your_user_name');
			if(empty($this->loginPass))
				$this->errors[] = Main::getModule('Language')->getString('please_enter_your_password');
			if(empty($this->errors))
			{
				//Prerequisite are met, prepare data
				$this->loginName = Functions::strtolower($this->loginName);
				$this->loginPass = Functions::getHash($this->loginPass);
				$found = false;
				//Start crawling by ignoring XBB files with leading zeros (=skip guest) and temporary ones
				foreach(glob(DATAPATH . 'members/[!0t]*.xbb') as $curMember)
				{
					$curMember = Functions::file($curMember, null, null, false);
					if($this->loginName == Functions::strtolower($curMember[0]))
					{
						$found = true;
						//Deleted user
						if($curMember[4] == '5')
						{
							$this->errors[] = Main::getModule('Language')->getString('user_not_found');
							$this->loginName = $curMember[0]; //Undo strtolower for template and log
							Main::getModule('Logger')->log('Login with deleted user "' . $curMember[0] . '" (ID: ' . $curMember[1] . ') failed', LOG_FAILED_LOGIN);
							break;
						}
						//Don't allow login for non-admins in case of active maintenance mode
						elseif(Main::getModule('Config')->getCfgVal('uc') == 1 && $curMember[4] != '1')
							Main::getModule('Template')->printMessage('maintenance_mode_on');
						//Wrong password
						elseif(!in_array($this->loginPass, ($curPasses = (Functions::explodeByTab($curMember[2] . "\t"))))) //Attach additional tab to make sure [1] is set in any case
						{
							$this->errors[] = Main::getModule('Language')->getString('wrong_password');
							$this->loginName = $curMember[0]; //Undo strtolower for template
							Main::getModule('Logger')->log('Login with wrong password for user "' . $curMember[0] . '" (ID: ' . $curMember[1] . ') failed', LOG_FAILED_LOGIN);
							break;
						}
						//All ok, do login
						else
						{
							//Update last seen value
							$curMember[16] = time();
							//Remove custom tpls and styles, if it was prohibited in the meantime
							if(Main::getModule('Config')->getCfgVal('select_tpls') != 1 && isset($curMember[20]) && !empty($curMember[20]))
								$curMember[20] = '';
							//Also check if style was not found for current tpl
							if(isset($curMember[21]) && !empty($curMember[21]) && (!file_exists(Main::getModule('Template')->getTplDir() . 'styles/' . $curMember[21]) || Main::getModule('Config')->getCfgVal('select_styles') != 1))
								$curMember[21] = '';
							//Set a new requested password as new default one
							if($this->loginPass == $curPasses[1])
							{
								$curMember[2] = $curPasses[1];
								Main::getModule('Logger')->log('Requested password set as new one for "' . $curMember[0] . '" (ID: ' . $curMember[1] . ')', LOG_NEW_PASSWORD);
							}
							Functions::file_put_contents('members/' . $curMember[1] . '.xbb', implode("\n", $curMember));
							//Login session-based
							$_SESSION['userID'] = $curMember[1];
							$_SESSION['userHash'] = $this->loginPass;
							//Login cookie-based
							setcookie('cookie_xbbuser', $curMember[1] . "\t" . $this->loginPass, Functions::getValueFromGlobals('stayli') == 'yes' ? time()+60*60*24*365 : 0, Main::getModule('Config')->getCfgVal('path_to_forum'));
							//Delete guest ID from WIO to work with user ID form now on
							Main::getModule('WhoIsOnline')->delete($_SESSION['session_upbwio']);
							//Set ghost state
							if(Functions::getValueFromGlobals('bewio') == 'yes')
								$_SESSION['bewio'] = true;
							Main::getModule('Auth')->loginChanged();
							//That's it
							Main::getModule('Logger')->log($curMember[0] . ' (ID: ' . $curMember[1] . ') logged in', LOG_LOGIN_LOGOUT);
							//Detect location to redir
							$location = $curMember[11] == '1' ? INDEXFILE . '?faction=profile&mode=edit' . SID_AMPER_RAW : (isset($_COOKIE['upbwhere']) && !empty($_COOKIE['upbwhere']) ? $_COOKIE['upbwhere'] : INDEXFILE . SID_QMARK);
							header('Location: ' . $location);
							Main::getModule('Template')->printMessage('successfully_logged_in', $location);
						}
					}
				}
				//Not found in "member DB"
				if(!$found)
				{
					$this->errors[] = Main::getModule('Language')->getString('user_not_found');
					Main::getModule('Logger')->log('Login with unknown user "' . $this->loginName . '" failed', LOG_FAILED_LOGIN);
				}
			}
			break;

//RequestPassword
			case 'sendpw':
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('request_new_password'), INDEXFILE . '?faction=sendpw' . SID_AMPER);
			if(Main::getModule('Config')->getCfgVal('activate_mail') != 1)
				Main::getModule('Template')->printMessage('function_deactivated');
			$this->loginName = Functions::getValueFromGlobals('nick');
			if(Functions::getValueFromGlobals('send') == '1')
			{
				if(empty($this->loginName))
					$this->errors[] = Main::getModule('Language')->getString('please_enter_your_user_name');
				else
				{
					//Prerequisites are met, prepare data and start crawling
					$this->loginName = Functions::strtolower($this->loginName);
					foreach(glob(DATAPATH . 'members/[!0t]*.xbb') as $curMember)
					{
						$curMember = Functions::file($curMember);
						if($this->loginName == Functions::strtolower($curMember[0]))
						{
							$curMember[2] = current(Functions::explodeByTab($curMember[2])) . "\t" . Functions::getHash($newPass = Functions::getRandomPass());
							Functions::file_put_contents('members/' . $curMember[1]. '.xbb', implode("\n", $curMember));
							if(!Functions::sendMessage($curMember[3], 'new_password_requested', $_SERVER['REMOTE_ADDR'], Functions::getValueFromGlobals('nick'), $newPass, Main::getModule('Config')->getCfgVal('address_to_forum') . '/' . INDEXFILE . '?faction=login'))
								Main::getModule('Template')->printMessage('sending_mail_failed');
							Main::getModule('Logger')->log('New password requested and sent to "' . $curMember[0] . '" (ID: ' . $curMember[1] . ')', LOG_NEW_PASSWORD);
							Main::getModule('Template')->printMessage('new_password_created');
						}
					}
					$this->errors[] = Main::getModule('Language')->getString('user_not_found');
					Main::getModule('Logger')->log('New password request for unknown user "' . $this->loginName . '" failed', LOG_NEW_PASSWORD);
				}
			}
			else
				//In case the nick was submitted from login formular (send != 1) additional decode is needed
				$this->loginName = urldecode($this->loginName);
			break;

//Logout
			case 'logout':
			if(Main::getModule('Auth')->isLoggedIn())
			{
				Main::getModule('Logger')->log('%s logged out', LOG_LOGIN_LOGOUT);
				//Delete user ID from WIO to work with previous guest ID form now on
				Main::getModule('WhoIsOnline')->delete($_SESSION['userID']);
				//Logout cookie-based
				setcookie('cookie_xbbuser', '', time()-1000, Main::getModule('Config')->getCfgVal('path_to_forum'));
				//Logout session-based
				unset($_SESSION['userID'], $_SESSION['userHash']);
				if(Main::getModule('Auth')->isGhost())
					unset($_SESSION['bewio']);
				Main::getModule('Auth')->loginChanged();
			}
			//Done, redir to forum index
			header('Location: ' . INDEXFILE . SID_QMARK);
			Main::getModule('Template')->printMessage('successfully_logged_out', INDEXFILE . SID_QMARK);
			break;
		}
		//Show formular (again)
		Main::getModule('Template')->printPage(self::$modeTable[array_key_exists($this->mode, self::$modeTable) ? $this->mode : '' . Main::getModule('Logger')->log('Unknown mode ' . $this->mode . ' in ' . __CLASS__ . '; using default', LOG_FILESYSTEM)], array('loginName' => $this->loginName,
			'errors' => $this->errors));
	}
}
?>