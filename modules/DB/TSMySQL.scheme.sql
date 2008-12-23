/*!40100 SET CHARACTER SET latin1*/;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ANSI'*/;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0*/;

CREATE TABLE /*!32312 IF NOT EXISTS*/ "/*TABLEPREFIX*/avatars" (
  "avatarID" smallint(5) unsigned NOT NULL auto_increment,
  "avatarAddress" varchar(255) NOT NULL default '',
  PRIMARY KEY  ("avatarID")
) /*!40100 DEFAULT CHARSET=utf8*/;

CREATE TABLE /*!32312 IF NOT EXISTS*/ "/*TABLEPREFIX*/cats" (
  "catID" mediumint(5) unsigned NOT NULL auto_increment,
  "catL" mediumint(5) NOT NULL default '0',
  "catR" mediumint(5) NOT NULL default '0',
  "catStandardStatus" tinyint(1) unsigned NOT NULL default '1',
  "catName" varchar(255) NOT NULL default '',
  "catDescription" varchar(255) NOT NULL default '',
  PRIMARY KEY  ("catID")
) /*!40100 DEFAULT CHARSET=utf8*/;

CREATE TABLE /*!32312 IF NOT EXISTS*/ "/*TABLEPREFIX*/config" (
  "configName" varchar(255) NOT NULL default '',
  "configValue" varchar(255) NOT NULL default '',
  PRIMARY KEY  ("configName")
) /*!40100 DEFAULT CHARSET=utf8*/;

CREATE TABLE /*!32312 IF NOT EXISTS*/ "/*TABLEPREFIX*/forums" (
  "forumID" mediumint(8) unsigned NOT NULL auto_increment,
  "catID" mediumint(8) unsigned NOT NULL default '0',
  "orderID" mediumint(8) unsigned NOT NULL default '0',
  "forumName" varchar(255) NOT NULL default '',
  "forumDescription" varchar(255) NOT NULL default '',
  "forumTopicsCounter" mediumint(8) unsigned NOT NULL default '0',
  "forumPostsCounter" mediumint(8) unsigned NOT NULL default '0',
  "forumLastPostID" mediumint(8) unsigned NOT NULL default '0',
  "forumEnableBBCode" tinyint(1) unsigned NOT NULL default '0',
  "forumEnableHtmlCode" tinyint(1) unsigned NOT NULL default '0',
  "forumEnableSmilies" tinyint(1) unsigned NOT NULL default '0',
  "forumEnableURITransformation" tinyint(1) unsigned NOT NULL default '0',
  "forumIsModerated" tinyint(1) unsigned NOT NULL default '0',
  "forumShowLatestPosts" tinyint(1) unsigned NOT NULL default '0',
  "authViewForumMembers" tinyint(1) unsigned NOT NULL default '0',
  "authPostTopicMembers" tinyint(1) unsigned NOT NULL default '0',
  "authPostReplyMembers" tinyint(1) unsigned NOT NULL default '0',
  "authPostPollMembers" tinyint(1) unsigned NOT NULL default '0',
  "authEditPostsMembers" tinyint(1) unsigned NOT NULL default '0',
  "authViewForumGuests" tinyint(1) unsigned NOT NULL default '0',
  "authPostTopicGuests" tinyint(1) unsigned NOT NULL default '0',
  "authPostReplyGuests" tinyint(1) unsigned NOT NULL default '0',
  "authPostPollGuests" tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  ("forumID"),
  KEY "catID" ("catID"),
  KEY "orderID" ("orderID")
) /*!40100 DEFAULT CHARSET=utf8*/;

CREATE TABLE /*!32312 IF NOT EXISTS*/ "/*TABLEPREFIX*/forums_auth" (
  "forumID" mediumint(8) unsigned NOT NULL default '0',
  "authType" tinyint(1) unsigned NOT NULL default '0',
  "authID" mediumint(8) unsigned NOT NULL default '0',
  "authViewForum" tinyint(1) unsigned NOT NULL default '0',
  "authPostTopic" tinyint(1) unsigned NOT NULL default '0',
  "authPostReply" tinyint(1) unsigned NOT NULL default '0',
  "authPostPoll" tinyint(1) unsigned NOT NULL default '0',
  "authEditPosts" tinyint(1) unsigned NOT NULL default '0',
  "authIsMod" tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY "forumID" ("forumID","authType","authID")
) /*!40100 DEFAULT CHARSET=utf8*/;

CREATE TABLE /*!32312 IF NOT EXISTS*/ "/*TABLEPREFIX*/groups" (
  "groupID" smallint(5) unsigned NOT NULL auto_increment,
  "groupName" varchar(255) NOT NULL default '',
  PRIMARY KEY  ("groupID")
) /*!40100 DEFAULT CHARSET=utf8*/;

