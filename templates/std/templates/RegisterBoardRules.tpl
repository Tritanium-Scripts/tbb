<form method="post" action="{$indexFile}?action=Register&amp;mode=BoardRules&amp;doit=1&amp;{$mySID}">
	<table class="TableStd" width="100%">
		<tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('register')}</span></td></tr>
		{include file=_ErrorRow.tpl colSpan=1}
		<tr><td class="CellCat"><span class="FontCat">{$modules.Language->getString('board_rules')}</span></td></tr>
		<tr><td class="CellInfoBox"><span class="FontInfoBox"><img src="templates/std/images/icons/Info.png" class="ImageIcon" alt=""/>{$modules.Language->getString('board_rules_info')}</span></td></tr>
		<tr><td class="CellAlt"><span class="FontNorm">{$modules.Language->getString('board_rules_text')}</span></td></tr>
		{if $modules.Config->getValue('require_accept_boardrules') == 1}
			<tr><td class="CellStd" align="center"><span class="FontNorm"><b><input type="checkbox" name="c[acceptRules]"/>&nbsp;{$modules.Language->getString('accept_board_rules')}</b></span></td></tr>
		{/if}
		<tr><td class="CellButtons" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('next_step')}"/></td></tr>
	</table>
</form>
