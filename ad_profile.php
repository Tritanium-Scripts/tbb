<?php
/**
*
* Tritanium Bulletin Board 2 - ad_profile.php
* version #2005-01-20-20-45-11
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

switch(@$_GET['mode']) {
	default:
		$tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_profile']);

		$DB->query("SELECT field_id,field_name,field_type FROM ".TBLPFX."profile_fields");
		while($akt_field = $DB->fetch_array()) {
			$akt_field_type = '';
			if($akt_field['field_type'] == 0) $akt_field_type = $LNG['Textfield'];
			elseif($akt_field['field_type'] == 1) $akt_field_type = $LNG['Textarea'];
			elseif($akt_field['field_type'] == 2) $akt_field_type = $LNG['Single_selection_list'];
			elseif($akt_field['field_type'] == 3) $akt_field_type = $LNG['Multiple_selection_list'];

			$tpl->blocks['fieldrow']->parse_code(FALSE,TRUE);
		}

		include_once('ad_pheader.php');
		$tpl->parse_code(TRUE);
		include_once('ad_ptail.php');
	break;

	case 'addfield':
		$p_field_type = isset($_POST['p_field_type']) ? intval($_POST['p_field_type']) : 0;
		$p_field_name = isset($_POST['p_field_name']) ? $_POST['p_field_name'] : '';
		$p_field_regex_verification = isset($_POST['p_field_regex_verification']) ? $_POST['p_field_regex_verification'] : '';
		$p_field_is_required = isset($_POST['p_field_is_required']) ? intval($_POST['p_field_is_required']) : 0;
		$p_field_show_registration = isset($_POST['p_field_show_registration']) ? intval($_POST['p_field_show_registration']) : 0;
		$p_field_show_memberlist = isset($_POST['p_field_show_memberlist']) ? intval($_POST['p_field_show_memberlist']) : 0;
		$p_field_data = array();

		if(isset($_POST['p_field_data']) == TRUE && $_POST['p_field_data'] != '') {
			$p_field_data = str_replace("\r",'',$_POST['p_field_data']);
			$p_field_data = explode("\n",$p_field_data);
		}

		if(isset($_GET['doit'])) {
			$DB->query("INSERT INTO ".TBLPFX."profile_fields (field_name,field_type,field_is_required,field_show_registration,field_show_memberlist,field_data,field_regex_verification) VALUES ('$p_field_name','$p_field_type','$p_field_is_required','$p_field_show_registration','$p_field_show_memberlist','".serialize($p_field_data)."','$p_field_regex_verification')");

			header("Location: administration.php?faction=ad_profile&$MYSID"); exit;
		}

		$p_field_data = implode("\n",$p_field_data);

		$tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_profile_addfield']);

		include_once('ad_pheader.php');
		$tpl->parse_code(TRUE);
		include_once('ad_ptail.php');
	break;

	case 'editfield':
		$field_id = isset($_GET['field_id']) ? intval($_GET['field_id']) : 0;

		$DB->query("SELECT * FROM ".TBLPFX."profile_fields WHERE field_id='$field_id'");
		($DB->affected_rows == 0) ? die('Nein...') : $field_data = $DB->fetch_array();

		$p_field_type = isset($_POST['p_field_type']) ? intval($_POST['p_field_type']) : $field_data['field_type'];
		$p_field_name = isset($_POST['p_field_name']) ? $_POST['p_field_name'] : $field_data['field_name'];
		$p_field_regex_verification = isset($_POST['p_field_regex_verification']) ? $_POST['p_field_regex_verification'] : $field_data['field_regex_verification'];
		$p_field_is_required = isset($_POST['p_field_is_required']) ? intval($_POST['p_field_is_required']) : $field_data['field_is_required'];;
		$p_field_show_registration = isset($_POST['p_field_show_registration']) ? intval($_POST['p_field_show_registration']) : $field_data['field_show_registration'];
		$p_field_show_memberlist = isset($_POST['p_field_show_memberlist']) ? intval($_POST['p_field_show_memberlist']) : $field_data['field_show_memberlist'];

		if(isset($_POST['p_field_data']) == TRUE) {
			if($_POST['p_field_data'] == '') $p_field_data = array();
			else {
				$p_field_data = str_replace("\r",'',$_POST['p_field_data']);
				$p_field_data = explode("\n",$p_field_data);
			}
		}
		else $p_field_data = unserialize($field_data['field_data']);


		if(isset($_GET['doit'])) {
			$DB->query("UPDATE ".TBLPFX."profile_fields SET field_name='$p_field_name', field_type='$p_field_type', field_is_required='$p_field_is_required', field_show_registration='$p_field_show_registration', field_show_memberlist='$p_field_show_memberlist', field_data='".serialize($p_field_data)."', field_regex_verification='$p_field_regex_verification' WHERE field_id='$field_id'");

			header("Location: administration.php?faction=ad_profile&$MYSID"); exit;
		}

		$p_field_data = implode("\n",$p_field_data);

		$tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_profile_editfield']);

		include_once('ad_pheader.php');
		$tpl->parse_code(TRUE);
		include_once('ad_ptail.php');
	break;

	case 'deletefield':
		$field_id = isset($_GET['field_id']) ? intval($_GET['field_id']) : 0;

		$DB->query("DELETE FROM ".TBLPFX."profile_fields WHERE field_id='$field_id'");
		$DB->query("DELETE FROM ".TBLPFX."profile_fields_data WHERE field_id='$field_id'");

		header("Location: administration.php?faction=ad_profile&$MYSID"); exit;
	break;
}

?>