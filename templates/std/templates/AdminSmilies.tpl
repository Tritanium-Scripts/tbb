<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="5"><span class="FontTitle">{$modules.Language->getString('Smilies')}</span></td></tr>
{foreach from=$smiliesData item=curSmiley}
 <tr>
  <td class="CellStd" align="center"><img src="{$curSmiley.smileyFileName}" alt=""/></td>
  <td class="CellAlt"><span class="FontSmall">{$curSmiley.smileyFileName}</span></td>
  <td class="CellStd"><span class="FontSmall">{$curSmiley.smileySynonym}</span></td>
  <td class="CellAlt" align="center"><span class="FontSmall">{if $curSmiley.smileyStatus == 1}{$modules.Language->getString('visible')}{else}{$modules.Language->getString('unsichtbar')}{/if}</span></td>
  <td class="CellStd" align="right"><span class="FontSmall"><a href="{$indexFile}?action=AdminSmilies&amp;mode=deleteSmiley&amp;smileyID={$curSmiley.smileyID}&amp;{$mySID}">{$modules.Language->getString('delete')}</a> | <a href="{$indexFile}?action=AdminSmilies&amp;mode=editSmiley&amp;smileyID={$curSmiley.smileyID}&amp;{$mySID}">{$modules.Language->getString('edit')}</a></span></td>
 </tr>
{/foreach}
<tr><td class="CellTitle" colspan="5"><span class="FontTitle">{$modules.Language->getString('Adminsmilies')}</span></td></tr>
{foreach from=$adminSmiliesData item=curSmiley}
 <tr>
  <td class="CellStd" align="center"><img src="{$curSmiley.smileyFileName}" alt=""/></td>
  <td class="CellAlt"><span class="FontSmall">{$curSmiley.smileyFileName}</span></td>
  <td class="CellStd" colspan="2"><span class="FontSmall">{$curSmiley.smileySynonym}</span></td>
  <td class="CellStd" align="right"><span class="FontSmall"><a href="{$indexFile}?action=AdminSmilies&amp;mode=deleteSmiley&amp;smileyID={$curSmiley.smileyID}&amp;{$mySID}">{$modules.Language->getString('delete')}</a> | <a href="{$indexFile}?action=AdminSmilies&amp;mode=editSmiley&amp;smileyID={$curSmiley.smileyID}&amp;{$mySID}">{$modules.Language->getString('edit')}</a></span></td>
 </tr>
{/foreach}
<tr><td class="CellTitle" colspan="5"><span class="FontTitle">{$modules.Language->getString('Topic_pics')}</span></td></tr>
{foreach from=$postPicsData item=curSmiley}
<tr>
 <td class="CellStd" align="center"><img src="{$curSmiley.smileyFileName}" alt=""/></td>
 <td class="CellAlt" colspan="3"><span class="FontSmall">{$curSmiley.smileyFileName}</span></td>
 <td class="CellStd" align="right"><span class="FontSmall"><a href="{$indexFile}?action=AdminSmilies&amp;mode=deleteSmiley&amp;smileyID={$curSmiley.smileyID}&amp;{$mySID}">{$modules.Language->getString('delete')}</a> | <a href="{$indexFile}?action=AdminSmilies&amp;mode=editSmiley&amp;smileyID={$curSmiley.smileyID}&amp;{$mySID}">{$modules.Language->getString('edit')}</a></span></td>
</tr>
{/foreach}
</table>
<br/>
<table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('Other_options')}</span></td></tr>
<tr><td class="CellStd"><span class="FontNorm"><a href="{$indexFile}?action=AdminSmilies&amp;mode=addSmiley&amp;smileyType={$smarty.const.SMILEY_TYPE_SMILEY}&amp;{$mySID}">{$modules.Language->getString('Add_smiley')}</a><br/><a href="{$indexFile}?action=AdminSmilies&amp;mode=addSmiley&amp;smileyType={$smarty.const.SMILEY_TYPE_TPIC}&amp;{$mySID}">{$modules.Language->getString('Add_topic_pic')}</a><br/><a href="{$indexFile}?action=AdminSmilies&amp;mode=addSmiley&amp;smileyType={$smarty.const.SMILEY_TYPE_ADMINSMILEY}&amp;{$mySID}">{$modules.Language->getString('Add_adminsmiley')}</a></span></td></tr>
</table>