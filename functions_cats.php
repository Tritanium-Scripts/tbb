<?php
/**
*
* Tritanium Bulletin Board 2 - functions_cats.php
* version #2005-01-20-20-45-11
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

/*
*
* Hier wird das sogenannte "Nested Set"-Modell zur Beschreibung der Baumstruktur verwendet.
* Wer es verstehen will, dem empfehle ich die folgenden zwei Links zum Einstieg:
*
* http://ffm.junetz.de/members/reeg/DSP/node10.html#SECTION04344000000000000000
* http://www.develnet.org/36.html
*
* Ansonsten hilft nur viel nachdenken und ausprobieren
*
*/


//*
//* Fuegt eine neue Kategorie hinzu
//*
function cats_add_cat_data($parent_id = 1) {
	global $DB;

	$DB->query("LOCK TABLES ".TBLPFX."cats WRITE"); // Die Tabelle sperren

	$DB->query("SELECT cat_l,cat_r FROM ".TBLPFX."cats WHERE cat_id='$parent_id'"); // Die Daten der uebergeordneten Kategorie laden
	$parent_cat_data = $DB->fetch_array();

	$DB->query("UPDATE ".TBLPFX."cats SET cat_l=cat_l+2 WHERE cat_l> '".$parent_cat_data['cat_r']."'"); // Platz schaffen
	$DB->query("UPDATE ".TBLPFX."cats SET cat_r=cat_r+2 WHERE cat_r>='".$parent_cat_data['cat_r']."'"); // und nochmal Platz schaffen
	$DB->query("INSERT INTO ".TBLPFX."cats (cat_l,cat_r) VALUES ('".$parent_cat_data['cat_r']."','".($parent_cat_data['cat_r']+1)."')"); // Daten der neuen Kategorie einfuegen
	$new_cat_id = $DB->insert_id;

	$DB->query("UNLOCK TABLES"); // Tabelle entsperren

	return $new_cat_id;
}


//*
//* Verschiebt eine Kategorie, d.h. weisst ihr eine neue Elternkategorie zu
//*
function cats_move_cat($cat_id,$target_id) {
	global $DB;

	$DB->query("LOCK TABLES ".TBLPFX."cats WRITE");

	$DB->query("SELECT cat_l,cat_r FROM ".TBLPFX."cats WHERE cat_id='$cat_id'");
	$cat_data = $DB->fetch_array();

	$cat_size = $cat_data['cat_r'] - $cat_data['cat_l'] + 1; // Die Groesse des Astes

	$DB->query("SELECT cat_l,cat_r FROM ".TBLPFX."cats WHERE cat_id='$target_id'");
	$target_cat_data = $DB->fetch_array();

	if($target_cat_data['cat_l'] < $cat_data['cat_l'] || $target_cat_data['cat_l'] > $cat_data['cat_r']) {
		$DB->query("UPDATE ".TBLPFX."cats SET cat_l=cat_l*-1, cat_r=cat_r*-1 WHERE cat_l BETWEEN '".$cat_data['cat_l']."' AND '".$cat_data['cat_r']."'"); // Den gewaehlten Ast ins Negative verschieben

		$DB->query("UPDATE ".TBLPFX."cats SET cat_l=cat_l-$cat_size WHERE cat_l>'".$cat_data['cat_r']."'"); // Das entstandene Loch beseitigen
		$DB->query("UPDATE ".TBLPFX."cats SET cat_r=cat_r-$cat_size WHERE cat_r>'".$cat_data['cat_r']."'"); // Das entstandene Loch beseitigen

		if($target_cat_data['cat_r'] > $cat_data['cat_r']) $target_cat_data['cat_r'] -= $cat_size;

		$DB->query("UPDATE ".TBLPFX."cats SET cat_l=cat_l+$cat_size WHERE cat_l> '".$target_cat_data['cat_r']."'"); // Platz schaffen am neuen Ort fuer den Ast
		$DB->query("UPDATE ".TBLPFX."cats SET cat_r=cat_r+$cat_size WHERE cat_r>='".$target_cat_data['cat_r']."'"); // Platz schaffen am neuen Ort fuer den Ast

		$move_steps = $target_cat_data['cat_r'] - $cat_data['cat_l'];

		$DB->query("UPDATE ".TBLPFX."cats SET cat_l=cat_l*-1+$move_steps, cat_r=cat_r*-1+$move_steps WHERE cat_l BETWEEN  '".($cat_data['cat_r']*-1)."' AND '".($cat_data['cat_l']*-1)."'"); // Den Ast aus dem Negativen wieder ins Positive verschieben und direkt an die richtige Stelle machen
	}

	$DB->query("UNLOCK TABLES");
}


