<script type="text/javascript">
	LanguageDelete = EntitiesToUnicode('{$Modules.Language->getString('delete')}');
	RowsCounter = {$PollOptionsCounter};
	LastRowID = {$PollOptionsCounter};

	{literal}
	function addPollOption() {
		LastRowID++

		var newtr = document.getElementById('idPollOptionsTable').insertRow(RowsCounter);
		newtr.id = 'idOption'+LastRowID;

		var newtd = document.createElement('td');
		//newtd.className = 'CellMain';
		var newinput = document.createElement('input');
		newinput.type = 'text';
		newinput.className = 'FormText';
		newinput.size = '30';
		newinput.name = "p[PollOptions][]";
		//newinput.id = "idPollOptions"+LastRowID;
		newtd.appendChild(newinput);
		newtr.appendChild(newtd);

		// Links (loeschen);
		var newtd = document.createElement('td');
		//newtd.className = 'cellmain';
		newtd.align = 'left';
		var newspan = document.createElement('span');
		newspan.className = 'FontSmall';
		newspan.appendChild(getAElement(LanguageDelete,'javascript:deletePollOption(\'idOption'+LastRowID+'\')'));
		newtd.appendChild(newspan);
		newtr.appendChild(newtd);

		RowsCounter++;
	}

	function getTextElement(Text) {
		return document.createTextNode(Text);
	}

	function getAElement(Text,URL) {
		var newa = document.createElement('a');
		newa.href = URL;
		newa.appendChild(getTextElement(Text));
		return newa;
	}

	function deletePollOption(RowID) {
		document.getElementById('idPollOptionsTable').deleteRow(document.getElementById(RowID).rowIndex);
		RowsCounter--;
	}
	{/literal}
</script>
{if $Show.PreviewBox}
 <table class="TableStd" border="0" cellspacing="0" cellpadding="3" width="100%">
 <tr><td class="CellTitle"><span class="FontTitle">{$Modules.Language->getString('Preview')}</span></td></tr>
 <tr><td class="CellStd"><span class="FontNorm">{$preview_post}</span></td></tr>
 </table>
 <br/>
{/if}
<form method="post" action="{$IndexFile}?Action=Posting&amp;Mode={$Mode}&amp;ForumID={$ForumID}&amp;TopicID={$TopicID}&amp;PostID={$PostID}&amp;Doit=1&amp;{$MySID}" name="MyForm">
<table class="TableStd" border="0" cellspacing="0" cellpadding="3" width="100%">
<colgroup>
 <col width="20%"/>
 <col width="80%"/>
</colgroup>
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$ActionText}</span></td></tr>
<tr><td class="CellCat" colspan="2"><span class="FontCat">{$Modules.Language->getString('Post')}</span></td></tr>
{if $Error != ''}<tr><td class="CellError" colspan="2"><span class="FontError">{$Error}</span></td></tr>{/if}
{if $Modules.Auth->isLoggedIn() != 1}
 <tr>
  <td class="CellStd"><span class="FontNorm">{$Modules.Language->getString('Your_name')}:</span><br/><span class="FontSmall">{$Modules.Language->getString('nick_conventions')}</span></td>
  <td class="CellAlt" valign="top"><input size="20" class="FormText" type="text" name="p[GuestNick]" value="{$p.GuestNick}" maxlength="15"/></td>
 </tr>
{/if}
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$Modules.Language->getString('Post_pic')}:</span></td>
 <td class="CellAlt" valign="top">{$PPicsBox}</td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$Modules.Language->getString('Title')}:</span></td>
 <td class="CellAlt"><input class="FormText" type="text" size="65" name="p[MessageTitle]" value="{$p.MessageTitle}" maxlength="100"/>&nbsp;<span class="FontSmall">({$title_max_chars})</span></td>
</tr>
 <tr>
  <td class="CellStd" valign="top"></td>
  <td class="CellAlt">{$bbcode_box}</td>
 </tr>
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$Modules.Language->getString('Post')}:</span><br/><br/>{$SmiliesBox}</td>
 <td class="CellAlt"><textarea class="FormTextArea" name="p[MessageText]" rows="14" cols="80" onselect="storecaret();" onclick="storecaret();" onkeyup="storecaret();">{$p.MessageText}</textarea></td>
