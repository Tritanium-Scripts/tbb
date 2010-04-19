<?

/* ad_smilies.php - Verwaltet die Smilies (c) 2001-2002 Tritanium Scripts */

require_once("functions.php");
require_once("loadset.php");
require_once("auth.php");
ad();

if($user_logged_in != 1 || $user_data[status] != 1) {
	mylog("2","%1: Administrationszugriffversuch (IP: %2)");
	header("Location: ad_login.php?$HSID"); exit;
}
else {
	$dosave == "";

	switch($mode) {

	// ***Übersicht anzeigen***
		default:
			include("pageheader.php");
			$sm_file = myfile("vars/smilies.var"); $sm_file_size = sizeof($sm_file);
			echo adnavbar($lng['ad_smilies']['Smilies_Emoticons_Overview']);
			?>
				<table class="tbl" border=0 width="<?=$twidth?>" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
				<tr><td class="kat" colspan=5><span class="kat"><?=$lng['ad_smilies']['Smilies']?></span></td></tr>
				<tr>
				 <th class="thsmall"><span class="thsmall"><?=$lng['ad_smilies']['Pic']?></span></th>
				 <th class="thsmall"><span class="thsmall"><?=$lng['ad_smilies']['Pic_Address']?></span></th>
				 <th class="thsmall"><span class="thsmall"><?=$lng['ad_smilies']['Synonym']?></span></th>
				 <th class="thsmall"></th>
				 <th class="thsmall"></th>
				</tr>
			<?

			for($i = 0; $i < $sm_file_size; $i++) {
				$akt_sm = myexplode($sm_file[$i]);

				if ($i == 0 && $i == $sm_file_size) $moving="";
				elseif($i == 0) $moving = "<a class=\"norm\" href=\"ad_smilies.php?mode=movedown&id=$akt_sm[0]$MYSID2\">&darr;</a>";
				elseif($i == ($sm_file_size - 1)) $moving = "<a class=\"norm\" href=\"ad_smilies.php?mode=moveup&id=$akt_sm[0]$MYSID2\">&uarr;</a>";
				else $moving = "<a class=\"norm\" href=\"ad_smilies.php?mode=moveup&id=$akt_sm[0]$MYSID2\">&uarr;</a>&nbsp;|&nbsp;<a class=\"norm\" href=\"ad_smilies.php?mode=movedown&id=$akt_sm[0]$MYSID2\">&darr;</a>";

				?>
					<tr>
					 <td class="td1" align=center><img border=0 src="<?=$akt_sm[2]?>"></td>
					 <td class="td2"><span class="norm"><?=$akt_sm[2]?></span></td>
					 <td class="td1"><span class="norm"><?=$akt_sm[1]?></span></td>
					 <td class="td2" align=center><span class="norm"><?=$moving?></span></td>
					 <td class="td1" align=center><span class="norm"><a class="norm" href="ad_smilies.php?mode=edit&id=<?=$akt_sm[0]?><?=$MYSID2?>"><?=$lng['edit']?></a> | <a class="norm" href="ad_smilies.php?mode=kill&id=<?=$akt_sm[0]?><?=$MYSID2?>"><?=$lng['delete']?></a></span></td>
					</tr>
				<?
			}
			?>
				</table></center><span class="norm"><a class="norm" href="ad_smilies.php?mode=new<?=$MYSID2?>"><?=$lng['ad_smilies']['Add_Smiley']?></a></span><center><br><br>
				<table class="tbl" border=0 width="<?=$twidth?>" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
				<tr><td class="kat" colspan=5><span class="kat"><?=$lng['ad_smilies']['Emoticons']?></span></td></tr>
				<tr>
				 <th class="thsmall"><span class="thsmall"><?=$lng['ad_smilies']['Pic']?></span></th>
				 <th class="thsmall"><span class="thsmall"><?=$lng['ad_smilies']['Pic_Address']?></span></th>
				 <th class="thsmall"></th>
				 <th class="thsmall"></th>
				</tr>
			<?

			$tsm_file = myfile("vars/tsmilies.var"); $tsm_file_size = sizeof($tsm_file);
			for($i = 0; $i < $tsm_file_size; $i++) {
				$akt_tsm = myexplode($tsm_file[$i]);

				if ($i == 0 && $i == $tsm_file_size) $moving="";
				elseif($i == 0) $moving = "<a class=\"norm\" href=\"ad_smilies.php?mode=movedownt&id=$akt_tsm[0]$MYSID2\">&darr;</a>";
				elseif($i == ($tsm_file_size - 1)) $moving = "<a class=\"norm\" href=\"ad_smilies.php?mode=moveupt&id=$akt_tsm[0]$MYSID2\">&uarr;</a>";
				else $moving = "<a class=\"norm\" href=\"ad_smilies.php?mode=moveupt&id=$akt_tsm[0]$MYSID2\">&uarr;</a>&nbsp;|&nbsp;<a class=\"norm\" href=\"ad_smilies.php?mode=movedownt&id=$akt_tsm[0]$MYSID2\">&darr;</a>";

				?>
					<tr>
					 <td class="td1" align=center><img border=0 src="<?=$akt_tsm[1]?>"></td>
					 <td class="td2"><span class="norm"><?=$akt_tsm[1]?></span></td>
					 <td class="td1" align=center><span class="norm"><?=$moving?></span></td>
					 <td class="td2" align=center><span class="norm"><a class="norm" href="ad_smilies.php?mode=editt&id=<?=$akt_tsm[0]?><?=$MYSID2?>"><?=$lng['edit']?></a>&nbsp;|&nbsp;<a class="norm" href="ad_smilies.php?mode=killt&id=<?=$akt_tsm[0]?><?=$MYSID2?>"><?=$lng['delete']?></a></span></td>
					</tr>
				<?
			}
			echo "</table></center><span class=\"norm\"><a class=\"norm\" href=\"ad_smilies.php?mode=newt$MYSID2\">".$lng['ad_smilies']['Add_Emoticon']."</a></span>";
		break;


	// ***Smilie editieren***
		case "edit":
			$sm_file = myfile("vars/smilies.var"); $sm_file_size = sizeof($sm_file);
			if($save != "yes") {
				include("pageheader.php");
				echo adnavbar("<a class=\"navbar\" href=\"ad_smilies.php$MYSID1\">".$lng['ad_smilies']['Smilies_Emoticons_Overview']."</a>\t".$lng['ad_smilies']['Edit_Smiley']);
				?>
					<form method="post" action="ad_smilies.php?mode=edit&id=<?=$id?><?=$MYSID2?>"><input type="hidden" name="save" value="yes">
					<table class="tbl" border=0 width="<?=$twidth?>" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
					<tr><th class="thnorm" colspan="2"><span class="thnorm"><?=$lng['ad_smilies']['Edit_Smiley']?></span></th></tr>
				<?
				for ($i = 0; $i < $sm_file_size; $i++) {
					$akt_sm = myexplode($sm_file[$i]);
					if ($akt_sm[0] == $id) {
						?>
							<tr>
							 <td class="td1" width=20%><span class="norm"><b><?=$lng['ad_smilies']['Pic']?>:</b></span></td>
							 <td class="td1" width=80%><img src="<?=$akt_sm[2]?>"></td>
							</tr>
							<tr>
							 <td class="td1" width=20%><span class="norm"><b><?=$lng['ad_smilies']['Pic_Address']?>:</b></span></td>
							 <td class="td1" width=80%><input type="text" name="picadress" value="<?=$akt_sm[2]?>"> <span class="small">(<?=$lng['ad_smilies']['URL_or_path']?>)</span></td>
							</tr>
							<tr>
							 <td class="td1" width=20%><span class="norm"><b><?=$lng['ad_smilies']['Synonym']?>:</b></span></td>
							 <td class="td1" width=80%><input type="text" name="synonym" value="<?=$akt_sm[1]?>"></td>
							</tr>
						<?
						break;
					}
				}
				echo "</table><br><input type=\"submit\" value=\"".$lng['ad_smilies']['Edit_Smiley']."\"></center>";
			}
			else {
				for ($i = 0; $i < $sm_file_size; $i++) {
					$akt_sm = myexplode($sm_file[$i]);
					if($akt_sm[0] == $id) {
						$akt_sm[1] = $synonym;
						$akt_sm[2] = $picadress;
						$dosave = "yes";
						$sm_file[$i] = myimplode($akt_sm); break;
					}
				}

				if($dosave = "yes") {
					myfwrite("vars/smilies.var",$sm_file,"w");
					mylog("8","%1: Smilie (ID: $id) bearbeitet (IP: %2)");
					header("Location: ad_smilies.php?$HSID"); exit;
				}
			}
		break;

	// ***T-Smilie editieren***
		case "editt":
			$tsm_file = myfile("vars/tsmilies.var"); $tsm_file_size = sizeof($tsm_file);
			if($save != "yes") {
				include("pageheader.php");
				echo adnavbar("<a class=\"navbar\" href=\"ad_smilies.php$MYSID1\">".$lng['ad_smilies']['Smilies_Emoticons_Overview']."</a>\t".$lng['ad_smilies']['Edit_Emoticon']);
				?>
					<form method="post" action="ad_smilies.php?mode=editt&id=<?=$id?><?=$MYSID2?>"><input type=hidden name=save value=yes>
					<table class="tbl" border=0 width="<?=$twidth?>" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
					<tr><th class="thnorm" colspan="2"><span class="thnorm"><?=$lng['ad_smilies']['Edit_Emoticon']?></span></th></tr>
				<?
				for ($i = 0; $i < $tsm_file_size; $i++) {
					$akt_tsm = myexplode($tsm_file[$i]);
					if ($akt_tsm[0] == $id) {
						?>
							<tr>
							 <td class="td1" width=20%><span class="norm"><b><?=$lng['ad_smilies']['Pic']?>:</b></span></td>
							 <td class="td1" width=80%><img src="<?=$akt_tsm[1]?>"></td>
							</tr>
							<tr>
							 <td class="td1" width=20%><span class="norm"><b><?=$lng['ad_smilies']['Pic_Address']?>:</b></span></td>
							 <td class="td1" width=80%><input type="text" name="picadress" value="<?=$akt_tsm[1]?>"> <span class="small">(<?=$lng['ad_smilies']['URL_or_path']?>)</span></td>
							</tr>
						<?
						break;
					}
				}
				echo "</table><br><input type=\"submit\" value=\"".$lng['ad_smilies']['Edit_Emoticon']."\"></center>";
			}
			else {
				for ($i = 0; $i < $tsm_file_size; $i++) {
					$akt_tsm = myexplode($tsm_file[$i]);
					if($akt_tsm[0] == $id) {
						$akt_tsm[1] = $picadress;
						$dosave = "yes";
						$tsm_file[$i] = myimplode($akt_tsm); break;
					}
				}

				if($dosave = "yes") {
					myfwrite("vars/tsmilies.var",$tsm_file,"w");
					mylog("8","%1: T-Smilie (ID: $id) editiert (IP: %2)");
					header("Location: ad_smilies.php?$HSID"); exit;
				}
			}
		break;

	// ***Smilie löschen***
		case "kill":
			if($id && $id != "") {
				$sm_file = myfile("vars/smilies.var");
				for($i = 0; $i < sizeof($sm_file); $i++) {
					$akt_sm = myexplode($sm_file[$i]);
					if($akt_sm[0] == $id) {
						$sm_file[$i] = "";
						$dosave = "yes"; break;
					}
				}

				if($dosave == "yes") {
					myfwrite("vars/smilies.var",$sm_file,"w");
					mylog("8","%1: Smilie (ID: $id) gelöscht (IP: %2)");
					header("Location: ad_smilies.php?$HSID"); exit;
				}
				else echo "Smilie-Lösch Fehler";
			}
		break;

		// ***TSmilie löschen***
		case "killt":
			if($id && $id != "") {
				$tsm_file = myfile("vars/tsmilies.var");
				for($i = 0; $i < sizeof($tsm_file); $i++) {
					$akt_tsm = myexplode($tsm_file[$i]);
					if($akt_tsm[0] == $id) {
						$tsm_file[$i] = "";
						$dosave = "yes"; break;
					}
				}

				if($dosave == "yes") {
					myfwrite("vars/tsmilies.var",$tsm_file,"w");
					mylog("8","%1: T-Smilie (ID: $id) gelöscht (IP: %2)");
					header("Location: ad_smilies.php?$HSID"); exit;
				}
				else echo "TSmilie-Lösch Fehler";
			}
		break;

	// ***Smilie nach oben schieben***
		case "moveup":
			$sm_file = myfile("vars/smilies.var");
			for($i = 0; $i < sizeof($sm_file); $i++) {
				$akt_sm = myexplode($sm_file[$i]);
				if ($akt_sm[0] == $id) {
					$sm_file_backup = $sm_file[$i];
					$sm_file[$i] = $sm_file[($i - 1)];
					$sm_file[($i - 1)] = $sm_file_backup;
					$dosave = "yes"; break;
				}
			}

			if ($dosave == "yes") {
				myfwrite("vars/smilies.var",$sm_file,"w");
				header("Location: ad_smilies.php?$HSID"); exit;
			}
			else echo "Smilie-Moveup-Error!";
		break;

	// ***Smilie nach unten schieben***
		case "movedown":
			$sm_file = myfile("vars/smilies.var");
			for($i = 0; $i < sizeof($sm_file); $i++) {
				$akt_sm = myexplode($sm_file[$i]);
				if ($akt_sm[0] == $id) {
					$sm_file_backup = $sm_file[$i];
					$sm_file[$i] = $sm_file[($i + 1)];
					$sm_file[($i + 1)] = $sm_file_backup;
					$dosave = "yes"; break;
				}
			}

			if ($dosave == "yes") {
				myfwrite("vars/smilies.var",$sm_file,"w");
				header("Location: ad_smilies.php?$HSID"); exit;
			}
			else echo "Smilie-Movedown-Error!";
		break;


	// ***TSmilie nach oben schieben***
		case "moveupt":
			$tsm_file = myfile("vars/tsmilies.var");
			for($i = 0; $i < sizeof($tsm_file); $i++) {
				$akt_tsm = myexplode($tsm_file[$i]);
				if ($akt_tsm[0] == $id) {
					$tsm_file_backup = $tsm_file[$i];
					$tsm_file[$i] = $tsm_file[($i - 1)];
					$tsm_file[($i - 1)] = $tsm_file_backup;
					$dosave = "yes"; break;
				}
			}

			if ($dosave == "yes") {
				myfwrite("vars/tsmilies.var",$tsm_file,"w");
				header("Location: ad_smilies.php?$HSID"); exit;
			}
			else echo "TSmilie-Moveup-Error!";
		break;

	// ***TSmilie nach unten schieben***
		case "movedownt":
			$tsm_file = myfile("vars/tsmilies.var");
			for($i = 0; $i < sizeof($tsm_file); $i++) {
				$akt_tsm = myexplode($tsm_file[$i]);
				if ($akt_tsm[0] == $id) {
					$tsm_file_backup = $tsm_file[$i];
					$tsm_file[$i] = $tsm_file[($i + 1)];
					$tsm_file[($i + 1)] = $tsm_file_backup;
					$dosave = "yes"; break;
				}
			}

			if ($dosave == "yes") {
				myfwrite("vars/tsmilies.var",$tsm_file,"w");
				header("Location: ad_smilies.php?$HSID"); exit;
			}
			else echo "TSmilie-Movedown-Error!";
		break;

	// ***Neuen Smilie erstellen***
		case "new":
			if($save != "yes") {
				include("pageheader.php");
				echo adnavbar("<a class=\"navbar\" href=\"ad_smilies.php$MYSID1\">".$lng['ad_smilies']['Smilies_Emoticons_Overview']."</a>\t".$lng['ad_smilies']['Add_Smiley']);
				?>
					<form method=post action="ad_smilies.php?mode=new<?=$MYSID2?>"><input type=hidden name=save value=yes>
					<table class="tbl" border=0 width="<?=$twidth?>" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
					<tr><th class="thnorm" colspan="2"><span class="thnorm"><?=$lng['ad_smilies']['Add_Smiley']?></span></th></tr>
					<tr>
					 <td class="td1" width="20%"><span class="norm"><b><?=$lng['ad_smilies']['Pic_Address']?></b></span></td>
					 <td class="td1" width="80%"><input type="text" name="smadress"> <span class="small">(<?=$lng['ad_smilies']['URL_or_path']?>)</span></td>
					</tr>
					<tr>
					 <td class="td1" width="20%"><span class="norm"><b><?=$lng['ad_smilies']['Synonym']?>:</b></span></td>
					 <td class="td1" width="80%"><input type="text" name="synonym"></td>
					</tr>
					</table><br><input type="submit" value="<?=$lng['ad_smilies']['Add_Smiley']?>"></form>
				<?
			}
			else {
				$newid = myfile("vars/smiliess.var"); $newid = $newid[0]+1; // Neue ID herausfinden
				$towrite = "$newid\t$synonym\t$smadress\t\r\n"; // Smiliedaten vorbereiten
				myfwrite("vars/smilies.var",$towrite,"a"); myfwrite("vars/smiliess.var",$newid,"w"); // Daten schreiben
				mylog("8","%1: Smilie (ID: $newid) erstellt (IP: %2)");
				header("Location: ad_smilies.php?$HSID"); // Wieder zurück zur Übersicht
				exit;
			}
		break;

	// ***Neuen T-Smilie erstellen***
		case "newt":
			if($save != "yes") {
				include("pageheader.php");
				echo adnavbar("<a class=\"navbar\" href=\"ad_smilies.php$MYSID1\">".$lng['ad_smilies']['Smilies_Emoticons_Overview']."</a>\t".$lng['ad_smilies']['Add_Emoticon']);
				?>
					<form method="post" action="ad_smilies.php?mode=newt<?=$MYSID2?>"><input type="hidden" name="save" value="yes">
					<table class="tbl" border=0 width="<?=$twidth?>" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
					<tr><th class="thnorm" colspan="2"><span class="thnorm"><?=$lng['ad_smilies']['Add_Emoticon']?></span></th></tr>
					<tr>
					 <td class="td1" width=20%><span class="norm"><b><?=$lng['ad_smilies']['Pic_Address']?></b></span></td>
					 <td class="td1" width=80%><input type="text" name="tsmadress"> <span class="small">(<?=$lng['ad_smilies']['URL_or_path']?>)</span></td>
					</tr>
					</table><br><input type="submit" value="<?=$lng['ad_smilies']['Add_Emoticon']?>"></form>
				<?
			}
			else {
				$newid = myfile("vars/tsmiliess.var"); $newid = $newid[0]+1; // Neue ID herausfinden
				$towrite = "$newid\t$tsmadress\t\r\n"; // TSmiliedaten vorbereiten
				myfwrite("vars/tsmilies.var",$towrite,"a"); myfwrite("vars/tsmiliess.var",$newid,"w"); // Daten schreiben
				mylog("8","%1: T-Smilie (ID: $newid) erstellt (IP: %2)");
				header("Location: ad_smilies.php?$HSID"); // Wieder zurück zur Übersicht
				exit;
			}
		break;
	}
	wio_set("ad");
	include("pagetail.php");
// N
}