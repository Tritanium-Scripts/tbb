# ACHTUNG:
# Eventuell muss der Cache geloescht werden nachdem die Daten eingefuegt wurden.
# D.h. einfach alle Dateien in /cache/ loeschen

/*!40100 SET CHARACTER SET latin1*/;


#
# Dumping data for table 'tbb2_cats'
#

/*!40000 ALTER TABLE `tbb2_cats` DISABLE KEYS*/;
LOCK TABLES `tbb2_cats` WRITE;
REPLACE INTO `tbb2_cats` (`catID`, `catL`, `catR`, `catStandardStatus`, `catName`, `catDescription`) VALUES (1,1,4,1,'ROOT',''),
	(2,2,3,1,'Testkategorie','');
UNLOCK TABLES;
/*!40000 ALTER TABLE `tbb2_cats` ENABLE KEYS*/;


#
# Dumping data for table 'tbb2_config'
#

/*!40000 ALTER TABLE `tbb2_config` DISABLE KEYS*/;
LOCK TABLES `tbb2_config` WRITE;
REPLACE INTO `tbb2_config` (`configName`, `configValue`) VALUES ('admin_rank_pic','images/rankpics/admin.gif'),
	('allow_pms_bbcode','1'),
	('allow_pms_htmlcode','0'),
	('allow_pms_rconfirmation','1'),
	('allow_pms_signature','1'),
	('allow_pms_smilies','1'),
	('allow_select_lng','1'),
	('allow_select_style','1'),
	('allow_select_tpl','1'),
	('allow_sig_bbcode','1'),
	('allow_sig_html','0'),
	('allow_sig_smilies','1'),
	('avatar_image_height','64'),
	('avatar_image_width','64'),
	('board_address',''),
	('board_email_address',''),
	('board_logo',''),
	('board_name','Testcommunity'),
	('email_signature',''),
	('enable_avatar_upload','1'),
	('enable_avatars','1'),
	('enable_email_functions','1'),
	('enable_email_formular','1'),
	('enable_file_upload','1'),
	('enable_gzip','1'),
	('enable_news_module','0'),
	('enable_outbox','1'),
	('enable_pms','1'),
	('enable_registration','1'),
	('enable_sig','1'),
	('enable_topic_subscription','1'),
	('enable_wio','1'),
	('guests_enter_board','1'),
	('max_avatar_file_size','10'),
	('max_latest_posts','5'),
	('maximum_pms','15'),
	('maximum_pms_folders','2'),
	('maximum_registrations','-1'),
	('maximum_sig_length','1000'),
	('mod_rank_pic','images/rankpics/mod.gif'),
	('newest_user_id','2541'),
	('newest_user_nick','test456'),
	('news_forum','0'),
	('online_users_record','10,1118500662'),
	('path_to_forum','/path/to/testforum'),
	('posts_per_page','10'),
	('require_accept_boardrules','1'),
	('search_status','2'),
	('show_boardstats_forumindex','1'),
	('show_latest_posts_forumindex','1'),
	('show_news_forumindex','1'),
	('show_techstats','1'),
	('show_wio_forumindex','1'),
	('sr_timeout','10'),
	('srgc_probability','10'),
	('standard_language','de'),
	('standard_style','ts_tbb2_standard.css'),
	('standard_tpl','ts_tbb2_standard'),
	('standard_tz','gmt'),
	('topics_per_page','15'),
	('verify_email_address','0'),
	('wio_timeout','10'),
	('dataversion','0.2.0.8.20050121'),
	('allow_profile_notes','1'),
	('allow_select_lng_guests','1'),
	('use_language_detection','1'),
	('supermod_rank_pic','images/rankpics/supermod.gif'),
	('auth_global_smilies','0'),
	('check_unique_email_addresses','1'),
	('enable_attachments','1'),
	('max_attachments_per_post','-1'),
	('allowed_attachment_types','gif jpeg jpg bmp zip rar ace bzip2'),
	('forbidden_attachment_types',''),
	('max_attachment_size','-1'),
	('allow_ghost_mode','0');
UNLOCK TABLES;
/*!40000 ALTER TABLE `tbb2_config` ENABLE KEYS*/;


#
# Dumping data for table 'tbb2_forums'
#

