<?php

class Vote extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'DB'
	);

	public function executeMe() {
		$pollID = isset($_GET['pollID']) ? intval($_GET['pollID']) : 0;
		$p['optionID'] = isset($_POST['p']['optionID']) ? intval($_POST['p']['optionID']) : 0;
		$returnPage = isset($_GET['returnPage']) ? $_GET['returnPage'] : 1;

		if(!$pollData = FuncPolls::getPollData($pollID)) die('Cannot load data: poll');
		if(!$topicData = FuncTopics::getTopicData($pollData['topicID'])) die('Cannot load data: topic');
		if(!$forumData = FuncForums::getForumData($topicData['forumID'])) die('Cannot load data: forum');

		$forumID = &$topicData['forumID'];
		$topicID = &$topicData['topicID'];

		$authData = $this->_authenticateUser($forumData);

		$sPollVotes = (isset($_SESSION['pollVotes']) && $_SESSION['pollVotes'] != '') ? explode(',',$_SESSION['pollVotes']) : array();
		$cPollVotes = (isset($_COOKIE['pollVotes']) && $_COOKIE['pollVotes'] != '') ? explode(',',$_COOKIE['pollVotes']) : array();

		if($this->modules['Auth']->isLoggedIn() == 1) {
			$this->modules['DB']->query("SELECT voterID FROM ".TBLPFX."polls_votes WHERE pollID='".$pollData['pollID']."' AND voterID='".USERID."'");
			if($this->modules['DB']->getAffectedRows() == 0) {
				$this->modules['DB']->query("UPDATE ".TBLPFX."polls_options SET optionVotesCounter=optionVotesCounter+1 WHERE pollID='$pollID' AND optionID='".$p['optionID']."'");
				if($this->modules['DB']->getAffectedRows() == 1) {
					$this->modules['DB']->query("UPDATE ".TBLPFX."polls SET pollVotesCounter=pollVotesCounter+1 WHERE pollID='$pollID'");
					$this->modules['DB']->query("INSERT INTO ".TBLPFX."polls_votes SET pollID='$pollID', voterID='".USERID."'");
					$sPollVotes[] = $pollID;
					$cPollVotes[] = $pollID;
					$_SESSION['pollVotes'] = implode(',',$sPollVotes);
					$_COOKIE['pollVotes'] = implode(',',$cPollVotes);
				}
			}

		} elseif($pollData['pollGuestsVote'] == 1) {
			if(!in_array($pollID,$sPollVotes) && !in_array($cPollVotes)) {
				$this->modules['DB']->query("UPDATE ".TBLPFX."polls_options SET optionVotesCounter=optionVotesCounter+1 WHERE pollID='$pollID' AND optionID='".$p['optionID']."'");
				if($this->modules['DB']->getAffectedRows() == 1) {
					$this->modules['DB']->query("UPDATE ".TBLPFX."polls SET pollVotesCounter=pollVotesCounter+1 WHERE pollID='$pollID'");
					$sPollVotes[] = $pollID;
					$cPollVotes[] = $pollID;
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
			// TODO
			echo 'Kein Zugriff';
			exit;
		}

		return $authData;
	}
}

?>