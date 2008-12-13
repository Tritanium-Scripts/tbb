<form method="post" action="{$smarty.const.INDEXFILE}?action=EditTopic&amp;mode=Move&amp;topicID={$topicID}&amp;doit=1&amp;{$smarty.const.MYSID}">
<table class="TableStd" width="100%">
<colgroup>
 <col width="20%"/>
 <col width="80%"/>
</colgroup>
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('move_topic')}</span></td></tr>
{if $error != ''}<tr><td class="CellError" colspan="2"><img class="ImageIcon" src="{$modules.Template->getTD()}/images/icons/Warning.png" alt=""/><span class="FontError">{$error}</span></td></tr>{/if}
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('target_forum')}:</span></td>
 <td class="CellAlt"><select class="FormSelect" size="8" name="p[targetForumID]">
 {foreach from=$selectOptions item=curOption}
  <option value="{$curOption.0}"{if $curOption.0 != '' && $curOption.0 == $p.targetForumID} selected="selected"{/if}>{$curOption.1}</option>
 {/foreach}
 </select></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">Optionen</span></td>
 <td class="CellAlt"><span class="FontNorm"><label><input type="checkbox" value="1" name="c[createReference]"{if $c.createReference == 1} checked="checked"{/if}/> {$modules.Language->getString('create_reference_topic')}</label></span></td>
</tr>
<tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('move_topic')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('reset')}"/></td></tr>
</table>
</form>
