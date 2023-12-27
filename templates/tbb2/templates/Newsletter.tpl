<!-- Newsletter -->{if Auth::getInstance()->isAdmin()}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=newsletter&amp;mode=delete{$smarty.const.SID_AMPER}">{/if}
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr>
{if Auth::getInstance()->isAdmin()}  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall"><input type="checkbox" onclick="negateBoxes('letter');" /></span></th>{/if}
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{Language::getInstance()->getString('date')}</span></th>
  <th class="cellTitle" style="text-align:center; width:50%;"><span class="fontTitleSmall">{Language::getInstance()->getString('subject')}</span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{Language::getInstance()->getString('author')}</span></th>
 </tr>{foreach $newsletter as $curNewsletter}
 <tr onmouseover="setRowCellsClass(this, 'cellHighlight');" onmouseout="restoreRowCellsClass(this);">{if Auth::getInstance()->isAdmin()}
  <td class="cellAlt" style="text-align:center;"><span class="fontNorm"><input type="checkbox" name="deleteletter[]" value="{$curNewsletter.id}" /></span></td>{/if}
  <td class="cellStd" style="text-align:center;"><span class="fontNorm">{$curNewsletter.date}</span></td>
  <td class="cellAlt" style="width:50%;"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=newsletter&amp;mode=read&amp;newsletter={$curNewsletter.id}{$smarty.const.SID_AMPER}">{$curNewsletter.subject}</a></span></td>
  <td class="cellStd"><span class="fontNorm">{$curNewsletter.author}</span></td>
 </tr>
{foreachelse}
 <tr><td class="cellStd" colspan="{if Auth::getInstance()->isAdmin()}4{else}3{/if}" style="text-align:center;"><span class="fontNorm" style="font-weight:bold;">{Language::getInstance()->getString('no_newsletter_to_display')}</span></td></tr>
{/foreach}
</table>{if Auth::getInstance()->isAdmin()}
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('delete_selected_newsletter')}" onclick="return confirm('{Language::getInstance()->getString('really_delete_selected_newsletter')}');" /></p>
</form>{/if}