<?php
/**
 * Displays e-mail list.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class AdminMailList implements Module
{
	/**
	 * Displays e-mail list of all user based on their mail options.
	 */
	public function execute()
	{
		Functions::accessAdminPanel();
		Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('email_list'), INDEXFILE . '?faction=ad_emailist' . SID_AMPER);
		$mailAddys = array();
		foreach(glob(DATAPATH . 'members/[!0t]*.xbb') as $curMember)
		{
			list(,,,$curMailAddy,,,,,,,,,,,$cuMailOptions) = Functions::file($curMember, null, null, false);
			//Only collect mail address if mail option allows it
			if(current(Functions::explodeByComma($cuMailOptions)) == '1')
				$mailAddys[] = $curMailAddy;
		}
		Main::getModule('Logger')->log('%s retrieved e-mail list', LOG_ACP_ACTION);
		Main::getModule('Template')->printPage('AdminMailList', 'mailAddys', $mailAddys);
	}
}
?>