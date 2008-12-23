<?php
/**
 * @author Julian Backes <julian@tritanium-scripts.com>
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2003 - 2009, Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package tbb2
 */
class AuthAdmin extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth'
	);

	public function initializeMe() {
		if($this->modules['Auth']->getValue('userIsAdmin') != 1)
			die('Access denied: Administrator rights required');
	}
}