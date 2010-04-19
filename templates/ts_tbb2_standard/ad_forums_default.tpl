<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="3"><span class="thnorm">{LNG_MANAGE_FORUMS}</span></th></tr>
<!-- TPLBLOCK catrow -->
 <tr>
  <td class="adcat"><span class="norm">{catrow.CAT_NAME}</span></td>
  <td class="adcat"><span class="small">{catrow.APPENDIX} {catrow.MOVEUP} | {catrow.MOVEDOWN}</span></td>
  <td class="adcat" align="right"><span class="small"><a href="administration.php?faction=ad_forums&amp;mode=editcat&amp;cat_id={catrow.CAT_ID}&amp;{catrow.MYSID}">{catrow.LNG_EDIT}</a> | <a href="administration.php?faction=ad_forums&amp;mode=addcat&amp;parent_id={catrow.CAT_ID}&amp;{catrow.MYSID}">{catrow.LNG_ADD_SUB_CATEGORY}</a> | <a href="administration.php?faction=ad_forums&amp;mode=addforum&amp;cat_id={catrow.CAT_ID}&amp;{catrow.MYSID}">{catrow.LNG_ADD_FORUM}</a></span></td>
 </tr>
 <!-- TPLBLOCK catrow.forumrow -->
 <tr>
  <td class="{catrow.forumrow.AKT_CLASS}"><span class="norm">{catrow.forumrow.FORUM_NAME}</span></td>
  <td class="{catrow.forumrow.AKT_CLASS}"><span class="small">{catrow.forumrow.APPENDIX} {catrow.forumrow.MOVEUP} | {catrow.forumrow.MOVEDOWN}</span></td>
  <td class="{catrow.forumrow.AKT_CLASS}" align="right"><span class="small"><a href="administration.php?faction=ad_forums&amp;mode=editforum&amp;forum_id={catrow.forumrow.FORUM_ID}&amp;{catrow.forumrow.MYSID}">{catrow.forumrow.LNG_EDIT}</a></span></td>
 </tr>
 <!-- /TPLBLOCK catrow.forumrow -->
<!-- /TPLBLOCK catrow -->
</table>
<br />
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{LNG_FORUMS_WITHOUT_CATEGORY}</span></th></tr>
 <!-- TPLBLOCK forumrow -->
 <tr>
  <td class="{forumrow.AKT_CLASS}"><span class="norm">{forumrow.FORUM_NAME}</span></td>
  <td class="{forumrow.AKT_CLASS}" align="right"><span class="small"><a href="administration.php?faction=ad_forums&amp;mode=editforum&amp;forum_id={forumrow.FORUM_ID}&amp;{forumrow.MYSID}">{forumrow.LNG_EDIT}</a></span></td>
 </tr>
 <!-- /TPLBLOCK forumrow -->
</table>
<br />
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm"><span class="thnorm">{LNG_OTHER_OPTIONS}</span></th></tr>
<tr><td class="td1"><span class="norm"><a href="administration.php?faction=ad_forums&amp;mode=addcat&amp;parent_id=0&amp;{MYSID}">{LNG_ADD_CATEGORY}</a><br /><a href="administration.php?faction=ad_forums&amp;mode=addforum&amp;cat_id=0&amp;{MYSID}">{LNG_ADD_FORUM}</a></span></td></tr>
</table>