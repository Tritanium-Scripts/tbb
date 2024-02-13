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
        $this->mailBlocks = [];
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
            NavBar::getInstance()->addElement(Language::getInstance()->getString('manage_mail_blocks'), INDEXFILE . '?faction=adminMailBlock&amp;mode=new' . SID_AMPER);
            $newMailAddressLocalPart = Functions::getValueFromGlobals('mailAddressLocalPart');
            $newMailAddressSld = Functions::getValueFromGlobals('mailAddressSld');
            $newMailAddressTld = Functions::getValueFromGlobals('mailAddressTld');
            PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_MAIL_BLOCK_NEW_BLOCK, $newMailAddressLocalPart, $newMailAddressSld, $newMailAddressTld);
            if(Functions::getValueFromGlobals('create') == 'yes')
            {
                if(empty($newMailAddressLocalPart) && empty($newMailAddressSld) && empty($newMailAddressTld))
                    $this->errors[] = Language::getInstance()->getString('please_enter_a_mail_part');
                else if(Functions::strpos($newMailAddressLocalPart, '@') !== false || Functions::strpos($newMailAddressSld, '@') !== false || Functions::strpos($newMailAddressTld, '@') !== false)
                    $this->errors[] = Language::getInstance()->getString('please_enter_a_valid_mail');
                if(empty($this->errors))
                {
                    //Get new ID
                    $this->mailBlockId = !empty($this->mailBlocks) ? current(end($this->mailBlocks))+1 : 1;
                }
            }
            Template::getInstance()->assign(['newMailAddressLocalPart' => $newMailAddressLocalPart,
                'newMailAddressSld' => $newMailAddressSld,
                'newMailAddressTld' => $newMailAddressTld]);
            break;

            case 'kill':
            if(($key = array_search($this->mailBlockId, array_map('current', $this->mailBlocks))) === false)
                Template::getInstance()->printMessage('mail_block_not_found');
            PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_MAIL_BLOCK_DELETE_BLOCK, $key);
            //Delete it
            unset($this->mailBlocks[$key]);
            break;

//AdminMailBlock
            default:
            PlugIns::getInstance()->callHook(PlugIns::HOOK_ADMIN_MAIL_BLOCK_SHOW_BLOCKS);
            Template::getInstance()->assign('mailBlocks', $this->mailBlocks);
            break;
        }
        Template::getInstance()->printPage(self::$modeTable[$this->mode], ['errors' => $this->errors]);
    }
}
?>