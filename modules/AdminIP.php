<?php
/**
 * Manages blocking of IP addresses.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class AdminIP implements Module
{
	/**
	 * Detected errors during IP block actions.
	 *
	 * @var array Error messages
	 */
	private $errors = array();

	/**
	 * ID of current IP block.
	 *
	 * @var int IP block ID
	 */
	private $ipBlockID;

	/**
	 * Existing IP address blocks.
	 *
	 * @var array Blocked IPs
	 */
	private $ipBlocks;

	/**
	 * Contains mode to execute.
	 *
	 * @var string IP ban mode
	 */
	private $mode;

	/**
	 * Translates a mode to its template file.
	 *
	 * @var array Mode and template counterparts
	 */
	private static $modeTable = array('ad_ip' => 'AdminIP',
		'new' => 'AdminIPNewBlock');

	/**
	 * Sets mode.
	 * 
	 * @param string $mode Mode
	 * @return AdminIP New instance of this class
	 */
	function __construct($mode)
	{
		$this->mode = $mode;
		$this->ipBlockID = intval(Functions::getValueFromGlobals('id'));
		$this->ipBlocks = Functions::getBannedIPs();
	}

	/**
	 * Executes mode.
	 */
	public function execute()
	{
		Functions::accessAdminPanel();
		Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('manage_ip_blocks'), INDEXFILE . '?faction=ad_ip' . SID_AMPER);
		switch($this->mode)
		{
//AdminIPNewBlock
			case 'new':
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('manage_ip_blocks'), INDEXFILE . '?faction=ad_ip&amp;mode=new' . SID_AMPER);
			$newIPAddress = Functions::getValueFromGlobals('ip');
			$newBlockPeriod = intval(Functions::getValueFromGlobals('sperrtime')) or $newBlockPeriod = '';
			$newBlockForumID = intval(Functions::getValueFromGlobals('sperrziel'));
			if(Functions::getValueFromGlobals('create') == 'yes')
			{
				if(empty($newIPAddress))
					$this->errors[] = Main::getModule('Language')->getString('please_enter_an_ip_address');
				if(empty($newBlockPeriod))
					$newBlockPeriod = -1;
				if($newBlockForumID == 0)
					$this->errors[] = Main::getModule('Language')->getString('please_select_a_forum');
				elseif($newBlockForumID != -1 && !Functions::file_exists('foren/' . $newBlockForumID . '-threads.xbb'))
					$this->errors[] = Main::getModule('Language')->getString('text_forum_not_found', 'Messages');
				if(empty($this->errors))
				{
					//$ip = $ip; I lol'd
					list(,,,$this->ipBlockID) = end($this->ipBlocks);
					Functions::file_put_contents('vars/ip.var', $newIPAddress . "\t" . ($newBlockPeriod != -1 ? time()+$newBlockPeriod*60 : $newBlockPeriod) . "\t" . $newBlockForumID . "\t" . ++$this->ipBlockID . "\t\n", FILE_APPEND);
					Main::getModule('Logger')->log('%s added new ip block (' . $newIPAddress . ', ' . $newBlockForumID . ', ' . $newBlockPeriod . ')', LOG_ACP_ACTION);
					header('Location: ' . INDEXFILE . '?faction=ad_ip' . SID_AMPER_RAW);
					Main::getModule('Template')->printMessage('ip_block_added');
				}
			}
			//Build forum list to choose from
			$forums = array();
			foreach(array_map(array('Functions', 'explodeByTab'), Functions::file('vars/foren.var')) as $curForum)
				$forums[] = array('forumID' => $curForum[0],
					'forumName' => $curForum[1],
					'catID' => $curForum[5]);
			Main::getModule('Template')->assign(array('newIPAddress' => $newIPAddress,
				'newBlockPeriod' => $newBlockPeriod,
				'newBlockForumID' => $newBlockForumID,
				'cats' => array_map(array('Functions', 'explodeByTab'), Functions::file('vars/kg.var')),
				'forums' => $forums));
			break;

			case 'kill':
			foreach($this->ipBlocks as $curKey => $curIPBlock)
				if($curIPBlock[3] == $this->ipBlockID)
				{
					unset($this->ipBlocks[$curKey]);
					Functions::file_put_contents('vars/ip.var', empty($this->ipBlocks) ? '' : implode("\n", array_map(array('Functions', 'implodeByTab'), $this->ipBlocks)) . "\n");
					Main::getModule('Logger')->log('%s deleted ip block (ID: ' . $this->ipBlockID . ')', LOG_ACP_ACTION);
					header('Location: ' . INDEXFILE . '?faction=ad_ip' . SID_AMPER_RAW);
					Main::getModule('Template')->printMessage('ip_block_deleted');
				}
			Main::getModule('Template')->printMessage('ip_block_not_found');
			break;

//AdminIP
			default:
			foreach($this->ipBlocks as &$curIPBlock)
			{
				$curIPBlock[1] = $curIPBlock[1] == '-1' ? Main::getModule('Language')->getString('forever_blocked') : ($curIPBlock[1] > time() ? sprintf(Main::getModule('Language')->getString('x_minutes'), round(($curIPBlock[1]-time())/60)) : Main::getModule('Language')->getString('expired'));
				$curIPBlock[2] = $curIPBlock[2] == '-1' ? Main::getModule('Language')->getString('entire_board') : @next(Functions::getForumData($curIPBlock[2]));
			}
			Main::getModule('Template')->assign('ipBlocks', $this->ipBlocks);
			break;
		}
		Main::getModule('Template')->printPage(self::$modeTable[$this->mode], array('errors' => $this->errors));
	}
}
?>