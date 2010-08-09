<?php
/**
 * Manages normal and admin smilies plus topic icons (= topic smilies).
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class AdminSmiley implements Module
{
	/**
	 * Detected errors during smiley actions.
	 *
	 * @var array Error messages
	 */
	private $errors = array();

	/**
	 * Contains mode to execute.
	 *
	 * @var string Smiley mode
	 */
	private $mode;

	/**
	 * Translates a mode to its template file.
	 *
	 * @var array Mode and template counterparts
	 */
	private static $modeTable = array('ad_smilies' => 'AdminSmiley',
		//New Smilies
		'new' => 'AdminSmileyNewSmiley',
		'newt' => 'AdminSmileyNewSmiley',
		'newa' => 'AdminSmileyNewSmiley',
		//Edit smilies
		'edit' => 'AdminSmileyEditSmiley',
		'editt' => 'AdminSmileyEditSmiley',
		'edita' => 'AdminSmileyEditSmiley');

	/**
	 * ID of current smiley.
	 *
	 * @var int Smiley ID
	 */
	private $smileyID;

	/**
	 * Current handled type of smiley, depending on the mode.
	 *
	 * @var int Smiley type constant
	 */
	private $smileyType = SMILEY_SMILEY;

	/**
	 * Existing smilies.
	 *
	 * @var array All available smilies
	 */
	private $smilies;

	/**
	 * Existing topic smilies.
	 *
	 * @var array All available topic smilies
	 */
	private $tSmilies;

	/**
	 * Existing admin smilies.
	 *
	 * @var array All available admin smilies
	 */
	private $aSmilies;

	/**
	 * Sets mode, current smiley type, smiley id and loads all smilies.
	 * 
	 * @param string $mode Mode
	 * @return AdminSmiley New instance of this class
	 */
	function __construct($mode)
	{
		$this->mode = $mode;
		$this->smileyID = intval(Functions::getValueFromGlobals('id'));
		//Load all smilies
		$this->smilies = array_map(array('Functions', 'explodeByTab'), Functions::file('vars/smilies.var'));
		$this->tSmilies = array_map(array('Functions', 'explodeByTab'), Functions::file('vars/tsmilies.var'));
		$this->aSmilies = Functions::file_exists('vars/adminsmilies.var') ? array_map(array('Functions', 'explodeByTab'), Functions::file('vars/adminsmilies.var')) : array();
		//Detect type of smiley mode
		preg_match('/(new|edit|kill|moveup|movedown)([at])?/si', $this->mode, $mode);
		if(isset($mode[2]))
			switch($mode[2])
			{
				case 't':
				$this->smileyType = SMILEY_TOPIC;
				break;

				case 'a':
				$this->smileyType = SMILEY_ADMIN;
				break;
			}
	}

	/**
	 * Executes mode.
	 */
	public function execute()
	{
		Functions::accessAdminPanel();
		Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('manages_smilies_post_icons'), INDEXFILE . '?faction=ad_smilies' . SID_AMPER);
		//Modes in this module are grouped by function and then being switched by previous detected type
		switch($this->mode)
		{
//AdminSmileyNewSmiley
			case 'new':
			case 'newt':
			case 'newa':
			$newAddress = htmlspecialchars(Functions::getValueFromGlobals('smadress'));
			$newSynonym = htmlspecialchars(trim(Functions::getValueFromGlobals('synonym')));
			switch($this->smileyType)
			{
				case SMILEY_SMILEY:
				Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('add_new_smiley'), INDEXFILE . '?faction=ad_smilies&amp;mode=new' . SID_AMPER);
				if(Functions::getValueFromGlobals('save') == 'yes')
				{
					if(empty($newAddress))
						$this->errors[] = Main::getModule('Language')->getString('please_enter_an_address');
					if(empty($newSynonym))
						$this->errors[] = Main::getModule('Language')->getString('please_enter_a_synonym');
					if(empty($this->errors))
					{
						Functions::file_put_contents('vars/smiliess.var', $this->smileyID = Functions::file_get_contents('vars/smiliess.var')+1);
						Functions::file_put_contents('vars/smilies.var', $this->smileyID . "\t" . $newSynonym . "\t" . $newAddress . "\t\n", FILE_APPEND);
						Main::getModule('Logger')->log('%s added new smiley (ID: ' . $this->smileyID . ')', LOG_ACP_ACTION);
						header('Location: ' . INDEXFILE . '?faction=ad_smilies' . SID_AMPER_RAW);
						Main::getModule('Template')->printMessage('smiley_added');
					}
				}
				break;

				case SMILEY_TOPIC:
				Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('add_new_post_icon'), INDEXFILE . '?faction=ad_smilies&amp;mode=newt' . SID_AMPER);
				if(Functions::getValueFromGlobals('save') == 'yes')
				{
					if(empty($newAddress))
						$this->errors[] = Main::getModule('Language')->getString('please_enter_an_address');
					else
					{
						Functions::file_put_contents('vars/tsmiliess.var', $this->smileyID = Functions::file_get_contents('vars/tsmiliess.var')+1);
						Functions::file_put_contents('vars/tsmilies.var', $this->smileyID . "\t" . $newAddress . "\t\n", FILE_APPEND);
						Main::getModule('Logger')->log('%s added new topic smiley (ID: ' . $this->smileyID . ')', LOG_ACP_ACTION);
						header('Location: ' . INDEXFILE . '?faction=ad_smilies' . SID_AMPER_RAW);
						Main::getModule('Template')->printMessage('smiley_added');
					}
				}
				break;

				case SMILEY_ADMIN:
				Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('add_new_asmiley'), INDEXFILE . '?faction=ad_smilies&amp;mode=newa' . SID_AMPER);
				if(Functions::getValueFromGlobals('save') == 'yes')
				{
					if(empty($newAddress))
						$this->errors[] = Main::getModule('Language')->getString('please_enter_an_address');
					if(empty($newSynonym))
						$this->errors[] = Main::getModule('Language')->getString('please_enter_a_synonym');
					if(empty($this->errors))
					{
						Functions::file_put_contents('vars/adminsmiliess.var', $this->smileyID = Functions::file_exists('vars/adminsmiliess.var') ? Functions::file_get_contents('vars/adminsmiliess.var')+1 : 1);
						Functions::file_put_contents('vars/adminsmilies.var', $this->smileyID . "\t" . $newSynonym . "\t" . $newAddress . "\t\n", FILE_APPEND);
						Main::getModule('Logger')->log('%s added new admin smiley (ID: ' . $this->smileyID . ')', LOG_ACP_ACTION);
						header('Location: ' . INDEXFILE . '?faction=ad_smilies' . SID_AMPER_RAW);
						Main::getModule('Template')->printMessage('smiley_added');
					}
				}
				else
					$newSynonym = '##mod_ad_sm::';
				break;
			}
			Main::getModule('Template')->assign(array('newAddress' => $newAddress,
				'newSynonym' => $newSynonym,
				'smileyType' => $this->smileyType));
			break;

