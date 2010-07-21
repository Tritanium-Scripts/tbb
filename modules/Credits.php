<?php
/**
 * The credits. *g*
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class Credits implements Module
{
	/**
	 * Displays credits.
	 */
	public function execute()
	{
		$credits = array();
		foreach(Main::getModule('Language')->getStrings() as $curIndex => $curString)
			if(Functions::strpos($curIndex, 'credits') !== false)
				$credits[] = $curString;
		Main::getModule('WhoIsOnline')->setLocation('Credits');
		Main::getModule('Template')->display('Credits', 'credits', $credits);
	}
}
?>