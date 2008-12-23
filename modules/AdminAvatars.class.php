<?php
/**
 * @author Julian Backes <julian@tritanium-scripts.com>
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2003 - 2009, Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package tbb2
 */
class AdminAvatars extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'AuthAdmin',
		'DB',
		'GlobalsAdmin',
		'Language',
		'Navbar',
		'Template'
	);

	public function executeMe() {
		$this->modules['Language']->addFile('AdminAvatars');
		$this->modules['Navbar']->addElement($this->modules['Language']->getString('manage_avatars'),INDEXFILE.'?action=AdminAvatars&amp;'.MYSID);

		switch(@$_GET['mode']) {
			default:
				$this->modules['DB']->query('SELECT * FROM '.TBLPFX.'avatars');
				$avatarsData = $this->modules['DB']->raw2Array();

				$this->modules['Template']->assign(array(
					'avatarsData'=>$avatarsData
				));
				$this->modules['Template']->printPage('AdminAvatars.tpl');
				break;

			case 'DeleteAvatar':
				$avatarID = isset($_GET['avatarID']) ? intval($_GET['avatarID']) : 0;

				$this->modules['DB']->queryParams('
					DELETE FROM '.TBLPFX.'avatars
					WHERE
						"avatarID"=$1
				',array(
					$avatarID
				));

				Functions::myHeader(INDEXFILE.'?action=AdminAvatars&'.MYSID);
				break;

			case 'AddAvatar':
				$p = Functions::getSGValues($_POST['p'],array('avatarAddress'),'');

				$error = '';

				if(isset($_GET['doit'])) {
					if($p['avatarAddress'] == '') $error = $this->modules['Language']->getString('error_no_avatar_address');
					else {
						$this->modules['DB']->queryParams('
							INSERT INTO
								'.TBLPFX.'avatars
							SET
								"avatarAddress"=$1
						',array(
							$p['avatarAddress']
						));

						Functions::myHeader(INDEXFILE.'?action=AdminAvatars&'.MYSID);
					}
				}

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('add_avatar'),INDEXFILE.'?action=AdminAvatars&amp;mode=AddAvatar&amp;'.MYSID);

				$this->modules['Template']->assign(array(
					'p'=>$p,
					'error'=>$error
				));
				$this->modules['Template']->printPage('AdminAvatarsAddAvatar.tpl');
				break;

			case 'EditAvatar':
				$avatarID = isset($_GET['avatarID']) ? intval($_GET['avatarID']) : 0;
				if(!$avatarData = FuncAvatars::getAvatarData($avatarID)) die('Cannot load data: avatar');

				$p = Functions::getSGValues($_POST['p'],array('avatarAddress'),'',$avatarData);

				$error = '';

				if(isset($_GET['doit'])) {
					if($p['avatarAddress'] == '') $error = $this->modules['Language']->getString('error_no_avatar_address');
					else {
						$this->modules['DB']->queryParams('
							UPDATE
								'.TBLPFX.'avatars
							SET
								"avatarAddress"=$1
							WHERE
								"avatarID"=$2
						',array(
							$p['avatarAddress'],
							$avatarID
						));

						Functions::myHeader(INDEXFILE.'?action=AdminAvatars&'.MYSID);
					}
				}

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('edit_avatar'),INDEXFILE.'?action=AdminAvatars&amp;mode=EditAvatar&amp;avatarID='.$avatarID.'&amp;'.MYSID);

				$this->modules['Template']->assign(array(
					'p'=>$p,
					'error'=>$error,
					'avatarID'=>$avatarID
				));
				$this->modules['Template']->printPage('AdminAvatarsEditAvatar.tpl');
				break;
		}
	}
}