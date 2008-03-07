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

		switch(@$_GET['mode']) {
			case 'EditPost':
				$postID = isset($_GET['postID']) ? intval($_GET['postID']) : 0;
				$postText = isset($_GET['postText']) ? $_GET['postText'] : '';
				$mode = 'EditPost';

				if($this->modules['Auth']->isLoggedIn() != 1) $error = 'Kann Beitrag nicht laden: Nicht eingeloggt';
				elseif(!$postData = Functions::getPostData($postID)) $error = 'Kann Daten nicht laden: Beitrag';
				elseif(!$forumData = FuncForums::getForumData($postData['forumID'])) $error = 'Kann Daten nicht laden: Forum';
				else {
					$authData = Functions::getAuthData($forumData,array('authIsMod','authEditPosts'));
					if($authData['authEditPosts'] != 1) $error = 'Kann Beitrag nicht bearbeiten: Kein Zugriff';
					else {
                        $this->modules['DB']->queryParams('
                            UPDATE
                                '.TBLPFX.'posts
                            SET
                                "postText"=$1,
                                "postEditedCounter"="postEditedCounter"+1,
                                "postLastEditorNick"=$2
                            WHERE
                                "postID"=$3
                        ', array(
                            $postText,
                            $this->modules['Auth']->getValue('userNick'),
                            $postID
                        ));

						$postText = Functions::stripSlashes($postText);
						$postTextHTMLReady = $postText;
						if($postData['postEnableHtmlCode'] != 1 || $forumData['ForumEnableHtmlCode'] != 1) $postTextHTMLReady = Functions::HTMLSpecialChars($postTextHTMLReady);
						if($postData['postEnableSmilies'] == 1 && $forumData['forumEnableSmilies'] == 1) $postTextHTMLReady = strtr($postTextHTMLReady,$this->modules['Cache']->getSmiliesData('write'));
						$postTextHTMLReady = nl2br($postTextHTMLReady);
						//if($postData['postEnableBBCode'] == 1 && $forumData['forumEnableBBCode'] == TRUE) $postTextHTMLReady = Functions::BBCode($postTextHTMLReady);

						$values = array(
							array('name'=>'postID','value'=>$postID),
							array('name'=>'postTextRaw','value'=>Functions::XMLEscapeString($postText)),
							array('name'=>'postTextHTMLReady','value'=>Functions::XMLEscapeString($postTextHTMLReady))
						);

						$status = 'SUCC';
					}
				}

				break;

			default:
				break;
		}

		$values[] = array('name'=>'error','value'=>$error);

		$this->modules['Template']->assign(array(
			'status'=>$status,
			'mode'=>$mode,
			'values'=>$values
		));
		$this->modules['Template']->display('AjaxResult.xml');
	}
}

?>