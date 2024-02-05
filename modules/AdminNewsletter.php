<?php
/**
 * Sends newsletter via PM or email.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2023 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class AdminNewsletter extends PublicModule
{
    use Singleton, Mode;

    /**
     * Translates a mode to its template file.
     *
     * @var array Mode and template counterparts
     */
    private static array $modeTable = ['ad_newsletter' => 'AdminNewsletter',
        'accept' => 'AdminNewsletterConfirm'];

    /**
     * Maximal execution time for sending newsletter to require a break to continue.
     *
     * @var int Seconds timeout
     */
    private int $timeout;

    /**
     * Sets mode and timeout.
     *
     * @param string $mode Mode
     */
    function __construct(string $mode)
    {
        parent::__construct();
        $this->mode = $mode;
        $this->timeout = ini_get('max_execution_time');
        if($this->timeout > 10)
            $this->timeout -= 10;
        PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_NEWSLETTER_INIT);
    }

    /**
     * Checks current execution time of the sending process and reloads it, if needed.
     *
     * @param bool $check Check the run time or reload script anyway
     */
    private function checkTime(bool $check=true): void
    {
        //Check execution time limit
        if(!$check || microtime(true)-SCRIPTSTART > $this->timeout)
        {
            header('Location: ' . INDEXFILE . '?faction=ad_newsletter&mode=send' . SID_AMPER_RAW);
            exit('<a href="' . INDEXFILE . '?faction=ad_newsletter&amp;mode=send' . SID_AMPER . '">Go on</a>');
        }
    }

    /**
     * Executes mode.
     */
    public function publicCall(): void
    {
        Functions::accessAdminPanel();
        NavBar::getInstance()->addElement(Language::getInstance()->getString('send_newsletter'), INDEXFILE . '?faction=ad_newsletter' . SID_AMPER);
        switch($this->mode)
        {
            case 'send':
            if(isset($_SESSION['newsletter']))
            {
                //Dispatch done?
                if(empty($_SESSION['newsletter']['recipients']))
                {
                    Logger::getInstance()->log('%s sent newsletter (' . $_SESSION['newsletter']['dispatch'] . ', ' . $_SESSION['newsletter']['recipient'] . ')', Logger::LOG_ACP_ACTION);
                    $sent = $_SESSION['newsletter']['sent'];
                    unset($_SESSION['newsletter']);
                    Template::getInstance()->printMessage('newsletter_sent_to_x_user', $sent);
                }
                //Ongoing dispatch
                if($_SESSION['newsletter']['dispatch'] == 1)
                    while(!empty($_SESSION['newsletter']['recipients']))
                    {
                        if(Functions::mail(array_shift($_SESSION['newsletter']['recipients']), $_SESSION['newsletter']['subject'], $_SESSION['newsletter']['message']))
                            $_SESSION['newsletter']['sent']++;
                        $this->checkTime();
                    }
                else
                    while(!empty($_SESSION['newsletter']['recipients']))
                    {
                        $curRecipientID = array_shift($_SESSION['newsletter']['recipients']);
                        //Build current PM
                        $curPM = [(int) @current(Functions::explodeByTab(array_pop(Functions::file('members/' . $curRecipientID . '.pm')) ?? ''))+1,
                            htmlspecialchars($_SESSION['newsletter']['subject']),
                            Functions::nl2br(htmlspecialchars($_SESSION['newsletter']['message'])),
                            Auth::getInstance()->getUserID(),
                            gmdate('YmdHis'),
                            '0',
                            '0',
                            '1',
                            ''];
                        Functions::file_put_contents('members/' . $curRecipientID . '.pm', Functions::implodeByTab($curPM) . "\n", FILE_APPEND);
                        $_SESSION['newsletter']['sent']++;
                        $this->checkTime();
                    }
                //Sending done
                $this->checkTime(false);
            }
            //Prepare sending
            $recipient = intval(Functions::getValueFromGlobals('target'));
            $recipients = [];
            switch($recipient)
            {
                //All members
                case 1:
                foreach(Functions::glob(DATAPATH . 'members/[!0t]*.xbb') as $curMember)
                {
                    $curMember = Functions::getUserData(basename($curMember, '.xbb'));
                    if($curMember[4] != '5' && $curMember[14][0] == '1')
                        $recipients[$curMember[1]] = $curMember[3];
                }
                break;

                //Only (s)mods
                case 2:
                foreach(Functions::glob(DATAPATH . 'members/[!0t]*.xbb') as $curMember)
                {
                    $curMember = Functions::getUserData(basename($curMember, '.xbb'));
                    if($curMember[4] == '2' || $curMember[4] == '6')
                        $recipients[$curMember[1]] = $curMember[3];
                }
                break;

                //Only admins
                case 3:
                foreach(Functions::glob(DATAPATH . 'members/[!0t]*.xbb') as $curMember)
                {
                    $curMember = Functions::getUserData(basename($curMember, '.xbb'));
                    if($curMember[4] == '1')
                        $recipients[$curMember[1]] = $curMember[3];
                }
                break;
            }
            //If recipient is just the sending admin, cancel sending
            if(empty($recipients) || (count($recipients) == 1 && isset($recipients[Auth::getInstance()->getUserID()])))
                Template::getInstance()->printMessage('no_recipients_found');
            //Compile send parameter
            $dispatch = intval(Functions::getValueFromGlobals('sendmethod'));
            $_SESSION['newsletter'] = ['dispatch' =>  $dispatch,
                'recipient' => $recipient,
                'recipients' => $dispatch == 1 ? array_values($recipients) : array_keys($recipients),
                'subject' => Functions::getValueFromGlobals('betreff'),
                'message' => Functions::str_replace("\r", '', Functions::getValueFromGlobals('newsletter', false)),
                'sent' => 0];
            //Store in archive?
            if(Functions::getValueFromGlobals('isArchived') == 'true')
                Functions::file_put_contents('vars/newsletter.var', gmdate('YmdHis') . "\t" . Auth::getInstance()->getUserID() . "\t" . htmlspecialchars($_SESSION['newsletter']['subject']) . "\t" . Functions::nl2br(htmlspecialchars($_SESSION['newsletter']['message'])) . "\t" . $recipient . "\n", FILE_APPEND);
            //Point of no return
            $this->checkTime(false);
            break;

//AdminNewsletterConfirm
            case 'accept':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('confirmation'), INDEXFILE . '?faction=ad_newsletter&amp;mode=accept' . SID_AMPER);
            Template::getInstance()->assign(['recipient' => intval(Functions::getValueFromGlobals('target')),
                'dispatch' => intval(Functions::getValueFromGlobals('sendmethod')),
                'subject' => htmlspecialchars(trim(Functions::getValueFromGlobals('betreff'))),
                'message' => htmlspecialchars(trim(Functions::getValueFromGlobals('newsletter', false))),
                'isArchived' => Functions::getValueFromGlobals('isArchived') == 'true']);
            break;
        }
        Template::getInstance()->printPage(self::$modeTable[$this->mode]);
    }
}
?>