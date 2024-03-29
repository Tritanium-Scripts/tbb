{include file='AdminMenu.tpl'}
<!-- AdminTemplate -->{if isset($errors)}{if empty($errors)}
<table cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><td class="fontNorm" style="background-color:#D1FFD1; border:1px solid #00FF00; color:#00FF00; padding:5px;"><img src="{Template::getInstance()->getTplDir()}images/icons/tick.png" alt="" class="imageIcon" /> {Language::getInstance()->getString('no_errors_were_reported')}</td></tr>
</table>
<br />
{else}{include file='Errors.tpl'}{/if}{/if}
<form action="{$smarty.const.INDEXFILE}?faction=adminTemplate{$smarty.const.SID_AMPER}" method="post">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="6"><span class="fontTitle">{Language::getInstance()->getString('manage_templates')}</span></th></tr>
 <tr>
  <td class="cellCat" colspan="2"><span class="fontCat">{Language::getInstance()->getString('name_of_template')}</span></td>
  <td class="cellCat"><span class="fontCat">{Language::getInstance()->getString('author')}</span></td>
  <td class="cellCat"><span class="fontCat">{Language::getInstance()->getString('comment_of_author')}</span></td>
  <td class="cellCat"><span class="fontCat">{Language::getInstance()->getString('target_version')}</span></td>
  <td class="cellCat"><span class="fontCat">{Language::getInstance()->getString('default_style')}</span></td>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_TEMPLATE_TEMPLATES_TABLE_HEAD}
 </tr>{foreach $templates as $curTplID => $curTemplate}
 <tr>
  <td class="cellStd" style="width:1%;"><input type="radio" id="{$curTplID}" name="template" value="{$curTplID}"{if $curTplID == $defaultTplID} checked="checked"{/if} /></td>
  <td class="cellStd"><label for="{$curTplID}" class="fontNorm">{$curTemplate.name}</label></td>
  <td class="cellAlt"><span class="fontNorm"><a href="{$curTemplate.website}" target="_blank">{$curTemplate.author}</a></span></td>
  <td class="cellStd"><span class="fontNorm">{$curTemplate.comment}</span></td>
  <td class="cellAlt"><span class="fontNorm" style="color:{if version_compare($curTemplate.target, $smarty.const.VERSION_PRIVATE, '<')}red{else}green{/if};">{$curTemplate.target|trim_version}</span></td>
  <td class="cellStd"><select class="formSelect" name="styles[{$curTplID}]">{foreach $curTemplate.styles as $curStyle}<option value="{$curStyle}"{if ($curTplID == $defaultTplID && $curStyle == $defaultStyle) || ($curTplID != $defaultTplID && $curStyle == $curTemplate.style)} selected="selected"{/if}>{$curStyle}</option>{/foreach}</select></td>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_TEMPLATE_TEMPLATES_TABLE_BODY}
 </tr>{/foreach}
 <tr><td class="cellCat" colspan="6"><span class="fontCat">{Language::getInstance()->getString('template_settings')}</span></td></tr>
 <tr><td class="cellStd" colspan="6"><input type="checkbox" value="true" id="isTplSelectable" name="isTplSelectable"{if Config::getInstance()->getCfgVal('select_tpls')} checked="checked"{/if} /> <label for="isTplSelectable" class="fontNorm">{Language::getInstance()->getString('members_may_select_other_templates')}</label></td></tr>
 <tr><td class="cellStd" colspan="6"><input type="checkbox" value="true" id="isStyleSelectable" name="isStyleSelectable"{if Config::getInstance()->getCfgVal('select_styles')} checked="checked"{/if} /> <label for="isStyleSelectable" class="fontNorm">{Language::getInstance()->getString('members_may_select_other_styles')}</label></td></tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('update_template_configuration')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="submit" name="testInstall" value="{Language::getInstance()->getString('test_template_installation')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" />{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_TEMPLATE_TEMPLATES_BUTTONS}</p>
<input type="hidden" name="update" value="true" />
</form>
{include file='AdminMenuTail.tpl'}