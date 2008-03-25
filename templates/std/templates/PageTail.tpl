{if $modules.Auth->getValue('userIsAdmin') == 1}
<br/>
<div align="center">
 <span class="FontNorm"><a href="{$indexFile}?action=AdminIndex&amp;{$mySID}">{$modules.Language->getString('Administration')}</a></span>
</div>
{/if}
<br/>
<div align="center">
	{include file=_Copyright.tpl}
</div>
{*<template:techstats>
 <br/><br/>
 <table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr><td align="center"><span class="techstats">{$techstats_text}</span></td></tr>
 </table>
</template>*}
</div></div>
</body>
</html>