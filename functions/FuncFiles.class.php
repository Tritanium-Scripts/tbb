<?php

class FuncFiles {
	public static function getFileData($fileID) {
		$DB = Factory::singleton('DB');
        $DB->queryParams('SELECT * FROM '.TBLPFX.'files WHERE "fileID"=$1', array($fileID));
		return ($DB->numRows() == 1) ? $DB->fetchArray() : FALSE;
	}

	public static function deleteFile($fileID) {
		$DB = Factory::singleton('DB');
		$DB->queryParams('DELETE FROM '.TBLPFX.'posts_files WHERE "fileID"=$1', array($fileID));
		$DB->queryParams('DELETE FROM '.TBLPFX.'files WHERE "fileID"=$1', array($fileID));
	}
}
