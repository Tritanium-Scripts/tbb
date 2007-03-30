<?php

class Core extends ModuleTemplate {
	protected $RequiredModules = array(
		'Auth',
		'Config',
		'DB',
		'Language',
		'Navbar',
		'Template'
	);

	protected $Modules = array();

	public function executeMe() {
		// Ein paar wichtige Dinge
		error_reporting(E_ALL); // Zeigt alle Fehler an
		set_magic_quotes_runtime(0); // Beim Laden von Daten aus der Datenbank sollen keine Backslashes hinzugefuegt werden
		ini_set('arg_separator.output','&amp;'); // Falls session.use_trans_sid ueber .htaccess nicht geaendert werden kann, wird die SID so wenigstens richtig angezeigt
		if(file_exists('install.php')) die('Please delete install.php first!'); // Ueberprueft, ob die Installationsdatei geloescht wurde

		// Konstanten setzen
		$this->setConstants();

		// GZIP-Komprimierung
		if($this->Modules['Config']->getValue('enable_gzip') == 1) { // Falls von der Boardkonfiguration her GZIP verwendet werden soll...
			if(ini_get('zlib.output_compression') != 1 && ini_get('output_handler') != 'ob_gzhandler') // ...und die Seite nicht schon von der PHP-Konfiguration her automatisch komprimiert wird...
				@ob_start('ob_gzhandler'); // ...die Seite komprimieren
		}

		// Veraltete Suchergebnisse loeschen
		if($this->Modules['Config']->getValue('srgc_probability') >= rand(1,100)) // Falls die Wahrscheinlichkeit groesser oder gleich der Zufallszahl ist...
			$this->Modules['DB']->query("DELETE FROM ".TBLPFX."search_results WHERE search_last_access<'".$this->Modules['DB']->fromUnixTimestamp(time()-$this->Modules['Config']->getValue('sr_timeout')*60)."'"); // ...veraltete Suchergebnisse loeschen

		// Smarty Zeugs
		$Modules = &Factory::getInstances();
		$this->Modules['Template']->setDirs('std');
		$this->Modules['Template']->assign('IndexFile',$this->getConfigValue('IndexFile'));
		$this->Modules['Template']->assign_by_ref('Modules',$Modules);

		// Navigationsleiste
		$this->Modules['Navbar']->addElement($this->Modules['Config']->getValue('board_name'),INDEXFILE.'?'.MYSID);

		// Etwas GPC Zeugs
		if(get_magic_quotes_gpc() == 0) { // Falls Werte von "aussen" nicht automatisch mit \ escaped werden (ist sehr wichtig fuer die Sicherheit der Datenbankabfragen)...
			$_POST = Functions::addSlashes($_POST); // ...dies mit den $_POST-Werten tun...
			$_GET = Functions::addSlashes($_GET); // ...und dies mit den $_GET-Werten tun...
			$_REQUEST = Functions::addSlashes($_REQUEST); // ...und den $_REQUEST-Werten tun
		}
		if(isset($_POST['p']) == FALSE || is_array($_POST['p']) == FALSE) $_POST['p'] = array();
		if(isset($_POST['c']) == FALSE || is_array($_POST['c']) == FALSE) $_POST['c'] = array();
		if(isset($_GET['Doit'])) {
			while(list($curKey) = each($_POST['c'])) {
				$_POST['c'][$curKey] = 1;
			}
		}

		// Die Aktion bestimmen und ausfuehren
		$Action = (isset($_GET['Action']) == TRUE && in_array($_GET['Action'],$this->getConfigValue('AllowedActions')) == TRUE) ? $_GET['Action'] : (($this->Modules['Config']->getValue('DefaultAction') != '') ? $this->Modules['Config']->getValue('DefaultAction') : $this->getConfigValue('DefaultAction'));
		$this->Modules[$Action] = Factory::singleton($Action);
		$this->Modules[$Action]->executeMe();
	}

	protected function setConstants() {
		define('INDEXFILE',$this->getConfigValue('IndexFile'));

		define('SMILEY_TYPE_SMILEY',0);
		define('SMILEY_TYPE_TPIC',1);
		define('SMILEY_TYPE_ADMINSMILEY',2);

		define('SUBSCRIPTION_TYPE_FORUM',0);
		define('SUBSCRIPTION_TYPE_TOPIC',1);

		define('AUTH_TYPE_USER',0);
		define('AUTH_TYPE_GROUP',1);

		define('USER_STATUS_INACTIVE',0);

		define('TOPIC_STATUS_OPEN',0);
		define('TOPIC_STATUS_CLOSED',1);

		define('PROFILE_FIELD_TYPE_TEXT',0);
		define('PROFILE_FIELD_TYPE_TEXTAREA',1);
		define('PROFILE_FIELD_TYPE_SELECTSINGLE',2);
		define('PROFILE_FIELD_TYPE_SELECTMULTI',3);
	}
}

?>