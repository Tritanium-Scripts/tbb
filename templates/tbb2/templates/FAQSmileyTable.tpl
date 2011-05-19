<!-- FAQSmileyTable -->
<table cellpadding="3" cellspacing="0">
{foreach $smilies as $curSynonym => $curSmiley}
 <tr><td style="padding:3px;"><span class="fontNorm">{$curSynonym}</span></td><td style="padding:3px;">{$curSmiley}</td></tr>
{foreachelse}
 <tr><td style="font-weight:bold; padding:3px; text-align:center;"><span class="fontNorm">{$modules.Language->getString('no_smilies_available')}</span></td></tr>
{/foreach}
</table>