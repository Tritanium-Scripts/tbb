{include file='AdminMenu.tpl'}
<!-- AdminSmileyEditSmiley -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=edit{if $smileyType == BBCode::SMILEY_TOPIC}t{elseif $smileyType == BBCode::SMILEY_ADMIN}a{/if}&amp;id={$smileyID}{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <colgroup>
  <col width="20%" />
  <col width="80%" />
 </colgroup>
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('edit_smiley')}</span></th></tr>
{if $smileyType == BBCode::SMILEY_TOPIC}{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_SMILEY_EDIT_TOPIC_SMILEY_FORM_START}{elseif $smileyType == BBCode::SMILEY_ADMIN}{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_SMILEY_EDIT_ADMIN_SMILEY_FORM_START}{else}{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_SMILEY_EDIT_SMILEY_FORM_START}{/if}
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('smiley_colon')}</span></td>
  <td class="cellAlt"><img src="{$editAddress}" alt="{$editSynonym}" /></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('address_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="picadress" value="{$editAddress}" size="50" /> <span class="fontSmall">{Language::getInstance()->getString('url_or_path')}</span></td>
 </tr>{if $smileyType != BBCode::SMILEY_TOPIC}
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('synonym_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="synonym" value="{$editSynonym}" /></td>
 </tr>{/if}
{if $smileyType == BBCode::SMILEY_TOPIC}{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_SMILEY_EDIT_TOPIC_SMILEY_FORM_END}{elseif $smileyType == BBCode::SMILEY_ADMIN}{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_SMILEY_EDIT_ADMIN_SMILEY_FORM_END}{else}{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_SMILEY_EDIT_SMILEY_FORM_END}{/if}
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('edit_smiley')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" />{if $smileyType == BBCode::SMILEY_TOPIC}{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_SMILEY_EDIT_TOPIC_SMILEY_BUTTONS}{elseif $smileyType == BBCode::SMILEY_ADMIN}{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_SMILEY_EDIT_ADMIN_SMILEY_BUTTONS}{else}{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_SMILEY_EDIT_SMILEY_BUTTONS}{/if}</p>
<input type="hidden" name="save" value="yes" />
</form>
{include file='AdminMenuTail.tpl'}