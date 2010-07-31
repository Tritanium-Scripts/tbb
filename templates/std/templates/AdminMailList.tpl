<!-- AdminMailList -->
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{$modules.Language->getString('email_list')}</span></th></tr>
 <tr><td class="td1"><p class="norm" style="background-color:#FFD1D1; border:2px solid #FF0000; color:#FF0000; font-weight:bold; padding:3px;">{$modules.Language->getString('this_list_may_not_be_distributed_to_third_party')}</p></td></tr>
 <tr><td class="td1"><textarea cols="40" rows="20" readonly="readonly">{"\n"|implode:$mailAddys}</textarea></td></tr>
</table>