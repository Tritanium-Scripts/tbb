<?php
/**
 * Manages archived newsletters.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class Newsletter implements Module
{
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
	private static $modeTable = array('newsletter' => 'Newsletter',
		'read' => 'NewsletterReadLetter');

	/**
	 * Archived newsletter.
	 *
	 * @var array All available newsletter
	 */
	private $newsletter;

	/**
	 * ID (=date) of current newsletter.
	 *
	 * @var int Newsletter ID
	 */
	private $newsletterID;

	/**
	 * Sets mode, ID and loads available newsletter.
	 *
	 * @param string $mode Mode
	 * @return Newsletter New instance of this class
	 */
	function __construct($mode)
	{
		$this->mode = $mode;
		$this->newsletterID = Functions::getValueFromGlobals('newsletter');
		$this->newsletter = @Functions::file('vars/newsletter.var') or $this->newsletter = array();
		if(!empty($this->newsletter))
			$this->newsletter = array_map(array('Functions', 'explodeByTab'), $this->newsletter);
	}

	/**
	 * Executes mode.
	 */
	public function execute()
	{
		Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('newsletter_archive'), INDEXFILE . '?faction=newsletter' . SID_AMPER);
		if(!Main::getModule('Auth')->isLoggedIn())
			Main::getModule('Template')->printMessage('login_only', INDEXFILE . '?faction=register' . SID_AMPER, INDEXFILE . '?faction=login' . SID_AMPER);
		switch($this->mode)
		{
//NewsletterReadLetter
			case 'read':
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('read_newsletter'), INDEXFILE . '?faction=newsletter&amp;mode=read&amp;newsletter=' . $this->newsletterID . SID_AMPER);
			if(($key = array_search($this->newsletterID, array_map('current', $this->newsletter))) === false)
				Main::getModule('Template')->printMessage('newsletter_not_found');
			if(!isset($this->newsletter[$key][4]) || empty($this->newsletter[$key][4]) || $this->newsletter[$key][4] == '1' || Main::getModule('Auth')->isAdmin() || ($this->newsletter[$key][4] == '2' && Main::getModule('Auth')->isMod()))
				Main::getModule('Template')->assign(array('date' => Functions::formatDate($this->newsletter[$key][0]),
					'author' => Functions::getProfileLink($this->newsletter[$key][1], true),
					'subject' => $this->newsletter[$key][2],
					'message' => $this->newsletter[$key][3]));
			else
				Main::getModule('Template')->printMessage('permission_denied');
			break;

			case 'delete':
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('delete_newsletter'), INDEXFILE . '?faction=newsletter&amp;mode=delete' . SID_AMPER);
			if(!Main::getModule('Auth')->isAdmin())
				Main::getModule('Template')->printMessage('permission_denied');
			$toDelete = Functions::getValueFromGlobals('deleteletter') or $toDelete = array();
			foreach($this->newsletter as $curKey => $curNewsletter)
				if(in_array($curNewsletter[0], $toDelete))
					unset($this->newsletter[$curKey]);
			Functions::file_put_contents('vars/newsletter.var', empty($this->newsletter) ? '' : implode("\n", array_map(array('Functions', 'implodeByTab'), $this->newsletter)) . "\n");
			//Done
			Main::getModule('Logger')->log('%s deleted ' . count($toDelete) . ' newsletter', LOG_ACP_ACTION);
			header('Location: ' . INDEXFILE . '?faction=newsletter' . SID_AMPER_RAW);
			Main::getModule('Template')->printMessage('newsletter_deleted');
			break;

//Newsletter
			default:
			$newsletter = array();
			foreach(array_reverse($this->newsletter) as $curNewsletter)
				//Check permissions
				if(!isset($curNewsletter[4]) || empty($curNewsletter[4]) || $curNewsletter[4] == '1' || Main::getModule('Auth')->isAdmin() || ($curNewsletter[4] == '2' && Main::getModule('Auth')->isMod()))
					$newsletter[] = array('id' => $curNewsletter[0],
						'date' => Functions::formatDate($curNewsletter[0]),
						'author' => Functions::getProfileLink($curNewsletter[1], true),
						'subject' => $curNewsletter[2]);
			Main::getModule('Template')->assign('newsletter', $newsletter);
			break;
		}
		Main::getModule('Template')->printPage(self::$modeTable[array_key_exists($this->mode, self::$modeTable) ? $this->mode : 'newsletter' . Main::getModule('Logger')->log('Unknown mode ' . $this->mode . ' in ' . __CLASS__ . '; using default', LOG_FILESYSTEM)], null, null, ',' . $this->newsletterID);
	}
}
?>