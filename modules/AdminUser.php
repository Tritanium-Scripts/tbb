<?php
/**
 * Manages members.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2023 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class AdminUser extends PublicModule
{
    use Singleton, Mode, Errors;

    /**
     * Translates a mode to its template file.
     *
     * @var array Mode and template counterparts
     */
    private static array $modeTable = ['ad_user' => 'AdminUser',
        'search' => 'AdminUser',
        'new' => 'AdminUserNewUser',
        'edit' => 'AdminUserEditUser'];

    /**
     * Sets mode and provides needed lang strings.
     *
     * @param string $mode User mode
     */
    function __construct(string $mode)
    {
        parent::__construct();
        $this->mode = $mode;
        Language::getInstance()->parseFile('MemberList');
    }

    /**
     * Compares member search results by percentage of similarity of the search term.
     *
     * @param array $m1 First member search result to compare with
     * @param array $m2 Second member search result to compare with
     * @return int Comparison result as natural order
     */
    private function cmpByPercent(array $m1, array $m2)
    {
        return $m1['percent'] == $m2['percent']
            ? ($m1['id'] == $m2['id'] ? 0 : ($m1['id'] > $m2['id'] ? 1 : -1))
            : ($m1['percent'] < $m2['percent'] ? 1 : -1);
    }

    /**
     * Searches for, edits and creates new member.
     */
    public function publicCall(): void
    {
        Functions::accessAdminPanel();
        NavBar::getInstance()->addElement(Language::getInstance()->getString('manage_user'), INDEXFILE . '?faction=ad_user' . SID_AMPER);
        switch($this->mode)
        {
//AdminUserNewUser
            case 'new':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('add_new_member'), INDEXFILE . '?faction=ad_user&amp;mode=new' . SID_AMPER);
            $newUser = Functions::getValueFromGlobals('new');
            $groups = array_map(['Functions', 'explodeByTab'], Functions::file('vars/groups.var'));
            if(Functions::getValueFromGlobals('create') == 'yes')
            {
                $newUser['nick'] = htmlspecialchars(trim($newUser['nick']));
                $sendRegMail = $newUser['send_reg'] = isset($newUser['send_reg']);
                //Check nick name
                if(empty($newUser['nick']))
                    $this->errors[] = Language::getInstance()->getString('please_enter_an_user_name');
                elseif(Functions::unifyUserName($newUser['nick']))
                    $this->errors[] = Language::getInstance()->getString('the_user_name_already_exists');
                //Check mail addy
                if(empty($newUser['email']))
                    $this->errors[] = Language::getInstance()->getString('please_enter_a_mail');
                elseif(!Functions::isValidMail($newUser['email']))
                    $this->errors[] = Language::getInstance()->getString('please_enter_a_valid_mail');
                elseif(Functions::unifyUserMail($newUser['email']))
                    $this->errors[] = Language::getInstance()->getString('the_mail_address_already_exists');
                //Check + hash password
                if(empty($newUser['pw1']))
                    $this->errors[] = Language::getInstance()->getString('please_enter_a_password');
                elseif($newUser['pw1'] != $newUser['pw2'])
                    $this->errors[] = Language::getInstance()->getString('passwords_do_not_match');
                else
                    //Unhashed pass is still available under 'pw2' for sending reg mail
                    $newUser['pw1'] = Functions::getHash($newUser['pw1']);
                if(empty($this->errors))
                {
                    //Get new ID
                    $lockObj = Functions::getLockObject('vars/last_user_id.var');
                    $newUserID = $lockObj->getFileContent()+1;
                    //Process group stuff
                    if(!empty($newUser['group']))
                    {
                        foreach($groups as &$curGroup)
                            if($curGroup[0] == $newUser['group'])
                            {
                                $curGroup[3] .= (empty($curGroup[3]) ? '' : ',') . $newUserID;
                                break;
                            }
                        Functions::file_put_contents('vars/groups.var', implode("\n", array_map(['Functions', 'implodeByTab'], $groups)) . "\n");
                    }
                    //Build member file
                    $newUserFile = [$newUser['nick'],
                        $newUserID,
                        $newUser['pw1'],
                        $newUser['email'],
                        '3',
                        '0',
                        date('YmdHis'),
                        '',
                        '',
                        '',
                        '',
                        '0',
                        '',
                        '',
                        '1,1',
                        $newUser['group'],
                        //New TBB 1.5 values
                        time(),
                        '',
                        '',
                        '',
                        '',
                        ''];
                    //Writing time
                    Functions::file_put_contents('members/' . $newUserID . '.xbb', implode("\n", $newUserFile));
                    Functions::file_put_contents('members/' . $newUserID . '.pm', '');
                    $lockObj->setFileContent($newUserID);
                    $lockObj = Functions::getLockObject('vars/member_counter.var');
                    $lockObj->setFileContent($lockObj->getFileContent()+1);
                    //Send reg mail, if required
                    if($sendRegMail)
                        Functions::sendMessage($newUserFile[3], 'new_registration', htmlspecialchars_decode($newUserFile[0]), Config::getInstance()->getCfgVal('forum_name'), $newUserFile[1], $newUserFile[3], $newUser['pw2'], Config::getInstance()->getCfgVal('address_to_forum') . '/' . INDEXFILE);
                    //Done
                    Logger::getInstance()->log('%s created new member (ID: ' . $newUserID . ')', Logger::LOG_ACP_ACTION);
                    Template::getInstance()->printMessage('member_created');
                }
            }
            else
                $newUser = ['nick' => '',
                    'email' => '',
                    'group' => '',
                    'send_reg' => true];
            Template::getInstance()->assign(['newUser' => $newUser,
                'groups' => $groups]);
            break;

//AdminUserEditUser
            case 'edit':
            $editUserID = intval(Functions::getValueFromGlobals('id'));
            NavBar::getInstance()->addElement(Language::getInstance()->getString('edit_user'), INDEXFILE . '?faction=ad_user&amp;mode=edit&amp;id=' . $editUserID . SID_AMPER);
            $editUser = Functions::getUserData($editUserID) or Template::getInstance()->printMessage('user_does_not_exist');
            if(Functions::getValueFromGlobals('edit') == 'yes')
            {
                //Delete user?
                if(Functions::getValueFromGlobals('kill') != '')
                {
                    //Remove from group
                    if(!empty($editUser[15]))
                    {
                        $groups = array_map(['Functions', 'explodeByTab'], Functions::file('vars/groups.var'));
                        foreach($groups as &$curGroup)
                            if($curGroup[0] == $editUser[15])
                            {
                                $curGroup[3] = Functions::explodeByComma($curGroup[3]);
                                if(($key = array_search($editUser[1], $curGroup[3])) !== false)
                                {
                                    unset($curGroup[3][$key]);
                                    $curGroup[3] = implode(',', $curGroup[3]);
                                    Functions::file_put_contents('vars/groups.var', implode("\n", array_map(['Functions', 'implodeByTab'], $groups)) . "\n");
                                }
                                break;
                            }
                    }
                    //Bye bye
                    Functions::unlink('members/' . $editUser[1] . '.xbb');
                    Functions::unlink('members/' . $editUser[1] . '.pm');
                    if(Functions::file_exists('members/' . $editUser[1] . '.ach'))
                        Functions::unlink('members/' . $editUser[1] . '.ach');
                    //Decrease member counter
                    $lockObj = Functions::getLockObject('vars/member_counter.var');
                    $lockObj->setFileContent($lockObj->getFileContent()-1);
                    //In case of self-deletion
                    if($editUser[1] == Auth::getInstance()->getUserID())
                    {
                        //Perform a logout "light"
                        unset($_SESSION['userID'], $_SESSION['userHash']);
                        //Notify other modules
                        WhoIsOnline::getInstance()->delete($editUser[1]);
                        Auth::getInstance()->loginChanged();
                    }
                    //Done
                    Logger::getInstance()->log('%s deleted user (ID: ' . $editUser[1] . ')', Logger::LOG_ACP_ACTION);
                    Template::getInstance()->printMessage('member_deleted');
                }
                //Normal edit
                $editUserName = htmlspecialchars(trim(Functions::getValueFromGlobals('name')));
                $editUser[3] = Functions::getValueFromGlobals('email');
                $editUser[4] = intval(Functions::getValueFromGlobals('status'));
                $editUser[7] = Functions::nl2br(htmlspecialchars(trim(Functions::getValueFromGlobals('signatur', false))));
                $editUser[9] = Functions::getValueFromGlobals('hp');
                $editUser[10] = Functions::getValueFromGlobals('pic');
                $editUser[17] = htmlspecialchars(trim(Functions::getValueFromGlobals('specialState')));
                if(empty($editUserName))
                    $this->errors[] = Language::getInstance()->getString('please_enter_an_user_name');
                elseif($editUser[0] != $editUserName && Functions::unifyUserName($editUserName))
                    $this->errors[] = Language::getInstance()->getString('the_user_name_already_exists');
                else
                    $editUser[0] = $editUserName;
                if(empty($editUser[3]))
                    $this->errors[] = Language::getInstance()->getString('please_enter_a_mail');
                elseif(!Functions::isValidMail($editUser[3]))
                    $this->errors[] = Language::getInstance()->getString('please_enter_a_valid_mail');
                if(empty($this->errors))
                {
                    $editUser[14] = implode(',', $editUser[14]);
                    $editUser[19] = Functions::implodeByTab($editUser[19]);
                    Functions::file_put_contents('members/' . $editUser[1] . '.xbb', implode("\n", $editUser));
                    //Done
                    Logger::getInstance()->log('%s edited user (ID: ' . $editUser[1] . ')', Logger::LOG_ACP_ACTION);
                    Template::getInstance()->printMessage('member_edited');
                }
            }
            $editUser[7] = Functions::br2nl($editUser[7]);
            unset($editUser[2], $editUser[5], $editUser[6], $editUser[8], $editUser[11], $editUser[12], $editUser[13], $editUser[14], $editUser[15], $editUser[16], $editUser[18], $editUser[19]);
            Template::getInstance()->assign('editUser', $editUser);
            break;

//AdminUser
            default:
            NavBar::getInstance()->addElement(Language::getInstance()->getString('member_search'), INDEXFILE . '?faction=ad_user&amp;mode=search' . SID_AMPER);
            $searchMethod = Functions::getValueFromGlobals('searchmethod') ?: 'nick';
            $searchFor = Functions::strtolower(htmlspecialchars(trim(Functions::getValueFromGlobals('searched'))));
            $results = [];
            if(Functions::getValueFromGlobals('search') == 'yes')
            {
                if(empty($searchFor))
                    $this->errors[] = Language::getInstance()->getString('please_enter_a_search_term');
                else
                {
                    switch($searchMethod)
                    {
                        case 'id':
                        if(($userFile = Functions::getUserData($searchFor)) !== false)
                            $results[] = ['id' => $userFile[1],
                                'nick' => $userFile[0],
                                'mail' => $userFile[3],
                                'percent' => 100];
                        break;

                        case 'nick':
                        case 'email':
                        $index = $searchMethod == 'nick' ? 0 : 3;
                        foreach(Functions::glob(DATAPATH . 'members/[!0t]*.xbb') as $curMember)
                        {
                            $curMember = Functions::file($curMember, null, null, false);
                            similar_text(Functions::strtolower($curMember[$index]), $searchFor, $curPercent); //Calculate percentage of similarity
                            if($curPercent > 0) //Add to result list by having a minimum of similarity
                                $results[] = ['id' => $curMember[1],
                                'nick' => $curMember[0],
                                'mail' => $curMember[3],
                                'percent' => $curPercent];
                        }
                        break;
                    }
                    if(count($results) > 1)
                        usort($results, [$this, 'cmpByPercent']);
                }
            }
            Template::getInstance()->assign(['results' => $results,
                'searchMethod' => $searchMethod,
                'searchFor' => $searchFor]);
            break;
        }
        Template::getInstance()->printPage(self::$modeTable[$this->mode], ['errors' => $this->errors]);
    }
}
?>