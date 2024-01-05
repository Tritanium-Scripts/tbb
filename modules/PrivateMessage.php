<?php
/**
 * Manages private messages.
 *
 * PM / ACH file structure:
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
 * @copyright Copyright (c) 2010-2023 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class PrivateMessage extends PublicModule
{
    use Singleton, Mode;

    /**
     * Translates a mode to its template file.
     *
     * @var array Mode and template counterparts
     */
    private static array $modeTable = array('' => 'PrivateMessageIndex',
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
    private int $pmBoxID;

    /**
     * ID of current PM in "single mode".
     *
     * @var int Single PM ID
     */
    private int $pmID;

    /**
     * Active message box is the outbox.
     *
     * @var bool Outbox active
     */
    private bool $isOutbox;

    /**
     * Contains suffix for nav bar URLs depending on active box.
     *
     * @var string Appendix for URLs
     */
    private string $urlSuffix = '';

    /**
     * Type of active message box (".ach" or ".pm").
     *
     * @var string Message box file ending
     */
    private string $boxType = '.pm';

    /**
     * Sets mode, PM (box) ID and type.
     *
     * @param string $mode PM mode
     */
    function __construct(string $mode='overview')
    {
        parent::__construct();
        $this->mode = $mode;
        $this->pmBoxID = intval(Functions::getValueFromGlobals('pmbox_id')) ?: Auth::getInstance()->getUserID();
        $this->pmID = intval(Functions::getValueFromGlobals('pm_id'));
        if($this->isOutbox = Functions::getValueFromGlobals('box') == 'out')
        {
            $this->urlSuffix = '&amp;box=out';
            $this->boxType = '.ach';
        }
    }

    /**
     * Executes pm mode.
     */
    public function publicCall(): void
    {
        NavBar::getInstance()->addElement(Language::getInstance()->getString('pms'), INDEXFILE . '?faction=pm&amp;mode=overview' . $this->urlSuffix . SID_AMPER);
        if(!Auth::getInstance()->isLoggedIn())
            Template::getInstance()->printMessage('login_only', INDEXFILE . '?faction=register' . SID_AMPER, INDEXFILE . '?faction=login' . SID_AMPER);
        elseif($this->pmBoxID != Auth::getInstance()->getUserID())
            Template::getInstance()->printMessage('pm_no_access');
        switch($this->mode)
        {
//PrivateMessageViewPM
            case 'view':
            $found = false;
            $pms = ($pms = @Functions::file('members/' . $this->pmBoxID . $this->boxType)) == false ? [] : array_reverse($pms);
            foreach($pms as &$curPM)
            {
                $curPM = Functions::explodeByTab($curPM);
                //Search for target pm
                if($curPM[0] == $this->pmID)
                {
                    NavBar::getInstance()->addElement($curPM[1], INDEXFILE . '?faction=pm&amp;mode=view&amp;pm_id=' . $this->pmID . '&amp;pmbox_id=' . $this->pmBoxID . $this->urlSuffix . SID_AMPER);
                    //Remove unread flag, if needed
                    if($curPM[7] == '1')
                    {
                        $curPM[7] = '0';
                        //Implode for file write
                        $curPM = Functions::implodeByTab($curPM);
                        Functions::file_put_contents('members/' . $this->pmBoxID . $this->boxType, implode("\n", array_reverse($pms)) . "\n");
                        //Undo implode for file write
                        $curPM = Functions::explodeByTab($curPM);
                    }
                    //Proceed with data preparation
                    $curPM[2] = BBCode::getInstance()->parse($curPM[2], false, $curPM[5] == '1', $curPM[6] == '1');
                    $curPM[3] = Functions::getProfileLink($curPM[3], true);
                    $curPM[4] = Functions::formatDate($curPM[4]);
                    Template::getInstance()->assign('pm', $curPM);
                    $found = true;
                    break;
                }
                else
                    //Always implode back in case of updating any unread flags
                    $curPM = Functions::implodeByTab($curPM);
            }
            if(!$found)
                Template::getInstance()->printMessage('pm_not_found');
            break;

//PrivateMessageNewPM
            case 'reply':
            //Replying is just quoting, ergo look up quoted PM and go straight on into "send" mode (hence no break statement or nav bar)
            foreach(array_reverse(Functions::file('members/' . $this->pmBoxID . '.pm')) as $curPM)
            {
                $curPM = Functions::explodeByTab($curPM);
                if($curPM[0] == $this->pmID)
                {
                    $recipientID = $curPM[3];
                    $newPM = &$curPM;
                    $newPM[0] = -1; //Remove old ID for new PM
                    //Update title
                    $newPM[1] = Language::getInstance()->getString('re_colon') . $newPM[1];
                    //Insert quoted text with BBCode
                    $newPM[2] = '[quote' . (($newPM[3] = Functions::getUserData($newPM[3])) !== false ? '=' . $newPM[3][0] : '') . ']' . Functions::br2nl($newPM[2]) . '[/quote]';
                    $newPM[3] = $this->pmBoxID; //Update sender ID
                    $newPM[4] = ''; //Remove old date
                    //Do not change the used BBCode and smiley settings
                    $newPM[7] = '1'; //Update unread flag
                    break;
                }
            }

//PrivateMessageNewPM
            case 'send':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('new_pm'), INDEXFILE . '?faction=pm&amp;pmbox_id=' . $this->pmBoxID . '&amp;mode=send' . SID_AMPER);
            if(!isset($newPM))
                $newPM = array(-1,
                    htmlspecialchars(trim(Functions::getValueFromGlobals('betreff'))),
                    htmlspecialchars(trim(Functions::getValueFromGlobals('pm', false))),
                    $this->pmBoxID,
                    '',
                    Functions::getValueFromGlobals('smilies') == '1',
                    Functions::getValueFromGlobals('use_upbcode') == '1',
                    '1',
                    '');
            $storeToOutbox = Functions::getValueFromGlobals('storeToOutbox') == 'true';
            $errors = [];
            if(!isset($recipientID))
                $recipientID = Functions::getValueFromGlobals('target_id');
            //Send PM?
            if(Functions::getValueFromGlobals('send') == 'yes')
            {
                $recipient = Functions::getUserData($recipientID);
                if($recipient == false)
                    $errors[] = Language::getInstance()->getString('recipient_does_not_exist');
                else
                    $recipient = array_slice($recipient, 0, 15); //Cut off not needed infos
                if($newPM[1] == '')
                    $errors[] = Language::getInstance()->getString('please_enter_a_subject');
                if(empty($errors))
                {
                    //Confirmed?
                    if(Functions::getValueFromGlobals('check') == 'yes')
                    {
                        $newPM[2] = Functions::nl2br($newPM[2]);
                        $newPM[4] = gmdate('YmdHis');
                        //Detect new PM ID
                        $recipientLastPM = @Functions::explodeByTab(array_pop(Functions::file('members/' . $recipient[1] . '.pm')));
                        $recipientUnreadPMs;
                        if(empty($recipientLastPM[0]))
                        {
                            $newPM[0] = 1;
                            $recipientUnreadPMs = false;
                        }
                        else
                        {
                            $newPM[0] = $recipientLastPM[0]+1;
                            $recipientUnreadPMs = $recipientLastPM[7] == '1';
                        }
                        Functions::file_put_contents('members/' . $recipient[1] . '.pm', Functions::implodeByTab($newPM) . "\n", FILE_APPEND);
                        //Notify recipient via email by having no other unread PMs
                        if(!$recipientUnreadPMs && $recipient[14][0] == '1')
                            Functions::sendMessage($recipient[3], 'pm_from_user', $recipient[0], Auth::getInstance()->getUserNick(), $newPM[1], Config::getInstance()->getCfgVal('address_to_forum') . '/' . INDEXFILE . '?faction=pm&mode=view&pm_id=' . $newPM[0] . '&pmbox_id=' . $recipient[1]);
                        //Handle outbox
                        if($storeToOutbox)
                        {
                            //Detect new PM ID for outbox
                            $newPM[0] = ($newPM[0] = @Functions::file('members/' . $this->pmBoxID . '.ach')) == false ? 1 : current(Functions::explodeByTab(array_pop($newPM[0])))+1;
                            $newPM[3] = $recipient[1]; //Change sender to recipient
                            $newPM[7] = '0'; //Do not use unread flag
                            Functions::file_put_contents('members/' . $this->pmBoxID . '.ach', Functions::implodeByTab($newPM) . "\n", FILE_APPEND);
                        }
                        //Done
                        Logger::getInstance()->log('%s sent PM to ' . $recipient[0] . ' (ID: ' . $recipient[1] . ')', Logger::LOG_USER_TRAFFIC);
                        Functions::skipConfirmMessage(INDEXFILE . '?faction=pm&pmbox_id=' . $this->pmBoxID . SID_AMPER_RAW);
                        Template::getInstance()->printMessage('pm_sent', INDEXFILE . '?faction=pm&amp;pmbox_id=' . $this->pmBoxID . SID_AMPER, Functions::getMsgBackLinks());
                    }
                    //Get confirmation
                    else
                    {
                        NavBar::getInstance()->addElement(Language::getInstance()->getString('confirmation'));
                        $this->mode = 'PrivateMessageNewPMConfirmSend';
                        $recipientID = &$recipient;
                    }
                }
            }
            //Set default options on calling new PM page the first time
            else
                $newPM[5] = $newPM[6] = $storeToOutbox = true;
            Template::getInstance()->assign(array('newPM' => $newPM,
                'recipient' => $recipientID,
                'errors' => $errors,
                'isMod' => Auth::getInstance()->isAdmin() || Auth::getInstance()->isMod(), //Needed for smilies
                'storeToOutbox' => $storeToOutbox));
            break;

//PrivateMessageConfirmDelete
            case 'kill':
            if(Functions::getValueFromGlobals('kill') != 'yes')
            {
                //Retrieve pm title
                foreach(array_reverse(Functions::file('members/' . $this->pmBoxID . $this->boxType)) as $curPM)
                {
                    $curPM = Functions::explodeByTab($curPM);
                    if($curPM[0] == $this->pmID)
                    {
                        NavBar::getInstance()->addElement(array(
                            array($curPM[1], INDEXFILE . '?faction=pm&amp;mode=view&amp;pm_id=' . $this->pmID . '&amp;pmbox_id=' . $this->pmBoxID . $this->urlSuffix . SID_AMPER),
                            array(Language::getInstance()->getString('delete_pm'))));
                        Template::getInstance()->assign('pmTitle', $curPM[1]);
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
                $toDelete = array_keys(($toDelete = Functions::getValueFromGlobals('deletepm')) != '' ? $toDelete : []);
            if(!empty($toDelete))
            {
                $size = count($pms = $this->getPMs($this->boxType));
                for($i=0; $i<$size; $i++)
                    if(in_array($pms[$i][0], $toDelete))
                        unset($pms[$i]);
                Functions::file_put_contents('members/' . $this->pmBoxID . $this->boxType, empty($pms) ? '' : implode("\n", array_map(['Functions', 'implodeByTab'], $pms)) . "\n");
            }
            header('Location: ' . INDEXFILE . '?faction=pm&profile_id=' . Auth::getInstance()->getUserID() . ($this->isOutbox ? '&box=out' : '') . SID_AMPER_RAW);
            Template::getInstance()->assign('pmBoxID', $this->pmBoxID);
            Template::getInstance()->printMessage('selected_pms_deleted');
            break;

//PrivateMessageIndex
            case 'pm':
            case 'overview':
            default:
            $pms = ($pms = @Functions::file('members/' . $this->pmBoxID . $this->boxType)) == false ? [] : array_reverse($pms);
            foreach($pms as &$curPM)
            {
                $curPM = Functions::explodeByTab($curPM);
                $curPM[3] = Functions::getProfileLink($curPM[3], true);
                $curPM[4] = Functions::formatDate($curPM[4]);
            }
            Template::getInstance()->assign('pms', $pms);
            break;
        }
        Template::getInstance()->printPage(Functions::handleMode($this->mode, self::$modeTable, __CLASS__), array('pmBoxID' => $this->pmBoxID,
            'pmID' => $this->pmID,
            'isOutbox' => $this->isOutbox,
            'urlSuffix' => $this->urlSuffix));
    }

    /**
     * Returns current and fully exploded PMs from user.
     *
     * @param string $boxType PM box type file ending
     * @return array All saved PMs from current user
     */
    private function getPMs(string $boxType): array
    {
        return ($pms = @Functions::file('members/' . $this->pmBoxID . $boxType)) == false ? [] : array_map(['Functions', 'explodeByTab'], $pms);
    }

    /**
     * Return amount of unread private messages.
     *
     * @return int Amount of unread PMs
     */
    public function getUnreadPMs(): int
    {
        $unread = 0;
        if(Auth::getInstance()->isLoggedIn())
            foreach($this->getPMs('.pm') as $curPM)
                if($curPM[7] == '1')
                    $unread++;
        return $unread;
    }

    /**
     * Returns to remind the user to check for new pms.
     *
     * @return bool Remind the user to check his pm box
     */
    public function isRemind(): bool
    {
        if(!isset($_SESSION['lastUnreadReminder']) || time() > $_SESSION['lastUnreadReminder']+Config::getInstance()->getCfgVal('new_pm_reminder'))
        {
            $_SESSION['lastUnreadReminder'] = time();
            return true;
        }
        return false;
    }
}
?>