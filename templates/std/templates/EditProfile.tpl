<script type="text/javascript">
function refreshGames()
{
	var ajax = window.XMLHttpRequest ? new XMLHttpRequest() : (window.ActiveXObject ? new ActiveXObject('Microsoft.XMLHTTP') : null);
	if(ajax == null)
	{
		alert('{*$modules.Language->getString('your_browser_does_not_support_ajax')*}');
		return;
	}
	ajax.open('POST', '{$smarty.const.INDEXFILE}', false);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	ajax.send('faction=profile&mode=refreshSteamGames&profile_id={$userData[1]}{$smarty.const.SID_AMPER_RAW}');
	var ajaxResponse = eval('(' + ajax.responseText + ')'); //Evaluate JSON string
	for(var i=0; i<ajaxResponse.errors.length; i++)
		alert(ajaxResponse.errors[i]);
	if(ajaxResponse.errors.length == 0)
		for(var i=0; i<ajaxResponse.values.length; i++)
		{
			var curInput = document.createElement('input');
			curInput.type = 'checkbox';
			curInput.name = 'steamGames[]';
			curInput.id = curInput.value = ajaxResponse.values[i].gameID;
			curInput.checked = ajaxResponse.values[i].selected;
			document.getElementById('steamGames').appendChild(curInput);
			document.getElementById('steamGames').appendChild(document.createTextNode(curInput.value));
		}
}
</script>

{* 0:nick - 1:id - 2:rankImage(s) - 3:mail - 4:rank - 5:posts - 6:regDate - 7:signature - 9:hp - 10:avatar - 12:realName - 13:icq - 14:mailOptions[ - 17:specialState - 18:steamProfile - 19:steamGames] *}
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
 </tr>{if $modules.Config->getCfgVal('achievements') == 1}
 <tr><td class="kat" colspan="2"><span class="kat">{$modules.Language->getString('steam_achievements')}</span></td></tr>
 <tr><td class="td1" colspan="2"><span class="small">{$modules.Language->getString('steam_achievements_info')}</span></td></tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('steam_profile_name_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="steamProfile" value="{$userData[18]}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="width:20%; vertical-align:top;">
   <span class="norm" style="font-weight:bold;">{$modules.Language->getString('steam_game_names_colon')}</span><br />
   <span class="small">{$modules.Language->getString('steam_game_names_info')}</span>{if !empty($userData[18])}<br />
   <button type="button" onclick="refreshGames();">Refresh</button>{/if}
  </td>
  <td class="td1" id="steamGames" style="width:80%;">{foreach $userData[19] as $curGameName}<input type="checkbox" id="{$curGameName}" name="steamGames[]" value="{$curGameName}" /> <label for="{$curGameName}">{$curGameName}</label><br />{/foreach}</td>
 </tr>{/if}
 <tr><td class="kat" colspan="2"><span class="kat">{$modules.Language->getString('options')}</span></td></tr>
 <tr><td class="td1" colspan="2"><input type="checkbox" value="1" id="new_mail2" name="new_mail2" style="vertical-align:middle;"{if $userData[14][1] == '1'} checked="checked"{/if} /> <label for="new_mail2" class="norm">{$modules.Language->getString('show_email_address')}</label><br /><span class="small">{$modules.Language->getString('show_email_address_info')}</span></td></tr>
 <tr><td class="td1" colspan="2"><input type="checkbox" value="1" id="new_mail1" name="new_mail1" style="vertical-align:middle;"{if $userData[14][0] == '1'} checked="checked"{/if} /> <label for="new_mail1" class="norm">{$modules.Language->getString('receive_emails_from_forum')}</label><br /><span class="small">{$modules.Language->getString('receive_emails_from_forum_info')}</span></td></tr>{if $modules.Config->getCfgVal('select_tpls') == 1}
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('template_colon')}</span></td>
  <td class="td1" style="width:80%;"><select name="ownTemplate" style="width:250px;"><option value="">{$modules.Language->getString('default_brackets')}</option>{foreach $templates as $curTplID => $curTemplate}<option value="{$curTplID}"{if $curTplID == $userData[20]} selected="selected"{/if}>{$curTemplate.name}</option>{/foreach}</select></td>
 </tr>{/if}{if $modules.Config->getCfgVal('select_styles') == 1}{if empty($userData[20])}{$userData[20] = $modules.Config->getCfgVal('default_tpl')}{/if}
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('style_colon')}</span></td>
  <td class="td1" style="width:80%;"><select name="ownStyle" style="width:250px;"><option value="">{$modules.Language->getString('default_brackets')}</option>{foreach $templates[$userData[20]]['styles'] as $curStyle}<option value="{$curStyle}"{if $curStyle == $userData[21]} selected="selected"{/if}>{$curStyle}</option>{/foreach}</select></td>
 </tr>{/if}
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