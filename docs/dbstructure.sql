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
  `avatar_id` smallint(5) unsigned NOT NULL auto_increment,
  `avatar_address` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`avatar_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Table structure for table tbb2_cats
#

CREATE TABLE `tbb2_cats` (
  `CatID` mediumint(5) unsigned NOT NULL auto_increment,
  `CatL` mediumint(5) NOT NULL default '0',
  `CatR` mediumint(5) NOT NULL default '0',
  `CatStandardStatus` tinyint(1) unsigned NOT NULL default '1',
  `CatName` varchar(255) NOT NULL default '',
  `CatDescription` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`CatID`)
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
  `ForumID` mediumint(8) unsigned NOT NULL auto_increment,
  `CatID` mediumint(8) unsigned NOT NULL default '0',
  `OrderID` mediumint(8) unsigned NOT NULL default '0',
  `ForumName` varchar(255) NOT NULL default '',
  `ForumDescription` varchar(255) NOT NULL default '',
  `ForumTopicsCounter` mediumint(8) unsigned NOT NULL default '0',
  `ForumPostsCounter` mediumint(8) unsigned NOT NULL default '0',
  `ForumLastPostID` mediumint(8) unsigned NOT NULL default '0',
  `ForumEnableBBCode` tinyint(1) unsigned NOT NULL default '0',
  `ForumEnableHtmlCode` tinyint(1) unsigned NOT NULL default '0',
  `ForumEnableSmilies` tinyint(1) unsigned NOT NULL default '0',
  `ForumEnableURITransformation` tinyint(1) unsigned NOT NULL default '0',
  `forum_is_moderated` tinyint(1) unsigned NOT NULL default '0',
  `forum_show_latest_posts` tinyint(1) unsigned NOT NULL default '0',
  `auth_members_view_forum` tinyint(1) unsigned NOT NULL default '0',
  `auth_members_post_topic` tinyint(1) unsigned NOT NULL default '0',
  `auth_members_post_reply` tinyint(1) unsigned NOT NULL default '0',
  `auth_members_post_poll` tinyint(1) unsigned NOT NULL default '0',
  `auth_members_edit_posts` tinyint(1) unsigned NOT NULL default '0',
  `GuestsAuthViewForum` tinyint(1) unsigned NOT NULL default '0',
  `auth_guests_post_topic` tinyint(1) unsigned NOT NULL default '0',
  `auth_guests_post_reply` tinyint(1) unsigned NOT NULL default '0',
  `auth_guests_post_poll` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ForumID`),
  KEY `cat_id` (`CatID`),
  KEY `order_id` (`OrderID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Table structure for table tbb2_forums_auth
#

CREATE TABLE `tbb2_forums_auth` (
  `ForumID` mediumint(8) unsigned NOT NULL default '0',
  `AuthType` tinyint(1) unsigned NOT NULL default '0',
  `AuthID` mediumint(8) unsigned NOT NULL default '0',
  `auth_view_forum` tinyint(1) unsigned NOT NULL default '0',
  `auth_post_topic` tinyint(1) unsigned NOT NULL default '0',
  `auth_post_reply` tinyint(1) unsigned NOT NULL default '0',
  `auth_post_poll` tinyint(1) unsigned NOT NULL default '0',
  `auth_edit_posts` tinyint(1) unsigned NOT NULL default '0',
  `AuthIsMod` tinyint(1) unsigned NOT NULL default '0',
  KEY `forum_id` (`ForumID`)
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
  `PMID` mediumint(8) unsigned NOT NULL auto_increment,
  `FolderID` smallint(5) unsigned NOT NULL default '0',
  `PMFromID` mediumint(8) unsigned NOT NULL default '0',
  `PMToID` mediumint(8) unsigned NOT NULL default '0',
  `PMIsRead` tinyint(1) unsigned NOT NULL default '0',
  `PMType` tinyint(1) unsigned NOT NULL default '0',
  `PMSubject` varchar(255) NOT NULL default '',
  `PMMessageText` text NOT NULL,
  `PMSendTimestamp` int(10) unsigned NOT NULL default '0',
  `pm_enable_bbcode` tinyint(1) unsigned NOT NULL default '0',
  `pm_enable_smilies` tinyint(1) unsigned NOT NULL default '0',
  `pm_enable_htmlcode` tinyint(1) unsigned NOT NULL default '0',
  `pm_show_sig` tinyint(1) unsigned NOT NULL default '0',
  `PMRequestReadReceipt` tinyint(1) unsigned NOT NULL default '0',
  `PMGuestNick` varchar(255) NOT NULL default '',
  `PMIsReplied` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`PMID`),
  KEY `folder_id` (`FolderID`),
  KEY `pm_from_id` (`PMFromID`),
  KEY `pm_to_id` (`PMToID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Table structure for table tbb2_pms_folders
#

CREATE TABLE `tbb2_pms_folders` (
  `FolderID` smallint(5) unsigned NOT NULL default '0',
  `UserID` mediumint(8) unsigned NOT NULL default '0',
  `FolderName` varchar(255) NOT NULL default '',
  KEY `folder_id` (`FolderID`),
  KEY `user_id` (`UserID`)
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
  `PostID` mediumint(8) unsigned NOT NULL auto_increment,
  `TopicID` mediumint(8) unsigned NOT NULL default '0',
  `ForumID` mediumint(8) unsigned NOT NULL default '0',
  `PosterID` mediumint(8) unsigned NOT NULL default '0',
  `PostTimestamp` int(10) unsigned NOT NULL default '0',
  `PostIP` varchar(15) NOT NULL default '',
  `SmileyID` smallint(5) unsigned NOT NULL default '0',
  `PostEnableBBCode` tinyint(1) unsigned NOT NULL default '0',
  `PostEnableSmilies` tinyint(1) unsigned NOT NULL default '0',
  `PostEnableHtmlCode` tinyint(1) unsigned NOT NULL default '0',
  `PostShowSignature` tinyint(1) unsigned NOT NULL default '0',
  `post_enable_urltransformation` tinyint(1) unsigned NOT NULL default '0',
  `post_show_editings` tinyint(1) unsigned NOT NULL default '0',
  `PostGuestNick` varchar(15) NOT NULL default '',
  `PostEditedCounter` smallint(5) unsigned NOT NULL default '0',
  `post_last_editor_id` mediumint(8) unsigned NOT NULL default '0',
  `PostTitle` varchar(255) NOT NULL default '',
  `PostText` text NOT NULL,
  PRIMARY KEY  (`PostID`),
  KEY `topic_id` (`TopicID`),
  KEY `forum_id` (`ForumID`),
  KEY `poster_id` (`PosterID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Table structure for table tbb2_profile_fields
#

CREATE TABLE `tbb2_profile_fields` (
  `FieldID` smallint(5) unsigned NOT NULL auto_increment,
  `FieldName` varchar(255) NOT NULL default '',
  `FieldType` tinyint(1) unsigned NOT NULL default '0',
  `FieldIsRequired` tinyint(1) unsigned NOT NULL default '0',
  `FieldShowRegistration` tinyint(1) unsigned NOT NULL default '0',
  `FieldShowMemberlist` tinyint(1) unsigned NOT NULL default '0',
  `FieldLink` varchar(255) NOT NULL default '',
  `FieldData` text NOT NULL,
  `FieldRegexVerification` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`FieldID`)
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
  `RankID` smallint(5) unsigned NOT NULL auto_increment,
  `RankType` tinyint(1) unsigned NOT NULL default '0',
  `RankName` varchar(255) NOT NULL default '',
  `RankGfx` text NOT NULL,
  `RankPosts` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`RankID`),
  KEY `rank_type` (`RankType`)
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
  `SessionID` varchar(32) NOT NULL default '',
  `SessionLastUpdate` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `SessionData` text NOT NULL,
  `SessionUserID` mediumint(8) unsigned NOT NULL default '0',
  `SessionIsGhost` tinyint(1) unsigned NOT NULL default '0',
  `SessionLastLocation` varchar(255) NOT NULL default 'forumindex',
  PRIMARY KEY  (`SessionID`),
  KEY `session_last_update` (`SessionLastUpdate`)
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
  `TopicID` mediumint(8) unsigned NOT NULL auto_increment,
  `ForumID` mediumint(8) unsigned NOT NULL default '0',
  `PosterID` mediumint(8) unsigned NOT NULL default '0',
  `TopicStatus` tinyint(1) unsigned NOT NULL default '0',
  `TopicIsPinned` tinyint(1) unsigned NOT NULL default '0',
  `SmileyID` smallint(5) unsigned NOT NULL default '0',
  `TopicRepliesCounter` mediumint(8) unsigned NOT NULL default '0',
  `TopicViewsCounter` mediumint(8) unsigned NOT NULL default '0',
  `TopicHasPoll` tinyint(1) unsigned NOT NULL default '0',
  `TopicFirstPostID` mediumint(8) unsigned NOT NULL default '0',
  `TopicLastPostID` mediumint(8) unsigned NOT NULL default '0',
  `TopicMovedID` mediumint(8) unsigned NOT NULL default '0',
  `TopicPostTime` int(10) unsigned NOT NULL default '0',
  `TopicTitle` varchar(255) NOT NULL default '',
  `TopicGuestNick` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`TopicID`),
  KEY `forum_id_topic_id` (`ForumID`,`TopicID`),
  KEY `poster_id` (`PosterID`),
  KEY `topic_moved_id` (`TopicMovedID`)
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
  `UserID` mediumint(8) unsigned NOT NULL auto_increment,
  `UserStatus` tinyint(1) unsigned NOT NULL default '0',
  `UserIsAdmin` tinyint(1) unsigned NOT NULL default '0',
  `UserIsSupermod` tinyint(1) unsigned NOT NULL default '0',
  `user_hash` varchar(32) NOT NULL default '',
  `UserNick` varchar(255) NOT NULL default '',
  `UserEmail` varchar(255) NOT NULL default '',
  `UserPassword` varchar(255) NOT NULL default '',
  `UserPasswordSalt` varchar(255) default NULL,
  `UserNewPassword` varchar(255) default NULL,
  `UserNewPasswordSalt` varchar(255) default NULL,
  `UserPostsCounter` mediumint(8) unsigned NOT NULL default '0',
  `UserRegistrationTimestamp` int(10) unsigned NOT NULL default '0',
  `UserSignature` text NOT NULL,
  `user_group_id` mediumint(8) unsigned NOT NULL default '0',
  `user_special_status` mediumint(8) unsigned NOT NULL default '0',
  `user_last_action` int(10) unsigned NOT NULL default '0',
  `RankID` smallint(5) unsigned NOT NULL default '0',
  `UserAvatarAddress` varchar(255) NOT NULL default '',
  `user_tz` varchar(255) NOT NULL default 'gmt',
  `user_new_pw` varchar(32) NOT NULL default '',
  `UserReceiveEmails` tinyint(1) unsigned NOT NULL default '1',
  `UserHideEmail` tinyint(1) unsigned NOT NULL default '0',
  `user_is_locked` tinyint(1) unsigned NOT NULL default '0',
  `user_memo` text NOT NULL,
  `user_auth_profile_notes` tinyint(1) unsigned NOT NULL default '2',
  `user_language` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`UserID`),
  KEY `user_last_action` (`user_last_action`),
  KEY `user_rank_id` (`RankID`)
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
