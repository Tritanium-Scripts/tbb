<script type="text/javascript">
{literal}
	function submit_form() {
		if(document.forms.TBBForm.elements.p_action.options[document.forms.TBBForm.elements.p_action.selectedIndex].value != '') {
			document.forms.TBBForm.action = document.forms.TBBForm.elements.p_action.options[document.forms.TBBForm.elements.p_action.selectedIndex].value;
			document.forms.TBBForm.submit();
		}
	}

	function switch_all(status) {
		for(i = 0; i < document.TBBForm.length; i++) {
			if(document.TBBForm.elements[i].name == 'PMIDs[]') document.TBBForm.elements[i].checked = status;
		}
	}
{/literal}
</script>
<form method="post" action="{$IndexFile}?Action=PrivateMessages&amp;FolderID={$FolderID}&amp;z={$z}&amp;{$MySID}" name="TBBForm">
<table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <td class="CellCat" align="center"><input type="checkbox" name="p_checkall" onclick="switch_all(this.checked);" /></td>
 <td class="CellCat" align="center">&nbsp;</td>
 <td class="CellCat" align="center"><span class="FontTitle">{$Modules.Language->getString('Subject')}</span></td>
 <td class="CellCat"><span class="FontTitle">&nbsp;</span></td>
 <td class="CellCat" align="center"><span class="FontTitle">{$Modules.Language->getString('Date')}</span></td>
 <td class="CellCat" align="center"><span class="FontTitle">{$Modules.Language->getString('Actions')}</span></td>
</tr>
{foreach from=$PMsData item=curPM}
 <tr>
  <td class="CellAlt" align="center"><input type="checkbox" value="{$curPM.PMID}" name="PMIDs[]" /></td>
  <td class="CellAlt" align="center"><img src="{if $curPM.PMIsRead == 1}{if $curPM.PMIsReplied == 1}{$Modules.Template->getTemplateDir()}/images/icons/PrivateMessageReadReplied.png{else}{$Modules.Template->getTemplateDir()}/images/icons/PrivateMessageRead.png{/if}{else}{$Modules.Template->getTemplateDir()}/images/icons/PrivateMessageUnread.png{/if}" alt="" border="0"/></td>
  <td class="CellStd"><span class="FontNorm"><a href="{$IndexFile}?Action=PrivateMessages&amp;Mode=ViewPM&amp;PMID={$curPM.PMID}&amp;{$MySID}">{if $curPM.PMIsRead == 1}{$curPM.PMSubject}{else}<b>{$curPM.PMSubject}</b>{/if}</a></span></td>
  <td class="CellAlt"><span class="FontSmall">{$curPM._PMSender}</span></td>
  <td class="CellStd" align="center"><span class="FontSmall">{$curPM._PMSendDateTime}</span></td>
  <td class="CellAlt" align="right"><span class="FontSmall"><a href="{$IndexFile}?Action=PrivateMessages&amp;Mode=deletepms&amp;pm_id={$akt_pm.pm_id}&amp;{$MySID}"><img src="{$Modules.Template->getTemplateDir()}/images/icons/PrivateMessageDelete.png" class="ImageIcon" alt="{$Modules.Language->getString('delete')}" border="0"/></a>{if $curPM.PMType == 0 && $curPM.PMFromID != 0}<a href="{$IndexFile}?Action=PrivateMessages&amp;Mode=ViewPM&amp;PMID={$curPM.PMID}&amp;{$MySID}#Reply"><img src="{$Modules.Template->getTemplateDir()}/images/icons/PrivateMessageReadReplied.png" class="ImageIcon" alt="" border="0"/></a>{/if}</span></td>
 </tr>
{foreachelse}
 <tr><td class="CellStd" colspan="6" align="center"><span class="FontNorm">-- {$Modules.Language->getString('No_messages_in_this_folder')} --</span></td></tr>
{/foreach}
<tr><td class="CellButtons" colspan="6"><select class="FormSelect" name="p_action" onchange="submit_form();"><option value=''>-- {$Modules.Language->getString('Select_action')} --</option><option value=''></option><option value="{$IndexFile}?Action=PrivateMessages&amp;Mode=markread&amp;return_f={$FolderID}&amp;return_z={$z}&amp;{$MySID}">{$Modules.Language->getString('Mark_as_read')}</option><option value="{$IndexFile}?Action=PrivateMessages&amp;Mode=deletepms&amp;return_f={$FolderID}&amp;return_z={$z}&amp;{$MySID}">{$Modules.Language->getString('delete')}</option></select></td></tr>
</table>
</form>
