<!-- PostNewTopic -->
{if $preview}
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{$modules.Language->getString('preview')}</span></th></tr>
 <tr><td class="cellStd"><div class="fontNorm">{$newPost.preview.post}</div>{if $newPost.isSignature}<br /><div class="signature">-----------------------<br />{$newPost.preview.signature}</div>{/if}</td></tr>
</table>
<br />
{else}{include file='Errors.tpl'}{/if}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=newtopic{$smarty.const.SID_AMPER}" name="beitrag">
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <colgroup>
  <col width="20%" />
  <col width="80%" />
 </colgroup>
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{$modules.Language->getString('post_new_topic')}</span></th></tr>{if !$modules.Auth->isLoggedIn()}
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('your_name_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="nli_name" value="{$newPost.nick}" /></td>
 </tr>{/if}
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{$modules.Language->getString('post_icon_colon')}</span></td>
  <td class="cellAlt">{include file='TopicSmilies.tpl' checked=$newPost.tSmiley}</td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('title_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" size="65" name="title" value="{$newPost.title}" /></td>
 </tr>{if $forum.isBBCode}
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{$modules.Language->getString('bbcode_colon')}</span></td>
  <td class="cellAlt">{include file='BBCodes.tpl' targetBoxID='post'}</td>
 </tr>{/if}
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{$modules.Language->getString('post_colon')}</span><br /><br />{include file='Smilies.tpl' targetBoxID='post'}</td>
  <td class="cellAlt"><textarea class="formTextArea" id="post" name="post" rows="15" cols="80">{$newPost.post}</textarea></td>
 </tr>
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{$modules.Language->getString('options_colon')}</span></td>
  <td class="cellAlt">
   <input type="checkbox" id="smilies" name="smilies" value="1"{if !$preview || $newPost.isSmilies} checked="checked"{/if} /> <label for="smilies" class="fontNorm">{$modules.Language->getString('enable_smilies')}</label>{if $modules.Auth->isLoggedIn()}<br />
   <input type="checkbox" id="show_signatur" name="show_signatur" value="1"{if !$preview || $newPost.isSignature} checked="checked"{/if} /> <label for="show_signatur" class="fontNorm">{$modules.Language->getString('show_signature')}</label>{/if}{if $forum.isBBCode}<br />
   <input type="checkbox" id="use_upbcode" name="use_upbcode" value="1" onclick="document.getElementById('isAddURLs').disabled = !this.checked;"{if !$preview || $newPost.isBBCode} checked="checked"{/if} /> <label for="use_upbcode" class="fontNorm">{$modules.Language->getString('enable_bbcode')}</label>{/if}{if $forum.isXHTML}<br />
   <input type="checkbox" id="use_htmlcode" name="use_htmlcode" value="1"{if $newPost.isXHTML} checked="checked"{/if} /> <label for="use_htmlcode" class="fontNorm">{$modules.Language->getString('enable_xhtml')}</label>{/if}{if $modules.Config->getCfgVal('activate_mail') == 1 && $modules.Config->getCfgVal('notify_new_replies') == 1 && $modules.Auth->isLoggedIn()}<br />
   <input type="checkbox" id="sendmail2" name="sendmail2" value="1"{if $newPost.isNotify} checked="checked"{/if} /> <label for="sendmail2" class="fontNorm">{$modules.Language->getString('notify_on_new_reply')}</label>{/if}{if $forum.isBBCode}<br />
   <input type="checkbox" id="isAddURLs" name="isAddURLs" value="true"{if !$preview || $newPost.isAddURLs} checked="checked"{/if} /> <label for="isAddURLs" class="fontNorm">{$modules.Language->getString('auto_transform_links')}</label>{/if}
  </td>
 </tr>
</table>
<p class="cellButtons"><input class="formButton" type="submit" value="{$modules.Language->getString('post_new_topic')}" />&nbsp;&nbsp;&nbsp;<input class="formBButton" type="submit" name="preview" value="{$modules.Language->getString('preview')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" /></p>
<input type="hidden" name="save" value="yes" />
<input type="hidden" name="forum_id" value="{$forum.forumID}" />
</form>