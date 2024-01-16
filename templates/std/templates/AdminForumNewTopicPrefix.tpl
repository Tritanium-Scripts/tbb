<!-- AdminForumNewTopicPrefix -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=newTopicPrefix&amp;forum_id={$forumID}{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th colspan="2" class="thnorm"><span class="thnorm">{Language::getInstance()->getString('add_new_topic_prefix')}</span></th></tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('prefix')}</span></td>
  <td class="td1"><input type="text" name="prefix" value="{$newPrefix}" /></td>
 </tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('color')}</span></td>
  <td class="td1"><input type="text" name="color" value="{$newColor}" style="color:{$newColor};" onchange="this.style.color = this.value;" /> <span class="small">{Language::getInstance()->getString('color_hint')}</span></td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('add_new_topic_prefix')}" /></p>
<input type="hidden" name="change" value="yes" />
</form>