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
	forum_show_latest_posts TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
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
	user_regtime INT(10) UNSIGNED DEFAULT '0' NOT NULL,
	user_signature TEXT DEFAULT '' NOT NULL,
	user_group_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	user_special_status MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	user_last_action INT(10) UNSIGNED DEFAULT '0' NOT NULL,
	user_rank_id SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
	user_avatar_address VARCHAR(255) DEFAULT '' NOT NULL,
	user_tz VARCHAR(255) DEFAULT 'gmt' NOT NULL,
	user_new_pw VARCHAR(32) DEFAULT '' NOT NULL,
	user_receive_emails TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL,
	user_hide_email TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	user_is_locked TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	user_memo TEXT DEFAULT '' NOT NULL,
	user_auth_profile_notes TINYINT(1) UNSIGNED DEFAULT '2' NOT NULL,
	PRIMARY KEY (user_id),
	KEY user_last_action (user_last_action),
	KEY user_rank_id (user_rank_id)
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
	cat_l MEDIUMINT(5) DEFAULT '0' NOT NULL,
	cat_r MEDIUMINT(5) DEFAULT '0' NOT NULL,
	cat_standard_status TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL,
	cat_name VARCHAR(255) DEFAULT '' NOT NULL,
	cat_description VARCHAR(255) DEFAULT '' NOT NULL,
	PRIMARY KEY (cat_id)
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
	post_time INT(10) UNSIGNED DEFAULT '0' NOT NULL,
	post_ip VARCHAR(15) DEFAULT '' NOT NULL,
	post_pic SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
	post_enable_bbcode TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	post_enable_smilies  TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	post_enable_html TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	post_show_sig TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	post_guest_nick VARCHAR(15) DEFAULT '' NOT NULL,
	post_edited_counter SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
	post_last_editor_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	post_title VARCHAR(255) DEFAULT '' NOT NULL,
	post_text TEXT DEFAULT '' NOT NULL,
	PRIMARY KEY (post_id),
	KEY topic_id (topic_id),
	KEY forum_id (forum_id),
	KEY poster_id (poster_id)
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
	topic_moved_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	topic_post_time INT(10) UNSIGNED DEFAULT '0' NOT NULL,
	topic_title VARCHAR(255) DEFAULT '' NOT NULL,
	topic_guest_nick VARCHAR(255) DEFAULT '' NOT NULL,
	PRIMARY KEY (topic_id),
	KEY forum_id_topic_id (forum_id,topic_id),
	KEY poster_id (poster_id),
	KEY topic_moved_id (topic_moved_id)
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
	pm_text TEXT DEFAULT '' NOT NULL,
	pm_send_time INT(10) UNSIGNED DEFAULT '0' NOT NULL,
	pm_enable_bbcode TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	pm_enable_smilies TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	pm_enable_htmlcode TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	pm_show_sig TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	pm_request_rconfirmation TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	pm_guest_nick VARCHAR(255)  DEFAULT '' NOT NULL,
	PRIMARY KEY (pm_id),
	KEY folder_id (folder_id),
	KEY pm_from_id (pm_from_id),
	KEY pm_to_id (pm_to_id)
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
	KEY member_id_group_id (member_id,group_id)
);

CREATE TABLE IF NOT EXISTS tblprefix.polls (
	poll_id MEDIUMINT(8) UNSIGNED AUTO_INCREMENT,
	topic_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	poster_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	poll_title VARCHAR(255) DEFAULT '' NOT NULL,
	poll_votes MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	poll_guest_nick VARCHAR(255) DEFAULT '' NOT NULL,
	poll_start_time INT(10) UNSIGNED DEFAULT '0' NOT NULL,
	poll_end_time INT(10) UNSIGNED DEFAULT '0' NOT NULL,
	PRIMARY KEY (poll_id),
	KEY topic_id_poll_id (topic_id,poll_id),
	KEY poster_id (poster_id)
);

CREATE TABLE IF NOT EXISTS tblprefix.polls_votes (
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

CREATE TABLE IF NOT EXISTS tblprefix.topics_subscriptions (
	topic_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	user_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	KEY topic_id (topic_id),
	KEY user_id_topic_id (user_id,topic_id)
);

CREATE TABLE IF NOT EXISTS tblprefix.sessions (
	session_id VARCHAR(32) DEFAULT '' NOT NULL,
	session_last_update TIMESTAMP(14),
	session_data text DEFAULT '' NOT NULL,
	session_user_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	session_is_ghost TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	session_last_location VARCHAR(255) DEFAULT 'forumindex' NOT NULL,
	PRIMARY KEY (session_id),
	KEY session_last_update (session_last_update)
);

CREATE TABLE IF NOT EXISTS tblprefix.search_results (
	search_id VARCHAR(32) DEFAULT '' NOT NULL,
	session_id VARCHAR(32) DEFAULT '' NOT NULL,
	search_last_access TIMESTAMP(14),
	search_results text DEFAULT '' NOT NULL,
	PRIMARY KEY (search_id)
);

CREATE TABLE IF NOT EXISTS tblprefix.ranks (
	rank_id SMALLINT(5) UNSIGNED AUTO_INCREMENT,
	rank_type TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	rank_name VARCHAR(255) DEFAULT '' NOT NULL,
	rank_gfx TEXT DEFAULT '' NOT NULL,
	rank_posts MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	PRIMARY KEY (rank_id),
	KEY rank_type (rank_type)
);

CREATE TABLE IF NOT EXISTS tblprefix.avatars (
	avatar_id SMALLINT(5) UNSIGNED AUTO_INCREMENT,
	avatar_address VARCHAR(255) DEFAULT '' NOT NULL,
	PRIMARY KEY (avatar_id)
);

CREATE TABLE IF NOT EXISTS tblprefix.users_locks (
	user_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	lock_type TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	lock_start_time INT(10) UNSIGNED DEFAULT '0' NOT NULL,
	lock_dur_time INT(10) UNSIGNED DEFAULT '0' NOT NULL,
	KEY user_id (user_id),
	KEY lock_type_user_id (lock_type,user_id)
);

CREATE TABLE IF NOT EXISTS tblprefix.profile_fields (
	field_id SMALLINT(5) UNSIGNED AUTO_INCREMENT,
	field_name VARCHAR(255) DEFAULT '' NOT NULL,
	field_type TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	field_is_required TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	field_show_registration TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	field_show_memberlist TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	field_link VARCHAR(255) DEFAULT '' NOT NULL,
	field_data TEXT DEFAULT '' NOT NULL,
	field_regex_verification VARCHAR(255) DEFAULT '' NOT NULL,
	PRIMARY KEY (field_id)
);

CREATE TABLE IF NOT EXISTS tblprefix.profile_fields_data (
	field_id SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
	user_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	field_value TEXT DEFAULT '' NOT NULL,
	KEY field_id (field_id),
	KEY user_id_field_id (user_id,field_id)
);

CREATE TABLE IF NOT EXISTS tblprefix.profile_notes (
	note_id MEDIUMINT(8) UNSIGNED AUTO_INCREMENT,
	user_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	profile_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	note_time INT(10) UNSIGNED DEFAULT '0' NOT NULL,
	note_is_public TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	note_text TEXT DEFAULT '' NOT NULL,
	PRIMARY KEY (note_id),
	KEY user_id_profile_id (user_id,profile_id)
);

INSERT INTO tblprefix.cats (cat_id,cat_l,cat_r,cat_name) VALUES (1,1,2,'ROOT');