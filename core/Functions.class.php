<?php

class Functions {
	protected static $HTMLCharsSearch = array('/&(?!\#[0-9]+;)/','/</','/>/','/"/');
	protected static $HTMLCharsReplace = array('&amp;','&lt;','&gt;','&quot;');

	protected static $XMLCharsSearch = array('/</','/>/','/&/','/"/','/\'/');
	protected static $XMLCharsReplace = array('&lt;','&gt;','&amp;','&quot;','&apos;');

	public static function getMicroTime() {
		$mtime = explode(" ",microtime());
		return $mtime[1] + $mtime[0];
	}

	public static function myHeader($Link) {
		header("Location: ".$Link);
		exit;
	}

	public static function myCrypt($String) {
		return md5($String);
	}

	public static function verifyUserName($UserName) {
		return (strlen($UserName) <= 15 && preg_match('/^[a-z_]{1}[a-z0-9_]{1,}$/si',$UserName));
	}

	public static function verifyEmailAddress($emailAddress) {
		return preg_match('/^[\.0-9a-z_-]{1,}@[\.0-9a-z-]{1,}\.[a-z]{1,}$/si',$emailAddress);
	}

	public static function unifyUserName($UserName) {
		$DB = Factory::singleton('DB');

		$DB->query("SELECT UserID FROM ".TBLPFX."users WHERE UserNick='$UserName' LIMIT 1");
		return ($DB->getAffectedRows() != 1);
	}

	public static function unifyEmailAddress($emailAddress) {
		$DB = Factory::singleton('DB');

		$DB->query("SELECT userID FROM ".TBLPFX."users WHERE userEmailAddress='$emailAddress' LIMIT 1");
		return ($DB->getAffectedRows() != 1);
	}

	public static function getRandomString($length,$onlyAlphaNumeric = FALSE) {
		if(!$onlyAlphaNumeric) {
			$string = '';
			for($i = 0; $i < $length; $i++)
				$string .= chr(rand(33,126));
			return $string;
		}
		else
			return substr(md5(uniqid(rand(),1)),0,$length);
	}

	public static function getSaltedHash($Value,$Salt) {
		return hash('sha512',hash('sha512',$Value).$Salt);
	}

	public static function getSGValues(&$SGVar,array $KeysArray,$stdValue = '',array $stdValues = array()) {
		$ValuesArray = array();

		while(list(,$curValue) = each($KeysArray)) {
			if(is_array($curValue)) $ValuesArray[$curValue[0]] = isset($SGVar[$curValue[0]]) ? $SGVar[$curValue[0]] : (isset($stdValues[$curValue[0]]) ? $stdValues[$curValue[0]] : $curValue[1]);
			else $ValuesArray[$curValue] = isset($SGVar[$curValue]) ? $SGVar[$curValue] : (isset($stdValues[$curValue]) ? $stdValues[$curValue] : $stdValue);
		}

		return $ValuesArray;
	}

	public static function HTMLSpecialChars($Value) {
	   if(is_array($Value) == TRUE) $Value = array_map(array('Functions','HTMLSpecialChars'),$Value);
	   else $Value =  preg_replace(self::$HTMLCharsSearch,self::$HTMLCharsReplace,$Value);

	   return $Value;
	}

	public static function XMLEscapeString($Value) {
	   if(is_array($Value) == TRUE) $Value = array_map(array('Functions','XMLEscapeString'),$Value);
	   else $Value =  preg_replace(self::$XMLCharsSearch,self::$XMLCharsReplace,$Value);

	   return $Value;
	}

	public static function toDateTime($Timestamp) {
		$Lng = Factory::singleton('Language');
		return date($Lng->getString('date_time_format'),$Timestamp);
	}

	public static function toTime($Timestamp) {
		$Lng = Factory::singleton('Language');
		return date($Lng->getString('time_format'),$Timestamp);
	}

	public static function addHttp($text) {
		if(substr($text,0,7) != "http://") $text = "http://".$text;
		return $text;
	}

	public static function br2nl($text) {
		$text = str_replace('<br>',"\n",$text);
		$text = str_replace('<br/>',"\n",$text);
		return str_replace('<br />',"\n",$text);
	}

