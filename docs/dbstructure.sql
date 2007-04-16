/*!40100 SET CHARACTER SET latin1*/;


#
# Database structure for database 'tbb2test'
#

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `tbb2test`;

USE `tbb2test`;


#
# Table structure for table 'tbb2_avatars'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `tbb2_avatars` (
  `avatarID` smallint(5) unsigned NOT NULL auto_increment,
  `avatarAddress` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`avatarID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



#
# Table structure for table 'tbb2_cats'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `tbb2_cats` (
  `catID` mediumint(5) unsigned NOT NULL auto_increment,
  `catL` mediumint(5) NOT NULL default '0',
  `catR` mediumint(5) NOT NULL default '0',
  `catStandardStatus` tinyint(1) unsigned NOT NULL default '1',
  `catName` varchar(255) NOT NULL default '',
  `catDescription` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`catID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



#
# Table structure for table 'tbb2_config'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `tbb2_config` (
  `configName` varchar(255) NOT NULL default '',
  `configValue` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`configName`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



#
# Table structure for table 'tbb2_forums'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `tbb2_forums` (
  `forumID` mediumint(8) unsigned NOT NULL auto_increment,
  `catID` mediumint(8) unsigned NOT NULL default '0',
  `orderID` mediumint(8) unsigned NOT NULL default '0',
  `forumName` varchar(255) NOT NULL default '',
  `forumDescription` varchar(255) NOT NULL default '',
  `forumTopicsCounter` mediumint(8) unsigned NOT NULL default '0',
  `forumPostsCounter` mediumint(8) unsigned NOT NULL default '0',
  `forumLastPostID` mediumint(8) unsigned NOT NULL default '0',
  `forumEnableBBCode` tinyint(1) unsigned NOT NULL default '0',
  `forumEnableHtmlCode` tinyint(1) unsigned NOT NULL default '0',
  `forumEnableSmilies` tinyint(1) unsigned NOT NULL default '0',
  `forumEnableURITransformation` tinyint(1) unsigned NOT NULL default '0',
  `forumIsModerated` tinyint(1) unsigned NOT NULL default '0',
  `forumShowLatestPosts` tinyint(1) unsigned NOT NULL default '0',
  `authViewForumMembers` tinyint(1) unsigned NOT NULL default '0',
  `authPostTopicMembers` tinyint(1) unsigned NOT NULL default '0',
  `authPostReplyMembers` tinyint(1) unsigned NOT NULL default '0',
  `authPostPollMembers` tinyint(1) unsigned NOT NULL default '0',
  `authEditPostsMembers` tinyint(1) unsigned NOT NULL default '0',
  `authViewForumGuests` tinyint(1) unsigned NOT NULL default '0',
  `authPostTopicGuests` tinyint(1) unsigned NOT NULL default '0',
  `authPostReplyGuests` tinyint(1) unsigned NOT NULL default '0',
  `authPostPollGuests` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`forumID`),
  KEY `cat_id` (`catID`),
  KEY `order_id` (`orderID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



#
# Table structure for table 'tbb2_forums_auth'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `tbb2_forums_auth` (
  `forumID` mediumint(8) unsigned NOT NULL default '0',
  `authType` tinyint(1) unsigned NOT NULL default '0',
  `authID` mediumint(8) unsigned NOT NULL default '0',
  `authViewForum` tinyint(1) unsigned NOT NULL default '0',
  `authPostTopic` tinyint(1) unsigned NOT NULL default '0',
  `authPostReply` tinyint(1) unsigned NOT NULL default '0',
  `authPostPoll` tinyint(1) unsigned NOT NULL default '0',
  `authEditPosts` tinyint(1) unsigned NOT NULL default '0',
  `authIsMod` tinyint(1) unsigned NOT NULL default '0',
  KEY `forum_id` (`forumID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



#
# Table structure for table 'tbb2_groups'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `tbb2_groups` (
  `groupID` smallint(5) unsigned NOT NULL auto_increment,
  `groupName` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`groupID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



#
# Table structure for table 'tbb2_groups_members'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `tbb2_groups_members` (
  `GroupID` smallint(5) unsigned NOT NULL default '0',
  `MemberID` mediumint(8) unsigned NOT NULL default '0',
  `MemberStatus` tinyint(1) unsigned NOT NULL default '0',
  KEY `group_id` (`GroupID`),
  KEY `member_id_group_id` (`MemberID`,`GroupID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



#
# Table structure for table 'tbb2_pms'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `tbb2_pms` (
  `pmID` mediumint(8) unsigned NOT NULL auto_increment,
  `folderID` smallint(5) unsigned NOT NULL default '0',
  `pmFromID` mediumint(8) unsigned NOT NULL default '0',
  `pmToID` mediumint(8) unsigned NOT NULL default '0',
  `pmIsRead` tinyint(1) unsigned NOT NULL default '0',
  `pmType` tinyint(1) unsigned NOT NULL default '0',
  `pmSubject` varchar(255) NOT NULL default '',
  `pmMessageText` text NOT NULL,
  `pmSendTimestamp` int(10) unsigned NOT NULL default '0',
  `pmEnableBBCode` tinyint(1) unsigned NOT NULL default '0',
  `pmEnableSmilies` tinyint(1) unsigned NOT NULL default '0',
  `pmEnableHtmlCode` tinyint(1) unsigned NOT NULL default '0',
  `pmShowSignature` tinyint(1) unsigned NOT NULL default '0',
  `pmRequestReadReceipt` tinyint(1) unsigned NOT NULL default '0',
  `pmGuestNick` varchar(255) NOT NULL default '',
  `pmIsReplied` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`pmID`),
  KEY `folder_id` (`folderID`),
  KEY `pm_from_id` (`pmFromID`),
  KEY `pm_to_id` (`pmToID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



#
# Table structure for table 'tbb2_pms_folders'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `tbb2_pms_folders` (
  `folderID` smallint(5) unsigned NOT NULL default '0',
  `userID` mediumint(8) unsigned NOT NULL default '0',
  `folderName` varchar(255) NOT NULL default '',
  KEY `userID` (`userID`),
  KEY `folderIDUserID` (`folderID`,`userID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



#
# Table structure for table 'tbb2_polls'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `tbb2_polls` (
  `poll_id` mediumint(8) unsigned NOT NULL auto_increment,
  `topic_id` mediumint(8) unsigned NOT NULL default '0',
  `poster_id` mediumint(8) unsigned NOT NULL default '0',
  `poll_title` varchar(255) NOT NULL default '',
  `poll_votes` mediumint(8) unsigned NOT NULL default '0',
  `poll_guest_nick` varchar(255) NOT NULL default '',
  `poll_start_time` int(10) unsigned NOT NULL default '0',
  `poll_end_time` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`poll_id`),
  KEY `topic_id_poll_id` (`topic_id`,`poll_id`),
  KEY `poster_id` (`poster_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



#
# Table structure for table 'tbb2_polls_options'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `tbb2_polls_options` (
  `poll_id` mediumint(8) unsigned NOT NULL default '0',
  `option_id` smallint(5) unsigned NOT NULL default '0',
  `option_title` varchar(255) NOT NULL default '',
  `option_votes` mediumint(8) NOT NULL default '0',
  KEY `option_id` (`option_id`),
  KEY `poll_id_option_id` (`poll_id`,`option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



#
# Table structure for table 'tbb2_polls_votes'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `tbb2_polls_votes` (
  `poll_id` mediumint(8) unsigned NOT NULL default '0',
  `voter_id` mediumint(8) unsigned NOT NULL default '0',
  KEY `poll_id` (`poll_id`),
  KEY `voter_id_poll_id` (`voter_id`,`poll_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



#
# Table structure for table 'tbb2_posts'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `tbb2_posts` (
  `postID` mediumint(8) unsigned NOT NULL auto_increment,
  `topicID` mediumint(8) unsigned NOT NULL default '0',
  `forumID` mediumint(8) unsigned NOT NULL default '0',
  `posterID` mediumint(8) unsigned NOT NULL default '0',
  `postTimestamp` int(10) unsigned NOT NULL default '0',
  `postIP` varchar(15) NOT NULL default '',
  `smileyID` smallint(5) unsigned NOT NULL default '0',
  `postEnableBBCode` tinyint(1) unsigned NOT NULL default '0',
  `postEnableSmilies` tinyint(1) unsigned NOT NULL default '0',
  `postEnableHtmlCode` tinyint(1) unsigned NOT NULL default '0',
  `postShowSignature` tinyint(1) unsigned NOT NULL default '0',
  `postEnableURITransformation` tinyint(1) unsigned NOT NULL default '0',
  `postShowEditings` tinyint(1) unsigned NOT NULL default '0',
  `postGuestNick` varchar(15) NOT NULL default '',
  `postEditedCounter` smallint(5) unsigned NOT NULL default '0',
  `postLastEditorNick` varchar(255) NOT NULL default '',
  `postTitle` varchar(255) NOT NULL default '',
  `postText` text NOT NULL,
  PRIMARY KEY  (`postID`),
  KEY `topic_id` (`topicID`),
  KEY `forum_id` (`forumID`),
  KEY `poster_id` (`posterID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



#
# Table structure for table 'tbb2_profile_fields'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `tbb2_profile_fields` (
  `fieldID` smallint(5) unsigned NOT NULL auto_increment,
  `fieldName` varchar(255) NOT NULL default '',
  `fieldType` tinyint(1) unsigned NOT NULL default '0',
  `fieldIsRequired` tinyint(1) unsigned NOT NULL default '0',
  `fieldShowRegistration` tinyint(1) unsigned NOT NULL default '0',
  `fieldShowMemberlist` tinyint(1) unsigned NOT NULL default '0',
  `fieldLink` varchar(255) NOT NULL default '',
  `fieldData` text NOT NULL,
  `fieldRegexVerification` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`fieldID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



#
# Table structure for table 'tbb2_profile_fields_data'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `tbb2_profile_fields_data` (
  `fieldID` smallint(5) unsigned NOT NULL default '0',
  `userID` mediumint(8) unsigned NOT NULL default '0',
  `fieldValue` text NOT NULL,
  KEY `field_id` (`fieldID`),
  KEY `user_id_field_id` (`userID`,`fieldID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



#
# Table structure for table 'tbb2_profile_notes'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `tbb2_profile_notes` (
  `noteID` mediumint(8) unsigned NOT NULL auto_increment,
  `userID` mediumint(8) unsigned NOT NULL default '0',
  `profileID` mediumint(8) unsigned NOT NULL default '0',
  `noteTimestamp` int(10) unsigned NOT NULL default '0',
  `noteIsPublic` tinyint(1) unsigned NOT NULL default '0',
  `noteText` text NOT NULL,
  PRIMARY KEY  (`noteID`),
  KEY `user_id_profile_id` (`userID`,`profileID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



#
# Table structure for table 'tbb2_ranks'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `tbb2_ranks` (
  `rankID` smallint(5) unsigned NOT NULL auto_increment,
  `rankType` tinyint(1) unsigned NOT NULL default '0',
  `rankName` varchar(255) NOT NULL default '',
  `rankGfx` text NOT NULL,
  `rankPosts` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`rankID`),
  KEY `rank_type` (`rankType`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



#
# Table structure for table 'tbb2_search_results'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `tbb2_search_results` (
  `search_id` varchar(32) NOT NULL default '',
  `session_id` varchar(32) NOT NULL default '',
  `search_last_access` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `search_results` text NOT NULL,
  PRIMARY KEY  (`search_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



#
# Table structure for table 'tbb2_sessions'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `tbb2_sessions` (
  `sessionID` varchar(32) NOT NULL default '',
  `sessionLastUpdate` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `sessionData` text NOT NULL,
  `sessionUserID` mediumint(8) unsigned NOT NULL default '0',
  `sessionIsGhost` tinyint(1) unsigned NOT NULL default '0',
  `sessionLastLocation` varchar(255) NOT NULL default 'forumindex',
  PRIMARY KEY  (`sessionID`),
  KEY `session_last_update` (`sessionLastUpdate`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



#
# Table structure for table 'tbb2_smilies'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `tbb2_smilies` (
  `SmileyID` smallint(5) unsigned NOT NULL auto_increment,
  `SmileyType` tinyint(1) unsigned NOT NULL default '0',
  `SmileyFileName` varchar(255) NOT NULL default '',
  `SmileySynonym` varchar(255) NOT NULL default '',
  `SmileyStatus` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`SmileyID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



#
# Table structure for table 'tbb2_topics'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `tbb2_topics` (
  `topicID` mediumint(8) unsigned NOT NULL auto_increment,
  `forumID` mediumint(8) unsigned NOT NULL default '0',
  `posterID` mediumint(8) unsigned NOT NULL default '0',
  `topicIsClosed` tinyint(1) unsigned NOT NULL default '0',
  `topicIsPinned` tinyint(1) unsigned NOT NULL default '0',
  `smileyID` smallint(5) unsigned NOT NULL default '0',
  `topicRepliesCounter` mediumint(8) unsigned NOT NULL default '0',
  `topicViewsCounter` mediumint(8) unsigned NOT NULL default '0',
  `topicHasPoll` tinyint(1) unsigned NOT NULL default '0',
  `topicFirstPostID` mediumint(8) unsigned NOT NULL default '0',
  `topicLastPostID` mediumint(8) unsigned NOT NULL default '0',
  `topicMovedID` mediumint(8) unsigned NOT NULL default '0',
  `topicMovedTimestamp` int(10) unsigned NOT NULL default '0',
  `topicPostTimestamp` int(10) unsigned NOT NULL default '0',
  `topicTitle` varchar(255) NOT NULL default '',
  `topicGuestNick` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`topicID`),
  KEY `forum_id_topic_id` (`forumID`,`topicID`),
  KEY `poster_id` (`posterID`),
  KEY `topic_moved_id` (`topicMovedID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



#
# Table structure for table 'tbb2_topics_subscriptions'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `tbb2_topics_subscriptions` (
  `topicID` mediumint(8) unsigned NOT NULL default '0',
  `userID` mediumint(8) unsigned NOT NULL default '0',
  KEY `topic_id` (`topicID`),
  KEY `user_id_topic_id` (`userID`,`topicID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



#
# Table structure for table 'tbb2_users'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `tbb2_users` (
  `userID` mediumint(8) unsigned NOT NULL auto_increment,
  `userIsActivated` tinyint(1) unsigned NOT NULL default '0',
  `userIsAdmin` tinyint(1) unsigned NOT NULL default '0',
  `userIsSupermod` tinyint(1) unsigned NOT NULL default '0',
  `userHash` varchar(32) NOT NULL default '',
  `userNick` varchar(255) NOT NULL default '',
  `userEmailAddress` varchar(255) NOT NULL default '',
  `userPassword` varchar(255) NOT NULL default '',
  `userPasswordSalt` varchar(255) default NULL,
  `userNewPassword` varchar(255) default NULL,
  `userNewPasswordSalt` varchar(255) default NULL,
  `userPostsCounter` mediumint(8) unsigned NOT NULL default '0',
  `userRegistrationTimestamp` int(10) unsigned NOT NULL default '0',
  `userSignature` text NOT NULL,
  `groupID` mediumint(8) unsigned NOT NULL default '0',
  `userLastAction` int(10) unsigned NOT NULL default '0',
  `rankID` smallint(5) unsigned NOT NULL default '0',
  `userAvatarAddress` varchar(255) NOT NULL default '',
  `userTimeZone` varchar(255) NOT NULL default 'gmt',
  `userReceiveEmails` tinyint(1) unsigned NOT NULL default '1',
  `userHideEmailAddress` tinyint(1) unsigned NOT NULL default '0',
  `userIsLocked` tinyint(1) unsigned NOT NULL default '0',
  `userMemo` text NOT NULL,
  `userAuthProfileNotes` tinyint(1) unsigned NOT NULL default '2',
  `userLanguage` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`userID`),
  KEY `userLastAction` (`userLastAction`),
  KEY `rankID` (`rankID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



#
# Table structure for table 'tbb2_users_locks'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ `tbb2_users_locks` (
  `user_id` mediumint(8) unsigned NOT NULL default '0',
  `lock_type` tinyint(1) unsigned NOT NULL default '0',
  `lock_start_time` int(10) unsigned NOT NULL default '0',
  `lock_dur_time` int(10) unsigned NOT NULL default '0',
  KEY `user_id` (`user_id`),
  KEY `lock_type_user_id` (`lock_type`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

