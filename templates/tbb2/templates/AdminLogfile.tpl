{include file='AdminMenu.tpl'}
<!-- AdminLogfile -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=adminLogfile&amp;mode=delete{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="6"><span class="fontTitle">{$modules.Language->getString('manage_logfiles')}</span></th></tr>
 <tr>
  <th class="cellCat" style="text-align:center;"><span class="fontCat"><input type="checkbox" onclick="negateBoxes('log');" /></span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat"><a href="{$smarty.const.INDEXFILE}?faction=adminLogfile&amp;sortMethod=byDate&amp;orderType={$orderTypeDate}{$smarty.const.SID_AMPER}">{$modules.Language->getString('logfile')}</a></span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat"><a href="{$smarty.const.INDEXFILE}?faction=adminLogfile&amp;sortMethod=bySize&amp;orderType={$orderTypeSize}{$smarty.const.SID_AMPER}">{$modules.Language->getString('size')}</a></span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat"><a href="{$smarty.const.INDEXFILE}?faction=adminLogfile&amp;sortMethod=byEntries&amp;orderType={$orderTypeEntries}{$smarty.const.SID_AMPER}">{$modules.Language->getString('entries')}</a></span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('last_change')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('options')}</span></th>
 </tr>
{foreach $logfiles as $curLogfile}
 <tr>
  <td class="cellAlt" style="text-align:center;"><span class="fontNorm"><input type="checkbox" name="deletelog[{$curLogfile.name}]" value="true"{if !$curLogfile.isDeletable} disabled="disabled"{/if} /></span></td>
  <td class="cellStd">
   <table cellpadding="0" cellspacing="0" style="width:100%;">
   <colgroup>
    <col width="50%" />
    <col width="50%" />
   </colgroup>
    <tr>
     <td style="padding-right:3px; text-align:right;"><span class="fontNorm">{$curLogfile.weekday}</span></td>
     <td style="padding-left:3px;"><span class="fontNorm">{$curLogfile.date}</span></td>
    </tr>
   </table>
  </td>
  <td class="cellAlt" style="text-align:right;"><span class="fontNorm">{$curLogfile.size|string_format:$modules.Language->getString('x_kib')}</span></td>
  <td class="cellStd" style="text-align:right;"><span class="fontNorm">{$curLogfile.entries}</span></td>
  <td class="cellAlt" style="text-align:center;"><span class="fontSmall">{$curLogfile.lastChange}</span></td>
  <td class="cellStd" style="text-align:center;"><span class="fontSmall"><a href="{$smarty.const.INDEXFILE}?faction=adminLogfile&amp;mode=view&amp;log={$curLogfile.name}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/icons/report.png" alt="{$modules.Language->getString('view')}" style="vertical-align:middle;" /> {$modules.Language->getString('view')}</a> | {if $curLogfile.isDeletable}<a href="{$smarty.const.INDEXFILE}?faction=adminLogfile&amp;mode=delete&amp;log={$curLogfile.name}{$smarty.const.SID_AMPER}" onclick="return confirm('{$modules.Language->getString('really_delete_this_logfile')}');"><img src="{$modules.Template->getTplDir()}images/icons/report_delete.png" alt="{$modules.Language->getString('delete')}" style="vertical-align:middle;" /> {$modules.Language->getString('delete')}</a> | {/if}<a href="{$smarty.const.INDEXFILE}?faction=adminLogfile&amp;mode=download&amp;log={$curLogfile.name}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/icons/report_disk.png" alt="{$modules.Language->getString('download')}" style="vertical-align:middle;" /> {$modules.Language->getString('download')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="cellStd" colspan="7" style="text-align:center;"><span class="fontNorm" style="font-weight:bold;">{$modules.Language->getString('no_logfiles_found')}</span></td></tr>
{/foreach}
</table>
{if count($logfiles) > 0}<p class="cellButtons"><input class="formBButton" type="submit" name="multiDelete" value="{$modules.Language->getString('delete_selected_logfiles')}" /></p>{/if}
</form>
{include file='AdminMenuTail.tpl'}