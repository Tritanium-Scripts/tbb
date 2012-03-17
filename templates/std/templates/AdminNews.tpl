<!-- AdminNews -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_news&amp;save=yes{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th colspan="2" class="thnorm"><span class="thnorm">{$modules.Language->getString('edit_forum_news')}</span></th></tr>
 <tr><td colspan="2" class="kat"><span class="kat">{$modules.Language->getString('options')}</span></td></tr>
 <tr>
  <td class="td1" rowspan="2" style="vertical-align:top;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('news_type_colon')}</span></td>
  <td class="td1"><input type="radio" id="newsType1" name="typ" value="1"{if $newsType == 1} checked="checked"{/if} /> <label for="newsType1" class="norm">{$modules.Language->getString('static')}</label><br /><span class="small">{$modules.Language->getString('static_description')}</span></td>
 </tr>
 <tr>
  <td class="td1"><input type="radio" id="newsType2" name="typ" value="2"{if $newsType == 2} checked="checked"{/if} /> <label for="newsType2" class="norm">{$modules.Language->getString('fader')}</label><br /><span class="small">{$modules.Language->getString('fader_description')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="vertical-align:top;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('display_duration_colon')}</span></td>
  <td class="td1"><select name="expiredate"><option value="-1"{if $newsDuration == -1} selected="selected"{/if}>{$modules.Language->getString('forever')}</option><option value="60"{if $newsDuration == 60} selected="selected"{/if}>{$modules.Language->getString('one_hour')}</option><option value="120"{if $newsDuration == 120} selected="selected"{/if}>{2|string_format:$modules.Language->getString('x_hours')}</option><option value="300"{if $newsDuration == 300} selected="selected"{/if}>{5|string_format:$modules.Language->getString('x_hours')}</option><option value="1440"{if $newsDuration == 1440} selected="selected"{/if}>{$modules.Language->getString('one_day')}</option><option value="2880"{if $newsDuration == 2880} selected="selected"{/if}>{2|string_format:$modules.Language->getString('x_days')}</option><option value="7200"{if $newsDuration == 7200} selected="selected"{/if}>{5|string_format:$modules.Language->getString('x_days')}</option><option value="14400"{if $newsDuration == 14400} selected="selected"{/if}>{10|string_format:$modules.Language->getString('x_days')}</option><option value="43200"{if $newsDuration == 43200} selected="selected"{/if}>{30|string_format:$modules.Language->getString('x_days')}</option></select></td>
 </tr>
 <tr><td class="kat" colspan="2"><span class="kat">{$modules.Language->getString('current_news')}</span></td></tr>{if empty($newsText)}
 <tr><td class="td1" colspan="2" style="font-weight:bold; text-align:center;"><span class="norm">{$modules.Language->getString('no_news_available')}</span></td></tr>{elseif $newsType == 1}
 <tr><td class="td1" colspan="2"><div class="norm">{$newsPreview}</div></td></tr>{elseif $newsType == 2}
 <script src="{$modules.Template->getTplDir()}scripts/fader.js" type="text/javascript"></script>
 <script type="text/javascript">onload = function(){ next(5000, true); };</script>
 <tr>
  <td class="td1" colspan="2" style="text-align:center;">
   <div class="news" id="fader" style="position:relative;">
    <span class="fade"></span>{foreach $newsPreview as $curNews}<span class="fade">{$curNews}</span>{/foreach}
   </div>
  </td>
 </tr>{/if}
 <tr><td class="kat" colspan="2"><span class="kat">{$modules.Language->getString('write_news')}</span></td></tr>
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top;"><span class="norm">{$modules.Language->getString('bbcode_colon')}</span></td>
  <td class="td1">{include file='BBCodes.tpl' targetBoxID='news'}</td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top;"><span class="norm">{$modules.Language->getString('news_colon')}</span><br /><br />{include file='Smilies.tpl' targetBoxID='news'}</td>
  <td class="td1"><textarea cols="80" rows="7" id="news" name="news">{$newsText}</textarea><br /><span class="small">{$modules.Language->getString('write_news_hint')}</span></td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('edit_forum_news')}" /></p>
</form>