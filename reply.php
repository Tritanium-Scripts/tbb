<?

/* reply.php - Antworten erstellen (c) 2001-2002 Tritanium Scripts */

require_once("auth.php");

if(!isset($topic_id)) $topic_id = $thread_id;

$right = 0;

if(!$forum_data = get_forum_data($forum_id)) die('Error loading forum data!');
elseif(!$topic_data = get_topic_data($forum_id,$topic_id)) die('Error loading topic data!');
elseif($user_logged_in != 1) {
	if($forum_data['rights'][8] != 1) {
		echo navbar("<a href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">$forum_data[name]</a>\t<a href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\">$topic_data[title]</a>\t".$lng['No_access']);
		echo get_message('nli','<br>'.sprintf($lng['links']['register_or_login'],"<a class=\"norm\" href=\"index.php?faction=register$MYSID2\">",'</a>',"<a class=\"norm\" href=\"index.php?faction=login$MYSID2\">",'</a>'));
	}
	elseif($topic_data['status'] != "1" && $topic_data['status'] != "open" && $user_data['status'] != 1 && test_mod($forum_id,$user_id) != 1) { // Falls Thema geschlossen ist
		echo navbar("<a href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">$forum_data[name]</a>\t<a href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\">$topic_data[title]</a>\t".$lng['templates']['topic_closed_na'][0]);
		echo get_message('topic_closed_na');
	}
	else $right = 1;
}
else {
	if($user_data[status] == 4) { // Falls User gebannt ist
		echo navbar("<a href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">$forum_data[name]</a>\t<a href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\">$topic_data[title]</a>\t".$lng['No_access']);
		echo get_message('banned');
	}
	elseif(check_right($forum_id,2) != 1) { // Falls User kein Recht zum Antworten hat
		echo navbar("<a href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">$forum_data[name]</a>\t<a href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\">$topic_data[title]</a>\t".$lng['templates']['na'][0]);
		echo get_message('na');
	}
	elseif($topic_data['status'] != "1" && $topic_data['status'] != "open" && $user_data['status'] != 1 && test_mod($forum_id,$user_id) != 1) { // Falls Thema geschlossen ist
		echo navbar("<a href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">$forum_data[name]</a>\t<a href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\">$topic_data[title]</a>\t".$lng['templates']['topic_closed_na'][0]);
		echo get_message('topic_closed_na');
	}
	else $right = 1;
}

