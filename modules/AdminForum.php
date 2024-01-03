<?php
/**
 * Manages categories and forums incl. special rights.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2023 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class AdminForum extends PublicModule
{
    use Singleton, Mode, Errors;

    /**
     * Translates a category ID to its name.
     *
     * @var array Cat IDs and name counterparts
     */
    private array $catTable = [];

    /**
     * Available forums.
     *
     * @var array All current forums
     */
    private array $forums;

    /**
     * Translates a mode to its template file.
     *
     * @var array Mode and template counterparts
     */
    private static $modeTable = ['ad_forum' => 'AdminForum',
        'forumview' => 'AdminForumIndex',
        'newforum' => 'AdminForumNewForum',
        'change' => 'AdminForumEditForum',
        'AdminForumDeleteForum' => 'AdminForumDeleteForum',
        //Rights
        'edit_forum_rights' => 'AdminForumSpecialRights',
        'new_user_right' => 'AdminForumNewUserRight',
        'new_group_right' => 'AdminForumNewGroupRight',
        //Cats
        'viewkg' => 'AdminForumIndexCat',
        'newkg' => 'AdminForumNewCat',
        'chgkg' => 'AdminForumEditCat'];

    /**
     * Sets mode and prepares category translation table.
     *
     * @param string $mode Forum mode to execute
     * @return AdminForum New instance of this class
     */
    function __construct(string $mode)
    {
        parent::__construct();
        $this->mode = $mode;
        $this->catTable[-1] = Language::getInstance()->getString('no_category');
        foreach(array_map(['Functions', 'explodeByTab'], Functions::file('vars/kg.var')) as $curCat)
            $this->catTable[$curCat[0]] = $curCat[1];
        $this->forums = array_map(['Functions', 'explodeByTab'], Functions::file('vars/foren.var'));
    }

    /**
     * Executes mode.
     */
    public function publicCall(): void
    {
        Functions::accessAdminPanel();
        NavBar::getInstance()->addElement(Language::getInstance()->getString('manage_forums_categories'), INDEXFILE . '?faction=ad_forum' . SID_AMPER);
        switch($this->mode)
        {
//AdminForumIndex
            case 'forumview':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('manage_forums'), INDEXFILE . '?faction=ad_forum&amp;mode=forumview' . SID_AMPER);
            $forums = [];
            foreach($this->forums as $curForum)
                $forums[] = ['id' => $curForum[0],
                    'name' => $curForum[1],
                    'descr' => $curForum[2],
                    'catID' => $curForum[5],
                    'mods' => Functions::getProfileLink($curForum[11])];
            Template::getInstance()->assign('forums', $forums);
            break;

//AdminForumNewForum
            case 'newforum':
            NavBar::getInstance()->addElement([
                [Language::getInstance()->getString('manage_forums'), INDEXFILE . '?faction=ad_forum&amp;mode=forumview' . SID_AMPER],
                [Language::getInstance()->getString('add_new_forum'), INDEXFILE . '?faction=ad_forum&amp;mode=newforum' . SID_AMPER]]);
            $newName = htmlspecialchars(trim(Functions::getValueFromGlobals('titel')));
            $newDescr = htmlspecialchars(trim(Functions::getValueFromGlobals('description')));
            $newCatID = intval(Functions::getValueFromGlobals('kg'));
            $newIsBBCode = Functions::getValueFromGlobals('upbcode');
            $newIsXHTML = Functions::getValueFromGlobals('htmlcode');
            $newIsNotify = Functions::getValueFromGlobals('sm_mods');
            $newRights = (array) Functions::getValueFromGlobals('new_rights') + array_fill(0, 10, ''); //Fill up missing keys/values from unchecked boxes with this neat array union trick :)
            $newModIDs = array_filter(array_map('trim', Functions::explodeByComma(Functions::getValueFromGlobals('mods'))), 'is_numeric');
            if(Functions::getValueFromGlobals('create') == 'yes')
            {
                if($newName == '')
                    $this->errors[] = Language::getInstance()->getString('please_enter_a_forum_name');
                //Allow no description
                else
                {
                    ksort($newRights);
                    //Check user states of given mod IDs and fix them, if needed
                    foreach(array_map(['Functions', 'getUserData'], $newModIDs) as $curKey => $curUser)
                    {
                        if($curUser == false)
                            unset($newModIDs[$curKey]);
                        elseif($curUser[4] != 1 && !in_array($curUser[1], $this->getModIDs()))
                        {
                            $curUser[4] = 2;
                            Functions::file_put_contents('members/' . $curUser[1] . '.xbb', implode("\n", $curUser));
                        }
                    }
                    //Get and update newest forum ID
                    Functions::file_put_contents('vars/forens.var', $newForumID = Functions::file_get_contents('vars/forens.var')+1);
                    //Build forum data
                    $newForum = [$newForumID,
                        $newName,
                        $newDescr,
                        0, //Topics
                        0, //Posts
                        $newCatID,
                        '', //Timestamp of last post
                        $newIsBBCode . ',' . $newIsXHTML . ',' . $newIsNotify,
                        '', //Status?
                        '', //Last post
                        implode(',', $newRights),
                        implode(',', $newModIDs),
                        '', '', '', "\n"];
                    //Write all the data
                    Functions::file_put_contents('foren/' . $newForumID . '-ltopic.xbb', '0');
                    Functions::file_put_contents('foren/' . $newForumID . '-threads.xbb', '');
                    Functions::file_put_contents('vars/foren.var', Functions::implodeByTab($newForum), FILE_APPEND);
                    //Done
                    Logger::getInstance()->log('%s created new forum (ID: ' . $newForumID . ')', LOG_ACP_ACTION);
                    header('Location: ' . INDEXFILE . '?faction=ad_forum&mode=forumview' . SID_AMPER_RAW);
                    Template::getInstance()->printMessage('new_forum_added');
                }
            }
            else
            {
                //Set default settings
                $newRights = array_fill(0, 7, true) + array_fill(7, 3, false);
                $newIsBBCode = true;
                $newIsXHTML = $newIsNotify = false;
            }
            Template::getInstance()->assign(['newName' => $newName,
                'newDescr' => $newDescr,
                'newCatID' => $newCatID,
                'newIsBBCode' => $newIsBBCode == '1',
                'newIsXHTML' => $newIsXHTML == '1',
                'newIsNotify' => $newIsNotify == '1',
                'newRights' => $newRights,
                'newModIDs' => $newModIDs]);
            break;

//AdminForumEditForum
            case 'change':
            $forumID = intval(Functions::getValueFromGlobals('ad_forum_id'));
            NavBar::getInstance()->addElement([
                [Language::getInstance()->getString('manage_forums'), INDEXFILE . '?faction=ad_forum&amp;mode=forumview' . SID_AMPER],
                [Language::getInstance()->getString('edit_forum'), INDEXFILE . '?faction=ad_forum&amp;ad_forum_id=' . $forumID . '&amp;mode=change' . SID_AMPER]]);
            //Get forum to edit
            if(($key = array_search($forumID, array_map('current', $this->forums))) === false)
                Template::getInstance()->printMessage('forum_not_found');
            $editForum = &$this->forums[$key];
            $editForum[7] = Functions::explodeByComma($editForum[7]);
            $editForum[10] = Functions::explodeByComma($editForum[10]);
            $editForum[11] = Functions::explodeByComma($editForum[11]);
            if(Functions::getValueFromGlobals('change') == 'yes')
            {
                //Delete forum?
                if(Functions::getValueFromGlobals('kill') != '')
                {
                    //Confirmed?
                    if(Functions::getValueFromGlobals('confirm') == 'yes')
                    {
                        //Let's rock! :D
                        //Remove from forum index
                        unset($this->forums[$key]);
                        Functions::file_put_contents('vars/foren.var', empty($this->forums) ? '' : implode("\n", array_map(['Functions', 'implodeByTab'], $this->forums)) . "\n");
                        $sizeCounter = $fileCounter = 0;
                        //Update groups with data from special rights file
                        if(Functions::file_exists('foren/' . $forumID . '-rights.xbb'))
                        {
                            $groups = array_map(['Functions', 'explodeByTab'], Functions::file('vars/groups.var'));
                            foreach(array_map(['Functions', 'explodeByTab'], Functions::file('foren/' . $forumID . '-rights.xbb')) as $curSpecialRight)
                                //Look for special group rights
                                if($curSpecialRight[1] == '2')
                                    foreach($groups as &$curGroup)
                                        if($curGroup[0] == $curSpecialRight[2])
                                        {
                                            if(($key = array_search($forumID, ($curGroup[5] = Functions::explodeByComma($curGroup[5])))) !== false)
                                            {
                                                //Delete special group right
                                                unset($curGroup[5][$key]);
                                                $curGroup[5] = implode(',', $curGroup[5]);
                                                Functions::file_put_contents('vars/groups.var', implode("\n", array_map(['Functions', 'implodeByTab'], $groups)) . "\n");
                                            }
                                            break;
                                        }
                            $sizeCounter += Functions::unlink('foren/' . $forumID . '-rights.xbb');
                            $fileCounter++;
                        }
                        //Delete topics
                        foreach(Functions::file('foren/' . $forumID . '-threads.xbb') as $curTopicID)
                        {
                            //Delete possible poll
                            $curTopic = Functions::explodeByTab(current(Functions::file('foren/' . $forumID . '-' . $curTopicID . '.xbb', null, null, false)));
                            if(!empty($curTopic[7]))
                            {
                                $sizeCounter += Functions::unlink('polls/' . $curTopic[7] . '-1.xbb');
                                $sizeCounter += Functions::unlink('polls/' . $curTopic[7] . '-2.xbb');
                                $fileCounter += 2;
                            }
                            //Delete topic
                            $sizeCounter += Functions::unlink('foren/' . $forumID . '-' . $curTopicID . '.xbb');
                            $fileCounter++;
                        }
                        //Delete links of moved topics (if any)
                        foreach(glob(DATAPATH . 'foren/' . $forumID . '-[0-9]*.xbb') as $curMovedTopic)
                        {
                            $sizeCounter += Functions::unlink($curMovedTopic, false);
                            $fileCounter++;
                        }
                        //Delete forum data
                        $sizeCounter += Functions::unlink('foren/' . $forumID . '-threads.xbb');
                        $sizeCounter += Functions::unlink('foren/' . $forumID . '-ltopic.xbb');
                        $fileCounter += 2;
                        if(Functions::file_exists('foren/' . $forumID . '-sticker.xbb'))
                        {
                            $sizeCounter += Functions::unlink('foren/' . $forumID . '-sticker.xbb');
                            $fileCounter++;
                        }
                        if(Functions::file_exists('vars/tview-' . $forumID . '.lock'))
                        {
                            $sizeCounter += Functions::unlink('vars/tview-' . $forumID . '.lock');
                            $fileCounter++;
                        }
                        //Check mods (if any)
                        foreach($editForum[11] as $curModID)
                            //By deleting forum from index, all mod IDs won't list them either
                            if(!in_array($curModID, $this->getModIDs()) && !empty($curModID))
                            {
                                $curUser = Functions::getUserData($curModID);
                                if($curUser[4] == 1)
                                    continue;
                                $curUser[4] = 3;
                                $curUser[14] = implode(',', $curUser[14]);
                                $curUser[19] = Functions::implodeByTab($curUser[19]);
                                Functions::file_put_contents('members/' . $curModID . '.xbb', implode("\n", $curUser));
                            }
                        //Done
                        Logger::getInstance()->log('%s deleted forum (ID: ' . $forumID . ')', LOG_ACP_ACTION);
                        Template::getInstance()->printMessage('forum_deleted_freed_x_in_xxx', $sizeCounter/1024, $fileCounter, $editForum[4], $editForum[3]);
                    }
//AdminForumDeleteForum
                    //Get confirmation
                    else
                    {
                        NavBar::getInstance()->addElement(Language::getInstance()->getString('delete_forum'));
                        $this->mode = 'AdminForumDeleteForum';
                    }
                }
                //Normal edit
                else
                {
                    $editForum[1] = htmlspecialchars(trim(Functions::getValueFromGlobals('titel')));
                    $editForum[2] = htmlspecialchars(trim(Functions::getValueFromGlobals('description')));
                    $editForum[5] = intval(Functions::getValueFromGlobals('kg'));
                    $editForum[7][0] = Functions::getValueFromGlobals('upbcode');
                    $editForum[7][1] = Functions::getValueFromGlobals('htmlcode');
                    $editForum[7][2] = Functions::getValueFromGlobals('sm_mods');
                    $editForum[10] = (array) Functions::getValueFromGlobals('new_rights') + array_fill(0, 10, ''); //Fill up missing keys/values from unchecked boxes with this neat array union trick :)
                    ksort($editForum[10]);
                    if(empty($editForum[1]))
                        $this->errors[] = Language::getInstance()->getString('please_enter_a_forum_name');
                    else
                    {
                        $editModIDs = array_filter(array_map('trim', Functions::explodeByComma(Functions::getValueFromGlobals('mods'))), 'is_numeric');
                        //Adjust new mod user rankings
                        if($editForum[11] != $editModIDs)
                        {
                            $allModIDs = $this->getModIDs();
                            //#1 Get IDs of mods to downgrade rank
                            foreach(array_diff($editForum[11], $editModIDs) as $curModID)
                            {
                                if(empty($curModID))
                                    continue;
                                //Kick out removed mod one time from all known mods
                                unset($allModIDs[array_search($curModID, $allModIDs)]);
                                //Verify they need to be downgraded aka being no mod somewhere else
                                if(!in_array($curModID, $allModIDs))
                                {
                                    $curUser = Functions::getUserData($curModID);
                                    if($curUser[4] == 1)
                                        continue;
                                    $curUser[4] = 3;
                                    $curUser[14] = implode(',', $curUser[14]);
                                    $curUser[19] = Functions::implodeByTab($curUser[19]);
                                    Functions::file_put_contents('members/' . $curModID . '.xbb', implode("\n", $curUser));
                                }
                            }
                            //#2 Get IDs of users to upgrade rank
                            foreach(array_diff($editModIDs, $editForum[11]) as $curModID)
                                //Verify they need to be upgraded aka not being mod somewhere else already
                                if(!in_array($curModID, $allModIDs) && !empty($curModID))
                                {
                                    $curUser = Functions::getUserData($curModID);
                                    if($curUser == false)
                                        unset($editModIDs[array_search($curModID, $editModIDs)]);
                                    elseif($curUser[4] != 1)
                                    {
                                        $curUser[4] = 2;
                                        $curUser[14] = implode(',', $curUser[14]);
                                        $curUser[19] = Functions::implodeByTab($curUser[19]);
                                        Functions::file_put_contents('members/' . $curModID . '.xbb', implode("\n", $curUser));
                                    }
                                }
                        }
                        $editForum[11] = $editModIDs;
                        $editForum[7] = implode(',', $editForum[7]);
                        $editForum[10] = implode(',', $editForum[10]);
                        $editForum[11] = implode(',', $editForum[11]);
                        Functions::file_put_contents('vars/foren.var', implode("\n", array_map(['Functions', 'implodeByTab'], $this->forums)) . "\n");
                        //Done
                        Logger::getInstance()->log('%s edited forum (ID: ' . $forumID . ')', LOG_ACP_ACTION);
                        header('Location: ' . INDEXFILE . '?faction=ad_forum&mode=forumview' . SID_AMPER_RAW);
                        Template::getInstance()->printMessage('forum_edited');
                    }
                }
            }
            Template::getInstance()->assign(['editID' => $forumID,
                'editName' => $editForum[1],
                'editDescr' => $editForum[2],
                'editCatID' => $editForum[5],
                'editOptions' => $editForum[7],
                'editRights' => $editForum[10],
                'editModIDs' => $editForum[11]]);
            break;

            case 'moveforumup':
            $forumID = intval(Functions::getValueFromGlobals('id'));
            //Get forum to edit
            if(($key = array_search($forumID, array_map('current', $this->forums))) === false)
                Template::getInstance()->printMessage('forum_not_found');
            //Already on top?
            if($key != 0)
            {
                //Credits for this nice var swapping idea goes to hasin:
                //http://booleandreams.wordpress.com/2008/07/30/how-to-swap-values-of-two-variables-without-using-a-third-variable/#comment-10486
                list($this->forums[$key], $this->forums[$key-1]) = [$this->forums[$key-1], $this->forums[$key]];
                Functions::file_put_contents('vars/foren.var', implode("\n", array_map(['Functions', 'implodeByTab'], $this->forums)) . "\n");
            }
            header('Location: ' . INDEXFILE . '?faction=ad_forum&mode=forumview' . SID_AMPER_RAW);
            Template::getInstance()->printMessage('forum_moved');
            break;

#  )   ___                                   ______                              ______                  #
# (__/_____) /)       ,                     (, /    )     /) /)        ,        (, /    )             /) #
#   /       (/   __     _   _      __/        /---(      // //  _ _/_   __        /---(  ____   __  _(/  #
#  /        / )_/ (__(_/_)_/_)_(_/_ /(__   ) / ____)(_(_(/_(/__(/_(___(_/ (_   ) / ____)(_)(_(_/ (_(_(_  #
# (______)                    .-/  /      (_/ (                               (_/ (                      #
#                            (_/                                                                         #

            case 'moveforumdown':
            $forumID = intval(Functions::getValueFromGlobals('id'));
            //Get forum to edit
            if(($key = array_search($forumID, array_map('current', $this->forums))) === false)
                Template::getInstance()->printMessage('forum_not_found');
            //Already at bottom?
            if($key != count($this->forums)-1)
            {
                //Credits for this nice var swapping idea goes to hasin:
                //http://booleandreams.wordpress.com/2008/07/30/how-to-swap-values-of-two-variables-without-using-a-third-variable/#comment-10486
                list($this->forums[$key], $this->forums[$key+1]) = [$this->forums[$key+1], $this->forums[$key]];
                Functions::file_put_contents('vars/foren.var', implode("\n", array_map(['Functions', 'implodeByTab'], $this->forums)) . "\n");
            }
            header('Location: ' . INDEXFILE . '?faction=ad_forum&mode=forumview' . SID_AMPER_RAW);
            Template::getInstance()->printMessage('forum_moved');
            break;

//AdminForumSpecialRights
            case 'edit_forum_rights':
            $forumID = intval(Functions::getValueFromGlobals('forum_id'));
            NavBar::getInstance()->addElement([
                [Language::getInstance()->getString('manage_forums'), INDEXFILE . '?faction=ad_forum&amp;mode=forumview' . SID_AMPER],
                [Language::getInstance()->getString('edit_forum'), INDEXFILE . '?faction=ad_forum&amp;ad_forum_id=' . $forumID . '&amp;mode=change' . SID_AMPER],
                [Language::getInstance()->getString('edit_special_rights'), INDEXFILE . '?faction=ad_forum&amp;mode=edit_forum_rights&amp;forum_id=' . $forumID . SID_AMPER]]);
            //Check for valid forum ID
            if(!in_array($forumID, array_map('current', $this->forums)))
                Template::getInstance()->printMessage('forum_not_found');
            //Get special rights or create new
            $specialRights = @Functions::file('foren/' . $forumID . '-rights.xbb') ?: [];
            #0:rightID - 1:rightType - 2:user/groupID - 3:isAccessForum - 4:isPostTopics - 5:isPostReplies - 6:isPostPolls - 7:isEditOwnPosts - 8:isEditOwnPolls
            $specialRights = array_map(['Functions', 'explodeByTab'], $specialRights);
            if(Functions::getValueFromGlobals('change') == 'yes' && ($newRights = Functions::getValueFromGlobals('new_rights')) != '')
            {
                foreach($specialRights as &$curSpecialRight)
                {
                    //Only consider changes to special rights if the first option to access a forum is set
                    if(isset($newRights[$curSpecialRight[0]][0]))
                    {
                        $newRights[$curSpecialRight[0]] += array_fill(1, 5, ''); //Fill up missing indices aka unchecked options
                        list($curSpecialRight[3], $curSpecialRight[4], $curSpecialRight[5], $curSpecialRight[6], $curSpecialRight[7], $curSpecialRight[8]) = [$newRights[$curSpecialRight[0]][0], $newRights[$curSpecialRight[0]][1], $newRights[$curSpecialRight[0]][2], $newRights[$curSpecialRight[0]][3], $newRights[$curSpecialRight[0]][4], $newRights[$curSpecialRight[0]][5]];
                    }
                    $curSpecialRight = Functions::implodeByTab($curSpecialRight);
                }
                Functions::file_put_contents('foren/' . $forumID . '-rights.xbb', implode("\n", $specialRights) . "\n");
                Logger::getInstance()->log('%s edited special rights of forum (ID: ' . $forumID . ')', LOG_ACP_ACTION);
                //Undo implode for template
                $specialRights = array_map(['Functions', 'explodeByTab'], $specialRights);
            }
            //Get names for user/group IDs and split them by right type
            $specialUserRights = $specialGroupRights = [];
            foreach($specialRights as &$curSpecialRight)
            {
                if($curSpecialRight[1] == '1')
                    $specialUserRights[] = $curSpecialRight + ['idName' => Functions::getProfileLink($curSpecialRight[2], true)];
                elseif($curSpecialRight[1] == '2')
                    $specialGroupRights[] = $curSpecialRight + ['idName' => @next(Functions::getGroupData($curSpecialRight[2]))];
            }
            Template::getInstance()->assign(['forumID' => $forumID,
                'specialUserRights' => $specialUserRights,
                'specialGroupRights' => $specialGroupRights]);
            break;

//AdminForumNewUserRight
            case 'new_user_right':
            $forumID = intval(Functions::getValueFromGlobals('forum_id'));
            NavBar::getInstance()->addElement([
                [Language::getInstance()->getString('manage_forums'), INDEXFILE . '?faction=ad_forum&amp;mode=forumview' . SID_AMPER],
                [Language::getInstance()->getString('edit_forum'), INDEXFILE . '?faction=ad_forum&amp;ad_forum_id=' . $forumID . '&amp;mode=change' . SID_AMPER],
                [Language::getInstance()->getString('edit_special_rights'), INDEXFILE . '?faction=ad_forum&amp;mode=edit_forum_rights&amp;forum_id=' . $forumID . SID_AMPER],
                [Language::getInstance()->getString('add_new_special_user_right'), INDEXFILE . '?faction=ad_forum&amp;mode=new_user_right&amp;forum_id=' . $forumID . SID_AMPER]]);
            //Check for valid forum ID
            if(($key = array_search($forumID, array_map('current', $this->forums))) === false)
                Template::getInstance()->printMessage('forum_not_found');
            if(Functions::getValueFromGlobals('change') == 'yes')
            {
                //Get special rights or create new
                $specialRights = @Functions::file('foren/' . $forumID . '-rights.xbb') ?: [];
                $specialUserIDs = array_map(function($right)
                {
                    return $right[1] == 1 ? $right[2] : 0;
                }, $specialRights = array_map(['Functions', 'explodeByTab'], $specialRights));
                //Get new user IDs and rights to add
                $newUserIDs = array_unique(Functions::explodeByComma(Functions::getValueFromGlobals('new_user_ids')));
                $newUserRights = (array) Functions::getValueFromGlobals('new_right') + array_fill(0, 6, '');
                ksort($newUserRights);
                //Filter out invalid IDs
                foreach($newUserIDs as $curKey => $curUserID)
                    if($curUserID == 0 || !Functions::file_exists('members/' . $curUserID . '.xbb') || in_array($curUserID, $specialUserIDs))
                        unset($newUserIDs[$curKey]);
                //Add new valid rights
                $newSpecialRightID = empty($specialRights) ? 1 : current(end($specialRights))+1;
                $toAppend = '';
                foreach($newUserIDs as $curUserID)
                    $toAppend .= $newSpecialRightID++ . "\t1\t" . $curUserID . "\t" . Functions::implodeByTab($newUserRights) . "\t\t\t\t\t\t\n";
                Functions::file_put_contents('foren/' . $forumID . '-rights.xbb', $toAppend, FILE_APPEND);
                //Done
                Logger::getInstance()->log('%s added new special user right(s) for forum (ID: ' . $forumID . ')', LOG_ACP_ACTION);
                header('Location: ' . INDEXFILE . '?faction=ad_forum&mode=edit_forum_rights&forum_id=' . $forumID . SID_AMPER_RAW);
                Template::getInstance()->printMessage('special_right_added');
            }
            Template::getInstance()->assign(['forumID' => $forumID,
                'forumRights' => array_map(function($right)
                {
                    return $right == 1;
                }, Functions::explodeByComma($this->forums[$key][10]))]);
            break;

//AdminForumNewGroupRight
            case 'new_group_right':
            $forumID = intval(Functions::getValueFromGlobals('forum_id'));
            NavBar::getInstance()->addElement([
                [Language::getInstance()->getString('manage_forums'), INDEXFILE . '?faction=ad_forum&amp;mode=forumview' . SID_AMPER],
                [Language::getInstance()->getString('edit_forum'), INDEXFILE . '?faction=ad_forum&amp;ad_forum_id=' . $forumID . '&amp;mode=change' . SID_AMPER],
                [Language::getInstance()->getString('edit_special_rights'), INDEXFILE . '?faction=ad_forum&amp;mode=edit_forum_rights&amp;forum_id=' . $forumID . SID_AMPER],
                [Language::getInstance()->getString('add_new_special_group_right'), INDEXFILE . '?faction=ad_forum&amp;mode=new_group_right&amp;forum_id=' . $forumID . SID_AMPER]]);
            //Check for valid forum ID
            if(($key = array_search($forumID, array_map('current', $this->forums))) === false)
                Template::getInstance()->printMessage('forum_not_found');
            //Make sure there are groups available
            if(count($groups = array_map(['Functions', 'explodeByTab'], Functions::file('vars/groups.var'))) == 0)
                Template::getInstance()->printMessage('no_groups_available');
            //Get special rights or create new
            $specialRights = @Functions::file('foren/' . $forumID . '-rights.xbb') ?: [];
            $specialGroupIDs = array_filter(array_map(function($right)
            {
                return $right[1] == 2 ? $right[2] : null;
            }, $specialRights = array_map(['Functions', 'explodeByTab'], $specialRights)), 'is_numeric');
            //Make sure there are groups with no special rights for current forum
            if(count($specialGroupIDs) == count($groups))
                Template::getInstance()->printMessage('all_groups_assigned');
            if(Functions::getValueFromGlobals('add') == 'yes')
            {
                $newGroupID = intval(Functions::getValueFromGlobals('new_group_id'));
                if(in_array($newGroupID, $specialGroupIDs))
                    $this->errors[] = Language::getInstance()->getString('group_already_has_special_rights');
                else
                {
                    //Update group file and special rights file
                    foreach($groups as &$curGroup)
                        if($curGroup[0] == $newGroupID)
                        {
                            if(empty($curGroup[5]))
                                $curGroup[5] = $forumID;
                            else
                                if(!in_array($forumID, ($curGroup[5] = Functions::explodeByComma($curGroup[5]))))
                                {
                                    $curGroup[5][] = $forumID;
                                    $curGroup[5] = implode(',', $curGroup[5]);
                                }
                            Functions::file_put_contents('vars/groups.var', implode("\n", array_map(['Functions', 'implodeByTab'], $groups)) . "\n");
                            //Group done, proceed with special forum rights
                            $newGroupRights = (array) Functions::getValueFromGlobals('new_right') + array_fill(0, 6, '');
                            ksort($newGroupRights);
                            Functions::file_put_contents('foren/' . $forumID . '-rights.xbb', (empty($specialRights) ? 1 : current(end($specialRights))+1) . "\t2\t" . $newGroupID . "\t" . Functions::implodeByTab($newGroupRights) . "\t\t\t\t\t\t\n", FILE_APPEND);
                            //Done
                            Logger::getInstance()->log('%s added new special group right for forum (ID: ' . $forumID . ')', LOG_ACP_ACTION);
                            header('Location: ' . INDEXFILE . '?faction=ad_forum&mode=edit_forum_rights&forum_id=' . $forumID . SID_AMPER_RAW);
                            Template::getInstance()->printMessage('special_right_added');
                            break;
                        }
                    //This should not happen
                    $this->errors[] = '<b>ERROR:</b> Group was not found!';
                }
            }
            Template::getInstance()->assign(['forumID' => $forumID,
                //Only assign groups without having special rights for this forum
                'groups' => array_filter($groups, fn($group) => !in_array($group[0], [implode(',', $specialGroupIDs)])),
                'forumRights' => array_map(function($right)
                {
                    return $right == 1;
                }, Functions::explodeByComma($this->forums[$key][10]))]);
            break;

            case 'kill_right':
            $forumID = intval(Functions::getValueFromGlobals('forum_id'));
            $specialRights = @Functions::file('foren/' . $forumID . '-rights.xbb') or Template::getInstance()->printMessage('forum_not_found');
            $specialRightID = intval(Functions::getValueFromGlobals('right_id'));
            $size = count($specialRights = array_map(['Functions', 'explodeByTab'], $specialRights));
            for($i=0; $i<$size; $i++)
                if($specialRights[$i][0] == $specialRightID)
                {
                    if($specialRights[$i][1] == '1')
                        //Delete special user right
                        unset($specialRights[$i]);
                    elseif($specialRights[$i][1] == '2')
                    {
                        $groups = array_map(['Functions', 'explodeByTab'], Functions::file('vars/groups.var'));
                        foreach($groups as &$curGroup)
                            if($curGroup[0] == $specialRights[$i][2])
                            {
                                if(($key = array_search($forumID, ($curGroup[5] = Functions::explodeByComma($curGroup[5])))) !== false)
                                {
                                    //Delete special group right - part 1
                                    unset($curGroup[5][$key]);
                                    $curGroup[5] = implode(',', $curGroup[5]);
                                    Functions::file_put_contents('vars/groups.var', implode("\n", array_map(['Functions', 'implodeByTab'], $groups)) . "\n");
                                }
                                break;
                            }
                        //Delete special group right - part 2
                        unset($specialRights[$i]);
                    }
                    if(empty($specialRights))
                        Functions::unlink('foren/' . $forumID . '-rights.xbb');
                    else
                        Functions::file_put_contents('foren/' . $forumID . '-rights.xbb', implode("\n", array_map(['Functions', 'implodeByTab'], $specialRights)) . "\n");
                    Logger::getInstance()->log('%s deleted special right for forum (ID: ' . $forumID . ')', LOG_ACP_ACTION);
                    header('Location: ' . INDEXFILE . '?faction=ad_forum&mode=edit_forum_rights&forum_id=' . $forumID . SID_AMPER_RAW);
                    Template::getInstance()->printMessage('special_right_deleted');
                    break;
                }
            Template::getInstance()->printMessage('special_right_not_found');
            break;

//AdminForumIndexCat
            case 'viewkg':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('manage_categories'), INDEXFILE . '?faction=ad_forum&amp;mode=viewkg' . SID_AMPER);
            unset($this->catTable[-1]); //Don't list this one
            break;

