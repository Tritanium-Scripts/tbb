<script type="text/javascript">
<!--
	function insertatcaret(text) {
		if(opener.document.forms['tbb_form'].elements['p_post'].createTextRange && opener.document.forms['tbb_form'].elements['p_post'].caretPos) {
			var caretPos = opener.document.forms['tbb_form'].elements['p_post'].caretPos;
			caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
		}
		else opener.document.forms['tbb_form'].elements['p_post'].value += text;
	}
//-->
</script>
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm"><span class="thnorm">{$lng['Smilies']}</span></th></tr>
<tr><td class="td1" align="center"><table border="0" cellpadding="3" cellspacing="0">
<template:smileyrow>
 <tr>
 <template:smileyrow.smileycol>
  <td valign="bottom"><a href="javascript:insertatcaret(' {smileyrow.smileycol.$akt_smiley['smiley_synonym']} ')"><img src="{smileyrow.smileycol.$akt_smiley['smiley_gfx']}" alt="{smileyrow.smileycol.$akt_smiley['smiley_synonym']}" border="0" /></a></td>
 </template:smileyrow.smileycol>
 </tr>
</template:smileyrow>
</table><span class="small"><a href="javascript:opener.document.forms['tbb_form'].elements['p_post'].focus();window.close();">{$lng['Close_window']}</a></span></td></tr>

</table>