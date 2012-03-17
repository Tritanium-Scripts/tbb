<!-- PostReply -->
{if $preview}
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{$modules.Language->getString('preview')}</span></th></tr>
 <tr><td class="cellStd"><div class="fontNorm">{$newReply.preview.post}</div>{if $newReply.isSignature && !empty($newPost.preview.signature)}<br /><div class="signature">-----------------------<br />{$newReply.preview.signature}</div>{/if}</td></tr>
</table>
<br />
{else}{include file='Errors.tpl'}{/if}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=reply&amp;mode=save{$smarty.const.SID_AMPER}" name="beitrag">
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <colgroup>
  <col width="20%" />
  <col width="80%" />
 </colgroup>
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{$modules.Language->getString('post_new_reply')}</span></th></tr>{if !$modules.Auth->isLoggedIn()}
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('your_name_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="nli_name" value="{$newReply.nick}" /></td>
 </tr>{/if}
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{$modules.Language->getString('post_icon_colon')}</span></td>
  <td class="cellAlt" style="vertical-align:top;">{include file='TopicSmilies.tpl' checked=$newReply.tSmileyID}</td>
 </tr>{if $forum.isBBCode}
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{$modules.Language->getString('bbcode_colon')}</span></td>
  <td class="cellAlt">{include file='BBCodes.tpl' targetBoxID='post'}</td>
 </tr>{/if}
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{$modules.Language->getString('post_colon')}</span><br /><br />{include file='Smilies.tpl' targetBoxID='post'}</td>
  <td class="cellAlt"><textarea class="formTextArea" id="post" name="post" rows="15" cols="80">{$newReply.post}</textarea></td>
 </tr>
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{$modules.Language->getString('options_colon')}</span></td>
  <td class="cellAlt">
   <input type="checkbox" id="smilies" name="smilies" value="1"{if !$preview || $newReply.isSmilies} checked="checked"{/if} /> <label for="smilies" class="fontNorm">{$modules.Language->getString('enable_smilies')}</label>{if $modules.Auth->isLoggedIn()}<br />
   <input type="checkbox" id="show_signatur" name="show_signatur" value="1"{if !$preview || $newReply.isSignature} checked="checked"{/if} /> <label for="show_signatur" class="fontNorm">{$modules.Language->getString('show_signature')}</label>{/if}{if $forum.isBBCode}<br />
   <input type="checkbox" id="use_upbcode" name="use_upbcode" value="1" onclick="document.getElementById('isAddURLs').disabled = !this.checked;"{if !$preview || $newReply.isBBCode} checked="checked"{/if} /> <label for="use_upbcode" class="fontNorm">{$modules.Language->getString('enable_bbcode')}</label>{/if}{if $forum.isXHTML}<br />
   <input type="checkbox" id="use_htmlcode" name="use_htmlcode" value="1"{if $newReply.isXHTML} checked="checked"{/if} /> <label for="use_htmlcode" class="fontNorm">{$modules.Language->getString('enable_xhtml')}</label>{/if}{if $forum.isBBCode}<br />
   <input type="checkbox" id="isAddURLs" name="isAddURLs" value="true"{if !$preview || $newReply.isAddURLs} checked="checked"{/if} /> <label for="isAddURLs" class="fontNorm">{$modules.Language->getString('auto_transform_links')}</label>{/if}
  </td>
 </tr>
</table>
<p class="cellButtons"><input class="formButton" type="submit" value="{$modules.Language->getString('post_new_reply')}" />&nbsp;&nbsp;&nbsp;<input class="formBButton" type="submit" name="preview" value="{$modules.Language->getString('preview')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" /></p>
<input type="hidden" name="topic_id" value="{$topicID}" />
<input type="hidden" name="forum_id" value="{$forum.forumID}" />
</form>

<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <colgroup>
  <col width="15%" />
  <col width="85%" />
 </colgroup>
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{$modules.Language->getString('newest_replies')}</span></th></tr>
{foreach $lastReplies as $curReply}
 <tr>
  <td class="cellAlt" style="font-weight:bold; vertical-align:top;"><span class="fontNorm">{$curReply.nick}</span></td>
  <td class="cellStd"><div class="fontNorm" style="min-height:50px;">{$curReply.post}</div></td>
 </tr>
{/foreach}
</table>
