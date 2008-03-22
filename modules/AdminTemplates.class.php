<?php

class AdminTemplates extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'AuthAdmin',
		'Config',
		'DB',
		'GlobalsAdmin',
		'Language',
		'Navbar',
		'Template'
	);

	public function executeMe() {
		$this->modules['Language']->addFile('AdminTemplates');

		$p = array();
		$p['standardTemplate'] = isset($_POST['p']['standardTemplate']) ? basename($_POST['p']['standardTemplate']) : $this->modules['Config']->getValue('standard_tpl');
		$p['standardStyle'] = isset($_POST['p']['standardStyle']) ? basename($_POST['p']['standardStyle']) : $this->modules['Config']->getValue('standard_style');
		$p['allowSelectTemplate'] = isset($_POST['p']['allowSelectTemplate']) ? $_POST['p']['allowSelectTemplate'] : $this->modules['Config']->getValue('allow_select_tpl');
		$p['allowSelectStyle'] = isset($_POST['p']['allowSelectStyle']) ? $_POST['p']['allowSelectStyle'] : $this->modules['Config']->getValue('allow_select_style');

		if(isset($_GET['doit'])) {
			if($p['standardTemplate'] != $this->modules['Config']->getValue('standard_tpl')) {
				$this->modules['Config']->updateValue('standard_tpl',$p['standardTemplate'],FALSE);

				$templateConfig = parse_ini_file('templates/'.$p['standardTemplate'].'/config/TemplateInfo.ini');
				$this->modules['Config']->updateValue('standard_style',$templateConfig['standardStyle']);

				Functions::myHeader(INDEXFILE.'?action=AdminTemplates&'.MYSID);
			}
			elseif(file_exists('templates/'.$p['standardTemplate'].'/styles/'.$p['standardStyle']))
				$this->modules['Config']->updateValue('standard_style',$p['standardStyle'],FALSE);

			$this->modules['Config']->updateValue('allow_select_tpl',$p['allowSelectTemplate'],FALSE);
			$this->modules['Config']->updateValue('allow_select_style',$p['allowSelectStyle']);


			FuncMisc::printMessage('template_config_updated'); exit;
		}

		$templatesData = array();
		if(@$dp = opendir('templates')) {
			while($curObject = readdir($dp)) {
				if(Functions::substr($curObject,0,1) == '.') continue;

				$curTemplateInfo = parse_ini_file('templates/'.$curObject.'/config/TemplateInfo.ini',TRUE);
				$templatesData[] = array(
					'templateDir'=>$curObject,
					'templateInfo'=>$curTemplateInfo
				);
			}
			closedir($dp);
		}

		$stylesData = array();
		if(@$dp = opendir('templates/'.$p['standardTemplate'].'/styles')) {
			while($curObject = readdir($dp)) {
				if($curObject == '.' || $curObject == '..') continue;
				
				$stylesData[] = $curObject;
			}
			closedir($dp);
		}

		$this->modules['Template']->assign(array(
			'templatesData'=>$templatesData,
			'stylesData'=>$stylesData,
			'p'=>$p
		));
		$this->modules['Template']->printPage('AdminTemplates.tpl');
	}
}

?>