<?php

class Cache extends ModuleTemplate {
	protected $requiredModules = array(
		'DB'
	);
	protected $isWritable = FALSE;

	public function initializeMe() {
		$this->isWritable = is_writable('cache');
	}

	public function setSmiliesData() {
		$toWrite1 = $toWrite2 = array();
		$this->modules['DB']->query("SELECT SmileyID,SmileyType,SmileyFileName,SmileySynonym,SmileyStatus FROM ".TBLPFX."smilies WHERE SmileyType='".SMILEY_TYPE_SMILEY."' OR SmileyType='".SMILEY_TYPE_ADMINSMILEY."'");
		while($curSmiley = $this->modules['DB']->fetchArray()) {
			if($curSmiley['SmileyType'] != SMILEY_TYPE_ADMINSMILEY)
				$toWrite1[] = 'array(\'SmileyID\'=>\''.$curSmiley['SmileyID'].'\',\'SmileyFileName\'=>\''.$curSmiley['SmileyFileName'].'\',\'SmileySynonym\'=>\''.$curSmiley['SmileySynonym'].'\',\'SmileyStatus\'=>\''.$curSmiley['SmileyStatus'].'\')';
			$toWrite2[] = '\''.$curSmiley['SmileySynonym'].'\'=>\'<img src="'.$curSmiley['SmileyFileName'].'" border="0" alt="'.$curSmiley['SmileySynonym'].'"/>\'';
		}

		$toWrite1 = '<?php $smiliesDataRead = array('.implode(',',$toWrite1).'); ?>';
		$toWrite2 = '<?php $smiliesDataWrite = array('.implode(',',$toWrite2).'); ?>';

		Functions::FileWrite('cache/cache_smilies_read.php',$toWrite1,'w');
		Functions::FileWrite('cache/cache_smilies_write.php',$toWrite2,'w');
	}

	public function getSmiliesData($mode = 'read') {
		if($mode == 'read'){
			$smiliesDataRead = array();

			if(file_exists('cache/SmiliesRead.cache.php') == TRUE)
				include('cache/SmiliesRead.cache.php');
			else {
				$this->modules['DB']->query("SELECT SmileyID,SmileyFileName,SmileySynonym,SmileyStatus FROM ".TBLPFX."smilies WHERE SmileyType='".SMILEY_TYPE_SMILEY."'");
				$smiliesDataRead = $this->modules['DB']->raw2Array();
			}

			return $smiliesDataRead;
		}
		else {
			$smiliesDataWrite = array();

			if(file_exists('cache/SmiliesWrite.cache.php') == TRUE)
				include('cache/SmiliesWrite.cache.php');
			else {
				$this->modules['DB']->query("SELECT SmileyFileName,SmileySynonym FROM ".TBLPFX."smilies WHERE SmileyType='".SMILEY_TYPE_SMILEY."' OR SmileyType='".SMILEY_TYPE_ADMINSMILEY."'");
				while($curSmiley = $this->modules['DB']->fetchArray())
					$smiliesDataWrite[$curSmiley['SmileySynonym']] = '<img src="'.$curSmiley['SmileyFileName'].'" border="0" alt="'.$curSmiley['SmileySynonym'].'"/>';
			}

			return $smiliesDataWrite;
		}
	}

	public function getAdminSmiliesData() {
		$adminSmiliesData = array();

		if(file_exists('cache/AdminSmilies.cache.php') == TRUE) include('cache/AdminSmilies.cache.php');
		else return $this->setAdminSmiliesData();

		return $adminSmiliesData;
	}

	public function setAdminSmiliesData() {
		$adminSmiliesData = $toWrite = array();

		$this->modules['DB']->query("SELECT SmileyID,SmileyType,SmileyFileName,SmileySynonym,SmileyStatus FROM ".TBLPFX."smilies WHERE SmileyType='".SMILEY_TYPE_ADMINSMILEY."'");
		while($curSmiley = $this->modules['DB']->fetchArray()) {
			$adminSmiliesData[] = array(
				'SmileyID'=>$curSmiley['SmileyID'],
				'SmileyFileName'=>$curSmiley['SmileyFileName'],
				'SmileySynonym'=>$curSmiley['SmileySynonym'],
				'SmileyStatus'=>$curSmiley['SmileyStatus']
			);
			$toWrite[] = 'array(\'SmileyID\'=>\''.$curSmiley['SmileyID'].'\',\'SmileyFileName\'=>\''.$curSmiley['SmileyFileName'].'\',\'SmileySynonym\'=>\''.$curSmiley['SmileySynonym'].'\',\'SmileyStatus\'=>\''.$curSmiley['SmileyStatus'].'\')';
		}

		Functions::FileWrite('cache/AdminSmilies.cache.php','<?php $adminSmiliesData = array('.implode(',',$toWrite).'); ?>','w');

		return $adminSmiliesData;
	}

