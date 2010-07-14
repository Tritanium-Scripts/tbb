<?

/* pm.php - Private Nachrichten verwalten (c) 2001-2002 Tritanium Scripts */

require_once("auth.php");

if(!isset($pmbox_id)) $pmbox_id = $user_id; // Falls keine spezielle PM-Box angegeben wurde (für Admins), wird die des aktuellen Users verwendet

if($user_logged_in != 1) { // Falls User nicht eingeloggt ist
	include("pagehader.php");
	echo navbar($lng['pms']['PMs']);
	echo get_message('nli','<br>'.sprintf($lng['links']['register_or_login'],"<a class=\"norm\" href=\"index.php?faction=register$MYSID2\">",'</a>',"<a class=\"norm\" href=\"index.php?faction=login$MYSID2\">",'</a>'));
}
elseif($pmbox_id != $user_id) echo "No Access!"; // Es wird geprüft, ob der User berechtigt ist, auf die PM-Box zuzugreifen
else { // User hat nun Zugriff

	$save = ""; // Speichern wird erst mal abgestellt
	$pms = myfile("members/$pmbox_id.pm"); $pm_anzahl = sizeof($pms); // PMs laden

	switch($mode) {

	// ***PM-Box anzeigen***
	default:
		include("pageheader.php");
		$pms = array_reverse($pms); // Neueste PM zuerst
		echo navbar("PMs");
		?>
			<form method="post" action="index.php?faction=pm&pmbox_id=<?=$pmbox_id?>&mode=deletemany<?=$MYSID2?>">
			<table class="tbl" border="0" cellpadding="<?=$tpadding?>" cellspacing="<?=$tspacing?>" width="<?=$twidth?>">
			<tr>
			 <th class="thsmall"></th>
			 <th class="thsmall" width="50%"><span class="thsmall"><?=$lng['Subject']?></span></th>
			 <th class="thsmall"><span class="thsmall"><?=$lng['From']?></span></th>
			 <th class="thsmall"><span class="thsmall"><?=$lng['Date']?></span></th>
			 <th class="thsmall"></th>
			</tr>
		<?
		if($pm_anzahl != 0) { // Nur fortfahren, wenn auch eine PM vorhanden ist
			for($i = 0; $i < $pm_anzahl; $i++) {
				$ak_pm = myexplode( $pms[$i]); // $ak_pm ist eigentlich ein blöder Variablenname :)
				if($ak_pm[7] == 1) $ak_pm[1] = "<a href=\"index.php?faction=pm&mode=view&pm_id=$ak_pm[0]&pmbox_id=$pmbox_id$MYSID2\"><b>$ak_pm[1]</b></a>";
				else $ak_pm[1] = "<a href=\"index.php?faction=pm&mode=view&pm_id=$ak_pm[0]&pmbox_id=$pmbox_id$MYSID2\">$ak_pm[1]</a>";
				?>
					<tr>
					 <td class="td1"><span class="norm"><input type="checkbox" name="deletepm[<?=$ak_pm[0]?>]" value="1" onfocus="this.blur()"></span></td>
					 <td class="td2" width=50%><span class="norm"><?=$ak_pm[1]?></span></td>
					 <td class="td1"><span class="norm"><?=get_user_name($ak_pm[3])?></font></td>
					 <td class="td2" align="center"><span class="small"><?=makedatum($ak_pm[4])?></span></td>
					 <td class="td1" align="center"><span class="small"><a href="index.php?faction=pm&mode=kill&pm_id=<?=$ak_pm[0]?>&pmbox_id=<?=$pmbox_id?><?=$MYSID2?>"><?=$lng['delete']?></a> | <a href="index.php?faction=pm&pmbox_id=<?=$pmbox_id?>&mode=reply&pm_id=<?=$ak_pm[0]?><?=$MYSID2?>"><?=$lng['reply']?></a></span></td>
					</tr>
				<?
			}
			echo "</table><br><input type=\"submit\" value=\"Markierte PMs löschen\">";
		}
		else echo "<tr><td class=\"td1\" colspan=\"5\" align=\"center\"><span class=\"norm\">--Keine Nachrichten vorhanden--</span></td></tr></table>"; // Falls keine PM vorhanden ist, wird das auch angezeigt
		echo "</form>";
	break;

	case "deletemany":
		for($i = 0; $i < $pm_anzahl; $i++) {
			$akt_pm = myexplode($pms[$i]);
			if($deletepm[$akt_pm[0]] == 1) {
				$pms[$i] = '';
			}
		}
		myfwrite("members/$pmbox_id.pm",$pms,'w');
		header("Location: index.php?faction=pm&profile_id=$profile_id&$HSID");
	break;

	// ***einzelne PM anzeigen***
	case "view":
		include("pageheader.php");
		for($i = 0; $i < $pm_anzahl; $i++) {
			$aktuelle_pm = myexplode($pms[$i]);
			if($aktuelle_pm[0] == $pm_id) { // Falls gesuchte ID zutrifft, kann fortgefahren werden
				if($aktuelle_pm[7] == 1) make_read($pmbox_id,$pm_id); // Falls PM noch ungelesen war, PM als "gelesen" markieren
				if($aktuelle_pm[5] == 1) $aktuelle_pm[2] = make_smilies($aktuelle_pm[2]); // Falls gewählt, werden Smilies umgewandelt
				if($aktuelle_pm[6] == 1) $aktuelle_pm[2] = upbcode($aktuelle_pm[2]); // Falls gewählt, wird UPB-Code umgewandelt
				echo navbar("<a class=\"navbar\" href=\"index.php?faction=pm&pmbox_id=$pmbox_id$MYSID2\">".$lng['pms']['PMs']."</a>\t$aktuelle_pm[1]");
				?>
					<table class="tbl" border="0" cellpadding="<?=$tpadding?>" cellspacing="<?=$tspacing?>" width="<?=$twidth?>">
					<tr><td class="thnorm" colspan="2"><span class="thnorm"><?=$lng['pms']['Read_PM']?></span></td></tr>
					<tr>
					 <td width="15%" class="td1"><span class="norm"><b><?=$lng['From']?>:</b></span>
					 <td width="85%" class="td1"><span class="norm"><?=get_user_name($aktuelle_pm[3])?></span></td>
					</tr>
					<tr>
					 <td width="15%" class="td1"><span class="norm"><b><?=$lng['Date']?>:</b></span>
					 <td width="85%" class="td1"><span class="norm"><?=makedatum($aktuelle_pm[4])?></span></td>
					</tr>
					<tr>
					 <td width="15%" class="td1"><span class="norm"><b><?=$lng['Subject']?>:</b></span></td>
					 <td width="85%" class="td1"><span class="norm"><?=$aktuelle_pm[1]?></span></td>
					</tr>
					<? if($tspacing < 1) echo "<tr><td colspan=\"2\" class=\"td1\"><hr></td></tr>"; ?>
					<tr><td colspan="2" class="td1"><span class="norm"><?=$aktuelle_pm[2]?></span></td></tr>
					</table>
				<?
				break;
			}
		}
	break;

	// ***PM beantworten***
	case "reply":
		$pm_data = get_pm_data($pmbox_id,$pm_id); // Daten der zu beantwortenden PM laden
		$betreff = urlencode("RE: ".$pm_data['title']); // Den Betreff aus "RE: " und dem alten Titel zusammensetzen
		header("Location: index.php?faction=pm&mode=send&betreff=$betreff&target_id=$pm_data[creator_id]&pmbox_id=$pmbox_id&$HSID"); // Auf das Formular verweisen
		exit;
	break;

	// ***PM löschen***
	case "kill":
		if($kill != "yes") {
			$pm_name = get_pm_name($pmbox_id,$pm_id);
			include("pageheader.php");
			echo navbar("<a class=\"navbar\" href=\"index.php?faction=pm&pmbox_id=$pmbox_id$MYSID2\">".$lng['pms']['PMs']."</a>\t<a class=\"navbar\" href=\"index.php?faction=pm&mode=view&pmbox_id=$pmbox_id&pm_id=$pm_id$MYSID2\">$pm_name</a>\t".$lng['pms']['Read_PM']);
			?>
				<form method="post" action="index.php?faction=pm&mode=kill&kill=yes<?=$MYSID2?>"><input type="hidden" name="pmbox_id" value="<?=$pmbox_id?>"><input type="hidden" name="pm_id" value="<?=$pm_id?>">
				<table class="tbl" border="0" cellpadding="<?=$tpadding?>" cellspacing="<?=$tspacing?>" width="<?=$twidth?>">
				<tr><th class="thnorm"><span class="thnorm"><?=$lng['pms']['Delete_PM']?></span></th></tr>
				<tr><td class="td1"><span class="norm"><center><br><?=sprintf($lng['pms']['Really_delete'],$pm_name)?></span><br><br></td></tr>
				</table><br><input type="submit" value="<?=$lng['pms']['Delete_PM']?>" onfocus="this.blur()"></form></center>
			<?
		}
		else {
			for($i = 0; $i < $pm_anzahl; $i++) {
				$aktuelle_pm = myexplode($pms[$i]);
				if ($aktuelle_pm[0] == $pm_id) {
					$pms[$i] = ""; // PM löschen
					$save = 1;
					break;
				}
			}
			if($save == 1) {
				myfwrite("members/$pmbox_id.pm",$pms,"w");
				header("Location: index.php?faction=pm&pmbox_id=$pmbox_id&$HSID"); exit;
			}
			else echo "PM-Lösch-Fehler!";
		}
	break;

	// ***PM senden***
	case "send":
		$showformular = 1; // Formular auf jeden Fall mal anzeigen
		$fehler = '';
		$fehler2 = '';

		if ($send == "yes") {
			$target_data = get_user_data($target_id);
			if(!myfile_exists("members/$target_id.xbb") || $target_id == 0 || $target_data[status] == 5) $fehler = $lng['pms']['error']['Unknown_member']; // Überprüfen, ob der Zieluser existiert
			elseif(trim($betreff) == '') $fehler2 = $lng['pms']['error']['No_subject'];
			else {
				$showformular = 0;
				if ($check != "yes") {
					include("pageheader.php");
					echo navbar("<a href=\"index.php?faction=pm&pmbox_id=$pmbox_id$MYSID2\">".$lng['pms']['PMs']."</a>\t".$lng['pms']['Confirmation']);
				?>
					<form method="post" action="index.php?faction=pm&mode=send&send=yes&check=yes<?=$MYSID2?>"><input type=hidden name=target_id value="<?=$target_id?>"><input type="hidden" name="betreff" value="<?=mutate($betreff)?>"><input type="hidden" name="pm" value="<?=mutate($pm)?>"><input type="hidden" name="smilies" value="<?=$smilies?>"><input type="hidden" name="use_upbcode" value="<?=$use_upbcode?>"><input type="hidden" name="pmbox_id" value="<?=$pmbox_id?>">
					<table class="tbl" border="0" cellpadding="<?=$tpadding?>" cellspacing="<?=$tspacing?>" width="<?=$twidth?>">
					<tr><th class="thnorm"><span class="thnorm"><?=$lng['pms']['Confirmation']?></span></th></tr>
					<tr><td class="td1"><span class="norm"><center><br><?=sprintf($lng['pms']['Really_send'],mutate($betreff),"$target_id ($target_data[nick])")?><br><br></center></span></td></tr>
					</table><br><input type="submit" value="<?=$lng['pms']['Send_PM']?>"></form></center>
				<?
				}

				else {
					$betreff = trim(mutate($betreff)); $pm = nlbr(trim(mutate($pm))); // PM und Betreff speicherbar und HTML-kompatibel machen
					$new_id = myfile("members/$target_id.pm"); $new_id = myexplode($new_id[sizeof($new_id)-1]); $new_id = $new_id[0]+1; // Neue PM-ID rausfinden
					$datum = mydate(); // Datum bestimmen
					$towrite = "$new_id\t$betreff\t$pm\t$pmbox_id\t$datum\t$smilies\t$use_upbcode\t1\t\r\n"; // Das zu schreibende zusammenstellen
					myfwrite("members/$target_id.pm",$towrite,"a"); // Daten schreiben
					mylog("9","%1: PM (an: $target_id) gesendet (IP: %2)");
					include("pageheader.php");
					echo navbar("<a href=\"index.php?faction=pm&pmbox_id=$pmbox_id$MYSID2\">".$lng['pms']['PMs']."</a>\t".$lng['templates']['pm_send'][0]);
					echo get_message('pm_send','<br>'.sprintf($lng['links']['pm_box'],"<a class=\"norm\" href=\"index.php?faction=pm&pmbox_id=$pmbox_id$MYSID2\">",'</a>').'<br>'.sprintf($lng['links']['forum_index'],"<a class=\"norm\" href=\"index.php$MYSID1\">",'</a>'));
				}
			}
		}

		if ($showformular == 1) {
			include("pageheader.php");
			?>
				<script language="JavaScript">
					<!--
					function setsmile(Zeichen) {
						document.pmform.pm.value = document.pmform.pm.value + Zeichen;
					}
					//-->
				</script>
				<?=navbar("<a class=\"navbar\" href=\"index.php?faction=pm&pmbox_id=$pmbox_id$MYSID2\">".$lng['pms']['PMs']."</a>\t".$lng['pms']['New_PM'])?>
				<form name="pmform" method="post" action="index.php?faction=pm&mode=send&send=yes<?=$MYSID2?>"><input type="hidden" name="pmbox_id" value="<?=$pmbox_id?>">
				<table class="tbl" border="0" cellpadding="<?=$tpadding?>" cellspacing="<?=$tspacing?>" width="100%">
				<tr><th class="thnorm" colspan="2"><span class="thnorm"><?=$lng['pms']['New_PM']?></span></th></tr>
				<tr>
				 <td width="20%" class="td1"><span class="norm"><b><?=$lng['pms']['Recipients_ID']?>:</b></span></td>
				 <td width="80%" class="td1"><input type="text" name="target_id" value="<?=$target_id?>">&nbsp;<span class="error"><?=$fehler?></span></td>
				</tr>
				<tr>
				 <td width="20%" class="td1"><span class="norm"><b><?=$lng['Subject']?>:</b></span></td>
				 <td width="80%" class="td1"><input type="text" name="betreff" value="<?=$betreff?>">&nbsp;<span class="error"><?=$fehler2?></span></td>
				</tr>
				<tr>
				 <td width="20%" class="td1" valign="top"><span class="norm"><b><?=$lng['Message']?>:</b></span><br><br><? include("smilies.php") ?></td>
				 <td width="80%" class="td1"><textarea name="pm" rows="10" cols="50"></textarea></td>
				</tr>
				<? if($tspacing < 1) echo "<tr><td class=\"td1\" colspan=2><hr></td></tr>"; ?>
				<tr>
				 <td width="20%" class="td1" valign="top"><span class="norm"><b><?=$lng['Options']?>:</b></span></td>
				 <td width="80%" class="td1"><span class="norm"><input type="checkbox" name="smilies" value="1" onfocus="this.blur()" checked> <?=$lng['Enable_smilies']?><br><input type="checkbox" name="use_upbcode" value="1" onfocus="this.blur()" checked> <?=$lng['Enable_TBB_code']?></span></td>
				</tr>
				</table><br><input type="submit" value="<?=$lng['pms']['Send_PM']?>" onfocus="this.blur()"></form></center>
			<?
		}
	break;

	} // Hier endet switch($mode)

}

wio_set("pm"); // WIO konfigurieren

?>