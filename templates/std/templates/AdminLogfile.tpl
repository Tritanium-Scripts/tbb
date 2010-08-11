<!-- AdminLogfile -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=adminLogfile&amp;mode=delete{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr>
  <th class="thsmall"><span class="thsmall"><input type="checkbox" onclick="negateBoxes('log');" /></span></th>
  <th class="thsmall" colspan="2"><a href="{$smarty.const.INDEXFILE}?faction=adminLogfile&amp;sortMethod=byDate&amp;orderType={$orderTypeDate}{$smarty.const.SID_AMPER}"><span class="thsmall" style="text-decoration:underline;">{$modules.Language->getString('logfile')}</span></a></th>
  <th class="thsmall"><a href="{$smarty.const.INDEXFILE}?faction=adminLogfile&amp;sortMethod=bySize&amp;orderType={$orderTypeSize}{$smarty.const.SID_AMPER}"><span class="thsmall" style="text-decoration:underline;">{$modules.Language->getString('size')}</span></a></th>
  <th class="thsmall"><a href="{$smarty.const.INDEXFILE}?faction=adminLogfile&amp;sortMethod=byEntries&amp;orderType={$orderTypeEntries}{$smarty.const.SID_AMPER}"><span class="thsmall" style="text-decoration:underline;">{$modules.Language->getString('entries')}</span></a></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('last_change')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('options')}</span></th>
 </tr>
{foreach $logfiles as $curLogfile}
 <tr>
  <td class="td1"><span class="norm"><input type="checkbox" name="deletelog[{$curLogfile.name}]" value="true"{if !$curLogfile.isDeletable} disabled="disabled"{/if} /></span></td>
  <td class="td2" style="text-align:right;"><span class="norm">{$curLogfile.weekday}</span></td>
  <td class="td2"><span class="norm">{$curLogfile.date}</span></td>
  <td class="td1" style="text-align:right;"><span class="norm">{$curLogfile.size|string_format:$modules.Language->getString('x_kib')}</span></td>
  <td class="td2" style="text-align:right;"><span class="norm">{$curLogfile.entries}</span></td>
  <td class="td1" style="text-align:center;"><span class="small">{$curLogfile.lastChange}</span></td>
  <td class="td2" style="text-align:center;"><span class="small"><a href="{$smarty.const.INDEXFILE}?faction=adminLogfile&amp;mode=view&amp;log={$curLogfile.name}{$smarty.const.SID_AMPER}">{$modules.Language->getString('view')}</a> | {if $curLogfile.isDeletable}<a href="{$smarty.const.INDEXFILE}?faction=adminLogfile&amp;mode=delete&amp;log={$curLogfile.name}{$smarty.const.SID_AMPER}" onclick="return confirm('{$modules.Language->getString('really_delete_this_logfile')}');">{$modules.Language->getString('delete')}</a> | {/if}<a href="{$smarty.const.INDEXFILE}?faction=adminLogfile&amp;mode=download&amp;log={$curLogfile.name}{$smarty.const.SID_AMPER}">{$modules.Language->getString('download')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="td1" colspan="7" style="text-align:center;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('no_logfiles_found')}</span></td></tr>
{/foreach}
</table>
{if count($logfiles) > 0}<p style="text-align:center;"><input type="submit" name="multiDelete" value="{$modules.Language->getString('delete_selected_logfiles')}" /></p>{/if}
</form>