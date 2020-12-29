<?php
/**
 * Serves help pages.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2019 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.7
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
	private static $pageTable = array('' => 'FAQ', 'faq' => 'FAQ', 'regeln' => 'BoardRules', 'gdpr' => 'GDPR');

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
					//Filter out answers with special conditions
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

			case 'gdpr':
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('privacy_policy'), INDEXFILE . '?faction=gdpr' . SID_AMPER);
			//Dynamically build the GDPR text based on the enabled functions
			$gdprParagraphs = array();
			$curConfigKey;
			foreach(Main::getModule('Language')->getStrings() as $curIndex => $curString)
				if(Functions::strpos($curIndex, 'gdpr_') !== false)
				{
					switch($curIndex)
					{
						case 'gdpr_registration':
						$curConfigKey = 'activate_registration';
						break;

						case 'gdpr_contact_possibility':
						$curConfigKey = 'activate_mail';
						break;

						case 'gdpr_topic_subscriptions':
						$curConfigKey = 'notify_new_replies';
						break;

						case 'gdpr_steam':
						$curConfigKey = 'achievements';
						break;

						default:
						$curConfigKey = null;
						break;
					}
					//Exclude any paragraph related to a deactivated function
					if(!empty($curConfigKey) && Main::getModule('Config')->getCfgVal($curConfigKey) != 1)
						continue;
					$gdprParagraphs[] = $curString;
				}
			$gdprText = implode("\n", $gdprParagraphs);
			$gdprText = Functions::str_replace('{BOARDNAME}', Main::getModule('Config')->getCfgVal('forum_name'), $gdprText);
			$gdprText = Functions::str_replace('{EMAIL}', trim(Main::getModule('Template')->fetch('string:{mailto address="' . Main::getModule('Config')->getCfgVal('site_contact') . '" encode="javascript"}'), '.tpl'), $gdprText);
			$gdprText = Functions::str_replace('{WEBSITE}', Main::getModule('Config')->getCfgVal('address_to_forum'), $gdprText);
			$numOfParagraphs = Functions::substr_count($gdprText, '{PARAGRAPH}');
			for($i=1; $i<=$numOfParagraphs; $i++)
			{
				$curParagraphPos = Functions::strpos($gdprText, '{PARAGRAPH}');
				$gdprText = Functions::substr($gdprText, 0, $curParagraphPos) . $i . Functions::substr($gdprText, $curParagraphPos + 11);
			}
			Main::getModule('Template')->assign('gdprText', $gdprText);
			break;
		}
		Main::getModule('Template')->printPage(self::$pageTable[$this->page]);
	}
}
?>