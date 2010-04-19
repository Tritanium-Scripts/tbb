<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$table_header}</span></th></tr>
<tr><td class="cat" colspan="2"><span class="cat">{$lng['Group_leaders']}</span></td></tr>
<template:leaderrow>
 <tr>
  <td class="td1"><span class="norm">{$akt_leader['member_nick']}</span></td>
  <td class="td2" align="right"><span class="small"><a href="administration.php?faction=ad_groups&amp;group_id={$group_id}&amp;mode=switchmemberstatus&amp;member_id={$akt_leader['member_id']}&amp;{$MYSID}">{$lng['Downgrade_member']}</a> | <a href="administration.php?faction=ad_groups&amp;group_id={$group_id}&amp;mode=deletemember&amp;member_id={$akt_leader['member_id']}&amp;{$MYSID}">{$lng['delete']}</a></span></td>
 </tr>
</template>
<tr><td class="cat" colspan="2"><span class="cat">{$lng['Other_members']}</span></td></tr>
<template:memberrow>
 <tr>
  <td class="td1"><span class="norm">{$akt_member['member_nick']}</span></td>
  <td class="td2" align="right"><span class="small"><a href="administration.php?faction=ad_groups&amp;group_id={$group_id}&amp;mode=switchmemberstatus&amp;member_id={$akt_member['member_id']}&amp;{$MYSID}">{$lng['Upgrade_member']}</a> | <a href="administration.php?faction=ad_groups&amp;group_id={$group_id}&amp;mode=deletemember&amp;member_id={$akt_member['member_id']}&amp;{$MYSID}">{$lng['delete']}</a></span></td>
 </tr>
</template>
</table>
<br />
<form method="post" action="administration.php?faction=ad_groups&amp;mode=addmembers&amp;group_id={$group_id}&amp;{$MYSID}">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['Add_members']}</span></th></tr>
<tr>
 <td class="td1" width="15%"><span class="norm">{$lng['User']}:</span></td>
 <td class="td1" width="85%"><input class="form_text" type="text" name="p_users" value="" size="40" /></td>
</tr>
<tr>
 <td class="td2" width="15%"><span class="norm">{$lng['Group_leader']}:</span></td>
 <td class="td2" width="85%"><select class="form_select" name="p_leader"><option value="0">{$lng['No']}</option><option value="1">{$lng['Yes']}</option></select></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$lng['Add_members']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng['Reset']}" /></td></tr>
</table>
</form>