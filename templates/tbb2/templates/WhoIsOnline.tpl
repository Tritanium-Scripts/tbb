<!-- WhoIsOnline -->
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="3"><span class="fontTitle">{Language::getInstance()->getString('who_is_online')}</span></th></tr>
{plugin_hook hook=PlugIns::HOOK_WHO_IS_ONLINE_TABLE_HEAD}
{foreach from=$wioLocations item=curLocation}
{* 0:user - 1:location - 2:isGhost - 3:xMinAgo - 4:userAgent *}
 <tr{if !empty($curLocation[4])} title="{$curLocation[4]}"{/if} onmouseover="setRowCellsClass(this, 'cellHighlight');" onmouseout="restoreRowCellsClass(this);">
  <td class="cellStd"><span class="fontNorm">{if $curLocation[2]}<img src="{Template::getInstance()->getTplDir()}images/ghost.png" alt="{Language::getInstance()->getString('browses_as_ghost')}" title="{Language::getInstance()->getString('browses_as_ghost')}" style="vertical-align:top;" /> {/if}{$curLocation[0]}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$curLocation[1]}</span></td>
  <td class="cellAlt" style="text-align:right;"><span class="fontNorm">{$curLocation[3]}</span></td>
{plugin_hook hook=PlugIns::HOOK_WHO_IS_ONLINE_TABLE_BODY}
 </tr>
{foreachelse}
 <tr><td class="cellStd" colspan="3" style="font-weight:bold; text-align:center;"><span class="fontNorm">{Language::getInstance()->getString('no_user_available')}</span></td></tr>
{/foreach}
</table>