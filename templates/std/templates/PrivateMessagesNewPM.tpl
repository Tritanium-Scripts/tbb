<form method="post" action="{$IndexFile}?Action=PrivateMessages&amp;Mode=NewPM&amp;Doit=1&amp;{$MySID}" name="tbb_form">
<table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
<colgroup>
 <col width="20%"/>
 <col width="80%"/>
</colgroup>
<tr><td class="CellCat" colspan="2"><span class="FontCat">{$Modules.Language->getString('New_private_message')}</span></td></tr>
{if $Error != ''}<tr><td class="CellError" colspan="2"><span class="FontError">{$Error}</span></td></tr>{/if}
<tr>
 <td class="CellStd"><span class="FontNorm">{$Modules.Language->getString('Recipient')}:</span><br/><span class="FontSmall">{$Modules.Language->getString('recipient_info')}</span></td>
 <td class="CellAlt" valign="top"><input size=25" class="FormText" type="text" name="p[Recipients]" value="{$p.Recipients}"/></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$Modules.Language->getString('Subject')}:</span></td>
 <td class="CellAlt"><input size="60" class="FormText" type="text" name="p[PMSubject]" value="{$p.PMSubject}" maxlength="255"/></td>
</tr>
<template:bbcoderow>
 <tr>
  <td class="CellStd" valign="top"></td>
  <td class="CellAlt">{$bbcode_box}</td>
 </tr>
</template>
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$Modules.Language->getString('Message')}:</span></td>
 <td class="CellAlt"><textarea class="FormTextArea" rows="14" cols="80" name="p[PMMessageText]" onselect="storecaret();" onclick="storecaret();" onkeyup="storecaret();">{$p.PMMessageText}</textarea></td>
</tr>
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$Modules.Language->getString('Options')}:</span></td>
 <td class="CellAlt"><span class="FontNorm">
  {if $Show.EnableSmilies}<input type="checkbox" name="c[EnableSmilies]" value="1"{if $c.EnableSmilies == 1} checked="checked"{/if} id="iEnableSmilies"/><label for="iEnableSmilies">&nbsp;{$Modules.Language->getString('Enable_smilies')}</label><br/>{/if}
  {if $Show.ShowSignature}<input type="checkbox" name="c[ShowSignature]" value="1"{if $c.ShowSignature == 1} checked="checked"{/if} id="iShowSignature"/><label for="iShowSignature">&nbsp;{$Modules.Language->getString('Show_signature')}</label><br/>{/if}
  {if $Show.EnableBBCode}<input type="checkbox" name="c[EnableBBCode]" value="1"{if $c.EnableBBCode == 1} checked="checked"{/if} id="iEnableBBCode"/><label for="iEnableBBCode">&nbsp;{$Modules.Language->getString('Enable_bbcode')}</label><br/>{/if}
  {if $Show.EnableHtmlCode}<input type="checkbox" name="c[EnableHtmlCode]" value="1"{if $c.EnableHtmlCode == 1} checked="checked"{/if} id="iEnableHtmlCode"/><label for="iEnableHtmlCode">&nbsp;{$Modules.Language->getString('Enable_html_code')}</label><br/>{/if}
  {if $Show.SaveOutbox}<input type="checkbox" name="c[SaveOutbox]" value="1"{if $c.SaveOutbox == 1} checked="checked"{/if} id="iSaveOutbox"/><label for="iSaveOutbox">&nbsp;{$Modules.Language->getString('Save_pm_outbox')}</label><br/>{/if}
  {if $Show.RequestReadReceipt}<input type="checkbox" name="c[RequestReadReceipt]" value="1"{if $c.RequestReadReceipt == 1} checked="checked"{/if} id="iRequestReadReceipt"/><label for="iRequestReadReceipt">&nbsp;{$Modules.Language->getString('Request_read_confirmation')}</label><br/>{/if}
 </span></td>
</tr>
<tr><td class="CellButtons" colspan="2" align="center"><input type="submit" class="FormButton" value="{$Modules.Language->getString('Send_private_message')}"/>&nbsp;&nbsp;&nbsp;<input class="FormBButton" type="submit" name="ShowPreview" value="{$Modules.Language->getString('Preview')}"/>&nbsp;&nbsp;&nbsp;<input type="reset" class="FormButton" value="{$Modules.Language->getString('Reset')}"/></td></tr>
</table>
</form>
