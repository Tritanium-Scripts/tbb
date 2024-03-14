{* 0:nick - 1:id - 2:rankImage(s) - 3:mail - 4:rank - 5:posts - 6:regDate - 7:signature - 8:joinedXWeeksAgo - 9:hp - 10:avatar - 11:postsPerDay - 12:realName - 13:icq - 14:formMail - 15:group[ - 16:timestamp - 17:specialState - 18:steamProfileIDs - 19:steamGames[ - 22:birthday]] *}
<!-- ViewProfile -->
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr>
  <th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('view_profile')}</span></th>
  <th class="cellTitle" colspan="2" style="text-align:right;"><span class="fontTitle"><a href="{$smarty.const.INDEXFILE}?faction=profile&amp;profile_id={$userData[1]}&amp;mode=vCard{$smarty.const.SID_AMPER}" style="color:yellow;"><img src="{Template::getInstance()->getTplDir()}images/icons/vcard.png" alt="" style="vertical-align:top;" /> {Language::getInstance()->getString('download_as_vcard')}</a></span></th>
 </tr>
{plugin_hook hook=PlugIns::HOOK_TPL_PROFILE_VIEW_PROFILE_FORM_START}{if !empty($userData[10])}
 <tr>
  <td class="cellStd" rowspan="7" style="text-align:center;">
   <div id="avatar" style="background-color:rgba(0, 0, 0, 0.9); background-image:url({$userData[10]}); background-position:center; background-repeat:no-repeat; cursor:pointer; display:none; height:100%; left:0; position:fixed; top:0; width:100%; z-index:1;" onclick="this.style.display='none';"></div>
   <img src="{$userData[10]}" alt="" style="cursor:pointer; height:{$userData.avatarHeight}px; width:{$userData.avatarWidth}px;" onclick="document.getElementById('avatar').style.display='';" />
  </td>
 </tr>{/if}
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('user_name_colon')}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$userData[0]}</span></td>
  <td class="cellAlt"><span class="fontNorm">{Language::getInstance()->getString('user_id_colon')} #{$userData[1]}</span></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('email_address_colon')}</span></td>
  <td class="cellAlt"><span class="fontNorm">{if $userData[3] === false}{Language::getInstance()->getString('is_not_shown')}{else}{mailto address=$userData[3] encode="javascript"}{/if}</span></td>
  <td class="cellAlt"><span class="fontNorm">{if $userData[14]}<a href="{$smarty.const.INDEXFILE}?faction=formmail&amp;target_id={$userData[1]}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('send_mail_brackets')}</a>{/if} {if Auth::getInstance()->isLoggedIn()}<a href="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=send&amp;target_id={$userData[1]}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('send_pm_brackets')}</a>{/if}</span></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('homepage_colon')}</span></td>
  <td class="cellAlt" colspan="2"><span class="fontNorm">{if empty($userData[9])}<span style="font-style:italic;">{Language::getInstance()->getString('not_given')}</span>{else}<a href="{$userData[9]}" target="_blank">{$userData[9]}</a>{/if}</span></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('join_date_colon')}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$userData[6]}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$userData[8]|string_format:Language::getInstance()->getString('registered_x_weeks_ago')}</span></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('posts_colon')}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$userData[5]}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$userData[11]|string_format:Language::getInstance()->getString('x_posts_per_day')}</span></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('user_rank_colon')}</span></td>
  <td class="cellAlt"><span class="fontNorm">{if !empty($userData[17])}{$userData[17]}{else}{$userData[4]}{/if}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$userData[2]}</span></td>
 </tr>
 <tr>
  <td class="cellAlt" colspan="4"><span class="fontNorm">{if empty($userData[7])}<span style="font-style:italic;">{Language::getInstance()->getString('not_given')}</span>{else}{$userData[7]}{/if}</span></td>
 </tr>
