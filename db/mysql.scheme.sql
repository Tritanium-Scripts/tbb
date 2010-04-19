#
# Datenbankstruktur fuer MySQL
# tblprefix. wird durch das entsprechende ersetzt
#

# Tabelle 'config' fuer Konfigurationsdaten
CREATE TABLE IF NOT EXISTS tblprefix.config (
	config_name VARCHAR(255) DEFAULT '' NOT NULL,
	config_value VARCHAR(255) DEFAULT '' NOT NULL
);

CREATE TABLE IF NOT EXISTS tblprefix.forums (
	forum_id MEDIUMINT(8) UNSIGNED AUTO_INCREMENT,
	cat_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	order_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	forum_name VARCHAR(255) DEFAULT '' NOT NULL,
	forum_description VARCHAR(255) DEFAULT '' NOT NULL,
	forum_topics_counter MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	forum_posts_counter MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	forum_last_post_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	forum_enable_bbcode TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	forum_enable_htmlcode TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	forum_enable_smilies TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	forum_is_moderated TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	forum_add_last_posts TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	auth_members_view_forum TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	auth_members_post_topic TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	auth_members_post_reply TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	auth_members_post_poll TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	auth_members_edit_posts TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	auth_guests_view_forum TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	auth_guests_post_topic TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	auth_guests_post_reply TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	auth_guests_post_poll TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	PRIMARY KEY (forum_id),
	KEY cat_id (cat_id),
	KEY order_id (order_id)
);

CREATE TABLE IF NOT EXISTS tblprefix.users (
	user_id MEDIUMINT(8) UNSIGNED AUTO_INCREMENT,
	user_status TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	user_is_admin TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	user_is_supermod TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	user_hash VARCHAR(32) DEFAULT '' NOT NULL,
	user_nick VARCHAR(255) DEFAULT '' NOT NULL,
	user_email VARCHAR(255) DEFAULT '' NOT NULL,
	user_pw VARCHAR(32) DEFAULT '' NOT NULL,
	user_posts MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	user_regtime DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
	user_hp VARCHAR(255) DEFAULT '' NOT NULL,
	user_icq VARCHAR(255) DEFAULT '' NOT NULL,
	user_aim VARCHAR(255) DEFAULT '' NOT NULL,
	user_yahoo VARCHAR(255) DEFAULT '' NOT NULL,
	user_msn VARCHAR(255) DEFAULT '' NOT NULL,
	user_signature VARCHAR(255) DEFAULT '' NOT NULL,
	user_group_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	user_special_status MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	user_interests VARCHAR(255) DEFAULT '' NOT NULL,
	user_realname VARCHAR(255) DEFAULT '' NOT NULL,
	user_location VARCHAR(255) DEFAULT '' NOT NULL,
	user_last_action INT(10) UNSIGNED DEFAULT '0' NOT NULL,
	PRIMARY KEY (user_id),
	KEY user_last_action (user_last_action)
);

CREATE TABLE IF NOT EXISTS tblprefix.wio (
	wio_session_id VARCHAR(32) NOT NULL DEFAULT '',
	wio_user_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	wio_last_action INT(10) UNSIGNED DEFAULT '0' NOT NULL,
	wio_last_location VARCHAR(10) DEFAULT '' NOT NULL,
	wio_is_ghost TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL
);

CREATE TABLE IF NOT EXISTS tblprefix.cats (
	cat_id MEDIUMINT(5) UNSIGNED AUTO_INCREMENT,
	parent_id MEDIUMINT(5) UNSIGNED DEFAULT '0' NOT NULL,
	order_id MEDIUMINT(5) UNSIGNED DEFAULT '0' NOT NULL,
	cat_name VARCHAR(255) DEFAULT '' NOT NULL,
	cat_description VARCHAR(255),
	PRIMARY KEY (cat_id),
	KEY parent_id (parent_id),
	KEY order_id (order_id)
);

CREATE TABLE IF NOT EXISTS tblprefix.forums_auth (
	forum_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	auth_type TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	auth_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	auth_view_forum TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	auth_post_topic TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	auth_post_reply TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	auth_post_poll TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	auth_edit_posts TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	auth_is_mod TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	KEY forum_id (forum_id)
);

