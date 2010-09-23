{* 0:nick - 1:id - 2:rankImage(s) - 3:mail - 4:rank - 5:posts - 6:regDate - 7:signature - 9:hp - 10:avatar - 12:realName - 13:icq - 14:mailOptions[ - 17:specialState - 18:steamProfile - 19:steamGames] *}
<!-- EditProfile -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=profile&amp;mode=edit&amp;profile_id={$userData[1]}{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{$modules.Language->getString('my_profile')}</span></th></tr>
 <tr><td class="cellCat"><span class="fontCat">{$modules.Language->getString('change_user_data')}</span></td></tr>
 <tr>
  <td class="cellStd">
   <table border="0" cellpadding="2" cellspacing="0" style="width:100%;">
    <tr>
     <td style="padding:3px; width:20%;"><span class="fontNorm">{$modules.Language->getString('user_name_colon')}</span></td>
     <td style="padding:3px; width:80%;"><span class="fontNorm">{$userData[0]}</span></td>
    </tr>
    <tr>
     <td style="padding:3px; width:20%;"><span class="fontNorm">{$modules.Language->getString('email_address_colon')}</span></td>
     <td style="padding:3px; width:80%;"><input class="formText" type="text" name="new_mail" value="{$userData[3]}" style="width:250px;" /></td>
    </tr>
    <tr>
     <td style="padding:3px; width:20%;"><span class="fontNorm">{$modules.Language->getString('user_rank_colon')}</span></td>
     <td style="padding:3px; width:80%;"><span class="fontNorm">{if !empty($userData[17])}{$userData[17]}{else}{$userData[4]}{/if}&nbsp;{$userData[2]}</span></td>
    </tr>
    <tr>
     <td style="padding:3px; width:20%;"><span class="fontNorm">{$modules.Language->getString('posts_colon')}</span></td>
     <td style="padding:3px; width:80%;"><span class="fontNorm">{$userData[5]}</span></td>
    </tr>
    <tr>
     <td style="padding:3px; width:20%;"><span class="fontNorm">{$modules.Language->getString('user_id_colon')}</span></td>
     <td style="padding:3px; width:80%;"><span class="fontNorm">{$userData[1]}</span></td>
    </tr>
    <tr>
     <td style="padding:3px; width:20%;"><span class="fontNorm">{$modules.Language->getString('homepage_colon')}</span></td>
     <td style="padding:3px; width:80%;"><input class="formText" type="text" name="new_hp" value="{$userData[9]}" style="width:250px;" /> <span class="fontSmall">{$modules.Language->getString('http_dots')}</span></td>
    </tr>
    <tr>
     <td style="padding:3px; width:20%; vertical-align:top;"><span class="fontNorm">{$modules.Language->getString('avatar_colon')}</span></td>
     <td style="padding:3px; width:80%;"><input class="formText" type="text" name="new_pic" value="{$userData[10]}" style="width:250px;" /></td>
    </tr>
    <tr>
     <td style="padding:3px; width:20%;"><span class="fontNorm">{$modules.Language->getString('real_name_colon')}</span></td>
     <td style="padding:3px; width:80%;"><input class="formText" type="text" name="new_realname" value="{$userData[12]}" style="width:250px;" /></td>
    </tr>
    <tr>
     <td style="padding:3px; width:20%;"><span class="fontNorm">{$modules.Language->getString('icq_number_colon')}</span></td>
     <td style="padding:3px; width:80%;"><input class="formText" type="text" name="new_icq" value="{$userData[13]}" style="width:250px;" /> <span class="fontSmall">{$modules.Language->getString('number_only_no_dashes')}</span></td>
    </tr>
    <tr>
     <td style="padding:3px; width:20%; vertical-align:top;"><span class="fontNorm">{$modules.Language->getString('signature_colon')}</span><br /><span class="fontSmall">{$modules.Language->getString('bbcode_and_smilies_are_enabled')}</span></td>
     <td style="padding:3px; width:80%;"><textarea class="formTextArea" cols="60" rows="8" name="new_signatur">{$userData[7]}</textarea></td>
    </tr>
   </table>
  </td>
 </tr>
 <tr><td class="cellCat"><span class="fontCat">{$modules.Language->getString('steam_achievements')}</span></td></tr>
 <tr>
  <td class="cellStd">
   <table border="0" cellpadding="2" cellspacing="0" style="width:100%;">
    <tr><td class="divInfoBox" colspan="2"><span class="fontSmall"><img src="{$modules.Template->getTplDir()}images/icons/info.png" alt="" class="imageIcon" /> {$modules.Language->getString('steam_achievements_info')}</span></td></tr>
    <tr>
     <td style="padding:3px; width:20%;"><span class="fontNorm">{$modules.Language->getString('steam_profile_name_colon')}</span></td>
     <td style="padding:3px; width:80%;"><input class="formText" type="text" name="steamProfile" value="{$userData[18]}" style="width:250px;" /></td>
    </tr>
    <tr>
     <td style="padding:3px; width:20%; vertical-align:top;"><span class="fontNorm">{$modules.Language->getString('steam_game_names_colon')}</span><br /><span class="fontSmall">{$modules.Language->getString('steam_game_names_info')}</span></td>
     <td style="padding:3px; width:80%;"><textarea class="formTextArea" cols="60" rows="8" name="steamGames">{"\n"|implode:$userData[19]}</textarea></td>
    </tr>
   </table>
  </td>
 </tr>
 <tr><td class="cellCat"><span class="fontCat">{$modules.Language->getString('options')}</span></td></tr>
 <tr>
  <td class="cellStd">
   <table border="0" cellpadding="2" cellspacing="0" style="width:100%;">
    <tr><td colspan="2"><input type="checkbox" value="1" id="new_mail2" name="new_mail2"{if $userData[14][1] == '1'} checked="checked"{/if} /> <label for="new_mail2" class="fontNorm">{$modules.Language->getString('show_email_address')}</label><br /><span class="fontSmall">{$modules.Language->getString('show_email_address_info')}</span></td></tr>
    <tr><td colspan="2"><input type="checkbox" value="1" id="new_mail1" name="new_mail1"{if $userData[14][0] == '1'} checked="checked"{/if} /> <label for="new_mail1" class="fontNorm">{$modules.Language->getString('receive_emails_from_forum')}</label><br /><span class="fontSmall">{$modules.Language->getString('receive_emails_from_forum_info')}</span></td></tr>{if $modules.Config->getCfgVal('select_tpls') == 1}
    <tr>
     <td style="padding:3px; width:20%;"><span class="fontNorm">{$modules.Language->getString('template_colon')}</span></td>
     <td style="padding:3px; width:80%;"><select class="formSelect" name="ownTemplate" style="width:250px;"><option value="">{$modules.Language->getString('default_brackets')}</option>{foreach $templates as $curTplID => $curTemplate}<option value="{$curTplID}"{if $curTplID == $userData[20]} selected="selected"{/if}>{$curTemplate.name}</option>{/foreach}</select></td>
    </tr>{/if}{if $modules.Config->getCfgVal('select_styles') == 1}{if empty($userData[20])}{$userData[20] = $modules.Config->getCfgVal('default_tpl')}{/if}
    <tr>
     <td style="padding:3px; width:20%;"><span class="fontNorm">{$modules.Language->getString('style_colon')}</span></td>
     <td style="padding:3px; width:80%;"><select class="formSelect" name="ownStyle" style="width:250px;"><option value="">{$modules.Language->getString('default_brackets')}</option>{foreach $templates[$userData[20]]['styles'] as $curStyle}<option value="{$curStyle}"{if $curStyle == $userData[21]} selected="selected"{/if}>{$curStyle}</option>{/foreach}</select></td>
    </tr>{/if}
   </table>
  </td>
 </tr>
 <tr><td class="cellCat"><span class="fontCat">{$modules.Language->getString('change_password')}</span></td></tr>
 <tr>
  <td class="cellStd">
   <table border="0" cellpadding="2" cellspacing="0" style="width:100%;">
    <tr><td class="divInfoBox" colspan="2"><span class="fontNorm"><img src="{$modules.Template->getTplDir()}images/icons/info.png" alt="" class="imageIcon" /> {$modules.Language->getString('change_password_info')}</span></td></tr>
    <tr>
     <td style="padding:3px; width:20%;"><span class="fontNorm">{$modules.Language->getString('new_password_colon')}</span></td>
     <td style="padding:3px; width:80%;"><input class="formText" type="password" name="new_pw1" style="width:250px;" /></td>
    </tr>
    <tr>
     <td style="padding:3px; width:20%;"><span class="fontNorm">{$modules.Language->getString('confirm_new_password_colon')}</span></td>
     <td style="padding:3px; width:80%;"><input class="formText" type="password" name="new_pw2" style="width:250px;" /></td>
    </tr>
   </table>
  </td>
 </tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{$modules.Language->getString('change_profile')}" />&nbsp;&nbsp;<input class="formButton" type="submit" name="delete" value="{$modules.Language->getString('delete_account')}" /></p>
<input type="hidden" name="change" value="1" />
</form>