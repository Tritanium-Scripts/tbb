<?

/* ad_user.php - zum Verwalten der User (c) 2001-2002 Tritanium Scripts */

require_once("functions.php");
require_once("loadset.php");
require_once("auth.php");
ad();

if($user_logged_in != 1 || $user_data['status'] != 1) {
	mylog("2","%1: Administrationszugriffversuch (IP: %2)");
	header("Location: ad_login.php?$HSID"); exit;
}
else { // Nun hat der User Zugangsberechtigung

	if(!isset($mode)) $mode = 'search';

	function cmpsim($a,$b) {
	    if($a['sim']  == $b['sim']) {
	   	    if($a['id']  == $b['id']) return 0;
    		return ($a['id'] > $b['id']) ? 1 : -1;
	    }
	    return ($a['sim'] < $b['sim']) ? 1 : -1;
	}

	switch($mode) {

		case 'new':
			$showformular = 1;
			$error = '';
			if(isset($create)) {
				$mailnick = mysslashes($new['nick']);
				$new['nick'] = mutate(str_replace(' ','',$new['nick']));
				$new['pw1'] = mysslashes($new['pw1']); $new['pw2'] = mysslashes($new['pw2']);
				if($new['nick'] == '') $error = $lng['register']['error']['No_nick'];
				elseif(trim($new['email']) == '') $error = $lng['register']['error']['No_emailaddress'];
				elseif(trim($new['pw1']) == '') $error = $lng['register']['error']['No_password'];
				elseif($new['pw1'] != $new['pw2']) $error = $lng['register']['error']['Passwords_do_not_match'];
				elseif(check_name($new['nick'],0) == 1) $error = $lng['register']['error']['Nick_already_exists'];
				else {
					$showformular = 0;
					$date = date("Ym"); $new['pw1'] = mycrypt($new['pw1']);
					$new_id = myfile('vars/last_user_id.var'); $new_id = $new_id[0]+1;

					// Gruppe eventuell zuweisen
					if($new['group'] != '') {
						$group_file = myfile('vars/groups.var');
						for($i = 0; $i < sizeof($group_file); $i++) {
							$akt_group = myexplode($group_file[$i]);
							if($akt_group[0] == $new['group']) {
								if($akt_group[3] == '') $akt_group[4] = $new_id;
								else $akt_group[3] .= ",$new_id";
								$group_file[$i] = myimplode($akt_group);
								myfwrite('vars/groups.var',$group_file,'w');
								break;
							}
						}
					}
					$towrite = $new['nick']."\n".$new_id."\n".$new['pw1']."\n".$new['email']."\n3\n0\n".$date."\n\n\n\n\n0\n\n\n1,1\n".$new['group']."\n\n";
					myfwrite("members/$new_id.xbb",$towrite,'w'); myfwrite("members/$new_id.pm",'','w');
					$member_counter = myfile("vars/member_counter.var"); $member_counter = $member_counter[0]+1; myfwrite("vars/member_counter.var",$member_counter,'w');
					myfwrite("vars/last_user_id.var",$new_id,'w');
					if($new['send_reg'] == 1) {
							$search = array('{USERNAME}','{FORUMNAME}','{USERPW}','{FORUMLINK}');
							$replace = array($mailnick,$config['forum_name'],$new['pw2'],$config['address_to_forum'].'/index.php');
							$email_file = myfread($config['lng_folder'].'/mails/registration.dat');
							$email_file = str_replace($search,$replace,$email_file);
							mymail($newuser_email,sprintf($lng['mail_subjects']['registration'],$config['forum_name']),$email_file); // Registrierung per Mail verschicken
					}
					include('pageheader.php');
					echo adnavbar("<a class=\"navbar\" href=\"ad_user.php$MYSID1\">".$lng['ad_user']['Membersearch']."</a>\t<a class=\"navbar\" href=\"ad_user.php?mode=new$MYSID2\">".$lng['ad_user']['Create_member']."</a>\t".$lng['templates']['member_created'][0]);
					echo get_message('member_created');
				}
			}
			if($showformular == 1) {
				include('pageheader.php');
				echo adnavbar("<a class=\"navbar\" href=\"ad_user.php$MYSID1\">".$lng['ad_user']['Membersearch']."</a>\t".$lng['ad_user']['Create_member']);
				?>
					<form method="post" action="ad_user.php?mode=new&create=yes<?=$MYSID2?>">
					<table class="tbl" border=0 cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>" width=100%>
					<tr><th class="thnorm" colspan="2"><span class="thnorm"><?=$lng['ad_user']['Create_member']?></span></th></tr>
					<? if($error != "") echo "<tr><td class=\"td1\" colspan=\"2\"><span class=\"error\">$error</span></td></tr>"; ?>
					<tr><td colspan="2" class="kat"><span class="kat"><?=$lng['ad_user']['Userdata']?></span></td></tr>
					<tr>
					 <td width="30%" class="td1"><span class="norm"><b><?=$lng['Nick']?>:</b></span></td>
					 <td width="70%" class="td1"><input type="text" name="new[nick]" value="<?=$new['nick']?>"></td>
					</tr>
					<tr>
					 <td width="30%" class="td1"><span class="norm"><b><?=$lng['Emailaddress']?>:</b></span></td>
					 <td width="70%" class="td1"><input type="text" name="new[email]" value="<?=$new['email']?>"></td>
					</tr>
					<tr>
					 <td width="30%" class="td1"><span class="norm"><b><?=$lng['Password']?>:</b></span></td>
					 <td width="70%" class="td1"><input type="password" name="new[pw1]"></td>
					</tr>
					<tr>
					 <td width="30%" class="td1"><span class="norm"><b><?=$lng['Confirm_Password']?>:</b></span></td>
					 <td width="70%" class="td1"><input type="password" name="new[pw2]"></td>
					</tr>
					<tr>
					 <td width="30%" class="td1"><span class="norm"><b><?=$lng['Group']?>:</b></span></td>
					 <td width="70%" class="td1"><select name="new[group]"><option value=""<? if(!isset($new['group'])) echo ' selected' ?>><?=$lng['ad_user']['No_group']?></option>
				<?
					$group_file = myfile('vars/groups.var');
					for($i = 0; $i < sizeof($group_file); $i++) {
						$akt_group = myexplode($group_file[$i]);
						if(isset($new['group'])) {
							if($new['group'] == $akt_group[0]) $selected = ' selected';
							else $selected = '';
						}
						echo "<option value=\"$akt_group[0]\"$selected>$akt_group[1]</option>";
					}
				?>
					 </select</td>
					</tr>
					<tr><td colspan="2" class="kat"><span class="kat"><?=$lng['Options']?></span></td></tr>
					<tr><td colspan="2" class="td1"><input type="checkbox" name="new[send_reg]" value="1" checked> <span class="norm"><?=$lng['ad_user']['Notify_new_member_by_mail']?></span></td></tr>
					</table><br><input type="submit" value="<?=$lng['ad_user']['Create_member']?>"></form>
				<?
			}
		break;

		case 'edit':
			$showformular = 1;
			$fehler = '';
			if(!$user_daten = myfile("members/$id.xbb")) {
				header("Location: ad_user.php?$HSID"); exit;
			}
			if(isset($edit)) {
				if(isset($kill)) {
					$user_daten[15] = killnl($user_daten[15]);
					$user_daten[1] = killnl($user_daten[1]);

					// Eventuell User aus einer Gruppe löschen
					if($user_daten[15] != '') {
						$groups_file = myfile('vars/groups.var');
						for($i = 0; $i < sizeof($groups_file); $i++) {
							$akt_group = myexplode($groups_file[$i]);
							if($akt_group[0] == $user_daten[15]) {
								$akt_group_members = explode(',',$akt_group[3]);
								for($j = 0; $j < sizeof($akt_group_members); $j++) {
									if($akt_group_members[$j] == $user_daten[1]) {
										unset($akt_group_members[$j]);
										$akt_group[3] = implode(',',$akt_group_members);
										$groups_file[$i] = myimplode($akt_group);
										myfwrite("vars/groups.var",$groups_file,'w');
										break;
									}
								}
								break;
							}
						}
					}

					$showformular = 0; $member_counter = myfile("vars/member_counter.var"); $member_counter = $member_counter[0]-1;
					unlink("members/$id.xbb"); unlink("members/$id.pm"); myfwrite("vars/member_counter.var",$member_counter,'w');
					mylog("8","%1: Administration: User \"".killnl($user_daten[0])."\" (ID: ".killnl($user_daten[1]).") gelöscht (IP: %2)");
					include("pageheader.php");
					echo adnavbar("<a class=\"navbar\" href=\"ad_user.php$MYSID1\">".$lng['ad_user']['Membersearch']."</a>\t".$lng['templates']['member_deleted'][0]);
					echo get_message('member_deleted');
				}
				else {
					if(check_name($name,killnl($user_daten[1])) == 1) {
						$fehler = $lng['ad_user']['Nick_already_exists'];
					}
					else {
						$showformular = 0;
						$user_daten[0] = mutate($name)."\n";
						//$user_daten[8] = $access."\n";
						$user_daten[4] = mutate($status)."\n";
						$user_daten[7] = nlbr(mutate($signatur))."\n";
						$user_daten[3] = mutate($email)."\n";
						$user_daten[10] = mutate($pic)."\n";
						mylog("8","%1: Administration: User \"".killnl($user_daten[0])."\" (ID: ".killnl($user_daten[1]).") bearbeitet (IP: %2)");
						myfwrite("members/$id.xbb",$user_daten,"w");
						include("pageheader.php"); echo adnavbar("<a class=\"navbar\" href=\"ad_user.php$MYSID1\">".$lng['ad_user']['Membersearch']."</a>\t".$lng['templates']['member_edited'][0]);
						echo get_message('member_edited');
					}
				}
			}
			if($showformular == 1) {
				include("pageheader.php");
				echo adnavbar("<a class=\"navbar\" href=\"ad_user.php$MYSID1\">".$lng['ad_user']['Membersearch']."</a>\t".$lng['ad_user']['Edit_user']);
				if(!isset($name)) $name = killnl($user_daten[0]); else $name = mutate($name);
				if(!isset($status)) $status = killnl($user_daten[4]); else $status = mutate($status);
				if(!isset($signatur)) $signatur = killnl($user_daten[7]); else $signatur = mutate($signatur);
				if(!isset($email)) $email = killnl($user_daten[3]); else $email = mutate($email);
				if(!isset($pic)) $pic = killnl($user_daten[10]); else $pic = mutate($pic);
				?>
					<form method="post" action="ad_user.php?mode=edit&edit=yes<?=$MYSID2?>">
					<table class="tbl" border=0 cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>" width=100%>
					<tr><th class="thnorm" colspan="2"><span class="thnorm"><?=$lng['ad_user']['Edit_user']?></span></th></tr>
					<? if($fehler != "") echo "<tr><td class=\"td1\" colspan=\"2\"><span class=\"error\">$fehler</span></td></tr>"; ?>
					<tr>
					 <td class="td1"><span class="norm"><b><?=$lng['ID']?>:</b></span></td>
					 <td class="td1"><span class="norm"><?=killnl($user_daten[1])?></span></td>
					</tr>
					<tr>
					 <td class="td1"><span class="norm"><b><?=$lng['Nick']?>:</b></span></td>
					 <td class="td1"><span class="norm"><input type="text" name="name" value="<?=$name?>"></span></td>
					</tr>
					<tr>
					 <td class="td1"><span class="norm"><b><?=$lng['Emailaddress']?>:</b></span></td>
					 <td class="td1"><span class="norm"><input type="text" name="email" value="<?=$email?>"></span></td>
					</tr>
					<tr>
					 <td class="td1"><span class="norm"><b><?=$lng['Status']?>:</b></span></td>
					 <td class="td1"><span class="norm"><select name="status"><option value="1"<? if($status == 1) echo " selected"; ?>>Administrator (Der User hat überall alle Rechte)</option><option value="2"<? if($status == 2) echo " selected"; ?>>Moderator (Dieser Status wird normalerweise automatisch zugewiesen!)</option><option value="3"<? if($status == 3) echo " selected"; ?>>Normal (Der User wird als normaler User behandelt)</option><option value="4"<? if ($status == 4) echo " selected"; ?>>Verbannt (Der User kann sich noch einloggen, aber keine Beiträge mehr erstellen)</option></select></span></td>
					</tr>
					<tr>
					 <td class="td1"><span class="norm"><b><?=$lng['Avatar']?>:</b></span></td>
					 <td class="td1"><span class="norm"><input type="text" name="pic" value="<?=$pic?>"></span></td>
					</tr>
					<tr>
					 <td class="td1" valign="top"><span class="norm"><b><?=$lng['Signature']?>:</b></span></td>
					 <td class="td1"><textarea cols="50" rows="6" name="signatur"><?=brnl(killnl($user_daten[7]))?></textarea></td>
					</tr>
					</table><br><center><input type="submit" value="<?=$lng['ad_user']['Edit_user']?>">&nbsp;&nbsp;&nbsp;<input type="submit" name="kill" value="User löschen"><input type="hidden" name="id" value="<?=$id?>"></center></form>
				<?
			}
		break;

		default: // Nach einem User suchen/Einen User bearbeiten
			if(!isset($searchmethod)) $searchmethod = 'id';
			if(!isset($searched)) $searched = '';
			include("pageheader.php");
			echo adnavbar($lng['ad_user']['Membersearch']);

			?>
				<table class="tbl" width="<?=$twidth?>" cellspacing="<?=$tspacing?>" border="0" cellpadding="<?=$tpadding?>">
				<tr>
				 <th class="thsmall"><span class="thsmall"><?=$lng['ID']?></span></th>
				 <th class="thsmall"><span class="thsmall"><?=$lng['Nick']?></span></th>
				 <th class="thsmall"><span class="thsmall"><?=$lng['Emailaddress']?></span></th>
				 <th class="thsmall"><span class="thsmall">%</span></th>
				 <th class="thsmall"></th>
				</tr>
			<?

			if(isset($search)) {
				$result = array();
				$last_user_id = myfile("vars/last_user_id.var"); $last_user_id = $last_user_id[0];
				switch($searchmethod) {
					case "id":
						for($i = 1; $i < $last_user_id+1; $i++) {
							if($searched == $i) {
								if($user_file = myfile("members/$i.xbb")) $result[] = array('id' => killnl($user_file[1]),'nick' => killnl($user_file[0]),'email' => killnl($user_file[3]),'sim' => 100);
							}
						}
					break;

					case "nick":
						$searched = strtolower($searched);
						for($i = 1; $i < $last_user_id+1; $i++) {
							$akt_sim = 0;
							if($akt_user = myfile("members/$i.xbb")) {
								$akt_user[0] = killnl($akt_user[0]);
								similar_text(strtolower($akt_user[0]),$searched,$akt_sim);
								if($akt_sim > 0) $result[] = array('id' => killnl($akt_user[1]),'nick' => $akt_user[0],'email' => killnl($akt_user[3]),'sim' => $akt_sim);
							}
						}
					break;

					case "email":
						$searched = strtolower($searched);
						for($i = 1; $i < $last_user_id+1; $i++) {
							$akt_sim = 0;
							if($akt_user = myfile("members/$i.xbb")) {
								$akt_user[3] = killnl($akt_user[3]);
								similar_text(strtolower($akt_user[3]),$searched,$akt_sim);
								if($akt_sim > 0) $result[] = array('id' => killnl($akt_user[1]),'nick' => killnl($akt_user[0]),'email' => $akt_user[3],'sim' => $akt_sim);
							}
						}
					break;
				}
				if(sizeof($result) == 0) echo "<tr><td colspan=\"5\" class=\"td1\"><center><span class=\"norm\">".$lng['ad_user']['no_results']."</span></td></tr>";
				else {
					usort($result,"cmpsim");
					for($i = 0; $i < sizeof($result); $i++) {
						?>
							<tr>
							 <td class="td1" align="center"><span class="norm"><?=$result[$i]['id']?></span></td>
							 <td class="td1"><span class="norm"><?=$result[$i]['nick']?></span></td>
							 <td class="td1"><span class="norm"><?=$result[$i]['email']?></span></td>
							 <td class="td1" align="center"><span class="norm"><?=round($result[$i]['sim'])?></span></td>
							 <td class="td1" align="center"><span class="norm"><a class="norm" href="ad_user.php?mode=edit&id=<?=$result[$i]['id']?><?=$MYSID2?>"><?=$lng['edit']?></a></span></td>
							</tr>
						<?
					}
				}
			}
			else echo "<tr><td class=\"td1\" colspan=\"5\"><center><span class=\"norm\">".$lng['ad_user']['no_search_started']."</span></td></tr>";

			echo '</table>';
				?>
					<form method="post" action="ad_user.php?mode=search&search=yes<?=$MYSID2?>">
					<table class="tbl" width="<?=$twidth?>" cellspacing="<?=$tspacing?>" border=0 cellpadding="<?=$tpadding?>">
					<tr><th class="thnorm"><span class="thnorm"><?=$lng['ad_user']['Membersearch']?></span></th></tr>
					<tr><td class="td1"><span class="norm"><?=$lng['ad_user']['what_search_criterion']?><br><input type="radio" name="searchmethod" value="id" onfocus="this.blur()"<? if($searchmethod == "id" || !isset($searchmethod)) echo " checked"; ?>> ID  <input type="radio" name="searchmethod" value="nick" onfocus="this.blur()"<? if($searchmethod == "nick") echo " checked"; ?>> Nick  <input type="radio" name="searchmethod" value="email" onfocus="this.blur()"<? if($searchmethod == "email") echo " checked"; ?>> Email</span></td></tr>
					<tr><td class="td1"><span class="norm"><?=$lng['ad_user']['Search_for']?>:<br><input type="text" name="searched" value="<?=$searched?>"></span></td></tr>
					</table><br><center><input type="submit" value="<?=$lng['ad_user']['Start_search']?>"></center></form>
				<?
		break;
	}
}

wio_set("ad");
include("pagetail.php");

// H
?>