<?php
/**
 * Manages user groups.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2024 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class AdminGroup extends PublicModule
{
    use Singleton, Mode, Errors;

    /**
     * ID of current group.
     *
     * @var int Group ID
     */
    private int $groupID;

    /**
     * Existing groups.
     *
     * @var array Available groups
     */
    private array $groups;

    /**
     * Translates a mode to its template file.
     *
     * @var array Mode and template counterparts
     */
    private static $modeTable = ['ad_groups' => 'AdminGroup',
        'new' => 'AdminGroupNewGroup',
        'edit' => 'AdminGroupEditGroup',
        'kill' => 'AdminGroupDeleteGroup'];

    /**
     * Sets mode, group ID and loads all groups.
     *
     * @param string $mode Group mode
     */
    function __construct(string $mode)
    {
        parent::__construct();
        $this->mode = $mode;
        $this->groupID = intval(Functions::getValueFromGlobals('group_id'));
        $this->groups = array_map(['Functions', 'explodeByTab'], Functions::file('vars/groups.var'));
        foreach($this->groups as &$curGroup)
            $curGroup[3] = Functions::explodeByComma($curGroup[3]);
        PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_GROUP_INIT);
    }

    /**
     * Executes mode.
     */
    public function publicCall(): void
    {
        Functions::accessAdminPanel();
        NavBar::getInstance()->addElement(Language::getInstance()->getString('manage_groups'), INDEXFILE . '?faction=ad_groups' . SID_AMPER);
        switch($this->mode)
        {
//AdminGroupNewGroup
            case 'new':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('add_new_group'), INDEXFILE . '?faction=ad_groups&amp;mode=new' . SID_AMPER);
            $newName = htmlspecialchars(trim(Functions::getValueFromGlobals('title')));
            $newColor = Functions::getValueFromGlobals('color');
            $newAvatar = Functions::getValueFromGlobals('pic');
            $newUserIDs = array_unique(array_filter(array_map('trim', Functions::explodeByComma(Functions::getValueFromGlobals('group_members'))), 'is_numeric'));
            PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_GROUP_NEW_GROUP, $newName, $newColor, $newAvatar, $newUserIDs);
            if(Functions::getValueFromGlobals('create') == 'yes')
            {
                if(empty($newName))
                    $this->errors[] = Language::getInstance()->getString('please_enter_a_group_name');
                else
                {
                    $this->groupID = current(end($this->groups) ?: [])+1;
                    foreach($newUserIDs as $curKey => $curUserID)
                    {
                        //Don't add to group if user is invalid or already belongs to a group
                        if(($curUser = Functions::getUserData($curUserID)) == false || !empty($curUser[15]))
                            unset($newUserIDs[$curKey]);
                        else
                        {
                            //Add group to user
                            $curUser[15] = $this->groupID;
                            $curUser[14] = implode(',', $curUser[14]);
                            $curUser[19] = Functions::implodeByTab($curUser[19]);
                            Functions::file_put_contents('members/' . $curUser[1] . '.xbb', implode("\n", $curUser));
                        }
                    }
                    Functions::file_put_contents('vars/groups.var', $this->groupID . "\t" . $newName . "\t" . $newAvatar . "\t" . implode(',', $newUserIDs) . "\t" . $newColor . "\t\t\t\t\t\t\t\t\t\n", FILE_APPEND);
                    Logger::getInstance()->log('%s created new group (ID: ' . $this->groupID . ')', Logger::LOG_ACP_ACTION);
                    header('Location: ' . INDEXFILE . '?faction=ad_groups' . SID_AMPER_RAW);
                    Template::getInstance()->printMessage('group_created');
                }
            }
            Template::getInstance()->assign(['newName' => $newName,
                'newColor' => $newColor,
                'newAvatar' => $newAvatar,
                'newUserIDs' => implode(',', $newUserIDs)]);
            break;

