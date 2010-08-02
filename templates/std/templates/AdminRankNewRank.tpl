<!-- AdminRankNewRank -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_rank&amp;mode=new{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{$modules.Language->getString('add_new_rank')}</span></th></tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('rank_name_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="bez" value="{$rankName}" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('required_posts_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="minposts" value="{$requiredPosts}" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('number_of_stars_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" size="2" name="pic" value="{$stars}" /></td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('add_new_rank')}" /></p>
<input type=hidden name="save" value="yes" />
</form>