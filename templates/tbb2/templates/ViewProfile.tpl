{* 0:nick - 1:id - 2:rankImage(s) - 3:mail - 4:rank - 5:posts - 6:regDate - 7:signature - 8:joinedXWeeksAgo - 9:hp - 10:avatar - 11:postsPerDay - 12:realName - 13:icq - 14:formMail - 15:group[ - 16:timestamp - 17:specialState - 18:steamProfileIDs - 19:steamGames] *}
<!-- ViewProfile -->
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr>
  <th class="cellTitle" colspan="2"><span class="fontTitle">{$modules.Language->getString('view_profile')}</span></th>
  <th class="cellTitle" colspan="2" style="text-align:right;"><span class="fontTitle"><a href="{$smarty.const.INDEXFILE}?faction=profile&amp;profile_id={$userData[1]}&amp;mode=vCard{$smarty.const.SID_AMPER}" style="color:yellow;"><img src="{$modules.Template->getTplDir()}images/icons/vcard.png" alt="" style="vertical-align:top;" /> {$modules.Language->getString('download_as_vcard')}</a></span></th>
 </tr>{if !empty($userData[10])}
 <tr>
  <td class="cellStd" rowspan="7" style="text-align:center;">
   <div id="avatar" style="background-color:#000000; background-image:url({$userData[10]}); background-position:center; background-repeat:no-repeat; cursor:pointer; display:none; height:100%; left:0; opacity:0.9; position:fixed; top:0; width:100%; z-index:1;" onclick="this.style.display='none';"></div>
   <img src="{$userData[10]}" alt="" style="cursor:pointer; height:{$userData.avatarHeight}px; width:{$userData.avatarWidth}px;" onclick="document.getElementById('avatar').style.display='';" />
  </td>
 </tr>{/if}
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('user_name_colon')}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$userData[0]}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$modules.Language->getString('user_id_colon')} #{$userData[1]}</span></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('email_address_colon')}</span></td>
  <td class="cellAlt"><span class="fontNorm">{if $userData[3] === false}{$modules.Language->getString('is_not_shown')}{else}{mailto address=$userData[3] encode="javascript"}{/if}</span></td>
  <td class="cellAlt"><span class="fontNorm">{if $userData[14]}<a href="{$smarty.const.INDEXFILE}?faction=formmail&amp;target_id={$userData[1]}{$smarty.const.SID_AMPER}">{$modules.Language->getString('send_mail_brackets')}</a>{/if} {if $modules.Auth->isLoggedIn()}<a href="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=send&amp;target_id={$userData[1]}{$smarty.const.SID_AMPER}">{$modules.Language->getString('send_pm_brackets')}</a>{/if}</span></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('homepage_colon')}</span></td>
  <td class="cellAlt" colspan="2"><span class="fontNorm">{if empty($userData[9])}<span style="font-style:italic;">{$modules.Language->getString('not_given')}</span>{else}<a href="{$userData[9]}" target="_blank">{$userData[9]}</a>{/if}</span></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('join_date_colon')}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$userData[6]}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$userData[8]|string_format:$modules.Language->getString('registered_x_weeks_ago')}</span></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('posts_colon')}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$userData[5]}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$userData[11]|string_format:$modules.Language->getString('x_posts_per_day')}</span></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('user_rank_colon')}</span></td>
  <td class="cellAlt"><span class="fontNorm">{if !empty($userData[17])}{$userData[17]}{else}{$userData[4]}{/if}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$userData[2]}</span></td>
 </tr>
 <tr>
  <td class="cellAlt" colspan="4"><span class="fontNorm">{if empty($userData[7])}<span style="font-style:italic;">{$modules.Language->getString('not_given')}</span>{else}{$userData[7]}{/if}</span></td>
 </tr>
</table>
<br />
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr>
  <td class="cellStd" style="width:30%;"><span class="fontNorm">{$modules.Language->getString('real_name_colon')}</span></td>
  <td class="cellAlt" style="width:70%;"><span class="fontNorm">{if empty($userData[12])}<span style="font-style:italic;">{$modules.Language->getString('not_given')}</span>{else}{$userData[12]}{/if}</span></td>
 </tr>{if !empty($userData[15])}
 <tr>
  <td class="cellStd" style="width:30%;"><span class="fontNorm">{$modules.Language->getString('group_colon')}</span></td>
  <td class="cellAlt" style="width:70%;"><span class="fontNorm">{$userData[15]}</span></td>
 </tr>{/if}
 <tr>
  <td class="cellStd" style="width:30%;"><span class="fontNorm">{$modules.Language->getString('last_active_on_colon')}</span></td>
  <td class="cellAlt" style="width:70%;"><span class="fontNorm">{$userData[16]|date_format:$modules.Language->getString('DATEFORMAT')}</span></td>
 </tr>
 <tr>
  <td class="cellStd" style="width:30%;"><span class="fontNorm">{$modules.Language->getString('icq_number_colon')}</span></td>
  <td class="cellAlt" style="width:70%;"><span class="fontNorm">{if empty($userData[13])}<span style="font-style:italic;">{$modules.Language->getString('not_given')}</span>{else}<a href="http://www.icq.com/people/about_me.php?uin={$userData[13]}" target="_blank"><img src="http://status.icq.com/online.gif?icq={$userData[13]}&amp;img=5" alt="" style="vertical-align:top;" /> {$userData[13]|wordwrap:3:"-":true}</a>{/if}</span></td>
 </tr>
</table>{if $modules.Config->getCfgVal('achievements') == 1 && !empty($userData[19])}
<br />
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{$modules.Language->getString('steam_achievements_colon')}</span><span class="fontTitleSmall" style="float:right;">{$userData[18].profileName|string_format:$modules.Language->getString('from_x')}</span></th></tr>
 <tr><td class="cellAlt">{foreach $userData[19] as $curGame}<a href="{$smarty.const.INDEXFILE}?faction=profile&amp;profile_id={$userData[1]}&amp;mode=viewAchievements&amp;game={$curGame[0]}{$smarty.const.SID_AMPER}"><img src="{$curGame[1]}" alt="{$curGame[2]}" title="{$curGame[2]}" /></a> {/foreach}</td></tr>
</table>{/if}
<br />
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{$modules.Language->getString('options')}</span></th></tr>
 <tr><td class="cellAlt"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=search&amp;search=yes&amp;age=0&amp;searchfor={$userData[1]}&amp;auswahl=all&amp;searchOption=user&amp;soption1=3{$smarty.const.SID_AMPER}">{$userData[0]|string_format:$modules.Language->getString('search_all_topics_from_x_brackets')}</a></span></td></tr>
 <tr><td class="cellAlt"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=search&amp;search=yes&amp;age=0&amp;searchfor={$userData[1]}&amp;auswahl=all&amp;searchOption=user&amp;soption1=2{$smarty.const.SID_AMPER}">{$userData[0]|string_format:$modules.Language->getString('search_all_posts_from_x_brackets')}</a></span></td></tr>{if $modules.Auth->isAdmin()}
 <tr><td class="cellAlt"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_user&amp;mode=edit&amp;id={$userData[1]}{$smarty.const.SID_AMPER}">{$modules.Language->getString('edit_user_brackets')}</a></span></td></tr>{/if}
</table>