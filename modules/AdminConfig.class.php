<?php
/**
 * @author Julian Backes <julian@tritanium-scripts.com>
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2003 - 2009, Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package tbb2
 */
class AdminConfig extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'AuthAdmin',
		'Cache',
		'Config',
		'DB',
		'GlobalsAdmin',
		'Language',
		'Navbar',
		'Template'
	);

	public function executeMe() {
		$this->modules['Language']->addFile('AdminConfig');
		$this->modules['Navbar']->addElement($this->modules['Language']->getString('boardconfig'),INDEXFILE.'?action=AdminConfig&amp;'.MYSID);

		$configNames = array(
			'admin_rank_pic','allowed_attachment_types','allow_ghost_mode','allow_pms_bbcode','allow_pms_htmlcode',
			'allow_pms_rconfirmation','allow_pms_signature','allow_pms_smilies','allow_profile_notes','allow_select_lng',
			'allow_select_lng_guests','allow_select_style','allow_select_tpl','allow_sig_bbcode','allow_sig_html',
			'allow_sig_smilies','auth_global_smilies','avatar_image_height','avatar_image_width','board_address',
			'board_email_address','board_logo','board_name','check_unique_email_addresses','dataversion',
			'email_signature','enable_attachments','enable_avatars','enable_avatar_upload','enable_email_formular',
			'enable_email_functions','enable_file_upload','enable_gzip','enable_news_module','enable_outbox',
			'enable_pms','enable_registration','enable_sig','enable_topic_subscription','enable_wio',
			'forbidden_attachment_types','guests_enter_board','maximum_pms','maximum_pms_folders','maximum_registrations',
			'maximum_sig_length','max_attachments_per_post','max_attachment_size','max_avatar_file_size','max_latest_posts',
			'mod_rank_pic','newest_user_id','newest_user_nick','news_forum','online_users_record',
			'path_to_forum','posts_per_page','require_accept_boardrules','search_status','show_boardstats_forumindex',
			'show_latest_posts_forumindex','show_news_forumindex','show_techstats','show_wio_forumindex','srgc_probability',
			'sr_timeout','standard_language','standard_style','standard_tpl','standard_tz',
			'supermod_rank_pic','topics_per_page','usersCounter','use_language_detection','verify_email_address',
			'wio_timeout','hot_status_posts_last_hour','hide_not_accessible_forums','announcements_forum_id'
		);

		$p = array();

		foreach($configNames AS &$curName)
			$p['config'][$curName] = isset($_POST['p']['config'][$curName]) ? $_POST['p']['config'][$curName] : $this->modules['Config']->getValue($curName);

		$p['config']['email_signature'] = Functions::str_replace("\r\n","\n",$p['config']['email_signature']);

		if(isset($_GET['doit'])) {
			foreach($configNames AS &$curName)
				$this->modules['Config']->updateValue($curName,$p['config'][$curName],FALSE);

			$this->modules['Cache']->setConfig();

			FuncMisc::printMessage('board_config_updated'); exit;
		}

		$this->modules['DB']->query('SELECT "forumID","forumName" FROM '.TBLPFX.'forums ORDER BY "orderID"');
		$forumsData = $this->modules['DB']->raw2Array();
		foreach($forumsData AS &$curForum)
			$curForum['forumName'] = Functions::HTMLSpecialChars($curForum['forumName']);

		list(,$languages) = $this->modules['Cache']->getLanguages();

		$this->modules['Template']->assign(array(
			'timeZones'=>Functions::getTimeZones(TRUE),
			'forumsData'=>$forumsData,
			'languages'=>$languages,
			'p'=>$p
		));
		$this->modules['Template']->printPage('AdminConfig.tpl');
	}
}