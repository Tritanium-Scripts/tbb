<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="5"><span class="FontTitle">{$modules.Language->getString('smilies')}</span></td></tr>
{foreach from=$smiliesData item=curSmiley}
 <tr>
  <td class="CellStd" align="center"><img src="{$curSmiley.smileyFileName}" alt=""/></td>
  <td class="CellAlt"><span class="FontSmall">{$curSmiley.smileyFileName}</span></td>
  <td class="CellStd"><span class="FontSmall">{$curSmiley.smileySynonym}</span></td>
  <td class="CellAlt" align="center"><span class="FontSmall">{if $curSmiley.smileyStatus == 1}{$modules.Language->getString('visible')}{else}{$modules.Language->getString('invisible')}{/if}</span></td>
  <td class="CellStd" align="right"><span class="FontSmall"><a href="{$smarty.const.INDEXFILE}?action=AdminSmilies&amp;mode=deleteSmiley&amp;smileyID={$curSmiley.smileyID}&amp;{$smarty.const.MYSID}">{$modules.Language->getString('delete')}</a> | <a href="{$smarty.const.INDEXFILE}?action=AdminSmilies&amp;mode=editSmiley&amp;smileyID={$curSmiley.smileyID}&amp;{$smarty.const.MYSID}">{$modules.Language->getString('edit')}</a></span></td>
 </tr>
{/foreach}
<tr><td class="CellTitle" colspan="5"><span class="FontTitle">{$modules.Language->getString('adminsmilies')}</span></td></tr>
{foreach from=$adminSmiliesData item=curSmiley}
 <tr>
  <td class="CellStd" align="center"><img src="{$curSmiley.smileyFileName}" alt=""/></td>
  <td class="CellAlt"><span class="FontSmall">{$curSmiley.smileyFileName}</span></td>
  <td class="CellStd" colspan="2"><span class="FontSmall">{$curSmiley.smileySynonym}</span></td>
  <td class="CellStd" align="right"><span class="FontSmall"><a href="{$smarty.const.INDEXFILE}?action=AdminSmilies&amp;mode=deleteSmiley&amp;smileyID={$curSmiley.smileyID}&amp;{$smarty.const.MYSID}">{$modules.Language->getString('delete')}</a> | <a href="{$smarty.const.INDEXFILE}?action=AdminSmilies&amp;mode=editSmiley&amp;smileyID={$curSmiley.smileyID}&amp;{$smarty.const.MYSID}">{$modules.Language->getString('edit')}</a></span></td>
 </tr>
{/foreach}
<tr><td class="CellTitle" colspan="5"><span class="FontTitle">{$modules.Language->getString('topic_pics')}</span></td></tr>
{foreach from=$postPicsData item=curSmiley}
<tr>
 <td class="CellStd" align="center"><img src="{$curSmiley.smileyFileName}" alt=""/></td>
 <td class="CellAlt" colspan="3"><span class="FontSmall">{$curSmiley.smileyFileName}</span></td>
 <td class="CellStd" align="right"><span class="FontSmall"><a href="{$smarty.const.INDEXFILE}?action=AdminSmilies&amp;mode=deleteSmiley&amp;smileyID={$curSmiley.smileyID}&amp;{$smarty.const.MYSID}">{$modules.Language->getString('delete')}</a> | <a href="{$smarty.const.INDEXFILE}?action=AdminSmilies&amp;mode=editSmiley&amp;smileyID={$curSmiley.smileyID}&amp;{$smarty.const.MYSID}">{$modules.Language->getString('edit')}</a></span></td>
</tr>
{/foreach}
</table>
<br/>
<table class="TableStd" width="100%">
<tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('other_options')}</span></td></tr>
<tr><td class="CellStd"><span class="FontNorm"><a href="{$smarty.const.INDEXFILE}?action=AdminSmilies&amp;mode=addSmiley&amp;smileyType={$smarty.const.SMILEY_TYPE_SMILEY}&amp;{$smarty.const.MYSID}">{$modules.Language->getString('add_smiley')}</a><br/><a href="{$smarty.const.INDEXFILE}?action=AdminSmilies&amp;mode=addSmiley&amp;smileyType={$smarty.const.SMILEY_TYPE_TPIC}&amp;{$smarty.const.MYSID}">{$modules.Language->getString('add_topic_pic')}</a><br/><a href="{$smarty.const.INDEXFILE}?action=AdminSmilies&amp;mode=addSmiley&amp;smileyType={$smarty.const.SMILEY_TYPE_ADMINSMILEY}&amp;{$smarty.const.MYSID}">{$modules.Language->getString('add_adminsmiley')}</a></span></td></tr>
</table>