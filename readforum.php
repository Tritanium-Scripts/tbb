<?

/* readforum.php - Forenübersicht, Foren und Themen anzeigen (c) 2001-2002 Tritanium Scripts */

require_once("auth.php");


// ***Forenübersicht anzeigen***

switch($mode) {

default:
	$posts = 0; $themen = 0; // Zähler auf 0 setzen
	$kg = myfile("vars/kg.var"); $kg_size = sizeof($kg); // Kategorien laden
	$foren = myfile("vars/foren.var"); $foren_anzahl = sizeof($foren); // Foren laden
	?>
		<center><br><table class="navbar" width="<?=$twidth?>" border="0" cellspacing="0" cellpadding="0"><tr><td class="navbar"><span class="navbar">&nbsp;<?=$config['forum_name']?></span></td><td class="navbar" align="right"><span class="navbar"><?=$user_status?></span></td></tr></table><br>
		<table class="tbl" border="0" width="<?=$twidth?>" cellpadding="<?=$tpadding?>" cellspacing="<?=$tspacing?>">
	<?
	if($config['news_position'] == 1) include("shownews.php"); // News eventuell auf Position 1 anzeigen
	?>
		<tr class="thsmall">
		 <th class="thsmall" colspan=2><span class="thsmall"><?=$lng['Forum']?></span></th>
		 <th class="thsmall"><span class="thsmall"><?=$lng['Topics']?></span></th>
		 <th class="thsmall"><span class="thsmall"><?=$lng['readforum']['overview']['Posts']?></span></th>
		 <th class="thsmall" width="28%"><span class="thsmall"><?=$lng['Last_Post']?></span></th>
		 <th class="thsmall"><span class="thsmall"><?=$lng['readforum']['overview']['Mods']?></span></th>
		</tr>
	<?

	if($config['news_position'] == 2) include("shownews.php"); // News eventuell auf Position 2 anzeigen

	if($foren_anzahl == 0) echo "<tr><td class=\"td1\" colspan=\"6\"><span class=\"norm\"<b><center>".$lng['readforum']['overview']['No_forum']."</center></b></span></td></tr>"; // Falls keine Foren vorhanden sind, aufhören und das anzeigen
	for($k = 0; $k < $kg_size; $k++) {
		$aktuelle_kg = myexplode($kg[$k]); // Kategoriendaten "extrahieren"
		$x = FALSE;
		while($akt_value = each($foren)) {
		//for($i = 0; $i < $foren_anzahl; $i++) {
			$aktuelles_forum = myexplode($akt_value[1]); // Forendaten "extrahieren"
			//$aktuelles_forum = myexplode($foren[$i]);
			if($aktuelles_forum[5] == $aktuelle_kg[0]) { // Forum anzeigen, wenn es zur Kategorie passt
				if($config['show_private_forums'] == 1) $right = 1;
				else {
					$akt_forum_rights = explode(',',$aktuelles_forum[10]);
					$right = 0;
					if($user_logged_in != 1) {
						if($akt_forum_rights[6] == 1) $right = 1;
					}
					else {
						if(check_right($aktuelles_forum[0],0) == 1) $right = 1;
					}
				}

				if($right == 1) {
					if($x == FALSE) {
						if($config['show_kats'] == 1) echo "<tr><td class=\"kat\" colspan=6><span class=\"kat\">$aktuelle_kg[1]</span></td></tr>";
						$x = TRUE;
					}
					$cookie_var = "forum,$aktuelles_forum[0]"; // Name für die Cookie-Variable festlegen
					if(!isset($$cookie_var) || $$cookie_var < $aktuelles_forum[6]) $onoff = "<img src=images/npost.gif>"; else $onoff = "<img src=images/nnpost.gif>"; // Bild festlegen, ob neue Beiträge vorhanden sind
					$posts += $aktuelles_forum[4]; $themen += $aktuelles_forum[3]; // Gesamtzahl an Themen/Posts aktualisieren
					?>
						<tr>
						 <td class="td1"><?=$onoff?></td>
						 <td class="td2"><span class="forumlink"><a class="forumlink" href="index.php?mode=viewforum&forum_id=<?=$aktuelles_forum[0]?><?=$MYSID2?>"><?=$aktuelles_forum[1]?></a></span><br><span class="small"><?=$aktuelles_forum[2]?></span></td>
						 <td class="td1" align=center><span class="norm"><?=$aktuelles_forum[3]?></span></td>
						 <td class="td2" align=center><span class="norm"><?=$aktuelles_forum[4]?></span></td>
						 <td class="td1" align=center><span class="small"><?=make_last_post($aktuelles_forum[0],$aktuelles_forum[9],$aktuelles_forum[8])?></span></td>
						 <td class="td2" align=center><span class="small"><?=get_forum_mods($aktuelles_forum[11])?></span></td>
						</tr>
					<?
				}
				unset($foren[$akt_value[0]]); // Dieses Forum kann nun "gelöscht" werden, da es schonmal angezeigt wurde (falls...)
			}
		//}
		}
		reset($foren);
	}
	echo "</table></center>";
	wio_set("index");
	include("wio.php"); // WIO-Box anzeigen
	if($config['show_board_stats'] == 1) {
		$newest_member = myfile("vars/last_user_id.var"); $newest_member = get_user_link($newest_member[0]);
		$number_of_members = myfile("vars/member_counter.var"); $number_of_members = $number_of_members[0];
		?>
			<br><center><table class="tbl" border=0 width="<?=$twidth?>" cellpadding="<?=$tpadding?>" cellspacing="<?=$tspacing?>">
			<tr><td class="thnorm"><span class="thnorm"><?=$lng['readforum']['overview']['Boardstatistics']?></span></td></tr>
			<tr><td class="td1"><span class="small"><?=$lng['readforum']['overview']['Registered_members']?>: <?=$number_of_members?><br><?=$lng['readforum']['overview']['Newest_member']?>: <?=$newest_member?><br><?=$lng['readforum']['overview']['Number_of_topics_posts']?>: <?="$themen/$posts"?></span></td></tr>
			</table></center>
		<?
	}
	if($config['show_lposts'] == 1) {
		$lposts = myfile("vars/lposts.var"); $lposts = myexplode($lposts[0]);
		$lposts_size = sizeof($lposts);	if($lposts_size > 5) $lposts_size = 5;
		?>
			<br><center><table class="tbl" border=0 width="<?=$twidth?>" cellpadding="<?=$tpadding?>" cellspacing="<?=$tspacing?>">
			<tr><td class="thnorm"><span class="thnorm"><?=$lng['readforum']['overview']['Newest_posts']?></span></td></tr>
			<tr><td class="td1"><span class="small">
		<?
			if($lposts[0] == "") echo $lng['readforum']['overview']['No_newest_posts'];
			else {
				for($i = 0; $i < $lposts_size; $i++) {
					if($lposts[$i] != "") {
						$akt_lpost = explode(",",$lposts[$i]);
						if(!myfile_exists("foren/$akt_lpost[0]-$akt_lpost[1].xbb")) $post_link = $lng['Deleted'];
						else {
							$akt_title = get_thread_name($akt_lpost[0],$akt_lpost[1]);
							if($config['censored'] == 1) $akt_title = censor($akt_title);
							if(strlen($akt_title) > 50) $akt_title = substr($akt_title,0,50).'...';
							$post_link = "<a href=\"index.php?mode=viewthread&forum_id=$akt_lpost[0]&thread=$akt_lpost[1]$MYSID2\">".$akt_title."</a>";
						}
						echo sprintf($lng['readforum']['overview']['newest_posts_link'],$post_link,get_user_link($akt_lpost[2]),makedatum($akt_lpost[3])).'<br>';
					}
				}
			}
		?>
			</span></td></tr>
			</table></center>
		<?
	}
break;

// Anmerkung (21.11.2001): Es werden nur Foren angezeigt, denen eine Kategorie zugewiesen wurde. Damit kann man auch erreichen, dass Foren gar nicht angezeigt werden

// ***Nur die einzelnen Thementitel anzeigen***
case "viewforum":
	$forum_data = get_forum_data($forum_id);
	$right = 0;
	if($user_logged_in != 1) {
		if($forum_data['rights'][6] == 1) $right = 1;
		else {
			echo navbar($lng['templates']['forum_nli'][0]);
			echo get_message('forum_nli','<br>'.sprintf($lng['links']['register_or_login'],"<a class=\"norm\" href=\"index.php?faction=register$MYSID2\">",'</a>',"<a class=\"norm\" href=\"index.php?faction=login$MYSID2\">",'</a>'));
		}
	}
	else {
		if(check_right($forum_id,0) != 1) {
			echo navbar($lng['templates']['forum_na'][0]);
			echo get_message('forum_na');
		}
		else $right = 1;
	}

	if($right == 1) {

	$topics_file = myfile("foren/$forum_id-threads.xbb"); $topics_file_size = sizeof($topics_file); // Themen, die zum Forum gehören, laden
	$topics_file = array_reverse($topics_file); // Neustes Thema zuerst
	$seiten_anzahl = ceil($topics_file_size / $config['topics_per_page']); if (!$z) $z = 1; $j = $z * $config['topics_per_page']; $x = $j - $config['topics_per_page']; if ($j > $topics_file_size) $j = $topics_file_size; // Seitenzahl bestimmen

	// Konfiguration der Seitenanzahlsanzeige
		if ($seiten_anzahl == 1 || $seiten_anzahl == 0) $show_pages = "";
		else {
			for ($i = 0; $i < $seiten_anzahl;$i++) {
				$i2 = $i + 1;
				if ($i2 == $z) $pages[$i] = $i2;
				else $pages[$i] = "<a href=\"index.php?mode=viewforum&forum_id=$forum_id&z=$i2$MYSID2\">$i2</a>";
			}
			$show_pages = sprintf($lng['Pages'],implode(" ",$pages));
		}
	// Ende der Konfiguration Seitenanzahlsanzeige

	echo navbar("$forum_data[name]</b> $show_pages<b>");
	?>
		<table class="tbl" width="<?=$twidth?>" border=0 cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
		 <tr>
		  <th colspan=3 class="thsmall"><span class="thsmall"><?=$lng['Topic']?></span></th>
		  <th class="thsmall"><span class="thsmall"><?=$lng['Topic_Creator']?></span></th>
		  <th class="thsmall"><span class="thsmall"><?=$lng['Replies']?></span></th>
		  <th class="thsmall"><span class="thsmall"><?=$lng['Views']?></span></th>
		  <th class="thsmall"><span class="thsmall"><?=$lng['Last_Post']?></span></th>
		 </tr>
	<?
	if($topics_file_size != 0) {
		for($y = $x; $y < $j ; $y++) {
			$akt_topic['id'] = killnl($topics_file[$y]);
			$akt_topic_file = myfile("foren/$forum_id-$akt_topic[id].xbb"); $akt_topic_posts = sizeof($akt_topic_file); // Das Thema laden
			$akt_topic_data = myexplode($akt_topic_file[0]);
			$akt_topic_lpost = myexplode($akt_topic_file[$akt_topic_posts-1]);
			if($akt_topic_data[0] == "open") $akt_topic_data[0] = "1"; // Abwärtskompatiblität
			elseif($akt_topic_data[0] == "closed") $akt_topic_data[0] = "2"; // Abwärtskompatiblität

			if($akt_topic_data[6] == "") $akt_topic_data[6] = 0;

			// Beginn der Topic-Bild-Konfiguration
			$cookie_var = "topic,$forum_id,$akt_topic[id]"; // Name für die Cookie-Variable festlegen
			switch($akt_topic_data[0]) {
				case "1":
					if(!$$cookie_var || $$cookie_var < $akt_topic_data[5]) {
						if($akt_topic_posts <= $config['topic_is_hot']) $oc_image = "<img src=\"images/ontopic.gif\">";
						else $oc_image = "<img src=\"images/onstopic.gif\">";
					}
					else {
						if($akt_topic_posts <= $config['topic_is_hot']) $oc_image = "<img src=\"images/onntopic.gif\">";
						else $oc_image = "<img src=\"images/onnstopic.gif\">";
					}
				break;

				case "2":
					if(!$$cookie_var || $$cookie_var < $akt_topic_data[5]) {
						if($akt_topic_posts <= $config['topic_is_hot']) $oc_image = "<img src=\"images/cntopic.gif\">";
						else $oc_image = "<img src=\"images/cntopic.gif\">";
					}
					else {
						if($akt_topic_posts <= $config['topic_is_hot']) $oc_image = "<img src=\"images/cnntopic.gif\">";
						else $oc_image = "<img src=\"images/cnntopic.gif\">";
					}
				break;
			}
			// Ende der Topic-Bild-Konfiguration


			// Konfiguration der Seitenanzahl des einzelnen Themas
				$seitenanzahl = ceil(($akt_topic_posts-1) / $config['posts_per_page']);
				$seitenanzeige = "";
				if ($seitenanzahl > 1) {
					for ($ss = 0; $ss < $seitenanzahl; $ss++) {
						$ss2 = $ss + 1;	$seitenanzeige .= " <a href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$akt_topic[id]&z=$ss2$MYSID2\">$ss2</a>";
					}
					$seitenanzeige = sprintf($lng['Pages'],$seitenanzeige);
				}
			// Ende der Koniguration des...

			if($config['censored'] == 1) $akt_topic_data[1] = censor($akt_topic_data[1]);
			if($akt_topic_data[7] != '') $poll_text = $lng['Poll'].': ';
			else $poll_text = '';
			?>
				<tr>
				 <td class="td1" align="center"><?=$oc_image?></td>
				 <td class="td2" align="center"><img src="<?=get_tsmadress($akt_topic_data[3])?>"></td>
				 <td class="td1"><span class="topiclink"><?=$poll_text?><a class="topiclink" href="index.php?mode=viewthread&forum_id=<?=$forum_id?>&thread=<?=$akt_topic["id"]?><?=$MYSID2?>"><?=wordwrap($akt_topic_data[1],30,'<br>',1)?></a></span> <span class="small"><?=$seitenanzeige?></span></td>
				 <td class="td2"><span class="norm"><?=get_user_name($akt_topic_data[2])?></span></td>
				 <td class="td1" align="center"><span class="norm"><?=$akt_topic_posts-2?></span></td>
				 <td class="td2" align="center"><span class="norm"><?=$akt_topic_data[6]?></span></td>
				 <td class="td1" align="center"><span class="small"><?=sprintf($lng['readforum']['forum']['last_post'],makedatum($akt_topic_lpost[2]),get_user_name($akt_topic_lpost[1]))?></span></td>
				</tr>
			<?
		}
	}
	else echo "<tr><td class=\"td1\" colspan=\"7\" align=center><span class=\"norm\"><b>".$lng['readforum']['forum']['No_topics']."</b></span></td></tr>"; // Falls keine Beiträge vorhanden sind, das anzeigen
	echo "</table>";

	// Toolleiste anzeigen
	?>
		<br><table class="navbar" cellpadding="0" cellspacing="0" border="0" width="<?=$twidth?>"><tr><td class="td1"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td width="33%" valign="bottom"><span class="small">&nbsp;<a class="small" href="index.php?faction=newtopic&forum_id=<?=$forum_id?><?=$MYSID2?>" onfocus="this.blur()"><img border="0" src="images/newtopic.gif"></a>&nbsp;<a class="small" href="index.php?faction=newpoll&forum_id=<?=$forum_id?><?=$MYSID2?>" onfocus="this.blur()"><img border="0" src="images/newpoll.gif"></a></span></td><td width="34%" valign="bottom" align="center"><span class="small"><?=$toolbar?></span></td><td width=33% valign=middle align=right><span class="small"><?=$show_pages?></span></td></tr></table></td></tr></table></center>
	<?

	}
	wio_set("forum,$forum_id");
break;

// ***Das Thema ganz anzeigen***
case "viewthread":
	$is_mod = test_mod($forum_id);
	$forum_data = get_forum_data($forum_id);

	$right = 0;
	if($user_logged_in != 1) {
		if($forum_data['rights'][6] == 1) $right = 1;
		else {
			echo navbar($lng['templates']['forum_nli'][0]);
			echo get_message('forum_nli','<br>'.sprintf($lng['links']['register_or_login'],"<a class=\"norm\" href=\"index.php?faction=register$MYSID2\">",'</a>',"<a class=\"norm\" href=\"index.php?faction=login$MYSID2\">",'</a>'));
		}
	}
	else {
		if(check_right($forum_id,0) != 1) {
			echo navbar($lng['templates']['forum_na'][0]);
			echo get_message('forum_na');
		}
		else $right = 1;
	}

	if($right == 1) {
		$topic = myfile("foren/$forum_id-$thread.xbb"); // Laden des Themas
		$topic_data = myexplode($topic[0]);

		if($config['censored'] == 1) $topic_data[1] = censor($topic_data[1]);

		// Konfiguration der Toolleiste
			if($user_logged_in == 1) {
				if($is_mod == 1 || $user_data[status] == 1) {
					if($topic_data[0] == '1' || $topic_data[0] == 'open') $open_closed = "<a href=\"index.php?faction=topic&mode=close&forum_id=$forum_id&topic_id=$thread$MYSID2\"><img border=0 src=images/closetopic.gif></a>"; else $open_closed = "<a href=\"index.php?faction=topic&mode=open&forum_id=$forum_id&topic_id=$thread$MYSID2\"><img border=0 src=images/opentopic.gif></a>";
					$toolbar = "<a href=\"index.php?faction=topic&mode=kill&forum_id=$forum_id&topic_id=$thread$MYSID2\"><img border=0 src=images/deltopic.gif></a>&nbsp;$open_closed&nbsp;<a href=\"index.php?faction=topic&mode=move&forum_id=$forum_id&topic_id=$thread$MYSID2\"><img border=0 src=images/movetopic.gif></a>";
				} // if($is_mod == 1 || $user_data[status] == 1)
				else $toolbar = "&nbsp;";
			}
			else $toolbar = "&nbsp;";
		// Ende der Konfiguration der Toolleiste

		$real_size = sizeof($topic)-1; $seiten_anzahl = ceil($real_size / $config['posts_per_page'] ); if($z == "last") $z = $seiten_anzahl;
		if(!isset($z)) $z = 1; $j = $z * $config['posts_per_page']; $x = $j - $config['posts_per_page']; if ($j > $real_size) $j = $real_size; // Konfiguration der Seitenzahl



		// Konfiguration der Seitenanzahlsanzeige
			if($seiten_anzahl == 1) $show_pages = "";
			else {
				for ($i = 0; $i < $seiten_anzahl;$i++) {
					$i2 = $i + 1;
					if ($i2 == $z) $pages[$i] = $i2;
					else $pages[$i] = "<a href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$thread&z=$i2$MYSID2\">$i2</a>";
				}
				$show_pages = sprintf($lng['Pages'],implode(" ",$pages));
			}
		// Ende der Konfiguration der Seitenanzahlsanzeige

		echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">$forum_data[name]</a>\t".$topic_data[1].$show_pages);

		if($topic_data[7] != '') { // Falls Umfrage existiert
			if(myfile_exists("polls/$topic_data[7]-1.xbb")) {
				$poll_file = myfile("polls/$topic_data[7]-1.xbb");
				$poll_data = myexplode($poll_file[0]);
				$poll_voters = myfile("polls/$topic_data[7]-2.xbb"); $poll_voters = explode(',',$poll_voters[0]);

				$temp_var = "session_poll_$topic_data[7]";
				$temp_var2 = "cookie_poll_$topic_data[7]";
				$voted = 1;

				if($poll_data[0] > 2) {
					$button = "<span class=\"small\">(".$lng['readforum']['topic']['Poll_closed'].")</span>";
				}
				elseif(isset($$temp_var) || isset($$temp_var2) || ($user_logged_in == 1 && in_array($user_id,$poll_voters))) {
					$button = "<span class=\"small\">(".$lng['readforum']['topic']['Already_voted'].")</span>";
					$voted = 0;
				}
				elseif($user_logged_in == 1 || $poll_data[0] == 1) {
					$button = "<input type=\"submit\" value=\"".$lng['readforum']['topic']['Vote']."\">";
				}
				else $button = "<span class=\"small\">(".$lng['readforum']['topic']['Mbli_to_vote'].")</span>";

				if($user_data['status'] == 1 || $is_mod == 1 || ($user_id == $poll_data[1] && $user_logged_in == 1)) {
					$button .= "&nbsp;&nbsp;&nbsp;<input type=\"submit\" name=\"edit\" value=\"".$lng['edit']."\">";
				}

				?>
					<form method="post" action="index.php?faction=vote&forum_id=<?=$forum_id?>&topic_id=<?=$thread?>&poll_id=<?=$topic_data[7]?><?=$MYSID2?>">
					<table border="0" class="tbl" cellpadding="<?=$tpadding?>" cellspacing="<?=$tspacing?>" width="<?=$twidth?>">
					<tr><th class="thnorm"><span class="thnorm">Umfrage</span></th></tr>
					<tr><td class="td1">
					 <span class="norm"><b><?=$poll_data[3]?></b><br><table border="0" cellpadding="0" cellspacing="4">
				<?
				for($i = 1; $i < sizeof($poll_file); $i++) {
					$akt_poll = myexplode($poll_file[$i]);
					if($akt_poll[2] == 0) $votes = '0';
					else $votes = round(($akt_poll[2]/$poll_data[4])*100,1);
					echo "<tr><td><span class=\"norm\">$i. ";
					if($i == 1) $selected = ' checked';
					else $selected = '';
					if(($user_logged_in == 1 || $poll_data[0] == 1) && $poll_data[0] < 3 && $voted == 1) echo "<input type=\"radio\" name=\"vote_id\" onfocus=\"this.blur()\" value=\"$akt_poll[0]\"$selected>&nbsp;";
					?>
						<?=$akt_poll[1]?></span></td><td><img src="images/poll.gif" height="10" width="<?=round($votes)?>"></td><td><span class="small">(<?=$votes?> %, <?=$akt_poll[2]?> <?=$lng['readforum']['topic']['Votes']?>)</span></td></tr>
					<?
				}
				echo "</table>$button</span></td></tr></table></form>";
			}
		}

		?>
			<table class="tbl" width="<?=$twidth?>" border=0 cellspacing=<?=$tspacing?> cellpadding=<?=$tpadding?>>
			<tr>
			 <th class="thsmall" align="left" width=15%><span class="thsmall"><?=$lng['Author']?></span></th>
			 <th class="thsmall" align="left" width=85%><span class="thsmall"><?=$lng['Topic']?>: <?=$topic_data[1]?></span></th>
			</tr>
		<?



		//$akt_class = 'td1';

		for($i = $x + 1; $i < $j + 1; $i++) {
			$aktueller_beitrag = myexplode($topic[$i]);

			// Konfiguration der Userinformationen
				if(strncmp($aktueller_beitrag[1],'0',1) == 0) {
					$answer_creator['nick'] = substr($aktueller_beitrag[1],1,strlen($aktueller_beitrag[1]));
					$answer_creator['email'] = "";
					$answer_creator['regdatum'] = "";
					$answer_creator['hp'] = "";
					$answer_creator['icq'] = "";
					$answer_creator['group_name'] = "";
					$answer_creator['status'] = $lng['Guest'];

					$posts_text = '';
					$rank_pic = '';
					$id = '';
					$user_pic = '';
					$send_pm = '';
					$signatur = '';
				}
				elseif(!$answer_creator = get_user_data($aktueller_beitrag[1])) { // Falls der User gelöscht ist
					$answer_creator['nick'] = $config['var_killed'];
					$answer_creator['email'] = "";
					$answer_creator['regdatum'] = "";
					$answer_creator['hp'] = "";
					$answer_creator['icq'] = "";
					$answer_creator['group_name'] = "";
					$answer_creator['status'] = $lng['Deleted'];

					$posts_text = '';
					$rank_pic = '';
					$id = '';
					$user_pic = '';
					$send_pm = '';
					$signatur = '';
				}
				else {
					// Emailkram
						if($answer_creator['showemail'] != 1 && $answer_creator['forummails'] != 1) $answer_creator['email'] = "";
						elseif($answer_creator['forummails'] != 1 && $answer_creator['showemail'] == 1) $answer_creator['email'] = "&nbsp;&nbsp;<a href=\"mailto:$answer_creator[email]\"><img src=images/mailto.gif border=0></a> ".$lng['mail'];
						else $answer_creator['email'] = "&nbsp;&nbsp;<a href=\"index.php?faction=formmail&target_id=$answer_creator[id]$MYSID2\"><img src=images/mailto.gif border=0></a> ".$lng['mail'];
					// Ende vom Emailkram
					$answer_creator['nick'] = "<a span class=\"norm\" href=\"index.php?faction=profile&profile_id=$answer_creator[id]$MYSID2\" onfocus=\"this.blur()\">$answer_creator[nick]</a>";
					$answer_creator['regdatum'] = $lng['readforum']['topic']['userdata']['Regdate'].": <b>".makeregdatum($answer_creator['regdatum'])."</b> | ";
					if($answer_creator['hp'] != "") $answer_creator['hp'] = "&nbsp;&nbsp;<a href=\"".addhttp($answer_creator['hp'])."\" target=_blank><img src=images/hp.gif border=0></a> ".$lng['readforum']['topic']['homepage'];
					if($answer_creator['icq'] != "") $answer_creator['icq'] = "<br><br><a target=\"_blank\" href=\"http://wwp.icq.com/scripts/search.dll?to=$answer_creator[icq]\"><img border=\"0\" src=\"http://web.icq.com/whitepages/online?icq=$answer_creator[icq]&img=2\"></a>";

					if($answer_creator['group'] != "") { // Falls der User in einer Gruppe ist
						$group_data = get_group_data($answer_creator['group']);
						$answer_creator['group_name'] = $group_data['name'].'<br>';
						if($answer_creator['pic'] == '') $user_pic = get_user_pic($group_data['pic']); // Falls der User nicht ein anderes Bild hat, kann das der Gruppe geholt werden
						else $user_pic = get_user_pic($answer_creator['pic']);

					}
					else {
						$answer_creator['group_name'] = '';
						$user_pic = get_user_pic($answer_creator['pic']);
					}

					$posts_text = $lng['readforum']['topic']['userdata']['Posts'].": <b>$answer_creator[posts]</b> | ";
					$id = "ID # $answer_creator[id]";
					$rank_pic = get_rank_pic($answer_creator[status],$answer_creator[posts]);

					$send_pm = "&nbsp;&nbsp;<a href=\"index.php?faction=pm&mode=send&target_id=$aktueller_beitrag[1]$MYSID2\"><img border=0 src=images/pm.gif></a> ".$lng['pm'];
					$answer_creator['status'] = morph_status($answer_creator['status'],$answer_creator['posts']);

					if(($aktueller_beitrag[5] == 1 || $aktueller_beitrag[5] == "yes") && $answer_creator['signatur'] != "") {
						if($config['censored'] == 1) $temp_sig = censor($answer_creator['signatur']);
						else $temp_sig = $answer_creator['signatur'];
						$signatur = "<br><br>-----------------------<br>$temp_sig"; // Konfiguration der Signaturanzeige
					}
					else $signatur = '';

				}
			// Ende der Konfiguration der Userinformationen

			if($aktueller_beitrag[4] != "") $ip_info = "IP: <a href=\"index.php?faction=viewip&forum_id=$forum_id&topic_id=$thread&post_id=$aktueller_beitrag[0]$MYSID2\">".$lng['readforum']['topic']['ip']['saved']."</a>"; else $ip_info = "IP: ".$lng['readforum']['topic']['ip']['not_saved']; // Konfiguration der Anzeige, ob IP gespeichert wurde

			if($aktueller_beitrag[7] == 1 || $aktueller_beitrag[7] == "yes") $aktueller_beitrag[3] = make_smilies($aktueller_beitrag[3]); // Falls Smilies aktiviert wurden, Text umwandeln
			if(($aktueller_beitrag[8] == 1 || $aktueller_beitrag[8] == "yes") && $forum_data['upbcode'] == 1) $aktueller_beitrag[3] = upbcode($aktueller_beitrag[3]); // Falls UPB-Code aktiviert wurde, Text umwandeln
			if($aktueller_beitrag[9] == 1 && $forum_data['htmlcode'] == 1) $aktueller_beitrag[3] = demutate($aktueller_beitrag[3]);
			if($config['censored'] == 1) $aktueller_beitrag[3] = censor($aktueller_beitrag[3]);

			if($user_logged_in == 1) {
				if($user_data['status'] == 1 || $is_mod == 1 || ($user_data['id'] == $aktueller_beitrag[1] && $forum_data['rights'][4] == 1)) {
					$killpost = "<a href=\"index.php?faction=edit&mode=kill&forum_id=$forum_id&topic_id=$thread&post_id=$aktueller_beitrag[0]$MYSID2\"><img src=images/deltopic.gif border=0></a> ".$lng['delete'];
					$edit_post = "<a href=\"index.php?faction=edit&forum_id=$forum_id&topic_id=$thread&post_id=$aktueller_beitrag[0]$MYSID2\"><img src=images/edit.gif border=0></a>".$lng['edit'].' ';
				}
				else {
					$killpost = "";
					$edit_post = "";
				}
			}

			switch($akt_class) { // Farbe wechseln
				case "td1":
					$akt_class = "td2";
				break;
				default:
					$akt_class = "td1";
				break;
			}

			?>
				<tr>
				 <td rowspan="2" class="<?=$akt_class?>" width=15% valign=top><span class="norm"><b><?=$answer_creator[nick]?></b></span><br><span class="small"><?=$answer_creator['status']?><br><?=$answer_creator['group_name']?><?=$rank_pic?><br><?=$id?><br><br><?=$user_pic?><?=$answer_creator['icq']?></span></td>
				 <td class="<?=$akt_class?>" width=85% valign=top><span class="small"><img src="<? if ($aktueller_beitrag[6] == "" || get_tsmadress($aktueller_beitrag[6]) == "") echo "images/tsmilies/1.gif"; else echo get_tsmadress($aktueller_beitrag[6]); ?>">&nbsp;&nbsp;<?=$lng['readforum']['topic']['posted']?>: <?=makedatum($aktueller_beitrag[2])?>&nbsp;<img src="images/trenner.gif">&nbsp;<?=$edit_post?>&nbsp;<a href="index.php?faction=reply&thread_id=<?=$thread?>&forum_id=<?=$forum_id?>&quote=<?=$aktueller_beitrag[0]?><?=$MYSID2?>"><img border=0 src=images/quote.gif></a> <?=$lng['readforum']['topic']['quote']?>&nbsp;<?=$send_pm?><?=$answer_creator[email]?><?=$answer_creator[hp]?>&nbsp;&nbsp;<?=$killpost?></span><hr><span class="norm"><?=$aktueller_beitrag[3].upbcode_signatur($signatur)?></span></td>
				</tr>
				<tr>
				 <td class="<?=$akt_class?>" width=85%><? if($tspacing < 1) echo "<hr>" ?><font face=verdana size=1><?=$posts_text?><?=$answer_creator[regdatum]?><?=$ip_info?></font>
				</tr>
			<?
		}
		echo "</table>";
		echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">$forum_data[name]</a>\t".$topic_data[1].$show_pages,"no");
		?> <table class="navbar" width="<?=$twidth?>" cellspacing=0 border=0 cellpadding=0><tr><td class="navbar" width=33% valign=bottom><span class="navbar">&nbsp;<a href="index.php?faction=newtopic&forum_id=<?=$forum_id?><?=$MYSID2?>" onfocus=this.blur()><img border=0 src=images/newtopic.gif></a>&nbsp;<a class="small" href="index.php?faction=newpoll&forum_id=<?=$forum_id?><?=$MYSID2?>" onfocus="this.blur()"><img border="0" src="images/newpoll.gif"></a>&nbsp;<a href="index.php?faction=reply&thread_id=<?=$thread?>&forum_id=<?=$forum_id?><?=$MYSID2?>" onfocus="this.blur()"><img border=0 src=images/newreply.gif></a></span></td><td class="navbar" width=34% valign=bottom align=center><span class="navbar"><?=$toolbar?></span></td><td width=33% class="navbar" valign=middle align=right><span class="navbar"><?=$show_pages?></span></td></tr></table></center> <?
	}
	if($reply != 1) wio_set("view_topic,$forum_id,$thread");
break;

}

?>