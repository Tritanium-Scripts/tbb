<!-- Search -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=search{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <colgroup>
  <col width="30%" />
  <col width="70%" />
 </colgroup>
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('search')}</span></th></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_SEARCH_FORM_START}
 <tr>
  <td class="cellStd" style="font-weight:bold;"><span class="fontNorm">{Language::getInstance()->getString('search_for_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="searchfor" value="{$searchFor}" style="width:350px;" /><br /><span class="fontSmall">{Language::getInstance()->getString('separate_words_with_spaces')}</span></td>
 </tr>
 <tr>
  <td class="cellStd" style="font-weight:bold; vertical-align:top;"><span class="fontNorm">{Language::getInstance()->getString('search_option_colon')}</span></td>
  <td class="cellAlt">
   <input type="radio" id="searchOptionAnd" name="searchOption" value="and"{if empty($searchOption) || $searchOption == 'and'} checked="checked"{/if} /><label for="searchOptionAnd" class="fontNorm">{Language::getInstance()->getString('all_terms')} <span class="fontSmall">{Language::getInstance()->getString('example_and')}</span></label><br />
   <input type="radio" id="searchOptionOr" name="searchOption" value="or"{if $searchOption == 'or'} checked="checked"{/if} /><label for="searchOptionOr" class="fontNorm">{Language::getInstance()->getString('one_of_the_terms')} <span class="fontSmall">{Language::getInstance()->getString('example_or')}</span></label><br />
   <input type="radio" id="searchOptionExact" name="searchOption" value="exact"{if $searchOption == 'exact'} checked="checked"{/if} /><label for="searchOptionExact" class="fontNorm">{Language::getInstance()->getString('exact_input')} <span class="fontSmall">{Language::getInstance()->getString('example_exact')}</span></label><br />
   <input type="radio" id="searchOptionUser" name="searchOption" value="user"{if $searchOption == 'user'} checked="checked"{/if} /><label for="searchOptionUser" class="fontNorm">{Language::getInstance()->getString('user')} <span class="fontSmall">{Language::getInstance()->getString('example_user')}</span></label>
  </td>
 </tr>
 <tr><th class="cellCat" colspan="2"><span class="fontCat">{$smarty.config.langExtended}</span></th></tr>
 <tr>
  <td class="cellStd" colspan="2">
   <table border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td style="vertical-align:top;">
      <fieldset style="padding:3px; margin-right:5px;">
       <legend class="fontSmall" style="font-weight:bold;">{$smarty.config.langForums}</legend>
       <select class="formSelect" name="auswahl" size="10">
        <option value="all">{Language::getInstance()->getString('search_all_forums')}</option>{foreach $cats as $curCat}
        <option value=""></option>
        <option value="" style="background-color:gray; color:#FFFFFF;">--{$curCat[1]}</option>
        {foreach $forums as $curForum}{if $curForum.catID == $curCat[0]}<option value="{$curForum.forumID}"{if $searchIn == $curForum.forumID} selected="selected"{/if}>{$curForum.forumName}</option>{/if}{/foreach}{/foreach}
       </select>
      </fieldset>
     </td>
     <td style="vertical-align:top;">
      <fieldset style="padding:3px;">
       <legend class="fontSmall" style="font-weight:bold;">{Language::getInstance()->getString('maximum_age_colon')}</legend>
       <select class="formSelect" name="age">
        <option value="0"{if $searchAge == 0} selected="selected"{/if}>{Language::getInstance()->getString('dont_care')}</option>
        <option value="1"{if $searchAge == 1} selected="selected"{/if}>{Language::getInstance()->getString('one_day')}</option>
        <option value="7"{if $searchAge == 7} selected="selected"{/if}>{7|string_format:Language::getInstance()->getString('x_days')}</option>
        <option value="14"{if $searchAge == 14} selected="selected"{/if}>{14|string_format:Language::getInstance()->getString('x_days')}</option>
        <option value="30"{if $searchAge == 30} selected="selected"{/if}>{30|string_format:Language::getInstance()->getString('x_days')}</option>
       </select>
      </fieldset>
      <fieldset style="padding:3px;">
       <legend class="fontSmall" style="font-weight:bold;">{Language::getInstance()->getString('search_in_colon')}</legend>
       <select class="formSelect" name="soption1">
        {html_options values=array(1, 2, 3) output=array(Language::getInstance()->getString('titles_and_posts'), Language::getInstance()->getString('posts_only'), Language::getInstance()->getString('titles_only')) selected=$searchScope}
       </select>
      </fieldset>
     </td>
    </tr>
   </table>
  </td>
 </tr>
{plugin_hook hook=PlugIns::HOOK_TPL_SEARCH_FORM_END}
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('search')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" />{plugin_hook hook=PlugIns::HOOK_TPL_SEARCH_BUTTONS}</p>
<p class="fontSmall" style="text-align:center;">{Language::getInstance()->getString('search_hint')}</p>
<input type="hidden" name="search" value="yes" />
</form>