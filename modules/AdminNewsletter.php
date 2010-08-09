<?php
/**
 * Sends newsletter via PM or e-mail.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class AdminNewsletter implements Module
{
	/**
	 * Detected errors during newsletter actions.
	 *
	 * @var array Error messages
	 */
	private $errors = array();

	/**
	 * Contains mode to execute.
	 *
	 * @var string Newsletter mode
	 */
	private $mode;

	/**
	 * Translates a mode to its template file.
	 *
	 * @var array Mode and template counterparts
	 */
	private static $modeTable = array('ad_newsletter' => 'AdminNewsletter',
		'accept' => 'AdminNewsletterConfirm');

	/**
	 * Sets mode.
	 * 
	 * @param string $mode Mode
	 * @return AdminNewsletter New instance of this class
	 */
	function __construct($mode)
	{
		$this->mode = $mode;
	}

	/**
	 * Executes mode.
	 */
	public function execute()
	{
		Functions::accessAdminPanel();
		Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('send_newsletter'), INDEXFILE . '?faction=ad_newsletter' . SID_AMPER);
		switch($this->mode)
		{
			case 'accept':
			break;

			default:
			break;
		}
		Main::getModule('Template')->printPage(self::$modeTable[$this->mode], array('errors' => $this->errors));
	}
}
?>