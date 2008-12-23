<?php
/**
 * @author Julian Backes <julian@tritanium-scripts.com>
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2003 - 2009, Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package tbb2
 */
class Config extends ModuleTemplate {
	protected $requiredModules = array(
		'Cache',
		'DB'
	);
	protected $myConfig = array();

	public function initializeMe() {
		$this->myConfig = $this->modules['Cache']->getConfig();
	}

	public function getValue($configName) {
		if(!isset($this->myConfig[$configName]))
			throw new Exception('Unknown config name: '.$configName);
		
		return $this->myConfig[$configName];
	}

	public function updateValue($configName,$configValue,$updateCache = TRUE) {
		$this->modules['DB']->queryParams('
			UPDATE '.TBLPFX.'config SET
				"configValue"=$1
			WHERE
				"configName"=$2
		',array(
			$configValue,
			$configName
		));
		if($updateCache) $this->modules['Cache']->setConfig();
	}
}