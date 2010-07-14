<?

/* spmbox.php - Statusanzeige der PM-Box (c) 2001-2002 Tritanium Scripts */

require_once("auth.php");

if($user_logged_in != 1) die('Function not available');
else {
	$ungelesen = 0; // Zähler wird erst mal auf 0 gesetzt
	$user_pms = myfile("members/$user_id.pm"); $user_pms_anzahl = sizeof($user_pms);
	for ($i = 0; $i < $user_pms_anzahl; $i++) {
		$aktuelle_pm = myexplode($user_pms[$i]);
		if($aktuelle_pm[7] == 1) $ungelesen++; // Falls die PM ungelesen ist, Zähler um 1 erhöhen
	}
	if($ungelesen == 0) $ungelesen = 'keine ungelesenen';
	else $ungelesen = "<a class=\"small\" href=\"index.php?faction=pm$MYSID2\"><b>$ungelesen ungelesene</b></a>";
	?>
		<center><table class="tbl" border="0" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>" width="<?=$twidth?>">
		<tr><td class="td1"><span class="small"><?=sprintf($lng['spmbox']['text'],$ungelesen,"<a class=\"small\" href=\"index.php?faction=pm$MYSID2\">",'</a>')?></span></td></tr>
		</table></center>
	<?

}

?>