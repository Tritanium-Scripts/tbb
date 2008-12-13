<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('manage_avatars')}</span></td></tr>
{foreach from=$avatarsData item=curAvatar}
 <tr>
  <td class="CellStd"><img src="{$curAvatar.avatarAddress}" alt=""/></td>
  <td class="CellAlt" align="right"><span class="FontSmall"><a href="{$smarty.const.INDEXFILE}?action=AdminAvatars&amp;mode=DeleteAvatar&amp;avatarID={$curAvatar.avatarID}&amp;{$smarty.const.MYSID}">{$modules.Language->getString('delete')}</a> | <a href="{$smarty.const.INDEXFILE}?action=AdminAvatars&amp;mode=EditAvatar&amp;avatarID={$curAvatar.avatarID}&amp;{$smarty.const.MYSID}">{$modules.Language->getString('edit')}</a></span></td>
 </tr>
{/foreach}
</table>
<br/>
<table class="TableStd" width="100%">
<tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('other_options')}</span></td></tr>
<tr><td class="CellStd"><span class="FontNorm"><a href="{$smarty.const.INDEXFILE}?action=AdminAvatars&amp;mode=AddAvatar&amp;{$smarty.const.MYSID}">{$modules.Language->getString('add_avatar')}</a></span></td></tr>
</table>
