<!-- ViewAchievements -->
{include file='Errors.tpl'}
{if empty($errors)}
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><img src="{$icon}" alt="{$name}" title="{$name}" style="vertical-align:middle;" /> <span class="thnorm">{$name|string_format:Language::getInstance()->getString('achievements_from_x')}</span></th><td style="text-align:right;"><span class="thnorm">{sprintf(Language::getInstance()->getString('x_of_x_percent'), $numClosed, $numTotal, $percentClosed)}</span></td></tr>
{* <tr><td colspan="2"><img src="{$logo}" alt="{$name}" title="{$name}" /></td></tr> *}
{plugin_hook hook=PlugIns::HOOK_TPL_PROFILE_VIEW_ACHIEVEMENTS_TABLE_HEAD}
</table>
<br />
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><td class="kat" colspan="2"><span class="kat">{Language::getInstance()->getString('closed_achievements')}</span></td></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_PROFILE_VIEW_ACHIEVEMENTS_CLOSED_TABLE_HEAD}
{foreach $achievementsClosed as $curClosedAchievement}
 <tr>
  <td class="td1" rowspan="2" style="width:66px;"><img src="{$curClosedAchievement.icon}" alt="{$curClosedAchievement.name}" title="{$curClosedAchievement.name}" style="width:64px; height:64px;" /></td>
  <th class="td1"><span class="norm">{$curClosedAchievement.name}</span></th>
 </tr>
 <tr><td class="td1"><span class="norm">{$curClosedAchievement.description}</span>{if !empty($curClosedAchievement.unlocked)} <span class="small" style="float:right;"><span style="font-weight:bold;">{Language::getInstance()->getString('unlocked_colon')}</span> {$curClosedAchievement.unlocked|utf8_encode}</span>{/if}</td></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_PROFILE_VIEW_ACHIEVEMENTS_CLOSED_TABLE_BODY}
{foreachelse}
 <tr><td class="td1" colspan="2" style="font-weight:bold; text-align:center;"><span class="norm">{Language::getInstance()->getString('no_achievements_to_display')}</span></td></tr>
{/foreach}
</table>
<br />
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><td class="kat" colspan="2"><span class="kat">{Language::getInstance()->getString('open_achievements')}</span></td></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_PROFILE_VIEW_ACHIEVEMENTS_OPEN_TABLE_HEAD}
{foreach $achievementsOpen as $curOpenAchievement}
 <tr>
  <td class="td1" rowspan="2" style="width:66px;"><img src="{$curOpenAchievement.icon}" alt="{$curOpenAchievement.name}" title="{$curOpenAchievement.name}" style="width:64px; height:64px;" /></td>
  <th class="td1"><span class="norm">{$curOpenAchievement.name}</span></th>
 </tr>
 <tr><td class="td1"><span class="norm">{$curOpenAchievement.description}</span></td></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_PROFILE_VIEW_ACHIEVEMENTS_OPEN_TABLE_BODY}
{foreachelse}
 <tr><td class="td1" colspan="2" style="font-weight:bold; text-align:center;"><span class="norm">{Language::getInstance()->getString('no_achievements_to_display')}</span></td></tr>
{/foreach}
</table>
{/if}