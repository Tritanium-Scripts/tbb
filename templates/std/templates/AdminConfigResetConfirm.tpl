<!-- AdminConfigResetConfirm -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_settings&amp;mode=readsetfile{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{Language::getInstance()->getString('reset_settings')}</span></th></tr>
 <tr><td class="td1" style="text-align:center;"><p><span class="norm">{Language::getInstance()->getString('really_reset_settings')}</span></p></td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('reset_settings')}" />{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_CONFIG_RESET_SETTINGS_BUTTONS}</p>
<input type="hidden" name="confirm" value="1" />
</form>