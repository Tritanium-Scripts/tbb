<!-- ViewAchievements -->
{include file='Errors.tpl'}
{if empty($errors)}
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><img src="{$icon}" alt="{$name}" title="{$name}" style="vertical-align:middle;" /> <span class="thnorm">{$name|string_format:$modules.Language->getString('achievements_from_x')}</span></th><td style="text-align:right;"><span class="thnorm">{sprintf($modules.Language->getString('x_of_x_percent'), $numClosed, $numTotal, $percentClosed)}</span></td></tr>
{* <tr><td colspan="2"><img src="{$logo}" alt="{$name}" title="{$name}" /></td></tr> *}
</table>
<br />
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><td class="kat" colspan="2"><span class="kat">{$modules.Language->getString('closed_achievements')}</span></td></tr>
{foreach $achievementsClosed as $curClosedAchievement}
 <tr>
  <td class="td1" rowspan="2" style="width:66px;"><img src="{$curClosedAchievement.icon}" alt="{$curClosedAchievement.name}" title="{$curClosedAchievement.name}" /></td>
  <th class="td1"><span class="norm">{$curClosedAchievement.name}</span></th>
 </tr>
 <tr><td class="td1"><span class="norm">{$curClosedAchievement.description}</span>{if !empty($curClosedAchievement.unlocked)} <span class="small" style="float:right;"><span style="font-weight:bold;">{$modules.Language->getString('unlocked_colon')}</span> {$curClosedAchievement.unlocked}</span>{/if}</td></tr>
{foreachelse}
 <tr><td class="td1" colspan="2" style="font-weight:bold; text-align:center;"><span class="norm">{$modules.Language->getString('no_achievements_to_display')}</span></td></tr>
{/foreach}
</table>
<br />
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><td class="kat" colspan="2"><span class="kat">{$modules.Language->getString('open_achievements')}</span></td></tr>
{foreach $achievementsOpen as $curOpenAchievement}
 <tr>
  <td class="td1" rowspan="2" style="width:66px;"><img src="{$curOpenAchievement.icon}" alt="{$curOpenAchievement.name}" title="{$curOpenAchievement.name}" /></td>
  <th class="td1"><span class="norm">{$curOpenAchievement.name}</span></th>
 </tr>
 <tr><td class="td1"><span class="norm">{$curOpenAchievement.description}</span></td></tr>
{foreachelse}
 <tr><td class="td1" colspan="2" style="font-weight:bold; text-align:center;"><span class="norm">{$modules.Language->getString('no_achievements_to_display')}</span></td></tr>
{/foreach}
</table>
{/if}