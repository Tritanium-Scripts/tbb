{if $news != false}
<tr><td class="cellTitle" colspan="6"><span class="fontTitle">{$modules.Language->getString('news')}</span></td></tr>
{if $newsType == 1}
<tr><td class="cellStd" colspan="6"><div class="fontNorm">{$news[0]}</div></td></tr>
{elseif $newsType == 2}
<script type="text/javascript">onload = function(){ next(5000, true); };</script>
<tr><td class="cellStd" colspan="6" style="text-align:center;"><div class="news" id="fader" style="position:relative;">
<span class="fade"></span>{foreach $news as $curNews}<span class="fade">{$curNews}</span>{/foreach}
</div></td></tr>
{/if}
{/if}