if($right == 1) {
	$showformular = 1;
	$error = '';
	if(isset($nli_name)) $nli_name = mutate($nli_name);

	if($mode == "save" && !isset($preview)) {
		if($user_logged_in != 1 && trim($nli_name) == '' && $config['nli_must_enter_name'] == 1) {
			$error = $lng['replyf']['error']['Please_enter_a_name'];
		}
		else {
			$showformular = 0;
			$new_id = $topic_data['lpost_id']+1; // ID des neues Beitrags bestimmen
			$datum = mydate(); // Aktuelles Datum bestimmen
			// Hier beginnt der Sendmail Abschnitt
				if($topic_data['smstatus'] == 1 && $config['activate_mail'] == 1 && $config['notify_new_replies'] == 1) {
					if(myfile_exists('members/'.$topic_data['creator_id'].'.pm')) { // Nur senden, wenn der Zieluser auch existiert
						$search = array("{USERNAME}","{TOPICLINK}");
						$replace = array(get_user_name($topic_data['creator_id']),$config['address_to_forum']."/index.php?faction=readforum&mode=viewthread&forum_id=$forum_id&thread=$topic_id");
						$email_file = myfread($config['lng_folder'].'/mails/new_reply_posted.dat');
						$email_file = str_replace($search,$replace,$email_file);
						mymail(get_user_email($topic_data['creator_id']),$lng['mail_subjects']['new_reply_posted'],$email_file);
					}
				}
			// Hier endet der Sendmail Abschnitt

			if($user_logged_in == 1) $user_info = $user_id;
			else {
				if(trim($nli_name) == '') $nli_name = $lng['Guest'];
				$user_info = "0$nli_name";
			}

			$post = nlbr(trim(mutate($post))); // Beitrag "optimieren" und zum Speichern geeignet machen
			$towrite = "$new_id\t$user_info\t$datum\t$post\t$REMOTE_ADDR\t$show_signatur\t$tsmilie\t$smilies\t$use_upbcode\t$use_htmlcode\t\t\t\r\n"; // Zu schreibende Daten vorbereiten
			myfwrite("foren/$forum_id-$topic_id.xbb",$towrite,"a"); // Daten in die Datei schreiben

			rank_topic($forum_id,$topic_id); increase_posts_number($forum_id); increase_user_posts($user_id); update_last_post($forum_id,$datum,$user_info,$topic_id,$tsmilie); update_topic_time($forum_id,$topic_id); // Alle Angaben updaten (Forenposts, Userposts...)

			if($forum_data['rights'][6] == 1) {
				update_last_posts($forum_id,$topic_id,$user_info,$datum);
				update_today_posts($forum_id,$topic_id,$user_info,$datum);
			}


			mylog("4","%1: Neue Antwort erstellt: index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id (IP: %2)");

			echo navbar("<a href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">$forum_data[name]</a>\t<a href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\">$topic_data[title]</a>\t".$lng['templates']['reply_posted'][0]);
			echo get_message('reply_posted','<br>'.sprintf($lng['links']['topic'],"<a class=\"norm\" href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id&z=last$MYSID2\">",'</a>').'<br>'.sprintf($lng['links']['topic_index'],"<a class=\"norm\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">",'</a>').'<br>'.sprintf($lng['links']['forum_index'],"<a class=\"norm\" href=\"index.php$MYSID1\">",'</a>'));
		}
	}

	if($showformular == 1) {

		$topic_file = myfile("foren/$forum_id-$topic_id.xbb");

		if($quote != "") $quote = "[quote]".get_post($forum_id,$topic_id,$quote)."[/quote]\r\n";

		?>
			<script language="JavaScript">
				<!--
				function setsmile(Zeichen) {
				document.beitrag.post.value = document.beitrag.post.value + Zeichen;
				}
				//-->
			</script>
		<?
		echo navbar("<a href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">$forum_data[name]</a>\t<a href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\">$topic_data[title]</a>\t".$lng['Post_reply']);
		if(isset($preview)) {
			$preview_post = nlbr(trim(mutate($post)));
			if($use_htmlcode == 1 && $forum_data['htmlcode'] == 1) $preview_post = demutate($preview_post);
			if($show_signatur == 1 && $user_data['signatur'] != "") $signatur = "<br><br>-----------------------<br>".upbcode_signatur($user_data[signatur]); else $signatur = ""; // Konfiguration der Signaturanzeige
			if($smilies == 1) $preview_post = make_smilies($preview_post); // Falls Smilies aktiviert wurden, Text umwandeln
			if($use_upbcode == 1 && $forum_data['upbcode'] == 1) $preview_post = upbcode($preview_post); // Falls UPB-Code aktiviert wurde, Text umwandeln (irgendwo hab ich das schonmal gelesen :)
			?>
				<table class="tbl" width="<?=$twidth?>" border=0 cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
				<tr><th align="left" class="thnorm"><span class="thnorm"><?=$lng['Preview']?></span></th></tr>
				<tr><td class="td1"><span class="norm"><?=$preview_post.$signatur?></span></td></tr>
				</table><br>
			<?
		}

		if(!$preview || $show_signatur == 1) $checked['sig'] = " checked";
		if(!$preview || $smilies == 1) $checked['smilies'] = " checked";
		if(!$preview || $use_upbcode == 1) $checked['upbcode'] = " checked";
		if($use_htmlcode == 1) $checked['htmlcode'] = " checked";

		?>
			<form method="post" action="index.php?faction=reply&mode=save<?=$MYSID2?>" name="beitrag">
			<table class="tbl" width="100%" border="0" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
			<tr><th class="thnorm" colspan="2" align="left"><span class="thnorm"><?=$lng['Post_reply']?></span></th></tr>
			<? if($error != '') echo "<tr><td colspan=\"2\" class=\"td1\"><span class=\"error\">$error</span></td></tr>"; ?>
		<?
		if($user_logged_in != 1) { // Falls User nicht eingeloggt ist, wird das Namenfeld angezeigt
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
	 		 <td class="td1" width="80%"><? include("tsmilies.php") ?></td>
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
			 <td class="td1" width="20%" valign="top"><span class="norm"><b><?=$lng['Post']?>:</b><br><br><? include("smilies.php") ?></span></td>
			 <td class="td1" width="80%"><textarea name="post" rows="10" cols="60"><?=$quote?><?=trim(mutate($post))?></textarea></td>
			</tr>
			<? if($tspacing < 1) echo "<tr><td class=\"td1\" colspan=\"2\"><hr></td></tr>"; ?>
			<tr>
			 <td class="td1" width="20%" valign="top"><span class="norm"><b><?=$lng['Options']?>:</b></span></td>
			 <td class="td1" width="80%"><span class="norm"><input type="checkbox" name="smilies" value="1" onfocus="this.blur()"<?=$checked['smilies']?>> <?=$lng['Enable_smilies']?>
		<?
		if($user_logged_in == 1) {
			?>
				<br><input type="checkbox" name="show_signatur" value="1" onfocus="this.blur()"<?=$checked['sig']?>> <?=$lng['Show_signature']?>
			<?
		}
		if($forum_data['upbcode'] == 1) echo "<br><input type=\"checkbox\" name=\"use_upbcode\" value=\"1\" onfocus=\"this.blur()\"$checked[upbcode]> ".$lng['Enable_TBB_code'];
		if($forum_data['htmlcode'] == 1) echo "<br><input type=\"checkbox\" name=\"use_htmlcode\" value=\"1\" onfocus=\"this.blur()\"$checked[htmlcode]> ".$lng['Enable_HTML_code'];
		?>
			</span></td>
			</tr>
			<input type="hidden" name="topic_id" value="<?=$topic_id?>"><input type="hidden" name="forum_id" value="<?=$forum_id?>">
			</table><br><input type="submit" value="<?=$lng['replyf']['Post_reply']?>">&nbsp;&nbsp;&nbsp;<input type="submit" name="preview" value="<?=$lng['Preview']?>"></form>
			<table class="tbl" width="100%" border="0" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
			<tr><th class="thnorm" colspan="2" align="left"><span class="thnorm"><?=$lng['replyf']['Topic_review']?></span></th></tr>
		<?

		$temp_size = sizeof($topic_file);

		if($temp_size > 11) $temp_size = 11;

		for($i = $temp_size-1; $i > 0; $i--) {
			$akt_post = myexplode($topic_file[$i]);

			switch($akt_class) { // Farbe wechseln
				case "td1":
					$akt_class = "td2";
				break;
				default:
					$akt_class = "td1";
				break;
			}

			if($akt_post[7] == 1 || $akt_post[7] == "yes") $akt_post[3] = make_smilies($akt_post[3]); // Falls Smilies aktiviert wurden, Text umwandeln
			if(($akt_post[8] == 1 || $akt_post[8] == "yes") && $forum_data['upbcode'] == 1) $akt_post[3] = upbcode($akt_post[3]); // Falls UPB-Code aktiviert wurde, Text umwandeln
			if($akt_post[9] == 1 && $forum_data['htmlcode'] == 1) $akt_post[3] = demutate($akt_post[3]);
			if($config['censored'] == 1) $akt_post[3] = censor($akt_post[3]);

			?>
				<tr>
				 <td class="<?=$akt_class?>" width="15%" valign="top"><span class="norm"><b><?=get_user_name($akt_post[1])?></b></span></td>
				 <td class="<?=$akt_class?>" width="85%" valign="top"><span class="norm"><?=$akt_post[3]?></span></td>
				</tr>
			<?
		}

		echo "</table></center>";
	}

	/* Du musst doch echt verrückt sein, wenn du versuchst meinen Code zu verstehen ;) */
}

wio_set("reply");

?>