<?

/* ad_groups.php - Zum Bearbeiten der Gruppen (c) 2001-2002 Tritanium Scripts */

require_once("functions.php");
require_once("loadset.php");
require_once("auth.php");
ad();

if($user_logged_in != 1 || $user_data[status] != 1) {
	mylog("2","%1: Administrationszugriffversuch (IP: %2)");
	header("Location: ad_login.php?$HSID"); exit;
}
else {
	switch($mode) {
		default: /*** Übersicht anzeigen ***/
			$groups_file = myfile('vars/groups.var');
			include('pageheader.php');
			echo adnavbar($lng['ad_groups']['Group_management']);
			?>
				<table class="tbl" border="0" cellpadding="<?=$tpadding?>" cellspacing="<?=$tspacing?>" width="<?=$twidth?>">
				<tr>
				 <th class="thsmall"><span class="thsmall"><?=$lng['Title']?></span></th>
				 <th class="thsmall"><span class="thsmall"><?=$lng['Avatar']?></span></th>
				 <th class="thsmall"><span class="thsmall"><?=$lng['Members']?></span></th>
				 <th class="thsmall"><span class="thsmall"></span></th>
				</tr>
			<?
			if(sizeof($groups_file) == 0) echo "<tr><td colspan=\"4\" class=\"td1\"><span class=\"norm\"><center>".$lng['ad_groups']['No_groups']."</center></span></td></tr>";
			else {
				for($i = 0; $i < sizeof($groups_file); $i++) {
					$akt_group = myexplode($groups_file[$i]);
					if($akt_group[2] == '') $akt_group[2] = '('.$lng['ad_groups']['No_avatar'].')';
					else $akt_group[2] = "<img src=\"$akt_group[2]\" border=\"0\">";

					// Nun werden aus den IDs gegebenfalls Namen gemacht
					if($akt_group[3] == '') $group_members = '('.$lng['ad_groups']['No_members'].')';
					else {
						$group_members = explode(',',$akt_group[3]);
						for($j = 0; $j < sizeof($group_members); $j++) {
							$group_members[$j] = get_user_name($group_members[$j]);
						}
						$group_members = implode(', ',$group_members);
					}

					?>
						<tr>
						 <td class="td1" valign="top"><span class="small"><?=$akt_group[1]?></span></td>
						 <td class="td1" valign="top"><span class="small"><center><?=$akt_group[2]?></center></span></td>
						 <td class="td1" valign="top"><span class="small"><?=$group_members?></span></td>
						 <td class="td1" valign="top"><span class="small"><a href="ad_groups.php?mode=edit&group_id=<?=$akt_group[0]?><?=$MYSID2?>"><?=$lng['edit']?></a>&nbsp;|&nbsp;<a href="ad_groups.php?mode=kill&group_id=<?=$akt_group[0]?><?=$MYSID2?>"><?=$lng['delete']?></a></span></td>
						</tr>
					<?
				}
			}
			echo "</table></center><span class=\"norm\"><a href=\"ad_groups.php?mode=new$MYSID2\">".$lng['ad_groups']['Add_Group']."</a></span><center>";
		break;

		case 'new': /*** Neue Gruppe erstellen ***/
			if($create) {
				$title = mutate($title);
				$group_members = array_unique(explode(',',$group_members)); // sicherstellen, dass jeder User nur einmal angegeben wird
				$group_file = myfile("vars/groups.var");
				$new_id = myexplode($group_file[sizeof($group_file)-1]); $new_id = $new_id[0] + 1;

				// Als erstes ungültige "Einträge" löschen und "die anderen in der Userdatei eintragen" (keine Ahnung, wie ich das ausdrücken soll... :)
				while($akt_value = each($group_members)) {
					if(!$akt_member = myfile("members/$akt_value[1].xbb")) unset($group_members[$akt_value[0]]); // Wenn User nicht existiert löschen
					elseif(killnl($akt_member[15]) != "") unset($group_members[$akt_value[0]]); // Wenn der User schon Mitglied einer Gruppe ist, soll er gelöscht werden
					else {
						$akt_member[15] = "$new_id\r\n"; // Gruppe bei User hinzufügen
						myfwrite("members/$akt_value[1].xbb",$akt_member,"w"); // User speichern
					}
				}
				$group_members = implode(',',$group_members); // Das ganze wieder in eine String umwandeln
				$towrite = "$new_id\t$title\t$pic\t$group_members\t\t\t\t\t\t\t\t\t\t\n";
				myfwrite('vars/groups.var',$towrite,'a');
				header("Location: ad_groups.php"); exit;
			}

			include('pageheader.php');
			echo adnavbar("<a class=\"navbar\" href=\"ad_groups.php$MYSID1\">".$lng['ad_groups']['Group_management']."</a>\t".$lng['ad_groups']['Add_Group']);
			?>
				<form method="post" action="ad_groups.php?mode=new<?=$MYSID2?>"><input type="hidden" name="create" value="yes">
				<table class="tbl" border="0" cellpadding="<?=$tpadding?>" cellspacing="<?=$tspacing?>" width="<?=$twidth?>">
				<tr><th class="thnorm" colspan="2"><span class="thnorm"><?=$lng['ad_groups']['Add_Group']?></span></th></tr>
				<tr>
				 <td class="td1" width="20%"><span class="norm"><b><?=$lng['Title']?>:</b></a></td>
				 <td class="td1" width="80%"><input type="text" name="title"></td>
				</tr>
				<tr>
				 <td class="td1" width="20%"><span class="norm"><b><?=$lng['Avatar']?>:</b><br><span class="small"><?=$lng['ad_groups']['Avatar_description']?></span></a></td>
				 <td class="td1" width="80%" valign="top"><input type="text" name="pic"> <span class="small">(<?=$lng['ad_groups']['URL_or_Path']?>)</span></td>
				</tr>
				<tr>
				 <td class="td1" width="20%"><span class="norm"><b><?=$lng['Members']?>:</b></a></td>
				 <td class="td1" width="80%"><input type="text" name="group_members"> <span class="small">(<?=$lng['ad_groups']['Seperate_the_users_ids_with_commas']?>)</span></td>
				</tr>
				</table><br><input type="submit" value="<?=$lng['ad_groups']['Add_Group']?>">
			<?
		break;

		case 'kill':
			$groups_file = myfile('vars/groups.var');
			for($i = 0; $i < sizeof($groups_file); $i++) {
				$akt_group = myexplode($groups_file[$i]);
				if($akt_group[0] == $group_id) {
					if($kill) {

						// Erst wird die Gruppen aus den Userdateien gelöscht
						$group_members = explode(',',$akt_group[3]);
						for($j = 0; $j < sizeof($group_members); $j++) {
							change_user_db($group_members[$j],15,'');
						}

						// Jetzt wird die Gruppe aus den Forendateien gelöscht
						$group_forums = explode(',',$akt_group[5]);
						for($j = 0; $j < sizeof($group_forums); $j++) {
							$akt_forum_rfile = myfile("foren/$group_forums[$j]-rights.xbb");
							for($k = 0; $k < sizeof($akt_forum_rfile); $k++) {
								$akt_right = myexplode($akt_forum_rfile[$k]);
								if($akt_right[1] == 2 && $akt_right[2] == $akt_group[0]) {
									$akt_forum_rfile[$k] = '';
									myfwrite("foren/$group_forums[$j]-rights.xbb",$akt_forum_rfile,'w');
									break;
								}
							}
						}

						// Und jetzt erst aus der Gruppendatei
						$groups_file[$i] = '';
						myfwrite('vars/groups.var',$groups_file,'w');
						header("Location: ad_groups.php?$HSID"); exit;
					}

					include('pageheader.php');
					echo adnavbar("<a class=\"navbar\" href=\"ad_groups.php$MYSID1\">".$lng['ad_groups']['Group_management']."</a>\t".$lng['ad_groups']['Delete_Group']);
					?>
						<form method="post" action="ad_groups.php?mode=kill&group_id=<?=$group_id?><?=$MYSID2?>"><input type="hidden" name="kill" value="yes">
						<table class="tbl" border="0" cellpadding="<?=$tpadding?>" cellspacing="<?=$tspacing?>" width="<?=$twidth?>">
						<tr><th class="thnorm" colspan="2"><span class="thnorm"><?=$lng['ad_groups']['Delete_Group']?></span></th></tr>
						<tr><td class="td1"><center><br><span class="norm"><?=sprintf($lng['ad_groups']['Really_delete'],$akt_group[1])?><br><br></center></td></tr>
						</table><br><input type="submit" value="<?=$lng['ad_groups']['Delete_Group']?>">
					<?
					break;
				}
			}
		break;

		case 'edit': /*** Gruppe editieren ***/
			$groups_file = myfile('vars/groups.var');
			if($update) {
				for($i = 0; $i < sizeof($groups_file); $i++) {
					$akt_group = myexplode($groups_file[$i]);
					if($akt_group[0] == $group_id) {
						$title = mutate($title);
						$group_members = array_unique(explode(',',$group_members));
						$akt_group_members = explode(',',$akt_group[3]);
						for($j = 0; $j < sizeof($akt_group_members); $j++) {
							change_user_db($akt_group_members[$j],15,''); // Erst werden alle (mir fällt schon wieder nichts ein...) gelöscht
						}
						while($akt_value = each($group_members)) {
							if(!$akt_member = myfile("members/$akt_value[1].xbb")) unset($group_members[$akt_value[0]]);
							elseif(killnl($akt_member[15]) != '') unset($group_members[$akt_value[0]]);
							else {
								$akt_member[15] = "$group_id\r\n";
								myfwrite("members/$akt_value[1].xbb",$akt_member,'w');
							}
						}
						$group_members = implode(',',$group_members);
						$akt_group[1] = $title;
						$akt_group[2] = $pic;
						$akt_group[3] = $group_members;
						$groups_file[$i] = myimplode($akt_group);
						myfwrite('vars/groups.var',$groups_file,'w');
						header("Location: ad_groups.php?$HSID"); exit;
						break;
					}
				}
			}


			for($i = 0; $i < sizeof($groups_file); $i++) {
				$akt_group = myexplode($groups_file[$i]);
				if($akt_group[0] == $group_id) {
					include('pageheader.php');
					echo adnavbar("<a class=\"navbar\" href=\"ad_groups.php$MYSID1\">Gruppen verwalten</a>\t".$lng['ad_groups']['Edit_Group']);
					?>
						<form method="post" action="ad_groups.php?mode=edit&group_id=<?=$group_id?><?=$MYSID2?>"><input type="hidden" name="update" value="yes">
						<table class="tbl" border="0" cellpadding="<?=$tpadding?>" cellspacing="<?=$tspacing?>" width="<?=$twidth?>">
						<tr><th class="thnorm" colspan="2"><span class="thnorm"><?=$lng['ad_groups']['Edit_Group']?></span></th></tr>
						<tr>
						 <td class="td1" width="20%"><span class="norm"><b><?=$lng['Title']?>:</b></a></td>
						 <td class="td1" width="80%"><input type="text" value="<?=$akt_group[1]?>" name="title"></td>
						</tr>
						<tr>
						 <td class="td1" width="20%"><span class="norm"><b><?=$lng['Avatar']?>:</b><br><span class="small"><?=$lng['ad_groups']['Avatar_description']?></span></a></td>
						 <td class="td1" width="80%" valign="top"><input type="text" value="<?=$akt_group[2]?>" name="pic"> <span class="small">(<?=$lng['ad_groups']['URL_or_Path']?>)</span></td>
						</tr>
						<tr>
						 <td class="td1" width="20%"><span class="norm"><b><?=$lng['Members']?>:</b></a></td>
						 <td class="td1" width="80%"><input type="text" value="<?=$akt_group[3]?>" name="group_members"> <span class="small">(<?=$lng['ad_groups']['Seperate_the_users_ids_with_commas']?>)</span></td>
						</tr>
						</table><br><input type="submit" value="<?=$lng['ad_groups']['Edit_Group']?>">
					<?
					break;
				}
			}
		break;
	}
}

include('pagetail.php');
// V
?>