</table>
<br />
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{$smarty.config.langExtended}</span></th></tr>
 <tr>
  <td class="cellStd" style="width:30%;"><span class="fontNorm">{Language::getInstance()->getString('real_name_colon')}</span></td>
  <td class="cellAlt" style="width:70%;"><span class="fontNorm">{if empty($userData[12])}<span style="font-style:italic;">{Language::getInstance()->getString('not_given')}</span>{else}{$userData[12]}{/if}</span></td>
 </tr>
 <tr>
  <td class="cellStd" style="width:30%;"><span class="fontNorm">{Language::getInstance()->getString('birthday_colon')}</span></td>
  <td class="cellAlt" style="width:70%;"><span class="fontNorm">{if empty($userData[22])}<span style="font-style:italic;">{Language::getInstance()->getString('not_given')}</span>{else}{$userData[22]|date_format:Language::getInstance()->getString('DATEFORMAT')|utf8_encode}{/if}</span></td>
 </tr>{if !empty($userData[15])}
 <tr>
  <td class="cellStd" style="width:30%;"><span class="fontNorm">{Language::getInstance()->getString('group_colon')}</span></td>
  <td class="cellAlt" style="width:70%;"><span class="fontNorm">{$userData[15]}</span></td>
 </tr>{/if}
 <tr>
  <td class="cellStd" style="width:30%;"><span class="fontNorm">{Language::getInstance()->getString('last_active_on_colon')}</span></td>
  <td class="cellAlt" style="width:70%;"><span class="fontNorm">{$userData[16]|date_format:Language::getInstance()->getString('DATEFORMAT')|utf8_encode}</span></td>
 </tr>
 <tr>
  <td class="cellStd" style="width:30%;"><span class="fontNorm">{Language::getInstance()->getString('icq_number_colon')}</span></td>
  <td class="cellAlt" style="width:70%;"><span class="fontNorm">{if empty($userData[13])}<span style="font-style:italic;">{Language::getInstance()->getString('not_given')}</span>{else}<a href="https://icq.com/people/{$userData[13]}" target="_blank"><img src="http://status.icq.com/online.gif?icq={$userData[13]}&amp;img=5" alt="" style="vertical-align:top;" /> {$userData[13]|wordwrap:3:"-":true}</a>{/if}</span></td>
 </tr>{if Config::getInstance()->getCfgVal('achievements') == 1 && !empty($userData[19])}
</table>
<br />
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" id="achievements" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('steam_achievements')}</span><span class="fontTitleSmall" style="float:right;">{$userData[18].profileName|string_format:Language::getInstance()->getString('from_x')}</span></th></tr>
 <tr><td class="cellAlt">{foreach $userData[19] as $curGame}<a href="{$smarty.const.INDEXFILE}?faction=profile&amp;profile_id={$userData[1]}&amp;mode=viewAchievements&amp;game={$curGame[0]}{$smarty.const.SID_AMPER}"><img src="{$curGame[1]}" alt="{$curGame[2]}" title="{$curGame[2]}" /></a> {/foreach}</td></tr>{/if}
{plugin_hook hook=PlugIns::HOOK_TPL_PROFILE_VIEW_PROFILE_FORM_END}
</table>
<br />
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('options')}</span></th></tr>
 <tr><td class="cellAlt"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=search&amp;search=yes&amp;age=0&amp;searchfor={$userData[1]}&amp;auswahl=all&amp;searchOption=user&amp;soption1=3{$smarty.const.SID_AMPER}">{$userData[0]|string_format:Language::getInstance()->getString('search_all_topics_from_x_brackets')}</a></span></td></tr>
 <tr><td class="cellAlt"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=search&amp;search=yes&amp;age=0&amp;searchfor={$userData[1]}&amp;auswahl=all&amp;searchOption=user&amp;soption1=2{$smarty.const.SID_AMPER}">{$userData[0]|string_format:Language::getInstance()->getString('search_all_posts_from_x_brackets')}</a></span></td></tr>{if Auth::getInstance()->isAdmin()}
 <tr><td class="cellAlt"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_user&amp;mode=edit&amp;id={$userData[1]}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('edit_user_brackets')}</a></span></td></tr>{/if}
</table>