	public static function myMail($From,$To,$Subject,$Message,$AdditionalHeaders = '') {
		$AdditionalHeaders .= "From: $From\r\n".
		"Reply-To: $From\r\n".
		"Content-type: text/plain; charset=UTF-8";

		return mail($To,$Subject,$Message,$AdditionalHeaders);
	}

	public static function getPostData($PostID) {
		$DB = Factory::singleton('DB');
		$DB->query("SELECT * FROM ".TBLPFX."posts WHERE PostID='$PostID'");
		return ($DB->getAffectedRows() == 1) ? $DB->fetchArray() : FALSE;
	}

	public static function getUsersCounter() {
		$DB = Factory::singleton('DB');
		$DB->query("SELECT COUNT(*) FROM ".TBLPFX."users");
		list($Counter) = $DB->fetchArray();
		return $Counter;
	}

	public static function getPostsCounter($TopicID = 0) {
		$DB = Factory::singleton('DB');

		if($TopicID == 0) $DB->query("SELECT COUNT(*) FROM ".TBLPFX."posts");
		else $DB->query("SELECT COUNT(*) FROM ".TBLPFX."posts WHERE TopicID='$TopicID'");

		list($Counter) = $DB->fetchArray();
		return $Counter;
	}

	public static function getTopicsCounter($ForumID = 0) {
		$DB = Factory::singleton('DB');
		if($ForumID == 0) $DB->query("SELECT COUNT(*) FROM ".TBLPFX."topics");
		else $DB->query("SELECT COUNT(*) FROM ".TBLPFX."topics WHERE ForumID='$ForumID'");
		list($Counter) = $DB->fetchArray();
		return $Counter;
	}

	public static function getUserID($UserID) {
		$DB = Factory::singleton('DB');

		if(!preg_match('/^[0-9]{1,}$/si',$UserID))
			$DB->query("SELECT UserID FROM ".TBLPFX."users WHERE UserNick='$UserID' LIMIT 1");
		else $DB->query("SELECT UserID FROM ".TBLPFX."users WHERE UserID='$UserID' LIMIT 1");

		if($DB->getAffectedRows() == 1) {
			list($UserID) = $DB->fetchArray();
			return $UserID;
		}

		return FALSE;
	}

	public static function addSlashes($Value) {
	   if(is_array($Value) == TRUE) $Value = array_map(array('Functions','addSlashes'),$Value);
	   else $Value = addslashes($Value);

	   return $Value;
	}

	public static function getSubscriptionStatus($SubscriptionType,$UserID,$SubscriptionID) {
		$DB = Factory::singleton('DB');

		if($SubscriptionType == SUBSCRIPTION_TYPE_TOPIC) $DB->query("SELECT * FROM ".TBLPFX."topics_subscriptions WHERE UserID='$UserID' AND TopicID='$SubscriptionID'");
		else $DB->query("SELECT * FROM ".TBLPFX."forums_subscriptions WHERE UserID='$UserID' AND $ForumID='$SubscriptionID' LIMIT 1");

		return ($DB->getAffectedRows() == 1) ? TRUE : FALSE;
	}

	public static function createPageListing($entriesCounter,$entriesPerPage,&$page,$link) {
		$Language = Factory::singleton('Language');

		$pagesCounter = ceil($entriesCounter/$entriesPerPage);

		if($pagesCounter == 0) $page = 1;
		elseif($page == 'last' || $page > $pagesCounter) $page = $pagesCounter;

		$pageListing = array();

		$pre = $suf = '';

		if($pagesCounter > 0) {
			if($pagesCounter > 5) {
				if($page > 2 && $page < $pagesCounter-2) $pageListing = array($page-2,$page-1,$page,$page+1,$page+2);
				elseif($page <= 2) $pageListing = array(1,2,3,4,5);
				elseif($page >= $pagesCounter-2) $pageListing = array($pagesCounter-4,$pagesCounter-3,$pagesCounter-2,$pagesCounter-1,$pagesCounter);
			}
			else {
				for($i = 1; $i < $pagesCounter+1; $i++)
					$pageListing[] = $i;
			}
		}
		else $pageListing[] = 1;
		for($i = 0; $i < count($pageListing); $i++) {
			if($pageListing[$i] != $page) $pageListing[$i] = sprintf($link,$pageListing[$i],$pageListing[$i]);
		}

		if($page > 1) $pre = sprintf($link,1,$Language->getString('First_page')).'&nbsp;'.sprintf($link,$page-1,$Language->getString('Previous_page')).'&nbsp;&nbsp;';
		if($page < $pagesCounter) $suf = '&nbsp;&nbsp;'.sprintf($link,$page+1,$Language->getString('Next_page')).'&nbsp;'.sprintf($link,'last',$Language->getString('Last_page'));

		return sprintf($Language->getString('Pages'),$pagesCounter,$pre.implode(' | ',$pageListing).$suf);
	}

