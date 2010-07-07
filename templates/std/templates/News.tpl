{if $news != false}
<tr><td class="kat" colspan="6"><span class="kat">{$modules.Language->getString('news')}</span></td></tr>
{if $newsType == 1}
<tr><td class="td1" colspan="6"><span class="small">{$news[0]}</span></td></tr>
{elseif $newsType == 2}
<tr><td class="td1" colspan="6" style="text-align:center;"><div class="news" {*style="width:100%; height:30px;visibility:visible;filter:blendTrans(duration=1);"*} id="newsfader">
{foreach $news as $curNews}
{$curNews}<br />
{/foreach}
</div></td></tr>
{/if}
{/if}