//AdminForumNewCat
            case 'newkg':
            NavBar::getInstance()->addElement([
                [Language::getInstance()->getString('manage_categories'), INDEXFILE . '?faction=ad_forum&amp;mode=viewkg' . SID_AMPER],
                [Language::getInstance()->getString('add_new_category'), INDEXFILE . '?faction=ad_forum&amp;mode=newkg' . SID_AMPER]]);
            $newName = htmlspecialchars(trim(Functions::getValueFromGlobals('name')));
            if(Functions::getValueFromGlobals('newkg') == 'yes')
            {
                if(empty($newName))
                    $this->errors[] = Language::getInstance()->getString('please_enter_a_category_name');
                else
                {
                    //Get and update newest cat ID
                    Functions::file_put_contents('vars/kgs.var', $newCatID = Functions::file_get_contents('vars/kgs.var')+1);
                    //Add new category
                    Functions::file_put_contents('vars/kg.var', $newCatID . "\t" . $newName . "\t\n", FILE_APPEND);
                    //Done
                    Logger::getInstance()->log('%s created new category (ID: ' . $newCatID . ')', LOG_ACP_ACTION);
                    header('Location: ' . INDEXFILE . '?faction=ad_forum&mode=viewkg' . SID_AMPER_RAW);
                    Template::getInstance()->printMessage('new_category_added');
                }
            }
            Template::getInstance()->assign('newName', $newName);
            break;

            case 'movekgup':
            if(($key = array_search($catID = intval(Functions::getValueFromGlobals('id')), array_map('current', $cats = array_map(['Functions', 'explodeByTab'], Functions::file('vars/kg.var'))))) === false)
                Template::getInstance()->printMessage('category_not_found');
            //Already on top?
            if($key != 0)
            {
                //Credits for this nice var swapping idea goes to hasin:
                //http://booleandreams.wordpress.com/2008/07/30/how-to-swap-values-of-two-variables-without-using-a-third-variable/#comment-10486
                list($cats[$key], $cats[$key-1]) = [$cats[$key-1], $cats[$key]];
                Functions::file_put_contents('vars/kg.var', implode("\n", array_map(['Functions', 'implodeByTab'], $cats)) . "\n");
            }
            header('Location: ' . INDEXFILE . '?faction=ad_forum&mode=viewkg' . SID_AMPER_RAW);
            Template::getInstance()->printMessage('category_moved');
            break;

            case 'movekgdown':
            if(($key = array_search($catID = intval(Functions::getValueFromGlobals('id')), array_map('current', $cats = array_map(['Functions', 'explodeByTab'], Functions::file('vars/kg.var'))))) === false)
                Template::getInstance()->printMessage('category_not_found');
            //Already at bottom?
            if($key != count($cats)-1)
            {
                //Credits for this nice var swapping idea goes to hasin:
                //http://booleandreams.wordpress.com/2008/07/30/how-to-swap-values-of-two-variables-without-using-a-third-variable/#comment-10486
                list($cats[$key], $cats[$key+1]) = [$cats[$key+1], $cats[$key]];
                Functions::file_put_contents('vars/kg.var', implode("\n", array_map(['Functions', 'implodeByTab'], $cats)) . "\n");
            }
            header('Location: ' . INDEXFILE . '?faction=ad_forum&mode=viewkg' . SID_AMPER_RAW);
            Template::getInstance()->printMessage('category_moved');
            break;

