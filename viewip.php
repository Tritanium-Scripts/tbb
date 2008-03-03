<?

/* viewip.php - Zeigt die IP eines Posts an und sperrt sie gegebenfalls (c) 2001-2002 Tritanium Scripts */

require_once("auth.php");

if(!$forum_data = get_forum_data($forum_id)) echo "Kein Forum gewählt!";
elseif(!$topic_data = get_topic_data($forum_id,$topic_id)) echo "Kein Thema gewählt!";
elseif(!$post_id) echo "Kein Post gewählt!";
elseif($user_data[status] != 1 && test_mod($forum_id,$user_id) != 1) {
	echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">$forum_data[name]</a>\t<a class=\"navbar\" href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\">$topic_data[title]</a>\t".$lng['templates']['na'][0]);
	echo get_message('na');
}
else {
	if(!$mode || $mode == "") $mode = "view";

	if($mode == "view") {
		echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">$forum_data[name]</a>\t<a class=\"navbar\" href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\">$topic_data[title]</a>\t".$lng['viewip']['View_IP']);
		?>
			<table class="tbl" width="<?=$twidth?>" border=0 cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
			<tr><th class="thnorm"><span class="thnorm"><?=$lng['viewip']['View_IP']?></span></th></tr>
			<tr><td class="td1"><span class="norm"><center><br><?=sprintf($lng['viewip']['text'],get_post_ip($forum_id,$topic_id,$post_id),"<a class=\"norm\" href=\"index.php?faction=viewip&mode=sperren&forum_id=$forum_id&topic_id=$topic_id&post_id=$post_id$MYSID2\">",'</a>',"<a class=\"norm\" href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\">",'</a>')?><br><br></center></span></td></tr>
			</table></center>
		<?
	}

	if($mode == "sperren") {

		$showformular = 1;

		$target_ip = get_post_ip($forum_id,$topic_id,$post_id);

		$ips = myfile("vars/ip.var");

		for($i = 0; $i < sizeof($ips); $i++) {
			$akt_ip = myexplode($ips[$i]);
			if($akt_ip[0] == $target_ip && ($akt_ip[1] == -1 || $akt_ip[1] > time()) && ($akt_ip[2] == $forum_id || $akt_ip[2] == -1)) {
				$showformular = 0; $sperren = "";
				echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">$forum_data[name]</a>\t<a class=\"navbar\" href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\">$topic_data[title]</a>\t<a class=\"navbar\" href=\"index.php?faction=viewip&forum_id=$forum_id&topic_id=$topic_id&post_id=$post_id$MYSID2\">IP ansehen</a>\tIP sperren");
				?>
					<table class="tbl" width="<?=$twidth?>" border=0 cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
					<tr><th class="thnorm"><span class="thnorm">IP gesperrt</span></th></tr>
					<tr><td class="td1"><span class="norm"><center>Die IP wurde schon gesperrt!<br><a class="norm" href="index.php?mode=viewthread&forum_id=<?=$forum_id?>&thread=<?=$topic_id?><?=$MYSID2?>">Hier</a> geht's zurück zu Thema</center></span></td></tr>
					</table>
				<?
			}
		}

		if($sperren == "yes") {
			if($spdauer != 60 && $spdauer != 120 && $spdauer != 300 && $spdauer != 1440 && $spdauer != -1 && ($foren == "ja" && $user_data[status] != 1)) {
				$fehler = "Diese Funktion ist Administratoren vorbehalten!";
			}
			else {
				$last_id = myexplode($ips[sizeof($ips) - 1]);
				$last_id = $last_id[3]+1;

				$showformular = 0;
				if($spdauer == -1) $exp_time = -1; else $exp_time = time() + (60 * $spdauer);
				if($foren == "yes") $target_forum = -1; else $target_forum = $forum_id;
				$towrite = "$target_ip\t$exp_time\t$forum_id\t$last_id\t\r\n";
				myfwrite("vars/ip.var",$towrite,"a");
			}
		}

		if($showformular == 1) {
			echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">$forum_data[name]</a>\t<a class=\"navbar\" href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\">$topic_data[title]</a>\t<a class=\"navbar\" href=\"index.php?faction=viewip&forum_id=$forum_id&topic_id=$topic_id&post_id=$post_id$MYSID2\">IP ansehen</a>\tIP sperren");
			?>
				<form method="post" action="index.php?faction=viewip<?=$MYSID2?>"><input type="hidden" name="upb" value="viewip"><input type="hidden" name="mode" value="sperren"><input type="hidden" name="forum_id" value="<?=$forum_id?>"><input type="hidden" name="topic_id" value="<?=$topic_id?>"><input type="hidden" name="post_id" value="<?=$post_id?>"><input type="hidden" name="sperren" value="yes">
				<table class="tbl" width="<?=$twidth?>" border=0 cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
				<tr><th class="thnorm" colspan="2"><span class="thnorm">IP sperren</span></th></tr>
				<? if($fehler != "") echo "<tr><td class=\"td1\" colspan=\"2\"><span class=\"error\">$fehler</span></td></tr>"; ?>
				<tr>
				 <td class="td1" width="20%"><span class="norm"><b>IP:</b></span></td>
				 <td class="td1"><span class="norm"><?=$target_ip?></span></td>
				</tr>
				<tr>
				 <td class="td1" width="20%"><span class="norm"><b>Sperrdauer:</b></span></td>
				 <td class="td1"><select name="spdauer" size="1"><option value="60">1 Stunde</option><option value="120">2 Stunden</option><option value="300">5 Stunden</option><option value="1440">1 Tag</option><option value="-1">Immer</option></select></td>
				</tr>
			<?
			if($user_data[status] == 1) echo "<tr><td class=\"td1\" colspan=\"2\"><span class=\"norm\">IP für das gesamte UPB sperren <input type=\"checkbox\" name=\"foren\" value=\"ja\" onfocus=\"this.blur()\"></span></td></tr>";
			echo "</table><br><input type=\"submit\" value=\"sperren\"></form></center>";
		}
	}
}

?>