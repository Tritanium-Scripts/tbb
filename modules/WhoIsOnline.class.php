<?php

class WhoIsOnline extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'Config',
		'DB',
		'Language',
		'Navbar',
		'Template'
	);

	public function executeMe() {
		if($this->modules['Config']->getValue('enable_wio') != 1) {
			$this->modules['Navbar']->addElement($this->modules['Language']->getString('Function_deactivated'));
			FuncMisc::printMessage('function_deactivated');
			exit;
		}

		$this->modules['Language']->addFile('WhoIsOnline');

        $this->modules['DB']->queryParams('
            SELECT
                t1."sessionUserID",
                t1."sessionLastLocation",
                t1."sessionIsGhost",
                t2."userNick" AS "sessionUserNick"
            FROM '.TBLPFX.'sessions AS t1
            LEFT JOIN '.TBLPFX.'users AS t2 ON t1."sessionUserID"=t2."userID"
            WHERE
                t1."sessionIsGhost"<>1
                AND t1."sessionLastUpdate">$1
            ', array(
                $this->modules['DB']->fromUnixTimestamp(time()-$this->modules['Config']->getValue('wio_timeout')*60)
            ));
		$wioData = $this->modules['DB']->raw2Array();
		$wioDataCounter = count($wioData);

		for($i = 0; $i < $wioDataCounter; $i++) {
			$curWIO = &$wioData[$i];

			if($curWIO['sessionUserID'] == 0) $curWIO['sessionUserNick'] = $this->modules['Language']->getString('Guest');
			$curWIO['_sessionLastLocationText'] = $this->modules['Language']->getString('wio_'.$curWIO['sessionLastLocation']);
		}

		$this->modules['Navbar']->addElement($this->modules['Language']->getString('Who_is_online'),INDEXFILE.'?Action=WhoIsOnline&.'.MYSID);

		$this->modules['Template']->assign(array(
			'wioData'=>$wioData
		));
		$this->modules['Template']->printPage('WhoIsOnline.tpl');
	}
}

?>