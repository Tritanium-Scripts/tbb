<?

/* search.php - Zum durchsuchen des Forums (c) 2001-2002 Tritanium Scripts */

require_once("auth.php");

if($search != "yes" || $auswahl == "" || $searchfor == "") {
	echo navbar($lng['search']['Search']);
	?>
		<form method="post" action="index.php?faction=search<?=$MYSID2?>"><input type="hidden" name="search" value="yes">
		<table class="tbl" width="<?=$twidth?>" border=0 cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
		<tr><th class="thnorm" colspan="2"><span class="thnorm"><?=$lng['search']['Search']?></span></th></tr>
		<tr>
		 <td width="20%" class="td1"><span class="norm"><b><?=$lng['search']['Search_for']?>:</b></span></td>
		 <td width="80%" class="td1"><input type="text" name="searchfor"> <span class="small">(<?=$lng['search']['Seperate_with_spaces']?>)</span></td>
		</tr>
		<tr>
		 <td width="20%" class="td1"><span class="norm"><b><?=$lng['search']['Search_in']?>:</b></span></td>
		 <td width="80%" class="td1"><select name="auswahl" size="1"><option value="all"><?=$lng['search']['Search_all_forums']?></option>
	<?
	$foren = myfile("vars/foren.var"); $kg = myfile("vars/kg.var");
	for($j = 0; $j < sizeof($kg); $j++) {
		$ak_kg = myexplode($kg[$j]);
		echo "<option value=\"\"><option value=\"\">--$ak_kg[1]</option>";
		for($i = 0; $i < sizeof($foren); $i++) {
			$ak_forum = myexplode($foren[$i]);
			if($ak_forum[5] == $ak_kg[0]) {
				echo "<option value=$ak_forum[0]>$ak_forum[1]</option>";
			}
		}
	}
	?>
		</select></td></tr>
		<tr>
		 <td width="20%" class="td1"></td>
		 <td width="80%" class="td1"><select name="soption1"><option value="1"><?=$lng['search']['Titles_and_posts']?></option><option value="2"><?=$lng['search']['Only_posts']?></option><option value="3"><?=$lng['search']['Only_titles']?></option></select></td>
		</tr>
		<tr>
		 <td width="20%" class="td1"><span class="norm"><b><?=$lng['search']['Maximum_age']?>:</b></span></td>
		 <td width="80%" class="td1"><select name="age"><option value="-1"><?=$lng['search']['no_matter']?></option><option value="1"><?=$lng['1_day']?></option><option value="7"><?=$lng['7_days']?></option><option value="14"><?=$lng['14_days']?></option><option value="30"><?=$lng['30_days']?></option></select></td>
		</tr>
		</table><br><input type="submit" value="<?=$lng['search']['Search']?>"></form></center>
	<?
}

