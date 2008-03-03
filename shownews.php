<?

/* shownews.php - zeigt die News an (c) 2001-2002 Tritanium Scripts */

require_once("auth.php");

$news_file = myfile("vars/news.var"); $news_config = myexplode($news_file[0]); // News laden

if((time() < $news_config[1] || $news_config[1] == -1) && $news_file[0] != "") { // Nur News anzeigen, wenn sie noch nicht abgelaufen sind, bzw, wenn überhaupt welche exisitieren
	echo "<tr><td class=\"kat\" colspan=\"6\"><span class=\"kat\">".$lng['shownews']['News']."</span></td></tr>"; // Newsbalken anzeigen
	if($news_config[0] == 1) echo "<tr><td class=\"td1\" colspan=\"6\"><span class=\"small\">$news_file[1]</span></td></tr>"; // Newstyp 1 anzeigen
	elseif($news_config[0] == 2) { // Newstyp 2 ("anzeigen")
		$array_text = array();
		for($i = 1; $i < sizeof($news_file); $i++) {
			$array_text[] = killnl($news_file[$i]);
		}
		$array_text = '"'.implode('","',$array_text).'"';;
		?>
			<tr><td class="td1" colspan="6" align="center"><div class="news" style="width:100%; height:30px;visibility:visible;filter:blendTrans(duration=1);" id="newsfader">&nbsp;</div></td></tr>

			<script language='JScript' type='text/jscript'>
				<!--
				var arrayzaehler,arraygroesse;

				newsarray = new Array(<?=$array_text?>);

				arraygroesse = newsarray.length;
				arrayzaehler = 0;

				document.all.newsfader.innerHTML = "";

				setInterval("fade()",5000);

				function fade() {
					document.all.newsfader.filters.blendTrans.Apply();
					document.all.newsfader.innerHTML = "<span style=vertical-align:middle>"+newsarray[arrayzaehler]+"</span>";
					document.all.newsfader.filters.blendTrans.Play();
					arrayzaehler++;
					if(arrayzaehler >= arraygroesse) arrayzaehler = 0;
				}
				-->
			</script>
		<?
	}
}

?>