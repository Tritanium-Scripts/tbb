<?php

class Functions {
	static function getMicroTime() {
		$mtime = explode(" ",microtime());
		return $mtime[1] + $mtime[0];
	}
}

?>