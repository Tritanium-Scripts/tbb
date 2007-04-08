<form method="post" action="{$indexFile}?action=Register&amp;mode=BoardRules&amp;doit=1&amp;{$mySID}">
<table class="TableStd" width="100%">
<tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('Register')}</span></td></tr>
<tr><td class="CellCat"><span class="FontCat">{$modules.Language->getString('Board_rules')}</span></td></tr>
<tr><td class="CellError"><span class="FontError"><img src="{$modules.Template->getTD()}/images/icons/Warning.png" alt="" border="0" class="ImageIcon"/>{$modules.Language->getString('board_rules_info')}</span></td></tr>
<tr><td class="CellInfoBox"><span class="FontInfoBox">{$modules.Language->getString('board_rules_text')}</span></td></tr>
<tr><td class="CellButtons" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('Accept_board_rules')}"/></td></tr>
</table>
</form>
