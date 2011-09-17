<?php
/**
 * Manages and tests templates, styles and their configuration.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010, 2011 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.6
 */
class AdminTemplate implements Module
{
	/**
	 * Detects available templates and styles, updates configuration and tests installation.
	 */
	public function execute()
	{
		$oldTableWidth = Main::getModule('Config')->getCfgVal('twidth');
		Functions::accessAdminPanel();
		Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('manage_templates'), INDEXFILE . '?faction=adminTemplate' . SID_AMPER);
		if(Functions::getValueFromGlobals('update') == 'true')
			if(Functions::getValueFromGlobals('testInstall') != '')
			{
				Main::getModule('Template')->assign('errors', Main::getModule('Template')->testTplInstallation());
				Main::getModule('Logger')->log('%s tested template installation', LOG_ACP_ACTION);
			}
			else
			{
				Main::getModule('Config')->setCfgVal('twidth', $oldTableWidth);
				Main::getModule('Config')->setCfgVal('default_tpl', $newTplID = Functions::getValueFromGlobals('template'));
				$styles = Functions::getValueFromGlobals('styles');
				Main::getModule('Config')->setCfgVal('css_file', 'styles/' . $styles[$newTplID]);
				Main::getModule('Config')->setCfgVal('select_tpls', Functions::getValueFromGlobals('isTplSelectable') == 'true' ? 1 : 0);
				Main::getModule('Config')->setCfgVal('select_styles', Functions::getValueFromGlobals('isStyleSelectable') == 'true' ? 1 : 0, true);
				Main::getModule('Logger')->log('%s updated template config', LOG_ACP_ACTION);
				Main::getModule('Template')->printMessage('template_configuration_updated');
			}
		Main::getModule('Template')->printPage('AdminTemplate', array('templates' => Main::getModule('Template')->getAvailableTpls(),
			'defaultTplID' => Main::getModule('Config')->getCfgVal('default_tpl'),
			'defaultStyle' => basename(Main::getModule('Config')->getCfgVal('css_file'))));
	}
}
?>