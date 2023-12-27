<?php
/**
 * Logging module with different log levels.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2023 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class Logger
{
    use Singleton;

    /**
     * File used for logging entries.
     *
     * @var string File's position and name
     */
    private string $logFile;

    /**
     * Log levels used for filtering entries.
     *
     * @var array Log level settings.
     */
    private array $logLevels = [];

    /**
     * Sets log file and log levels.
     *
     * @return Logger New instance of this class
     */
    function __construct()
    {
        $this->logFile = DATAPATH . 'logs/' . gmdate('dmY') . '.log';
        $this->logLevels = Functions::explodeByComma(Config::getInstance()->getCfgVal('log_options'));
    }

    /**
     * Writes an entry based on log level settings to log file. %s will be replaced with user nick and WIO ID.
     *
     * @param mixed $data Data of entry to write
     * @param int $level Log level of this entry (1-12)
     */
    public function log($data, int $level): void
    {
        if(in_array($level, $this->logLevels))
            Functions::file_put_contents($this->logFile, date('r') . ' [IP: ' . $_SERVER['REMOTE_ADDR'] . ']: ' . sprintf(htmlspecialchars_decode($data), htmlspecialchars_decode(Auth::getInstance()->getUserNick()) . ' (ID: ' . Auth::getInstance()->getWIOID() . ')') . "\n", FILE_APPEND | LOCK_EX);
    }
}
?>