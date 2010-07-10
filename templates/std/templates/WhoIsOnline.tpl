<!-- WhoIsOnline -->
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{$modules.Language->getString('who_is_online')}</span></th></tr>
{foreach from=$wioLocations item=curLocation}
{* 0:user - 1:location - 2:isGhost *}
 <tr><td class="td1"><span class="norm">{if $curLocation[2]}<img src="{$modules.Template->getTplDir()}images/ghost.png" alt="{$modules.Language->getString('browses_as_ghost')}" title="{$modules.Language->getString('browses_as_ghost')}" /> {/if}{$curLocation[0]}</span></td><td class="td2"><span class="norm">{$curLocation[1]}</span></td></tr>
{foreachelse}
 <tr><td class="td1" colspan="2" style="font-weight:bold; text-align:center;"><span class="norm">{$modules.Language->getString('no_user_available')}</span></td></tr>
{/foreach}
</table>