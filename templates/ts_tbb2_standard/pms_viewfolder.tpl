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
 <td class="navbar" align="right"><span class="navbar"><a href="index.php?faction=pms&amp;mode=newpm&amp;{$MYSID}">{$lng["New_private_message"]}</a> | <a href="index.php?faction=pms&amp;mode=addfolder&amp;{$MYSID}">{$lng["Add_folder"]}</a></span></td></tr>
</table>
<br />
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr><td valign="top" width="20%">
 <table class="tbl" border="0" cellpadding="2" cellspacing="0" width="100%">
 <tr><th class="thnorm"><span class="thnorm">{$lng["Folders"]}</span></th></tr>
 <!-- TPLBLOCK folderrow -->
  <tr><td class="td1"><span class="small"><a href="index.php?faction=pms&amp;mode=viewfolder&amp;folder_id={folderrow.$akt_folder["folder_id"]}&amp;{folderrow.$MYSID}">{folderrow.$akt_folder["folder_name"]}</a></td></tr>
 <!-- /TPLBLOCK folderrow -->
 </table>
</td>
<td valign="top">
 <form method="post" action="index.php?faction=pms&amp;mode=viewfolder&amp;folder_id={$folder_id}&amp;z={$z}&amp;{$MYSID}" name="tbb_form">
 <table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
 <tr>
  <th class="thsmall"><span class="thsmall">&nbsp;</span></th>
  <th class="thsmall"><span class="thsmall">{$lng["Subject"]}</span></th>
  <th class="thsmall"><span class="thsmall">{$lng["Date"]}</span></th>
  <th class="thsmall"><span class="thsmall">&nbsp;</span></th>
  <th class="thsmall"><span class="thsmall">&nbsp;</span></th>
 </tr>
 <!-- TPLBLOCK pmrow -->
  <tr>
   <td class="td1" align="center"><input type="checkbox" value="{pmrow.$akt_pm["pm_id"]}" name="pm_ids[]" /></td>
   <td class="td2"><span class="norm"><a href="index.php?faction=pms&amp;mode=viewpm&amp;pm_id={pmrow.$akt_pm["pm_id"]}&amp;{pmrow.$MYSID}">{pmrow.$akt_pm["pm_subject"]}</a></span></td>
   <td class="td1" align="center"><span class="small">{pmrow.$akt_date}</span></td>
   <td class="td2"><span class="small">{pmrow.$akt_sender}</span></td>
   <td class="td1" align="right"><span class="small"><a href="index.php?faction=pms&amp;mode=deletepms&amp;pm_id={pmrow.$akt_pm["pm_id"]}&amp;{pmrow.$MYSID}">{pmrow.$lng["delete"]}</a></span></td>
  </tr>
 <!-- /TPLBLOCK pmrow -->
 <!-- TPLBLOCK nopms -->
  <tr><td class="td1" colspan="5" align="center"><span class="norm">-- {nopms.$lng["No_messages_in_this_folder"]} --</span></td></tr>
 <!-- /TPLBLOCK nopms -->
 <tr><td class="buttonrow" colspan="5"><select class="form_select" name="p_action" onchange="submit_form();"><option value=''>-- {$lng["Select_action"]} --</option><option value=''></option><option value="index.php?faction=pms&amp;mode=markread&amp;return_f={$folder_id}&amp;return_z={$z}&amp;{$MYSID}">{$lng["Mark_as_read"]}</option><option value="index.php?faction=pms&amp;mode=deletepms&amp;return_f={$folder_id}&amp;return_z={$z}&amp;{$MYSID}">{$lng["delete"]}</option></select></td></tr>
 </table>
 </form>
</td>
</tr>
</table>