<!-- FAQ -->
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{$modules.Language->getString('faq')}</span></th></tr>
 <tr><td class="kat"><span class="kat">{$modules.Language->getString('overview')}</span></td></tr>
{foreach $faqQuestions as $curQuestion} <tr><td class="td1"><span class="norm"><a href="#{$curQuestion@iteration}">{$curQuestion}</a></span></td></tr>{/foreach}
</table>
<br />
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
{foreach $faqQuestions as $curQuestion}
 <tr><td class="kat"><span class="kat"><a name="{$curQuestion@iteration}">{$curQuestion}</a></span></td></tr>
 <tr><td class="td1"><div class="norm">{$faqAnswers[$curQuestion@index]}</div></td></tr>
{/foreach}
</table>