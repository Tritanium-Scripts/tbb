<?php
/**
 * Manages new replies, poster IPs and post/poll management.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2024 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class Posting extends PublicModule
{
    use Singleton, Mode, Errors;

    /**
     * Data of target forum to post in / edit.
     *
     * @var array|bool Loaded forum data or false
     */
    private $forum;

    /**
     * Translates a mode to its template file.
     *
     * @var array Mode and template counterparts
     */
    private static array $modeTable = [
        //Reply actions
        'reply' => 'PostReply',
        'save' => 'PostReply',
        //IP actions
        'viewip' => 'PostViewIP',
        'sperren' => 'PostBlockIP',
        //Post actions
        'edit' => 'EditPost',
        'kill' => 'EditPostConfirmDelete',
        //Topic actions
        'killTopic' => 'EditTopicDelete',
        'close' => 'EditTopicClose',
        'open' => 'EditTopicOpen',
        'move' => 'EditTopicMove',
        //Poll actions
        'vote' => 'EditPoll',
        'editpoll' => 'EditPoll'];

    /**
     * Data of new reply.
     *
     * @var array New reply data
     */
    private array $newReply;

    /**
     * Contains calculated page number of post.
     *
     * @var int Page number
     */
    private int $page;

    /**
     * ID of post to edit or view IP.
     *
     * @var int Post ID
     */
    private int $postID;

    /**
     * New reply being previewed.
     *
     * @var bool New reply previewed
     */
    private bool $preview;

    /**
     * Meta data of target topic to post in / edit.
     *
     * @var array|bool Loaded topic data or false
     */
    private $topic;

    /**
     * Posts of target topic to post in / edit.
     *
     * @var array Topic posts
     */
    private array $topicFile;

    /**
     * ID of topic to reply to / edit.
     *
     * @var int Topic ID
     */
    private int $topicID;

    /**
     * Loads various data, sets IDs and mode.
     *
     * @param string $mode Mode to execute
     */
    function __construct(string $mode)
    {
        parent::__construct();
        $this->mode = $mode;
        $this->forum = Functions::getForumData($forumID = intval(Functions::getValueFromGlobals('forum_id')));
        $this->topicID = intval(Functions::getValueFromGlobals('thread_id')) ?: intval(Functions::getValueFromGlobals('topic_id'));
        $this->postID = intval(Functions::getValueFromGlobals('post_id')) ?: intval(Functions::getValueFromGlobals('quote'));
        if(($this->topicFile = @Functions::file('foren/' . $forumID . '-' . $this->topicID . '.xbb')) != false) //Not $this->forum[0] in case of "false[0]"
        {
            #0:postID - 1:posterID - 2:proprietaryDate - 3:post - 4:ip - 5:isSignature - 6:tSmileyID - 7:isSmiliesOn - 8:isBBCode - 9:isHTML[ - 10:lastEditByID]
            $this->topicFile = array_map(['Functions', 'explodeByTab'], $this->topicFile);
            #0:open/close[/moved] - 1:title - 2:userID - 3:tSmileyID - 4:notifyNewReplies[/movedForumID] - 5:timestamp[/movedTopicID] - 6:views[/prefixID] - 7:pollID[ - 8:subscribedUserIDs - 9:prefixID]
            $this->topic = array_shift($this->topicFile);
            $this->page = max(ceil(array_search($this->postID, array_map('current', $this->topicFile)) / Config::getInstance()->getCfgVal('posts_per_page')), 1);
        }
        $this->preview = Functions::getValueFromGlobals('preview') != '';
        //Get contents for new reply
        $this->newReply = ['nick' => htmlspecialchars(trim(Functions::getValueFromGlobals('nli_name'))),
            'title' => htmlspecialchars(trim(Functions::getValueFromGlobals('title'))),
            'post' => htmlspecialchars(trim(Functions::getValueFromGlobals('post', false))),
            'tSmileyID' => intval(Functions::getValueFromGlobals('tsmilie')),
            'isSmilies' => Functions::getValueFromGlobals('smilies') == '1',
            'isSignature' => Functions::getValueFromGlobals('show_signatur') == '1',
            'isBBCode' => Functions::getValueFromGlobals('use_upbcode') == '1',
            'isXHTML' => Functions::getValueFromGlobals('use_htmlcode') == '1',
            'isAddURLs' => Functions::getValueFromGlobals('isAddURLs') == 'true',
            'prefixId' => intval(Functions::getValueFromGlobals('prefixId'))];
        //Topic smiley fix
        if(empty($this->newReply['tSmileyID']))
            $this->newReply['tSmileyID'] = 1;
    }

    /**
     * Executes the mode.
     */
    public function publicCall(): void
    {
        //General checks and navbar for every mode
        if($this->forum == false)
            Template::getInstance()->printMessage('forum_not_found');
        NavBar::getInstance()->addElement($this->forum[1], INDEXFILE . '?mode=viewforum&amp;forum_id=' . $this->forum[0] . SID_AMPER);
        if(Auth::getInstance()->isBanned())
            Template::getInstance()->printMessage('banned_from_forum');
        if($this->topicFile == false)
            Template::getInstance()->printMessage('topic_not_found');
        NavBar::getInstance()->addElement(Functions::censor($this->topic[1]), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $this->forum[0] . '&amp;thread=' . $this->topicID . SID_AMPER);
        if($this->topic[0] == 'm')
            Template::getInstance()->printMessage('topic_has_moved', INDEXFILE . '?mode=viewthread&amp;forum_id=' . $this->topic[4] . '&amp;thread=' . $this->topic[5] . SID_AMPER, Functions::getMsgBackLinks($this->forum[0]));
        //Execute action (and subaction)
        switch($this->mode)
        {
//EditTopicSubscribe
            case 'subscribe':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('subscribe'), INDEXFILE . '?faction=topic&amp;mode=subscribe&amp;forum_id=' . $this->forum[0] . '&amp;topic_id=' . $this->topicID . SID_AMPER);
            if(!Functions::checkUserAccess($this->forum[0], 0))
                Template::getInstance()->printMessage(Auth::getInstance()->isLoggedIn() ? 'permission_denied' : 'login_only', INDEXFILE . '?faction=register' . SID_AMPER, INDEXFILE . '?faction=login' . SID_AMPER);
            if($this->topic[4] == '0' && Auth::getInstance()->getUserID() == $this->topic[2])
                $this->topic[4] = '1';
            elseif(!in_array(Auth::getInstance()->getUserID(), $subscribedUserIDs = Functions::explodeByComma($this->topic[8])) && Auth::getInstance()->getUserID() != $this->topic[2])
            {
                $subscribedUserIDs[] = Auth::getInstance()->getUserID();
                $this->topic[8] = implode(',', $subscribedUserIDs);
            }
            else
                Template::getInstance()->printMessage('topic_already_subscribed');
            Functions::file_put_contents('foren/' . $this->forum[0] . '-' . $this->topicID . '.xbb', Functions::implodeByTab($this->topic) . "\n" . implode("\n", array_map(['Functions', 'implodeByTab'], $this->topicFile)));
            Functions::skipConfirmMessage(INDEXFILE . '?mode=viewthread&forum_id=' . $this->forum[0] . '&thread=' . $this->topicID . SID_AMPER_RAW);
            Template::getInstance()->printMessage('topic_subscribed', Functions::getMsgBackLinks($this->forum[0], $this->topicID));
            break;

//EditTopicUnsubscribe
            case 'unsubscribe':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('unsubscribe'), INDEXFILE . '?faction=topic&amp;mode=unsubscribe&amp;forum_id=' . $this->forum[0] . '&amp;topic_id=' . $this->topicID . SID_AMPER);
            if(!Functions::checkUserAccess($this->forum[0], 0))
                Template::getInstance()->printMessage(Auth::getInstance()->isLoggedIn() ? 'permission_denied' : 'login_only', INDEXFILE . '?faction=register' . SID_AMPER, INDEXFILE . '?faction=login' . SID_AMPER);
            if($this->topic[4] == '1' && Auth::getInstance()->getUserID() == $this->topic[2])
                $this->topic[4] = '0';
            elseif(in_array(Auth::getInstance()->getUserID(), $subscribedUserIDs = Functions::explodeByComma($this->topic[8])) && Auth::getInstance()->getUserID() != $this->topic[2])
            {
                unset($subscribedUserIDs[array_search(Auth::getInstance()->getUserID(), $subscribedUserIDs)]);
                $this->topic[8] = implode(',', $subscribedUserIDs);
            }
            else
                Template::getInstance()->printMessage('topic_already_unsubscribed');
            Functions::file_put_contents('foren/' . $this->forum[0] . '-' . $this->topicID . '.xbb', Functions::implodeByTab($this->topic) . "\n" . implode("\n", array_map(['Functions', 'implodeByTab'], $this->topicFile)));
            Functions::skipConfirmMessage(INDEXFILE . '?mode=viewthread&forum_id=' . $this->forum[0] . '&thread=' . $this->topicID . SID_AMPER_RAW);
            Template::getInstance()->printMessage('topic_unsubscribed', Functions::getMsgBackLinks($this->forum[0], $this->topicID));
            break;

