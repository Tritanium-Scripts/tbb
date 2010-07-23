<?php
/**
 * Manages private messages.
 *
 * PM file structure:
 * <ol>
 *  <li>pmID</li>
 *  <li>title</li>
 *  <li>message</li>
 *  <li>senderUserID</li>
 *  <li>date</li>
 *  <li>enableSmilies</li>
 *  <li>enableBBCode</li>
 *  <li>unreadFlag</li>
 * </ol>
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class PrivateMessage implements Module
{
	/**
	 * Mode to execute.
	 *
	 * @var string PM mode
	 */
	private $mode;

	/**
	 * Translates a mode to its template file.
	 *
	 * @var array Mode and template counterparts
	 */
	private static $modeTable = array('' => 'PrivateMessageIndex',
		'pm' => 'PrivateMessageIndex',
		'overview' => 'PrivateMessageIndex',
		'view' => 'PrivateMessageViewPM',
		'reply' => 'PrivateMessageNewPM',
		'send' => 'PrivateMessageNewPM',
		'PrivateMessageNewPMConfirmSend' => 'PrivateMessageNewPMConfirmSend',
		'kill' => 'PrivateMessageConfirmDelete');

	/**
	 * ID of this PM box.
	 *
	 * @var int PM box ID
	 */
	private $pmBoxID;

	/**
	 * ID of current PM in "single mode".
	 *
	 * @var int Single PM ID
	 */
	private $pmID;

	/**
	 * Sets mode and PM box ID.
	 *
	 * @param string $mode PM mode
	 * @return PrivateMessage New instance of this class
	 */
	function __construct($mode=null)
	{
		$this->mode = $mode;
		$this->pmBoxID = Functions::getValueFromGlobals('pmbox_id') or $this->pmBoxID = Main::getModule('Auth')->getUserID();
		$this->pmID = Functions::getValueFromGlobals('pm_id');
	}

	/**
	 * Executes pm mode.
	 */
	public function execute()
	{
		Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('pms'), INDEXFILE . '?faction=pm&amp;mode=overview' . SID_AMPER);
		if(!Main::getModule('Auth')->isLoggedIn())
			Main::getModule('Template')->printMessage('login_only', INDEXFILE . '?faction=register' . SID_AMPER, INDEXFILE . '?faction=login' . SID_AMPER);
		elseif($this->pmBoxID != Main::getModule('Auth')->getUserID())
			Main::getModule('Template')->printMessage('pm_no_access');
		switch($this->mode)
		{
//PrivateMessageViewPM
			case 'view':
			$found = false;
			$pms = array_reverse(Functions::file('members/' . $this->pmBoxID . '.pm'));
			foreach($pms as &$curPM)
			{
				$curPM = Functions::explodeByTab($curPM);
				//Search for target pm
				if($curPM[0] == $this->pmID)
				{
					Main::getModule('NavBar')->addElement($curPM[1], INDEXFILE . '?faction=pm&amp;mode=view&amp;pm_id=' . $this->pmID . '&amp;pmbox_id=' . $this->pmBoxID . SID_AMPER);
					//Remove unread flag, if needed
					if($curPM[7] == '1')
					{
						$curPM[7] = '0';
						//Implode for file write
						$curPM = Functions::implodeByTab($curPM);
						Functions::file_put_contents('members/' . $this->pmBoxID . '.pm', implode("\n", array_reverse($pms)) . "\n");
						//Undo implode for file write
						$curPM = Functions::explodeByTab($curPM);
					}
					//Proceed with data preparation
					$curPM[2] = Main::getModule('BBCode')->parse($curPM[2], false, $curPM[5] == '1', $curPM[6] == '1');
					$curPM[3] = Functions::getProfileLink($curPM[3], true);
					$curPM[4] = Functions::formatDate($curPM[4]);
					Main::getModule('Template')->assign('pm', $curPM);
					$found = true;
					break;
				}
				else
					//Always implode back in case of updating any unread flags
					$curPM = Functions::implodeByTab($curPM);
			}
			if(!$found)
				Main::getModule('Template')->printMessage('pm_not_found');
			break;

//PrivateMessageNewPM
			case 'reply':
			//Replying ist just quoting, ergo look up quoted PM and go straight on into "send" mode (hence no break statement)
			foreach(array_reverse(Functions::file('members/' . $this->pmBoxID . '.pm')) as $curPM)
			{
				$curPM = Functions::explodeByTab($curPM);
				if($curPM[0] == $this->pmID)
				{
					$recipient = $curPM[3];
					$newPM = &$curPM;
					$newPM[0] = -1; //Remove old ID for new PM
					//Update title
					$newPM[1] = Main::getModule('Language')->getString('re_colon') . $newPM[1];
					//Insert quoted text with BBCode
					$newPM[2] = '[quote' . (($newPM[3] = Functions::getUserData($newPM[3])) !== false ? '=' . $newPM[3][0] : '') . ']' . Functions::br2nl($newPM[2]) . '[/quote]';
					$newPM[3] = $this->pmBoxID; //Update sender ID
					$newPM[4] = ''; //Remove old date
					$newPM[7] = '1'; //Update unread flag
					break;
				}
			}

//PrivateMessageNewPM
			case 'send':
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('new_pm'), INDEXFILE . '?faction=pm&amp;pmbox_id=' . $this->pmBoxID . '&amp;mode=send' . SID_AMPER);
			if(!isset($newPM))
				$newPM = array(-1,
					htmlspecialchars(trim(Functions::getValueFromGlobals('betreff'))),
					htmlspecialchars(trim(Functions::getValueFromGlobals('pm'))),
					$this->pmBoxID,
					'',
					Functions::getValueFromGlobals('smilies') == '1',
					Functions::getValueFromGlobals('use_upbcode') == '1',
					'1',
					'');
			$errors = array();
			if(!isset($recipient))
				$recipient = Functions::getValueFromGlobals('target_id');
			//Send PM?
			if(Functions::getValueFromGlobals('send') == 'yes')
			{
				$recipient = Functions::getUserData($recipient);
				if($recipient == false)
					$errors[] = Main::getModule('Language')->getString('recipient_does_not_exist');
				else
					$recipient = array_slice($recipient, 0, 2); //Cut off not needed infos
				if($newPM[1] == '')
					$errors[] = Main::getModule('Language')->getString('please_enter_a_subject');
				if(empty($errors))
				{
					//Confirmed?
					if(Functions::getValueFromGlobals('check') == 'yes')
					{
						$newPM[2] = Functions::nl2br($newPM[2]);
						$newPM[4] = gmdate('YmdHis');
						//Detect new PM ID
						$newPM[0] = @current(Functions::explodeByTab(array_pop(Functions::file('members/' . $recipient[1] . '.pm'))))+1;
						Functions::file_put_contents('members/' . $recipient[1] . '.pm', Functions::implodeByTab($newPM) . "\n", FILE_APPEND);
						Main::getModule('Logger')->log('%s sent PM to ' . $recipient[0] . ' (ID: ' . $recipient[1] . ')', LOG_USER_TRAFFIC);
						Main::getModule('Template')->printMessage('pm_sent', INDEXFILE . '?faction=pm&amp;pmbox_id=' . $this->pmBoxID . SID_AMPER, INDEXFILE . SID_AMPER);
					}
					//Get confirmation
					else
					{
						Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('confirmation'));
						$this->mode = 'PrivateMessageNewPMConfirmSend';
					}
				}
			}
			Main::getModule('Template')->assign(array('newPM' => $newPM,
				'recipient' => $recipient,
				'errors' => $errors,
				'smilies' => Main::getModule('BBCode')->getSmilies()));
			break;

