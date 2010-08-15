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
 *    <li>Member</li>
 *    <li>Banned</li>
 *    <li>Deleted</li>
 *    <li>[Super Mod]</li>
 *   </ol>
 *  </li>
 *  <li>Posts</li>
 *  <li>Reg date (year+month)</li>
 *  <li>Signature</li>
 *  <li>Forum access permissions [obsolete]</li>
 *  <li>Homepage</li>
 *  <li>Avatar</li>
 *  <li>Forum updated state</li>
 *  <li>Real name</li>
 *  <li>ICQ</li>
 *  <li>Mail options:
 *   <ol>
 *    <li>mails from forum</li>
 *    <li>show mail</li>
 *   </ol>
 *  </li>
 *  <li>Group ID</li>
 *  <li>[timestamp]</li>
 *  <li>[specialState]</li>
 *  <li>[steamName]</li>
 *  <li>[steamGames]</li>
 *  <li>[ownTemplate]</li>
 *  <li>[ownStyle]</li>
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
	 * User connected first time to board.
	 *
	 * @var bool Connection state
	 */
	private $connected = false;

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
	 * Special ID for WIO listing.
	 *
	 * @var int|string User ID or special guest ID
	 */
	private $wioID;

	/**
	 * Loads user data from XBB file according to user ID in the session or cookie.
	 *
	 * @return Auth New instance of this class
	 */
	function __construct()
	{
		$this->userData = Functions::file('members/' . (isset($_SESSION['userID'], $_SESSION['userHash']) && Functions::file_exists('members/' . $_SESSION['userID'] . '.xbb') ? $_SESSION['userID'] : '0') . '.xbb');
		//Check session-based login
		if(isset($_SESSION['userHash']) && $_SESSION['userHash'] == current(Functions::explodeByTab($this->userData[2])))
			$this->loggedIn = true;
		//Check cookie-based login
		elseif(isset($_COOKIE['cookie_xbbuser']))
		{
			$cUser = Functions::explodeByTab($_COOKIE['cookie_xbbuser']);
			if(!empty($cUser[1]) && ($cUserData = @Functions::file('members/' . $cUser[0] . '.xbb')) != false && $cUser[1] == $cUserData[2])
			{
				$this->loggedIn = true;
				$_SESSION['userID'] = $cUser[0];
				$_SESSION['userHash'] = $cUser[1];
				$this->userData = $cUserData;
			}
		}
		//Set connection state and special ID for WIO
		if(!isset($_SESSION['session_upbwio']))
			$_SESSION['session_upbwio'] = 'guest' . mt_rand(10000, 99999);
		else
			$this->connected = true;
		$this->wioID = $this->loggedIn ? $this->getUserID() : $_SESSION['session_upbwio'];
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
	 * Returns ID of user. 0 = Guest!
	 *
	 * @return int User ID
	 */
	public function getUserID()
	{
		return intval($this->userData[1]);
	}

	/**
	 * Returns e-mail of user.
	 *
	 * @return string User e-mail address
	 */
	public function getUserMail()
	{
		return $this->userData[3];
	}

	/**
	 * Returns nick of user.
	 *
	 * @return string User nick
	 */
	public function getUserNick()
	{
		return $this->userData[0];
	}

	/**
	 * Returns signature of user.
	 *
	 * @return string User signature
	 */
	public function getUserSig()
	{
		return $this->userData[7];
	}

	/**
	 * Returns custom style setting of user, default otherwise.
	 *
	 * @return string CSS filename
	 */
	public function getUserStyle()
	{
		return isset($this->userData[21]) && !empty($this->userData[21]) ? 'styles/' . $this->userData[21] : Main::getModule('Config')->getCfgVal('css_file');
	}

	/**
	 * Returns custom template setting of user, default otherwise.
	 *
	 * @return string Template identifier
	 */
	public function getUserTpl()
	{
		return isset($this->userData[20]) && !empty($this->userData[20]) ? $this->userData[20] : Main::getModule('Config')->getCfgVal('default_tpl');
	}

	/**
	 * Returns either User ID (if logged in) or special guest ID.
	 *
	 * @return int|string Special ID for WIO list
	 */
	public function getWIOID()
	{
		return $this->wioID;
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
	 * Returns user is banned.
	 *
	 * @return bool Banned
	 */
	public function isBanned()
	{
		return $this->userData[4] == '4';
	}

	/**
	 * Returns user is already connected to board.
	 *
	 * @return bool Connection state
	 */
	public function isConnected()
	{
		return $this->connected;
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
	 * Returns user is *somewhere* moderator of a forum.
	 * This does NOT rely on a context of a current forum!
	 *
	 * @return bool Moderator rank
	 */
	public function isMod()
	{
		return $this->userData[4] == '2' || $this->userData[4] == '6';
	}

	/**
	 * Returns user has super moderator permissions.
	 *
	 * @return bool Super moderator permissions
	 */
	public function isSuperMod()
	{
		return $this->userData[4] == '6';
	}

	/**
	 * Reloads the WIO ID after user logs in or out to supply correct IDs to WIO list.
	 */
	public function loginChanged()
	{
		$this->wioID = isset($_SESSION['userID']) ? $_SESSION['userID'] : $_SESSION['session_upbwio'];
		$this->loggedIn = is_numeric($this->wioID);
	}
}
?>