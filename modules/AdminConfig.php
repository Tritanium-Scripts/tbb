<?php
/**
 * Manages the board configuration and maintenance operations.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010, 2011 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.6
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
		'recalculateCounters' => 'AdminConfigCountersConfirm',
		'rebuildTopicIndex' => 'AdminConfigRebuildConfirm');

	/**
	 * Maximal execution time for couting to require a break to continue.
	 *
	 * @var int Seconds timeout
	 */
	private $timeout;

	/**
	 * Sets timeout and mode to execute.
	 *
	 * @param string $mode The mode
	 * @return AdminConfig New instance of this class
	 */
	function __construct($mode)
	{
		$this->mode = $mode;
		if(($this->timeout = ini_get('max_execution_time')) > 10)
			$this->timeout -= 10;
	}

	/**
	 * Checks current execution time of the crawling progress and reloads it, if needed.
	 *
	 * @param bool $check Check the run time or reload script anyway
	 * @param string $mode Mode to execute after script reloading
	 */
	private function checkTime($check=true, $mode='recalculateCounters')
	{
		if(!$check || microtime(true)-SCRIPTSTART > $this->timeout)
		{
			header('Location: ' . INDEXFILE . '?faction=ad_settings&mode=' . $mode . SID_AMPER_RAW);
			exit('<a href="' . INDEXFILE . '?faction=ad_settings&amp;mode=' . $mode . SID_AMPER . '">Go on</a>');
		}
	}

	/**
	 * Reads, writes and resets the board settings. Clears cache and recalculate various counters.
	 */
	public function execute()
	{
		$oldTableWidth = Main::getModule('Config')->getCfgVal('twidth');
		Functions::accessAdminPanel();
		switch($this->mode)
		{
//AdminConfigRebuildConfirm
			case 'rebuildTopicIndex':
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('rebuild_topic_index'), INDEXFILE . '?faction=ad_settings&amp;mode=rebuildTopicIndex' . SID_AMPER);
			if(isset($_SESSION['rebuildTopicIndex']))
			{
				foreach($_SESSION['rebuildTopicIndex'] as $curForumID => &$curTopics)
				{
					//Fetch topic IDs for each forum
					if(empty($curTopics))
						foreach(glob(DATAPATH . 'foren/' . $curForumID . '-[0-9]*.xbb') as $curTopic) //Get topics of current forum
							//Retrieve topic IDs for index
							if(preg_match('/' . $curForumID . '-(\d+).xbb/si', $curTopic, $curMatch) == 1)
								$curTopics[$curMatch[1]] = false;
					$this->checkTime(true, 'rebuildTopicIndex');
					//Topics found for this forum?
					if(!empty($curTopics))
					{
						//Fetch timestamp of last post for each topic
						foreach($curTopics as $curTopicID => &$curTimestamp)
							if(!$curTimestamp)
							{
								$curTopicData = Functions::explodeByTab(current(Functions::file('foren/' . $curForumID . '-' . $curTopicID . '.xbb')));
								$curTimestamp = $curTopicData[0] == 'm' ? filemtime(DATAPATH . 'foren/' . $curForumID . '-' . $curTopicID . '.xbb') : $curTopicData[5];
								$this->checkTime(true, 'rebuildTopicIndex');
							}
						//Sort via timestamp from oldest to newest
						asort($curTopics, SORT_NUMERIC);
						//Save rebuilt topic index
						Functions::file_put_contents('foren/' . $curForumID . '-threads.xbb', implode("\n", array_keys($curTopics)) . "\n");
					}
					unset($_SESSION['rebuildTopicIndex'][$curForumID]);
				}
				unset($_SESSION['rebuildTopicIndex']);
				Main::getModule('Logger')->log('%s rebuilt topic index', LOG_ACP_ACTION);
				Main::getModule('Template')->printMessage('topic_index_rebuilt');
			}
			if(Functions::getValueFromGlobals('confirmed') == 'true')
			{
				//Prepare rebuild stuff
				$_SESSION['rebuildTopicIndex'] = array_combine($forums = array_map(create_function('$forum', 'return current(Functions::explodeByTab($forum));'), Functions::file('vars/foren.var', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)), array_fill(0, count($forums), array()));
				if(empty($forums))
					$_SESSION['rebuildTopicIndex'] = array();
				$this->checkTime(false, 'rebuildTopicIndex');
			}
			break;

//AdminConfigCountersConfirm
			case 'recalculateCounters':
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('recalculate_counters'), INDEXFILE . '?faction=ad_settings&amp;mode=recalculateCounters' . SID_AMPER);
			if(isset($_SESSION['recalculateCounters']))
			{
				while(!empty($_SESSION['recalculateCounters']['forums']))
				{
					$curForumID = key($_SESSION['recalculateCounters']['forums']);
					if(!isset($_SESSION['recalculateCounters']['forums'][$curForumID][0]))
					{
						//Get real existent topics
						$_SESSION['recalculateCounters']['forums'][$curForumID] = glob(DATAPATH . 'foren/' . $curForumID . '-[0-9]*.xbb');
						$_SESSION['recalculateCounters']['total'][$curForumID]['topics'] = $_SESSION['recalculateCounters']['total'][$curForumID]['posts'] = 0;
					}
					while(!empty($_SESSION['recalculateCounters']['forums'][$curForumID]))
					{
						$curTopic = Functions::file(current($_SESSION['recalculateCounters']['forums'][$curForumID]), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES, null, false);
						//Ignore moved topics
						if($curTopic[0][0] != 'm')
						{
							$_SESSION['recalculateCounters']['total'][$curForumID]['topics']++;
							$_SESSION['recalculateCounters']['total'][$curForumID]['posts'] += count($curTopic)-1;
						}
						array_shift($_SESSION['recalculateCounters']['forums'][$curForumID]);
						$this->checkTime();
					}
					unset($_SESSION['recalculateCounters']['forums'][key($_SESSION['recalculateCounters']['forums'])]);
					$this->checkTime();
				}
				//Counting done, save results
				Functions::getFileLock('foren');
				$forums = array_map(array('Functions', 'explodeByTab'), Functions::file('vars/foren.var'));
				foreach($forums as &$curForum)
					if(isset($_SESSION['recalculateCounters']['total'][$curForum[0]]))
					{
						$curForum[3] = $_SESSION['recalculateCounters']['total'][$curForum[0]]['topics'];
						$curForum[4] = $_SESSION['recalculateCounters']['total'][$curForum[0]]['posts'];
					}
				Functions::file_put_contents('vars/foren.var', implode("\n", array_map(array('Functions', 'implodeByTab'), $forums)));
				Functions::releaseLock('foren');
				unset($_SESSION['recalculateCounters']);
				//Now the members
				Functions::file_put_contents('vars/member_counter.var', count(glob(DATAPATH . 'members/[!0t]*.xbb')));
				Main::getModule('Logger')->log('%s recalculated counters', LOG_ACP_ACTION);
				Main::getModule('Template')->printMessage('counters_recalculated');
			}
			if(Functions::getValueFromGlobals('confirmed') == 'true')
			{
				//Prepare recalculation stuff
				$_SESSION['recalculateCounters'] = array('forums' => array_combine($forums = array_map(create_function('$forum', 'return current(Functions::explodeByTab($forum));'), Functions::file('vars/foren.var', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)), array_fill(0, count($forums), array())), 'total' => array());
				$this->checkTime(false);
			}
			break;

			case 'clearCache':
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('clear_cache'), INDEXFILE . '?faction=ad_settings&amp;mode=clearCache' . SID_AMPER);
			$deleted = Main::getModule('Template')->clearCache();
			foreach(glob('cache/*.[!svn]*') as $curFile)
				if(unlink($curFile))
					$deleted++;
			Main::getModule('Logger')->log('%s cleared cache', LOG_ACP_ACTION);
			Main::getModule('Template')->printMessage('cache_cleared', $deleted);
			break;

