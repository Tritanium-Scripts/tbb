<?php
/**
 * Provides external access to WIO box.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class ExtWIOBox
{
/*
 * Copy this file into your website directory and paste in the following into your PHP code:
 * include('ext_wio.php');
 * But prior to this you have to adjust these settings:
 */
	/**
	 * (Relative) Path to forum.
	 */
	private static $extPathToForum = 'path/to/forum/'; //Has to end with trailing slash!

	/**
	 * Encode output as UTF-8?
	 */
	private static $isUTF8 = true; //If your website don't use UTF-8, set this to false

	/**
	 * Used language strings for output; you can translate them.
	 */
	 private static $in_last_min_were_active_colon = 'In den letzten Minuten waren im Forum aktiv:';
	 private static $no_members = 'Keine Mitglieder';
	 private static $members_colon = 'Mitglieder:';
	 private static $no_guests = 'Keine Gäste';
	 private static $one_guest = 'Ein Gast';
	 private static $x_guests = '%d Gäste';
	 private static $no_ghosts = 'Keine Geister';
	 private static $one_ghost = 'Ein Geist';
	 private static $x_ghosts = '%d Geister';


/* Do not change anything beyond this line */


	/**
	 * Provides needed constants and includes required functions.
	 */
	function __construct()
	{
		if(!defined('EXT_PATH_TO_FORUM'))
		{
			define('EXT_PATH_TO_FORUM', self::$extPathToForum);
			require_once(EXT_PATH_TO_FORUM . 'core/Constants.php');
			define('EXT_PATH_TO_DATA', EXT_PATH_TO_FORUM . DATAPATH);
			define('EXT_IS_UTF8', self::$isUTF8);
			require_once(EXT_PATH_TO_FORUM . 'core/ExtFunctions.php');
			error_reporting(ERR_REPORTING);
			//Qick 'n' dirty fix to set "proper" timezone
			@date_default_timezone_set(date_default_timezone_get());
		}
	}

	/**
	 * Prints out the WIO box with current active user.
	 */
	public function printWIOBox()
	{
		$guests = $ghosts = 0;
		$members = array();
		foreach(ExtFunctions::file('vars/wio.var') as $curWIOEntry)
		{
			$curWIOEntry = ExtFunctions::explodeByTab($curWIOEntry);
			is_numeric($curWIOEntry[1]) ? ($curWIOEntry[4] != '1' ? $members[] = ExtFunctions::getProfileLink($curWIOEntry[1]) : $ghosts++) : $guests++;
		}
		echo(self::$in_last_min_were_active_colon . '<br />
' . (empty($members) ? self::$no_members : self::$members_colon . ' ' . implode(', ', $members)) . '<br />
' . ($ghosts == 0 ? self::$no_ghosts : ($ghosts == 1 ? self::$one_ghost : sprintf(self::$x_ghosts, $ghosts))) . '<br />
' . ($guests == 0 ? self::$no_guests : ($guests == 1 ? self::$one_guest : sprintf(self::$x_guests, $guests))));
	}
}

$extWIO = new ExtWIOBox;
$extWIO->printWIOBox();
?>