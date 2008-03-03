<?

/* newtopic.php - Erstellt ein neues Thema (c) 2001-2002 Tritanium Scripts */

require_once("auth.php");

if(!$forum_data = get_forum_data($forum_id)) die('Error loading forum data!');

$right = 0;
if($user_logged_in != 1) {
	if($forum_data['rights'][9] == 1) $right = 1;
	else {
		include("pageheader.php");
		echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">$forum_data[name]</a>\t".$lng['No_access']);
		echo get_message('nli','<br>'.sprintf($lng['links']['register_or_login'],"<a class=\"norm\" href=\"index.php?faction=register$MYSID2\">",'</a>',"<a class=\"norm\" href=\"index.php?faction=login$MYSID2\">",'</a>'));
	}
}
else {
	if($user_data[status] == 4) {
		include("pageheader.php");
		echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">$forum_data[name]</a>\t".$lng['No_access']);
		echo get_message('banned');
	}
	elseif(check_right($forum_id,3) != 1) {
		include("pageheader.php");
		echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">$forum_data[name]</a>\t".$lng['templates']['na'][0]);
		echo get_message('na');
	}
	else $right = 1;
}

if($right == 1) {
	$max_poll_choices = 10;
	switch($mode) {
		default: // = Schritt 1
			include("pageheader.php");
			echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">$forum_data[name]</a>\t".$lng['newpoll']['Post_Poll']);
			?>
				<form method="post" action="index.php?faction=newpoll&mode=step2&forum_id=<?=$forum_id?><?=$MYSID2?>">
				<table class="tbl" border="0" cellpadding="<?=$tpadding?>" cellspacing="<?=$tspacing?>" width="<?=$twidth?>">
				<tr><th class="thnorm" colspan="2"><span class="thnorm"><?=$lng['newpoll']['Post_Poll']?></span></th></tr>
				<tr>
				 <td class="td1" width="30%"><span class="norm"><?=$lng['newpoll']['Number_of_choices']?>:</span></td>
				 <td class="td1" width="70%"><select name="choices"><option value="2">2</option>
			<?
				for($i = 3; $i <= $max_poll_choices; $i++) {
					echo "<option value=\"$i\">$i</option>";
				}
			?>
				 </select></span></td>
				</tr>
				<tr>
				 <td class="td1" width="30%"><span class="norm"><?=$lng['newpoll']['Who_is_allowed_to_vote']?>:</span></td>
				 <td class="td1" width="70%"><span class="norm"><select name="poll_type"><option value="1"><?=$lng['newpoll']['Everybody']?></option><option value="2"><?=$lng['newpoll']['Members_only']?></option></select></span></td>
				</tr>
				</table><br><input type="submit" value="<?=$lng['newpoll']['next']?>"></form>
			<?
		break;

		case 'step2':
			$choices = round($choices);
			if($choices < 2 || $choices > $max_poll_choices) {
				header("Location: index.php?faction=newpoll&mode=newpoll&forum_id=$forum_id&$HSID");
			}
			else {

				$showformular = 1;
				$error = '';

				if(isset($save)) {

					$post = mutate($post);
					$title = mutate($title);
					array_walk($poll_choice,'array_mutate');
					reset($poll_choice);

					if(!isset($preview)) {
						while($akt_value = each($poll_choice)) {
							if($akt_value[1] == '') unset($poll_choice[$akt_value[0]]);
						}
						reset($poll_choice);
						$choice_number = sizeof($poll_choice);
						if($user_logged_in != 1 && trim($nli_name) == '' && $config['nli_must_enter_name'] == 1) {
							$error = $lng['newtopic']['error']['Please_enter_a_name'];
						}
						elseif($choice_number < 2 || $choice_number > $max_poll_choices) {
							$error = $lng['newpoll']['error']['Please_enter_more_choices'];
						}
						elseif(trim($title) == '') {
							$error = $lng['newpoll']['error']['Please_enter_a_subject'];
						}
						else {
							$showformular = 0;
							$x = 1;
							$date = mydate();
							if($user_logged_in == 1) $user_info = $user_id;
							else {
								if($nli_name == '') $nli_name = $lng['Guest'];
								$user_info = "0$nli_name";
							}
							if($poll_type != 2 && $poll_type != 1) $poll_type = 1;

							// Zuerst die neuen IDs herausfinden
							$new_poll_id = myfile('polls/polls.xbb'); $new_poll_id = $new_poll_id[0]+1;
							$new_topic_id = myfile("foren/$forum_id-ltopic.xbb"); $new_topic_id = $new_topic_id[0]+1;

							// Thema/Post schreiben
							$post = nlbr($post); // \r\n's des Beitrages in <br>s umwandeln
							$towrite = "1\t$title\t$user_info\t$tsmilie\t$sendmail2\t".time()."\t0\t$new_poll_id\t\t\t\t\t\t\n"."1\t$user_info\t$date\t$post\t$REMOTE_ADDR\t$show_signatur\t$tsmilie\t$smilies\t$use_upbcode\t$use_htmlcode\t\t\t\n"; // Daten zusammenstellen
							myfwrite("foren/$forum_id-threads.xbb","$new_topic_id\n","a"); myfwrite("foren/$forum_id-$new_topic_id.xbb",$towrite,"w"); myfwrite("foren/$forum_id-ltopic.xbb",$new_topic_id,"w"); // Daten schreiben
							increase_topic_number($forum_id); increase_posts_number($forum_id); increase_user_posts($user_id); update_last_post($forum_id,$date,$user_info,$new_topic_id,$tsmilie); // Posts und Themen des Forums um jeweils 1 erhöhen, außerdem letzten Beitrag des Forums updaten

							// Umfrage schreiben
							$towrite = "$poll_type\t$user_info\t$date\t$title\t0\t$forum_id,$new_topic_id\t\t\t\t\t\t\n";
							while($akt_value = each($poll_choice)) {
								$towrite .= "$x\t$akt_value[1]\t0\t\t\t\t\n";
								$x++;
							}
							myfwrite("polls/$new_poll_id-1.xbb",$towrite,'w');
							myfwrite("polls/$new_poll_id-2.xbb",'','w');
							myfwrite('polls/polls.xbb',$new_poll_id,'w');

							if($forum_data['rights'][6] == 1) { // Falls jeder das Forum betreten darf, Last-Posts updaten
								update_last_posts($forum_id,$new_topic_id,$user_info,$date);
								update_today_posts($forum_id,$new_topic_id,$user_info,$date);
							}

							// Hier beginnt "Moderatoren benachrichtigen"
								if($forum_data[smstatus] == 1) {
									$forum_mods = explode(",",$forum_data['mods']);
									$email_file = myfread($config['lng_folder']."/mails/new_poll_posted.dat");
									$email_file = str_replace('{POLLLINK}',$config['address_to_forum']."/index.php?faction=readforum&mode=viewthread&forum_id=$forum_id&thread=$new_topic_id",$email_file);
									$email_subject = sprintf($lng['mail_subjects']['new_topic_posted'],$forum_data['name']);
									for($i = 0; $i < sizeof($forum_mods); $i++) {
										$tosend = str_replace('{USERNAME}',get_user_name($forum_mods[$i]),$email_file);
										mymail(get_user_email($forum_mods[$i]),$email_subject,$tosend);
									}
								}
							// Hier endet "Moderatoren benachrichtigen"

							mylog("4","%1: Umfrage ($forum_id,$new_topic_id) erstellt (IP: %2)");

							include('pageheader.php');
							echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">$forum_data[name]</a>\t".$lng['templates']['poll_posted'][0]);
							echo get_message('poll_posted','<br>'.sprintf($lng['links']['new_topic'],"<a class=\"norm\" href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$new_topic_id$MYSID2\" onfocus=\"this.blur()\">",'</a>').'<br>'.sprintf($lng['links']['topic_index'],"<a class=\"norm\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\" onfocus=\"this.blur()\">",'</a>').'<br>'.sprintf($lng['links']['forum_index'],"<a class=\"norm\" href=\"index.php$MYSID1\">",'</a>'));


						}
					}
				}
				if($showformular == 1) {
						include("pageheader.php");
						?>
							<script language="JavaScript">
							<!--
								function setsmile(Zeichen) {
								document.beitrag.post.value = document.beitrag.post.value + Zeichen;
								}
							//-->
							</script>
						<?
						echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">$forum_data[name]</a>\tNeue Umfrage erstellen");
						if(isset($preview)) {
							$preview_post = nlbr($post);
							if($use_htmlcode == 1 && $forum_data['htmlcode'] == 1) $preview_post = demutate($preview_post);
							if($show_signatur == 1 && $user_data['signatur'] != "") $signatur = "<br><br>-----------------------<br>".upbcode_signatur($user_data['signatur']); else $signatur = ""; // Konfiguration der Signaturanzeige
							if($smilies == 1) $preview_post = make_smilies($preview_post); // Falls Smilies aktiviert wurden, Text umwandeln
							if($use_upbcode == 1 && $forum_data['upbcode'] == 1) $preview_post = upbcode($preview_post); // Falls UPB-Code aktiviert wurde, Text umwandeln
							?>
								<table class="tbl" width=100% border=0 cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
								<tr><th class="thnorm" align="left"><span class="thnorm"><?=$lng['Preview']?></span></th></tr>
								<tr><td class="td1"><span class="norm"><?=$preview_post.$signatur?></span></td></tr>
								</table><br>
							<?
						}

						// Die ganzen "checked" Teile machen
							if(!isset($preview) || $show_signatur == 1) $checked['sig'] = ' checked';
							if(!isset($preview) || $smilies == 1) $checked['smilies'] = ' checked';
							if(!isset($preview) || $use_upbcode == 1) $checked['upbcode'] = ' checked';
							if($use_htmlcode == 1) $checked['htmlcode'] = ' checked';
							if($sendmail2 == 1) $checked['sendmail'] = ' checked';
						?>
							<form method="post" action="index.php?faction=newpoll&forum_id=<?=$forum_id?>&mode=step2<?=$MYSID2?>" name="beitrag"><input type="hidden" name="poll_type" value="<?=$poll_type?>"><input type="hidden" name="choices" value="<?=$choices?>">
							<table width="100%" class="tbl" border="0" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
							<tr><th class="thnorm" align="left" colspan="2"><span class="thnorm"><?=$lng['newpoll']['Post_Poll']?></span></th></tr>
						<?
						if($error != '') echo "<tr><td colspan=\"2\" class=\"td1\"><span class=\"error\">$error</span></td></tr>";
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
							 <td class="td1" width="20%"><span class="norm"><b><?=$lng['newpoll']['Question_Subject']?>:</b></span></td>
							 <td class="td1" width="80%"><input type="text" size="30" name="title" value="<?=$title?>"></td>
							</tr>
							<tr><td class="kat" colspan="2"><span class="kat"><?=$lng['newpoll']['Choices']?></span></th></tr>
							<tr><td class="td1" colspan="2"><span class="norm">
						<?
							for($i = 0; $i < $choices; $i++) {
								$i2 = $i+1;
								echo "$i2: <input type=\"text\" size=\"40\" name=\"poll_choice[$i]\" value=\"$poll_choice[$i]\"><br>";
							}
						?>
							</span></td></tr>
							<tr><td class="kat" colspan="2"><span class="kat"><?=$lng['Post']?></span></th></tr>
							<tr>
							 <td class="td1" width="20%"><span class="norm"><b><?=$lng['Pic_for_this_post']?>:</b></span></td>
							 <td class="td1" width="80%" valign=top><? include("tsmilies.php") ?></td>
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
							 <td class="td1" width="20%" valign="top"><span class="norm"><b><?=$lng['Post']?>:</b></span><br><br><? include("smilies.php"); ?></font></td>
							 <td class="td1" width="80%"><textarea name="post" rows="10" cols="60"><?=$post?></textarea></td>
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
						if($config['activate_mail'] == 1 && $config['notify_new_replies'] == 1 && $user_logged_in == 1) echo "<br><input type=\"checkbox\" name=\"sendmail2\" value=\"1\" onfocus=\"this.blur()\"$checked[sendmail]> ".$lng['newtopic']['Notify_new_reply'];
						?>
							</span></td>
							</tr>
							<input type="hidden" name="save" value="yes">
							</table><br><input type="submit" value="<?=$lng['newpoll']['Post_Poll']?>">&nbsp;&nbsp;&nbsp;<input type="submit" name="preview" value="<?=$lng['Preview']?>"></form></center>
						<?
				}
			}
		break;
	}
}

wio_set('newpoll'); // WIO konfigurieren

?>