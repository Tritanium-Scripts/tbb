<!-- Register -->
{include file='Errors.tpl'}
<form action="{$smarty.const.INDEXFILE}?faction=register{$smarty.const.SID_AMPER}" method="post">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('register')}</span></th></tr>
 <tr>
  <td class="cellStd">
   <table border="0" cellpadding="2" cellspacing="0" style="width:100%;">
    <tr><td class="divInfoBox" colspan="2"><span class="fontNorm"><img src="{Template::getInstance()->getTplDir()}images/icons/info.png" alt="" class="imageIcon" /> {Language::getInstance()->getString('all_denoted_fields_are_mandatory')}</span></td></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_REGISTER_FORM_START}
    <tr>
     <td style="font-weight:bold; padding:3px; width:30%;"><span class="fontNorm">{Language::getInstance()->getString('nick_colon_denoted')}</span></td>
     <td style="padding:3px; width:70%;"><input class="formText" maxlength="15" type="text" name="newuser_name" value="{$newUser.nick}" style="width:150px;" /> <span class="fontSmall">{Language::getInstance()->getString('maximal_15_chars')}</span></td>
    </tr>{if Config::getInstance()->getCfgVal('create_reg_pw') != 1}
    <tr>
     <td style="font-weight:bold; padding:3px; width:30%;"><span class="fontNorm">{Language::getInstance()->getString('password_colon_denoted')}</span></td>
     <td style="padding:3px; width:70%;"><input class="formText" type="password" name="newuser_pw1" style="width:150px;" /></td>
    </tr>
    <tr>
     <td style="font-weight:bold; padding:3px; width:30%;"><span class="fontNorm">{Language::getInstance()->getString('confirm_password_colon_denoted')}</span></td>
     <td style="padding:3px; width:70%;"><input class="formText" type="password" name="newuser_pw2" style="width:150px;" /></td>
    </tr>{/if}
    <tr>
     <td style="font-weight:bold; padding:3px; width:30%;"><span class="fontNorm">{Language::getInstance()->getString('email_address_colon_denoted')}</span></td>
     <td style="padding:3px; width:70%;"><input class="formText" type="text" name="newuser_email" value="{$newUser.mail}" style="width:150px;" /></td>
    </tr>
    <tr>
     <td style="font-weight:bold; padding:3px; width:30%;"><span class="fontNorm">{Language::getInstance()->getString('homepage_colon')}</span></td>
     <td style="padding:3px; width:70%;"><input class="formText" type="text" name="newuser_hp" value="{$newUser.homepage}" style="width:150px;" /> <span class="fontSmall">{Language::getInstance()->getString('http_dots')}</span></td>
    </tr>
    <tr>
     <td style="font-weight:bold; padding:3px; width:30%;"><span class="fontNorm">{Language::getInstance()->getString('real_name_colon')}</span></td>
     <td style="padding:3px; width:70%;"><input class="formText" type="text" name="newuser_realname" value="{$newUser.realName}" style="width:150px;" /></td>
    </tr>
    <tr>
     <td style="font-weight:bold; padding:3px; width:30%;"><span class="fontNorm">{Language::getInstance()->getString('birthday_colon')}</span></td>
     <td style="padding:3px; width:70%;">{{html_select_date prefix='' time=$newUser.birthday end_year=$maxYearOfBirth reverse_years=true field_array='birthday' field_order=Language::getInstance()->getString('DATE_FIELD_ORDER') field_separator=Language::getInstance()->getString('DATE_SEPARATOR') all_extra='class="formSelect"' all_empty=''}|utf8_encode}</td>
    </tr>
    <tr>
     <td style="font-weight:bold; padding:3px; width:30%;"><span class="fontNorm">{Language::getInstance()->getString('icq_number_colon')}</span></td>
     <td style="padding:3px; width:70%;"><input class="formText" type="text" name="newuser_icq" value="{$newUser.icq}" style="width:150px;" /> <span class="fontSmall">{Language::getInstance()->getString('number_only_no_dashes')}</span></td>
    </tr>
    <tr>
     <td style="padding:3px; width:30%; vertical-align:top;"><span class="fontNorm" style="font-weight:bold;">{Language::getInstance()->getString('signature_colon')}</span><br /><span class="fontSmall">{Language::getInstance()->getString('signature_info')}</span></td>
     <td style="padding:3px; width:70%;"><textarea class="formTextArea" cols="60" rows="8" name="newuser_signatur">{$newUser.signature}</textarea></td>
    </tr>
{plugin_hook hook=PlugIns::HOOK_TPL_REGISTER_FORM_END}
    <tr><td colspan="2"><input type="checkbox" id="regeln" name="regeln" value="yes" /> <label for="regeln" class="fontNorm" style="font-weight:bold;">{$rulesLink|string_format:Language::getInstance()->getString('i_accept_board_rules_denoted')}</label></td></tr>{if !empty($privacyPolicyLink)}
    <tr><td colspan="2"><input type="checkbox" id="privacyPolicy" name="privacyPolicy" value="yes" /> <label for="privacyPolicy" class="fontNorm" style="font-weight:bold;">{$privacyPolicyLink|string_format:Language::getInstance()->getString('i_accept_privacy_policy_denoted')}</label></td></tr>{/if}
   </table>
  </td>
 </tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('register')}" />{plugin_hook hook=PlugIns::HOOK_TPL_REGISTER_BUTTONS}</p>
<input type="hidden" name="mode" value="createuser" />
</form>