<?php

class Cache extends ModuleTemplate {
	protected $RequiredModules = array(
		'DB'
	);
	protected $isWritable = FALSE;

	public function initializeMe() {
		$this->isWritable = is_writable('cache');
	}

	public function setSmiliesData() {
		$ToWrite1 = $ToWrite2 = array();
		$this->Modules['DB']->query("SELECT SmileyID,SmileyType,SmileyFileName,SmileySynonym,SmileyStatus FROM ".TBLPFX."smilies WHERE SmileyType='".SMILEY_TYPE_SMILEY."' OR SmileyType='".SMILEY_TYPE_ADMINSMILEY."'");
		while($curSmiley = $this->Modules['DB']->fetchArray()) {
			if($curSmiley['SmileyType'] != SMILEY_TYPE_ADMINSMILEY)
				$ToWrite1[] = 'array(\'SmileyID\'=>\''.$curSmiley['SmileyID'].'\',\'SmileyFileName\'=>\''.$curSmiley['SmileyFileName'].'\',\'SmileySynonym\'=>\''.$curSmiley['SmileySynonym'].'\',\'SmileyStatus\'=>\''.$curSmiley['SmileyStatus'].'\')';
			$ToWrite2[] = '\''.$curSmiley['SmileySynonym'].'\'=>\'<img src="'.$curSmiley['SmileyFileName'].'" border="0" alt="'.$curSmiley['SmileySynonym'].'"/>\'';
		}

		$ToWrite1 = '<?php $SmiliesDataRead = array('.implode(',',$ToWrite1).'); ?>';
		$ToWrite2 = '<?php $SmiliesDataWrite = array('.implode(',',$ToWrite2).'); ?>';

		Functions::FileWrite('cache/cache_smilies_read.php',$ToWrite1,'w');
		Functions::FileWrite('cache/cache_smilies_write.php',$ToWrite2,'w');
	}

	public function getSmiliesData($Mode = 'read') {
		if($Mode == 'read'){
			$SmiliesDataRead = array();

			if(file_exists('cache/SmiliesRead.cache.php') == TRUE)
				include('cache/SmiliesRead.cache.php');
			else {
				$this->Modules['DB']->query("SELECT SmileyID,SmileyFileName,SmileySynonym,SmileyStatus FROM ".TBLPFX."smilies WHERE SmileyType='".SMILEY_TYPE_SMILEY."'");
				$SmiliesDataRead = $this->Modules['DB']->Raw2Array();
			}

			return $SmiliesDataRead;
		}
		else {
			$SmiliesDataWrite = array();

			if(file_exists('cache/SmiliesWrite.cache.php') == TRUE)
				include('cache/SmiliesWrite.cache.php');
			else {
				$this->Modules['DB']->query("SELECT SmileyFileName,SmileySynonym FROM ".TBLPFX."smilies WHERE SmileyType='".SMILEY_TYPE_SMILEY."' OR SmileyType='".SMILEY_TYPE_ADMINSMILEY."'");
				while($curSmiley = $this->Modules['DB']->fetchArray())
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
		$AdminSmiliesData = $ToWrite = array();

		$this->Modules['DB']->query("SELECT SmileyID,SmileyType,SmileyFileName,SmileySynonym,SmileyStatus FROM ".TBLPFX."smilies WHERE SmileyType='".SMILEY_TYPE_ADMINSMILEY."'");
		while($curSmiley = $this->Modules['DB']->fetchArray()) {
			$AdminSmiliesData[] = array(
				'SmileyID'=>$curSmiley['SmileyID'],
				'SmileyFileName'=>$curSmiley['SmileyFileName'],
				'SmileySynonym'=>$curSmiley['SmileySynonym'],
				'SmileyStatus'=>$curSmiley['SmileyStatus']
			);
			$ToWrite[] = 'array(\'SmileyID\'=>\''.$curSmiley['SmileyID'].'\',\'SmileyFileName\'=>\''.$curSmiley['SmileyFileName'].'\',\'SmileySynonym\'=>\''.$curSmiley['SmileySynonym'].'\',\'SmileyStatus\'=>\''.$curSmiley['SmileyStatus'].'\')';
		}

		Functions::FileWrite('cache/AdminSmilies.cache.php','<?php $AdminSmiliesData = array('.implode(',',$ToWrite).'); ?>','w');

		return $AdminSmiliesData;
	}

	public function setPPicsData() {
		$ToWrite = $PPicsData = array();

		$this->Modules['DB']->query("SELECT SmileyID,SmileyFileName FROM ".TBLPFX."smilies WHERE SmileyType='1'");
		while($curSmiley = $this->Modules['DB']->fetchArray()) {
			$ToWrite[] = 'array(\'SmileyID\'=>\''.$curSmiley['SmileyID'].'\',\'SmileyFileName\'=>\''.$curSmiley['SmileyFileName'].'\')';
			$PPicsData[] = $curSmiley;
		}

		$ToWrite = '<?php $PPicsData = array('.implode(',',$ToWrite).'); ?>';

		Functions::FileWrite('cache/PPics.cache.php',$ToWrite,'w');

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

		$this->Modules['DB']->query("SELECT * FROM ".TBLPFX."ranks ORDER BY RankPosts");
		while($curRank = $this->Modules['DB']->fetchArray()) {
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

		$ToWrite = '<?php $RanksData = array(array('.implode(",",$RanksData1).'),array('.implode(",",$RanksData2).')); ?>';

		Functions::FileWrite('cache/Ranks.cache.php',$ToWrite,'w');

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

		$ToWrite1 = array();
		$ToWrite2 = array();

		$DP = opendir('languages');
		while($curObj = readdir($DP)) {
			if($DP == '..' || $DP == '.' || file_exists('languages/'.$curObj.'/Language.config') == FALSE) continue;

			$curLanguageConfig = parse_ini_file('languages/'.$curObj.'/Language.config');
			$curSupportedLanguages = explode(',',$curLanguageConfig['supported_languages']);

			foreach($curSupportedLanguages AS $curLanguage) {
				$ToWrite1[] = "'$curLanguage'=>'$curObj'";
				$LanguageIDs[$curLanguage] = $curObj;
			}

			$Languages[] = array(
				'Name'=>$curLanguageConfig['language_name'],
				'NativeName'=>$curLanguageConfig['language_name_native'],
				'Dir'=>$curObj,
				'SupportedLanguages'=>$curLanguageConfig['supported_languages']
			);

			$ToWrite2[] = "array('Name'=>'".$curLanguageConfig['language_name']."','NativeName'=>'".$curLanguageConfig['language_name_native']."','Dir'=>'".$curObj."','SupportedLanguages'=>'".$curLanguageConfig['supported_languages']."')";
		}
		closedir($DP);

		$ToWrite = '<?php $LanguageIDs = array('.implode(',',$ToWrite1).'); $Languages = array('.implode(',',$ToWrite2).'); ?>';

		Functions::FileWrite('cache/Languages.cache.php',$ToWrite,'w');

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
		$Config = $ToWrite = array();
		$this->Modules['DB']->query("SELECT * FROM ".TBLPFX."config");
		while($curRow = $this->Modules['DB']->fetchArray()) {
			$Config[$curRow['ConfigName']] = $curRow['ConfigValue'];
			$ToWrite[] = '\''.$curRow['ConfigName'].'\'=>\''.addslashes($curRow['ConfigValue']).'\'';
		}

		$ToWrite = '<?php $Config = array('.implode(',',$ToWrite).'); ?>';

		Functions::FileWrite('cache/Config.cache.php',$ToWrite,'w');

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