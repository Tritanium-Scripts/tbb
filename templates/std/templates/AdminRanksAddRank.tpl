<form method="post" action="{$indexFile}?action=AdminRanks&amp;mode=AddRank&amp;doit=1&amp;{$mySID}">
<table class="TableStd" width="100%">
<colgroup>
 <col width="20%"/>
 <col width="80%"/>
</colgroup>
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('add_rank')}</span></td></tr>
{if $error != ''}<tr><td class="CellError" colspan="2"><span class="FontError">{$error}</span></td></tr>{/if}
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('rank_name')}:</span></td>
 <td class="CellAlt"><input class="FormText" type="text" name="p[rankName]" maxlength="255" size="40" value="{$p.rankName}"/></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('rank_type')}:</span></td>
 <td class="CellAlt"><select class="FormSelect" name="p[rankType]"><option value="0"{if $p.rankType == 0} selected="selected"{/if}>{$modules.Language->getString('normal_rank')}</option><option value="1"{if $p.rankType == 1} selected="selected"{/if}>{$modules.Language->getString('special_rank')}</option></select></td>
</tr>
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('rank_image')}:</span><br/><span class="FontSmall">{$modules.Language->getString('rank_image_info')}</span></td>
 <td class="CellAlt" valign="top"><textarea class="FormTextArea" name="p[rankGfx]" rows="6" cols="60">{$p.rankGfx}</textarea></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('required_posts')}:</span></td>
 <td class="CellAlt"><input class="FormText" type="text" name="p[rankPosts]" maxlength="8" size="10" value="{$p.rankPosts}"/></td>
</tr>
<tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('add_rank')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('reset')}"/></td></tr>
</table>
</form>
