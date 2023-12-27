<!-- ViewTopic -->
{if $isPoll}<!-- Poll -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=vote&amp;forum_id={$forumID}&amp;topic_id={$topicID}&amp;poll_id={$pollID}{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('poll_colon')} {$pollTitle}</span> <span class="fontTitleSmall">{$totalVotes|string_format:Language::getInstance()->getString('x_votes_total')}</span></th></tr>
 <tr>
  <td class="cellStd">
   <table border="0" cellpadding="2" cellspacing="0">
    {foreach $pollOptions as $curOption}<tr>
     <td style="padding:3px;">{if !$hasVoted}<input type="radio" name="vote_id" value="{$curOption.optionID}" /> {/if}<span class="fontNorm">{$curOption.pollOption}</span></td>
     <td style="padding:3px;"><img src="{Template::getInstance()->getTplDir()}images/pollbar.gif" alt="" style="height:15px; vertical-align:middle; width:{round($curOption.percent)}px;" /></td>
     <td style="padding:3px;"><span class="fontSmall">{$curOption.voteText}</span></td>
    </tr>{/foreach}
   </table>
  </td>
 </tr>
 <tr>{if $isPollClosed}
  <td class="cellMessageBox"><span class="fontNorm">{Language::getInstance()->getString('the_poll_is_closed')}</span></td>{elseif $hasVoted}
  <td class="cellMessageBox"><span class="fontNorm">{Language::getInstance()->getString('you_already_voted')}</span></td>{elseif $needsLogin}
  <td class="cellMessageBox"><span class="fontNorm">{Language::getInstance()->getString('need_login_to_vote')}</span></td>{else}
  <td class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('vote')}" /></td>{/if}
 </tr>
</table>
</form><br />{/if}

