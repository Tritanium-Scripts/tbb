<?php
class SubscribeTopic extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'Config',
		'DB',
		'Language',
		'Navbar',
		'Template'

	);

	public function executeMe() {
		$topicID = isset($_GET['topicID']) ? intval($_GET['topicID']) : 0;
		$returnPage = isset($_GET['returnPage']) ? intval($_GET['returnPage']) : 1;

		if(!$topicData = FuncTopics::getTopicData($topicID)) die('Cannot load data: topic');
		if($topicData['topicMovedID'] != 0) die('Cannot subscribe topic: topic was moved');
		elseif(!$forumData = FuncForums::getForumData($topicData['forumID'])) die('Cannot load data: forum');

		$forumID = $forumData['forumID'];
		$authData = Functions::getAuthData($forumData,array('authViewForum'));
		if($authData['authViewForum'] != 1) die('Access denied: subscribe topic');

		if($this->modules['Auth']->isLoggedIn() == 1 && $this->modules['Config']->getValue('enable_email_functions') == 1 && $this->modules['Config']->getValue('enable_topic_subscription') == 1) {
            $this->modules['DB']->queryParams('SELECT "userID" FROM '.TBLPFX.'topics_subscriptions WHERE "userID"=$1 AND "topicID"=$2', array(USERID, $topicID));
			if($this->modules['DB']->getAffectedRows() == 1) {
                $this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'topics_subscriptions WHERE "userID"=$1 AND "topicID"=$2', array(USERID, $topicID));
				$message = 'topic_unsubscription_successful';
			}
			else {
                $this->modules['DB']->queryParams('INSERT INTO '.TBLPFX.'topics_subscriptions SET "topicID"=$1, "userID"=$2', array($topicID, USERID));
				$message = 'topic_subscription_successful';
			}

			$this->modules['Navbar']->addCategories($forumData['catID']);
			$this->modules['Navbar']->addElements(
				array(Functions::HTMLSpecialChars($forumData['forumName']),INDEXFILE.'?action=ViewForum&amp;forumID='.$forumID.'&amp;'.MYSID),
				array(Functions::HTMLSpecialChars($topicData['topicTitle']),INDEXFILE.'?action=ViewTopic&amp;topicID='.$topicID.'&amp;'.MYSID)
			);

			FuncMisc::printMessage($message); exit;
		}

		Functions::myHeader(INDEXFILE."?action=ViewTopic&topicID=$topicID&page=$returnPage&".MYSID);
	}
}