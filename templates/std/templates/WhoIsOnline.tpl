<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('Who_is_online')}</span></td></tr>
{foreach from=$wIOData item=curWIO}
 <tr>
  <td class="CellStd"><span class="FontNorm">{$curWIO.SessionUserNick}</span></td>
  <td class="CellAlt"><span class="FontNorm">{$curWIO._SessionLastLocationText}</span></td>
 </tr>
{/foreach}
</table>
