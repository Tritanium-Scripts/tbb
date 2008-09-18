<div style="overflow:auto; width:190px; max-height:170px; padding:0px;">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr><td align="center">
  <table border="0" cellpadding="0" cellspacing="4">
   <tr>
   {foreach from=$smiliesData item=curSmiley name=smiliesLoop}
    <td><a href="javascript:insert(' {$curSmiley.smileySynonym} ','')"><img src="{$curSmiley.smileyFileName}" alt="{$curSmiley.smileySynonym}"/></a></td>
    {if $smarty.foreach.smiliesLoop.iteration % 7 == 0 && $smarty.foreach.smiliesLoop.iteration != $smarty.foreach.smiliesLoop.total}</tr><tr>{/if}
   {/foreach}
   </tr>
  </table>
 </td></tr>
</table>
</div>