CREATE TABLE /*!32312 IF NOT EXISTS*/ "/*TABLEPREFIX*/groups_members" (
  "groupID" smallint(5) unsigned NOT NULL default '0',
  "memberID" mediumint(8) unsigned NOT NULL default '0',
  "memberStatus" tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY ("groupID","memberID"),
  KEY "memberID" ("memberID")
) /*!40100 DEFAULT CHARSET=utf8*/;

CREATE TABLE /*!32312 IF NOT EXISTS*/ "/*TABLEPREFIX*/pms" (
  "pmID" mediumint(8) unsigned NOT NULL auto_increment,
  "folderID" smallint(5) unsigned NOT NULL default '0',
  "pmFromID" mediumint(8) unsigned NOT NULL default '0',
  "pmToID" mediumint(8) unsigned NOT NULL default '0',
  "pmIsRead" tinyint(1) unsigned NOT NULL default '0',
  "pmType" tinyint(1) unsigned NOT NULL default '0',
  "pmSubject" varchar(255) NOT NULL default '',
  "pmMessageText" mediumtext NOT NULL,
  "pmSendTimestamp" int(10) unsigned NOT NULL default '0',
  "pmEnableBBCode" tinyint(1) unsigned NOT NULL default '0',
  "pmEnableSmilies" tinyint(1) unsigned NOT NULL default '0',
  "pmEnableHtmlCode" tinyint(1) unsigned NOT NULL default '0',
  "pmShowSignature" tinyint(1) unsigned NOT NULL default '0',
  "pmRequestReadReceipt" tinyint(1) unsigned NOT NULL default '0',
  "pmGuestNick" varchar(255) NOT NULL default '',
  "pmIsReplied" tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  ("pmID"),
  KEY "folderID" ("folderID"),
  KEY "pmFromID" ("pmFromID"),
  KEY "pmToID" ("pmToID")
) /*!40100 DEFAULT CHARSET=utf8*/;

CREATE TABLE /*!32312 IF NOT EXISTS*/ "/*TABLEPREFIX*/pms_folders" (
  "folderID" smallint(5) unsigned NOT NULL default '0',
  "userID" mediumint(8) unsigned NOT NULL default '0',
  "folderName" varchar(255) NOT NULL default '',
  PRIMARY KEY "folderIDUserID" ("folderID","userID"),
  KEY "userID" ("userID")
) /*!40100 DEFAULT CHARSET=utf8*/;

CREATE TABLE /*!32312 IF NOT EXISTS*/ "/*TABLEPREFIX*/polls_options" (
  "optionID" smallint(5) unsigned NOT NULL default '0',
  "optionTitle" varchar(255) NOT NULL default '',
  "optionVotesCounter" mediumint(8) NOT NULL default '0',
  "topicID" mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  ("topicID","optionID"),
  KEY "optionID" ("optionID")
) /*!40100 DEFAULT CHARSET=utf8*/;

CREATE TABLE /*!32312 IF NOT EXISTS*/ "/*TABLEPREFIX*/polls_votes" (
  "voterID" mediumint(8) unsigned NOT NULL default '0',
  "topicID" mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  ("topicID","voterID"),
  KEY "voterID" ("voterID")
) /*!40100 DEFAULT CHARSET=utf8*/;

CREATE TABLE /*!32312 IF NOT EXISTS*/ "/*TABLEPREFIX*/posts" (
  "postID" mediumint(8) unsigned NOT NULL auto_increment,
  "topicID" mediumint(8) unsigned NOT NULL default '0',
  "forumID" mediumint(8) unsigned NOT NULL default '0',
  "posterID" mediumint(8) unsigned NOT NULL default '0',
  "postTimestamp" int(10) unsigned NOT NULL default '0',
  "postIP" varchar(15) NOT NULL default '',
  "smileyID" smallint(5) unsigned NOT NULL default '0',
  "postEnableBBCode" tinyint(1) unsigned NOT NULL default '0',
  "postEnableSmilies" tinyint(1) unsigned NOT NULL default '0',
  "postEnableHtmlCode" tinyint(1) unsigned NOT NULL default '0',
  "postShowSignature" tinyint(1) unsigned NOT NULL default '0',
  "postEnableURITransformation" tinyint(1) unsigned NOT NULL default '0',
  "postShowEditings" tinyint(1) unsigned NOT NULL default '0',
  "postGuestNick" varchar(15) NOT NULL default '',
  "postEditedCounter" smallint(5) unsigned NOT NULL default '0',
  "postLastEditorNick" varchar(255) NOT NULL default '',
  "postTitle" varchar(255) NOT NULL default '',
  "postText" mediumtext NOT NULL,
  PRIMARY KEY  ("postID"),
  KEY "topicID" ("topicID"),
  KEY "posterID" ("posterID"),
  KEY "forumIDPostID" ("forumID","postID"),
  KEY "postTimestamp" ("postTimestamp")
) /*!40100 DEFAULT CHARSET=utf8*/;

