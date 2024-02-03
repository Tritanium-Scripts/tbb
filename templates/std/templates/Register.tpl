<!-- Register -->
{include file='Errors.tpl'}
<form action="{$smarty.const.INDEXFILE}?faction=register{$smarty.const.SID_AMPER}" method="post">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{Language::getInstance()->getString('register')}</span></th></tr>
 <tr><td class="td1" colspan="2"><span class="small">{Language::getInstance()->getString('all_denoted_fields_are_mandatory')}</span></td></tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:30%;"><span class="norm">{Language::getInstance()->getString('nick_colon_denoted')}</span></td>
  <td class="td1" style="width:70%;"><input maxlength="15" type="text" name="newuser_name" value="{$newUser.nick}" style="width:150px;" /> <span class="small">{Language::getInstance()->getString('maximal_15_chars')}</span></td>
 </tr>{if Config::getInstance()->getCfgVal('create_reg_pw') != 1}
 <tr>
  <td class="td1" style="font-weight:bold; width:30%;"><span class="norm">{Language::getInstance()->getString('password_colon_denoted')}</span></td>
  <td class="td1" style="width:70%;"><input type="password" name="newuser_pw1" style="width:150px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:30%;"><span class="norm">{Language::getInstance()->getString('confirm_password_colon_denoted')}</span></td>
  <td class="td1" style="width:70%;"><input type="password" name="newuser_pw2" style="width:150px;" /></td>
 </tr>{/if}
 <tr>
  <td class="td1" style="font-weight:bold; width:30%;"><span class="norm">{Language::getInstance()->getString('email_address_colon_denoted')}</span></td>
  <td class="td1" style="width:70%;"><input type="text" name="newuser_email" value="{$newUser.mail}" style="width:150px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:30%;"><span class="norm">{Language::getInstance()->getString('homepage_colon')}</span></td>
  <td class="td1" style="width:70%;"><input type="text" name="newuser_hp" value="{$newUser.homepage}" style="width:150px;" /> <span class="small">{Language::getInstance()->getString('http_dots')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:30%;"><span class="norm">{Language::getInstance()->getString('real_name_colon')}</span></td>
  <td class="td1" style="width:70%;"><input type="text" name="newuser_realname" value="{$newUser.realName}" style="width:150px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:30%;"><span class="norm">{Language::getInstance()->getString('birthday_colon')}</span></td>
  <td class="td1" style="width:70%;">{{html_select_date prefix='' time=$newUser.birthday end_year=$maxYearOfBirth reverse_years=true field_array='birthday' field_order=Language::getInstance()->getString('DATE_FIELD_ORDER') field_separator=Language::getInstance()->getString('DATE_SEPARATOR') all_empty=''}|utf8_encode}</td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:30%;"><span class="norm">{Language::getInstance()->getString('icq_number_colon')}</span></td>
  <td class="td1" style="width:70%;"><input type="text" name="newuser_icq" value="{$newUser.icq}" style="width:150px;" /> <span class="small">{Language::getInstance()->getString('number_only_no_dashes')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="width:30%; vertical-align:top;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('signature_colon')}</span><br /><span class="small">{Language::getInstance()->getString('signature_info')}</span></td>
  <td class="td1" style="width:70%;"><textarea cols="40" rows="7" name="newuser_signatur">{$newUser.signature}</textarea></td>
 </tr>
 <tr><td class="td1" colspan="2"><input type="checkbox" id="regeln" name="regeln" value="yes" style="vertical-align:middle;" /> <label for="regeln" class="norm">{$rulesLink|string_format:Language::getInstance()->getString('i_accept_board_rules_denoted')}</label></td></tr>{if !empty($privacyPolicyLink)}
 <tr><td class="td1" colspan="2"><input type="checkbox" id="privacyPolicy" name="privacyPolicy" value="yes" style="vertical-align:middle;" /> <label for="privacyPolicy" class="norm">{$privacyPolicyLink|string_format:Language::getInstance()->getString('i_accept_privacy_policy_denoted')}</label></td></tr>{/if}
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('register')}" /></p>
<input type="hidden" name="mode" value="createuser" />
</form>