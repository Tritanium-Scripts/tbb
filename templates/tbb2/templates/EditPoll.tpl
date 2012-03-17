<!-- EditPoll -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=editpoll&amp;forum_id={$forumID}&amp;topic_id={$topicID}&amp;poll_id={$pollID}&amp;mode=update{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{$modules.Language->getString('edit_poll')}</span></th></tr>
 <tr><td class="cellCat" colspan="2"><span class="fontCat">{$modules.Language->getString('question_title')}</span></td></tr>
 <tr><td class="cellStd"><span class="fontNorm" style="font-weight:bold;">{$pollTitle}</span></td></tr>
 <tr><td class="cellCat" colspan="2"><span class="fontCat">{$modules.Language->getString('choices')}</span></td></tr>
 <tr><td class="cellStd"><span class="fontNorm">{foreach $pollOptions as $curOption}<input type="text" name="poll_choice[{$curOption[0]}]" value="{$curOption[1]}" style="width:300px;" /><br />{/foreach}</span></td></tr>
</table>
<p class="cellButtons"><input class="formButton" type="submit" name="update" value="{$modules.Language->getString('edit_poll')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="submit" name="{if $isClosed}open" value="{$modules.Language->getString('open_poll')}{else}close" value="{$modules.Language->getString('close_poll')}{/if}" /></p>
</form>