<table class="TableStd" width="100%">
<colgroup>
 <col width="15%"/>
 <col width="85%"/>
</colgroup>
<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('View_private_message')}</span></td></tr>
<tr>
 <td class="CellAlt"><span class="FontNorm">{$modules.Language->getString('Date')}:</span></td>
 <td class="CellAlt"><span class="FontNorm">{$pmData._pmSendDateTime}</span></td>
</tr>
<tr>
 <td class="CellAlt"><span class="FontNorm">{$modules.Language->getString('Subject')}:</span></td>
 <td class="CellAlt"><span class="FontNorm"><b>{$pmData._pmSubject}</b> {$pmData._pmSender}</span></td>
</tr>
<tr><td class="CellStd" colspan="2"><span class="FontNorm">{$pmData._pmMessageText}</span></td></tr>
</table>
{if $pmData.pmType == 0 && $pmData.pmFromID != 0}
	{if !is_null($previewData)}
		<br/>
		<table class="TableStd" width="100%">
			<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('Preview')}</span></td></tr>
			<colgroup>
				<col width="20%"/>
				<col width="80%"/>
			</colgroup>
			<tr>
				<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Subject')}:</span></td>
				<td class="CellAlt"><span class="FontNorm">{$previewData.pmSubject}</span></td>
			</tr>
			<tr><td class="CellAlt" colspan="2"><span class="FontNorm">{$previewData.pmMessageText}</span></td></tr>
		</table>
	{/if}
 <br/>
 <form method="post" action="{$indexFile}?action=PrivateMessages&amp;mode=ViewPM&amp;pmID={$pmID}&amp;doit=1&amp;{$mySID}" name="tbb_form">
 <table class="TableStd" width="100%">
 <colgroup>
  <col width="20%"/>
  <col width="80%"/>
 </colgroup>
 <tr><td class="CellCat" colspan="2"><a name="Reply" id="Reply"></a><span class="FontCat">{$modules.Language->getString('Reply')}</span></td></tr>
 {include file=_ErrorRow.tpl colSpan=2}
 <tr>
  <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Recipient')}:</span></td>
  <td class="CellAlt"><span class="FontNorm">{$pmData.pmFromNick}</span></td>
 </tr>
 <tr>
  <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Subject')}:</span></td>
  <td class="CellAlt"><input size="60" class="FormText" type="text" name="p[pmSubject]" value="{$p.pmSubject}" maxlength="255"/></td>
 </tr>
 <!--
  <tr>
   <td class="CellStd" valign="top"></td>
   <td class="CellAlt">{$bbcode_box}</td>
  </tr>
 -->
 <tr>
  <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('Message')}:</span></td>
  <td class="CellAlt"><textarea class="FormTextArea" rows="14" cols="80" name="p[pmMessageText]" onselect="storecaret();" onclick="storecaret();" onkeyup="storecaret();">{$p.pmMessageText}</textarea></td>
 </tr>
 <tr>
  <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('Options')}:</span></td>
  <td class="CellAlt"><span class="FontNorm">
   {if $show.enableSmilies}<label><input type="checkbox" name="c[enableSmilies]" value="1"{if $c.enableSmilies == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('Enable_smilies')}</label><br/>{/if}
   {if $show.showSignature}<label><input type="checkbox" name="c[showSignature]" value="1"{if $c.showSignature == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('Show_signature')}</label><br/>{/if}
   {if $show.enableBBCode}<label><input type="checkbox" name="c[enableBBCode]" value="1"{if $c.enableBBCode == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('Enable_bbcode')}</label><br/>{/if}
   {if $show.enableHtmlCode}<label><input type="checkbox" name="c[enableHtmlCode]" value="1"{if $c.enableHtmlCode == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('Enable_html_code')}</label><br/>{/if}
   {if $show.saveOutbox}<label><input type="checkbox" name="c[saveOutbox]" value="1"{if $c.saveOutbox == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('Save_pm_outbox')}</label><br/>{/if}
   {if $show.requestReadReceipt}<label><input type="checkbox" name="c[requestReadReceipt]" value="1"{if $c.requestReadReceipt == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('Request_read_confirmation')}</label><br/>{/if}
  </span></td>
 </tr>
 <tr><td class="CellButtons" colspan="2" align="center"><input class="FormButton" type="submit" value="{$modules.Language->getString('Send_private_message')}"/>&nbsp;&nbsp;&nbsp;<input class="FormBButton" type="submit" name="showPreview" value="{$modules.Language->getString('Preview')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('Reset')}"/></td></tr>
 </table>
 </form>
{/if}
