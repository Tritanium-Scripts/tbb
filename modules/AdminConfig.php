<?php
/**
 * Manages the board configuration and maintenance operations.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2023 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class AdminConfig extends PublicModule
{
    use Singleton, Mode;

    /**
     * Translates a mode to its template file.
     *
     * @var array Mode and template counterparts
     */
    private static array $modeTable = ['ad_settings' => 'AdminConfig',
        'editsettings' => 'AdminConfig',
        'readsetfile' => 'AdminConfigResetConfirm',
        'recalculateCounters' => 'AdminConfigCountersConfirm',
        'rebuildTopicIndex' => 'AdminConfigRebuildConfirm'];

    /**
     * Maximal execution time for counting to require a break to continue.
     *
     * @var int Seconds timeout
     */
    private int $timeout;

    /**
     * Sets timeout and mode to execute.
     *
     * @param string $mode The mode
     */
    function __construct(string $mode)
    {
        parent::__construct();
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
    private function checkTime(bool $check=true, string $mode='recalculateCounters'): void
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
    public function publicCall(): void
    {
        $oldTableWidth = Config::getInstance()->getCfgVal('twidth');
        Functions::accessAdminPanel();
        switch($this->mode)
        {
//AdminConfigRebuildConfirm
            case 'rebuildTopicIndex':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('rebuild_topic_index'), INDEXFILE . '?faction=ad_settings&amp;mode=rebuildTopicIndex' . SID_AMPER);
            if(isset($_SESSION['rebuildTopicIndex']))
            {
                foreach($_SESSION['rebuildTopicIndex'] as $curForumID => &$curTopics)
                {
                    //Fetch topic IDs for each forum
                    if(empty($curTopics))
                        foreach(Functions::glob(DATAPATH . 'foren/' . $curForumID . '-[0-9]*.xbb') as $curTopic) //Get topics of current forum
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
                Logger::getInstance()->log('%s rebuilt topic index', Logger::LOG_ACP_ACTION);
                Template::getInstance()->printMessage('topic_index_rebuilt');
            }
            if(Functions::getValueFromGlobals('confirmed') == 'true')
            {
                //Prepare rebuild stuff
                $forums = array_map(fn($forum) => current(Functions::explodeByTab($forum)), Functions::file('vars/foren.var', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
                $_SESSION['rebuildTopicIndex'] = array_combine($forums, array_fill(0, count($forums), []));
                if(empty($forums))
                    $_SESSION['rebuildTopicIndex'] = [];
                $this->checkTime(false, 'rebuildTopicIndex');
            }
            break;

//AdminConfigCountersConfirm
            case 'recalculateCounters':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('recalculate_counters'), INDEXFILE . '?faction=ad_settings&amp;mode=recalculateCounters' . SID_AMPER);
            if(isset($_SESSION['recalculateCounters']))
            {
                while(!empty($_SESSION['recalculateCounters']['forums']))
                {
                    $curForumID = key($_SESSION['recalculateCounters']['forums']);
                    if(!isset($_SESSION['recalculateCounters']['forums'][$curForumID][0]))
                    {
                        //Get real existent topics
                        $_SESSION['recalculateCounters']['forums'][$curForumID] = Functions::glob(DATAPATH . 'foren/' . $curForumID . '-[0-9]*.xbb');
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
                $forums = array_map(['Functions', 'explodeByTab'], Functions::file('vars/foren.var'));
                foreach($forums as &$curForum)
                    if(isset($_SESSION['recalculateCounters']['total'][$curForum[0]]))
                    {
                        $curForum[3] = $_SESSION['recalculateCounters']['total'][$curForum[0]]['topics'];
                        $curForum[4] = $_SESSION['recalculateCounters']['total'][$curForum[0]]['posts'];
                    }
                Functions::file_put_contents('vars/foren.var', implode("\n", array_map(['Functions', 'implodeByTab'], $forums)) . "\n");
                Functions::releaseLock('foren');
                unset($_SESSION['recalculateCounters']);
                //Now the members
                Functions::file_put_contents('vars/member_counter.var', count(Functions::glob(DATAPATH . 'members/[!0t]*.xbb')));
                Logger::getInstance()->log('%s recalculated counters', Logger::LOG_ACP_ACTION);
                Template::getInstance()->printMessage('counters_recalculated');
            }
            if(Functions::getValueFromGlobals('confirmed') == 'true')
            {
                //Prepare recalculation stuff
                $forums = array_map(fn($forum) => current(Functions::explodeByTab($forum)), Functions::file('vars/foren.var', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
                $_SESSION['recalculateCounters'] = ['forums' => array_combine($forums, array_fill(0, count($forums), [])), 'total' => []];
                $this->checkTime(false);
            }
            break;

            case 'clearCache':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('clear_cache'), INDEXFILE . '?faction=ad_settings&amp;mode=clearCache' . SID_AMPER);
            $deleted = Template::getInstance()->clearCache();
            foreach(Functions::glob('cache/*.[!svn]*') as $curFile)
                if(unlink($curFile))
                    $deleted++;
            Logger::getInstance()->log('%s cleared cache', Logger::LOG_ACP_ACTION);
            Template::getInstance()->printMessage('cache_cleared', $deleted);
            break;

//AdminConfigResetConfirm
            case 'readsetfile':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('reset_settings'), INDEXFILE . '?faction=ad_settings&amp;mode=readsetfile' . SID_AMPER);
            if(Functions::getValueFromGlobals('confirm') == '1')
            {
                if(Functions::file_exists('vars/settings.var'))
                    Functions::unlink('vars/settings.var');
                Logger::getInstance()->log('%s reset board settings', Logger::LOG_ACP_ACTION);
                Template::getInstance()->printMessage('settings_reset');
            }
            break;

//AdminConfig
            case 'editsettings':
            default:
            NavBar::getInstance()->addElement(Language::getInstance()->getString('edit_settings'), INDEXFILE . '?faction=ad_settings&amp;mode=editsettings' . SID_AMPER);
            if(Functions::getValueFromGlobals('save') == '1')
            {
                $newSettings = Functions::getValueFromGlobals('settings');
                list($newSettings[2], $newSettings[5], $newSettings[26], $newSettings[27], $newSettings[28], $newSettings[29], $newSettings[65]) = array_map('htmlspecialchars', [$newSettings[2], $newSettings[5], $newSettings[26], $newSettings[27], $newSettings[28], $newSettings[29], $newSettings[65]]);
                $newSettings[7] = Config::getInstance()->getCfgVal('uc_message');
                $newSettings[9] = !isset($newSettings[9]) ? '' : implode(',', $newSettings[9]);
                $newSettings[38] = Config::getInstance()->getCfgVal('css_file');
                $newSettings[56] = Config::getInstance()->getCfgVal('default_tpl');
                $newSettings[70] = Config::getInstance()->getCfgVal('select_tpls');
                $newSettings[71] = Config::getInstance()->getCfgVal('select_styles');
                $newSettings[73] = Functions::strtolower($newSettings[73]); //Lower file extensions
                ksort($newSettings);
                Functions::file_put_contents('vars/settings.var', implode("\n", $newSettings));
                Logger::getInstance()->log('%s edited board settings', Logger::LOG_ACP_ACTION);
                Template::getInstance()->printMessage('new_settings_saved');
            }
            //Get time zones
            $timeZones = [];
            foreach(Language::getInstance()->getStrings() as $curIndex => $curString)
                //Look up string with "tz[Minutes]" (Minutes ranges from 0 (=-12 hours) to 1440 (=+12 hours))
                if(preg_match('/^tz(\d+)$/si', $curIndex, $curMatch) == 1)
                    //Format minutes from strings to positive and negative hours
                    $timeZones[] = [Functions::str_replace('.', '', sprintf('%+06.2F', ($curMatch[1]-720)/60)), $curString];
            //Prepare log settings
            Config::getInstance()->setCfgVal('log_options', Functions::explodeByComma(Config::getInstance()->getCfgVal('log_options')));
            Template::getInstance()->assign(['oldTableWidth' => $oldTableWidth,
                'configValues' => Config::getInstance()->getCfgSet(),
                'timeZones' => $timeZones,
                //The default level may be overwritten by a predefined one with the same outcome
                'errorLevels' => [ERR_REPORTING => Language::getInstance()->getString('default'),
                    0 => Language::getInstance()->getString('non'),
                    E_ERROR | E_PARSE => Language::getInstance()->getString('errors_only'),
                    E_ERROR | E_WARNING | E_PARSE => Language::getInstance()->getString('errors_and_warnings'),
                    E_ERROR | E_WARNING | E_PARSE | E_NOTICE => Language::getInstance()->getString('errors_warnings_and_notices'),
                    E_ALL => Language::getInstance()->getString('all')]]);
            break;
        }
        Template::getInstance()->printPage(self::$modeTable[$this->mode]);
    }
}
?>