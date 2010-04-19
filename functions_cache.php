<?php
/**
*
* Tritanium Bulletin Board 2 - functions_cache.php
* version #2004-11-15-20-38-18
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

function cache_set_smilies_data() {
	global $db;

	$towrite1 = $towrite2 = array();
	$db->query("SELECT smiley_id,smiley_gfx,smiley_synonym,smiley_status FROM ".TBLPFX."smilies WHERE smiley_type='0'");
	while($akt_smiley = $db->fetch_array()) {
		$towrite1[] = 'array(\'smiley_id\'=>\''.$akt_smiley['smiley_id'].'\',\'smiley_gfx\'=>\''.$akt_smiley['smiley_gfx'].'\',\'smiley_synonym\'=>\''.$akt_smiley['smiley_synonym'].'\',\'smiley_status\'=>\''.$akt_smiley['smiley_status'].'\')';
		$towrite2[] = '\''.$akt_smiley['smiley_synonym'].'\'=>\'<img src="'.$akt_smiley['smiley_gfx'].'" border="0" alt="'.$akt_smiley['smiley_synonym'].'" />\'';
	}

	$towrite1 = '<?php $SMILIES_DATA_READ = array('.implode(',',$towrite1).'); ?>';
	$towrite2 = '<?php $SMILIES_DATA_WRITE = array('.implode(',',$towrite2).'); ?>';

	file_write('cache/cache_smilies_read.php',$towrite1,'w');
	file_write('cache/cache_smilies_write.php',$towrite2,'w');
}

function cache_get_smilies_data($mode = 'read') {
	global $db;

	if($mode == 'read'){
		$SMILIES_DATA_READ = array();

		if(file_exists('cache/cache_smilies_read.php') == TRUE)
			include('cache/cache_smilies_read.php');
		else {
			$db->query("SELECT smiley_id,smiley_gfx,smiley_synonym,smiley_status FROM ".TBLPFX."smilies WHERE smiley_type='0'");
			$SMILIES_DATA_READ = $db->raw2array();
		}

		return $SMILIES_DATA_READ;
	}
	else {
		$SMILIES_DATA_WRITE = array();

		if(file_exists('cache/cache_smilies_write.php') == TRUE)
			include('cache/cache_smilies_write.php');
		else {
			$db->query("SELECT smiley_gfx,smiley_synonym FROM ".TBLPFX."smilies WHERE smiley_type='0'");
			while($akt_smiley = $db->fetch_array())
				$SMILIES_DATA_WRITE[$akt_smiley['smiley_synonym']] = '<img src="'.$akt_smiley['smiley_gfx'].'" border="0" alt="'.$akt_smiley['smiley_synonym'].'" />';
		}

		return $SMILIES_DATA_WRITE;
	}
}

function cache_set_ppics_data() {
	global $db;

	$towrite = array();
	$db->query("SELECT smiley_id,smiley_gfx FROM ".TBLPFX."smilies WHERE smiley_type='1'");
	while($akt_smiley = $db->fetch_array())
		$towrite[] = 'array(\'smiley_id\'=>\''.$akt_smiley['smiley_id'].'\',\'smiley_gfx\'=>\''.$akt_smiley['smiley_gfx'].'\')';

	$towrite = '<?php $PPICS_DATA = array('.implode(',',$towrite).'); ?>';

	file_write('cache/cache_ppics.php',$towrite,'w');
}

function cache_get_ppics_data() {
	global $db;

	$PPICS_DATA = array();

	if(file_exists('cache/cache_ppics.php') == TRUE)
		include('cache/cache_ppics.php');
	else {
		$db->query("SELECT smiley_id,smiley_gfx FROM ".TBLPFX."smilies WHERE smiley_type='1'");
		$PPICS_DATA = $db->raw2array();
	}

	return $PPICS_DATA;
}

function cache_set_ranks_data() {
	global $db;

	$ranks_data_1 = $ranks_data_2 = array();

	$db->query("SELECT * FROM ".TBLPFX."ranks ORDER BY rank_posts");
	while($akt_rank = $db->fetch_array()) {
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
	global $db;

	$RANKS_DATA = array(array(),array());

	if(file_exists('cache/cache_ranks.php') == TRUE)
		include('cache/cache_ranks.php');
	else {
		$db->query("SELECT * FROM ".TBLPFX."ranks ORDER BY rank_posts");
		while($akt_rank = $db->fetch_array()) {
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
	global $db;

	$db->query("SELECT user_nick,user_id FROM ".TBLPFX."users ORDER BY user_regtime DESC LIMIT 1");
	$newest_user_data = $db->fetch_array();
	$db->query("UPDATE ".TBLPFX."config SET config_value='".$newest_user_data['user_id']."' WHERE config_name='newest_user_id'");
	$db->query("UPDATE ".TBLPFX."config SET config_value='".$newest_user_data['user_nick']."' WHERE config_name='newest_user_nick'");
}

function cache_set_all_data() {
	cache_set_smilies_data();
	cache_set_ppics_data();
	cache_set_ranks_data();
}

?>