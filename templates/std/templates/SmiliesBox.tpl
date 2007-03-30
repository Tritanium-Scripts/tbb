<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr><td align="center">
  <table border="0" cellpadding="3" cellspacing="0">
   <tr>
   {foreach from=$SmiliesData item=curSmiley name=SmiliesLoop}
    <td valign="bottom"><a href="javascript:insertatcaret(' {$curSmiley.SmileySynonym} ')"><img src="{$curSmiley.SmileyFileName}" alt="{$curSmiley.SmileySynonym}" border="0"/></a></td>
    {if $smarty.foreach.SmiliesLoop.iteration % 7 == 0 && $smarty.foreach.SmiliesLoop.iteration != $smarty.foreach.SmiliesLoop.total}</tr><tr>{/if}
   {/foreach}
   </tr>
   <tr><td colspan="6" align="center"><span class="FontSmall"><a href="javascript:popup('index.php?action=viewsmilies&amp;{$MySID}','tbbsmilies','width=300,height=400,scrollbars=yes,toolbar=no');">{$Modules.Language->getString('More_smilies')}</a></span></td></tr>
  </table>
 </td></tr>
</table>
