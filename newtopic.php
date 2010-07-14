<?

/* newtopic.php - Erstellt ein neues Thema (c) 2001-2002 Tritanium Scripts */

require_once("auth.php");

if(!$forum_data = get_forum_data($forum_id)) die('Error loading forum data!');

$right = 0;
if($user_logged_in != 1) {
	if($forum_data['rights'][7] == 1) $right = 1;
	else {
		echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">$forum_data[name]</a>\t".$lng['No_access']);
		echo get_message('nli','<br>'.sprintf($lng['links']['register_or_login'],"<a class=\"norm\" href=\"index.php?faction=register$MYSID2\">",'</a>',"<a class=\"norm\" href=\"index.php?faction=login$MYSID2\">",'</a>'));
	}
}
else {
	if($user_data[status] == 4) {
		echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">$forum_data[name]</a>\t".$lng['No_access']);
		echo get_message('banned');
	}
	elseif(check_right($forum_id,1) != 1) {
		echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">$forum_data[name]</a>\t".$lng['templates']['na'][0]);
		echo get_message('na');
	}
	else $right = 1;
}

if($right == 1) {
	$showformular = 1;
	$post = trim(mutate($post)); $title = trim(mutate($title)); // Beitrag und Titel von unnötigen Leerzeichen befreien und alle Spezialzeichen umwandeln
	if($save == "yes" && !$preview) {
		if($title == "") $error = $lng['newtopic']['error']['no_title'];
		elseif($user_logged_in != 1 && trim($nli_name) == '' && $config['nli_must_enter_name'] == 1) {
			$error = $lng['newtopic']['error']['Please_enter_a_name'];
		}
		else {
			$showformular = 0;
			$new_id = myfile("foren/$forum_id-ltopic.xbb"); $new_id = $new_id[0]+1; // Neue Topic-ID festlegen
			$post = nlbr($post); // \r\n's des Beitrages in <br>s umwandeln
			$datum = mydate(); // Datum festlegen

			if($user_logged_in == 1) $user_info = $user_id;
			else {
				if($nli_name == '') $nli_name = $lng['Guest'];
				$user_info = "0$nli_name";
			}

			$towrite = "1\t$title\t$user_info\t$tsmilie\t".$config['notify_new_replies']."\t".time()."\t0\t\t\t\t\t\t\t\n"."1\t$user_info\t$datum\t$post\t$REMOTE_ADDR\t$show_signatur\t$tsmilie\t$smilies\t$use_upbcode\t$use_htmlcode\t\t\t\r\n"; // Das zu Schreibende "vorformatieren"
			myfwrite("foren/$forum_id-threads.xbb","$new_id\r\n","a"); myfwrite("foren/$forum_id-$new_id.xbb",$towrite,"w"); myfwrite("foren/$forum_id-ltopic.xbb",$new_id,"w"); // Alle Daten schreiben
			increase_topic_number($forum_id); increase_posts_number($forum_id); increase_user_posts($user_id); update_last_post($forum_id,$datum,$user_info,$new_id,$tsmilie); // Posts und Themen des Forums um jeweils 1 erhöhen, außerdem letzten Beitrag des Forums updaten
			if($forum_data['rights'][6] == 1) {
				update_last_posts($forum_id,$new_id,$user_info,$datum);
				update_today_posts($forum_id,$new_id,$user_info,$datum);
			}

			// Hier beginnt "Moderatoren benachrichtigen"
				if($forum_data['smstatus'] == 1) {
					$forum_mods = explode(",",$forum_data['mods']);
					$email_file = myfread($config['lng_folder']."/mails/new_topic_posted.dat");
					$email_file = str_replace('{TOPICLINK}',$config['address_to_forum']."/index.php?faction=readforum&mode=viewthread&forum_id=$forum_id&thread=$new_id",$email_file);
					$email_subject = sprintf($lng['mail_subjects']['new_topic_posted'],$forum_data['name']);
					for($i = 0; $i < sizeof($forum_mods); $i++) {
						$tosend = str_replace('{USERNAME}',get_user_name($forum_mods[$i]),$email_file);
						mymail(get_user_email($forum_mods[$i]),$email_subject,$tosend);
					}
				}
			// Hier endet "Moderatoren benachrichtigen"

			mylog("4","%1: Thema ($forum_id,$new_id) erstellt (IP: %2)");

			echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">$forum_data[name]</a>\t".$lng['newtopic']['Topic_posted']);
			echo get_message('topic_posted','<br>'.sprintf($lng['links']['new_topic'],"<a class=\"norm\" href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$new_id$MYSID2\" onfocus=\"this.blur()\">",'</a>').'<br>'.sprintf($lng['links']['topic_index'],"<a class=\"norm\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\" onfocus=\"this.blur()\">",'</a>').'<br>'.sprintf($lng['links']['forum_index'],"<a class=\"norm\" href=\"index.php$MYSID1\">",'</a>'));
		}
	}

	if($showformular == 1) {
		?>
			<script language="JavaScript">
			<!--
				function setsmile(Zeichen) {
				document.beitrag.post.value = document.beitrag.post.value + Zeichen;
				}
			//-->
			</script>
		<?
		echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">$forum_data[name]</a>\t".$lng['newtopic']['Post_Topic']);
		if($preview) {
			$preview_post = nlbr($post);
			if($use_htmlcode == 1 && $forum_data[htmlcode] == 1) $preview_post = demutate($preview_post);
			if($show_signatur == 1 && $user_data[signatur] != "") $signatur = "<br><br>-----------------------<br>".upbcode_signatur($user_data[signatur]); else $signatur = ""; // Konfiguration der Signaturanzeige
			if($smilies == 1) $preview_post = make_smilies($preview_post); // Falls Smilies aktiviert wurden, Text umwandeln
			if($use_upbcode == 1 && $forum_data[upbcode] == 1) $preview_post = upbcode($preview_post); // Falls UPB-Code aktiviert wurde, Text umwandeln
			?>
				<table class="tbl" width=100% border=0 cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
				<tr><th class="thnorm" align="left"><span class="thnorm"><?=$lng['Preview']?></span></th></tr>
				<tr><td class="td1"><span class="norm"><?=$preview_post.$signatur?></span></td></tr>
				</table><br>
			<?
		}

		// Die ganzen "checked" Teile machen
			if(!$preview || $show_signatur == 1) $checked['sig'] = " checked";
			if(!$preview || $smilies == 1) $checked['smilies'] = " checked";
			if(!$preview || $use_upbcode == 1) $checked['upbcode'] = " checked";
			if($use_htmlcode == 1) $checked['htmlcode'] = " checked";
			if($sendmail2 == 1) $checked['sendmail'] = " checked";
		?>
			<form method="post" action="index.php?faction=newtopic<?=$MYSID2?>" name="beitrag">
			<table width="100%" class="tbl" border="0" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
			<tr><th class="thnorm" align="left" colspan="2"><span class="thnorm"><?=$lng['newtopic']['Post_Topic']?></span></th></tr>
		<?
		if($user_logged_in != 1) {
			?>
				<tr>
				 <td class="td1" width="20%"><span class="norm"><b><?=$lng['Your_Name']?>:</b></span></td>
	 			 <td class="td1" width="80%"><input type="text" name="nli_name" value="<?=$nli_name?>"></td>
	 			</tr>
	 		<?
	 	}
	 	?>
			<tr>
			 <td class="td1" width="20%"><span class="norm"><b><?=$lng['Pic_for_this_post']?>:</b></span></td>
			 <td class="td1" width="100%" valign=top><? include("tsmilies.php") ?></td>
			</tr>
			<tr>
			 <td class="td1" width="20%"><span class="norm"><b><?=$lng['Title']?>:</b></span></td>
			 <td class="td1" width="80%"><input type="text" size=30 name="title" value="<?=$title?>">&nbsp;<span class="error"><?=$error?></span></td>
			</tr>
		<?
		if($forum_data['upbcode'] == 1) {
			?>
				<tr>
				 <td class="td1" width="20%" valign="top"><span class="norm"><b><?=$lng['TBB-Code']?>:</b></span></td>
	 			 <td class="td1" width="80%"><? include("forumcode.php") ?></td>
	 			</tr>
			<?
		}
		?>
			<tr>
			 <td class="td1" valign="top"><span class="norm"><b><?=$lng['Post']?>:</b></span><br><br><? include("smilies.php"); ?></font></td>
			 <td class="td1" width="80%"><textarea name="post" rows=10 cols=60><?=$post?></textarea></td>
			</tr>
			<? if($tspacing < 1) echo "<tr><td class=\"td1\" colspan=\"2\"><hr></td></tr>"; ?>
			<tr>
			 <td class="td1" width="20%" valign="top"><span class="norm"><b><?=$lng['Options']?>:</b></span></td>
			 <td class="td1" width="80%"><span class="norm"><input type="checkbox" name="smilies" value="1" onfocus="this.blur()"<?=$checked[smilies]?>> <?=$lng['Enable_smilies']?>
		<?
		if($user_logged_in == 1) {
			?>
				<br><input type="checkbox" name="show_signatur" value="1" onfocus="this.blur()"<?=$checked['sig']?>> <?=$lng['Show_signature']?>
			<?
		}
		if($forum_data['upbcode'] == 1) echo "<br><input type=\"checkbox\" name=\"use_upbcode\" value=\"1\" onfocus=\"this.blur()\"$checked[upbcode]> ".$lng['Enable_TBB_code'];
		if($forum_data['htmlcode'] == 1) echo "<br><input type=\"checkbox\" name=\"use_htmlcode\" value=\"1\" onfocus=\"this.blur()\"$checked[htmlcode]> ".$lng['Enable_HTML_code'];
		if($config['activate_mail'] == 1 && $config['notify_new_replies'] == 1 && $user_logged_in == 1) echo "<br><input type=\"checkbox\" name=\"sendmail2\" value=\"1\" onfocus=\"this.blur()\"$checked[sendmail]> ".$lng['newtopic']['Notify_new_reply'];
		?>
			</span></td>
			</tr>
			<input type="hidden" name="save" value="yes"><input type="hidden" name="forum_id" value="<?=$forum_id?>">
			</table><br><input type="submit" value="<?=$lng['newtopic']['Post_Topic']?>">&nbsp;&nbsp;&nbsp;<input type="submit" name="preview" value="<?=$lng['Preview']?>"></form></center>
		<?
	}
}

wio_set("newtopic"); // WIO konfigurieren

?>