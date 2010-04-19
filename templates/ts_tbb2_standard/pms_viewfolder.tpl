<script type="text/javascript">
<!--
 function submit_form() {
 	if(document.forms['tbb_form'].elements['p_action'].options[document.forms['tbb_form'].elements['p_action'].selectedIndex].value != '') {
 		document.forms['tbb_form'].action = document.forms['tbb_form'].elements['p_action'].options[document.forms['tbb_form'].elements['p_action'].selectedIndex].value;
 		document.forms['tbb_form'].submit();
 	}
 }
//-->
</script>
<table class="navbar" border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
 <td class="navbar"><span class="navbar">{$page_listing}</span></td>
 <td class="navbar" align="right"><span class="navbar"><a href="index.php?faction=pms&amp;mode=newpm&amp;{$MYSID}">{$LNG['New_private_message']}</a> | <a href="index.php?faction=pms&amp;mode=addfolder&amp;{$MYSID}">{$LNG['Add_folder']}</a></span></td></tr>
</table>
<br />
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle"><span class="fonttitle">{$LNG['Private_messages']}</span></td></tr>
<tr><td class="cellstd">
 <table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <td valign="top" width="200">
   <table class="tablenav" border="0" cellpadding="3" cellspacing="0" width="100%">
   <tr><td class="cellcat"><span class="fontcat">{$LNG['Folders']}</span></td></tr>
   <template:folderrow>
    <tr><td class="cellnav_inactive" onmouseover="change_class(this,'cellnav_hover');" onmouseout="change_class(this,'cellnav_inactive');"><span class="fontnorm"><a href="index.php?faction=pms&amp;mode=viewfolder&amp;folder_id={$akt_folder['folder_id']}&amp;{$MYSID}">{$akt_folder['folder_name']}</a></td></tr>
   </template>
   </table>
  </td>
  <td valign="top" width="10">&nbsp;</td>
  <td valign="top">
   <form method="post" action="index.php?faction=pms&amp;mode=viewfolder&amp;folder_id={$folder_id}&amp;z={$z}&amp;{$MYSID}" name="tbb_form">
   <table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
   <tr>
    <td class="cellcat"><span class="fonttitle">&nbsp;</span></td>
    <td class="cellcat" align="center"><span class="fonttitle">{$LNG['Subject']}</span></td>
    <td class="cellcat"><span class="fonttitle">&nbsp;</span></td>
    <td class="cellcat" align="center"><span class="fonttitle">{$LNG['Date']}</span></td>
    <td class="cellcat"><span class="fonttitle">&nbsp;</span></td>
   </tr>
   <template:pmrow>
    <tr>
     <td class="cellalt" align="center"><input type="checkbox" value="{$akt_pm['pm_id']}" name="pm_ids[]" /></td>
     <td class="cellstd"><span class="fontnorm"><a href="index.php?faction=pms&amp;mode=viewpm&amp;pm_id={$akt_pm['pm_id']}&amp;{$MYSID}">{$akt_pm['pm_subject']}</a></span></td>
     <td class="cellalt"><span class="fontsmall">{$akt_sender}</span></td>
     <td class="cellstd" align="center"><span class="fontsmall">{$akt_date}</span></td>
     <td class="cellalt" align="right"><span class="fontsmall"><a href="index.php?faction=pms&amp;mode=deletepms&amp;pm_id={$akt_pm['pm_id']}&amp;{$MYSID}">{$LNG['delete']}</a></span></td>
    </tr>
   </template>
   <template:nopms>
    <tr><td class="cellstd" colspan="5" align="center"><span class="fontnorm">-- {$LNG['No_messages_in_this_folder']} --</span></td></tr>
   </template>
   <tr><td class="cellbuttons" colspan="5"><select class="form_select" name="p_action" onchange="submit_form();"><option value=''>-- {$LNG['Select_action']} --</option><option value=''></option><option value="index.php?faction=pms&amp;mode=markread&amp;return_f={$folder_id}&amp;return_z={$z}&amp;{$MYSID}">{$LNG['Mark_as_read']}</option><option value="index.php?faction=pms&amp;mode=deletepms&amp;return_f={$folder_id}&amp;return_z={$z}&amp;{$MYSID}">{$LNG['delete']}</option></select></td></tr>
   </table>
   </form>
  </td>
 </tr>
 </table>
</td></tr>
</table>
