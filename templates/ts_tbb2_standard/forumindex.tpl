<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <th class="thsmall">{LNG_FORUM}</th>
 <th class="thsmall">{LNG_TOPICS}</th>
 <th class="thsmall">{LNG_POSTS}</th>
 <th class="thsmall">{LNG_LAST_POST}</th>
 <th class="thsmall">{LNG_MODERATORS}</th>
</tr>
<!-- TPLBLOCK catrow -->
 <tr>
  <td colspan="5" class="cat">{catrow.APPENDIX}{catrow.PLUS_MINUS_PIC}&nbsp;<span class="cat">{catrow.CAT_NAME}</span></td>
 </tr>
 <!-- TPLBLOCK catrow.forumrow -->
  <tr>
   <td class="td2">
    <table border="0" cellspacing="0" cellpadding="0">
     <tr>
      <td rowspan="2">{catrow.forumrow.APPENDIX}{catrow.forumrow.NEW_POST_STATUS}&nbsp;</td>
      <td><span class="forumlink"><a class="forumlink" href="index.php?faction=viewforum&amp;forum_id={catrow.forumrow.FORUM_ID}&amp;{catrow.forumrow.MYSID}">{catrow.forumrow.FORUM_NAME}</a></span></td>
     </tr>
     <tr><td><span class="small">{catrow.forumrow.FORUM_DESCRIPTION}</span></td></tr>
    </table></td>
   <td class="td1" align="center"><span class="small">{catrow.forumrow.FORUM_TOPICS_COUNTER}</span></td>
   <td class="td2" align="center"><span class="small">{catrow.forumrow.FORUM_POSTS_COUNTER}</span></td>
   <td class="td1" align="center">
    <table border="0" cellpadding="0" cellspacing="0">
    <tr>
     <td>{catrow.forumrow.LAST_POST_PIC}</td>
     <td><span class="small">{catrow.forumrow.LAST_POST_TEXT}</span></td>
    </tr>
    </table>
   </td>
   <td class="td2"><span class="norm">{catrow.forumrow.FORUM_MODS}</span></td>
  </tr>
 <!-- /TPLBLOCK catrow.forumrow -->
<!-- /TPLBLOCK catrow -->
</table>
<!-- TPLBLOCK wiobox -->
<br />
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm"><span class="thnorm">{wiobox.LNG_WHO_IS_ONLINE}</span></th></tr>
<tr><td class="td1"><span class="small">{wiobox.MEMBERS_COUNTER}<br />{wiobox.GUESTS_COUNTER}</span></td>
</table>
<!-- /TPLBLOCK wiobox -->