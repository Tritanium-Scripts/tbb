<!-- PostNewTopic -->
{if $preview}
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{$modules.Language->getString('preview')}</span></th></tr>
 <tr><td class="td1"><span class="norm">$preview_post.$signatur</span></td></tr>
</table>
<br />
{else}{include file='Errors.tpl'}{/if}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=newtopic{$smarty.const.SID_AMPER}" name="beitrag">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{$modules.Language->getString('post_new_topic')}</span></th></tr>{if !$modules.Auth->isLoggedIn()}
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('your_name_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="nli_name" value="$nli_name" /></td>
 </tr>{/if}
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('post_icon_colon')}</span></td>
  <td class="td1" style="vertical-align:top; width:100%;">tsmilies</td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('title_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" size="30" name="title" value="$title" /></td>
 </tr>{if $forum[7][0] == '1'}
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top; width:20%;"><span class="norm">{$modules.Language->getString('bbcode_colon')}</span></td>
  <td class="td1" style="width:80%;">bbcodes</td>
 </tr>{/if}
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top;"><span class="norm">{$modules.Language->getString('post_colon')}</span><br /><br />{include file='Smilies.tpl' targetBoxID='post'}</td>
  <td class="td1" style="width:80%;"><textarea id="post" name="post" rows="10" cols="60">$post</textarea></td>
 </tr>{if $modules.Config->getCfgVal('tspacing') < 1}
 <tr><td class="td1" colspan="2"><hr /></td></tr>{/if}
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top; width:20%;"><span class="norm">{$modules.Language->getString('options_colon')}</span></td>
  <td class="td1" style="width:80%;">
   <input type="checkbox" id="smilies" name="smilies" value="1" style="vertical-align:middle;"{if !$preview} checked="checked"{/if} /> <label for="smilies" class="norm">{$modules.Language->getString('enable_smilies')}</label>{if $modules.Auth->isLoggedIn()}<br />
   <input type="checkbox" id="show_signatur" name="show_signatur" value="1" style="vertical-align:middle;"{if !$preview} checked="checked"{/if} /> <label for="show_signatur" class="norm">{$modules.Language->getString('show_signature')}</label>{/if}{if $forum[7][0] == '1'}<br />
   <input type="checkbox" id="use_upbcode" name="use_upbcode" value="1" style="vertical-align:middle;"{if !$preview} checked="checked"{/if} /> <label for="use_upbcode" class="norm">{$modules.Language->getString('enable_bbcode')}</label>{/if}{if $forum[7][1] == '1'}<br />
   <input type="checkbox" id="use_htmlcode" name="use_htmlcode" value="1" style="vertical-align:middle;"{if !$preview} checked="checked"{/if} /> <label for="use_htmlcode" class="norm">{$modules.Language->getString('enable_xhtml')}</label>{/if}{if $modules.Config->getCfgVal('activate_mail') == 1 && $modules.Config->getCfgVal('notify_new_replies') == 1 && $modules.Auth->isLoggedIn()}<br />
   <input type="checkbox" id="sendmail2" name="sendmail2" value="1" style="vertical-align:middle;"{if !$preview} checked="checked"{/if} /> <label for="sendmail2" class="norm">{$modules.Language->getString('notify_on_new_reply')}</label>{/if}
  </td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('post_new_topic')}" />&nbsp;&nbsp;&nbsp;<input type="submit" name="preview" value="{$modules.Language->getString('preview')}" style="font-weight:bold;" /></p>
<input type="hidden" name="save" value="yes" />
<input type="hidden" name="forum_id" value="{$forum[0]}" />
</form>