<?

/* listmember.php - Zeigt eine Liste aller Member an (c) 2001-2002 Tritanium Scripts */

require_once("auth.php");

if($config['activate_mlist'] != 1) { // Prüfen, ob die Mitgliederliste in den Settings aktiviert wurde
	echo navbar($lng['templates']['function_not_available'][0]);
	echo get_message('function_not_available','<br>'.sprintf($lng['links']['forum_index'],"<a class=\"norm\" href=\"index.php$MYSID1\">",'</a>'));
}
else {

// Erst mal die Sortierfunktionen
function cmpid($a,$b) {
    if($a['id']  == $b['id']) return 0;
    return ($a['id'] > $b['id']) ? 1 : -1;
}

function cmpposts($a,$b) {
    if($a['posts'] == $b['posts']) return 0;
    return ($a['posts'] > $b['posts']) ? -1 : 1;
}

function cmpname($a,$b) {
    return strcasecmp($a['name'],$b['name']);
}

function cmpstatus($a,$b) {
    if($a['status']  == $b['status']) { // Das einzige Kompliziertere: Falls die Stati gleich sind, wird sekundär nach Posts sortiert
        if($a['posts']  == $b['posts']) return 0;
    	return ($a['posts'] > $b['posts']) ? -1 : 1;
    }
    return ($a['status'] > $b['status']) ? 1 : -1;
}

$x = 0;

$memberanzahl = myfile("vars/last_user_id.var"); $memberanzahl = $memberanzahl[0] + 1;

// Jetzt werden alle Member geladen
for($i = 1; $i < $memberanzahl; $i++) {
	if($akt_member = myfile("members/$i.xbb")) {
		if(killnl($akt_member[4]) != 5) {
			$member[$x]["name"] = killnl($akt_member[0]);
			$member[$x]["id"] = killnl($akt_member[1]);
			$member[$x]["status"] = killnl($akt_member[4]);
			$member[$x]["posts"] = killnl($akt_member[5]);
			$member[$x]["mail"] = killnl($akt_member[3]);
			$member[$x]["moptions"] = explode(",",killnl($akt_member[14]));
			$x++;
		}
	}
}

$memberanzahl = sizeof($member); // Das stellt fest, wieviele Mitglieder tatsächlich vorhanden sind
if(!isset($sortmethod)) $sortmethod = 'id';
// Jetzt können die Member (gegebenfalls) erst sortiert werden
switch($sortmethod) {
	case "name":
		usort($member,"cmpname");
	break;
	case "id":
		nix(); // Diese Funktion macht so viel, hoffentlich geht da der Webserver nicht kaputt...
	break;
	case "posts":
		usort($member,"cmpposts");
	break;
	case "status":
		usort($member,"cmpstatus");
	break;
	default:
		nix(); // Hier gilt das gleiche wie oben, jeder Server hat seine Grenzen...
	break;
}

$member_per_page = 30; // Anzahl der Mitglieder, die pro Seite angezeigt werden

$seitenzahl = ceil($memberanzahl/$member_per_page);

if(!$z) $z = 1; $z2 = $z * $member_per_page; $y = $z2-$member_per_page; if($z2 > $memberanzahl) $z2 = $memberanzahl;

for($i = 1; $i < $seitenzahl+1; $i++) {
	if($i != $z) {
		$seitenanzeige[($i-1)] = "<a class=\"small\" href=\"index.php?faction=mlist&sortmethod=$sortmethod&z=$i$MYSID2\">$i</a>";
	}
	else $seitenanzeige[$i-1] = $i;
}
$seitenanzeige = sprintf($lng['Pages'],implode(" ",$seitenanzeige));


echo navbar($lng['Memberlist']);
?>
	<table class="tbl" border="0" cellpadding="<?=$tpadding?>" cellspacing="<?=$tspacing?>" width="<?=$twidth?>">
	<tr>
	 <th class="thsmall"><a class="thsmall" href="index.php?faction=mlist&sortmethod=id&z=<?=$z?><?=$MYSID2?>"><span class="thsmall"><u><?=$lng['ID']?></u></span></a></th>
	 <th class="thsmall"><a class="thsmall" href="index.php?faction=mlist&sortmethod=name&z=<?=$z?><?=$MYSID2?>"><span class="thsmall"><u><?=$lng['Nick']?></u></span></a></th>
	 <th class="thsmall"><a href="index.php?faction=mlist&sortmethod=status&z=<?=$z?><?=$MYSID2?>"><span class="thsmall"><u><?=$lng['Status']?></u></span></a></th>
	 <th class="thsmall"><a href="index.php?faction=mlist&sortmethod=posts&z=<?=$z?><?=$MYSID2?>"><span class="thsmall"><u><?=$lng['Posts']?></u></b></span></a></th>
	 <th class="thsmall" width="1%"></th>
	 <th class="thsmall" width="1%"></th>
	</tr>
<?
	for($i = $y; $i < $z2; $i++) {
		if($member[$i]["status"] != "5") {
			// Emailkram
				if($member[$i]['moptions'][1] != 1 && $member[$i]['moptions'][0] != 1) $akt_email = "";
				elseif($member[$i]['moptions'][0] != 1 && $member[$i]['moptions'][1] == 1) $akt_email = "<a href=\"mailto:".$member[$i]["mail"]."\"><img src=images/mailto.gif border=0></a>&nbsp;".$lng['mail'];
				else $akt_email = "<a href=\"index.php?faction=formmail&target_id=".$member[$i]['id']."$MYSID2\"><img src=images/mailto.gif border=0></a>&nbsp;".$lng['mail'];
			// Ende vom Emailkram
			?>
				<tr>
				 <td class="td1"><span class="norm"><?=$member[$i]["id"]?></span></td>
				 <td class="td2"><span class="norm"><a class="norm" href="index.php?faction=profile&profile_id=<?=$member[$i]["id"]?><?=$MYSID2?>"><?=$member[$i]["name"]?></a></span></td>
				 <td class="td1"><span class="norm"><?=morph_status($member[$i]["status"],$member[$i]["posts"])?></span></td>
				 <td class="td2"><span class="norm"><?=$member[$i]["posts"]?></span></td>
				 <td class="td1" align="center"><span class="small"><?=$akt_email?></span></td>
				 <td class="td2" align="center"><span class="small"><a class="small" href="index.php?faction=pm&mode=send&target_id=<?=$member[$i]["id"]?><?=$MYSID2?>"><img border=0 src=images/pm.gif></a>&nbsp;<?=$lng['pm']?></span></td>
				 <? if($user_data[status] == 1) echo "<td class=\"td1\" align=\"center\"><span class=\"small\"><a class=\"small\" href=\"ad_user.php?mode=edit&id=".$member[$i]["id"]."$MYSID2\">".$lng['Edit_user']."</a></span></td>"; ?>
				</tr>
			<?
		}
	}
?>
	</table><br>
	<table class="tbl" border="0" cellpadding="<?=$tpadding?>" cellspacing="<?=$tspacing?>" width="<?=$twidth?>">
	<tr><td class="td1"><span class="small"><?=$seitenanzeige?></span></td></tr>
	</table>
<?

}

/* Diese Zeile darf nicht gelöscht werden!! Warum weiß ich auch nicht. Hab ich aber grade so beschlossen! */

?>