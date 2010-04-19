<?php
/**
*
* Tritanium Bulletin Board 2 - functions.php
* version #2003-09-17-17-03-24
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

// Überprüfen, ob eine Name gültig ist
function verify_nick($nick) {
	if(strlen($nick) > 15) return FALSE;
	if(preg_match('/^[a-z_]{1}[a-z0-9_]{1,}$/si',$nick)) return TRUE;
	return FALSE;
}

// Überprüfen ob eine ICQ-UIN gültig ist
function verify_icq_uin($icq_uin) {
	if(preg_match('/^[0-9]{1,}$/si',$icq_uin)) return TRUE;
	return FALSE;
}

// Überprüfen ob eine Emailadresse gültig ist
function verify_email($email) {
	if(preg_match('/^[\.0-9a-z_-]{1,}@[\.0-9a-z-]{1,}\.[a-z]{1,}$/si',$email)) return TRUE;
	return FALSE;
}

function mutate($text) {
	$text = myhtmlentities(mysslashes($text),TRUE);
	return $text;
}

function multimutate() {
	$args = func_get_args();
	while(list(,$akt_arg) = each($args))
		$GLOBALS[$akt_arg] = mutate($GLOBALS[$akt_arg]);
}

function mycrypt($string) {
	return md5($string);
}

// Backslashes entfernen
function mysslashes($text) {
	$text = str_replace("\\\"","\"",$text);
	$text = str_replace("\\\\","\\",$text);
	$text = str_replace("\\'","'",$text);
	return $text;
}

function kill_r($data) {
	return str_replace("\r",'',$data);
}

function nl2r($data) {
	return str_replace("\n","\r",$data);
}

function r2nl($data) {
	return str_replace("\r","\n",$data);
}

function kill_t($data) {
	return str_replace("\t",'',$data);
}

function get_rand_string($length) {
	mt_srand((double)microtime()*1000000);
	return substr(md5(mt_rand()),0,$length);
}

function show_message($message_title,$message_text,$append = '') {
	global $lng,$template_path,$tpl_config;
	$message_tpl = new template;
	$message_tpl->load($template_path.'/'.$tpl_config['tpl_message']);

	$message_tpl->values = array(
		'MESSAGE_TITLE'=>$lng[$message_title],
		'MESSAGE_TEXT'=>$lng[$message_text].$append
	);

	$message_tpl->parse_code(TRUE);
}

function show_navbar($left = '', $center = '', $right = '') {
	global $template_path,$tpl_config;

	$left = str_replace("\r","&nbsp;&#187;&nbsp;",$left);

	$navbar_tpl = new template;
	$navbar_tpl->load($template_path.'/'.$tpl_config['tpl_navbar']);
	$navbar_tpl->values = array(
		'LEFT'=>$left,
		'CENTER'=>$center,
		'RIGHT'=>$right
	);
	$navbar_tpl->parse_code(TRUE);
}

function format_time($timestamp) {
	return date('H:i',$timestamp);
}

function format_date($timestamp) {
	global $lng;

	if(date('d.m.Y') == date("d.m.Y",$timestamp)) return $lng['Today'].', '.date('H:i',$timestamp);
	elseif(date('d.m.Y',time()-86400) == date("d.m.Y",$timestamp)) return $lng['Yesterday'].', '.date('H:i',$timestamp);
	return date('d.m.Y H:i',$timestamp);
}

function nlbr($text) {
	return str_replace("\n",'<br />',$text);
}

function myhtmlentities($string,$specialchr = TRUE) {
	global $html_trans_table;
	if($specialchr == TRUE) return htmlentities($string);
	return strtr($string,$html_trans_table);
}

function update_forum_cookie($forum_id) {
	$cookie_forums = isset($_COOKIE['c_forums']) ? explode('x',$_COOKIE['c_forums']) : array();
	$x = FALSE;
	while(list($akt_key,$akt_forum_cookie) = each($cookie_forums)) {
		$akt_forum_cookie = explode('_',$akt_forum_cookie);

		if($akt_forum_cookie[0] == $forum_id) {
			$x = TRUE;
			$akt_forum_cookie[1] = time();
			$cookie_forums[$akt_key] = $akt_forum_cookie[0].'_'.$akt_forum_cookie[1];
			break;
		}
	}
	if($x == FALSE)
		$cookie_forums[] = $forum_id.'_'.time();

	$cookie_forums = implode('x',$cookie_forums);

	setcookie('c_forums',$cookie_forums,time()+31536000,'/');
}

function update_topic_cookie($forum_id,$topic_id,$when) {
	$cookie_topics = isset($_COOKIE['c_topics']) ? explode('x',$_COOKIE['c_topics']) : array();
	$x = $y = FALSE;
	while(list($akt_forum_key,$akt_forum_value) = each($cookie_topics)) {
		$akt_forum_value = explode('y',$akt_forum_value);
		if($akt_forum_value[0] == $forum_id) {
			$x = TRUE;
			$akt_forum_topics = explode('z',$akt_forum_value[1]);
			while(list($akt_topic_key,$akt_topic_value) = each($akt_forum_topics)) {
				$akt_topic_value = explode('_',$akt_topic_value);
				if($akt_topic_value[0] == $topic_id) {
					$y = TRUE;
					$akt_topic_value[1] = $when;
					$akt_forum_topics[$akt_topic_key] = implode('_',$akt_topic_value);
					break;
				}
			}
			if($y == FALSE)
				$akt_forum_topics[] = $topic_id.'_'.$when;
			$akt_forum_value[1] = implode('z',$akt_forum_topics);
			$cookie_topics[$akt_forum_key] = implode('y',$akt_forum_value);
			break;
		}
	}
	if($x == FALSE)
		$cookie_topics[] = $forum_id.'y'.$topic_id.'_'.$when;

	$cookie_topics = implode('x',$cookie_topics);

	setcookie('c_topics',$cookie_topics,time()+31536000,'/');
	$_COOKIE['c_topics'] = $cookie_topics;
}

function get_smilies_box() {
	global $tpl_config,$template_path;

	$smilies_tpl = new template;
	$smilies_tpl->load($template_path.'/'.$tpl_config['tpl_smiliesbox']);

	$smilies_data = get_smilies_data(array('smiley_type'=>0,'smiley_status'=>1),$tpl_config['smiliesbox_maximum_smilies']);

	$smilies_counter = sizeof($smilies_data);

	for($i = 0; $i < $smilies_counter; $i++) {
		$smilies_tpl->blocks['smileyrow']->blocks['smileycol']->values = array(
			'akt_smiley'=>$smilies_data[$i]
		);
		$smilies_tpl->blocks['smileyrow']->blocks['smileycol']->parse_code(FALSE,TRUE);
		if(($i+1) % $tpl_config['smiliesbox_smilies_per_row'] == 0 && $i != $smilies_counter-1) {
			$smilies_tpl->blocks['smileyrow']->parse_code(FALSE,TRUE);
			$smilies_tpl->blocks['smileyrow']->blocks['smileycol']->reset_tpl();
		}
	}
	$smilies_tpl->blocks['smileyrow']->parse_code(FALSE,TRUE);

	return $smilies_tpl->parse_code();
}

function get_ppics_box($checked_id = 0) {
	global $tpl_config,$template_path;

	$ppics_tpl = new template;
	$ppics_tpl->load($template_path.'/'.$tpl_config['tpl_ppicsbox']);

	$ppics_data = get_smilies_data(array('smiley_type'=>1));

	$ppics_counter = sizeof($ppics_data);

	for($i = 0; $i < $ppics_counter; $i++) {
		$akt_ppic = &$ppics_data[$i];

		$akt_checked = ($akt_ppic['smiley_id'] == $checked_id) ? ' checked="checked"' : '';

		$ppics_tpl->blocks['ppicrow']->blocks['ppiccol']->values = array(
			'akt_ppic'=>$akt_ppic,
			'akt_checked'=>$akt_checked
		);
		$ppics_tpl->blocks['ppicrow']->blocks['ppiccol']->parse_code(FALSE,TRUE);
		if(($i+1) % 7 == 0 && $i != $ppics_counter-1) {
			$ppics_tpl->blocks['ppicrow']->parse_code(FALSE,TRUE);
			$ppics_tpl->blocks['ppicrow']->blocks['ppiccol']->reset_tpl();
		}
	}
	$ppics_tpl->blocks['ppicrow']->parse_code(FALSE,TRUE);
	return $ppics_tpl->parse_code();
}

function array_addslashes(&$array) {
	while(list($akt_key) = each($array)) {
		if(is_array($array[$akt_key]) == TRUE) array_addslashes($array[$akt_key]);
		else $array[$akt_key] = addslashes($array[$akt_key]);
	}
	reset($array);
}

function get_mtime_counter() {
	$mtime = explode(" ",microtime());
	return $mtime[1] + $mtime[0];
}

?>