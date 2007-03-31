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
<form method="post" action="{$indexFile}?action=PrivateMessages&amp;FolderID={$folderID}&amp;z={$z}&amp;{$mySID}" name="TBBForm">
<table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <td class="CellCat" align="center"><input type="checkbox" name="p_checkall" onclick="switch_all(this.checked);" /></td>
 <td class="CellCat" align="center">&nbsp;</td>
 <td class="CellCat" align="center"><span class="FontTitle">{$modules.Language->getString('Subject')}</span></td>
 <td class="CellCat"><span class="FontTitle">&nbsp;</span></td>
 <td class="CellCat" align="center"><span class="FontTitle">{$modules.Language->getString('Date')}</span></td>
 <td class="CellCat" align="center"><span class="FontTitle">{$modules.Language->getString('Actions')}</span></td>
</tr>
{foreach from=$pMsData item=curPM}
 <tr>
  <td class="CellAlt" align="center"><input type="checkbox" value="{$curPM.PMID}" name="PMIDs[]" /></td>
  <td class="CellAlt" align="center"><img src="{if $curPM.PMIsRead == 1}{if $curPM.PMIsReplied == 1}{$modules.Template->getTemplateDir()}/images/icons/PrivateMessageReadReplied.png{else}{$modules.Template->getTemplateDir()}/images/icons/PrivateMessageRead.png{/if}{else}{$modules.Template->getTemplateDir()}/images/icons/PrivateMessageUnread.png{/if}" alt="" border="0"/></td>
  <td class="CellStd"><span class="FontNorm"><a href="{$indexFile}?action=PrivateMessages&amp;mode=ViewPM&amp;PMID={$curPM.PMID}&amp;{$mySID}">{if $curPM.PMIsRead == 1}{$curPM.PMSubject}{else}<b>{$curPM.PMSubject}</b>{/if}</a></span></td>
  <td class="CellAlt"><span class="FontSmall">{$curPM._PMSender}</span></td>
  <td class="CellStd" align="center"><span class="FontSmall">{$curPM._PMSendDateTime}</span></td>
  <td class="CellAlt" align="right"><span class="FontSmall"><a href="{$indexFile}?action=PrivateMessages&amp;mode=deletepms&amp;pm_id={$akt_pm.pm_id}&amp;{$mySID}"><img src="{$modules.Template->getTemplateDir()}/images/icons/PrivateMessageDelete.png" class="ImageIcon" alt="{$modules.Language->getString('delete')}" border="0"/></a>{if $curPM.PMType == 0 && $curPM.PMFromID != 0}<a href="{$indexFile}?action=PrivateMessages&amp;mode=ViewPM&amp;PMID={$curPM.PMID}&amp;{$mySID}#Reply"><img src="{$modules.Template->getTemplateDir()}/images/icons/PrivateMessageReadReplied.png" class="ImageIcon" alt="" border="0"/></a>{/if}</span></td>
 </tr>
{foreachelse}
 <tr><td class="CellStd" colspan="6" align="center"><span class="FontNorm">-- {$modules.Language->getString('No_messages_in_this_folder')} --</span></td></tr>
{/foreach}
<tr><td class="CellButtons" colspan="6"><select class="FormSelect" name="p_action" onchange="submit_form();"><option value=''>-- {$modules.Language->getString('Select_action')} --</option><option value=''></option><option value="{$indexFile}?action=PrivateMessages&amp;mode=markread&amp;return_f={$folderID}&amp;return_z={$z}&amp;{$mySID}">{$modules.Language->getString('Mark_as_read')}</option><option value="{$indexFile}?action=PrivateMessages&amp;mode=deletepms&amp;return_f={$folderID}&amp;return_z={$z}&amp;{$mySID}">{$modules.Language->getString('delete')}</option></select></td></tr>
</table>
</form>
