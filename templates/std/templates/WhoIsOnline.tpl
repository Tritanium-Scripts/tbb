<!-- WhoIsOnline -->
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="3"><span class="thnorm">{Language::getInstance()->getString('who_is_online')}</span></th></tr>
{foreach from=$wioLocations item=curLocation}
{* 0:user - 1:location - 2:isGhost - 3:xMinAgo - 4:userAgent *}
 <tr{if !empty($curLocation[4])} title="{$curLocation[4]}"{/if}><td class="td1"><span class="norm">{if $curLocation[2]}<img src="{Template::getInstance()->getTplDir()}images/ghost.png" alt="{Language::getInstance()->getString('browses_as_ghost')}" title="{Language::getInstance()->getString('browses_as_ghost')}" style="vertical-align:top;" /> {/if}{$curLocation[0]}</span></td><td class="td2"><span class="norm">{$curLocation[1]}</span></td><td class="td2" style="text-align:right;"><span class="norm">{$curLocation[3]}</span></td></tr>
{foreachelse}
 <tr><td class="td1" colspan="3" style="font-weight:bold; text-align:center;"><span class="norm">{Language::getInstance()->getString('no_user_available')}</span></td></tr>
{/foreach}
</table>