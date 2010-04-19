<form method="post" action="index.php?faction=search&amp;doit=1&amp;{$MYSID}">
<table class="tbl" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['Search']}</span></th></tr>
<template:errorrow>
 <tr><td class="error" colspan="2"><span class="error">{errorrow.$error}</span></td></tr>
</template:errorrow>
<tr>
 <td class="td1" width="30%" valign="top"><span class="norm"><b>{$lng['Search_for_keywords']}:</b></span><br /><span class="small">{$lng['search_keywords_info']}</span></td>
 <td class="td2" width="70%" valign="top"><input class="form_text" type="text" name="p_search_words" value="{$p_search_words}" size="50" /><br /><span class="small"><input type="checkbox" name="p_search_words_exact" value="1" />&nbsp;{$lng['Exact_search']}</span></td>
</tr>
<tr>
 <td class="td1" width="30%" valign="top"><span class="norm"><b>{$lng['Search_for_author']}:</b></span><br /><span class="small">{$lng['search_author_info']}</span></td>
 <td class="td2" width="70%" valign="top"><input class="form_text" type="text" name="p_search_author" value="{$p_search_author}" size="20" /></td>
</tr>
<tr><td class="cat" colspan="2"><span class="cat">{$lng['Advanced_search_options']}</span></td></tr>
<tr>
 <td class="td1" colspan="2">
  <table border="0" cellpadding="3" cellspacing="0">
  <tr>
   <td valign="top">
     <fieldset style="padding:3px"><legend><span class="small"><b>{$lng['Forums']}</b></span></legend>
     <select class="form_select" name="p_search_forums[]" size="10" multiple="multiple"><option value="all">{$lng['Search_all_forums']}</option><option value=""></option>
     <template:optionrow>
      <option value="{optionrow.$akt_option_value}">{optionrow.$akt_option_text}</option>
     </template:optionrow>
     </select></fieldset>
    </td>
    <td valign="top">
     <fieldset style="padding:3px"><legend><span class="small"><b>{$lng['Sort_by']}</b></span></legend>
     <select class="form_select" name="p_search_sort_by"><option value="0">{$lng['Post_age']}</option></select><br />
     <span class="small"><input type="radio" name="p_search_sort_method" value="0" /> {$lng['Descending']}&nbsp;&nbsp;&nbsp;<input type="radio" name="p_search_sort_method" value="1" /> {$lng['Ascending']}</span>
     </fieldset>
     
     <fieldset style="padding:3px"><legend><span class="small"><b>{$lng['Results']}</b></span></legend>
     <select class="form_select" name="p_display_results"><option value="0">{$lng['Display_as_topics']}</option><option value="1">{$lng['Display_as_posts']}</option></select><br />
     </fieldset>
    </td>
   </tr>
   </table>
 </td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$lng['Start_search']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng['Reset']}" /></td></tr>
</table>
</form>