<table class="TableStd" width="100%">
{if $Flags.InPrivateMessages || $Flags.InEditProfile}
 <tr><td class="CellCat"><span class="FontCat">{$MessageTitle}</span></td></tr>
{else}
 <tr><td class="CellTitle"><span class="FontTitle">{$MessageTitle}</span></td></tr>
{/if}
<tr><td class="CellStd" style="padding:15px;" align="center"><span class="FontNorm">{$MessageText}</span></td></tr>
</table>
