<?php
/**
*
* Tritanium Bulletin Board 2 - functions.php
* Beinhaltet einige wichtige und weniger wichtige Funktionen
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.de
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
	$text = htmlspecialchars(mysslashes($text));
	return $text;
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
	global $CONFIG;
	return date($CONFIG['time_format'],$timestamp);
}

function format_date($timestamp) {
	global $CONFIG;
	return date($CONFIG['date_format'],$timestamp);
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

?>