<?php
/**
 * Manages censorships.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class AdminCensor implements Module
{
	/**
	 * ID of current censorship.
	 *
	 * @var int Censorship ID
	 */
	private $censorshipID;

	/**
	 * Existing censorships.
	 *
	 * @var array All available censorships
	 */
	private $censorships;

	/**
	 * Detected errors during censor actions.
	 *
	 * @var array Error messages
	 */
	private $errors = array();

	/**
	 * Contains mode to execute.
	 *
	 * @var string Censor mode
	 */
	private $mode;

	/**
	 * Translates a mode to its template file.
	 *
	 * @var array Mode and template counterparts
	 */
	private static $modeTable = array('ad_censor' => 'AdminCensor',
		'new' => 'AdminCensorNewWord',
		'edit' => 'AdminCensorEditWord');

	/**
	 * Sets mode and loads censorships.
	 * 
	 * @param string $mode Censor mode
	 * @return AdminCensor New instance of this class
	 */
	function __construct($mode)
	{
		$this->mode = $mode;
		$this->censorshipID = intval(Functions::getValueFromGlobals('id'));
		$this->censorships = array_map(array('Functions', 'explodeByTab'), Functions::file('vars/cwords.var'));
	}

	/**
	 * Executes mode.
	 */
	public function execute()
	{
		Functions::accessAdminPanel();
		Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('manage_censorships'), INDEXFILE . '?faction=ad_censor' . SID_AMPER);
		switch($this->mode)
		{
//AdminCensorNewWord
			case 'new':
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('add_new_censorship'), INDEXFILE . '?faction=ad_censor&amp;mode=new' . SID_AMPER);
			$newWord = htmlspecialchars(trim(Functions::getValueFromGlobals('word')));
			$newReplacement = htmlspecialchars(trim(Functions::getValueFromGlobals('replacement'))) or $newReplacement = '******';
			if(Functions::getValueFromGlobals('create') == '1')
			{
				if(empty($newWord))
					$this->errors[] = Main::getModule('Language')->getString('please_enter_a_word');
				else
				{
					//Get new ID
					$this->censorshipID = current(end($this->censorships))+1;
					//Add to censorships
					Functions::file_put_contents('vars/cwords.var', $this->censorshipID . "\t" . $newWord . "\t" . $newReplacement . "\t\n", FILE_APPEND);
					//Done
					Main::getModule('Logger')->log('%s added new censorship (ID: ' . $this->censorshipID . ')', LOG_ACP_ACTION);
					header('Location: ' . INDEXFILE . '?faction=ad_censor' . SID_AMPER_RAW);
					Main::getModule('Template')->printMessage('censorship_added');
				}
			}
			Main::getModule('Template')->assign(array('newWord' => $newWord,
				'newReplacement' => $newReplacement));
			break;

//AdminCensorEditWord
			case 'edit';
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('edit_censorship'), INDEXFILE . '?faction=ad_censor&amp;mode=edit&amp;id=' . $this->censorshipID . SID_AMPER);
			if(($key = array_search($this->censorshipID, array_map('current', $this->censorships))) === false)
				Main::getModule('Template')->printMessage('censorship_not_found');
			$editWord = htmlspecialchars(trim(Functions::getValueFromGlobals('word')));
			$editReplacement = htmlspecialchars(trim(Functions::getValueFromGlobals('replacement'))) or $editReplacement = '******';
			if(Functions::getValueFromGlobals('update') == '1')
			{
				if(empty($editWord))
					$this->errors[] = Main::getModule('Language')->getString('please_enter_a_word');
				else
				{
					//Update censorship
					$this->censorships[$key][1] = $editWord;
					$this->censorships[$key][2] = $editReplacement;
					//Save it
					Functions::file_put_contents('vars/cwords.var', implode("\n", array_map(array('Functions', 'implodeByTab'), $this->censorships)) . "\n");
					//Done
					Main::getModule('Logger')->log('%s edited censorship (ID: ' . $this->censorshipID . ')', LOG_ACP_ACTION);
					header('Location: ' . INDEXFILE . '?faction=ad_censor' . SID_AMPER_RAW);
					Main::getModule('Template')->printMessage('censorship_edited');
				}
			}
			else
			{
				$editWord = $this->censorships[$key][1];
				$editReplacement = $this->censorships[$key][2];
			}
			Main::getModule('Template')->assign(array('censorshipID' => $this->censorshipID,
				'editWord' => $editWord,
				'editReplacement' => $editReplacement));
			break;

			case 'kill';
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('delete_censorship'), INDEXFILE . '?faction=ad_censor&amp;mode=kill&amp;id=' . $this->censorshipID . SID_AMPER);
			if(($key = array_search($this->censorshipID, array_map('current', $this->censorships))) === false)
				Main::getModule('Template')->printMessage('censorship_not_found');
			//Delete it
			unset($this->censorships[$key]);
			Functions::file_put_contents('vars/cwords.var', empty($this->censorships) ? '' : implode("\n", array_map(array('Functions', 'implodeByTab'), $this->censorships)) . "\n");
			//Done
			Main::getModule('Logger')->log('%s deleted censorship (ID: ' . $this->censorshipID . ')', LOG_ACP_ACTION);
			header('Location: ' . INDEXFILE . '?faction=ad_censor' . SID_AMPER_RAW);
			Main::getModule('Template')->printMessage('censorship_deleted');
			break;

//AdminCensor
			default:
			Main::getModule('Template')->assign('censorships', $this->censorships);
			break;
		}
		Main::getModule('Template')->printPage(self::$modeTable[$this->mode], array('errors' => $this->errors));
	}
}
?>