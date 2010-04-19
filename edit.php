<?

/* edit.php - Um Beiträge zu bearbeiten/löschen (c) 2001/2002 Tritanium Scripts */

require_once("auth.php");

if(!$topic_file = myfile("foren/$forum_id-$topic_id.xbb")) die("Error loading data!");
else {

$topic_data = myexplode($topic_file[0]);
$post_data = get_post_data($forum_id,$topic_id,$post_id);
$is_mod = test_mod($forum_id);
$forum_data = get_forum_data($forum_id);

if($user_logged_in != 1) {
	echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">".$forum_data['name']."</a>\t<a class=\"navbar\" href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\">$topic_data[1]</a>\t".$lng['No_access']);
	echo get_message('nli','<br>'.sprintf($lng['links']['register_or_login'],"<a class=\"norm\" href=\"index.php?faction=register$MYSID2\">",'</a>',"<a class=\"norm\" href=\"index.php?faction=login$MYSID2\">",'</a>'));
}
elseif(check_right($forum_id,4) != 1) {
	echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">".$forum_data['name']."</a>\t<a class=\"navbar\" href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\">$topic_data[1]</a>\t".$lng['No_access']);
	echo get_message('na');
}
elseif($post_data['creator_id'] != $user_id && $is_mod != 1 && $user_data['status'] != 1) { // Überprüfen, ob User autorisiert ist
	echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">".$forum_data['name']."</a>\t<a class=\"navbar\" href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\">$topic_data[1]</a>\t".$lng['No_access']);
	echo get_message('na');
}
else {

	$save = "";

	switch($mode) {
		default:
			if($post_data['signatur'] == 1) $checked['sig'] = " checked";
			if($post_data['smilies'] == 1) $checked['smilies'] = " checked";
			if($post_data['upbcode'] == 1 && $forum_data['upbcode']) $checked['upbcode'] = " checked";
			if($post_data['htmlcode'] == 1 && $forum_data['htmlcode']) $checked['htmlcode'] = " checked";
			?>
				<script language="JavaScript">
					<!--
					function setsmile(Zeichen) {
					document.beitrag.post.value = document.beitrag.post.value + Zeichen;
					}
					//-->
				</script>
				<?=navbar("<a href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">".$forum_data['name']."</a>\t<a href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\">$topic_data[1]</a>\t".$lng['edit2']['Edit_post'])?>
				<form method="post" action="index.php?faction=edit&topic_id=<?=$topic_id?>&post_id=<?=$post_id?>&forum_id=<?=$forum_id?><?=$MYSID2?>" name="beitrag"><input type="hidden" name="mode" value="update">
				<table class="tbl" width="<?=$twidth?>" border="0" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
				<tr><th class="thnorm" colspan=2><span class="thnorm"><?=$lng['edit2']['Edit_post']?></span></th></tr>
	 		<?
			if($forum_data['upbcode'] == 1) {
				?>
					<tr>
					 <td class="td1" width="20%" valign="top"><span class="norm"><b><?=$lng['TBB-Code']?>:</b></span></td>
	 				 <td class="td1" width="80%"><? include("forumcode.php") ?></td>
	 				</tr>
				<?
			}
	 		?>

				<tr>
				 <td class="td1" width="20%" valign="top"><span class="norm"><b><?=$lng['Post']?>:</b></span><br><br><? include("smilies.php"); ?></td>
				 <td class="td1" width="80%"><textarea name="post" rows="10" cols="60"><?=brnl($post_data[post])?></textarea></td>
				</tr>
				<? if($cellspacing > 0) echo "<tr><td class=\"td1\" colspan=\"2\"><hr></td></tr>"; ?>
				<tr>
				 <td class="td1" width="20%" valign="top"><span class="norm"><b><?=$lng['Options']?>:</b></span></td>
				 <td class="td1" width="80%"><span class="norm"><input type="checkbox" name="show_signatur" value="1" onfocus="this.blur()"<?=$checked['sig']?>> <?=$lng['Show_signature']?><br><input type="checkbox" name="smilies" value="1" onfocus="this.blur()"<?=$checked['smilies']?>> <?=$lng['Enable_smilies']?><? if($forum_data['upbcode'] == 1) echo "<br><input type=\"checkbox\" name=\"use_upbcode\" value=\"1\" onfocus=\"this.blur()\"".$checked['upbcode']."> ".$lng['Enable_TBB_code']; ?><? if($forum_data['htmlcode'] == 1) echo "<br><input type=\"checkbox\" name=\"use_htmlcode\" value=\"1\" onfocus=\"this.blur()\"".$checked['htmlcode']."> ".$lng['Enable_HTML_code']; ?></span></td>
				</tr>
				</table><br><input type="submit" value="<?=$lng['edit2']['Edit_post']?>"></form></center>
			<?
		break;

		case "update":
			$post = nlbr(trim(mutate($post))); // Text zum Speichern geeignet machen
			for($i = 1; $i < sizeof($topic_file); $i++) {
				$akt_post = myexplode( $topic_file[$i]);
				if($akt_post[0] == $post_id) {
					$akt_post[3] = $post; // Text updaten
					$akt_post[5] = $show_signatur; // Signaturzeigen-Option updaten
					$akt_post[7] = $smilies; // Smilieszeigen-Option updaten
					$akt_post[8] = $use_upbcode; // UPBverwenden-Option updaten
					$akt_post[9] = $use_htmlcode;
					$topic_file[$i] = myimplode($akt_post);
					$save = 1; // Speichern aktivieren
					break; // Schleife beenden (= Zeit sparen)
				}
			}
			if($save == 1) {
				myfwrite("foren/$forum_id-$topic_id.xbb",$topic_file,"w");
				mylog("5","%1: Beitrag ($forum_id,$topic_id,$post_id) bearbeitet (IP: %2)");
				echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">".$forum_data['name']."</a>\t<a class=\"navbar\" href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\">$topic_data[1]</a>\t".$lng['edit2']['Post_edited']);
				echo get_message('post_edited','<br>'.sprintf($lng['links']['topic'],"<a class=\"norm\" href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id&z=last$MYSID2\">",'</a>').'<br>'.sprintf($lng['links']['topic_index'],"<a class=\"norm\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">",'</a>').'<br>'.sprintf($lng['links']['forum_index'],"<a class=\"norm\" href=\"index.php$MYSID1\">",'</a>'));
			}
		break;

		case "kill":
			if($kill != "yes") {
				echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">".$forum_data['name']."</a>\t<a class=\"navbar\" href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\">$topic_data[1]</a>\t".$lng['edit2']['Delete_post']);
				?>
					<form method="post" action="index.php?faction=edit&mode=kill&forum_id=<?=$forum_id?>&topic_id=<?=$topic_id?>&post_id=<?=$post_id?><?=$MYSID2?>"><input type="hidden" name="kill" value="yes">
					<table class="tbl" width="<?=$twidth?>" border="0" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
					<tr><th class="thnorm" colspan=2><span class="thnorm"><?=$lng['edit2']['Delete_post']?></span></th></tr>
					<tr><td class="td1"><span class="norm"><br><center><?=$lng['edit2']['Really_delete']?><br><br></span></td></tr>
					</table><br><input type="submit" value="<?=$lng['edit2']['Delete_post']?>"></form></center>
				<?
			}
			else {
				$topic_file_size = sizeof($topic_file); // "Größe" der Themendatei bestimmen
				for($i = 1; $i < $topic_file_size; $i++) {
					$aktueller_post = myexplode( $topic_file[$i]);
					if($post_id == $aktueller_post[0]) {
						$topic_file[$i] = ""; // Beitrag löschen
						$save = 1; // Speichern aktivieren
						break; // Schleife beenden (= Zeit sparen :)
					}
				}
				if($save == 1) { // Falls Speichern aktiviert wurde, gehts weiter
					myfwrite("foren/$forum_id-$topic_id.xbb",$topic_file,"w");
					decrease_posts_number($forum_id,1); // Zahl aller Beiträge des Forums um 1 verkleinern

					if($topic_file_size == 2) { // Falls das der letzte Beitrag des Themas war, kann das Thema gelöscht werden
						$topic_deleted = 1; // Sicherstellen, dass später keine Link zum Thema mehr angezezigt wird

						// Falls Umfrage existiert
						if($topic_data[7] != '') {
							unlink("polls/$topic_data[7]-1.xbb");
							unlink("polls/$topic_data[7]-2.xbb");
						}

						unlink("foren/$forum_id-$topic_id.xbb"); // Themendatei löschen
						$topics = myfile("foren/$forum_id-threads.xbb"); // Forumthemendatei laden
						for($i = 0; $i < sizeof($topics); $i++) {
							if($topic_id == killnl($topics[$i])) {
								$topics[$i] = ""; // Themenverweis löschen
								$save = "yes"; // Speichern aktivieren
								break; // Schleife beenden
							}
						}
						if($save == "yes") {
							myfwrite("foren/$forum_id-threads.xbb",$topics,"w"); // Wenn speichern aktiviert wurde, geht's weiter
							decrease_topic_number($forum_id); // Zahl aller Themen des Forums um 1 verringern
						}
						else echo "Themen-Löschen-Fehler!"; // Falls nicht geschrieben wurde, Fehler anzeigen
					}
					if($topic_deleted == 1) {
						echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">".$forum_data['name']."</a>\t".$lng['edit2']['Post_deleted']);
						echo get_message('post_deleted','<br>'.sprintf($lng['links']['topic_index'],"<a class=\"norm\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">",'</a>').'<br>'.sprintf($lng['links']['forum_index'],"<a class=\"norm\" href=\"index.php$MYSID1\">",'</a>'));
					}
					else {
						echo navbar("<a class=\"navbar\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">".$forum_data['name']."</a>\t<a class=\"navbar\" href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id$MYSID2\">$topic_data[1]</a>\t".$lng['edit2']['Post_deleted']);
						echo get_message('post_deleted','<br>'.sprintf($lng['links']['topic'],"<a class=\"norm\" href=\"index.php?mode=viewthread&forum_id=$forum_id&thread=$topic_id&z=last$MYSID2\">",'</a>').'<br>'.sprintf($lng['links']['topic_index'],"<a class=\"norm\" href=\"index.php?mode=viewforum&forum_id=$forum_id$MYSID2\">",'</a>').'<br>'.sprintf($lng['links']['forum_index'],"<a class=\"norm\" href=\"index.php$MYSID1\">",'</a>'));
					}

				}
				else echo "Post delete error!"; // English-Test! :)
			}
		break;
	}
}

wio_set("edit"); // WhoIsOnline konfigurieren

}
// E
?>