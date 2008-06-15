<?php

class Vote extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'DB'
	);

	public function executeMe() {
		$topicID = isset($_GET['topicID']) ? intval($_GET['topicID']) : 0;
		$p['optionID'] = isset($_POST['p']['optionID']) ? intval($_POST['p']['optionID']) : 0;
		$returnPage = isset($_GET['returnPage']) ? $_GET['returnPage'] : 1;

		if(!$topicData = FuncTopics::getTopicData($topicID)) die('Cannot load data: topic');
		if($topicData['topicHasPoll'] != 1) die('Topic has no poll');
		if(!$forumData = FuncForums::getForumData($topicData['forumID'])) die('Cannot load data: forum');

		$forumID = &$topicData['forumID'];
		$topicID = &$topicData['topicID'];

		$authData = $this->_authenticateUser($forumData);

		$sPollVotes = (isset($_SESSION['pollVotes']) && $_SESSION['pollVotes'] != '') ? explode(',',$_SESSION['pollVotes']) : array();
		$cPollVotes = (isset($_COOKIE['pollVotes']) && $_COOKIE['pollVotes'] != '') ? explode(',',$_COOKIE['pollVotes']) : array();

		if($this->modules['Auth']->isLoggedIn() == 1) {
            $this->modules['DB']->queryParams('SELECT "voterID" FROM '.TBLPFX.'polls_votes WHERE "topicID"=$1 AND "voterID"=$2', array($topicData['topicID'], USERID));
			if($this->modules['DB']->numRows() == 0) {
                $this->modules['DB']->queryParams('UPDATE '.TBLPFX.'polls_options SET "optionVotesCounter"="optionVotesCounter"+1 WHERE "topicID"=$1 AND "optionID"=$2', array($topicID, $p['optionID']));
				if($this->modules['DB']->getAffectedRows() == 1) {
                    $this->modules['DB']->queryParams('UPDATE '.TBLPFX.'topics SET "topicPollVotesCounter"="topicPollVotesCounter"+1 WHERE "topicID"=$1', array($topicID));
                    $this->modules['DB']->queryParams('INSERT INTO '.TBLPFX.'polls_votes SET "topicID"=$1, "voterID"=$2', array($topicID, USERID));
					$sPollVotes[] = $topicID;
					$cPollVotes[] = $topicID;
					$_SESSION['pollVotes'] = implode(',',$sPollVotes);
					$_COOKIE['pollVotes'] = implode(',',$cPollVotes);
				}
			}

		} elseif($topicData['topicPollGuestsVote'] == 1) {
			if(!in_array($topicID,$sPollVotes) && !in_array($cPollVotes)) {
                $this->modules['DB']->queryParams('UPDATE '.TBLPFX.'polls_options SET "optionVotesCounter"="optionVotesCounter"+1 WHERE "topicID"=$1 AND "optionID"=$2', array($topicID, $p['optionID']));
				if($this->modules['DB']->getAffectedRows() == 1) {
                    $this->modules['DB']->queryParams('UPDATE '.TBLPFX.'topics SET "topicPollVotesCounter"="topicPollVotesCounter"+1 WHERE "topicID"=$1', array($topicID));
					$sPollVotes[] = $topicID;
					$cPollVotes[] = $topicID;
					$_SESSION['pollVotes'] = implode(',',$sPollVotes);
					$_COOKIE['pollVotes'] = implode(',',$cPollVotes);
				}
			}
		}

		Functions::myHeader(INDEXFILE."?action=ViewTopic&topicID=$topicID&page=$returnPage&".MYSID);
	}

	protected function _authenticateUser(&$forumData) {
		$authData = Functions::getAuthData($forumData,array('authViewForum'));
		if($authData['authViewForum'] != 1) {
			FuncMisc::printMessage('access_denied');
			exit;
		}

		return $authData;
	}
}

?>