{include file='AdminMenu.tpl'}
<!-- AdminForumNewTopicPrefix -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=newTopicPrefix&amp;forum_id={$forumID}{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th colspan="2" class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('add_new_topic_prefix')}</span></th></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_FORUM_NEW_TOPIC_PREFIX_FORM_START}
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('prefix')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="prefix" value="{$newPrefix}" /></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('color')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="color" value="{$newColor}" style="color:{$newColor};" onchange="this.style.color = this.value;" /> <span class="fontSmall">{Language::getInstance()->getString('color_hint')}</span></td>
 </tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_FORUM_NEW_TOPIC_PREFIX_FORM_END}
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('add_new_topic_prefix')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" />{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_FORUM_NEW_TOPIC_PREFIX_BUTTONS}</p>
<input type="hidden" name="change" value="yes" />
</form>
{include file='AdminMenuTail.tpl'}