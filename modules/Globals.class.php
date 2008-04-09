<?php

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

		// global page frame / popup
		$this->modules['Template']->setGlobalFrame(array($this,'printHeader'),array($this,'printTail'));
		if(isset($_GET['inPopup'])) $this->modules['Template']->setInPopup(TRUE);
		
		// deny guests to enter board
		if($this->modules['Config']->getValue('guests_enter_board') != 1 && !$this->modules['Auth']->isLoggedIn() && ACTION != 'Register' && ACTION != 'Login') {
			FuncMisc::printMessage('enter_board_not_logged_in',array(sprintf($this->modules['Language']->getString('message_link_click_here_login'),'<a href="'.INDEXFILE.'?action=Login&amp;'.MYSID.'">','</a>'),sprintf($this->modules['Language']->getString('message_link_click_here_register'),'<a href="'.INDEXFILE.'?action=Register&amp;'.MYSID.'">','</a>')));
			exit;
		}

        //Siehe Ticket #35
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
	}

	public function printHeader() {
		if(isset($_GET['inPopup'])) {
			$this->modules['Template']->display('PopupHeader.tpl');
		} else {
			if($this->modules['Config']->getValue('board_logo') != '') $boardBanner = '<img src="'.$this->modules['Config']->getValue('board_logo').'" alt="'.$this->modules['Config']->getValue('board_name').'" />';
			else $boardBanner = $this->modules['Config']->getValue('board_name');
	
			if($this->modules['Auth']->isLoggedIn() == 1) $welcomeText = sprintf($this->modules['Language']->getString('welcome_logged_in'),$this->modules['Auth']->getValue('userNick'),Functions::toTime(time()),INDEXFILE,MYSID);
			else $welcomeText = sprintf($this->modules['Language']->getString('welcome_not_logged_in'),$this->modules['Config']->getValue('board_name'),INDEXFILE,MYSID);
			
			$newPrivateMessageReceived = FALSE;
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
			}
	
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

?>