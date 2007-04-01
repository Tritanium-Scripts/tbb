<?php

include('Template/Smarty.class.php');

class Template extends ModuleTemplate {
	protected $smarty = NULL;

	public function setDirs($dirName) {
		$this->smarty->template_dir = 'templates/'.$dirName.'/templates';
		$this->smarty->compile_dir = 'templates/'.$dirName.'/templates_c';
		$this->smarty->cache_dir = 'templates/'.$dirName.'/cache';
		$this->smarty->config_dir = 'templates/'.$dirName.'/configs';
	}

	public function getTemplateDir() {
		return $this->smarty->template_dir;
	}

	public function getTD() {
		return $this->smarty->template_dir;
	}

	public function initializeMe() {
		$this->smarty = new Smarty;

		$this->smarty->error_reporting = E_ALL;

		$this->setDirs($this->getC('defaultTemplateDir'));
		$this->smarty->assign('indexFile',INDEXFILE);

		$modules = &Factory::getInstances();
		$this->smarty->assign_by_ref('modules',$modules);
	}

	public function assign($value1, $value2 = NULL) {
		$this->smarty->assign($value1, $value2);
	}

	public function display($value1) {
		$this->smarty->display($value1);
	}

	public function fetch($value) {
		return $this->smarty->fetch($value);
	}
}

?>