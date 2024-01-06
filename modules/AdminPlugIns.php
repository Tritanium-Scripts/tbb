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
    use Singleton;

    /**
     * Executes module.
     */
    public function publicCall(): void
    {
        Functions::accessAdminPanel();
        NavBar::getInstance()->addElement(Language::getInstance()->getString('manage_plug_ins'), INDEXFILE . '?faction=adminPlugIns' . SID_AMPER);
        if(Config::getInstance()->getCfgVal('activate_plug_ins') != 1)
            Template::getInstance()->printMessage('function_deactivated');
        $plugIns = [];
        foreach(PlugIns::getInstance()->getPlugIns() as $curPlugIn)
            $plugIns[get_class($curPlugIn)] = ['author' => $curPlugIn->getAuthorName(),
                'website' => $curPlugIn->getAuthorUrl(),
                'name' => $curPlugIn->getName(),
                'description' => $curPlugIn->getDescription()];
        Template::getInstance()->printPage('AdminPlugIns', 'plugIns', $plugIns);
    }
}
?>