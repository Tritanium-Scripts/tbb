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
		 * - Strip slashes
		 * - Sets available checkbox variables to 1 if a form was submitted
		 */
		if(get_magic_quotes_gpc() == 1) {
			$_POST = Functions::stripSlashes($_POST);
			$_GET = Functions::stripSlashes($_GET);
			$_COOKIE = Functions::stripSlashes($_COOKIE);
			$_REQUEST = Functions::stripSlashes($_REQUEST);
		}
		if(!isset($_POST['p']) || !is_array($_POST['p'])) $_POST['p'] = array();
		if(!isset($_POST['c']) || !is_array($_POST['c'])) $_POST['c'] = array();
		if(isset($_GET['doit'])) {
			foreach($_POST['c'] AS &$curValue)
				$curValue = 1;
		}

		$action = (isset($_GET['action']) && in_array($_GET['action'],$this->getC('allowedActions'))) ? $_GET['action'] : $this->getC('defaultAction');
		define('ACTION',$action);

		if(Factory::moduleExists('Globals'))
			Factory::singleton('Globals');
		
		$this->modules[$action] = Factory::singleton($action);
		$this->modules[$action]->executeMe();
	}
}

function __autoload($className) {
	require_once('functions/'.$className.'.class.php');
}

?>