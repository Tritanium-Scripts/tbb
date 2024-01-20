<!-- EditPost -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=edit&amp;topic_id={$topicID}&amp;post_id={$postID}&amp;forum_id={$forumID}{$smarty.const.SID_AMPER}" name="beitrag">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{Language::getInstance()->getString('edit_post')}</span></th></tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('post_icon_colon')}</span></td>
  <td class="td1" style="vertical-align:top; width:80%;">{include file='TopicSmilies.tpl' checked=$editPost.tSmileyID}</td>
 </tr>{if !empty($editPost.title)}
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('title_colon')}</span></td>
  <td class="td1" style="width:80%;">{if !empty($prefixes)}<select name="prefixId" class="norm"><option>{Language::getInstance()->getString('prefix')}</option>{foreach $prefixes as $curPrefix}<option value="{$curPrefix[0]}"{if $curPrefix[0] == $editPost.prefixId} selected="selected"{/if}{if !empty($curPrefix[2])} style="color:{$curPrefix[2]};"{/if}>{$curPrefix[1]}</option>{/foreach}</select> {/if}<input type="text" size="30" name="title" value="{$editPost.title}" /></td>
 </tr>{/if}{if $forum.isBBCode}
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top; width:20%;"><span class="norm">{Language::getInstance()->getString('bbcode_colon')}</span></td>
  <td class="td1" style="width:80%;">{include file='BBCodes.tpl' targetBoxID='post'}</td>
 </tr>{/if}
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top;"><span class="norm">{Language::getInstance()->getString('post_colon')}</span><br /><br />{include file='Smilies.tpl' targetBoxID='post'}</td>
  <td class="td1" style="width:80%;"><textarea id="post" name="post" rows="10" cols="60">{$editPost.post}</textarea></td>
 </tr>{if Config::getInstance()->getCfgVal('tspacing') < 1}
 <tr><td class="td1" colspan="2"><hr /></td></tr>{/if}
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top; width:20%;"><span class="norm">{Language::getInstance()->getString('options_colon')}</span></td>
  <td class="td1" style="width:80%;">
   <input type="checkbox" id="smilies" name="smilies" value="1" style="vertical-align:middle;"{if $editPost.isSmilies} checked="checked"{/if} /> <label for="smilies" class="norm">{Language::getInstance()->getString('enable_smilies')}</label>{if Auth::getInstance()->isLoggedIn()}<br />
   <input type="checkbox" id="show_signatur" name="show_signatur" value="1" style="vertical-align:middle;"{if $editPost.isSignature} checked="checked"{/if} /> <label for="show_signatur" class="norm">{Language::getInstance()->getString('show_signature')}</label>{/if}{if $forum.isBBCode}<br />
   <input type="checkbox" id="use_upbcode" name="use_upbcode" value="1" style="vertical-align:middle;" onclick="document.getElementById('isAddURLs').disabled = !this.checked;"{if $editPost.isBBCode} checked="checked"{/if} /> <label for="use_upbcode" class="norm">{Language::getInstance()->getString('enable_bbcode')}</label>{/if}{if $forum.isXHTML}<br />
   <input type="checkbox" id="use_htmlcode" name="use_htmlcode" value="1" style="vertical-align:middle;"{if $editPost.isXHTML} checked="checked"{/if} /> <label for="use_htmlcode" class="norm">{Language::getInstance()->getString('enable_xhtml')}</label>{/if}{if $forum.isBBCode}<br />
   <input type="checkbox" id="isAddURLs" name="isAddURLs" value="true" style="vertical-align:middle;"{if $editPost.isAddURLs} checked="checked"{/if} /> <label for="isAddURLs" class="norm">{Language::getInstance()->getString('auto_transform_links')}</label>{/if}<br />
   <input type="checkbox" id="isLastEditBy" name="isLastEditBy" value="true" style="vertical-align:middle;"{if $editPost.isLastEditBy} checked="checked"{/if} /> <label for="isLastEditBy" class="norm">{Language::getInstance()->getString('show_as_last_editor')}</label>
  </td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('edit_post')}" /></p>
<input type="hidden" name="update" value="true" />
</form>