//AdminGroupEditGroup
            case 'edit':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('edit_group'), INDEXFILE . '?faction=ad_groups&amp;mode=edit&amp;group_id=' . $this->groupID . SID_AMPER);
            if(($key = array_search($this->groupID, array_map('current', $this->groups))) === false)
                Template::getInstance()->printMessage('group_not_found');
            $editName = htmlspecialchars(trim(Functions::getValueFromGlobals('title')));
            $editColor = Functions::getValueFromGlobals('color');
            $editAvatar = Functions::getValueFromGlobals('pic');
            $editUserIDs = array_unique(array_filter(array_map('trim', Functions::explodeByComma(Functions::getValueFromGlobals('group_members'))), 'is_numeric'));
            PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_GROUP_EDIT_GROUP, $key, $editName, $editColor, $editAvatar, $editUserIDs);
            if(Functions::getValueFromGlobals('update') == 'yes')
            {
                if(empty($editName))
                    $this->errors[] = Language::getInstance()->getString('please_enter_a_group_name');
                else
                {
                    //Update members
                    #1 Remove from group
                    foreach(array_diff($this->groups[$key][3], $editUserIDs) as $curUserID)
                    {
                        $curUser = Functions::getUserData($curUserID);
                        if($curUser != false)
                        {
                            $curUser[15] = '';
                            $curUser[14] = implode(',', $curUser[14]);
                            $curUser[19] = Functions::implodeByTab($curUser[19]);
                            Functions::file_put_contents('members/' . $curUser[1] . '.xbb', implode("\n", $curUser));
                        }
                    }
                    #2 Add to group
                    foreach(array_diff($editUserIDs, $this->groups[$key][3]) as $curUserID)
                    {
                        $curUser = Functions::getUserData($curUserID);
                        //Don't add to group if user is invalid or already belongs to a group
                        if($curUser == false || !empty($curUser[15]))
                            unset($editUserIDs[array_search($curUserID, $editUserIDs)]);
                        else
                        {
                            //Add group to user
                            $curUser[15] = $this->groupID;
                            $curUser[14] = implode(',', $curUser[14]);
                            $curUser[19] = Functions::implodeByTab($curUser[19]);
                            Functions::file_put_contents('members/' . $curUser[1] . '.xbb', implode("\n", $curUser));
                        }
                    }
                    //Update group
                    $this->groups[$key][1] = $editName;
                    $this->groups[$key][2] = $editAvatar;
                    $this->groups[$key][3] = $editUserIDs;
                    foreach($this->groups as &$curGroup)
                        $curGroup[3] = implode(',', $curGroup[3]);
                    $this->groups[$key][4] = $editColor;
                    Functions::file_put_contents('vars/groups.var', implode("\n", array_map(['Functions', 'implodeByTab'], $this->groups)) . "\n");
                    //Done
                    Logger::getInstance()->log('%s edited group (ID: ' . $this->groupID . ')', Logger::LOG_ACP_ACTION);
                    header('Location: ' . INDEXFILE . '?faction=ad_groups' . SID_AMPER_RAW);
                    Template::getInstance()->printMessage('group_edited');
                }
            }
            else
            {
                $editName = $this->groups[$key][1];
                $editAvatar = $this->groups[$key][2];
                $editUserIDs = !empty($this->groups[$key][3][0]) ? $this->groups[$key][3] : [];
                $editColor = $this->groups[$key][4];
            }
            Template::getInstance()->assign(['groupID' => $this->groupID,
                'editName' => $editName,
                'editColor' => $editColor,
                'editAvatar' => $editAvatar,
                'editUserIDs' => implode(',', $editUserIDs)]);
            break;

//AdminGroupDeleteGroup
            case 'kill':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('delete_group'), INDEXFILE . '?faction=ad_groups&amp;mode=kill&amp;group_id=' . $this->groupID . SID_AMPER);
            if(($key = array_search($this->groupID, array_map('current', $this->groups))) === false)
                Template::getInstance()->printMessage('group_not_found');
            PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_GROUP_DELETE_GROUP, $key);
            if(Functions::getValueFromGlobals('kill') == 'yes')
            {
                //Delete group from members
                foreach($this->groups[$key][3] as $curUserID)
                    if(($curUser = Functions::getUserData($curUserID)) != false)
                    {
                        $curUser[15] = '';
                        $curUser[14] = implode(',', $curUser[14]);
                        $curUser[19] = Functions::implodeByTab($curUser[19]);
                        Functions::file_put_contents('members/' . $curUser[1] . '.xbb', implode("\n", $curUser));
                    }
                //Delete group from forum special rights
                foreach(Functions::explodeByComma($this->groups[$key][5]) as $curForumID)
                {
                    if(empty($curForumID))
                        continue;
                    $curSpecialRights = Functions::file('foren/' . $curForumID . '-rights.xbb');
                    foreach($curSpecialRights as $curKey => $curSpecialRight)
                    {
                        $curSpecialRight = Functions::explodeByTab($curSpecialRight);
                        if($curSpecialRight[1] == '2' && $curSpecialRight[2] == $this->groupID)
                        {
                            unset($curSpecialRights[$curKey]);
                            Functions::file_put_contents('foren/' . $curForumID . '-rights.xbb', implode("\n", $curSpecialRights) . "\n");
                            break;
                        }
                    }
                }
                //Delete group
                unset($this->groups[$key]);
                foreach($this->groups as &$curGroup)
                    $curGroup[3] = implode(',', $curGroup[3]);
                Functions::file_put_contents('vars/groups.var', empty($this->groups) ? '' : implode("\n", array_map(['Functions', 'implodeByTab'], $this->groups)) . "\n");
                //Done
                Logger::getInstance()->log('%s deleted group (ID: ' . $this->groupID . ')', Logger::LOG_ACP_ACTION);
                header('Location: ' . INDEXFILE . '?faction=ad_groups' . SID_AMPER_RAW);
                Template::getInstance()->printMessage('group_deleted');
            }
            Template::getInstance()->assign(['groupID' => $this->groupID,
                'groupName' => $this->groups[$key][1]]);
            break;

//AdminGroup
            default:
            //Get names from IDs
            foreach($this->groups as &$curGroup)
                $curGroup[3] = !empty($curGroup[3][0]) ? array_map(['Functions', 'getProfileLink'], $curGroup[3], array_fill(0, count($curGroup[3]), true)) : [];
            PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_GROUP_SHOW_GROUPS);
            break;
        }
        Template::getInstance()->printPage(self::$modeTable[$this->mode], ['groups' => $this->groups,
            'errors' => $this->errors]);
    }
}
?>