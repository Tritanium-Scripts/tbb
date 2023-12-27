<!-- FAQ -->
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{Language::getInstance()->getString('faq')}</span></th></tr>
 <tr><td class="kat"><span class="kat">{Language::getInstance()->getString('overview')}</span></td></tr>
{foreach $faqQuestions as $curQuestion} <tr><td class="td1"><span class="norm"><a href="#{$curQuestion@iteration}">{$curQuestion}</a></span></td></tr>{/foreach}
</table>
<br />
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
{foreach $faqQuestions as $curQuestion}
 <tr><td class="kat"><span class="kat"><a name="{$curQuestion@iteration}">{$curQuestion}</a></span></td></tr>
 <tr><td class="td1"><div class="norm">{$faqAnswers[$curQuestion@index]}</div></td></tr>
{/foreach}
</table>