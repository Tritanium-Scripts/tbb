<form method="post" action="{$smarty.const.INDEXFILE}?action=AdminAvatars&amp;mode=AddAvatar&amp;doit=1&amp;{$smarty.const.MYSID}">
<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('add_avatar')}</span></td></tr>
{if $error != ''}<tr><td class="CellError" colspan="2"><span class="FontError">{$error}</span></td></tr>{/if}
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('avatar_address')}:</span><br/><span class="FontSmall">{$modules.Language->getString('path_or_url')}</span></td>
 <td class="CellAlt"><input class="FormText" type="text" name="p[avatarAddress]" maxlength="255" size="40" value="{$p.avatarAddress}"/></td>
</tr>
<tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('add_avatar')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('reset')}"/></td></tr>
</table>
</form>