	public static function FileWrite($FileName,$Data,$Mode) {
		if(!$FP = @fopen($FileName,$Mode.'b')) return FALSE;

		flock($FP,LOCK_EX);
		fwrite($FP,$Data);
		flock($FP,LOCK_UN); fclose($FP);
		@chmod($FileName,0777);

		return TRUE;
	}

	public static function stripSlashes($Value) {
	   if(is_array($Value) == TRUE) $Value = array_map(array('Functions','stripSlashes'),$Value);
	   else $Value = stripslashes($Value);

	   return $Value;
	}

	public static function getPostPicsBox($checkedID = 0) {
		$Template = Factory::singleton('Template');
		$Cache = Factory::singleton('Cache');

		$postPicsData = $Cache->getPostPicsData();

		$Template->assign(array(
			'postPicsData'=>$postPicsData,
			'checkedID'=>$checkedID
		));

		return $Template->fetch('PostPicsBox.tpl');
	}

	public static function getSmiliesBox() {
		$Cache = Factory::singleton('Cache');
		$Template = Factory::singleton('Template');

		$smiliesData = array_slice($Cache->getSmiliesData('read'),0,24);

		$Template->assign('smiliesData',$smiliesData);

		return $Template->fetch('SmiliesBox.tpl');
	}

	public static function getAuthData(&$forumData,$authNames) {
		$authData = array();
		$Auth = Factory::singleton('Auth');

		if($Auth->isLoggedIn() == 0) {
			foreach($authNames AS $curAuth)
				$authData[$curAuth] = isset($forumData[$curAuth.'Guests']) ? $forumData[$curAuth.'Guests'] : 0;

			return $authData;
		}
		elseif($Auth->getValue('userIsAdmin') == 1 || $Auth->getValue('userIsSupermod') == 1) {
			foreach($authNames AS $curAuth)
				$authData[$curAuth] = 1;

			return $authData;
		}

		$authNamesI = implode(', ',$authNames);

		$DB = Factory::singleton('DB');

		$DB->query("SELECT $authNamesI FROM ".TBLPFX."forums_auth WHERE forumID='".$forumData['forumID']."' AND authType='".AUTH_TYPE_USER."' AND authID='$userID'");
		if($DB->getAffectedRows() == 1) return $DB->fetchArray();

		$DB->query("SELECT GroupID FROM ".TBLPFX."groups_members WHERE memberID='".USERID."'");
		if($DB->getAffectedRows() > 0) {
			$groupIDs = $DB->raw2FVArray();

			$DB->query("SELECT $AuthNamesI FROM ".TBLPFX."forums_auth WHERE forumID='".$forumData['forumID']."' AND authType='".AUTH_TYPE_GROUP."' AND authID IN ('".implode("','",$groupIDs)."')");
			if($DB->getAffectedRows() > 0) {
				$groupsAuthData = $DB->raw2Array();
				foreach($authNames AS $curAuth) {
					$authData[$curAuth] = $forumData['Members'.$curAuth];
					foreach($groupsAuthData AS $curGroupAuth) {
						if($curGroupAuth[$curAuth] == 1 - $authData[$curAuth]) {
							$authData[$curAuth] = 1 - $authData[$curAuth];
							break;
						}
					}
				}

				if($authData['authIsMod'] == 1) {
					foreach($authNames AS $curAuth)
						$authData[$curAuth] = 1;
				}

				return $authData;
			}
		}

		foreach($authData AS $curAuth)
			$auth[$curAuth] = isset($forumData['Guests'.$curAuth]) ? $forumData['Guests'.$curAuth] : 0;

		return $authData;
	}

