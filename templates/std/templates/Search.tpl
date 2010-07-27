<!-- Search -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=search{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{$modules.Language->getString('search')}</span></th></tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('search_for_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="searchfor" /> <span class="small">{$modules.Language->getString('separate_words_with_spaces')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%; vertical-align:top;"><span class="norm">{$modules.Language->getString('search_option_colon')}</span></td>
  <td class="td1" style="width:80%;">
   <input type="radio" name="searchOption" value="and" checked="checked" />{$modules.Language->getString('all_terms')}<br />
   <input type="radio" name="searchOption" value="or" />{$modules.Language->getString('one_of_the_terms')}<br />
   <input type="radio" name="searchOption" value="exact" />{$modules.Language->getString('exact_input')}<br />
   <input type="radio" name="searchOption" value="user" />{$modules.Language->getString('user_id')}
  </td>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('maximum_age_colon')}</span></td>
  <td class="td1" style="width:80%;">
   <input type="radio" name="age" value="-1" checked="checked" />{$modules.Language->getString('dont_care')}
   <input type="radio" name="age" value="1" />{$modules.Language->getString('one_day')}
   <input type="radio" name="age" value="7" />{7|string_format:$modules.Language->getString('x_days')}
   <input type="radio" name="age" value="14" />{14|string_format:$modules.Language->getString('x_days')}
   <input type="radio" name="age" value="30" />{30|string_format:$modules.Language->getString('x_days')}
  </td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('search_in_colon')}</span></td>
  <td class="td1" style="width:80%;">
   <select name="auswahl" size="1">
    <option value="all">{$modules.Language->getString('search_all_forums')}</option>{foreach $cats as $curCat}
    <option value="" style="background-color:#333333; color:#FFFFFF;">--{$curCat[1]}</option>
    {foreach $forums as $curForum}{if $curForum.catID == $curCat[0]}<option value="{$curForum.forumID}">{$curForum.forumName}</option>{/if}{/foreach}
    <option value=""></option>{/foreach}
   </select>
   <select name="soption1">
    <option value="1">{$modules.Language->getString('titles_and_posts')}</option>
    <option value="2">{$modules.Language->getString('posts_only')}</option>
    <option value="3">{$modules.Language->getString('titles_only')}</option>
   </select>
  </td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('search')}" /></p>
<input type="hidden" name="search" value="yes" />
</form>