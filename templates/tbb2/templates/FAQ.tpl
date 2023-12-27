<!-- FAQ -->
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('faq')}</span></th></tr>
 <tr><td class="cellCat"><span class="fontCat">{Language::getInstance()->getString('overview')}</span></td></tr>
{foreach $faqQuestions as $curQuestion} <tr><td class="cellStd"><span class="fontNorm"><a href="#{$curQuestion@iteration}">{$curQuestion}</a></span></td></tr>{/foreach}
</table>
<br />
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
{foreach $faqQuestions as $curQuestion}
 <tr><td class="cellCat"><span class="fontCat"><a name="{$curQuestion@iteration}">{$curQuestion}</a></span></td></tr>
 <tr><td class="cellStd"><div class="fontNorm">{$faqAnswers[$curQuestion@index]}</div></td></tr>
{/foreach}
</table>