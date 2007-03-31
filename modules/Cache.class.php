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

		$toWrite1 = '<?php $SmiliesDataRead = array('.implode(',',$toWrite1).'); ?>';
		$toWrite2 = '<?php $SmiliesDataWrite = array('.implode(',',$toWrite2).'); ?>';

		Functions::FileWrite('cache/cache_smilies_read.php',$toWrite1,'w');
		Functions::FileWrite('cache/cache_smilies_write.php',$toWrite2,'w');
	}

	public function getSmiliesData($Mode = 'read') {
		if($Mode == 'read'){
			$SmiliesDataRead = array();

			if(file_exists('cache/SmiliesRead.cache.php') == TRUE)
				include('cache/SmiliesRead.cache.php');
			else {
				$this->modules['DB']->query("SELECT SmileyID,SmileyFileName,SmileySynonym,SmileyStatus FROM ".TBLPFX."smilies WHERE SmileyType='".SMILEY_TYPE_SMILEY."'");
				$SmiliesDataRead = $this->modules['DB']->Raw2Array();
			}

			return $SmiliesDataRead;
		}
		else {
			$SmiliesDataWrite = array();

			if(file_exists('cache/SmiliesWrite.cache.php') == TRUE)
				include('cache/SmiliesWrite.cache.php');
			else {
				$this->modules['DB']->query("SELECT SmileyFileName,SmileySynonym FROM ".TBLPFX."smilies WHERE SmileyType='".SMILEY_TYPE_SMILEY."' OR SmileyType='".SMILEY_TYPE_ADMINSMILEY."'");
				while($curSmiley = $this->modules['DB']->fetchArray())
					$SmiliesDataWrite[$curSmiley['SmileySynonym']] = '<img src="'.$curSmiley['SmileyFileName'].'" border="0" alt="'.$curSmiley['SmileySynonym'].'"/>';
			}

			return $SmiliesDataWrite;
		}
	}

	public function getAdminSmiliesData() {
		$AdminSmiliesData = array();

		if(file_exists('cache/AdminSmilies.cache.php') == TRUE) include('cache/AdminSmilies.cache.php');
		else return $this->setAdminSmiliesData();

		return $AdminSmiliesData;
	}

	public function setAdminSmiliesData() {
		$AdminSmiliesData = $toWrite = array();

		$this->modules['DB']->query("SELECT SmileyID,SmileyType,SmileyFileName,SmileySynonym,SmileyStatus FROM ".TBLPFX."smilies WHERE SmileyType='".SMILEY_TYPE_ADMINSMILEY."'");
		while($curSmiley = $this->modules['DB']->fetchArray()) {
			$AdminSmiliesData[] = array(
				'SmileyID'=>$curSmiley['SmileyID'],
				'SmileyFileName'=>$curSmiley['SmileyFileName'],
				'SmileySynonym'=>$curSmiley['SmileySynonym'],
				'SmileyStatus'=>$curSmiley['SmileyStatus']
			);
			$toWrite[] = 'array(\'SmileyID\'=>\''.$curSmiley['SmileyID'].'\',\'SmileyFileName\'=>\''.$curSmiley['SmileyFileName'].'\',\'SmileySynonym\'=>\''.$curSmiley['SmileySynonym'].'\',\'SmileyStatus\'=>\''.$curSmiley['SmileyStatus'].'\')';
		}

		Functions::FileWrite('cache/AdminSmilies.cache.php','<?php $AdminSmiliesData = array('.implode(',',$toWrite).'); ?>','w');

		return $AdminSmiliesData;
	}

	public function setPPicsData() {
		$toWrite = $PPicsData = array();

		$this->modules['DB']->query("SELECT SmileyID,SmileyFileName FROM ".TBLPFX."smilies WHERE SmileyType='1'");
		while($curSmiley = $this->modules['DB']->fetchArray()) {
			$toWrite[] = 'array(\'SmileyID\'=>\''.$curSmiley['SmileyID'].'\',\'SmileyFileName\'=>\''.$curSmiley['SmileyFileName'].'\')';
			$PPicsData[] = $curSmiley;
		}

		$toWrite = '<?php $PPicsData = array('.implode(',',$toWrite).'); ?>';

		Functions::FileWrite('cache/PPics.cache.php',$toWrite,'w');

		return $PPicsData;
	}

	public function getPPicsData() {
		$PPicsData = array();

		if(file_exists('cache/PPics.cache.php') == TRUE)
			include('cache/PPics.cache.php');
		else return $$this->setPPicsData();

		return $PPicsData;
	}

	public function setRanksData() {
		$RanksData1 = $RanksData2 = array();

		$this->modules['DB']->query("SELECT * FROM ".TBLPFX."ranks ORDER BY RankPosts");
		while($curRank = $this->modules['DB']->fetchArray()) {
			$curRankGfx = '';

			if($curRank['RankGfx'] != '') {
				$curRankGfx = explode(';',$curRank['RankGfx']);
				while(list($curKey) = each($curRankGfx))
					$curRankGfx[$curKey] = '<img src="'.$curRankGfx[$curKey].'" border="0" alt=""/>';
				$curRankGfx = implode('',$curRankGfx);
			}

			if($curRank['RankType'] == 0)
				$RanksData1[] = "array('RankName'=>'".$curRank['RankName']."','RankPosts'=>'".$curRank['RankPosts']."','RankGfx'=>'".$curRankGfx."')";
			else
				$RanksData2[] = "'".$curRank['RankID']."'=>array('RankName'=>'".$curRank['RankName']."','RankGfx'=>'".$curRankGfx."')";
		}

		$toWrite = '<?php $RanksData = array(array('.implode(",",$RanksData1).'),array('.implode(",",$RanksData2).')); ?>';

		Functions::FileWrite('cache/Ranks.cache.php',$toWrite,'w');

		return array($RanksData1,$RanksData2);
	}

	public function getRanksData() {
		$RanksData = array(array(),array());

		if(file_exists('cache/Ranks.cache.php') == TRUE)
			include('cache/Ranks.cache.php');
		else return $this->setRanksData();

		return $RanksData;
	}

	public function setLanguages() {
		$Languages = $LanguageIDs = array();

		$toWrite1 = array();
		$toWrite2 = array();

		$DP = opendir('languages');
		while($curObj = readdir($DP)) {
			if($DP == '..' || $DP == '.' || file_exists('languages/'.$curObj.'/Language.config') == FALSE) continue;

			$curLanguageConfig = parse_ini_file('languages/'.$curObj.'/Language.config');
			$curSupportedLanguages = explode(',',$curLanguageConfig['supported_languages']);

			foreach($curSupportedLanguages AS $curLanguage) {
				$toWrite1[] = "'$curLanguage'=>'$curObj'";
				$LanguageIDs[$curLanguage] = $curObj;
			}

			$Languages[] = array(
				'Name'=>$curLanguageConfig['language_name'],
				'NativeName'=>$curLanguageConfig['language_name_native'],
				'Dir'=>$curObj,
				'SupportedLanguages'=>$curLanguageConfig['supported_languages']
			);

			$toWrite2[] = "array('Name'=>'".$curLanguageConfig['language_name']."','NativeName'=>'".$curLanguageConfig['language_name_native']."','Dir'=>'".$curObj."','SupportedLanguages'=>'".$curLanguageConfig['supported_languages']."')";
		}
		closedir($DP);

		$toWrite = '<?php $LanguageIDs = array('.implode(',',$toWrite1).'); $Languages = array('.implode(',',$toWrite2).'); ?>';

		Functions::FileWrite('cache/Languages.cache.php',$toWrite,'w');

		return array($LanguageIDs,$Languages);
	}

	public function getLanguages() {
		$LanguageIDs = array();
		$Languages = array();

		if(file_exists('cache/Languages.cache.php') == TRUE) {
			include('cache/Languages.cache.php');
			return array($LanguageIDs,$Languages);
		}
		else return $this->setLanguages();
	}

	public function setConfig() {
		$Config = $toWrite = array();
		$this->modules['DB']->query("SELECT * FROM ".TBLPFX."config");
		while($curRow = $this->modules['DB']->fetchArray()) {
			$Config[$curRow['ConfigName']] = $curRow['ConfigValue'];
			$toWrite[] = '\''.$curRow['ConfigName'].'\'=>\''.addslashes($curRow['ConfigValue']).'\'';
		}

		$toWrite = '<?php $Config = array('.implode(',',$toWrite).'); ?>';

		Functions::FileWrite('cache/Config.cache.php',$toWrite,'w');

		return $Config;
	}

	public function getConfig() {
		$Config = array();
		if(file_exists('cache/Config.cache.php') == TRUE) include('cache/Config.cache.php');
		else $Config = $this->setConfig();
		return $Config;
	}

}

?>