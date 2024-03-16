<!-- AdminSmileyNewSmiley -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=new{if $smileyType == BBCode::SMILEY_TOPIC}t{elseif $smileyType == BBCode::SMILEY_ADMIN}a{/if}{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{Language::getInstance()->getString('add_new_smiley')}</span></th></tr>
{if $smileyType == BBCode::SMILEY_TOPIC}{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_SMILEY_ADD_TOPIC_SMILEY_FORM_START}{elseif $smileyType == BBCode::SMILEY_ADMIN}{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_SMILEY_ADD_ADMIN_SMILEY_FORM_START}{else}{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_SMILEY_ADD_SMILEY_FORM_START}{/if}
 <tr>
  <td class="td1" style="width:20%;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('address_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="smadress" value="{$newAddress}" /> <span class="small">{Language::getInstance()->getString('url_or_path')}</span></td>
 </tr>{if $smileyType != BBCode::SMILEY_TOPIC}
 <tr>
  <td class="td1" style="width:20%;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('synonym_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="synonym" value="{$newSynonym}" /></td>
 </tr>{/if}
{if $smileyType == BBCode::SMILEY_TOPIC}{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_SMILEY_ADD_TOPIC_SMILEY_FORM_END}{elseif $smileyType == BBCode::SMILEY_ADMIN}{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_SMILEY_ADD_ADMIN_SMILEY_FORM_END}{else}{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_SMILEY_ADD_SMILEY_FORM_END}{/if}
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('add_new_smiley')}" />{if $smileyType == BBCode::SMILEY_TOPIC}{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_SMILEY_ADD_TOPIC_SMILEY_BUTTONS}{elseif $smileyType == BBCode::SMILEY_ADMIN}{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_SMILEY_ADD_ADMIN_SMILEY_BUTTONS}{else}{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_SMILEY_ADD_SMILEY_BUTTONS}{/if}</p>
<input type="hidden" name="save" value="yes" />
</form>