<!-- Posts -->
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr>
  <th class="cellTitle" style="text-align:left; width:15%;"><span class="fontTitle">{Language::getInstance()->getString('author')}</span></th>
  <th class="cellTitle" style="text-align:left; width:85%;"><span class="fontTitle">{Language::getInstance()->getString('topic_colon')} {$topicTitle}{if Auth::getInstance()->isLoggedIn()} (<a href="{$smarty.const.INDEXFILE}?faction=topic&amp;mode={if $isSubscribed}unsubscribe&amp;forum_id={$forumID}&amp;topic_id={$topicID}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('unsubscribe')}{else}subscribe&amp;forum_id={$forumID}&amp;topic_id={$topicID}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('subscribe')}{/if}</a>){/if}</span></th>
 </tr>
{foreach $posts as $curPost}
 <tr id="post{$curPost.postID}">
  <td{if $curPost@last} id="last"{/if} rowspan="3" class="cellAlt" style="vertical-align:top; width:15%;">
   <span class="fontNorm" style="font-weight:bold;">{$curPost.userNick}</span><br />
   <span class="fontSmall">{if !empty($curPost.userSpecialState)}{$curPost.userSpecialState}{else}{$curPost.userState}{/if}<br />
   {if !empty($curPost.userGroup)}{$curPost.userGroup}<br />{/if}{$curPost.userRank}<br />
   {if $curPost.userID != 0}{$curPost.userID|string_format:Language::getInstance()->getString('id_x')}{/if}<br /><br />
   {$curPost.userAvatar}<br />{if !empty($curPost.userICQ)}<br />
   <a href="https://icq.com/people/{$curPost.userICQ}" target="_blank"><img src="http://status.icq.com/online.gif?icq={$curPost.userICQ}&amp;img=5" alt="" style="vertical-align:middle;" /> {$curPost.userICQ|wordwrap:3:"-":true}</a>{/if}{if !empty($curPost.userSteamGames[0])}<br />
   <a href="{$smarty.const.INDEXFILE}?faction=profile&amp;profile_id={$curPost.userID}{$smarty.const.SID_AMPER}#achievements"><img src="{Template::getInstance()->getTplDir()}images/steam.png" alt="" style="vertical-align:middle;" /> {Language::getInstance()->getString('achievements')}</a>{/if}</span>
  </td>
  <td class="cellAlt" style="vertical-align:middle; width:85%;">
   <table border="0" cellspacing="0" cellpadding="0" style="width:100%;">
    <tr>
     <td><img src="{$curPost.tSmileyURL}" alt="" style="vertical-align:middle;" /> <span class="fontSmall">{$curPost.date|string_format:Language::getInstance()->getString('posted_on_x')} (<a href="{$smarty.const.INDEXFILE}?mode=viewthread&amp;forum_id={$forumID}&amp;thread={$topicID}&amp;z={$page}{$smarty.const.SID_AMPER}#post{$curPost.postID}">#{$curPost.postID}</a>)</span></td>
     <td style="text-align:right;">{if $curPost.canModify}
      <a href="{$smarty.const.INDEXFILE}?faction=edit&amp;mode=kill&amp;forum_id={$forumID}&amp;topic_id={$topicID}&amp;post_id={$curPost.postID}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/delete.png" alt="{Language::getInstance()->getString('delete')}" class="imageButton" /></a>
      <a href="{$smarty.const.INDEXFILE}?faction=edit&amp;forum_id={$forumID}&amp;topic_id={$topicID}&amp;post_id={$curPost.postID}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/edit.png" alt="{Language::getInstance()->getString('edit')}" class="imageButton" /></a>{/if}{if !empty($curPost.userHP)}
      <a href="{$curPost.userHP}" target="_blank"><img src="{Template::getInstance()->getTplDir()}images/buttons/homepage.png" alt="{Language::getInstance()->getString('homepage')}" class="imageButton" /></a>{/if}{if $curPost.sendPM}
      <a href="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=send&amp;target_id={$curPost.userID}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/pm.png" alt="{Language::getInstance()->getString('pm')}" class="imageButton" /></a>{/if}{if $curPost.userEMail !== false}
      <a href="{if $curPost.userEMail === true}{$smarty.const.INDEXFILE}?faction=formmail&amp;target_id={$curPost.userID}{$smarty.const.SID_AMPER}{else}mailto:{$curPost.userEMail}{/if}"><img src="{Template::getInstance()->getTplDir()}images/buttons/email.png" alt="{Language::getInstance()->getString('email')}" class="imageButton" /></a>{/if}
      <a href="{$smarty.const.INDEXFILE}?faction=reply&amp;thread_id={$topicID}&amp;forum_id={$forumID}&amp;quote={$curPost.postID}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/quote.png" alt="{Language::getInstance()->getString('quote')}" class="imageButton" /></a>
     </td>
    </tr>
   </table>
  </td>
 </tr>
 <tr>
  <td class="cellStd" style="width:85%;">
   <div class="fontNorm" style="min-height:50px;">{$curPost.post}</div>{if $curPost.userSig != false}<br />
   <div class="signature">-----------------------<br />
    {$curPost.userSig}
   </div>{/if}{if !empty($curPost.lastEditBy)}<hr style="margin-left:0; text-align:left; width:50%;" />
   <span class="fontSmall">{$curPost.lastEditBy|string_format:Language::getInstance()->getString('last_edit_by_x')}</span>{/if}
  </td>
 </tr>
 <tr><td class="cellStd" style="width:85%;"><span class="fontSmall">{* reuse sendPM value here *}{if $curPost.sendPM}{$curPost.userPosts|string_format:Language::getInstance()->getString('x_posts')} | {/if}{if $curPost.sendPM}{$curPost.userRegDate|string_format:Language::getInstance()->getString('member_since_x')} | {/if}{$curPost.postIPText}</span></td></tr>
{/foreach}
</table>

<!-- NavBar -->
<br />
<table class="tableNavBar">
 <tr>
  <td class="cellNavBarBig">
   <table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
    <tr>
     <td><span class="fontNavBar">{$pageBar}</span></td>
     <td style="text-align:right; white-space:nowrap;"><span class="fontNavBar"><a href="{$smarty.const.INDEXFILE}?faction=newtopic&amp;forum_id={$forumID}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/new_topic.png" alt="" class="imageButton" /></a>&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=newpoll&amp;forum_id={$forumID}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/new_poll.png" alt="" class="imageButton" /></a>&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=reply&amp;forum_id={$forumID}&amp;thread_id={$topicID}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/new_reply.png" alt="" class="imageButton" /></a></span></td>
    </tr>
   </table>
  </td>
 </tr>
</table>{if Auth::getInstance()->isLoggedIn() && $isOpen}

<br />
<form action="{$smarty.const.INDEXFILE}?faction=reply&amp;mode=save{$smarty.const.SID_AMPER}" method="post">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('quick_reply')}</span></th></tr>
 <tr>
  <td class="cellStd">
   <table>
    <tr>
     <td style="width:10%;"><textarea class="formTextArea" id="post" name="post" rows="12" cols="60"></textarea></td>
     <td style="vertical-align:top;"><br />{include file='Smilies.tpl' targetBoxID='post' isMod=$canModify}</td>
    </tr>
   </table>
  </td>
 </tr>
 <tr><td class="cellButtons" colspan="2"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('quick_reply')}" /></td></tr>
