#
# Standardwerte fuer MySQL. tblprefix. wird autoamtisch ersetzt
#

#
# Tabelle "config"
#
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('admin_rank_pic','images/rankpics/admin.gif');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('allow_pms_bbcode','1');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('allow_pms_htmlcode','0');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('allow_pms_rconfirmation','1');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('allow_pms_signature','1');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('allow_pms_smilies','1');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('allow_select_lng','1');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('allow_select_style','1');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('allow_select_tpl','1');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('allow_sig_bbcode','1');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('allow_sig_html','0');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('allow_sig_smilies','1');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('avatar_image_height','64');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('avatar_image_width','64');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('board_address','');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('board_email_address','');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('board_logo','');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('board_name','My Community');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('email_signature','');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('enable_avatar_upload','1');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('enable_avatars','1');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('enable_email_functions','1');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('enable_email_formular','1');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('enable_file_upload','1');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('enable_gzip','1');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('enable_news_module','0');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('enable_outbox','1');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('enable_pms','1');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('enable_registration','1');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('enable_sig','1');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('enable_topic_subscription','1');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('enable_wio','1');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('guests_enter_board','1');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('max_avatar_file_size','10');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('max_latest_posts','5');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('maximum_pms','15');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('maximum_pms_folders','2');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('maximum_registrations',-'1');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('maximum_sig_length','1000');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('mod_rank_pic','images/rankpics/mod.gif');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('newest_user_id','0');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('newest_user_nick','');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('news_forum','0');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('online_users_record','');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('path_to_forum','');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('posts_per_page','10');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('require_accept_boardrules','1');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('search_status','2');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('show_boardstats_forumindex','1');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('show_latest_posts_forumindex','1');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('show_news_forumindex','1');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('show_techstats','1');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('show_wio_forumindex','1');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('sr_timeout','10');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('srgc_probability','10');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('standard_language','ts_german');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('standard_style','ts_tbb2_standard.css');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('standard_tpl','ts_tbb2_standard');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('standard_tz','gmt');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('topics_per_page','15');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('verify_email_address','0');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('dataversion','');
INSERT INTO tblprefix.config (config_name,config_value) VALUES ('wio_timeout','10');


INSERT INTO tblprefix.ranks (rank_name,rank_type,rank_posts) VALUES ('User','0','0');

#
# Tabelle "smilies"
#
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('0','images/smilies/standard_smile.gif',':-)','1');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('0','images/smilies/standard_wink.gif',';-)','1');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('0','images/smilies/standard_razz.gif',':-P','1');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('0','images/smilies/standard_none.gif',':-|','1');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('0','images/smilies/standard_eek.gif',':eek:','1');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('0','images/smilies/standard_biggrin.gif',':-D','1');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('0','images/smilies/standard_cool.gif',':cool:','1');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('0','images/smilies/standard_sad.gif',':-(','1');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('0','images/smilies/standard_cry.gif',':cry:','1');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('0','images/smilies/standard_dead.gif',':dead:','1');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('0','images/smilies/standard_yes.gif',':yes:','1');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('0','images/smilies/standard_laugh.gif',':laugh:','1');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('0','images/smilies/standard_mad.gif',':mad:','1');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('0','images/smilies/standard_no.gif',':no:','1');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('0','images/smilies/standard_rolleyes.gif',':rolleyes:','1');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('0','images/smilies/standard_shy.gif',':shy:','1');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('0','images/smilies/standard_embarrassed.gif',':-O','1');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('0','images/smilies/standard_smilewinkgrin.gif',':smilewinkgrin:','1');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('0','images/smilies/standard_uhoh.gif',':uhoh:','1');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('0','images/smilies/standard_confused.gif',':confused:','1');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('0','images/smilies/standard_sleep.gif',':sleep:','1');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('0','images/smilies/standard_upset.gif',':upset:','1');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('1','images/postpics/note.gif','','0');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('1','images/postpics/attention.gif','','0');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('1','images/postpics/bad.gif','','0');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('1','images/postpics/cool.gif','','0');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('1','images/postpics/good.gif','','0');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('1','images/postpics/grin.gif','','0');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('1','images/postpics/idea.gif','','0');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('1','images/postpics/mad.gif','','0');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('1','images/postpics/question.gif','','0');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('1','images/postpics/sad.gif','','0');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('1','images/postpics/shy.gif','','0');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('1','images/postpics/smile.gif','','0');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('1','images/postpics/wink.gif','','0');
INSERT INTO tblprefix.smilies (smiley_type,smiley_gfx,smiley_synonym,smiley_status) VALUES ('1','images/postpics/arrow.gif','','0');