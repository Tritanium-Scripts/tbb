<?php
/**
 * Manages the board configuration and maintenance operations.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class AdminConfig implements Module
{
	/**
	 * Contains mode to execute.
	 *
	 * @var string Settings mode
	 */
	private $mode;

	/**
	 * Translates a mode to its template file.
	 *
	 * @var array Mode and template counterparts
	 */
	private static $modeTable = array('ad_settings' => 'AdminConfig',
		'editsettings' => 'AdminConfig',
		'readsetfile' => 'AdminConfigResetConfirm',
		'recalculateCounters' => 'AdminConfigCountersConfirm');

	/**
	 * Sets mode to execute.
	 *
	 * @param string $mode The mode
	 * @return AdminConfig New instance of this class
	 */
	function __construct($mode)
	{
		$this->mode = $mode;
	}

	/**
	 * Reads, writes and resets the board settings. Clears cache and recalculate various counters.
	 */
	public function execute()
	{
		Functions::accessAdminPanel();
		switch($this->mode)
		{
//AdminConfigCountersConfirm
			case 'recalculateCounters':
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('recalculate_counters'), INDEXFILE . '?faction=ad_settings&amp;mode=recalculateCounters' . SID_AMPER);
			if(Functions::getValueFromGlobals('confirmed') == 'true')
			{
				Main::getModule('Template')->printMessage('counters_recalculated');
			}
			break;

			case 'clearCache':
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('clear_cache'), INDEXFILE . '?faction=ad_settings&amp;mode=clearCache' . SID_AMPER);
			$deleted = Main::getModule('Template')->clearCache();
			foreach(glob('cache/*.[!svn]*') as $curFile)
				if(unlink($curFile))
					$deleted++;
			Main::getModule('Template')->printMessage('cache_cleared', $deleted);
			break;

//AdminConfigResetConfirm
			case 'readsetfile':
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('reset_settings'), INDEXFILE . '?faction=ad_settings&amp;mode=readsetfile' . SID_AMPER);
			if(Functions::getValueFromGlobals('confirm') == '1')
			{
				if(Functions::file_exists('vars/settings.var'))
					Functions::unlink('vars/settings.var');
				Main::getModule('Template')->printMessage('settings_reset');
			}
			break;

//AdminConfig
			case 'editsettings':
			default:
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('edit_settings'), INDEXFILE . '?faction=ad_settings&amp;mode=editsettings' . SID_AMPER);
			if(Functions::getValueFromGlobals('save') == '1')
			{
				$newSettings = Functions::getValueFromGlobals('settings');
				list($newSettings[2], $newSettings[5], $newSettings[7], $newSettings[26], $newSettings[27], $newSettings[28], $newSettings[29], $newSettings[65]) = array_map('htmlspecialchars', array($newSettings[2], $newSettings[5], $newSettings[7], $newSettings[26], $newSettings[27], $newSettings[28], $newSettings[29], $newSettings[65]));
				$newSettings[7] = Main::getModule('Config')->getCfgVal('uc_message');
				$newSettings[9] = !isset($newSettings[9]) ? '' : implode(',', $newSettings[9]);
				ksort($newSettings);
				Functions::file_put_contents('vars/settings.var', implode("\n", $newSettings));
				Main::getModule('Template')->printMessage('new_settings_saved');
			}
			//Get time zones
			$timeZones = array();
			foreach(Main::getModule('Language')->getStrings() as $curIndex => $curString)
				//Look up string with "tz[Minutes]" (Minutes ranges from 0 (=-12 hours) to 1440 (=+12 hours))
				if(preg_match('/^tz(\d+)$/si', $curIndex, $curMatch) == 1)
					//Format minutes from strings to positive and negative hours
					$timeZones[] = array(Functions::str_replace(',', '', sprintf('%+06.2f', ($curMatch[1]-720)/60)), $curString);
			Main::getModule('Template')->assign(array('configValues' => Main::getModule('Config')->getCfgSet(),
				'timeZones' => $timeZones));
			break;
		}
		Main::getModule('Template')->printPage(self::$modeTable[$this->mode]);
	}
}
?>