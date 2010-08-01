<!-- AdminLogfileViewLog -->
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{$date|string_format:$modules.Language->getString('view_logfile_from_x')}</span></th></tr>
 <tr><td class="td1"><pre style="font-size:11px;">{"\n"|implode:$logfile}</pre></td></tr>
</table>