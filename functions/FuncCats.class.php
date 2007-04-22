<?php

class FuncCats {
	//*
	//* Fuegt eine neue Kategorie hinzu
	//*
	function addCatData($parentID = 1) {
		$DB = Factory::singleton('DB');

		$DB->query("LOCK TABLES ".TBLPFX."cats WRITE"); // Die Tabelle sperren

		$DB->query("SELECT catL,catR FROM ".TBLPFX."cats WHERE catID='$parentID'"); // Die Daten der uebergeordneten Kategorie laden
		$parentCatData = $DB->fetchArray();

		$DB->query("UPDATE ".TBLPFX."cats SET catL=catL+2 WHERE catL> '".$parentCatData['catR']."'"); // Platz schaffen
		$DB->query("UPDATE ".TBLPFX."cats SET catR=catR+2 WHERE catR>='".$parentCatData['catR']."'"); // und nochmal Platz schaffen
		$DB->query("INSERT INTO ".TBLPFX."cats (catL,catR) VALUES ('".$parentCatData['catR']."','".($parentCatData['catR']+1)."')"); // Daten der neuen Kategorie einfuegen
		$newCatID = $DB->getInsertID();

		$DB->query("UNLOCK TABLES"); // Tabelle entsperren

		return $newCatID;
	}

	public static function moveCat($catID,$targetID) {
		$DB = Factory::singleton('DB');

		$DB->query("LOCK TABLES ".TBLPFX."cats WRITE");

		$DB->query("SELECT catL,catR,(catR-catL+1) AS catSize FROM ".TBLPFX."cats WHERE catID='$catID'");
		$catData = $DB->fetchArray();

		$DB->query("SELECT catL,catR FROM ".TBLPFX."cats WHERE catID='$targetID'");
		$targetCatData = $DB->fetchArray();

		if($targetCatData['catL'] < $catData['catL'] || $targetCatData['catL'] > $catData['catR']) {
			$DB->query("UPDATE ".TBLPFX."cats SET catL=catL*-1, catR=catR*-1 WHERE catL BETWEEN '".$catData['catL']."' AND '".$catData['catR']."'"); // Den gewaehlten Ast ins Negative verschieben

			$DB->query("UPDATE ".TBLPFX."cats SET catL=catL-".$catData['catSize']." WHERE catL>'".$catData['catR']."'"); // Das entstandene Loch beseitigen
			$DB->query("UPDATE ".TBLPFX."cats SET catR=catR-".$catData['catSize']." WHERE catR>'".$catData['catR']."'"); // Das entstandene Loch beseitigen

			if($targetCatData['catR'] > $catData['catR']) $targetCatData['catR'] -= $catData['catSize'];

			$DB->query("UPDATE ".TBLPFX."cats SET catL=catL+".$catData['catSize']." WHERE catL> '".$targetCatData['catR']."'"); // Platz schaffen am neuen Ort fuer den Ast
			$DB->query("UPDATE ".TBLPFX."cats SET catR=catR+".$catData['catSize']." WHERE catR>='".$targetCatData['catR']."'"); // Platz schaffen am neuen Ort fuer den Ast

			$moveSteps = $targetCatData['catR'] - $catData['catL'];

			$DB->query("UPDATE ".TBLPFX."cats SET catL=catL*-1+$moveSteps, catR=catR*-1+$moveSteps WHERE catL BETWEEN  '".($catData['catR']*-1)."' AND '".($catData['catL']*-1)."'"); // Den Ast aus dem Negativen wieder ins Positive verschieben und direkt an die richtige Stelle machen
		}

		$DB->query("UNLOCK TABLES");
	}

	static public function moveCatDown($catID) {
		$DB = Factory::singleton('DB');

		$DB->query("LOCK TABLES ".TBLPFX."cats WRITE");

		$DB->query("SELECT catL,catR,(catR-catL+1) AS catSize FROM ".TBLPFX."cats WHERE catID='$catID'");
		$catData = $DB->fetchArray();

		$DB->query("SELECT catL,catR FROM ".TBLPFX."cats WHERE catL='".($catData['catR']+1)."'");
		if($DB->getAffectedRows() == 0) return FALSE;
		$targetCatData = $DB->fetchArray();

		$moveSteps = $targetCatData['catR'] - $catData['catL'] + 1;

		$DB->query("UPDATE ".TBLPFX."cats SET catL=catL+".$catData['catSize']." WHERE catL>'".$targetCatData['catR']."'");
		$DB->query("UPDATE ".TBLPFX."cats SET catR=catR+".$catData['catSize']." WHERE catR>'".$targetCatData['catR']."'");

		$DB->query("UPDATE ".TBLPFX."cats SET catL=catL+$moveSteps, catR=catR+$moveSteps WHERE catL BETWEEN '".$catData['catL']."' AND '".$catData['catR']."'");

		$DB->query("UPDATE ".TBLPFX."cats SET catL=catL-".$catData['catSize']." WHERE catL>'".$catData['catR']."'");
		$DB->query("UPDATE ".TBLPFX."cats SET catR=catR-".$catData['catSize']." WHERE catR>'".$catData['catR']."'");

		$DB->query("UNLOCK TABLES");
	}

