<?

/* profile.php - ansehen oder bearbeiten eines Profiles (c) 2001-2002 Tritanium Scripts */

require_once("auth.php");

if(!$profile_id) $profile_id = $user_id;

if($profile_id == 0 || !$profile_data = get_user_data($profile_id)) { // Sicherstellen, dass man keine ungültige Profil-ID gewählt hat
	include("pageheader.php");
	echo navbar($lng['templates']['user_does_not_exist'][0]);
	echo get_message('user_does_not_exist','<br>'.sprintf($lng['links']['forum_index'],"<a class=\"norm\" href=\"index.php$MYSID1\">",'</a>'));
}
elseif($profile_data['status'] == 5) { // Nur weitermachen, wenn User nicht gelöscht ist
	include("pageheader.php");
	echo navbar($lng['templates']['user_does_not_exist'][0]);
	echo get_message('user_does_not_exist','<br>'.sprintf($lng['links']['forum_index'],"<a class=\"norm\" href=\"index.php$MYSID1\">",'</a>'));
}
else {
	switch($mode) {

	default:
		include("pageheader.php");
		if($profile_data['forummails'] != 1) $email2 = "";
		else $email2 = "&nbsp;&nbsp;<a class=\"norm\" href=\"index.php?faction=formmail&target_id=$profile_id$MYSID2\">".$lng['profile']['Send_email'].'</a>';
		if($profile_data['showemail'] != 1) $email1 = $lng['profile']['hidden'];
		else $email1 = $profile_data[email];
		echo navbar(sprintf($lng['profile']['View_someones_profile'],$profile_data[nick]));
		?>
		<table class="tbl" width="<?=$twidth?>" border="0" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
		<tr><th align="left" colspan="2" class="thnorm"><span class="thnorm"><?=$lng['profile']['View_profile']?></span></th></tr>
		<tr>
		 <td width=20% class="td1"><span class="norm"><b><?=$lng['User_ID']?>:</b></span></td>
		 <td width=80% class="td1"><span class="norm"><?=$profile_data['id']?></span></td>
		</tr>
		<tr>
		 <td width=20% class="td1"><span class="norm"><b><?=$lng['Nick']?>:</b></span></td>
		 <td width=80% class="td1"><span class="norm"><?=$profile_data['nick']?></span></td>
		</tr>
		<tr>
		 <td width=20% class="td1"><span class="norm"><b><?=$lng['Emailaddress']?>:</b></span></td>
		 <td width=80% class="td1"><span class="norm"><?=$email1?><?=$email2?></span></td>
		</tr>
		<tr>
		 <td width=20% class="td1"><span class="norm"><b><?=$lng['Real_name']?>:</b></span></td>
		 <td width=80% class="td1"><span class="norm"><? if($profile_data['name'] != "") echo $profile_data['name']; else echo "--nicht angegeben--"; ?></span></td>
		</tr>
		<tr>
		 <td width=20% class="td1"><span class="norm"><b><?=$lng['profile']['Homepage']?>:</b></span></td>
		 <td width=80% class="td1"><span class="norm"><? if($profile_data[hp] != "") echo "<a class=\"norm\" target=\"_blank\" href=\"".addhttp($profile_data[hp])."\">".addhttp($profile_data[hp])."</a>"; else echo "--nicht angegeben--"; ?></span></td>
		</tr>
		<tr>
		 <td width=20% class="td1"><span class="norm"><b><?=$lng['Status']?>:</b></span></td>
		 <td width=80% class="td1"><span class="norm"><?=morph_status($profile_data['status'],$profile_data[posts])."&nbsp;".get_rank_pic($profile_data[status],$profile_data[posts])?></span></td>
		</tr>
		<tr>
		 <td width=20% class="td1"><span class="norm"><b><?=$lng['Posts']?>:</b></span></td>
		 <td width=80% class="td1"><span class="norm"><?=$profile_data['posts']?></span></td>
		</tr>
		<tr>
		 <td width=20% class="td1" valign=top><span class="norm"><b><?=$lng['profile']['Avatar']?>:</b></span></td>
		 <td width=80% class="td1"><span class="norm"><? if($profile_data[pic] != "") echo "<img src=".addhttp($profile_data[pic]).">"; else echo "--nicht angegeben--"; ?></span></td>
		</tr>
		<tr>
		 <td width=20% class="td1" valign=top><span class="norm"><b><?=$lng['profile']['Signature']?>:</b></span></td>
		 <td width=80% class="td1"><span class="norm"><?=upbcode_signatur($profile_data['signatur'])?></span></td>
		</tr>
		<tr>
		 <td width=20% class="td1" valign=top><span class="norm"><b><?=$lng['profile']['ICQ']?>:</b></span></td>
		 <td width=80% class="td1"><span class="norm"><? if($profile_data[icq] != "") echo "$profile_data[icq]&nbsp;&nbsp;<a target=\"_blank\" href=\"http://wwp.icq.com/scripts/search.dll?to=$profile_data[icq]\"><img border=\"0\" src=\"http://web.icq.com/whitepages/online?icq=$profile_data[icq]&img=2\"></a>"; else echo "--nicht angegeben--"; ?></span></td>
		</tr>
		<?

		if($user_data['status'] == 1) echo "<tr><td class=\"td1\" colspan=2><span class=\"norm\"><a class=\"norm\" href=\"ad_user.php?mode=edit&id=$profile_id$MYSID2\">".$lng['Edit_user'].'</a></span></td></tr>';
		echo "</table></center>";
	break;

	case "edit":
		if($user_logged_in != 1) echo $lng['No_access']; // Falls User nicht eingeloggt ist
		elseif($user_id != $profile_id && $user_data['status'] != 1) echo $lng['No_access']; // Falls man nicht Admin oder "Eigentümer" des Profiles ist
		else {

			$showformular = 1;
			$mailfehler = "";
			$pwfehler = "";
			$update_text = "";

			if($change == 1) {
				if(isset($delete)) { // Wurde "Account löschen" angeklickt?
					$showformular = 0;
					if(isset($confirm)) { // Nun kann gelöscht werden
						unlink("members/$profile_id.xbb"); unlink("members/$profile_id.pm");
						$member_counter = myfile("vars/member_counter.var"); myfwrite("vars/member_counter.var",$member_counter[0]-1,'w');
						if($profile_id == $user_id) {
							$user_logged_in = 0; unset($user_data); unset($user_id);
						}
						include('pageheader.php');
						echo navbar($lng['templates']['account_deleted'][0]);
						echo get_message('account_deleted');
					}
					else { // Bestätigung anzeigen
						include('pageheader.php');
						echo navbar("<a class=\"navbar\" href=\"index.php?faction=profile&mode=edit&profile_id=$profile_id$MYSID2\">".$lng['profile']['View_change_my_profile']."</a>\t".$lng['profile']['Delete_account']);
						?>
							<form method=post action="index.php?faction=profile&mode=edit&profile_id=<?=$profile_id?><?=$MYSID2?>"><input type="hidden" name="change" value="1"><input type="hidden" name="delete" value="1"><input type="hidden" name="confirm" value="1">
							<table class="tbl" width="<?=$twidth?>" border=0 cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
							<tr><th class="thnorm" colspan=2><span class="thnorm"><?=$lng['profile']['Delete_account']?></span></th></tr>
							<tr><td class="td1"><span class="norm"><center><br><?=$lng['profile']['Really_delete_account']?><br><br></center></span></td></tr>
							</table><br><input type="submit" value="<?=$lng['profile']['Delete_account']?>"></form>
						<?
					}
				}
				else {

					$new_pw1 = mysslashes($new_pw1); $new_pw2 = mysslashes($new_pw2);
					if($new_mail == "") $mailfehler = $lng['profile']['error']['No_email']; // Es muss eine Mailadresse eingegeben werden
					//elseif(check_mail($new_mail,$profile_id) == 1) $mailfehler = "Diese Emailadresse wird schon verwendet!"; // Es dürfen nicht 2 die gleiche Emailadresse haben
					elseif($new_pw1 != "" && $new_pw1 != $new_pw2) $pwfehler = $lng['profile']['error']['Password_doesnt_match']; // Die Passwörter werden verglichen
					else {
						$showformular = 0;
						$new_signatur = nlbr(trim(mutate($HTTP_POST_VARS['new_signatur']))); // Signatur wird kompatibel gemacht
						if($HTTP_POST_VARS['new_pw1'] == "") $new_pw = $profile_data['pw']; else $new_pw = mycrypt($HTTP_POST_VARS['new_pw1']); // Altes Passwort verwenden oder neues verschlüsseln
						$user_file = myfile("members/$profile_id.xbb"); $user_file_size = sizeof($user_file);
						$user_file[2] = $new_pw."\n"; // Neues Paswort
						$user_file[3] = $HTTP_POST_VARS['new_mail']."\n"; // Neue Mailadresse
						$user_file[7] = $new_signatur."\n"; // Neue Signatur
						$user_file[9] = $HTTP_POST_VARS['new_hp']."\n"; // Neue HP-Adresse
						$user_file[10] = $HTTP_POST_VARS['new_pic']."\n"; // Neues Bild
						$user_file[12] = $HTTP_POST_VARS['new_realname']."\n"; // Neuer Name
						$user_file[13] = $HTTP_POST_VARS['new_icq']."\n"; // Neue ICQ-Nummer
						$user_file[14] = $HTTP_POST_VARS['new_mail1'].','.$HTTP_POST_VARS['new_mail2']."\n"; // Neue Mailoptionen
						myfwrite("members/$profile_id.xbb",$user_file,"w");

						// Neukonfiguration des Cookies
							if($new_pw1 != "") {
								if($user_data[status] != 1 || $user_id == $profile_id) { // Falls der User Admin ist und ihm das Profil nicht gehört, wird auch das Cookie nicht geupdated
									$sesion_user_pw = $new_pw; session_register("session_user_pw"); // Passwort in der Session aktualisieren
									if($cookie_xbbuser) { // Falls ein Cookie existiert diesen auch aktualisieren
										$cookie_daten = "$user_id\t$new_pw";
										setcookie("cookie_xbbuser",$cookie_daten,time()+(3600*24*365),$config['path_to_forum']); // Cookie mit neuem Passwort wird vorbereitet und dann gesetzt
									}
								}
							}
						// Ende der Neukonfiguration des Cookies

						mylog("10","%1: Profil bearbeitet (IP: %2)");

						include("pageheader.php");
						echo navbar($lng['templates']['profile_saved'][0]);
						echo get_message('profile_saved','<br>'.sprintf($lng['links']['profile'],"<a class=\"norm\" href=\"index.php?faction=profile&mode=edit&profile_id=$profile_id$MYSID2\" onfocus=\"this.blur()\">",'</a>').'<br>'.sprintf($lng['links']['forum_index'],"<a class=\"norm\" href=\"index.php$MYSID1\" onfocus=\"this.blur()\">",'</a>'));
					}
				}
			}

			if ($showformular == 1) {

				if($profile_data[updatestatus] == 1 && $profile_id == $user_id) {
					$update_text = $lng['profile']['Forum_update'];
					change_user_db($profile_id,11,0);
				}

				include("pageheader.php");
				echo navbar($lng['profile']['View_change_my_profile']);
				?>
				<form method=post action="index.php?faction=profile&mode=edit&profile_id=<?=$profile_id?><?=$MYSID2?>"><input type=hidden name=change value=1>
				<table class="tbl" width="<?=$twidth?>" border=0 cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
				<tr><th class="thnorm" colspan=2><span class="thnorm"><?=$lng['profile']['View_change_my_profile']?></span></th></tr>
				<tr><td class="kat" colspan=2><span class="kat"><?=$lng['profile']['Change_user_data']?></span></td></tr>
				<? if($update_text != "") echo "<tr><td class=\"td1\" colspan=2><span class=\"error\">$update_text</span></td></tr>"; ?>
				<tr>
				 <td class="td1" width="20%"><span class="norm"><b><?=$lng['Nick']?>:</b></span></td>
				 <td class="td1" width="80%"><span class="norm"><?=$profile_data[nick]?></span></td>
				</tr>
				<tr>
				 <td class="td1" width="20%"><span class="norm"><b><?=$lng['Emailaddress']?>:</font></td>
				 <td class="td1" width="80%"><input type="text" name="new_mail" value="<?=$profile_data[email]?>"><span class="error"><?=$mailfehler?></span></td>
				</tr>
				<tr>
				 <td class="td1" width="20%"><span class="norm"><b><?=$lng['Status']?>:</b></span></td>
				 <td class="td1" width="80%"><span class="norm"><?=morph_status($profile_data[status],$profile_data[posts])."&nbsp;".get_rank_pic($profile_data[status],$profile_data[posts])?></span></td>
				</tr>
				<tr>
				 <td class="td1" width="20%"><span class="norm"><b><?=$lng['Posts']?>:</b></span></td>
				 <td class="td1" width="80%"><span class="norm"><?=$profile_data[posts]?></span></td>
				</tr>
				<tr>
				 <td class="td1" width="20%"><span class="norm"><b><?=$lng['User_ID']?>:</b></span></td>
				 <td class="td1" width="80%"><span class="norm"><?=$profile_data[id]?></span></td>
				</tr>
				<tr>
				 <td class="td1" width="20%"><span class="norm"><b><?=$lng['profile']['Homepage']?>:</b></span></td>
				 <td class="td1" width="80%"><input type="text" name="new_hp" value="<?=$profile_data[hp]?>"></td>
				</tr>
				<tr>
				 <td class="td1" width="20%"><span class="norm"><b><?=$lng['profile']['Avatar']?>:</b></span></td>
				 <td class="td1" width="80%"><input type="text" name="new_pic" value="<?=$profile_data[pic]?>"></td>
				</tr>
				<tr>
				 <td class="td1" width="20%"><span class="norm"><b><?=$lng['profile']['Real_name']?>:</b></span></td>
				 <td class="td1" width="80%"><input type="text" name="new_realname" value="<?=$profile_data[name]?>"></td>
				</tr>
				<tr>
				 <td class="td1" width="20%"><span class="norm"><b><?=$lng['profile']['ICQ']?>:</b></span></td>
				 <td class="td1" width="80%"><input type="text" name="new_icq" value="<?=$profile_data[icq]?>"></td>
				</tr>
				<tr>
				 <td class="td1" width="20%" valign="top"><span class="norm"><b><?=$lng['profile']['Signature']?>:</b></span><br><span class="small"><?=$lng['profile']['TBB_Code_enabled']?></span></td>
				 <td class="td1" width="80%"><textarea cols="50" rows="6" name="new_signatur"><?=brnl($profile_data[signatur])?></textarea></td>
				</tr>
				<tr><td class="kat" colspan="2"><span class="kat"><?=$lng['Options']?></span></td></tr>
				<tr><td class="td1" colspan="2"><input type="checkbox" value="1" name="new_mail2"<? if($profile_data[showemail] == 1) echo " checked" ?>> <span class="norm"><?=$lng['profile']['Show_emailaddress']?></span></td></tr>
				<tr><td class="td1" colspan="2"><input type="checkbox" value="1" name="new_mail1"<? if($profile_data[forummails] == 1) echo " checked" ?>> <span class="norm"><?=$lng['profile']['Receive_mails_from_forum']?></span></td></tr>
				<tr><td class="kat" colspan="2"><span class="kat"><?=$lng['profile']['Change_password']?></span></td></tr>
				<? if($pwfehler != "") echo "<tr><td class=\"td1\" colspan=\"2\"><span class=\"error\">$pwfehler</span></td></tr>"; ?>
				<tr><td class="td1" colspan="2"><span class="small"><?=$lng['profile']['Not_to_change_password']?></span></td></tr>
				<tr>
				 <td class="td1" width="20%"><span class="norm"><b><?=$lng['profile']['New_password']?>:</b></span></td>
				 <td class="td1" width="80%" valign=top><input type="password" name="new_pw1"></td>
				</tr>
				<tr>
				 <td class="td1" width="20%"><span class="norm"><b><?=$lng['profile']['Confirm_new_password']?>:</b></span></td>
				 <td class="td1" width="80%" valign=top><input type="password" name="new_pw2"></td>
				</tr>
				</table><br><input type="submit" value="<?=$lng['profile']['Change_profile']?>">&nbsp;&nbsp;<input type="submit" name="delete" value="<?=$lng['profile']['Delete_account']?>"></form></center>
				<?
			}
		}
	break;

	} // Hier endet switch($mode)

}

wio_set("profile");

?>