<?php
/**
 * Logging module with different log levels.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2024 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class Logger
{
    use Singleton;

    /**
     * Problems with filesystem.
     */
    public const LOG_FILESYSTEM = 1;

    /**
     * Failed ACP access.
     */
    public const LOG_ACP_ACCESS = 2;

    /**
     * Failed login.
     */
    public const LOG_FAILED_LOGIN = 3;

    /**
     * New topic or post.
     */
    public const LOG_NEW_POSTING = 4;

    /**
     * Edited, deleted, moved post or topic.
     */
    public const LOG_EDIT_POSTING = 5;

    /**
     * User connected to board.
     */
    public const LOG_USER_CONNECT = 6;

    /**
     * Logins and logouts.
     */
    public const LOG_LOGIN_LOGOUT = 7;

    /**
     * Admin actions.
     */
    public const LOG_ACP_ACTION = 8;

    /**
     * PMs and mails.
     */
    public const LOG_USER_TRAFFIC = 9;

    /**
     * Profile changed.
     */
    public const LOG_EDIT_PROFILE = 10;

    /**
     * New registration.
     */
    public const LOG_REGISTRATION = 11;

    /**
     * New password request.
     */
    public const LOG_NEW_PASSWORD = 12;

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
     */
    function __construct()
    {
        $this->logFile = DATAPATH . 'logs/' . gmdate('dmY') . '.log';
        $this->logLevels = Functions::explodeByComma(Config::getInstance()->getCfgVal('log_options'));
        PlugIns::getInstance()->callHook(PlugIns::HOOK_LOGGER_INIT);
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
        {
            PlugIns::getInstance()->callHook(PlugIns::HOOK_LOGGER_LOG, $data, $level);
            Functions::file_put_contents($this->logFile, date('r') . ' [IP: ' . $_SERVER['REMOTE_ADDR'] . ']: ' . sprintf(htmlspecialchars_decode($data), htmlspecialchars_decode(Auth::getInstance()->getUserNick()) . ' (ID: ' . Auth::getInstance()->getWIOID() . ')') . "\n", FILE_APPEND | LOCK_EX);
        }
    }
}
?>