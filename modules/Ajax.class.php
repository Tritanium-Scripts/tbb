<?php

class Ajax extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'Cache',
		'Config',
		'DB',
		'Template'
	);

	public function executeMe() {
		header('Content-type: text/xml; charset=UTF-8');

		$values = array();
		$status = 'FAIL';
		$mode = '';
		$error = '';

		switch(@$_GET['Mode']) {
			case 'EditPost':
				$postID = isset($_GET['PostID']) ? intval($_GET['PostID']) : 0;
				$postText = isset($_GET['PostText']) ? $_GET['PostText'] : '';
				$mode = 'EditPost';

				if($this->modules['Auth']->isLoggedIn() != 1) $error = 'Kann Beitrag nicht laden: Nicht eingeloggt';
				elseif(!$postData = Functions::getPostData($postID)) $error = 'Kann Daten nicht laden: Beitrag';
				elseif(!$forumData = Functions::getForumData($postData['ForumID'])) $error = 'Kann Daten nicht laden: Forum';
				else {
					$authData = Functions::getAuthData($forumData,array('AuthIsMod','AuthEditPosts'));
					if($authData['AuthEditPosts'] != 1) $error = 'Kann Beitrag nicht bearbeiten: Kein Zugriff';
					else {
						$this->modules['DB']->query("
							UPDATE ".TBLPFX."posts
							SET
								PostText='$postText',
								PostEditedCounter=PostEditedCounter+1,
								PostLastEditorNick='".addslashes($this->modules['Auth']->getValue('UserNick'))."'
							WHERE
								PostID='$postID'
						");

						$postText = Functions::stripSlashes($postText);
						$postTextHTMLReady = $postText;
						if($postData['PostEnableHtmlCode'] != 1 || $forumData['ForumEnableHtmlCode'] == FALSE) $postTextHTMLReady = Functions::HTMLSpecialChars($postTextHTMLReady);
						if($postData['PostEnableSmilies'] == 1 && $forumData['ForumEnableSmilies'] == TRUE) $postTextHTMLReady = strtr($postTextHTMLReady,$this->modules['Cache']->getSmiliesData('write'));
						$postTextHTMLReady = nl2br($postTextHTMLReady);
						//if($postData['PostEnableBBCode'] == 1 && $forumData['ForumEnableBBCode'] == TRUE) $postTextHTMLReady = Functions::BBCode($postTextHTMLReady);

						$values = array(
							array('Name'=>'PostID','Value'=>$postID),
							array('Name'=>'PostTextRaw','Value'=>Functions::XMLEscapeString($postText)),
							array('Name'=>'PostTextHTMLReady','Value'=>Functions::XMLEscapeString($postTextHTMLReady))
						);

						$status = 'SUCC';
					}
				}

				break;

			default:
				break;
		}

		$values[] = array('Name'=>'Error','Value'=>$error);

		$this->modules['Template']->assign(array(
			'Status'=>$status,
			'Mode'=>$mode,
			'Values'=>$values
		));
		$this->modules['Template']->display('AjaxResult.xml');
	}
}

?>