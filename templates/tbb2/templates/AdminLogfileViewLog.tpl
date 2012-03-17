{include file='AdminMenu.tpl'}
<!-- AdminLogfileViewLog -->
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{$date|string_format:$modules.Language->getString('view_logfile_from_x')}</span></th></tr>
 <tr><td class="cellStd"><pre style="font-size:11px;">{"\n"|implode:$logfile}</pre></td></tr>
</table>
{include file='AdminMenuTail.tpl'}