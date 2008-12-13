<table class="TableStd" width="100%">
<tr><td class="CellCat"><span class="FontCat">{$modules.Language->getString('overview')}</span></td></tr>
<tr><td class="CellStd">
 <table width="100%">
 <colgroup>
  <col width="50%"/>
  <col width="50%"/>
 </colgroup>
 <tr>
  <td style="padding:10px;"><span class="FontBig"><a href="{$smarty.const.INDEXFILE}?action=AdminUsers&amp;{$smarty.const.MYSID}">{$modules.Language->getString('manage_users')}</a></span><br/><span class="FontSmall">- <a href="{$smarty.const.INDEXFILE}?action=AdminUsers&amp;mode=AddUser&amp;{$smarty.const.MYSID}">{$modules.Language->getString('add_user')}</a></span></td>
  <td style="padding:10px;"><span class="FontBig"><a href="{$smarty.const.INDEXFILE}?action=AdminProfileFields&amp;{$smarty.const.MYSID}">Profilfelder verwalten</a></span><br/><span class="FontSmall">- <a href="{$smarty.const.INDEXFILE}?action=AdminProfileFields&amp;mode=AddField&amp;{$smarty.const.MYSID}">Profilfeld anlegen</a></span></td>
 </tr>
 <tr>
  <td style="padding:10px;"><span class="FontBig"><a href="{$smarty.const.INDEXFILE}?action=AdminForums&amp;{$smarty.const.MYSID}">{$modules.Language->getString('manage_forums')}</a></span><br/><span class="FontSmall">- <a href="{$smarty.const.INDEXFILE}?action=AdminForums&amp;mode=AddCat&amp;{$smarty.const.MYSID}">{$modules.Language->getString('add_category')}</a><br/>- <a href="{$smarty.const.INDEXFILE}?action=AdminForums&amp;mode=AddForum&amp;{$smarty.const.MYSID}">{$modules.Language->getString('add_forum')}</a></span></td>
  <td style="padding:10px;"><span class="FontBig"><a href="{$smarty.const.INDEXFILE}?action=AdminSmilies&amp;{$smarty.const.MYSID}">Smilies verwalten</a></span><br/><span class="FontSmall">- <a href="{$smarty.const.INDEXFILE}?action=AdminSmilies&amp;mode=addSmiley&amp;smileyType=0&amp;{$smarty.const.MYSID}">Smiley hinzuf&uuml;gen</a><br/>- <a href="{$smarty.const.INDEXFILE}?action=AdminSmilies&amp;mode=addSmiley&amp;smileyType=1&amp;{$smarty.const.MYSID}">Themenbild hinzuf&uuml;gen</a></span></td>
 </tr>
 </table>
</td></tr>
</table>