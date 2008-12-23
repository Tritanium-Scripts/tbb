<?php
/**
 * @author Julian Backes <julian@tritanium-scripts.com>
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2003 - 2009, Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package tbb2
 */
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

		foreach($wioData AS &$curWIO) {
			if($curWIO['sessionUserID'] == 0) $curWIO['sessionUserNick'] = $this->modules['Language']->getString('guest');
			$curWIO['_sessionLastLocationText'] = $this->modules['Language']->getString('wio_'.$curWIO['sessionLastLocation']);
		}

		$this->modules['Navbar']->addElement($this->modules['Language']->getString('who_is_online'),INDEXFILE.'?action=WhoIsOnline&amp;'.MYSID);

		$this->modules['Template']->assign(array(
			'wioData'=>$wioData
		));
		$this->modules['Template']->printPage('WhoIsOnline.tpl');
	}
}