	public function setPPicsData() {
		$toWrite = $pPicsData = array();

		$this->modules['DB']->query("SELECT SmileyID,SmileyFileName FROM ".TBLPFX."smilies WHERE SmileyType='1'");
		while($curSmiley = $this->modules['DB']->fetchArray()) {
			$toWrite[] = 'array(\'SmileyID\'=>\''.$curSmiley['SmileyID'].'\',\'SmileyFileName\'=>\''.$curSmiley['SmileyFileName'].'\')';
			$pPicsData[] = $curSmiley;
		}

		$toWrite = '<?php $pPicsData = array('.implode(',',$toWrite).'); ?>';

		Functions::FileWrite('cache/PPics.cache.php',$toWrite,'w');

		return $pPicsData;
	}

	public function getPPicsData() {
		$pPicsData = array();

		if(file_exists('cache/PPics.cache.php') == TRUE)
			include('cache/PPics.cache.php');
		else return $$this->setPPicsData();

		return $pPicsData;
	}

	public function setRanksData() {
		$ranksData1 = $ranksData2 = array();

		$this->modules['DB']->query("SELECT * FROM ".TBLPFX."ranks ORDER BY rankPosts");
		while($curRank = $this->modules['DB']->fetchArray()) {
			$curRankGfx = '';

			if($curRank['rankGfx'] != '') {
				$curRankGfx = explode(';',$curRank['rankGfx']);
				while(list($curKey) = each($curRankGfx))
					$curRankGfx[$curKey] = '<img src="'.$curRankGfx[$curKey].'" border="0" alt=""/>';
				$curRankGfx = implode('',$curRankGfx);
			}

			if($curRank['rankType'] == 0)
				$ranksData1[] = "array('rankName'=>'".$curRank['rankName']."','rankPosts'=>'".$curRank['rankPosts']."','rankGfx'=>'".$curRankGfx."')";
			else
				$ranksData2[] = "'".$curRank['rankID']."'=>array('rankName'=>'".$curRank['rankName']."','rankGfx'=>'".$curRankGfx."')";
		}

		$toWrite = '<?php $ranksData = array(array('.implode(",",$ranksData1).'),array('.implode(",",$ranksData2).')); ?>';

		Functions::FileWrite('cache/Ranks.cache.php',$toWrite,'w');

		return array($ranksData1,$ranksData2);
	}

	public function getRanksData() {
		$ranksData = array(array(),array());

		if(file_exists('cache/Ranks.cache.php') == TRUE)
			include('cache/Ranks.cache.php');
		else return $this->setRanksData();

		return $ranksData;
	}

	public function setLanguages() {
		$languages = $languageIDs = array();

		$toWrite1 = array();
		$toWrite2 = array();

		$dP = opendir('languages');
		while($curObj = readdir($dP)) {
			if($dP == '..' || $dP == '.' || file_exists('languages/'.$curObj.'/Language.config') == FALSE) continue;

			$curLanguageConfig = parse_ini_file('languages/'.$curObj.'/Language.config');
			$curSupportedLanguages = explode(',',$curLanguageConfig['supported_languages']);

			foreach($curSupportedLanguages AS $curLanguage) {
				$toWrite1[] = "'$curLanguage'=>'$curObj'";
				$languageIDs[$curLanguage] = $curObj;
			}

			$languages[] = array(
				'Name'=>$curLanguageConfig['language_name'],
				'NativeName'=>$curLanguageConfig['language_name_native'],
				'Dir'=>$curObj,
				'SupportedLanguages'=>$curLanguageConfig['supported_languages']
			);

			$toWrite2[] = "array('Name'=>'".$curLanguageConfig['language_name']."','NativeName'=>'".$curLanguageConfig['language_name_native']."','Dir'=>'".$curObj."','SupportedLanguages'=>'".$curLanguageConfig['supported_languages']."')";
		}
		closedir($dP);

		$toWrite = '<?php $languageIDs = array('.implode(',',$toWrite1).'); $languages = array('.implode(',',$toWrite2).'); ?>';

		Functions::FileWrite('cache/Languages.cache.php',$toWrite,'w');

		return array($languageIDs,$languages);
	}

	public function getLanguages() {
		$languageIDs = array();
		$languages = array();

		if(file_exists('cache/Languages.cache.php') == TRUE) {
			include('cache/Languages.cache.php');
			return array($languageIDs,$languages);
		}
		else return $this->setLanguages();
	}

	public function setConfig() {
		$config = $toWrite = array();
		$this->modules['DB']->query("SELECT * FROM ".TBLPFX."config");
		while($curRow = $this->modules['DB']->fetchArray()) {
			$config[$curRow['ConfigName']] = $curRow['ConfigValue'];
			$toWrite[] = '\''.$curRow['ConfigName'].'\'=>\''.addslashes($curRow['ConfigValue']).'\'';
		}

		$toWrite = '<?php $config = array('.implode(',',$toWrite).'); ?>';

		Functions::FileWrite('cache/Config.cache.php',$toWrite,'w');

		return $config;
	}

	public function getConfig() {
		$config = array();
		if(file_exists('cache/Config.cache.php') == TRUE) include('cache/Config.cache.php');
		else $config = $this->setConfig();
		return $config;
	}

}

?>