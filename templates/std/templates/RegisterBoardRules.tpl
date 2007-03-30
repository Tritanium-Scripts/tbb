<form method="post" action="{$IndexFile}?Action=Register&amp;Mode=BoardRules&amp;Doit=1&amp;{$MySID}">
<table class="TableStd" width="100%">
<tr><td class="CellTitle"><span class="FontTitle">{$Modules.Language->getString('Register')}</span></td></tr>
<tr><td class="CellCat"><span class="FontCat">{$Modules.Language->getString('Board_rules')}</span></td></tr>
<tr><td class="CellError"><span class="FontError"><img src="{$Modules.Template->getTD()}/images/icons/Warning.png" alt="" border="0" class="ImageIcon"/>{$Modules.Language->getString('board_rules_info')}</span></td></tr>
<tr><td class="CellInfoBox"><span class="FontInfoBox">{$Modules.Language->getString('board_rules_text')}</span></td></tr>
<tr><td class="CellButtons" align="center"><input class="FormBButton" type="submit" value="{$Modules.Language->getString('Accept_board_rules')}"/></td></tr>
</table>
</form>