CREATE TABLE /*!32312 IF NOT EXISTS*/ "/*TABLEPREFIX*/profile_fields" (
  "fieldID" smallint(5) unsigned NOT NULL auto_increment,
  "fieldName" varchar(255) NOT NULL default '',
  "fieldType" tinyint(1) unsigned NOT NULL default '0',
  "fieldIsRequired" tinyint(1) unsigned NOT NULL default '0',
  "fieldShowRegistration" tinyint(1) unsigned NOT NULL default '0',
  "fieldShowMemberlist" tinyint(1) unsigned NOT NULL default '0',
  "fieldLink" varchar(255) NOT NULL default '',
  "fieldData" mediumtext NOT NULL,
  "fieldRegexVerification" varchar(255) NOT NULL default '',
  "fieldVarName" varchar(255) NOT NULL default '',
  "fieldIsLocked" tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  ("fieldID"),
  UNIQUE "fieldVarName" ("fieldVarName")
) /*!40100 DEFAULT CHARSET=utf8*/;

CREATE TABLE /*!32312 IF NOT EXISTS*/ "/*TABLEPREFIX*/profile_fields_data" (
  "fieldID" smallint(5) unsigned NOT NULL default '0',
  "userID" mediumint(8) unsigned NOT NULL default '0',
  "fieldValue" mediumtext NOT NULL,
  PRIMARY KEY ("fieldID","userID"),
  KEY "userID" ("userID")
) /*!40100 DEFAULT CHARSET=utf8*/;

CREATE TABLE /*!32312 IF NOT EXISTS*/ "/*TABLEPREFIX*/profile_notes" (
  "noteID" mediumint(8) unsigned NOT NULL auto_increment,
  "userID" mediumint(8) unsigned NOT NULL default '0',
  "profileID" mediumint(8) unsigned NOT NULL default '0',
  "noteTimestamp" int(10) unsigned NOT NULL default '0',
  "noteIsPublic" tinyint(1) unsigned NOT NULL default '0',
  "noteText" mediumtext NOT NULL,
  PRIMARY KEY  ("noteID"),
  KEY "userIDProfileID" ("userID","profileID")
) /*!40100 DEFAULT CHARSET=utf8*/;

CREATE TABLE /*!32312 IF NOT EXISTS*/ "/*TABLEPREFIX*/ranks" (
  "rankID" smallint(5) unsigned NOT NULL auto_increment,
  "rankType" tinyint(1) unsigned NOT NULL default '0',
  "rankName" varchar(255) NOT NULL default '',
  "rankGfx" mediumtext NOT NULL,
  "rankPosts" mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  ("rankID"),
  KEY "rankType" ("rankType")
) /*!40100 DEFAULT CHARSET=utf8*/;

CREATE TABLE /*!32312 IF NOT EXISTS*/ "/*TABLEPREFIX*/search_results" (
  "searchID" varchar(32) NOT NULL default '',
  "sessionID" varchar(32) NOT NULL default '',
  "searchLastAccess" timestamp NOT NULL default '0000-00-00 00:00:00',
  "searchResults" mediumtext NOT NULL,
  PRIMARY KEY  ("searchID")
) /*!40100 DEFAULT CHARSET=utf8*/;

CREATE TABLE /*!32312 IF NOT EXISTS*/ "/*TABLEPREFIX*/sessions" (
  "sessionID" varchar(32) NOT NULL default '',
  "sessionLastUpdate" timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  "sessionData" mediumtext NOT NULL,
  "sessionUserID" mediumint(8) unsigned NOT NULL default '0',
  "sessionIsGhost" tinyint(1) unsigned NOT NULL default '0',
  "sessionLastLocation" varchar(255) NOT NULL default 'forumindex',
  PRIMARY KEY  ("sessionID"),
  KEY "sessionLastUpdate" ("sessionLastUpdate")
) /*!40100 DEFAULT CHARSET=utf8*/;

CREATE TABLE /*!32312 IF NOT EXISTS*/ "/*TABLEPREFIX*/smilies" (
  "smileyID" smallint(5) unsigned NOT NULL auto_increment,
  "smileyType" tinyint(1) unsigned NOT NULL default '0',
  "smileyFileName" varchar(255) NOT NULL default '',
  "smileySynonym" varchar(255) NOT NULL default '',
  "smileyStatus" tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  ("smileyID")
) /*!40100 DEFAULT CHARSET=utf8*/;

