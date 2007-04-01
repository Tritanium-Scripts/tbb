# SQLFront 3.2  (Build 14.11)

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES latin1 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='SYSTEM' */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE */;
/*!40101 SET SQL_MODE='' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES */;
/*!40103 SET SQL_NOTES='ON' */;


# Host: localhost    Database: tbb2test
# ------------------------------------------------------
# Server version 4.1.14-nt

DROP DATABASE IF EXISTS `tbb2test`;
CREATE DATABASE `tbb2test` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `tbb2test`;

#
# Table structure for table tbb2_avatars
#

CREATE TABLE `tbb2_avatars` (
  `avatarID` smallint(5) unsigned NOT NULL auto_increment,
  `avatarAddress` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`avatarID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Table structure for table tbb2_cats
#

CREATE TABLE `tbb2_cats` (
  `catID` mediumint(5) unsigned NOT NULL auto_increment,
  `catL` mediumint(5) NOT NULL default '0',
  `catR` mediumint(5) NOT NULL default '0',
  `catStandardStatus` tinyint(1) unsigned NOT NULL default '1',
  `catName` varchar(255) NOT NULL default '',
  `catDescription` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`catID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Table structure for table tbb2_config
#

CREATE TABLE `tbb2_config` (
  `ConfigName` varchar(255) NOT NULL default '',
  `ConfigValue` varchar(255) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Table structure for table tbb2_forums
#

CREATE TABLE `tbb2_forums` (
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
  `membersAuthViewForum` tinyint(1) unsigned NOT NULL default '0',
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
# Table structure for table tbb2_forums_auth
#

CREATE TABLE `tbb2_forums_auth` (
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
# Table structure for table tbb2_groups
#

CREATE TABLE `tbb2_groups` (
  `GroupID` smallint(5) unsigned NOT NULL auto_increment,
  `GroupName` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`GroupID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Table structure for table tbb2_groups_members
#

CREATE TABLE `tbb2_groups_members` (
  `GroupID` smallint(5) unsigned NOT NULL default '0',
  `MemberID` mediumint(8) unsigned NOT NULL default '0',
  `MemberStatus` tinyint(1) unsigned NOT NULL default '0',
  KEY `group_id` (`GroupID`),
  KEY `member_id_group_id` (`MemberID`,`GroupID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Table structure for table tbb2_pms
#

CREATE TABLE `tbb2_pms` (
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
# Table structure for table tbb2_pms_folders
#

CREATE TABLE `tbb2_pms_folders` (
  `folderID` smallint(5) unsigned NOT NULL default '0',
  `userID` mediumint(8) unsigned NOT NULL default '0',
  `folderName` varchar(255) NOT NULL default '',
  KEY `folder_id` (`folderID`),
  KEY `user_id` (`userID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Table structure for table tbb2_polls
#

CREATE TABLE `tbb2_polls` (
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
# Table structure for table tbb2_polls_options
#

CREATE TABLE `tbb2_polls_options` (
  `poll_id` mediumint(8) unsigned NOT NULL default '0',
  `option_id` smallint(5) unsigned NOT NULL default '0',
  `option_title` varchar(255) NOT NULL default '',
  `option_votes` mediumint(8) NOT NULL default '0',
  KEY `option_id` (`option_id`),
  KEY `poll_id_option_id` (`poll_id`,`option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Table structure for table tbb2_polls_votes
#

CREATE TABLE `tbb2_polls_votes` (
  `poll_id` mediumint(8) unsigned NOT NULL default '0',
  `voter_id` mediumint(8) unsigned NOT NULL default '0',
  KEY `poll_id` (`poll_id`),
  KEY `voter_id_poll_id` (`voter_id`,`poll_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Table structure for table tbb2_posts
#

CREATE TABLE `tbb2_posts` (
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
# Table structure for table tbb2_profile_fields
#

CREATE TABLE `tbb2_profile_fields` (
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
# Table structure for table tbb2_profile_fields_data
#

CREATE TABLE `tbb2_profile_fields_data` (
  `FieldID` smallint(5) unsigned NOT NULL default '0',
  `UserID` mediumint(8) unsigned NOT NULL default '0',
  `FieldValue` text NOT NULL,
  KEY `field_id` (`FieldID`),
  KEY `user_id_field_id` (`UserID`,`FieldID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Table structure for table tbb2_profile_notes
#

CREATE TABLE `tbb2_profile_notes` (
  `note_id` mediumint(8) unsigned NOT NULL auto_increment,
  `user_id` mediumint(8) unsigned NOT NULL default '0',
  `profile_id` mediumint(8) unsigned NOT NULL default '0',
  `note_time` int(10) unsigned NOT NULL default '0',
  `note_is_public` tinyint(1) unsigned NOT NULL default '0',
  `note_text` text NOT NULL,
  PRIMARY KEY  (`note_id`),
  KEY `user_id_profile_id` (`user_id`,`profile_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Table structure for table tbb2_ranks
#

CREATE TABLE `tbb2_ranks` (
  `rankID` smallint(5) unsigned NOT NULL auto_increment,
  `rankType` tinyint(1) unsigned NOT NULL default '0',
  `rankName` varchar(255) NOT NULL default '',
  `rankGfx` text NOT NULL,
  `rankPosts` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`rankID`),
  KEY `rank_type` (`rankType`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Table structure for table tbb2_search_results
#

CREATE TABLE `tbb2_search_results` (
  `search_id` varchar(32) NOT NULL default '',
  `session_id` varchar(32) NOT NULL default '',
  `search_last_access` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `search_results` text NOT NULL,
  PRIMARY KEY  (`search_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Table structure for table tbb2_sessions
#

CREATE TABLE `tbb2_sessions` (
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
# Table structure for table tbb2_smilies
#

CREATE TABLE `tbb2_smilies` (
  `SmileyID` smallint(5) unsigned NOT NULL auto_increment,
  `SmileyType` tinyint(1) unsigned NOT NULL default '0',
  `SmileyFileName` varchar(255) NOT NULL default '',
  `SmileySynonym` varchar(255) NOT NULL default '',
  `SmileyStatus` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`SmileyID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Table structure for table tbb2_topics
#

CREATE TABLE `tbb2_topics` (
  `topicID` mediumint(8) unsigned NOT NULL auto_increment,
  `forumID` mediumint(8) unsigned NOT NULL default '0',
  `posterID` mediumint(8) unsigned NOT NULL default '0',
  `topicStatus` tinyint(1) unsigned NOT NULL default '0',
  `topicIsPinned` tinyint(1) unsigned NOT NULL default '0',
  `smileyID` smallint(5) unsigned NOT NULL default '0',
  `topicRepliesCounter` mediumint(8) unsigned NOT NULL default '0',
  `topicViewsCounter` mediumint(8) unsigned NOT NULL default '0',
  `topicHasPoll` tinyint(1) unsigned NOT NULL default '0',
  `topicFirstPostID` mediumint(8) unsigned NOT NULL default '0',
  `topicLastPostID` mediumint(8) unsigned NOT NULL default '0',
  `topicMovedID` mediumint(8) unsigned NOT NULL default '0',
  `topicPostTime` int(10) unsigned NOT NULL default '0',
  `topicTitle` varchar(255) NOT NULL default '',
  `topicGuestNick` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`topicID`),
  KEY `forum_id_topic_id` (`forumID`,`topicID`),
  KEY `poster_id` (`posterID`),
  KEY `topic_moved_id` (`topicMovedID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Table structure for table tbb2_topics_subscriptions
#

CREATE TABLE `tbb2_topics_subscriptions` (
  `TopicID` mediumint(8) unsigned NOT NULL default '0',
  `UserID` mediumint(8) unsigned NOT NULL default '0',
  KEY `topic_id` (`TopicID`),
  KEY `user_id_topic_id` (`UserID`,`TopicID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Table structure for table tbb2_users
#

CREATE TABLE `tbb2_users` (
  `userID` mediumint(8) unsigned NOT NULL auto_increment,
  `userStatus` tinyint(1) unsigned NOT NULL default '0',
  `userIsAdmin` tinyint(1) unsigned NOT NULL default '0',
  `userIsSupermod` tinyint(1) unsigned NOT NULL default '0',
  `userHash` varchar(32) NOT NULL default '',
  `userNick` varchar(255) NOT NULL default '',
  `userEmail` varchar(255) NOT NULL default '',
  `userPassword` varchar(255) NOT NULL default '',
  `userPasswordSalt` varchar(255) default NULL,
  `userNewPassword` varchar(255) default NULL,
  `userNewPasswordSalt` varchar(255) default NULL,
  `userPostsCounter` mediumint(8) unsigned NOT NULL default '0',
  `userRegistrationTimestamp` int(10) unsigned NOT NULL default '0',
  `userSignature` text NOT NULL,
  `user_group_id` mediumint(8) unsigned NOT NULL default '0',
  `user_special_status` mediumint(8) unsigned NOT NULL default '0',
  `userLastAction` int(10) unsigned NOT NULL default '0',
  `rankID` smallint(5) unsigned NOT NULL default '0',
  `userAvatarAddress` varchar(255) NOT NULL default '',
  `user_tz` varchar(255) NOT NULL default 'gmt',
  `userReceiveEmails` tinyint(1) unsigned NOT NULL default '1',
  `userHideEmail` tinyint(1) unsigned NOT NULL default '0',
  `userIsLocked` tinyint(1) unsigned NOT NULL default '0',
  `userMemo` text NOT NULL,
  `user_auth_profile_notes` tinyint(1) unsigned NOT NULL default '2',
  `user_language` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`userID`),
  KEY `user_last_action` (`userLastAction`),
  KEY `user_rank_id` (`rankID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Table structure for table tbb2_users_locks
#

CREATE TABLE `tbb2_users_locks` (
  `user_id` mediumint(8) unsigned NOT NULL default '0',
  `lock_type` tinyint(1) unsigned NOT NULL default '0',
  `lock_start_time` int(10) unsigned NOT NULL default '0',
  `lock_dur_time` int(10) unsigned NOT NULL default '0',
  KEY `user_id` (`user_id`),
  KEY `lock_type_user_id` (`lock_type`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Table structure for table tbb2_wio
#

CREATE TABLE `tbb2_wio` (
  `wio_session_id` varchar(32) NOT NULL default '',
  `wio_user_id` mediumint(8) unsigned NOT NULL default '0',
  `wio_last_action` int(10) unsigned NOT NULL default '0',
  `wio_last_location` varchar(10) NOT NULL default '',
  `wio_is_ghost` tinyint(1) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
