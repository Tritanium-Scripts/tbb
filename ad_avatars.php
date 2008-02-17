<?php
/**
*
* Tritanium Bulletin Board 2 - ad_avatars.php
* version #2005-05-02-18-17-06
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

switch(@$_GET['mode']) {
	default:
		$ad_avatars_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_avatars']);

		$DB->query("SELECT * FROM ".TBLPFX."avatars");
		if($DB->affected_rows > 0) {
			while($akt_avatar = $DB->fetch_array())
				$ad_avatars_tpl->blocks['avatarrow']->parse_code(FALSE,TRUE);
		}

		include_once('pheader.php');
		$ad_avatars_tpl->parse_code(TRUE);
		include_once('ptail.php');
	break;

	case 'deleteavatar':
		$avatar_id = isset($_GET['avatar_id']) ? $_GET['avatar_id'] : 0;

		$DB->query("DELETE FROM ".TBLPFX."avatars WHERE avatar_id='$avatar_id'");

		header("Location: administration.php?faction=ad_avatars&$MYSID"); exit;
	break;

	case 'addavatar':
		$p_avatar_address = isset($_POST['p_avatar_address']) ? $_POST['p_avatar_address'] : '';

		$error = '';

		if(isset($_GET['doit'])) {
			if($p_avatar_address == '') $error = $LNG['error_no_avatar_address'];
			else {
				$DB->query("INSERT INTO ".TBLPFX."avatars (avatar_address) VALUES ('$p_avatar_address')");

				header("Location: administration.php?faction=ad_avatars&$MYSID"); exit;
			}
		}

		$ad_avatars_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_avatars_addavatar']);

		include_once('pheader.php');
		$ad_avatars_tpl->parse_code(TRUE);
		include_once('ptail.php');
	break;

	case 'editavatar':
		$avatar_id = isset($_GET['avatar_id']) ? $_GET['avatar_id'] : 0;

		if(!$avatar_data = get_avatar_data($avatar_id)) die('Kann Avatardaten nicht laden!');

		$p_avatar_address = isset($_POST['p_avatar_address']) ? $_POST['p_avatar_address'] : $avatar_data['avatar_address'];

		$error = '';

		if(isset($_GET['doit'])) {
			if($p_avatar_address == '') $error = $LNG['error_no_avatar_address'];
			else {
				$DB->query("UPDATE ".TBLPFX."avatars SET avatar_address='$p_avatar_address' WHERE avatar_id='$avatar_id'");

				header("Location: administration.php?faction=ad_avatars&$MYSID"); exit;
			}
		}

		$ad_avatars_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_avatars_editavatar']);

		if($error != '') $ad_avatars_tpl->blocks['errorrow']->parse_code();

		include_once('pheader.php');
		$ad_avatars_tpl->parse_code(TRUE);
		include_once('ptail.php');
	break;
}

?>