//PrivateMessageConfirmDelete
			case 'kill':
			if(Functions::getValueFromGlobals('kill') != 'yes')
			{
				//Retrieve pm title
				foreach(array_reverse(Functions::file('members/' . $this->pmBoxID . '.pm')) as $curPM)
				{
					$curPM = Functions::explodeByTab($curPM);
					if($curPM[0] == $this->pmID)
					{
						Main::getModule('NavBar')->addElement(array(
							array($curPM[1], INDEXFILE . '?faction=pm&amp;mode=view&amp;pm_id=' . $this->pmID . '&amp;pmbox_id=' . $this->pmBoxID . SID_AMPER),
							array(Main::getModule('Language')->getString('delete_pm'))));
						Main::getModule('Template')->assign('pmTitle', $curPM[1]);
						break;
					}
				}
				break; //Exit switch
			}
			//Use "deletemany" to delete a single pm, hence no break
			else
				$toDelete = array($this->pmID);

//PrivateMessageIndex (via redir)
			case 'deletemany':
			if(!isset($toDelete))
				$toDelete = array_keys(($toDelete = Functions::getValueFromGlobals('deletepm')) != '' ? $toDelete : array());
			if(!empty($toDelete))
			{
				$size = count($pms = $this->getPMs());
				for($i=0; $i<$size; $i++)
					if(in_array($pms[$i][0], $toDelete))
						unset($pms[$i]);
				Functions::file_put_contents('members/' . $this->pmBoxID . '.pm', implode("\n", array_map(array('Functions', 'implodeByTab'), $pms)) . "\n");
			}
			header('Location: ' . INDEXFILE . '?faction=pm&profile_id=' . Main::getModule('Auth')->getUserID() . SID_AMPER_RAW);
			Main::getModule('Template')->assign('pmBoxID', $this->pmBoxID);
			Main::getModule('Template')->printMessage('selected_pms_deleted');
			break;

//PrivateMessageIndex
			case 'pm':
			case 'overview':
			default:
			$pms = array_reverse(Functions::file('members/' . $this->pmBoxID . '.pm'));
			foreach($pms as &$curPM)
			{
				$curPM = Functions::explodeByTab($curPM);
				$curPM[3] = Functions::getProfileLink($curPM[3], true);
				$curPM[4] = Functions::formatDate($curPM[4]);
			}
			Main::getModule('Template')->assign('pms', $pms);
			break;
		}
		Main::getModule('Template')->printPage(self::$modeTable[$this->mode], array('pmBoxID' => $this->pmBoxID,
			'pmID' => $this->pmID));
	}

	/**
	 * Returns current and fully exploded PMs from user.
	 *
	 * @return array All saved PMs from current user
	 */
	private function getPMs()
	{
		return array_map(array('Functions', 'explodeByTab'), Functions::file('members/' . $this->pmBoxID . '.pm'));
	}

	/**
	 * Return amount of unread private messages.
	 *
	 * @return int Amount of unread PMs
	 */
	public function getUnreadPMs()
	{
		$unread = 0;
		if(Main::getModule('Auth')->isLoggedIn())
			foreach($this->getPMs() as $curPM)
				if($curPM[7] == '1')
					$unread++;
		return $unread;
	}

	/**
	 * Returns to remind the user to check for new pms.
	 *
	 * @return bool Remind the user to check his pm box
	 */
	public function isRemind()
	{
		if(!isset($_SESSION['lastUnreadReminder']) || time() > $_SESSION['lastUnreadReminder']+Main::getModule('Config')->getCfgVal('new_pm_reminder'))
		{
			$_SESSION['lastUnreadReminder'] = time();
			return true;
		}
		return false;
	}
}
?>