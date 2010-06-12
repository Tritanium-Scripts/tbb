<?php
/**
 * @author Julian Backes <julian@tritanium-scripts.com>
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2003 - 2009, Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package tbb2
 */
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
		$fieldsData = array();
		$this->modules['DB']->query('SELECT * FROM '.TBLPFX.'profile_fields WHERE "fieldShowMemberList"=1');
		while($curResult = $this->modules['DB']->fetchArray()) {
			$curResult['_fieldData'] = unserialize($curResult['fieldData']);
			$fieldsData[] = $curResult;
		}


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

		foreach($usersData AS &$curUser) {
			$curUserRank = '';
			if($curUser['userIsAdmin'] == 1) $curUserRank = $this->modules['Language']->getString('administrator');
			elseif($curUser['userIsSupermod'] == 1) $curUserRank = $this->modules['Language']->getString('supermoderator');
			elseif(in_array($curUser['userID'],$modIDs)) $curUserRank = $this->modules['Language']->getString('moderator');
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
			foreach($fieldsData AS &$curField) {
				$curFieldValue = '';

				foreach($fieldsValues AS $curKey => $curValue) {
					if($curValue['userID'] != $curUser['userID'] || $curValue['fieldID'] != $curField['fieldID']) continue;
					
					switch($curField['fieldType']) {
						case PROFILE_FIELD_TYPE_TEXT:
							$curFieldValue = sprintf($curField['fieldLink'],Functions::HTMLSpecialChars($curValue['fieldValue']));
							break;
							
						case PROFILE_FIELD_TYPE_TEXTAREA:
							$curFieldValue = sprintf($curField['fieldLink'],nl2br(Functions::HTMLSpecialChars($curValue['fieldValue'])));
							break;
							
						case PROFILE_FIELD_TYPE_SELECTSINGLE:
							$curFieldValue = sprintf($curField['fieldLink'],Functions::HTMLSpecialChars($curField['_fieldData'][$curValue['fieldValue']]));
							break;
							
						case PROFILE_FIELD_TYPE_SELECTMULTI:
							$curFieldValue = array();
							$curValue['fieldValue'] = explode(',',$curValue['fieldValue']);
							foreach($curValue['fieldValue'] AS &$tmp)
								$curFieldValue[] = sprintf($curField['fieldLink'],Functions::HTMLSpecialChars($curField['_fieldData'][$tmp]));
								
							$curFieldValue = implode(', ',$curFieldValue);
							break;
					}					
					unset($fieldsValues[$curKey]);
					break;
				}

				$curUserFieldsValues[$curField['fieldID']] = $curFieldValue;
			}

			$curUser['_userFieldsValues'] = $curUserFieldsValues;
		}


		$this->modules['Navbar']->addElement($this->modules['Language']->getString('memberlist'),INDEXFILE.'?action=MemberList&'.MYSID);

		// Seite ausgeben
		$this->modules['Template']->assign(array(
			'fieldsData'=>$fieldsData,
			'usersData'=>$usersData,
			'page'=>$page,
			'orderBy'=>$orderBy,
			'orderType'=>$orderType,
			'usersPerPage'=>$usersPerPage,
			'colSpan'=>4+count($fieldsData)
		));

		$this->modules['Template']->printPage('MemberList.tpl');
	}
}