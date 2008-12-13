<script type="text/javascript">/* <![CDATA[ */
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
/* ]]> */</script>
<form method="post" action="{$smarty.const.INDEXFILE}?action=PrivateMessages&amp;folderID={$folderID}&amp;page={$page}&amp;{$smarty.const.MYSID}" name="myForm">
<table class="TableStd" width="100%">
<tr>
 <td class="CellCat" align="center"><input type="checkbox" name="p_checkall" onclick="switchAllCheckboxes(this.checked);" /></td>
 <td class="CellCat" align="center">&nbsp;</td>
 <td class="CellCat" align="center"><span class="FontTitle">{$modules.Language->getString('subject')}</span></td>
 <td class="CellCat"><span class="FontTitle">&nbsp;</span></td>
 <td class="CellCat" align="center"><span class="FontTitle">{$modules.Language->getString('date')}</span></td>
 <td class="CellCat" align="center"><span class="FontTitle">{$modules.Language->getString('actions')}</span></td>
</tr>
{foreach from=$pmsData item=curPM}
 <tr>
  <td class="CellAlt" align="center"><input type="checkbox" value="{$curPM.pmID}" name="pmIDs[]" /></td>
  <td class="CellAlt" align="center"><img src="{if $curPM.pmIsRead == 1}{if $curPM.pmIsReplied == 1}{$modules.Template->getTemplateDir()}/images/icons/PrivateMessageReadReplied.png{else}{$modules.Template->getTemplateDir()}/images/icons/PrivateMessageRead.png{/if}{else}{$modules.Template->getTemplateDir()}/images/icons/PrivateMessageUnread.png{/if}" alt=""/></td>
  <td class="CellStd"><span class="FontNorm"><a href="{$smarty.const.INDEXFILE}?action=PrivateMessages&amp;mode=ViewPM&amp;pmID={$curPM.pmID}&amp;{$smarty.const.MYSID}">{if $curPM.pmIsRead == 1}{$curPM.pmSubject}{else}<b>{$curPM.pmSubject}</b>{/if}</a></span><br/><span class="FontSmall">{$curPM._pmMessageTextShort}</span></td>
  <td class="CellAlt"><span class="FontSmall">{$curPM._pmSender}</span></td>
  <td class="CellStd" align="center"><span class="FontSmall">{$curPM._pmSendDateTime}</span></td>
  <td class="CellAlt" align="right"><span class="FontSmall"><a href="{$smarty.const.INDEXFILE}?action=PrivateMessages&amp;mode=DeletePMs&amp;pmID={$curPM.pmID}&amp;returnFolderID={$folderID}&amp;returnPage={$page}&amp;{$smarty.const.MYSID}"><img src="{$modules.Template->getTemplateDir()}/images/icons/PrivateMessageDelete.png" class="ImageIcon" alt="{$modules.Language->getString('delete')}"/></a>{if $curPM.pmType == 0 && $curPM.pmFromID != 0}<a href="{$smarty.const.INDEXFILE}?action=PrivateMessages&amp;mode=ViewPM&amp;pmID={$curPM.pmID}&amp;{$smarty.const.MYSID}#Reply"><img src="{$modules.Template->getTemplateDir()}/images/icons/PrivateMessageReadReplied.png" class="ImageIcon" alt=""/></a>{/if}</span></td>
 </tr>
{foreachelse}
 <tr><td class="CellStd" colspan="6" align="center"><span class="FontNorm">-- {$modules.Language->getString('no_messages_in_this_folder')} --</span></td></tr>
{/foreach}
<tr><td class="CellButtons" colspan="6">
 <select class="FormSelect" name="selectAction" onchange="submitMyForm();">
  <option value="">-- {$modules.Language->getString('select_action')} --</option>
  <option value=""></option>
  <option value="{$smarty.const.INDEXFILE}?action=PrivateMessages&amp;mode=MarkPMsRead&amp;returnFolderID={$folderID}&amp;returnPage={$page}&amp;{$smarty.const.MYSID}">{$modules.Language->getString('mark_as_read')}</option>
  <option value="{$smarty.const.INDEXFILE}?action=PrivateMessages&amp;mode=DeletePMs&amp;returnFolderID={$folderID}&amp;returnPage={$page}&amp;{$smarty.const.MYSID}">{$modules.Language->getString('delete')}</option>
  <option value=""></option>
  {foreach from=$foldersData item=curFolder}
   {if $curFolder.folderID != $folderID}
    <option value="{$smarty.const.INDEXFILE}?action=PrivateMessages&amp;mode=MovePMs&amp;returnFolderID={$folderID}&amp;returnPage={$page}&amp;targetFolderID={$curFolder.folderID}&amp;{$smarty.const.MYSID}">{$curFolder._moveText}</option>
   {/if}
  {/foreach}
 </select>
</td></tr>
</table>
</form>