	static public function moveCatUp($catID) {
		$DB = Factory::singleton('DB');

		$DB->query("LOCK TABLES ".TBLPFX."cats WRITE");

		$DB->query("SELECT catL,catR,(catR-catL+1) AS catSize FROM ".TBLPFX."cats WHERE catID='$catID'");
		$catData = $DB->fetchArray();

		$DB->query("SELECT catL,catR FROM ".TBLPFX."cats WHERE catR='".($catData['catL']-1)."'");
		if($DB->getAffectedRows() == 0) return FALSE;
		$targetCatData = $DB->fetchArray();

		$moveSteps = $catData['catR']-$targetCatData['catL']+1;

		$DB->query("UPDATE ".TBLPFX."cats SET catL=catL+".$catData['catSize']." WHERE catL>='".$targetCatData['catL']."'");
		$DB->query("UPDATE ".TBLPFX."cats SET catR=catR+".$catData['catSize']." WHERE catR> '".$targetCatData['catL']."'");

		$DB->query("UPDATE ".TBLPFX."cats SET catL=catL-$moveSteps, catR=catR-$moveSteps WHERE catL BETWEEN '".($catData['catL']+$catData['catSize'])."' AND '".($catData['catR']+$catData['catSize'])."'");

		$DB->query("UPDATE ".TBLPFX."cats SET catL=catL-".$catData['catSize']." WHERE catL>'".($targetCatData['catR']+$catData['catSize'])."'");
		$DB->query("UPDATE ".TBLPFX."cats SET catR=catR-".$catData['catSize']." WHERE catR>'".($targetCatData['catR']+$catData['catSize'])."'");

		$DB->query("UNLOCK TABLES");
	}


	/**
	 * Enter description here...
	 *
	 * @param int $catID
	 * @return array
	 */
	static public function getParentCatData($catID) {
		$DB = Factory::singleton('DB');

		$DB->query("SELECT t1.* FROM ".TBLPFX."cats AS t1, ".TBLPFX."cats AS t2 WHERE t2.catID='$catID' AND t1.catID<>'$catID' AND t2.catL BETWEEN t1.catL AND t1.catR ORDER BY t1.catL DESC LIMIT 1");
		return ($DB->getAffectedRows() == 0) ? FALSE : $DB->fetchArray();
	}


	//*
	//* Bestimmt alle Vaterkategorien einer Kategorie
	//*
	static public function getParentCatsData($catID,$includeSelf = TRUE) {
		$DB = Factory::singleton('DB');

		if($catID == 1) return array();

		$DB->query("SELECT t1.* FROM ".TBLPFX."cats AS t1, ".TBLPFX."cats AS t2 WHERE t2.catID='$catID' AND t1.catID<>1 AND t2.catL BETWEEN t1.catL AND t1.catR ".(!$includeSelf ? "AND t1.catID<>'$catID'" : '')." ORDER BY t1.catL");

		return $DB->raw2Array();
	}


	/**
	 * Loads all categories including their depth and number of children
	 *
	 * @param int $catID
	 * @return array
	 */
	static public function getCatsData($catID = 1) {
		$DB = Factory::singleton('DB');

		if($catID == 1) $DB->query("SELECT t1.*, COUNT(*)-1 AS catDepth, (t1.catR - t1.catL - 1) / 2 AS catChildsCounter FROM (".TBLPFX."cats AS t1, ".TBLPFX."cats AS t2) WHERE t1.catID<>'1' AND t1.catL BETWEEN t2.catL AND t2.catR GROUP BY t1.catL ORDER BY catL");
		else {
			$DB->query("SELECT catL,catR FROM ".TBLPFX."cats WHERE catID='$catID'");
			if($DB->getAffectedRows() != 1) return FALSE;

			list($catL,$catR) = $DB->fetchArray();
			$DB->query("SELECT t1.*, COUNT(*)-1 AS catDepth, (t1.catR - t1.catL - 1) / 2 AS catChildsCounter FROM (".TBLPFX."cats AS t1, ".TBLPFX."cats AS t2) WHERE t1.catID<>'$catID' AND t1.catL BETWEEN '$catL' AND '$catR' AND t1.catL BETWEEN t2.catL AND t2.catR GROUP BY t1.catL ORDER BY catL");
			if($DB->getAffectedRows() == 0) return array();
		}

		return $DB->raw2Array();
	}


	//*
	//* Laedt die Daten einer Kategorie inklusive der Tiefe und der Anzahl der Kinder
	//*
	static public function getCatData($catID) {
		$DB = Factory::singleton('DB');

		$DB->query("SELECT t1.*, COUNT(*)-1 AS catDepth, (t1.catR - t1.catL - 1) / 2 AS catChildsCounter FROM ".TBLPFX."cats AS t1, ".TBLPFX."cats AS t2 WHERE t1.catID='$catID' AND t1.catL BETWEEN t2.catL AND t2.catR GROUP BY t1.catL");
		return ($DB->getAffectedRows() == 0) ? FALSE : $DB->fetchArray();
	}
}

?>