//PostReply
            case 'reply':
            case 'save':
            setcookie('upbwhere', INDEXFILE . '?faction=reply&forum_id=' . $this->forum[0] . '&thread_id=' . $this->topicID); //Redir cookie after login
            //Specific checks and navbar for this mode
            NavBar::getInstance()->addElement(Language::getInstance()->getString('post_new_reply'), INDEXFILE . '?faction=reply&amp;thread_id=' . $this->topicID . '&amp;forum_id=' . $this->forum[0] . SID_AMPER);
            if(!Functions::checkUserAccess($this->forum, 2, 8))
                Template::getInstance()->printMessage(Auth::getInstance()->isLoggedIn() ? 'permission_denied' : 'login_only', INDEXFILE . '?faction=register' . SID_AMPER, INDEXFILE . '?faction=login' . SID_AMPER);
            elseif($this->topic[0] != '1' && $this->topic[0] != 'open' && !Auth::getInstance()->isAdmin() && !($isMod = Functions::checkModOfForum($this->forum)))
                Template::getInstance()->printMessage('topic_is_closed');
            //Auto URL check
            if($this->newReply['isAddURLs'] && $this->newReply['isBBCode'])
                $this->newReply['post'] = Functions::addURL($this->newReply['post']);
            //Preview...
            if($this->preview)
                $this->newReply['preview'] = ['title' => &$this->newReply['title'],
                    'tSmileyID' => Functions::getTSmileyURL($this->newReply['tSmileyID']),
                    'post' => BBCode::getInstance()->parse(Functions::nl2br($this->newReply['post']), $this->newReply['isXHTML'], $this->newReply['isSmilies'], $this->newReply['isBBCode'], $this->topicFile),
                    'signature' => $this->newReply['isSignature'] ? BBCode::getInstance()->parse(Auth::getInstance()->getUserSig()) : false];
            //...or final save...
            elseif($this->mode == 'save')
            {
                if(!Auth::getInstance()->isLoggedIn() && empty($this->newReply['nick']) && Config::getInstance()->getCfgVal('nli_must_enter_name') == 1)
                    $this->errors[] = Language::getInstance()->getString('please_enter_your_user_name');
                if(empty($this->newReply['post']))
                    $this->errors[] = Language::getInstance()->getString('please_enter_a_post');
                if(isset($_SESSION['lastPost']) && time() < ($greenPeriod = $_SESSION['lastPost']+intval(Config::getInstance()->getCfgVal('spam_delay'))))
                    $this->errors[] = sprintf(Language::getInstance()->getString('please_wait_x_seconds_to_avoid_spam'), $greenPeriod-time());
                if(empty($this->errors))
                {
                    //Set proper nick name
                    $this->newReply['nick'] = Auth::getInstance()->isLoggedIn() ? Auth::getInstance()->getUserID() : (empty($this->newReply['nick']) ? Language::getInstance()->getString('guest') : '0' . $this->newReply['nick']);
                    //Build new post
                    $newPost = [current(end($this->topicFile))+1,
                        $this->newReply['nick'],
                        gmdate('YmdHis'),
                        Functions::stripSIDs(Functions::nl2br($this->newReply['post'])),
                        Functions::getIPAddress(),
                        $this->newReply['isSignature'] ? '1' : '0',
                        $this->newReply['tSmileyID'],
                        $this->newReply['isSmilies'] ? '1' : '0',
                        $this->newReply['isBBCode'] ? '1' : '0',
                        $this->newReply['isXHTML'] ? '1' : '0',
                        '', '', "\n"];
                    //Write post related stuff
                    $this->topic[5] = $_SESSION['lastPost'] = time();
                    $this->topicFile[] = $newPost;
                    Functions::file_put_contents('foren/' . $this->forum[0] . '-' . $this->topicID . '.xbb', Functions::implodeByTab($this->topic) . "\n" . implode("\n", array_map(['Functions', 'implodeByTab'], $this->topicFile)));
                    $this->setTopicOnTop();
                    //Update all the counters and stats
                    Functions::updateForumData($this->forum[0], 0, 1, $this->topicID, $this->newReply['nick'], $newPost[2], $this->newReply['tSmileyID']);
                    if(Auth::getInstance()->isLoggedIn())
                        Functions::updateUserPostCounter($this->newReply['nick']);
                    Functions::getFileLock('ltposts');
                    if($this->forum[10][6] == '1')
                        Functions::updateLastPosts($this->forum[0], $this->topicID, $this->newReply['nick'], $newPost[2], $this->newReply['tSmileyID'], $newPost[0]);
                    Functions::updateTodaysPosts($this->forum[0], $this->topicID, $this->newReply['nick'], $newPost[2], $this->newReply['tSmileyID'], $newPost[0]);
                    Functions::releaseLock('ltposts');
                    //Notify topic creator and subscribed users
                    if(Config::getInstance()->getCfgVal('activate_mail') == 1 && Config::getInstance()->getCfgVal('notify_new_replies') == 1)
                    {
                        if($this->topic[4] == '1' && Auth::getInstance()->getUserID() != $this->topic[2] && ($notifyUser = Functions::getUserData($this->topic[2])) != false)
                            Functions::sendMessage($notifyUser[3], 'notify_new_reply', $notifyUser[0], Config::getInstance()->getCfgVal('address_to_forum') . '/' . INDEXFILE . '?mode=viewthread&forum_id=' . $this->forum[0] . '&thread=' . $this->topicID);
                        foreach(Functions::explodeByComma($this->topic[8]) as $curSubscribedUserID)
                            if(Auth::getInstance()->getUserID() != $curSubscribedUserID && ($notifyUser = Functions::getUserData($curSubscribedUserID)) != false)
                                Functions::sendMessage($notifyUser[3], 'notify_sub_new_reply', $notifyUser[0], Config::getInstance()->getCfgVal('address_to_forum') . '/' . INDEXFILE . '?mode=viewthread&forum_id=' . $this->forum[0] . '&thread=' . $this->topicID);
                    }
                    //Done
                    Logger::getInstance()->log('New reply (' . $this->forum[0] . ',' . $this->topicID . ') posted by %s', Logger::LOG_NEW_POSTING);
                    Functions::skipConfirmMessage(INDEXFILE . '?mode=viewthread&forum_id=' . $this->forum[0] . '&thread=' . $this->topicID . '&z=last' . SID_AMPER_RAW . '#post' . $newPost[0]);
                    Template::getInstance()->printMessage('reply_posted', Functions::getMsgBackLinks($this->forum[0], $this->topicID, 'view_new_reply', $newPost[0]));
                }
            }
            //...or first call and add possible quote with quoted user
            elseif(!empty($this->postID))
            {
                if(($quote = $this->getPostData($this->postID)) != false)
                    $this->newReply['post'] = '[quote=' . (!Functions::isGuestID($quote[1])
                            ? (($this->newReply['post'] = Functions::getUserData($quote[1])) !== false ? current($this->newReply['post']) : Language::getInstance()->getString('deleted'))
                            : Functions::substr($quote[1], 1)) . ']' .
                        Functions::br2nl(in_array(Auth::getInstance()->getUserID(), array_filter(array_unique(@array_map('next', $this->topicFile)), fn($id) => !Functions::isGuestID($id)))
                            ? $quote[3]
                            : preg_replace("/\[lock\](.*?)\[\/lock\]/si", '', $quote[3])) . '[/quote]';
                else
                    $this->errors[] = Language::getInstance()->getString('quoted_post_was_not_found');
            }
            //Process last x posts in reverse order
            $lastReplies = [];
            foreach(array_reverse(array_slice($this->topicFile, -10)) as $curReply) //Just the last 10 replies
                $lastReplies[] = ['nick' => Functions::getProfileLink($curReply[1], true),
                    'post' => Functions::censor(BBCode::getInstance()->parse($curReply[3], $curReply[9] == '1' && $this->forum[7][1] == '1', $curReply[7] == '1' || $curReply[7] == 'yes', ($curReply[8] == '1' || $curReply[8] == 'yes') && $this->forum[7][0] == '1', $this->topicFile))];
            Template::getInstance()->assign(['newReply' => $this->newReply,
                'preview' => $this->preview,
                'lastReplies' => $lastReplies,
                'isMod' => isset($isMod) ? $isMod : Functions::checkModOfForum($this->forum)]);
            break;

