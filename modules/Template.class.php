<?php

include('Template/Smarty.class.php');

class Template extends ModuleTemplate {
	protected $smarty = NULL;

	public function setDirs($dirName) {
		$this->smarty->template_dir = 'templates/'.$dirName.'/files';
		$this->smarty->config_dir = 'templates/'.$dirName.'/config';
		$this->smarty->compile_id = 'templates/'.$dirName;
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
		$this->smarty->compile_dir = 'cache';
		$this->smarty->cache_dir = 'cache';

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

	public function fetch($file,$baseDir = '') {
		if($baseDir != '') {
			$oldTemplateDir = $this->smarty->template_dir;
			$oldCompileID = $this->smarty->compile_id;

			$this->smarty->compile_id = $baseDir;
			$this->smarty->template_dir = $baseDir;

			$result = $this->smarty->fetch($file);

			$this->smarty->compile_id = $oldCompileID;
			$this->smarty->template_dir = $oldTemplateDir;

			return $result;
		}

		return $this->smarty->fetch($file);
	}
}

?>