<?

/* ad_forum.php - zum Verwalten der Foren/Kategorien (c) 2001-2002 Tritanium Scripts */

require_once("functions.php");
require_once("loadset.php");
require_once("auth.php");
ad();

if($user_logged_in != 1 || $user_data['status'] != 1) {
	mylog("2","%1: Administrationszugriffversuch (IP: %2)");
	header("Location: ad_login.php?$HSID"); exit;
}
else {

	$dosave = "";

	switch($mode) {

	default: // Auswahlmenü anzeigen
		include("pageheader.php");
		echo adnavbar($lng['ad_forum']['Edit_Forums_Categories']);
		?>
			<table class="tbl" border=0 width="<?=$twidth?>" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
			<tr><th class="thnorm"><span class="thnorm"><?=$lng['ad_forum']['Edit_Forums_Categories']?></span></th></tr>
			<tr><td class="td1"><span class="norm"><a class="norm" href="ad_forum.php?mode=forumview<?=$MYSID2?>"><b><?=$lng['ad_forum']['Edit_Forums']?></b></a></span><br><span class="small"><?=$lng['ad_forum']['edit_forums_description']?></span></td></tr>
			<tr><td class="td1"><span class="norm"><a class="norm" href="ad_forum.php?mode=viewkg<?=$MYSID2?>"><b><?=$lng['ad_forum']['Edit_Categories']?></b></a></span><br><span class="small"><?=$lng['ad_forum']['edit_categories_description']?></span></tr>
			</table>
		<?
	break;

	case "forumview": // Forenübersicht anzeigen
		include("pageheader.php");
		echo adnavbar($lng['ad_forum']['Edit_Forums']);
		echo "</center><span class=\"norm\"><a class=\"norm\" href=\"ad_forum.php?mode=newforum$MYSID2\">".$lng['ad_forum']['Add_Forum']."</a></span><center><br>";
		$foren = myfile("vars/foren.var"); $foren_anzahl = sizeof($foren); $kgs = myfile("vars/kg.var");
		?>
			<table class="tbl" width="<?=$twidth?>" border="0" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
			<tr>
			 <!--<th class="thsmall"><span class="thsmall">ID</span></th>-->
			 <th class="thsmall"><span class="thsmall"><?=$lng['Title']?></span></th>
			 <th class="thsmall"><span class="thsmall"><?=$lng['Description']?></span></th>
			 <th class="thsmall"><span class="thsmall"><?=$lng['Moderators']?></span></th>
			 <th class="thsmall"><span class="thsmall"><?=$lng['ad_forum']['Category']?></span></th>
			 <th class="thsmall"></th>
			 <th class="thsmall"></th>
			</tr>
		<?
		if($foren_anzahl == 0) echo "<tr><td class=\"td1\" colspan=\"7\" align=\"center\"><span class=\"norm\">".$lng['ad_forum']['No_Forum']."</span></td></tr>";
		else {
			for($i = 0; $i < $foren_anzahl; $i++) {
				$akt_forum = myexplode($foren[$i]);
				if($foren_anzahl != 1) {
					if($i == 0) $moving = "<a href=\"ad_forum.php?mode=moveforumdown&id=$akt_forum[0]$MYSID2\">&darr;</a>";
					elseif($i == $foren_anzahl - 1) $moving = "<a href=\"ad_forum.php?mode=moveforumup&id=$akt_forum[0]$MYSID2\">&uarr;</a>";
					else $moving = "<a href=\"ad_forum.php?mode=moveforumdown&id=$akt_forum[0]$MYSID2\">&darr;</a>&nbsp|&nbsp<a href=\"ad_forum.php?mode=moveforumup&id=$akt_forum[0]$MYSID2\">&uarr;</a>";
				}
				?>
					<tr>
					 <!--<td class="td1" valign=top><span class="small"><?=$akt_forum[0]?></span></td>-->
					 <td class="td1" valign=top><span class="small"><?=$akt_forum[1]?></span></td>
					 <td class="td2" valign=top><span class="small"><?=$akt_forum[2]?></span></td>
					 <td class="td1" valign=top><span class="small"><?=get_forum_mods($akt_forum[11])?></span></td>
					 <td class="td2" valign=top><span class="small"><?=get_kg_name($akt_forum[5],$kgs)?></span></td>
					 <td class="td1" align=center><span class="norm"><?=$moving?></span></td>
					 <td class="td2" align=center><span class="small"><a class="small" href="ad_forum.php?ad_forum_id=<?=$akt_forum[0]?>&mode=change<?=$MYSID2?>"><?=$lng['edit']?></a></span></td>
					</tr>
				<?
			}
		}
		echo "</table><br></center><span class=\"norm\"><a class=\"norm\" href=\"ad_forum.php?mode=newforum$MYSID2\">".$lng['ad_forum']['Add_Forum']."</a></span><center>";
	break;

	case "newforum": // Neues Forum erstellen
		if($create != "yes") {
			include("pageheader.php");
			echo adnavbar("<a class=\"navbar\" href=\"ad_forum.php?mode=forumview$MYSID2\">".$lng['ad_forum']['Edit_Forums']."</a>\t".$lng['ad_forum']['Add_Forum']);
			?>
				<form method="post" action="ad_forum.php?mode=newforum&create=yes<?=$MYSID2?>">
				<table class="tbl" border=0 cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>" width="<?=$twidth?>">
				<tr><th colspan="2" class="thnorm"><span class="thnorm"><?=$lng['ad_forum']['Add_Forum']?></span></th></tr>
				<tr><td colspan="2" class="kat"><span class="kat"><?=$lng['ad_forum']['General_information']?></span></td></tr>
				<tr>
				 <td class="td1"><span class="norm"><b><?=$lng['Title']?>:</b></span></td>
				 <td class="td1"><input type="text" name="titel"></td>
				</tr><tr>
				 <td class="td1"><span class="norm"><b><?=$lng['Description']?>:</b></span></td>
				 <td class="td1"><input type="text" size="50" name="description"></td>
				</tr><tr>
				 <td class="td1"><span class="norm"><b><?=$lng['Moderators']?>:</b></span></td>
				 <td class="td1"><input type="text" size="10" name="mods"> <span class="small">(<?=$lng['ad_forum']['Seperate_the_mod_ids_with_commas']?>)</span></td>
				</tr><tr>
				 <td class="td1"><span class="norm"><b><?=$lng['ad_forum']['Category']?>:</b></span></td>
				 <td class="td1"><select name="kg" size=1><option value="-1" selected><?=$lng['ad_forum']['No_Category']?></option>
			<?
				$kgs = myfile("vars/kg.var");
			  	for($j = 0; $j < sizeof($kgs); $j++) {
			  		$akt_kg = myexplode($kgs[$j]);
			  		echo "<option value=\"$akt_kg[0]\">$akt_kg[1]</option>";
				}
			?>
				  </select></td>
				</tr>
				<tr><td colspan="2" class="kat"><span class="kat"><?=$lng['ad_forum']['General_rights']?></span></td></tr>
				<tr><td colspan="2" class="td1"><span class="norm"><input type="checkbox" name="new_rights[0]" value="1" checked onfocus="this.blur()"> <?=$lng['ad_forum']['Members_are_allowed_to_access_forum']?></span></td></tr>
				<tr><td colspan="2" class="td1"><span class="norm"><input type="checkbox" name="new_rights[1]" value="1" checked onfocus="this.blur()"> <?=$lng['ad_forum']['Members_are_allowed_to_post_new_topics']?></span></td></tr>
				<tr><td colspan="2" class="td1"><span class="norm"><input type="checkbox" name="new_rights[2]" value="1" checked onfocus="this.blur()"> <?=$lng['ad_forum']['Members_are_allowed_to_post_replies']?></span></td></tr>
				<tr><td colspan="2" class="td1"><span class="norm"><input type="checkbox" name="new_rights[3]" value="1" checked onfocus="this.blur()"> <?=$lng['ad_forum']['Members_are_allowed_to_post_polls']?></span></td></tr>
				<tr><td colspan="2" class="td1"><span class="norm"><input type="checkbox" name="new_rights[4]" value="1" checked onfocus="this.blur()"> <?=$lng['ad_forum']['Members_are_allowed_to_edit_their_posts']?></span></td></tr>
				<tr><td colspan="2" class="td1"><span class="norm"><input type="checkbox" name="new_rights[5]" value="1" checked onfocus="this.blur()"> <?=$lng['ad_forum']['Members_are_allowed_to_edit_their_polls']?></span></td></tr>
				<tr><td colspan="2" class="td1"><span class="norm"><input type="checkbox" name="new_rights[6]" value="1" checked onfocus="this.blur()"> <?=$lng['ad_forum']['Guests_are_allowed_to_access_forum']?></span></td></tr>
				<tr><td colspan="2" class="td1"><span class="norm"><input type="checkbox" name="new_rights[7]" value="1" onfocus="this.blur()"> <?=$lng['ad_forum']['Guests_are_allowed_to_post_new_topics']?></span></td></tr>
				<tr><td colspan="2" class="td1"><span class="norm"><input type="checkbox" name="new_rights[8]" value="1" onfocus="this.blur()"> <?=$lng['ad_forum']['Guests_are_allowed_to_post_replies']?></span></td></tr>
				<tr><td colspan="2" class="td1"><span class="norm"><input type="checkbox" name="new_rights[9]" value="1" onfocus="this.blur()"> <?=$lng['ad_forum']['Guests_are_allowed_to_post_polls']?></span></td></tr>
				<tr><td colspan="2" class="kat"><span class="kat"><?=$lng['Options']?></span></td></tr>
				<tr><td colspan=2 class="td1"><span class="norm"><input type="checkbox" name="upbcode" value="1" checked onfocus="this.blur()"> <?=$lng['ad_forum']['Enable_TBB_Code']?></span></td></tr>
				<tr><td colspan=2 class="td1"><span class="norm"><input type="checkbox" name="htmlcode" value="1" onfocus="this.blur()"> <?=$lng['ad_forum']['Enable_HTML_Code']?></span></td></tr>
				<tr><td colspan=2 class="td1"><span class="norm"><input type="checkbox" name="sm_mods" value="1" onfocus="this.blur()"> <?=$lng['ad_forum']['Notify_mods_about_new_topics']?></span></td></tr>
				</table><br><input type="submit" value="<?=$lng['ad_forum']['Add_Forum']?>"></form></center>
			<?
		}

		else {
			// Erst neuen Mods Rang zuweisen, wenn sie noch nicht Mod eines anderen Forum sind
			if($mods != "") {
				$check_mods = explode(",",$mods);
				for($i = 0; $i < sizeof($check_mods); $i++) {
					if(get_real_user_status($check_mods[$i]) != 1) {
						if(check_if_mod(-1,$check_mods[$i]) != 1) {
							change_user_db($check_mods[$i],4,"2");
						}
					}
				}
			}

			// Letzte ID rausfinden
			$foren_ids = myfile("vars/forens.var");
			$neue_id = $foren_ids[0]+1;

			// Benötigte Dateien anlegen
			myfwrite("foren/$neue_id-ltopic.xbb","0","w");
			myfwrite("foren/$neue_id-threads.xbb","","w");

			// Jetzt kann geschrieben werden
			$titel = trim(mutate($titel)); $description = trim(mutate($description));
			$towrite = "$neue_id\t$titel\t$description\t0\t0\t$kg\t\t$upbcode,$htmlcode,$sm_mods\t\t\t$new_rights[0],$new_rights[1],$new_rights[2],$new_rights[3],$new_rights[4],$new_rights[5],$new_rights[6],$new_rights[7],$new_rights[8],$new_rights[9]\t$mods\t\t\t\t\r\n";
			myfwrite("vars/foren.var",$towrite,"a");

			// Letzte ID updaten
			myfwrite("vars/forens.var",$neue_id,"w");

			mylog("8","%1: Administration: Forum (ID: $neue_id) erstellt (IP: %2)");

			header("Location: ad_forum.php?mode=forumview&$HSID"); exit;
		}
	break;

	case 'new_user_right':
		$showformular = 1;
		if($change == 'yes') {
			$showformular = 0;
			$new_user_ids = explode(',',$new_user_ids);
			$rights_file = myfile("foren/$forum_id-rights.xbb");
			$new_user_ids = array_unique($new_user_ids);

			// Zuerst alle User löschen, die gar nicht existieren
			while($akt_value = each($new_user_ids)) {
				if(!myfile_exists("members/$akt_value[1].xbb") || $akt_value[1] == 0) unset($new_user_ids[$akt_value[0]]);
			}
			reset($new_user_ids);

			// Jetzt User löschen, die schon spezielle Rechte für dieses Forum haben
			for($i = 0; $i < sizeof($rights_file); $i++){
				$akt_right = myexplode($rights_file[$i]);
				if($akt_right[1] == 1) {
					while($akt_value = each($new_user_ids)) {
						if($akt_value[1] == $akt_right[2]) unset($new_user_ids[$akt_value[0]]);
					}
					reset($new_user_ids);
				}
			}

			// Jetzt können die User eingetragen werden
			if(sizeof($new_user_ids) != 0) {
				$new_id = myexplode($rights_file[sizeof($rights_file)-1]); $new_id = $new_id[0]+1; // Erst mal neue ID rausfinden
				$towrite = '';
				while($akt_value = each($new_user_ids)) {
					$towrite .= "$new_id\t1\t$akt_value[1]\t$new_right[0]\t$new_right[1]\t$new_right[2]\t$new_right[3]\t$new_right[4]\t$new_right[5]\t\t\t\t\t\t\r\n";
					$new_id++;
				}
				myfwrite("foren/$forum_id-rights.xbb",$towrite,'a'); // Jetzt alles schreiben
			}
			header("Location: ad_forum.php?mode=edit_forum_rights&forum_id=$forum_id&$HSID"); exit;
		}

		if($showformular == 1) {
			$forum_data = get_forum_data($forum_id);
			include("pageheader.php");
			echo adnavbar("<a class=\"navbar\" href=\"ad_forum.php?mode=forumview$MYSID2\">".$lng['ad_forum']['Edit_Forums']."</a>\t<a class=\"navbar\" href=\"ad_forum.php?ad_forum_id=$forum_id&mode=change$MYSID2\">Forum #$forum_id bearbeiten</a>\t<a class=\"navbar\" href=\"ad_forum.php?mode=edit_forum_rights&forum_id=$forum_id$MYSID2\">".$lng['ad_forum']['Edit_special_rights']."</a>\t".$lng['ad_forum']['Add_user_right']);
			?>
				<form method="post" action="ad_forum.php?mode=new_user_right&forum_id=<?=$forum_id?><?=$MYSID2?>"><input type="hidden" name="change" value="yes">
				<table class="tbl" border="0" cellpadding="<?=$tpadding?>" cellspacing="<?=$tspacing?>" width="<?=$twidth?>">
				<tr><th colspan="2" class="thnorm"><span class="thnorm"><?=$lng['ad_forum']['Add_user_right']?></span></th></tr>
				<tr>
				 <td class="td1"><span class="norm"><b><?=$lng['User_ID']?>:</b></span></td>
				 <td class="td1"><input type="text" name="new_user_ids"><span class="small"> (<?=$lng['ad_forum']['Seperate_ids_with_commas']?>)</span></td>
				</tr>
				<tr>
				 <td class="td1" valign="top"><span class="norm"><b><?=$lng['ad_forum']['Rights']?>:</b></span></td>
				 <td class="td1"><input type="checkbox" value="1" name="new_right[0]" onfocus="this.blur()"<? if($forum_data['rights'][0] == 1) echo " checked"; ?>> <span class="norm"><?=$lng['ad_forum']['Is_allowed_to_access_forum']?></span><br><input value="1" type="checkbox" name="new_right[1]" onfocus="this.blur()"<? if($forum_data['rights'][1] == 1) echo " checked"; ?>> <span class="norm"><?=$lng['ad_forum']['Is_allowed_to_post_topics']?></span><br><input value="1" type="checkbox" name="new_right[2]" onfocus="this.blur()"<? if($forum_data['rights'][2] == 1) echo " checked"; ?>> <span class="norm"><?=$lng['ad_forum']['Is_allowed_to_post_replies']?></span><br><input type="checkbox" value="1" name="new_right[3]" onfocus="this.blur()"<? if($forum_data['rights'][3] == 1) echo " checked"; ?>> <span class="norm"><?=$lng['ad_forum']['Is_allowed_to_post_polls']?></span><br><input type="checkbox" value="1" name="new_right[4]" onfocus="this.blur()"<? if($forum_data['rights'][4] == 1) echo " checked"; ?>> <span class="norm"><?=$lng['ad_forum']['Is_allowed_to_edit_own_posts']?></span><br><input value="1" type="checkbox" name="new_right[5]" onfocus="this.blur()"<? if($forum_data['rights'][5] == 1) echo " checked"; ?>> <span class="norm"><?=$lng['ad_forum']['Is_allowed_to_edit_own_polls']?></span></td>
				</tr>
				</table><br><input type="submit" value="<?=$lng['ad_forum']['Add_user_right']?>">
			<?
		}
	break;

	case 'kill_right':
		$rights_file = myfile("foren/$forum_id-rights.xbb");
		for($i = 0; $i < sizeof($rights_file); $i++) {
			$akt_right = myexplode($rights_file[$i]);
			if($akt_right[0] == $right_id) {
				switch($akt_right[1]) { // Überprüfen, ob User- oder Gruppenrecht vorliegt
					case '1': // User
						$rights_file[$i] = '';
						myfwrite("foren/$forum_id-rights.xbb",$rights_file,'w');
						header("Location: ad_forum.php?mode=edit_forum_rights&forum_id=$forum_id&$HSID"); exit;
					break;

					case '2': // Gruppe
						$groups_file = myfile('vars/groups.var');
						for($j = 0; $j < sizeof($groups_file); $j++) {
							$akt_group = myexplode($groups_file[$j]);
							if($akt_group[0] == $akt_right[2]) {
								$akt_group_forums = explode(',',$akt_group[5]);
								if(in_array($forum_id,$akt_group_forums)) { // Falls Forum gefunden wird...
									unset($akt_group_forums[array_search(forum_id,$akt_group_forums)]); // ...löschen
									$akt_group[5] = implode(',',$akt_group_forums);
									$groups_file[$j] = myimplode($akt_group);
									myfwrite('vars/groups.var',$groups_file,'w');
								}
								break;
							}
						}
						$rights_file[$i] = '';
						myfwrite("foren/$forum_id-rights.xbb",$rights_file,'w');
						header("Location: ad_forum.php?mode=edit_forum_rights&forum_id=$forum_id&$HSID"); exit;
					break;
				}
				break;
			}
		}
	break;

	case 'new_group_right':
		$forums_file = myfile('vars/foren.var'); $groups_file = myfile('vars/groups.var');
		if(sizeof($groups_file) == 0) { // Erst überprüfen, ob überhaupt Gruppen exisitieren
			include("pageheader.php");
			echo adnavbar("<a class=\"navbar\" href=\"ad_forum.php?mode=forumview$MYSID2\">".$lng['ad_forum']['Edit_Forums']."</a>\t<a class=\"navbar\" href=\"ad_forum.php?ad_forum_id=$forum_id&mode=change$MYSID2\">Forum #$forum_id bearbeiten</a>\t<a class=\"navbar\" href=\"ad_forum.php?mode=edit_forum_rights&forum_id=$forum_id$MYSID2\">".$lng['ad_forum']['Edit_special_rights']."</a>\t".$lng['templates']['no_groups'][0]);
			echo get_message('no_groups');
		}
		else {
			for($i = 0; $i < sizeof($forums_file); $i++) {
				$akt_forum = myexplode($forums_file[$i]);
				if($akt_forum[0] == $forum_id) {
					$rights_file = myfile("foren/$forum_id-rights.xbb");
					$akt_forum_rights = explode(',',$akt_forum[10]);

					// Erst wird geprüft, wieviele Gruppen schon "registriert" sind
					$group_counter = 0;
					$forum_groups = array();
					for($j = 0; $j < sizeof($rights_file); $j++) {
						$akt_right = myexplode($rights_file[$j]);
						if($akt_right[1] == 2) {
							$group_counter++;
							$forum_groups[] = $akt_right[2]; // Jetzt schonmal für eventuell später alle Gruppen-IDs in Array sammeln
						}
					}
					if(sizeof($groups_file) == $group_counter) { // Falls schon alle Gruppen eingetragen sind
						include("pageheader.php");
						echo adnavbar("<a class=\"navbar\" href=\"ad_forum.php?mode=forumview$MYSID2\">".$lng['ad_forum']['Edit_Forums']."</a>\t<a class=\"navbar\" href=\"ad_forum.php?ad_forum_id=$forum_id&mode=change$MYSID2\">Forum #$forum_id bearbeiten</a>\t<a class=\"navbar\" href=\"ad_forum.php?mode=edit_forum_rights&forum_id=$forum_id$MYSID2\">".$lng['ad_forum']['Edit_special_rights']."</a>\t".$lng['templates']['all_groups_srights'][0]);
						echo get_message('all_groups_srights');
					}
					else {
						$error = '';
						if($add) {
							if(in_array($new_group_id,$forum_groups)) $error = $lng['ad_forum']['error']['This_group_has_already_got_special_rights']; // Falls diese Gruppe schon eingetragen ist
							else {
								for($j = 0; $j < sizeof($groups_file); $j++) {
									$akt_group = myexplode($groups_file[$j]);
									if($akt_group[0] == $new_group_id) {
										if($akt_group[5] == '') $akt_group[5] = $forum_id;
										else {
											$akt_group_forums = explode(',',$akt_group[5]);
											if(!in_array($forum_id,$akt_group_forums)) { // Wenn Forum noch nicht eingetragen ist (und es sollte auch noch nicht eingetragen sein!), eintragen
												if($akt_group[5] == '') $akt_group[5] = $forum_id;
												else {
													$akt_group_forums[] = $forum_id;
													$akt_group[5] = implode(',',$akt_group_forums);
												}
											}
										}
										$groups_file[$j] = myimplode($akt_group);
										myfwrite('vars/groups.var',$groups_file,'w');
										$new_id = myexplode($rights_file[sizeof($rights_file)-1]); $new_id = $new_id[0] + 1;
										$towrite = "$new_id\t2\t$new_group_id\t$new_right[0]\t$new_right[1]\t$new_right[2]\t$new_right[3]\t$new_right[4]\t$new_right[5]\t\t\t\t\t\t\r\n";
										myfwrite("foren/$forum_id-rights.xbb",$towrite,'a');
										header("Location: ad_forum.php?mode=edit_forum_rights&forum_id=$forum_id&$HSID"); exit;
										break;
									}
								}
							}
						}
						include("pageheader.php");
						echo adnavbar("<a class=\"navbar\" href=\"ad_forum.php?mode=forumview$MYSID2\">".$lng['ad_forum']['Edit_Forums']."</a>\t<a class=\"navbar\" href=\"ad_forum.php?ad_forum_id=$forum_id&mode=change$MYSID2\">Forum #$forum_id bearbeiten</a>\t<a class=\"navbar\" href=\"ad_forum.php?mode=edit_forum_rights&forum_id=$forum_id$MYSID2\">".$lng['ad_forum']['Edit_special_rights']."</a>\t".$lng['ad_forum']['Add_group_right']);
						?>
							<form method="post" action="ad_forum.php?mode=new_group_right&forum_id=<?=$forum_id?><?=$MYSID2?>"><input type="hidden" name="add" value="yes">
							<table class="tbl" border="0" cellpadding="<?=$tpadding?>" cellspacing="<?=$tspacing?>" width="<?=$twidth?>">
							<tr><th colspan="2" class="thnorm"><span class="thnorm"><?=$lng['ad_forum']['Add_group_right']?></span></th></tr>
							<? if($error != '') echo "<tr><td colspan=\"2\" class=\"td1\"><span class=\"error\">$error</span></td></tr>"; ?>
							<tr>
							 <td class="td1"><span class="norm"><b><?=$lng['Group']?>:</b></span></td>
							 <td class="td1"><select size="1" name="new_group_id">
						<?
						for($j = 0; $j < sizeof($groups_file); $j++) {
							$akt_group = myexplode($groups_file[$j]);;
							if(!in_array($akt_group[0],$forum_groups)) echo "<option value=\"$akt_group[0]\">$akt_group[1]</option>";
						}
						?>
							 </select
							 </td>
							</tr>
							<tr>
							 <td class="td1" valign="top"><span class="norm"><b><?=$lng['ad_forum']['Rights']?>:</b></span></td>
							 <td class="td1"><input type="checkbox" value="1" name="new_right[0]" onfocus="this.blur()"<? if($akt_forum_rights[0] == 1) echo " checked"; ?>> <span class="norm"><?=$lng['ad_forum']['Is_allowed_to_access_forum']?></span><br><input value="1" type="checkbox" name="new_right[1]" onfocus="this.blur()"<? if($akt_forum_rights[1] == 1) echo " checked"; ?>> <span class="norm"><?=$lng['ad_forum']['Is_allowed_to_post_topics']?></span><br><input value="1" type="checkbox" name="new_right[2]" onfocus="this.blur()"<? if($akt_forum_rights[2] == 1) echo " checked"; ?>> <span class="norm"><?=$lng['ad_forum']['Is_allowed_to_post_replies']?></span><br><input type="checkbox" value="1" name="new_right[3]" onfocus="this.blur()"<? if($akt_forum_rights[3] == 1) echo " checked"; ?>> <span class="norm"><?=$lng['ad_forum']['Is_allowed_to_post_polls']?></span><br><input type="checkbox" value="1" name="new_right[4]" onfocus="this.blur()"<? if($akt_forum_rights[4] == 1) echo " checked"; ?>> <span class="norm"><?=$lng['ad_forum']['Is_allowed_to_edit_own_posts']?></span><br><input value="1" type="checkbox" name="new_right[5]" onfocus="this.blur()"<? if($akt_forum_rights[5] == 1) echo " checked"; ?>> <span class="norm"><?=$lng['ad_forum']['Is_allowed_to_edit_own_polls']?></span></td>
							</tr>
							</table><br><input type="submit" value="<?=$lng['ad_forum']['Add_group_right']?>">
						<?
					}
					break;
				}
			}
		}
	break;

	case 'edit_forum_rights':
		$rights = myfile("foren/$forum_id-rights.xbb");
		if(!is_array($rights)) $rights = array();
		if(isset($change)) {
			if(isset($new_rights)) {
				ksort($new_rights,SORT_NUMERIC); // Erst muss das Array sortiert werden, da Forumrechte ohne ID-Datei arbeiten
				for($i = 0; $i < sizeof($rights); $i++) {
					$akt_right = myexplode($rights[$i]);
					if(isset($new_rights[$akt_right[0]][0])) {
						$akt_right[3] = $new_rights[$akt_right[0]][0];
						$akt_right[4] = $new_rights[$akt_right[0]][1];
						$akt_right[5] = $new_rights[$akt_right[0]][2];
						$akt_right[6] = $new_rights[$akt_right[0]][3];
						$akt_right[7] = $new_rights[$akt_right[0]][4];
						$akt_right[8] = $new_rights[$akt_right[0]][5];
						$rights[$i] = myimplode($akt_right);
					}
				}
				myfwrite("foren/$forum_id-rights.xbb",$rights,'w');
			}
		}
			include('pageheader.php');
			echo adnavbar("<a class=\"navbar\" href=\"ad_forum.php?mode=forumview$MYSID2\">".$lng['ad_forum']['Edit_Forums']."</a>\t<a class=\"navbar\" href=\"ad_forum.php?ad_forum_id=$forum_id&mode=change$MYSID2\">".$lng['ad_forum']['Edit_Forum']."</a>\t".$lng['ad_forum']['Edit_special_rights']);
			?>
				<form method="post" action="ad_forum.php?mode=edit_forum_rights&forum_id=<?=$forum_id?><?=$MYSID2?>"><input type="hidden" name="change" value="yes">
				<table class="tbl" width="<?=$twidth?>" border=0 cellpadding="<?=$tpadding?>" cellspacing="<?=$tspacing?>">
				<tr>
				 <th class="thsmall"><span class="thsmall"><?=$lng['ad_forum']['User_Group']?></span></th>
				 <th class="thsmall"><span class="thsmall"><?=$lng['ad_forum']['Access_Forum']?></span></th>
				 <th class="thsmall"><span class="thsmall"><?=$lng['ad_forum']['Post_Topics']?></span></th>
				 <th class="thsmall"><span class="thsmall"><?=$lng['ad_forum']['Post_Replies']?></span></th>
				 <th class="thsmall"><span class="thsmall"><?=$lng['ad_forum']['Post_Polls']?></span></th>
				 <th class="thsmall"><span class="thsmall"><?=$lng['ad_forum']['Edit_Own_Posts']?></span></th>
				 <th class="thsmall"><span class="thsmall"><?=$lng['ad_forum']['Edit_Own_Polls']?></span></th>
				 <th class="thsmall"></th>
				</tr>
				<tr><td class="kat" colspan="8"><span class="kat"><?=$lng['ad_forum']['User_Rights']?></span></td></tr>
			<?
			$x = 0;
			while($akt_value = each($rights)) {
				$akt_right = myexplode($akt_value[1]);
				if($akt_right[1] == 1) {
					echo "<input type=\"hidden\" name=\"new_rights[$akt_right[0]][type]\" value=\"$akt_right[1]\">";
					echo "<input type=\"hidden\" name=\"new_rights[$akt_right[0]][target]\" value=\"$akt_right[2]\">";
					if($akt_right[3] == 1) $checked[0] = ' checked'; else $checked[0] = '';
					if($akt_right[4] == 1) $checked[1] = ' checked'; else $checked[1] = '';
					if($akt_right[5] == 1) $checked[2] = ' checked'; else $checked[2] = '';
					if($akt_right[6] == 1) $checked[3] = ' checked'; else $checked[3] = '';
					if($akt_right[7] == 1) $checked[4] = ' checked'; else $checked[4] = '';
					if($akt_right[8] == 1) $checked[5] = ' checked'; else $checked[5] = '';
					?>
						<tr>
						 <td class="td1" align="center"><span class="small"><?=get_user_name($akt_right[2])?></span></td>
						 <td class="td1" align="center"><span class="small"><input type="checkbox" value="1" name="new_rights[<?=$akt_right[0]?>][0]"<?=$checked[0]?> onfocus="this.blur()"></span></td>
						 <td class="td1" align="center"><span class="small"><input type="checkbox" value="1" name="new_rights[<?=$akt_right[0]?>][1]"<?=$checked[1]?> onfocus="this.blur()"></span></td>
						 <td class="td1" align="center"><span class="small"><input type="checkbox" value="1" name="new_rights[<?=$akt_right[0]?>][2]"<?=$checked[2]?> onfocus="this.blur()"></span></td>
						 <td class="td1" align="center"><span class="small"><input type="checkbox" value="1" name="new_rights[<?=$akt_right[0]?>][3]"<?=$checked[3]?> onfocus="this.blur()"></span></td>
						 <td class="td1" align="center"><span class="small"><input type="checkbox" value="1" name="new_rights[<?=$akt_right[0]?>][4]"<?=$checked[4]?> onfocus="this.blur()"></span></td>
						 <td class="td1" align="center"><span class="small"><input type="checkbox" value="1" name="new_rights[<?=$akt_right[0]?>][5]"<?=$checked[5]?> onfocus="this.blur()"></span></td>
						 <td class="td1" align="center"><span class="small"><a class="small" href="ad_forum.php?mode=kill_right&forum_id=<?=$forum_id?>&right_id=<?=$akt_right[0]?><?=$MYSID2?>"><?=$lng['delete']?></a></span></td>
						</tr>
					<?
					$x++;
					unset($rights[$akt_value[0]]);
				}
			}
			if($x == 0) echo "<tr><td class=\"td1\" colspan=\"8\"><span class=\"norm\"><center>".$lng['ad_forum']['No_user_rights']."</center></span></td></tr>";
			echo "<tr><td class=\"kat\" colspan=\"8\"><span class=\"kat\">".$lng['ad_forum']['Group_Rights']."</span></td></tr>";
			$x = 0;
			reset($rights); $groups_file = myfile('vars/groups.var');
			while($akt_value = each($rights)) {
				$akt_right = myexplode($akt_value[1]); $group_name = '';
				if($akt_right[1] == 2) {
					while($akt_value2 = each($groups_file)) {
						$akt_group = myexplode($akt_value2[1]);
						if($akt_group[0] == $akt_right[2]) {
							$group_name = $akt_group[1];
							unset($groups_file[$akt_value2[0]]);
							break;
						}
					}
					reset($groups_file);
					echo "<input type=\"hidden\" name=\"new_rights[$akt_right[0]][type]\" value=\"$akt_right[1]\">";
					echo "<input type=\"hidden\" name=\"new_rights[$akt_right[0]][target]\" value=\"$akt_right[2]\">";
					if($akt_right[3] == 1) $checked[0] = ' checked'; else $checked[0] = '';
					if($akt_right[4] == 1) $checked[1] = ' checked'; else $checked[1] = '';
					if($akt_right[5] == 1) $checked[2] = ' checked'; else $checked[2] = '';
					if($akt_right[6] == 1) $checked[3] = ' checked'; else $checked[3] = '';
					if($akt_right[7] == 1) $checked[4] = ' checked'; else $checked[4] = '';
					if($akt_right[8] == 1) $checked[5] = ' checked'; else $checked[5] = '';
					?>
						<tr>
						 <td class="td1" align="center"><span class="small"><?=$group_name?></span></td>
						 <td class="td1" align="center"><span class="small"><input type="checkbox" value="1" name="new_rights[<?=$akt_right[0]?>][0]"<?=$checked[0]?> onfocus="this.blur()"></span></td>
						 <td class="td1" align="center"><span class="small"><input type="checkbox" value="1" name="new_rights[<?=$akt_right[0]?>][1]"<?=$checked[1]?> onfocus="this.blur()"></span></td>
						 <td class="td1" align="center"><span class="small"><input type="checkbox" value="1" name="new_rights[<?=$akt_right[0]?>][2]"<?=$checked[2]?> onfocus="this.blur()"></span></td>
						 <td class="td1" align="center"><span class="small"><input type="checkbox" value="1" name="new_rights[<?=$akt_right[0]?>][3]"<?=$checked[3]?> onfocus="this.blur()"></span></td>
						 <td class="td1" align="center"><span class="small"><input type="checkbox" value="1" name="new_rights[<?=$akt_right[0]?>][4]"<?=$checked[4]?> onfocus="this.blur()"></span></td>
						 <td class="td1" align="center"><span class="small"><input type="checkbox" value="1" name="new_rights[<?=$akt_right[0]?>][5]"<?=$checked[5]?> onfocus="this.blur()"></span></td>
						 <td class="td1" align="center"><span class="small"><a class="small" href="ad_forum.php?mode=kill_right&forum_id=<?=$forum_id?>&right_id=<?=$akt_right[0]?><?=$MYSID2?>"><?=$lng['delete']?></a></span></td>
						</tr>
					<?
					$x++;
				}
			}
			if($x == 0) echo "<tr><td class=\"td1\" colspan=\"8\"><span class=\"norm\"><center>".$lng['ad_forum']['No_group_rights']."</center></span></td></tr>";
			echo "</table></center><span class=\"norm\"><a href=\"ad_forum.php?mode=new_user_right&forum_id=$forum_id$MYSID2\">".$lng['ad_forum']['Add_user_right']."</a> | <a href=\"ad_forum.php?mode=new_group_right&forum_id=$forum_id$MYSID2\">".$lng['ad_forum']['Add_group_right']."</a></span><center><br><input type=\"submit\" value=\"".$lng['ad_forum']['Edit_special_rights']."\"></form>";
	break;

	case "change": // Forum bearbeiten
		$foren = myfile('vars/foren.var'); $foren_size = sizeof($foren);
		if($change != "yes") {
			for($i = 0; $i < $foren_size; $i++) {
				$akt_forum = myexplode($foren[$i]);
				if($akt_forum[0] == $ad_forum_id) {
					$akt_forum_options = explode(',',$akt_forum[7]);
					$akt_forum_rights = explode(',',$akt_forum[10]);
					include("pageheader.php");
					echo adnavbar("<a class=\"navbar\" href=\"ad_forum.php?mode=forumview$MYSID2\">".$lng['ad_forum']['Edit_Forums']."</a>\t".$lng['ad_forum']['Edit_Forum']);
					?>
					<form method="post" action="ad_forum.php?mode=change&change=yes&ad_forum_id=<?=$akt_forum[0]?><?=$MYSID2?>">
					<table class="tbl" width="<?=$twidth?>" border=0 cellpadding="<?=$tpadding?>" cellspacing="<?=$tspacing?>">
					<tr><th class="thnorm" colspan="2"><span class="thnorm"><?=$lng['ad_forum']['Edit_Forum']?></span></th></tr>
					<tr><td class="kat" colspan="2"><span class="kat"><?=$lng['ad_forum']['General_information']?></span></td></tr>
					<tr>
					 <td class="td1"><span class="norm"><b><?=$lng['Title']?>:</b></span></td>
					 <td class="td1"><input type="text" name="titel" value="<?=$akt_forum[1]?>"></td>
					</tr>
					<tr>
					 <td class="td1"><span class="norm"><b><?=$lng['Description']?>:</b></span></td>
					 <td class="td1"><input type="text" name="beschreibung" size="50" value="<?=$akt_forum[2]?>"></td>
					</tr>
					<tr>
					 <td class="td1"><span class="norm"><b><?=$lng['Moderators']?>:</b></span></td>
					 <td class="td1"><input type="text" size="10" name="mods" value="<?=$akt_forum[11]?>"> <span class="small">(<?=$lng['ad_forum']['Seperate_the_mod_ids_with_commas']?>)</span></td>
					</tr>
					<tr>
					 <td class="td1"><span class="norm"><b><?=$lng['ad_forum']['Category']?>:</b></span></td>
					 <td class="td1"><select name="kg" size="1"><option value="-1" <? if($akt_forum[5] == "-1") echo "selected"; ?>><?=$lng['ad_forum']['No_Category']?></option>
					  <?
					  	$kgs = myfile("vars/kg.var");
					  	for($j = 0; $j < sizeof($kgs); $j++) {
					  		$akt_kg = myexplode($kgs[$j]);
					  		if($akt_forum[5] == $akt_kg[0]) {
					  			echo "<option value=\"$akt_kg[0]\" selected>$akt_kg[1]</option>";
					  		}
					  		else echo "<option value=\"$akt_kg[0]\">$akt_kg[1]</option>";
					  	}
					  ?>
					  </select>
					 </td>
					</tr>
					<tr><td class="kat" colspan=2><span class="kat"><?=$lng['ad_forum']['General_rights']?></span></td></tr>
					 <tr><td colspan="2" class="td1"><span class="norm"><input type="checkbox" name="new_rights[0]" value="1"<? if($akt_forum_rights[0] == 1) echo " checked"; ?> onfocus="this.blur()"> <?=$lng['ad_forum']['Members_are_allowed_to_access_forum']?></span></td></tr>
					 <tr><td colspan="2" class="td1"><span class="norm"><input type="checkbox" name="new_rights[1]" value="1"<? if($akt_forum_rights[1] == 1) echo " checked"; ?> onfocus="this.blur()"> <?=$lng['ad_forum']['Members_are_allowed_to_post_new_topics']?></span></td></tr>
					 <tr><td colspan="2" class="td1"><span class="norm"><input type="checkbox" name="new_rights[2]" value="1"<? if($akt_forum_rights[2] == 1) echo " checked"; ?> onfocus="this.blur()"> <?=$lng['ad_forum']['Members_are_allowed_to_post_replies']?></span></td></tr>
					 <tr><td colspan="2" class="td1"><span class="norm"><input type="checkbox" name="new_rights[3]" value="1"<? if($akt_forum_rights[3] == 1) echo " checked"; ?> onfocus="this.blur()"> <?=$lng['ad_forum']['Members_are_allowed_to_post_polls']?></span></td></tr>
					 <tr><td colspan="2" class="td1"><span class="norm"><input type="checkbox" name="new_rights[4]" value="1"<? if($akt_forum_rights[4] == 1) echo " checked"; ?> onfocus="this.blur()"> <?=$lng['ad_forum']['Members_are_allowed_to_edit_their_posts']?></span></td></tr>
					 <tr><td colspan="2" class="td1"><span class="norm"><input type="checkbox" name="new_rights[5]" value="1"<? if($akt_forum_rights[5] == 1) echo " checked"; ?> onfocus="this.blur()"> <?=$lng['ad_forum']['Members_are_allowed_to_edit_their_polls']?></span></td></tr>
					 <tr><td colspan="2" class="td1"><span class="norm"><input type="checkbox" name="new_rights[6]" value="1"<? if($akt_forum_rights[6] == 1) echo " checked"; ?> onfocus="this.blur()"> <?=$lng['ad_forum']['Guests_are_allowed_to_access_forum']?></span></td></tr>
					 <tr><td colspan="2" class="td1"><span class="norm"><input type="checkbox" name="new_rights[7]" value="1"<? if($akt_forum_rights[7] == 1) echo " checked"; ?> onfocus="this.blur()"> <?=$lng['ad_forum']['Guests_are_allowed_to_post_new_topics']?></span></td></tr>
					 <tr><td colspan="2" class="td1"><span class="norm"><input type="checkbox" name="new_rights[8]" value="1"<? if($akt_forum_rights[8] == 1) echo " checked"; ?> onfocus="this.blur()"> <?=$lng['ad_forum']['Guests_are_allowed_to_post_replies']?></span></td></tr>
					 <tr><td colspan="2" class="td1"><span class="norm"><input type="checkbox" name="new_rights[9]" value="1"<? if($akt_forum_rights[9] == 1) echo " checked"; ?> onfocus="this.blur()"> <?=$lng['ad_forum']['Guests_are_allowed_to_post_polls']?></span></td></tr>
					 <tr><td colspan="2" class="td1"><span class="norm"><a class="norm" href="ad_forum.php?mode=edit_forum_rights&forum_id=<?=$akt_forum[0]?><?=$MYSID2?>"><?=$lng['ad_forum']['Edit_special_rights']?></a></span></td></tr>
					<tr><td class="kat" colspan=2><span class="kat"><?=$lng['Options']?></span></td></tr>
					 <tr><td colspan="2" class="td1"><span class="norm"><input type="checkbox" name="upbcode" value="1"<? if($akt_forum_options[0] == 1) echo " checked"; ?> onfocus="this.blur()"> <?=$lng['ad_forum']['Enable_TBB_Code']?></span></td></tr>
					 <tr><td colspan="2" class="td1"><span class="norm"><input type="checkbox" name="htmlcode" value="1"<? if($akt_forum_options[1] == 1) echo " checked"; ?> onfocus="this.blur()"> <?=$lng['ad_forum']['Enable_HTML_Code']?></span></td></tr>
					 <tr><td colspan="2" class="td1"><span class="norm"><input type="checkbox" name="sm_mods" value="1"<? if($akt_forum_options[2] == 1) echo " checked"; ?> onfocus="this.blur()"> <?=$lng['ad_forum']['Notify_mods_about_new_topics']?></span></td></tr>
					</table><br><input type="submit" value="<?=$lng['ad_forum']['Edit_Forum']?>">&nbsp;&nbsp;&nbsp;<input type="submit" name="kill" value="Forum löschen"></center></form>
					<?
					break;
				}
			}
		}
		else {
			if(isset($kill)) {
				$forum_data = get_forum_data($ad_forum_id);
				if($confirm == "yes") { // Let's rock :)
					$free_space_counter = 0; $topics_file = myfile("foren/$forum_data[id]-threads.xbb");

					// Erst Foreninfos aus der Forendatei löschen
					$forums_file = myfile("vars/foren.var");
					for($i = 0; $i < sizeof($forums_file); $i++) {
						$akt_forum = myexplode($forums_file[$i]);
						if($akt_forum[0] == $forum_data[id]) {
							$forums_file[$i] = ""; $save = 1;
							break;
						}
					}
					if($save == 1) myfwrite("vars/foren.var",$forums_file,"w");
					else die("error deleting the forum. it has not been deleted!"); // Bei einem Fehler direkt alles komplett abbrechen (zur Sicherheit)

					// Foren eventuell aus Gruppen entfernen
					$group_save = 0;
					$rights_file = myfile("foren/$forum_data[id]-rights.xbb"); $groups_file = myfile("vars/groups.var");
					for($i = 0; $i < sizeof($rights_file); $i++) {
						$akt_right = myexplode($rights_file[$i]);
						if($akt_right[1] == 2) {
							for($j = 0; $j < sizeof($groups_file); $j++) {
								$akt_group = myexplode($groups_file[$j]);
								if($akt_group[0] == $akt_right[2]) {
									$akt_group_forums = explode(',',$akt_group[5]);
									for($k = 0; $k < sizeof($akt_group_forums); $k++) {
										if($akt_group_forums[$k] == $forum_data['id']) {
											unset($akt_group_forums[$k]);
											$akt_group[5] = implode(',',$akt_group_forums);
											$groups_file[$j] = myimplode($akt_group);
											$group_save = 0;
											break;
										}
									}
									break;
								}
							}
						}
					}

					// Forendateien löschen
					$free_space_counter += filesize("foren/$forum_data[id]-threads.xbb") + filesize("foren/$forum_data[id]-ltopic.xbb") + filesize("foren/$forum_data[id]-rights.xbb");
					unlink("foren/$forum_data[id]-threads.xbb"); unlink("foren/$forum_data[id]-ltopic.xbb"); unlink("foren/$forum_data[id]-rights.xbb");

					// Themen löschen
					for($i = 0; $i < sizeof($topics_file); $i++) {
						$akt_topic = killnl($topics_file[$i]);
						$akt_topic_file = myfile("foren/$forum_data[id]-$akt_topic.xbb");
						$akt_topic_data = myexplode($akt_topic_file[0]);
						if($akt_topic_data[7] != '') { // Falls Umfrage existiert diese auch löschen
							$free_space_counter += filesize("polls/$akt_topic_data[7]-1.xbb");
							$free_space_counter += filesize("polls/$akt_topic_data[7]-2.xbb");
							unlink("polls/$akt_topic_data[7]-1.xbb");
							unlink("polls/$akt_topic_data[7]-2.xbb");
						}
						$free_space_counter += filesize("foren/$forum_data[id]-$akt_topic.xbb");
						unlink("foren/$forum_data[id]-$akt_topic.xbb");
					}

					// Moderatoren umstellen
					if($forum_data[mods] != "") {
						$check_mods = explode(",",$forum_data[mods]); $check_mods_size = sizeof($check_mods);
						for($j = 0; $j < $check_mods_size; $j++) {
							if(get_real_user_status($check_mods[$j]) != 1) {
								if(check_if_mod($forum_data[id],$check_mods[$j]) != 1) {
									change_user_db($check_mods[$j],4,'3');
								}
							}
						}
					}

					mylog('8',"%1: Forum (ID: $forum_data[id]) gelöscht (IP: %2)");

					include("pageheader.php");
					echo adnavbar("<a class=\"navbar\" href=\"ad_forum.php?mode=forumview$MYSID2\">".$lng['ad_forum']['Edit_Forums']."</a>\t".$lng['ad_forum']['Forum_Deleted']);
					?>
						<table class="tbl" border=0 width="<?=$twidth?>" cellpadding="<?=$tpadding?>" cellspacing="<?=$tspacing?>">
						<tr><th class="thnorm"><span class="thnorm">Forum gelöscht</span></th></tr>
						<tr><td class="td1"><span class="norm">Das Forum wurde erfolgreich gelöscht!<br>Dabei wurden insgesamt <?=round($free_space_counter/1024)?> KB in <?=sizeof($topics_file)+1?> Dateien freigegeben (<?=$forum_data[posts]?> Posts in <?=$forum_data[topics]?> Themen).</span></td></tr>
						</table>
					<?
				}
				else {
					include("pageheader.php");
					echo adnavbar("<a class=\"navbar\" href=\"ad_forum.php?mode=forumview$MYSID2\">".$lng['ad_forum']['Edit_Forums']."</a>\tForum #$ad_forum_id löschen");
					?>
						<form method=post action="ad_forum.php?mode=change&change=yes&ad_forum_id=<?=$ad_forum_id?><?=$MYSID2?>"><input type="hidden" name="confirm" value="yes"><input type="hidden" name="kill" value="yes">
						<table class="tbl" border=0 width="<?=$twidth?>" cellpadding="<?=$tpadding?>" cellspacing="<?=$tspacing?>">
						<tr><th class="thnorm"><span class="thnorm"><?=$lng['ad_forum']['Delete_Forum']?></span></th></tr>
						<tr><td class="td1"><br><span class="norm"><center><?=sprintf($lng['ad_forum']['Really_delete_forum'],$forum_data['name'])?></center></span><br><br></td></tr>
						</table><br><input type="submit" value="<?=$lng['ad_forum']['Delete_Forum']?>"></form></center>
					<?
				}
			}
			else {
				$titel = trim(mutate($titel)); $beschreibung = trim(mutate($beschreibung));
				for($i = 0; $i < $foren_size; $i++) {
					$akt_forum = myexplode($foren[$i]);
					if($akt_forum[0] == $ad_forum_id) {

						// Moderatoren umstellen
						if($akt_forum[11] != "") {
							$check_mods = explode(",",$akt_forum[11]); $check_mods_size = sizeof($check_mods);
							for($j = 0; $j < $check_mods_size; $j++) {
								if(get_real_user_status($check_mods[$j]) != 1) {
									if(check_if_mod($akt_forum[0],$check_mods[$j]) != 1) {
										change_user_db($check_mods[$j],4,"3");
									}
								}
							}
						}
						if($mods != "") {
							$check_mods = explode(",",$mods); $check_mods_size = sizeof($check_mods);
							for($j = 0; $j < $check_mods_size; $j++) {
								if(get_real_user_status($check_mods[$j]) != 1) {
									if(check_if_mod($akt_forum[0],$check_mods[$j]) != 1) {
										change_user_db($check_mods[$j],4,"2");
									}
								}
							}
						}

						// Neue Werte zuweisen
						$akt_forum[10] = "$new_rights[0],$new_rights[1],$new_rights[2],$new_rights[3],$new_rights[4],$new_rights[5],$new_rights[6],$new_rights[7],$new_rights[8],$new_rights[9],,,,,";
						$akt_forum[8] = $op; $akt_forum[11] = $mods; $akt_forum[1] = $titel; $akt_forum[2] = $beschreibung; $akt_forum[5] = $kg; $akt_forum[7] = "$upbcode,$htmlcode,$sm_mods";
						$foren[$i] = myimplode($akt_forum); $save = 1; break;
					}
				}
				if($save == 1) {
					myfwrite("vars/foren.var",$foren,"w");
					header("Location: ad_forum.php?mode=forumview&$HSID");
					mylog("8","%1: Forum (ID: $ad_forum_id) bearbeitet (IP: %2)");
					exit;
				}
				else echo "Forum-Bearbeiten-Fehler!";
			}
		}
	break;

	case "moveforumup": // Forum eins nach oben schieben
		$foren = myfile("vars/foren.var"); $foren_anzahl = sizeof($foren);
		for($i = 0; $i < $foren_anzahl; $i++) {
			$aktuelles_forum = myexplode($foren[$i]);
			if($aktuelles_forum[0] == $id) {
				$foren_backup = $foren[($i - 1)]; $foren[($i - 1)] = $foren[$i]; $foren[$i] = $foren_backup;
				$dosave = "yes"; break;
			}
		}

		if($dosave == "yes") {
			myfwrite("vars/foren.var",$foren,"w");
			header ("Location: ad_forum.php?mode=forumview&$HSID"); exit;
		}
		else echo "Forum-Nachoben-Schieb-Fehler!";
	break;

	/*************\
	*  _      _   *
	* |_|    |_|  *
	*             *
	*     | |     *
	*     |_|     *
	*             *
	*   \_____/   *
	*             *
	\*************/

	case "moveforumdown": // Forum eins nach unten schieben
		$foren = myfile("vars/foren.var"); $foren_anzahl = sizeof($foren);
		for($i = 0; $i < $foren_anzahl; $i++) {
			$aktuelles_forum = myexplode($foren[$i]);
			if($aktuelles_forum[0] == $id) {
				$foren_backup = $foren[($i + 1)]; $foren[($i + 1)] = $foren[$i]; $foren[$i] = $foren_backup;
				$dosave = "yes"; break;
			}
		}

		if($dosave == "yes") {
			myfwrite("vars/foren.var",$foren,"w");
			header ("Location: ad_forum.php?mode=forumview&$HSID"); exit;
		}
		else echo "Forum-Nachunten-Schieb-Fehler!";
	break;

	case "viewkg": // Kategorien anzeigen
		include("pageheader.php");
		echo adnavbar($lng['ad_forum']['Edit_Categories']);
		?>
			</center><span class="norm"><a class="norm" href="ad_forum.php?mode=newkg<?=$MYSID2?>"><?=$lng['ad_forum']['Add_Category']?></a></span><center>
			<table class="tbl" border=0 cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>" width="<?=$twidth?>">
			<tr>
			 <!--<th class="thsmall"><span class="thsmall">ID</span></th>-->
			 <th class="thsmall"><span class="thsmall"><?=$lng['Description']?></span></th>
			 <th class="thsmall"><span class="thsmall"></span</th>
			 <th class="thsmall"><span class="thsmall"></span></th>
			</tr>
		<?
		$kg = myfile("vars/kg.var"); $kg_anzahl = sizeof($kg);
		if($kg_anzahl == 0) echo "<tr><td class=\"td1\" colspan=\"7\" align=\"center\"><span class=\"norm\">".$lng['ad_forum']['No_Category']."</span></td></tr>";
		else {
			for($i = 0; $i < $kg_anzahl; $i++) {
				$aktuelle_kg = myexplode($kg[$i]);
				if($kg_anzahl != 1) {
					if($i == 0) $moving =  "<a href=\"ad_forum.php?mode=movekgdown&id=$aktuelle_kg[0]$MYSID2\">&darr;</a>";
					elseif($i == $kg_anzahl - 1) $moving = "<a href=\"ad_forum.php?mode=movekgup&id=$aktuelle_kg[0]$MYSID2\">&uarr;</a>";
					else $moving = "<a href=\"ad_forum.php?mode=movekgdown&id=$aktuelle_kg[0]$MYSID2\">&darr;</a> | <a href=\"ad_forum.php?mode=movekgup&id=$aktuelle_kg[0]$MYSID2\">&uarr;</a>";
				}
				?>
					<tr>
					 <!--<td class="td1" align=center><span class="norm"><?=$aktuelle_kg[0]?></span></td>-->
					 <td class="td2"><span class="norm"><?=$aktuelle_kg[1]?></span></td>
					 <td class="td1" align=center><span class="norm"><?=$moving?></span></td>
					 <td class="td2" align=center><span class="norm"><a class="norm" href="ad_forum.php?mode=killkg&id=<?=$aktuelle_kg[0]?><?=$MYSID2?>"><?=$lng['delete']?></a> | <a class="norm" href="ad_forum.php?mode=chgkg&id=<?=$aktuelle_kg[0]?><?=$MYSID2?>"><?=$lng['edit']?></a></span></td>
					</tr>
				<?
			}
		}
		echo "</table></center><span class=\"norm\"><a class=\"norm\" href=\"ad_forum.php?mode=newkg$MYSID2\">".$lng['ad_forum']['Add_Category']."</a></span><center>";
	break;

	case "movekgup": // Kategorie eins nach oben schieben
		$kg = myfile("vars/kg.var"); $kg_anzahl = sizeof($kg);
		for($i = 0; $i < $kg_anzahl; $i++) {
			$aktuelle_kg = myexplode($kg[$i]);
			if($aktuelle_kg[0] == $id) {
				$kg_backup = $kg[($i - 1)]; $kg[($i - 1)] = $kg[$i]; $kg[$i] = $kg_backup;
				$dosave = "yes"; break;
			}
		}

		if($dosave == "yes") {
			myfwrite("vars/kg.var",$kg,"w");
			header ("Location: ad_forum.php?mode=viewkg&$HSID"); exit;
		}
		else echo "KG-Nachoben-Schieb-Fehler!";
	break;

	case "movekgdown": // Kategorie eins nach unten schieben
		$kg = myfile("vars/kg.var"); $kg_anzahl = sizeof($kg);
		for($i = 0; $i < $kg_anzahl; $i++) {
			$aktuelle_kg = myexplode($kg[$i]);
			if($aktuelle_kg[0] == $id) {
				$kg_backup = $kg[($i + 1)]; $kg[($i + 1)] = $kg[$i]; $kg[$i] = $kg_backup;
				$dosave = "yes"; break;
			}
		}

		if($dosave == "yes") {
			myfwrite("vars/kg.var",$kg,"w");
			header("Location: ad_forum.php?mode=viewkg&$HSID"); exit;
		}
		else echo "KG-Nachunten-Schieb-Fehler!";
	break;

	case "newkg": // Neue Kategorie erstellen
		if ($newkg != "yes") {
			include("pageheader.php");
			echo adnavbar("<a class=\"navbar\" href=\"ad_forum.php?mode=viewkg$MYSID2\">".$lng['ad_forum']['Edit_Categories']."</a>\t".$lng['ad_forum']['Add_Category']);
			?>
				<form method=post action="ad_forum.php?mode=newkg&newkg=yes<?=$MYSID2?>">
				<table class="tbl" width="<?=$twidth?>" cellspacing="<?=$tspacing?>" border=0 cellpadding="<?=$tpadding?>">
				<tr><th class="thnorm"><span class="thnorm"><?=$lng['ad_forum']['Add_Category']?></span></th></tr>
				<tr><td class="td1"><span class="norm"><b><?=$lng['Title']?>:</b></span> <input type="text" name="name"></td></tr>
				</table><br><input type="submit" value="<?=$lng['ad_forum']['Add_Category']?>"></form>
			<?
		}
		else {
			// Erst letzte ID rausfinden
			$letzte_id = myfile("vars/kgs.var");
			$neue_id = $letzte_id[0]+1;

			// Jetzt kann geschrieben werden
			$name = trim(mutate($name));
			$towrite = "$neue_id\t$name\t\r\n";
			myfwrite("vars/kg.var",$towrite,"a");

			// Letzte ID updaten
			myfwrite("vars/kgs.var",$neue_id,"w");

			mylog("8","%1: Neue Kategorie (ID: $neue_id) erstellt (IP: %2)");

			header ("Location: ad_forum.php?mode=viewkg&$HSID"); exit;
		}
	break;

	case "killkg": // Kategorie löschen
		$kg = myfile("vars/kg.var"); $kg_anzahl = sizeof($kg);
		for ($i = 0; $i < $kg_anzahl; $i++) {
			$aktueller_kg = myexplode($kg[$i]);
			if ($aktueller_kg[0] == $id) {
				$kg[$i] = "";
				$save = 1; break;
			}
		}

		if ($save == 1) {
			myfwrite("vars/kg.var",$kg,"w");
			mylog("8","%1: Kategorie (ID: $id) gelöscht (IP: %2)");
			header ("Location: ad_forum.php?mode=viewkg&$HSID"); exit;
		}
		else echo "fehler";
	break;

	case "chgkg": // Kategorie ändern
		$kg = myfile("vars/kg.var"); $kg_anzahl = sizeof($kg);
		if($chgkg != "yes") {
			include("pageheader.php");
			for($i = 0; $i < $kg_anzahl; $i++) {
				$aktuelle_kg = myexplode($kg[$i]);
				if($aktuelle_kg[0] == $id) {
					echo adnavbar("<a class=\"navbar\" href=\"ad_forum.php?mode=viewkg$MYSID2\">".$lng['ad_forum']['Edit_Categories']."</a>\t".sprintf($lng['ad_forum']['Edit_Category_X'],$aktuelle_kg[1]));
					?>
						<table class="tbl" width="<?=$twidth?>" cellspacing="<?=$tspacing?>" border=0 cellpadding="<?=$tpadding?>">
						<form method=post action="ad_forum.php?mode=chgkg&chgkg=yes<?=$MYSID2?>">
						<tr><th class="thnorm" colspan=2><span class="thnorm"><?=$lng['ad_forum']['Edit_Category']?></span></th></tr>
						<tr>
						 <td class="td1"><span class="norm"><b><?=$lng['Title']?>:</b></span></td>
						 <td class="td1"><input type="text" name="name" value="<?=$aktuelle_kg[1]?>"></td>
						</tr></table>
						<br><input type="hidden" name="id" value="<?=$id?>"><center><input type="submit" value="<?=$lng['ad_forum']['Edit_Category']?>"></center></form>
					<?
					break;
				}
			}
		}

		else {
			for($i = 0; $i < $kg_anzahl; $i++) {
				$aktuelle_kg = myexplode($kg[$i]);
				if($aktuelle_kg[0] == $id) {
					$aktuelle_kg[1] = mutate($name);
					$kg[$i] = myimplode($aktuelle_kg);
					$save = 1; break;
				}
			}

			if ($save == 1) {
				myfwrite("vars/kg.var",$kg,"w");
				mylog("8","%1: Kategorie (ID: $id) bearbeitet (IP: %2)");
				header ("Location: ad_forum.php?mode=viewkg&$HSID"); exit;
			}
			else echo "Fehler!";
		}
	break;
	}
}

wio_set("ad");
include("pagetail.php");
// O
?>