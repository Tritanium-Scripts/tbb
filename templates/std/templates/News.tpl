{if $news != false}
<tr><td class="kat" colspan="6"><span class="kat">{$modules.Language->getString('news')}</span></td></tr>
{if $newsType == 1}
<tr><td class="td1" colspan="6"><span class="small">{$news[0]}</span></td></tr>
{elseif $newsType == 2}
<script type="text/javascript">onload = function(){ next(); };</script>
<tr><td class="td1" colspan="6" style="text-align:center;"><div class="news" id="fader" style="position:relative;">
{foreach $news as $curNews}<span class="fade">{$curNews}</span>{/foreach}
</div></td></tr>
{/if}
{/if}