<?

/* ad_rank.php - Verwaltet die Rankings (c) 2001-2002 Tritanium Scripts */

require_once("functions.php");
require_once("loadset.php");
require_once("auth.php");
ad();

if($user_logged_in != 1 || $user_data[status] != 1) {
	mylog("2","%1: Administrationszugriffversuch (IP: %2)");
	header("Location: ad_login.php?$HSID"); exit;
}
else {

	$dosave = "";

	switch($mode) {
		default: // Übersicht anzeigen
			include("pageheader.php");
			$rank_file = myfile("vars/rank.var"); $rank_file_size = sizeof($rank_file);
			echo adnavbar($lng['ad_rank']['Ranking_Overview']);
			?>
				<table class="tbl" border="0" width="<?=$twidth?>" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
				<tr>
				 <th class="thsmall"><span class="thsmall"><?=$lng['ad_rank']['Rank']?></span></th>
				 <th class="thsmall"><span class="thsmall"><?=$lng['ad_rank']['Min_Posts']?></span></th>
				 <th class="thsmall"><span class="thsmall"><?=$lng['ad_rank']['Max_Posts']?></span></th>
				 <th class="thsmall"><span class="thsmall"><?=$lng['ad_rank']['Stars']?></span></th>
				 <th class="thsmall"></th>
				 <th class="thsmall"></th>
				</tr>
			<?
			for ($i = 0; $i < $rank_file_size; $i++) {
				$akt_rank = myexplode($rank_file[$i]);

				if ($i == 0 && $i == $rank_file_size) $moving="";
				elseif($i == 0) $moving = "<a class=\"norm\" href=\"ad_rank.php?mode=movedown&id=$akt_rank[0]$MYSID2\">&darr;</a>";
				elseif($i == ($rank_file_size - 1)) $moving = "<a class=\"norm\" href=\"ad_rank.php?mode=moveup&id=$akt_rank[0]$MYSID2\">&uarr;</a>";
				else $moving = "<a class=\"norm\" href=\"ad_rank.php?mode=moveup&id=$akt_rank[0]$MYSID2\">&uarr;</a> | <a class=\"norm\" href=\"ad_rank.php?mode=movedown&id=$akt_rank[0]$MYSID2\">&darr;</a>";

				?>
					<tr>
					 <td class="td1"><span class="norm"><?=$akt_rank[1]?></span></td>
					 <td class="td2" align=center><span class="norm"><?=$akt_rank[2]?></span></td>
					 <td class="td1" align=center><span class="norm"><?=$akt_rank[3]?></span></td>
					 <td class="td2" align=center><span class="norm"><?=$akt_rank[4]?></span></td>
					 <td class="td1" align=center><span class="norm"><?=$moving?></span></td>
					 <td class="td2" align=center><span class="norm"><a class="norm" href="ad_rank.php?mode=edit&id=<?=$akt_rank[0]?><?=$MYSID2?>"><?=$lng['edit']?></a> | <a class="norm" href="ad_rank.php?mode=kill&id=<?=$akt_rank[0]?><?=$MYSID2?>"><?=$lng['delete']?></a></span></td>
					</tr>
				<?
			}
			echo "</table></center><span class=\"norm\"><a class=\"norm\" href=\"ad_rank.php?mode=new$MYSID2\">".$lng['ad_rank']['Add_rank']."</a></span>";
		break;

		case "edit": // Rang bearbeiten
			$rank_file = myfile("vars/rank.var"); $rank_file_size = sizeof($rank_file);
			if ($save != "yes") {
				include("pageheader.php");
				echo adnavbar("<a class=\"navbar\" href=\"ad_rank.php$MYSID1\">".$lng['ad_rank']['Ranking_Overview']."</a>\t".$lng['ad_rank']['Edit_rank']);
				?>
					<form method="post" action="ad_rank.php?id=<?=$id?>&mode=edit<?=$MYSID2?>"><input type="hidden" name="save" value="yes">
					<table class="tbl" border="0" width="<?=$twidth?>" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
					<tr><th class="thnorm" colspan="2"><span class="thnorm"><?=$lng['ad_rank']['Edit_rank']?></span></th></tr>
				<?
				for($i = 0; $i < $rank_file_size; $i++) {
					$akt_rank = myexplode($rank_file[$i]);
					if($akt_rank[0] == $id) {
						?>
							<tr>
							 <td class="td1" width=20%><span class="norm"><b><?=$lng['ad_rank']['Rank']?>:</b></span></td>
							 <td class="td1" width=80%><input type="text" name="bez" value="<?=$akt_rank[1]?>"></td>
							</tr>
							<tr>
							 <td class="td1" width=20%><span class="norm"><b><?=$lng['ad_rank']['Min_Posts']?>:</b></span></td>
							 <td class="td1" width=80%><input type="text" size="8" name="minposts" value="<?=$akt_rank[2]?>"></td>
							</tr>
							<tr>
							 <td class="td1" width=20%><span class="norm"><b><?=$lng['ad_rank']['Max_Posts']?>:</b></span></td>
							 <td class="td1" width=80%><input type="text" size="8" name="maxposts" value="<?=$akt_rank[3]?>"></td>
							</tr>
							<tr>
							 <td class="td1" width=20%><span class="norm"><b><?=$lng['ad_rank']['Stars']?>:</b></span></td>
							 <td class="td1" width=80%><input type="text" size="2" name="pic" value="<?=$akt_rank[4]?>"></td>
							</tr>
						<?
						break;
					}
				}
				echo "</table><br><input type=\"submit\" value=\"".$lng['ad_rank']['Edit_rank']."\"></form></center>";
			}

			else {
				for($i = 0; $i < $rank_file_size;$i++) {
					$akt_rank = myexplode($rank_file[$i]);
					if($akt_rank[0] == $id) {
						$akt_rank[1] = mutate($bez);
						$akt_rank[2] = $minposts;
						$akt_rank[3] = $maxposts;
						$akt_rank[4] = $pic;
						$rank_file[$i] = myimplode($akt_rank);
						$dosave = "yes"; break;
					}
				}

				if($dosave == "yes") {
					myfwrite("vars/rank.var",$rank_file,"w");
					mylog("8","%1: Rang (ID: $id) bearbeitet (IP: %2)");
					header("Location: ad_rank.php?$HSID"); exit;
				}
				else echo "Rank-Save-Fehler!";
			}
		break;


		case "kill": // Rank löschen
			$rank_file = myfile("vars/rank.var");
			for($i = 0; $i < sizeof($rank_file); $i++) {
				$akt_rank = myexplode($rank_file[$i]);
				if ($akt_rank[0] == $id) {
					$rank_file[$i] = "";
					$dosave = "yes"; break;
				}
			}

			if ($dosave == "yes") {
				myfwrite("vars/rank.var",$rank_file,"w");
				mylog("8","%1: Rang (ID: $id) gelöscht (IP: %2)");
				header("Location: ad_rank.php?$HSID"); exit;
			}
			else echo "Rank-Kill-Error!";
		break;

		case "moveup": // Rank nach oben schieben
			$rank_file = myfile("vars/rank.var"); $rank_file_size = sizeof($rank_file);
			for($i = 0; $i < $rank_file_size;$i++) {
				$akt_rank = myexplode($rank_file[$i]);
				if ($akt_rank[0] == $id) {
					$rank_file_backup = $rank_file[$i];
					$rank_file[$i] = $rank_file[($i - 1)];
					$rank_file[($i - 1)] = $rank_file_backup;
					$save = "yes"; break;
				}
			}

			if ($save == "yes") {
				myfwrite("vars/rank.var",$rank_file,"w");
				header("Location: ad_rank.php?$HSID"); exit;
			}
			else echo "Rank-Moveup-Error!";
		break;

		case "movedown": // Rank nach unten schieben
			$rank_file = myfile("vars/rank.var");
			for($i = 0; $i < sizeof($rank_file); $i++) {
				$akt_rank = myexplode($rank_file[$i]);
				if ($akt_rank[0] == $id) {
					$rank_file_backup = $rank_file[$i];
					$rank_file[$i] = $rank_file[($i + 1)];
					$rank_file[($i + 1)] = $rank_file_backup;
					$save = "yes"; break;
				}
			}

			if ($save == "yes") {
				myfwrite("vars/rank.var",$rank_file,"w");
				header("Location: ad_rank.php?$HSID"); exit;
			}
			else echo "Rank-Movedown-Error!";
		break;

		case "new": // Neuen Rank erstellen
			if($save != "yes") {
				include("pageheader.php");
				echo adnavbar("<a class=\"navbar\" href=\"ad_rank.php$MYSID1\">".$lng['ad_rank']['Ranking_Overview']."</a>\t".$lng['ad_rank']['Add_rank']);
				?>
					<form method="post" action="ad_rank.php?mode=new<?=$MYSID2?>"><input type=hidden name=save value=yes>
					<table class="tbl" border="0" width="<?=$twidth?>" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
					<tr><th class="thnorm" colspan="2"><span class="thnorm"><?=$lng['ad_rank']['Add_rank']?></span></th></tr>
					<tr>
					 <td class="td1" width=20%><span class="norm"><b><?=$lng['ad_rank']['Rank']?>:</b></span></td>
					 <td class="td1" width=80%><input type="text" name="bez"></td>
					</tr>
					<tr>
					 <td class="td1" width=20%><span class="norm"><b><?=$lng['ad_rank']['Min_Posts']?>:</b></span></td>
					 <td class="td1" width=80%><input type="text" name="minposts"></td>
					</tr>
					<tr>
					 <td class="td1" width=20%><span class="norm"><b><?=$lng['ad_rank']['Max_Posts']?>:</b></span></td>
					 <td class="td1" width=80%><input type="text" name="maxposts"></td>
					</tr>
					<tr>
					 <td class="td1" width=20%><span class="norm"><b><?=$lng['ad_rank']['Stars']?>:</b></span></td>
					 <td class="td1" width=80%><input type="text" size="2" name="pic"></td>
					</tr>
					</table><br><input type="submit" value="<?=$lng['ad_rank']['Add_rank']?>"></form></center>
				<?
			}
			else {
				// get new ID (wow, English! :)
				$new_id = myfile("vars/ranks.var"); $new_id = $new_id[0]+1;

				// Neue ID schreiben
				myfwrite("vars/ranks.var",$new_id,"w");

				// Neuen Rang schreiben
				$towrite = $new_id."\t$bez\t$minposts\t$maxposts\t$pic\t\n";
				myfwrite("vars/rank.var",$towrite,"a");
				mylog("8","%1: Rang (ID: $new_id) erstellt (IP: %2)");
				header("Location: ad_rank.php?$HSID"); exit;
			}
		break;
	}

wio_set("ad");
include("pagetail.php");

}
// T
?>