<?php
include('Template/Smarty.class.php');
/**
 * Wrapper class for Smarty API.
 */
class Template
{
	/**
	 * The Smarty object to work with.
	 *
	 * @var mixed Smarty object
	 */
	private $smarty;

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
	}

	public function assign($tplVar, $value=null)
	{
		$this->smarty->assign($tplVar, $value);
	}

	public function display($template)
	{
		$this->smarty->display($template);
	}
}
?>