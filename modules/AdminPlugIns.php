<?php
/**
 * Manages plug-ins.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2024 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class AdminPlugIns extends PublicModule
{
    use Singleton, Mode;

    /**
     * Sets mode.
     *
     * @param string $mode Mode to execute
     */
    function __construct(string $mode)
    {
        parent::__construct();
        $this->mode = $mode;
        PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_PLUG_INS_INIT);
    }

    /**
     * Executes module.
     */
    public function publicCall(): void
    {
        Functions::accessAdminPanel();
        NavBar::getInstance()->addElement(Language::getInstance()->getString('manage_plug_ins'), INDEXFILE . '?faction=adminPlugIns' . SID_AMPER);
        if(Config::getInstance()->getCfgVal('activate_plug_ins') != 1)
            Template::getInstance()->printMessage('function_deactivated');
        switch($this->mode)
        {
            case 'delete':
            $plugInFile = basename(Functions::getValueFromGlobals('plugIn'));
            PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_PLUG_INS_DELETE_PLUG_IN, $plugInFile);
            if(PlugIns::getInstance()->deletePlugIn($plugInFile))
                Logger::getInstance()->log('%s deleted plug-in ' . $plugInFile, Logger::LOG_ACP_ACTION);
            else
                Template::getInstance()->printMessage('plug_in_not_found');

//AdminPlugIns
            default:
            $plugIns = [];
            foreach(PlugIns::getInstance()->getPlugIns() as $curPlugInFile => $curPlugIn)
                $plugIns[$curPlugInFile] = ['author' => $curPlugIn->getAuthorName(),
                    'website' => $curPlugIn->getAuthorUrl(),
                    'name' => $curPlugIn->getName(),
                    'description' => $curPlugIn->getDescription(),
                    'version' => $curPlugIn->getVersion(),
                    'minVersion' => $curPlugIn->getMinVersion()];
            PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_PLUG_INS_SHOW_PLUG_IN, $plugIns);
            Template::getInstance()->assign('plugIns', $plugIns);
            break;
        }
        Template::getInstance()->printPage('AdminPlugIns');
    }
}
?>