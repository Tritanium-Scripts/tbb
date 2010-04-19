<script type="text/javascript">
<!--
	tagsarray = new Array();
	
	tagsarray['bold'] = 0;
	tagsarray['italic'] = 0;
	tagsarray['quote'] = 0;
	tagsarray['url'] = 0;
	tagsarray['img'] = 0;
	tagsarray['underline'] = 0;
	tagsarray['strike'] = 0;
	tagsarray['color'] = 0;
	tagsarray['code'] = 0;
	tagsarray['center'] = 0;
	tagsarray['email'] = 0;

	function bbcodetags (opentag,closetag,type) {
		document.forms['tbb_form'].elements['p_post'].focus();
		if(window.getSelection && window.getSelection() != '') {
			window.getSelection() = opentag + window.getSelection() + closetag;
			
		}
		else if(document.getSelection && document.getSelection() != '') {
			document.getSelection() = opentag + document.getSelection() + closetag;
			
		}
		else if(document.selection && document.selection.createRange().text != '') {
			document.selection.createRange().text = opentag + document.selection.createRange().text + closetag;
		}
		else {
			
			if(tagsarray[type] == 0) {
				tagsarray[type] = 1;
				insertatcaret(opentag);
			}
			else {
				tagsarray[type] = 0;
				insertatcaret(closetag);
			}
		}
	}
//-->
</script>
<input class="form_bbcode_button" style="font-weight:bold" type="button" value="B" onclick="bbcodetags('[b]','[/b]','bold');" />
<input class="form_bbcode_button" style="font-style:italic" type="button" value="I" onclick="bbcodetags('[i]','[/i]','italic');" />
<input class="form_bbcode_button" type="button" value="{$lng['Quote']}" onclick="bbcodetags('[quote]','[/quote]','quote');" />
<input class="form_bbcode_button" type="button" value="URL" onclick="bbcodetags('[url]','[/url]','url');" />
<input class="form_bbcode_button" type="button" value="IMG" onclick="bbcodetags('[img]','[/img]','img');" />
<input class="form_bbcode_button" style="text-decoration:underline;" type="button" value="U" onclick="bbcodetags('[u]','[/u]','underline');" /><br />
<input class="form_bbcode_button" style="text-decoration:line-through;" type="button" value="S" onclick="bbcodetags('[s]','[/s]','strike');" />
<input class="form_bbcode_button" type="button" value="Code" onclick="bbcodetags('[code]','[/code]','code');" />
<input class="form_bbcode_button" type="button" value="Center" onclick="bbcodetags('[center]','[/center]','center');" />
<input class="form_bbcode_button" type="button" value="Email" onclick="bbcodetags('[email]','[/email]','email');" />