<?php
/**
*
* Tritanium Bulletin Board 2 - ad_smilies.php
* version #2003-09-17-17-03-24
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

switch(@$_GET['mode']) {
	default:
		$ad_smilies_tpl = new template;
		$ad_smilies_tpl->load($template_path.'/'.$tpl_config['tpl_ad_smilies_default']);

		$smilies_data = get_smilies_data(array('smiley_type'=>0));
		if(sizeof($smilies_data) != 0) {
			while(list(,$akt_smiley) = each($smilies_data)) {
				$akt_status = ($akt_smiley['smiley_status'] == 1) ? $lng['visible'] : $lng['invisible'];
				$ad_smilies_tpl->blocks['smileyrow']->parse_code(FALSE,TRUE);
			}
		}
		else $ad_smilies_tpl->unset_block('smileyrow');


		$ppics_data = get_smilies_data(array('smiley_type'=>1));
		if(sizeof($ppics_data) != 0) {
			while(list(,$akt_tpic) = each($ppics_data))
				$ad_smilies_tpl->blocks['tpicrow']->parse_code(FALSE,TRUE);
		}
		else $ad_smilies_tpl->unset_block('tpicrow');


		include_once('ad_pheader.php');

		$ad_smilies_tpl->parse_code(TRUE);

		include_once('ad_ptail.php');
	break;

	case 'delete':
		$smiley_id = isset($_GET['smiley_id']) ? $_GET['smiley_id'] : 0;

		delete_smilies_data(array(
			'smiley_id'=>array($smiley_id)
		));

		header("Location: administration.php?faction=ad_smilies&amp;$MYSID"); exit;
	break;

	case 'add':
		$p_type = isset($_GET['p_type']) ? $_GET['p_type'] : 0;
		if(isset($_POST['p_type'])) $p_type = $_POST['p_type'];

		$p_gfx = isset($_POST['p_gfx']) ? $_POST['p_gfx'] : '';
		$p_synonym = isset($_POST['p_synonym']) ? $_POST['p_synonym'] : '';
		$p_status = isset($_POST['p_status']) ? $_POST['p_status'] : 1;

		$error = '';

		if(isset($_GET['doit'])) {
			if(trim($p_gfx) == '') $error = $lng['error_no_path_or_url'];
			elseif($p_type == 0 && trim($p_synonym) == '') $error = $lng['error_no_synonym'];
			else {
				add_smiley_data(array(
					'smiley_type'=>$p_type,
					'smiley_gfx'=>$p_gfx,
					'smiley_status'=>($p_type == 1) ? 0 : $p_status,
					'smiley_synonym'=>($p_type == 1) ? '' : $p_synonym
				));
				header("Location: administration.php?faction=ad_smilies&$MYSID"); exit;
			}
		}

		$c = ' selected="selected"';

		$checked = array('p_type_0'=>'','p_type_1'=>'','p_status_0'=>'','p_status_1'=>'');
		($p_type == 0) ? $checked['p_type_0'] = $c : $checked['p_type_1'] = $c;
		($p_status == 0) ? $checked['p_status_0'] = $c : $checked['p_status_1'] = $c;

		$ad_smilies_tpl = new template;
		$ad_smilies_tpl->load($template_path.'/'.$tpl_config['tpl_ad_smilies_add']);

		if($error != '') $ad_smilies_tpl->blocks['errorrow']->parse_code();
		else $ad_smilies_tpl->unset_block('errorrow');

		include_once('ad_pheader.php');

		$ad_smilies_tpl->parse_code(TRUE);

		include_once('ad_ptail.php');
	break;

	case 'edit':
		$smiley_id = isset($_GET['smiley_id']) ? $_GET['smiley_id'] : 0;

		if(!$smiley_data = get_smiley_data(array('smiley_id'=>$smiley_id))) die('Kann Smileydaten nicht laden!');

		$p_type = isset($_POST['p_type']) ? $_POST['p_type'] : $smiley_data['smiley_type'];
		$p_gfx = isset($_POST['p_gfx']) ? $_POST['p_gfx'] : $smiley_data['smiley_gfx'];
		$p_synonym = isset($_POST['p_synonym']) ? $_POST['p_synonym'] : $smiley_data['smiley_synonym'];
		$p_status = isset($_POST['p_status']) ? $_POST['p_status'] : $smiley_data['smiley_status'];

		$error = '';

		if(isset($_GET['doit'])) {
			if(trim($p_gfx) == '') $error = $lng['error_no_path_or_url'];
			elseif($p_type == 0 && trim($p_synonym) == '') $error = $lng['error_no_synonym'];
			else {
				update_smilies_data(array('smiley_id'=>$smiley_id),array(
					'smiley_type'=>array('STR',$p_type),
					'smiley_gfx'=>array('STR',$p_gfx),
					'smiley_status'=>array('STR',($p_type == 1) ? 0 : $p_status),
					'smiley_synonym'=>array('STR',($p_type == 1) ? '' : $p_synonym)
				));
				header("Location: administration.php?faction=ad_smilies&$MYSID"); exit;
			}
		}

		$c = ' selected="selected"';

		$checked = array('p_type_0'=>'','p_type_1'=>'','p_status_0'=>'','p_status_1'=>'');
		($p_type == 0) ? $checked['p_type_0'] = $c : $checked['p_type_1'] = $c;
		($p_status == 0) ? $checked['p_status_0'] = $c : $checked['p_status_1'] = $c;

		$ad_smilies_tpl = new template;
		$ad_smilies_tpl->load($template_path.'/'.$tpl_config['tpl_ad_smilies_edit']);

		if($error != '') $ad_smilies_tpl->blocks['errorrow']->parse_code();
		else $ad_smilies_tpl->unset_block('errorrow');

		include_once('ad_pheader.php');

		$ad_smilies_tpl->parse_code(TRUE);

		include_once('ad_ptail.php');
	break;
}

?>