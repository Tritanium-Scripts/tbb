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
	newInput.className = 'norm';
	newInput.size = '40';
	newInput.name = 'poll_choice[]';
	newTD.appendChild(newInput);
	newTR.appendChild(newTD);

	//Create new delete link and append to row
	var newTD = document.createElement('td');
	//newTD.align = 'left';
	var newSpan = document.createElement('span');
	newSpan.className = 'small';
	var newA = document.createElement('a');
	newA.href = 'javascript:deletePollOption(\'idOption' + lastRowID + '\');';
	newA.appendChild(document.createTextNode('{$modules.Language->getString('delete')}'));
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
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{$modules.Language->getString('preview')}</span></th></tr>
 <tr><td class="td1"><div class="norm">{$newPost.preview.post}{if $newPost.isSignature}<br /><br />-----------------------<br />{$newPost.preview.signature}{/if}</div></td></tr>
</table>
<br />
{else}{include file='Errors.tpl'}{/if}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=newpoll&amp;forum_id={$forum.forumID}&amp;mode=step2{$smarty.const.SID_AMPER}" name="beitrag">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{$modules.Language->getString('post_new_poll')}</span></th></tr>{if !$modules.Auth->isLoggedIn()}
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('your_name_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="nli_name" value="{$newPost.nick}" /></td>
 </tr>{/if}
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('who_may_vote_colon')}</span></td>
  <td class="td1" style="width:80%;"><span class="norm">{html_options name='poll_type' values=array(1, 2) output=array($modules.Language->getString('everybody'), $modules.Language->getString('members_only')) selected=$newPost.pollType}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('question_title_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" size="30" name="title" value="{$newPost.title}" /></td>
 </tr>
 <tr><td class="kat" colspan="2"><span class="kat">{$modules.Language->getString('choices')}</span></td></tr>
 <tr>
  <td class="td1" colspan="2">
   <table cellpadding="1" cellspacing="0" id="idPollOptionsTable">{foreach $newPost.choices as $curChoice}
    <tr id="idOption{$curChoice@iteration}"><td><span class="norm">{$curChoice@iteration}. <input type="text" size="40" name="poll_choice[]" value="{$curChoice}" /></span></td><td><span class="small"><a href="javascript:deletePollOption('idOption{$curChoice@iteration}');">{$modules.Language->getString('delete')}</a></span></td></tr>{/foreach}
    <tr><td colspan="2"><span class="small"><a href="javascript:addPollOption();">{$modules.Language->getString('add_choice')}</a></span></td></tr>
   </table>
  </td>
 </tr>
 <tr><td class="kat" colspan="2"><span class="kat">{$modules.Language->getString('post')}</span></td></tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('post_icon_colon')}</span></td>
  <td class="td1" style="vertical-align:top; width:80%;">{include file='TopicSmilies.tpl' checked=$newPost.tSmiley}</td>
 </tr>{if $forum.isBBCode}
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top; width:20%;"><span class="norm">{$modules.Language->getString('bbcode_colon')}</span></td>
  <td class="td1" style="width:80%;">{include file='BBCodes.tpl' targetBoxID='post'}</td>
 </tr>{/if}
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top;"><span class="norm">{$modules.Language->getString('post_colon')}</span><br /><br />{include file='Smilies.tpl' targetBoxID='post'}</td>
  <td class="td1" style="width:80%;"><textarea id="post" name="post" rows="10" cols="60">{$newPost.post}</textarea></td>
 </tr>{if $modules.Config->getCfgVal('tspacing') < 1}
 <tr><td class="td1" colspan="2"><hr /></td></tr>{/if}
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top; width:20%;"><span class="norm">{$modules.Language->getString('options_colon')}</span></td>
  <td class="td1" style="width:80%;">
   <input type="checkbox" id="smilies" name="smilies" value="1" style="vertical-align:middle;"{if !$preview || $newPost.isSmilies} checked="checked"{/if} /> <label for="smilies" class="norm">{$modules.Language->getString('enable_smilies')}</label>{if $modules.Auth->isLoggedIn()}<br />
   <input type="checkbox" id="show_signatur" name="show_signatur" value="1" style="vertical-align:middle;"{if !$preview || $newPost.isSignature} checked="checked"{/if} /> <label for="show_signatur" class="norm">{$modules.Language->getString('show_signature')}</label>{/if}{if $forum.isBBCode}<br />
   <input type="checkbox" id="use_upbcode" name="use_upbcode" value="1" style="vertical-align:middle;" onclick="document.getElementById('isAddURLs').disabled = !this.checked;"{if !$preview || $newPost.isBBCode} checked="checked"{/if} /> <label for="use_upbcode" class="norm">{$modules.Language->getString('enable_bbcode')}</label>{/if}{if $forum.isXHTML}<br />
   <input type="checkbox" id="use_htmlcode" name="use_htmlcode" value="1" style="vertical-align:middle;"{if $newPost.isXHTML} checked="checked"{/if} /> <label for="use_htmlcode" class="norm">{$modules.Language->getString('enable_xhtml')}</label>{/if}{if $modules.Config->getCfgVal('activate_mail') == 1 && $modules.Config->getCfgVal('notify_new_replies') == 1 && $modules.Auth->isLoggedIn()}<br />
   <input type="checkbox" id="sendmail2" name="sendmail2" value="1" style="vertical-align:middle;"{if $newPost.isNotify} checked="checked"{/if} /> <label for="sendmail2" class="norm">{$modules.Language->getString('notify_on_new_reply')}</label>{/if}{if $forum.isBBCode}<br />
   <input type="checkbox" id="isAddURLs" name="isAddURLs" value="true" style="vertical-align:middle;"{if !$preview || $newPost.isAddURLs} checked="checked"{/if} /> <label for="isAddURLs" class="norm">{$modules.Language->getString('auto_transform_links')}</label>{/if}
  </td>
 </tr>
</table>
<script type="text/javascript">
if(rowsCounter == 0)
	addPollOption();
</script>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('post_new_poll')}" />&nbsp;&nbsp;&nbsp;<input type="submit" name="preview" value="{$modules.Language->getString('preview')}" style="font-weight:bold;" /></p>
<input type="hidden" name="save" value="yes" />
</form>