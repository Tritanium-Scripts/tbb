<table class="TableStd" width="100%">
{if $flags.inPrivateMessages || $flags.inEditProfile || $pageInPage}
 <tr><td class="CellCat"><span class="FontCat">{$messageTitle}</span></td></tr>
{else}
 <tr><td class="CellTitle"><span class="FontTitle">{$messageTitle}</span></td></tr>
{/if}
<tr><td class="CellStd" style="padding:15px;" align="center"><span class="FontNorm">{$messageText}</span></td></tr>
</table>
