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
		var newinput = document.createElement('input');
		newinput.type = 'text';
		newinput.className = 'FormText';
		newinput.size = '30';
		newinput.name = "p[pollOptions][]";
		newtd.appendChild(newinput);
		newtr.appendChild(newtd);

		var newtd = document.createElement('td');
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
{if $show.previewBox}
 <table class="TableStd" border="0" cellspacing="0" cellpadding="3" width="100%">
 <tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('Preview')}</span></td></tr>
 <tr><td class="CellStd"><span class="FontNorm">{$preview_post}</span></td></tr>
 </table>
 <br/>
{/if}
<form method="post" action="{$indexFile}?action=Posting&amp;mode={$mode}&amp;forumID={$forumID}&amp;topicID={$topicID}&amp;postID={$postID}&amp;doit=1&amp;{$mySID}" name="MyForm">
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
  <td class="CellAlt" valign="top"><input size="20" class="FormText" type="text" name="p[guestNick]" value="{$p.guestNick}" maxlength="15"/></td>
 </tr>
{/if}
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('Post_pic')}:</span></td>
 <td class="CellAlt" valign="top">{$postPicsBox}</td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Title')}:</span></td>
 <td class="CellAlt"><input class="FormText" type="text" size="65" name="p[messageTitle]" value="{$p.messageTitle}" maxlength="100"/>&nbsp;<span class="FontSmall"></span></td>
</tr>
 <tr>
  <td class="CellStd" valign="top"></td>
  <td class="CellAlt"></td>
 </tr>
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('Post')}:</span><br/><br/>{$smiliesBox}</td>
 <td class="CellAlt"><textarea class="FormTextArea" name="p[messageText]" rows="14" cols="80" onselect="storecaret();" onclick="storecaret();" onkeyup="storecaret();">{$p.messageText}</textarea></td>
</tr>
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('Options')}:</span></td>
 <td class="CellAlt"><span class="FontNorm">
  {if $show.enableSmilies}<label><input type="checkbox" name="c[enableSmilies]" value="1"{if $c.enableSmilies == 1} checked="checked"{/if}/> {$modules.Language->getString('Enable_smilies')}</label><br/>{/if}
  {if $show.showSignature}<label><input type="checkbox" name="c[showSignature]" value="1"{if $c.showSignature == 1} checked="checked"{/if}/> {$modules.Language->getString('Show_signature')}</label><br/>{/if}
  {if $show.enableBBCode}<label><input type="checkbox" name="c[enableBBCode]" value="1"{if $c.enableBBCode} == 1} checked="checked"{/if}/> {$modules.Language->getString('Enable_bbcode')}</label><br/>{/if}
  {if $show.enableHtmlCode}<label><input type="checkbox" name="c[enableHtmlCode]" value="1"{if $c.enableHtmlCode == 1} checked="checked"{/if}/> {$modules.Language->getString('Enable_html_code')}</label><br/>{/if}
  {if $show.enableURITransformation}<label><input type="checkbox" name="c[enableURITransformation]" value="1"{if $c.enableURITransformation == 1} checked="checked"{/if}/> {$modules.Language->getString('Enable_url_transformation')}</label><br/>{/if}
  {if $show.showEditings}<label><input type="checkbox" name="c[showEditings]" value="1"{if $c.showEditings == 1} checked="checked"{/if}/> {$modules.Language->getString('Show_post_editings')}</label><br/>{/if}
  {if $show.subscribeTopic}<label><input type="checkbox" name="c[subscribeTopic]" value="1"{if $c.subscribeTopic == 1} checked="checked"{/if}/> {$modules.Language->getString('Subscribe_topic')}</label><br/>{/if}
  {if $show.pinTopic}<label><input type="checkbox" name="c[pinTopic]" value="1"{if $c.pinTopic == 1} checked="checked"{/if}/> {$modules.Language->getString('Mark_topic_important')}</label><br/>{/if}
  {if $show.closeTopic}<label><input type="checkbox" name="c[closeTopic]" value="1"{if $c.closeTopic == 1} checked="checked"{/if}/> {$modules.Language->getString('Close_topic')}</label>{/if}
 </span></td>
</tr>
{if $show.pollBox}
 <tr><td class="CellCat" colspan="2"><a name="pollrow"></a><span class="FontCat">{$modules.Language->getString('Poll')}</span></td></tr>
 <tr>
  <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('Poll_title')}:</span><br/><span class="FontSmall">{$modules.Language->getString('add_poll_info')}</span></td>
  <td class="CellAlt" valign="top"><input class="FormText" type="text" name="p[pollTitle]" maxlength="255" size="60" value="{$p.pollTitle}"/></td>
 </tr>
 <tr>
  <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('Poll_options')}:</span></td>
  <td class="CellAlt" valign="top">
   <table id="idPollOptionsTable" border="0" cellpadding="1" cellspacing="0">
   {foreach from=$p.pollOptions item=curOption name=pollOptionsLoop}
    <tr id="idOption{$smarty.foreach.pollOptionsLoop.iteration}">
     <td><input type="text" class="FormText" size="30" value="{$curOption}" name="p[pollOptions][]""/></td>
     <td><span class="FontSmall"><a href="javascript:deletePollOption('idOption{$smarty.foreach.pollOptionsLoop.iteration}');">l&ouml;schen</a></span></td>
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