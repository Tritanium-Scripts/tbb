<?

/* adminpanel.php - Administrationsübersicht (c) 2001-2002 Tritanium Scripts */

require_once("functions.php");
require_once("loadset.php");
require_once("auth.php");
ad();

if($user_logged_in != 1 || $user_data[status] != 1) {
	mylog("2","%1: Administrationszugriffversuch (IP: %2)");
	header("Location: ad_login.php?$HSID"); exit;
}
else {
	include("pageheader.php");
	echo navbar($lng['adminpanel']['Administration']);
	?>
		<table class="tbl" border="0" width="<?=$twidth?>" cellpadding="8" cellspacing="<?=$tspacing?>">
		<tr><th class="thnorm" colspan="2"><span class="thnorm"><?=$lng['adminpanel']['Administration']?></span></th></tr>
		<tr>
		 <td class="td1" valign="top" width="50%"><span class="norm"><a href="ad_forum.php<?=$MYSID1?>"><b><?=$lng['adminpanel']['Forums_Cats']?></b></a></span><br><span class="small">&nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="ad_forum.php?mode=forumview<?=$MYSID2?>"><?=$lng['adminpanel']['Forum_index']?></a><br>&nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="ad_forum.php?mode=newforum<?=$MYSID2?>"><?=$lng['adminpanel']['Add_new_forum']?></a><br>&nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="ad_forum.php?mode=viewkg<?=$MYSID2?>"><?=$lng['adminpanel']['Cat_index']?></a><br>&nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="ad_forum.php?mode=newkg<?=$MYSID2?>"><?=$lng['adminpanel']['Add_new_cat']?></a></span></td>
		 <td class="td1" valign="top" width="50%"><span class="norm"><a href="ad_user.php<?=$MYSID1?>"><b><?=$lng['adminpanel']['Members']?></b></a></span><br><span class="small">&nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="ad_user.php<?=$MYSID1?>"><?=$lng['adminpanel']['Membersearch']?></a><br>&nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="ad_user.php?mode=new<?=$MYSID2?>"><?=$lng['adminpanel']['Add_new_member']?></a></span></td>
		</tr>

		<tr>
		 <td class="td1" valign="top" width="50%"><span class="norm"><a href="ad_groups.php<?=$MYSID1?>"><b><?=$lng['adminpanel']['Groups']?></b></a></span><br><span class="small">&nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="ad_groups.php<?=$MYSID1?>"><?=$lng['adminpanel']['Group_index']?></a><br>&nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="ad_groups.php?mode=new<?=$MYSID2?>"><?=$lng['adminpanel']['Add_new_group']?></a></span></td>
		 <td class="td1" valign="top" width="50%"><span class="norm"><a href="ad_rank.php<?=$MYSID1?>"><b><?=$lng['adminpanel']['Ranking']?></b></a></span><br><span class="small">&nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="ad_rank.php<?=$MYSID1?>"><?=$lng['adminpanel']['Ranking_index']?></a><br>&nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="ad_rank.php?mode=new<?=$MYSID2?>"><?=$lng['adminpanel']['Add_new_rank']?></a></span></td>
		</tr>

		<tr>
		 <td class="td1" valign="top" width="50%"><span class="norm"><a href="ad_smilies.php<?=$MYSID1?>"><b><?=$lng['adminpanel']['Smilies']?></b></a></span><br><span class="small">&nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="ad_smilies.php<?=$MYSID1?>"><?=$lng['adminpanel']['Smilies_Emoticons_index']?></a><br>&nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="ad_smilies.php?mode=new<?=$MYSID2?>"><?=$lng['adminpanel']['Add_new_smiley']?></a><br>&nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="ad_smilies.php?mode=newt<?=$MYSID2?>"><?=$lng['adminpanel']['Add_new_emoticon']?></a></span></td>
		 <td class="td1" valign="top" width="50%"><span class="norm"><a href="ad_ip.php<?=$MYSID1?>"><b><?=$lng['adminpanel']['IP_bans']?></b></a></span><br><span class="small">&nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="ad_ip.php<?=$MYSID1?>"><?=$lng['adminpanel']['IP_bans_index']?></a><br>&nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="ad_ip.php?mode=new<?=$MYSID2?>"><?=$lng['adminpanel']['Add_IP_ban']?></a></span></td>
		</tr>

		<tr>
		 <td class="td1" valign="top" width="50%"><span class="norm"><a href="ad_censor.php<?=$MYSID1?>"><b><?=$lng['adminpanel']['Censor']?></b></a></span><br><span class="small">&nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="ad_censor.php<?=$MYSID1?>"><?=$lng['adminpanel']['Censor_index']?></a><br>&nbsp;&nbsp;&nbsp;&nbsp;<a href="ad_censor.php?mode=new<?=$MYSID2?>"><?=$lng['adminpanel']['Add_new_word']?></a></span></td>
		 <td class="td1" valign="top" width="50%"><span class="norm"><a href="ad_settings.php<?=$MYSID1?>"><b><?=$lng['adminpanel']['Settings']?></b></a></span><br><span class="small">&nbsp;&nbsp;&nbsp;&nbsp;<a class="small" href="ad_settings.php<?=$MYSID1?>"><?=$lng['adminpanel']['Edit_Settings']?></a><br>&nbsp;&nbsp;&nbsp;&nbsp;<a href="ad_settings.php?mode=readsetfile<?=$MYSID2?>"><?=$lng['adminpanel']['Read_in_settings']?></a></span></td>
		</tr>

		<tr>
		 <td class="td1" valign="top" width="50%"><span class="norm"><a href="ad_news.php<?=$MYSID1?>"><b><?=$lng['adminpanel']['Forumnews']?></b></a></span><br><span class="small"><?=$lng['adminpanel']['forumnews_description']?></span></td>
		 <td class="td1" valign="top" width="50%"><span class="norm"><a href="ad_newsletter.php<?=$MYSID1?>"><b><?=$lng['adminpanel']['Newsletter']?></b></a></span><br><span class="small"><?=$lng['adminpanel']['newsletter_description']?></span></td>
		</tr>

		<tr>
		 <td class="td1" valign="top" width="50%"><span class="norm"><a href="ad_emailist.php<?=$MYSID1?>"><b><?=$lng['adminpanel']['Emaillist']?></b></a></span><br><span class="small"><?=$lng['adminpanel']['emaillist_description']?></span></td>
		 <td class="td1" valign="top" width="50%"><span class="norm"><a href="ad_killposts.php<?=$MYSID1?>"><b><?=$lng['adminpanel']['Delete_old_topics']?></b></a></span><br><span class="small"><?=$lng['adminpanel']['delete_old_topics_description']?></span></td>
		</tr>
		</table>
	<?
	wio_set("ad");
	include("pagetail.php");
}
// E
?>