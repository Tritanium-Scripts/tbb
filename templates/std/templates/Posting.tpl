<script type="text/javascript">
	dojo.require("dojo.io.iframe");
	LanguageDelete = EntitiesToUnicode('{$modules.Language->getString('delete')}');
	LanguageUpload = EntitiesToUnicode('{$modules.Language->getString('upload')}');
	RowsCounter = {$pollOptionsCounter};
	LastRowID = {$pollOptionsCounter};
	var lastUploadFieldID = 0;

	{literal}
	function addPollOption() {
		LastRowID++

		var newtr = document.getElementById('idPollOptionsTable').insertRow(RowsCounter);
		newtr.id = 'idOption'+LastRowID;

		var newtd = document.createElement('td');
		newtd.style.padding = '3px';
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

	function addUploadField() {
		var fieldID = lastUploadFieldID++;

		var newdiv = document.createElement('div');
		newdiv.id = 'UploadField'+fieldID;

		var newinput = document.createElement('input');
		newinput.type = 'file';
		newinput.name = 'uploads[]';

		newdiv.appendChild(newinput);

		dojo.byId('UploadFields').appendChild(newdiv);
	}

	function closeUploadFormBox() {
		dojo.byId('UploadFormBox').style.display = 'none';
	}

	function openUploadFormBox() {
		dojo.byId('UploadFormBox').style.display = 'table';
	}
	{/literal}
</script>
{if $show.fileUploads}
	<div id="UploadFormBox" style="position:fixed; display:table; left:0; top:0; width:100%; height:100%;">
		<div style="position:fixed; left:0; top:0; width:100%; height:100%; background-color:#000000; opacity:0.9; z-index:1;">
		</div>
		<div style="display:table-cell; vertical-align:middle; position:relative; z-index:2;">
			<form id="UploadForm">
				<table class="TableStd" style="margin:auto; width:500px;">
					<tr><td class="CellTitle"><span class="FontTitle">Dateiupload</span></td></tr>
					<tr>
						<td class="CellStd">
							<div id="UploadFields"></div>
							<span class="FontSmall"><a href="javascript:addUploadField();">Feld f&uuml;r Upload hinzuf&uuml;gen</a></span>
						</td>
					</tr>
					<tr><td class="CellButtons"><button type="button" class="FormBButton" onclick="">Datei(en) hochladen</button>&nbsp;&nbsp;&nbsp;<button type="button" class="FormButton" onclick="closeUploadFormBox();">Abbrechen</button></tr></td>
				</table>
			</form>
		</div>
	</div>
{/if}
{if $show.previewBox}
 <table class="TableStd" width="100%">
 <tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('preview')}</span></td></tr>
 <tr><td class="CellStd"><span class="FontNorm">{$previewData.messageText}</span></td></tr>
 </table>
 <br/>
{/if}
<form method="post" action="{$smarty.const.INDEXFILE}?action=Posting&amp;mode={$mode}&amp;forumID={$forumID}&amp;topicID={$topicID}&amp;postID={$postID}&amp;doit=1&amp;{$smarty.const.MYSID}" name="MyForm">
<table class="TableStd" width="100%">
<colgroup>
 <col width="20%"/>
 <col width="80%"/>
</colgroup>
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$actionText}</span></td></tr>
<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('post')}</span></td></tr>
{if $error != ''}<tr><td class="CellError" colspan="2"><span class="FontError">{$error}</span></td></tr>{/if}
{if $modules.Auth->isLoggedIn() != 1}
 <tr>
  <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('your_name')}:</span><br/><span class="FontSmall">{$modules.Language->getString('nick_conventions')}</span></td>
  <td class="CellAlt" valign="top"><input size="20" class="FormText" type="text" name="p[guestNick]" value="{$p.guestNick}" maxlength="15"/></td>
 </tr>
{/if}
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('post_pic')}:</span></td>
 <td class="CellAlt" valign="top">{$postPicsBox}</td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('title')}:</span></td>
 <td class="CellAlt"><input class="FormText" type="text" size="65" name="p[messageTitle]" value="{$p.messageTitle}" maxlength="100"/>&nbsp;<span class="FontSmall"></span></td>
</tr>
{if $show.enableBBCode}
 <tr>
  <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('bbcode')}:</span></td>
  <td class="CellAlt">{include file=BBCodeBox.tpl}</td>
 </tr>
{/if}
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('post')}:</span><br/><br/>{$smiliesBox}</td>
 <td class="CellAlt"><div style="float:left;"><textarea class="FormTextArea" name="p[messageText]" rows="15" cols="80" id="messageBox">{$p.messageText}</textarea></div>{if $show.adminSmilies}<div style="float:left; padding-left:3px;"><span class="FontNorm">{$modules.Language->getString('admin_moderator_smilies')}</span><br/><br/>{$adminSmiliesBox}</div>{/if}</td>
