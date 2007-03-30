<?php

class ExceptionHandler {
	private $Smarty = NULL;

	function __construct(&$Smarty) {
		$this->Smarty = &$Smarty;
	}

	function catchException($Exception) {
		$this->Smarty->assign('error',$Exception->getMessage());
		$this->Smarty->display('FatalError.tpl');
	}
}

?>