//*
//* Verschiebt eine Kategorie nach unten
//*
function cats_move_cat_down($cat_id) {
	global $DB;

	$DB->query("LOCK TABLES ".TBLPFX."cats WRITE");

	$DB->query("SELECT cat_l,cat_r FROM ".TBLPFX."cats WHERE cat_id='$cat_id'");
	$cat_data = $DB->fetch_array();

	$cat_size = $cat_data['cat_r'] - $cat_data['cat_l'] + 1; // Die Groesse des Astes

	$DB->query("SELECT cat_l,cat_r FROM ".TBLPFX."cats WHERE cat_l='".($cat_data['cat_r']+1)."'");
	if($DB->affected_rows == 0) return FALSE;
	$target_cat_data = $DB->fetch_array();

	$move_steps = $target_cat_data['cat_r'] - $cat_data['cat_l'] + 1;

	$DB->query("UPDATE ".TBLPFX."cats SET cat_l=cat_l+$cat_size WHERE cat_l>'".$target_cat_data['cat_r']."'");
	$DB->query("UPDATE ".TBLPFX."cats SET cat_r=cat_r+$cat_size WHERE cat_r>'".$target_cat_data['cat_r']."'");

	$DB->query("UPDATE ".TBLPFX."cats SET cat_l=cat_l+$move_steps, cat_r=cat_r+$move_steps WHERE cat_l BETWEEN '".$cat_data['cat_l']."' AND '".$cat_data['cat_r']."'");

	$DB->query("UPDATE ".TBLPFX."cats SET cat_l=cat_l-$cat_size WHERE cat_l>'".$cat_data['cat_r']."'");
	$DB->query("UPDATE ".TBLPFX."cats SET cat_r=cat_r-$cat_size WHERE cat_r>'".$cat_data['cat_r']."'");

	$DB->query("UNLOCK TABLES");
}


//*
//* Verschiebt eine Kategorie nach oben
//*
function cats_move_cat_up($cat_id) {
	global $DB;

	$DB->query("LOCK TABLES ".TBLPFX."cats WRITE");



	$DB->query("SELECT cat_l,cat_r FROM ".TBLPFX."cats WHERE cat_id='$cat_id'");
	$cat_data = $DB->fetch_array();

	$cat_size = $cat_data['cat_r'] - $cat_data['cat_l'] + 1; // Die Groesse des Astes

	$DB->query("SELECT cat_l,cat_r FROM ".TBLPFX."cats WHERE cat_r='".($cat_data['cat_l']-1)."'");
	if($DB->affected_rows == 0) return FALSE;
	$target_cat_data = $DB->fetch_array();

	$move_steps = $cat_data['cat_r']-$target_cat_data['cat_l']+1;

	$DB->query("UPDATE ".TBLPFX."cats SET cat_l=cat_l+$cat_size WHERE cat_l>='".$target_cat_data['cat_l']."'");
	$DB->query("UPDATE ".TBLPFX."cats SET cat_r=cat_r+$cat_size WHERE cat_r> '".$target_cat_data['cat_l']."'");

	$DB->query("UPDATE ".TBLPFX."cats SET cat_l=cat_l-$move_steps, cat_r=cat_r-$move_steps WHERE cat_l BETWEEN '".($cat_data['cat_l']+$cat_size)."' AND '".($cat_data['cat_r']+$cat_size)."'");

	$DB->query("UPDATE ".TBLPFX."cats SET cat_l=cat_l-$cat_size WHERE cat_l>'".($target_cat_data['cat_r']+$cat_size)."'");
	$DB->query("UPDATE ".TBLPFX."cats SET cat_r=cat_r-$cat_size WHERE cat_r>'".($target_cat_data['cat_r']+$cat_size)."'");

	$DB->query("UNLOCK TABLES");
}


