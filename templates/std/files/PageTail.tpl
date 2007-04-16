{if $modules.Auth->getValue('userIsAdmin') == 1}
<br/>
<div align="center">
 <span class="FontNorm"><a href="{$indexFile}?action=AdminMain&amp;{$mySID}">{$modules.Language->getString('Administration')}</a></span></td></tr>
</div>
{/if}
<br/>
<div align="center">
<table class="TableCopyright">
<tr><td class="CellCopyright" align="center"><span class="FontCopyright">Tritanium Bulletin Board 2 Beta<br/>&copy; <a class="FontCopyright" target="_blank" href="http://www.tritanium-scripts.com">Tritanium Scripts</a></span></td></tr>
</table>
</div>
<!--<template:techstats>
 <br/><br/>
 <table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr><td align="center"><span class="techstats">{$techstats_text}</span></td></tr>
 </table>
</template>-->
</div></div>
</body>
</html>