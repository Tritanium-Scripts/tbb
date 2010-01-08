<form method="post" action="{$smarty.const.INDEXFILE}?action=AdminUsers&amp;mode=EditUser&amp;userID={$userData.userID}&amp;doit=1&amp;{$smarty.const.MYSID}">
<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('edit_user')}</span></td></tr>
{if $error != ''}<tr><td class="CellError" colspan="2"><span class="FontError">{$error}</span></td></tr>{/if}
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('user_id')}:</span></td>
 <td class="CellAlt"><span class="FontNorm">{$userData.userID}</span></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('user_name')}:</span></td>
 <td class="CellAlt"><span class="FontNorm">{$userData.userNick}</span></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('email_address')}:</span></td>
 <td class="CellAlt"><input class="FormText" type="text" name="p[userEmailAddress]" value="{$p.userEmailAddress}" size="40" /></td>
</tr>
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('signature')}:</span></td>
 <td class="CellAlt" valign="top"><textarea class="FormTextArea" cols="50" rows="5" name="p[userSignature]">{$p.userSignature}</textarea></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('avatar')}:</span></td>
 <td class="CellAlt"><input class="FormText" type="text" name="p[userAvatarAddress]" value="{$p.userAvatarAddress}" size="60" /></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('special_rank')}:</span></td>
  <td class="CellAlt"><select class="FormSelect" name="p[rankID]">
  <option value="0"{if $p.rankID == 0} selected="selected"{/if}>-- {$modules.Language->getString('no_special_rank')} --</option>
  {foreach from=$ranksData item=curRank}
   <option value="{$curRank.rankID}"{if $p.rankID == $curRank.rankID} selected="selected"{/if}>{$curRank.rankName}</option>
  {/foreach}
 </select></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('profile_notes')}:</span></td>
 <td class="CellAlt">
  <select class="FormSelect" name="p[userAuthProfileNotes]">
   <option value="1"{if $p.userAuthProfileNotes == 1} selected="selected"{/if}>{$modules.Language->getString('allow')}</option>
   <option value="1"{if $p.userAuthProfileNotes == 2} selected="selected"{/if}>{$modules.Language->getString('use_default')}</option>
   <option value="1"{if $p.userAuthProfileNotes == 0} selected="selected"{/if}>{$modules.Language->getString('disallow')}</option>
  </select>
 </td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('file_upload')}:</span></td>
 <td class="CellAlt">
  <select class="FormSelect" name="p[userAuthUpload]">
   <option value="1"{if $p.userAuthUpload == 1} selected="selected"{/if}>{$modules.Language->getString('allow')}</option>
   <option value="1"{if $p.userAuthUpload == 2} selected="selected"{/if}>{$modules.Language->getString('use_default')}</option>
   <option value="1"{if $p.userAuthUpload == 0} selected="selected"{/if}>{$modules.Language->getString('disallow')}</option>
  </select>
 </td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('file_download')}:</span></td>
 <td class="CellAlt">
  <select class="FormSelect" name="p[userAuthDownload]">
   <option value="1"{if $p.userAuthDownload == 1} selected="selected"{/if}>{$modules.Language->getString('allow')}</option>
   <option value="1"{if $p.userAuthDownload == 2} selected="selected"{/if}>{$modules.Language->getString('use_default')}</option>
   <option value="1"{if $p.userAuthDownload == 0} selected="selected"{/if}>{$modules.Language->getString('disallow')}</option>
  </select>
 </td>
</tr>
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('options')}:</span></td>
 <td class="CellAlt" valign="top"><span class="FontNorm">
  <label><input type="checkbox" name="c[userIsAdmin]" value="1"{if $c.userIsAdmin == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('user_is_admin')}</label><br/>
  <label><input type="checkbox" name="c[userIsSupermod]" value="1"{if $c.userIsSupermod == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('user_is_supermod')}</label><br/>
 </span></td>
</tr>
<tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('edit_user')}" />&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('reset')}" /></td></tr>
</table>
</form>
<br/>
{if $userData.userIsLocked != $smarty.const.LOCK_TYPE_NO_LOCK}
 <form method="post" action="{$smarty.const.INDEXFILE}?action=AdminUsers&amp;mode=UnlockUser&amp;userID={$userData.userID}&amp;{$smarty.const.MYSID}">
 <table class="TableStd" width="100%">
 <tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('unlock_user')}</span></td></tr>
 <tr>
  <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('ban_type')}:</span></td>
  <td class="CellAlt"><span class="FontNorm">{if $userData.userIsLocked == 1}{$modules.Language->getString('user_must_not_login')}{else}{$modules.Language->getString('user_must_not_write')}{/if}</span></td>
 </tr>
 <tr>
  <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('remaining_lock_time')}:</span></td>
  <td class="CellAlt"><span class="FontNorm">{$remainingLockTime}</span></td>
 </tr>
 <tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('unlock_user')}" /></td></tr>
 </table>
 <br/>
 </form>
{else}
 <form method="post" action="{$smarty.const.INDEXFILE}?action=AdminUsers&amp;mode=LockUser&amp;userID={$userData.userID}&amp;{$smarty.const.MYSID}">
 <table class="TableStd" width="100%">
 <tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('lock_user')}</span></td></tr>
 <tr>
  <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('ban_type')}:</span></td>
  <td class="CellAlt"><select class="FormSelect" name="p[lockType]"><option value="1">{$modules.Language->getString('user_must_not_login')}</option><option value="2">{$modules.Language->getString('user_must_not_write')}</option></select></td>
 </tr>
 <tr>
  <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('ban_time')}:</span></td>
  <td class="CellAlt"><input class="FormText" type="text" name="p[lockTime]"/> <span class="FontSmall">({$modules.Language->getString('ban_time_info')})</span></td>
 </tr>
 <tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('lock_user')}" />&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('reset')}" /></td></tr>
 </table>
 </form>
 <br/>
{/if}
<form method="post" action="{$smarty.const.INDEXFILE}?action=AdminUsers&amp;mode=DeleteUser&amp;userID={$userData.userID}&amp;{$smarty.const.MYSID}">
<table class="TableStd" width="100%">
 <tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('delete_user')}</span></td></tr>
 <tr><td class="CellStd"><span class="FontNorm"><label><input type="checkbox" name="c[deletePosts]" value="1"/>&nbsp;{$modules.Language->getString('delete_users_posts')}</label></span></td></tr>
 <tr><td class="CellStd"><span class="FontNorm"><label><input type="checkbox" name="c[deleteTopics]" value="1"/>&nbsp;{$modules.Language->getString('delete_users_topics')}</label></span></td></tr>
 <tr><td class="CellStd"><span class="FontNorm"><label><input type="checkbox" name="c[deleteSentPMs]" value="1"/>&nbsp;{$modules.Language->getString('delete_users_sent_pms')}</label></span></td></tr>
 {*<tr><td class="CellStd"><span class="FontNorm"><label><input type="checkbox" name="p_ban_nick_email" value="1" checked="checked" />&nbsp;{$modules.Language->getString('ban_nick_email')}</label></span></td></tr>*}
 <tr><td class="CellButtons" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('delete_user')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('reset')}"/></td></tr>
</table>
</form>
