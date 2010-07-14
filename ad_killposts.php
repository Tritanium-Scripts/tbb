<?

/* ad_killposts.php - ermöglicht es, bequem veraltete Beiträge zu löschen (c) 2001-2002 Tritanium Scripts */

require_once("functions.php");
require_once("loadset.php");
require_once("auth.php");
ad();

if($user_logged_in != 1 || $user_data[status] != 1) {
	mylog("2","%1: Administrationszugriffversuch (IP: %2)");
	header("Location: ad_login.php?$HSID"); exit;
}
else {
	switch($mode) {
		case "kill":
			$new_space_counter = 0; $killed_topics_counter = 0; $killed_posts_counter = 0; // Setzt alle Zähler auf 0
			if(!myfile_exists("foren/$target_forum-threads.xbb") && $target_forum != "all") header("Location: ad_killposts.php?$HSID"); // Falls kein Forum ausgewählt wurde, wird der User wieder zum Formular gelinkt
			elseif($target_forum == "all") { // Falls "Alle Foren" ausgewählt wurde
				$foren = myfile("vars/foren.var"); // Foren laden
				for($a = 0; $a < sizeof($foren); $a++) {
					$akt_forum = myexplode($foren[$a]);
					$akt_forum_topics = myfile("foren/$akt_forum[0]-threads.xbb"); // Themen-Index des Forums laden
					for($b = 0; $b < sizeof($akt_forum_topics); $b++) {
						$akt_forum_topics_file = killnl($akt_forum_topics[$b]);
						$akt_topic = myfile("foren/$akt_forum[0]-$akt_forum_topics_file.xbb");
						$akt_topic_lpost = myexplode($akt_topic[sizeof($akt_topic) - 1]);
						if(round(((time() - mktime(substr($akt_topic_lpost[2],8,2),substr($akt_topic_lpost[2],10,2),0,substr($akt_topic_lpost[2],4,2),substr($akt_topic_lpost[2],6,2),substr($akt_topic_lpost[2],0,4))) / 60 / 60 / 24)) > $topic_age) {
							$akt_topic_file_info = stat("foren/$akt_forum[0]-$akt_forum_topics_file.xbb"); $new_space_counter = $new_space_counter + $akt_topic_file_info[7]; // Freier Speicherplatz Counter erhöhen
							$killed_topics_counter++; $killed_posts_counter = $killed_posts_counter + sizeof($akt_topic) - 1; // Gelöschte Posts/Themen Counter um 1 bzw. x erhöhen
							decrease_topic_number($akt_forum[0]); decrease_posts_number($akt_forum[0],(sizeof($akt_topic) - 1)); unlink("foren/$akt_forum[0]-$akt_forum_topics_file.xbb");
							$akt_forum_topics[$b] = ""; // Thema im Themen-Index löschen
						}
					}
					myfwrite("foren/$akt_forum[0]-threads.xbb",$akt_forum_topics,"w");
				}
			}
			else {
				$target_forum_topics = myfile("foren/$target_forum-threads.xbb"); // Themen-Index des Forums laden
				for($b = 0; $b < sizeof($target_forum_topics); $b++) {
					$target_forum_topics_file = killnl($target_forum_topics[$b]);
					$akt_topic = myfile("foren/$target_forum-$target_forum_topics_file.xbb");
					$akt_topic_lpost = myexplode($akt_topic[sizeof($akt_topic) - 1]);
					if(round(((time() - mktime(substr($akt_topic_lpost[2],8,2),substr($akt_topic_lpost[2],10,2),0,substr($akt_topic_lpost[2],4,2),substr($akt_topic_lpost[2],6,2),substr($akt_topic_lpost[2],0,4))) / 60 / 60 / 24)) > $topic_age) {
						$akt_topic_file_info = stat("foren/$target_forum-$target_forum_topics_file.xbb"); $new_space_counter = $new_space_counter + $akt_topic_file_info[7]; // Freier Speicherplatz Counter erhöhen
						$killed_topics_counter++; $killed_posts_counter = $killed_posts_counter + sizeof($akt_topic) - 1; // Gelöschte Posts/Themen Counter um 1 bzw. x erhöhen
						decrease_topic_number($target_forum); decrease_posts_number($target_forum,(sizeof($akt_topic) - 1)); unlink("foren/$target_forum-$target_forum_topics_file.xbb");
						$target_forum_topics[$b] = ""; // Thema im Themen-Index löschen
					}
				}
				myfwrite("foren/$target_forum-threads.xbb",$target_forum_topics,"w");
			}

			mylog("8","%1: \"Alte Themen löschen\" (Ziel: $target_forum) ausgeführt (IP:%2)");

			include("pageheader.php");
			echo adnavbar("<a class=\"navbar\" href=\"ad_killposts.php$MYSID1\">".$lng['ad_killposts']['Delete_Old_Topics']."</a>\t".$lng['templates']['old_topics_deleted_statistic'][0]);
			echo get_message('old_topics_deleted_statistic',"<br>($killed_posts_counter ".$lng['Posts'].", $killed_topics_counter ".$lng['Topics'].')',round($new_space_counter / 1024,2));
		break;

		default:
			include("pageheader.php");
			echo adnavbar($lng['ad_killposts']['Delete_Old_Topics']);
			?>
				<form method="post" action="ad_killposts.php<?=$MYSID1?>"><input type="hidden" name="mode" value="kill">
				<table class="tbl" border="0" cellpadding="<?=$tpadding?>" cellspacing="<?=$tspacing?>" width="<?=$twidth?>">
				<tr><th class="thnorm" colspan=2><span class="thnorm"><?=$lng['ad_killposts']['Delete_Old_Topics']?></span></th></tr>
				<tr>
				 <td class="td1" width="10%"><span class="norm"><b><?=$lng['Forum']?>:</b></span></td>
				 <td class="td1" width="90%"><select size="1" name="target_forum"><option value="all"><?=$lng['ad_killposts']['all_forums']?></option>
				 <?
				 	$foren = myfile("vars/foren.var"); $kg = myfile("vars/kg.var");
				 	for ($j = 0; $j < sizeof($kg); $j++) {
				 		$ak_kg = myexplode($kg[$j]);
				 		echo "<option value=\"\"><option value=\"\">--$ak_kg[1]</option>";
				 		for ($i = 0; $i < sizeof($foren); $i++) {
				 			$ak_forum = myexplode($foren[$i]);
				 			if ($ak_forum[5] == $ak_kg[0]) {
				 				echo "<option value=\"$ak_forum[0]\">$ak_forum[1]</option>";
				 			}
				 		}
				 	}
				 ?>
				 </select></td>
				</tr>
				<tr><td class="td1" colspan="2"><span class="norm"><?=sprintf($lng['ad_killposts']['delete_topics_older_than'],"<select name=\"topic_age\"><option value=\"15\">".$lng['time']['15_days']."</option><option value=\"30\">".$lng['time']['1_month']."</option><option value=\"60\">".$lng['time']['2_months']."</option><option value=\"90\" selected>".$lng['time']['3_months']."</option><option value=\"180\">".$lng['time']['6_months']."</option></select>")?></span></td></tr>
				<tr><td class="td1" colspan="2"><span class="norm"><? if($tspacing < 1) echo "<hr>" ?><b><?=$lng['ad_killposts']['warning']?></b></span></td></tr>
				</table><br><input type="submit" value="<?=$lng['delete']?>"></form></center>
			<?
		break;
	}
}

wio_set("ad");
include("pagetail.php");
// K
?>