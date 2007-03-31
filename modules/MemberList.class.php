<?php

class MemberList extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'Cache',
		'DB',
		'Language',
		'Navbar',
		'PageParts',
		'Template'
	);

	public function executeMe() {
		$usersPerPage = isset($_REQUEST['UsersPerPage']) ? intval($_REQUEST['UsersPerPage']) : 20;
		$orderBy = isset($_REQUEST['OrderBy']) ? $_REQUEST['OrderBy'] : 'id';
		$orderType = isset($_REQUEST['OrderType']) ? $_REQUEST['OrderType'] : 'ASC';
		$page = isset($_GET['Page']) ? $_GET['Page'] : '1';


		//
		// Stellt sicher, dass keine ungueltigen Werte uebergeben wurden
		//
		if(!in_array($orderBy,array('id','nick','rank','posts')))
			$orderBy = 'id';
		if(!in_array($orderType,array('ASC','DESC')))
			$orderType = 'DESC';


		//
		// Die Seitenanzeige
		//
		$this->modules['DB']->query("SELECT COUNT(*) AS UsersCounter FROM ".TBLPFX."users");
		list($usersCounter) = $this->modules['DB']->fetchArray();

		$pageListing = Functions::createPageListing($usersCounter,$usersPerPage,$page,'<a href="'.INDEXFILE.'?Action=MemberList&amp;OrderBy='.$orderBy.'&amp;OrderType='.$orderType.'&amp;UsersPerPage='.$usersPerPage.'&amp;Page=%1$s&amp;'.MYSID.'">%2$s</a>');
		$start = $page*$usersPerPage-$usersPerPage;

		$this->modules['Navbar']->setRightArea($pageListing);


		//
		// Nach welchem Aspekt sortiert werden soll...
		//
		$queryOrderBy = '';
		if($orderBy == 'id') $queryOrderBy = 't1.UserID';
		elseif($orderBy == 'nick') $queryOrderBy = 't1.UserNick';
		elseif($orderBy == 'rank') $queryOrderBy = "t1.UserIsAdmin $orderType,t1.UserIsSupermod $orderType,t1.RankID $orderType,t1.UserPostsCounter";
		else $queryOrderBy = 't1.UserPostsCounter';


		//
		// Rangdaten laden
		//
		$ranksData = $this->modules['Cache']->getRanksData();


		//
		// User-IDs aller Moderatoren laden
		//
		$modIDs = array();
		$this->modules['DB']->query("SELECT AuthID FROM ".TBLPFX."forums_auth WHERE AuthType='".AUTH_TYPE_USER."' AND AuthIsMod='1' GROUP BY AuthID");
		while(list($curUserID) = $this->modules['DB']->fetchArray())
			$modIDs[] = $curUserID;

		$this->modules['DB']->query("SELECT t2.MemberID FROM ".TBLPFX."forums_auth AS t1, ".TBLPFX."groups_members AS t2 WHERE t1.AuthIsMod='1' AND t1.AuthType='".AUTH_TYPE_GROUP."' AND t2.GroupID=t1.AuthID GROUP BY t2.MemberID");
		while(list($curUserID) = $this->modules['DB']->fetchArray())
			$modIDs[] = $curUserID;

		$modIDs = array_unique($modIDs);


		//
		// Die Daten der Profilfelder laden, die in der Mitgliederliste zusaetzlich angezeigt werden sollen
		//
		$this->modules['DB']->query("SELECT * FROM ".TBLPFX."profile_fields WHERE FieldShowMemberList='1'");
		$fieldsData = $this->modules['DB']->raw2Array();


		//
		// Die Titel fuer die Profilfelder, gleichzeitig noch die IDs der Felder bestimmen
		//
		$fieldIDs = array();
		foreach($fieldsData AS $curField)
			$fieldIDs[] = $curField['FieldID'];


		//
		// Mitgliederdaten laden
		//
		$this->modules['DB']->query("SELECT t1.UserID,t1.UserNick,t1.UserEmail,t1.UserPostsCounter,t1.UserIsAdmin,t1.UserIsSupermod,t2.RankName AS UserRankName FROM ".TBLPFX."users AS t1 LEFT JOIN ".TBLPFX."ranks AS t2 ON t1.RankID=t2.RankID ORDER BY $queryOrderBy $orderType LIMIT $start,$usersPerPage");
		$usersData = $this->modules['DB']->raw2Array();


		//
		// Mitglieder-IDs bestimmen
		//
		$userIDs = array();
		foreach($usersData AS $curUser)
			$userIDs[] = $curUser['UserID'];


		//
		// Die Mitgliederdaten der extra-Profilfelder laden
		//
		$this->modules['DB']->query("SELECT UserID,FieldID,FieldValue FROM ".TBLPFX."profile_fields_data WHERE UserID IN ('".implode("','",$userIDs)."') AND FieldID IN ('".implode("','",$fieldIDs)."')");
		$fieldsValues = $this->modules['DB']->raw2Array();


		//
		// Daten ausgeben
		//
		for($i = 0; $i < count($usersData); $i++) {
			$curUser = &$usersData[$i];

			$curUserRank = '';
			if($curUser['UserIsAdmin'] == 1) $curUserRank = $this->modules['Language']->getString('Administrator');
			elseif($curUser['UserIsSupermod'] == 1) $curUserRank = $this->modules['Language']->getString('Supermoderator');
			elseif(in_array($curUser['UserID'],$modIDs)) $curUserRank = $this->modules['Language']->getString('Moderator');
			elseif($curUser['UserRankName'] != '') $curUserRank = $curUser['UserRankName'];
			else {
				foreach($ranksData[0] AS $curRank) {
					if($curRank['RankPosts'] > $curUser['UserPostsCounter']) break;

					$curUserRank = $curRank['RankName']; // ...den Namen das Rangs verwenden...
					//$cur_poster_rank_pic = $cur_rank['rank_gfx']; // ...und das Bild des Rangs verwenden
				}
			}

			$curUser['_UserRankName'] = $curUserRank;

			// Die extra-Profilefelder
			$curUserFieldsValues = array();
			foreach($fieldsData AS $curField) {
				$curFieldValue = '';

				while(list($curKey,$curValue) = each($fieldsValues)) {
					if($curValue['UserID'] != $curUser['UserID'] || $curValue['FieldID'] != $curField['FieldID']) continue;
					$curFieldValue = $curValue['FieldValue'];
					unset($fieldsValues[$curKey]);
					break;
				}

				if($curFieldValue != '') $curFieldValue = sprintf($curField['FieldLink'],$curFieldValue);
				$curUserFieldsValues[$curField['FieldID']] = $curFieldValue;
			}

			$curUser['_UserFieldsValues'] = $curUserFieldsValues;
		}


		$this->modules['Navbar']->addElement($this->modules['Language']->getString('Memberlist'),INDEXFILE.'?Action=MemberList&'.MYSID);

		// Seite ausgeben
		$this->modules['Template']->assign(array(
			'FieldsData'=>$fieldsData,
			'UsersData'=>$usersData,
			'Page'=>$page,
			'OrderBy'=>$orderBy,
			'OrderType'=>$orderType
		));

		$this->modules['PageParts']->printPage('MemberList.tpl');
	}
}

?>