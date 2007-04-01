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
			if(document.TBBForm.elements[i].name == 'pmIDs[]') document.TBBForm.elements[i].checked = status;
		}
	}
{/literal}
</script>
<form method="post" action="{$indexFile}?action=PrivateMessages&amp;folderID={$folderID}&amp;page={$page}&amp;{$mySID}" name="TBBForm">
<table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <td class="CellCat" align="center"><input type="checkbox" name="p_checkall" onclick="switch_all(this.checked);" /></td>
 <td class="CellCat" align="center">&nbsp;</td>
 <td class="CellCat" align="center"><span class="FontTitle">{$modules.Language->getString('Subject')}</span></td>
 <td class="CellCat"><span class="FontTitle">&nbsp;</span></td>
 <td class="CellCat" align="center"><span class="FontTitle">{$modules.Language->getString('Date')}</span></td>
 <td class="CellCat" align="center"><span class="FontTitle">{$modules.Language->getString('Actions')}</span></td>
</tr>
{foreach from=$pmsData item=curPM}
 <tr>
  <td class="CellAlt" align="center"><input type="checkbox" value="{$curPM.pmID}" name="pmIDs[]" /></td>
  <td class="CellAlt" align="center"><img src="{if $curPM.pmIsRead == 1}{if $curPM.pmIsReplied == 1}{$modules.Template->getTemplateDir()}/images/icons/PrivateMessageReadReplied.png{else}{$modules.Template->getTemplateDir()}/images/icons/PrivateMessageRead.png{/if}{else}{$modules.Template->getTemplateDir()}/images/icons/PrivateMessageUnread.png{/if}" alt="" border="0"/></td>
  <td class="CellStd"><span class="FontNorm"><a href="{$indexFile}?action=PrivateMessages&amp;mode=ViewPM&amp;pmID={$curPM.pmID}&amp;{$mySID}">{if $curPM.pmIsRead == 1}{$curPM.pmSubject}{else}<b>{$curPM.pmSubject}</b>{/if}</a></span></td>
  <td class="CellAlt"><span class="FontSmall">{$curPM._pmSender}</span></td>
  <td class="CellStd" align="center"><span class="FontSmall">{$curPM._pmSendDateTime}</span></td>
  <td class="CellAlt" align="right"><span class="FontSmall"><a href="{$indexFile}?action=PrivateMessages&amp;mode=DeletePMs&amp;pmID={$curPM.pmID}&amp;{$mySID}"><img src="{$modules.Template->getTemplateDir()}/images/icons/PrivateMessageDelete.png" class="ImageIcon" alt="{$modules.Language->getString('delete')}" border="0"/></a>{if $curPM.pmType == 0 && $curPM.pmFromID != 0}<a href="{$indexFile}?action=PrivateMessages&amp;mode=ViewPM&amp;pmID={$curPM.pmID}&amp;{$mySID}#Reply"><img src="{$modules.Template->getTemplateDir()}/images/icons/PrivateMessageReadReplied.png" class="ImageIcon" alt="" border="0"/></a>{/if}</span></td>
 </tr>
{foreachelse}
 <tr><td class="CellStd" colspan="6" align="center"><span class="FontNorm">-- {$modules.Language->getString('No_messages_in_this_folder')} --</span></td></tr>
{/foreach}
<tr><td class="CellButtons" colspan="6"><select class="FormSelect" name="p_action" onchange="submit_form();"><option value=''>-- {$modules.Language->getString('Select_action')} --</option><option value=''></option><option value="{$indexFile}?action=PrivateMessages&amp;mode=markread&amp;returnFolderID{$folderID}&amp;returnPage={$page}&amp;{$mySID}">{$modules.Language->getString('Mark_as_read')}</option><option value="{$indexFile}?action=PrivateMessages&amp;mode=deletepms&amp;returnFolderID{$folderID}&amp;returnPage={$page}&amp;{$mySID}">{$modules.Language->getString('delete')}</option></select></td></tr>
</table>
</form>
