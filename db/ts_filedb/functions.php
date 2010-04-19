<?php
/**
*
* Tritanium Bulletin Board 2 - db/ts_filedb/functions.php
* version #2003-09-17-17-03-24
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('functions.php');

function get_user_counter() {
	return filetostr('data/vars/user_counter.dat');
}

function get_posts_counter() {
	return filetostr('data/vars/posts_counter.dat');
}

function get_topics_counter() {
	return filetostr('data/vars/topics_counter.dat');
}

function add_user_data($new_user_data) {
	array_walk($new_user_data,'array_prepare_db');

	$new_user_data['user_regtime'] = time();
	$new_user_data['user_hash'] = md5(get_rand_string(32));
	$new_user_data['user_id'] = get_new_id('data/vars/last_user_id.dat');

	$def = get_def('users');
	$towrite = array();

	while(list(,$akt_def) = each($def)) {
		isset($new_user_data[$akt_def]) ? $towrite[$akt_def] = $new_user_data[$akt_def] : $towrite[$akt_def] = '';
	}
	$towrite = myimplode($towrite);

	myfwrite('data/users/'.$new_user_data['user_id'].'-profile.dat',$towrite,'w');
	myfwrite('data/users/'.$new_user_data['user_id'].'-pms.dat','','w');
	get_new_id('data/vars/user_counter.dat');

	$def = get_def('user_nick_id_index');
	$towrite = array();
	while(list(,$akt_def) = each($def)) {
		isset($new_user_data[$akt_def]) ? $towrite[$akt_def] = $new_user_data[$akt_def] : $towrite[$akt_def] = '';
	}
	$towrite['user_nick'] = strtolower($towrite['user_nick']);
	$towrite = myimplode($towrite)."\n";

	myfwrite('data/vars/user_nick_id_index.dat',$towrite,'a');

	return $new_user_data;
}

function update_user_data($user_id,$updated_data) {
	$user_data = get_user_data($user_id);

	while(list($akt_key,$akt_value) = each($updated_data)) {
		switch($akt_value[0]) {
			case 'STR':
				$user_data[$akt_key] = value_prepare_db($akt_value[1]);
			break;

			case 'INT':
				$user_data[$akt_key] += $akt_value[1];
			break;
		}
	}

	$user_data = myimplode($user_data);

	myfwrite('data/users/'.$user_id.'-profile.dat',$user_data,'w');
}

function unify_nick($nick) {
	$nick = strtolower($nick);
	$data = myfile('data/vars/user_nick_id_index.dat');
	assign_def_keys($data,'user_nick_id_index');
	while(list(,$akt_user) = each($data)) {
		if($akt_user['user_nick'] == $nick) return FALSE;
	}
	return TRUE;
}

function get_user_data($user_id) {
	if(!preg_match('/^[0-9]{1,}$/si',$user_id)) {
		if(!$user_id = get_user_id($user_id)) return FALSE;
	}
	if(!$data = myfile('data/users/'.$user_id.'-profile.dat')) return FALSE;
	assign_def_keys($data,'users');
	return $data[0];
}

function get_newest_user_data() {
	$last_user_id = filetostr('data/vars/last_user_id.dat');
	do {
		$user_data = get_user_data($last_user_id);
		$last_user_id--;
		if($last_user_id <= 0) break;
	} while($user_data == FALSE);

	return $user_data;
}

function get_user_id($user_nick) {
	if(preg_match('/^[0-9]{1,}$/si',$user_nick) == TRUE) {
		if(file_exists('data/users/'.$user_nick.'-profile.dat') == TRUE)
			return $user_nick;
		return FALSE;
	}

	$user_nick = strtolower($user_nick);
	$data = myfile('data/vars/user_nick_id_index.dat');
	assign_def_keys($data,'user_nick_id_index');
	while(list(,$akt_user) = each($data)) {
		if($akt_user['user_nick'] == $user_nick) return $akt_user['user_id'];
	}
	return FALSE;
}

function add_forum_data($new_forum_data) {
	array_walk($new_forum_data,'array_prepare_db');

	$new_forum_id = get_new_id('data/vars/last_forum_id.dat');
	$new_forum_data['forum_id'] = $new_forum_data['order_id'] = $new_forum_id;

	$def = get_def('forums');
	$towrite = array();

	while(list(,$akt_def) = each($def)) {
		$towrite[$akt_def] = isset($new_forum_data[$akt_def]) ? $new_forum_data[$akt_def] : '';
	}
	$towrite = myimplode($towrite)."\n";

	myfwrite('data/vars/forums.dat',$towrite,'a');
	myfwrite('data/forums/'.$new_forum_id.'-topic_index.dat','','w');
	myfwrite('data/forums/'.$new_forum_id.'-topic_index-pinned.dat','','w');

	return $new_forum_data;
}

function get_forum_data($forum_id) {
	$data = get_forums_data();
	while(list($akt_key,$akt_forum) = each($data)) {
		if($akt_forum['forum_id'] == $forum_id) return $data[$akt_key];
	}
	return FALSE;
}

function update_forum_data($forum_id,$updated_data) {
	$data = myfile('data/vars/forums.dat');
	assign_def_keys($data,'forums');

	while(list($akt_forum_key,$akt_forum) = each($data)) {
		if($akt_forum['forum_id'] == $forum_id) {
			while(list($akt_update_key,$akt_value) = each($updated_data)) {
				switch($akt_value[0]) {
					case 'STR':
						$data[$akt_forum_key][$akt_update_key] = value_prepare_db($akt_value[1]);
					break;

					case 'INT':
						$data[$akt_forum_key][$akt_update_key] += $akt_value[1];
					break;
				}
			}
		}
		$data[$akt_forum_key] = myimplode($data[$akt_forum_key]);
	}

	myfwrite('data/vars/forums.dat',$data,'w');
}

function cmpordid($a,$b) {
    if($a['order_id']  == $b['order_id']) return 0;
    return ($a['order_id'] > $b['order_id']) ? 1 : -1;
}

function get_forums_data() {
	$data = myfile('data/vars/forums.dat');
	assign_def_keys($data,'forums');

	while(list($akt_key,$akt_forum) = each($data)) {
		if($akt_forum['forum_last_post_post_id'] != 0 && ($akt_last_post = get_post_data($akt_forum['forum_last_post_topic_id'],$akt_forum['forum_last_post_post_id']))) {
			$data[$akt_key]['forum_last_post_title'] = $akt_last_post['post_title'];
			$data[$akt_key]['forum_last_post_time'] = $akt_last_post['post_time'];
			$data[$akt_key]['forum_last_post_poster_nick'] = $akt_last_post['poster_nick'];
		}
	}

	usort($data,'cmpordid');

	reset($data);

	return $data;
}


function get_forum_topics_counter($forum_id) {
	$topics_counter = sizeof(get_forum_topic_index($forum_id));
	return $topics_counter;
}

function get_topics_data($forum_id,$start = 0,$go = 15) {
	global $lng;

	$topic_index = get_forum_topic_index($forum_id);

	$topics_counter = sizeof($topic_index);

	$topics_data = array();

	if($start+$go > $topics_counter) $go = $topics_counter-$start;

	assign_def_keys($topic_index,'topic_index');

	$topic_index = array_reverse($topic_index);

	for($i = $start; $i < $start+$go; $i++) {
		$akt_topic_data = get_topic_data($topic_index[$i]['topic_id']);
		if($akt_topic_data['topic_poster_id'] != 0) {
			if($akt_author = get_user_data($akt_topic_data['topic_poster_id']))
				$akt_topic_data['topic_poster_nick'] = $akt_author['user_nick'];
			else {
				$akt_topic_data['topic_poster_id'] = 0;
				$akt_topic_data['topic_poster_nick'] = $lng['Deleted_user'];
			}
		}
		else {
			$akt_topic_data['topic_poster_nick'] = ($akt_topic_data['topic_guest_nick'] == '') ? $lng['Guest'] : $akt_topic_data['topic_guest_nick'];
		}

		if($akt_last_post = get_post_data($akt_topic_data['topic_id'],$akt_topic_data['topic_last_post_id'])) {
			$akt_topic_data['topic_last_post_title'] = $akt_last_post['post_title'];
			$akt_topic_data['topic_last_post_time'] = $akt_last_post['post_time'];
			$akt_topic_data['topic_last_post_poster_nick'] = $akt_last_post['poster_nick'];
			$akt_topic_data['topic_last_post_poster_id'] = $akt_last_post['poster_id'];
		}
		$topics_data[] = $akt_topic_data;
	}

	return $topics_data;
}

function assign_def_keys(&$array,$def) {
	$def = get_def($def);
	while(list($akt_key) = each($array)) {
		$array[$akt_key] = myexplode($array[$akt_key]);
		$a3 = array();
		for($i = 0; $i < sizeof($def); $i++) {
			$a3[$def[$i]] = isset($array[$akt_key][$i]) ? $array[$akt_key][$i] : '';
		}
		$array[$akt_key] = $a3;
	}
	reset($array);
}

function filetostr($file,$reload = FALSE) {
	global $STATS, $CACHE;

	if(isset($CACHE[$file]) == FALSE || $reload == TRUE) {

		if(isset($CACHE[$file])) clearstatcache();

		@$fp = fopen($file,'rb'); @flock($fp,LOCK_SH);
		$CACHE[$file] = @fread($fp,filesize($file));
		@flock($fp,LOCK_UN); @fclose($fp);
		$STATS['query_counter']++;
	}

	return $CACHE[$file];
}


function get_def($type) {
	global $DEFS;
	return $DEFS[$type];
}

function myfile($file,$reload = FALSE) {
	$data = filetostr($file,$reload);
	$data = explode("\n",$data);
	if(sizeof($data) > 1) {
		end($data);
		unset($data[key($data)]);
	}
	elseif($data[0] == '') return array();
	reset($data);
	return $data;
}

function myexplode($data) {
	return explode("\t",$data);
}

function myimplode($data) {
	return implode("\t",$data);
}

function get_new_id($file, $x = 1) {
	$fp = fopen($file,'rb+'); flock($fp,LOCK_EX); // Datei öffnen

	$new_id = fread($fp,filesize($file))+$x; // Dateinhalt einlesen und um eins erhöhren
	ftruncate($fp,0); // Datei löschen
	fseek($fp,0); // Den Filepointer an den Anfang der Datei setzen
	fwrite($fp,$new_id); // Neue ID reinschreiben

	flock($fp,LOCK_UN); fclose($fp); // Datei schließen

	return $new_id;
}

function value_prepare_db($item) {
	$item = mysslashes($item);
	$item = kill_r($item);
	$item = nl2r($item);
	$item = kill_t($item);
	return $item;
}

function array_prepare_db(&$item,$key) {
	$item = mysslashes($item);
	$item = kill_r($item);
	$item = nl2r($item);
	$item = kill_t($item);
}

function myfwrite($file,$towrite,$mode) {
	global $CACHE,$STATS;
	$set_chmod = 0;
	if(!file_exists($file)) $set_chmod = 1;
	$fp = fopen($file,$mode.'b'); flock($fp,LOCK_EX);
	if(is_array($towrite)) {
		$towrite = (sizeof($towrite) == 0) ? '' :  implode("\n",$towrite)."\n";
	}
	fwrite($fp,$towrite);
	flock($fp,LOCK_UN); fclose($fp);
	if($set_chmod == 1) {
		@chmod($file,0777);
	}

	if($mode == 'w') $CACHE[$file] = $towrite;
	else myfile($file,TRUE);

	$STATS['query_counter']++;
}

function add_topic_data($new_topic_data) {
	array_walk($new_topic_data,'array_prepare_db');

	$new_topic_id = get_new_id('data/vars/last_topic_id.dat');
	$new_topic_data['topic_id'] = $new_topic_id;
	$new_topic_data['topic_post_time'] = time();

	$def = get_def('topics');
	$towrite = array();

	while(list(,$akt_def) = each($def)) {
		isset($new_topic_data[$akt_def]) ? $towrite[$akt_def] = $new_topic_data[$akt_def] : $towrite[$akt_def] = '';
	}
	$towrite = myimplode($towrite);

	myfwrite('data/topics/'.$new_topic_id.'-info.dat',$towrite,'w');
	myfwrite('data/topics/'.$new_topic_id.'-posts.dat','','w');

	$def = get_def('topic_index');
	$towrite = array();
	while(list(,$akt_def) = each($def)) {
		isset($new_topic_data[$akt_def]) ? $towrite[$akt_def] = $new_topic_data[$akt_def] : $towrite[$akt_def] = '';
	}
	$towrite = myimplode($towrite)."\n";

	myfwrite('data/forums/'.$new_topic_data['forum_id'].'-topic_index.dat',$towrite,'a');
	get_new_id('data/vars/topics_counter.dat');

	return $new_topic_data;
}

function get_post_data($topic_id,$post_id) {
	global $lng;

	if(!$posts_file = myfile('data/topics/'.$topic_id.'-posts.dat')) return FALSE;
	assign_def_keys($posts_file,'posts');
	while(list(,$akt_post) = each($posts_file)) {
		if($akt_post['post_id'] == $post_id) {
			$post_data = $akt_post;
			$post_data['post_text'] = r2nl($post_data['post_text']);
			if($akt_post['poster_id'] != 0) {
				if($poster_data = get_user_data($akt_post['poster_id'])) {
					$post_data['poster_nick'] = $poster_data['user_nick'];
				}
				else {
					$akt_post['poster_id'] = 0;
					$post_data['poster_nick'] = $lng['Deleted_user'];
				}
			}
			else {
				$post_data['poster_nick'] = $lng['Guest'];
			}
			return $post_data;
		}
	}

	return FALSE;
}

function update_posts_data($where,$what) {
	if(!$data = myfile('data/topics/'.$where['topic_id'].'-posts.dat')) return FALSE;
	assign_def_keys($data,'posts');

	while(list($akt_key1) = each($data)) {
		$x = TRUE;
		while(list($akt_key2) = each($where)) {
			if($data[$akt_key1][$akt_key2] != $where[$akt_key2]) {
				$x = FALSE;
				break;
			}
		}

		if($x == TRUE) {
			while(list($akt_what_key,$akt_what) = each($what)) {
				switch($akt_what[0]) {
					case 'STR':
						$data[$akt_key1][$akt_what_key] = value_prepare_db($akt_what[1]);
					break;

					case 'INT':
						$data[$akt_key1][$akt_what_key] += $akt_what[1];
					break;
				}
			}
		}

		$data[$akt_key1] = myimplode($data[$akt_key1]);

		reset($where);
	}
	myfwrite('data/topics/'.$where['topic_id'].'-posts.dat',$data,'w');
}

function delete_posts_data($where) {
	if(!$data = myfile('data/topics/'.$where['topic_id'].'-posts.dat')) return FALSE;
	assign_def_keys($data,'posts');

	$deleted_counter = 0;

	while(list($akt_key1) = each($data)) {
		$x = TRUE;
		while(list($akt_key2) = each($where)) {
			if($data[$akt_key1][$akt_key2] != $where[$akt_key2]) {
				$x = FALSE;
				break;
			}
		}

		if($x == TRUE) {
			unset($data[$akt_key1]);
			$deleted_counter--;
		}
		else $data[$akt_key1] = myimplode($data[$akt_key1]);

		reset($where);
	}
	myfwrite('data/topics/'.$where['topic_id'].'-posts.dat',$data,'w');

	get_new_id('data/vars/posts_counter.dat',$deleted_counter);
}

function get_topic_posts_counter($topic_id) {
	return sizeof(myfile('data/topics/'.$topic_id.'-posts.dat'));
}

function get_posts_data($topic_id,$start = 0, $go = 10) {
	global $lng;
	$posts_file = myfile('data/topics/'.$topic_id.'-posts.dat');
	$posts_counter = sizeof($posts_file);

	assign_def_keys($posts_file,'posts');

	$posts_data = array();

	if($start+$go > $posts_counter) $go = $posts_counter-$start;

	for($i = $start; $i < $start+$go; $i++) {
		if($posts_file[$i]['poster_id'] == 0) {
			$posts_file[$i]['poster_nick'] = isset($posts_file[$i]['post_guest_nick']) ? $posts_file[$i]['post_guest_nick'] : $lng['Guest'];
			$posts_file[$i]['poster_posts'] = 0;
			$posts_file[$i]['poster_hp'] = '';
			$posts_file[$i]['poster_email'] = '';
		}
		else {
			if($akt_poster_data = get_user_data($posts_file[$i]['poster_id'])) {
				$posts_file[$i]['poster_nick'] = $akt_poster_data['user_nick'];
				$posts_file[$i]['poster_posts'] = $akt_poster_data['user_posts'];
				$posts_file[$i]['poster_hp'] = $akt_poster_data['user_hp'];
				$posts_file[$i]['poster_email'] = $akt_poster_data['user_email'];
			}
			else {
				$posts_file[$i]['poster_nick'] = $lng['Deleted_user'];
				$posts_file[$i]['poster_posts'] = 0;
				$posts_file[$i]['poster_hp'] = '';
				$posts_file[$i]['poster_email'] = '';
			}
		}
		$posts_file[$i]['post_text'] = r2nl($posts_file[$i]['post_text']);

		$posts_data[] = $posts_file[$i];

	}

	reset($posts_data);

	return $posts_data;
}

function get_topic_data($topic_id) {
	if(!$data = myfile('data/topics/'.$topic_id.'-info.dat')) return FALSE;
	assign_def_keys($data,'topics');
	return $data[0];
}

function update_topic_data($topic_id,$updated_data)  {
	$topic_data = get_topic_data($topic_id);

	while(list($akt_key,$akt_value) = each($updated_data)) {
		switch($akt_value[0]) {
			case 'STR':
				$topic_data[$akt_key] = value_prepare_db($akt_value[1]);
			break;

			case 'INT':
				$topic_data[$akt_key] += $akt_value[1];
			break;
		}
	}

	myfwrite('data/topics/'.$topic_id.'-info.dat',myimplode($topic_data),'w');

	if(isset($updated_data['topic_is_pinned'])) {
		if($updated_data['topic_is_pinned'][1] == 1) {
			$index_1 = 'data/forums/'.$topic_data['forum_id'].'-topic_index-pinned.dat';
			$index_2 = 'data/forums/'.$topic_data['forum_id'].'-topic_index.dat';
		}
		else {
			$index_1 = 'data/forums/'.$topic_data['forum_id'].'-topic_index.dat';
			$index_2 = 'data/forums/'.$topic_data['forum_id'].'-topic_index-pinned.dat';
		}

		$index_2_f = myfile($index_2);
		while(list($akt_key,$akt_value) = each($index_2_f)) {
			if($akt_value == $topic_id) {

				unset($index_2_f[$akt_key]);
				reset($index_2_f);
				myfwrite($index_2,$index_2_f,'w');

				$index_1_f = myfile($index_1);
				$index_1_f[] = $topic_id;
				myfwrite($index_1,$index_1_f,'w');

				break;
			}
		}
	}
}

function add_post_data($new_post_data) {
	array_walk($new_post_data,'array_prepare_db');

	$new_post_id = get_new_id('data/vars/last_post_id.dat');

	$new_post_data['post_id'] = $new_post_id;
	$new_post_data['post_time'] = time();

	$def = get_def('posts');
	$towrite = array();

	while(list(,$akt_def) = each($def)) {
		isset($new_post_data[$akt_def]) ? $towrite[$akt_def] = $new_post_data[$akt_def] : $towrite[$akt_def] = '';
	}
	$towrite = myimplode($towrite)."\n";

	myfwrite('data/topics/'.$new_post_data['topic_id'].'-posts.dat',$towrite,'a');
	get_new_id('data/vars/posts_counter.dat');

	return $new_post_data;
}

function get_cats_data() {
	$cats_data = myfile('data/vars/cats.dat');
	assign_def_keys($cats_data,'cats');
	usort($cats_data,'cmpordid');
	return $cats_data;
}

function get_cat_data($cat_id) {
	$data = get_cats_data();
	while(list($akt_key,$akt_cat) = each($data)) {
		if($akt_cat['cat_id'] == $cat_id) return $data[$akt_key];
	}
	return FALSE;
}

function update_cat_data($cat_id,$updated_data) {
	$data = myfile('data/vars/cats.dat');
	assign_def_keys($data,'cats');

	while(list($akt_cat_key,$akt_cat) = each($data)) {
		if($akt_cat['cat_id'] == $cat_id) {
			while(list($akt_update_key,$akt_value) = each($updated_data)) {
				switch($akt_value[0]) {
					case 'STR':
						$data[$akt_cat_key][$akt_update_key] = value_prepare_db($akt_value[1]);
					break;

					case 'INT':
						$data[$akt_cat_key][$akt_update_key] += $akt_value[1];
					break;
				}
			}
		}
		$data[$akt_cat_key] = myimplode($data[$akt_cat_key]);
	}

	myfwrite('data/vars/cats.dat',$data,'w');
}

function add_cat_data($new_cat_data) {
	array_walk($new_cat_data,'array_prepare_db');

	$new_cat_id = get_new_id('data/vars/last_cat_id.dat');
	$new_cat_data['cat_id'] = $new_cat_data['order_id'] = $new_cat_id;

	$def = get_def('cats');
	$towrite = array();

	while(list(,$akt_def) = each($def)) {
		$towrite[$akt_def] = isset($new_cat_data[$akt_def]) ? $new_cat_data[$akt_def] : '';
	}
	$towrite = myimplode($towrite)."\n";

	myfwrite('data/vars/cats.dat',$towrite,'a');

	return $new_cat_data;
}

function add_wio_data($new_wio_data) {
	array_walk($new_wio_data,'array_prepare_db');

	$def = get_def('wio');
	$towrite = array();

	while(list(,$akt_def) = each($def)) {
		$towrite[$akt_def] = isset($new_wio_data[$akt_def]) ? $new_wio_data[$akt_def] : '';
	}
	$towrite = myimplode($towrite)."\n";

	myfwrite('data/vars/wio.dat',$towrite,'a');

	return $new_wio_data;
}

function get_wio_data($where = array()) {
	global $lng;

	$data = myfile('data/vars/wio.dat');
	assign_def_keys($data,'wio');

	$return_data = array();

	while(list($akt_key1) = each($data)) {
		$x = TRUE;
		while(list($akt_key2) = each($where)) {
			if($data[$akt_key1][$akt_key2] != $where[$akt_key2]) {
				$x = FALSE;
				break;
			}
		}

		if($x == TRUE) {
			if($data[$akt_key1]['wio_user_id'] == 0) $data[$akt_key1]['wio_user_nick'] = $lng['Guest'];
			elseif($akt_wio_user = get_user_data($data[$akt_key1]['wio_user_id'])) $data[$akt_key1]['wio_user_nick'] = $akt_wio_user['user_nick'];
			else $data[$akt_key1]['wio_user_nick'] = $lng['Deleted_user'];
			$return_data[] = $data[$akt_key1];
		}
		reset($where);
	}

	return $return_data;
}

function update_wio_data($wio_session_id,$updated_data) {
	$wio_data = myfile('data/vars/wio.dat');
	assign_def_keys($wio_data,'wio');

	$updated = FALSE;

	$updated_data['wio_last_action'] = array('STR',time());

	while(list($akt_wio_key,$akt_wio) = each($wio_data)) {
		if($akt_wio['wio_session_id'] == $wio_session_id) {
			$updated = TRUE;
			while(list($akt_update_key,$akt_value) = each($updated_data)) {
				switch($akt_value[0]) {
					case 'STR':
						$wio_data[$akt_wio_key][$akt_update_key] = value_prepare_db($akt_value[1]);
					break;

					case 'INT':
						$wio_data[$akt_wio_key][$akt_update_key] += $akt_value[1];
					break;
				}
			}
		}
		$wio_data[$akt_wio_key] = myimplode($wio_data[$akt_wio_key]);
	}

	if($updated == TRUE) myfwrite('data/vars/wio.dat',$wio_data,'w');
	else {
		add_wio_data(array(
			'wio_session_id'=>$wio_session_id,
			'wio_user_id'=>$updated_data['wio_user_id'][1],
			'wio_last_action'=>$updated_data['wio_last_action'][1],
			'wio_last_location'=>$updated_data['wio_last_location'][1]
		));
	}
}

function delete_wio_data($where) {
	$data = myfile('data/vars/wio.dat');
	assign_def_keys($data,'wio');

	while(list($akt_key1) = each($data)) {
		$x = TRUE;
		while(list($akt_key2) = each($where)) {
			if(is_array($where[$akt_key2]) == TRUE && in_array($data[$akt_key1][$akt_key2],$where[$akt_key2]) == FALSE || $data[$akt_key1][$akt_key2] != $where[$akt_key2]) {
				$x = FALSE;
				break;
			}
		}

		if($x == TRUE) unset($data[$akt_key1]);
		else $data[$akt_key1] = myimplode($data[$akt_key1]);

		reset($where);
	}

	myfwrite('data/vars/wio.dat',$data,'w');
}

function delete_old_wio_data() {
	global $CONFIG;

	$timeout = $CONFIG['wio_timeout']*60;
	$time = time();

	$wio_data = myfile('data/vars/wio.dat');
	assign_def_keys($wio_data,'wio');
	while(list($akt_key) = each($wio_data)) {
		if($wio_data[$akt_key]['wio_last_action']+$timeout < $time)
			unset($wio_data[$akt_key]);
	}
	reset($wio_data);

	while(list($akt_key) = each($wio_data)) {
		$wio_data[$akt_key] = myimplode($wio_data[$akt_key]);;
	}

	myfwrite('data/vars/wio.dat',$wio_data,'w');
}

function update_forum_last_post($forum_id) {
	$topic_index = myfile('data/forums/'.$forum_id.'-topic_index.dat');
	if(sizeof($topic_index) == 0) {
		update_forum_data($forum_id,array(
			'forum_last_post_topic_id'=>array('STR',0),
			'forum_last_post_post_id'=>array('STR',0)
		));
	}
	else {
		$last_topic_data = get_topic_data($topic_index[sizeof($topic_index)-1]);
		update_forum_data($forum_id,array(
			'forum_last_post_topic_id'=>array('STR',$last_topic_data['topic_id']),
			'forum_last_post_post_id'=>array('STR',$last_topic_data['topic_last_post_id'])
		));
	}
}

function update_topic_last_post($topic_id) {
	$data = myfile('data/topics/'.$topic_id.'-posts.dat');
	assign_def_keys($data,'posts');
	$last_post_id = $data[sizeof($data)-1]['post_id'];
	update_topic_data($topic_id,array(
		'topic_last_post_id'=>array('STR',$last_post_id)
	));
}

function get_forum_topic_index($forum_id) {
	$topic_indexx = myfile('data/forums/'.$forum_id.'-topic_index-pinned.dat');
	$topic_index = myfile('data/forums/'.$forum_id.'-topic_index.dat');

	while(list(,$akt_value) = each($topic_indexx)) {
		$topic_index[] = $akt_value;
	}

	return $topic_index;
}

function delete_topic_data($topic_id) {
	$topic_data = get_topic_data($topic_id);
	$posts_data = myfile('data/topics/'.$topic_id.'-posts.dat');
	assign_def_keys($posts_data,'posts');
	$posts_counter = sizeof($posts_data);

	$index_file = ($topic_data['topic_is_pinned'] == 1) ? 'data/forums/'.$topic_data['forum_id'].'-topic_index-pinned.dat' : 'data/forums/'.$topic_data['forum_id'].'-topic_index.dat';
	$topic_index = myfile($index_file);

	while(list($akt_key) = each($topic_index)) {
		if($topic_index[$akt_key] == $topic_id) {
			unset($topic_index[$akt_key]);
			myfwrite($index_file,$topic_index,'w');
			break;
		}
	}

	$user_posts = array();

	while(list(,$akt_post) = each($posts_data)) {
		isset($user_posts[$akt_post['poster_id']]) ? $user_posts[$akt_post['poster_id']]++ : $user_posts[$akt_post['poster_id']] = 1;
	}
	reset($user_posts);

	while(list($akt_user_id,$akt_user_posts) = each($user_posts)) {
		update_user_data($akt_user_id,array(
			'user_posts'=>array('INT',-$akt_user_posts)
		));
	}

	update_forum_data($topic_data['forum_id'],array(
		'forum_topics_counter'=>array('INT',-1),
		'forum_posts_counter'=>array('INT',-$posts_counter)
	));

	@unlink('data/topics/'.$topic_id.'-info.dat');
	@unlink('data/topics/'.$topic_id.'-posts.dat');

	get_new_id('data/vars/posts_counter.dat',0-$posts_counter);
	get_new_id('data/vars/topics_counter.dat',-1);
}

function delete_abos_data($where) {
	$data = myfile('data/vars/abos.dat');
	assign_def_keys($data,'abos');

	while(list($akt_key1) = each($data)) {
		$x = TRUE;
		while(list($akt_key2) = each($where)) {
			if(is_array($where[$akt_key2]) == TRUE && in_array($data[$akt_key1][$akt_key2],$where[$akt_key2]) == FALSE || $data[$akt_key1][$akt_key2] != $where[$akt_key2]) {
				$x = FALSE;
				break;
			}
		}

		if($x == TRUE) unset($data[$akt_key1]);
		else $data[$akt_key1] = myimplode($data[$akt_key1]);

		reset($where);
	}
	myfwrite('data/vars/abos.dat',$data,'w');
}

function add_abo_data($what) {
	array_walk($what,'array_prepare_db');

	$def = get_def('abos');
	$towrite = array();

	while(list(,$akt_def) = each($def)) {
		isset($what[$akt_def]) ? $towrite[$akt_def] = $what[$akt_def] : $towrite[$akt_def] = '';
	}
	$towrite = myimplode($towrite)."\n";

	myfwrite('data/vars/abos.dat',$towrite,'a');
}

function get_config_data() {
	global $CONFIG;
	$config_data = myfile('data/vars/config.dat');
	assign_def_keys($config_data,'config');

	while(list(,$akt_config) = each($config_data)) {
		$CONFIG[$akt_config['config_name']] = $akt_config['config_value'];
	}
}

function add_config_data($new_config_data) {
	array_walk($new_config_data,'array_prepare_db');

	$def = get_def('config');
	$towrite = array();

	while(list(,$akt_def) = each($def)) {
		$towrite[$akt_def] = isset($new_config_data[$akt_def]) ? $new_config_data[$akt_def] : '';
	}
	$towrite = myimplode($towrite)."\n";

	myfwrite('data/vars/config.dat',$towrite,'a');
}

function update_config_data($config_name,$updated_data) {
	$data = myfile('data/vars/config.dat');
	assign_def_keys($data,'config');

	while(list($akt_config_key,$akt_config) = each($data)) {
		if($akt_config['config_name'] == $config_name) {
			while(list($akt_update_key,$akt_value) = each($updated_data)) {
				switch($akt_value[0]) {
					case 'STR':
						$data[$akt_config_key][$akt_update_key] = value_prepare_db($akt_value[1]);
					break;

					case 'INT':
						$data[$akt_config_key][$akt_update_key] += $akt_value[1];
					break;
				}
			}
		}
		$data[$akt_config_key] = myimplode($data[$akt_config_key]);
	}

	myfwrite('data/vars/config.dat',$data,'w');
}

function rank_topic($forum_id,$topic_id) {
	$topic_index = myfile('data/forums/'.$forum_id.'-topic_index.dat');

	while(list($akt_key,$akt_topic_id) = each($topic_index)) {
		if($akt_topic_id == $topic_id) {
			unset($topic_index[$akt_key]);
			$topic_index[] = $topic_id;
			break;
		}
	}
	reset($topic_index);
	myfwrite('data/forums/'.$forum_id.'-topic_index.dat',$topic_index,'w');
}

function update_auth_data($where,$what) {
	$data = myfile('data/vars/forums_auth.dat');
	assign_def_keys($data,'forums_auth');

	while(list($akt_key) = each($data)) {
		$y = FALSE;
		while(list($akt_key2) = each($where)) {
			if($where[$akt_key2] != $data[$akt_key][$akt_key2]) {
				$y = TRUE;
				break;
			}
		}

		if($y == FALSE) {
			while(list($akt_what_key,$akt_what) = each($what)) {
				switch($akt_what[0]) {
					case 'STR':
						$data[$akt_key][$akt_what_key] = value_prepare_db($akt_what[1]);
					break;

					case 'INT':
						$data[$akt_key][$akt_what_key] += $akt_what[1];
					break;
				}
			}
		}

		reset($where);

		$data[$akt_key] = myimplode($data[$akt_key]);
	}

	myfwrite('data/vars/forums_auth.dat',$data,'w');
}

function get_auth_data($where) {
	$data = myfile('data/vars/forums_auth.dat');
	assign_def_keys($data,'forums_auth');

	while(list($akt_key1) = each($data)) {
		$x = TRUE;
		while(list($akt_key2) = each($where)) {
			if($data[$akt_key1][$akt_key2] != $where[$akt_key2]) {
				$x = FALSE;
				break;
			}
		}

		if($x == TRUE)
			return $data[$akt_key1];

		reset($where);
	}

	return FALSE;
}

function add_auth_data($data) {
	array_walk($data,'array_prepare_db');

	$def = get_def('forums_auth');
	$towrite = array();

	while(list(,$akt_def) = each($def)) {
		$towrite[] = isset($data[$akt_def]) ? $data[$akt_def] : '';
	}
	$towrite = myimplode($towrite)."\n";

	myfwrite('data/vars/forums_auth.dat',$towrite,'a');
}

function get_auth_user_data($where) {
	$data = myfile('data/vars/forums_auth.dat');
	assign_def_keys($data,'forums_auth');

	$auth_data = array();

	while(list($akt_key1) = each($data)) {
		$x = TRUE;
		while(list($akt_key2) = each($where)) {
			if($data[$akt_key1][$akt_key2] != $where[$akt_key2]) {
				$x = FALSE;
				break;
			}
		}

		if($x == TRUE) {
			$akt_user_data = get_user_data($data[$akt_key1]['auth_id']);
			$data[$akt_key1]['auth_user_nick'] = $akt_user_data['user_nick'];
			$auth_data[] = &$data[$akt_key1];
		}

		reset($where);
	}

	return $auth_data;
}

function add_smiley_data($data) {
	array_walk($data,'array_prepare_db');

	$data['smiley_id'] = get_new_id('data/vars/last_smiley_id.dat');

	$def = get_def('smilies');
	$towrite = array();

	while(list(,$akt_def) = each($def)) {
		$towrite[] = isset($data[$akt_def]) ? $data[$akt_def] : '';
	}
	$towrite = myimplode($towrite)."\n";

	myfwrite('data/vars/smilies.dat',$towrite,'a');
}

function get_smilies_data($where,$max = -1) {
	$data = myfile('data/vars/smilies.dat');
	assign_def_keys($data,'smilies');

	$return_data = array();
	$counter = 0;

	while(list($akt_key1) = each($data)) {
		$x = TRUE;
		while(list($akt_key2) = each($where)) {
			if($data[$akt_key1][$akt_key2] != $where[$akt_key2]) {
				$x = FALSE;
				break;
			}
		}

		if($x == TRUE) {
			$return_data[] = &$data[$akt_key1];
			if($max != -1 && $counter >= $max) break;
		}

		reset($where);
	}

	return $return_data;
}

function get_smiley_data($where) {
	$data = myfile('data/vars/smilies.dat');
	assign_def_keys($data,'smilies');

	while(list($akt_key1) = each($data)) {
		$x = TRUE;
		while(list($akt_key2) = each($where)) {
			if($data[$akt_key1][$akt_key2] != $where[$akt_key2]) {
				$x = FALSE;
				break;
			}
		}

		if($x == TRUE) return $data[$akt_key1];

		reset($where);
	}

	return FALSE;
}

function delete_smilies_data($where) {
	$data = myfile('data/vars/smilies.dat');
	assign_def_keys($data,'smilies');

	while(list($akt_key1) = each($data)) {
		$x = TRUE;
		while(list($akt_key2) = each($where)) {
			if(in_array($data[$akt_key1][$akt_key2],$where[$akt_key2]) == FALSE) {
				$x = FALSE;
				break;
			}
		}

		if($x == TRUE) unset($data[$akt_key1]);
		else $data[$akt_key1] = myimplode($data[$akt_key1]);

		reset($where);
	}

	myfwrite('data/vars/smilies.dat',$data,'w');
}

function update_smilies_data($where,$what) {
	$data = myfile('data/vars/smilies.dat');
	assign_def_keys($data,'smilies');

	while(list($akt_key1) = each($data)) {
		$x = TRUE;
		while(list($akt_key2) = each($where)) {
			if($data[$akt_key1][$akt_key2] != $where[$akt_key2]) {
				$x = FALSE;
				break;
			}
		}

		if($x == TRUE) {
			while(list($akt_what_key,$akt_what) = each($what)) {
				switch($akt_what[0]) {
					case 'STR':
						$data[$akt_key1][$akt_what_key] = value_prepare_db($akt_what[1]);
					break;

					case 'INT':
						$data[$akt_key1][$akt_what_key] += $akt_what[1];
					break;
				}
			}
		}

		$data[$akt_key1] = myimplode($data[$akt_key1]);

		reset($where);
	}

	myfwrite('data/vars/smilies.dat',$data,'w');
}

$DEFS = array(
	'forums'=>array(
		'forum_id',
		'cat_id',
		'order_id',
		'forum_name',
		'forum_description',
		'forum_topics_counter',
		'forum_posts_counter',
		'forum_last_post_topic_id',
		'forum_last_post_post_id',
		'forum_enable_bbcode',
		'forum_enable_htmlcode',
		'forum_enable_smilies',
		'forum_is_moderated',
		'forum_add_last_posts',
		'auth_members_view_forum',
		'auth_members_post_topic',
		'auth_members_post_reply',
		'auth_members_post_poll',
		'auth_members_edit_posts',
		'auth_guests_view_forum',
		'auth_guests_post_topic',
		'auth_guests_post_reply',
		'auth_guests_post_poll'
	),
	'cats'=>array(
		'cat_id',
		'parent_id',
		'order_id',
		'cat_name',
		'cat_description'
	),
	'posts'=>array(
		'post_id',
		'topic_id',
		'forum_id',
		'poster_id',
		'post_time',
		'post_ip',
		'post_pic',
		'post_enable_bbcode',
		'post_enable_smilies',
		'post_enable_html',
		'post_show_sig',
		'post_title',
		'post_text',
		'post_guest_nick'
	),
	'topic_index'=>array(
		'topic_id'
	),
	'topics'=>array(
		'topic_id',
		'forum_id',
		'topic_status',
		'topic_is_pinned',
		'topic_poster_id',
		'topic_pic',
		'topic_replies',
		'topic_views',
		'topic_poll_id',
		'topic_first_post_id',
		'topic_last_post_id',
		'topic_is_moved',
		'topic_post_time',
		'topic_title',
		'topic_guest_nick'
	),
	'user_nick_id_index'=>array(
		'user_nick',
		'user_id'
	),
	'users'=>array(
		'user_id',
		'user_status',
		'user_is_admin',
		'user_hash',
		'user_nick',
		'user_email',
		'user_pw',
		'user_posts',
		'user_regtime',
		'user_hp',
		'user_icq',
		'user_aim',
		'user_yahoo',
		'user_msn',
		'user_signature',
		'user_group_id',
		'user_special_status',
		'user_interests',
		'user_realname',
		'user_location'
	),
	'wio'=>array(
		'wio_session_id',
		'wio_user_id',
		'wio_last_action',
		'wio_last_location',
		'wio_is_ghost'
	),
	'config'=>array(
		'config_name',
		'config_value'
	),
	'forums_auth'=>array(
		'auth_forum_id',
		'auth_type',
		'auth_id',
		'auth_view_forum',
		'auth_post_topic',
		'auth_post_reply',
		'auth_post_poll',
		'auth_edit_posts',
		'auth_is_mod'
	),
	'smilies'=>array(
		'smiley_id',
		'smiley_type',
		'smiley_gfx',
		'smiley_synonym',
		'smiley_status'
	),
	'abos'=>array(
		'abo_topic_id',
		'abo_user_id'
	)
);

?>