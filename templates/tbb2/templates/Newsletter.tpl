<!-- Newsletter -->{if $modules.Auth->isAdmin()}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=newsletter&amp;mode=delete{$smarty.const.SID_AMPER}">{/if}
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr>
{if $modules.Auth->isAdmin()}  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall"><input type="checkbox" onclick="negateBoxes('letter');" /></span></th>{/if}
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{$modules.Language->getString('date')}</span></th>
  <th class="cellTitle" style="text-align:center; width:50%;"><span class="fontTitleSmall">{$modules.Language->getString('subject')}</span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{$modules.Language->getString('author')}</span></th>
 </tr>{foreach $newsletter as $curNewsletter}
 <tr onmouseover="setRowCellsClass(this, 'cellHighlight');" onmouseout="restoreRowCellsClass(this);">{if $modules.Auth->isAdmin()}
  <td class="cellAlt" style="text-align:center;"><span class="fontNorm"><input type="checkbox" name="deleteletter[]" value="{$curNewsletter.id}" /></span></td>{/if}
  <td class="cellStd" style="text-align:center;"><span class="fontNorm">{$curNewsletter.date}</span></td>
  <td class="cellAlt" style="width:50%;"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=newsletter&amp;mode=read&amp;newsletter={$curNewsletter.id}{$smarty.const.SID_AMPER}">{$curNewsletter.subject}</a></span></td>
  <td class="cellStd"><span class="fontNorm">{$curNewsletter.author}</span></td>
 </tr>
{foreachelse}
 <tr><td class="cellStd" colspan="{if $modules.Auth->isAdmin()}4{else}3{/if}" style="text-align:center;"><span class="fontNorm" style="font-weight:bold;">{$modules.Language->getString('no_newsletter_to_display')}</span></td></tr>
{/foreach}
</table>{if $modules.Auth->isAdmin()}
<p class="cellButtons"><input class="formBButton" type="submit" value="{$modules.Language->getString('delete_selected_newsletter')}" onclick="return confirm('{$modules.Language->getString('really_delete_selected_newsletter')}');" /></p>
</form>{/if}