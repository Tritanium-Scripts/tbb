<?

/* ad_ip.php - Verwaltet die IP-Sperren (c) 2001-2002 Tritanium Scripts */

require_once("functions.php");
require_once("loadset.php");
require_once("auth.php");
ad();

if($user_logged_in != 1 || $user_data['status'] != 1) {
	mylog("2","%1: Administrationszugriffversuch (IP: %2)");
	header("Location: ad_login.php?$HSID"); exit;
}
else {
	$save = "";
	if(!$mode || $mode == "") $mode = "overview";

	if($mode == "overview") {
		$ips = myfile("vars/ip.var");
		include("pageheader.php");
		echo adnavbar($lng['ad_ip']['Manage_IP_bans']);
		?>
			<table class="tbl" border=0 width="<?=$twidth?>" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
			<tr>
			 <th class="thsmall"><span class="thsmall"><?=$lng['ad_ip']['IP']?></span></th>
			 <th class="thsmall"><span class="thsmall"><?=$lng['ad_ip']['remaining_ban_time']?></span></th>
			 <th class="thsmall"><span class="thsmall"><?=$lng['ad_ip']['banned_for']?></span></th>
			 <th class="thsmall"></th>
			</tr>
		<?
		if(sizeof($ips) == 0) echo "<tr><td class=\"td1\" colspan=\"4\" align=\"center\"><span class=\"norm\">".$lng['ad_ip']['no_bans']."</span></td></tr>";
		for($i = 0; $i < sizeof($ips); $i++) {
			$akt_ip = myexplode($ips[$i]);
			if($akt_ip[1] == -1) $akt_ip[1] = '('.$lng['ad_ip']['banned_forever'].')';
			elseif($akt_ip[1] > time()) $akt_ip[1] = round(($akt_ip[1] - time()) / 60).' '.$lng['ad_ip']['minutes'];
			else $akt_ip[1] = '('.$lng['ad_ip']['finished'].')';
			if($akt_ip[2] == -1) $akt_ip[2] = $lng['ad_ip']['all_forums'];
			else $akt_ip[2] = get_forum_name($akt_ip[2]);
			?>
				<tr>
				 <td class="td1"><span class="norm"><?=$akt_ip[0]?></span></td>
				 <td class="td1"><span class="norm"><?=$akt_ip[1]?></span></td>
				 <td class="td1"><span class="norm"><?=$akt_ip[2]?></span></td>
				 <td class="td1" align="center"><span class="norm"><a class="norm" href="ad_ip.php?mode=kill&id=<?=$akt_ip[3]?><?=$MYSID2?>"><?=$lng['delete']?></a></span></td>
				</tr>
			<?
		}
		echo "</table></center><span class=\"norm\"><a class=\"norm\" href=\"ad_ip.php?mode=new$MYSID2\">".$lng['ad_ip']['add_IP_ban']."</a></span><center>";
	}

	if($mode == "kill") {
		$ips = myfile("vars/ip.var");
		for($i = 0; $i < sizeof($ips); $i++) {
			$akt_ip = myexplode($ips[$i]);
			if($id == $akt_ip[3]) {
				$save = 1; $ips[$i] = ""; break;
			}
		}

		if($save == 1) {
			myfwrite("vars/ip.var",$ips,"w");
			mylog("8","%1: Administration: IP-Sperre gelöscht (IP: %2)");
			header("Location: ad_ip.php?$HSID"); exit;
		}
		else echo "IP-Sperre-Lösch-Fehler!";
	}

	if($mode == "new") {
		$showformular = 1;
		if($create == "yes") {
			if(!myfile_exists("foren/$sperrziel-threads.xbb") && $sperrziel != -1) {
				$fehler = $lng['ad_ip']['error']['forum_does_not_exist'];
			}
			else {
				$ip = $ip;
				$showformular = 0;
				$last_id = myfile("vars/ip.var"); $last_id = myexplode($last_id[sizeof($last_id) - 1]); $last_id = $last_id[3]+1;

				if($sperrtime != -1) $sperrtime = time() + ($sperrtime * 60);

				$towrite = "$ip\t$sperrtime\t$sperrziel\t$last_id\t\r\n";
				myfwrite("vars/ip.var",$towrite,"a");

				mylog("8","%1: Administraion: Neue IP-Sperre ($ip, $sperrziel, $sperrtime) erstellt (IP: %2)");
				header("Location: ad_ip.php?$HSID"); exit;
			}
		}

		if($showformular == 1) {
			include("pageheader.php");
			echo adnavbar("<a class=\"navbar\" href=\"ad_ip.php$MYSID1\">".$lng['ad_ip']['Manage_IP_bans']."</a>\t".$lng['ad_ip']['add_IP_ban']);
			?>
				<form method="post" action="ad_ip.php<?=$MYSID1?>"><input type="hidden" name="mode" value="new"><input type="hidden" name="create" value="yes">
				<table class="tbl" border=0 cellpadding="<?=$tpadding?>" cellspacing="<?=$tspacing?>" width="<?=$twidth?>">
				<tr><th class="thnorm" colspan="2"><span class="thnorm"><?=$lng['ad_ip']['add_IP_ban']?></span></th>
				<? if($fehler != "") echo "<tr><td class=\"td1\" colspan=2><span class=\"error\">$fehler</span></td></tr>"; ?>
				<tr>
				 <td class="td1" width=1%><span class="norm"><b><?=$lng['ad_ip']['IP']?>:</b></span></td>
				 <td class="td1"><input type="text" name="ip" value="<?=$ip?>"></td>
				</tr>
				<tr>
				 <td class="td1" width=1%><span class="norm"><b><?=$lng['ad_ip']['ban_duration']?>:</b></span></font></td>
				 <td class="td1"><input type="text" size="4" name="sperrtime" value="<?=$sperrtime?>"> <span class="small">(<?=$lng['ad_ip']['ban_duration_text']?>)</span></td>
				</tr>
				<tr>
				 <td class="td1" width=1%><span class="norm"><b><?=$lng['ad_ip']['ban_destination']?>:</b></span></td>
				 <td class="td1"><select name="sperrziel" size="1"><option value="-1"><?=$lng['ad_ip']['all_forums']?></option>
			<?
			$forums = myfile("vars/foren.var");
			for($i = 0; $i < sizeof($forums); $i++) {
				$akt_forum = myexplode($forums[$i]);
				echo "<option value=\"$akt_forum[0]\">$akt_forum[1]</option>";
			}
			?>
				 </select></td>
				</tr>
				</table><br><input type="submit" value="<?=$lng['ad_ip']['add_IP_ban']?>"></form></center>
			<?
		}
	}

wio_set("ad");
include("pagetail.php");
// E
}