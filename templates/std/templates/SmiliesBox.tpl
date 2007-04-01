<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr><td align="center">
  <table border="0" cellpadding="0" cellspacing="4">
   <tr>
   {foreach from=$smiliesData item=curSmiley name=smiliesLoop}
    <td><a href="javascript:insertatcaret(' {$curSmiley.smileySynonym} ')"><img src="{$curSmiley.smileyFileName}" alt="{$curSmiley.smileySynonym}" border="0"/></a></td>
    {if $smarty.foreach.smiliesLoop.iteration % 7 == 0 && $smarty.foreach.smiliesLoop.iteration != $smarty.foreach.smiliesLoop.total}</tr><tr>{/if}
   {/foreach}
   </tr>
   <tr><td colspan="6" align="center"><span class="FontSmall"><a href="javascript:popup('index.php?action=viewsmilies&amp;{$mySID}','tbbsmilies','width=300,height=400,scrollbars=yes,toolbar=no');">{$modules.Language->getString('More_smilies')}</a></span></td></tr>
  </table>
 </td></tr>
</table>
