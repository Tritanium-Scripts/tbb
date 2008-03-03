<?

/* regeln.php - Zeigt die Regeln des Boards an (c) 2001-2002 Tritanium Scripts */

require_once("auth.php");
require_once($config['lng_folder']."/lng_rules.php");

echo navbar($lng['Boardrules']);

?>
	<table class="tbl" border="0" width="<?=$twidth?>" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
	<tr><th class="thnorm"><span class="thnorm"><?=$lng['Boardrules']?></span></th></tr>
	<tr><td class="td1"><span class="norm"><?=$lng['boardrules_text']?></span></td></tr>
	</table></center>
<?

wio_set("regeln");

?>