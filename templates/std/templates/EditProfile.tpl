{* 0:nick - 1:id - 2:rankImage(s) - 3:mail - 4:rank - 5:posts - 6:regDate - 7:signature - 9:hp - 10:avatar - 12:realName - 13:icq - 14:mailOptions[ - 17:specialRank - 18:steamProfile - 19:steamGames] *}
<!-- EditProfile -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=profile&amp;mode=edit&amp;profile_id={$userData[1]}{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{$modules.Language->getString('my_profile')}</span></th></tr>
 <tr><td class="kat" colspan="2"><span class="kat">{$modules.Language->getString('change_user_data')}</span></td></tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('user_name_colon')}</span></td>
  <td class="td1" style="width:80%;"><span class="norm">{$userData[0]}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('email_address_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="new_mail" value="{$userData[3]}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('user_rank_colon')}</span></td>
  <td class="td1" style="width:80%;"><span class="norm">{if !empty($userData[17])}{$userData[17]}{else}{$userData[4]}{/if}&nbsp;{$userData[2]}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('posts_colon')}</span></td>
  <td class="td1" style="width:80%;"><span class="norm">{$userData[5]}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('user_id_colon')}</span></td>
  <td class="td1" style="width:80%;"><span class="norm">{$userData[1]}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('homepage_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="new_hp" value="{$userData[9]}" style="width:250px;" /> <span class="small">{$modules.Language->getString('http_dots')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%; vertical-align:top;"><span class="norm">{$modules.Language->getString('avatar_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="new_pic" value="{$userData[10]}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('real_name_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="new_realname" value="{$userData[12]}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('icq_number_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="new_icq" value="{$userData[13]}" style="width:250px;" /> <span class="small">{$modules.Language->getString('number_only_no_dashes')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="width:20%; vertical-align:top;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('signature_colon')}</span><br /><span class="small">{$modules.Language->getString('bbcode_and_smilies_are_enabled')}</span></td>
  <td class="td1" style="width:80%;"><textarea cols="55" rows="8" name="new_signatur">{$userData[7]}</textarea></td>
 </tr>
 <tr><td class="kat" colspan="2"><span class="kat">{$modules.Language->getString('steam_achievements')}</span></td></tr>
 <tr><td class="td1" colspan="2"><span class="small">{$modules.Language->getString('steam_achievements_info')}</span></td></tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('steam_profile_name_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="steamProfile" value="{$userData[18]}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="width:20%; vertical-align:top;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('steam_game_names_colon')}</span><br /><span class="small">{$modules.Language->getString('steam_game_names_info')}</span></td>
  <td class="td1" style="width:80%;"><textarea cols="55" rows="8" name="steamGames">{"\n"|implode:$userData[19]}</textarea></td>
 </tr>
 <tr><td class="kat" colspan="2"><span class="kat">{$modules.Language->getString('options')}</span></td></tr>
 <tr><td class="td1" colspan="2"><input type="checkbox" value="1" id="new_mail2" name="new_mail2" style="vertical-align:middle;"{if $userData[14][1] == '1'} checked="checked"{/if} /> <label for="new_mail2" class="norm">{$modules.Language->getString('show_email_address')}</label><br /><span class="small">{$modules.Language->getString('show_email_address_info')}</span></td></tr>
 <tr><td class="td1" colspan="2"><input type="checkbox" value="1" id="new_mail1" name="new_mail1" style="vertical-align:middle;"{if $userData[14][0] == '1'} checked="checked"{/if} /> <label for="new_mail1" class="norm">{$modules.Language->getString('receive_emails_from_forum')}</label><br /><span class="small">{$modules.Language->getString('receive_emails_from_forum_info')}</span></td></tr>
 <tr><td class="kat" colspan="2"><span class="kat">{$modules.Language->getString('change_password')}</span></td></tr>
 <tr><td class="td1" colspan="2"><span class="small">{$modules.Language->getString('change_password_info')}</span></td></tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('new_password_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="password" name="new_pw1" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('confirm_new_password_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="password" name="new_pw2" style="width:250px;" /></td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('change_profile')}" />&nbsp;&nbsp;<input type="submit" name="delete" value="{$modules.Language->getString('delete_account')}" /></p>
<input type="hidden" name="change" value="1" />
</form>