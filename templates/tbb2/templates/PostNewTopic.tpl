<!-- PostNewTopic -->
{if $preview}
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('preview')}</span></th></tr>
 <tr><td class="cellStd"><div class="fontNorm">{$newPost.preview.post}</div>{if $newPost.isSignature && !empty($newPost.preview.signature)}<br /><div class="signature">-----------------------<br />{$newPost.preview.signature}</div>{/if}</td></tr>
</table>
<br />
{else}{include file='Errors.tpl'}{/if}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=newtopic{$smarty.const.SID_AMPER}" name="beitrag">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <colgroup>
  <col width="20%" />
  <col width="80%" />
 </colgroup>
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('post_new_topic')}</span></th></tr>{if !Auth::getInstance()->isLoggedIn()}
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('your_name_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="nli_name" value="{$newPost.nick}" /></td>
 </tr>{/if}
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{Language::getInstance()->getString('post_icon_colon')}</span></td>
  <td class="cellAlt">{include file='TopicSmilies.tpl' checked=$newPost.tSmiley}</td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('title_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" size="65" name="title" value="{$newPost.title}" /></td>
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
</table>
<p class="cellButtons"><input class="formButton" type="submit" value="{Language::getInstance()->getString('post_new_topic')}" />&nbsp;&nbsp;&nbsp;<input class="formBButton" type="submit" name="preview" value="{Language::getInstance()->getString('preview')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" /></p>
<input type="hidden" name="save" value="yes" />
<input type="hidden" name="forum_id" value="{$forum.forumID}" />
</form>