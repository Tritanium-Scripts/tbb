<form method="post" action="{$indexFile}?action=EditTopic&amp;mode=Edit&amp;topicID={$topicID}&amp;doit=1&amp;{$mySID}">
<table class="TableStd" width="100%">
<colgroup>
 <col width="23%"/>
 <col width="77%"/>
</colgroup>
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('edit_topic')}</span></td></tr>
{if $error != ''}<tr><td class="CellError" colspan="2"><img class="ImageIcon" src="{$modules.Template->getTD()}/images/icons/Warning.png" alt=""/><span class="FontError">{$error}</span></td></tr>{/if}
<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('topic')}</span></td></tr>
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('post_pic')}:</span></td>
 <td class="CellAlt" valign="top">{$postPicsBox}</td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('title')}:</span></td>
 <td class="CellAlt"><input class="FormText" type="text" size="65" name="p[topicTitle]" value="{$p.topicTitle}"/></td>
</tr>
{if $topicData.topicHasPoll}
 <tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('poll')}</span></td></tr>
 <tr>
  <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('poll_title')}:</span><br/><span class="FontSmall">{$modules.Language->getString('add_poll_info')}</span></td>
  <td class="CellAlt" valign="top"><input class="FormText" type="text" name="p[topicPollTitle]" maxlength="255" size="60" value="{$p.topicPollTitle}"/></td>
 </tr>
 <tr>
  <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('poll_duration')}:</span></td>
  <td class="CellAlt" valign="top"><input class="FormText" size="5" name="p[pollDuration]" value="{$p.pollDuration}"/> <span class="FontSmall">({$modules.Language->getString('in_days')})</span></td>
 </tr>
 <tr>
  <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('options')}:</span></td>
  <td class="CellAlt" valign="top"><span class="FontNorm">
   <label><input class="FormCheckbox" type="checkbox" name="c[topicPollShowResultsAfterEnd]"{if $c.topicPollShowResultsAfterEnd == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('show_results_after_end')}</label>
   <br/><label><input class="FormCheckbox" type="checkbox" name="c[topicPollGuestsVote]"{if $c.topicPollGuestsVote == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('guests_allowed_vote')}</label>
   <br/><label><input class="FormCheckbox" type="checkbox" name="c[topicPollGuestsViewResults]"{if $c.topicPollGuestsViewResults == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('guests_allowed_view_results')}</label>
  </span></td>
 </tr>
 <tr>
  <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('poll_options')}:</span></td>
  <td class="CellAlt" valign="top">
   <table>
   {foreach from=$optionsData item=curOption}
    {assign var=curOptionID value=$curOption.optionID}
    <tr><td style="padding:3px;"><input type="text" class="FormText" size="30" value="{$p.optionsData.$curOptionID}" name="p[optionsData][{$curOption.optionID}]"/></td></tr>
   {/foreach}
   </table>
  </td>
 </tr>
{/if}
<tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('edit_topic')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('reset')}"/></td></tr>
</table>
</form>