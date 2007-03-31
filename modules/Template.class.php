<?php

include('smarty/Smarty.class.php');

class Template extends Smarty {
	public function setDirs($DirName) {
		$this->template_dir = 'templates/'.$DirName.'/templates';
		$this->compile_dir = 'templates/'.$DirName.'/templates_c';
		$this->cache_dir = 'templates/'.$DirName.'/cache';
		$this->config_dir = 'templates/'.$DirName.'/configs';
	}

	public function getTemplateDir() {
		return $this->template_dir;
	}

	public function getTD() {
		return $this->template_dir;
	}

	public function initializeMe() {
		$this->error_reporting = E_ALL;
	}
}

?>