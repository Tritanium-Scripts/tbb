<?php
/**
*
* Tritanium Bulletin Board 2 - ad_avatars.php
* version #2004-03-07-20-21-33
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

switch(@$_GET['mode']) {
	default:
		$ad_avatars_tpl = new template;
		$ad_avatars_tpl->load($template_path.'/'.$tpl_config['tpl_ad_avatars_index']);

		$db->query("SELECT * FROM ".TBLPFX."avatars");
		if($db->affected_rows > 0) {
			while($akt_avatar = $db->fetch_array())
				$ad_avatars_tpl->blocks['avatarrow']->parse_code(FALSE,TRUE);
		}
		else $ad_avatars_tpl->unset_block('avatarrow');

		include_once('ad_pheader.php');
		$ad_avatars_tpl->parse_code(TRUE);
		include_once('ad_ptail.php');
	break;

	case 'deleteavatar':
		$avatar_id = isset($_GET['avatar_id']) ? $_GET['avatar_id'] : 0;

		$db->query("DELETE FROM ".TBLPFX."avatars WHERE avatar_id='$avatar_id'");

		header("Location: administration.php?faction=ad_avatars&$MYSID"); exit;
	break;

	case 'addavatar':
		$p_avatar_address = isset($_POST['p_avatar_address']) ? $_POST['p_avatar_address'] : '';

		$error = '';

		if(isset($_GET['doit'])) {
			if($p_avatar_address == '') $error = $lng['error_no_avatar_address'];
			else {
				$db->query("INSERT INTO ".TBLPFX."avatars (avatar_address) VALUES ('$p_avatar_address')");

				header("Location: administration.php?faction=ad_avatars&$MYSID"); exit;
			}
		}

		$ad_avatars_tpl = new template;
		$ad_avatars_tpl->load($template_path.'/'.$tpl_config['tpl_ad_avatars_addavatar']);

		if($error != '') $ad_avatars_tpl->blocks['errorrow']->parse_code();
		else $ad_avatars_tpl->unset_block('errorrow');

		include_once('ad_pheader.php');
		$ad_avatars_tpl->parse_code(TRUE);
		include_once('ad_ptail.php');
	break;

	case 'editavatar':
		$avatar_id = isset($_GET['avatar_id']) ? $_GET['avatar_id'] : 0;

		if(!$avatar_data = get_avatar_data($avatar_id)) die('Kann Avatardaten nicht laden!');

		$p_avatar_address = isset($_POST['p_avatar_address']) ? $_POST['p_avatar_address'] : $avatar_data['avatar_address'];

		$error = '';

		if(isset($_GET['doit'])) {
			if($p_avatar_address == '') $error = $lng['error_no_avatar_address'];
			else {
				$db->query("UPDATE ".TBLPFX."avatars SET avatar_address='$p_avatar_address' WHERE avatar_id='$avatar_id'");

				header("Location: administration.php?faction=ad_avatars&$MYSID"); exit;
			}
		}

		$ad_avatars_tpl = new template;
		$ad_avatars_tpl->load($template_path.'/'.$tpl_config['tpl_ad_avatars_editavatar']);

		if($error != '') $ad_avatars_tpl->blocks['errorrow']->parse_code();
		else $ad_avatars_tpl->unset_block('errorrow');

		include_once('ad_pheader.php');
		$ad_avatars_tpl->parse_code(TRUE);
		include_once('ad_ptail.php');
	break;
}

?>