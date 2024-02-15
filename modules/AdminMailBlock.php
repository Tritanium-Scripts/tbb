<?php
/**
 * Manages blocking of email addresses.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2024 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class AdminMailBlock extends PublicModule
{
    use Singleton, Mode, Errors;

    /**
     * Translates a mode to its template file.
     *
     * @var array Mode and template counterparts
     */
    private static array $modeTable = ['adminMailBlock' => 'AdminMailBlock',
        'new' => 'AdminMailBlockNewAddress'];

    /**
     * ID of current email address block.
     *
     * @var int Email block ID
     */
    private int $mailBlockId;

    /**
     * Existing email address blocks.
     *
     * @var array Blocked emails
     */
    private array $mailBlocks;

    /**
     * Sets mode.
     *
     * @param string $mode Mode
     */
    function __construct(string $mode)
    {
        parent::__construct();
        $this->mode = $mode;
        $this->mailBlockId = intval(Functions::getValueFromGlobals('id'));
        $this->mailBlocks = array_map(['Functions', 'explodeByTab'], Functions::file('vars/mailblocks.var') ?: []);
        PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_MAIL_BLOCK_INIT);
    }

    /**
     * Executes mode.
     */
    public function publicCall(): void
    {
        Functions::accessAdminPanel();
        NavBar::getInstance()->addElement(Language::getInstance()->getString('manage_mail_blocks'), INDEXFILE . '?faction=adminMailBlock' . SID_AMPER);
        switch($this->mode)
        {
//AdminMailBlockNewAddress
            case 'new':
            NavBar::getInstance()->addElement(Language::getInstance()->getString('add_new_mail_block'), INDEXFILE . '?faction=adminMailBlock&amp;mode=new' . SID_AMPER);
            $newMailAddressLocalPart = Functions::getValueFromGlobals('mailAddressLocalPart');
            $newMailAddressSld = Functions::getValueFromGlobals('mailAddressSld');
            $newMailAddressTld = Functions::getValueFromGlobals('mailAddressTld');
            $newBlockPeriod = intval(Functions::getValueFromGlobals('blockPeriod')) ?: '';
            PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_MAIL_BLOCK_NEW_BLOCK, $newMailAddressLocalPart, $newMailAddressSld, $newMailAddressTld, $newBlockPeriod);
            if(Functions::getValueFromGlobals('create') == 'yes')
            {
                if(empty($newMailAddressLocalPart) && empty($newMailAddressSld) && empty($newMailAddressTld))
                    $this->errors[] = Language::getInstance()->getString('please_enter_a_mail_part');
                else if(empty($newMailAddressTld) || !Functions::isValidMail(($newMailAddressLocalPart ?: 'a') . '@' . ($newMailAddressSld ?: 'a') . '.' . $newMailAddressTld))
                    $this->errors[] = Language::getInstance()->getString('please_enter_a_valid_mail');
                if(empty($newBlockPeriod))
                    $newBlockPeriod = -1;
                if(empty($this->errors))
                {
                    //Get new ID
                    $this->mailBlockId = !empty($this->mailBlocks) ? current(end($this->mailBlocks))+1 : 1;
                    //Add to banned emails
                    Functions::file_put_contents('vars/mailblocks.var', $this->mailBlockId . "\t" . $newMailAddressLocalPart . "\t" . $newMailAddressSld . "\t" . $newMailAddressTld . "\t" . ($newBlockPeriod != -1 ? time()+$newBlockPeriod*60 : $newBlockPeriod) . "\t\n", FILE_APPEND);
                    //Done
                    Logger::getInstance()->log('%s added new mail block (ID: ' . $this->mailBlockId . ')', Logger::LOG_ACP_ACTION);
                    header('Location: ' . INDEXFILE . '?faction=adminMailBlock' . SID_AMPER_RAW);
                    Template::getInstance()->printMessage('mail_block_added');
                }
            }
            Template::getInstance()->assign(['newMailAddressLocalPart' => $newMailAddressLocalPart,
                'newMailAddressSld' => $newMailAddressSld,
                'newMailAddressTld' => $newMailAddressTld,
                'newBlockPeriod' => $newBlockPeriod]);
            break;

            case 'kill':
            if(($key = array_search($this->mailBlockId, array_map('current', $this->mailBlocks))) === false)
                Template::getInstance()->printMessage('mail_block_not_found');
            PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_MAIL_BLOCK_DELETE_BLOCK, $key);
            //Delete it
            unset($this->mailBlocks[$key]);
            if(empty($this->mailBlocks))
                Functions::unlink('vars/mailblocks.var');
            else
                Functions::file_put_contents('vars/mailblocks.var', implode("\n", array_map(['Functions', 'implodeByTab'], $this->mailBlocks)) . "\n");
            //Done
            Logger::getInstance()->log('%s deleted mail block (ID: ' . $this->mailBlockId . ')', Logger::LOG_ACP_ACTION);
            header('Location: ' . INDEXFILE . '?faction=adminMailBlock' . SID_AMPER_RAW);
            Template::getInstance()->printMessage('mail_block_deleted');
            break;

//AdminMailBlock
            default:
            foreach($this->mailBlocks as &$curMailBlock)
            {
                if(empty($curMailBlock[1]))
                    $curMailBlock[1] = '*';
                if(empty($curMailBlock[2]))
                    $curMailBlock[2] = '*';
                $curMailBlock[4] = $curMailBlock[4] == '-1'
                    ? Language::getInstance()->getString('forever_blocked')
                    : ($curMailBlock[4] > time()
                        ? sprintf(Language::getInstance()->getString('x_minutes'), round(($curMailBlock[4]-time())/60))
                        : Language::getInstance()->getString('expired'));
            }
            PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_MAIL_BLOCK_SHOW_BLOCKS);
            Template::getInstance()->assign('mailBlocks', $this->mailBlocks);
            break;
        }
        Template::getInstance()->printPage(self::$modeTable[$this->mode], ['errors' => $this->errors]);
    }
}
?>