/*!40000 ALTER TABLE `tbb2_forums` DISABLE KEYS*/;
LOCK TABLES `tbb2_forums` WRITE;
REPLACE INTO `tbb2_forums` (`forumID`, `catID`, `orderID`, `forumName`, `forumDescription`, `forumTopicsCounter`, `forumPostsCounter`, `forumLastPostID`, `forumEnableBBCode`, `forumEnableHtmlCode`, `forumEnableSmilies`, `forumEnableURITransformation`, `forumIsModerated`, `forumShowLatestPosts`, `membersAuthViewForum`, `authPostTopicMembers`, `authPostReplyMembers`, `authPostPollMembers`, `authEditPostsMembers`, `authViewForumGuests`, `authPostTopicGuests`, `authPostReplyGuests`, `authPostPollGuests`) VALUES
	(1,2,1,'Testforum','Zum Testen',0,0,0,1,0,1,1,0,1,1,1,1,1,1,1,0,0,0),;
UNLOCK TABLES;
/*!40000 ALTER TABLE `tbb2_forums` ENABLE KEYS*/;


#
# Dumping data for table 'tbb2_ranks'
#

/*!40000 ALTER TABLE `tbb2_ranks` DISABLE KEYS*/;
LOCK TABLES `tbb2_ranks` WRITE;
REPLACE INTO `tbb2_ranks` (`rankID`, `rankType`, `rankName`, `rankGfx`, `rankPosts`) VALUES (1,0,'Ganz neu hier','images/rankpics/ystar.gif',0),
	(2,0,'Lernt noch alles kennen','images/rankpics/ystar.gif',10),
	(3,0,'Ist oefters hier','images/rankpics/ystar.gif;images/rankpics/ystar.gif',20),
	(4,0,'Kennt sich schon aus','images/rankpics/ystar.gif;images/rankpics/ystar.gif',50),
	(5,0,'Stammgast','images/rankpics/ystar.gif;images/rankpics/ystar.gif',150),
	(6,0,'Fuehlt sich wie zu Hause','images/rankpics/ystar.gif;images/rankpics/ystar.gif;images/rankpics/ystar.gif',350),
	(7,0,'Wird so langsam nervig','images/rankpics/ystar.gif;images/rankpics/ystar.gif;images/rankpics/ystar.gif',500),
	(8,0,'Fast schon Admin','images/rankpics/ystar.gif;images/rankpics/ystar.gif;images/rankpics/ystar.gif',800),
	(9,0,'Wohnt hier','images/rankpics/ystar.gif;images/rankpics/ystar.gif;images/rankpics/ystar.gif;images/rankpics/ystar.gif',1500),
	(10,1,'Verueckter Idiot','',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `tbb2_ranks` ENABLE KEYS*/;


#
# Dumping data for table 'tbb2_smilies'
#

/*!40000 ALTER TABLE `tbb2_smilies` DISABLE KEYS*/;
LOCK TABLES `tbb2_smilies` WRITE;
REPLACE INTO `tbb2_smilies` (`SmileyID`, `SmileyType`, `SmileyFileName`, `SmileySynonym`, `SmileyStatus`) VALUES (1,1,'images/tsmilies/1.gif','',0),
	(10,1,'images/tsmilies/10.gif','',0),
	(11,1,'images/tsmilies/11.gif','',0),
	(12,1,'images/tsmilies/12.gif','',0),
	(13,1,'images/tsmilies/13.gif','',0),
	(14,1,'images/tsmilies/14.gif','',0),
	(2,1,'images/tsmilies/2.gif','',0),
	(3,1,'images/tsmilies/3.gif','',0),
	(4,1,'images/tsmilies/4.gif','',0),
	(5,1,'images/tsmilies/5.gif','',0),
	(6,1,'images/tsmilies/6.gif','',0),
	(7,1,'images/tsmilies/7.gif','',0),
	(8,1,'images/tsmilies/8.gif','',0),
	(9,1,'images/tsmilies/9.gif','',0),
	(15,0,'images/smilies/1.gif',':)',1),
	(16,0,'images/smilies/2.gif',':(',1),
	(17,0,'images/smilies/3.gif',':o',1),
	(18,0,'images/smilies/4.gif',':D',1),
	(19,0,'images/smilies/5.gif',';)',1),
	(20,0,'images/smilies/6.gif',':P',1),
	(21,0,'images/smilies/7.gif',':cool:',1),
	(22,0,'images/smilies/8.gif',':rolleyes:',1),
	(23,0,'images/smilies/9.gif',':mad:',1),
	(24,0,'images/smilies/10.gif',':eek:',1),
	(25,2,'images/smilies/11.gif',':TEST:',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `tbb2_smilies` ENABLE KEYS*/;
