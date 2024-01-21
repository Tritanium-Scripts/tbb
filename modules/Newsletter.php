<?php
/**
 * Manages archived newsletters.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2023 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class Newsletter extends PublicModule
{
    use Singleton, Mode;

    /**
     * Translates a mode to its template file.
     *
     * @var array Mode and template counterparts
     */
    private static array $modeTable = ['newsletter' => 'Newsletter',
        'read' => 'NewsletterReadLetter'];

    /**
     * Archived newsletter.
     *
     * @var array All available newsletter
     */
    private array $newsletter;

    /**
     * ID (=date) of current newsletter.
     *
     * @var int Newsletter ID
     */
    private int $newsletterID;

    /**
     * Sets mode, ID and loads available newsletter.
     *
     * @param string $mode Mode
     */
    function __construct(string $mode)
    {
        parent::__construct();
        $this->mode = $mode;
        $this->newsletterID = intval(Functions::getValueFromGlobals('newsletter'));
        $this->newsletter = @Functions::file('vars/newsletter.var') ?: [];
        if(!empty($this->newsletter))
            $this->newsletter = array_map(['Functions', 'explodeByTab'], $this->newsletter);
    }

    /**
     * Executes mode.
     */
    public function publicCall(): void
    {
        NavBar::getInstance()->addElement(Language::getInstance()->getString('newsletter_archive'), INDEXFILE . '?faction=newsletter' . SID_AMPER);
        if(!Auth::getInstance()->isLoggedIn())
            Template::getInstance()->printMessage('login_only', INDEXFILE . '?faction=register' . SID_AMPER, INDEXFILE . '?faction=login' . SID_AMPER);
        switch($this->mode)
        {
//NewsletterReadLetter
            case 'read':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('read_newsletter'), INDEXFILE . '?faction=newsletter&amp;mode=read&amp;newsletter=' . $this->newsletterID . SID_AMPER);
            if(($key = array_search($this->newsletterID, array_map('current', $this->newsletter))) === false)
                Template::getInstance()->printMessage('newsletter_not_found');
            if(!isset($this->newsletter[$key][4]) || empty($this->newsletter[$key][4]) || $this->newsletter[$key][4] == '1' || Auth::getInstance()->isAdmin() || ($this->newsletter[$key][4] == '2' && Auth::getInstance()->isMod()))
                Template::getInstance()->assign(['date' => Functions::formatDate($this->newsletter[$key][0]),
                    'author' => Functions::getProfileLink($this->newsletter[$key][1], true),
                    'subject' => $this->newsletter[$key][2],
                    'message' => $this->newsletter[$key][3]]);
            else
                Template::getInstance()->printMessage('permission_denied');
            break;

            case 'delete':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('delete_newsletter'), INDEXFILE . '?faction=newsletter&amp;mode=delete' . SID_AMPER);
            if(!Auth::getInstance()->isAdmin())
                Template::getInstance()->printMessage('permission_denied');
            $toDelete = Functions::getValueFromGlobals('deleteletter') ?: [];
            foreach($this->newsletter as $curKey => $curNewsletter)
                if(in_array($curNewsletter[0], $toDelete))
                    unset($this->newsletter[$curKey]);
            Functions::file_put_contents('vars/newsletter.var', empty($this->newsletter) ? '' : implode("\n", array_map(['Functions', 'implodeByTab'], $this->newsletter)) . "\n");
            //Done
            Logger::getInstance()->log('%s deleted ' . count($toDelete) . ' newsletter', Logger::LOG_ACP_ACTION);
            header('Location: ' . INDEXFILE . '?faction=newsletter' . SID_AMPER_RAW);
            Template::getInstance()->printMessage('newsletter_deleted');
            break;

//Newsletter
            default:
            $newsletter = [];
            foreach(array_reverse($this->newsletter) as $curNewsletter)
                //Check permissions
                if(!isset($curNewsletter[4]) || empty($curNewsletter[4]) || $curNewsletter[4] == '1' || Auth::getInstance()->isAdmin() || ($curNewsletter[4] == '2' && Auth::getInstance()->isMod()))
                    $newsletter[] = ['id' => $curNewsletter[0],
                        'date' => Functions::formatDate($curNewsletter[0]),
                        'author' => Functions::getProfileLink($curNewsletter[1], true),
                        'subject' => $curNewsletter[2]];
            Template::getInstance()->assign('newsletter', $newsletter);
            break;
        }
        Template::getInstance()->printPage(Functions::handleMode($this->mode, self::$modeTable, __CLASS__, 'newsletter'), null, null, ',' . $this->newsletterID);
    }
}
?>