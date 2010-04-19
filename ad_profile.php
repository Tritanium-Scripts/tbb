<?php
/**
*
* Tritanium Bulletin Board 2 - ad_profile.php
* version #2004-11-15-20-38-18
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

switch(@$_GET['mode']) {
	default:
		$tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_profile']);

		$db->query("SELECT field_id,field_name,field_type FROM ".TBLPFX."profile_fields");
		while($akt_field = $db->fetch_array()) {
			$akt_field_type = '';
			if($akt_field['field_type'] == 0) $akt_field_type = $lng['Textfield'];
			elseif($akt_field['field_type'] == 1) $akt_field_type = $lng['Textarea'];
			elseif($akt_field['field_type'] == 2) $akt_field_type = $lng['Single_selection_list'];
			elseif($akt_field['field_type'] == 3) $akt_field_type = $lng['Multiple_selection_list'];

			$tpl->blocks['fieldrow']->parse_code(FALSE,TRUE);
		}

		include_once('ad_pheader.php');
		$tpl->parse_code(TRUE);
		include_once('ad_ptail.php');
	break;

	case 'addfield':
		$p_field_type = isset($_POST['p_field_type']) ? intval($_POST['p_field_type']) : 0;
		$p_field_name = isset($_POST['p_field_name']) ? $_POST['p_field_name'] : '';
		$p_field_data = isset($_POST['p_field_data']) ? $_POST['p_field_data'] : '';
		$p_field_regex_verification = isset($_POST['p_field_regex_verification']) ? $_POST['p_field_regex_verification'] : '';
		$p_field_is_required = isset($_POST['p_field_is_required']) ? intval($_POST['p_field_is_required']) : 0;

		if(isset($_GET['doit'])) {
			$db->query("INSERT INTO ".TBLPFX."profile_fields (field_name,field_type,field_is_required,field_data,field_regex_verification) VALUES ('$p_field_name','$p_field_type','$p_field_is_required','$p_field_data','$p_field_regex_verification')");

			header("Location: administration.php?faction=ad_profile&$MYSID"); exit;
		}

		$tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_profile_addfield']);

		include_once('ad_pheader.php');
		$tpl->parse_code(TRUE);
		include_once('ad_ptail.php');
	break;

	case 'editfield':
		$field_id = isset($_GET['field_id']) ? intval($_GET['field_id']) : 0;

		$db->query("SELECT * FROM ".TBLPFX."profile_fields WHERE field_id='$field_id'");
		($db->affected_rows == 0) ? die('Nein...') : $field_data = $db->fetch_array();

		$p_field_type = isset($_POST['p_field_type']) ? intval($_POST['p_field_type']) : $field_data['field_type'];
		$p_field_name = isset($_POST['p_field_name']) ? $_POST['p_field_name'] : $field_data['field_name'];
		$p_field_data = isset($_POST['p_field_data']) ? $_POST['p_field_data'] : $field_data['field_data'];
		$p_field_regex_verification = isset($_POST['p_field_regex_verification']) ? $_POST['p_field_regex_verification'] : $field_data['field_regex_verification'];
		$p_field_is_required = isset($_POST['p_field_is_required']) ? intval($_POST['p_field_is_required']) : $field_data['field_is_required'];

		if(isset($_GET['doit'])) {
			$db->query("INSERT INTO ".TBLPFX."profile_fields (field_name,field_type,field_is_required,field_data,field_regex_verification) VALUES ('$p_field_name','$p_field_type','$p_field_is_required','$p_field_data','$p_field_regex_verification')");

			header("Location: administration.php?faction=ad_profile&$MYSID"); exit;
		}

		$tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_profile_editfield']);

		include_once('ad_pheader.php');
		$tpl->parse_code(TRUE);
		include_once('ad_ptail.php');
	break;

	case 'deletefield':
		$field_id = isset($_GET['field_id']) ? intval($_GET['field_id']) : 0;

		$db->query("DELETE FROM ".TBLPFX."profile_field WHERE field_id='$field_id'");
		$db->query("DELETE FROM ".TBLPFX."profile_fields_data WHERE field_id='$field_id'");

		header("Location: administration.php?faction=ad_profile&$MYSID"); exit;
	break;
}

?>