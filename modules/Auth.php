<?php
/**
 * Login state and data of current user.
 *
 * XBB user file structure:
 * <ol>
 *  <li>Nick</li>
 *  <li>User ID</li>
 *  <li>Password Hash</li>
 *  <li>E-Mail</li>
 *  <li>State:
 *   <ol>
 *    <li>Admin</li>
 *    <li>Mod</li>
 *    <li>User</li>
 *    <li>Banned</li>
 *    <li>Deleted</li>
 *   </ol>
 *  </li>
 *  <li>Posts</li>
 *  <li>Reg date (year+month)</li>
 *  <li>Signature</li>
 *  <li>Forum access permissions</li>
 *  <li>Homepage</li>
 *  <li>Avatar</li>
 *  <li>Update state?</li>
 *  <li>Real name</li>
 *  <li>ICQ</li>
 *  <li>Mail options</li>
 *  <li>Group ID</li>
 *  <li>?</li>
 * </ol>
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

	private $wioID;

	/**
	 * Loads user data from XBB file according to user ID in the session or cookie.
	 */
	function __construct()
	{
		$this->userData = Functions::file('members/' . (isset($_SESSION['userID'], $_SESSION['userHash']) && Functions::file_exists('members/' . $_SESSION['userID'] . '.xbb') ? $_SESSION['userID'] : '0') . '.xbb');
		//Check session-based login
		if(isset($_SESSION['userHash']) && $_SESSION['userHash'] == $this->userData[2])
			$this->loggedIn = true;
		//Check cookie-based login
		elseif(isset($_COOKIE['cookie_xbbuser']))
		{
			$cUser = Functions::explodeByTab($_COOKIE['cookie_xbbuser']);
			if(!empty($cUser[1]) && ($cUserData = Functions::file('members/' . $cUser[0] . '.xbb') != false) && $cUser[1] == $cUserData[2])
			{
				$this->loggedIn = true;
				$_SESSION['userID'] = $cUser[0];
				$_SESSION['userHash'] = $cUser[1];
				$this->userData = $cUserData;
			}
		}
		//Set special ID for WIO
		$this->wioID = $this->loggedIn ? $this->getUserID() : (isset($_SESSION['session_upbwio']) ? $_SESSION['session_upbwio'] : ($_SESSION['session_upbwio'] = 'guest' . mt_rand(10000, 99999)));
	}

	/**
	 * Returns group ID of user (empty string = no group).
	 *
	 * @return string Group ID
	 */
	public function getGroupID()
	{
		return $this->userData[15];
	}

	/**
	 * Returns either User ID (if logged in) or special guest ID.
	 *
	 * @return int|string Speical ID for WIO list
	 */
	public function getWIOID()
	{
		return $this->wioID;
	}

	/**
	 * Returns ID of user. 0 = Guest!
	 *
	 * @return int User ID
	 */
	public function getUserID()
	{
		return intval($this->userData[1]);
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
	 * Returns user is logged in (if he really is!) as a ghost.
	 *
	 * @return bool User hides from WIO
	 */
	public function isGhost()
	{
		return isset($_SESSION['bewio']);
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
	 * Returns user has moderator rank.
	 *
	 * @return bool Moderator rank
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