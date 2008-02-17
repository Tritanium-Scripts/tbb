<form method="post" action="index.php?faction=edittopic&amp;mode=edit&amp;topic_id={$topic_id}&amp;doit=1&amp;{$MYSID}" name="tbb_form">
<table width="100%" class="tablestd" border="0" cellspacing="0" cellpadding="3">
<tr><td class="celltitle" align="left" colspan="2"><span class="fonttitle">{$LNG['Edit_topic']}</span></td></tr>
<template:errorrow>
 <tr><td class="cellalt" colspan="2"><span class="fonterror">{$error}</span></td></tr>
</template>
<tr>
 <td class="cellstd" valign="top"><span class="fontnorm">{$LNG['Post_pic']}:</span></td>
 <td class="cellstd" valign="top">{$ppics_box}</td>
</tr>
<tr>
 <td class="cellalt"><span class="fontnorm">{$LNG['Title']}:</span></td>
 <td class="cellalt"><input class="form_text" type="text" size="65" maxlength="60" name="p_title" value="{$p_title}" /></td>
</tr>
<tr><td class="cellbuttons" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Edit_topic']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table></form>