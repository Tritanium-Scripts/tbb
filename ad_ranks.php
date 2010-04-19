<?php
/**
*
* Tritanium Bulletin Board 2 - ad_ranks.php
* version #2005-01-20-20-45-11
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

switch(@$_GET['mode']) {
	default:
		$ad_ranks_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_ranks_index']);

		$DB->query("SELECT * FROM ".TBLPFX."ranks WHERE rank_type='0' ORDER BY rank_posts");
		if($DB->affected_rows != 0) {
			while($akt_rank = $DB->fetch_array()) {
				$akt_rank_gfx = '';
				if($akt_rank['rank_gfx'] != '') {
					$akt_rank_gfx = explode(';',$akt_rank['rank_gfx']);
					while(list($akt_key) = each($akt_rank_gfx))
						$akt_rank_gfx[$akt_key] = '<img src="'.$akt_rank_gfx[$akt_key].'" alt="" border="" />';

					$akt_rank_gfx = implode('',$akt_rank_gfx);
				}
				$ad_ranks_tpl->blocks['nrankrow']->parse_code(FALSE,TRUE);
			}
		}

		$DB->query("SELECT * FROM ".TBLPFX."ranks WHERE rank_type='1' ORDER BY rank_name");
		if($DB->affected_rows != 0) {
			while($akt_rank = $DB->fetch_array()) {
				if($akt_rank['rank_gfx'] != '') {
					$akt_rank_gfx = explode(';',$akt_rank['rank_gfx']);
					while(list($akt_key) = each($akt_rank_gfx))
						$akt_rank_gfx[$akt_key] = '<img src="'.$akt_rank_gfx[$akt_key].'" alt="" border="" />';

					$akt_rank_gfx = implode('',$akt_rank_gfx);
				}
				$ad_ranks_tpl->blocks['srankrow']->parse_code(FALSE,TRUE);
			}
		}

		include_once('ad_pheader.php');
		$ad_ranks_tpl->parse_code(TRUE);
		include_once('ad_ptail.php');
	break;

	case 'addrank':
		$p_rank_posts = isset($_POST['p_rank_posts']) ? $_POST['p_rank_posts'] : 0;
		$p_rank_name = isset($_POST['p_rank_name']) ? $_POST['p_rank_name'] : '';
		$p_rank_type = isset($_POST['p_rank_type']) ? $_POST['p_rank_type'] : 0;
		$p_rank_gfx = isset($_POST['p_rank_gfx']) ? $_POST['p_rank_gfx'] : '';

		$error = '';


		if(isset($_GET['doit'])) {
			if($p_rank_name == '') $error = $LNG['error_no_rank_name'];
			else {
				$p_rank_gfx = explode(';',$p_rank_gfx);
				while(list($akt_key) = each($p_rank_gfx))
					$p_rank_gfx[$akt_key] = trim($p_rank_gfx[$akt_key]);
				$p_rank_gfx = implode(';',$p_rank_gfx);

				if($p_rank_type == 1)
					$p_rank_posts = 0;

				$DB->query("INSERT INTO ".TBLPFX."ranks (rank_type,rank_name,rank_gfx,rank_posts) VALUES ('$p_rank_type','$p_rank_name','$p_rank_gfx','$p_rank_posts')");
				cache_set_ranks_data();

				header("Location: administration.php?faction=ad_ranks&$MYSID"); exit;
			}
		}


		$c = ' selected="selected"';
		$selected = array(
			'normal_rank'=>($p_rank_type == 0) ? $c : '',
			'special_rank'=>($p_rank_type == 1) ? $c : ''
		);


		$ad_ranks_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_ranks_addrank']);

		if($error != '') $ad_ranks_tpl->blocks['errorrow']->parse_code();

		include_once('ad_pheader.php');
		$ad_ranks_tpl->parse_code(TRUE);
		include_once('ad_ptail.php');
	break;

	case 'deleterank':
		$rank_id = isset($_GET['rank_id']) ? $_GET['rank_id'] : 0;

		if($rank_data = get_rank_data($rank_id)) {
			$DB->query("DELETE FROM ".TBLPFX."ranks WHERE rank_id='$rank_id'");
			if($rank_data['rank_type'] == 1) {
				$DB->query("UPDATE ".TBLPFX."users SET user_rank_id='0' WHERE user_rank_id='$rank_id'");
				cache_set_ranks_data();
			}
		}

		header("Location: administration.php?faction=ad_ranks&$MYSID"); exit;
	break;

	case 'editrank':
		$rank_id = isset($_GET['rank_id']) ? $_GET['rank_id'] : 0;

		if(!$rank_data = get_rank_data($rank_id)) die('Kann Rangdaten nicht laden!');


		$p_rank_posts = isset($_POST['p_rank_posts']) ? $_POST['p_rank_posts'] : $rank_data['rank_posts'];
		$p_rank_name = isset($_POST['p_rank_name']) ? $_POST['p_rank_name'] : $rank_data['rank_name'];
		$p_rank_type = isset($_POST['p_rank_type']) ? $_POST['p_rank_type'] : $rank_data['rank_type'];
		$p_rank_gfx = isset($_POST['p_rank_gfx']) ? $_POST['p_rank_gfx'] : $rank_data['rank_gfx'];

		$error = '';


		if(isset($_GET['doit'])) {
			if($p_rank_name == '') $error = $LNG['error_no_rank_name'];
			else {
				$p_rank_gfx = explode(';',$p_rank_gfx);
				while(list($akt_key) = each($p_rank_gfx))
					$p_rank_gfx[$akt_key] = trim($p_rank_gfx[$akt_key]);
				$p_rank_gfx = implode(';',$p_rank_gfx);

				if($p_rank_type == 1)
					$p_rank_posts = 0;

				$DB->query("UPDATE ".TBLPFX."ranks SET rank_type='$p_rank_type', rank_name='$p_rank_name', rank_gfx='$p_rank_gfx', rank_posts='$p_rank_posts' WHERE rank_id='$rank_id'");
				cache_set_ranks_data();

				header("Location: administration.php?faction=ad_ranks&$MYSID"); exit;
			}
		}


		$c = ' selected="selected"';
		$selected = array(
			'normal_rank'=>($p_rank_type == 0) ? $c : '',
			'special_rank'=>($p_rank_type == 1) ? $c : ''
		);


		$ad_ranks_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_ranks_editrank']);

		if($error != '') $ad_ranks_tpl->blocks['errorrow']->parse_code();

		include_once('ad_pheader.php');
		$ad_ranks_tpl->parse_code(TRUE);
		include_once('ad_ptail.php');
	break;
}

?>