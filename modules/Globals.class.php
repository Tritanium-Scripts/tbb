<?php
/**
 * @author Julian Backes <julian@tritanium-scripts.com>
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2003 - 2009, Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package tbb2
 */
class Globals extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'Config',
		'Constants',
		'DB',
		'Language',
		'Navbar',
		'Session',
		'Template'
	);

	public function initializeMe() {
		$this->modules['Navbar']->addElement($this->modules['Config']->getValue('board_name'),INDEXFILE.'?'.MYSID);

		// some url wrapper
		if(isset($_GET['t'])) {
			$_GET['action'] = 'ViewTopic';
			$_GET['topicID'] = $_GET['t'];
		}
		if(isset($_GET['p'])) {
			$_GET['action'] = 'ViewTopic';
			$_GET['postID'] = $_GET['p'];
		}

		//Set template defined by admin
		//TODO set template based on user settings
		$this->modules['Template']->setDirs($this->modules['Config']->getValue('standard_tpl'));

		// global page frame / popup
		$this->modules['Template']->setGlobalFrame(array($this,'printHeader'),array($this,'printTail'));
		if(isset($_GET['inPopup'])) $this->modules['Template']->setInPopup(TRUE);
		
		// deny guests to enter board
		if($this->modules['Config']->getValue('guests_enter_board') != 1 && !$this->modules['Auth']->isLoggedIn() && ACTION != 'Register' && ACTION != 'Login') {
			FuncMisc::printMessage('enter_board_not_logged_in',array(sprintf($this->modules['Language']->getString('message_link_click_here_login'),'<a href="'.INDEXFILE.'?action=Login&amp;'.MYSID.'">','</a>'),sprintf($this->modules['Language']->getString('message_link_click_here_register'),'<a href="'.INDEXFILE.'?action=Register&amp;'.MYSID.'">','</a>')));
			exit;
		}

        //See ticket #35
        // proper content type
        //if(stristr($_SERVER['HTTP_ACCEPT'],'application/xhtml+xml')) header('Content-Type: application/xhtml+xml');

		// last visit / last action
		if($this->modules['Auth']->isLoggedIn()) {
			// 2419200 seconds = 28 days. perhaps we should use a config value instead
			if($this->modules['Auth']->getValue('userLastVisit') < time() - 2419200) {
				$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'users SET "userLastVisit"=$1, "userLastAction"=$1 WHERE "userID"=$2',array(time(), USERID));
				$this->modules['Auth']->setValue('userLastVisit',time());
			}
			else
				$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'users SET "userLastAction"='.time().' WHERE "userID"=$1',array(USERID));
			$this->modules['Auth']->setValue('userLastAction',time());
		} else {
			if(isset($_COOKIE['tbbLastVisit']) && isset($_COOKIE['tbbLastAction'])) {
				if(!isset($_SESSION['guestLastVisitDone'])) {
					$_COOKIE['tbbLastVisit'] = intval($_COOKIE['tbbLastAction']);
					Functions::set1YearCookie('tbbLastVisit',intval($_COOKIE['tbbLastAction']));
					$_SESSION['guestLastVisitDone'] = 1;
				}
				elseif($_COOKIE['tbbLastVisit'] < time() - 2419200) {
					$_COOKIE['tbbLastVisit'] = time();
					Functions::set1YearCookie('tbbLastVisit',time());
				}
			} else {
				$_SESSION['guestLastVisitDone'] = 1;
				$_COOKIE['tbbLastVisit'] = 0;
				Functions::set1YearCookie('tbbLastVisit',0);
			}
			$_COOKIE['tbbLastAction'] = time();
			Functions::set1YearCookie('tbbLastAction',time());
		}

		// forum visits
		if(!isset($_SESSION['forumVisits'])) {
			if(isset($_COOKIE['forumVisits']) && $_COOKIE['forumVisits'] != '') {
				$tmpVisits = explode(',',$_COOKIE['forumVisits']);
				foreach($tmpVisits AS $curVisit) {
					$curVisit = explode('.',$curVisit);
					$_SESSION['forumVisits'][$curVisit[0]] = $curVisit[1];
				}
			}
			else
				$_SESSION['forumVisits'] = array();
		}

		// topic visits
		if(!isset($_SESSION['topicVisits'])) {
			if(isset($_COOKIE['topicVisits']) && $_COOKIE['topicVisits'] != '') {
				$tmpVisits = explode(',',$_COOKIE['topicVisits']);
				foreach($tmpVisits AS $curVisit) {
					$curVisit = explode('.',$curVisit);
					$_SESSION['topicVisits'][$curVisit[0]] = $curVisit[1];
				}
			}
			else
				$_SESSION['topicVisits'] = array();
		}

		//Set WIO location
		$this->modules['DB']->queryParams('
			UPDATE
				'.TBLPFX.'sessions
			SET
				"sessionLastLocation"=$1
			WHERE
				"sessionID"=$2
		',array(
			//TODO proper subaction
			ACTION,// . (isset($_GET['mode']) ? $_GET['mode'] : ''),
			session_id()
		));
	}

	public function printHeader() {
		if(isset($_GET['inPopup'])) {
			$this->modules['Template']->display('PopupHeader.tpl');
		} else {
			if($this->modules['Config']->getValue('board_logo') != '') $boardBanner = '<img src="'.$this->modules['Config']->getValue('board_logo').'" alt="'.$this->modules['Config']->getValue('board_name').'" />';
			else $boardBanner = $this->modules['Config']->getValue('board_name');
				
			$newPrivateMessageReceived = FALSE;
			$unreadPrivateMessages = 0;
			if($this->modules['Config']->getValue('enable_pms') == 1 && $this->modules['Auth']->isLoggedIn()) {
				if(!isset($_SESSION['lastPrivateMessageTimestamp']))
					$_SESSION['lastPrivateMessageTimestamp'] = 0;
				
				$this->modules['DB']->queryParams('
					SELECT
						"pmSendTimestamp"
					FROM
						'.TBLPFX.'pms
					WHERE
						"pmToID"=$1
						AND "pmIsRead"=0
						AND "pmSendTimestamp">$2
					ORDER BY
						"pmSendTimestamp" DESC
					LIMIT 1
				',array(
					USERID,
					$_SESSION['lastPrivateMessageTimestamp']
				));
				if($this->modules['DB']->numRows() != 0) {
					list($timestamp) = $this->modules['DB']->fetchArray();
					$_SESSION['lastPrivateMessageTimestamp'] = $timestamp;
					$newPrivateMessageReceived = TRUE;
				}

				$this->modules['DB']->queryParams('
					SELECT
						COUNT(*)
					FROM
						'.TBLPFX.'pms
					WHERE
						"pmToID"=$1
						AND "folderID"=0
						AND "pmIsRead"=0
				',array(
					USERID
				));
				list($unreadPrivateMessages) = $this->modules['DB']->fetchArray();
			}

			if($this->modules['Auth']->isLoggedIn() == 1) {
				if($unreadPrivateMessages > 0) {
					$welcomeText = sprintf(
						$this->modules['Language']->getString('welcome_logged_in_x_unread_pms'),
						$this->modules['Auth']->getValue('userNick'),
						Functions::toTime(time()),
						INDEXFILE.'?action=Logout&amp;'.MYSID,
						INDEXFILE.'?action=PrivateMessages&amp;'.MYSID,
						$unreadPrivateMessages
					);
				} else {
					$welcomeText = sprintf(
						$this->modules['Language']->getString('welcome_logged_in_no_unread_pms'),
						$this->modules['Auth']->getValue('userNick'),
						Functions::toTime(time()), INDEXFILE.'?action=Logout&amp;'.MYSID
					);
				}
			}
			else $welcomeText = sprintf(
					$this->modules['Language']->getString('welcome_not_logged_in'),
					$this->modules['Config']->getValue('board_name'),
					INDEXFILE . '?action=ViewHelp&amp;' . MYSID,
					INDEXFILE . '?action=Register&amp;' . MYSID,
					INDEXFILE . '?action=Login&amp;' . MYSID
				);

			$this->modules['Template']->assign(array(
				'boardBanner'=>$boardBanner,
				'welcomeText'=>$welcomeText,
				'newPrivateMessageReceived'=>$newPrivateMessageReceived
			));
	
			$this->modules['Template']->display('PageHeader.tpl');
		}
	}

	public function printTail() {
		if(isset($_GET['inPopup'])) {
			$this->modules['Template']->display('PopupTail.tpl');
		} else {
			$this->modules['Template']->display('PageTail.tpl');
		}
	}
}