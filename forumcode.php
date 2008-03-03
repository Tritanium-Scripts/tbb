<?

/* forumcode.php - Zeigt Forumcodebuttons an (c) 2001-2002 Tritanium Scripts */

?>

<script type="text/javascript">
<!--
	function addforumcode1(frage,vorgabe,opentag,endtag) {
		text = prompt(frage,vorgabe);
		if(text != null) document.beitrag.post.value += opentag+text+endtag;
		document.beitrag.post.focus();
	}

	function addforumcode2(frage1,frage2,vorgabe,opentag1,opentag2,endtag) {
		text1 = prompt(frage1,vorgabe);
		if(text1 != null) {
			text2 = prompt(frage2,text1);
			if(text2 != null) document.beitrag.post.value += opentag1+text1+opentag2+text2+endtag;
		}
		document.beitrag.post.focus();
	}

	function addcolor(color) {
		text = prompt("Bitte geben sie den Text ein, den sie in dieser Farbe darstellen wollen","");
		if(text != null) {
			document.beitrag.post.value += "[color="+color+"]"+text+"[/color]";
		}
		document.beitrag.post.focus();
	}
-->
</script>

<button class="forumcode" onclick="addforumcode2('Bitte geben sie die URL an','Bitte geben sie den Text ein, der den Link darstellen soll','http://','[url=',']','[/url]')">URL</button>&nbsp;
<button class="forumcode" onclick="addforumcode1('Bitte geben sie die URL oder den Pfad des Bildes an','http://','[img]','[/img]')">IMG</button>&nbsp;
<button class="forumcode" onclick="addforumcode1('Bitte geben sie den Text ein, den sie kursiv darstellen möchten','','[i]','[/i]')"><i>I</i></button>&nbsp;
<button class="forumcode" onclick="addforumcode1('Bitte geben sie den Text ein, den sie fett darstellen möchten','','[b]','[/b]')"><b>B</b></button>&nbsp;
<button class="forumcode" onclick="addforumcode1('Bitte geben sie den Text ein, den sie unterstrichen darstellen möchten','','[u]','[/u]')"><u>U</u></button>&nbsp;
<button class="forumcode" onclick="addforumcode1('Bitte geben sie den Text ein, den sie durchgestrichen darstellen möchten','','[s]','[/s]')"><s>S</s></button>&nbsp;
<select name="colorselect" onchange="addcolor(document.beitrag.colorselect.options[document.beitrag.colorselect.options.selectedIndex].value);" class="forumcode" style="width:100px">
 <option value="#000000" style="background-color:#000000"></option>
 <option value="#808080" style="background-color:#808080"></option>
 <option value="#800000" style="background-color:#800000"></option>
 <option value="#FF0000" style="background-color:#FF0000"></option>
 <option value="#008000" style="background-color:#008000"></option>
 <option value="#00FF00" style="background-color:#00FF00"></option>
 <option value="#808000" style="background-color:#808000"></option>
 <option value="#FFFF00" style="background-color:#FFFF00"></option>
 <option value="#000080" style="background-color:#000080"></option>
 <option value="#0000FF" style="background-color:#0000FF"></option>
 <option value="#800080" style="background-color:#800080"></option>
 <option value="#FF00FF" style="background-color:#FF00FF"></option>
 <option value="#008080" style="background-color:#008080"></option>
 <option value="#00FFFF" style="background-color:#00FFFF"></option>
 <option value="#C0C0C0" style="background-color:#C0C0C0"></option>
 <option value="#FFFFFF" style="background-color:#FFFFFF"></option>
</select><br>
<button class="forumcode" onclick="addforumcode1('Bitte geben sie den Text ein, den sie als Code darstellen möchten','','[code]','[/code]')">CODE</button>&nbsp;
<button class="forumcode" onclick="addforumcode1('Bitte geben sie den Text ein, den sie zentriert darstellen möchten','','[center]','[/center]')">CENTER</button>&nbsp;
<button class="forumcode" onclick="addforumcode1('Bitte geben sie den Text ein, den sie als Laufschrift darstellen möchten','','[marquee]','[/marquee]')">MARQUEE</button>&nbsp;
<button class="forumcode" onclick="addforumcode1('Bitte geben sie die Emailadresse ein','','[email]','[/email]')">EM@IL</button>
