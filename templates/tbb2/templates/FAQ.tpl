<!-- FAQ -->
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{$modules.Language->getString('faq')}</span></th></tr>
 <tr><td class="cellCat"><span class="fontCat">{$modules.Language->getString('overview')}</span></td></tr>
{foreach $faqQuestions as $curQuestion} <tr><td class="cellStd"><span class="fontNorm"><a href="#{$curQuestion@iteration}">{$curQuestion}</a></span></td></tr>{/foreach}
</table>
<br />
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
{foreach $faqQuestions as $curQuestion}
 <tr><td class="cellCat"><span class="fontCat"><a name="{$curQuestion@iteration}">{$curQuestion}</a></span></td></tr>
 <tr><td class="cellStd"><div class="fontNorm">{$faqAnswers[$curQuestion@index]}</div></td></tr>
{/foreach}
</table>