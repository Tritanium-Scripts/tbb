<!-- AdminLogfile -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=adminLogfile&amp;mode=delete{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr>
  <th class="thsmall"><span class="thsmall"><input type="checkbox" onclick="negateBoxes('log');" /></span></th>
  <th class="thsmall" colspan="2"><a href="{$smarty.const.INDEXFILE}?faction=adminLogfile&amp;sortMethod=byDate&amp;orderType={$orderTypeDate}{$smarty.const.SID_AMPER}"><span class="thsmall" style="text-decoration:underline;">{Language::getInstance()->getString('logfile')}</span></a></th>
  <th class="thsmall"><a href="{$smarty.const.INDEXFILE}?faction=adminLogfile&amp;sortMethod=bySize&amp;orderType={$orderTypeSize}{$smarty.const.SID_AMPER}"><span class="thsmall" style="text-decoration:underline;">{Language::getInstance()->getString('size')}</span></a></th>
  <th class="thsmall"><a href="{$smarty.const.INDEXFILE}?faction=adminLogfile&amp;sortMethod=byEntries&amp;orderType={$orderTypeEntries}{$smarty.const.SID_AMPER}"><span class="thsmall" style="text-decoration:underline;">{Language::getInstance()->getString('entries')}</span></a></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('last_change')}</span></th>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_LOGFILE_LOGS_TABLE_HEAD}
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('options')}</span></th>
 </tr>
{foreach $logfiles as $curLogfile}
 <tr>
  <td class="td1"><span class="norm"><input type="checkbox" name="deletelog[{$curLogfile.name}]" value="true"{if !$curLogfile.isDeletable} disabled="disabled"{/if} /></span></td>
  <td class="td2" style="text-align:right;"><span class="norm">{$curLogfile.weekday|utf8_encode}</span></td>
  <td class="td2"><span class="norm">{$curLogfile.date}</span></td>
  <td class="td1" style="text-align:right;"><span class="norm">{$curLogfile.size|string_format:Language::getInstance()->getString('x_kib')}</span></td>
  <td class="td2" style="text-align:right;"><span class="norm">{$curLogfile.entries}</span></td>
  <td class="td1" style="text-align:center;"><span class="small">{$curLogfile.lastChange|utf8_encode}</span></td>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_LOGFILE_LOGS_TABLE_BODY}
  <td class="td2" style="text-align:center;"><span class="small"><a href="{$smarty.const.INDEXFILE}?faction=adminLogfile&amp;mode=view&amp;log={$curLogfile.name}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('view')}</a> | {if $curLogfile.isDeletable}<a href="{$smarty.const.INDEXFILE}?faction=adminLogfile&amp;mode=delete&amp;log={$curLogfile.name}{$smarty.const.SID_AMPER}" onclick="return confirm('{Language::getInstance()->getString('really_delete_this_logfile')}');">{Language::getInstance()->getString('delete')}</a> | {/if}<a href="{$smarty.const.INDEXFILE}?faction=adminLogfile&amp;mode=download&amp;log={$curLogfile.name}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('download')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="td1" colspan="7" style="text-align:center;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('no_logfiles_found')}</span></td></tr>
{/foreach}
</table>
{if count($logfiles) > 0}<p style="text-align:center;"><input type="submit" name="multiDelete" value="{Language::getInstance()->getString('delete_selected_logfiles')}" />{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_LOGFILE_LOGS_OPTIONS}</p>{/if}
</form>