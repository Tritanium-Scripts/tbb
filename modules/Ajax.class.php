<?php
/**
 * @author Julian Backes <julian@tritanium-scripts.com>
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2003 - 2009, Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package tbb2
 */
class Ajax extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'BBCode',
		'Cache',
		'Config',
		'DB',
		'Language',
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
				$this->modules['Language']->addFile('ViewTopic');

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

						$postTextHTMLReady = $this->modules['BBCode']->format($postText, ($postData['postEnableHtmlCode'] == 1 && $forumData['forumEnableHtmlCode'] == 1), ($postData['postEnableSmilies'] == 1 && $forumData['forumEnableSmilies'] == 1), ($postData['postEnableBBCode'] == 1 && $forumData['forumEnableBBCode'] == 1), $postData['topicID']);
						
						$values = array(
							array('key'=>'postID','value'=>$postID),
							array('key'=>'postTextRaw','value'=>$postText),
							array('key'=>'postTextHTMLReady','value'=>$postTextHTMLReady)
						);

						$status = 'SUCC';
					}
				}

				break;

			default:
				break;
		}

		$values[] = array('key'=>'error','value'=>$error);

		$this->modules['Template']->assign(array(
			'status'=>$status,
			'mode'=>$mode,
			'values'=>$values,
			'error'=>''
		));
		$this->modules['Template']->display('AjaxResult.tpl');
	}
}