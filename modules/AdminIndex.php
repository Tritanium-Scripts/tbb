<?php
/**
 * Displays the admin control panel.
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
		Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('administration'), INDEXFILE . '?faction=adminpanel' . SID_AMPER);
		if(!Main::getModule('Auth')->isAdmin())
		{
			Main::getModule('Logger')->log('%s tried to access administration', LOG_ACP_ACCESS);
			Main::getModule('Template')->printMessage('permission_denied');
		}
		//Nothing much do here on the PHP side of life...
		Main::getModule('Config')->setCfgVal('twidth', '100%');
		Main::getModule('Template')->printPage('AdminIndex');
	}
}
?>