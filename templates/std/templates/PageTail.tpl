{if $modules.Auth->getValue('userIsAdmin') == 1}
	<div id="AdministrationBox">
		<span class="FontNorm"><a href="{$smarty.const.INDEXFILE}?action=AdminIndex&amp;{$smarty.const.MYSID}">{$modules.Language->getString('administration')}</a></span>
	</div>
{/if}

{include file=_Copyright.tpl}

{*<template:techstats>
 <br/><br/>
 <table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr><td align="center"><span class="techstats">{$techstats_text}</span></td></tr>
 </table>
</template>*}

</div>

</body>
</html>