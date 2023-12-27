<!-- AdminIPNewBlock -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_ip{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{Language::getInstance()->getString('add_new_ip_block')}</span></th></tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('ip_address_colon')}</span></td>
  <td class="td1"><input type="text" name="ip" value="{$newIPAddress}" /></td>
 </tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('blocking_period_colon')}</span></td>
  <td class="td1"><input type="text" size="4" name="sperrtime" value="{$newBlockPeriod}" /> <span class="small">{Language::getInstance()->getString('blocking_period_hint')}</span></td>
 </tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('block_target_colon')}</span></td>
  <td class="td1">
   <select name="sperrziel" size="1">
    <option value="-1">{Language::getInstance()->getString('entire_board')}</option>{foreach $cats as $curCat}
    <option value="" style="background-color:#333333; color:#FFFFFF;">--{$curCat[1]}</option>
    {foreach $forums as $curForum}{if $curForum.catID == $curCat[0]}<option value="{$curForum.forumID}"{if $curForum.forumID == $newBlockForumID} selected="selected"{/if}>{$curForum.forumName}</option>{/if}{/foreach}
    <option value=""></option>{/foreach}
   </select>
  </td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('add_new_ip_block')}" /></p>
<input type="hidden" name="mode" value="new" />
<input type="hidden" name="create" value="yes" />
</form>