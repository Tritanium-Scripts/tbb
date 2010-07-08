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
	 */
	function __construct()
	{
		$this->logFile = 'logs/' . gmdate('dmY') . '.log';
		$this->logLevels = explode(',', Main::getModule('Config')->getCfgVal('log_options'));
	}

	/**
	 * Writes an entry based on log level settings to log file.
	 *
	 * @param mixed $data Data of entry to write
	 * @param int $level Log level of this entry (1-12)
	 */
	public function log($data, $level)
	{
		if(in_array($level, $this->logLevels))
			Functions::file_put_contents($this->logFile, date('r') . $data, FILE_APPEND);
	}
}
?>