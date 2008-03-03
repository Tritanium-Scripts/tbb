<?

/* topic.php - zum löschen, öffnen/schließen und verschieben eines Topics (c) 2001-2002 Tritanium Scripts */

require_once("auth.php");

if($user_logged_in != 1 || (test_mod($forum_id,$user_id) != 1 && $user_data['status'] != 1)) echo "No"; // Autosisierung testen
elseif(!$topic_file = myfile("foren/$forum_id-$topic_id.xbb")) die('Error loading topic data!'); // Sicherstellen, dass Beitrag existiert
else {

	$topic_data = myexplode($topic_file[0]); // Topicinfo extrahieren
	if($config['censored'] == 1) $topic_data[1] = censor($topic_data[1]);
	$save = '';

	switch($mode) {
		case "kill":;
			if($kill != "yes") {
				echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">".get_forum_name($forum_id)."</a>\t<a class=\"navbar\" href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\">$topic_data[1]</a>\t".$lng['topic']['Delete_topic']);
				?>
					<form method="post" action="index.php?faction=topic&mode=kill&forum_id=<?=$forum_id?>&topic_id=<?=$topic_id?><?=$MYSID2?>"><input type="hidden" name="kill" value="yes">
					<table class="tbl" width="<?=$twidth?>" border="0" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
					<tr><th class="thnorm"><span class="thnorm"><?=$lng['topic']['Delete_topic']?></span></th></tr>
					<tr><td class="td1"><span class="norm"><center><br><?=sprintf($lng['topic']['Really_delete'],$topic_data[1])?><br><br></center></span></td></tr>
					</table><br><input type="submit" value="<?=$lng['topic']['Delete_topic']?>"></form></center>
				<?
			}
			else {

				// Falls Umfrage existiert
				if($topic_data[7] != '') {
					unlink("polls/$topic_data[7]-1.xbb");
					unlink("polls/$topic_data[7]-2.xbb");
				}

				$topic_size = sizeof($topic_file)-1; // Anzahl der Beiträge des Themas bestimmen
				unlink("foren/$forum_id-$topic_id.xbb"); // Themendatei löschen
				$topics = myfile("foren/$forum_id-threads.xbb");
				for($i = 0; $i < sizeof($topics); $i++) {
					if($topic_id == killnl($topics[$i])) {
						$topics[$i] = ""; break;
					}
				}

				myfwrite("foren/$forum_id-threads.xbb",$topics,"w");
				mylog("5","%1: Thema ($forum_id,$topic_id) gelöscht (IP: %2)");
				decrease_topic_number($forum_id); decrease_posts_number($forum_id,$topic_size);
				echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">".get_forum_name($forum_id)."</a>\t".$lng['templates']['topic_deleted'][0]);
				echo get_message('topic_deleted','<br>'.sprintf($lng['links']['topic_index'],"<a class=\"norm\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">",'</a>').'<br>'.sprintf($lng['links']['forum_index'],"<a class=\"norm\" href=\"index.php$MYSID1\">",'</a>'));
			}
		break;

		case "close":
			if($close != "yes") {
				echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">".get_forum_name($forum_id)."</a>\t<a class=\"navbar\" href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\">$topic_data[1]</a>\t".$lng['topic']['Close_topic']);
				?>
					<form method="post" action="index.php?faction=topic&mode=close&forum_id=<?=$forum_id?>&topic_id=<?=$topic_id?><?=$MYSID2?>"><input type="hidden" name="close" value="yes">
					<table class="tbl" width="<?=$twidth?>" border="0" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
					<tr><th class="thnorm"><span class="thnorm"><?=$lng['topic']['Close_topic']?></span></th></tr>
					<tr><td class="td1"><span class="norm"><center><br><?=sprintf($lng['topic']['Really_close'],$topic_data[1])?><br><br></center></span></td></tr>
					</table><br><input type="submit" value="<?=$lng['topic']['Close_topic']?>"></form></center>
				<?
			}
			else {
				$topic_data[0] = "2"; $topic_file[0] = myimplode($topic_data);
				myfwrite("foren/$forum_id-$topic_id.xbb",$topic_file,"w");
				mylog("5","%1: Thema ($forum_id,$topic_id) geschlossen (IP: %2)");
				echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">".get_forum_name($forum_id)."</a>\t<a class=\"navbar\" href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\">$topic_data[1]</a>\t".$lng['templates']['topic_closed'][0]);
				echo get_message('topic_closed','<br>'.sprintf($lng['links']['topic'],"<a class=\"norm\" href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\">",'</a>').'<br>'.sprintf($lng['links']['topic_index'],"<a class=\"norm\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">",'</a>').'<br>'.sprintf($lng['links']['forum_index'],"<a class=\"norm\" href=\"index.php$MYSID1\">",'</a>'));
			}
		break;

		case "open":
			if ($open != "yes") {
				echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">".get_forum_name($forum_id)."</a>\t<a class=\"navbar\" href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\">$topic_data[1]</a>\t".$lng['topic']['Open_topic']);
				?>
					<form method="post" action="index.php?faction=topic&mode=open&forum_id=<?=$forum_id?>&topic_id=<?=$topic_id?><?=$MYSID2?>"><input type="hidden" name="open" value="yes">
					<table class="tbl" width="<?=$twidth?>" border="0" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
					<tr><th class="thnorm"><span class="thnorm"><?=$lng['topic']['Open_topic']?></span></th></tr>
					<tr><td class="td1"><span class="norm"><center><br><?=sprintf($lng['topic']['Really_open'],$topic_data[1])?><br><br></center></span></td></tr>
					</table><br><input type="submit" value="<?=$lng['topic']['Open_topic']?>"></form></center>
				<?
			}
			else {
				$topic_data[0] = "1"; $topic_file[0] = myimplode($topic_data);
				myfwrite("foren/$forum_id-$topic_id.xbb",$topic_file,"w");
				mylog("5","%1: Thema ($forum_id,$topic_id) geöffnet (IP: %2)");
				echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">".get_forum_name($forum_id)."</a>\t<a class=\"navbar\" href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\">$topic_data[1]</a>\t".$lng['templates']['topic_opened'][0]);
				echo get_message('topic_opened','<br>'.sprintf($lng['links']['topic'],"<a class=\"norm\" href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\">",'</a>').'<br>'.sprintf($lng['links']['topic_index'],"<a class=\"norm\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">",'</a>').'<br>'.sprintf($lng['links']['forum_index'],"<a class=\"norm\" href=\"index.php$MYSID1\">",'</a>'));
			}
		break;

		case "move":
			$showformular = "yes";
			if($move == "yes") {
				if(!myfile_exists("foren/$target_forum-threads.xbb") || check_user_access($user_id,$target_forum) != 1) $fehler = $lng['topic']['error']['No_access_to_this_forum'].'<br>';
				else {
					$new_id = myfile("foren/$target_forum-ltopic.xbb"); $new_id = $new_id[0]+1; // Neue ID herausfinden
					$oldforum = myfile("foren/$forum_id-threads.xbb");
					$beitragszahl = sizeof($topic_file)-1;

					// Beitrag in Topicübersicht des alten Forums löschen
						for($i = 0; $i < sizeof($oldforum); $i++) {
							if(killnl($oldforum[$i]) == $topic_id) {
								$oldforum[$i] = "";	$save = 1;	break;
							}
						}
						if($save == 1) myfwrite("foren/$forum_id-threads.xbb",$oldforum,"w");
						else echo "Altertopic-Lösch Fehler!";

					myfwrite("foren/$target_forum-threads.xbb","$new_id\r\n","a");
					rename("foren/$forum_id-$topic_id.xbb","foren/$target_forum-$new_id.xbb"); // Datei umbenennen
					myfwrite("foren/$target_forum-ltopic.xbb",$new_id,"w");
					mylog("5","%1: Thema ($forum_id,$topic_id) verschoben ($target_forum,$new_id) (IP: %2)");
					decrease_posts_number($forum_id,$beitragszahl); decrease_topic_number($forum_id); increase_topic_number($target_forum); increase_posts_numberx($target_forum,$beitragszahl); // Gesamt Post-/Topiczahlen korrigieren
					$showformular = "no";
					echo navbar($lng['templates']['topic_moved'][0]);
					echo get_message('topic_moved','<br>'.sprintf($lng['links']['moved_topic'],"<a class=\"norm\" href=\"index.php?mode=viewthread&forum_id=$target_forum&thread=$new_id$MYSID2\">",'</a>').'<br>'.sprintf($lng['links']['forum_index'],"<a class=\"norm\" href=\"index.php$MYSID1\">",'</a>'));
				}
			}

			if($showformular == "yes") {
			echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">".get_forum_name($forum_id)."</a>\t<a class=\"navbar\" href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\">$topic_data[1]</a>\t".$lng['topic']['Move_topic']);
			?>
				<form method=post action="index.php?faction=topic<?=$MYSID2?>"><input type="hidden" name="forum_id" value="<?=$forum_id?>"><input type="hidden" name="move" value="yes"><input type="hidden" name="topic_id" value="<?=$topic_id?>"><input type="hidden" name="mode" value="move">
				<table class="tbl" width="<?=$twidth?>" border="0" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
				<tr><th class="thnorm"><span class="thnorm"><?=$lng['topic']['Move_topic']?></span></th></tr>
				<? if($fehler != "") echo "<tr><td class=\"td1\"><span class=\"error\">$fehler</span></td></tr>"; ?>
				<tr><td class="td1"><span class="norm"><?=sprintf($lng['topic']['Where_move'],$topic_data[1]);?></span><br>
				<select name="target_forum" size="1">
				<?
					$foren = myfile("vars/foren.var"); $foren_anzahl = sizeof($foren);
					$kg = myfile("vars/kg.var"); $kg_anzahl = sizeof($kg);
					for ($j = 0; $j < $kg_anzahl; $j++) {
						$ak_kg = myexplode($kg[$j]);
						echo "<option value=\"\">--$ak_kg[1]</option>";
						for ($i = 0; $i < $foren_anzahl; $i++) {
							$ak_forum = myexplode($foren[$i]);
							if ($ak_forum[5] == $ak_kg[0] && check_user_access($user_id,$ak_forum[0]) == 1 && $ak_forum[0] != $forum_id) {
								echo "<option value=\"$ak_forum[0]\">$ak_forum[1]</option>";
							}
						}
						echo "<option value=\"\"></option>";
					}
		 		?>
				</select></td></tr>
				</table><br><input type="submit" value="<?=$lng['topic']['Move_topic']?>"></form></center>
			<?
			}
		break;
	}
}

wio_set("topic");

?>