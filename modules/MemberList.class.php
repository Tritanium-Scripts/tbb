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


		// Die Seitenanzeige
		$usersCounter = FuncUsers::getUsersCounter();

		$pageListing = Functions::createPageListing($usersCounter,$usersPerPage,$page,'<a href="'.INDEXFILE.'?action=MemberList&amp;orderBy='.$orderBy.'&amp;orderType='.$orderType.'&amp;usersPerPage='.$usersPerPage.'&amp;page=%1$s&amp;'.MYSID.'">%2$s</a>');
		$start = $page*$usersPerPage-$usersPerPage;

		$this->modules['Navbar']->setRightArea($pageListing);


		//
		// Nach welchem Aspekt sortiert werden soll...
		//
		$queryOrderBy = '';
		if($orderBy == 'id') $queryOrderBy = 't1."userID"';
		elseif($orderBy == 'nick') $queryOrderBy = 't1."userNick"';
		elseif($orderBy == 'rank') $queryOrderBy = 't1."userIsAdmin" '.$orderType.',t1."userIsSupermod" '.$orderType.',t1."rankID" '.$orderType.',t1."userPostsCounter"';
		else $queryOrderBy = 't1."userPostsCounter"';


		// Rangdaten laden
		$ranksData = $this->modules['Cache']->getRanksData();


		// User-IDs aller Moderatoren laden
		$modIDs = array();
		$this->modules['DB']->queryParams('
			SELECT DISTINCT
				"authID" AS "userID"
			FROM
				'.TBLPFX.'forums_auth 
			WHERE
				"authType"=$1
				AND "authIsMod"=1
			UNION
			SELECT DISTINCT
				t2."memberID" AS "userID"
			FROM (
				'.TBLPFX.'forums_auth t1,
				'.TBLPFX.'groups_members t2
			) WHERE
				t1."authIsMod"=1
				AND t1."authType"=$2
				AND t2."groupID"=t1."authID"
		',array(
			AUTH_TYPE_USER,
			AUTH_TYPE_GROUP
		));
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
		$this->modules['DB']->query('
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
			ORDER BY '.$queryOrderBy.' '.$orderType.'
			LIMIT '.intval($start).', '.intval($usersPerPage).'
		');
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
		));
		$fieldsValues = $this->modules['DB']->raw2Array();


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


		$this->modules['Navbar']->addElement($this->modules['Language']->getString('Memberlist'),INDEXFILE.'?action=MemberList&'.MYSID);

		// Seite ausgeben
		$this->modules['Template']->assign(array(
			'fieldsData'=>$fieldsData,
			'usersData'=>$usersData,
			'page'=>$page,
			'orderBy'=>$orderBy,
			'orderType'=>$orderType,
			'usersPerPage'=>$usersPerPage
		));

		$this->modules['Template']->printPage('MemberList.tpl');
	}
}

?>