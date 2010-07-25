<!-- PostReply -->
{if $preview}
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{$modules.Language->getString('preview')}</span></th></tr>
 <tr><td class="td1"><span class="norm">{$newReply.preview.post}{if $newReply.isSignature}<br /><br />-----------------------<br />{$newReply.preview.signature}{/if}</span></td></tr>
</table>
<br />
{else}{include file='Errors.tpl'}{/if}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=reply&amp;mode=save{$smarty.const.SID_AMPER}" name="beitrag">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{$modules.Language->getString('post_new_reply')}</span></th></tr>{if !$modules.Auth->isLoggedIn()}
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('your_name_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="nli_name" value="{$newReply.nick}" /></td>
 </tr>{/if}
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('post_icon_colon')}</span></td>
  <td class="td1" style="vertical-align:top; width:80%;">{include file='TopicSmilies.tpl' checked=$newReply.tSmileyID}</td>
 </tr>{if $forum.isBBCode}
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top; width:20%;"><span class="norm">{$modules.Language->getString('bbcode_colon')}</span></td>
  <td class="td1" style="width:80%;">{include file='BBCodes.tpl'}</td>
 </tr>{/if}
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top;"><span class="norm">{$modules.Language->getString('post_colon')}</span><br /><br />{include file='Smilies.tpl' targetBoxID='post'}</td>
  <td class="td1" style="width:80%;"><textarea id="post" name="post" rows="10" cols="60">{$newReply.post}</textarea></td>
 </tr>{if $modules.Config->getCfgVal('tspacing') < 1}
 <tr><td class="td1" colspan="2"><hr /></td></tr>{/if}
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top; width:20%;"><span class="norm">{$modules.Language->getString('options_colon')}</span></td>
  <td class="td1" style="width:80%;">
   <input type="checkbox" id="smilies" name="smilies" value="1" style="vertical-align:middle;"{if !$preview || $newReply.isSmilies} checked="checked"{/if} /> <label for="smilies" class="norm">{$modules.Language->getString('enable_smilies')}</label>{if $modules.Auth->isLoggedIn()}<br />
   <input type="checkbox" id="show_signatur" name="show_signatur" value="1" style="vertical-align:middle;"{if !$preview || $newReply.isSignature} checked="checked"{/if} /> <label for="show_signatur" class="norm">{$modules.Language->getString('show_signature')}</label>{/if}{if $forum.isBBCode}<br />
   <input type="checkbox" id="use_upbcode" name="use_upbcode" value="1" style="vertical-align:middle;"{if !$preview || $newReply.isBBCode} checked="checked"{/if} /> <label for="use_upbcode" class="norm">{$modules.Language->getString('enable_bbcode')}</label>{/if}{if $forum.isXHTML}<br />
   <input type="checkbox" id="use_htmlcode" name="use_htmlcode" value="1" style="vertical-align:middle;"{if $newReply.isXHTML} checked="checked"{/if} /> <label for="use_htmlcode" class="norm">{$modules.Language->getString('enable_xhtml')}</label>{/if}<br />
   <input type="checkbox" id="isAddURLs" name="isAddURLs" value="true" style="vertical-align:middle;"{if !$preview || $newReply.isAddURLs} checked="checked"{/if} /> <label for="isAddURLs" class="norm">{$modules.Language->getString('auto_transform_links')}</label>
  </td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('post_new_reply')}" />&nbsp;&nbsp;&nbsp;<input type="submit" name="preview" value="{$modules.Language->getString('preview')}" style="font-weight:bold;" /></p>
<input type="hidden" name="thread_id" value="{$topicID}" />
<input type="hidden" name="forum_id" value="{$forum.forumID}" />
</form>

<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{$modules.Language->getString('newest_replies')}</span></th></tr>
{foreach $lastReplies as $curReply}
 <tr>
  <td class="{cycle values="td1,td2" advance=false}" style="font-weight:bold; vertical-align:top; width:15%;"><span class="norm">{$curReply.nick}</span></td>
  <td class="{cycle values="td1,td2"}" style="vertical-align:top; width:85%;"><span class="norm">{$curReply.post}</span></td>
 </tr>
{/foreach}
</table>