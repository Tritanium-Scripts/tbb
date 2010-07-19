{* 0:nick - 1:id - 2:rankImage(s) - 3:mail - 4:rank - 5:posts - 6:regDate - 7:signature - 8:joinedXWeeksAgo - 9:hp - 10:avatar - 11:postsPerDay - 12:realName - 13:icq - 14:formMail - 15:group[ - 16:timestamp - 17:specialNick - 18:steamProfile - 19:steamGames] *}
<!-- ViewProfile -->
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr>
  <th class="thnorm"><span class="thnorm">{$modules.Language->getString('view_profile')}</span></th>
  <th class="thnorm" style="text-align:right;"><span class="thnorm"><a href="{$smarty.const.INDEXFILE}?faction=profile&amp;profile_id={$userData[1]}&amp;mode=vCard{$smarty.const.SID_AMPER}" style="color:yellow;">{$modules.Language->getString('download_as_vcard')}</a></span></th>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('user_id_colon')}</span></td>
  <td class="td1" style="width:80%;"><span class="norm">{$userData[1]}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('user_name_colon')}</span></td>
  <td class="td1" style="width:80%;"><span class="norm">{$userData[0]}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('email_address_colon')}</span></td>
  <td class="td1" style="width:80%;"><span class="norm">{if $userData[3] === false}{$modules.Language->getString('is_not_shown')}{else}{mailto address=$userData[3] encode="javascript"}{/if}&nbsp;{if $userData[14]}<a href="{$smarty.const.INDEXFILE}?faction=formmail&amp;target_id={$userData[1]}{$smarty.const.SID_AMPER}">{$modules.Language->getString('send_mail_brackets')}</a>{/if}</span></td>
 </tr>{if $modules.Auth->isLoggedIn()}
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('pm_colon')}</span></td>
  <td class="td1" style="width:80%;"><span class="norm"><a href="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=send&amp;target_id={$userData[1]}{$smarty.const.SID_AMPER}">{$modules.Language->getString('send_pm_brackets')}</a></span></td>
 </tr>{/if}
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('real_name_colon')}</span></td>
  <td class="td1" style="width:80%;"><span class="norm">{if empty($userData[12])}<span style="font-style:italic;">{$modules.Language->getString('not_given')}</span>{else}{$userData[12]}{/if}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('homepage_colon')}</span></td>
  <td class="td1" style="width:80%;"><span class="norm">{if empty($userData[9])}<span style="font-style:italic;">{$modules.Language->getString('not_given')}</span>{else}<a href="{$userData[9]}" target="_blank">{$userData[9]}</a>{/if}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('user_rank_colon')}</span></td>
  <td class="td1" style="width:80%;"><span class="norm">{if !empty($userData[17])}{$userData[17]}{else}{$userData[4]}{/if}&nbsp;{$userData[2]}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('join_date_colon')}</span></td>
  <td class="td1" style="width:80%;"><span class="norm">{$userData[6]}&nbsp;<span class="small">{$userData[8]|string_format:$modules.Language->getString('registered_x_weeks_ago')}</span></span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('posts_colon')}</span></td>
  <td class="td1" style="width:80%;"><span class="norm">{$userData[5]}&nbsp;<span class="small">{$userData[11]|string_format:$modules.Language->getString('x_posts_per_day')}</span></span></td>
 </tr>{if !empty($userData[15])}
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('group_colon')}</span></td>
  <td class="td1" style="width:80%;"><span class="norm">{$userData[15]}</span></td>
 </tr>{/if}
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('last_active_on_colon')}</span></td>
  <td class="td1" style="width:80%;"><span class="norm">{$userData[16]|date_format:$modules.Language->getString('DATEFORMAT')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%; vertical-align:top;"><span class="norm">{$modules.Language->getString('avatar_colon')}</span></td>
  <td class="td1" style="width:80%;"><span class="norm">{if empty($userData[10])}<span style="font-style:italic;">{$modules.Language->getString('not_given')}</span>{else}<img src="{$userData[10]}" alt="" />{/if}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%; vertical-align:top;"><span class="norm">{$modules.Language->getString('signature_colon')}</span></td>
  <td class="td1" style="width:80%;"><span class="norm">{if empty($userData[7])}<span style="font-style:italic;">{$modules.Language->getString('not_given')}</span>{else}{$userData[7]}{/if}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('icq_number_colon')}</span></td>
  <td class="td1" style="width:80%;"><span class="norm">{if empty($userData[13])}<span style="font-style:italic;">{$modules.Language->getString('not_given')}</span>{else}<a href="http://www.icq.com/people/about_me.php?uin={$userData[13]}" target="_blank"><img src="http://status.icq.com/online.gif?icq={$userData[13]}&amp;img=5" alt="" style="vertical-align:top;" /> {$userData[13]|wordwrap:3:"-":true}</a>{/if}</span></td>
 </tr>{if $modules.Config->getCfgVal('achievements') == 1 && !empty($userData[19])}
 <tr>
  <td class="td1" style="font-weight:bold; width:20%; vertical-align:top;"><span class="norm">{$modules.Language->getString('steam_achievements_colon')}</span></td>
  <td class="td1" style="width:80%;">{foreach $userData[19] as $curGame}<a href="{$smarty.const.INDEXFILE}?faction=profile&amp;profile_id={$userData[1]}&amp;mode=viewAchievements&amp;game={$curGame[0]}{$smarty.const.SID_AMPER}"><img src="{$curGame[1]}" alt="{$curGame[2]}" title="{$curGame[2]}" /></a> {/foreach}</td>
 </tr>{/if}{if $modules.Auth->isAdmin()}
 <tr><td class="td1" colspan="2"><span class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_user&amp;mode=edit&amp;id={$userData[1]}{$smarty.const.SID_AMPER}">{$modules.Language->getString('edit_user_brackets')}</a></span></td></tr>{/if}
</table>