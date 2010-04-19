<?php
/**
*
* Tritanium Bulletin Board 2 - ad_smilies.php
* version #2004-11-15-20-38-18
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

switch(@$_GET['mode']) {
	default:
		$ad_smilies_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_smilies_default']);

		$smilies_data = cache_get_smilies_data();

		while(list(,$akt_smiley) = each($smilies_data)) {
			$akt_status = ($akt_smiley['smiley_status'] == 1) ? $lng['visible'] : $lng['invisible'];
			$ad_smilies_tpl->blocks['smileyrow']->parse_code(FALSE,TRUE);
		}


		$ppics_data = cache_get_ppics_data();

		if(count($ppics_data) != 0) {
			while(list(,$akt_tpic) = each($ppics_data))
				$ad_smilies_tpl->blocks['tpicrow']->parse_code(FALSE,TRUE);
		}


		include_once('ad_pheader.php');
		$ad_smilies_tpl->parse_code(TRUE);
		include_once('ad_ptail.php');
	break;

	case 'delete':
		$smiley_id = isset($_GET['smiley_id']) ? $_GET['smiley_id'] : 0;

		$db->query("DELETE FROM ".TBLPFX."smilies WHERE smiley_id='$smiley_id'");
		cache_set_smilies_data();
		cache_set_ppics_data();

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
				$db->query("INSERT INTO ".TBLPFX."smilies (smiley_type,smiley_gfx,smiley_status,smiley_synonym) VALUES ('$p_type','$p_gfx','".(($p_type == 1) ? 0 : $p_status)."','".(($p_type == 1) ? '' : $p_synonym)."')");
				cache_set_smilies_data();
				cache_set_ppics_data();
				header("Location: administration.php?faction=ad_smilies&$MYSID"); exit;
			}
		}

		$c = ' selected="selected"';

		$checked = array('p_type_0'=>'','p_type_1'=>'','p_status_0'=>'','p_status_1'=>'');
		($p_type == 0) ? $checked['p_type_0'] = $c : $checked['p_type_1'] = $c;
		($p_status == 0) ? $checked['p_status_0'] = $c : $checked['p_status_1'] = $c;

		$ad_smilies_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_smilies_add']);

		if($error != '') $ad_smilies_tpl->blocks['errorrow']->parse_code();

		include_once('ad_pheader.php');
		$ad_smilies_tpl->parse_code(TRUE);
		include_once('ad_ptail.php');
	break;

	case 'edit':
		$smiley_id = isset($_GET['smiley_id']) ? $_GET['smiley_id'] : 0;

		if(!$smiley_data = get_smiley_data($smiley_id)) die('Kann Smileydaten nicht laden!');

		$p_type = isset($_POST['p_type']) ? $_POST['p_type'] : $smiley_data['smiley_type'];
		$p_gfx = isset($_POST['p_gfx']) ? $_POST['p_gfx'] : $smiley_data['smiley_gfx'];
		$p_synonym = isset($_POST['p_synonym']) ? $_POST['p_synonym'] : $smiley_data['smiley_synonym'];
		$p_status = isset($_POST['p_status']) ? $_POST['p_status'] : $smiley_data['smiley_status'];

		$error = '';

		if(isset($_GET['doit'])) {
			if(trim($p_gfx) == '') $error = $lng['error_no_path_or_url'];
			elseif($p_type == 0 && trim($p_synonym) == '') $error = $lng['error_no_synonym'];
			else {
				$db->query("UPDATE ".TBLPFX."smilies SET smiley_type='$smiley_type', smiley_gfx='$smiley_gfx', smiley_status='".(($p_type == 1) ? 0 : $p_status)."', smiley_synonym='".(($p_type == 1) ? '' : $p_synonym)."' WHERE smiley_id='$smiley_id'");
				cache_set_smilies_data();
				cache_set_ppics_data();
				header("Location: administration.php?faction=ad_smilies&$MYSID"); exit;
			}
		}

		$c = ' selected="selected"';

		$checked = array('p_type_0'=>'','p_type_1'=>'','p_status_0'=>'','p_status_1'=>'');
		($p_type == 0) ? $checked['p_type_0'] = $c : $checked['p_type_1'] = $c;
		($p_status == 0) ? $checked['p_status_0'] = $c : $checked['p_status_1'] = $c;

		$ad_smilies_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_smilies_edit']);

		if($error != '') $ad_smilies_tpl->blocks['errorrow']->parse_code();

		include_once('ad_pheader.php');
		$ad_smilies_tpl->parse_code(TRUE);
		include_once('ad_ptail.php');
	break;
}

?>