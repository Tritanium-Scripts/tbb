<?php
/**
 * Manages post process of new topic or new poll.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2024 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class PostNew extends PublicModule
{
    use Singleton, Mode, Errors;

    /**
     * Data of target forum to post in.
     *
     * @var array|bool Loaded forum data or false
     */
    private $forum;

    /**
     * New post being previewed.
     *
     * @var bool New post previewed
     */
    private bool $preview;

    /**
     * Translates a mode to its template file.
     *
     * @var array Mode and template counterparts
     */
    private static array $modeTable = ['newtopic' => 'PostNewTopic',
        'newpoll' => 'PostNewPoll',
        'step2' => 'PostNewPoll'];

    /**
     * Data of new post.
     *
     * @var array New post data
     */
    private array $newPost;

    /**
     * Seconds of waiting time between new postings.
     *
     * @var int Spam delay seconds
     */
    private int $spamDelay;

    /**
     * Configured prefixes for topics within the forum.
     *
     * @var array Topic prefixes to select from
     */
    private array $prefixes;

    /**
     * Loads various data and sets mode.
     *
     * @param string $newType Type of new post
     */
    function __construct(string $newType)
    {
        parent::__construct();
        $this->mode = $newType;
        $this->forum = Functions::getForumData(intval(Functions::getValueFromGlobals('forum_id')));
        $this->prefixes = array_map(['Functions', 'explodeByTab'], Functions::file('foren/' . $this->forum[0] . '-prefixes.xbb') ?: []);
        $this->preview = Functions::getValueFromGlobals('preview') != '';
        $this->spamDelay = intval(Config::getInstance()->getCfgVal('spam_delay'));
        //Get contents for new post
        $this->newPost = ['nick' => htmlspecialchars(trim(Functions::getValueFromGlobals('nli_name'))),
            'title' => htmlspecialchars(trim(Functions::getValueFromGlobals('title'))),
            'post' => htmlspecialchars(trim(Functions::getValueFromGlobals('post', false))),
            'tSmiley' => intval(Functions::getValueFromGlobals('tsmilie')),
            'isSmilies' => Functions::getValueFromGlobals('smilies') == '1',
            'isSignature' => Functions::getValueFromGlobals('show_signatur') == '1',
            'isBBCode' => Functions::getValueFromGlobals('use_upbcode') == '1',
            'isXHTML' => Functions::getValueFromGlobals('use_htmlcode') == '1',
            'isNotify' => Functions::getValueFromGlobals('sendmail2') == '1',
            'isAddURLs' => Functions::getValueFromGlobals('isAddURLs') == 'true',
            'prefixId' => intval(Functions::getValueFromGlobals('prefixId'))];
        //Topic smiley fix
        if(empty($this->newPost['tSmiley']))
            $this->newPost['tSmiley'] = 1;
        PlugIns::getInstance()->callHook(PlugIns::HOOK_POST_NEW_INIT);
    }

    /**
     * Posts new topic or poll.
     */
    public function publicCall(): void
    {
        //General checks and navbar for every mode
        if($this->forum == false)
            Template::getInstance()->printMessage('forum_not_found');
        NavBar::getInstance()->addElement($this->forum[1], INDEXFILE . '?mode=viewforum&amp;forum_id=' . $this->forum[0] . SID_AMPER);
        if(Auth::getInstance()->isBanned())
            Template::getInstance()->printMessage('banned_from_forum');
        //Applicable for both modes
        if($this->newPost['isAddURLs'] && $this->newPost['isBBCode'])
            $this->newPost['post'] = Functions::addURL($this->newPost['post']);
        //Execute mode
        switch($this->mode)
        {
//PostNewPoll
            case 'newpoll':
            case 'step2':
            setcookie('upbwhere', INDEXFILE . '?faction=newpoll&forum_id=' . $this->forum[0]); //Redir cookie after login
            //Specific checks and navbar for this mode
            NavBar::getInstance()->addElement(Language::getInstance()->getString('post_new_poll'), INDEXFILE . '?faction=newpoll&amp;forum_id=' . $this->forum[0] . SID_AMPER);
            if(!Functions::checkUserAccess($this->forum, 3, 9))
                Template::getInstance()->printMessage(Auth::getInstance()->isLoggedIn() ? 'forum_no_access' : 'login_only', INDEXFILE . '?faction=register' . SID_AMPER, INDEXFILE . '?faction=login' . SID_AMPER);
            //Add special poll vars
            $this->newPost['pollType'] = intval(Functions::getValueFromGlobals('poll_type'));
            $this->newPost['choices'] = (array) Functions::getValueFromGlobals('poll_choice');
            foreach($this->newPost['choices'] as $key => &$curChoice)
            {
                $curChoice = htmlspecialchars(trim($curChoice));
                if($curChoice == '')
                    unset($this->newPost['choices'][$key]);
            }
            //Preview...
            if($this->preview)
                $this->newPost['preview'] = ['title' => &$this->newPost['title'],
                    'tSmiley' => Functions::getTSmileyURL($this->newPost['tSmiley']),
                    'post' => BBCode::getInstance()->parse(Functions::nl2br($this->newPost['post']), $this->newPost['isXHTML'], $this->newPost['isSmilies'], $this->newPost['isBBCode']),
                    'signature' => $this->newPost['isSignature'] ? BBCode::getInstance()->parse(Auth::getInstance()->getUserSig()) : false,
                    'choices' => &$this->newPost['choices']];
            //...or final save
            elseif(Functions::getValueFromGlobals('save') == 'yes')
            {
                if(($size = count($this->newPost['choices'])) < 2)
                    $this->errors[] = Language::getInstance()->getString('please_enter_at_least_two_choices');
                if(empty($this->newPost['title']))
                    $this->errors[] = Language::getInstance()->getString('please_enter_a_title_for_this_question');
                if(!Auth::getInstance()->isLoggedIn() && empty($this->newPost['nick']) && Config::getInstance()->getCfgVal('nli_must_enter_name') == 1)
                    $this->errors[] = Language::getInstance()->getString('please_enter_your_user_name');
                if(isset($_SESSION['lastPost']) && time() < $_SESSION['lastPost']+$this->spamDelay)
                    $this->errors[] = sprintf(Language::getInstance()->getString('please_wait_x_seconds_to_avoid_spam'), $_SESSION['lastPost']+$this->spamDelay-time());
                //This should be impossible to get, but whatever...
                if($this->newPost['pollType'] != 1 && $this->newPost['pollType'] != 2)
                    $this->errors[] = Language::getInstance()->getString('please_select_a_valid_poll_type');
                if(empty($this->errors))
                {
                    //Set proper nick name
                    $this->newPost['nick'] = Auth::getInstance()->isLoggedIn() ? Auth::getInstance()->getUserID() : '0' . (empty($this->newPost['nick']) ? Language::getInstance()->getString('guest') : $this->newPost['nick']);
                    //Prepare choices for writing
                    for($i=0; $i<$size; $i++)
                        $this->newPost['choices'][$i] = ($i+1) . "\t" . $this->newPost['choices'][$i] . "\t0\t\t\t\t";
                    //Get new IDs
                    $lastPollIDFile = Functions::getLockObject('polls/polls.xbb');
                    $lastTopicIDFile = Functions::getLockObject('foren/' . $this->forum[0] . '-ltopic.xbb');
                    $newLastPollID = $lastPollIDFile->getFileContent()+1;
                    $newLastTopicID = $lastTopicIDFile->getFileContent()+1;
                    //Build and write topic related stuff
                    $newTopic = $this->writeTopic($lastTopicIDFile, $newLastTopicID, $newLastPollID);
                    //Build poll meta data
                    $newPoll = [$this->newPost['pollType'],
                        $this->newPost['nick'],
                        $newTopic[15],
                        $this->newPost['title'],
                        '0', //Total votes
                        $this->forum[0] . ',' . $newLastTopicID,
                        '', '', '', '', '',
                    //Build poll choices
                        "\n" . implode("\n", $this->newPost['choices'])]; //(incl. another empty unused value from poll meta data)
                    //Write poll related stuff
                    Functions::file_put_contents('polls/' . $newLastPollID . '-1.xbb', Functions::implodeByTab($newPoll));
                    Functions::file_put_contents('polls/' . $newLastPollID . '-2.xbb', '');
                    $lastPollIDFile->setFileContent($newLastPollID);
                    //Notify mods
                    if($this->forum[7][2] == '1')
                        foreach(array_map(['Functions', 'getUserData'], Functions::explodeByComma($this->forum[11])) as $curMod)
                            Functions::sendMessage($curMod[3], 'notify_mod_new_poll', $curMod[0], Config::getInstance()->getCfgVal('address_to_forum') . '/' . INDEXFILE . '?mode=viewthread&forum_id=' . $this->forum[0] . '&thread=' . $newLastTopicID);
                    //Done
                    Logger::getInstance()->log('New poll (' . $this->forum[0] . ',' . $newLastTopicID . ') posted by %s', Logger::LOG_NEW_POSTING);
                    Functions::skipConfirmMessage(INDEXFILE . '?mode=viewthread&forum_id=' . $this->forum[0] . '&thread=' . $newLastTopicID . SID_AMPER_RAW);
                    Template::getInstance()->printMessage('poll_posted', Functions::getMsgBackLinks($this->forum[0], $newLastTopicID, 'view_new_poll'));
                }
            }
            break;

//PostNewTopic
            case 'newtopic':
            setcookie('upbwhere', INDEXFILE . '?faction=newtopic&forum_id=' . $this->forum[0]); //Redir cookie after login
            //Specific checks and navbar for this mode
            NavBar::getInstance()->addElement(Language::getInstance()->getString('post_new_topic'), INDEXFILE . '?faction=newtopic&amp;forum_id=' . $this->forum[0] . SID_AMPER);
            if(!Functions::checkUserAccess($this->forum, 1, 7))
                Template::getInstance()->printMessage(Auth::getInstance()->isLoggedIn() ? 'forum_no_access' : 'login_only', INDEXFILE . '?faction=register' . SID_AMPER, INDEXFILE . '?faction=login' . SID_AMPER);
            //Preview...
            if($this->preview)
                $this->newPost['preview'] = ['title' => &$this->newPost['title'],
                    'tSmiley' => Functions::getTSmileyURL($this->newPost['tSmiley']),
                    'post' => BBCode::getInstance()->parse(Functions::nl2br($this->newPost['post']), $this->newPost['isXHTML'], $this->newPost['isSmilies'], $this->newPost['isBBCode']),
                    'signature' => $this->newPost['isSignature'] ? BBCode::getInstance()->parse(Auth::getInstance()->getUserSig()) : false];
            //...or final save
            elseif(Functions::getValueFromGlobals('save') == 'yes')
            {
                if(empty($this->newPost['title']))
                    $this->errors[] = Language::getInstance()->getString('please_enter_a_title');
                if(empty($this->newPost['post']))
                    $this->errors[] = Language::getInstance()->getString('please_enter_a_post');
                if(!Auth::getInstance()->isLoggedIn() && empty($this->newPost['nick']) && Config::getInstance()->getCfgVal('nli_must_enter_name') == 1)
                    $this->errors[] = Language::getInstance()->getString('please_enter_your_user_name');
                if(isset($_SESSION['lastPost']) && time() < $_SESSION['lastPost']+$this->spamDelay)
                    $this->errors[] = sprintf(Language::getInstance()->getString('please_wait_x_seconds_to_avoid_spam'), $_SESSION['lastPost']+$this->spamDelay-time());
                if(empty($this->errors))
                {
                    //Set proper nick name
                    $this->newPost['nick'] = Auth::getInstance()->isLoggedIn() ? Auth::getInstance()->getUserID() : '0' . (empty($this->newPost['nick']) ? Language::getInstance()->getString('guest') : $this->newPost['nick']);
                    $lastTopicIDFile = Functions::getLockObject('foren/' . $this->forum[0] . '-ltopic.xbb');
                    $newLastTopicID = $lastTopicIDFile->getFileContent()+1;
                    //Build and write topic related stuff
                    $this->writeTopic($lastTopicIDFile, $newLastTopicID);
                    //Notify mods
                    if($this->forum[7][2] == '1' && !empty($this->forum[11]))
                        foreach(array_map(['Functions', 'getUserData'], Functions::explodeByComma($this->forum[11])) as $curMod)
                            Functions::sendMessage($curMod[3], 'notify_mod_new_topic', $curMod[0], Config::getInstance()->getCfgVal('address_to_forum') . '/' . INDEXFILE . '?mode=viewthread&forum_id=' . $this->forum[0] . '&thread=' . $newLastTopicID);
                    //Done
                    Logger::getInstance()->log('New topic (' . $this->forum[0] . ',' . $newLastTopicID . ') posted by %s', Logger::LOG_NEW_POSTING);
                    Functions::skipConfirmMessage(INDEXFILE . '?mode=viewthread&forum_id=' . $this->forum[0] . '&thread=' . $newLastTopicID . SID_AMPER_RAW);
                    Template::getInstance()->printMessage('topic_posted', Functions::getMsgBackLinks($this->forum[0], $newLastTopicID, 'view_new_topic'));
                }
            }
            break;
        }
        //Always append IDs to WIO location. WIO will not parse them in inapplicable mode.
        Template::getInstance()->printPage(Functions::handleMode($this->mode, self::$modeTable, __CLASS__, 'newtopic'), ['forumID' => $this->forum[0],
            'newPost' => $this->newPost,
            'prefixes' => $this->prefixes,
            //Just give the template what it needs to know
            'forum' => ['forumID' => $this->forum[0],
                'isBBCode' => $this->forum[7][0] == '1',
                'isXHTML' => $this->forum[7][1] == '1'],
            'preview' => $this->preview,
            'isMod' => Functions::checkModOfForum($this->forum),
            'errors' => $this->errors], null , ',' . $this->forum[0]);
    }

    /**
     * Writes a new topic and updates all associated counters and statistics.
     *
     * @param LockObject $lastTopicIDFile File handle to use for writing new ID
     * @param int $newLastTopicID ID of new topic to write
     * @param int $newLastPollID Optional ID of new poll to link from new topic
     * @return array Compiled topic data from build process
     */
    private function writeTopic(&$lastTopicIDFile, int $newLastTopicID, string $newLastPollID=''): array
    {
        //Build topic meta data
        $newTopic = ['1', //Open state
            $this->newPost['title'],
            $this->newPost['nick'],
            $this->newPost['tSmiley'],
            $this->newPost['isNotify'] ? '1' : '0',
            ($_SESSION['lastPost'] = time()),
            '0', //Views
            $newLastPollID,
            '', //Subscribed user IDs
            $this->newPost['prefixId'],
            '', '', '',
        //Build first post
            "\n1", //Post ID (incl. another empty unused value from topic meta data)
            $this->newPost['nick'],
            gmdate('YmdHis'),
            Functions::stripSIDs(Functions::nl2br($this->newPost['post'])),
            Functions::getIPAddress(),
            $this->newPost['isSignature'] ? '1' : '0',
            $this->newPost['tSmiley'],
            $this->newPost['isSmilies'] ? '1' : '0',
            $this->newPost['isBBCode'] ? '1' : '0',
            $this->newPost['isXHTML'] ? '1' : '0',
            '', '', "\n"];
        //Getting serious: Time to write
        Functions::file_put_contents('foren/' . $this->forum[0] . '-threads.xbb', $newLastTopicID . "\n", FILE_APPEND);
        Functions::file_put_contents('foren/' . $this->forum[0] . '-' . $newLastTopicID . '.xbb', Functions::implodeByTab($newTopic));
        $lastTopicIDFile->setFileContent($newLastTopicID);
        //Update all the counters and stats
        Functions::updateForumData($this->forum[0], 1, 1, $newLastTopicID, $this->newPost['nick'], $newTopic[15], $this->newPost['tSmiley']);
        if(Auth::getInstance()->isLoggedIn())
            Functions::updateUserPostCounter($this->newPost['nick']);
        Functions::getFileLock('ltposts');
        if($this->forum[10][6] == '1')
            Functions::updateLastPosts($this->forum[0], $newLastTopicID, $this->newPost['nick'], $newTopic[15], $this->newPost['tSmiley'], 1);
        Functions::updateTodaysPosts($this->forum[0], $newLastTopicID, $this->newPost['nick'], $newTopic[15], $this->newPost['tSmiley'], 1);
        Functions::releaseLock('ltposts');
        return $newTopic;
    }
}
?>