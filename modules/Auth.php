<?php
/**
 * Login state and data of current user.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class Auth
{
	/**
	 * Login state of current user.
	 *
	 * @var bool Login state
	 */
	private $loggedIn = false;

	/**
	 * Loaded user data or default guest values.
	 *
	 * @var array User data
	 */
	private $userData = array();

	/**
	 * Loads user data from XBB file according to user ID in the session or cookie.
	 */
	function __construct()
	{
		$this->userData = Functions::file('members/' . (isset($_SESSION['userID'], $_SESSION['userHash']) && file_exists('members/' . $_SESSION['userID'] . '.xbb') ? $_SESSION['userID'] : '0') . '.xbb');
		//Check session-based login
		if(isset($_SESSION['userHash']) && $_SESSION['userHash'] == $this->userData[2])
			$this->loggedIn = true;
		//Check cookie-based login
		elseif(isset($_COOKIE['cookie_xbbuser']))
		{
			$cUser = explode("\t", $_COOKIE['cookie_xbbuser']);
			if(!empty($cUser[1]) && ($cUserData = Functions::file('members/' . $cUser[0] . '.xbb') != false) && $cUser[1] == $cUserData[2])
			{
				$this->loggedIn = true;
				$_SESSION['userID'] = $cUser[0];
				$_SESSION['userHash'] = $cUser[1];
				$this->userData = $cUserData;
			}
		}
	}

	/**
	 * Returns user has admin permissions.
	 *
	 * @return bool Admin permissions
	 */
	public function isAdmin()
	{
		return $this->userData[4] == '1';
	}

	/**
	 * Returns user is already connected to board.
	 *
	 * @return bool Connection state
	 */
	public function isConnected()
	{
		return isset($_SESSION['connected']);
	}

	/**
	 * Returns login state of user.
	 *
	 * @return bool User logged in
	 */
	public function isLoggedIn()
	{
		return $this->loggedIn;
	}

	/**
	 * Returns user has moderator permissions.
	 *
	 * @return bool Moderator permissions
	 */
	public function isMod()
	{
		return $this->userData[4] <= '2';
	}

	/**
	 * Sets user has connected to board.
	 */
	public function setConnected()
	{
		$_SESSION['connected'] = true;
	}
}
?>