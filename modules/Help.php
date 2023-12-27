<?php
/**
 * Serves help pages.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2023 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class Help extends PublicModule
{
    use Singleton;

    /**
     * Page to display.
     *
     * @var string Current help page
     */
    private string $page;

    /**
     * Translates a page to its template file.
     *
     * @var array Page and template counterparts
     */
    private static array $pageTable = array('' => 'FAQ', 'faq' => 'FAQ', 'regeln' => 'BoardRules', 'gdpr' => 'GDPR');

    /**
     * Sets help page to display.
     *
     * @param string $page Help page
     * @return Help New instance of this class
     */
    function __construct(string $page)
    {
        parent::__construct();
        $this->page = $page;
    }

    /**
     * Prepares help page and displays it.
     */
    public function publicCall(): void
    {
        switch($this->page)
        {
            case 'faq':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('faq'), INDEXFILE . '?faction=faq' . SID_AMPER);
            //Prepare FAQ questions and answers
            $faqQuestions = $faqAnswers = [];
            foreach(Language::getInstance()->getStrings() as $curIndex => $curString)
                //Filter out questions
                if(Functions::strpos($curIndex, 'faq_question_') !== false)
                    $faqQuestions[] = $curString;
                else
                    //Filter out answers with special conditions
                    switch($curIndex)
                    {
                        case 'faq_answer_what_are_smilies':
                        $faqAnswers[] = Functions::str_replace('{SMILIES}', Template::getInstance()->fetch('FAQSmileyTable', 'smilies', BBCode::getInstance()->getSmilies()), $curString);
                        break;

                        case 'faq_answer_what_is_bbcode':
                        $faqAnswers[] = BBCode::getInstance()->parse($curString);
                        break;

                        case 'faq_answer_what_ranks_exist':
                        $faqAnswers[] = Functions::str_replace('{RANKS}', Template::getInstance()->fetch('FAQRankTable', 'ranks', Functions::getRanks()), $curString);
                        break;

                        //Filter out normal answers
                        default:
                        if(Functions::strpos($curIndex, 'faq_answer_') !== false)
                            $faqAnswers[] = $curString;
                        break;
                    }
            //Assign FAQ entries
            Template::getInstance()->assign(array('faqQuestions' => $faqQuestions,
                'faqAnswers' => $faqAnswers));
            break;

            case 'regeln':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('board_rules'), INDEXFILE . '?faction=regeln' . SID_AMPER);
            break;

            case 'gdpr':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('privacy_policy'), INDEXFILE . '?faction=gdpr' . SID_AMPER);
            //Dynamically build the GDPR text based on the enabled functions
            $gdprParagraphs = [];
            $curConfigKey;
            foreach(Language::getInstance()->getStrings() as $curIndex => $curString)
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
                    if(!empty($curConfigKey) && Config::getInstance()->getCfgVal($curConfigKey) != 1)
                        continue;
                    $gdprParagraphs[] = $curString;
                }
            $gdprText = implode("\n", $gdprParagraphs);
            $gdprText = Functions::str_replace('{BOARDNAME}', Config::getInstance()->getCfgVal('forum_name'), $gdprText);
            $gdprText = Functions::str_replace('{EMAIL}', trim(Template::getInstance()->fetch('string:{mailto address="' . Config::getInstance()->getCfgVal('site_contact') . '" encode="javascript"}'), '.tpl'), $gdprText);
            $gdprText = Functions::str_replace('{WEBSITE}', Config::getInstance()->getCfgVal('address_to_forum'), $gdprText);
            $numOfParagraphs = Functions::substr_count($gdprText, '{PARAGRAPH}');
            for($i=1; $i<=$numOfParagraphs; $i++)
            {
                $curParagraphPos = Functions::strpos($gdprText, '{PARAGRAPH}');
                $gdprText = Functions::substr($gdprText, 0, $curParagraphPos) . $i . Functions::substr($gdprText, $curParagraphPos + 11);
            }
            Template::getInstance()->assign('gdprText', $gdprText);
            break;
        }
        Template::getInstance()->printPage(self::$pageTable[$this->page]);
    }
}
?>