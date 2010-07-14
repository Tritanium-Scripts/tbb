<?

/* ad_newsletter.php - ermöglicht einen Newsletter per Mail oder PM zu schreiben (c) 2001-2002 Tritanium Scripts */

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
		default:
			include("pageheader.php");
			echo adnavbar($lng['ad_newsletter']['Send_Newsletter']);
			?>
				<form method="post" action="ad_newsletter.php<?=$MYSID1?>"><input type="hidden" name="mode" value="accept">
				<table class="tbl" border="0" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>" width="<?=$twidth?>">
				<tr><th class="thnorm"><span class="thnorm"><?=$lng['ad_newsletter']['Send_Newsletter']?></span></th></tr>
				<tr><td class="td1"><span class="norm"><b><?=$lng['ad_newsletter']['Who_is_the_newsletter_for']?></b><br><select name="target"><option value="1"><?=$lng['ad_newsletter']['All_members']?></option><option value="2"><?=$lng['ad_newsletter']['Only_moderators']?></option><option value="3"><?=$lng['ad_newsletter']['Only_administrators']?></option></select><br><b><?=$lng['ad_newsletter']['How_should_the_newsletter_be_sent']?></b><br><input type="radio" name="sendmethod" checked value="1"><?=$lng['ad_newsletter']['by_pm']?>  <input type="radio" name="sendmethod" value="2"> <?=$lng['ad_newsletter']['by_mail']?><br><b><?=$lng['ad_newsletter']['What_is_the_subject']?></b><br><input type="text" name="betreff" size="30"><br><b><?=$lng['ad_newsletter']['What_is_the_message']?></b><br><textarea name="newsletter" rows="8" cols="60"></textarea></span></td></tr>
				</table><br><input type="submit" value="<?=$lng['ad_newsletter']['next']?>"></form></center>
			<?
		break;

		case "accept":
			include("pageheader.php");
			echo adnavbar("<a href=\"ad_newsletter.php$MYSID1\">".$lng['ad_newsletter']['Send_Newsletter']."</a>\t".$lng['ad_newsletter']['Confirmation']);
			?>
				<form method="post" action="ad_newsletter.php<?=$MYSID1?>"><input type="hidden" name="mode" value="send"><input type="hidden" name="target" value="<?=$target?>"><input type="hidden" name="sendmethod" value="<?=$sendmethod?>"><input type="hidden" name="betreff" value="<?=mutate($betreff)?>"><input type="hidden" name="newsletter" value="<?=mutate($newsletter)?>">
				<table class="tbl" border="0" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>" width="<?=$twidth?>">
				<tr><th class="thnorm" colspan="2"><span class="thnorm"><?=$lng['ad_newsletter']['Confirmation']?></span></th></tr>
				<tr>
				 <td class="td1" width=10%><span class="norm"><b><?=$lng['Receiver']?>:</b></span></td>
				 <td class="td1" width=90%><span class="norm">
			<?
			switch($target) {
				default:
					echo $lng['ad_newsletter']['All_members'];
				break;
				case '2':
					echo $lng['ad_newsletter']['Only_moderators'];
				break;
				case '3':
					echo $lng['ad_newsletter']['Only_administrators'];
				break;
			}
			?>
				 </span></td>
				</tr>
				<tr>
				 <td class="td1" width="10%"><span class="norm"><b><?=$lng['ad_newsletter']['Sendmethod']?>:</b></span></td>
				 <td class="td1" width="90%"><span class="norm">
			<?
			switch($sendmethod) {
				default:
					echo $lng['ad_newsletter']['by_pm'];
				break;
				case '2':
					echo $lng['ad_newsletter']['by_mail'];
				break;
			}
			?>
				</span></td>
				</tr>
				<tr>
				 <td class="td1" width=10%><span class="norm"><b><?=$lng['Subject']?>:</b></span></td>
				 <td class="td1" width=90%><span class="norm"><?=trim(mutate($betreff))?></span></td>
				</tr>
				<tr>
				 <td class="td1" width=10% valign=top><span class="norm"><b><?=$lng['Message']?>:</b></span></td>
				 <td class="td1" width=90%><span class="norm"><?=trim(nlbr(mutate($newsletter)))?></span></td>
				</tr>
				</table><br><input type="submit" value="<?=$lng['ad_newsletter']['Confirmation']?>"></form></center>
			<?
		break;

		case "send":
			$members = myfile("vars/last_user_id.var"); $members = $members[0] + 1;
			$target_ids = ""; $member_id = 0;

			switch($target) {
				case '1':
					for($i = 1; $i < $members; $i++) {
						if($akt_user = myfile("members/$i.xbb")) {
							if(killnl($akt_user[4]) != "5" && killnl($akt_user[1]) != $user_id) {
								$akt_mail_options = explode(",",killnl($akt_user[14]));
								if($akt_mail_options[0] == 1) {
									$target_ids[$member_id][id] = killnl($akt_user[1]);
									$target_ids[$member_id][email] = killnl($akt_user[3]);
									$member_id++;
								}
							}
						}
					}
				break;
				case '2':
					for($i = 1; $i < $members; $i++) {
						if($akt_user = myfile("members/$i.xbb")) {
							if(killnl($akt_user[4]) == '2' && killnl($akt_user[1]) != $user_id) {
								$target_ids[$member_id][id] = killnl($akt_user[1]);
								$target_ids[$member_id][email] = killnl($akt_user[3]);
								$member_id++;
							}
						}
					}
				break;
				case '3':
					for($i = 1; $i < $members; $i++) {
						if($akt_user = myfile("members/$i.xbb")) {
							if(killnl($akt_user[4]) == "1" && killnl($akt_user[1]) != $user_id) {
								$target_ids[$member_id][id] = killnl($akt_user[1]);
								$target_ids[$member_id][email] = killnl($akt_user[3]);
								$member_id++;
							}
						}
					}
				break;
			}

			if($target_ids[0] == "") {
				include("pageheader.php");
				echo adnavbar("<a href=\"ad_newsletter.php$MYSID1\">".$lng['ad_newsletter']['Send_Newsletter']."</a>\t".$lng['templates']['no_receiver_in_target_group'][0]);
				echo get_message('no_receiver_in_target_group');
			}
			else {
				switch($sendmethod) {
					case '1':
						$betreff = mysslashes($betreff);
						$newsletter = str_replace("\r\n","\n",mysslashes($newsletter));
						for($i = 0; $i < sizeof($target_ids); $i++) {
							mymail($target_ids[$i][email],$betreff,$newsletter);
						}
					break;
					case '2':
						$betreff = mutate($betreff);
						$newsletter = nlbr(mutate($newsletter));
						for($i = 0; $i < sizeof($target_ids); $i++) {
							sendpm($target_ids[$i][id],$user_id,$betreff,$newsletter,0,0);
						}
					break;
				}
				mylog("8","%1: Newsletter ($sendmethod, $target) gesendet (IP: %2)");
				include("pageheader.php");
				echo adnavbar("<a class=\"navbar\" href=\"ad_newsletter.php$MYSID1\">".$lng['ad_newsletter']['Send_Newsletter']."</a>\t".$lng['templates']['newsletter_sent'][0]);
				echo get_message('newsletter_sent','<br>'.sprintf($lng['links']['administration'],"<a href=\"adminpanel.php$MYSID1\">",'</a>'),sizeof($target_ids));
			}
		break;
	}
wio_set("ad");
include("pagetail.php");
}

// S
?>