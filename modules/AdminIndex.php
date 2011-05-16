<?php
/**
 * Handles version check and displays the admin control panel.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class AdminIndex implements Module
{
	/**
	 * Checks for admin rights and displays the ACP.
	 */
	public function execute()
	{
		Functions::accessAdminPanel();
		//If version check was done before, cache result to session
		if(!isset($_SESSION['isNewVersion']) && isset($_COOKIE['versionCompare']))
		{
			$_SESSION['isNewVersion'] = $_COOKIE['versionCompare'] == '-1';
			setcookie('versionCompare', '', time()-60);
			//Also save news text in case of new version
			if($_SESSION['isNewVersion'] && isset($_COOKIE['versionNews']))
			{
				$_SESSION['versionNews'] = nl2br(base64_decode($_COOKIE['versionNews']));
				setcookie('versionNews', '', time()-60);
			}
		}
		Main::getModule('Template')->printPage('AdminIndex', array(
			'styleURL' => urlencode(Main::getModule('Config')->getCfgVal('address_to_forum') . '/' . Main::getModule('Template')->getTplDir() . Main::getModule('Auth')->getUserStyle()),
			'isNewVersion' => isset($_SESSION['isNewVersion']) ? $_SESSION['isNewVersion'] : true, //true on first time to get actual state for current session
			'versionNews' => isset($_SESSION['versionNews']) ? $_SESSION['versionNews'] : ''));
	}
}
?>