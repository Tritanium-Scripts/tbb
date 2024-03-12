{include file='AdminMenu.tpl'}
<!-- AdminLogfile -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=adminLogfile&amp;mode=delete{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="6"><span class="fontTitle">{Language::getInstance()->getString('manage_logfiles')}</span></th></tr>
 <tr>
  <th class="cellCat" style="text-align:center;"><span class="fontCat"><input type="checkbox" onclick="negateBoxes('log');" /></span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat"><a href="{$smarty.const.INDEXFILE}?faction=adminLogfile&amp;sortMethod=byDate&amp;orderType={$orderTypeDate}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('logfile')}</a></span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat"><a href="{$smarty.const.INDEXFILE}?faction=adminLogfile&amp;sortMethod=bySize&amp;orderType={$orderTypeSize}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('size')}</a></span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat"><a href="{$smarty.const.INDEXFILE}?faction=adminLogfile&amp;sortMethod=byEntries&amp;orderType={$orderTypeEntries}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('entries')}</a></span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('last_change')}</span></th>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_LOGFILE_LOGS_TABLE_HEAD}
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('options')}</span></th>
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
  <td class="cellAlt" style="text-align:right;"><span class="fontNorm">{$curLogfile.size|string_format:Language::getInstance()->getString('x_kib')}</span></td>
  <td class="cellStd" style="text-align:right;"><span class="fontNorm">{$curLogfile.entries}</span></td>
  <td class="cellAlt" style="text-align:center;"><span class="fontSmall">{$curLogfile.lastChange}</span></td>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_LOGFILE_LOGS_TABLE_BODY}
  <td class="cellStd" style="text-align:center;"><span class="fontSmall"><a href="{$smarty.const.INDEXFILE}?faction=adminLogfile&amp;mode=view&amp;log={$curLogfile.name}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/icons/report.png" alt="{Language::getInstance()->getString('view')}" style="vertical-align:middle;" /> {Language::getInstance()->getString('view')}</a> | {if $curLogfile.isDeletable}<a href="{$smarty.const.INDEXFILE}?faction=adminLogfile&amp;mode=delete&amp;log={$curLogfile.name}{$smarty.const.SID_AMPER}" onclick="return confirm('{Language::getInstance()->getString('really_delete_this_logfile')}');"><img src="{Template::getInstance()->getTplDir()}images/icons/report_delete.png" alt="{Language::getInstance()->getString('delete')}" style="vertical-align:middle;" /> {Language::getInstance()->getString('delete')}</a> | {/if}<a href="{$smarty.const.INDEXFILE}?faction=adminLogfile&amp;mode=download&amp;log={$curLogfile.name}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/icons/report_disk.png" alt="{Language::getInstance()->getString('download')}" style="vertical-align:middle;" /> {Language::getInstance()->getString('download')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="cellStd" colspan="7" style="text-align:center;"><span class="fontNorm" style="font-weight:bold;">{Language::getInstance()->getString('no_logfiles_found')}</span></td></tr>
{/foreach}
</table>
{if count($logfiles) > 0}<p class="cellButtons"><input class="formBButton" type="submit" name="multiDelete" value="{Language::getInstance()->getString('delete_selected_logfiles')}" />{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_LOGFILE_LOGS_OPTIONS}</p>{/if}
</form>
{include file='AdminMenuTail.tpl'}