{include file='AdminMenu.tpl'}
<!-- AdminTemplate -->{if isset($errors)}{if empty($errors)}
<table cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><td class="fontNorm" style="background-color:#D1FFD1; border:1px solid #00FF00; color:#00FF00; padding:5px;"><img src="{$modules.Template->getTplDir()}images/icons/tick.png" alt="" class="imageIcon" /> {$modules.Language->getString('no_errors_were_reported')}</td></tr>
</table>
<br />
{else}{include file='Errors.tpl'}{/if}{/if}
<form action="{$smarty.const.INDEXFILE}?faction=adminTemplate" method="post">
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="6"><span class="fontTitle">{$modules.Language->getString('manage_templates')}</span></th></tr>
 <tr>
  <td class="cellCat" colspan="2"><span class="fontCat">{$modules.Language->getString('name_of_template')}</span></td>
  <td class="cellCat"><span class="fontCat">{$modules.Language->getString('author')}</span></td>
  <td class="cellCat"><span class="fontCat">{$modules.Language->getString('comment_of_author')}</span></td>
  <td class="cellCat"><span class="fontCat">{$modules.Language->getString('target_version')}</span></td>
  <td class="cellCat"><span class="fontCat">{$modules.Language->getString('default_style')}</span></td>
 </tr>{foreach $templates as $curTplID => $curTemplate}
 <tr>
  <td class="cellStd" style="width:1%;"><input type="radio" id="{$curTplID}" name="template" value="{$curTplID}"{if $curTplID == $defaultTplID} checked="checked"{/if} /></td>
  <td class="cellStd"><label for="{$curTplID}" class="fontNorm">{$curTemplate.name}</label></td>
  <td class="cellAlt"><span class="fontNorm"><a href="{$curTemplate.website}" target="_blank">{$curTemplate.author}</a></span></td>
  <td class="cellStd"><span class="fontNorm">{$curTemplate.comment}</span></td>
  <td class="cellAlt"><span class="fontNorm" style="color:{if version_compare($curTemplate.target, $smarty.const.VERSION_PRIVATE, '<')}red{else}green{/if};">{$curTemplate.target|rtrim:'.0'}</span></td>
  <td class="cellStd"><select class="formSelect" name="styles[{$curTplID}]">{foreach $curTemplate.styles as $curStyle}<option value="{$curStyle}"{if ($curTplID == $defaultTplID && $curStyle == $defaultStyle) || ($curTplID != $defaultTplID && $curStyle == $curTemplate.style)} selected="selected"{/if}>{$curStyle}</option>{/foreach}</select></td>
 </tr>{/foreach}
 <tr><td class="cellCat" colspan="6"><span class="fontCat">{$modules.Language->getString('template_settings')}</span></td></tr>
 <tr><td class="cellStd" colspan="6"><input type="checkbox" value="true" id="isTplSelectable" name="isTplSelectable"{if $modules.Config->getCfgVal('select_tpls')} checked="checked"{/if} /> <label for="isTplSelectable" class="fontNorm">{$modules.Language->getString('members_may_select_other_templates')}</label></td></tr>
 <tr><td class="cellStd" colspan="6"><input type="checkbox" value="true" id="isStyleSelectable" name="isStyleSelectable"{if $modules.Config->getCfgVal('select_styles')} checked="checked"{/if} /> <label for="isStyleSelectable" class="fontNorm">{$modules.Language->getString('members_may_select_other_styles')}</label></td></tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{$modules.Language->getString('update_template_configuration')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="submit" name="testInstall" value="{$modules.Language->getString('test_template_installation')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" /></p>
<input type="hidden" name="update" value="true" />
</form>
{include file='AdminMenuTail.tpl'}