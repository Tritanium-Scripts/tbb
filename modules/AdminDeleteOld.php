<?php
/**
 * Convenient way to delete topics with a certain date.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2023 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class AdminDeleteOld extends PublicModule
{
    use Singleton, Errors;

    /**
     * Deletes topic from stated forum with given age.
     *
     * @param int $forumID ID of forum
     * @param int $topicAge Topic age in days
     * @return array Stats with number of deleted topics, posts and freed space
     */
    private function deleteTopics(int $forumID, int $topicAge): array
    {
        //Needed counter (really needed, not just for "yay stats!!111oneoneeleven")^^
        $topicCounter = $postCounter = $filesizeCounter = 0;
        //Get topic IDs of forum
        $topicIndex = Functions::file('foren/' . $forumID . '-threads.xbb');
        //Get sticky topics (better not delete those)
        $stickyIndex = @Functions::file('foren/' . $forumID . '-sticker.xbb') ?: [];
        foreach($topicIndex as $curKey => $curTopicID)
        {
            //Load current topic
            $curTopic = Functions::file('foren/' . $forumID . '-' . $curTopicID . '.xbb');
            //Get meta data
            $curTopicData = Functions::explodeByTab(array_shift($curTopic));
            //Ignore moved topics and sticky ones, otherwise check age
            if($curTopicData[0] != 'm' && !in_array($curTopicID, $stickyIndex) && round((time()-$curTopicData[5])/60/60/24) > $topicAge)
            {
                //Delete, update counters
                $filesizeCounter += Functions::unlink('foren/' . $forumID . '-' . $curTopicID . '.xbb');
                unset($topicIndex[$curKey]);
                $topicCounter++;
                $postCounter += count($curTopic);
            }
        }
        //Update topic index
        Functions::file_put_contents('foren/' . $forumID . '-threads.xbb', empty($topicIndex) ? '' : implode("\n", $topicIndex) . "\n");
        //Update serious forum stats with collected data
        Functions::updateForumData($forumID, -$topicCounter, -$postCounter);
        //Return the funky stats
        return [$topicCounter, $postCounter, $filesizeCounter];
    }

    /**
     * Executes module.
     */
    public function publicCall(): void
    {
        Functions::accessAdminPanel();
        NavBar::getInstance()->addElement(Language::getInstance()->getString('delete_old_topics'), INDEXFILE . '?faction=ad_killposts' . SID_AMPER);
        $deleteFromForumID = Functions::getValueFromGlobals('target_forum');
        $topicAge = intval(Functions::getValueFromGlobals('topic_age')) ?: 90;
        if(Functions::getValueFromGlobals('mode') == 'kill')
        {
            if(empty($deleteFromForumID))
                $this->errors[] = Language::getInstance()->getString('please_choose_a_forum');
            else
            {
                $stats = [0, 0, 0];
                if($deleteFromForumID == 'all')
                    //Delete from all forums
                    foreach(array_map(['Functions', 'explodeByTab'], Functions::file('vars/foren.var')) as $curForum)
                    {
                        $curStats = $this->deleteTopics($curForum[0], $topicAge);
                        $stats[0] += $curStats[0];
                        $stats[1] += $curStats[1];
                        $stats[2] += $curStats[2];
                    }
                else
                    //Just this single forum
                    $stats = $this->deleteTopics($deleteFromForumID, $topicAge);
                Logger::getInstance()->log('%s deleted ' . $stats[0] . ' old topics (target: ' . $deleteFromForumID . ')', LOG_ACP_ACTION);
                Template::getInstance()->printMessage('old_topics_deleted', $stats[0], $stats[1], $stats[2]/1024);
            }
        }
        //Build forum list to choose from
        $forums = [];
        foreach(array_map(['Functions', 'explodeByTab'], Functions::file('vars/foren.var')) as $curForum)
            $forums[] = ['forumID' => $curForum[0],
                'forumName' => $curForum[1],
                'catID' => $curForum[5]];
        Template::getInstance()->printPage('AdminDeleteOld', ['errors' => $this->errors,
            'topicAge' => $topicAge,
            'deleteFromForumID' => $deleteFromForumID,
            'cats' => array_map(['Functions', 'explodeByTab'], Functions::file('vars/kg.var')),
            'forums' => $forums]);
    }
}
?>