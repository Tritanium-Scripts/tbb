{if !is_null($previewData)}
	<table class="TableStd" width="100%">
		<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('preview')}</span></td></tr>
		<colgroup>
			<col width="20%"/>
			<col width="80%"/>
		</colgroup>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('recipient')}:</span></td>
			<td class="CellAlt"><span class="FontNorm">{foreach from=$previewData.pmRecipients item=curRecipient}{$curRecipient.userNick} {/foreach}</span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('subject')}:</span></td>
			<td class="CellAlt"><span class="FontNorm">{$previewData.pmSubject}</span></td>
		</tr>
		<tr><td class="CellAlt" colspan="2"><span class="FontNorm">{$previewData.pmMessageText}</span></td></tr>
	</table>
	<br/>
{/if}

<form method="post" action="{$indexFile}?action=PrivateMessages&amp;mode=NewPM&amp;doit=1&amp;{$mySID}" name="myForm">
<table class="TableStd" width="100%">
<colgroup>
 <col width="20%"/>
 <col width="80%"/>
</colgroup>
<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('new_private_message')}</span></td></tr>
{include file=_ErrorRow.tpl colSpan=2}
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('recipient')}:</span><br/><span class="FontSmall">{$modules.Language->getString('recipient_info')}</span></td>
 <td class="CellAlt" valign="top"><input size="25" class="FormText" type="text" name="p[recipients]" value="{$p.recipients}"/></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('subject')}:</span></td>
 <td class="CellAlt"><input size="60" class="FormText" type="text" name="p[pmSubject]" value="{$p.pmSubject}" maxlength="255"/></td>
</tr>
<!--
 <tr>
  <td class="CellStd" valign="top"></td>
  <td class="CellAlt">{$bbcode_box}</td>
 </tr>
-->
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('message')}:</span></td>
 <td class="CellAlt"><textarea class="FormTextArea" rows="14" cols="80" name="p[pmMessageText]" onselect="storecaret();" onclick="storecaret();" onkeyup="storecaret();">{$p.pmMessageText}</textarea></td>
</tr>
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('options')}:</span></td>
 <td class="CellAlt"><span class="FontNorm">
  {if $show.enableSmilies}<label><input type="checkbox" name="c[enableSmilies]" value="1"{if $c.enableSmilies == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('enable_smilies')}</label><br/>{/if}
  {if $show.showSignature}<label><input type="checkbox" name="c[showSignature]" value="1"{if $c.showSignature == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('show_signature')}</label><br/>{/if}
  {if $show.enableBBCode}<label><input type="checkbox" name="c[enableBBCode]" value="1"{if $c.enableBBCode == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('enable_bbcode')}</label><br/>{/if}
  {if $show.enableHtmlCode}<label><input type="checkbox" name="c[enableHtmlCode]" value="1"{if $c.enableHtmlCode == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('enable_html_code')}</label><br/>{/if}
  {if $show.saveOutbox}<label><input type="checkbox" name="c[saveOutbox]" value="1"{if $c.saveOutbox == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('save_pm_outbox')}</label><br/>{/if}
  {if $show.requestReadReceipt}<label><input type="checkbox" name="c[requestReadReceipt]" value="1"{if $c.requestReadReceipt == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('request_read_confirmation')}</label><br/>{/if}
 </span></td>
</tr>
<tr><td class="CellButtons" colspan="2" align="center"><input type="submit" class="FormButton" value="{$modules.Language->getString('send_private_message')}"/>&nbsp;&nbsp;&nbsp;<input class="FormBButton" type="submit" name="showPreview" value="{$modules.Language->getString('preview')}"/>&nbsp;&nbsp;&nbsp;<input type="reset" class="FormButton" value="{$modules.Language->getString('reset')}"/></td></tr>
</table>
</form>
