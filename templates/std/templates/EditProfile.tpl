{* 0:nick - 1:id - 2:rankImage(s) - 3:mail - 4:rank - 5:posts - 6:regDate - 7:signature - 9:hp - 10:avatar - 12:realName - 13:icq - 14:mailOptions[ - 17:specialState - 18:steamProfile - 19:steamGames] *}
<!-- EditProfile -->
<script type="text/javascript">
/* <![CDATA[ */
/**
 * Refreshes selectable Steam games via Ajax.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 */
function refreshGames()
{
	//Create Ajax instance
	var ajax = window.XMLHttpRequest ? new XMLHttpRequest() : (window.ActiveXObject ? new ActiveXObject('Microsoft.XMLHTTP') : null);
	if(ajax == null)
	{
		alert('{Language::getInstance()->getString('your_browser_does_not_support_ajax')}');
		return;
	}
	//Open synchronous connection
	ajax.open('POST', '{$smarty.const.INDEXFILE}', false);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	//Send request
	ajax.send('faction=profile&mode=refreshSteamGames&profile_id={$userData[1]}{$smarty.const.SID_AMPER_RAW}');
	//Wait for response
	var ajaxResponse = eval('(' + ajax.responseText + ')'); //Evaluate JSON string
	//Process refreshed data...
	if(ajaxResponse.errors.length == 0)
	{
		//Clear old data first
		var steamGames = document.getElementById('steamGames');
		while(steamGames.hasChildNodes())
			steamGames.removeChild(steamGames.firstChild);
		//Append new data
		for(var i=0; i<ajaxResponse.values.length; i++)
		{
			var curInput = document.createElement('input');
			curInput.type = 'checkbox';
			curInput.name = 'steamGames[]';
			curInput.value = ajaxResponse.values[i].gameID;
			curInput.id = 'game' + curInput.value;
			curInput.checked = ajaxResponse.values[i].gameSelected;
			steamGames.appendChild(curInput);
			steamGames.appendChild(document.createTextNode(' '));
			var curLabel = document.createElement('label');
			curLabel.htmlFor = curInput.id;
			curLabel.className = 'norm';
			curLabel.appendChild(document.createTextNode(ajaxResponse.values[i].gameName))
			steamGames.appendChild(curLabel);
			steamGames.appendChild(document.createElement('br'));
		}
	}
	//...or display errors instead
	else
		for(var i=0; i<ajaxResponse.errors.length; i++)
			alert(ajaxResponse.errors[i]);
}
/* ]]> */
</script>
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=profile&amp;mode=edit&amp;profile_id={$userData[1]}{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{Language::getInstance()->getString('my_profile')}</span></th></tr>
 <tr><td class="kat" colspan="2"><span class="kat">{Language::getInstance()->getString('change_user_data')}</span></td></tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('user_name_colon')}</span></td>
  <td class="td1" style="width:80%;"><span class="norm">{$userData[0]}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('email_address_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="new_mail" value="{$userData[3]}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('user_rank_colon')}</span></td>
  <td class="td1" style="width:80%;"><span class="norm">{if !empty($userData[17])}{$userData[17]}{else}{$userData[4]}{/if}&nbsp;{$userData[2]}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('posts_colon')}</span></td>
  <td class="td1" style="width:80%;"><span class="norm">{$userData[5]}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('user_id_colon')}</span></td>
  <td class="td1" style="width:80%;"><span class="norm">{$userData[1]}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('homepage_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="new_hp" value="{$userData[9]}" style="width:250px;" /> <span class="small">{Language::getInstance()->getString('http_dots')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%; vertical-align:top;"><span class="norm">{Language::getInstance()->getString('avatar_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="new_pic" value="{$userData[10]}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('real_name_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="new_realname" value="{$userData[12]}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('icq_number_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="new_icq" value="{$userData[13]}" style="width:250px;" /> <span class="small">{Language::getInstance()->getString('number_only_no_dashes')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="width:20%; vertical-align:top;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('signature_colon')}</span><br /><span class="small">{Language::getInstance()->getString('bbcode_and_smilies_are_enabled')}</span></td>
  <td class="td1" style="width:80%;"><textarea cols="55" rows="8" name="new_signatur">{$userData[7]}</textarea></td>
 </tr>{if Config::getInstance()->getCfgVal('achievements') == 1}
 <tr><td class="kat" colspan="2"><span class="kat">{Language::getInstance()->getString('steam_achievements')}</span></td></tr>
 <tr><td class="td1" colspan="2"><span class="small">{Language::getInstance()->getString('steam_achievements_info')}</span></td></tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('steam_profile_name_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="steamProfile" value="{$userData[18].profileID}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="width:20%; vertical-align:top;">
   <span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('steam_games_colon')}</span>{if !empty($userData[18].profileID)}<br /><br />
   <button type="button" onclick="refreshGames();">{Language::getInstance()->getString('refresh_game_list')}</button>{/if}
  </td>
  <td class="td1" id="steamGames" style="width:80%;">{foreach $userData[19] as $curGame}
   <input type="checkbox" id="game{$curGame[0]}" name="steamGames[]" value="{$curGame[0]}"{if $curGame[3]} checked="checked"{/if} /> <label for="game{$curGame[0]}" class="norm">{$curGame[2]}</label><br />{/foreach}
  </td>
 </tr>{/if}
 <tr><td class="kat" colspan="2"><span class="kat">{Language::getInstance()->getString('options')}</span></td></tr>
 <tr><td class="td1" colspan="2"><input type="checkbox" value="1" id="new_mail2" name="new_mail2" style="vertical-align:middle;"{if $userData[14][1] == '1'} checked="checked"{/if} /> <label for="new_mail2" class="norm">{Language::getInstance()->getString('show_email_address')}</label><br /><span class="small">{Language::getInstance()->getString('show_email_address_info')}</span></td></tr>
 <tr><td class="td1" colspan="2"><input type="checkbox" value="1" id="new_mail1" name="new_mail1" style="vertical-align:middle;"{if $userData[14][0] == '1'} checked="checked"{/if} /> <label for="new_mail1" class="norm">{Language::getInstance()->getString('receive_emails_from_forum')}</label><br /><span class="small">{Language::getInstance()->getString('receive_emails_from_forum_info')}</span></td></tr>{if Config::getInstance()->getCfgVal('select_tpls') == 1}
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('template_colon')}</span></td>
  <td class="td1" style="width:80%;"><select name="ownTemplate" style="width:250px;"><option value="">{Language::getInstance()->getString('default_brackets')}</option>{foreach $templates as $curTplID => $curTemplate}<option value="{$curTplID}"{if $curTplID == $userData[20]} selected="selected"{/if}>{$curTemplate.name}</option>{/foreach}</select></td>
 </tr>{/if}{if Config::getInstance()->getCfgVal('select_styles') == 1}{if empty($userData[20])}{$userData[20] = Config::getInstance()->getCfgVal('default_tpl')}{/if}
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('style_colon')}</span></td>
  <td class="td1" style="width:80%;"><select name="ownStyle" style="width:250px;"><option value="">{Language::getInstance()->getString('default_brackets')}</option>{foreach $templates[$userData[20]]['styles'] as $curStyle}<option value="{$curStyle}"{if $curStyle == $userData[21]} selected="selected"{/if}>{$curStyle}</option>{/foreach}</select></td>
 </tr>{/if}
 <tr><td class="kat" colspan="2"><span class="kat">{Language::getInstance()->getString('change_password')}</span></td></tr>
 <tr><td class="td1" colspan="2"><span class="small">{Language::getInstance()->getString('change_password_info')}</span></td></tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('new_password_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="password" name="new_pw1" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('confirm_new_password_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="password" name="new_pw2" style="width:250px;" /></td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('change_profile')}" />{if Config::getInstance()->getCfgVal('delete_profiles') == 1 || Config::getInstance()->getCfgVal('delete_profiles') == 2 && $userData[5] < 1}&nbsp;&nbsp;<input type="submit" name="delete" value="{Language::getInstance()->getString('delete_account')}" />{/if}</p>
<input type="hidden" name="change" value="1" />
</form>