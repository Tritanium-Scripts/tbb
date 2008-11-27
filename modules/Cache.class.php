<?php
class Cache extends ModuleTemplate {
	protected $requiredModules = array(
		'DB',
		'Constants'
	);
	protected $isWritable = FALSE;

	public function initializeMe() {
		$this->isWritable = is_writable('cache');
	}

	public function setSmiliesData() {
		$toWrite1 = $toWrite2 = array();
        $this->modules['DB']->queryParams('SELECT "smileyID", "smileyType", "smileyFileName", "smileySynonym", "smileyStatus" FROM '.TBLPFX.'smilies WHERE "smileyType"=$1 OR "smileyType"=$2', array(SMILEY_TYPE_SMILEY, SMILEY_TYPE_ADMINSMILEY));
		while($curSmiley = $this->modules['DB']->fetchArray()) {
			if($curSmiley['smileyType'] != SMILEY_TYPE_ADMINSMILEY)
				$toWrite1[] = 'array(\'smileyID\'=>\''.$curSmiley['smileyID'].'\',\'smileyFileName\'=>\''.$curSmiley['smileyFileName'].'\',\'smileySynonym\'=>\''.$curSmiley['smileySynonym'].'\',\'smileyStatus\'=>\''.$curSmiley['smileyStatus'].'\')';
			$toWrite2[] = '\''.$curSmiley['smileySynonym'].'\'=>\'<img src="'.$curSmiley['smileyFileName'].'" alt="'.$curSmiley['smileySynonym'].'"/>\'';
		}

		$toWrite1 = '<?php $smiliesDataRead = array('.implode(',',$toWrite1).'); ?>';
		$toWrite2 = '<?php $smiliesDataWrite = array('.implode(',',$toWrite2).'); ?>';

		Functions::FileWrite('cache/SmiliesRead.cache.php',$toWrite1,'w');
		Functions::FileWrite('cache/SmiliesWrite.cache.php',$toWrite2,'w');
	}

