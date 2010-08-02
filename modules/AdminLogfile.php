<?php
/**
 * Manages various logfile tasks.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class AdminLogfile implements Module
{
	/**
	 * Name of current logfile.
	 *
	 * @var string Logfile name
	 */
	private $log;

	/**
	 * Contains mode to execute.
	 *
	 * @var string Logfile option mode
	 */
	private $mode;

	/**
	 * Translates a mode to its template file.
	 *
	 * @var array Mode and template counterparts
	 */
	private static $modeTable = array('adminLogfile' => 'AdminLogfile',
		'view' => 'AdminLogfileViewLog',
		'delete' => 'AdminLogfile');

	/**
	 * Sets mode and current logfile.
	 *
	 * @param string $mode Mode to execute
	 * @return AdminLogfile New instance of this class
	 */
	function __construct($mode)
	{
		$this->mode = $mode;
		$this->log = 'logs/' . Functions::getValueFromGlobals('log') . '.log';
	}

	public function execute()
	{
		Functions::accessAdminPanel();
		Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('manage_logfiles'), INDEXFILE . '?faction=adminLogfile' . SID_AMPER);
		switch($this->mode)
		{
//AdminLogfileViewLog
			case 'view':
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('view_logfile'), INDEXFILE . '?faction=adminLogfile&amp;mode=view&amp;log=' . basename($this->log, '.log') . SID_AMPER);
			if(!Functions::file_exists($this->log))
				Main::getModule('Template')->printMessage('logfile_not_found');
			Main::getModule('Template')->assign(array('logfile' => Functions::file($this->log),
				'date' => strftime(Main::getModule('Language')->getString('DAYLOGFORMAT'), gmmktime(0, 0, 0, Functions::substr($logfile = basename($this->log, '.log'), 2, 2), Functions::substr($logfile, 0, 2), Functions::substr($logfile, 4)))));
			Main::getModule('Logger')->log('%s viewed logfile ' . $this->log, LOG_ACP_ACTION);
			break;

			case 'download':
			if(!Functions::file_exists($this->log))
				Main::getModule('Template')->printMessage('logfile_not_found');
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . basename($this->log));
			header('Expires: 0');
			header('Cache-Control: private, must-revalidate, no-cache');
			header('Pragma: no-cache');
			header('Content-Length: ' . filesize($this->log));
			readfile($this->log);
			Main::getModule('Logger')->log('%s downloaded logfile ' . $this->log, LOG_ACP_ACTION);
			exit();
			break;

//AdminLogfile
			case 'delete':
			if(Functions::getValueFromGlobals('multiDelete') != '')
				$toDelete = array_keys(Functions::getValueFromGlobals('deletelog'));
			elseif(!Functions::file_exists($this->log))
				Main::getModule('Template')->printMessage('logfile_not_found');
			else
				$toDelete = array(basename($this->log, '.log'));
			foreach($toDelete as $curLogfile)
				if($curLogfile != gmdate('dmY'))
				{
					Functions::unlink('logs/' . $curLogfile . '.log');
					Main::getModule('Logger')->log('%s deleted logfile logs/' . $curLogfile . '.log', LOG_ACP_ACTION);
				}

//AdminLogfile
			default:
			$logfiles = array();
			foreach(glob(DATAPATH . 'logs/*.log') as $curLogfile)
			{
				$curTimestamp = gmmktime(0, 0, 0, Functions::substr($curFilename = basename($curLogfile, '.log'), 2, 2), Functions::substr($curFilename, 0, 2), Functions::substr($curFilename, 4));
				$logfiles[] = array('name' => $curFilename,
					'isDeletable' => $curFilename != gmdate('dmY'),
					'timestamp' => $curTimestamp,
					'weekday' => utf8_encode(strftime('%A', $curTimestamp)),
					'date' => strftime(Main::getModule('Language')->getString('DAYLOGFORMAT'), $curTimestamp),
					'size' => filesize($curLogfile)/1024,
					'entries' => count(Functions::file($curLogfile, null, null, false)),
					'lastChange' => utf8_encode(strftime(Main::getModule('Language')->getString('DATEFORMAT'), filemtime($curLogfile))));
			}
			//Apply order type, one of them will be changed depending on current sort methode
			$orderTypeDate = $orderTypeSize = $orderTypeEntries = false;
			$orderType = Functions::getValueFromGlobals('orderType') == '1';
			//Sorting
			switch(Functions::getValueFromGlobals('sortMethod'))
			{
				case 'byDate':
				default:
				usort($logfiles, create_function('$file1, $file2', 'return strnatcasecmp($file1[\'timestamp\'], $file2[\'timestamp\']);'));
				$orderTypeDate = !$orderType;
				break;

				case 'bySize':
				usort($logfiles, create_function('$file1, $file2', 'return strnatcasecmp($file1[\'size\'], $file2[\'size\']);'));
				$orderTypeSize = !$orderType;
				break;

				case 'byEntries':
				usort($logfiles, create_function('$file1, $file2', 'return strnatcasecmp($file1[\'entries\'], $file2[\'entries\']);'));
				$orderTypeEntries = !$orderType;
				break;
			}
			//Sort by date DESC as default
			if(!$orderType)
				$logfiles = array_reverse($logfiles);
			Main::getModule('Template')->assign(array('logfiles' => $logfiles,
				'orderTypeDate' => $orderTypeDate,
				'orderTypeSize' => $orderTypeSize,
				'orderTypeEntries' => $orderTypeEntries));
			break;
		}
		Main::getModule('Template')->printPage(self::$modeTable[$this->mode]);
	}
}
?>