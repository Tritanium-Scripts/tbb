<?php
/**
*
* Tritanium Bulletin Board 2 - search.php
* version #2005-05-02-18-17-06
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

if($CONFIG['search_status'] == 0) {
	add_navbar_items(array($LNG['Function_deactivated'],''));

	include_once('pheader.php');
		show_message($LNG['Function_deactivated'],$LNG['message_function_deactivated']);
	include_once('ptail.php'); exit;
}
elseif($USER_LOGGED_IN != 1 && $CONFIG['search_status'] == 1) {
	add_navbar_items(array($LNG['Not_logged_in'],''));

	include_once('pheader.php');
		show_message($LNG['Not_logged_in'],$LNG['message_not_logged_in']);
	include_once('ptail.php'); exit;
}

add_navbar_items(array($LNG['Search'],"index.php?faction=search&amp;$MYSID"));

switch(@$_GET['mode']) {
	default:
		$p_search_words = isset($_POST['p_search_words']) ? $_POST['p_search_words'] : '';
		$p_search_author = isset($_POST['p_search_author']) ? $_POST['p_search_author'] : '';
		$p_search_forums = isset($_POST['p_search_forums']) ? $_POST['p_search_forums'] : array('all');
		$p_display_results = isset($_POST['p_display_results']) ? $_POST['p_display_results'] : '';
		$p_search_method = isset($_POST['p_search_method']) ? $_POST['p_search_method'] : 2;

		$p_search_words_exact = 0;
		$error = '';

		// 0: nur titel
		// 1: nur Beitraege
		// 2: posts und titel

		$authed_forums_ids = get_authed_forums();
		$target_forums_ids = array();


		if(isset($_GET['doit'])) {
			$p_search_words_exact = isset($_POST['p_search_words_exact']) ? 1 : 0;

			if(in_array('all',$p_search_forums) == TRUE) $target_forums_ids = $authed_forums_ids;
			else $target_forums_ids = get_common_values($authed_forums_ids,$p_search_forums);
			if(count($target_forums_ids) == 0) $target_forums_ids == $authed_forums_ids;

			$search_words = explode(' ',preg_replace('/[ ]{2,}/',' ',trim($p_search_words)));

			while(list($akt_key) = each($search_words)) {
				if(strlen($search_words[$akt_key]) < 4 || preg_match('/^[*]{1,}$/',$search_words[$akt_key]) == TRUE) unset($search_words[$akt_key]);
				else {
					$search_words[$akt_key] = str_replace('%','\%',$search_words[$akt_key]);
					$search_words[$akt_key] = str_replace('*','%',$search_words[$akt_key]);

					if($p_search_words_exact != 1)
						$search_words[$akt_key] = '%'.$search_words[$akt_key].'%';
				}
			}
			reset($search_words);

			$query_words = '';
			if(count($search_words) != 0) {
				$query_words = array();
				while(list(,$akt_word) = each($search_words)) {
					if($p_search_method == 0 || $p_search_method == 2) $query_words[] = "post_title LIKE '$akt_word'";
					if($p_search_method == 1 || $p_search_method == 2) $query_words[] = "post_text LIKE '$akt_word'";
				}

				$query_words = implode(' OR ',$query_words);

				$found_posts_ids = array();
				$DB->query("SELECT post_id FROM ".TBLPFX."posts WHERE $query_words");
				while(list($akt_post_id) = $DB->fetch_array())
					$found_posts_ids[] = $akt_post_id;

				$query_words = " AND post_id IN ('".implode("','",$found_posts_ids)."')";
			}

			$query_author = '';
			if($p_search_author != '') {
				if($author_id = get_user_id($p_search_author))
					$query_author = " AND poster_id='$author_id'";
			}

			$found_posts_ids = array();
			$DB->query("SELECT post_id FROM ".TBLPFX."posts WHERE forum_id IN ('".implode("','",$target_forums_ids)."')$query_author$query_words");
			while(list($akt_post_id) = $DB->fetch_array())
				$found_posts_ids[] = $akt_post_id;


			if(count($found_posts_ids) == 0) $error = $LNG['error_no_search_results'];
			else {
				$new_search_id = get_rand_string(32);
				$DB->query("INSERT INTO ".TBLPFX."search_results (search_id,session_id,search_last_access,search_results) VALUES ('$new_search_id','".session_id()."',NOW(),'".implode(',',$found_posts_ids)."')");
				header("Location: index.php?faction=search&mode=viewresults&search_id=$new_search_id&$MYSID"); exit;
			}
		}


		$DB->query("SELECT forum_id,cat_id,forum_name FROM ".TBLPFX."forums WHERE forum_id IN ('".implode("','",$authed_forums_ids)."')");
		$authed_forums_data = $DB->raw2array();
		$authed_forums_counter = count($authed_forums_data);


		$cats_data = cats_get_cats_data();
		$cats_counter = count($cats_data);


		$search_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['search_form']);

		for($i = 0; $i < $cats_counter; $i++) {
			$akt_prefix = '';
			for($j = 1; $j < $cats_data[$i]['cat_depth']; $j++)
				$akt_prefix .= '--';


			$akt_option_value = '';
			$akt_option_text = $akt_prefix.' ('.$cats_data[$i]['cat_name'].')';
			$search_tpl->blocks['optionrow']->parse_code(FALSE,TRUE);

			while(list($akt_key,$akt_forum) = each($authed_forums_data)) {
				if($akt_forum['cat_id'] == $cats_data[$i]['cat_id']) {
					$akt_option_value = $akt_forum['forum_id'];
					$akt_option_text = $akt_prefix.'-- '.$akt_forum['forum_name'];
					$search_tpl->blocks['optionrow']->parse_code(FALSE,TRUE);

					unset($authed_forums_data[$akt_key]);
				}
			}
			reset($authed_forums_data);
		}


		if($error != '') $search_tpl->blocks['errorrow']->parse_code();

		include_once('pheader.php');
				$search_tpl->parse_code(TRUE);
		include_once('ptail.php');
	break;

	case 'viewresults':
		$search_id = isset($_GET['search_id']) ? $_GET['search_id'] : '';
		$display_type = isset($_REQUEST['display_type']) ? $_REQUEST['display_type'] : 'topics';
		$sort_method = isset($_REQUEST['sort_method']) ? $_REQUEST['sort_method'] : 'DESC';
		$sort_type = isset($_REQUEST['sort_type']) ? $_REQUEST['sort_type'] : 'time';
		$results_per_page = isset($_REQUEST['results_per_page']) ? intval($_REQUEST['results_per_page']) : 20;

		if($sort_type != 'time' && $sort_type != 'author' && $sort_type != 'title') $sort_type = 'time';
		if($display_type != 'topics' && $display_type != 'posts') $display_type = 'topics';
		if($sort_method != 'ASC' && $sort_method != 'DESC') $sort_method = 'DESC';

		$DB->query("SELECT * FROM ".TBLPFX."search_results WHERE search_id='$search_id'"); // Suchergebnisse aus der Datenbank laden...
		($DB->affected_rows == 0) ?  die('Kann Suchergebnisse nicht laden!') : $search_data = $DB->fetch_array(); // ...und einen Fehler ausgeben, wenn keine gefunden wurden. Andernfalls die Daten speichern...
		if($search_data['session_id'] != session_id()) die('Sie sind nicht berechtigt diese Suchergebnisse zu sehen!'); // ...und ueberpruefen, ob die Session die Suchergebnisse auswerten darf
		$DB->query("UPDATE ".TBLPFX."search_results SET search_last_access=NOW() WHERE search_id='$search_id'"); // Den letzten Zugriff auf die Suche updaten


		if($display_type == 'topics') { // Anzeigeoption "Themen"
			$display_type = $search_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['search_viewresults_topics']);
			$DB->query("SELECT topic_id FROM ".TBLPFX."posts WHERE post_id IN (".$search_data['search_results'].") GROUP BY topic_id");
			$results_counter = $DB->count_rows();

			$topic_ids = $DB->raw2fvarray();

			$query_sort_type = '';
			if($sort_type == 'time') $query_sort_type = 't2.post_time';
			elseif($sort_type == 'title') $query_sort_type = 't1.topic_title';
			else $query_sort_type = 't1.poster_id';

			$DB->query("SELECT t1.topic_id,t1.topic_title,t1.topic_last_post_id,t1.topic_replies_counter,t1.topic_views_counter,t1.topic_is_pinned,t1.topic_guest_nick,t1.topic_poll,t1.poster_id,t3.user_nick AS poster_nick, t2.poster_id AS topic_last_post_poster_id, t2.post_guest_nick AS topic_last_post_guest_nick, t2.post_time AS topic_last_post_time, t4.user_nick AS topic_last_post_poster_nick FROM ".TBLPFX."topics AS t1, ".TBLPFX."posts AS t2 LEFT JOIN ".TBLPFX."users AS t3 ON t1.poster_id=t3.user_id LEFT JOIN ".TBLPFX."users AS t4 ON t2.poster_id=t4.user_id WHERE t1.topic_id IN (".implode(',',$topic_ids).") AND t2.post_id=t1.topic_last_post_id ORDER BY $query_sort_type $sort_method LIMIT 0,$results_per_page");

			while($akt_topic_data = $DB->fetch_array()) {
				$akt_topic_prefix = '';
				if($akt_topic_data['topic_is_pinned'] == 1) $akt_topic_prefix .= $LNG['Important'].': ';
				if($akt_topic_data['topic_poll'] == 1) $akt_topic_prefix .= $LNG['Poll'].': ';

				$akt_topic_poster_nick = ($akt_topic_data['poster_id'] == 0) ? $akt_topic_data['topic_guest_nick'] : '<a href="index.php?faction=viewprofile&amp;profile_id='.$akt_topic_data['poster_id'].'&amp;'.$MYSID.'">'.$akt_topic_data['poster_nick'].'</a>';

				if($akt_topic_data['topic_last_post_poster_id'] == 0)
					$topic_last_post_poster = $akt_topic_data['topic_last_post_guest_nick'];
				else $topic_last_post_poster = '<a href="index.php?faction=viewprofile&amp;profile_id='.$akt_topic_data['topic_last_post_poster_id'].'&amp;'.$MYSID.'">'.$akt_topic_data['topic_last_post_poster_nick'].'</a>';
				$topic_last_post = format_date($akt_topic_data['topic_last_post_time']).'<br />'.$LNG['by'].' '.$topic_last_post_poster.' <a href="index.php?faction=viewtopic&amp;topic_id='.$akt_topic_data['topic_id'].'&amp;z=last&amp;'.$MYSID.'#post'.$akt_topic_data['topic_last_post_id'].'">&#187;</a>';

				$search_tpl->blocks['topicrow']->parse_code(FALSE,TRUE);
			}

		}
		elseif($display_type == 'posts') { // Anzeigeoption "Beitraege"
			$search_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['search_viewresults_posts']);
		}

		$c = ' selected="selected"';
		$checked = array();
		$checked['sort_method_asc'] = ($sort_method == 'ASC') ? $c : '';
		$checked['sort_method_desc'] = ($sort_method == 'DESC') ? $c : '';
		$checked['sort_type_time'] = ($sort_type == 'time') ? $c : '';
		$checked['sort_type_author'] = ($sort_type == 'author') ? $c : '';
		$checked['sort_type_title'] = ($sort_type == 'title') ? $c : '';
		$checked['display_type_posts'] = ($display_type == 'posts') ? $c : '';
		$checked['display_type_topics'] = ($display_type == 'topics') ? $c : '';


		add_navbar_items(array($LNG['View_search_results'],"index.php?faction=search&amp;mode=viewresults&amp;search_id=$search_id&amp;$MYSID"));

		include_once('pheader.php');
				$search_tpl->parse_code(TRUE);
		include_once('ptail.php');
	break;
}

?>