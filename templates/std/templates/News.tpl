{if $news != false}
<tr><td class="kat" colspan="6"><span class="kat">{$modules.Language->getString('news')}</span></td></tr>
{if $newsType == 1}
<tr><td class="td1" colspan="6"><span class="small">{$news[0]}</span></td></tr>
{elseif $newsType == 2}
<script src="{$modules.Template->getTplDir()}scripts/fader.js" type="text/javascript"></script>
<script type="text/javascript">onload = function(){ next(5000, true); };</script>
<tr><td class="td1" colspan="6" style="text-align:center;"><div class="news" id="fader" style="position:relative;">
<span class="fade"></span>{foreach $news as $curNews}<span class="fade">{$curNews}</span>{/foreach}
</div></td></tr>
{/if}
{/if}