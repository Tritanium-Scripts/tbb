<!-- EditPoll -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=editpoll&amp;forum_id={$forumID}&amp;topic_id={$topicID}&amp;poll_id={$pollID}&amp;mode=update{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{Language::getInstance()->getString('edit_poll')}</span></th></tr>
 <tr><td class="kat" colspan="2"><span class="kat">{Language::getInstance()->getString('question_title')}</span></td></tr>
 <tr><td class="td1"><span class="norm" style="font-weight:bold;">{$pollTitle}</span></td></tr>
 <tr><td class="kat" colspan="2"><span class="kat">{Language::getInstance()->getString('choices')}</span></td></tr>
 <tr><td class="td1"><span class="norm">{foreach $pollOptions as $curOption}{$curOption@iteration}: <input type="text" name="poll_choice[{$curOption[0]}]" value="{$curOption[1]}" style="width:300px;" /><br />{/foreach}</span></td></tr>
</table>
<p style="text-align:center;"><input type="submit" name="update" value="{Language::getInstance()->getString('edit_poll')}" />&nbsp;&nbsp;&nbsp;<input type="submit" name="{if $isClosed}open" value="{Language::getInstance()->getString('open_poll')}{else}close" value="{Language::getInstance()->getString('close_poll')}{/if}" /></p>
</form>