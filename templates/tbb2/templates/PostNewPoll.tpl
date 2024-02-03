<!-- PostNewPoll -->
<script type="text/javascript">
/* <![CDATA[ */
lastRowID = {count($newPost.choices)};
rowsCounter = {count($newPost.choices)};

/**
 * Adds dynamically a new choice to poll options table.
 * Modifications by Chrissyx.
 *
 * @author Julian Backes <julian@tritanium-scripts.com>
 */
function addPollOption()
{
	lastRowID++

	//Create new table row
	var newTR = document.getElementById('idPollOptionsTable').insertRow(rowsCounter);
	newTR.id = 'idOption' + lastRowID;

	//Create new input field and append to row
	var newTD = document.createElement('td');
	newTD.style.padding = '3px';
	var newInput = document.createElement('input');
	newInput.type = 'text';
	newInput.className = 'formText';
	newInput.size = '30';
	newInput.name = 'poll_choice[]';
	newTD.appendChild(newInput);
	newTR.appendChild(newTD);

	//Create new delete link and append to row
	var newTD = document.createElement('td');
	//newTD.align = 'left';
	var newSpan = document.createElement('span');
	newSpan.className = 'fontSmall';
	var newA = document.createElement('a');
	newA.href = 'javascript:deletePollOption(\'idOption' + lastRowID + '\');';
	newA.appendChild(document.createTextNode('{Language::getInstance()->getString('delete')}'));
	newSpan.appendChild(newA);
	newTD.appendChild(newSpan);
	newTR.appendChild(newTD);

	rowsCounter++;
};

/**
 * Removes a choice from poll options table.
 *
 * @author Julian Backes <julian@tritanium-scripts.com>
 */
function deletePollOption(rowID)
{
	document.getElementById('idPollOptionsTable').deleteRow(document.getElementById(rowID).rowIndex);
	rowsCounter--;
};
/* ]]> */
</script>

{if $preview}
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('preview')}</span></th></tr>
 <tr><td class="cellStd"><div class="fontNorm">{$newPost.preview.post}</div>{if $newPost.isSignature && !empty($newPost.preview.signature)}<br /><div class="signature">-----------------------<br />{$newPost.preview.signature}</div>{/if}</td></tr>
