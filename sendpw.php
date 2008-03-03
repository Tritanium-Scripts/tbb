<?

/* sendpw.php - Erstellt ein neues PW und schickt es einem per Mail (c) 2001-2002 Tritanium Scipts */

require_once("auth.php");
include("pageheader.php");

$showformular = 1;
$fehler = "";
if($config['activate_mail'] != 1) {
	echo navbar($lng['templates']['function_not_available'][0]);
	echo get_message('function_not_available');
}
else {

	if($send == 1) {
		$nick = mutate($nick);
		if(!$target_id = get_user_id_nick($nick)) $fehler = $lng['sendpw']['error']['Unknown_user'];
		elseif(!$target_data = get_user_data($target_id)) $fehler = $lng['sendpw']['error']['Unknown_user'];
		else {
			$showformular = 0; $new_pw = get_rand_string(6);
			change_user_db($target_id,2,mycrypt($new_pw));

			$search = array('{USERNAME}','{USERPW}','{LOGINLINK}');
			$replace = array(demutate($target_data['nick']),$new_pw,$config['address_to_forum'].'/index.php?faction=login');
			$email_file = myfread($config['lng_folder'].'/mails/new_password_requested.dat');
			$email_file = str_replace($search,$replace,$email_file);
			mymail($target_data['email'],$lng['mail_subjects']['new_password_requested'],$email_file);

			mylog("12","Neues Passwort an UserID $target_id geschickt (IP: %2)");
			echo navbar("<a class=\"navbar\" href=\"index.php?faction=sendpw$MYSID2\">".$lng['sendpw']['Password_forgotten']."</a>\t".$lng['templates']['new_password_generated'][0]);
			echo get_message('new_password_generated');
		}
	}

	if($showformular == 1) {
		echo navbar($lng['sendpw']['Password_forgotten']);
		if(isset($send_name)) $nick = mutate(urldecode($send_name));
		?>
			<form method="post" action="index.php?faction=sendpw<?=$MYSID2?>"><input type="hidden" name="send" value="1">
			<table class="tbl" width="<?=$twidth?>" border="0" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
			<tr><th class="thnorm"><span class="thnorm"><?=$lng['sendpw']['Password_forgotten']?></span></th></tr>
			<tr><td class="td1"><span class="norm"><b><?=$lng['Nick']?>:</b></span> <input type="text" name="nick" value="<?=$nick?>"> <span class="error"><?=$fehler?></span><hr><span class="norm"><?=$lng['sendpw']['information']?></span></td></tr>
			</table><br><input type="submit" value="<?=$lng['sendpw']['Generate_password']?>"></form></center>
		<?
	}
}


?>