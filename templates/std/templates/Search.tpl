<!-- Search -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=search{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{Language::getInstance()->getString('search')}</span></th></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_SEARCH_FORM_START}
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('search_for_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="searchfor" value="{$searchFor}" style="width:350px;" /><br /><span class="small">{Language::getInstance()->getString('separate_words_with_spaces')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%; vertical-align:top;"><span class="norm">{Language::getInstance()->getString('search_option_colon')}</span></td>
  <td class="td1" style="width:80%;">
   <input type="radio" id="searchOptionAnd" name="searchOption" value="and"{if empty($searchOption) || $searchOption == 'and'} checked="checked"{/if} /><label for="searchOptionAnd" class="norm">{Language::getInstance()->getString('all_terms')} <span class="small">{Language::getInstance()->getString('example_and')}</span></label><br />
   <input type="radio" id="searchOptionOr" name="searchOption" value="or"{if $searchOption == 'or'} checked="checked"{/if} /><label for="searchOptionOr" class="norm">{Language::getInstance()->getString('one_of_the_terms')} <span class="small">{Language::getInstance()->getString('example_or')}</span></label><br />
   <input type="radio" id="searchOptionExact" name="searchOption" value="exact"{if $searchOption == 'exact'} checked="checked"{/if} /><label for="searchOptionExact" class="norm">{Language::getInstance()->getString('exact_input')} <span class="small">{Language::getInstance()->getString('example_exact')}</span></label><br />
   <input type="radio" id="searchOptionUser" name="searchOption" value="user"{if $searchOption == 'user'} checked="checked"{/if} /><label for="searchOptionUser" class="norm">{Language::getInstance()->getString('user')} <span class="small">{Language::getInstance()->getString('example_user')}</span></label>
  </td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('maximum_age_colon')}</span></td>
  <td class="td1" style="width:80%;">
   <input type="radio" id="age0" name="age" value="0"{if $searchAge == 0} checked="checked"{/if} /><label for="age0" class="norm">{Language::getInstance()->getString('dont_care')}</label>
   <input type="radio" id="age1" name="age" value="1"{if $searchAge == 1} checked="checked"{/if} /><label for="age1" class="norm">{Language::getInstance()->getString('one_day')}</label>
   <input type="radio" id="age7" name="age" value="7"{if $searchAge == 7} checked="checked"{/if} /><label for="age7" class="norm">{7|string_format:Language::getInstance()->getString('x_days')}</label>
   <input type="radio" id="age14" name="age" value="14"{if $searchAge == 14} checked="checked"{/if} /><label for="age14" class="norm">{14|string_format:Language::getInstance()->getString('x_days')}</label>
   <input type="radio" id="age30" name="age" value="30"{if $searchAge == 30} checked="checked"{/if} /><label for="age30" class="norm">{30|string_format:Language::getInstance()->getString('x_days')}</label>
  </td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('search_in_colon')}</span></td>
  <td class="td1" style="width:80%;">
   <select name="auswahl" size="1">
    <option value="all">{Language::getInstance()->getString('search_all_forums')}</option>{foreach $cats as $curCat}
    <option value="" style="background-color:#333333; color:#FFFFFF;">--{$curCat[1]}</option>
    {foreach $forums as $curForum}{if $curForum.catID == $curCat[0]}<option value="{$curForum.forumID}"{if $searchIn == $curForum.forumID} selected="selected"{/if}>{$curForum.forumName}</option>{/if}{/foreach}
    <option value=""></option>{/foreach}
   </select>
   {html_options name='soption1' values=array(1, 2, 3) output=array(Language::getInstance()->getString('titles_and_posts'), Language::getInstance()->getString('posts_only'), Language::getInstance()->getString('titles_only')) selected=$searchScope}
  </td>
 </tr>
{plugin_hook hook=PlugIns::HOOK_TPL_SEARCH_FORM_END}
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('search')}" />{plugin_hook hook=PlugIns::HOOK_TPL_SEARCH_BUTTONS}<br /><br /><span class="small">{Language::getInstance()->getString('search_hint')}</span></p>
<input type="hidden" name="search" value="yes" />
</form>