//EditPost
            case 'edit':
            case 'kill':
            #0:postID - 1:posterID - 2:proprietaryDate - 3:post - 4:ip - 5:isSignature - 6:tSmileyID - 7:isSmilies - 8:isBBCode - 9:isXHTML
            $post = $this->getPostData($this->postID) or Template::getInstance()->printMessage('post_not_found');
            if(!Auth::getInstance()->isLoggedIn() || !(Functions::checkUserAccess($this->forum[0], 4) || Functions::getTimestamp(gmdate('YmdHis')) < Functions::getTimestamp($post[2])+intval(Config::getInstance()->getCfgVal('edit_time'))))
                Template::getInstance()->printMessage(Auth::getInstance()->isLoggedIn() ? 'permission_denied' : 'login_only', INDEXFILE . '?faction=register' . SID_AMPER, INDEXFILE . '?faction=login' . SID_AMPER);
            elseif(!($isMod = Functions::checkModOfForum($this->forum)) && $post[1] != Auth::getInstance()->getUserID() && !Auth::getInstance()->isAdmin())
                Template::getInstance()->printMessage('permission_denied');
            //Delete post?
            if($this->mode == 'kill')
            {
//EditPostConfirmDelete
                NavBar::getInstance()->addElement(Language::getInstance()->getString('delete_post'), INDEXFILE . '?faction=edit&amp;mode=kill&amp;forum_id=' . $this->forum[0] . '&amp;topic_id=' . $this->topicID . '&amp;post_id=' . $this->postID . SID_AMPER);
                //Confirmed?
                if(Functions::getValueFromGlobals('kill') == 'yes')
                {
                    $size = count($this->topicFile);
                    //Before doing any updates, check if deleted post was the only one left
                    if($size == 1)
                    {
                        //Topic was pinned?
                        if(($stickyFile = @Functions::file('foren/' . $this->forum[0] . '-sticker.xbb', FILE_SKIP_EMPTY_LINES)) != false && ($key = array_search($this->topicID, $stickyFile)) !== false)
                        {
                            unset($stickyFile[$key]);
                            Functions::file_put_contents('foren/' . $this->forum[0] . '-sticker.xbb', implode("\n", $stickyFile));
                        }
                        //Topic was poll?
                        if($this->topic[7] != '')
                        {
                            Functions::unlink('polls/' . $this->topic[7] . '-1.xbb');
                            Functions::unlink('polls/' . $this->topic[7] . '-2.xbb');
                        }
                        //Delete topic
                        Functions::unlink('foren/' . $this->forum[0] . '-' . $this->topicID . '.xbb');
                        //Update topic ID index
                        $topicIDs = Functions::file('foren/' . $this->forum[0] . '-threads.xbb', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                        if(($key = array_search($this->topicID, $topicIDs)) !== false)
                        {
                            unset($topicIDs[$key]);
                            Functions::file_put_contents('foren/' . $this->forum[0] . '-threads.xbb', empty($topicIDs) ? '' : implode("\n", $topicIDs) . "\n");
                        }
                        //Update counters and set new last post
                        Functions::updateForumData($this->forum[0], -1, -1);
                        //Done
                        Logger::getInstance()->log('%s deleted topic by deleting the last post (' . $this->forum[0] . ',' . $this->topicID . ',' . $this->postID . ')', Logger::LOG_EDIT_POSTING);
                        Functions::skipConfirmMessage(INDEXFILE . '?mode=viewforum&forum_id=' . $this->forum[0] . SID_AMPER_RAW);
                        Template::getInstance()->printMessage('topic_deleted', Functions::getMsgBackLinks($this->forum[0]));
                    }
                    else
                    {
                        //Look up and delete target post, continue imploding otherwise
                        for($i=0; $i<$size; $i++)
                            if($this->topicFile[$i][0] == $this->postID)
                                unset($this->topicFile[$i]);
                            else
                                $this->topicFile[$i] = Functions::implodeByTab($this->topicFile[$i]);
                        //Update topic and associated counters
                        Functions::file_put_contents('foren/' . $this->forum[0] . '-' . $this->topicID . '.xbb', Functions::implodeByTab($this->topic) . "\n" . implode("\n", $this->topicFile) . "\n");
                        Functions::updateForumData($this->forum[0], 0, -1);
                        //Done
                        Logger::getInstance()->log('%s deleted post (' . $this->forum[0] . ',' . $this->topicID . ',' . $this->postID . ')', Logger::LOG_EDIT_POSTING);
                        Functions::skipConfirmMessage(INDEXFILE . '?mode=viewthread&forum_id=' . $this->forum[0] . '&thread=' . $this->topicID . SID_AMPER_RAW);
                        Template::getInstance()->printMessage('post_deleted', Functions::getMsgBackLinks($this->forum[0], $this->topicID));
                    }
                }
            }
            //Update post
            else
            {
                NavBar::getInstance()->addElement(Language::getInstance()->getString('edit_post'), INDEXFILE . '?faction=edit&amp;forum_id=' . $this->forum[0] . '&amp;topic_id=' . $this->topicID . '&amp;post_id=' . $this->postID . SID_AMPER);
                //Update post
                if(Functions::getValueFromGlobals('update') == 'true')
                {
                    //Look up post to edit
                    foreach($this->topicFile as &$curPost)
                    {
                        if($curPost[0] == $this->postID)
                        {
                            //Reuse (auto-)loaded data in $this->newReply for editing
                            $curPost[3] = Functions::nl2br($this->newReply['isAddURLs'] && $this->newReply['isBBCode'] ? Functions::addURL($this->newReply['post']) : $this->newReply['post']);
                            $curPost[5] = $this->newReply['isSignature'] ? '1' : '0';
                            $curPost[6] = $this->newReply['tSmileyID'];
                            $curPost[7] = $this->newReply['isSmilies'] ? '1' : '0';
                            $curPost[8] = $this->newReply['isBBCode'] ? '1' : '0';
                            $curPost[9] = $this->newReply['isXHTML'] ? '1' : '0';
                            if(Functions::getValueFromGlobals('isLastEditBy') == 'true')
                                $curPost[10] = Auth::getInstance()->getUserID();
                            #else delete? or keep?
                        }
                        $curPost = Functions::implodeByTab($curPost);
                    }
                    //Update title/prefix of topic?
                    if($isMod || Auth::getInstance()->isAdmin())
                    {
                        if(!empty($this->newReply['title']))
                            $this->topic[1] = $this->newReply['title'];
                        $this->topic[9] = $this->newReply['prefixId'];
                    }
                    //Update post in topic
                    Functions::file_put_contents('foren/' . $this->forum[0] . '-' . $this->topicID . '.xbb', Functions::implodeByTab($this->topic) . "\n" . implode("\n", $this->topicFile) . "\n");
                    //Done
                    Logger::getInstance()->log('%s edited post (' . $this->forum[0] . ',' . $this->topicID . ',' . $this->postID . ')', Logger::LOG_EDIT_POSTING);
                    Functions::skipConfirmMessage(INDEXFILE . '?mode=viewthread&forum_id=' . $this->forum[0] . '&thread=' . $this->topicID . '&z=' . $this->page . SID_AMPER_RAW . '#post' . $this->postID);
                    Template::getInstance()->printMessage('post_edited', Functions::getMsgBackLinks($this->forum[0], $this->topicID, 'back_to_post', $this->postID, $this->page));
                }
                //Set data to edit post
                else
                    //Reuse $this->newReply for editing
                    $this->newReply = ['title' => $isMod || Auth::getInstance()->isAdmin() ? $this->topic[1] : '',
                        'post' => Functions::br2nl($post[3]),
                        'isSignature' => $post[5] == '1',
                        'tSmileyID' => $post[6],
                        'isSmilies' => $post[7] == '1',
                        'isBBCode' => $post[8] == '1',
                        'isXHTML' => $post[9] == '1',
                        'isAddURLs' => true,
                        'prefixId' => $isMod || Auth::getInstance()->isAdmin() ? $this->topic[9] : '',
                        'isLastEditBy' => isset($post[10]) && is_numeric($post[10])];
                Template::getInstance()->assign(['editPost' => $this->newReply,
                    'isMod' => $isMod,
                    'prefixes' => array_map(['Functions', 'explodeByTab'], Functions::file('foren/' . $this->forum[0] . '-prefixes.xbb') ?: [])]);
            }
            break;

//EditTopic
            case 'topic':
            case 'killTopic':
            case 'close':
            case 'open':
            case 'move':
            case 'pin':
            case 'unpin':
            if(!Auth::getInstance()->isLoggedIn() || (!Functions::checkModOfForum($this->forum) && !Auth::getInstance()->isAdmin()))
                Template::getInstance()->printMessage('permission_denied');
            switch($this->mode)
            {
//EditTopicDelete
                case 'killTopic':
                NavBar::getInstance()->addElement(Language::getInstance()->getString('delete_topic'), INDEXFILE . '?faction=topic&amp;mode=kill&amp;forum_id=' . $this->forum[0] . '&amp;topic_id=' . $this->topicID . SID_AMPER);
                //Confirmed?
                if(Functions::getValueFromGlobals('kill') == 'yes')
                {
                    //Topic was poll?
                    if($this->topic[7] != '')
                    {
                        Functions::unlink('polls/' . $this->topic[7] . '-1.xbb');
                        Functions::unlink('polls/' . $this->topic[7] . '-2.xbb');
                    }
                    //Topic was pinned?
                    if(($stickyFile = @Functions::file('foren/' . $this->forum[0] . '-sticker.xbb', FILE_SKIP_EMPTY_LINES)) != false && ($key = array_search($this->topicID, $stickyFile)) !== false)
                    {
                        unset($stickyFile[$key]);
                        Functions::file_put_contents('foren/' . $this->forum[0] . '-sticker.xbb', implode("\n", $stickyFile));
                    }
                    //Before deleting, get the amount of posts to subtract from stats
                    $size = count($this->topicFile);
                    //Delete topic
                    Functions::unlink('foren/' . $this->forum[0] . '-' . $this->topicID . '.xbb');
                    //Update topic ID index
                    $topicIDs = Functions::file('foren/' . $this->forum[0] . '-threads.xbb', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    if(($key = array_search($this->topicID, $topicIDs)) !== false)
                    {
                        unset($topicIDs[$key]);
                        Functions::file_put_contents('foren/' . $this->forum[0] . '-threads.xbb', empty($topicIDs) ? '' : implode("\n", $topicIDs) . "\n");
                    }
                    //Update counters
                    Functions::updateForumData($this->forum[0], -1, -$size);
                    //Done
                    Logger::getInstance()->log('%s deleted topic (' . $this->forum[0] . ',' . $this->topicID . ')', Logger::LOG_EDIT_POSTING);
                    Functions::skipConfirmMessage(INDEXFILE . '?mode=viewforum&forum_id=' . $this->forum[0] . SID_AMPER_RAW);
                    Template::getInstance()->printMessage('topic_deleted', Functions::getMsgBackLinks($this->forum[0]));
                }
                break;

//EditTopicClose
                case 'close':
                NavBar::getInstance()->addElement(Language::getInstance()->getString('close_topic'), INDEXFILE . '?faction=topic&amp;mode=close&amp;forum_id=' . $this->forum[0] . '&amp;topic_id=' . $this->topicID . SID_AMPER);
                if(Functions::getValueFromGlobals('close') == 'yes')
                {
                    $this->topic[0] = 2;
                    Functions::file_put_contents('foren/' . $this->forum[0] . '-' . $this->topicID . '.xbb', Functions::implodeByTab($this->topic) . "\n" . implode("\n", array_map(['Functions', 'implodeByTab'], $this->topicFile)));
                    Logger::getInstance()->log('%s closed topic (' . $this->forum[0] . ',' . $this->topicID . ')', Logger::LOG_EDIT_POSTING);
                    Functions::skipConfirmMessage(INDEXFILE . '?mode=viewthread&forum_id=' . $this->forum[0] . '&thread=' . $this->topicID . SID_AMPER_RAW);
                    Template::getInstance()->printMessage('topic_closed', Functions::getMsgBackLinks($this->forum[0], $this->topicID));
                }
                break;

//EditTopicOpen
                case 'open':
                NavBar::getInstance()->addElement(Language::getInstance()->getString('open_topic'), INDEXFILE . '?faction=topic&amp;mode=open&amp;forum_id=' . $this->forum[0] . '&amp;topic_id=' . $this->topicID . SID_AMPER);
                if(Functions::getValueFromGlobals('open') == 'yes')
                {
                    $this->topic[0] = 1;
                    Functions::file_put_contents('foren/' . $this->forum[0] . '-' . $this->topicID . '.xbb', Functions::implodeByTab($this->topic) . "\n" . implode("\n", array_map(['Functions', 'implodeByTab'], $this->topicFile)));
                    Logger::getInstance()->log('%s opened topic (' . $this->forum[0] . ',' . $this->topicID . ')', Logger::LOG_EDIT_POSTING);
                    Functions::skipConfirmMessage(INDEXFILE . '?mode=viewthread&forum_id=' . $this->forum[0] . '&thread=' . $this->topicID . SID_AMPER_RAW);
                    Template::getInstance()->printMessage('topic_opened', Functions::getMsgBackLinks($this->forum[0], $this->topicID));
                }
                break;

//EditTopicMove
                case 'move':
                NavBar::getInstance()->addElement(Language::getInstance()->getString('move_topic'), INDEXFILE . '?faction=topic&amp;mode=move&amp;forum_id=' . $this->forum[0] . '&amp;topic_id=' . $this->topicID . SID_AMPER);
                $isLinked = $isNewest = true;
                if(Functions::getValueFromGlobals('move') == 'yes')
                {
                    $targetForumID = intval(Functions::getValueFromGlobals('target_forum'));
                    $isLinked = Functions::getValueFromGlobals('isLinked') == 'true';
                    $isNewest = Functions::getValueFromGlobals('isNewest') == 'true';
                    if($targetForumID == 0)
                        $this->errors[] = Language::getInstance()->getString('please_select_a_forum');
                    elseif(!Functions::file_exists('foren/' . $targetForumID . '-threads.xbb'))
                        $this->errors[] = Language::getInstance()->getString('text_forum_not_found', 'Messages');
                    elseif(!Functions::checkUserAccess($targetForumID, 0))
                        $this->errors[] = Language::getInstance()->getString('text_permission_denied', 'Messages');
                    if(empty($this->errors))
                    {
                        $isPinned = false;
                        //Topic was pinned?
                        if(($stickyFile = @Functions::file('foren/' . $this->forum[0] . '-sticker.xbb', FILE_SKIP_EMPTY_LINES)) != false && ($key = array_search($this->topicID, $stickyFile)) !== false)
                        {
                            unset($stickyFile[$key]);
                            Functions::file_put_contents('foren/' . $this->forum[0] . '-sticker.xbb', implode("\n", $stickyFile));
                            $isPinned = true;
                        }
                        if(!$isLinked)
                        {
                            //Remove topic from ID list of old forum
                            $topicIDs = Functions::file('foren/' . $this->forum[0] . '-threads.xbb', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                            if(($key = array_search($this->topicID, $topicIDs)) !== false)
                            {
                                unset($topicIDs[$key]);
                                Functions::file_put_contents('foren/' . $this->forum[0] . '-threads.xbb', empty($topicIDs) ? '' : implode("\n", $topicIDs) . "\n");
                            }
                        }
                        //Topic has prefix?
                        if(!empty($this->topic[9]))
                        {
                            $prefixId = $this->topic[9];
                            //Remove prefix ID since they are bound to each forum
                            $this->topic[9] = '';
                            Functions::file_put_contents('foren/' . $this->forum[0] . '-' . $this->topicID . '.xbb', Functions::implodeByTab($this->topic) . "\n" . implode("\n", array_map(['Functions', 'implodeByTab'], $this->topicFile)));
                            //Restore prefix ID for possible permanent link use
                            $this->topic[9] = $prefixId;
                        }
                        //Get new ID for moved topic
                        $newTopicID = Functions::file_get_contents('foren/' . $targetForumID . '-ltopic.xbb')+1;
                        //Append it to ID list of new forum
                        Functions::file_put_contents('foren/' . $targetForumID . '-threads.xbb', $newTopicID . "\n", FILE_APPEND);
                        //Now move the topic
                        rename(DATAPATH . 'foren/' . $this->forum[0] . '-' . $this->topicID . '.xbb', DATAPATH . 'foren/' . $targetForumID . '-' . $newTopicID . '.xbb');
                        //Announce moved topic as newest in target forum
                        Functions::file_put_contents('foren/' . $targetForumID . '-ltopic.xbb', $newTopicID);
                        //Update counters in old and new forum
                        $size = count($this->topicFile);
                        Functions::updateForumData($this->forum[0], -1, -$size);
                        if($isNewest)
                        {
                            $lastPost = end($this->topicFile);
                            Functions::updateForumData($targetForumID, 1, $size, $newTopicID, $lastPost[1], $lastPost[2], $this->topic[3]);
                        }
                        else
                            Functions::updateForumData($targetForumID, 1, $size);
                        //Topic was pinned?
                        if($isPinned)
                        {
                            $stickyFile = @Functions::file('foren/' . $targetForumID . '-sticker.xbb', FILE_SKIP_EMPTY_LINES) ?: [];
                            $stickyFile[] = $newTopicID;
                            Functions::file_put_contents('foren/' . $targetForumID . '-sticker.xbb', implode("\n", $stickyFile));
                        }
                        //Generate permanent link?
                        if($isLinked)
                            Functions::file_put_contents('foren/' . $this->forum[0] . '-' . $this->topicID . '.xbb', 'm' . "\t" . $this->topic[1] . "\t" . $this->topic[2] . "\t" . $this->topic[3] . "\t" . $targetForumID . "\t" . $newTopicID . "\t" . $this->topic[9] . "\n");
                        //Update link(s) in last and todays posts (if topic is listed in there) with some l33t h4x regex magic :)
                        Functions::getFileLock('ltposts');
                        Functions::file_put_contents('vars/lposts.var', preg_replace('/' . $this->forum[0] . ',' . $this->topicID . ',(.*?),(\d+),(\d+),(\d+)/si', $targetForumID . ',' . $newTopicID . ',\1,\2,\3,\4', Functions::file_get_contents('vars/lposts.var')));
                        Functions::file_put_contents('vars/todayposts.var', preg_replace('/' . $this->forum[0] . ',' . $this->topicID . ',(.*?),(\d+),(\d+),(\d+)/si', $targetForumID . ',' . $newTopicID . ',\1,\2,\3,\4', Functions::file_get_contents('vars/todayposts.var')));
                        Functions::releaseLock('ltposts');
                        //Done
                        Logger::getInstance()->log('%s moved topic from (' . $this->forum[0] . ',' . $this->topicID . ') to (' . $targetForumID . ',' . $newTopicID . ')', Logger::LOG_EDIT_POSTING);
                        Template::getInstance()->printMessage('topic_moved', Functions::getMsgBackLinks($targetForumID, $newTopicID, 'to_moved_topic'));
                    }
                }
                //Build forum list to choose from
                $forums = [];
                foreach(array_map(['Functions', 'explodeByTab'], Functions::file('vars/foren.var')) as $curForum)
                    if(Functions::checkUserAccess($curForum, 0) && $curForum[0] != $this->forum[0])
                        $forums[] = ['forumID' => $curForum[0],
                            'forumName' => $curForum[1],
                            'catID' => $curForum[5]];
                Template::getInstance()->assign(['cats' => array_map(['Functions', 'explodeByTab'], Functions::file('vars/kg.var')),
                    'forums' => $forums,
                    'isLinked' => $isLinked,
                    'isNewest' => $isNewest]);
                break;

//EditTopicPin
                case 'pin':
                $stickyFile = @Functions::file('foren/' . $this->forum[0] . '-sticker.xbb', FILE_SKIP_EMPTY_LINES) ?: [];
                if(!in_array($this->topicID, $stickyFile))
                {
                    $stickyFile[] = $this->topicID;
                    Functions::file_put_contents('foren/' . $this->forum[0] . '-sticker.xbb', implode("\n", $stickyFile));
                    Logger::getInstance()->log('%s pinned topic (' . $this->forum[0] . ',' . $this->topicID . ')', Logger::LOG_EDIT_POSTING);
                    Functions::skipConfirmMessage(INDEXFILE . '?mode=viewthread&forum_id=' . $this->forum[0] . '&thread=' . $this->topicID . SID_AMPER_RAW);
                    Template::getInstance()->printMessage('topic_pinned', Functions::getMsgBackLinks($this->forum[0], $this->topicID));
                }
                else
                    Template::getInstance()->printMessage('topic_already_pinned');
                break;

//EditTopicUnpin
                case 'unpin':
                $stickyFile = @Functions::file('foren/' . $this->forum[0] . '-sticker.xbb', FILE_SKIP_EMPTY_LINES) ?: [];
                if(($key = array_search($this->topicID, $stickyFile)) !== false)
                {
                    unset($stickyFile[$key]);
                    Functions::file_put_contents('foren/' . $this->forum[0] . '-sticker.xbb', implode("\n", $stickyFile));
                    Logger::getInstance()->log('%s unpinned topic (' . $this->forum[0] . ',' . $this->topicID . ')', Logger::LOG_EDIT_POSTING);
                    Functions::skipConfirmMessage(INDEXFILE . '?mode=viewthread&forum_id=' . $this->forum[0] . '&thread=' . $this->topicID . SID_AMPER_RAW);
                    Template::getInstance()->printMessage('topic_unpinned', Functions::getMsgBackLinks($this->forum[0], $this->topicID));
                }
                else
                    Template::getInstance()->printMessage('topic_already_unpinned');
                break;
            }
            Template::getInstance()->assign('title', $this->topic[1]);
            break;

            case 'vote':
            case 'update':
            case 'editpoll':
            if($this->topic[7] != Functions::getValueFromGlobals('poll_id'))
                Template::getInstance()->printMessage('forum_poll_mismatch');
            #0:pollState - 1:creatorID - 2:proprietaryDate - 3:title/question - 4:totalVotes - 5:forumID,topicID
            $pollFile = Functions::file('polls/' . $this->topic[7] . '-1.xbb') or Template::getInstance()->printMessage('poll_not_found');
            $pollVoters = Functions::explodeByComma(Functions::file_get_contents('polls/' . $this->topic[7] . '-2.xbb'));
            $pollFile = array_map(['Functions', 'explodeByTab'], $pollFile);
            $poll = array_shift($pollFile);
            //Edit poll...
            if(Functions::getValueFromGlobals('edit') != '' || $this->mode == 'editpoll' || $this->mode == 'update')
            {
//EditPoll
                NavBar::getInstance()->addElement(Language::getInstance()->getString('edit_poll'), INDEXFILE . '?faction=editpoll&amp;forum_id=' . $this->forum[0] . '&amp;topic_id=' . $this->topicID . '&amp;poll_id=' . $this->topic[7] . SID_AMPER);
                if(!Auth::getInstance()->isLoggedIn() || !Functions::checkUserAccess($this->forum[0], 5))
                    Template::getInstance()->printMessage(Auth::getInstance()->isLoggedIn() ? 'permission_denied' : 'login_only', INDEXFILE . '?faction=register' . SID_AMPER, INDEXFILE . '?faction=login' . SID_AMPER);
                elseif($poll[1] != Auth::getInstance()->getUserID() && !Functions::checkModOfForum($this->forum) && !Auth::getInstance()->isAdmin())
                    Template::getInstance()->printMessage('permission_denied');
                if($this->mode == 'update')
                {
                    //Open poll
                    if(Functions::getValueFromGlobals('open') != '' && $poll[0] > '2')
                    {
                        if($poll[0] == '3')
                            $poll[0] = 1;
                        elseif($poll[0] == '4')
                            $poll[0] = 2;
                        Functions::file_put_contents('polls/' . $this->topic[7] . '-1.xbb', Functions::implodeByTab($poll) . "\n" . implode("\n", array_map(['Functions', 'implodeByTab'], $pollFile)) . "\n");
                        Logger::getInstance()->log('%s opened poll (' . $this->forum[0] . ',' . $this->topicID . ')', Logger::LOG_EDIT_POSTING);
                        header('Location: ' . INDEXFILE . '?faction=editpoll&poll_id=' . $this->topic[7] . '&forum_id=' . $this->forum[0] . '&topic_id=' . $this->topicID . SID_AMPER_RAW);
                        Template::getInstance()->printMessage('poll_edited', '');
                    }
                    //Close poll
                    elseif(Functions::getValueFromGlobals('close') != '' && $poll[0] < '3')
                    {
                        if($poll[0] == '1')
                            $poll[0] = 3;
                        elseif($poll[0] == '2')
                            $poll[0] = 4;
                        Functions::file_put_contents('polls/' . $this->topic[7] . '-1.xbb', Functions::implodeByTab($poll) . "\n" . implode("\n", array_map(['Functions', 'implodeByTab'], $pollFile)) . "\n");
                        Logger::getInstance()->log('%s closed poll (' . $this->forum[0] . ',' . $this->topicID . ')', Logger::LOG_EDIT_POSTING);
                        header('Location: ' . INDEXFILE . '?faction=editpoll&poll_id=' . $this->topic[7] . '&forum_id=' . $this->forum[0] . '&topic_id=' . $this->topicID . SID_AMPER_RAW);
                        Template::getInstance()->printMessage('poll_edited', '');
                    }
                    //Update poll
                    else
                    {
                        $choices = Functions::getValueFromGlobals('poll_choice');
                        foreach($pollFile as &$curPollOption)
                        {
                            //Update each found option if it's not empty
                            if(isset($choices[$curPollOption[0]]) && trim($choices[$curPollOption[0]]) != '')
                                $curPollOption[1] = htmlspecialchars(trim($choices[$curPollOption[0]]));
                            //Implode back for writing in any case
                            $curPollOption = Functions::implodeByTab($curPollOption);
                        }
                        Functions::file_put_contents('polls/' . $this->topic[7] . '-1.xbb', Functions::implodeByTab($poll) . "\n" . implode("\n", $pollFile) . "\n");
                        Logger::getInstance()->log('%s edited poll (' . $this->forum[0] . ',' . $this->topicID . ')', Logger::LOG_EDIT_POSTING);
                        Functions::skipConfirmMessage(INDEXFILE . '?mode=viewthread&forum_id=' . $this->forum[0] . '&thread=' . $this->topicID . SID_AMPER_RAW);
                        Template::getInstance()->printMessage('poll_edited', Functions::getMsgBackLinks($this->forum[0], $this->topicID, 'back_to_poll'));
                    }
                }
                Template::getInstance()->assign(['pollTitle' => $poll[3],
                    'isClosed' => $poll[0] > '2',
                    'pollOptions' => $pollFile,
                    'pollID' => $this->topic[7]]);
            }
            //...or vote it
            else
            {
                $voteID = intval(Functions::getValueFromGlobals('vote_id'));
                NavBar::getInstance()->addElement(Language::getInstance()->getString('vote'), INDEXFILE . '?faction=vote&amp;forum_id=' . $this->forum[0] . '&amp;topic_id=' . $this->topicID . '&amp;poll_id=' . $this->topic[7] . '&amp;vote_id=' . $voteID . SID_AMPER);
                if(!Functions::checkUserAccess($this->forum[0], 0))
                    Template::getInstance()->printMessage(Auth::getInstance()->isLoggedIn() ? 'permission_denied' : 'login_only', INDEXFILE . '?faction=register' . SID_AMPER, INDEXFILE . '?faction=login' . SID_AMPER);
                //Check for vote permission
                elseif($poll[0] > '2')
                    Template::getInstance()->printMessage('poll_is_closed');
                elseif(!Auth::getInstance()->isLoggedIn() && $poll[0] != '1')
                    Template::getInstance()->printMessage('poll_need_login');
                elseif((Auth::getInstance()->isLoggedIn() && in_array(Auth::getInstance()->getUserID(), $pollVoters)) || isset($_SESSION['session_poll_' . $this->topic[7]]) || isset($_COOKIE['cookie_poll_' . $this->topic[7]]))
                    Template::getInstance()->printMessage('already_voted');
                elseif(empty($voteID))
                    Template::getInstance()->printMessage('choose_choice');
                //Do voting
                foreach($pollFile as &$curPollOption)
                    if($curPollOption[0] == $voteID)
                    {
                        $poll[4]++; //Total votes 1up
                        $curPollOption[2]++; //Vote option 1up
                        Functions::file_put_contents('polls/' . $this->topic[7] . '-1.xbb', Functions::implodeByTab($poll) . "\n" . implode("\n", array_map(['Functions', 'implodeByTab'], $pollFile)) . "\n");
                        if(Auth::getInstance()->isLoggedIn())
                        {
                            $pollVoters[] = Auth::getInstance()->getUserID();
                            Functions::file_put_contents('polls/' . $this->topic[7] . '-2.xbb', implode(',', $pollVoters));
                        }
                        //Mark as voted
                        $_SESSION['session_poll_' . $this->topic[7]] = true;
                        setcookie('cookie_poll_' . $this->topic[7], '1', time()+3600*24*365, Config::getInstance()->getCfgVal('path_to_forum'));
                        header('Location: ' . INDEXFILE . '?mode=viewthread&forum_id=' . $this->forum[0] . '&thread=' . $this->topicID . SID_AMPER_RAW);
                        Template::getInstance()->printMessage('vote_added');
                    }
                Template::getInstance()->printMessage('choice_not_found');
            }
            break;

//PostViewIP
            case 'viewip':
            case 'sperren':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('view_ip_address'), INDEXFILE . '?faction=viewip&amp;forum_id=' . $this->forum[0] . '&amp;topic_id=' . $this->topicID . '&amp;post_id=' . $this->postID . SID_AMPER);
            if(!Auth::getInstance()->isAdmin() && !Functions::checkModOfForum($this->forum))
                Template::getInstance()->printMessage('permission_denied');
            elseif(($post = $this->getPostData($this->postID)) == false)
                Template::getInstance()->printMessage('post_not_found');
//PostBlockIP
            if($this->mode == 'sperren')
            {
                NavBar::getInstance()->addElement(Language::getInstance()->getString('block_ip_address'), INDEXFILE . '?faction=viewip&amp;mode=sperren&amp;forum_id=' . $this->forum[0] . '&amp;topic_id=' . $this->topicID . '&amp;post_id=' . $this->postID . SID_AMPER);
                if(Functions::checkIPAccess($this->forum[0], $post[4]) !== true)
                    Template::getInstance()->printMessage('ip_already_blocked', Functions::getMsgBackLinks($this->forum[0], $this->topicID));
                if(Functions::getValueFromGlobals('sperren') == 'yes')
                {
                    $blockPeriod = intval(Functions::getValueFromGlobals('spdauer'));
                    $entireBoard = Functions::getValueFromGlobals('foren') == 'ja';
                    if(($blockPeriod != 60 && $blockPeriod != 120 && $blockPeriod != 300 && $blockPeriod != 1440 && $blockPeriod != -1) || ($entireBoard && !Auth::getInstance()->isAdmin()))
                        $this->errors[] = Language::getInstance()->getString('only_admins_text');
                    else
                    {
                        list(,,,$lastIPID) = @end(Functions::getBannedIPs());
                        Functions::file_put_contents('vars/ip.var', Functions::implodeByTab([$post[4], $blockPeriod == '-1' ? $blockPeriod : time()+60*$blockPeriod, $entireBoard ? '-1' : $this->forum[0], $lastIPID+1, '']) . "\n", FILE_APPEND);
                        Template::getInstance()->printMessage('ip_blocked_successfully', Functions::getMsgBackLinks($this->forum[0], $this->topicID));
                    }
                }
            }
            //Assign IP to template in any case
            Template::getInstance()->assign('ipAddress', $post[4]);
        }
        Template::getInstance()->printPage(Functions::handleMode($this->mode, self::$modeTable, __CLASS__, 'reply'), ['forumID' => $this->forum[0],
            'topicID' => $this->topicID,
            'postID' => $this->postID,
            //Just give the template what it needs to know
            'forum' => ['forumID' => $this->forum[0],
                'isBBCode' => $this->forum[7][0] == '1',
                'isXHTML' => $this->forum[7][1] == '1'],
            'errors' => $this->errors],
            //Always append IDs + page to WIO location. WIO will not parse them in inapplicable mode.
            null , ',' . $this->forum[0] . ',' . $this->topicID . ',' . $this->postID . ',' . $this->page);
    }

    /**
     * Returns data of a single post.
     *
     * @param int $postID ID of post of current topic
     * @return array|bool Post data or false if not found
     */
    private function getPostData(int $postID)
    {
        foreach($this->topicFile as $curPost)
            if($curPost[0] == $postID)
                return $curPost;
        return false;
    }

    /**
     * Sets the current loaded topic on newest position in the topic list of current forum.
     */
    private function setTopicOnTop(): void
    {
        $topicIDs = Functions::file('foren/' . $this->forum[0] . '-threads.xbb', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $oldPos = array_search($this->topicID, $topicIDs);
        if($oldPos !== false)
        {
            unset($topicIDs[$oldPos]);
            $topicIDs[] = $this->topicID;
        }
        Functions::file_put_contents('foren/' . $this->forum[0] . '-threads.xbb', implode("\n", $topicIDs) . "\n");
    }
}
//Guess I'm a crazy coder^^
    /* Du musst doch echt verrückt sein, wenn du versuchst meinen Code zu verstehen ;) */
?>