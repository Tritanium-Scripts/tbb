<form method="post" action="index.php?faction=search&amp;doit=1&amp;{$MYSID}">
<table class="tablestd" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Search']}</span></td></tr>
<template:errorrow>
 <tr><td class="cellerror" colspan="2"><span class="fonterror">{$error}</span></td></tr>
</template>
<tr>
 <td class="cellstd" width="30%" valign="top"><span class="fontnorm"><b>{$LNG['Search_for_keywords']}:</b></span><br /><span class="fontsmall">{$LNG['search_keywords_info']}</span></td>
 <td class="cellalt" width="70%" valign="top"><input class="form_text" type="text" name="p_search_words" value="{$p_search_words}" size="50" /><br /><span class="fontsmall"><input type="checkbox" name="p_search_words_exact" value="1" />&nbsp;{$LNG['Exact_search']}</span></td>
</tr>
<tr>
 <td class="cellstd" width="30%" valign="top"><span class="fontnorm"><b>{$LNG['Search_for_author']}:</b></span><br /><span class="fontsmall">{$LNG['search_author_info']}</span></td>
 <td class="cellalt" width="70%" valign="top"><input class="form_text" type="text" name="p_search_author" value="{$p_search_author}" size="20" /></td>
</tr>
<tr><td class="cellcat" colspan="2"><span class="fontcat">{$LNG['Advanced_search_options']}</span></td></tr>
<tr>
 <td class="cellstd" colspan="2">
  <table border="0" cellpadding="3" cellspacing="0">
  <tr>
   <td valign="top">
     <fieldset style="padding:3px"><legend><span class="fontsmall"><b>{$LNG['Forums']}</b></span></legend>
     <select class="form_select" name="p_search_forums[]" size="10" multiple="multiple"><option value="all">{$LNG['Search_all_forums']}</option><option value=""></option>
     <template:optionrow>
      <option value="{$akt_option_value}">{$akt_option_text}</option>
     </template>
     </select></fieldset>
    </td>
    <td valign="top">
     <fieldset style="padding:3px"><legend><span class="fontsmall"><b>{$LNG['Sort_by']}</b></span></legend>
     <select class="form_select" name="p_search_sort_by"><option value="0">{$LNG['Post_age']}</option></select><br />
     <span class="fontsmall"><input type="radio" name="p_search_sort_method" value="0" /> {$LNG['Descending']}&nbsp;&nbsp;&nbsp;<input type="radio" name="p_search_sort_method" value="1" /> {$LNG['Ascending']}</span>
     </fieldset>     
     <fieldset style="padding:3px"><legend><span class="fontsmall"><b>{$LNG['Results']}</b></span></legend>
     <select class="form_select" name="p_display_results"><option value="0">{$LNG['Display_as_topics']}</option><option value="1">{$LNG['Display_as_posts']}</option></select><br />
     </fieldset>
    </td>
   </tr>
   </table>
 </td>
</tr>
<tr><td class="cellbuttons" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Start_search']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>