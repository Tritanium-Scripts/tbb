<!-- EditPost -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=edit&amp;topic_id={$topicID}&amp;post_id={$postID}&amp;forum_id={$forumID}{$smarty.const.SID_AMPER}" name="beitrag">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <colgroup>
  <col width="20%" />
  <col width="80%" />
 </colgroup>
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('edit_post')}</span></th></tr>
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{Language::getInstance()->getString('post_icon_colon')}</span></td>
  <td class="cellAlt" style="vertical-align:top;">{include file='TopicSmilies.tpl' checked=$editPost.tSmileyID}</td>
 </tr>{if !empty($editPost.title)}
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('title_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" size="65" name="title" value="{$editPost.title}" /></td>
 </tr>{/if}{if $forum.isBBCode}
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{Language::getInstance()->getString('bbcode_colon')}</span></td>
  <td class="cellAlt">{include file='BBCodes.tpl' targetBoxID='post'}</td>
 </tr>{/if}
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{Language::getInstance()->getString('post_colon')}</span><br /><br />{include file='Smilies.tpl' targetBoxID='post'}</td>
  <td class="cellAlt"><textarea class="formTextArea" id="post" name="post" rows="15" cols="80">{$editPost.post}</textarea></td>
 </tr>
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{Language::getInstance()->getString('options_colon')}</span></td>
  <td class="cellAlt">
   <input type="checkbox" id="smilies" name="smilies" value="1"{if $editPost.isSmilies} checked="checked"{/if} /> <label for="smilies" class="fontNorm">{Language::getInstance()->getString('enable_smilies')}</label>{if Auth::getInstance()->isLoggedIn()}<br />
   <input type="checkbox" id="show_signatur" name="show_signatur" value="1"{if $editPost.isSignature} checked="checked"{/if} /> <label for="show_signatur" class="fontNorm">{Language::getInstance()->getString('show_signature')}</label>{/if}{if $forum.isBBCode}<br />
   <input type="checkbox" id="use_upbcode" name="use_upbcode" value="1" onclick="document.getElementById('isAddURLs').disabled = !this.checked;"{if $editPost.isBBCode} checked="checked"{/if} /> <label for="use_upbcode" class="fontNorm">{Language::getInstance()->getString('enable_bbcode')}</label>{/if}{if $forum.isXHTML}<br />
   <input type="checkbox" id="use_htmlcode" name="use_htmlcode" value="1"{if $editPost.isXHTML} checked="checked"{/if} /> <label for="use_htmlcode" class="fontNorm">{Language::getInstance()->getString('enable_xhtml')}</label>{/if}{if $forum.isBBCode}<br />
   <input type="checkbox" id="isAddURLs" name="isAddURLs" value="true"{if $editPost.isAddURLs} checked="checked"{/if} /> <label for="isAddURLs" class="fontNorm">{Language::getInstance()->getString('auto_transform_links')}</label>{/if}<br />
   <input type="checkbox" id="isLastEditBy" name="isLastEditBy" value="true"{if $editPost.isLastEditBy} checked="checked"{/if} /> <label for="isLastEditBy" class="fontNorm">{Language::getInstance()->getString('show_as_last_editor')}</label>
  </td>
 </tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('edit_post')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" /></p>
<input type="hidden" name="update" value="true" />
</form>