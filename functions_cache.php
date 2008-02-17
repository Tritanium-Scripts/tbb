<?php
/**
*
* Tritanium Bulletin Board 2 - functions_cache.php
* version #2005-05-02-18-17-06
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('functions_files.php');

function cache_set_smilies_data() {
	global $DB;

	$towrite1 = $towrite2 = array();
	$DB->query("SELECT smiley_id,smiley_gfx,smiley_synonym,smiley_status FROM ".TBLPFX."smilies WHERE smiley_type='0'");
	while($akt_smiley = $DB->fetch_array()) {
		$towrite1[] = 'array(\'smiley_id\'=>\''.$akt_smiley['smiley_id'].'\',\'smiley_gfx\'=>\''.$akt_smiley['smiley_gfx'].'\',\'smiley_synonym\'=>\''.$akt_smiley['smiley_synonym'].'\',\'smiley_status\'=>\''.$akt_smiley['smiley_status'].'\')';
		$towrite2[] = '\''.$akt_smiley['smiley_synonym'].'\'=>\'<img src="'.$akt_smiley['smiley_gfx'].'" border="0" alt="'.$akt_smiley['smiley_synonym'].'" />\'';
	}

	$towrite1 = '<?php $SMILIES_DATA_READ = array('.implode(',',$towrite1).'); ?>';
	$towrite2 = '<?php $SMILIES_DATA_WRITE = array('.implode(',',$towrite2).'); ?>';

	file_write('cache/cache_smilies_read.php',$towrite1,'w');
	file_write('cache/cache_smilies_write.php',$towrite2,'w');
}

function cache_get_smilies_data($mode = 'read') {
	global $DB;

	if($mode == 'read'){
		$SMILIES_DATA_READ = array();

		if(file_exists('cache/cache_smilies_read.php') == TRUE)
			include('cache/cache_smilies_read.php');
		else {
			$DB->query("SELECT smiley_id,smiley_gfx,smiley_synonym,smiley_status FROM ".TBLPFX."smilies WHERE smiley_type='0'");
			$SMILIES_DATA_READ = $DB->raw2array();
		}

		return $SMILIES_DATA_READ;
	}
	else {
		$SMILIES_DATA_WRITE = array();

		if(file_exists('cache/cache_smilies_write.php') == TRUE)
			include('cache/cache_smilies_write.php');
		else {
			$DB->query("SELECT smiley_gfx,smiley_synonym FROM ".TBLPFX."smilies WHERE smiley_type='0'");
			while($akt_smiley = $DB->fetch_array())
				$SMILIES_DATA_WRITE[$akt_smiley['smiley_synonym']] = '<img src="'.$akt_smiley['smiley_gfx'].'" border="0" alt="'.$akt_smiley['smiley_synonym'].'" />';
		}

		return $SMILIES_DATA_WRITE;
	}
}

function cache_set_ppics_data() {
	global $DB;

	$towrite = array();
	$DB->query("SELECT smiley_id,smiley_gfx FROM ".TBLPFX."smilies WHERE smiley_type='1'");
	while($akt_smiley = $DB->fetch_array())
		$towrite[] = 'array(\'smiley_id\'=>\''.$akt_smiley['smiley_id'].'\',\'smiley_gfx\'=>\''.$akt_smiley['smiley_gfx'].'\')';

	$towrite = '<?php $PPICS_DATA = array('.implode(',',$towrite).'); ?>';

	file_write('cache/cache_ppics.php',$towrite,'w');
}

function cache_get_ppics_data() {
	global $DB;

	$PPICS_DATA = array();

	if(file_exists('cache/cache_ppics.php') == TRUE)
		include('cache/cache_ppics.php');
	else {
		$DB->query("SELECT smiley_id,smiley_gfx FROM ".TBLPFX."smilies WHERE smiley_type='1'");
		$PPICS_DATA = $DB->raw2array();
	}

	return $PPICS_DATA;
}

function cache_set_ranks_data() {
	global $DB;

	$ranks_data_1 = $ranks_data_2 = array();

	$DB->query("SELECT * FROM ".TBLPFX."ranks ORDER BY rank_posts");
	while($akt_rank = $DB->fetch_array()) {
		$akt_rank_gfx = '';

		if($akt_rank['rank_gfx'] != '') {
			$akt_rank_gfx = explode(';',$akt_rank['rank_gfx']);
			while(list($akt_key) = each($akt_rank_gfx))
				$akt_rank_gfx[$akt_key] = '<img src="'.$akt_rank_gfx[$akt_key].'" border="0" alt="" />';
			$akt_rank_gfx = implode('',$akt_rank_gfx);
		}

		if($akt_rank['rank_type'] == 0)
			$ranks_data_1[] = "array('rank_name'=>'".$akt_rank['rank_name']."','rank_posts'=>'".$akt_rank['rank_posts']."','rank_gfx'=>'".$akt_rank_gfx."')";
		else
			$ranks_data_2[] = "'".$akt_rank['rank_id']."'=>array('rank_name'=>'".$akt_rank['rank_name']."','rank_gfx'=>'".$akt_rank_gfx."')";
	}

	$towrite = '<?php $RANKS_DATA = array(array('.implode(",",$ranks_data_1).'),array('.implode(",",$ranks_data_2).')); ?>';

	file_write('cache/cache_ranks.php',$towrite,'w');
}

function cache_get_ranks_data() {
	global $DB;

	$RANKS_DATA = array(array(),array());

	if(file_exists('cache/cache_ranks.php') == TRUE)
		include('cache/cache_ranks.php');
	else {
		$DB->query("SELECT * FROM ".TBLPFX."ranks ORDER BY rank_posts");
		while($akt_rank = $DB->fetch_array()) {
			$akt_rank_gfx = '';

			if($akt_rank['rank_gfx'] != '') {
				$akt_rank_gfx = explode(';',$akt_rank['rank_gfx']);
				while(list($akt_key) = each($akt_rank_gfx))
					$akt_rank_gfx[$akt_key] = '<img src="'.$akt_rank_gfx[$akt_key].'" border="0" alt="" />';
				$akt_rank_gfx = implode('',$akt_rank_gfx);
			}

			if($akt_rank['rank_type'] == 0) {
				$RANKS_DATA[0][] = array(
					'rank_name'=>$akt_rank['rank_name'],
					'rank_posts'=>$akt_rank['rank_posts'],
					'rank_gfx'=>$akt_rank_gfx
				);
			}
			else {
				$RANKS_DATA[1][$akt_rank['rank_id']] = array(
					'rank_name'=>$akt_rank['rank_name'],
					'rank_gfx'=>$akt_rank_gfx
				);
			}
		}
	}

	return $RANKS_DATA;
}

function cache_set_newest_user_data() {
	global $DB;

	$DB->query("SELECT user_nick,user_id FROM ".TBLPFX."users ORDER BY user_regtime DESC LIMIT 1");
	$newest_user_data = $DB->fetch_array();
	$DB->query("UPDATE ".TBLPFX."config SET config_value='".$newest_user_data['user_id']."' WHERE config_name='newest_user_id'");
	$DB->query("UPDATE ".TBLPFX."config SET config_value='".$newest_user_data['user_nick']."' WHERE config_name='newest_user_nick'");
}

function cache_set_languages() {
	$towrite1 = array();
	$towrite2 = array();
	
	$dp = opendir('languages');
	while($cur_obj = readdir($dp)) {
		if($dp == '..' || $dp == '.' || file_exists('languages/'.$cur_obj.'/language.cfg') == FALSE) continue;
		
		$cur_language_config = parse_ini_file('languages/'.$cur_obj.'/language.cfg');
		$cur_supported_languages = explode(',',$cur_language_config['supported_languages']);
		
		foreach($cur_supported_languages AS $cur_language)
			$towrite1[] = "'$cur_language'=>'$cur_obj'";
		
		$towrite2[] = "array('name'=>'".$cur_language_config['language_name']."','native_name'=>'".$cur_language_config['language_name_native']."','dir'=>'".$cur_obj."','supported_languages'=>'".$cur_language_config['supported_languages']."')";
	}
	closedir($dp);

	$towrite = '<?php $LANGUAGE_IDS = array('.implode(',',$towrite1).'); $LANGUAGES = array('.implode(',',$towrite2).'); ?>';
	
	file_write('cache/cache_languages.php',$towrite,'w');
}

function cache_get_languages() {
	global $LANGUAGE_IDS,$LANGUAGES;
	
	$LANGUAGE_IDS = array();
	$LANGUAGES = array();
	
	if(file_exists('cache/cache_languages.php') == TRUE)
		include('cache/cache_languages.php');
	else {
		$dp = opendir('languages');
		while($cur_obj = readdir($dp)) {
			if($dp == '..' || $dp == '.' || file_exists('languages/'.$cur_obj.'/language.cfg') == FALSE) continue;
			
			$cur_language_config = parse_ini_file('languages/'.$cur_obj.'/language.cfg');
			$cur_supported_languages = explode(',',$cur_language_config['supported_languages']);
			
			foreach($cur_supported_languages AS $cur_language)
				$LANGUAGE_IDS[$cur_language] = $cur_obj;
			
			$LANGUAGES[] = array(
				'name'=>$cur_language_config['language_name'],
				'native_name'=>$cur_language_config['language_name_native'],
				'dir'=>$cur_obj,
				'supported_languages'=>$cur_language_config['supported_languages']
			);
		}
		closedir($dp);
	}	
}


function cache_set_all_data() {
	cache_set_smilies_data();
	cache_set_ppics_data();
	cache_set_ranks_data();
	cache_set_languages();
}

?>