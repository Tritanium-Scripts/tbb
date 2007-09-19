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

				$this->modules['Template']->printPage('AdminSmilies.tpl');
				break;

			case 'deleteSmiley':
				$smileyID = isset($_GET['smileyID']) ? intval($_GET['smileyID']) : 0;

				$this->modules['DB']->query("DELETE FROM ".TBLPFX."smilies WHERE smileyID='$smileyID'");
				$this->modules['Cache']->setSmiliesData();
				$this->modules['Cache']->setPostPicsData();
				$this->modules['Cache']->setAdminSmiliesData();

				Functions::myHeader(INDEXFILE."?action=AdminSmilies&amp;".MYSID);
				break;

			case 'addSmiley':
				$p = Functions::getSGValues($_POST['p'],array('smileyType','smileyFileName','smileySynonym','smileyStatus'),'');
				if(isset($_GET['smileyType'])) $p['smileyType'] = $_GET['smileyType'];

				if(!in_array($p['smileyType'],array(SMILEY_TYPE_SMILEY,SMILEY_TYPE_ADMINSMILEY,SMILEY_TYPE_TPIC))) $p['smileyType'] = SMILEY_TYPE_SMILEY;

				$error = '';

				if(isset($_GET['doit'])) {
					if(trim($p['smileyFileName']) == '') $error = $this->modules['Language']->getString('error_no_path_or_url');
					elseif(($p['smileyType'] == SMILEY_TYPE_SMILEY || $p['smileyType'] == SMILEY_TYPE_ADMINSMILEY) && trim($p['smileySynonym']) == '') $error = $this->modules['Language']->getString('error_no_synonym');
					else {
						$this->modules['DB']->query("
							INSERT INTO
								".TBLPFX."smilies
							SET
								smileyType='".$p['smileyType']."',
								smileyFileName='".$p['smileyFileName']."',
								smileyStatus='".$p['smileyStatus']."',
								smileySynonym='".$p['smileySynonym']."'
						");
						$this->modules['Cache']->setSmiliesData();
						$this->modules['Cache']->setPostPicsData();
						$this->modules['Cache']->setAdminSmiliesData();

						Functions::myHeader(INDEXFILE."?action=AdminSmilies&amp;".MYSID);
					}
				}

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('Add_smiley'),INDEXFILE.'?action=addSmiley&amp;'.MYSID);

				$this->modules['Template']->assign(array(
					'p'=>$p,
					'error'=>$error
				));
				$this->modules['Template']->printPage('AdminSmiliesAddSmiley.tpl');
				break;

			case 'editSmiley':
				$smileyID = isset($_GET['smileyID']) ? intval($_GET['smileyID']) : 0;
				if(!$smileyData = FuncSmilies::getSmileyData($smileyID)) die('Cannot load data: smiley');

				$p = Functions::getSGValues($_POST['p'],array('smileyType','smileyFileName','smileySynonym','smileyStatus'),'',Functions::addSlashes($smileyData));
				if(isset($_GET['smileyType'])) $p['smileyType'] = $_GET['smileyType'];

				if(!in_array($p['smileyType'],array(SMILEY_TYPE_SMILEY,SMILEY_TYPE_ADMINSMILEY,SMILEY_TYPE_TPIC))) $p['smileyType'] = SMILEY_TYPE_SMILEY;

				$error = '';

				if(isset($_GET['doit'])) {
					if(trim($p['smileyFileName']) == '') $error = $this->modules['Language']->getString('error_no_path_or_url');
					elseif(($p['smileyType'] == SMILEY_TYPE_SMILEY || $p['smileyType'] == SMILEY_TYPE_ADMINSMILEY) && trim($p['smileySynonym']) == '') $error = $this->modules['Language']->getString('error_no_synonym');
					else {
						$this->modules['DB']->query("
							UPDATE
								".TBLPFX."smilies
							SET
								smileyType='".$p['smileyType']."',
								smileyFileName='".$p['smileyFileName']."',
								smileyStatus='".$p['smileyStatus']."',
								smileySynonym='".$p['smileySynonym']."'
							WHERE
								smileyID='$smileyID'
						");
						$this->modules['Cache']->setSmiliesData();
						$this->modules['Cache']->setPostPicsData();
						$this->modules['Cache']->setAdminSmiliesData();

						Functions::myHeader(INDEXFILE."?action=AdminSmilies&amp;".MYSID);
					}
				}

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('Edit_smiley'),INDEXFILE.'?action=editSmiley&amp;smileyID='.$smileyID.'&amp;'.MYSID);

				$this->modules['Template']->assign(array(
					'p'=>$p,
					'error'=>$error,
					'smileyID'=>$smileyID
				));
				$this->modules['Template']->printPage('AdminSmiliesEditSmiley.tpl');
				break;
		}
	}
}

?>