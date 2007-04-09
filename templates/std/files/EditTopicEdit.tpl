<form method="post" action="{$indexFile}?action=EditTopic&amp;mode=Edit&amp;topicID={$topicID}&amp;doit=1&amp;{$mySID}">
<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('Edit_topic')}</span></td></tr>
{if $error != ''}<tr><td class="CellError" colspan="2"><img src="{$modules.Template->getTD()}/images/icons/Warning.png" alt=""/><span class="FontError">{$error}</span></td></tr>{/if}
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('Post_pic')}:</span></td>
 <td class="CellAlt" valign="top">{$postPicsBox}</td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Title')}:</span></td>
 <td class="CellAlt"><input class="FormText" type="text" size="65" name="p[topicTitle]" value="{$p.topicTitle}"/></td>
</tr>
<tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('Edit_topic')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('Reset')}"/></td></tr>
</table>
</form>