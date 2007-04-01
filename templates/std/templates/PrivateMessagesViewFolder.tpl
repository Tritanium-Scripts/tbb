<script type="text/javascript">
{literal}
	function submitMyForm() {
		if(document.forms.myForm.elements.selectAction.options[document.forms.myForm.elements.selectAction.selectedIndex].value != '') {
			document.forms.myForm.action = document.forms.myForm.elements.selectAction.options[document.forms.myForm.elements.selectAction.selectedIndex].value;
			document.forms.myForm.submit();
		}
	}

	function switchAllCheckboxes(status) {
		for(i = 0; i < document.myForm.length; i++) {
			if(document.myForm.elements[i].name == 'pmIDs[]') document.myForm.elements[i].checked = status;
		}
	}
{/literal}
</script>
<form method="post" action="{$indexFile}?action=PrivateMessages&amp;folderID={$folderID}&amp;page={$page}&amp;{$mySID}" name="myForm">
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
  <td class="CellAlt" align="right"><span class="FontSmall"><a href="{$indexFile}?action=PrivateMessages&amp;mode=DeletePMs&amp;pmID={$curPM.pmID}&amp;returnFolderID={$folderID}&amp;returnPage={$page}&amp;{$mySID}"><img src="{$modules.Template->getTemplateDir()}/images/icons/PrivateMessageDelete.png" class="ImageIcon" alt="{$modules.Language->getString('delete')}" border="0"/></a>{if $curPM.pmType == 0 && $curPM.pmFromID != 0}<a href="{$indexFile}?action=PrivateMessages&amp;mode=ViewPM&amp;pmID={$curPM.pmID}&amp;{$mySID}#Reply"><img src="{$modules.Template->getTemplateDir()}/images/icons/PrivateMessageReadReplied.png" class="ImageIcon" alt="" border="0"/></a>{/if}</span></td>
 </tr>
{foreachelse}
 <tr><td class="CellStd" colspan="6" align="center"><span class="FontNorm">-- {$modules.Language->getString('No_messages_in_this_folder')} --</span></td></tr>
{/foreach}
<tr><td class="CellButtons" colspan="6">
 <select class="FormSelect" name="selectAction" onchange="submitMyForm();">
  <option value="">-- {$modules.Language->getString('Select_action')} --</option>
  <option value=""></option>
  <option value="{$indexFile}?action=PrivateMessages&amp;mode=MarkPMsRead&amp;returnFolderID={$folderID}&amp;returnPage={$page}&amp;{$mySID}">{$modules.Language->getString('Mark_as_read')}</option>
  <option value="{$indexFile}?action=PrivateMessages&amp;mode=DeletePMs&amp;returnFolderID={$folderID}&amp;returnPage={$page}&amp;{$mySID}">{$modules.Language->getString('delete')}</option>
  <option value=""></option>
  {foreach from=$foldersData item=curFolder}
   {if $curFolder.folderID != $folderID}
    <option value="{$indexFile}?action=PrivateMessages&amp;mode=MovePMs&amp;returnFolderID={$folderID}&amp;returnPage={$page}&amp;targetFolderID={$curFolder.folderID}&amp;{$mySID}">{$curFolder._moveText}</option>
   {/if}
  {/foreach}
 </select>
</td></tr>
</table>
</form>
