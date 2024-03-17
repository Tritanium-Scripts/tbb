<?php
/**
 * Manages various logfile tasks.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2024 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class AdminLogfile extends PublicModule
{
    use Singleton, Mode;

    /**
     * Name of current logfile.
     *
     * @var string Logfile name
     */
    private string $log;

    /**
     * Translates a mode to its template file.
     *
     * @var array Mode and template counterparts
     */
    private static array $modeTable = ['adminLogfile' => 'AdminLogfile',
        'view' => 'AdminLogfileViewLog',
        'delete' => 'AdminLogfile'];

    /**
     * Sets mode and current logfile.
     *
     * @param string $mode Mode to execute
     */
    function __construct(string $mode)
    {
        parent::__construct();
        $this->mode = $mode;
        $this->log = 'logs/' . Functions::getValueFromGlobals('log') . '.log';
        PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_LOGFILE_INIT);
    }

    /**
     * Executes mode.
     */
    public function publicCall(): void
    {
        Functions::accessAdminPanel();
        NavBar::getInstance()->addElement(Language::getInstance()->getString('manage_logfiles'), INDEXFILE . '?faction=adminLogfile' . SID_AMPER);
        switch($this->mode)
        {
//AdminLogfileViewLog
            case 'view':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('view_logfile'), INDEXFILE . '?faction=adminLogfile&amp;mode=view&amp;log=' . basename($this->log, '.log') . SID_AMPER);
            if(!Functions::file_exists($this->log))
                Template::getInstance()->printMessage('logfile_not_found');
            PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_LOGFILE_VIEW_LOG);
            Template::getInstance()->assign(['logfile' => array_map('htmlspecialchars', Functions::file($this->log)),
                'date' => Functions::gmstrftime(Language::getInstance()->getString('DAYLOGFORMAT'), gmmktime(0, 0, 0, Functions::substr($logfile = basename($this->log, '.log'), 2, 2), Functions::substr($logfile, 0, 2), Functions::substr($logfile, 4)))]);
            Logger::getInstance()->log('%s viewed logfile ' . $this->log, Logger::LOG_ACP_ACTION);
            break;

            case 'download':
            if(!Functions::file_exists($this->log))
                Template::getInstance()->printMessage('logfile_not_found');
            PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_LOGFILE_DOWNLOAD_LOG);
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($this->log));
            header('Expires: 0');
            header('Cache-Control: private, must-revalidate, no-cache');
            header('Pragma: no-cache');
            header('Content-Length: ' . filesize(DATAPATH . $this->log));
            readfile(DATAPATH . $this->log);
            Logger::getInstance()->log('%s downloaded logfile ' . $this->log, Logger::LOG_ACP_ACTION);
            exit();
            break;

//AdminLogfile
            case 'delete':
            if(Functions::getValueFromGlobals('multiDelete') != '')
                $toDelete = array_keys(Functions::getValueFromGlobals('deletelog') ?: []);
            elseif(!Functions::file_exists($this->log))
                Template::getInstance()->printMessage('logfile_not_found');
            else
                $toDelete = [basename($this->log, '.log')];
            PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_LOGFILE_DELETE_LOG, $toDelete);
            foreach($toDelete as $curLogfile)
                if($curLogfile != gmdate('dmY'))
                {
                    Functions::unlink('logs/' . $curLogfile . '.log');
                    Logger::getInstance()->log('%s deleted logfile logs/' . $curLogfile . '.log', Logger::LOG_ACP_ACTION);
                }

//AdminLogfile
            default:
            $logfiles = [];
            foreach(Functions::glob(DATAPATH . 'logs/*.log') as $curLogfile)
            {
                $curTimestamp = gmmktime(0, 0, 0, Functions::substr($curFilename = basename($curLogfile, '.log'), 2, 2), Functions::substr($curFilename, 0, 2), Functions::substr($curFilename, 4));
                $logfiles[] = ['name' => $curFilename,
                    'isDeletable' => $curFilename != gmdate('dmY'),
                    'timestamp' => $curTimestamp,
                    'weekday' => Functions::gmstrftime('%A', $curTimestamp),
                    'date' => Functions::gmstrftime(Language::getInstance()->getString('DAYLOGFORMAT'), $curTimestamp),
                    'size' => filesize($curLogfile)/1024,
                    'entries' => count(Functions::file($curLogfile, null, null, false)),
                    'lastChange' => Functions::gmstrftime(Language::getInstance()->getString('DATEFORMAT'), filemtime($curLogfile))];
            }
            //Apply order type, one of them will be changed depending on current sort method
            $orderTypeDate = $orderTypeSize = $orderTypeEntries = false;
            $orderType = Functions::getValueFromGlobals('orderType') == '1';
            //Sorting
            switch(Functions::getValueFromGlobals('sortMethod'))
            {
                case 'byDate':
                default:
                usort($logfiles, fn($file1, $file2) => strnatcasecmp($file1['timestamp'], $file2['timestamp']));
                $orderTypeDate = !$orderType;
                break;

                case 'bySize':
                usort($logfiles, fn($file1, $file2) => strnatcasecmp($file1['size'], $file2['size']));
                $orderTypeSize = !$orderType;
                break;

                case 'byEntries':
                usort($logfiles, fn($file1, $file2) => strnatcasecmp($file1['entries'], $file2['entries']));
                $orderTypeEntries = !$orderType;
                break;
            }
            //Sort by date DESC as default
            if(!$orderType)
                $logfiles = array_reverse($logfiles);
            PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_LOGFILE_SHOW_LOGS, $logfiles, $orderTypeDate, $orderTypeSize, $orderTypeEntries);
            Template::getInstance()->assign(['logfiles' => $logfiles,
                'orderTypeDate' => $orderTypeDate,
                'orderTypeSize' => $orderTypeSize,
                'orderTypeEntries' => $orderTypeEntries]);
            break;
        }
        Template::getInstance()->printPage(self::$modeTable[$this->mode]);
    }
}
?>