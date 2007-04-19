<form method="post" action="{$indexFile}?action=EditTopic&amp;mode=Edit&amp;topicID={$topicID}&amp;doit=1&amp;{$mySID}">
<table class="TableStd" width="100%">
<colgroup>
 <col width="23%"/>
 <col width="77%"/>
</colgroup>
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('Edit_topic')}</span></td></tr>
{if $error != ''}<tr><td class="CellError" colspan="2"><img class="ImageIcon" src="{$modules.Template->getTD()}/images/icons/Warning.png" alt=""/><span class="FontError">{$error}</span></td></tr>{/if}
<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('Topic')}</span></td></tr>
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('Post_pic')}:</span></td>
 <td class="CellAlt" valign="top">{$postPicsBox}</td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Title')}:</span></td>
 <td class="CellAlt"><input class="FormText" type="text" size="65" name="p[topicTitle]" value="{$p.topicTitle}"/></td>
</tr>
{if $topicData.topicHasPoll}
 <tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('Poll')}</span></td></tr>
 <tr>
  <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('Poll_title')}:</span><br/><span class="FontSmall">{$modules.Language->getString('add_poll_info')}</span></td>
  <td class="CellAlt" valign="top"><input class="FormText" type="text" name="p[pollTitle]" maxlength="255" size="60" value="{$p.pollTitle}"/></td>
 </tr>
 <tr>
  <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('Poll_duration')}:</span></td>
  <td class="CellAlt" valign="top"><input class="FormText" size="5" name="p[pollDuration]" value="{$p.pollDuration}"/> <span class="FontSmall">({$modules.Language->getString('in_days')})</span></td>
 </tr>
 <tr>
  <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('Options')}:</span></td>
  <td class="CellAlt" valign="top"><span class="FontNorm">
   <label><input class="FormCheckbox" type="checkbox" name="c[pollShowResultsAfterEnd]"{if $c.pollShowResultsAfterEnd == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('Show_results_after_end')}</label>
   <br/><label><input class="FormCheckbox" type="checkbox" name="c[pollGuestsVote]"{if $c.pollGuestsVote == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('Guests_allowed_vote')}</label>
   <br/><label><input class="FormCheckbox" type="checkbox" name="c[pollGuestsViewResults]"{if $c.pollGuestsViewResults == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('Guests_allowed_view_results')}</label>
  </span></td>
 </tr>
 <tr>
  <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('Poll_options')}:</span></td>
  <td class="CellAlt" valign="top">
   <table>
   {foreach from=$optionsData item=curOption}
    {assign var=curOptionID value=$curOption.optionID}
    <tr><td style="padding:3px;"><input type="text" class="FormText" size="30" value="{$p.optionsData.$curOptionID}" name="p[optionsData][{$curOption.optionID}]""/></td></tr>
   {/foreach}
   </table>
  </td>
 </tr>
{/if}
<tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('Edit_topic')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('Reset')}"/></td></tr>
</table>
</form>