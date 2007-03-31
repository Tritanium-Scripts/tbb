<table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
<colgroup>
 <col width="15%"/>
 <col width="85%"/>
</colgroup>
<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('View_private_message')}</span></td></tr>
<tr>
 <td class="CellAlt"><span class="FontNorm">{$modules.Language->getString('Date')}:</span></td>
 <td class="CellAlt"><span class="FontNorm">{$pMData._PMSendDateTime}</span></td>
</tr>
<tr>
 <td class="CellAlt"><span class="FontNorm">{$modules.Language->getString('Subject')}:</span></td>
 <td class="CellAlt"><span class="FontNorm"><b>{$pMData._PMSubject}</b> {$pMData._PMSender}</span></td>
</tr>
<tr><td class="CellStd" colspan="2"><span class="FontNorm">{$pMData._PMMessageText}</span></td></tr>
</table>
{if $pMData.PMType == 0 && $pMData.PMFromID != 0}
 <br/>
 <form method="post" action="{$indexFile}?action=PrivateMessages&amp;mode=ViewPM&amp;PMID={$pMID}&amp;Doit=1&amp;{$mySID}" name="tbb_form">
 <table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
 <colgroup>
  <col width="20%"/>
  <col width="80%"/>
 </colgroup>
 <tr><td class="CellCat" colspan="2"><a name="Reply" id="Reply"></a><span class="FontCat">{$modules.Language->getString('Reply')}</span></td></tr>
 {if $error != ''}<tr><td class="CellError" colspan="2"><span class="FontError">{$error}</span></td></tr>{/if}
 <tr>
  <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Recipient')}:</span></td>
  <td class="CellAlt"><span class="FontNorm">{$pMData.PMFromNick}</span></td>
 </tr>
 <tr>
  <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Subject')}:</span></td>
  <td class="CellAlt"><input size="60" class="FormText" type="text" name="p[PMSubject]" value="{$p.PMSubject}" maxlength="255"/></td>
 </tr>
 <template:bbcoderow>
  <tr>
   <td class="CellStd" valign="top"></td>
   <td class="CellAlt">{$bbcode_box}</td>
  </tr>
 </template>
 <tr>
  <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('Message')}:</span></td>
  <td class="CellAlt"><textarea class="FormTextArea" rows="14" cols="80" name="p[PMMessageText]" onselect="storecaret();" onclick="storecaret();" onkeyup="storecaret();">{$p.PMMessageText}</textarea></td>
 </tr>
 <tr>
  <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('Options')}:</span></td>
  <td class="CellAlt"><span class="FontNorm">
   {if $show.EnableSmilies}<input type="checkbox" name="c[EnableSmilies]" value="1"{if $c.EnableSmilies == 1} checked="checked"{/if} id="iEnableSmilies"/><label for="iEnableSmilies">&nbsp;{$modules.Language->getString('Enable_smilies')}</label><br/>{/if}
   {if $show.ShowSignature}<input type="checkbox" name="c[ShowSignature]" value="1"{if $c.ShowSignature == 1} checked="checked"{/if} id="iShowSignature"/><label for="iShowSignature">&nbsp;{$modules.Language->getString('Show_signature')}</label><br/>{/if}
   {if $show.EnableBBCode}<input type="checkbox" name="c[EnableBBCode]" value="1"{if $c.EnableBBCode == 1} checked="checked"{/if} id="iEnableBBCode"/><label for="iEnableBBCode">&nbsp;{$modules.Language->getString('Enable_bbcode')}</label><br/>{/if}
   {if $show.EnableHtmlCode}<input type="checkbox" name="c[EnableHtmlCode]" value="1"{if $c.EnableHtmlCode == 1} checked="checked"{/if} id="iEnableHtmlCode"/><label for="iEnableHtmlCode">&nbsp;{$modules.Language->getString('Enable_html_code')}</label><br/>{/if}
   {if $show.SaveOutbox}<input type="checkbox" name="c[SaveOutbox]" value="1"{if $c.SaveOutbox == 1} checked="checked"{/if} id="iSaveOutbox"/><label for="iSaveOutbox">&nbsp;{$modules.Language->getString('Save_pm_outbox')}</label><br/>{/if}
   {if $show.RequestReadReceipt}<input type="checkbox" name="c[RequestReadReceipt]" value="1"{if $c.RequestReadReceipt == 1} checked="checked"{/if} id="iRequestReadReceipt"/><label for="iRequestReadReceipt">&nbsp;{$modules.Language->getString('Request_read_confirmation')}</label><br/>{/if}
  </span></td>
 </tr>
 <tr><td class="CellButtons" colspan="2" align="center"><input class="FormButton" type="submit" value="{$modules.Language->getString('Send_private_message')}"/>&nbsp;&nbsp;&nbsp;<input class="FormBButton" type="submit" name="ShowPreview" value="{$modules.Language->getString('Preview')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('Reset')}"/></td></tr>
 </table>
 </form>
{/if}
