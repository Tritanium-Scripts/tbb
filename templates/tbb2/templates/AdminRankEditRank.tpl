{include file='AdminMenu.tpl'}
<!-- AdminRankEditRank -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_rank&amp;id={$rankID}&amp;mode=edit{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <colgroup>
  <col width="20%" />
  <col width="80%" />
 </colgroup>
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('edit_rank')}</span></th></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_RANK_EDIT_RANK_FORM_START}
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('rank_name_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="bez" value="{$rankName}" /></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('required_posts_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="minposts" value="{$requiredPosts}" /></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('number_of_stars_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" size="2" name="pic" value="{$stars}" /></td>
 </tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_RANK_EDIT_RANK_FORM_END}
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('edit_rank')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" />{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_RANK_EDIT_RANK_BUTTONS}</p>
<input type="hidden" name="save" value="yes" />
</form>
{include file='AdminMenuTail.tpl'}