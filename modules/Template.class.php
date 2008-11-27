<?php
include('Template/Smarty.class.php');

class Template extends ModuleTemplate {
	protected $smarty = NULL;
	protected $globalFrame = array();
	protected $subFrames = array();
	protected $templateDir = '';
	protected $inPopup = FALSE;

	public function setDirs($dirName) {
		$this->smarty->template_dir = 'templates/'.$dirName.'/templates';
		$this->smarty->config_dir = 'templates/'.$dirName.'/config';
		$this->smarty->compile_id = 'templates/'.$dirName;
		$this->templateDir = 'templates/'.$dirName;
	}

	public function setInPopup($newInPopup = FALSE) {
		$this->inPopup = $newInPopup;
	}
	
	public function getTemplateDir() {
		return $this->getTD();
	}

	public function getTD() {
		return $this->templateDir;
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

	public function setGlobalFrame($headerFunction, $tailFunction) {
		$this->globalFrame = array($headerFunction,$tailFunction);
	}

	public function registerSubFrame($headerFunction, $tailFunction) {
		$this->subFrames[] = array($headerFunction,$tailFunction);
	}

	public function printMessage($messageTitle,$messageText,$additionalLinks = array(),$inPopup = FALSE) {
		$this->assign(array(
			'messageTitle'=>$messageTitle,
			'messageText'=>$messageText,
			'additionalLinks'=>$additionalLinks,
			'pageInPage'=>(count($this->subFrames) > 0)
		));

		if($inPopup) $this->printPage('Message.tpl');
		else $this->printPage('Message.tpl');
	}

	public function printPage($templateName) {
		$this->printHeader();
		$this->display($templateName);
		$this->printTail();
	}

	public function printHeader() {
		call_user_func($this->globalFrame[0]);
		
		if(!$this->inPopup) {
			foreach($this->subFrames AS $curFrame)
				call_user_func($curFrame[0]);
		}
	}

	public function printTail() {
		if(!$this->inPopup) {
			foreach($this->subFrames AS $curFrame)
				call_user_func($curFrame[1]);
		}
		call_user_func($this->globalFrame[1]);
	}
}
?>