CREATE TABLE IF NOT EXISTS tblprefix.posts (
	post_id MEDIUMINT(8) UNSIGNED AUTO_INCREMENT,
	topic_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	forum_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	poster_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	post_time DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
	post_ip VARCHAR(12) DEFAULT '' NOT NULL,
	post_pic SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
	post_enable_bbcode TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	post_enable_smilies  TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	post_enable_html TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	post_show_sig TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	post_guest_nick VARCHAR(15) DEFAULT '' NOT NULL,
	PRIMARY KEY (post_id),
	KEY topic_id (topic_id),
	KEY forum_id (forum_id),
	KEY poster_id (poster_id)
);

CREATE TABLE IF NOT EXISTS tblprefix.posts_text (
	post_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	post_title VARCHAR(255) DEFAULT '' NOT NULL,
	post_text TEXT DEFAULT '' NOT NULL,
	KEY post_id (post_id)
);

CREATE TABLE IF NOT EXISTS tblprefix.topics (
	topic_id MEDIUMINT(8) UNSIGNED AUTO_INCREMENT,
	forum_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	poster_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	topic_status TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	topic_is_pinned TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	topic_pic SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
	topic_replies_counter MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	topic_views_counter MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	topic_poll TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	topic_first_post_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	topic_last_post_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	topic_is_moved TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	topic_post_time DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
	topic_title VARCHAR(255) DEFAULT '' NOT NULL,
	topic_guest_nick VARCHAR(255) DEFAULT '' NOT NULL,
	PRIMARY KEY (topic_id),
	KEY forum_id_topic_id (forum_id,topic_id),
	KEY poster_id (poster_id)
);

CREATE TABLE IF NOT EXISTS tblprefix.smilies (
	smiley_id SMALLINT(5) UNSIGNED AUTO_INCREMENT,
	smiley_type TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	smiley_gfx VARCHAR(255) DEFAULT '' NOT NULL,
	smiley_synonym VARCHAR(255) DEFAULT '' NOT NULL,
	smiley_status TINYINT(1) UNSIGNED DEFAULT '' NOT NULL,
	PRIMARY KEY (smiley_id)
);

CREATE TABLE IF NOT EXISTS tblprefix.pms (
	pm_id MEDIUMINT(8) UNSIGNED AUTO_INCREMENT,
	folder_id SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
	pm_from_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	pm_to_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	pm_read_status TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	pm_type TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	pm_subject VARCHAR(255) DEFAULT '' NOT NULL,
	pm_send_time DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
	pm_enable_bbcode TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	pm_enable_smilies TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	pm_enable_htmlcode TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	pm_show_sig TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	pm_request_rconfirmation TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	PRIMARY KEY (pm_id),
	KEY folder_id (folder_id),
	KEY pm_from_id (pm_from_id),
	KEY pm_to_id (pm_to_id)
);

CREATE TABLE IF NOT EXISTS tblprefix.pms_text (
	pm_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	pm_text text DEFAULT '' NOT NULL,
	KEY pm_id (pm_id)
);

CREATE TABLE IF NOT EXISTS tblprefix.pms_folders (
	folder_id SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
	user_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	folder_name VARCHAR(255) DEFAULT '' NOT NULL,
	KEY folder_id (folder_id),
	KEY user_id (user_id)
);

CREATE TABLE IF NOT EXISTS tblprefix.groups (
	group_id SMALLINT(5) UNSIGNED AUTO_INCREMENT,
	group_name VARCHAR(255) DEFAULT '' NOT NULL,
	PRIMARY KEY (group_id)
);

CREATE TABLE IF NOT EXISTS tblprefix.groups_members (
	group_id SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
	member_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	member_status TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	KEY group_id (group_id),
	KEY member_id (member_id),
	KEY group_id_member_id (group_id,member_id)
);

CREATE TABLE IF NOT EXISTS tblprefix.polls (
	poll_id MEDIUMINT(8) UNSIGNED AUTO_INCREMENT,
	topic_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	poll_title VARCHAR(255) DEFAULT '' NOT NULL,
	poll_votes MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	PRIMARY KEY (poll_id),
	KEY topic_id_poll_id (topic_id,poll_id)
);

CREATE TABLE IF NOT EXISTS tblprefix.polls_voters (
	poll_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	voter_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	KEY poll_id (poll_id),
	KEY voter_id_poll_id (voter_id,poll_id)
);

CREATE TABLE IF NOT EXISTS tblprefix.polls_options (
	poll_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	option_id SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
	option_title VARCHAR(255) DEFAULT '' NOT NULL,
	option_votes MEDIUMINT(8) DEFAULT '0' NOT NULL,
	KEY option_id (option_id),
	KEY poll_id_option_id (poll_id,option_id)
);