</table>
<br />
{else}{include file='Errors.tpl'}{/if}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=newpoll&amp;forum_id={$forum.forumID}&amp;mode=step2{$smarty.const.SID_AMPER}" name="beitrag">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <colgroup>
  <col width="20%" />
  <col width="80%" />
 </colgroup>
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('post_new_poll')}</span></th></tr>
 <tr><td class="cellCat" colspan="2"><span class="fontCat">{Language::getInstance()->getString('post')}</span></td></tr>{if !Auth::getInstance()->isLoggedIn()}
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('your_name_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="nli_name" value="{$newPost.nick}" /></td>
 </tr>{/if}
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{Language::getInstance()->getString('post_icon_colon')}</span></td>
  <td class="cellAlt" style="vertical-align:top;">{include file='TopicSmilies.tpl' checked=$newPost.tSmiley}</td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('question_title_colon')}</span></td>
  <td class="cellAlt">{if !empty($prefixes)}<select class="formSelect" name="prefixId" class="fontNorm"><option>{Language::getInstance()->getString('prefix')}</option>{foreach $prefixes as $curPrefix}<option value="{$curPrefix[0]}"{if $curPrefix[0] == $newPost.prefixId} selected="selected"{/if}{if !empty($curPrefix[2])} style="color:{$curPrefix[2]};"{/if}>{$curPrefix[1]}</option>{/foreach}</select> {/if}<input class="formText" type="text" size="65" name="title" value="{$newPost.title}" /></td>
 </tr>{if $forum.isBBCode}
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{Language::getInstance()->getString('bbcode_colon')}</span></td>
  <td class="cellAlt">{include file='BBCodes.tpl' targetBoxID='post'}</td>
 </tr>{/if}
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{Language::getInstance()->getString('post_colon')}</span><br /><br />{include file='Smilies.tpl' targetBoxID='post'}</td>
  <td class="cellAlt"><textarea class="formTextArea" id="post" name="post" rows="15" cols="80">{$newPost.post}</textarea></td>
 </tr>
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{Language::getInstance()->getString('options_colon')}</span></td>
  <td class="cellAlt">
   <input type="checkbox" id="smilies" name="smilies" value="1"{if !$preview || $newPost.isSmilies} checked="checked"{/if} /> <label for="smilies" class="fontNorm">{Language::getInstance()->getString('enable_smilies')}</label>{if Auth::getInstance()->isLoggedIn()}<br />
   <input type="checkbox" id="show_signatur" name="show_signatur" value="1"{if !$preview || $newPost.isSignature} checked="checked"{/if} /> <label for="show_signatur" class="fontNorm">{Language::getInstance()->getString('show_signature')}</label>{/if}{if $forum.isBBCode}<br />
   <input type="checkbox" id="use_upbcode" name="use_upbcode" value="1" onclick="document.getElementById('isAddURLs').disabled = !this.checked;"{if !$preview || $newPost.isBBCode} checked="checked"{/if} /> <label for="use_upbcode" class="fontNorm">{Language::getInstance()->getString('enable_bbcode')}</label>{/if}{if $forum.isXHTML}<br />
   <input type="checkbox" id="use_htmlcode" name="use_htmlcode" value="1"{if $newPost.isXHTML} checked="checked"{/if} /> <label for="use_htmlcode" class="fontNorm">{Language::getInstance()->getString('enable_xhtml')}</label>{/if}{if Config::getInstance()->getCfgVal('activate_mail') == 1 && Config::getInstance()->getCfgVal('notify_new_replies') == 1 && Auth::getInstance()->isLoggedIn()}<br />
   <input type="checkbox" id="sendmail2" name="sendmail2" value="1"{if $newPost.isNotify} checked="checked"{/if} /> <label for="sendmail2" class="fontNorm">{Language::getInstance()->getString('notify_on_new_reply')}</label>{/if}{if $forum.isBBCode}<br />
   <input type="checkbox" id="isAddURLs" name="isAddURLs" value="true"{if !$preview || $newPost.isAddURLs} checked="checked"{/if} /> <label for="isAddURLs" class="fontNorm">{Language::getInstance()->getString('auto_transform_links')}</label>{/if}
  </td>
 </tr>
 <tr><td class="cellCat" colspan="2"><span class="fontCat">{Language::getInstance()->getString('poll', 'Forum')}</span></td></tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('who_may_vote_colon')}</span></td>
  <td class="cellAlt"><span class="fontNorm"><select class="formSelect" name="poll_type">{html_options values=array(1, 2) output=array(Language::getInstance()->getString('everybody'), Language::getInstance()->getString('members_only')) selected=$newPost.pollType}</select></span></td>
 </tr>
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{Language::getInstance()->getString('choices')}</span></td>
  <td class="cellAlt">
   <table cellpadding="1" cellspacing="0" id="idPollOptionsTable">{foreach $newPost.choices as $curChoice}
    <tr id="idOption{$curChoice@iteration}"><td style="padding:3px;"><span class="fontNorm"><input class="formText" type="text" size="30" name="poll_choice[]" value="{$curChoice}" /></span></td><td style="padding:3px;"><span class="fontSmall"><a href="javascript:deletePollOption('idOption{$curChoice@iteration}');">{Language::getInstance()->getString('delete')}</a></span></td></tr>{/foreach}
    <tr><td colspan="2"><span class="fontSmall"><a href="javascript:addPollOption();">{Language::getInstance()->getString('add_choice')}</a></span></td></tr>
   </table>
  </td>
 </tr>
</table>
<script type="text/javascript">
if(rowsCounter == 0)
	addPollOption();
</script>
<p class="cellButtons"><input class="formButton" type="submit" value="{Language::getInstance()->getString('post_new_poll')}" />&nbsp;&nbsp;&nbsp;<input class="formBButton" type="submit" name="preview" value="{Language::getInstance()->getString('preview')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" /></p>
<input type="hidden" name="save" value="yes" />
</form>