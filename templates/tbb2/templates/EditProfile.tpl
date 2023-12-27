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
			curLabel.className = 'fontNorm';
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
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('my_profile')}</span></th></tr>
 <tr><td class="cellCat"><span class="fontCat">{Language::getInstance()->getString('change_user_data')}</span></td></tr>
 <tr>
  <td class="cellStd">
   <table border="0" cellpadding="2" cellspacing="0" style="width:100%;">
    <tr>
     <td style="padding:3px; width:20%;"><span class="fontNorm">{Language::getInstance()->getString('user_name_colon')}</span></td>
     <td style="padding:3px; width:80%;"><span class="fontNorm">{$userData[0]}</span></td>
    </tr>
    <tr>
     <td style="padding:3px; width:20%;"><span class="fontNorm">{Language::getInstance()->getString('email_address_colon')}</span></td>
     <td style="padding:3px; width:80%;"><input class="formText" type="text" name="new_mail" value="{$userData[3]}" style="width:250px;" /></td>
    </tr>
    <tr>
     <td style="padding:3px; width:20%;"><span class="fontNorm">{Language::getInstance()->getString('user_rank_colon')}</span></td>
     <td style="padding:3px; width:80%;"><span class="fontNorm">{if !empty($userData[17])}{$userData[17]}{else}{$userData[4]}{/if}&nbsp;{$userData[2]}</span></td>
    </tr>
    <tr>
     <td style="padding:3px; width:20%;"><span class="fontNorm">{Language::getInstance()->getString('posts_colon')}</span></td>
     <td style="padding:3px; width:80%;"><span class="fontNorm">{$userData[5]}</span></td>
    </tr>
    <tr>
     <td style="padding:3px; width:20%;"><span class="fontNorm">{Language::getInstance()->getString('user_id_colon')}</span></td>
     <td style="padding:3px; width:80%;"><span class="fontNorm">{$userData[1]}</span></td>
    </tr>
    <tr>
     <td style="padding:3px; width:20%;"><span class="fontNorm">{Language::getInstance()->getString('homepage_colon')}</span></td>
     <td style="padding:3px; width:80%;"><input class="formText" type="text" name="new_hp" value="{$userData[9]}" style="width:250px;" /> <span class="fontSmall">{Language::getInstance()->getString('http_dots')}</span></td>
    </tr>
    <tr>
     <td style="padding:3px; width:20%; vertical-align:top;"><span class="fontNorm">{Language::getInstance()->getString('avatar_colon')}</span></td>
     <td style="padding:3px; width:80%;"><input class="formText" type="text" name="new_pic" value="{$userData[10]}" style="width:250px;" /></td>
    </tr>
    <tr>
     <td style="padding:3px; width:20%;"><span class="fontNorm">{Language::getInstance()->getString('real_name_colon')}</span></td>
     <td style="padding:3px; width:80%;"><input class="formText" type="text" name="new_realname" value="{$userData[12]}" style="width:250px;" /></td>
    </tr>
    <tr>
     <td style="padding:3px; width:20%;"><span class="fontNorm">{Language::getInstance()->getString('icq_number_colon')}</span></td>
     <td style="padding:3px; width:80%;"><input class="formText" type="text" name="new_icq" value="{$userData[13]}" style="width:250px;" /> <span class="fontSmall">{Language::getInstance()->getString('number_only_no_dashes')}</span></td>
    </tr>
    <tr>
     <td style="padding:3px; width:20%; vertical-align:top;"><span class="fontNorm">{Language::getInstance()->getString('signature_colon')}</span><br /><span class="fontSmall">{Language::getInstance()->getString('bbcode_and_smilies_are_enabled')}</span></td>
     <td style="padding:3px; width:80%;"><textarea class="formTextArea" cols="60" rows="8" name="new_signatur">{$userData[7]}</textarea></td>
    </tr>
   </table>
  </td>
 </tr>{if Config::getInstance()->getCfgVal('achievements') == 1}
 <tr><td class="cellCat"><span class="fontCat">{Language::getInstance()->getString('steam_achievements')}</span></td></tr>
 <tr>
  <td class="cellStd">
   <table border="0" cellpadding="2" cellspacing="0" style="width:100%;">
    <tr><td class="divInfoBox" colspan="2"><span class="fontSmall"><img src="{Template::getInstance()->getTplDir()}images/icons/info.png" alt="" class="imageIcon" /> {Language::getInstance()->getString('steam_achievements_info')}</span></td></tr>
    <tr>
     <td style="padding:3px; width:20%;"><span class="fontNorm">{Language::getInstance()->getString('steam_profile_name_colon')}</span></td>
     <td style="padding:3px; width:80%;"><input class="formText" type="text" name="steamProfile" value="{$userData[18].profileID}" style="width:250px;" /></td>
    </tr>
    <tr>
     <td style="padding:3px; width:20%; vertical-align:top;">
      <span class="fontNorm">{Language::getInstance()->getString('steam_games_colon')}</span>{if !empty($userData[18].profileID)}<br /><br />
      <button class="formButton" type="button" onclick="refreshGames();">{Language::getInstance()->getString('refresh_game_list')}</button>{/if}
     </td>
     <td id="steamGames" style="padding:3px; width:80%;">{foreach $userData[19] as $curGame}
      <input type="checkbox" id="game{$curGame[0]}" name="steamGames[]" value="{$curGame[0]}"{if $curGame[3]} checked="checked"{/if} /> <label for="game{$curGame[0]}" class="fontNorm">{$curGame[2]}</label><br />{/foreach}
     </td>
    </tr>
   </table>
  </td>
 </tr>{/if}
 <tr><td class="cellCat"><span class="fontCat">{Language::getInstance()->getString('options')}</span></td></tr>
 <tr>
  <td class="cellStd">
   <table border="0" cellpadding="2" cellspacing="0" style="width:100%;">
    <tr><td colspan="2"><input type="checkbox" value="1" id="new_mail2" name="new_mail2"{if $userData[14][1] == '1'} checked="checked"{/if} /> <label for="new_mail2" class="fontNorm">{Language::getInstance()->getString('show_email_address')}</label><br /><span class="fontSmall">{Language::getInstance()->getString('show_email_address_info')}</span></td></tr>
    <tr><td colspan="2"><input type="checkbox" value="1" id="new_mail1" name="new_mail1"{if $userData[14][0] == '1'} checked="checked"{/if} /> <label for="new_mail1" class="fontNorm">{Language::getInstance()->getString('receive_emails_from_forum')}</label><br /><span class="fontSmall">{Language::getInstance()->getString('receive_emails_from_forum_info')}</span></td></tr>{if Config::getInstance()->getCfgVal('select_tpls') == 1}
    <tr>
     <td style="padding:3px; width:20%;"><span class="fontNorm">{Language::getInstance()->getString('template_colon')}</span></td>
     <td style="padding:3px; width:80%;"><select class="formSelect" name="ownTemplate" style="width:250px;"><option value="">{Language::getInstance()->getString('default_brackets')}</option>{foreach $templates as $curTplID => $curTemplate}<option value="{$curTplID}"{if $curTplID == $userData[20]} selected="selected"{/if}>{$curTemplate.name}</option>{/foreach}</select></td>
    </tr>{/if}{if Config::getInstance()->getCfgVal('select_styles') == 1}{if empty($userData[20])}{$userData[20] = Config::getInstance()->getCfgVal('default_tpl')}{/if}
    <tr>
     <td style="padding:3px; width:20%;"><span class="fontNorm">{Language::getInstance()->getString('style_colon')}</span></td>
     <td style="padding:3px; width:80%;"><select class="formSelect" name="ownStyle" style="width:250px;"><option value="">{Language::getInstance()->getString('default_brackets')}</option>{foreach $templates[$userData[20]]['styles'] as $curStyle}<option value="{$curStyle}"{if $curStyle == $userData[21]} selected="selected"{/if}>{$curStyle}</option>{/foreach}</select></td>
    </tr>{/if}
   </table>
  </td>
 </tr>
 <tr><td class="cellCat"><span class="fontCat">{Language::getInstance()->getString('change_password')}</span></td></tr>
 <tr>
  <td class="cellStd">
   <table border="0" cellpadding="2" cellspacing="0" style="width:100%;">
    <tr><td class="divInfoBox" colspan="2"><span class="fontNorm"><img src="{Template::getInstance()->getTplDir()}images/icons/info.png" alt="" class="imageIcon" /> {Language::getInstance()->getString('change_password_info')}</span></td></tr>
    <tr>
     <td style="padding:3px; width:20%;"><span class="fontNorm">{Language::getInstance()->getString('new_password_colon')}</span></td>
     <td style="padding:3px; width:80%;"><input class="formText" type="password" name="new_pw1" style="width:250px;" /></td>
    </tr>
    <tr>
     <td style="padding:3px; width:20%;"><span class="fontNorm">{Language::getInstance()->getString('confirm_new_password_colon')}</span></td>
     <td style="padding:3px; width:80%;"><input class="formText" type="password" name="new_pw2" style="width:250px;" /></td>
    </tr>
   </table>
  </td>
 </tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('change_profile')}" />{if Config::getInstance()->getCfgVal('delete_profiles') == 1 || Config::getInstance()->getCfgVal('delete_profiles') == 2 && $userData[5] < 1}&nbsp;&nbsp;<input class="formButton" type="submit" name="delete" value="{Language::getInstance()->getString('delete_account')}" />{/if}</p>
<input type="hidden" name="change" value="1" />
</form>