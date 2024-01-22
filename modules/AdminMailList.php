<?php
/**
 * Displays email list.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2023 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class AdminMailList extends PublicModule
{
    use Singleton;

    /**
     * Displays email list of all user based on their mail options.
     */
    public function publicCall(): void
    {
        Functions::accessAdminPanel();
        NavBar::getInstance()->addElement(Language::getInstance()->getString('email_list'), INDEXFILE . '?faction=ad_emailist' . SID_AMPER);
        $mailAddys = [];
        foreach(Functions::glob(DATAPATH . 'members/[!0t]*.xbb') as $curMember)
        {
            list(,,,$curMailAddy,,,,,,,,,,,$curMailOptions) = Functions::file($curMember, null, null, false);
            //Only collect mail address if mail option allows it
            if(current(Functions::explodeByComma($curMailOptions)) == '1')
                $mailAddys[] = $curMailAddy;
        }
        Logger::getInstance()->log('%s retrieved email list', Logger::LOG_ACP_ACTION);
        Template::getInstance()->printPage('AdminMailList', 'mailAddys', $mailAddys);
    }
}
?>