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
				update_config_value('standard_style',$p['standardStyle'],FALSE);

			$this->modules['Config']->updateValue('allow_select_tpl',$p['allowSelectTemplate'],FALSE);
			$this->modules['Config']->updateValue('allow_select_style',$p['allowSelectStyle']);


			include_once('pheader.php');
			show_message($LNG['Template_config_updated'],$LNG['message_template_config_updated'],FALSE);
			include_once('ptail.php'); exit;
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

		if(@$fp = opendir('templates/'.$p_standard_tpl.'/styles')) {
			while($akt_dir = readdir($fp)) {
				if($akt_dir != '.' && $akt_dir != '..')
					$adtemplates_tpl->Blocks['stylerow']->parseCode(FALSE,TRUE);
			}
			closedir($fp);
		}

		$this->modules['Template']->assign(array(
			'templatesData'=>$templatesData,
			'p'=>$p
		));
		$this->modules['Template']->printPage('AdminTemplates.tpl');
	}
}

?>