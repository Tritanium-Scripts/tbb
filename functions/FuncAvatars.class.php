<?php

class FuncAvatars {
	public static function getAvatarData($avatarID) {
		$DB = Factory::singleton('DB');
		$DB->query("SELECT * FROM ".TBLPFX."avatars WHERE avatarID='$avatarID'");
		return ($DB->getAffectedRows() == 0) ? FALSE : $DB->fetchArray();
	}
}

?>