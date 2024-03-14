<!-- PostReply -->
{if $preview}
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{Language::getInstance()->getString('preview')}</span></th></tr>
 <tr><td class="td1"><div class="norm">{$newReply.preview.post}{if $newReply.isSignature}<br /><br />-----------------------<br />{$newReply.preview.signature}{/if}</div></td></tr>
</table>
<br />
{else}{include file='Errors.tpl'}{/if}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=reply&amp;mode=save{$smarty.const.SID_AMPER}" name="beitrag">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{Language::getInstance()->getString('post_new_reply')}</span></th></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_POSTING_NEW_REPLY_FORM_START}{if !Auth::getInstance()->isLoggedIn()}
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('your_name_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="nli_name" value="{$newReply.nick}" /></td>
 </tr>{/if}
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('post_icon_colon')}</span></td>
  <td class="td1" style="vertical-align:top; width:80%;">{include file='TopicSmilies.tpl' checked=$newReply.tSmileyID}</td>
 </tr>{if $forum.isBBCode}
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top; width:20%;"><span class="norm">{Language::getInstance()->getString('bbcode_colon')}</span></td>
  <td class="td1" style="width:80%;">{include file='BBCodes.tpl' targetBoxID='post'}</td>
 </tr>{/if}
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top;"><span class="norm">{Language::getInstance()->getString('post_colon')}</span><br /><br />{include file='Smilies.tpl' targetBoxID='post'}</td>
  <td class="td1" style="width:80%;"><textarea id="post" name="post" rows="10" cols="60">{$newReply.post}</textarea></td>
 </tr>
{plugin_hook hook=PlugIns::HOOK_TPL_POSTING_NEW_REPLY_FORM_END}{if Config::getInstance()->getCfgVal('tspacing') < 1}
 <tr><td class="td1" colspan="2"><hr /></td></tr>{/if}
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top; width:20%;"><span class="norm">{Language::getInstance()->getString('options_colon')}</span></td>
  <td class="td1" style="width:80%;">
   <input type="checkbox" id="smilies" name="smilies" value="1" style="vertical-align:middle;"{if !$preview || $newReply.isSmilies} checked="checked"{/if} /> <label for="smilies" class="norm">{Language::getInstance()->getString('enable_smilies')}</label>{if Auth::getInstance()->isLoggedIn()}<br />
   <input type="checkbox" id="show_signatur" name="show_signatur" value="1" style="vertical-align:middle;"{if !$preview || $newReply.isSignature} checked="checked"{/if} /> <label for="show_signatur" class="norm">{Language::getInstance()->getString('show_signature')}</label>{/if}{if $forum.isBBCode}<br />
   <input type="checkbox" id="use_upbcode" name="use_upbcode" value="1" style="vertical-align:middle;" onclick="document.getElementById('isAddURLs').disabled = !this.checked;"{if !$preview || $newReply.isBBCode} checked="checked"{/if} /> <label for="use_upbcode" class="norm">{Language::getInstance()->getString('enable_bbcode')}</label>{/if}{if $forum.isXHTML}<br />
   <input type="checkbox" id="use_htmlcode" name="use_htmlcode" value="1" style="vertical-align:middle;"{if $newReply.isXHTML} checked="checked"{/if} /> <label for="use_htmlcode" class="norm">{Language::getInstance()->getString('enable_xhtml')}</label>{/if}{if $forum.isBBCode}<br />
   <input type="checkbox" id="isAddURLs" name="isAddURLs" value="true" style="vertical-align:middle;"{if !$preview || $newReply.isAddURLs} checked="checked"{/if} /> <label for="isAddURLs" class="norm">{Language::getInstance()->getString('auto_transform_links')}</label>{/if}
  </td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('post_new_reply')}" />&nbsp;&nbsp;&nbsp;<input type="submit" name="preview" value="{Language::getInstance()->getString('preview')}" style="font-weight:bold;" />{plugin_hook hook=PlugIns::HOOK_TPL_POSTING_NEW_REPLY_BUTTONS}</p>
<input type="hidden" name="topic_id" value="{$topicID}" />
<input type="hidden" name="forum_id" value="{$forum.forumID}" />
</form>

<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{Language::getInstance()->getString('newest_replies')}</span></th></tr>
{foreach $lastReplies as $curReply}
 <tr>
  <td class="{cycle values="td1,td2" advance=false}" style="font-weight:bold; vertical-align:top; width:15%;"><span class="norm">{$curReply.nick}</span></td>
  <td class="{cycle values="td1,td2"}" style="vertical-align:top; width:85%;"><div class="norm">{$curReply.post}</div></td>
 </tr>
{/foreach}
</table>