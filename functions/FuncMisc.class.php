<?php

class FuncMisc {
	public static function printMessage($message, $additionalLinks = array(), $inPopup = FALSE) {
		$Language = Factory::singleton('Language');
		$Navbar = Factory::singleton('Navbar');
		$Template = Factory::singleton('Template');

		$Language->addFile('Messages');

		$messageTitle = is_array($message) ? $message[0] : $Language->getString('message_title_'.$message);
		$messageText = is_array($message) ? $message[1] : $Language->getString('message_text_'.$message);

		$Navbar->addElement($messageTitle,'');

		$Template->printMessage($messageTitle,$messageText,$additionalLinks,$inPopup);
	}
}

?>