<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$Modules.Language->getString('Who_is_online')}</span></td></tr>
{foreach from=$WIOData item=curWIO}
 <tr>
  <td class="CellStd"><span class="FontNorm">{$curWIO.SessionUserNick}</span></td>
  <td class="CellAlt"><span class="FontNorm">{$curWIO._SessionLastLocationText}</span></td>
 </tr>
{/foreach}
</table>
