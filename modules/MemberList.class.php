<?php

class MemberList extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'Cache',
		'Constants',
		'DB',
		'Language',
		'Navbar',
		'Template'
	);

	public function executeMe() {
		$usersPerPage = isset($_REQUEST['usersPerPage']) ? intval($_REQUEST['usersPerPage']) : 20;
		$orderBy = isset($_REQUEST['orderBy']) ? $_REQUEST['orderBy'] : 'id';
		$orderType = isset($_REQUEST['orderType']) ? $_REQUEST['orderType'] : 'ASC';
		$page = isset($_GET['page']) ? $_GET['page'] : '1';


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
        $this->modules['DB']->query('SELECT COUNT(*) AS "UsersCounter" FROM '.TBLPFX.'users');
		list($usersCounter) = $this->modules['DB']->fetchArray();

		$pageListing = Functions::createPageListing($usersCounter,$usersPerPage,$page,'<a href="'.INDEXFILE.'?action=MemberList&amp;orderBy='.$orderBy.'&amp;orderType='.$orderType.'&amp;usersPerPage='.$usersPerPage.'&amp;page=%1$s&amp;'.MYSID.'">%2$s</a>');
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
        $this->modules['DB']->queryParams('SELECT "AuthID" FROM '.TBLPFX.'forums_auth WHERE "AuthType"=$1 AND "AuthIsMod"=1 GROUP BY "AuthID"', array(AUTH_TYPE_USER));
		while(list($curUserID) = $this->modules['DB']->fetchArray())
			$modIDs[] = $curUserID;

        $this->modules['DB']->queryParams('SELECT t2."MemberID" FROM '.TBLPFX.'forums_auth AS t1, '.TBLPFX.'groups_members AS t2 WHERE t1."AuthIsMod"=1 AND t1."AuthType"=$1 AND t2."GroupID"=t1."AuthID" GROUP BY t2."MemberID"', array(AUTH_TYPE_GROUP));
		while(list($curUserID) = $this->modules['DB']->fetchArray())
			$modIDs[] = $curUserID;

		$modIDs = array_unique($modIDs);


		//
		// Die Daten der Profilfelder laden, die in der Mitgliederliste zusaetzlich angezeigt werden sollen
		//
		$this->modules['DB']->query('SELECT * FROM '.TBLPFX.'profile_fields WHERE "fieldShowMemberList"=1');
		$fieldsData = $this->modules['DB']->raw2Array();


		//
		// Die Titel fuer die Profilfelder, gleichzeitig noch die IDs der Felder bestimmen
		//
		$fieldIDs = array();
		foreach($fieldsData AS $curField)
			$fieldIDs[] = $curField['fieldID'];


		//
		// Mitgliederdaten laden
		//
        $this->modules['DB']->queryParams('
            SELECT
                t1."userID",
                t1."userNick",
                t1."userEmailAddress",
                t1."userHideEmailAddress",
                t1."userPostsCounter",
                t1."userIsAdmin",
                t1."userIsSupermod",
                t2."rankName" AS "userRankName"
            FROM
                '.TBLPFX.'users AS t1
            LEFT JOIN '.TBLPFX.'ranks AS t2 ON t1."rankID"=t2."rankID"
            ORDER BY $1 $2
            LIMIT $3, $4
        ', array(
            $queryOrderBy,
            $orderType,
            $start,
            $usersPerPage
        ));
		$usersData = $this->modules['DB']->raw2Array();


		//
		// Mitglieder-IDs bestimmen
		//
		$userIDs = array();
		foreach($usersData AS $curUser)
			$userIDs[] = $curUser['userID'];


		//
		// Die Mitgliederdaten der extra-Profilfelder laden
		//
        $this->modules['DB']->queryParams('
            SELECT
                "userID",
                "fieldID",
                "fieldValue"
            FROM
                '.TBLPFX.'profile_fields_data
            WHERE
                "userID" IN $1
                AND "fieldID" IN $2
        ', array(
            $userIDs,
            $fieldIDs
        )); //IN ('".implode("','",$userIDs)."') AND fieldID IN ('".implode("','",$fieldIDs)."')
		$fieldsValues = $this->modules['DB']->raw2Array();


		//
		// Daten ausgeben
		//
		for($i = 0; $i < count($usersData); $i++) {
			$curUser = &$usersData[$i];

			$curUserRank = '';
			if($curUser['userIsAdmin'] == 1) $curUserRank = $this->modules['Language']->getString('Administrator');
			elseif($curUser['userIsSupermod'] == 1) $curUserRank = $this->modules['Language']->getString('Supermoderator');
			elseif(in_array($curUser['userID'],$modIDs)) $curUserRank = $this->modules['Language']->getString('Moderator');
			elseif($curUser['userRankName'] != '') $curUserRank = $curUser['userRankName'];
			else {
				foreach($ranksData[0] AS $curRank) {
					if($curRank['rankPosts'] > $curUser['userPostsCounter']) break;

					$curUserRank = $curRank['rankName']; // ...den Namen das Rangs verwenden...
					//$cur_poster_rank_pic = $cur_rank['rank_gfx']; // ...und das Bild des Rangs verwenden
				}
			}

			$curUser['_userRankName'] = $curUserRank;

			// Die extra-Profilefelder
			$curUserFieldsValues = array();
			foreach($fieldsData AS $curField) {
				$curFieldValue = '';

				while(list($curKey,$curValue) = each($fieldsValues)) {
					if($curValue['userID'] != $curUser['userID'] || $curValue['fieldID'] != $curField['fieldID']) continue;
					$curFieldValue = $curValue['fieldValue'];
					unset($fieldsValues[$curKey]);
					break;
				}

				if($curFieldValue != '') $curFieldValue = sprintf($curField['fieldLink'],$curFieldValue);
				$curUserFieldsValues[$curField['fieldID']] = $curFieldValue;
			}

			$curUser['_userFieldsValues'] = $curUserFieldsValues;
		}


		$this->modules['Navbar']->addElement($this->modules['Language']->getString('Memberlist'),INDEXFILE.'?Action=MemberList&'.MYSID);

		// Seite ausgeben
		$this->modules['Template']->assign(array(
			'fieldsData'=>$fieldsData,
			'usersData'=>$usersData,
			'page'=>$page,
			'orderBy'=>$orderBy,
			'orderType'=>$orderType
		));

		$this->modules['Template']->printPage('MemberList.tpl');
	}
}

?>