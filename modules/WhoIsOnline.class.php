<?php

class WhoIsOnline extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'Config',
		'DB',
		'Language',
		'Navbar',
		'PageParts',
		'Template'
	);

	public function executeMe() {
		if($this->modules['Config']->getValue('enable_wio') != 1) {
			// TODO: Richtige Meldung
			//add_navbar_items(array($lNG['Function_deactivated'],''));

			//include_once('pheader.php');
			//show_message($lNG['Function_deactivated'],$lNG['message_function_deactivated']);
			//include_once('ptail.php'); exit;
			die('Diese Funktion ist nicht verfuegbar');
		}

		$this->modules['Language']->addFile('WhoIsOnline');

		$this->modules['DB']->query("
			SELECT
				t1.SessionUserID,
				t1.SessionLastLocation,
				t1.SessionIsGhost,
				t2.UserNick AS SessionUserNick
			FROM ".TBLPFX."sessions AS t1
			LEFT JOIN ".TBLPFX."users AS t2 ON t1.SessionUserID=t2.UserID
			WHERE
				t1.SessionIsGhost<>'1'
				AND t1.SessionLastUpdate>'".$this->modules['DB']->fromUnixTimestamp(time()-$this->modules['Config']->getValue('wio_timeout')*60)."'
		");
		$wIOData = $this->modules['DB']->raw2Array();
		$wIODataCounter = count($wIOData);

		for($i = 0; $i < $wIODataCounter; $i++) {
			$curWIO = &$wIOData[$i];

			if($curWIO['SessionUserID'] == 0) $curWIO['SessionUserNick'] = $this->modules['Language']->getString('Guest');
			$curWIO['_SessionLastLocationText'] = $this->modules['Language']->getString('wio_'.$curWIO['SessionLastLocation']);
		}

		$this->modules['Navbar']->addElement($this->modules['Language']->getString('Who_is_online'),INDEXFILE.'?Action=WhoIsOnline&.'.MYSID);

		$this->modules['Template']->assign(array(
			'WIOData'=>$wIOData
		));
		$this->modules['PageParts']->printPage('WhoIsOnline.tpl');
	}
}

?>