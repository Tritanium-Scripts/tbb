<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$table_header}</span></td></tr>
<tr><td class="cellcat" colspan="2"><span class="fontcat">{$LNG['Group_leaders']}</span></td></tr>
<template:leaderrow>
 <tr>
  <td class="cellstd"><span class="fontnorm">{$akt_leader['member_nick']}</span></td>
  <td class="cellalt" align="right"><span class="fontsmall"><a href="administration.php?faction=ad_groups&amp;group_id={$group_id}&amp;mode=switchmemberstatus&amp;member_id={$akt_leader['member_id']}&amp;{$MYSID}">{$LNG['Downgrade_member']}</a> | <a href="administration.php?faction=ad_groups&amp;group_id={$group_id}&amp;mode=deletemember&amp;member_id={$akt_leader['member_id']}&amp;{$MYSID}">{$LNG['delete']}</a></span></td>
 </tr>
</template>
<tr><td class="cellcat" colspan="2"><span class="fontcat">{$LNG['Other_members']}</span></td></tr>
<template:memberrow>
 <tr>
  <td class="cellstd"><span class="fontnorm">{$akt_member['member_nick']}</span></td>
  <td class="cellalt" align="right"><span class="fontsmall"><a href="administration.php?faction=ad_groups&amp;group_id={$group_id}&amp;mode=switchmemberstatus&amp;member_id={$akt_member['member_id']}&amp;{$MYSID}">{$LNG['Upgrade_member']}</a> | <a href="administration.php?faction=ad_groups&amp;group_id={$group_id}&amp;mode=deletemember&amp;member_id={$akt_member['member_id']}&amp;{$MYSID}">{$LNG['delete']}</a></span></td>
 </tr>
</template>
</table>
<br />
<form method="post" action="administration.php?faction=ad_groups&amp;mode=addmembers&amp;group_id={$group_id}&amp;{$MYSID}">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Add_members']}</span></td></tr>
<tr>
 <td class="cellstd" width="15%"><span class="fontnorm">{$LNG['User']}:</span></td>
 <td class="cellstd" width="85%"><input class="form_text" type="text" name="p_users" value="" size="40" /></td>
</tr>
<tr>
 <td class="cellalt" width="15%"><span class="fontnorm">{$LNG['Group_leader']}:</span></td>
 <td class="cellalt" width="85%"><select class="form_select" name="p_leader"><option value="0">{$LNG['No']}</option><option value="1">{$LNG['Yes']}</option></select></td>
</tr>
<tr><td class="cellbuttons" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Add_members']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>