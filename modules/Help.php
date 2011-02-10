<?php
/**
 * Serves help pages.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class Help implements Module
{
	/**
	 * Page to display.
	 *
	 * @var string Current help page
	 */
	private $page;

	/**
	 * Translates a page to its template file.
	 *
	 * @var array Page and template counterparts
	 */
	private static $pageTable = array('' => 'FAQ', 'faq' => 'FAQ', 'regeln' => 'BoardRules');

	/**
	 * Sets help page to display.
	 *
	 * @param string $page Help page
	 * @return Help New instance of this class
	 */
	function __construct($page)
	{
		$this->page = $page;
	}

	/**
	 * Prepares help page and displays it.
	 */
	public function execute()
	{
		switch($this->page)
		{
			case 'faq':
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('faq'), INDEXFILE . '?faction=faq' . SID_AMPER);
			//Prepare FAQ questions and answers
			$faqQuestions = $faqAnswers = array();
			foreach(Main::getModule('Language')->getStrings() as $curIndex => $curString)
				//Filter out questions
				if(Functions::strpos($curIndex, 'faq_question_') !== false)
					$faqQuestions[] = $curString;
				else
					//Filter out answer with special conditions
					switch($curIndex)
					{
						case 'faq_answer_what_are_smilies':
						$faqAnswers[] = Functions::str_replace('{SMILIES}', Main::getModule('Template')->fetch('FAQSmileyTable', 'smilies', Main::getModule('BBCode')->getSmilies()), $curString);
						break;

						case 'faq_answer_what_is_bbcode':
						$faqAnswers[] = Main::getModule('BBCode')->parse($curString);
						break;

						case 'faq_answer_what_ranks_exist':
						$faqAnswers[] = Functions::str_replace('{RANKS}', Main::getModule('Template')->fetch('FAQRankTable', 'ranks', Functions::getRanks()), $curString);
						break;

						//Filter out normal answers
						default:
						if(Functions::strpos($curIndex, 'faq_answer_') !== false)
							$faqAnswers[] = $curString;
						break;
					}
			//Assign FAQ entries
			Main::getModule('Template')->assign(array('faqQuestions' => $faqQuestions,
				'faqAnswers' => $faqAnswers));
			break;

			case 'regeln':
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('board_rules'), INDEXFILE . '?faction=regeln' . SID_AMPER);
			break;
		}
		Main::getModule('Template')->printPage(self::$pageTable[$this->page]);
	}
}
?>