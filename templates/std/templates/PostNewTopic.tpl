<!-- PostNewTopic -->
{if $preview}
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{Language::getInstance()->getString('preview')}</span></th></tr>
 <tr><td class="td1"><div class="norm">{$newPost.preview.post}{if $newPost.isSignature}<br /><br />-----------------------<br />{$newPost.preview.signature}{/if}</div></td></tr>
</table>
<br />
{else}{include file='Errors.tpl'}{/if}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=newtopic{$smarty.const.SID_AMPER}" name="beitrag">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{Language::getInstance()->getString('post_new_topic')}</span></th></tr>{if !Auth::getInstance()->isLoggedIn()}
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('your_name_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="nli_name" value="{$newPost.nick}" /></td>
 </tr>{/if}
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('post_icon_colon')}</span></td>
  <td class="td1" style="vertical-align:top; width:80%;">{include file='TopicSmilies.tpl' checked=$newPost.tSmiley}</td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('title_colon')}</span></td>
  <td class="td1" style="width:80%;">{if !empty($prefixes)}<select name="prefixId" class="norm"><option value=""></option>{foreach $prefixes as $curPrefix}<option value="{$curPrefix[0]}"{if $curPrefix[0] == $newPost.prefixId} selected="selected"{/if}{if !empty($curPrefix[2])} style="color:{$curPrefix[2]};"{/if}>{$curPrefix[1]}</option>{/foreach}</select> {/if}<input type="text" size="30" name="title" value="{$newPost.title}" /></td>
 </tr>{if $forum.isBBCode}
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top; width:20%;"><span class="norm">{Language::getInstance()->getString('bbcode_colon')}</span></td>
  <td class="td1" style="width:80%;">{include file='BBCodes.tpl' targetBoxID='post'}</td>
 </tr>{/if}
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top;"><span class="norm">{Language::getInstance()->getString('post_colon')}</span><br /><br />{include file='Smilies.tpl' targetBoxID='post'}</td>
  <td class="td1" style="width:80%;"><textarea id="post" name="post" rows="10" cols="60">{$newPost.post}</textarea></td>
 </tr>{if Config::getInstance()->getCfgVal('tspacing') < 1}
 <tr><td class="td1" colspan="2"><hr /></td></tr>{/if}
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top; width:20%;"><span class="norm">{Language::getInstance()->getString('options_colon')}</span></td>
  <td class="td1" style="width:80%;">
   <input type="checkbox" id="smilies" name="smilies" value="1" style="vertical-align:middle;"{if !$preview || $newPost.isSmilies} checked="checked"{/if} /> <label for="smilies" class="norm">{Language::getInstance()->getString('enable_smilies')}</label>{if Auth::getInstance()->isLoggedIn()}<br />
   <input type="checkbox" id="show_signatur" name="show_signatur" value="1" style="vertical-align:middle;"{if !$preview || $newPost.isSignature} checked="checked"{/if} /> <label for="show_signatur" class="norm">{Language::getInstance()->getString('show_signature')}</label>{/if}{if $forum.isBBCode}<br />
   <input type="checkbox" id="use_upbcode" name="use_upbcode" value="1" style="vertical-align:middle;" onclick="document.getElementById('isAddURLs').disabled = !this.checked;"{if !$preview || $newPost.isBBCode} checked="checked"{/if} /> <label for="use_upbcode" class="norm">{Language::getInstance()->getString('enable_bbcode')}</label>{/if}{if $forum.isXHTML}<br />
   <input type="checkbox" id="use_htmlcode" name="use_htmlcode" value="1" style="vertical-align:middle;"{if $newPost.isXHTML} checked="checked"{/if} /> <label for="use_htmlcode" class="norm">{Language::getInstance()->getString('enable_xhtml')}</label>{/if}{if Config::getInstance()->getCfgVal('activate_mail') == 1 && Config::getInstance()->getCfgVal('notify_new_replies') == 1 && Auth::getInstance()->isLoggedIn()}<br />
   <input type="checkbox" id="sendmail2" name="sendmail2" value="1" style="vertical-align:middle;"{if $newPost.isNotify} checked="checked"{/if} /> <label for="sendmail2" class="norm">{Language::getInstance()->getString('notify_on_new_reply')}</label>{/if}{if $forum.isBBCode}<br />
   <input type="checkbox" id="isAddURLs" name="isAddURLs" value="true" style="vertical-align:middle;"{if !$preview || $newPost.isAddURLs} checked="checked"{/if} /> <label for="isAddURLs" class="norm">{Language::getInstance()->getString('auto_transform_links')}</label>{/if}
  </td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('post_new_topic')}" />&nbsp;&nbsp;&nbsp;<input type="submit" name="preview" value="{Language::getInstance()->getString('preview')}" style="font-weight:bold;" /></p>
<input type="hidden" name="save" value="yes" />
<input type="hidden" name="forum_id" value="{$forum.forumID}" />
</form>