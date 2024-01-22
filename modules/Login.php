<?php
/**
 * Manages the login, request of new password and logout.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2023 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class Login extends PublicModule
{
    use Singleton, Mode, Errors;

    /**
     * Entered login name.
     *
     * @var string Login name
     */
    private string $loginName;

    /**
     * Entered password.
     *
     * @var string Password
     */
    private string $loginPass;

    /**
     * Translates a mode to its template file.
     *
     * @var array Mode and template counterparts
     */
    private static array $modeTable = ['' => 'Login',
        'login' => 'Login',
        'verify' => 'Login',
        'sendpw' => 'RequestPassword'];

    /**
     * Prepares and sets login name, login password and mode.
     *
     * @param string $fAction Mode to execute
     */
    function __construct(string $fAction)
    {
        parent::__construct();
        $this->loginName = Functions::latin9ToEntities(htmlspecialchars(trim(Functions::getValueFromGlobals('login_name'))));
        $this->loginPass = Functions::getValueFromGlobals('login_pw');
        $this->mode = $fAction;
    }

    /**
     * Performs login, sending new password or logout.
     */
    public function publicCall(): void
    {
        NavBar::getInstance()->addElement(Language::getInstance()->getString('login'), INDEXFILE . '?faction=login' . SID_AMPER);
        //If user is already logged in
        if(Auth::getInstance()->isLoggedIn() && $this->mode == 'login')
        {
            Logger::getInstance()->log('%s tried to log in again', Logger::LOG_FAILED_LOGIN);
            header('Location: ' . INDEXFILE . SID_QMARK);
            Template::getInstance()->printMessage('already_logged_in', Functions::getMsgBackLinks());
        }
        //Obsolete check?
        if(Functions::file_exists('vars/alarm.var'))
            Template::getInstance()->printMessage('board_disabled');
//Login
        switch($this->mode)
        {
            //Check login data
            case 'verify':
            if(empty($this->loginName))
                $this->errors[] = Language::getInstance()->getString('please_enter_your_user_name');
            if(empty($this->loginPass))
                $this->errors[] = Language::getInstance()->getString('please_enter_your_password');
            if(empty($this->errors))
            {
                //Prerequisite are met, prepare data
                $this->loginName = Functions::strtolower($this->loginName);
                $this->loginPass = Functions::getHash($this->loginPass);
                $found = false;
                //Start crawling by ignoring XBB files with leading zeros (=skip guest) and temporary ones
                foreach(Functions::glob(DATAPATH . 'members/[!0t]*.xbb') as $curMember)
                {
                    $curMember = Functions::file($curMember, null, null, false);
                    if($this->loginName == Functions::strtolower($curMember[0]))
                    {
                        $found = true;
                        //Deleted user
                        if($curMember[4] == '5')
                        {
                            $this->errors[] = Language::getInstance()->getString('user_not_found');
                            $this->loginName = $curMember[0]; //Undo strtolower for template and log
                            Logger::getInstance()->log('Login with deleted user "' . $curMember[0] . '" (ID: ' . $curMember[1] . ') failed', Logger::LOG_FAILED_LOGIN);
                            break;
                        }
                        //Don't allow login for non-admins in case of active maintenance mode
                        elseif(Config::getInstance()->getCfgVal('uc') == 1 && $curMember[4] != '1')
                            Template::getInstance()->printMessage('maintenance_mode_on');
                        //Wrong password
                        elseif(!in_array($this->loginPass, ($curPasses = (Functions::explodeByTab($curMember[2] . "\t"))))) //Attach additional tab to make sure [1] is set in any case
                        {
                            $this->errors[] = Language::getInstance()->getString('wrong_password');
                            $this->loginName = $curMember[0]; //Undo strtolower for template
                            Logger::getInstance()->log('Login with wrong password for user "' . $curMember[0] . '" (ID: ' . $curMember[1] . ') failed', Logger::LOG_FAILED_LOGIN);
                            break;
                        }
                        //All ok, do login
                        else
                        {
                            //Update last seen value
                            $curMember[16] = time();
                            //Remove custom tpls and styles, if it was prohibited in the meantime
                            if(Config::getInstance()->getCfgVal('select_tpls') != 1 && isset($curMember[20]) && !empty($curMember[20]))
                                $curMember[20] = '';
                            //Also check if style was not found for current tpl
                            if(isset($curMember[21]) && !empty($curMember[21]) && (!file_exists(Template::getInstance()->getTplDir() . 'styles/' . $curMember[21]) || Config::getInstance()->getCfgVal('select_styles') != 1))
                                $curMember[21] = '';
                            //Set a new requested password as new default one
                            if($this->loginPass == $curPasses[1])
                            {
                                $curMember[2] = $curPasses[1];
                                Logger::getInstance()->log('Requested password set as new one for "' . $curMember[0] . '" (ID: ' . $curMember[1] . ')', Logger::LOG_NEW_PASSWORD);
                            }
                            Functions::file_put_contents('members/' . $curMember[1] . '.xbb', implode("\n", $curMember));
                            //Login session-based
                            $_SESSION['userID'] = $curMember[1];
                            $_SESSION['userHash'] = $this->loginPass;
                            //Login cookie-based
                            setcookie('cookie_xbbuser', $curMember[1] . "\t" . $this->loginPass, Functions::getValueFromGlobals('stayli') == 'yes' ? time()+60*60*24*365 : 0, Config::getInstance()->getCfgVal('path_to_forum'));
                            //Delete guest ID from WIO to work with user ID from now on
                            WhoIsOnline::getInstance()->delete($_SESSION['session_upbwio']);
                            //Set ghost state
                            if(Functions::getValueFromGlobals('bewio') == 'yes')
                                $_SESSION['bewio'] = true;
                            Auth::getInstance()->loginChanged();
                            //That's it
                            Logger::getInstance()->log($curMember[0] . ' (ID: ' . $curMember[1] . ') logged in', Logger::LOG_LOGIN_LOGOUT);
                            //Detect location to redir
                            $location = $curMember[11] == '1' ? INDEXFILE . '?faction=profile&mode=edit' . SID_AMPER_RAW : (isset($_COOKIE['upbwhere']) && !empty($_COOKIE['upbwhere']) ? $_COOKIE['upbwhere'] : INDEXFILE . SID_QMARK);
                            header('Location: ' . $location);
                            Template::getInstance()->printMessage('successfully_logged_in', $location);
                        }
                    }
                }
                //Not found in "member DB"
                if(!$found)
                {
                    $this->errors[] = Language::getInstance()->getString('user_not_found');
                    Logger::getInstance()->log('Login with unknown user "' . $this->loginName . '" failed', Logger::LOG_FAILED_LOGIN);
                }
            }
            break;

//RequestPassword
            case 'sendpw':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('request_new_password'), INDEXFILE . '?faction=sendpw' . SID_AMPER);
            if(Config::getInstance()->getCfgVal('activate_mail') != 1)
                Template::getInstance()->printMessage('function_deactivated');
            $this->loginName = Functions::getValueFromGlobals('nick');
            if(Functions::getValueFromGlobals('send') == '1')
            {
                if(empty($this->loginName))
                    $this->errors[] = Language::getInstance()->getString('please_enter_your_user_name');
                else
                {
                    //Prerequisites are met, prepare data and start crawling
                    $this->loginName = Functions::strtolower($this->loginName);
                    foreach(Functions::glob(DATAPATH . 'members/[!0t]*.xbb') as $curMember)
                    {
                        $curMember = Functions::file($curMember);
                        if($this->loginName == Functions::strtolower($curMember[0]))
                        {
                            $curMember[2] = current(Functions::explodeByTab($curMember[2])) . "\t" . Functions::getHash($newPass = Functions::getRandomPass());
                            Functions::file_put_contents('members/' . $curMember[1] . '.xbb', implode("\n", $curMember));
                            if(!Functions::sendMessage($curMember[3], 'new_password_requested', $_SERVER['REMOTE_ADDR'], Functions::getValueFromGlobals('nick'), $newPass, Config::getInstance()->getCfgVal('address_to_forum') . '/' . INDEXFILE . '?faction=login'))
                                Template::getInstance()->printMessage('sending_mail_failed');
                            Logger::getInstance()->log('New password requested and sent to "' . $curMember[0] . '" (ID: ' . $curMember[1] . ')', Logger::LOG_NEW_PASSWORD);
                            Template::getInstance()->printMessage('new_password_created');
                        }
                    }
                    $this->errors[] = Language::getInstance()->getString('user_not_found');
                    Logger::getInstance()->log('New password request for unknown user "' . $this->loginName . '" failed', Logger::LOG_NEW_PASSWORD);
                }
            }
            else
                //In case the nick was submitted from login form (send != 1) additional decode is needed
                $this->loginName = urldecode($this->loginName);
            break;

//Logout
            case 'logout':
            if(Auth::getInstance()->isLoggedIn())
            {
                Logger::getInstance()->log('%s logged out', Logger::LOG_LOGIN_LOGOUT);
                //Delete user ID from WIO to work with previous guest ID form now on
                WhoIsOnline::getInstance()->delete($_SESSION['userID']);
                //Logout cookie-based
                setcookie('cookie_xbbuser', '', time()-1000, Config::getInstance()->getCfgVal('path_to_forum'));
                //Logout session-based
                unset($_SESSION['userID'], $_SESSION['userHash']);
                if(Auth::getInstance()->isGhost())
                    unset($_SESSION['bewio']);
                Auth::getInstance()->loginChanged();
            }
            //Done, redir to forum index
            header('Location: ' . INDEXFILE . SID_QMARK);
            Template::getInstance()->printMessage('successfully_logged_out', INDEXFILE . SID_QMARK);
            break;
        }
        //Show form (again)
        Template::getInstance()->printPage(Functions::handleMode($this->mode, self::$modeTable, __CLASS__), ['loginName' => $this->loginName,
            'errors' => $this->errors]);
    }
}
?>