</table>
<input type="hidden" name="topic_id" value="{$topicID}" />
<input type="hidden" name="forum_id" value="{$forumID}" />
<input type="hidden" name="tsmilie" value="1" />{* not sure about this one *}
<input type="hidden" name="smilies" value="1" />
<input type="hidden" name="show_signatur" value="1" />
<input type="hidden" name="use_upbcode" value="1" />
<input type="hidden" name="isAddURLs" value="true" />
</form>{/if}{if $canModify}

<!-- Toolbar -->
<br />
<table class="tableNavBar" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr>
  <td class="cellNavBar" style="text-align:center;"><span class="fontNavBar">{if $isPoll && $canEdit}
   <a href="{$smarty.const.INDEXFILE}?faction=vote&amp;forum_id={$forumID}&amp;topic_id={$topicID}&amp;poll_id={$pollID}&amp;edit=true{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/icons/chart_bar_edit.png" alt="{Language::getInstance()->getString('edit_poll', 'Posting')}" title="{Language::getInstance()->getString('edit_poll')}" /></a>{/if}
   <a href="{$smarty.const.INDEXFILE}?faction=topic&amp;mode=killTopic&amp;forum_id={$forumID}&amp;topic_id={$topicID}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/icons/cross.png" alt="{Language::getInstance()->getString('delete_topic', 'Posting')}" title="{Language::getInstance()->getString('delete_topic')}" /></a>
   <a href="{$smarty.const.INDEXFILE}?faction=topic&amp;mode={if $isOpen}close&amp;forum_id={$forumID}&amp;topic_id={$topicID}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/icons/lock.png" alt="{Language::getInstance()->getString('close_topic')}" title="{Language::getInstance()->getString('close_topic')}" />{else}open&amp;forum_id={$forumID}&amp;topic_id={$topicID}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/icons/lock_open.png" alt="{Language::getInstance()->getString('open_topic')}" title="{Language::getInstance()->getString('open_topic')}" />{/if}</a>
   <a href="{$smarty.const.INDEXFILE}?faction=topic&amp;mode=move&amp;forum_id={$forumID}&amp;topic_id={$topicID}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/icons/movetopic.gif" alt="{Language::getInstance()->getString('move_topic')}" title="{Language::getInstance()->getString('move_topic')}" /></a>{if $isSticky}
   <a href="{$smarty.const.INDEXFILE}?faction=topic&amp;mode=unpin&amp;forum_id={$forumID}&amp;topic_id={$topicID}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/icons/error_delete.png" alt="unpin" title="unpin" /></a>{else}
   <a href="{$smarty.const.INDEXFILE}?faction=topic&amp;mode=pin&amp;forum_id={$forumID}&amp;topic_id={$topicID}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/icons/error_add.png" alt="pin" title="pin" /></a>{/if}</span>
  </td>
 </tr>
</table>{/if}