	public function getSmiliesData($mode = 'read') {
		if($mode == 'read'){
			$smiliesDataRead = array();

			if(file_exists('cache/SmiliesRead.cache.php') == TRUE)
				include('cache/SmiliesRead.cache.php');
			else {
                $this->modules['DB']->queryParams('SELECT "smileyID", "smileyFileName", "smileySynonym", "smileyStatus" FROM '.TBLPFX.'smilies WHERE "smileyType"=$1', array(SMILEY_TYPE_SMILEY));
				$smiliesDataRead = $this->modules['DB']->raw2Array();
			}

			return $smiliesDataRead;
		}
		else {
			$smiliesDataWrite = array();

			if(file_exists('cache/SmiliesWrite.cache.php') == TRUE)
				include('cache/SmiliesWrite.cache.php');
			else {
                $this->modules['DB']->queryParams('SELECT "smileyFileName", "smileySynonym" FROM '.TBLPFX.'smilies WHERE "smileyType"=$1 OR "smileyType"=$2', array(SMILEY_TYPE_SMILEY, SMILEY_TYPE_ADMINSMILEY));
				while($curSmiley = $this->modules['DB']->fetchArray())
					$smiliesDataWrite[$curSmiley['smileySynonym']] = '<img src="'.$curSmiley['smileyFileName'].'" alt="'.$curSmiley['smileySynonym'].'"/>';
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

        $this->modules['DB']->queryParams('SELECT "smileyID", "smileyType", "smileyFileName", "smileySynonym", "smileyStatus" FROM '.TBLPFX.'smilies WHERE "smileyType"=$1', array(SMILEY_TYPE_ADMINSMILEY));
		while($curSmiley = $this->modules['DB']->fetchArray()) {
			$adminSmiliesData[] = array(
				'smileyID'=>$curSmiley['smileyID'],
				'smileyFileName'=>$curSmiley['smileyFileName'],
				'smileySynonym'=>$curSmiley['smileySynonym'],
				'smileyStatus'=>$curSmiley['smileyStatus']
			);
			$toWrite[] = 'array(\'smileyID\'=>\''.$curSmiley['smileyID'].'\',\'smileyFileName\'=>\''.$curSmiley['smileyFileName'].'\',\'smileySynonym\'=>\''.$curSmiley['smileySynonym'].'\',\'smileyStatus\'=>\''.$curSmiley['smileyStatus'].'\')';
		}

		Functions::FileWrite('cache/AdminSmilies.cache.php','<?php $adminSmiliesData = array('.implode(',',$toWrite).'); ?>','w');

		return $adminSmiliesData;
	}

	public function setPostPicsData() {
		$toWrite = $postPicsData = array();

        $this->modules['DB']->queryParams('SELECT "smileyID", "smileyFileName" FROM '.TBLPFX.'smilies WHERE "smileyType"=$1', array(SMILEY_TYPE_TPIC));
		while($curSmiley = $this->modules['DB']->fetchArray()) {
			$toWrite[] = 'array(\'smileyID\'=>\''.$curSmiley['smileyID'].'\',\'smileyFileName\'=>\''.$curSmiley['smileyFileName'].'\')';
			$postPicsData[] = $curSmiley;
		}

		$toWrite = '<?php $postPicsData = array('.implode(',',$toWrite).'); ?>';

		Functions::FileWrite('cache/PostPics.cache.php',$toWrite,'w');

		return $postPicsData;
	}

	public function getPostPicsData() {
		$postPicsData = array();

		if(file_exists('cache/PostPics.cache.php'))
			include('cache/PostPics.cache.php');
		else return $this->setPostPicsData();

		return $postPicsData;
	}

	public function setRanksData() {
		$ranksData1 = $ranksData2 = array();
		$ranksData1ToWrite = $ranksData2ToWrite = array();

		$this->modules['DB']->query('SELECT * FROM '.TBLPFX.'ranks ORDER BY "rankPosts"');
		while($curRank = $this->modules['DB']->fetchArray()) {
			$curRankGfx = '';

			if($curRank['rankGfx'] != '') {
				$curRankGfx = explode(';',$curRank['rankGfx']);
				while(list($curKey) = each($curRankGfx))
					$curRankGfx[$curKey] = '<img src="'.$curRankGfx[$curKey].'" alt=""/>';
				$curRankGfx = implode('',$curRankGfx);
			}

			if($curRank['rankType'] == 0) {
				$ranksData1[] = array('rankName'=>$curRank['rankName'],'rankPosts'=>$curRank['rankPosts'],'rankGfx'=>$curRankGfx);
				$ranksData1ToWrite[] = "array('rankName'=>'".$curRank['rankName']."','rankPosts'=>'".$curRank['rankPosts']."','rankGfx'=>'".$curRankGfx."')";
			}
			else {
				$ranksData2[$curRank['rankID']] = array('rankName'=>$curRank['rankName'],'rankPosts'=>$curRank['rankPosts'],'rankGfx'=>$curRankGfx);
				$ranksData2ToWrite[] = "'".$curRank['rankID']."'=>array('rankName'=>'".$curRank['rankName']."','rankGfx'=>'".$curRankGfx."')";
			}
		}

		$toWrite = '<?php $ranksData = array(array('.implode(",",$ranksData1ToWrite).'),array('.implode(",",$ranksData2ToWrite).')); ?>';

		Functions::FileWrite('cache/Ranks.cache.php',$toWrite,'w');

		return array($ranksData1,$ranksData2);
	}

	public function getRanksData() {
		$ranksData = array(array(),array());

		if(file_exists('cache/Ranks.cache.php'))
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
			if($dP == '..' || $dP == '.' || file_exists('languages/'.$curObj.'/Language.ini') == FALSE) continue;

			$curLanguageConfig = parse_ini_file('languages/'.$curObj.'/Language.ini');
			$curSupportedLanguages = explode(',',$curLanguageConfig['supported_languages']);

			foreach($curSupportedLanguages AS $curLanguage) {
				$toWrite1[] = "'$curLanguage'=>'$curObj'";
				$languageIDs[$curLanguage] = $curObj;
			}

			$languages[] = array(
				'name'=>$curLanguageConfig['language_name'],
				'nativeName'=>$curLanguageConfig['language_name_native'],
				'dir'=>$curObj,
				'supportedLanguages'=>$curLanguageConfig['supported_languages']
			);

			$toWrite2[] = "array('name'=>'".$curLanguageConfig['language_name']."','nativeName'=>'".$curLanguageConfig['language_name_native']."','dir'=>'".$curObj."','supportedLanguages'=>'".$curLanguageConfig['supported_languages']."')";
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
		$this->modules['DB']->query('SELECT * FROM '.TBLPFX.'config');
		while($curRow = $this->modules['DB']->fetchArray()) {
			$config[$curRow['configName']] = $curRow['configValue'];
			$toWrite[] = '\''.$curRow['configName'].'\'=>\''.addslashes($curRow['configValue']).'\'';
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