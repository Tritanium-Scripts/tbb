<?php

class AdminSmilies extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'AuthAdmin',
		'Cache',
		'DB',
		'GlobalsAdmin',
		'Language',
		'Navbar',
		'PageParts',
		'Template'
	);

	public function executeMe() {
		$this->modules['Language']->addFile('AdminSmilies');
		$this->modules['Navbar']->addElement($this->modules['Language']->getString('Manage_smilies'),INDEXFILE.'?action=AdminSmilies&amp;'.MYSID);

		switch(@$_GET['mode']) {
			default:
				$smiliesData = $this->modules['Cache']->getSmiliesData();
				$postPicsData = $this->modules['Cache']->getPostPicsData();
				$adminSmiliesData = $this->modules['Cache']->getAdminSmiliesData();

				$this->modules['Template']->assign(array(
					'smiliesData'=>$smiliesData,
					'postPicsData'=>$postPicsData,
					'adminSmiliesData'=>$adminSmiliesData
				));

				$this->modules['PageParts']->printPage('AdminSmilies.tpl');
				break;

			case 'delete':
				$smiley_id = isset($_GET['smiley_id']) ? $_GET['smiley_id'] : 0;

				$DB->query("DELETE FROM ".TBLPFX."smilies WHERE smiley_id='$smiley_id'");
				cache_set_smilies_data();
				cache_set_ppics_data();
				cache_set_adminsmilies();

				header("Location: administration.php?action=ad_smilies&amp;$MYSID"); exit;
			break;

			case 'add':
				$p_smiley_type = isset($_GET['smiley_type']) ? intval($_GET['smiley_type']) : 0;
				if(isset($_POST['p_type'])) $p_smiley_type = intval($_POST['p_smiley_type']);

				if($p_smiley_type != 0 && $p_smiley_type != 1 && $p_smiley_type != 2) $p_smiley_type = 0;

				$p_smiley_gfx = isset($_POST['p_smiley_gfx']) ? $_POST['p_smiley_gfx'] : '';
				$p_smiley_synonym = isset($_POST['p_smiley_synonym']) ? $_POST['p_smiley_synonym'] : '';
				$p_smiley_status = isset($_POST['p_smiley_status']) ? $_POST['p_smiley_status'] : 1;

				$error = '';

				if(isset($_GET['doit'])) {
					if(trim($p_smiley_gfx) == '') $error = $LNG['error_no_path_or_url'];
					elseif($p_smiley_type == 0 && trim($p_synonym) == '') $error = $LNG['error_no_synonym'];
					else {
						$DB->query("INSERT INTO ".TBLPFX."smilies (smiley_type,smiley_gfx,smiley_status,smiley_synonym) VALUES ('$p_smiley_type','$p_smiley_gfx','".(($p_smiley_type == 1) ? 0 : $p_smiley_status)."','".(($p_smiley_type == 1) ? '' : $p_smiley_synonym)."')");
						cache_set_smilies_data();
						cache_set_ppics_data();
						cache_set_adminsmilies();

						header("Location: administration.php?action=ad_smilies&$MYSID"); exit;
					}
				}

				$tpl = new Template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_smilies_add']);

				include_once('pheader.php');
				$tpl->parseCode(TRUE);
				include_once('ptail.php');
			break;

			case 'edit':
				$smiley_id = isset($_GET['smiley_id']) ? $_GET['smiley_id'] : 0;

				if(!$smiley_data = get_smiley_data($smiley_id)) die('Kann Smileydaten nicht laden!');

				$p_smiley_type = isset($_POST['p_smiley_type']) ? intval($_POST['p_smiley_type']) : $smiley_data['smiley_type'];
				$p_smiley_gfx = isset($_POST['p_smiley_gfx']) ? $_POST['p_smiley_gfx'] : addslashes($smiley_data['smiley_gfx']);
				$p_smiley_synonym = isset($_POST['p_smiley_synonym']) ? $_POST['p_smiley_synonym'] : addslashes($smiley_data['smiley_synonym']);
				$p_smiley_status = isset($_POST['p_smiley_status']) ? intval($_POST['p_smiley_status']) : $smiley_data['smiley_status'];

				$error = '';

				if(isset($_GET['doit'])) {
					if(trim($p_smiley_gfx) == '') $error = $LNG['error_no_path_or_url'];
					elseif($p_smiley_type == 0 && trim($p_synonym) == '') $error = $LNG['error_no_synonym'];
					else {
						$DB->query("UPDATE ".TBLPFX."smilies SET
							smiley_type='$p_smiley_type',
							smiley_gfx='$p_smiley_gfx',
							smiley_status='".(($p_smiley_type == 1) ? 0 : $p_smiley_status)."',
							smiley_synonym='".(($p_smiley_type == 1) ? '' : $p_smiley_synonym)."'
						WHERE smiley_id='$smiley_id'");

						cache_set_smilies_data();
						cache_set_ppics_data();
						cache_set_adminsmilies();

						header("Location: administration.php?action=ad_smilies&$MYSID"); exit;
					}
				}

				$tpl = new Template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_smilies_edit']);

				include_once('pheader.php');
				$tpl->parseCode(TRUE);
				include_once('ptail.php');
			break;
		}
	}
}

?>