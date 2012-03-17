<!-- WhoIsOnline -->
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="3"><span class="fontTitle">{$modules.Language->getString('who_is_online')}</span></th></tr>
{foreach from=$wioLocations item=curLocation}
{* 0:user - 1:location - 2:isGhost - 3:xMinAgo - 4:userAgent *}
 <tr{if !empty($curLocation[4])} title="{$curLocation[4]}"{/if} onmouseover="setRowCellsClass(this, 'cellHighlight');" onmouseout="restoreRowCellsClass(this);"><td class="cellStd"><span class="fontNorm">{if $curLocation[2]}<img src="{$modules.Template->getTplDir()}images/ghost.png" alt="{$modules.Language->getString('browses_as_ghost')}" title="{$modules.Language->getString('browses_as_ghost')}" style="vertical-align:top;" /> {/if}{$curLocation[0]}</span></td><td class="cellAlt"><span class="fontNorm">{$curLocation[1]}</span></td><td class="cellAlt" style="text-align:right;"><span class="fontNorm">{$curLocation[3]}</span></td></tr>
{foreachelse}
 <tr><td class="cellStd" colspan="3" style="font-weight:bold; text-align:center;"><span class="fontNorm">{$modules.Language->getString('no_user_available')}</span></td></tr>
{/foreach}
</table>