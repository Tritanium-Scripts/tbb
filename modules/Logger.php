<?php
/**
 * Logging module with different log levels.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class Logger
{
	/**
	 * File used for logging entries.
	 *
	 * @var string File's position and name
	 */
	private $logFile;

	/**
	 * Log levels used for filtering entries.
	 *
	 * @var array Log level settings.
	 */
	private $logLevels = array();

	/**
	 * Sets log file and log levels.
	 *
	 * @return Logger New instance of this class
	 */
	function __construct()
	{
		$this->logFile = DATAPATH . 'logs/' . gmdate('dmY') . '.log';
		$this->logLevels = Functions::explodeByComma(Main::getModule('Config')->getCfgVal('log_options'));
	}

	/**
	 * Writes an entry based on log level settings to log file. %s will be replaced with user nick and WIO ID.
	 *
	 * @param mixed $data Data of entry to write
	 * @param int $level Log level of this entry (1-12)
	 */
	public function log($data, $level)
	{
		if(in_array($level, $this->logLevels))
			Functions::file_put_contents($this->logFile, date('r') . ' [IP: ' . $_SERVER['REMOTE_ADDR'] . ']: ' . sprintf(htmlspecialchars_decode($data), htmlspecialchars_decode(Main::getModule('Auth')->getUserNick()) . ' (ID: ' . Main::getModule('Auth')->getWIOID() . ')') . "\n", FILE_APPEND);
	}
}
?>