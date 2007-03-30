<?php

class ConfigTemplate {
	protected $Config = array();

	public function getValue($ConfigName) {
		return $this->Config[$ConfigName];
	}
}

?>