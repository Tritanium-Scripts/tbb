<?php

class Session extends ModuleTemplate {
	protected $requiredModules = array(
		'DB',
		'Template'
	);

	public function initializeMe() {
		session_set_save_handler(
			array($this,'dataHandlerOpen'),
			array($this,'dataHandlerClose'),
			array($this,'dataHandlerRead'),
			array($this,'dataHandlerWrite'),
			array($this,'dataHandlerDestroy'),
			array($this,'dataHandlerGc')
		);
		session_name('sid');
		
		if(stripos($_SERVER['HTTP_USER_AGENT'],'bot') !== FALSE) {
			$mySID = 'sid=0';
			define('MYSID',$mySID);
			$this->modules['Template']->assign('mySID',$mySID);
			return;
		}
		
		session_start();

		if(session_id() == '0') { // Falls eine ungueltige Session-ID existiert...
			session_unset();
			session_regenerate_id();
		}

		$mySID = (SID == '') ? 'sid=0' : 'sid='.session_id(); // Falls die Session-ID per Cookie uebergeben wird ist SID leer, man braucht also auch keine (gueltige) Session-ID per URL zu uebergeben

		define('MYSID',$mySID);
		$this->modules['Template']->assign('mySID',$mySID);
		register_shutdown_function('session_write_close');
	}

	public function dataHandlerOpen($savePath,$sessionName) {
		return TRUE;
	}

	public function dataHandlerClose() {
		return TRUE;
	}

	public function dataHandlerRead($sessionID) {
		$this->modules['DB']->queryParams('SELECT "sessionData" FROM '.TBLPFX.'sessions WHERE "sessionID"=$1',array($sessionID));

		if($this->modules['DB']->numRows() == 1) {
			list($sessionData) = $this->modules['DB']->fetchArray();
			$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'sessions SET "sessionLastUpdate"=NOW() WHERE "sessionID"=$1',array($sessionID));
			return $sessionData;
		}

		$this->modules['DB']->queryParams('INSERT INTO '.TBLPFX.'sessions SET "sessionID"=$1',array($sessionID));
		return '';
	}

	public function dataHandlerWrite($sessionID,$sessionData) {
		$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'sessions SET "sessionData"=$1 WHERE "sessionID"=$2',array($sessionData,$sessionID));
		if($this->modules['DB']->getAffectedRows() == 0)
			return FALSE;

		return TRUE;
	}

	public function dataHandlerDestroy($sessionID) {
		$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'sessions WHERE "sessionID"=$1',array($sessionID));
		return ($this->modules['DB']->getAffectedRows() == 0) ? FALSE : TRUE;
	}

	public function dataHandlerGc($sessionMaxLifeTime) {
		$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'sessions WHERE "sessionLastUpdate"<$1',array($this->modules['DB']->fromUnixTimestamp(time()-$sessionMaxLifeTime)));
		return TRUE;
	}
}

?>