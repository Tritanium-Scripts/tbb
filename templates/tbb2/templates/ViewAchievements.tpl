<!-- ViewAchievements -->
{include file='Errors.tpl'}
{if empty($errors)}
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><img src="{$icon}" alt="{$name}" title="{$name}" style="vertical-align:middle;" /> <span class="fontTitle">{$name|string_format:Language::getInstance()->getString('achievements_from_x')}</span></th><td class="cellTitle" style="text-align:right;"><img src="{Template::getInstance()->getTplDir()}images/icons/controller.png" alt="" style="vertical-align:middle;" /> <span class="fontTitle">{sprintf(Language::getInstance()->getString('x_of_x_percent'), $numClosed, $numTotal, $percentClosed)}</span></td></tr>
{* <tr><td colspan="2"><img src="{$logo}" alt="{$name}" title="{$name}" /></td></tr> *}
{plugin_hook hook=PlugIns::HOOK_TPL_PROFILE_VIEW_ACHIEVEMENTS_TABLE_HEAD}
</table>
<br />
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><td class="cellCat" colspan="2"><span class="fontCat">{Language::getInstance()->getString('closed_achievements')}</span></td></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_PROFILE_VIEW_ACHIEVEMENTS_CLOSED_TABLE_HEAD}
 <tr>
  <td class="cellStd">
   <table style="width:100%;">
{foreach $achievementsClosed as $curClosedAchievement}
    <tr>
     <td rowspan="2" style="width:66px;"><img src="{$curClosedAchievement.icon}" alt="{$curClosedAchievement.name}" title="{$curClosedAchievement.name}" style="width:64px; height:64px;" /></td>
     <th><span class="fontNorm">{$curClosedAchievement.name}</span></th>
    </tr>
    <tr><td><span class="fontNorm">{$curClosedAchievement.description}</span>{if !empty($curClosedAchievement.unlocked)} <span class="fontSmall" style="float:right;"><span style="font-weight:bold;">{Language::getInstance()->getString('unlocked_colon')}</span> {$curClosedAchievement.unlocked}</span>{/if}</td></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_PROFILE_VIEW_ACHIEVEMENTS_CLOSED_TABLE_BODY}
{foreachelse}
    <tr><td colspan="2" style="font-weight:bold; text-align:center;"><span class="fontNorm">{Language::getInstance()->getString('no_achievements_to_display')}</span></td></tr>
{/foreach}
   </table>
  </td>
 </tr>
</table>
<br />
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><td class="cellCat" colspan="2"><span class="fontCat">{Language::getInstance()->getString('open_achievements')}</span></td></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_PROFILE_VIEW_ACHIEVEMENTS_OPEN_TABLE_HEAD}
 <tr>
  <td class="cellStd">
   <table style="width:100%;">
{foreach $achievementsOpen as $curOpenAchievement}
    <tr>
     <td rowspan="2" style="width:66px;"><img src="{$curOpenAchievement.icon}" alt="{$curOpenAchievement.name}" title="{$curOpenAchievement.name}" style="width:64px; height:64px;" /></td>
     <th><span class="fontNorm">{$curOpenAchievement.name}</span></th>
    </tr>
    <tr><td><span class="fontNorm">{$curOpenAchievement.description}</span></td></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_PROFILE_VIEW_ACHIEVEMENTS_OPEN_TABLE_BODY}
{foreachelse}
    <tr><td colspan="2" style="font-weight:bold; text-align:center;"><span class="fontNorm">{Language::getInstance()->getString('no_achievements_to_display')}</span></td></tr>
{/foreach}
   </table>
  </td>
 </tr>
</table>
{/if}