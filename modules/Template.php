<?php
/**
 * Inits Smarty, manages configuration, assigns values to template files and prints pages.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
require_once('Template/Smarty.class.php');
/**
 * Wrapper class for Smarty 3 API.
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
	 * Directory of used template.
	 *
	 * @var string Template folder
	 */
	private $tplDir;

	/**
	 * Sets up Smarty instance, loads configuration values and assigns default vars.
	 *
	 * @return Template New instance of this class
	 */
	function __construct()
	{
		$this->smarty = new Smarty;
		//Settings
		$this->smarty->setErrorReporting(ERR_REPORTING);
		$this->smarty->setErrorUnassigned(ERR_REPORTING == E_ALL);
		$this->smarty->setCacheDir('cache/');
		$this->smarty->setCompileDir('cache/');
		$this->tplDir = 'templates/' . Main::getModule('Config')->getCfgVal('default_tpl') . '/';
		$this->smarty->setTemplateDir($this->tplDir . 'templates/');
		$this->smarty->setConfigDir($this->tplDir . 'config/');
		$this->smarty->setCompileId($this->tplDir);
		//Load config(s)
		foreach(glob($this->tplDir . 'config/*.conf') as $curConfig)
			$this->smarty->configLoad($curConfig);
		$this->smarty->setDebugging($this->smarty->getConfigVariable('debug'));
		//Assign defaults
		$this->smarty->assignByRef('modules', Main::getModules());
		$this->smarty->assignByRef('smartyTime', $this->smarty->start_time);
	}

	/**
	 * Assigns value(s) to Smarty.
	 *
	 * @param mixed $tplVar Name of value or array with name+value pairs
	 * @param mixed $value Value for single var
	 */
	public function assign($tplVar, $value=null)
	{
		$this->smarty->assign($tplVar, $value);
	}

	/**
	 * Displays a template file and assigns prior optional values to it.
	 *
	 * @param string $tplName Name of template file
	 * @param mixed $tplVar Name of single value or array with name+value pairs
	 * @param mixed $value Value for single var
	 */
	public function display($tplName, $tplVar=null, $value=null)
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
	public function fetch($tplName, $tplVar=null, $value=null)
	{
		if(!empty($tplVar))
			$this->assign($tplVar, $value);
		return $this->smarty->fetch($tplName . '.tpl');
	}

	/**
	 * Returns configuration values from template config file(s).
	 *
	 * @return array All found and loaded config values
	 */
	public function getTplCfg()
	{
		return $this->smarty->getConfigVars();
	}

	/**
	 * Returns used template directory.
	 *
	 * @return string Used template folder
	 */
	public function getTplDir()
	{
		return $this->tplDir;
	}

	/**
	 * Prints the head of a page.
	 */
	public function printHeader()
	{
		//ClickJacking protection
		if(Main::getModule('Config')->getCfgVal('clickjacking') == 1)
			header("X-FRAME-OPTIONS: SAMEORIGIN");
		$this->display('PageHeader');
	}

	/**
	 * Prints a full page message and exits program execution.
	 *
	 * @param string $msgIndex Identifier part of message title and text
	 * @param mixed $args,... Optional arguments to be replaced in message text
	 */
	public function printMessage($msgIndex, $args=null)
	{
		$this->assign('subAction', 'Message');
		//Update NavBar + WIO
		Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('title_' . $msgIndex, 'Messages'));
		Main::getModule('WhoIsOnline')->setLocation('Message');
		//Print message
		$this->printHeader();
		$temp = func_get_args();
		$this->display('Message', array('action' => 'Message',
			'msgTitle' => Main::getModule('Language')->getString('title_' . $msgIndex),
			'msgText' => vsprintf(Main::getModule('Language')->getString('text_' . $msgIndex), array_splice($temp, 1))));
		exit($this->printTail());
	}

	/**
	 * Prints a full page with provided template file, optional values to assign before, additional WIO location and exists program execution.
	 *
	 * @param string $tplName Name of template file
	 * @param mixed $tplVar Name of single value or array with name+value pairs
	 * @param mixed $value Value for single var
	 * @param string $addToWIOLoc Additional value to append to WIO location
	 */
	public function printPage($tplName, $tplVar=null, $value=null, $addToWIOLoc=null)
	{
		if(!empty($tplVar))
			$this->assign($tplVar, $value);
		$this->assign('subAction', $tplName);
		Main::getModule('WhoIsOnline')->setLocation($tplName . $addToWIOLoc);
		$this->printHeader();
		$this->display($tplName);
		exit($this->printTail());
	}

	/**
	 * Prints the tail of a page.
	 */
	public function printTail()
	{
		$this->display('PageTail', array('creationTime' => microtime(true)-SCRIPTSTART,
			'processedFiles' => Functions::getFileCounter(),
			'memoryUsage' => memory_get_usage()/1024));
	}
}
?>