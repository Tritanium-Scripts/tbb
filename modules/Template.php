<?php
/**
 * Inits Smarty, manages configuration, assigns values to template files and prints pages.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2023 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class Template
{
    use Singleton;

    /**
     * The Smarty object to work with.
     *
     * @var Smarty Smarty instance
     */
    private $smarty;

    /**
     * Directory of used template.
     *
     * @var string Template folder
     */
    private string $tplDir;

    /**
     * Sets up Smarty instance, loads configuration values and assigns default vars.
     */
    function __construct()
    {
        $this->smarty = new Smarty();
        //Settings
        $this->smarty->setErrorUnassigned(error_reporting() == E_ALL);
        $this->smarty->setCacheDir('cache/')
            ->setCompileDir('cache/');
        $this->tplDir = 'templates/' . (Config::getInstance()->getCfgVal('select_tpls') == 1 ? Auth::getInstance()->getUserTpl() : Config::getInstance()->getCfgVal('default_tpl')) . '/';
        $this->smarty->setTemplateDir($this->tplDir . 'templates/')
            ->setConfigDir($this->tplDir . 'config/')
            ->addPluginsDir('modules/Template/plugins/')
            //TODO replace registerPlugins with wildcard extension having Smarty 5
            ->registerPlugin('modifier', 'in_array', 'in_array')
            ->registerPlugin('modifier', 'implode', 'implode')
            ->registerPlugin('modifier', 'rtrim', 'rtrim')
            ->setCompileId($this->tplDir);
        //Load config(s)
        foreach(glob($this->tplDir . 'config/*.conf') as $curConfig)
            $this->smarty->configLoad($curConfig);
        $this->smarty->setDebugging($this->smarty->getConfigVars('debug'));
        //Assign defaults
        $this->smarty->assignByRef('smartyTime', $this->smarty->start_time);
        //Initialization done
        PlugIns::getInstance()->callHook(PlugIns::HOOK_TEMPLATE_INIT);
    }

    /**
     * Assigns value(s) to Smarty.
     *
     * @param mixed $tplVar Name of value or array with name+value pairs
     * @param mixed $value Value for single var
     */
    public function assign($tplVar, $value=null): void
    {
        $this->smarty->assign($tplVar, $value);
    }

    /**
     * Clears the entire Smarty cache.
     *
     * @return int Amount of deleted files
     */
    public function clearCache(): int
    {
        return $this->smarty->clearAllCache();
    }

    /**
     * Displays a template file and assigns prior optional values to it.
     *
     * @param string $tplName Name of template file
     * @param mixed $tplVar Name of single value or array with name+value pairs
     * @param mixed $value Value for single var
     */
    public function display(string $tplName, $tplVar=null, $value=null): void
    {
        if(!empty($tplVar))
            $this->assign($tplVar, $value);
        $this->smarty->display($tplName . '.tpl');
    }

    /**
     * Returns fetched contents (with assigned data) of a template file.
     *
     * @param string $tplName Name of template file
     * @param mixed $tplVar Name of single value or array with name+value pairs
     * @param mixed $value Value for single var
     * @return string Rendered template output
     */
    public function fetch(string $tplName, $tplVar=null, $value=null): string
    {
        if(!empty($tplVar))
            $this->assign($tplVar, $value);
        return $this->smarty->fetch($tplName . '.tpl');
    }

    /**
     * Returns available templates with their styles.
     *
     * @return array Available templates with config values and styles
     */
    public function getAvailableTpls(): array
    {
        $templates = [];
        //Get all templates
        foreach(glob('templates/*') as $curTemplate)
        {
            $curTemplateName = basename($curTemplate);
            //Get all config files from each template and parse their contents
            foreach(@array_map('parse_ini_file', glob($curTemplate . '/config/*.conf')) as $curConfigFile)
            {
                if(isset($curConfigFile['templateName']))
                    $templates[$curTemplateName]['name'] = $curConfigFile['templateName'];
                if(isset($curConfigFile['authorName']))
                    $templates[$curTemplateName]['author'] = $curConfigFile['authorName'];
                if(isset($curConfigFile['authorURL']))
                    $templates[$curTemplateName]['website'] = $curConfigFile['authorURL'];
                if(isset($curConfigFile['authorComment']))
                    $templates[$curTemplateName]['comment'] = $curConfigFile['authorComment'];
                if(isset($curConfigFile['defaultStyle']))
                    $templates[$curTemplateName]['style'] = $curConfigFile['defaultStyle'];
                if(isset($curConfigFile['targetVersion']))
                    $templates[$curTemplateName]['target'] = $curConfigFile['targetVersion'];
                //Get all styles from each template
                if(!isset($templates[$curTemplateName]['styles']))
                    $templates[$curTemplateName]['styles'] = array_map('basename', glob($curTemplate . '/styles/*.css'));
            }
            if(!isset($templates[$curTemplateName]['target']))
                $templates[$curTemplateName]['target'] = '1.5.0.0';
            else
                //Provide proper version number with all 4 parts (major.minor.patch.build)
                while(Functions::substr_count($templates[$curTemplateName]['target'], '.') < 3)
                    $templates[$curTemplateName]['target'] .= '.0';
        }
        return $templates;
    }

    /**
     * Returns configuration values from template config file(s).
     *
     * @return array All found and loaded config values
     */
    public function getTplCfg(): array
    {
        return $this->smarty->getConfigVars();
    }

    /**
     * Returns used template directory.
     *
     * @return string Used template folder
     */
    public function getTplDir(): string
    {
        return $this->tplDir;
    }

    /**
     * Prints the head of a page.
     */
    public function printHeader(): void
    {
        //Clickjacking protection
        if(Config::getInstance()->getCfgVal('clickjacking') == 1)
            header('X-FRAME-OPTIONS: SAMEORIGIN');
        //Announce amount of *now* unread pms to template, just before printing out any of them
        $this->display('PageHeader', ['unreadPMs' => PrivateMessage::getInstance()->getUnreadPMs(),
            //Not using $smarty.now because of GMT and DST stuff
            'currentTime' => gmstrftime(Language::getInstance()->getString('TIMEFORMAT'), Functions::getTimestamp(gmdate('YmdHis')))]);
    }

    /**
     * Prints a full page message and exits program execution.
     *
     * @param string $msgIndex Identifier part of message title and text
     * @param mixed $args,... Optional arguments to be replaced in message text
     */
    public function printMessage(string $msgIndex, ...$args): void
    {
        $this->assign('subAction', 'Message');
        //Update NavBar + WIO
        NavBar::getInstance()->addElement(Language::getInstance()->getString('title_' . $msgIndex, 'Messages'));
        WhoIsOnline::getInstance()->setLocation('Message');
        PlugIns::getInstance()->callHook(PlugIns::HOOK_TEMPLATE_PAGE);
        //Print message
        $this->printHeader();
        $this->display('Message', ['action' => 'Message',
            'msgTitle' => Language::getInstance()->getString('title_' . $msgIndex),
            'msgText' => vsprintf(Language::getInstance()->getString('text_' . $msgIndex), $args)]);
        exit($this->printTail());
    }

    /**
     * Prints a full page with provided template file, optional values to assign before, additional WIO location and exits program execution.
     *
     * @param string $tplName Name of template file
     * @param mixed $tplVar Name of single value or array with name+value pairs
     * @param mixed $value Value for single var
     * @param string $addToWIOLoc Additional value to append to WIO location
     */
    public function printPage(string $tplName, $tplVar=null, $value=null, ?string $addToWIOLoc=null): void
    {
        if(!empty($tplVar))
            $this->assign($tplVar, $value);
        $this->assign('subAction', $tplName);
        WhoIsOnline::getInstance()->setLocation($tplName . $addToWIOLoc);
        PlugIns::getInstance()->callHook(PlugIns::HOOK_TEMPLATE_PAGE);
        $this->printHeader();
        $this->display($tplName);
        exit($this->printTail());
    }

    /**
     * Prints the tail of a page.
     */
    public function printTail(): void
    {
        $privacyPolicyLink = $this->smarty->getTemplateVars('privacyPolicyLink');
        if(!isset($privacyPolicyLink))
        {
            $privacyPolicyLink = Config::getInstance()->getCfgVal('privacy_policy_link');
            if($privacyPolicyLink == '?faction=gdpr')
                $privacyPolicyLink = INDEXFILE . $privacyPolicyLink . SID_AMPER;
            $this->assign('privacyPolicyLink', $privacyPolicyLink);
        }
        $this->display('PageTail', ['creationTime' => microtime(true)-SCRIPTSTART,
            'processedFiles' => Functions::getFileCounter(),
            'memoryUsage' => memory_get_usage()/1024]);
    }

    /**
     * Tests template engine installation and returns found errors.
     *
     * @return array Reported errors during test run
     */
    public function testTplInstallation(): array
    {
        $errors = [];
        $this->smarty->testInstall($errors);
        return $errors;
    }
}
?>