//AdminForumEditCat
            case 'chgkg':
            $catID = intval(Functions::getValueFromGlobals('id'));
            NavBar::getInstance()->addElement([
                [Language::getInstance()->getString('manage_categories'), INDEXFILE . '?faction=ad_forum&amp;mode=viewkg' . SID_AMPER],
                [Language::getInstance()->getString('edit_category'), INDEXFILE . '?faction=ad_forum&amp;mode=chgkg' . SID_AMPER]]);
            if(!isset($catID))
                Template::getInstance()->printMessage('category_not_found');
            $editName = htmlspecialchars(trim(Functions::getValueFromGlobals('name')));
            if(Functions::getValueFromGlobals('chgkg') == 'yes')
            {
                if(empty($editName))
                    $this->errors[] = Language::getInstance()->getString('please_enter_a_category_name');
                else
                {
                    $this->catTable[$catID] = $editName;
                    unset($this->catTable[-1]);
                    //Prepare cat table for writing
                    foreach($this->catTable as $curCatID => $curCatName)
                        $this->catTable[$curCatID] = [$curCatID, $curCatName];
                    Functions::file_put_contents('vars/kg.var', implode("\n", array_map(['Functions', 'implodeByTab'], $this->catTable)) . "\n");
                    //Done
                    Logger::getInstance()->log('%s edited category (ID: ' . $catID . ')', LOG_ACP_ACTION);
                    header('Location: ' . INDEXFILE . '?faction=ad_forum&mode=viewkg' . SID_AMPER_RAW);
                    Template::getInstance()->printMessage('category_edited');
                }
            }
            else
                $editName = $this->catTable[$catID];
            Template::getInstance()->assign(['catID' => $catID,
                'editName' => $editName]);
            break;

            case 'killkg':
            $catID = intval(Functions::getValueFromGlobals('id'));
            if(!isset($catID))
                Template::getInstance()->printMessage('category_not_found');
            unset($this->catTable[-1], $this->catTable[$catID]);
            //Prepare cat table for writing
            foreach($this->catTable as $curCatID => $curCatName)
                $this->catTable[$curCatID] = [$curCatID, $curCatName];
            Functions::file_put_contents('vars/kg.var', empty($this->catTable) ? '' : implode("\n", array_map(['Functions', 'implodeByTab'], $this->catTable)) . "\n");
            //Done
            Logger::getInstance()->log('%s deleted category (ID: ' . $catID . ')', LOG_ACP_ACTION);
            header('Location: ' . INDEXFILE . '?faction=ad_forum&mode=viewkg' . SID_AMPER_RAW);
            Template::getInstance()->printMessage('category_deleted');
            break;
        }
        Template::getInstance()->printPage(self::$modeTable[$this->mode], ['catTable' => $this->catTable,
            'errors' => $this->errors]);
    }

    /**
     * Returns all user IDs with moderator positions.
     *
     * @return array Moderator IDs of entire board
     */
    private function getModIDs(): array
    {
        return array_filter(Functions::explodeByComma(implode(',', array_map(function($forum)
        {
            return implode(',', (array) $forum[11]);
        }, $this->forums))), 'is_numeric');
    }
}
?>