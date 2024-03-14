<!-- Newsletter -->{if Auth::getInstance()->isAdmin()}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=newsletter&amp;mode=delete{$smarty.const.SID_AMPER}">{/if}
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr>
{if Auth::getInstance()->isAdmin()}  <th class="thsmall"><span class="thsmall"><input type="checkbox" onclick="negateBoxes('letter');" /></span></th>{/if}
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('date')}</span></th>
  <th class="thsmall" style="width:50%;"><span class="thsmall">{Language::getInstance()->getString('subject')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('author')}</span></th>
{plugin_hook hook=PlugIns::HOOK_TPL_NEWSLETTER_LETTERS_TABLE_HEAD}
 </tr>{foreach $newsletter as $curNewsletter}
 <tr>
{if Auth::getInstance()->isAdmin()}  <td class="td2"><span class="norm"><input type="checkbox" name="deleteletter[]" value="{$curNewsletter.id}" /></span></td>{/if}
  <td class="td1" style="text-align:center;"><span class="norm">{$curNewsletter.date}</span></td>
  <td class="td2" style="width:50%;"><span class="norm"><a href="{$smarty.const.INDEXFILE}?faction=newsletter&amp;mode=read&amp;newsletter={$curNewsletter.id}{$smarty.const.SID_AMPER}">{$curNewsletter.subject}</a></span></td>
  <td class="td1"><span class="norm">{$curNewsletter.author}</span></td>
{plugin_hook hook=PlugIns::HOOK_TPL_NEWSLETTER_LETTERS_TABLE_BODY}
 </tr>
{foreachelse}
 <tr><td class="td1" colspan="{if Auth::getInstance()->isAdmin()}4{else}3{/if}" style="text-align:center;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('no_newsletter_to_display')}</span></td></tr>
{/foreach}
</table>{if Auth::getInstance()->isAdmin()}
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('delete_selected_newsletter')}" onclick="return confirm('{Language::getInstance()->getString('really_delete_selected_newsletter')}');" />{plugin_hook hook=PlugIns::HOOK_TPL_NEWSLETTER_LETTERS_BUTTONS}</p>
</form>{/if}