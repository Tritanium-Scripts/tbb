<?

/* ad_emaillist.php - Erstellt eine Liste bestimmter Emailadressen (c) 2001-2002 Tritanium Scripts */

require_once("functions.php");
require_once("loadset.php");
require_once("auth.php");
ad();

if($user_logged_in != 1 || $user_data[status] != 1) {
	mylog("2","%1: Administrationszugriffversuch (IP: %2)");
	header("Location: ad_login.php?$HSID"); exit;
}
else {
	$members = myfile("vars/last_user_id.var"); $members = $members[0]+1;
	include("pageheader.php");
	echo adnavbar("Emailliste");
	?>
		<table class="tbl" cellpadding="<?=$tpadding?>" cellspacing="<?=$tspacing?>" width="<?=$twidth?>">
		<tr><th class="thnorm"><span class="thnorm"><?=$lng['ad_emailist']['Mailing_List']?></span></th></tr>
		<tr><td class="td1"><span class="norm"><?=$lng['ad_emailist']['not_allowed_to_distribute']?></b><br></span>
		<textarea readonly cols="40" rows="20" wrap="off"><?
		for($i = 1; $i < $members; $i++) {
			if($akt_member = myfile("members/$i.xbb")) {
				$akt_moptions = explode(",",killnl($akt_member[14]));
				if($akt_moptions[0] == 1) echo $akt_member[3];
			}
		}
	echo "</textarea></td></tr></table></center>";
	mylog("8","%1: Emailliste abgerufen (IP: %2)");
	include("pagetail.php");
// L
}