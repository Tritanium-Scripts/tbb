<?php
/**
 * Manages user ranking.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2023 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class AdminRank extends PublicModule
{
    use Singleton, Mode, Errors;

    /**
     * Translates a mode to its template file.
     *
     * @var array Mode and template counterparts
     */
    private static array $modeTable = ['ad_rank' => 'AdminRankIndex',
        'edit' => 'AdminRankEditRank',
        'new' => 'AdminRankNewRank'];

    /**
     * Contains new or edited rank name.
     *
     * @var string Name of current rank
     */
    private string $rankName;

    /**
     * All available ranks.
     *
     * @var array User ranking
     */
    private array $ranks;

    /**
     * Needed number of posts for current rank.
     *
     * @var int Required amount of posts.
     */
    private string $requiredPosts;

    /**
     * Number of stars for current rank.
     *
     * @var int Number of stars
     */
    private string $stars;

    /**
     * Sets mode and loads user rank(s).
     *
     * @param string $mode Mode to execute
     */
    function __construct(string $mode)
    {
        parent::__construct();
        $this->mode = $mode;
        $this->ranks = Functions::getRanks();
        //Get data for new or edited rank
        $this->rankName = htmlspecialchars(Functions::getValueFromGlobals('bez'));
        $this->requiredPosts = Functions::getValueFromGlobals('minposts');
        $this->stars = Functions::getValueFromGlobals('pic');
    }

    /**
     * Executes rank operation.
     */
    public function publicCall(): void
    {
        Functions::accessAdminPanel();
        NavBar::getInstance()->addElement(Language::getInstance()->getString('manage_ranks'), INDEXFILE . '?faction=ad_rank' . SID_AMPER);
        switch($this->mode)
        {
//AdminRankEditRank
            case 'edit':
            $rankID = intval(Functions::getValueFromGlobals('id'));
            NavBar::getInstance()->addElement(Language::getInstance()->getString('edit_rank'), INDEXFILE . '?faction=ad_rank&amp;mode=edit&amp;id=' . $rankID . SID_AMPER);
            if(Functions::getValueFromGlobals('save') == 'yes')
            {
                if(empty($this->rankName))
                    $this->errors[] = Language::getInstance()->getString('please_enter_a_rank_name');
                if(($this->requiredPosts = intval($this->requiredPosts)) < 0)
                    $this->errors[] = Language::getInstance()->getString('please_enter_valid_number_of_required_posts');
                if(($this->stars = intval($this->stars)) < 1)
                    $this->errors[] = Language::getInstance()->getString('please_enter_valid_number_of_stars');
                if(empty($this->errors))
                    foreach($this->ranks as &$curRank)
                        if($curRank[0] == $rankID)
                        {
                            //Edit rank
                            $curRank = [$curRank[0], $this->rankName, $this->requiredPosts, PHP_INT_MAX, $this->stars, ''];
                            //Refresh sort order and save
                            $this->sortAndSaveRanks();
                            //Done
                            Logger::getInstance()->log('%s edited rank (ID: ' . $rankID . ')', Logger::LOG_ACP_ACTION);
                            header('Location: ' . INDEXFILE . '?faction=ad_rank' . SID_AMPER_RAW);
                            Template::getInstance()->printMessage('rank_edited');
                            break;
                        }
                $found = false;
            }
            else
                //Look up rank to edit
                foreach($this->ranks as &$curRank)
                    if($curRank[0] == $rankID)
                    {
                        list(,$this->rankName,$this->requiredPosts,,$this->stars) = $curRank;
                        $found = true;
                        break;
                    }
            if(!isset($found))
                //This will also catch an invalid rank ID in save mode
                Template::getInstance()->printMessage('rank_not_found');
            Template::getInstance()->assign(['rankID' => $rankID,
                'rankName' => $this->rankName,
                'requiredPosts' => $this->requiredPosts,
                'stars' => $this->stars,
                'errors' => $this->errors]);
            break;

//AdminRankNewRank
            case 'new':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('add_new_rank'), INDEXFILE . '?faction=ad_rank&amp;mode=new' . SID_AMPER);
            if(Functions::getValueFromGlobals('save') == 'yes')
            {
                if(empty($this->rankName))
                    $this->errors[] = Language::getInstance()->getString('please_enter_a_rank_name');
                if(($this->requiredPosts = intval($this->requiredPosts)) < 0)
                    $this->errors[] = Language::getInstance()->getString('please_enter_valid_number_of_required_posts');
                if(($this->stars = intval($this->stars)) < 1)
                    $this->errors[] = Language::getInstance()->getString('please_enter_valid_number_of_stars');
                if(empty($this->errors))
                {
                    //Neue ID bestimmen (wow, German! :D )
                    Functions::file_put_contents('vars/ranks.var', $newRankID = Functions::file_get_contents('vars/ranks.var')+1);
                    //Add new rank
                    $this->ranks[] = [$newRankID, $this->rankName, $this->requiredPosts, PHP_INT_MAX, $this->stars, ''];
                    $this->sortAndSaveRanks();
                    //Done
                    Logger::getInstance()->log('%s created new rank (ID: ' . $newRankID . ')', Logger::LOG_ACP_ACTION);
                    header('Location: ' . INDEXFILE . '?faction=ad_rank' . SID_AMPER_RAW);
                    Template::getInstance()->printMessage('new_rank_added');
                }
            }
            Template::getInstance()->assign(['rankName' => $this->rankName,
                'requiredPosts' => $this->requiredPosts,
                'stars' => $this->stars,
                'errors' => $this->errors]);
            break;

            case 'kill':
            $rankID = intval(Functions::getValueFromGlobals('id'));
            foreach($this->ranks as $curKey => $curRank)
                if($curRank[0] == $rankID)
                {
                    unset($this->ranks[$curKey]);
                    //At least one rank is required
                    if(empty($this->ranks))
                        Template::getInstance()->printMessage('one_rank_required');
                    $this->sortAndSaveRanks();
                    Logger::getInstance()->log('%s deleted rank (ID: ' . $rankID . ')', Logger::LOG_ACP_ACTION);
                    header('Location: ' . INDEXFILE . '?faction=ad_rank' . SID_AMPER_RAW);
                    Template::getInstance()->printMessage('rank_deleted');
                    break;
                }
            Template::getInstance()->printMessage('rank_not_found');
            break;

//AdminRankIndex
            default:
            Template::getInstance()->assign('ranks', $this->ranks);
            break;
        }
        Template::getInstance()->printPage(self::$modeTable[$this->mode]);
    }

    /**
     * Sorts by required posts, recalculates max posts and saves all ranks to rank var file.
     */
    private function sortAndSaveRanks(): void
    {
        //Sort
        usort($this->ranks, function($rank1, $rank2)
        {
            return strnatcasecmp($rank1[2], $rank2[2]);
        });
        //Recalculate
        $size = count($this->ranks);
        for($i=1; $i<$size; $i++)
            //Max posts of prior rank are min posts of current rank minus one (value of last rank doesn't matter)
            $this->ranks[$i-1][3] = $this->ranks[$i][2]-1;
        //Save
        Functions::file_put_contents('vars/rank.var', implode("\n", array_map(['Functions', 'implodeByTab'], $this->ranks)));
    }
}
?>