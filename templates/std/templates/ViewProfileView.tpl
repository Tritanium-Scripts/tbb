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
 <td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('view_profile')}: {$profileData.userNick}</span></td>
 <td class="CellTitle" colspan="2" style="text-align:right;"><span class="FontTitle"><a href="{$smarty.const.INDEXFILE}?action=ViewProfile&amp;profileID={$profileID}&amp;mode=vCard&amp;{$smarty.const.MYSID}">{$modules.Language->getString('download_vcard')}</a></span></td>
</tr>
{if $modules.Config->getValue('enable_avatars') == 1 && $profileData.userAvatarAddress != ''}
<tr>
 <td class="CellStd" rowspan="7">
  <div id="Avatar" style="position:fixed; left:0; top:0; width:100%; height:100%; background-image:url({$profileData.userAvatarAddress}); background-repeat:no-repeat; background-position:center; background-color:#000000; cursor:pointer; z-index:1; visibility:hidden; opacity:0.9;" onclick="this.style.visibility='hidden';"></div>
  <img src="{$profileData.userAvatarAddress}" alt="{$profileData.userNick}'s {$modules.Language->getString('avatar')}" style="width:128px; height:128px; cursor:pointer;" onclick="document.getElementById('Avatar').style.visibility='visible';"/>
 </td>
</tr>
{/if}
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('user_name')}:</span></td>
 <td class="CellAlt"><span class="FontNorm">{$profileData.userNick}</span></td>
 <td class="CellAlt"><span class="FontNorm">{$modules.Language->getString('user_id')}: #{$profileData.userID}</span></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('email_address')}:</span></td>                
 <td class="CellAlt"><span class="FontNorm">{if $profileData.userHideEmailAddress != 1}<a href="mailto:{$profileData.userEmailAddress}">{$profileData.userEmailAddress}</a>{else}{$modules.Language->getString('email_address_hidden')}{/if}</span></td>
 <td class="CellAlt"><span class="FontNorm">{if $profileData.userReceiveEmails == 1 && $modules.Auth->isLoggedIn() == 1 && $modules.Config->getValue('enable_email_formular') == 1} <a href="{$smarty.const.INDEXFILE}?action=ViewProfile&amp;profileID={$profileID}&amp;mode=SendEmail&amp;{$smarty.const.MYSID}">[{$modules.Language->getString('send_email')}]</a>{/if}{if $modules.Auth->isLoggedIn() == 1} <a href="{$smarty.const.INDEXFILE}?action=PrivateMessages&amp;mode=NewPM&amp;recipients={$profileData.userNick}&amp;{$smarty.const.MYSID}">[{$modules.Language->getString('send_pm')}]</a>{/if}</span></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('homepage')}:</span></td>
 <td class="CellAlt" colspan="2"><span class="FontNorm">{$fieldsData.homepage._fieldValue}</span></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('register_date')}:</span></td>
 <td class="CellAlt"><span class="FontNorm">{$profileData._profileRegisterDate}</span></td>
 <td class="CellAlt"><span class="FontNorm">{$profileData._profileRegisterDateText}</span></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('posts')}:</span></td>
 <td class="CellAlt"><span class="FontNorm">{$profileData.userPostsCounter}</span></td>
 <td class="CellAlt"><span class="FontNorm">{$profileData._userPostsCounterText}</span></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('user_rank')}:</span></td>
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
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('extended')}</span></td></tr>
{foreach from=$fieldsData item=curField}
{if $curField.fieldIsLocked != 1}
<tr><td class="CellStd"><span class="FontNorm">{$curField.fieldName}:</span></td><td class="CellAlt"><span class="FontNorm">{$curField._fieldValue}</span></td></tr>
{/if}
{/foreach}
<tr><td class="CellAlt" colspan="2"><span class="FontNorm">[<a href="{$smarty.const.INDEXFILE}?action=Search&amp;p[searchAuthorPosts]={$profileData.userID}&amp;doit=1&amp;p[displayResults]=posts">{$profileData._searchPostsText}</a>]</span></td></tr>
<tr><td class="CellAlt" colspan="2"><span class="FontNorm">[<a href="{$smarty.const.INDEXFILE}?action=Search&amp;p[searchAuthorTopics]={$profileData.userID}&amp;doit=1">{$profileData._searchTopicsText}</a>]</span></td></tr>
</table>
{if $show.notesTable}
 <br/>
 <table class="TableStd" width="100%">
 <tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('notes_about_user')}</span></td></tr>
 {foreach from=$notesData item=curNote}
  <tr>
   <td class="CellAlt" valign="top" rowspan="2"><span class="FontNorm"><b><a href="{$smarty.const.INDEXFILE}?action=ViewProfile&amp;profileID={$curNote.userID}&amp;{$smarty.const.MYSID}">{$curNote.userNick}</a></b></span></td>
   <td class="CellAlt">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
     <td><span class="FontSmall">{$curNote._noteDate}</span></td>
     <td align="right"><span class="FontSmall">{if $modules.Auth->getValue('userIsAdmin') == 1 || $modules.Auth->getValue('userID') == $curNote.userID}<a href="{$smarty.const.INDEXFILE}?action=ViewProfile&amp;profileID={$profileID}&amp;mode=DeleteNote&amp;noteID={$curNote.noteID}&amp;{$smarty.const.MYSID}">{$modules.Language->getString('delete_note')}</a> | <a href="{$smarty.const.INDEXFILE}?action=ViewProfile&amp;profileID={$profileID}&amp;mode=EditNote&amp;noteID={$curNote.noteID}&amp;{$smarty.const.MYSID}">{$modules.Language->getString('edit_note')}</a>{/if}</span></td>
    </tr>
    </table>
   </td>
  </tr>
  <tr><td class="CellStd"><span class="FontNorm">{$curNote._noteText}</span></td></tr>
 {foreachelse}
  <tr><td class="CellStd" align="center"><span class="FontNorm">{$modules.Language->getString('no_notes')}</span></td></tr>
 {/foreach}
 </table>
 <br/>
 <table class="TableStd" width="100%">
 <tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('other_options')}</span></td></tr>
 <tr><td class="CellStd"><span class="FontNorm"><a href="{$smarty.const.INDEXFILE}?action=ViewProfile&amp;profileID={$profileID}&amp;mode=AddNote&amp;{$smarty.const.MYSID}">{$modules.Language->getString('add_note')}</a></span></td></tr>
 <tr><td class="CellStd"><span class="FontNorm"><a href="{$smarty.const.INDEXFILE}?action=AdminUsers&amp;mode=EditUser&amp;userID={$profileID}&amp;{$smarty.const.MYSID}">{$modules.Language->getString('user')} {$modules.Language->getString('edit')}</a></span></td></tr>
 </table>
{/if}