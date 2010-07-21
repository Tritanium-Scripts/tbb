<!-- FAQ -->
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{$modules.Language->getString('faq')}</span></th></tr>
 <tr><td class="kat"><span class="kat">{$modules.Language->getString('overview')}</span></td></tr>
{foreach from=$faqQuestions item=curQuestion name=faqQuestions} <tr><td class="td1"><span class="norm"><a href="#{$smarty.foreach.faqQuestions.iteration}">{$curQuestion}</a></span></td></tr>{/foreach}
</table>
<br />
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
{foreach from=$faqQuestions item=curQuestion name=faqQuestions}
 <tr><td class="kat"><span class="kat"><a name="{$smarty.foreach.faqQuestions.iteration}">{$curQuestion}</a></span></td></tr>
 <tr><td class="td1"><span class="norm">{$faqAnswers[$smarty.foreach.faqQuestions.index]}</span></td></tr>
{/foreach}
</table>