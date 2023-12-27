<?php
/**
 * Manages and tests templates, styles and their configuration.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2023 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class AdminTemplate extends PublicModule
{
    use Singleton;

    /**
     * Detects available templates and styles, updates configuration and tests installation.
     */
    public function publicCall(): void
    {
        $oldTableWidth = Config::getInstance()->getCfgVal('twidth');
        Functions::accessAdminPanel();
        NavBar::getInstance()->addElement(Language::getInstance()->getString('manage_templates'), INDEXFILE . '?faction=adminTemplate' . SID_AMPER);
        if(Functions::getValueFromGlobals('update') == 'true')
            if(Functions::getValueFromGlobals('testInstall') != '')
            {
                Template::getInstance()->assign('errors', Template::getInstance()->testTplInstallation());
                Logger::getInstance()->log('%s tested template installation', LOG_ACP_ACTION);
            }
            else
            {
                Config::getInstance()->setCfgVal('twidth', $oldTableWidth);
                Config::getInstance()->setCfgVal('default_tpl', $newTplID = Functions::getValueFromGlobals('template'));
                $styles = Functions::getValueFromGlobals('styles');
                Config::getInstance()->setCfgVal('css_file', 'styles/' . $styles[$newTplID]);
                Config::getInstance()->setCfgVal('select_tpls', Functions::getValueFromGlobals('isTplSelectable') == 'true' ? 1 : 0);
                Config::getInstance()->setCfgVal('select_styles', Functions::getValueFromGlobals('isStyleSelectable') == 'true' ? 1 : 0, true);
                Logger::getInstance()->log('%s updated template config', LOG_ACP_ACTION);
                Template::getInstance()->printMessage('template_configuration_updated');
            }
        Template::getInstance()->printPage('AdminTemplate', array('templates' => Template::getInstance()->getAvailableTpls(),
            'defaultTplID' => Config::getInstance()->getCfgVal('default_tpl'),
            'defaultStyle' => basename(Config::getInstance()->getCfgVal('css_file'))));
    }
}
?>