<?php

class Core extends ModuleTemplate {
	public function executeMe() {
		/**
		 * Some basic settings:
		 * - Show all PHP-messages
		 * - Set GET argument seperator to &amp; (for XHTML compatibility)
		 * - Disable slashing of database query results
		 */
		error_reporting(E_ALL);
		set_magic_quotes_runtime(0);
		ini_set('arg_separator.output','&amp;');
		//if(file_exists('install.php')) die('Please delete install.php first!'); // Ueberprueft, ob die Installationsdatei geloescht wurde


		/**
		 * Make indexFile everywhere available
		 */
		define('INDEXFILE',$this->getC('indexFile'));


		/**
		 * Enable output compression
		 */
		if($this->getC('enableOutputCompression')) {
			if(ini_get('zlib.output_compression') != 1 && ini_get('output_handler') != 'ob_gzhandler')
				@ob_start('ob_gzhandler');
		}


		/**
		 * Some GPC-Stuff
		 * - Add slashes
		 * - Sets available checkbox variables to 1 if a form was submitted
		 */
		if(get_magic_quotes_gpc() == 0) {
			$_POST = Functions::addSlashes($_POST);
			$_GET = Functions::addSlashes($_GET);
			$_REQUEST = Functions::addSlashes($_REQUEST);
		}
		if(!isset($_POST['p']) || !is_array($_POST['p'])) $_POST['p'] = array();
		if(!isset($_POST['c']) || !is_array($_POST['c'])) $_POST['c'] = array();
		if(isset($_GET['doit'])) {
			foreach($_POST['c'] AS &$curValue)
				$curValue = 1;
		}

		/**
		 * Initialize global stuff
		 */
		Factory::singleton('Globals');

		/**
		 * Check what to do and execute specified module, if allowed and if existing.
		 * Otherwise execute default module
		 */
		$action = (isset($_GET['action']) && in_array($_GET['action'],$this->getC('allowedActions'))) ? $_GET['action'] : $this->getC('defaultAction');
		$this->modules[$action] = Factory::singleton($action);
		$this->modules[$action]->executeMe();
	}
}

function __autoload($className) {
	require_once('functions/'.$className.'.class.php');
}

?>