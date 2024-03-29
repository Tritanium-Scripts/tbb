<!-- MemberList -->
{$isAdmin=Auth::getInstance()->isAdmin()}
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr>
  <th class="thsmall"><a class="thsmall" href="{$smarty.const.INDEXFILE}?faction=mlist&amp;sortmethod=id&amp;z={$page}&amp;orderType={$orderTypeID}{$smarty.const.SID_AMPER}"><span class="thsmall" style="text-decoration:underline;">{Language::getInstance()->getString('id')}</span></a></th>
  <th class="thsmall"><a class="thsmall" href="{$smarty.const.INDEXFILE}?faction=mlist&amp;sortmethod=name&amp;z={$page}&amp;orderType={$orderTypeName}{$smarty.const.SID_AMPER}"><span class="thsmall" style="text-decoration:underline;">{Language::getInstance()->getString('user_name')}</span></a></th>
  <th class="thsmall"><a class="thsmall" href="{$smarty.const.INDEXFILE}?faction=mlist&amp;sortmethod=status&amp;z={$page}&amp;orderType={$orderTypeRank}{$smarty.const.SID_AMPER}"><span class="thsmall" style="text-decoration:underline;">{Language::getInstance()->getString('user_rank')}</span></a></th>
  <th class="thsmall"><a class="thsmall" href="{$smarty.const.INDEXFILE}?faction=mlist&amp;sortmethod=posts&amp;z={$page}&amp;orderType={$orderTypePosts}{$smarty.const.SID_AMPER}"><span class="thsmall" style="text-decoration:underline;">{Language::getInstance()->getString('posts')}</span></a></th>
  <th class="thsmall" style="width:1%;"></th>
  <th class="thsmall" style="width:1%;"></th>{if $isAdmin}
  <th class="thsmall"></th>{/if}
 </tr>
{foreach $members as $curMember}
 <tr>
  <td class="td1"><span class="norm">{$curMember.id}</span></td>
  <td class="td2"><span class="norm">{$curMember.nick}</span></td>
  <td class="td1"><span class="norm">{$curMember.rank}</span></td>
  <td class="td2"><span class="norm">{$curMember.posts}</span></td>
  <td class="td1" style="text-align:center; white-space:nowrap;"><span class="small">{if $curMember.eMail !== false}<a href="{if $curMember.eMail === true}{$smarty.const.INDEXFILE}?faction=formmail&amp;target_id={$curMember.id}{$smarty.const.SID_AMPER}{else}mailto:{$curMember.eMail}{/if}"><img src="{Template::getInstance()->getTplDir()}images/mailto.gif" alt="{Language::getInstance()->getString('email')}" /></a> {Language::getInstance()->getString('email')}{/if}</span></td>
  <td class="td2" style="text-align:center; white-space:nowrap;"><span class="small"><a class="small" href="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=send&amp;target_id={$curMember.id}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/pm.gif" alt="" /></a> {Language::getInstance()->getString('pm')}</span></td>{if $isAdmin}
  <td class="td1" style="text-align:center;"><span class="small"><a class="small" href="{$smarty.const.INDEXFILE}?faction=ad_user&amp;mode=edit&amp;id={$curMember.id}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('edit_user_brackets')}</a></span></td>{/if}
 </tr>
{foreachelse}
 <tr><td class="td1" colspan="{if $isAdmin}7{else}6{/if}" style="font-weight:bold; text-align:center;"><span class="norm">{Language::getInstance()->getString('no_user_available')}</span></td></tr>
{/foreach}
</table>

<!-- PageBar -->
<br />
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><td class="td1"><span class="small">{$pageBar}</span></td></tr>
</table>