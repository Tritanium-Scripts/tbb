<table border="0" cellpadding="0" cellspacing="4">
 <tr>
  <td><label class="FontNorm"><input type="radio" name="p[smileyID]" value=""{if $checkedID == 0} checked="checked"{/if}/>{$modules.Language->getString('non')}</label></td>
{foreach from=$postPicsData item=curPostPic name=postPicsLoop}
  <td><label><input type="radio" name="p[smileyID]" value="{$curPostPic.smileyID}"{if $curPostPic.smileyID == $checkedID} checked="checked"{/if}/><img src="{$curPostPic.smileyFileName}" alt=""/></label></td>
 {if $smarty.foreach.postPicsLoop.iteration % 7 == 0 && $smarty.foreach.postPicsLoop.iteration != $smarty.foreach.postPicsLoop.total}</tr><tr><td></td>{/if}
{/foreach}
 </tr>
</table>