<?php
/**
 * Inits Smarty, assign values to templates and prints pages.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
include('Template/Smarty.class.php');
/**
 * Wrapper class for Smarty API.
 */
class Template
{
	/**
	 * The Smarty object to work with.
	 *
	 * @var Smarty Smarty object
	 */
	private $smarty;

	/**
	 * Sets up Smarty instance.
	 */
	function __construct()
	{
		$this->smarty = new Smarty;
		$this->smarty->setErrorReporting(E_ALL);
		$this->smarty->setCacheDir('cache');
		$this->smarty->setCompileDir('cache');
		$stdTpl = Main::getModule('Config')->getCfgVal('default_tpl');
		$this->smarty->setTemplateDir('templates/' . $stdTpl . '/templates');
		$this->smarty->setConfigDir('templates/' . $stdTpl);
		$this->smarty->setCompileId('templates/' . $stdTpl);
		$this->smarty->setCaching(Main::getModule('Config')->getCfgVal('use_file_caching') == 1);
		$this->smarty->assignByRef('modules', Main::getModules());
	}

	public function assign($tplVar, $value=null)
	{
		$this->smarty->assign($tplVar, $value);
	}

	/**
	 * Displays a template file.
	 *
	 * @param string $tplName Name of template file
	 */
	public function display($tplName)
	{
		$this->smarty->display($tplName . '.tpl');
	}

	/**
	 * Assigns values to template and displays it.
	 *
	 * @param string $tplName Name of template file
	 * @param string|array $tplVar
	 * @param mixed $value
	 */
	public function display($tplName, $tplVar, $value=null)
	{
		$this->smarty->assign($tplVar, $value);
		$this->smarty->display($tplName . '.tpl');
	}

	/**
	 * Prints a full page message and exits any further program execution.
	 *
	 * @param string $msgIndex Identifier part of message title and text
	 * @param mixed $args,... Optional arguments to be replaced in message text
	 */
	public function printMessage($msgIndex, $args=null)
	{
		$this->display('Message', array('msgTitle' => Main::getModule('Language')->getString('title_' . $msgIndex, 'Messages'),
			'msgText' => sprintf(Main::getModule('Language')->getString('text_' . $msgIndex), array_splice(func_get_args(), 1))));
		exit();
	}
}
?>