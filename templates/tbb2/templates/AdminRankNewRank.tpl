{include file='AdminMenu.tpl'}
<!-- AdminRankNewRank -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_rank&amp;mode=new{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <colgroup>
  <col width="20%" />
  <col width="80%" />
 </colgroup>
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('add_new_rank')}</span></th></tr>
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
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('add_new_rank')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" /></p>
<input type="hidden" name="save" value="yes" />
</form>
{include file='AdminMenuTail.tpl'}