	//*
	//* Fuegt eine neue Kategorie hinzu
	//*
	function cats_add_cat_data($parent_id = 1) {
		global $DB;

		$DB->query("LOCK TABLES ".TBLPFX."cats WRITE"); // Die Tabelle sperren

		$DB->query("SELECT catL,catR FROM ".TBLPFX."cats WHERE catID='$parent_id'"); // Die Daten der uebergeordneten Kategorie laden
		$parent_cat_data = $DB->fetch_array();

		$DB->query("UPDATE ".TBLPFX."cats SET cat_l=cat_l+2 WHERE cat_l> '".$parent_cat_data['cat_r']."'"); // Platz schaffen
		$DB->query("UPDATE ".TBLPFX."cats SET cat_r=cat_r+2 WHERE cat_r>='".$parent_cat_data['cat_r']."'"); // und nochmal Platz schaffen
		$DB->query("INSERT INTO ".TBLPFX."cats (cat_l,cat_r) VALUES ('".$parent_cat_data['cat_r']."','".($parent_cat_data['cat_r']+1)."')"); // Daten der neuen Kategorie einfuegen
		$newCatID = $DB->insert_id;

		$DB->query("UNLOCK TABLES"); // Tabelle entsperren

		return $newCatID;
	}


	//*
	//* Verschiebt eine Kategorie, d.h. weisst ihr eine neue Elternkategorie zu
	//*
	/*static function moveCat($CatID,$TargetID) {
		$DB = Factory::singleton('DB');

		$DB->query("LOCK TABLES ".TBLPFX."cats WRITE");

		$DB->query("SELECT cat_l,cat_r FROM ".TBLPFX."cats WHERE CatID='$CatID'");
		$cat_data = $DB->fetch_array();

		$cat_size = $cat_data['cat_r'] - $cat_data['cat_l'] + 1; // Die Groesse des Astes

		$DB->query("SELECT cat_l,cat_r FROM ".TBLPFX."cats WHERE CatID='$TargetID'");
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
	}*/


