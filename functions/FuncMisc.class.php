<?php
/**
 * @author Julian Backes <julian@tritanium-scripts.com>
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2003 - 2009, Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package tbb2
 */
class FuncMisc {
	public static function printMessage($message, $additionalLinks = array()) {
		$Language = Factory::singleton('Language');
		$Navbar = Factory::singleton('Navbar');
		$Template = Factory::singleton('Template');

		$Language->addFile('Messages');

		$messageTitle = is_array($message) ? $message[0] : $Language->getString('message_title_'.$message);
		$messageText = is_array($message) ? $message[1] : $Language->getString('message_text_'.$message);

		$Navbar->addElement($messageTitle,'');

		$Template->printMessage($messageTitle,$messageText,$additionalLinks);
	}
}