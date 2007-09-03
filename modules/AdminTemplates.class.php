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
		$p['standardTemplate'] = isset($_POST['p']['standardTemplate']) ? $_POST['p']['standardTemplate'] : $this->modules['Config']->getValue('standard_tpl');
		$p['standardStyle'] = isset($_POST['p']['standardStyle']) ? $_POST['p']['standardStyle'] : $this->modules['Config']->getValue('standard_style');
		$p['allowSelectTemplate'] = isset($_POST['p']['allowSelectTemplate']) ? $_POST['p']['allowSelectTemplate'] : $this->modules['Config']->getValue('allow_select_tpl');
		$p['allowSelectStyle'] = isset($_POST['p']['allowSelectStyle']) ? $_POST['p']['allowSelectStyle'] : $this->modules['Config']->getValue('allow_select_style');

		if(isset($_GET['doit'])) {
			if($p_standard_tpl != $CONFIG['standard_tpl']) {
				update_config_value('standard_tpl',$standard_tpl,FALSE);

				include('templates/'.$p_standard_tpl.'/template_config.php');
				update_config_value('standard_style',$standard_style);

				header("Location: administration.php?action=ad_templates&$MYSID"); exit;
			}
			else update_config_value('standard_style',$p_standard_style,FALSE);

			update_config_value('allow_select_tpl',$p_allow_select_tpl,FALSE);
			update_config_value('allow_select_style',$p_allow_select_style);

			include_once('pheader.php');
			show_message($LNG['Template_config_updated'],$LNG['message_template_config_updated'],FALSE);
			include_once('ptail.php'); exit;
		}

		$templatesData = array();
		if(@$dp = opendir('templates')) {
			while($curObject = readdir($dp)) {
				if(substr($curObject,0,1) == '.') continue;

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