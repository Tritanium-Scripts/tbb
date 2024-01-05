<?php
/**
 * Manages registrations of new user.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2023 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class Register extends PublicModule
{
    use Singleton, Mode, Errors;

    /**
     * If passwords for new user should be created automatically.
     *
     * @var bool User may not choose an own password
     */
    private bool $createRegPass;

    /**
     * Link to privacy policy to be accepted by new users.
     *
     * @var string Privacy policy link
     */
    private string $privacyPolicyLink;

    /**
     * Amount of registered members.
     *
     * @var LockObject Amount of members
     */
    private $memberCounter;

    /**
     * Translates a mode to its template file.
     *
     * @var array Mode and template counterparts
     */
    private static array $modeTable = array('' => 'Register', 'register' => 'Register', 'createuser' => 'Register', 'verifyAccount' => 'RegisterVerification');

    /**
     * Provides named keys for new user data.
     *
     * @var array Named keys
     */
    private static array $newUserKeys = array('nick', 'mail', 'homepage', 'realName', 'icq', 'signature');

    /**
     * Sets privacy policy link, member counter and mode.
     *
     * @param string $mode Registration mode
     */
    function __construct(string $mode)
    {
        parent::__construct();
        $this->createRegPass = Config::getInstance()->getCfgVal('create_reg_pw') == 1;
        $this->privacyPolicyLink = Config::getInstance()->getCfgVal('privacy_policy_link');
        if($this->privacyPolicyLink == '?faction=gdpr')
            $this->privacyPolicyLink = INDEXFILE . $this->privacyPolicyLink . SID_AMPER;
        $this->memberCounter = Functions::getLockObject('vars/member_counter.var');
        $this->mode = $mode;
    }

    /**
     * Performs new registrations.
     */
    public function publicCall(): void
    {
        NavBar::getInstance()->addElement(Language::getInstance()->getString('register'), INDEXFILE . '?faction=register' . SID_AMPER);
        if(Config::getInstance()->getCfgVal('activate_registration') != 1)
            Template::getInstance()->printMessage('registration_deactivated');
        elseif($this->memberCounter->getFileContent() >= Config::getInstance()->getCfgVal('max_registrations') && Config::getInstance()->getCfgVal('max_registrations') != -1)
            Template::getInstance()->printMessage('no_more_registrations');
        //If user is already logged in
        elseif(Auth::getInstance()->isLoggedIn())
        {
            Logger::getInstance()->log('%s tried to register again', Logger::LOG_REGISTRATION);
            header('Location: ' . INDEXFILE . SID_QMARK);
            Template::getInstance()->printMessage('already_registered', Functions::getMsgBackLinks());
        }
        switch($this->mode)
        {
//Register
            case 'createuser':
            $newUser = array_combine(self::$newUserKeys, array(trim(Functions::getValueFromGlobals('newuser_name')),
                trim(Functions::getValueFromGlobals('newuser_email')),
                trim(Functions::getValueFromGlobals('newuser_hp')),
                htmlspecialchars(trim(Functions::getValueFromGlobals('newuser_realname'))),
                trim(Functions::getValueFromGlobals('newuser_icq')),
                htmlspecialchars(trim(Functions::nl2br(Functions::getValueFromGlobals('newuser_signatur', false))))));
            //A lot of checking...
            if(empty($newUser['nick']))
                $this->errors[] = Language::getInstance()->getString('please_enter_an_user_name');
            elseif(Functions::strlen($newUser['nick']) > 15)
                $this->errors[] = Language::getInstance()->getString('the_user_name_is_too_long');
            elseif(Functions::unifyUserName($newUser['nick']))
                $this->errors[] = Language::getInstance()->getString('the_user_name_already_exists');
            else
                $newUser['nick'] = htmlspecialchars($newUser['nick']);
            if(!$this->createRegPass)
            {
                //In case of not creating a pass for new user, check the given one, too
                if(($newPass = Functions::getValueFromGlobals('newuser_pw1')) == '')
                    $this->errors[] = Language::getInstance()->getString('please_enter_a_password');
                elseif($newPass != Functions::getValueFromGlobals('newuser_pw2'))
                    $this->errors[] = Language::getInstance()->getString('passwords_do_not_match');
                else
                    //If ok, hash it - the original pw is not longer needed to know
                    $newPass = Functions::getHash($newPass);
            }
            else
                //In case of creating a pass for new user, get it here, but don't hash it yet
                $newPass = Functions::getRandomPass();
            if(empty($newUser['mail']))
                $this->errors[] = Language::getInstance()->getString('please_enter_your_mail');
            elseif(!Functions::isValidMail($newUser['mail']))
                $this->errors[] = Language::getInstance()->getString('please_enter_a_valid_mail');
            elseif(Functions::unifyUserMail($newUser['mail']))
                $this->errors[] = Language::getInstance()->getString('the_mail_address_already_exists');
            if(!empty($newUser['icq']) && !is_numeric($newUser['icq']))
                $this->errors[] = Language::getInstance()->getString('please_enter_a_valid_icq_number');
            if(Functions::getValueFromGlobals('regeln') != 'yes')
                $this->errors[] = Language::getInstance()->getString('you_have_to_accept_board_rules');
            if(!empty($this->privacyPolicyLink) && Functions::getValueFromGlobals('privacyPolicy') != 'yes')
                $this->errors[] = Language::getInstance()->getString('you_have_to_accept_privacy_policy');
            if(empty($this->errors))
            {
                //Detect new ID
                $lockObj = Functions::getLockObject('vars/last_user_id.var');
                $newUserID = $lockObj->getFileContent()+1;
                //Prepare contents of new member file
                $newMemberFile = array($newUser['nick'],
                    $newUserID,
                    !$this->createRegPass ? $newPass : Functions::getHash($newPass),
                    $newUser['mail'],
                    $newUserID == 1 ? '1' : '3', //First user is admin
                    '0',
                    date('YmdHis'),
                    $newUser['signature'],
                    '',
                    $newUser['homepage'],
                    '',
                    '0',
                    $newUser['realName'],
                    $newUser['icq'],
                    '1,1',
                    '',
                    //New TBB 1.5 values
                    time(),
                    '',
                    '',
                    '',
                    '',
                    '');
                //Register as new member only, if no mail validation is required
                if(Config::getInstance()->getCfgVal('confirm_reg_mail') != 1)
                {
                    Functions::file_put_contents('members/' . $newUserID . '.xbb', implode("\n", $newMemberFile));
                    Functions::file_put_contents('members/' . $newUserID . '.pm', '');
                    $lockObj->setFileContent($newUserID);
                    $this->memberCounter->setFileContent($this->memberCounter->getFileContent()+1);
                    //Send reg mail (and random pass, if needed)
                    Functions::sendMessage($newMemberFile[3], 'new_registration', $newMemberFile[0], Config::getInstance()->getCfgVal('forum_name'), $newMemberFile[1], $newMemberFile[3], $this->createRegPass ? $newPass : Language::getInstance()->getString('already_set_by_yourself'), Config::getInstance()->getCfgVal('address_to_forum') . '/' . INDEXFILE);
                    Logger::getInstance()->log('New registration: ' . $newMemberFile[0] . ' (ID: ' . $newMemberFile[1] . ')', Logger::LOG_REGISTRATION);
                    //Notify admin
                    if(Config::getInstance()->getCfgVal('mail_admin_new_registration') == 1)
                        Functions::sendMessage(Config::getInstance()->getCfgVal('admin_email'), 'admin_new_registration', Config::getInstance()->getCfgVal('forum_name'), $newMemberFile[0], $newMemberFile[1], $newMemberFile[3]);
                    if($this->createRegPass)
                        Template::getInstance()->printMessage('registration_successful_pass', $newMemberFile[0]);
                    else
                        Template::getInstance()->printMessage('registration_successful_plain', $newMemberFile[0], INDEXFILE . '?faction=login' . SID_AMPER);
                }
                //Save data only temporarily until mail addy is confirmed
                else
                {
                    Functions::file_put_contents('members/temp' . $newMemberFile[16] . '.xbb', implode("\n", $newMemberFile));
                    Functions::sendMessage($newMemberFile[3], 'validate_new_registration', $newMemberFile[0], Config::getInstance()->getCfgVal('forum_name'), Config::getInstance()->getCfgVal('address_to_forum') . '/' . INDEXFILE . '?faction=register&mode=verifyAccount&code=' . md5('temp' . $newMemberFile[16]), Config::getInstance()->getCfgVal('address_to_forum') . '/' . INDEXFILE . '?faction=register&mode=verifyAccount', md5('temp' . $newMemberFile[16]));
                    Logger::getInstance()->log('New registration waiting for mail validation: ' . $newMemberFile[0] . ' (preliminary ID: temp' . $newMemberFile[16] . ')', Logger::LOG_REGISTRATION);
                    Template::getInstance()->printMessage('registration_successful_mail', $newMemberFile[0]);
                }
            }
            break;

//RegisterVerification
            case 'verifyAccount':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('activate_account'), INDEXFILE . '?faction=register&amp;mode=verifyAccount' . SID_AMPER);
            if(($code = Functions::getValueFromGlobals('code')) != '')
            {
                foreach(glob(DATAPATH . 'members/temp*.xbb') as $curPreMember)
                    if($code == md5(basename($curPreMember, '.xbb')))
                    {
                        //Get temporarily data of verfied member
                        $newMemberFile = Functions::file($curPreMember, null, null, false);
                        //Generate password, if needed
                        if($this->createRegPass)
                            $newMemberFile[2] = Functions::getHash($newPass = Functions::getRandomPass());
                        //Update last seen
                        $newMemberFile[16] = time();
                        //Detect new ID
                        $lockObj = Functions::getLockObject('vars/last_user_id.var');
                        $newUserID = $lockObj->getFileContent()+1;
                        //Apply to new member: update current one
                        $newMemberFile[1] = $newUserID;
                        //Write confirmed data with new ID (and new pass, if needed)
                        Functions::file_put_contents('members/' . $newUserID . '.xbb', implode("\n", $newMemberFile));
                        Functions::file_put_contents('members/' . $newUserID . '.pm', '');
                        $lockObj->setFileContent($newUserID);
                        $this->memberCounter->setFileContent($this->memberCounter->getFileContent()+1);
                        //Delete old temporarily data
                        Functions::unlink($curPreMember);
                        //Send default reg mail (and random pass, if needed)
                        Functions::sendMessage($newMemberFile[3], 'new_registration', $newMemberFile[0], Config::getInstance()->getCfgVal('forum_name'), $newMemberFile[1], $newMemberFile[3], $this->createRegPass ? $newPass : Language::getInstance()->getString('already_set_by_yourself'), Config::getInstance()->getCfgVal('address_to_forum') . '/' . INDEXFILE);
                        Logger::getInstance()->log('New registration verified: ' . $newMemberFile[0] . ' (ID: ' . $newMemberFile[1] . ')', Logger::LOG_REGISTRATION);
                        //Notify admin
                        if(Config::getInstance()->getCfgVal('mail_admin_new_registration') == 1)
                            Functions::sendMessage(Config::getInstance()->getCfgVal('admin_email'), 'admin_new_registration', Config::getInstance()->getCfgVal('forum_name'), $newMemberFile[0], $newMemberFile[1], $newMemberFile[3]);
                        if($this->createRegPass)
                            Template::getInstance()->printMessage('registration_successful_pass', $newMemberFile[0]);
                        else
                            Template::getInstance()->printMessage('registration_successful_plain', $newMemberFile[0], INDEXFILE . '?faction=login' . SID_AMPER);
                    }
                $this->errors[] = Language::getInstance()->getString('no_account_for_code_found');
            }
            elseif(isset($_POST['verify']))
                $this->errors[] = Language::getInstance()->getString('please_enter_your_code');
            $newUser = array('code' => $code);
            break;

//Register
            case 'register':
            default:
            $newUser = array_combine(self::$newUserKeys, array('', '', '', '', '', ''));
            break;
        }
        Template::getInstance()->printPage(Functions::handleMode($this->mode, self::$modeTable, __CLASS__), array('newUser' => $newUser,
            'errors' => $this->errors,
            'rulesLink' => INDEXFILE . '?faction=regeln' . SID_AMPER,
            'privacyPolicyLink' => $this->privacyPolicyLink));
    }
}
?>