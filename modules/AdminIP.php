<?php
/**
 * Manages blocking of IP addresses.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2023 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class AdminIP extends PublicModule
{
    use Singleton, Mode, Errors;

    /**
     * ID of current IP block.
     *
     * @var int IP block ID
     */
    private int $ipBlockID;

    /**
     * Existing IP address blocks.
     *
     * @var array Blocked IPs
     */
    private array $ipBlocks;

    /**
     * Translates a mode to its template file.
     *
     * @var array Mode and template counterparts
     */
    private static array $modeTable = ['ad_ip' => 'AdminIP',
        'new' => 'AdminIPNewBlock'];

    /**
     * Sets mode.
     *
     * @param string $mode Mode
     * @return AdminIP New instance of this class
     */
    function __construct(string $mode)
    {
        parent::__construct();
        $this->mode = $mode;
        $this->ipBlockID = intval(Functions::getValueFromGlobals('id'));
        $this->ipBlocks = Functions::getBannedIPs();
    }

    /**
     * Executes mode.
     */
    public function publicCall(): void
    {
        Functions::accessAdminPanel();
        NavBar::getInstance()->addElement(Language::getInstance()->getString('manage_ip_blocks'), INDEXFILE . '?faction=ad_ip' . SID_AMPER);
        switch($this->mode)
        {
//AdminIPNewBlock
            case 'new':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('manage_ip_blocks'), INDEXFILE . '?faction=ad_ip&amp;mode=new' . SID_AMPER);
            $newIPAddress = Functions::getValueFromGlobals('ip');
            $newBlockPeriod = intval(Functions::getValueFromGlobals('sperrtime')) ?: '';
            $newBlockForumID = intval(Functions::getValueFromGlobals('sperrziel'));
            if(Functions::getValueFromGlobals('create') == 'yes')
            {
                if(empty($newIPAddress))
                    $this->errors[] = Language::getInstance()->getString('please_enter_an_ip_address');
                if(empty($newBlockPeriod))
                    $newBlockPeriod = -1;
                if($newBlockForumID == 0)
                    $this->errors[] = Language::getInstance()->getString('please_select_a_forum');
                elseif($newBlockForumID != -1 && !Functions::file_exists('foren/' . $newBlockForumID . '-threads.xbb'))
                    $this->errors[] = Language::getInstance()->getString('text_forum_not_found', 'Messages');
                if(empty($this->errors))
                {
                    //$ip = $ip; I lol'd
                    if(!empty($this->ipBlocks))
                        list(,,,$this->ipBlockID) = end($this->ipBlocks);
                    Functions::file_put_contents('vars/ip.var', $newIPAddress . "\t" . ($newBlockPeriod != -1 ? time()+$newBlockPeriod*60 : $newBlockPeriod) . "\t" . $newBlockForumID . "\t" . ++$this->ipBlockID . "\t\n", FILE_APPEND);
                    Logger::getInstance()->log('%s added new ip block (' . $newIPAddress . ', ' . $newBlockForumID . ', ' . $newBlockPeriod . ')', Logger::LOG_ACP_ACTION);
                    header('Location: ' . INDEXFILE . '?faction=ad_ip' . SID_AMPER_RAW);
                    Template::getInstance()->printMessage('ip_block_added');
                }
            }
            //Build forum list to choose from
            $forums = [];
            foreach(array_map(['Functions', 'explodeByTab'], Functions::file('vars/foren.var')) as $curForum)
                $forums[] = ['forumID' => $curForum[0],
                    'forumName' => $curForum[1],
                    'catID' => $curForum[5]];
            Template::getInstance()->assign(['newIPAddress' => $newIPAddress,
                'newBlockPeriod' => $newBlockPeriod,
                'newBlockForumID' => $newBlockForumID,
                'cats' => array_map(['Functions', 'explodeByTab'], Functions::file('vars/kg.var')),
                'forums' => $forums]);
            break;

            case 'kill':
            foreach($this->ipBlocks as $curKey => $curIPBlock)
                if($curIPBlock[3] == $this->ipBlockID)
                {
                    unset($this->ipBlocks[$curKey]);
                    Functions::file_put_contents('vars/ip.var', empty($this->ipBlocks) ? '' : implode("\n", array_map(['Functions', 'implodeByTab'], $this->ipBlocks)) . "\n");
                    Logger::getInstance()->log('%s deleted ip block (ID: ' . $this->ipBlockID . ')', Logger::LOG_ACP_ACTION);
                    header('Location: ' . INDEXFILE . '?faction=ad_ip' . SID_AMPER_RAW);
                    Template::getInstance()->printMessage('ip_block_deleted');
                }
            Template::getInstance()->printMessage('ip_block_not_found');
            break;

//AdminIP
            default:
            foreach($this->ipBlocks as &$curIPBlock)
            {
                $curIPBlock[1] = $curIPBlock[1] == '-1' ? Language::getInstance()->getString('forever_blocked') : ($curIPBlock[1] > time() ? sprintf(Language::getInstance()->getString('x_minutes'), round(($curIPBlock[1]-time())/60)) : Language::getInstance()->getString('expired'));
                $curIPBlock[2] = $curIPBlock[2] == '-1' ? Language::getInstance()->getString('entire_board') : @next(Functions::getForumData($curIPBlock[2]));
            }
            Template::getInstance()->assign('ipBlocks', $this->ipBlocks);
            break;
        }
        Template::getInstance()->printPage(self::$modeTable[$this->mode], ['errors' => $this->errors]);
    }
}
?>