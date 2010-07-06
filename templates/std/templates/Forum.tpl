<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
{if $modules.Config->getCfgVal('news_position') == 1}{include file='News.tpl'}{/if}
 <tr class="thsmall">
  <th class="thsmall" colspan="2"><span class="thsmall">{$modules.Language->getString('forum')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('topics')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('postings')}</span></th>
  <th class="thsmall" style="width:28%;"><span class="thsmall">{$modules.Language->getString('last_post')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('moderators')}</span></th>
 </tr>
{if $modules.Config->getCfgVal('news_position') == 2}{include file='News.tpl'}{/if}
</table>