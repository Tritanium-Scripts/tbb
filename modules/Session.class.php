<?php

class Session extends ModuleTemplate {
	protected $requiredModules = array(
		'Template',
		'DB'
	);

	function initializeMe() {
		session_set_save_handler( // Session-Management auf Datenbank umstellen
			array(&$this,'DataHandlerOpen'),
			array(&$this,'DataHandlerClose'),
			array(&$this,'DataHandlerRead'),
			array(&$this,'DataHandlerWrite'),
			array(&$this,'DataHandlerDestroy'),
			array(&$this,'DataHandlerGc')
		);
		session_name('sid'); // Name der Session zu "sid" aendern
		session_start(); // Session starten

		if(session_id() == '0') { // Falls eine ungueltige Session-ID existiert...
			session_unset();
			session_regenerate_id();
		}

		$MySID = (SID == '') ? 'sid=0' : 'sid='.session_id(); // Falls die Session-ID per Cookie uebergeben wird ist SID leer, man braucht also auch keine (gueltige) Session-ID per URL zu uebergeben

		define('MYSID',$MySID);
		$this->modules['Template']->assign('MySID',$MySID);
	}

	public function DataHandlerOpen($SavePath,$SessionName) {
		return TRUE;
	}

	public function DataHandlerClose() {
		return TRUE;
	}

	public function DataHandlerRead($SessionID) {
		$this->modules['DB']->query("SELECT SessionData FROM ".TBLPFX."sessions WHERE SessionID='$SessionID'");
		if($this->modules['DB']->getAffectedRows() == 0) {
			$this->modules['DB']->query("INSERT INTO ".TBLPFX."sessions (SessionID) VALUES ('$SessionID')");
			return "";
		}

		list($SessionData) = $this->modules['DB']->fetchArray();
		return $SessionData;
	}

	public function DataHandlerWrite($SessionID,$SessionData) {
		$SessionData = $this->modules['DB']->escapeString($SessionData);

		$this->modules['DB']->query("UPDATE ".TBLPFX."sessions SET SessionData='$SessionData', SessionLastUpdate=NOW() WHERE SessionID='$SessionID'");
		if($this->modules['DB']->getAffectedRows() == 0)
			return FALSE;

		return TRUE;
	}

	function DataHandlerDestroy($SessionID) {
		$this->modules['DB']->query("DELETE FROM ".TBLPFX."sessions WHERE SessionID='$SessionID'");
		return ($this->modules['DB']->getAffectedRows() == 0) ? FALSE : TRUE;
	}

	function DataHandlerGc($SessionMaxLifeTime) {
		$this->modules['DB']->query("DELETE FROM ".TBLPFX."sessions WHERE SessionLastUpdate<'".$this->modules['DB']->fromUnixTimestamp(time()-$SessionMaxLifeTime)."'");

		return TRUE;
	}

	public function __destruct() {
		session_write_close();
	}
}

?>