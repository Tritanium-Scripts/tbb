<!-- AdminGroupNewGroup -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_groups&amp;mode=new{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{Language::getInstance()->getString('add_new_group')}</span></th></tr>
 <tr>
  <td class="td1" style="width:20%;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('name_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="title" value="{$newName}" /></td>
 </tr>
 <tr>
  <td class="td1" style="width:20%;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('color_colon')}</span><br /><span class="small">{Language::getInstance()->getString('color_description')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="color" value="{$newColor}" onchange="this.style.color = this.value;" /></td>
 </tr>
 <tr>
  <td class="td1" style="width:20%;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('avatar_colon')}</span><br /><span class="small">{Language::getInstance()->getString('avatar_description')}</span></td>
  <td class="td1" style="vertical-align:top; width:80%;"><input type="text" name="pic" value="{$newAvatar}" /> <span class="small">{Language::getInstance()->getString('url_or_path')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="width:20%;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('members_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="group_members" value="{$newUserIDs}" /> <span class="small">{Language::getInstance()->getString('separate_ids_with_comma')}</span></td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('add_new_group')}" /></p>
<input type="hidden" name="create" value="yes" />
</form>