</tr>
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$Modules.Language->getString('Options')}:</span></td>
 <td class="CellAlt"><span class="FontNorm">
  {if $Show.EnableSmilies}<input type="checkbox" name="c[EnableSmilies]" value="1"{if $c.EnableSmilies == 1} checked="checked"{/if} id="idEnableSmilies"/><label for="idEnableSmilies"> {$Modules.Language->getString('Enable_smilies')}</label><br/>{/if}
  {if $Show.ShowSignature}<input type="checkbox" name="c[ShowSignature]" value="1"{if $c.ShowSignature == 1} checked="checked"{/if} id="idShowSignature"/><label for="idShowSignature"> {$Modules.Language->getString('Show_signature')}</label><br/>{/if}
  {if $Show.EnableBBCode}<input type="checkbox" name="c[EnableBBCode]" value="1"{if $c.EnableBBCode} == 1} checked="checked"{/if} id="idEnableBBCode"/><label for="idEnableBBCode"> {$Modules.Language->getString('Enable_bbcode')}</label><br/>{/if}
  {if $Show.EnableHtmlCode}<input type="checkbox" name="c[EnableHtmlCode]" value="1"{if $c.EnableHtmlCode == 1} checked="checked"{/if} id="idEnableHtmlCode"/><label for="idEnableHtmlCode"> {$Modules.Language->getString('Enable_html_code')}</label><br/>{/if}
  {if $Show.EnableURITransformation}<input type="checkbox" name="c[EnableURITransformation]" value="1"{if $c.EnableURITransformation == 1} checked="checked"{/if} id="idEnableURITransformation"/><label for="idEnableURITransformation">&nbsp;{$Modules.Language->getString('Enable_url_transformation')}</label><br/>{/if}
  {if $Show.ShowEditings}<input type="checkbox" name="c[ShowEditings]" value="1"{if $c.ShowEditings == 1} checked="checked"{/if} id="idShowEditings"/><label for="idShowEditings"> {$Modules.Language->getString('Show_post_editings')}</label><br/>{/if}
  {if $Show.SubscribeTopic}<input type="checkbox" name="c[SubscribeTopic]" value="1"{if $c.SubscribeTopic == 1} checked="checked"{/if} id="idSubscribeTopic"/><label for="idSubscribeTopic"> {$Modules.Language->getString('Subscribe_topic')}</label><br/>{/if}
  {if $Show.PicTopic}<input type="checkbox" name="c[PinTopic]" value="1"{if $c.PinTopic == 1} checked="checked"{/if} id="idPinTopic"/><label for="idPinTopic"> {$Modules.Language->getString('Mark_topic_important')}</label><br/>{/if}
  {if $Show.CloseTopic}<input type="checkbox" name="c[CloseTopic]" value="1"{if $c.CloseTopic == 1} checked="checked"{/if} id="idCloseTopic"/><label for="idCloseTopic"> {$Modules.Language->getString('Close_topic')}</label>{/if}
 </span></td>
</tr>
{if $Show.PollBox}
 <tr><td class="CellCat" colspan="2"><a name="pollrow"></a><span class="FontCat">{$Modules.Language->getString('Poll')}</span></td></tr>
 <tr>
  <td class="CellStd" valign="top"><span class="FontNorm">{$Modules.Language->getString('Poll_title')}:</span><br/><span class="FontSmall">{$Modules.Language->getString('add_poll_info')}</span></td>
  <td class="CellAlt" valign="top"><input class="FormText" type="text" name="p_poll_title" maxlength="255" size="60" value="{$p_poll_title}"/></td>
 </tr>
 <tr>
  <td class="CellStd" valign="top"><span class="FontNorm">{$Modules.Language->getString('Poll_options')}:</span></td>
  <td class="CellAlt" valign="top">
   <table id="idPollOptionsTable" border="0" cellpadding="1" cellspacing="0">
   {foreach from=$p.PollOptions item=curOption}
    <tr id="idOption{$poll_options_counter}">
     <td><input type="text" class="FormText" size="30" value="{$curOption}" name="p[PollOptions][]""/></td>
     <td><span class="FontSmall"><a href="javascript:deletePollOption('idOption{$poll_options_counter}');">l&ouml;schen</a></span></td>
    </tr>
   {/foreach}
   <tr><td><span class="FontSmall"><a href="javascript:addPollOption();">{$Modules.Language->getString('Add_poll_option')}</a></span></td></tr>
  </table>
  </td>
 </tr>
{/if}
<tr><td class="CellButtons" colspan="2" align="center"><input class="FormButton" type="submit" value="{$ActionText}"/>&nbsp;&nbsp;&nbsp;<input class="FormBButton" type="submit" name="ShowPreview" value="{$Modules.Language->getString('Preview')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$Modules.Language->getString('Reset')}"/></td></tr>
</table></form>
<script type="text/javascript">
	if(RowsCounter == 0) addPollOption();
</script>