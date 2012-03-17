<!-- Newsletter -->{if $modules.Auth->isAdmin()}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=newsletter&amp;mode=delete{$smarty.const.SID_AMPER}">{/if}
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr>
{if $modules.Auth->isAdmin()}  <th class="thsmall"><span class="thsmall"><input type="checkbox" onclick="negateBoxes('letter');" /></span></th>{/if}
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('date')}</span></th>
  <th class="thsmall" style="width:50%;"><span class="thsmall">{$modules.Language->getString('subject')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('author')}</span></th>
 </tr>{foreach $newsletter as $curNewsletter}
 <tr>
{if $modules.Auth->isAdmin()}  <td class="td2"><span class="norm"><input type="checkbox" name="deleteletter[]" value="{$curNewsletter.id}" /></span></td>{/if}
  <td class="td1" style="text-align:center;"><span class="norm">{$curNewsletter.date}</span></td>
  <td class="td2" style="width:50%;"><span class="norm"><a href="{$smarty.const.INDEXFILE}?faction=newsletter&amp;mode=read&amp;newsletter={$curNewsletter.id}{$smarty.const.SID_AMPER}">{$curNewsletter.subject}</a></span></td>
  <td class="td1"><span class="norm">{$curNewsletter.author}</span></td>
 </tr>
{foreachelse}
 <tr><td class="td1" colspan="{if $modules.Auth->isAdmin()}4{else}3{/if}" style="text-align:center;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('no_newsletter_to_display')}</span></td></tr>
{/foreach}
</table>{if $modules.Auth->isAdmin()}
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('delete_selected_newsletter')}" onclick="return confirm('{$modules.Language->getString('really_delete_selected_newsletter')}');" /></p>
</form>{/if}