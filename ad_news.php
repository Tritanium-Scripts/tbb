<?

/* ad_news.php - zum bestimmen der News (c) 2001-2002 Tritanium Scripts */

require_once("functions.php");
require_once("loadset.php");
require_once("auth.php");
ad();

if($user_logged_in != 1 || $user_data['status'] != 1) {
	mylog("2","%1: Administrationszugriffversuch (IP: %2)");
	header("Location: ad_login.php?$HSID"); exit;
}
else {

	if ($save != "yes") {
		$news_file = myfile("vars/news.var"); $news_config = myexplode($news_file[0]);
		include("pageheader.php");
		echo adnavbar($lng['ad_news']['Edit_News']);
		?>
			<form method="post" action="ad_news.php?save=yes<?=$MYSID2?>">
			<table class="tbl" border=0 cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>" width="<?=$twidth?>">
			<tr><th colspan=2 class="thnorm"><span class="thnorm"><?=$lng['ad_news']['Edit_News']?></span></th></tr>
			<tr><td colspan=2 class="kat"><span class="kat"><?=$lng['Options']?></kat></td></tr>
			<tr>
			 <td class="td1" valign="top"><span class="norm"><b><?=$lng['ad_news']['Newstype']?>:</b></span></td>
			 <td class="td1"><input type="radio" name="typ" value="1" checked> <span class="norm"><?=$lng['ad_news']['static']?></span><br><span class="small"><?=$lng['ad_news']['static_description']?></span><br><input type="radio" name="typ" value="2"> <span class="norm"><?=$lng['ad_news']['fader']?></span><br><span class="small"><?=$lng['ad_news']['fader_description']?></span></td>
			</tr>
			<tr>
			 <td class="td1" valign="top"><span class="norm"><b><?=$lng['ad_news']['Duration']?>:</b></span></td>
			 <td class="td1"><select name=expiredate><option value="-1" selected><?=$lng['time']['always']?></option><option value="60"><?=$lng['time']['1_hour']?></option><option value="120"><?=$lng['time']['2_hours']?></option><option value=300><?=$lng['time']['5_hours']?></option><option value="1440"><?=$lng['time']['1_day']?></option><option value=2880><?=$lng['time']['2_days']?></option><option value=7200><?=$lng['time']['5_days']?></option><option value="14400"><?=$lng['time']['10_days']?></option><option value="43200"><?=$lng['time']['30_days']?></option></td>
			</tr>
			<tr><td class="kat" colspan=2><span class="kat"><?=$lng['ad_news']['Latest_News']?></span></td></tr>
		<?
		if($news_file[0] == "") echo "<tr><td class=\"td1\" colspan=2><span class=\"norm\">".$lng['ad_news']['no_news']."</span></td></tr>";
		elseif($news_config[0] == 1) echo "<tr><td class=\"td1\" colspan=\"2\"><span class=\"norm\">$news_file[1]</span></td></tr>";
		elseif($news_config[0] == 2) {
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
						if(arrayzaehler == arraygroesse) arrayzaehler = 0;
					}
					-->
				</script>
			<?
		}
		?>
			<tr><td class="kat" colspan=2><span class="kat"><?=$lng['ad_news']['Write_News']?></span></td></tr>
			<tr><td class="td1" colspan=2><textarea wrap="off" cols="80" rows="7" name="news"></textarea><br><span class="small">(<?=$lng['ad_news']['info']?>)</span></td></tr>
			</table><br><center><input type="submit" value="<?=$lng['ad_news']['Edit_News']?>"></form></center>
		<?
	}

	else {
		if($news == "") $towrite = "";
		else {
			if($expiredate != -1) $expiredate = time()+60*$expiredate;
			if($typ == 1) {
				$towrite = "$typ\t$expiredate\t\r\n".nlbr(trim(mysslashes($news)))."\r\n";
			}
			elseif($typ == 2) {
				$towrite = "$typ\t$expiredate\t\r\n".trim(str_replace("\"","'",mysslashes($news)));
			}
		}
		mylog("8","%1: News geupdatet (IP: %2)");
		myfwrite("vars/news.var",$towrite,"w"); header("Location: ad_news.php?$HSID"); exit;
	}
}

wio_set("ad");
include("pagetail.php");
// R
?>