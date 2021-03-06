<!-- ViewTopic -->
{if $isPoll}<!-- Poll -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=vote&amp;forum_id={$forumID}&amp;topic_id={$topicID}&amp;poll_id={$pollID}{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{$modules.Language->getString('poll')}</span></th></tr>
 <tr>
  <td class="td1">
   <span class="norm" style="font-weight:bold;">{$pollTitle}</span> <span class="small">{$totalVotes|string_format:$modules.Language->getString('x_votes_total')}</span><br />
   <table cellpadding="0" cellspacing="4">
    {foreach $pollOptions as $curOption}<tr>
     <td style="text-align:right;"><span class="norm">{$curOption@iteration}. </span>{if !$hasVoted}<input type="radio" name="vote_id" value="{$curOption.optionID}" />{/if}</td>
     <td><span class="norm">{$curOption.pollOption}</span></td>
     <td><img src="{$modules.Template->getTplDir()}images/pollbar.gif" alt="" style="height:10px; vertical-align:middle; width:{round($curOption.percent)}px;" /></td>
     <td><span class="small">{$curOption.voteText}</span></td>
    </tr>{/foreach}
   </table>
   <span class="norm">
   {if $isPollClosed}<span class="small">{$modules.Language->getString('the_poll_is_closed')}</span>
   {elseif $hasVoted}<span class="small">{$modules.Language->getString('you_already_voted')}</span>
   {elseif $needsLogin}<span class="small">{$modules.Language->getString('need_login_to_vote')}</span>
   {else}<input type="submit" value="{$modules.Language->getString('vote')}" />{/if}
   {if $canEdit}&nbsp;&nbsp;&nbsp;<input type="submit" name="edit" value="{$modules.Language->getString('edit')}" />{/if}
   </span>
  </td>
 </tr>
</table>
</form><br />{/if}

<!-- Posts -->
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr>
  <th class="thsmall" style="text-align:left; width:15%;"><span class="thsmall">{$modules.Language->getString('author')}</span></th>
  <th class="thsmall" style="text-align:left; width:85%;"><span class="thsmall">{$modules.Language->getString('topic_colon')} {$topicTitle}{if $modules.Auth->isLoggedIn()} (<a style="color:#FFFF00;" href="{$smarty.const.INDEXFILE}?faction=topic&amp;mode={if $isSubscribed}unsubscribe&amp;forum_id={$forumID}&amp;topic_id={$topicID}{$smarty.const.SID_AMPER}">{$modules.Language->getString('unsubscribe')}{else}subscribe&amp;forum_id={$forumID}&amp;topic_id={$topicID}{$smarty.const.SID_AMPER}">{$modules.Language->getString('subscribe')}{/if}</a>){/if}</span></th>
 </tr>
{foreach $posts as $curPost}
 <tr id="post{$curPost.postID}">
  <td{if $curPost@last} id="last"{/if} rowspan="2" class="{cycle values="td1,td2" advance=false}" style="vertical-align:top; width:15%;">
   <span class="norm" style="font-weight:bold;">{$curPost.userNick}</span><br />
   <span class="small">{if !empty($curPost.userSpecialState)}{$curPost.userSpecialState}{else}{$curPost.userState}{/if}<br />
   {if !empty($curPost.userGroup)}{$curPost.userGroup}<br />{/if}{$curPost.userRank}<br />
   {if $curPost.userID != 0}{$curPost.userID|string_format:$modules.Language->getString('id_x')}{/if}<br /><br />
   {$curPost.userAvatar}<br />{if !empty($curPost.userICQ)}<br />
   <a href="https://icq.com/people/{$curPost.userICQ}" target="_blank"><img src="http://status.icq.com/online.gif?icq={$curPost.userICQ}&amp;img=5" alt="" style="vertical-align:middle;" /> {$curPost.userICQ|wordwrap:3:"-":true}</a>{/if}{if !empty($curPost.userSteamGames[0])}<br />
   <a href="{$smarty.const.INDEXFILE}?faction=profile&amp;profile_id={$curPost.userID}{$smarty.const.SID_AMPER}#achievements"><img src="{$modules.Template->getTplDir()}images/steam.png" alt="" style="vertical-align:middle;" /> {$modules.Language->getString('achievements')}</a>{/if}</span>
  </td>
  <td class="{cycle values="td1,td2" advance=false}" style="vertical-align:top; width:85%;">
   <span class="small"><img src="{$curPost.tSmileyURL}" alt="" style="vertical-align:bottom;" />
   &nbsp;&nbsp;{$curPost.date|string_format:$modules.Language->getString('posted_on_x')}&nbsp;(<a href="{$smarty.const.INDEXFILE}?mode=viewthread&amp;forum_id={$forumID}&amp;thread={$topicID}&amp;z={$page}{$smarty.const.SID_AMPER}#post{$curPost.postID}">#{$curPost.postID}</a>)
   &nbsp;<img src="{$modules.Template->getTplDir()}images/trenner.gif" alt="|" style="vertical-align:bottom;" />
   {if $curPost.canModify}&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=edit&amp;forum_id={$forumID}&amp;topic_id={$topicID}&amp;post_id={$curPost.postID}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/edit.gif" alt="{$modules.Language->getString('edit')}" style="vertical-align:bottom;" /></a> {$modules.Language->getString('edit')}{/if}
   &nbsp;<a href="{$smarty.const.INDEXFILE}?faction=reply&amp;thread_id={$topicID}&amp;forum_id={$forumID}&amp;quote={$curPost.postID}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/quote.gif" alt="{$modules.Language->getString('quote')}" style="vertical-align:bottom;" /></a> {$modules.Language->getString('quote')}
   {if $curPost.sendPM}&nbsp;&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=send&amp;target_id={$curPost.userID}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/pm.gif" alt="{$modules.Language->getString('pm')}" style="vertical-align:bottom;" /></a> {$modules.Language->getString('pm')}{/if}
   {if $curPost.userEMail !== false}&nbsp;&nbsp;<a href="{if $curPost.userEMail === true}{$smarty.const.INDEXFILE}?faction=formmail&amp;target_id={$curPost.userID}{$smarty.const.SID_AMPER}{else}mailto:{$curPost.userEMail}{/if}"><img src="{$modules.Template->getTplDir()}images/mailto.gif" alt="{$modules.Language->getString('email')}" style="vertical-align:bottom;" /></a> {$modules.Language->getString('email')}{/if}
   {if !empty($curPost.userHP)}&nbsp;&nbsp;<a href="{$curPost.userHP}" target="_blank"><img src="{$modules.Template->getTplDir()}images/hp.gif" alt="{$modules.Language->getString('homepage')}" style="vertical-align:bottom;" /></a> {$modules.Language->getString('homepage')}{/if}
   {if $curPost.canModify}&nbsp;&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=edit&amp;mode=kill&amp;forum_id={$forumID}&amp;topic_id={$topicID}&amp;post_id={$curPost.postID}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/deltopic.gif" alt="" style="vertical-align:bottom;" /></a> {$modules.Language->getString('delete')}{/if}</span><hr />
   <div class="norm">
    {$curPost.post}{if $curPost.userSig != false}<br /><br />
    -----------------------<br />
    {$curPost.userSig}{/if}{if !empty($curPost.lastEditBy)}<hr style="margin-left:0; text-align:left; width:50%;" />
    <span class="small">{$curPost.lastEditBy|string_format:$modules.Language->getString('last_edit_by_x')}</span>{/if}
   </div>
  </td>
 </tr>
 <tr><td class="{cycle values="td1,td2"}" style="vertical-align:bottom; width:85%;">{if $modules.Config->getCfgVal('tspacing') < 1}<hr />{/if}<span style="font-family:Verdana; font-size:xx-small;">{* reuse sendPM value here *}{if $curPost.sendPM}{$curPost.userPosts|string_format:$modules.Language->getString('x_posts')} | {/if}{if $curPost.sendPM}{$curPost.userRegDate|string_format:$modules.Language->getString('member_since_x')} | {/if}{$curPost.postIPText}</span></td></tr>
{/foreach}
</table>

<!-- NavBar -->
<br />
<table class="navbar" cellspacing="0" cellpadding="0" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><td class="navbar"><span class="navbar">&nbsp;{foreach $modules.NavBar->getNavBar() as $curElement}{if !$curElement@last}<a href="{$curElement[1]}" class="navbar">{$curElement[0]}</a>{$smarty.config.navBarDelim}{else}{$curElement[0]}{/if}{/foreach}</span></td></tr>
</table>

{if $modules.Auth->isLoggedIn() && $isOpen}<br />
<form action="{$smarty.const.INDEXFILE}?faction=reply&amp;mode=save{$smarty.const.SID_AMPER}" method="post">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{$modules.Language->getString('quick_reply')}</span></th></tr>
 <tr>
  <td class="{cycle values="td1,td2" advance=false}" style="width:10%;"><textarea id="post" name="post" rows="10" cols="60"></textarea></td>
  <td class="{cycle values="td1,td2" advance=false}" style="vertical-align:top;"><br />{include file='Smilies.tpl' targetBoxID='post' isMod=$canModify}</td>
 </tr>
 <tr><td class="{cycle values="td1,td2" advance=false}" colspan="2"><input type="submit" value="{$modules.Language->getString('quick_reply')}" /></td></tr>
</table>
<input type="hidden" name="topic_id" value="{$topicID}" />
<input type="hidden" name="forum_id" value="{$forumID}" />
<input type="hidden" name="tsmilie" value="1" />{* not sure about this one *}
<input type="hidden" name="smilies" value="1" />
<input type="hidden" name="show_signatur" value="1" />
<input type="hidden" name="use_upbcode" value="1" />
<input type="hidden" name="isAddURLs" value="true" />
</form>{/if}

<!-- Toolbar -->
<br />
<table class="navbar" cellpadding="0" cellspacing="0" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr>
   <td class="navbar" style="width:33%;"><span class="navbar">&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=newtopic&amp;forum_id={$forumID}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/newtopic.gif" alt="" style="vertical-align:middle;" /></a>&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=newpoll&amp;forum_id={$forumID}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/newpoll.gif" alt="" style="vertical-align:middle;" /></a>&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=reply&amp;forum_id={$forumID}&amp;thread_id={$topicID}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/newreply.gif" alt="" style="vertical-align:middle;" /></a></span></td>
   <td class="navbar" style="width:34%; text-align:center;"><span class="navbar">{if $canModify}<a href="{$smarty.const.INDEXFILE}?faction=topic&amp;mode=killTopic&amp;forum_id={$forumID}&amp;topic_id={$topicID}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/deltopic.gif" alt="" /></a>&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=topic&amp;mode={if $isOpen}close&amp;forum_id={$forumID}&amp;topic_id={$topicID}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/closetopic.gif" alt="" />{else}open&amp;forum_id={$forumID}&amp;topic_id={$topicID}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/opentopic.gif" alt="" />{/if}</a>&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=topic&amp;mode=move&amp;forum_id={$forumID}&amp;topic_id={$topicID}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/movetopic.gif" alt="" /></a>&nbsp;{if $isSticky}<a href="{$smarty.const.INDEXFILE}?faction=topic&amp;mode=unpin&amp;forum_id={$forumID}&amp;topic_id={$topicID}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/unpintopic.png" alt="" /></a>{else}<a href="{$smarty.const.INDEXFILE}?faction=topic&amp;mode=pin&amp;forum_id={$forumID}&amp;topic_id={$topicID}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/pintopic.png" alt="" /></a>{/if}{else}&nbsp;{/if}</span></td>
   <td class="navbar" style="width:33%; text-align:right;"><span class="navbar">{$pageBar}</span></td>
 </tr>
</table>