<script type="text/javascript">
	LanguageDelete = EntitiesToUnicode('{$modules.Language->getString('delete')}');
	RowsCounter = {$pollOptionsCounter};
	LastRowID = {$pollOptionsCounter};

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
{if $show.PreviewBox}
 <table class="TableStd" border="0" cellspacing="0" cellpadding="3" width="100%">
 <tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('Preview')}</span></td></tr>
 <tr><td class="CellStd"><span class="FontNorm">{$preview_post}</span></td></tr>
 </table>
 <br/>
{/if}
<form method="post" action="{$indexFile}?action=Posting&amp;mode={$mode}&amp;ForumID={$forumID}&amp;TopicID={$topicID}&amp;PostID={$postID}&amp;Doit=1&amp;{$mySID}" name="MyForm">
<table class="TableStd" border="0" cellspacing="0" cellpadding="3" width="100%">
<colgroup>
 <col width="20%"/>
 <col width="80%"/>
</colgroup>
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$actionText}</span></td></tr>
<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('Post')}</span></td></tr>
{if $error != ''}<tr><td class="CellError" colspan="2"><span class="FontError">{$error}</span></td></tr>{/if}
{if $modules.Auth->isLoggedIn() != 1}
 <tr>
  <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Your_name')}:</span><br/><span class="FontSmall">{$modules.Language->getString('nick_conventions')}</span></td>
  <td class="CellAlt" valign="top"><input size="20" class="FormText" type="text" name="p[GuestNick]" value="{$p.GuestNick}" maxlength="15"/></td>
 </tr>
{/if}
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('Post_pic')}:</span></td>
 <td class="CellAlt" valign="top">{$pPicsBox}</td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Title')}:</span></td>
 <td class="CellAlt"><input class="FormText" type="text" size="65" name="p[MessageTitle]" value="{$p.MessageTitle}" maxlength="100"/>&nbsp;<span class="FontSmall">({$title_max_chars})</span></td>
</tr>
 <tr>
  <td class="CellStd" valign="top"></td>
  <td class="CellAlt">{$bbcode_box}</td>
 </tr>
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('Post')}:</span><br/><br/>{$smiliesBox}</td>
 <td class="CellAlt"><textarea class="FormTextArea" name="p[MessageText]" rows="14" cols="80" onselect="storecaret();" onclick="storecaret();" onkeyup="storecaret();">{$p.MessageText}</textarea></td>
</tr>
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('Options')}:</span></td>
 <td class="CellAlt"><span class="FontNorm">
  {if $show.EnableSmilies}<input type="checkbox" name="c[EnableSmilies]" value="1"{if $c.EnableSmilies == 1} checked="checked"{/if} id="idEnableSmilies"/><label for="idEnableSmilies"> {$modules.Language->getString('Enable_smilies')}</label><br/>{/if}
  {if $show.ShowSignature}<input type="checkbox" name="c[ShowSignature]" value="1"{if $c.ShowSignature == 1} checked="checked"{/if} id="idShowSignature"/><label for="idShowSignature"> {$modules.Language->getString('Show_signature')}</label><br/>{/if}
  {if $show.EnableBBCode}<input type="checkbox" name="c[EnableBBCode]" value="1"{if $c.EnableBBCode} == 1} checked="checked"{/if} id="idEnableBBCode"/><label for="idEnableBBCode"> {$modules.Language->getString('Enable_bbcode')}</label><br/>{/if}
  {if $show.EnableHtmlCode}<input type="checkbox" name="c[EnableHtmlCode]" value="1"{if $c.EnableHtmlCode == 1} checked="checked"{/if} id="idEnableHtmlCode"/><label for="idEnableHtmlCode"> {$modules.Language->getString('Enable_html_code')}</label><br/>{/if}
  {if $show.EnableURITransformation}<input type="checkbox" name="c[EnableURITransformation]" value="1"{if $c.EnableURITransformation == 1} checked="checked"{/if} id="idEnableURITransformation"/><label for="idEnableURITransformation">&nbsp;{$modules.Language->getString('Enable_url_transformation')}</label><br/>{/if}
  {if $show.ShowEditings}<input type="checkbox" name="c[ShowEditings]" value="1"{if $c.ShowEditings == 1} checked="checked"{/if} id="idShowEditings"/><label for="idShowEditings"> {$modules.Language->getString('Show_post_editings')}</label><br/>{/if}
  {if $show.SubscribeTopic}<input type="checkbox" name="c[SubscribeTopic]" value="1"{if $c.SubscribeTopic == 1} checked="checked"{/if} id="idSubscribeTopic"/><label for="idSubscribeTopic"> {$modules.Language->getString('Subscribe_topic')}</label><br/>{/if}
  {if $show.PicTopic}<input type="checkbox" name="c[PinTopic]" value="1"{if $c.PinTopic == 1} checked="checked"{/if} id="idPinTopic"/><label for="idPinTopic"> {$modules.Language->getString('Mark_topic_important')}</label><br/>{/if}
  {if $show.CloseTopic}<input type="checkbox" name="c[CloseTopic]" value="1"{if $c.CloseTopic == 1} checked="checked"{/if} id="idCloseTopic"/><label for="idCloseTopic"> {$modules.Language->getString('Close_topic')}</label>{/if}
 </span></td>
</tr>
{if $show.PollBox}
 <tr><td class="CellCat" colspan="2"><a name="pollrow"></a><span class="FontCat">{$modules.Language->getString('Poll')}</span></td></tr>
 <tr>
  <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('Poll_title')}:</span><br/><span class="FontSmall">{$modules.Language->getString('add_poll_info')}</span></td>
  <td class="CellAlt" valign="top"><input class="FormText" type="text" name="p_poll_title" maxlength="255" size="60" value="{$p_poll_title}"/></td>
 </tr>
 <tr>
  <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('Poll_options')}:</span></td>
  <td class="CellAlt" valign="top">
   <table id="idPollOptionsTable" border="0" cellpadding="1" cellspacing="0">
   {foreach from=$p.PollOptions item=curOption}
    <tr id="idOption{$poll_options_counter}">
     <td><input type="text" class="FormText" size="30" value="{$curOption}" name="p[PollOptions][]""/></td>
     <td><span class="FontSmall"><a href="javascript:deletePollOption('idOption{$poll_options_counter}');">l&ouml;schen</a></span></td>
    </tr>
   {/foreach}
   <tr><td><span class="FontSmall"><a href="javascript:addPollOption();">{$modules.Language->getString('Add_poll_option')}</a></span></td></tr>
  </table>
  </td>
 </tr>
{/if}
<tr><td class="CellButtons" colspan="2" align="center"><input class="FormButton" type="submit" value="{$actionText}"/>&nbsp;&nbsp;&nbsp;<input class="FormBButton" type="submit" name="ShowPreview" value="{$modules.Language->getString('Preview')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('Reset')}"/></td></tr>
</table></form>
<script type="text/javascript">
	if(RowsCounter == 0) addPollOption();
</script>