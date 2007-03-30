<?php

class Ajax extends ModuleTemplate {
	protected $RequiredModules = array(
		'Auth',
		'Cache',
		'Config',
		'DB',
		'Template'
	);

	public function executeMe() {
		header('Content-type: text/xml; charset=UTF-8');

		$Values = array();
		$Status = 'FAIL';
		$Mode = '';
		$Error = '';

		switch(@$_GET['Mode']) {
			case 'EditPost':
				$PostID = isset($_GET['PostID']) ? intval($_GET['PostID']) : 0;
				$PostText = isset($_GET['PostText']) ? $_GET['PostText'] : '';
				$Mode = 'EditPost';

				if($this->Modules['Auth']->isLoggedIn() != 1) $Error = 'Kann Beitrag nicht laden: Nicht eingeloggt';
				elseif(!$PostData = Functions::getPostData($PostID)) $Error = 'Kann Daten nicht laden: Beitrag';
				elseif(!$ForumData = Functions::getForumData($PostData['ForumID'])) $Error = 'Kann Daten nicht laden: Forum';
				else {
					$AuthData = Functions::getAuthData($ForumData,array('AuthIsMod','AuthEditPosts'));
					if($AuthData['AuthEditPosts'] != 1) $Error = 'Kann Beitrag nicht bearbeiten: Kein Zugriff';
					else {
						$this->Modules['DB']->query("
							UPDATE ".TBLPFX."posts
							SET
								PostText='$PostText',
								PostEditedCounter=PostEditedCounter+1,
								PostLastEditorNick='".addslashes($this->Modules['Auth']->getValue('UserNick'))."'
							WHERE
								PostID='$PostID'
						");

						$PostText = Functions::stripSlashes($PostText);
						$PostTextHTMLReady = $PostText;
						if($PostData['PostEnableHtmlCode'] != 1 || $ForumData['ForumEnableHtmlCode'] == FALSE) $PostTextHTMLReady = Functions::HTMLSpecialChars($PostTextHTMLReady);
						if($PostData['PostEnableSmilies'] == 1 && $ForumData['ForumEnableSmilies'] == TRUE) $PostTextHTMLReady = strtr($PostTextHTMLReady,$this->Modules['Cache']->getSmiliesData('write'));
						$PostTextHTMLReady = nl2br($PostTextHTMLReady);
						//if($PostData['PostEnableBBCode'] == 1 && $ForumData['ForumEnableBBCode'] == TRUE) $PostTextHTMLReady = Functions::BBCode($PostTextHTMLReady);

						$Values = array(
							array('Name'=>'PostID','Value'=>$PostID),
							array('Name'=>'PostTextRaw','Value'=>Functions::XMLEscapeString($PostText)),
							array('Name'=>'PostTextHTMLReady','Value'=>Functions::XMLEscapeString($PostTextHTMLReady))
						);

						$Status = 'SUCC';
					}
				}

				break;

			default:
				break;
		}

		$Values[] = array('Name'=>'Error','Value'=>$Error);

		$this->Modules['Template']->assign(array(
			'Status'=>$Status,
			'Mode'=>$Mode,
			'Values'=>$Values
		));
		$this->Modules['Template']->display('AjaxResult.xml');
	}
}

?>