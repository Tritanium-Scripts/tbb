<?

/* register.php - zum erstellen eines neuen Member (c) 2001-2002 Tritanium Scripts */

require_once("auth.php");

$fehler = '';
$showformular = 1;
$member_counter = myfile('vars/member_counter.var'); $member_counter = $member_counter[0];

if($config['activate_registration'] != 1) {
	include('pageheader.php');
	echo navbar($lng['templates']['registration_deactivated'][0]);
	echo get_message('registration_deactivated');
}
elseif($member_counter >= $config['max_registrations'] && $config['max_registrations'] != -1) {
	include('pageheader.php');
	echo navbar($lng['templates']['no_more_registrations'][0]);
	echo get_message('no_more_registrations');
}
else {

if($mode == "createuser") {


	isset($_POST['newuser_name']) ? $nu_name = nlbr($_POST['newuser_name']) : $nu_name = '';
	isset($_POST['newuser_pw1']) ? $nu_pw1 = nlbr($_POST['newuser_pw1']) : $nu_pw1 = '';
	isset($_POST['newuser_pw2']) ? $nu_pw2 = nlbr($_POST['newuser_pw2']) : $nu_pw2 = '';
	isset($_POST['newuser_email']) ? $nu_email = nlbr($_POST['newuser_email']) : $nu_email = '';
	isset($_POST['newuser_hp']) ? $nu_hp = nlbr($_POST['newuser_hp']) : $nu_hp = '';
	isset($_POST['newuser_realname']) ? $nu_realname = nlbr($_POST['newuser_realname']) : $nu_realname = '';
	isset($_POST['newuser_icq']) ? $nu_icq = nlbr($_POST['newuser_icq']) : $nu_icq = '';
	isset($_POST['newuser_signatur']) ? $nu_signatur = nlbr(trim(mutate($_POST['newuser_signatur']))) : $nu_signatur = '';

	$nu_pw1 = mysslashes($nu_pw2);
	$nu_pw2 = mysslashes($nu_pw2);

	$nu_mailname = mysslashes($nu_name);

	$nu_name = mutate(str_replace(' ','_',$nu_name));
	if(trim($nu_name) == "") $fehler = $lng['register']['error']['No_nick']; // Überprüfen, ob ein Username eingegeben wurde
	if(strlen($nu_name) > 15) $fehler = 'Der Nick ist zu lange!';
	elseif(trim($nu_pw1) == "" && $config['create_reg_pw'] != 1) $fehler = $lng['register']['error']['No_password']; // Überprüfen, ob ein Passwort eingegben wurde bzw. ob keins eingegeben werden muss
	elseif(trim($nu_email) == "") $fehler = $lng['register']['error']['No_emailaddress']; // Überprüfen, ob eine eMail-Adresse eingegeben wurde
	elseif(!verify_email($nu_email)) $fehler = 'Bitte geben Sie eine gültige Emailadresse ein!';
	elseif($nu_pw1 != $nu_pw2 && $config['create_reg_pw'] != 1) $fehler = $lng['register']['error']['Passwords_do_not_match']; // gegebenenfalls überprüfen, ob die Passwörter übereinstimmen
	elseif(check_name($nu_name,-1) == 1) $fehler = $lng['register']['error']['Nick_already_exists']; // Überprüfen, ob der Username schon exisitert
	elseif($regeln != "yes") $fehler = $lng['register']['error']['Boardrules_not_accepted']; // Überprüfen, ob die Regeln akzeptiert wurden
	elseif($nu_icq != "" && verify_icq_uin($nu_icq) == FALSE) $fehler = 'Bitte geben Sie eine gültige ICQ Nummer ein!';
	else {

		$showformular = 0;
		$reg_datum = date("Ym"); // Datum herausfinden
		$memberzahl = myfile("vars/last_user_id.var"); $nu_id = $memberzahl[0]+1; // Neue ID herausfinden

		if($nu_id == 1) $nu_status = 1; // Falls das die erste Registrierung ist, User als Admin setzten
		else $nu_status = 3;

		if($config['create_reg_pw'] != 1) { // Die verschiedenen Nachrichten, falls das PW automatisch erstellt wird
			$nu_pw = $nu_pw1;
			$message = sprintf($lng['register']['Registration_successful_text'][0],$nu_name);
		}
		else {
			$nu_pw = get_rand_string(6);
			$message = sprintf($lng['register']['Registration_successful_text'][1],$nu_name);
		}

		$nu_pwc = mycrypt($nu_pw); // Passwort verschlüsseln
		$member_counter = myfile("vars/member_counter.var"); $member_counter = $member_counter[0]+1;

		$towrite = "$nu_name\n$nu_id\n$nu_pwc\n$nu_email\n$nu_status\n0\n$reg_datum\n$nu_signatur\n\n$nu_hp\n\n0\n$nu_realname\n$nu_icq\n1,1\n\n\n"; // Das zu Schreibende vorbereiten
		myfwrite("members/$nu_id.xbb",$towrite,"w"); myfwrite("members/$nu_id.pm","","w"); myfwrite("vars/last_user_id.var",$nu_id,"w"); myfwrite("vars/member_counter.var",$member_counter,'w'); // Daten schreiben

		// Registration dem neuen User zuschicken
			$search = array('{USERNAME}','{FORUMNAME}','{USERPW}','{FORUMLINK}');
			$replace = array($nu_mailname,$config['forum_name'],$nu_pw,$config['address_to_forum'].'/index.php');
			$email_file = myfread($config['lng_folder'].'/mails/registration.dat');
			$email_file = str_replace($search,$replace,$email_file);
			mymail($nu_email,sprintf($lng['mail_subjects']['registration'],$config['forum_name']),$email_file); // Registrierung per Mail verschicken

		mylog("11","Neue Registrierung: $nu_name (ID: $nu_id) (IP: %2)");

		if($config['mail_admin_new_registration'] == 1) { // Admin gegebenenfalls benachrichtigen
			$search = array('{USERNAME}','{FORUMNAME}','{USERID}');
			$replace = array($nu_mailname,$config['forum_name'],$nu_id);
			$email_file = myfread($config['lng_folder'].'/mails/admin_new_registration.dat');
			$email_file = str_replace($search,$replace,$email_file);
			mymail($config['admin_email'],sprintf($lng['mail_subjects']['admin_new_registration'],$nu_mailname),$email_file);
		}

		include("pageheader.php");
		echo navbar($lng['register']['Registration_successful']);
		?>
			<table class="tbl" width="<?=$twidth?>" border="0" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
			<tr><th class="thnorm"><span class="thnorm"><?=$lng['register']['Registration_successful']?></span></td></tr>
			<tr><td class="td1"><span class="norm"><center><br><?=$message?><br><br></center></span></td></tr>
			</table></center>
		<?
	}
}

if($showformular == 1) {
	include("pageheader.php");
	echo navbar($lng['register']['Register']);
	?>
		<form action="index.php?faction=register<?=$MYSID2?>" method="post"><input type="hidden" name="mode" value="createuser">
		<table class="tbl" border="0" width="<?=$twidth?>" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
		 <tr><th class="thnorm" colspan="2"><span class="thnorm"><?=$lng['register']['Register']?></span></th></tr>
		 <tr><td class="td1" colspan="2"><span class="small"><?=$lng['register']['Signed_fields_must_be_filled_in']?></span></td></tr>
		 <? if ($fehler != "") echo "<tr><td class=\"td1\" colspan=\"2\"><span class=\"error\">$fehler</span></td></tr>"; ?>
		 <tr>
		  <td class="td1" width=30%><span class="norm"><b><?=$lng['Nick']?>:*</b></span></td>
		  <td class="td1" width=70%><input maxlength="15" type="text" name="newuser_name" value="<?=$newuser_name?>"> <span class="small">(maximal 15 Zeichen)</span></td>
		 </tr>
	<?
	if($config['create_reg_pw'] != 1) {
		?>
			<tr>
			 <td class="td1" width=30%><span class="norm"><b><?=$lng['Password']?>:*</b></span></td>
			 <td class="td1" width=70%><input type="password" name="newuser_pw1"></td>
			</tr>
			<tr>
			 <td class="td1" width=30%><span class="norm"><b><?=$lng['Confirm_Password']?>:*</b></span></td>
			 <td class="td1" width=70%><input type="password" name="newuser_pw2"></td>
			</tr>
		 <?
	}
	?>
		<tr>
		  <td class="td1" width=30%><span class="norm"><b><?=$lng['Emailaddress']?>:*</b></span></td>
		  <td class="td1" width=70%><input type="text" name="newuser_email" value="<?=$newuser_email?>"></td>
		 </tr>
		 <tr>
		  <td class="td1" width=30%><span class="norm"><b><?=$lng['Homepage']?>:</b></span></td>
		  <td class="td1" width=70%><input type="text" name="newuser_hp" value="<?=$newuser_hp?>"></td>
		 </tr>
		 <tr>
		  <td class="td1" width=30%><span class="norm"><b><?=$lng['Real_name']?>:</b></span></td>
		  <td class="td1" width=70%><input type="text" name="newuser_realname" value="<?=$newuser_realname?>"></td>
		 </tr>
		 <tr>
		  <td class="td1" width=30%><span class="norm"><b><?=$lng['ICQ']?>:</b></span></td>
		  <td class="td1" width=70%><input type=text name="newuser_icq" value="<?=$newuser_icq?>"></td>
		 </tr>
		 <tr>
		  <td class="td1" width=30% valign=top><span class="norm"><b><?=$lng['Signature']?>:</b></span><br><span class="small"><?=$lng['register']['signature_description']?></span></td>
		  <td class="td1" width=70%><font face=verdana size=2><textarea cols="25" rows="6" name="newuser_signatur"><?=$signatur?></textarea></font></td>
		 </tr>
		 <tr>
		  <td class="td1" colspan="2"><span class="norm"><input type="checkbox" name="regeln" value="yes"> <?=$lng['register']['I_accept_the_boardrules']?>*</span></td>
		 </tr>
		</table><br><input type="submit" value="<?=$lng['register']['Register']?>" onfocus="this.blur()"></form></center>
	<?
}


}
wio_set("register"); // WIO-Status setzen

?>