else {

	$x1 = 0;
	$x2 = 0;
	$tosearch = '';
	$result = array();
	$searchfor = explode(' ',$searchfor);

	// Erst wird das zu Suchende zusammengestellt
	if($auswahl == "all") {
		$forum_file = myfile("vars/foren.var");
		for($i = 0; $i < sizeof($forum_file); $i++) {
			$akt_forum = myexplode($forum_file[$i]);
			$akt_forum_rights = explode(',',$akt_forum[10]);
			$right = 0;
			if($user_logged_in != 1) {
				if($akt_forum_rights[6] == 1) $right = 1;
			}
			elseif(check_right($akt_forum[0],0) == 1) $right = 1;

			if($right == 1) {
				$akt_forum_topics_file = myfile("foren/$akt_forum[0]-threads.xbb");
				for($j = 0; $j < sizeof($akt_forum_topics_file); $j++) {
					$tosearch[$x1] = "$akt_forum[0]-".killnl($akt_forum_topics_file[$j]);
					$x1++;
				}
			}
		}
	}
	else {
		if($forum_data = get_forum_data($auswahl)) {
			$right = 0;
			if($user_logged_in != 1) {
				if($forum_data['rights'][6] == 1) $right = 1;
			}
			elseif(check_right($akt_forum[0],0) == 1) $right = 1;

			if($right == 1) {
				$akt_forum_topics_file = myfile("foren/$auswahl-threads.xbb");
				for($j = 0; $j < sizeof($akt_forum_topics_file); $j++) {
					$tosearch[$x1] = "$auswahl-".killnl($akt_forum_topics_file[$j]);
					$x1++;
				}
			}
		}
	}


	// Auch wenn dieses Script am kompliziertesten erscheint, ist es doch das logischste (nur mal so als Anmerkung... ;)

	// Jetzt sind alle Themen zusammen, jetzt können sie durchsucht werden
	switch($soption1) {

		// Thementitel und Beiträge
		default:
			for($i = 0; $i < sizeof($tosearch); $i++) {
				if($akt_topic_file = myfile("foren/$tosearch[$i].xbb")) {
					$found = 0;
					$akt_topic_data = myexplode($akt_topic_file[0]); $akt_topic_lpost = myexplode($akt_topic_file[sizeof($akt_topic_file)-1]);
					if($age == -1 || get_time_string($akt_topic_lpost[2])+3600*24*$age >= time()) { // Erst wird überprüft, ob das Thema überhaupt in Frage kommt
						for($j = 0; $j < sizeof($searchfor); $j++) { // Als erstes wird dann der Titel durchsucht
							if(stristr($akt_topic_data[1],$searchfor[$j])) {
								$result[$x2] = $tosearch[$i];
								$x2++; $found = 1;
								break;
							}
						}
						if($found != 1) { // Falls im Titel nichts war, werden die einzelnen Posts durchsucht
							for($j = 1; $j < sizeof($akt_topic_file); $j++) {
								$akt_post = myexplode($akt_topic_file[$j]);
								if($age == -1 || get_time_string($akt_post[2])+3600*24*$age >= time()) {
									for($k = 0; $k < sizeof($searchfor); $k++) {
										if(stristr($akt_post[3],$searchfor[$k])) {
											$result[$x2] = $tosearch[$i];
											$x2++;
											break 2;
										}
									}
								}
							}
						}
					}
				}
			}
		break;

		// Nur Beiträge
		case "2":
			for($i = 0; $i < sizeof($tosearch); $i++) {
				if($akt_topic_file = myfile("foren/$tosearch[$i].xbb")) {
					$akt_topic_lpost = myexplode($akt_topic_file[sizeof($akt_topic_file)-1]);
					if($age == -1 || get_time_string($akt_topic_lpost[2])+3600*24*$age >= time()) { // Erst wird überprüft, ob das Thema überhaupt in Frage kommt
						for($j = 1; $j < sizeof($akt_topic_file); $j++) {
							$akt_post = myexplode($akt_topic_file[$j]);
							if($age == -1 || get_time_string($akt_post[2])+3600*24*$age >= time()) {
								for($k = 0; $k < sizeof($searchfor); $k++) {
									if(stristr($akt_post[3],$searchfor[$k])) {
										$result[$x2] = $tosearch[$i];
										$x2++;
										break 2;
									}
								}
							}
						}
					}
				}
			}
		break;

		// Nur Thementitel
		case "3":
			for($i = 0; $i < sizeof($tosearch); $i++) {
				if($akt_topic_file = myfile("foren/$tosearch[$i].xbb")) {
					$akt_topic_data = myexplode($akt_topic_file[0]); $akt_topic_lpost = myexplode($akt_topic_file[sizeof($akt_topic_file)-1]);
					if($age == -1 || get_time_string($akt_topic_lpost[2])+3600*24*$age >= time()) { // Erst wird überprüft, ob das Thema überhaupt in Frage kommt
						for($j = 0; $j < sizeof($searchfor); $j++) {
							if(stristr($akt_topic_data[1],$searchfor[$j])) {
								$result[$x2] = $tosearch[$i];
								$x2++; $found = 1;
								break;
							}
						}
					}
				}
			}
		break;
	}

	echo navbar("<a class=\"navbar\" href=\"index.php?faction=search$MYSID2\">Forum durchsuchen</a>\tSuche abgeschlossen");
	?>
		<table class="tbl" width="<?=$twidth?>" border=0 cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
		<tr><th class="thnorm"><span class="thnorm"><?=$lng['search']['Search_finished']?></span></th></tr>
	<?
	$results = sizeof($result);
	if($results == 0) echo "<tr><td class=\"td1\"><span class=\"norm\">".$lng['search']['Nothing_found'].'</span></td></tr>';
	else {
		echo "<tr><td class=\"td1\"><span class=\"norm\">".$lng['search']['Following_posts_found'].':<br>';
		for($h = 0; $h < $results; $h++) {
			$akt_result = explode("-",$result[$h]);
			echo "<a class=\"norm\" href=\"index.php?mode=viewthread&forum_id=$akt_result[0]&thread=$akt_result[1]$MYSID2\">".get_thread_name($akt_result[0],$akt_result[1])."</a><br>";
		}
		echo "</span></td></tr>";
	}
	echo "</table></center>";
}

wio_set("search");

?>