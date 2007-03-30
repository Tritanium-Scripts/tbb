<form method="post" action="{$IndexFile}?Action=EditProfile&amp;Mode=Memo&amp;Doit=1&amp;{$MySID}">
<table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="CellCat"><span class="FontCat">{$Modules.Language->getString('Memo')}</span></td></tr>
{if $MemoWasUpdated == TRUR}<tr><td class="CellInfoBox"><span class="FontInfoBox">{$Modules.Language->getString('message_memo_updated')}</span></td></tr>{/if}
<tr><td class="CellStd">
 <div class="DivInfoBox"><span class="FontInfoBox"><img src="templates/std/templates/images/lightbulb_a.gif" style="vertical-align:middle; padding:5px;"/>{$Modules.Language->getString('memo_info')}</span></div>
 <textarea class="FormTextArea" cols="150" rows="25" name="p_user_memo" style="width:99%;">{$p_user_memo}</textarea>
</td></tr>
<tr><td class="CellButtons" align="center"><input class="FormBButton" type="submit" value="{$Modules.Language->getString('Update_memo')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$Modules.Language->getString('Reset')}"/></td></tr>
</table>
</form>
