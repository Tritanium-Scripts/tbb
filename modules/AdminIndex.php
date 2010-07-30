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
		//Nothing much do here on the PHP side of life...
		Functions::accessAdminPanel();
		Main::getModule('Template')->printPage('AdminIndex');
	}
}
?>