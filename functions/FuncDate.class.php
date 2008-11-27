<?php
class FuncDate {
	public static function splitTime($seconds) {
		$array = array();

		$array['days'] = floor($seconds/86400);
		$seconds -= $array['days']*86400;
		$array['hours'] = floor($seconds/3600);
		$seconds -= $array['hours']*3600;
		$array['minutes'] = ceil($seconds/60);

		return $array;
	}
}
?>