<table border="0" cellpadding="2" cellspacing="0">
 <tr>
{foreach from=$PPicsData item=curPPic name=PPicsLoop}
  <td><input type="radio" name="p[SmileyID]" value="{$curPPic.SmileyID}"/><img border="0" src="{$curPPic.SmileyFileName}" alt=""/></td>
 {if $smarty.foreach.PPicsLoop.iteration % 7 == 0 && $smarty.foreach.PPicsLoop.iteration != $smarty.foreach.PPicsLoop.total}</tr><tr>{/if}
{/foreach}
 </tr>
</table>