{$isAdmin=$modules.Auth->isAdmin()}
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall"><a href="{$smarty.const.INDEXFILE}?faction=mlist&amp;sortmethod=id&amp;z={$page}&amp;orderType={$orderTypeID}{$smarty.const.SID_AMPER}">{$modules.Language->getString('id')}</a></span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall"><a href="{$smarty.const.INDEXFILE}?faction=mlist&amp;sortmethod=name&amp;z={$page}&amp;orderType={$orderTypeName}{$smarty.const.SID_AMPER}">{$modules.Language->getString('user_name')}</a></span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall"><a href="{$smarty.const.INDEXFILE}?faction=mlist&amp;sortmethod=status&amp;z={$page}&amp;orderType={$orderTypeRank}{$smarty.const.SID_AMPER}">{$modules.Language->getString('user_rank')}</a></span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall"><a href="{$smarty.const.INDEXFILE}?faction=mlist&amp;sortmethod=posts&amp;z={$page}&amp;orderType={$orderTypePosts}{$smarty.const.SID_AMPER}">{$modules.Language->getString('posts')}</a></span></th>
  <th class="cellTitle" style="width:1%;"></th>
  <th class="cellTitle" style="width:1%;"></th>{if $isAdmin}
  <th class="cellTitle"></th>{/if}
 </tr>
{foreach $members as $curMember}
 <tr onmouseover="setRowCellsClass(this, 'cellHighlight');" onmouseout="restoreRowCellsClass(this);">
  <td class="cellStd"><span class="fontNorm">{$curMember.id}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$curMember.nick}</span></td>
  <td class="cellStd"><span class="fontNorm">{$curMember.rank}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$curMember.posts}</span></td>
  <td class="cellStd" style="text-align:center; white-space:nowrap;"><span class="fontSmall">{if $curMember.eMail !== false}<a href="{if $curMember.eMail === true}{$smarty.const.INDEXFILE}?faction=formmail&amp;target_id={$curMember.id}{$smarty.const.SID_AMPER}{else}mailto:{$curMember.eMail}{/if}"><img src="{$modules.Template->getTplDir()}images/icons/email.png" alt="{$modules.Language->getString('email')}" style="vertical-align:middle;" /> {$modules.Language->getString('email')}</a>{/if}</span></td>
  <td class="cellAlt" style="text-align:center; white-space:nowrap;"><span class="fontSmall"><a href="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=send&amp;target_id={$curMember.id}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/icons/pm.png" alt="" style="vertical-align:middle;" /> {$modules.Language->getString('pm')}</a></span></td>{if $isAdmin}
  <td class="cellStd" style="text-align:center;"><span class="fontSmall"><a href="{$smarty.const.INDEXFILE}?faction=ad_user&amp;mode=edit&amp;id={$curMember.id}{$smarty.const.SID_AMPER}">{$modules.Language->getString('edit_user_brackets')}</a></span></td>{/if}
 </tr>
{foreachelse}
 <tr><td class="cellStd" colspan="{if $isAdmin}7{else}6{/if}" style="font-weight:bold; text-align:center;"><span class="fontNorm">{$modules.Language->getString('no_user_available')}</span></td></tr>
{/foreach}
</table>

<!-- PageBar -->
<br />
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><td class="cellStd"><span class="fontSmall">{$pageBar}</span></td></tr>
</table>