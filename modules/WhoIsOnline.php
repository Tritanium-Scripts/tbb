<?php
/**
 * Manages WIO list.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class WhoIsOnline implements Module
{
	/**
	 * Activation state of WIO module.
	 *
	 * @var bool State of WIO module
	 */
	private $enabled;

	/**
	 * Timeout to clear listed user from WIO list.
	 *
	 * @var int Timeout in minutes
	 */
	private $timeout;

	/**
	 * Sets config values.
	 */
	public function __construct()
	{
		$this->enabled = Main::getModule('Config')->getCfgVal('wio') == 1;
		$this->timeout = Main::getModule('Config')->getCfgVal('wio_timeout');
	}

	public function execute()
	{
		
	}

	/**
	 * Returns current active members and amount of guests.
	 *
	 * @return array Guests / members pair
	 */
	public function getUser()
	{
		$guests = 0;
		$members = array();
		if($this->enabled)
			foreach($this->refreshVar() as $curWIOEntry)
				if(is_numeric($curWIOEntry[1]))
					$members[] = Functions::getProfileLink($curWIOEntry[1], false, ' class="small"');
				else
					$guests++;
		return array($guests, $members);
	}

	public function setLocation()
	{
		if(!$this->enabled)
			return;
		$this->refreshVar();
	}

	/**
	 * Refreshes contents of the WIO data file.
	 *
	 * @return array Already exploded contents of WIO file.
	 */
	private function refreshVar()
	{
		$update = false;
		$wioFile = Functions::file('vars/wio.var');
		foreach($wioFile as &$curWIOEntry)
		{
			$curWIOEntry = Functions::explodeByTab($curWIOEntry);
			if($curWIOEntry[0] + $timeout*60 < time())
			{
				unset($curWIOEntry);
				$update = true;
			}
		}
		if($update)
			Functions::file_put_contents('vars/wio.var', implode("\n", $wioFile));
		return $wioFile;
	}
}
?>