<?php
/**
 * Manages censorships.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2024 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class AdminCensor extends PublicModule
{
    use Singleton, Mode, Errors;

    /**
     * ID of current censorship.
     *
     * @var int Censorship ID
     */
    private int $censorshipID;

    /**
     * Existing censorships.
     *
     * @var array All available censorships
     */
    private array $censorships;

    /**
     * Translates a mode to its template file.
     *
     * @var array Mode and template counterparts
     */
    private static array $modeTable = ['ad_censor' => 'AdminCensor',
        'new' => 'AdminCensorNewWord',
        'edit' => 'AdminCensorEditWord'];

    /**
     * Sets mode and loads censorships.
     *
     * @param string $mode Censor mode
     */
    function __construct(string $mode)
    {
        parent::__construct();
        $this->mode = $mode;
        $this->censorshipID = intval(Functions::getValueFromGlobals('id'));
        $this->censorships = array_map(['Functions', 'explodeByTab'], Functions::file('vars/cwords.var'));
        PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_CENSOR_INIT);
    }

    /**
     * Executes mode.
     */
    public function publicCall(): void
    {
        Functions::accessAdminPanel();
        NavBar::getInstance()->addElement(Language::getInstance()->getString('manage_censorships'), INDEXFILE . '?faction=ad_censor' . SID_AMPER);
        switch($this->mode)
        {
//AdminCensorNewWord
            case 'new':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('add_new_censorship'), INDEXFILE . '?faction=ad_censor&amp;mode=new' . SID_AMPER);
            $newWord = htmlspecialchars(trim(Functions::getValueFromGlobals('word')));
            $newReplacement = htmlspecialchars(trim(Functions::getValueFromGlobals('replacement'))) ?: '******';
            PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_CENSOR_NEW_CENSORSHIP, $newWord, $newReplacement);
            if(Functions::getValueFromGlobals('create') == '1')
            {
                if(empty($newWord))
                    $this->errors[] = Language::getInstance()->getString('please_enter_a_word');
                else
                {
                    //Get new ID
                    $this->censorshipID = current(end($this->censorships))+1;
                    //Add to censorships
                    Functions::file_put_contents('vars/cwords.var', $this->censorshipID . "\t" . $newWord . "\t" . $newReplacement . "\t\n", FILE_APPEND);
                    //Done
                    Logger::getInstance()->log('%s added new censorship (ID: ' . $this->censorshipID . ')', Logger::LOG_ACP_ACTION);
                    header('Location: ' . INDEXFILE . '?faction=ad_censor' . SID_AMPER_RAW);
                    Template::getInstance()->printMessage('censorship_added');
                }
            }
            Template::getInstance()->assign(['newWord' => $newWord,
                'newReplacement' => $newReplacement]);
            break;

//AdminCensorEditWord
            case 'edit';
            NavBar::getInstance()->addElement(Language::getInstance()->getString('edit_censorship'), INDEXFILE . '?faction=ad_censor&amp;mode=edit&amp;id=' . $this->censorshipID . SID_AMPER);
            if(($key = array_search($this->censorshipID, array_map('current', $this->censorships))) === false)
                Template::getInstance()->printMessage('censorship_not_found');
            $editWord = htmlspecialchars(trim(Functions::getValueFromGlobals('word')));
            $editReplacement = htmlspecialchars(trim(Functions::getValueFromGlobals('replacement'))) ?: '******';
            PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_CENSOR_EDIT_CENSORSHIP, $editWord, $editReplacement);
            if(Functions::getValueFromGlobals('update') == '1')
            {
                if(empty($editWord))
                    $this->errors[] = Language::getInstance()->getString('please_enter_a_word');
                else
                {
                    //Update censorship
                    $this->censorships[$key][1] = $editWord;
                    $this->censorships[$key][2] = $editReplacement;
                    //Save it
                    Functions::file_put_contents('vars/cwords.var', implode("\n", array_map(['Functions', 'implodeByTab'], $this->censorships)) . "\n");
                    //Done
                    Logger::getInstance()->log('%s edited censorship (ID: ' . $this->censorshipID . ')', Logger::LOG_ACP_ACTION);
                    header('Location: ' . INDEXFILE . '?faction=ad_censor' . SID_AMPER_RAW);
                    Template::getInstance()->printMessage('censorship_edited');
                }
            }
            else
            {
                $editWord = $this->censorships[$key][1];
                $editReplacement = $this->censorships[$key][2];
            }
            Template::getInstance()->assign(['censorshipID' => $this->censorshipID,
                'editWord' => $editWord,
                'editReplacement' => $editReplacement]);
            break;

            case 'kill';
            NavBar::getInstance()->addElement(Language::getInstance()->getString('delete_censorship'), INDEXFILE . '?faction=ad_censor&amp;mode=kill&amp;id=' . $this->censorshipID . SID_AMPER);
            if(($key = array_search($this->censorshipID, array_map('current', $this->censorships))) === false)
                Template::getInstance()->printMessage('censorship_not_found');
            PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_CENSOR_DELETE_CENSORSHIP, $key);
            //Delete it
            unset($this->censorships[$key]);
            Functions::file_put_contents('vars/cwords.var', empty($this->censorships) ? '' : implode("\n", array_map(['Functions', 'implodeByTab'], $this->censorships)) . "\n");
            //Done
            Logger::getInstance()->log('%s deleted censorship (ID: ' . $this->censorshipID . ')', Logger::LOG_ACP_ACTION);
            header('Location: ' . INDEXFILE . '?faction=ad_censor' . SID_AMPER_RAW);
            Template::getInstance()->printMessage('censorship_deleted');
            break;

//AdminCensor
            default:
            PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_CENSOR_SHOW_CENSORSHIPS);
            Template::getInstance()->assign('censorships', $this->censorships);
            break;
        }
        Template::getInstance()->printPage(self::$modeTable[$this->mode], ['errors' => $this->errors]);
    }
}
?>