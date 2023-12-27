<!-- AdminLogfileViewLog -->
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{$date|string_format:Language::getInstance()->getString('view_logfile_from_x')}</span></th></tr>
 <tr><td class="td1"><pre style="font-size:11px;">{"\n"|implode:$logfile}</pre></td></tr>
</table>