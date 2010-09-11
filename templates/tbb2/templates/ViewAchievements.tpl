<!-- ViewAchievements -->
{include file='Errors.tpl'}
{if empty($errors)}
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><img src="{$icon}" alt="{$name}" title="{$name}" style="vertical-align:middle;" /> <span class="fontTitle">{$name|string_format:$modules.Language->getString('achievements_from_x')}</span></th><td class="cellTitle" style="text-align:right;"><img src="{$modules.Template->getTplDir()}images/icons/controller.png" alt="" style="vertical-align:middle;" /> <span class="fontTitle">{sprintf($modules.Language->getString('x_of_x_percent'), $numClosed, $numTotal, $percentClosed)}</span></td></tr>
{* <tr><td colspan="2"><img src="{$logo}" alt="{$name}" title="{$name}" /></td></tr> *}
</table>
<br />
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><td class="cellCat" colspan="2"><span class="fontCat">{$modules.Language->getString('closed_achievements')}</span></td></tr>
 <tr>
  <td class="cellStd">
   <table style="width:100%;">
{foreach $achievementsClosed as $curClosedAchievement}
    <tr>
     <td rowspan="2" style="width:66px;"><img src="{$curClosedAchievement.icon}" alt="{$curClosedAchievement.name}" title="{$curClosedAchievement.name}" /></td>
     <th><span class="fontNorm">{$curClosedAchievement.name}</span></th>
    </tr>
    <tr><td><span class="fontNorm">{$curClosedAchievement.description}</span></td></tr>
{foreachelse}
    <tr><td colspan="2" style="font-weight:bold; text-align:center;"><span class="fontNorm">{$modules.Language->getString('no_achievements_to_display')}</span></td></tr>
{/foreach}
   </table>
  </td>
 </tr>
</table>
<br />
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><td class="cellCat" colspan="2"><span class="fontCat">{$modules.Language->getString('open_achievements')}</span></td></tr>
 <tr>
  <td class="cellStd">
   <table style="width:100%;">
{foreach $achievementsOpen as $curOpenAchievement}
    <tr>
     <td rowspan="2" style="width:66px;"><img src="{$curOpenAchievement.icon}" alt="{$curOpenAchievement.name}" title="{$curOpenAchievement.name}" /></td>
     <th><span class="fontNorm">{$curOpenAchievement.name}</span></th>
    </tr>
    <tr><td><span class="fontNorm">{$curOpenAchievement.description}</span></td></tr>
{foreachelse}
    <tr><td colspan="2" style="font-weight:bold; text-align:center;"><span class="fontNorm">{$modules.Language->getString('no_achievements_to_display')}</span></td></tr>
{/foreach}
   </table>
  </td>
 </tr>
</table>
{/if}