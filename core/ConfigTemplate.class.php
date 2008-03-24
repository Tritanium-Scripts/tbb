<?php

class ConfigTemplate {
	protected $config = array();

	public function getValue($configName) {
		return $this->config[$configName];
	}
}

?>