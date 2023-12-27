<!-- AdminMailList -->
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{Language::getInstance()->getString('email_list')}</span></th></tr>
 <tr><td class="td1"><p class="norm" style="background-color:#FFD1D1; border:2px solid #FF0000; color:#FF0000; font-weight:bold; padding:3px;">{Language::getInstance()->getString('this_list_may_not_be_distributed_to_third_party')}</p></td></tr>
 <tr><td class="td1"><textarea cols="40" rows="20" readonly="readonly">{"\n"|implode:$mailAddys}</textarea></td></tr>
</table>