//AdminConfigResetConfirm
			case 'readsetfile':
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('reset_settings'), INDEXFILE . '?faction=ad_settings&amp;mode=readsetfile' . SID_AMPER);
			if(Functions::getValueFromGlobals('confirm') == '1')
			{
				if(Functions::file_exists('vars/settings.var'))
					Functions::unlink('vars/settings.var');
				Main::getModule('Logger')->log('%s reset board settings', LOG_ACP_ACTION);
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
				list($newSettings[2], $newSettings[5], $newSettings[26], $newSettings[27], $newSettings[28], $newSettings[29], $newSettings[65]) = array_map('htmlspecialchars', array($newSettings[2], $newSettings[5], $newSettings[26], $newSettings[27], $newSettings[28], $newSettings[29], $newSettings[65]));
				$newSettings[7] = Main::getModule('Config')->getCfgVal('uc_message');
				$newSettings[9] = !isset($newSettings[9]) ? '' : implode(',', $newSettings[9]);
				$newSettings[38] = Main::getModule('Config')->getCfgVal('css_file');
				$newSettings[56] = Main::getModule('Config')->getCfgVal('default_tpl');
				$newSettings[70] = Main::getModule('Config')->getCfgVal('select_tpls');
				$newSettings[71] = Main::getModule('Config')->getCfgVal('select_styles');
				$newSettings[73] = Functions::strtolower($newSettings[73]); //Lower file extensions
				ksort($newSettings);
				Functions::file_put_contents('vars/settings.var', implode("\n", $newSettings));
				Main::getModule('Logger')->log('%s edited board settings', LOG_ACP_ACTION);
				Main::getModule('Template')->printMessage('new_settings_saved');
			}
			//Get time zones
			$timeZones = array();
			foreach(Main::getModule('Language')->getStrings() as $curIndex => $curString)
				//Look up string with "tz[Minutes]" (Minutes ranges from 0 (=-12 hours) to 1440 (=+12 hours))
				if(preg_match('/^tz(\d+)$/si', $curIndex, $curMatch) == 1)
					//Format minutes from strings to positive and negative hours
					$timeZones[] = array(Functions::str_replace('.', '', sprintf('%+06.2F', ($curMatch[1]-720)/60)), $curString);
			//Prepare log settings
			Main::getModule('Config')->setCfgVal('log_options', Functions::explodeByComma(Main::getModule('Config')->getCfgVal('log_options')));
			Main::getModule('Template')->assign(array('oldTableWidth' => $oldTableWidth,
				'configValues' => Main::getModule('Config')->getCfgSet(),
				'timeZones' => $timeZones,
				//The default level may be overwritten by a predefined one with the same outcome
				'errorLevels' => array(ERR_REPORTING => Main::getModule('Language')->getString('default'),
					0 => Main::getModule('Language')->getString('non'),
					E_ERROR | E_PARSE => Main::getModule('Language')->getString('errors_only'),
					E_ERROR | E_WARNING | E_PARSE => Main::getModule('Language')->getString('errors_and_warnings'),
					E_ERROR | E_WARNING | E_PARSE | E_NOTICE => Main::getModule('Language')->getString('errors_warnings_and_notices'),
					E_ALL => Main::getModule('Language')->getString('all'))));
			break;
		}
		Main::getModule('Template')->printPage(self::$modeTable[$this->mode]);
	}
}
?>