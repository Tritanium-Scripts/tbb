<?php

class WhoIsOnline extends ModuleTemplate {
	protected $RequiredModules = array(
		'Auth',
		'Config',
		'DB',
		'Language',
		'Navbar',
		'PageParts',
		'Template'
	);

	public function executeMe() {
		if($this->Modules['Config']->getValue('enable_wio') != 1) {
			// TODO: Richtige Meldung
			//add_navbar_items(array($LNG['Function_deactivated'],''));

			//include_once('pheader.php');
			//show_message($LNG['Function_deactivated'],$LNG['message_function_deactivated']);
			//include_once('ptail.php'); exit;
			die('Diese Funktion ist nicht verfuegbar');
		}

		$this->Modules['Language']->addFile('WhoIsOnline');

		$this->Modules['DB']->query("
			SELECT
				t1.SessionUserID,
				t1.SessionLastLocation,
				t1.SessionIsGhost,
				t2.UserNick AS SessionUserNick
			FROM ".TBLPFX."sessions AS t1
			LEFT JOIN ".TBLPFX."users AS t2 ON t1.SessionUserID=t2.UserID
			WHERE
				t1.SessionIsGhost<>'1'
				AND t1.SessionLastUpdate>'".$this->Modules['DB']->fromUnixTimestamp(time()-$this->Modules['Config']->getValue('wio_timeout')*60)."'
		");
		$WIOData = $this->Modules['DB']->Raw2Array();
		$WIODataCounter = count($WIOData);

		for($i = 0; $i < $WIODataCounter; $i++) {
			$curWIO = &$WIOData[$i];

			if($curWIO['SessionUserID'] == 0) $curWIO['SessionUserNick'] = $this->Modules['Language']->getString('Guest');
			$curWIO['_SessionLastLocationText'] = $this->Modules['Language']->getString('wio_'.$curWIO['SessionLastLocation']);
		}

		$this->Modules['Navbar']->addElement($this->Modules['Language']->getString('Who_is_online'),INDEXFILE.'?Action=WhoIsOnline&.'.MYSID);

		$this->Modules['Template']->assign(array(
			'WIOData'=>$WIOData
		));
		$this->Modules['PageParts']->printPage('WhoIsOnline.tpl');
	}
}

?>