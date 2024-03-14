<!-- EditPoll -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=editpoll&amp;forum_id={$forumID}&amp;topic_id={$topicID}&amp;poll_id={$pollID}&amp;mode=update{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('edit_poll')}</span></th></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_POSTING_EDIT_POLL_FORM_START}
 <tr><td class="cellCat" colspan="2"><span class="fontCat">{Language::getInstance()->getString('question_title')}</span></td></tr>
 <tr><td class="cellStd"><span class="fontNorm" style="font-weight:bold;">{$pollTitle}</span></td></tr>
 <tr><td class="cellCat" colspan="2"><span class="fontCat">{Language::getInstance()->getString('choices')}</span></td></tr>
 <tr><td class="cellStd"><span class="fontNorm">{foreach $pollOptions as $curOption}<input type="text" name="poll_choice[{$curOption[0]}]" value="{$curOption[1]}" style="width:300px;" /><br />{/foreach}</span></td></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_POSTING_EDIT_POLL_FORM_END}
</table>
<p class="cellButtons"><input class="formButton" type="submit" name="update" value="{Language::getInstance()->getString('edit_poll')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="submit" name="{if $isClosed}open" value="{Language::getInstance()->getString('open_poll')}{else}close" value="{Language::getInstance()->getString('close_poll')}{/if}" />{plugin_hook hook=PlugIns::HOOK_TPL_POSTING_EDIT_POLL_BUTTONS}</p>
</form>