//*
//* Bestimmt die Vaterkategorie einer Kategorie
//*
function cats_get_parent_cat_data($cat_id) {
	global $DB;

	$DB->query("SELECT t1.* FROM ".TBLPFX."cats AS t1, ".TBLPFX."cats AS t2 WHERE t2.cat_id='$cat_id' AND t1.cat_id<>'$cat_id' AND t2.cat_l BETWEEN t1.cat_l AND t1.cat_r ORDER BY t1.cat_l DESC LIMIT 1");
	return ($DB->affected_rows == 0) ? FALSE : $DB->fetch_array();
}


//*
//* Bestimmt alle Vaterkategorien einer Kategorie
//*
function cats_get_parent_cats_data($cat_id,$self = TRUE) {
	global $DB;

	$x = ($self == FALSE) ? "AND t1.cat_id<>'$cat_id'" : '';

	if(!$DB->query("SELECT t1.* FROM ".TBLPFX."cats AS t1, ".TBLPFX."cats AS t2 WHERE t2.cat_id='$cat_id' AND t1.cat_id<>1 AND t2.cat_l BETWEEN t1.cat_l AND t1.cat_r $x ORDER BY t1.cat_l"))
		return FALSE;

	return $DB->raw2array();
}


//*
//* Laedt alle Kategorien inklusive der Tiefe und der Anzahl der Kinder
//*
function cats_get_cats_data($cat_id = 1) {
	global $DB;

	if($cat_id == 1) {
		$DB->query("SELECT t1.*, COUNT(*)-1 AS cat_depth, (t1.cat_r - t1.cat_l - 1) / 2 AS cat_childs_counter FROM ".TBLPFX."cats AS t1, ".TBLPFX."cats AS t2 WHERE t1.cat_id<>'1' AND t1.cat_l BETWEEN t2.cat_l AND t2.cat_r GROUP BY t1.cat_l ORDER BY cat_l");
	}
	else {
		$DB->query("SELECT cat_l,cat_r FROM ".TBLPFX."cats WHERE cat_id='$cat_id'");
		if($DB->affected_rows != 1) return FALSE;

		list($cat_l,$cat_r) = $DB->fetch_array();
		$DB->query("SELECT t1.*, COUNT(*)-1 AS cat_depth, (t1.cat_r - t1.cat_l - 1) / 2 AS cat_childs_counter FROM ".TBLPFX."cats AS t1, ".TBLPFX."cats AS t2 WHERE t1.cat_id<>'1' AND t1.cat_l BETWEEN '$cat_l' AND '$cat_r' AND t1.cat_l BETWEEN t2.cat_l AND t2.cat_r GROUP BY t1.cat_l ORDER BY cat_l");
	}


	return $DB->raw2array();
}


//*
//* Laedt die Daten einer Kategorie inklusive der Tiefe und der Anzahl der Kinder
//*
function cats_get_cat_data($cat_id) {
	global $DB;

	$DB->query("SELECT t1.*, COUNT(*)-1 AS cat_depth, (t1.cat_r - t1.cat_l - 1) / 2 AS cat_childs_counter FROM ".TBLPFX."cats AS t1, ".TBLPFX."cats AS t2 WHERE t1.cat_id='$cat_id' AND t1.cat_l BETWEEN t2.cat_l AND t2.cat_r GROUP BY t1.cat_l");
	return ($DB->affected_rows == 0) ? FALSE : $DB->fetch_array();
}


?>