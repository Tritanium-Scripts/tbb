<?php
/**
 * Shows the member list with different ordering and sorting.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2023 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class MemberList extends PublicModule
{
    use Singleton;

    /**
     * Members to display each page.
     *
     * @var int Amount of members per page
     */
    private int $limit;

    /**
     * Contains states inferior to super mod state (6).
     *
     * @var array States inferior to s-mod state
     */
    private static array $lowerThanSMod = ['2', '3', '4', '5'];

    /**
     * Ascending or descending order.
     *
     * @var bool ASC or DESC order type
     */
    private bool $orderType;

    /**
     * Queried page of member list.
     *
     * @var int Current page
     */
    private int $page;

    /**
     * Contains sorting of member list.
     *
     * @var string Sort by method
     */
    private string $sortMethod;

    /**
     * Sets order type, page and sort method.
     */
    public function __construct()
    {
        parent::__construct();
        $this->limit = Config::getInstance()->getCfgVal('members_per_page');
        $this->orderType = Functions::getValueFromGlobals('orderType') == '1';
        $this->page = intval(Functions::getValueFromGlobals('z')) ?: 1;
        $this->sortMethod = Functions::getValueFromGlobals('sortmethod') ?: 'id';
        PlugIns::getInstance()->callHook(PlugIns::HOOK_MEMBER_LIST_INIT);
    }

    /**
     * Sorts and displays the member list.
     */
    public function publicCall(): void
    {
        switch(Config::getInstance()->getCfgVal('activate_mlist'))
        {
            case 2:
            if(!Auth::getInstance()->isLoggedIn())
                Template::getInstance()->printMessage('login_only', INDEXFILE . '?faction=register' . SID_AMPER, INDEXFILE . '?faction=login' . SID_AMPER);

            case 1:
            //All good
            break;

            case 0:
            default:
            Template::getInstance()->printMessage('function_deactivated');
            break;
        }
        $memberFiles = $members = $pageBar = [];
        //Build page navigation bar
        $pages = ceil(($size = count($availMembers = Functions::glob(DATAPATH . 'members/[!0t]*.xbb'))) / $this->limit);
        for($i=1; $i<=$pages; $i++)
            $pageBar[] = $i != $this->page ? '<a href="' . INDEXFILE . '?faction=mlist&amp;sortmethod=' . $this->sortMethod . '&amp;z=' . $i . '&amp;orderType=' . $this->orderType . SID_AMPER . '">' . $i . '</a>' : $i;
        //Only add bar by having more than one page
        NavBar::getInstance()->addElement(Language::getInstance()->getString('member_list'), INDEXFILE . '?faction=mlist' . SID_AMPER, ($pageBar = ' ' . sprintf(Language::getInstance()->getString('pages'), implode(' ', $pageBar))));
        //Apply order type, one of them will be changed depending on current sort method
        $orderTypeID = $orderTypeName = $orderTypeRank = $orderTypePosts = false;
        //Process members
        $lastID = intval(Functions::file_get_contents('vars/last_user_id.var'));
        $end = $this->page*$this->limit;
        //Sorting by IDs is possible based on filenames, so no need to read in all the member files or even sort the natural order of them :)
        if($this->sortMethod == 'id')
        {
            //Extract user IDs only
            $availMembers = array_map(fn($member) => intval(basename($member, '.xbb')), $availMembers);
            //Detect crawling direction
            if($this->orderType)
            {
                //Detect start position, backward direction
                for($i=$lastID, $membersForCurPage=0; $membersForCurPage<$end-$this->limit && $i>0; $i--)
                    if(in_array($i, $availMembers))
                        $membersForCurPage++;
                //From start position read in the previous x members
                for($membersForCurPage=0; $membersForCurPage<$this->limit && $i>0; $i--)
                    if(in_array($i, $availMembers))
                    {
                        $memberFiles[] = Functions::file('members/' . $i . '.xbb');
                        $membersForCurPage++;
                    }
            }
            else
            {
                //Detect start position, forward direction
                for($i=1,$membersForCurPage=0; $membersForCurPage<$end-$this->limit && $i<=$lastID; $i++)
                    if(in_array($i, $availMembers))
                        $membersForCurPage++;
                //From start position read in the next x members
                for($membersForCurPage=0; $membersForCurPage<$this->limit && $i<=$lastID; $i++)
                    if(in_array($i, $availMembers))
                    {
                        $memberFiles[] = Functions::file('members/' . $i . '.xbb');
                        $membersForCurPage++;
                    }
            }
            $orderTypeID = !$this->orderType;
        }
        //Otherwise process all member data for proper sorting page-wide and not only sorting each page in ID mode
//Julian told not to delete the following line, no. 132:
/* Diese Zeile darf nicht gelöscht werden!! Warum weiß ich auch nicht. Hab ich aber grade so beschlossen! */
        else
        {
            $optNull = array_fill(0, $size, null);
            $optFalse = array_fill(0, $size, false);
            $availMembers = array_map(['Functions', 'file'], $availMembers, $optNull, $optNull, $optFalse);
            //Sorting
            switch($this->sortMethod)
            {
                case 'name':
                usort($availMembers, [$this, 'cmpByName']);
                $orderTypeName = !$this->orderType;
                break;

                case 'posts':
                usort($availMembers, [$this, 'cmpByPost']);
                $orderTypePosts = !$this->orderType;
                break;

                case 'status':
                usort($availMembers, [$this, 'cmpByState']);
                $orderTypeRank = !$this->orderType;
                break;
            }
            if($this->orderType)
                $availMembers = array_reverse($availMembers);
            //Now extract the page piece from the complete sorted array
            for($i=$end-$this->limit; $i<($end > $size ? $size : $end); $i++)
                $memberFiles[] = $availMembers[$i];
            unset($availMembers); //Free memory (does this really work?)
        }
        //Prepare data for template
        foreach($memberFiles as &$curMember)
        {
            $curMember[14] = Functions::explodeByComma($curMember[14]);
            $members[] = ['id' => $curMember[1],
                'nick' => Functions::getProfileLink($curMember[1]),
                'rank' => !empty($curMember[17]) ? $curMember[17] : Functions::getStateName($curMember[4], $curMember[5]),
                'posts' => $curMember[5],
                'eMail' => $curMember[14][0] != '1' && $curMember[14][1] != '1' ? false : ($curMember[14][0] != '1' && $curMember[14][1] == '1' ? $curMember[3] : true)];
        }
        PlugIns::getInstance()->callHook(PlugIns::HOOK_MEMBER_LIST_SHOW_MEMBERS, $orderTypeID, $orderTypeName, $orderTypeRank, $orderTypePosts, $pageBar, $members);
        Template::getInstance()->printPage('MemberList', ['orderTypeID' => $orderTypeID,
            'orderTypeName' => $orderTypeName,
            'orderTypeRank' => $orderTypeRank,
            'orderTypePosts' => $orderTypePosts,
            'page' => $this->page,
            'pageBar' => $pageBar,
            'members' => $members]); //Prepared members
    }

    /**
     * Compares two members by their names.
     *
     * @param array $mem1 First member to compare with
     * @param array $mem2 Second member to compare with
     * @return int Comparison result as natural order
     */
    private function cmpByName(array $mem1, array $mem2): int
    {
        return strcasecmp($mem1[0], $mem2[0]);
    }

    /**
     * Compares two members based on their amount of posts.
     *
     * @param array $mem1 First member to compare with
     * @param array $mem2 Second member to compare with
     * @return int Comparison result as natural order
     */
    private function cmpByPost(array $mem1, array $mem2): int
    {
        return $mem1[5] == $mem2[5] ? 0 : ($mem1[5] > $mem2[5] ? -1 : 1);
    }

    /**
     * Compares two members by their states.
     *
     * @param array $mem1 First member to compare with
     * @param array $mem2 Second member to compare with
     * @return int Comparison result as natural order
     */
    private function cmpByState(array $mem1, array $mem2): int
    {
        //In case of same state, sort by posts
        return ($cmp = strcasecmp($mem1[4], $mem2[4])) == 0
            ? $this->cmpByPost($mem1, $mem2)
            : ($mem1[4] == '6' && in_array($mem2[4], self::$lowerThanSMod)
                ? -1 :
                ($mem2[4] == '6' && in_array($mem1[4], self::$lowerThanSMod)
                    ? 1
                    : $cmp));
    }
}
?>