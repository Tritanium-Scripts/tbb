<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('Who_is_online')}</span></td></tr>
{foreach from=$wioData item=curWIO}
 <tr>
  <td class="CellStd"><span class="FontNorm">{$curWIO.sessionUserNick}</span></td>
  <td class="CellAlt"><span class="FontNorm">{$curWIO._sessionLastLocationText}</span></td>
 </tr>
{/foreach}
</table>