CREATE TABLE /*!32312 IF NOT EXISTS*/ "/*TABLEPREFIX*/topics" (
  "topicID" mediumint(8) unsigned NOT NULL auto_increment,
  "forumID" mediumint(8) unsigned NOT NULL default '0',
  "posterID" mediumint(8) unsigned NOT NULL default '0',
  "topicIsClosed" tinyint(1) unsigned NOT NULL default '0',
  "topicIsPinned" tinyint(1) unsigned NOT NULL default '0',
  "smileyID" smallint(5) unsigned NOT NULL default '0',
  "topicRepliesCounter" mediumint(8) unsigned NOT NULL default '0',
  "topicViewsCounter" mediumint(8) unsigned NOT NULL default '0',
  "topicHasPoll" tinyint(1) unsigned NOT NULL default '0',
  "topicFirstPostID" mediumint(8) unsigned NOT NULL default '0',
  "topicLastPostID" mediumint(8) unsigned NOT NULL default '0',
  "topicMovedID" mediumint(8) unsigned NOT NULL default '0',
  "topicMovedTimestamp" int(10) unsigned NOT NULL default '0',
  "topicPostTimestamp" int(10) unsigned NOT NULL default '0',
  "topicTitle" varchar(255) NOT NULL default '',
  "topicGuestNick" varchar(255) NOT NULL default '',
  "topicPollTitle" varchar(255) NOT NULL default '',
  "topicPollVotesCounter" mediumint(8) unsigned NOT NULL default '0',
  "topicPollStartTimestamp" int(10) unsigned NOT NULL default '0',
  "topicPollEndTimestamp" int(10) unsigned NOT NULL default '0',
  "topicPollGuestsVote" tinyint(1) unsigned NOT NULL default '0',
  "topicPollGuestsViewResults" tinyint(1) unsigned NOT NULL default '1',
  "topicPollShowResultsAfterEnd" tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  ("topicID"),
  KEY "forumIDTopicID" ("forumID","topicID"),
  KEY "posterID" ("posterID"),
  KEY "topicMovedID" ("topicMovedID"),
  KEY "topicLastPostID" ("topicLastPostID")
) /*!40100 DEFAULT CHARSET=utf8*/;

CREATE TABLE /*!32312 IF NOT EXISTS*/ "/*TABLEPREFIX*/topics_subscriptions" (
  "topicID" mediumint(8) unsigned NOT NULL default '0',
  "userID" mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY ("topicID","userID"),
  KEY "userID" ("userID")
) /*!40100 DEFAULT CHARSET=utf8*/;

CREATE TABLE /*!32312 IF NOT EXISTS*/ "/*TABLEPREFIX*/users" (
  "userID" mediumint(8) unsigned NOT NULL auto_increment,
  "userIsActivated" tinyint(1) unsigned NOT NULL default '0',
  "userIsAdmin" tinyint(1) unsigned NOT NULL default '0',
  "userIsSupermod" tinyint(1) unsigned NOT NULL default '0',
  "userHash" varchar(32) NOT NULL default '',
  "userNick" varchar(255) NOT NULL default '',
  "userEmailAddress" varchar(255) NOT NULL default '',
  "userPassword" varchar(255) NOT NULL default '',
  "userPasswordSalt" varchar(255) NOT NULL default '',
  "userNewPassword" varchar(255) NOT NULL default '',
  "userNewPasswordSalt" varchar(255) NOT NULL default '',
  "userPostsCounter" mediumint(8) unsigned NOT NULL default '0',
  "userRegistrationTimestamp" int(10) unsigned NOT NULL default '0',
  "userSignature" mediumtext NOT NULL,
  "groupID" mediumint(8) unsigned NOT NULL default '0',
  "userLastAction" int(10) unsigned NOT NULL default '0',
  "userLastVisit" int(10) unsigned NOT NULL default '0',
  "rankID" smallint(5) unsigned NOT NULL default '0',
  "userAvatarAddress" varchar(255) NOT NULL default '',
  "userTimeZone" varchar(255) NOT NULL default 'gmt',
  "userReceiveEmails" tinyint(1) unsigned NOT NULL default '1',
  "userHideEmailAddress" tinyint(1) unsigned NOT NULL default '0',
  "userIsLocked" tinyint(1) unsigned NOT NULL default '0',
  "userMemo" mediumtext NOT NULL,
  "userAuthProfileNotes" tinyint(1) unsigned NOT NULL default '2',
  "userLanguage" varchar(255) NOT NULL default '',
  "userNotifyNewPM" tinyint(1) unsigned NOT NULL default '0',
  "userLockStartTimestamp" int(10) unsigned NOT NULL default '0',
  "userLockEndTimestamp" int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  ("userID"),
  KEY "userLastAction" ("userLastAction"),
  KEY "rankID" ("rankID")
) /*!40100 DEFAULT CHARSET=utf8*/;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE*/;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS*/;