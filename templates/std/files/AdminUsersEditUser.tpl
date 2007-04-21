<form method="post" action="{$indexFile}?action=AdminUsers&amp;mode=EditUser&amp;userID={$userData.userID}&amp;doit=1&amp;{$mySID}">
<table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('Edit_user')}</span></td></tr>
{if $error != ''}<tr><td class="CellError" colspan="2"><span class="FontError">{$error}</span></td></tr>{/if}
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('User_id')}:</span></td>
 <td class="CellAlt"><span class="FontNorm">{$userData.userID}</span></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('User_name')}:</span></td>
 <td class="CellAlt"><span class="FontNorm">{$userData.userNick}</span></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Email_address')}:</span></td>
 <td class="CellAlt"><input class="FormText" type="text" name="p[userEmailAddress]" value="{$p.userEmailAddress}" size="40" /></td>
</tr>
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('Signature')}:</span></td>
 <td class="CellAlt" valign="top"><textarea cols="50" rows="5" class="FormTextarea">{$p.userSignature}</textarea></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Avatar')}:</span></td>
 <td class="CellAlt"><input class="FormText" type="text" name="p[userAvatarAddress]" value="{$p.userAvatarAddress}" size="60" /></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Special_rank')}:</span></td>
  <td class="CellAlt"><select class="FormSelect" name="p[rankID]">
  <option value="0"{if $p.rankID == 0} selected="selected"{/if}>-- {$modules.Language->getString('No_special_rank')} --</option>
  {foreach from=$ranksData item=curRank}
   <option value="{$curRank.rankID}"{if $p.rankID == $curRank.rankID} selected="selected"{/if}>{$curRank.rankName}</option>
  {/foreach}
 </select></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Profile_notes')}:</span></td>
 <td class="CellAlt">
  <select class="FormSelect" name="p[userAuthProfileNotes]">
   <option value="1"{if $p.userAuthProfileNotes == 1} selected="selected"{/if}>{$modules.Language->getString('Allow')}</option>
   <option value="1"{if $p.userAuthProfileNotes == 2} selected="selected"{/if}>{$modules.Language->getString('Use_default')}</option>
   <option value="1"{if $p.userAuthProfileNotes == 0} selected="selected"{/if}>{$modules.Language->getString('Disallow')}</option>
  </select>
 </td>
</tr>
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('Options')}:</span></td>
 <td class="CellAlt" valign="top"><span class="FontNorm">
  <label><input type="checkbox" name="c[userIsAdmin]" value="1"{if $c.userIsAdmin == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('User_is_admin')}</label><br/>
  <label><input type="checkbox" name="c[userIsSupermod]" value="1"{if $c.userIsSupermod == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('User_is_supermod')}</label><br/>
 </span></td>
</tr>
<tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('Edit_user')}" />&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('Reset')}" /></td></tr>
</table>
</form>
<br/>
{if $userData.userIsLocked != 0}
 <form method="post" action="{$indexFile}?action=AdminUsers&amp;mode=UnlockUser&amp;userID={$userData.userID}&amp;{$mySID}">
 <table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
 <tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('Unlock_user')}</span></td></tr>
 <tr>
  <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Ban_type')}:</span></td>
  <td class="CellAlt"><span class="FontNorm">{if $lockData.lockType == 1}{$modules.Language->getString('User_must_not_login')}{else}{$modules.Language->getString('User_must_not_write')}{/if}</span></td>
 </tr>
 <tr>
  <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Remaining_lock_time')}:</span></td>
  <td class="CellAlt"><span class="FontNorm">{$remainingLockTime}</span></td>
 </tr>
 <tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('Unlock_user')}" /></td></tr>
 </table>
 <br/>
 </form>
{else}
 <form method="post" action="{$indexFile}?action=AdminUsers&amp;mode=LockUser&amp;userID={$userData.userID}&amp;{$mySID}">
 <table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
 <tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('Lock_user')}</span></td></tr>
 <tr>
  <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Ban_type')}:</span></td>
  <td class="CellAlt"><select class="FormSelect" name="p[lockType]"><option value="1">{$modules.Language->getString('User_must_not_login')}</option><option value="2">{$modules.Language->getString('User_must_not_write')}</option></select></td>
 </tr>
 <tr>
  <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Ban_time')}:</span></td>
  <td class="CellAlt"><input class="FormText" type="text" name="p[lockTime]"/> <span class="FontSmall">({$modules.Language->getString('ban_time_info')})</span></td>
 </tr>
 <tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('Lock_user')}" />&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('Reset')}" /></td></tr>
 </table>
 </form>
 <br/>
{/if}
<form method="post" action="administration.php?action=ad_users&amp;mode=deleteuser&amp;user_id={$user_id}&amp;{$MYSID}">
<table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
 <tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('Delete_user')}</span></td></tr>
 <tr><td class="CellStd"><span class="FontNorm"><label><input type="checkbox" name="p_delete_posts" value="1" />&nbsp;{$modules.Language->getString('Delete_users_posts')}</label></span></td></tr>
 <tr><td class="CellStd"><span class="FontNorm"><label><input type="checkbox" name="p_ban_nick_email" value="1" checked="checked" />&nbsp;{$modules.Language->getString('Ban_nick_email')}</label></span></td></tr>
 <tr><td class="CellButtons" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('Delete_user')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('Reset')}"/></td></tr>
</table>
</form>
