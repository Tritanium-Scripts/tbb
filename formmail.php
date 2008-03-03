<?

/* formmail.php - sendet einem User eine Mail per Formular zu (c) 2001-2002 Tritanium Scripts */

require_once("auth.php");

if($config['activate_mail'] != 1) {
	echo navbar($lng['templates']['function_not_available'][0]);
	echo get_message('function_not_available','<br>'.sprintf($lng['links']['forum_index'],"<a class=\"norm\" href=\"index.php$MYSID1\">",'</a>'));
}
elseif($user_logged_in != 1 && $config['formmail_mbli'] == 1) {
	echo navbar($lng['templates']['nli'][0]);
	echo get_message('nli','<br>'.sprintf($lng['links']['register_or_login'],"<a class=\"norm\" href=\"index.php?faction=register$MYSID2\">",'</a>',"<a class=\"norm\" href=\"index.php?faction=login$MYSID2\">",'</a>'));
}
elseif($target_id == 0 || !$profile_data = get_user_data($target_id)) {
	echo navbar($lng['templates']['user_does_not_exist'][0]);
	echo get_message('user_does_not_exist','<br>'.sprintf($lng['links']['forum_index'],"<a class=\"norm\" href=\"index.php$MYSID1\">",'</a>'));
}
elseif($profile_data['forummails'] != 1) {
	echo navbar("<a class=\"navbar\" href=\"index.php?faction=profile&profile_id=$target_id$MYSID2\">Profil von $profile_data[nick] ansehen</a>\t".$lng['templates']['user_no_form_mails'][0]);
	echo get_message('user_no_form_mails','<br>'.sprintf($lng['links']['profile'],"<a class=\"norm\" href=\"index.php?faction=profile&profile_id=$target_id$MYSID2\">",'</a>').'<br>'.sprintf($lng['links']['forum_index'],"<a class=\"norm\" href=\"index.php$MYSID1\">",'</a>'));
}
else{

	$showformular = 1;
	$fehler = "";

	if($send == "yes") {
		$betreff = mutate($betreff);
		$message = mutate($message);
		if($user_logged_in == 1) {
			$sender_name = demutate($user_data[nick]);
			$sender_email = $user_data[email];
		}
		else $sender_name = mysslashes($sender_name);
		if(trim($sender_name == "")) $fehler = $lng['formmail']['error']['No_name'];
		elseif(trim($sender_email == "")) $fehler = $lng['formmail']['error']['No_email'];
		else {
			$showformular = 0;
			$betreff = demutate($betreff);
			$message = demutate($message);
			$datum = makesdatum(mydate());

			$search = array('{USERTONAME}','{DATE}','{USERFROMNAME}','{USERFROMEMAIL}','{SUBJECT}','{MESSAGE}','{LOGINLINK}');
			$replace = array(demutate($profile_data['nick']),$datum,$sender_name,$sender_email,$betreff,$message,$config['address_to_forum'].'/index.php?faction=login');
			$email_file = myfread($config['lng_folder'].'/mails/formmail_from_user.dat');
			$email_file = str_replace($search,$replace,$email_file);
			mymail($profile_data[email],$lng['mail_subjects']['formmail_from_user'],$email_file);

			echo navbar("<a class=\"navbar\" href=\"index.php?faction=profile&profile_id=$target_id$MYSID2\">Profil von $profile_data[nick] ansehen</a>\t<a class=\"navbar\" href=\"index.php?faction=formmail&target_id=$target_id$HSID2\">Email schicken</a>\t".$lng['templates']['email_send'][0]);
			echo get_message('email_send','<br>'.sprintf($lng['links']['profile'],"<a class=\"norm\" href=\"index.php?faction=profile&profile_id=$target_id$MYSID2\">",'</a>').'<br>'.sprintf($lng['links']['forum_index'],"<a class=\"norm\" href=\"index.php$MYSID1\">",'</a>'));
		}
	}

	if($showformular == 1) {

		if($user_logged_in != 1) {
			$form_sender_name = "<input type=\"text\" value=\"$sender_name\" name=\"sender_name\">";
			$form_sender_email = "<input type=\"text\" value=\"$sender_email\" name=\"sender_email\">";
		}
		else {
			$form_sender_name = "<span class=\"norm\">$user_data[nick]</span>";
			$form_sender_email = "<span class=\"norm\">$user_data[email]</span>";
		}

		echo navbar("<a class=\"navbar\" href=\"index.php?faction=profile&profile_id=$target_id$MYSID2\">Profil von $profile_data[nick] ansehen</a>\t".$lng['formmail']['Send_email']);
		?>
			<form method="post" action="index.php?faction=formmail&target_id=<?=$target_id?><?=$MYSID2?>"><input type="hidden" name="send" value="yes">
			<table class="tbl" cellpadding="<?=$tpadding?>" cellspacing="<?=$tspacing?>" width="<?=$twidth?>">
			<tr><th class="thnorm" colspan="2"><span class="thnorm"><?=$lng['formmail']['Send_email']?></th></tr>
			<? if($fehler != "") echo "<tr><td class=\"td1\" colspan=\"2\"><span class=\"error\">$fehler</span></td></tr>"; ?>
			<tr>
			 <td class="td1" width="20%"><span class="norm"><b><?=$lng['Receiver']?>:</b></span></td>
			 <td class="td1" width="80%"><span class="norm"><?=$profile_data[nick]?></span></td>
			</tr>
			<tr>
			 <td class="td1" width="20%"><span class="norm"><b><?=$lng['From']?>:</b></span></td>
			 <td class="td1" width="80%"><?=$form_sender_name?></td>
			</tr>
			<tr>
			 <td class="td1" width="20%"><span class="norm"><b><?=$lng['Emailaddress']?>:</b></span></td>
			 <td class="td1" width="80%"><?=$form_sender_email?></td>
			</tr>
			<tr>
			 <td class="td1" width="20%"><span class="norm"><b><?=$lng['Subject']?>:</b></span></td>
			 <td class="td1" width="80%"><input type="text" size="30" name="subject" value="<?=$subject?>"></td>
			</tr>
			<tr>
			 <td class="td1" width="20%" valign="top"><span class="norm"><b><?=$lng['Message']?>:</b></span></td>
			 <td class="td1" width="80%"><textarea name="message" cols="60" rows="8"><?=$message?></textarea></td>
			</tr>
			</table><br><input type="submit" value="<?=$lng['formmail']['Send_email']?>"></center>
		<?
	}
}

?>