</tr>
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('options')}:</span></td>
 <td class="CellAlt"><span class="FontNorm">
  {if $show.enableSmilies}<label><input type="checkbox" name="c[enableSmilies]" value="1"{if $c.enableSmilies == 1} checked="checked"{/if}/> {$modules.Language->getString('enable_smilies')}</label><br/>{/if}
  {if $show.showSignature}<label><input type="checkbox" name="c[showSignature]" value="1"{if $c.showSignature == 1} checked="checked"{/if}/> {$modules.Language->getString('show_signature')}</label><br/>{/if}
  {if $show.enableBBCode}<label><input type="checkbox" name="c[enableBBCode]" value="1"{if $c.enableBBCode == 1} checked="checked"{/if}/> {$modules.Language->getString('enable_bbcode')}</label><br/>{/if}
  {if $show.enableHtmlCode}<label><input type="checkbox" name="c[enableHtmlCode]" value="1"{if $c.enableHtmlCode == 1} checked="checked"{/if}/> {$modules.Language->getString('enable_html_code')}</label><br/>{/if}
  {if $show.enableURITransformation}<label><input type="checkbox" name="c[enableURITransformation]" value="1"{if $c.enableURITransformation == 1} checked="checked"{/if}/> {$modules.Language->getString('enable_url_transformation')}</label><br/>{/if}
  {if $show.showEditings}<label><input type="checkbox" name="c[showEditings]" value="1"{if $c.showEditings == 1} checked="checked"{/if}/> {$modules.Language->getString('show_post_editings')}</label><br/>{/if}
  {if $show.subscribeTopic}<label><input type="checkbox" name="c[subscribeTopic]" value="1"{if $c.subscribeTopic == 1} checked="checked"{/if}/> {$modules.Language->getString('subscribe_topic')}</label><br/>{/if}
  {if $show.pinTopic}<label><input type="checkbox" name="c[pinTopic]" value="1"{if $c.pinTopic == 1} checked="checked"{/if}/> {$modules.Language->getString('mark_topic_important')}</label><br/>{/if}
  {if $show.closeTopic}<label><input type="checkbox" name="c[closeTopic]" value="1"{if $c.closeTopic == 1} checked="checked"{/if}/> {$modules.Language->getString('close_topic')}</label>{/if}
 </span></td>
</tr>
{if $show.pollBox}
 <tr><td class="CellCat" colspan="2"><a name="pollrow"></a><span class="FontCat">{$modules.Language->getString('poll')}</span></td></tr>
 <tr>
  <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('poll_title')}:</span><br/><span class="FontSmall">{$modules.Language->getString('add_poll_info')}</span></td>
  <td class="CellAlt" valign="top"><input class="FormText" type="text" name="p[pollTitle]" maxlength="255" size="60" value="{$p.pollTitle}"/></td>
 </tr>
 <tr>
  <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('poll_duration')}:</span></td>
  <td class="CellAlt" valign="top"><input class="FormText" size="5" name="p[pollDuration]" value="{$p.pollDuration}"/> <span class="FontSmall">({$modules.Language->getString('in_days')})</span></td>
 </tr>
 <tr>
  <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('options')}:</span></td>
  <td class="CellAlt" valign="top"><span class="FontNorm">
   <label><input class="FormCheckbox" type="checkbox" name="c[pollShowResultsAfterEnd]"{if $c.pollShowResultsAfterEnd == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('show_results_after_end')}</label>
   <br/><label><input class="FormCheckbox" type="checkbox" name="c[pollGuestsVote]"{if $c.pollGuestsVote == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('guests_allowed_vote')}</label>
   <br/><label><input class="FormCheckbox" type="checkbox" name="c[pollGuestsViewResults]"{if $c.pollGuestsViewResults == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('guests_allowed_view_results')}</label>
  </span></td>
 </tr>
 <tr>
  <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('poll_options')}:</span></td>
  <td class="CellAlt" valign="top">
   <table id="idPollOptionsTable" border="0" cellpadding="1" cellspacing="0">
   {foreach from=$p.pollOptions item=curOption name=pollOptionsLoop}
    <tr id="idOption{$smarty.foreach.pollOptionsLoop.iteration}">
     <td style="padding:3px;"><input type="text" class="FormText" size="30" value="{$curOption}" name="p[pollOptions][]"/></td>
     <td style="padding:3px;"><span class="FontSmall"><a href="javascript:deletePollOption('idOption{$smarty.foreach.pollOptionsLoop.iteration}');">l&ouml;schen</a></span></td>
    </tr>
   {/foreach}
   <tr><td><span class="FontSmall"><a href="javascript:addPollOption();">{$modules.Language->getString('add_poll_option')}</a></span></td></tr>
  </table>
  </td>
 </tr>
 <script type="text/javascript">
	if(RowsCounter == 0) addPollOption();
 </script>
{/if}
{if $show.fileUploads}
	<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('attachments')}</span></td></tr>
	<tr>
		<td class="CellStd" colspan="2">
			<span class="FontSmall"><a href="javascript:openUploadFormBox();">Feld f&uuml;r Upload hinzuf&uuml;gen</a></span>
		</td>
	</tr>
{/if}
<tr><td class="CellButtons" colspan="2" align="center"><input class="FormButton" type="submit" value="{$actionText}"/>&nbsp;&nbsp;&nbsp;<input class="FormBButton" type="submit" name="showPreview" value="{$modules.Language->getString('preview')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('reset')}"/></td></tr>
</table></form>
{if $mode == 'Reply'}
	<br/>
	<table class="TableStd">
		<colgroup>
			<col width="15%"/>
			<col width="85%"/>
		</colgroup>
		<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('latest_posts_in_topic')}</span></td></tr>
		{foreach from=$latestPostsData item=curPost}
			<tr>
				<td class="CellAlt" width="15%" valign="top" rowspan="3"><span class="FontNorm"><b>{$curPost._postPosterNick}</b></span></td>
				<td class="CellAlt" width="85%" valign="middle">{if $curPost.postSmileyFileName != ''}<span style="margin-right:4px;"><img src="{$curPost.postSmileyFileName}" alt=""/></span>{/if}<span class="FontSmall"><a id="post{$curPost.postID}" name="post{$curPost.postID}"></a><b>{$curPost.postTitle}</b></span></td>
			</tr>
			<tr><td class="CellStd"><div class="FontNorm" style="min-height:50px;">{$curPost._postText}</div></td></tr>
			<tr><td class="CellStd" width="85%"><span class="FontSmall">{$modules.Language->getString('posted')}: {$curPost._postDateTime}</span></td></tr>
		{/foreach}
	</table>
{/if}
