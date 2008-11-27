<?php
class FuncAvatars {
	public static function getAvatarData($avatarID) {
		$DB = Factory::singleton('DB');
        $DB->queryParams('SELECT * FROM '.TBLPFX.'avatars WHERE "avatarID"=$1', array($avatarID));
		return ($DB->getAffectedRows() == 0) ? FALSE : $DB->fetchArray();
	}
}
?>