	//*
	//* Verschiebt eine Kategorie nach unten
	//*
	static public function cats_move_cat_down($CatID) {
		global $DB;

		$DB->query("LOCK TABLES ".TBLPFX."cats WRITE");

		$DB->query("SELECT cat_l,cat_r FROM ".TBLPFX."cats WHERE CatID='$CatID'");
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
	static public function cats_move_cat_up($CatID) {
		global $DB;

		$DB->query("LOCK TABLES ".TBLPFX."cats WRITE");



		$DB->query("SELECT cat_l,cat_r FROM ".TBLPFX."cats WHERE CatID='$CatID'");
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
	static public function cats_get_parent_cat_data($CatID) {
		global $DB;

		$DB->query("SELECT t1.* FROM ".TBLPFX."cats AS t1, ".TBLPFX."cats AS t2 WHERE t2.CatID='$CatID' AND t1.CatID<>'$CatID' AND t2.cat_l BETWEEN t1.cat_l AND t1.cat_r ORDER BY t1.cat_l DESC LIMIT 1");
		return ($DB->affected_rows == 0) ? FALSE : $DB->fetch_array();
	}


	//*
	//* Bestimmt alle Vaterkategorien einer Kategorie
	//*
	static public function catsGetParentCatsData($catID,$includeSelf = TRUE) {
		$DB = Factory::singleton('DB');

		if($catID == 1) return array();

		$DB->query("SELECT t1.* FROM ".TBLPFX."cats AS t1, ".TBLPFX."cats AS t2 WHERE t2.catID='$catID' AND t1.catID<>1 AND t2.catL BETWEEN t1.catL AND t1.catR ".(!$includeSelf ? "AND t1.catID<>'$catID'" : '')." ORDER BY t1.catL");

		return $DB->raw2Array();
	}


	//*
	//* Laedt alle Kategorien inklusive der Tiefe und der Anzahl der Kinder
	//*
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
	static public function cats_get_cat_data($CatID) {
		global $DB;

		$DB->query("SELECT t1.*, COUNT(*)-1 AS cat_depth, (t1.cat_r - t1.cat_l - 1) / 2 AS cat_childs_counter FROM ".TBLPFX."cats AS t1, ".TBLPFX."cats AS t2 WHERE t1.CatID='$CatID' AND t1.cat_l BETWEEN t2.cat_l AND t2.cat_r GROUP BY t1.cat_l");
		return ($DB->affected_rows == 0) ? FALSE : $DB->fetch_array();
	}

	/**
	 * Returns true if user is mod, false otherwise
	 *
	 * @param int $userID
	 * @return bool
	 */
	static public function checkModStatus($userID) {
		$DB = Factory::singleton('DB');

		// Erst wird nach einem Mod-Recht des Users gesucht
		$DB->query("SELECT authID FROM ".TBLPFX."forums_auth WHERE authType='".AUTH_TYPE_GROUP."' AND authID='$userID' AND authIsMod='1' LIMIT 1");
		if($DB->getAffectedRows() > 0) return TRUE;

		// Nichts gefunden, also muessen die Gruppen ueberprueft werden, in denen der User Mitglied ist
		$DB->query("SELECT groupID FROM ".TBLPFX."groups_members WHERE memberID='$userID'");
		$groupIDs = $DB->raw2FVArray();
		$DB->query("SELECT authID FROM ".TBLPFX."forums_auth WHERE authType='".AUTH_TYPE_GROUP."' AND authID IN ('".implode("','",$groupIDs)."') AND authIsMod='1' LIMIT 1");
		if($DB->getAffectedRows() > 0) return TRUE;

		return FALSE; // User ist kein Mod
	}

	/**
	 * Returns the specified profile note or false on error
	 *
	 * @param int $noteID
	 * @return mixed
	 */
	static public function getProfileNoteData($noteID) {
		$DB = Factory::singleton('DB');

		$DB->query("SELECT * FROM ".TBLPFX."profile_notes WHERE noteID='$noteID'");
		return ($DB->getAffectedRows() == 1) ? $DB->fetchArray() : FALSE;
	}

	public static function getTimeZones($AssignNames = FALSE) {
		$TimeZones = array(
			'idlw'=>-43200,
			'nt'=>  -39600,
			'cat'=> -36000,
			'ahst'=>-36000,
			'hst'=> -36000,
			'hdt'=> -32400,
			'yst'=> -32400,
			'ydt'=> -28800,
			'pst'=> -28800,
			'mst'=> -25200,
			'pdt'=> -25200,
			'cst'=> -21600,
			'mdt'=> -21600,
			'cdt'=> -18000,
			'est'=> -18000,
			'ast'=> -14400,
			'edt'=> -14400,
			'adt'=> -10800,
			'at'=>   -7200,
			'gmt'=>      0,
			'utc'=>      0,
			'wet'=>      0,
			'cet'=>  +3600,
			'bst'=>  +3600,
			'fwt'=>  +3600,
			'met'=>  +3600,
			'mez'=>  +3600,
			'swt'=>  +3600,
			'mewt'=> +3600,
			'eet'=>  +7200,
			'fst'=>  +7200,
			'mest'=> +7200,
			'mesz'=> +7200,
			'cest'=> +7200,
			'sst'=>  +7200,
			'bt'=>  +10800,
			'wast'=>+25200,
			'cct'=> +28800,
			'wadt'=>+28800,
			'jst'=> +32400,
			'east'=>+36000,
			'gst'=> +36000,
			'eadt'=>+39600,
			'idle'=>+43200,
			'nzst'=>+43200,
			'nzdt'=>+46800
		);

		if($AssignNames == TRUE) {
			$Language = Factory::singleton('Language');
			$Language->addFile('TimeZones');
			while(list($curKey) = each($TimeZones))
				$TimeZones[$curKey] = $Language->getString('tz_'.$curKey);
		}

		return $TimeZones;
	}
}

?>