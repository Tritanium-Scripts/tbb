<?php

class AdminRanks extends ModuleTemplate {
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
		$this->modules['Language']->addFile('AdminRanks');
		$this->modules['Navbar']->addElement($this->modules['Language']->getString('Manage_ranks'),INDEXFILE.'?action=AdminRanks&amp;'.MYSID);

		switch(@$_GET['mode']) {
			default:
				$this->modules['DB']->query("SELECT * FROM ".TBLPFX."ranks WHERE rankType='0' ORDER BY rankPosts");
				$normalRanksData = $this->modules['DB']->raw2Array();

				foreach($normalRanksData AS &$curRank) {
					$curRankGfx = '';
					if($curRank['rankGfx'] != '') {
						$curRankGfx = explode(';',$curRank['rankGfx']);
						foreach($curRankGfx AS &$curGfx)
							$curGfx = '<img src="'.$curGfx.'" alt="" border="0"/>';

						$curRankGfx = implode('',$curRankGfx);
					}
					$curRank['_rankGfx'] = $curRankGfx;
				}


				$this->modules['DB']->query("SELECT * FROM ".TBLPFX."ranks WHERE rankType='1' ORDER BY rankName");
				$specialRanksData = $this->modules['DB']->raw2Array();

				foreach($specialRanksData AS &$curRank) {
					$curRankGfx = '';
					if($curRank['rankGfx'] != '') {
						$curRankGfx = explode(';',$curRank['rankGfx']);
						foreach($curRankGfx AS &$curGfx)
							$curGfx = '<img src="'.$curGfx.'" alt="" border="0"/>';
						$curRankGfx = implode('',$curRankGfx);
					}
					$curRank['_rankGfx'] = $curRankGfx;
				}

				$this->modules['Template']->assign(array(
					'normalRanksData'=>$normalRanksData,
					'specialRanksData'=>$specialRanksData
				));
				$this->modules['PageParts']->printPage('AdminRanks.tpl');
				break;

			case 'AddRank':
				$p = Functions::getSGValues($_POST['p'],array('rankPosts','rankName','rankType','rankGfx'),'');

				if(!in_array($p['rankType'],array(0,1))) $p['rankType'] = 0;

				$error = '';

				if(isset($_GET['doit'])) {
					if(trim($p['rankName']) == '') $error = $this->modules['Language']->getString('error_no_rank_name');
					else {
						$p['rankGfx'] = explode(';',$p['rankGfx']);
						foreach($p['rankGfx'] AS &$curGfx)
							$curGfx = trim($curGfx);
						$p['rankGfx'] = implode(';',$p['rankGfx']);

						if($p['rankType'] == 1)
							$p['rankPosts'] = 0;

						$this->modules['DB']->query("
							INSERT INTO
								".TBLPFX."ranks
							SET
								rankType='".$p['rankType']."',
								rankName='".$p['rankName']."',
								rankGfx='".$p['rankGfx']."',
								rankPosts='".$p['rankPosts']."'
						");
						$this->modules['Cache']->setRanksData();

						Functions::myHeader(INDEXFILE.'?action=AdminRanks&'.MYSID);
					}
				}

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('Add_rank'),INDEXFILE.'?action=AdminRanks&amp;mode=AddRank'.MYSID);

				$this->modules['Template']->assign(array(
					'p'=>$p,
					'error'=>$error
				));
				$this->modules['PageParts']->printPage('AdminRanksAddRank.tpl');
				break;

			case 'EditRank':
				$rankID = isset($_GET['rankID']) ? intval($_GET['rankID']) : 0;
				if(!$rankData = FuncRanks::getRankData($rankID)) die('Cannot load data: rank');

				$p = Functions::getSGValues($_POST['p'],array('rankPosts','rankName','rankType','rankGfx'),'',Functions::addSlashes($rankData));

				if(!in_array($p['rankType'],array(0,1))) $p['rankType'] = 0;

				$error = '';

				if(isset($_GET['doit'])) {
					if(trim($p['rankName']) == '') $error = $this->modules['Language']->getString('error_no_rank_name');
					else {
						$p['rankGfx'] = explode(';',$p['rankGfx']);
						foreach($p['rankGfx'] AS &$curGfx)
							$curGfx = trim($curGfx);
						$p['rankGfx'] = implode(';',$p['rankGfx']);

						if($p['rankType'] == 1)
							$p['rankPosts'] = 0;

						$this->modules['DB']->query("
							UPDATE
								".TBLPFX."ranks
							SET
								rankType='".$p['rankType']."',
								rankName='".$p['rankName']."',
								rankGfx='".$p['rankGfx']."',
								rankPosts='".$p['rankPosts']."'
							WHERE
								rankID='$rankID'
						");
						$this->modules['Cache']->setRanksData();

						Functions::myHeader(INDEXFILE.'?action=AdminRanks&'.MYSID);
					}
				}

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('Edit_rank'),INDEXFILE.'?action=AdminRanks&amp;mode=EditRank&amp;rankID='.$rankID.'&amp;'.MYSID);

				$this->modules['Template']->assign(array(
					'p'=>$p,
					'error'=>$error,
					'rankID'=>$rankID
				));
				$this->modules['PageParts']->printPage('AdminRanksEditRank.tpl');
				break;

			case 'DeleteRank':
				$rankID = isset($_GET['rankID']) ? $_GET['rankID'] : 0;

				if($rankData = FuncRanks::getRankData($rankID)) {
					$this->modules['DB']->query("DELETE FROM ".TBLPFX."ranks WHERE rankID='$rankID'");
					if($rankData['rankType'] == 1)
						$this->modules['DB']->query("UPDATE ".TBLPFX."users SET rankID='0' WHERE rankID='$rankID'");

					$this->modules['Cache']->setRanksData();
				}

				Functions::myHeader(INDEXFILE.'?action=AdminRanks&'.MYSID);
				break;
		}
	}
}

?>