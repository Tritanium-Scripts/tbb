<!-- FAQSmileyTable -->
<table cellpadding="3" cellspacing="0">
{foreach $smilies as $curSynonym => $curSmiley}
 <tr><td class="td1"><span class="norm">{$curSynonym}</span></td><td class="td1">{$curSmiley}</td></tr>
{foreachelse}
 <tr><td class="td1" style="font-weight:bold; text-align:center;"><span class="norm">{$modules.Language->getString('no_smilies_available')}</td></tr>
{/foreach}
</table>