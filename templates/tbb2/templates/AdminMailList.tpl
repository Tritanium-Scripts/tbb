{include file='AdminMenu.tpl'}
<!-- AdminMailList -->
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{$modules.Language->getString('email_list')}</span></th></tr>
 <tr><td class="cellError"><div class="fontNorm" style="color:#FF0000; font-weight:bold; padding:7px;"><img src="{$modules.Template->getTplDir()}images/icons/exclamation.png" alt="" class="imageIcon" />{$modules.Language->getString('this_list_may_not_be_distributed_to_third_party')}</div></td></tr>
 <tr><td class="cellStd"><textarea class="formTextArea" cols="40" rows="20" readonly="readonly">{"\n"|implode:$mailAddys}</textarea></td></tr>
</table>
{include file='AdminMenuTail.tpl'}