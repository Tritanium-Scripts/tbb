<?

/* ad_censor.php - zum Verwalten der zensierten Wörter (c) 2001-2002 Tritanium Scripts */

require_once("functions.php");
require_once("loadset.php");
require_once("auth.php");
ad();

if($user_logged_in != 1 || $user_data[status] != 1) {
	mylog("2","%1: Administrationszugriffversuch (IP: %2)");
	header("Location: ad_login.php?$HSID"); exit;
}
else {

	$cwords = myfile("vars/cwords.var");
	$save = 0;
	$fehler = "";

	switch($mode) {

		case "new":
			$showformular = 1;
			$fehler = "";

			if($replacement == "" || !$replacement) $replacement = "******";

			if($create == 1) {
				if(trim($word) == "") $fehler = $lng['ad_censor']['error']['Please_enter_a_word'];
				else {
					$new_id = myexplode($cwords[sizeof($cwords)-1]); $new_id = $new_id[0]+1;
					$towrite = "$new_id\t$word\t$replacement\t\r\n";
					myfwrite("vars/cwords.var",$towrite,"a"); $showformular = 0;
					mylog("8","%1: Administration: Zensur (ID: $newid) erstellt (IP: %2)");
					header("Location: ad_censor.php?$HSID"); exit;
				}
			}

			if($showformular == 1) {
				include("pageheader.php");
				echo adnavbar("<a class=\"navbar\" href=\"ad_censor.php$MYSID1\">".$lng['ad_censor']['Word_Censor']."</a>\t".$lng['ad_censor']['Add_Word']);
				?>
					<form method="post" action="ad_censor.php?mode=new<?=$MYSID2?>"><input type="hidden" name="create" value="1">
					<table class="tbl" border=0 width="<?=$twidth?>" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
					<tr><th class="thnorm" colspan=2><span class="thnorm"><?=$lng['ad_censor']['Add_Word']?></span></th></tr>
					<? if($fehler != "") echo "<tr><td class=\"td1\" colspan=2><span class=\"error\">$fehler</span></td></tr>"; ?>
					<tr>
					 <td class="td1"><span class="norm"><b><?=$lng['ad_censor']['Word']?>:</b></span></td>
					 <td class="td1"><input type="text" name="word"> <span class="small">(<?=$lng['ad_censor']['case_insensitive']?>)</span></td>
					</tr>
					<tr>
					 <td class="td1"><span class="norm"><b><?=$lng['ad_censor']['Replacement']?>:</b></span></td>
					 <td class="td1"><input type=text name="replacement" value="<?=$replacement?>"></td>
					</tr>
					</table><br><input type="submit" value="<?=$lng['ad_censor']['Add_Word']?>"></form></center>
				<?
			}
		break;

		case "edit":
			if($update == 1) {
				for($i = 0; $i < sizeof($cwords); $i++) {
					$akt_cword = myexplode($cwords[$i]);
					if($akt_cword[0] == $id) {
						$akt_cword[1] = $word; $akt_cword[2] = $replacement;
						$cwords[$i] = myimplode($akt_cword); $save = 1; break;
					}
				}

				if($save == 1) {
					myfwrite("vars/cwords.var",$cwords,"w");
					header("Location: ad_censor.php?$HSID");
					mylog("8","%1: Administration: Zensur (ID: $id) bearbeitet (IP: %2)"); exit;
				}
				else echo "ad_cword.php-edit-Fehler!";
			}
			else {
				for($i = 0; $i < sizeof($cwords); $i++) {
					$akt_cword = myexplode($cwords[$i]);
					if($akt_cword[0] == $id) {
						include("pageheader.php");
						echo adnavbar("<a class=\"navbar\" href=\"ad_censor.php$MYSID1\">".$lng['ad_censor']['Word_Censor']."</a>\t".$lng['ad_censor']['Edit_Word']);
						?>
							<form method="post" action="ad_censor.php?mode=edit<?=$MYSID2?>"><input type="hidden" name="update" value="1"><input type="hidden" name="id" value="<?=$id?>">
							<table class="tbl" border=0 width="<?=$twidth?>" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
							<tr><th class="thnorm" colspan="2"><span class="thnorm"><?=$lng['ad_censor']['Edit_Word']?></span></th></tr>
							<tr>
							 <td class="td1"><span class="norm"><b><?=$lng['ad_censor']['Word']?>:</b></span></td>
							 <td class="td1"><input type="text" name="word" value="<?=$akt_cword[1]?>"> <span class="small">(<?=$lng['ad_censor']['case_insensitive']?>)</span></td>
							</tr>
							<tr>
							 <td class="td1"><span class="norm"><b><?=$lng['ad_censor']['Replacement']?>:</b></span></td>
							 <td class="td1"><input type="text" name="replacement" value="<?=$akt_cword[2]?>"></td>
							</tr>
							</table><br><input type=submit value="<?=$lng['ad_censor']['Edit_Word']?>"></form></center>
						<?
						break;
					}
				}
			}
		break;

		case "kill":
			for($i = 0; $i < sizeof($cwords); $i++) {
				$akt_cword = myexplode($cwords[$i]);
				if($akt_cword[0] == $id) {
					$save = 1; $cwords[$i] = ""; break;
				}
			}

			if($save == 1) {
				myfwrite("vars/cwords.var",$cwords,"w");
				mylog("8","%1: Administration: Zensur (ID: $id) gelöscht (IP: %2)");
				header("Location: ad_censor.php?$HSID"); exit;
			}
			else echo "Zensur-Lösch-Fehler!";
		break;

		default:
			include("pageheader.php");
			echo adnavbar($lng['ad_censor']['Word_Censor']);
			?>
				<table class="tbl" border=0 width="<?=$twidth?>" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
				<tr>
				 <th class="thsmall"><span class="thsmall"><?=$lng['ad_censor']['Word']?></span></th>
				 <th class="thsmall"><span class="thsmall"><?=$lng['ad_censor']['Replacement']?></span></th>
				 <th class="thsmall"></th>
				</tr>
			<?
			if(sizeof($cwords) == 0) echo "<tr><td class=\"td1\" colspan=3><span class=\"norm\"><center>".$lng['ad_censor']['No_words']."</center></span></td></tr>";
			else {
				for($i = 0; $i < sizeof($cwords); $i++) {
					$akt_cword = myexplode($cwords[$i]);
					?>
						<tr>
						 <td class="td1"><span class="norm"><?=$akt_cword[1]?></span></td>
						 <td class="td2"><span class="norm"><?=$akt_cword[2]?></span></td>
						 <td class="td1"><span class="norm"><center><a class="norm" href="ad_censor.php?mode=kill&id=<?=$akt_cword[0]?><?=$MYSID2?>"><?=$lng['delete']?></a>&nbsp;|&nbsp;<a class="norm" href="ad_censor.php?mode=edit&id=<?=$akt_cword[0]?><?=$MYSID2?>"><?=$lng['edit']?></a></center></span></td>
						</tr>
					<?
				}
			}

			echo "</table></center><span class=\"norm\"><a class=\"norm\" href=\"ad_censor.php?mode=new$MYSID2\">".$lng['ad_censor']['Add_Word']."</a></span></center>";
		break;
	}
}

wio_set("ad");
include("pagetail.php");
// I
?>