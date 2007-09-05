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