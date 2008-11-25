<form method="post" action="{$indexFile}?action=EditProfile&amp;mode=Memo&amp;doit=1&amp;{$mySID}">
<table class="TableStd" width="100%">
<tr><td class="CellCat"><span class="FontCat">{$modules.Language->getString('memo')}</span></td></tr>
<tr><td class="CellStd">
 <div class="DivInfoBox"><span class="FontInfoBox"><img src="{$modules.Template->getTD()}/images/icons/Info.png" alt="" style="vertical-align:middle; padding:5px;"/>{$modules.Language->getString('memo_info')}</span></div>
 <textarea class="FormTextArea" cols="150" rows="25" name="p[userMemo]" style="width:99%;">{$p.userMemo}</textarea>
</td></tr>
<tr><td class="CellButtons" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('update_memo')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('reset')}"/></td></tr>
</table>
</form>
