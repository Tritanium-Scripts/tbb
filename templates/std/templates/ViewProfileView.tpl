<table class="TableStd" width="100%">
<colgroup>
 {if $modules.Config->getValue('enable_avatars') == 1 && $profileData.userAvatarAddress != ''}<col width="129"/>{/if}
<!--
 <col width="20%"/>
 <col width="40%"/>
 <col width="40%"/>
-->
</colgroup>
<tr>
 <td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('View_profile')}: {$profileData.userNick}</span></td>
 <td class="CellTitle" colspan="2" style="text-align:right;"><span class="FontTitle"><a href="{$indexFile}?action=ViewProfile&amp;profileID={$profileID}&amp;mode=vCard&amp;{$mySID}">{$modules.Language->getString('Download_vcard')}</a></span></td>
</tr>
{if $modules.Config->getValue('enable_avatars') == 1 && $profileData.userAvatarAddress != ''}
<tr><td class="CellStd" rowspan="7"><img src="{$profileData.userAvatarAddress}" width="128" height="128" alt="{$profileData.userNick}'s {$modules.Language->getString('Avatar')}"/></td></tr>
{/if}
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('User_name')}:</span></td>
 <td class="CellAlt"><span class="FontNorm">{$profileData.userNick}</span></td>
 <td class="CellAlt"><span class="FontNorm">{$modules.Language->getString('User_id')}: #{$profileData.userID}</span></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Email_address')}:</span></td>                
 <td class="CellAlt"><span class="FontNorm">{if $profileData.userHideEmailAddress != 1}<a href="mailto:{$profileData.userEmailAddress}">{$profileData.userEmailAddress}</a>{else}{$modules.Language->getString('Email_address_hidden')}{/if}</span></td>
 <td class="CellAlt"><span class="FontNorm">{if $profileData.userReceiveEmails == 1 && $modules.Auth->isLoggedIn() == 1 && $modules.Config->getValue('enable_email_formular') == 1} <a href="{$indexFile}?action=ViewProfile&amp;profileID={$profileID}&amp;mode=SendEmail&amp;{$mySID}">[{$modules.Language->getString('Send_email')}]</a>{/if}{if $modules.Auth->isLoggedIn() == 1} <a href="{$indexFile}?action=PrivateMessages&amp;mode=NewPM&amp;recipients={$profileData.userNick}&amp;{$mySID}">[{$modules.Language->getString('Send_pm')}]</a>{/if}</span></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Homepage')}:</span></td>
 <td class="CellAlt" colspan="2"><span class="FontNorm"><!-- TODO --></span></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Register_date')}:</span></td>
 <td class="CellAlt"><span class="FontNorm">{$profileData._profileRegisterDate}</span></td>
 <td class="CellAlt"><span class="FontNorm">{$profileData._profileRegisterDateText}</span></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Posts')}:</span></td>
 <td class="CellAlt"><span class="FontNorm">{$profileData.userPostsCounter}</span></td>
 <td class="CellAlt"><span class="FontNorm">{$profileData._userPostsCounterText}</span></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('User_rank')}:</span></td>
 <td class="CellAlt"><span class="FontNorm">{$profileData._profileRankText}</span></td>
 <td class="CellAlt"><span class="FontNorm">{$profileData._profileRankPic}</span></td>
</tr>
{if $modules.Config->getValue('enable_sig') == 1}
<tr>
 <td colspan="4" class="CellAlt"><span class="FontNorm">{$profileData.userSignature}</span></td>
</tr>
{/if}
</table>
<br/>
<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('Extended')}</span></td></tr>
{foreach from=$fieldsData item=curField}
<tr><td class="CellStd"><span class="FontNorm">{$curField.fieldName}:</span></td><td class="CellAlt"><span class="FontNorm"></span></td></tr>
{/foreach}
<tr><td class="CellAlt" colspan="2"><span class="FontNorm">[<a href="{$indexFile}?action=Search&amp;mode=">{$profileData._SearchPostsText}</a>]</span></td></tr>
<tr><td class="CellAlt" colspan="2"><span class="FontNorm">[<a href="{$indexFile}?action=Search&amp;mode=">{$profileData._SearchTopicsText}</a>]</span></td></tr>
</table>
{if $show.notesTable}
 <br/>
 <table class="TableStd" width="100%">
 <tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('Notes_about_user')}</span></td></tr>
 {foreach from=$notesData item=curNote}
  <tr>
   <td class="CellAlt" valign="top" rowspan="2"><span class="FontNorm"><b><a href="{$indexFile}?action=ViewProfile&amp;profileID={$curNote.userID}&amp;{$mySID}">{$curNote.userNick}</a></b></span></td>
   <td class="CellAlt">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
     <td><span class="FontSmall">{$curNote._noteDate}</span></td>
     <td align="right"><span class="FontSmall">{if $modules.Auth->getValue('userIsAdmin') == 1 || $modules.Auth->getValue('userID') == $curNote.userID}<a href="{$indexFile}?action=ViewProfile&amp;profileID={$profileID}&amp;mode=DeleteNote&amp;noteID={$curNote.noteID}&amp;{$mySID}">{$modules.Language->getString('Delete_note')}</a> | <a href="{$indexFile}?action=ViewProfile&amp;profileID={$profileID}&amp;mode=EditNote&amp;noteID={$curNote.noteID}&amp;{$mySID}">{$modules.Language->getString('Edit_note')}</a>{/if}</span></td>
    </tr>
    </table>
   </td>
  </tr>
  <tr><td class="CellStd"><span class="FontNorm">{$curNote._noteText}</span></td></tr>
 {foreachelse}
  <tr><td class="CellStd" align="center"><span class="FontNorm">{$modules.Language->getString('No_notes')}</span></td></tr>
 {/foreach}
 </table>
 <br/>
 <table class="TableStd" width="100%">
 <tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('Other_options')}</span></td></tr>
 <tr><td class="CellStd"><span class="FontNorm"><a href="{$indexFile}?action=ViewProfile&amp;profileID={$profileID}&amp;mode=AddNote&amp;{$mySID}">{$modules.Language->getString('Add_note')}</a></span></td></tr>
 <tr><td class="CellStd"><span class="FontNorm"><a href="{$indexFile}?action=AdminUsers&amp;mode=EditUser&amp;userID={$profileID}&amp;{$mySID}">{$modules.Language->getString('User')} {$modules.Language->getString('Edit')}</a></span></td></tr>
 </table>
{/if}