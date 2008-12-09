<?php
class Groups extends ModuleTemplate {
	protected $requiredModules = array(
	);
	
	public function executeMe() {
		FuncMisc::printMessage('function_deactivated');
		exit;
	}
}