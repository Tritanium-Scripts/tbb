<!-- Search -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=search{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{$modules.Language->getString('search')}</span></th></tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('search_for_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="searchfor" value="{$searchFor}" style="width:350px;" /><br /><span class="small">{$modules.Language->getString('separate_words_with_spaces')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%; vertical-align:top;"><span class="norm">{$modules.Language->getString('search_option_colon')}</span></td>
  <td class="td1" style="width:80%;">
   <input type="radio" id="searchOptionAnd" name="searchOption" value="and"{if empty($searchOption) || $searchOption == 'and'} checked="checked"{/if} /><label for="searchOptionAnd" class="norm">{$modules.Language->getString('all_terms')} <span class="small">{$modules.Language->getString('example_and')}</span></label><br />
   <input type="radio" id="searchOptionOr" name="searchOption" value="or"{if $searchOption == 'or'} checked="checked"{/if} /><label for="searchOptionOr" class="norm">{$modules.Language->getString('one_of_the_terms')} <span class="small">{$modules.Language->getString('example_or')}</span></label><br />
   <input type="radio" id="searchOptionExact" name="searchOption" value="exact"{if $searchOption == 'exact'} checked="checked"{/if} /><label for="searchOptionExact" class="norm">{$modules.Language->getString('exact_input')} <span class="small">{$modules.Language->getString('example_exact')}</span></label><br />
   <input type="radio" id="searchOptionUser" name="searchOption" value="user"{if $searchOption == 'user'} checked="checked"{/if} /><label for="searchOptionUser" class="norm">{$modules.Language->getString('user')} <span class="small">{$modules.Language->getString('example_user')}</span></label>
  </td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('maximum_age_colon')}</span></td>
  <td class="td1" style="width:80%;">
   <input type="radio" id="age0" name="age" value="0"{if $searchAge == 0} checked="checked"{/if} /><label for="age0" class="norm">{$modules.Language->getString('dont_care')}</label>
   <input type="radio" id="age1" name="age" value="1"{if $searchAge == 1} checked="checked"{/if} /><label for="age1" class="norm">{$modules.Language->getString('one_day')}</label>
   <input type="radio" id="age7" name="age" value="7"{if $searchAge == 7} checked="checked"{/if} /><label for="age7" class="norm">{7|string_format:$modules.Language->getString('x_days')}</label>
   <input type="radio" id="age14" name="age" value="14"{if $searchAge == 14} checked="checked"{/if} /><label for="age14" class="norm">{14|string_format:$modules.Language->getString('x_days')}</label>
   <input type="radio" id="age30" name="age" value="30"{if $searchAge == 30} checked="checked"{/if} /><label for="age30" class="norm">{30|string_format:$modules.Language->getString('x_days')}</label>
  </td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('search_in_colon')}</span></td>
  <td class="td1" style="width:80%;">
   <select name="auswahl" size="1">
    <option value="all">{$modules.Language->getString('search_all_forums')}</option>{foreach $cats as $curCat}
    <option value="" style="background-color:#333333; color:#FFFFFF;">--{$curCat[1]}</option>
    {foreach $forums as $curForum}{if $curForum.catID == $curCat[0]}<option value="{$curForum.forumID}"{if $searchIn == $curForum.forumID} selected="selected"{/if}>{$curForum.forumName}</option>{/if}{/foreach}
    <option value=""></option>{/foreach}
   </select>
   <select name="soption1">
    <option value="1"{if $searchScope == 1} selected="selected"{/if}>{$modules.Language->getString('titles_and_posts')}</option>
    <option value="2"{if $searchScope == 2} selected="selected"{/if}>{$modules.Language->getString('posts_only')}</option>
    <option value="3"{if $searchScope == 3} selected="selected"{/if}>{$modules.Language->getString('titles_only')}</option>
   </select>
  </td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('search')}" /><br /><br /><span class="small">{$modules.Language->getString('search_hint')}</span></p>
<input type="hidden" name="search" value="yes" />
</form>