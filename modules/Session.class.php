<?php

class Session extends ModuleTemplate {
	protected $requiredModules = array(
		'DB',
		'Template'
	);

	public function initializeMe() {
		session_set_save_handler( // Session-Management auf Datenbank umstellen
			array($this,'DataHandlerOpen'),
			array($this,'DataHandlerClose'),
			array($this,'DataHandlerRead'),
			array($this,'DataHandlerWrite'),
			array($this,'DataHandlerDestroy'),
			array($this,'DataHandlerGc')
		);
		session_name('sid'); // Name der Session zu "sid" aendern
		session_start(); // Session starten

		if(session_id() == '0') { // Falls eine ungueltige Session-ID existiert...
			session_unset();
			session_regenerate_id();
		}

		$mySID = (SID == '') ? 'sid=0' : 'sid='.session_id(); // Falls die Session-ID per Cookie uebergeben wird ist SID leer, man braucht also auch keine (gueltige) Session-ID per URL zu uebergeben

		define('MYSID',$mySID);
		$this->modules['Template']->assign('mySID',$mySID);
		register_shutdown_function('session_write_close');
	}

	public function DataHandlerOpen($savePath,$sessionName) {
		return TRUE;
	}

	public function DataHandlerClose() {
		return TRUE;
	}

	public function DataHandlerRead($sessionID) {
		$this->modules['DB']->query("SELECT SessionData FROM ".TBLPFX."sessions WHERE SessionID='$sessionID'");
		if($this->modules['DB']->getAffectedRows() == 0) {
			$this->modules['DB']->query("INSERT INTO ".TBLPFX."sessions (SessionID) VALUES ('$sessionID')");
			return "";
		}

		list($sessionData) = $this->modules['DB']->fetchArray();
		return $sessionData;
	}

	public function DataHandlerWrite($sessionID,$sessionData) {
		$sessionData = $this->modules['DB']->escapeString($sessionData);

		$this->modules['DB']->query("UPDATE ".TBLPFX."sessions SET SessionData='$sessionData', SessionLastUpdate=NOW() WHERE SessionID='$sessionID'");
		if($this->modules['DB']->getAffectedRows() == 0)
			return FALSE;

		return TRUE;
	}

	public function DataHandlerDestroy($sessionID) {
		$this->modules['DB']->query("DELETE FROM ".TBLPFX."sessions WHERE SessionID='$sessionID'");
		return ($this->modules['DB']->getAffectedRows() == 0) ? FALSE : TRUE;
	}

	public function DataHandlerGc($sessionMaxLifeTime) {
		$this->modules['DB']->query("DELETE FROM ".TBLPFX."sessions WHERE SessionLastUpdate<'".$this->modules['DB']->fromUnixTimestamp(time()-$sessionMaxLifeTime)."'");

		return TRUE;
	}
}

?>