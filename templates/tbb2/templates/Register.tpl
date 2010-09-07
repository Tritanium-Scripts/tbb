<!-- Register -->
{include file='Errors.tpl'}
<form action="{$smarty.const.INDEXFILE}?faction=register{$smarty.const.SID_AMPER}" method="post">
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{$modules.Language->getString('register')}</span></th></tr>
 <tr>
  <td class="cellStd">
   <table border="0" cellpadding="2" cellspacing="0" style="width:100%;">
    <tr><td class="divInfoBox" colspan="2"><span class="fontNorm"><img src="{$modules.Template->getTplDir()}images/icons/info.png" class="imageIcon" alt="" /> {$modules.Language->getString('all_denoted_fields_are_mandatory')}</span></td></tr>
    <tr>
     <td style="font-weight:bold; padding:3px; width:30%;"><span class="fontNorm">{$modules.Language->getString('nick_colon_denoted')}</span></td>
     <td style="padding:3px; width:70%;"><input class="formText" maxlength="15" type="text" name="newuser_name" value="{$newUser.nick}" style="width:150px;" /> <span class="fontSmall">{$modules.Language->getString('maximal_15_chars')}</span></td>
    </tr>{if $modules.Config->getCfgVal('create_reg_pw') != 1}
    <tr>
     <td style="font-weight:bold; padding:3px; width:30%;"><span class="fontNorm">{$modules.Language->getString('password_colon_denoted')}</span></td>
     <td style="padding:3px; width:70%;"><input class="formText" type="password" name="newuser_pw1" style="width:150px;" /></td>
    </tr>
    <tr>
     <td style="font-weight:bold; padding:3px; width:30%;"><span class="fontNorm">{$modules.Language->getString('confirm_password_colon_denoted')}</span></td>
     <td style="padding:3px; width:70%;"><input class="formText" type="password" name="newuser_pw2" style="width:150px;" /></td>
    </tr>{/if}
    <tr>
     <td style="font-weight:bold; padding:3px; width:30%;"><span class="fontNorm">{$modules.Language->getString('email_address_colon_denoted')}</span></td>
     <td style="padding:3px; width:70%;"><input class="formText" type="text" name="newuser_email" value="{$newUser.mail}" style="width:150px;" /></td>
    </tr>
    <tr>
     <td style="font-weight:bold; padding:3px; width:30%;"><span class="fontNorm">{$modules.Language->getString('homepage_colon')}</span></td>
     <td style="padding:3px; width:70%;"><input class="formText" type="text" name="newuser_hp" value="{$newUser.homepage}" style="width:150px;" /> <span class="fontSmall">{$modules.Language->getString('http_dots')}</span></td>
    </tr>
    <tr>
     <td style="font-weight:bold; padding:3px; width:30%;"><span class="fontNorm">{$modules.Language->getString('real_name_colon')}</span></td>
     <td style="padding:3px; width:70%;"><input class="formText" type="text" name="newuser_realname" value="{$newUser.realName}" style="width:150px;" /></td>
    </tr>
    <tr>
     <td style="font-weight:bold; padding:3px; width:30%;"><span class="fontNorm">{$modules.Language->getString('icq_number_colon')}</span></td>
     <td style="padding:3px; width:70%;"><input class="formText" type="text" name="newuser_icq" value="{$newUser.icq}" style="width:150px;" /> <span class="fontSmall">{$modules.Language->getString('number_only_no_dashes')}</span></td>
    </tr>
    <tr>
     <td style="padding:3px; width:30%; vertical-align:top;"><span class="fontNorm" style="font-weight:bold;">{$modules.Language->getString('signature_colon')}</span><br /><span class="fontSmall">{$modules.Language->getString('signature_info')}</span></td>
     <td style="padding:3px; width:70%;"><textarea class="formTextArea" cols="40" rows="7" name="newuser_signatur">{$newUser.signature}</textarea></td>
    </tr>
    <tr><td colspan="2"><input type="checkbox" id="regeln" name="regeln" value="yes" /> <label for="regeln" class="fontNorm" style="font-weight:bold;">{$rulesLink|string_format:$modules.Language->getString('i_accept_board_rules_denoted')}</label></td></tr>
   </table>
  </td>
 </tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{$modules.Language->getString('register')}" /></p>
<input type="hidden" name="mode" value="createuser" />
</form>