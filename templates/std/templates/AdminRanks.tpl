<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="4"><span class="FontTitle">{$modules.Language->getString('Normal_ranks')}</span></td></tr>
{foreach from=$normalRanksData item=curRank}
<tr>
 <td class="CellStd"><span class="FontNorm">{$curRank.rankName}</span></td>
 <td class="CellAlt" align="center"><span class="FontNorm">{$curRank.rankPosts}</span></td>
 <td class="CellStd" align="center"><span class="FontNorm">{$curRank._rankGfx}</span></td>
 <td class="CellAlt" align="right"><span class="FontSmall"><a href="{$indexFile}?action=AdminRanks&amp;mode=DeleteRank&amp;rankID={$curRank.rankID}&amp;{$mySID}"><img src="{$modules.Template->getTD()}/images/icons/AwardStarDelete.png" alt="{$modules.Language->getString('delete')}" title="{$modules.Language->getString('delete')}"/></a> | <a href="{$indexFile}?action=AdminRanks&amp;mode=EditRank&amp;rankID={$curRank.rankID}&amp;{$mySID}">{$modules.Language->getString('Edit')}</a></span></td>
</tr>
{/foreach}
</table>
<br/>
<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="3"><span class="FontTitle">{$modules.Language->getString('Special_ranks')}</span></td></tr>
{foreach from=$specialRanksData item=curRank}
<tr>
 <td class="CellStd"><span class="FontNorm">{$curRank.rankName}</span></td>
 <td class="CellAlt" align="center"><span class="FontNorm">{$curRank._rankGfx}</span></td>
 <td class="CellStd" align="right"><span class="FontSmall"><a href="{$indexFile}?action=AdminRanks&amp;mode=DeleteRank&amp;rankID={$curRank.rankID}&amp;{$mySID}"><img src="{$modules.Template->getTD()}/images/icons/AwardStarDelete.png" alt="{$modules.Language->getString('delete')}" title="{$modules.Language->getString('delete')}"/></a> | <a href="{$indexFile}?action=AdminRanks&amp;mode=EditRank&amp;rankID={$curRank.rankID}&amp;{$mySID}">{$modules.Language->getString('Edit')}</a></span></td>
</tr>
{/foreach}
</table>
<br/>
<table class="TableStd" width="100%">
<tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('Other_options')}</span></td></tr>
<tr><td class="CellStd"><span class="FontNorm"><a href="{$indexFile}?action=AdminRanks&amp;mode=AddRank&amp;{$mySID}"><img class="ImageIcon" src="{$modules.Template->getTD()}/images/icons/AwardStarAdd.png" alt=""/>{$modules.Language->getString('Add_rank')}</a></span></td></tr>
</table>
