<?php

class MemberList extends ModuleTemplate {
	protected $RequiredModules = array(
		'Auth',
		'Cache',
		'DB',
		'Language',
		'Navbar',
		'PageParts',
		'Template'
	);

	public function executeMe() {
		$UsersPerPage = isset($_REQUEST['UsersPerPage']) ? intval($_REQUEST['UsersPerPage']) : 20;
		$OrderBy = isset($_REQUEST['OrderBy']) ? $_REQUEST['OrderBy'] : 'id';
		$OrderType = isset($_REQUEST['OrderType']) ? $_REQUEST['OrderType'] : 'ASC';
		$Page = isset($_GET['Page']) ? $_GET['Page'] : '1';


		//
		// Stellt sicher, dass keine ungueltigen Werte uebergeben wurden
		//
		if(!in_array($OrderBy,array('id','nick','rank','posts')))
			$OrderBy = 'id';
		if(!in_array($OrderType,array('ASC','DESC')))
			$OrderType = 'DESC';


		//
		// Die Seitenanzeige
		//
		$this->Modules['DB']->query("SELECT COUNT(*) AS UsersCounter FROM ".TBLPFX."users");
		list($UsersCounter) = $this->Modules['DB']->fetchArray();

		$PageListing = Functions::createPageListing($UsersCounter,$UsersPerPage,$Page,'<a href="'.INDEXFILE.'?Action=MemberList&amp;OrderBy='.$OrderBy.'&amp;OrderType='.$OrderType.'&amp;UsersPerPage='.$UsersPerPage.'&amp;Page=%1$s&amp;'.MYSID.'">%2$s</a>');
		$Start = $Page*$UsersPerPage-$UsersPerPage;

		$this->Modules['Navbar']->setRightArea($PageListing);


		//
		// Nach welchem Aspekt sortiert werden soll...
		//
		$QueryOrderBy = '';
		if($OrderBy == 'id') $QueryOrderBy = 't1.UserID';
		elseif($OrderBy == 'nick') $QueryOrderBy = 't1.UserNick';
		elseif($OrderBy == 'rank') $QueryOrderBy = "t1.UserIsAdmin $OrderType,t1.UserIsSupermod $OrderType,t1.RankID $OrderType,t1.UserPostsCounter";
		else $QueryOrderBy = 't1.UserPostsCounter';


		//
		// Rangdaten laden
		//
		$RanksData = $this->Modules['Cache']->getRanksData();


		//
		// User-IDs aller Moderatoren laden
		//
		$ModIDs = array();
		$this->Modules['DB']->query("SELECT AuthID FROM ".TBLPFX."forums_auth WHERE AuthType='".AUTH_TYPE_USER."' AND AuthIsMod='1' GROUP BY AuthID");
		while(list($curUserID) = $this->Modules['DB']->fetchArray())
			$ModIDs[] = $curUserID;

		$this->Modules['DB']->query("SELECT t2.MemberID FROM ".TBLPFX."forums_auth AS t1, ".TBLPFX."groups_members AS t2 WHERE t1.AuthIsMod='1' AND t1.AuthType='".AUTH_TYPE_GROUP."' AND t2.GroupID=t1.AuthID GROUP BY t2.MemberID");
		while(list($curUserID) = $this->Modules['DB']->fetchArray())
			$ModIDs[] = $curUserID;

		$ModIDs = array_unique($ModIDs);


		//
		// Die Daten der Profilfelder laden, die in der Mitgliederliste zusaetzlich angezeigt werden sollen
		//
		$this->Modules['DB']->query("SELECT * FROM ".TBLPFX."profile_fields WHERE FieldShowMemberList='1'");
		$FieldsData = $this->Modules['DB']->Raw2Array();


		//
		// Die Titel fuer die Profilfelder, gleichzeitig noch die IDs der Felder bestimmen
		//
		$FieldIDs = array();
		foreach($FieldsData AS $curField)
			$FieldIDs[] = $curField['FieldID'];


		//
		// Mitgliederdaten laden
		//
		$this->Modules['DB']->query("SELECT t1.UserID,t1.UserNick,t1.UserEmail,t1.UserPostsCounter,t1.UserIsAdmin,t1.UserIsSupermod,t2.RankName AS UserRankName FROM ".TBLPFX."users AS t1 LEFT JOIN ".TBLPFX."ranks AS t2 ON t1.RankID=t2.RankID ORDER BY $QueryOrderBy $OrderType LIMIT $Start,$UsersPerPage");
		$UsersData = $this->Modules['DB']->Raw2Array();


		//
		// Mitglieder-IDs bestimmen
		//
		$UserIDs = array();
		foreach($UsersData AS $curUser)
			$UserIDs[] = $curUser['UserID'];


		//
		// Die Mitgliederdaten der extra-Profilfelder laden
		//
		$this->Modules['DB']->query("SELECT UserID,FieldID,FieldValue FROM ".TBLPFX."profile_fields_data WHERE UserID IN ('".implode("','",$UserIDs)."') AND FieldID IN ('".implode("','",$FieldIDs)."')");
		$FieldsValues = $this->Modules['DB']->Raw2Array();


		//
		// Daten ausgeben
		//
		for($i = 0; $i < count($UsersData); $i++) {
			$curUser = &$UsersData[$i];

			$curUserRank = '';
			if($curUser['UserIsAdmin'] == 1) $curUserRank = $this->Modules['Language']->getString('Administrator');
			elseif($curUser['UserIsSupermod'] == 1) $curUserRank = $this->Modules['Language']->getString('Supermoderator');
			elseif(in_array($curUser['UserID'],$ModIDs)) $curUserRank = $this->Modules['Language']->getString('Moderator');
			elseif($curUser['UserRankName'] != '') $curUserRank = $curUser['UserRankName'];
			else {
				foreach($RanksData[0] AS $curRank) {
					if($curRank['RankPosts'] > $curUser['UserPostsCounter']) break;

					$curUserRank = $curRank['RankName']; // ...den Namen das Rangs verwenden...
					//$cur_poster_rank_pic = $cur_rank['rank_gfx']; // ...und das Bild des Rangs verwenden
				}
			}

			$curUser['_UserRankName'] = $curUserRank;

			// Die extra-Profilefelder
			$curUserFieldsValues = array();
			foreach($FieldsData AS $curField) {
				$curFieldValue = '';

				while(list($curKey,$curValue) = each($FieldsValues)) {
					if($curValue['UserID'] != $curUser['UserID'] || $curValue['FieldID'] != $curField['FieldID']) continue;
					$curFieldValue = $curValue['FieldValue'];
					unset($FieldsValues[$curKey]);
					break;
				}

				if($curFieldValue != '') $curFieldValue = sprintf($curField['FieldLink'],$curFieldValue);
				$curUserFieldsValues[$curField['FieldID']] = $curFieldValue;
			}

			$curUser['_UserFieldsValues'] = $curUserFieldsValues;
		}


		$this->Modules['Navbar']->addElement($this->Modules['Language']->getString('Memberlist'),INDEXFILE.'?Action=MemberList&'.MYSID);

		// Seite ausgeben
		$this->Modules['Template']->assign(array(
			'FieldsData'=>$FieldsData,
			'UsersData'=>$UsersData,
			'Page'=>$Page,
			'OrderBy'=>$OrderBy,
			'OrderType'=>$OrderType
		));

		$this->Modules['PageParts']->printPage('MemberList.tpl');
	}
}

?>