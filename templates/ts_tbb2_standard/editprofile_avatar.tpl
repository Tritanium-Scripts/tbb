<templatefile:"editprofile_header.tpl" />
<form method="post" action="index.php?faction=editprofile&amp;mode=avatar&amp;doit=1&amp;{$MYSID}" name="editprofile_form">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="cellcat"><span class="fontcat">{$LNG['Avatar']}</span></td></tr>
<tr><td class="cellstd">
 <fieldset>
  <legend><span class="fontsmall"><b>{$LNG['Current_avatar']}</b></span></legend>
  <table border="0" cellpadding="3" cellspacing="0" width="100%">
  <tr>
   <td><span class="fontnorm">{$LNG['Avatar']}:</span><if:"{$USER_DATA['user_avatar_address']} != ''"><br /><img src="{$USER_DATA['user_avatar_address']}" width="{$CONFIG['avatar_image_width']}" height="{$CONFIG['avatar_image_height']}" /></if></td>
   <td valign="top"><input class="form_text" type="text" size="60" name="p_avatar_address" value="{$USER_DATA['user_avatar_address']}" /></td>
  </tr>
  <tr><td colspan="2"><span class="fontnorm"><a href="javascript:popup('index.php?faction=editprofile&amp;mode=uploadavatar&amp;{$MYSID}','uploadavatarwindow','width=500,height=250,scrollbars=yes,toolbar=no,status=yes')">{$LNG['Upload_avatar']}</a></span></td></tr>
  </table>
 </fieldset>
 <template:selectavatar>
 <fieldset>
  <legend><span class="fontsmall"><b>{$LNG['Select_avatar_from_list']}</b></span></legend>
  <table border="0" cellpadding="3" cellspacing="0" width="100%">
  <template:avatarrow>
   <tr>
   <template:avatarcol>
    <!--<td align="center"><a href="index.php?faction=editprofile&amp;mode=selectavatar&amp;doit=1&amp;avatar_address={$akt_encoded_avatar_address}&amp;{$MYSID}"><img src="{$akt_avatar['avatar_address']}" width="{$CONFIG['avatar_image_width']}" height="{$CONFIG['avatar_image_height']}" border="0" alt="" /></a></td>-->
    <td align="center"><a href="javascript:set_avatar_address('{$akt_encoded_avatar_address}'); document.forms['editprofile_form'].submit();"><img src="{$akt_avatar['avatar_address']}" width="{$CONFIG['avatar_image_width']}" height="{$CONFIG['avatar_image_height']}" border="0" alt="" /></a></td>
   </template>
   </tr>
  </template>
  </table>
 </fieldset>
 </template>
</td></tr>
<tr><td class="cellbuttons" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Save_changes']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>
<script type="text/javascript">
 function set_avatar_address(new_avatar_address) {
 	document.forms['editprofile_form'].elements['p_avatar_address'].value = new_avatar_address;
 }
</script>
<templatefile:"editprofile_tail.tpl" />
