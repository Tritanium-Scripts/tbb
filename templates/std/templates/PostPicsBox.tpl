<table border="0" cellpadding="0" cellspacing="4">
 <tr>
{foreach from=$postPicsData item=curPostPic name=postPicsLoop}
  <td><label><input type="radio" name="p[smileyID]" value="{$curPostPic.smileyID}"{if $curPostPic.smileyID == $checkedID} checked="checked"{/if}/><img border="0" src="{$curPostPic.smileyFileName}" alt=""/></label></td>
 {if $smarty.foreach.postPicsLoop.iteration % 7 == 0 && $smarty.foreach.postPicsLoop.iteration != $smarty.foreach.postPicsLoop.total}</tr><tr>{/if}
{/foreach}
 </tr>
</table>