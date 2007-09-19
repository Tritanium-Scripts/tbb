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

		if(!$topicData = Functions::FuncTopics($topicID)) die('Cannot load data: topic');
		if($topicData['topicMovedID'] != 0) die('Cannot subscribe topic: topic was moved');
		elseif(!$forumData = FuncForums::getForumData($topicData['forumID'])) die('Cannot load data: forum');

		$forumID = $forumData['forumID'];
		$authData = Functions::getAuthData($forumData,array('authViewForum'));
		if($authData['authViewForum'] != 1) die('Access denied: subscribe topic');

		if($this->modules['Auth']->isLoggedIn() == 1 && $this->modules['Config']->getValue('enable_email_functions') == 1 && $this->modules['Config']->getValue('enable_topic_subscription') == 1) {
			$this->modules['DB']->query("SELECT userID FROM ".TBLPFX."topics_subscriptions WHERE userID='".USERID."' AND topicID='$topicID'");
			if($this->modules['DB']->getAffectedRows() == 1) {
				$this->modules['DB']->query("DELETE FROM ".TBLPFX."topics_subscriptions WHERE userID='".USERID."' AND topicID='$topicID'");
				$message = 'topic_unsubscription_successful';
			}
			else {
				$this->modules['DB']->query("INSERT INTO ".TBLPFX."topics_subscriptions SET topicID='".$topicID."', userID='".USERID."'");
				$message = 'topic_subscription_successful';
			}

			$this->modules['Navbar']->addElements(
				array(Functions::HTMLSpecialChars($forumData['forumName']),INDEXFILE."?action=ViewForum&amp;forumID=$forumID&amp;".MYSID),
				array(Functions::HTMLSpecialChars($topicData['topicTitle']),INDEXFILE."?action=ViewTopic&amp;topicID=$topicID&amp;".MYSID)
			);

			$this->modules['Template']->printMessage($message); exit;
		}

		Functions::myHeader(INDEXFILE."?action=ViewTopic&topicID=$topicID&page=$returnPage&".MYSID);
	}
}

?>