//AdminSmileyEditSmiley
			case 'edit':
			case 'editt':
			case 'edita':
			$editAddress = htmlspecialchars(Functions::getValueFromGlobals('picadress'));
			$editSynonym = htmlspecialchars(trim(Functions::getValueFromGlobals('synonym')));
			switch($this->smileyType)
			{
				case SMILEY_SMILEY:
				Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('edit_smiley'), INDEXFILE . '?faction=ad_smilies&amp;mode=edit&amp;id=' . $this->smileyID . SID_AMPER);
				if(($key = array_search($this->smileyID, array_map('current', $this->smilies))) === false)
					Main::getModule('Template')->printMessage('smiley_not_found');
				if(Functions::getValueFromGlobals('save') == 'yes')
				{
					if(empty($editAddress))
						$this->errors[] = Main::getModule('Language')->getString('please_enter_an_address');
					if(empty($editSynonym))
						$this->errors[] = Main::getModule('Language')->getString('please_enter_a_synonym');
					if(empty($this->errors))
					{
						$this->smilies[$key][1] = $editSynonym;
						$this->smilies[$key][2] = $editAddress;
						Functions::file_put_contents('vars/smilies.var', implode("\n", array_map(array('Functions', 'implodeByTab'), $this->smilies)) . "\n");
						Main::getModule('Logger')->log('%s edited smiley (ID: ' . $this->smileyID . ')', LOG_ACP_ACTION);
						header('Location: ' . INDEXFILE . '?faction=ad_smilies' . SID_AMPER_RAW);
						Main::getModule('Template')->printMessage('smiley_edited');
					}
				}
				else
				{
					$editAddress = $this->smilies[$key][2];
					$editSynonym = $this->smilies[$key][1];
				}
				break;

				case SMILEY_TOPIC:
				Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('edit_post_icon'), INDEXFILE . '?faction=ad_smilies&amp;mode=editt&amp;id=' . $this->smileyID . SID_AMPER);
				if(($key = array_search($this->smileyID, array_map('current', $this->tSmilies))) === false)
					Main::getModule('Template')->printMessage('smiley_not_found');
				if(Functions::getValueFromGlobals('save') == 'yes')
				{
					if(empty($editAddress))
						$this->errors[] = Main::getModule('Language')->getString('please_enter_an_address');
					else
					{
						$this->tSmilies[$key][1] = $editAddress;
						Functions::file_put_contents('vars/tsmilies.var', implode("\n", array_map(array('Functions', 'implodeByTab'), $this->tSmilies)) . "\n");
						Main::getModule('Logger')->log('%s edited post icon (ID: ' . $this->smileyID . ')', LOG_ACP_ACTION);
						header('Location: ' . INDEXFILE . '?faction=ad_smilies' . SID_AMPER_RAW);
						Main::getModule('Template')->printMessage('smiley_edited');
					}
				}
				else
					$editAddress = $this->tSmilies[$key][1];
				break;

				case SMILEY_ADMIN:
				Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('edit_asmiley'), INDEXFILE . '?faction=ad_smilies&amp;mode=edita&amp;id=' . $this->smileyID . SID_AMPER);
				if(($key = array_search($this->smileyID, array_map('current', $this->aSmilies))) === false)
					Main::getModule('Template')->printMessage('smiley_not_found');
				if(Functions::getValueFromGlobals('save') == 'yes')
				{
					if(empty($editAddress))
						$this->errors[] = Main::getModule('Language')->getString('please_enter_an_address');
					if(empty($editSynonym))
						$this->errors[] = Main::getModule('Language')->getString('please_enter_a_synonym');
					if(empty($this->errors))
					{
						$this->aSmilies[$key][1] = $editSynonym;
						$this->aSmilies[$key][2] = $editAddress;
						Functions::file_put_contents('vars/adminsmilies.var', implode("\n", array_map(array('Functions', 'implodeByTab'), $this->aSmilies)) . "\n");
						Main::getModule('Logger')->log('%s edited admin smiley (ID: ' . $this->smileyID . ')', LOG_ACP_ACTION);
						header('Location: ' . INDEXFILE . '?faction=ad_smilies' . SID_AMPER_RAW);
						Main::getModule('Template')->printMessage('smiley_edited');
					}
				}
				else
				{
					$editAddress = $this->aSmilies[$key][2];
					$editSynonym = $this->aSmilies[$key][1];
				}
				break;
			}
			Main::getModule('Template')->assign(array('smileyID' => $this->smileyID,
				'editAddress' => $editAddress,
				'editSynonym' => $editSynonym,
				'smileyType' => $this->smileyType));
			break;

			case 'kill':
			case 'killt':
			case 'killa':
			switch($this->smileyType)
			{
				case SMILEY_SMILEY:
				Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('delete_smiley'), INDEXFILE . '?faction=ad_smilies&amp;mode=kill&amp;id=' . $this->smileyID . SID_AMPER);
				if(($key = array_search($this->smileyID, array_map('current', $this->smilies))) === false)
					Main::getModule('Template')->printMessage('smiley_not_found');
				unset($this->smilies[$key]);
				Functions::file_put_contents('vars/smilies.var', empty($this->smilies) ? '' : implode("\n", array_map(array('Functions', 'implodeByTab'), $this->smilies)) . "\n");
				Main::getModule('Logger')->log('%s deleted smiley (ID: ' . $this->smileyID . ')', LOG_ACP_ACTION);
				header('Location: ' . INDEXFILE . '?faction=ad_smilies' . SID_AMPER_RAW);
				Main::getModule('Template')->printMessage('smiley_deleted');
				break;

				case SMILEY_TOPIC:
				Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('delete_post_icon'), INDEXFILE . '?faction=ad_smilies&amp;mode=killt&amp;id=' . $this->smileyID . SID_AMPER);
				if(($key = array_search($this->smileyID, array_map('current', $this->tSmilies))) === false)
					Main::getModule('Template')->printMessage('smiley_not_found');
				unset($this->tSmilies[$key]);
				Functions::file_put_contents('vars/tsmilies.var', empty($this->tSmilies) ? '' : implode("\n", array_map(array('Functions', 'implodeByTab'), $this->tSmilies)) . "\n");
				Main::getModule('Logger')->log('%s deleted post icon (ID: ' . $this->smileyID . ')', LOG_ACP_ACTION);
				header('Location: ' . INDEXFILE . '?faction=ad_smilies' . SID_AMPER_RAW);
				Main::getModule('Template')->printMessage('smiley_deleted');
				break;

				case SMILEY_ADMIN:
				Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('delete_asmiley'), INDEXFILE . '?faction=ad_smilies&amp;mode=killa&amp;id=' . $this->smileyID . SID_AMPER);
				if(($key = array_search($this->smileyID, array_map('current', $this->aSmilies))) === false)
					Main::getModule('Template')->printMessage('smiley_not_found');
				unset($this->aSmilies[$key]);
				Functions::file_put_contents('vars/adminsmilies.var', empty($this->aSmilies) ? '' : implode("\n", array_map(array('Functions', 'implodeByTab'), $this->aSmilies)) . "\n");
				Main::getModule('Logger')->log('%s deleted admin smiley (ID: ' . $this->smileyID . ')', LOG_ACP_ACTION);
				header('Location: ' . INDEXFILE . '?faction=ad_smilies' . SID_AMPER_RAW);
				Main::getModule('Template')->printMessage('smiley_deleted');
				break;
			}
			break;

			case 'moveup':
			case 'moveupt':
			case 'moveupa':
			switch($this->smileyType)
			{
				case SMILEY_SMILEY:
				if(($key = array_search($this->smileyID, array_map('current', $this->smilies))) === false)
					Main::getModule('Template')->printMessage('smiley_not_found');
				if($key != 0)
				{
					list($this->smilies[$key], $this->smilies[$key-1]) = array($this->smilies[$key-1], $this->smilies[$key]);
					Functions::file_put_contents('vars/smilies.var', implode("\n", array_map(array('Functions', 'implodeByTab'), $this->smilies)) . "\n");
				}
				break;

				case SMILEY_TOPIC:
				if(($key = array_search($this->smileyID, array_map('current', $this->tSmilies))) === false)
					Main::getModule('Template')->printMessage('smiley_not_found');
				if($key != 0)
				{
					list($this->tSmilies[$key], $this->tSmilies[$key-1]) = array($this->tSmilies[$key-1], $this->tSmilies[$key]);
					Functions::file_put_contents('vars/tsmilies.var', implode("\n", array_map(array('Functions', 'implodeByTab'), $this->tSmilies)) . "\n");
				}
				break;

				case SMILEY_ADMIN:
				if(($key = array_search($this->smileyID, array_map('current', $this->aSmilies))) === false)
					Main::getModule('Template')->printMessage('smiley_not_found');
				if($key != 0)
				{
					list($this->aSmilies[$key], $this->aSmilies[$key-1]) = array($this->aSmilies[$key-1], $this->aSmilies[$key]);
					Functions::file_put_contents('vars/adminsmilies.var', implode("\n", array_map(array('Functions', 'implodeByTab'), $this->aSmilies)) . "\n");
				}
				break;
			}
			header('Location: ' . INDEXFILE . '?faction=ad_smilies' . SID_AMPER_RAW);
			Main::getModule('Template')->printMessage('smiley_moved');
			break;

			case 'movedown':
			case 'movedownt':
			case 'movedowna':
			switch($this->smileyType)
			{
				case SMILEY_SMILEY:
				if(($key = array_search($this->smileyID, array_map('current', $this->smilies))) === false)
					Main::getModule('Template')->printMessage('smiley_not_found');
				if($key != count($this->smilies)-1)
				{
					list($this->smilies[$key], $this->smilies[$key+1]) = array($this->smilies[$key+1], $this->smilies[$key]);
					Functions::file_put_contents('vars/smilies.var', implode("\n", array_map(array('Functions', 'implodeByTab'), $this->smilies)) . "\n");
				}
				break;

				case SMILEY_TOPIC:
				if(($key = array_search($this->smileyID, array_map('current', $this->tSmilies))) === false)
					Main::getModule('Template')->printMessage('smiley_not_found');
				if($key != count($this->tSmilies)-1)
				{
					list($this->tSmilies[$key], $this->tSmilies[$key+1]) = array($this->tSmilies[$key+1], $this->tSmilies[$key]);
					Functions::file_put_contents('vars/tsmilies.var', implode("\n", array_map(array('Functions', 'implodeByTab'), $this->tSmilies)) . "\n");
				}
				break;

				case SMILEY_ADMIN:
				if(($key = array_search($this->smileyID, array_map('current', $this->aSmilies))) === false)
					Main::getModule('Template')->printMessage('smiley_not_found');
				if($key != count($this->aSmilies)-1)
				{
					list($this->aSmilies[$key], $this->aSmilies[$key+1]) = array($this->aSmilies[$key+1], $this->aSmilies[$key]);
					Functions::file_put_contents('vars/adminsmilies.var', implode("\n", array_map(array('Functions', 'implodeByTab'), $this->aSmilies)) . "\n");
				}
				break;
			}
			header('Location: ' . INDEXFILE . '?faction=ad_smilies' . SID_AMPER_RAW);
			Main::getModule('Template')->printMessage('smiley_moved');
			break;

//AdminSmiley
			default:
			Main::getModule('Template')->assign(array('smilies' => $this->smilies,
				'tSmilies' => $this->tSmilies,
				'aSmilies' => $this->aSmilies));
			break;
		}
		Main::getModule('Template')->printPage(self::$modeTable[$this->mode], array('errors' => $this->errors));
	}
}
?>