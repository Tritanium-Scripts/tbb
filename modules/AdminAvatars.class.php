<?php

class AdminAvatars extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'AuthAdmin',
		'DB',
		'GlobalsAdmin',
		'Language',
		'Navbar',
		'PageParts',
		'Template'
	);

	public function executeMe() {
		$this->modules['Language']->addFile('AdminAvatars');
		$this->modules['Navbar']->addElement($this->modules['Language']->getString('Manage_avatars'),INDEXFILE.'?action=AdminAvatars&amp;'.MYSID);

		switch(@$_GET['mode']) {
			default:
				$this->modules['DB']->query("SELECT * FROM ".TBLPFX."avatars");
				$avatarsData = $this->modules['DB']->raw2Array();

				$this->modules['Template']->assign(array(
					'avatarsData'=>$avatarsData
				));
				$this->modules['PageParts']->printPage('AdminAvatars.tpl');
				break;

			case 'DeleteAvatar':
				$avatarID = isset($_GET['avatarID']) ? intval($_GET['avatarID']) : 0;

				$this->modules['DB']->query("DELETE FROM ".TBLPFX."avatars WHERE avatarID='$avatarID'");

				Functions::myHeader(INDEXFILE.'?action=AdminAvatars&'.MYSID);
				break;

			case 'AddAvatar':
				$p = Functions::getSGValues($_POST['p'],array('avatarAddress'),'');

				$error = '';

				if(isset($_GET['doit'])) {
					if($p['avatarAddress'] == '') $error = $this->modules['Language']->getString('error_no_avatar_address');
					else {
						$this->modules['DB']->query("
							INSERT INTO
								".TBLPFX."avatars
							SET
								avatarAddress='".$p['avatarAddress']."'
						");

						Functions::myHeader(INDEXFILE.'?action=AdminAvatars&'.MYSID);
					}
				}

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('Add_avatar'),INDEXFILE.'?action=AdminAvatars&amp;mode=AddAvatar&amp;'.MYSID);

				$this->modules['Template']->assign(array(
					'p'=>$p,
					'error'=>$error
				));
				$this->modules['PageParts']->printPage('AdminAvatarsAddAvatar.tpl');
				break;

			case 'EditAvatar':
				$avatarID = isset($_GET['avatarID']) ? $_GET['avatarID'] : 0;
				if(!$avatarData = FuncAvatars::getAvatarData($avatarID)) die('Cannot load data: avatar');

				$p = Functions::getSGValues($_POST['p'],array('avatarAddress'),'');

				$error = '';

				if(isset($_GET['doit'])) {
					if($p['avatarAddress'] == '') $error = $this->modules['Language']->getString('error_no_avatar_address');
					else {
						$this->modules['DB']->query("
							UPDATE
								".TBLPFX."avatars
							SET
								avatarAddress='".$p['avatarAddress']."'
							WHERE
								avatarID='$avatarID'
						");

						Functions::myHeader(INDEXFILE.'?action=AdminAvatars&'.MYSID);
					}
				}

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('Edit_avatar'),INDEXFILE.'?action=AdminAvatars&amp;mode=EditAvatar&amp;avatarID='.$avatarID.'&amp;'.MYSID);

				$this->modules['Template']->assign(array(
					'p'=>$p,
					'error'=>$error,
					'avatarID'=>$avatarID
				));
				$this->modules['PageParts']->printPage('AdminAvatarsEditAvatar.tpl');
				break;
		}
	}
}

?>