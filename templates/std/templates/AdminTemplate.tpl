<!-- AdminTemplate -->{if isset($errors)}{if empty($errors)}
<table cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="background-color:#D1FFD1; border:2px solid #00FF00; padding:3px; width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><td class="norm" style="color:#00FF00;">{Language::getInstance()->getString('no_errors_were_reported')}</td></tr>
</table>
<br />
{else}{include file='Errors.tpl'}{/if}{/if}
<form action="{$smarty.const.INDEXFILE}?faction=adminTemplate{$smarty.const.SID_AMPER}" method="post">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="6"><span class="thnorm">{Language::getInstance()->getString('manage_templates')}</span></th></tr>
 <tr>
  <td class="kat" colspan="2"><span class="kat">{Language::getInstance()->getString('name_of_template')}</span></td>
  <td class="kat"><span class="kat">{Language::getInstance()->getString('author')}</span></td>
  <td class="kat"><span class="kat">{Language::getInstance()->getString('comment_of_author')}</span></td>
  <td class="kat"><span class="kat">{Language::getInstance()->getString('target_version')}</span></td>
  <td class="kat"><span class="kat">{Language::getInstance()->getString('default_style')}</span></td>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_TEMPLATE_TEMPLATES_TABLE_HEAD}
 </tr>{foreach $templates as $curTplID => $curTemplate}
 <tr>
  <td class="td1" style="width:1%;"><input type="radio" id="{$curTplID}" name="template" value="{$curTplID}"{if $curTplID == $defaultTplID} checked="checked"{/if} /></td>
  <td class="td1"><label for="{$curTplID}" class="norm">{$curTemplate.name}</label></td>
  <td class="td2"><span class="norm"><a href="{$curTemplate.website}" target="_blank">{$curTemplate.author}</a></span></td>
  <td class="td1"><span class="small">{$curTemplate.comment}</span></td>
  <td class="td2"><span class="norm" style="color:{if version_compare($curTemplate.target, $smarty.const.VERSION_PRIVATE, '<')}red{else}green{/if};">{$curTemplate.target|trim_version}</span></td>
  <td class="td1"><select name="styles[{$curTplID}]" class="norm">{foreach $curTemplate.styles as $curStyle}<option value="{$curStyle}"{if ($curTplID == $defaultTplID && $curStyle == $defaultStyle) || ($curTplID != $defaultTplID && $curStyle == $curTemplate.style)} selected="selected"{/if}>{$curStyle}</option>{/foreach}</select></td>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_TEMPLATE_TEMPLATES_TABLE_BODY}
 </tr>{/foreach}
 <tr><td class="kat" colspan="6"><span class="kat">{Language::getInstance()->getString('template_settings')}</span></td></tr>
 <tr><td class="td1" colspan="6"><input type="checkbox" value="true" id="isTplSelectable" name="isTplSelectable" style="vertical-align:middle;"{if Config::getInstance()->getCfgVal('select_tpls')} checked="checked"{/if} /> <label for="isTplSelectable" class="norm">{Language::getInstance()->getString('members_may_select_other_templates')}</label></td></tr>
 <tr><td class="td1" colspan="6"><input type="checkbox" value="true" id="isStyleSelectable" name="isStyleSelectable" style="vertical-align:middle;"{if Config::getInstance()->getCfgVal('select_styles')} checked="checked"{/if} /> <label for="isStyleSelectable" class="norm">{Language::getInstance()->getString('members_may_select_other_styles')}</label></td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('update_template_configuration')}" />&nbsp;&nbsp;&nbsp;<input type="submit" name="testInstall" value="{Language::getInstance()->getString('test_template_installation')}" />{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_TEMPLATE_TEMPLATES_BUTTONS}</p>
<input type="hidden" name="update" value="true" />
</form>