<!-- AdminNews -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_news&amp;save=yes{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <colgroup>
  <col width="20%" />
  <col width="80%" />
 </colgroup>
 <tr><th colspan="2" class="thnorm"><span class="thnorm">{Language::getInstance()->getString('edit_forum_news')}</span></th></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_NEWS_FORM_START}
 <tr><td colspan="2" class="kat"><span class="kat">{Language::getInstance()->getString('options')}</span></td></tr>
 <tr>
  <td class="td1" rowspan="2" style="vertical-align:top;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('news_type_colon')}</span></td>
  <td class="td1"><input type="radio" id="newsType1" name="typ" value="1"{if $newsType == 1} checked="checked"{/if} /> <label for="newsType1" class="norm">{Language::getInstance()->getString('static')}</label><br /><span class="small">{Language::getInstance()->getString('static_description')}</span></td>
 </tr>
 <tr>
  <td class="td1"><input type="radio" id="newsType2" name="typ" value="2"{if $newsType == 2} checked="checked"{/if} /> <label for="newsType2" class="norm">{Language::getInstance()->getString('fader')}</label><br /><span class="small">{Language::getInstance()->getString('fader_description')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="vertical-align:top;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('display_duration_colon')}</span></td>
  <td class="td1"><select name="expiredate"><option value="-1"{if $newsDuration == -1} selected="selected"{/if}>{Language::getInstance()->getString('forever')}</option><option value="60"{if $newsDuration == 60} selected="selected"{/if}>{Language::getInstance()->getString('one_hour')}</option><option value="120"{if $newsDuration == 120} selected="selected"{/if}>{2|string_format:Language::getInstance()->getString('x_hours')}</option><option value="300"{if $newsDuration == 300} selected="selected"{/if}>{5|string_format:Language::getInstance()->getString('x_hours')}</option><option value="1440"{if $newsDuration == 1440} selected="selected"{/if}>{Language::getInstance()->getString('one_day')}</option><option value="2880"{if $newsDuration == 2880} selected="selected"{/if}>{2|string_format:Language::getInstance()->getString('x_days')}</option><option value="7200"{if $newsDuration == 7200} selected="selected"{/if}>{5|string_format:Language::getInstance()->getString('x_days')}</option><option value="14400"{if $newsDuration == 14400} selected="selected"{/if}>{10|string_format:Language::getInstance()->getString('x_days')}</option><option value="43200"{if $newsDuration == 43200} selected="selected"{/if}>{30|string_format:Language::getInstance()->getString('x_days')}</option></select></td>
 </tr>
 <tr><td class="kat" colspan="2"><span class="kat">{Language::getInstance()->getString('current_news')}</span></td></tr>{if empty($newsText)}
 <tr><td class="td1" colspan="2" style="font-weight:bold; text-align:center;"><span class="norm">{Language::getInstance()->getString('no_news_available')}</span></td></tr>{elseif $newsType == 1}
 <tr><td class="td1" colspan="2"><div class="norm">{$newsPreview}</div></td></tr>{elseif $newsType == 2}
 <script src="{Template::getInstance()->getTplDir()}scripts/fader.js" type="text/javascript"></script>
 <script type="text/javascript">onload = function(){ next(5000, true); };</script>
 <tr>
  <td class="td1" colspan="2" style="text-align:center;">
   <div class="news" id="fader" style="position:relative;">
    <span class="fade"></span>{foreach $newsPreview as $curNews}<span class="fade">{$curNews}</span>{/foreach}
   </div>
  </td>
 </tr>{/if}
 <tr><td class="kat" colspan="2"><span class="kat">{Language::getInstance()->getString('write_news')}</span></td></tr>
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top;"><span class="norm">{Language::getInstance()->getString('bbcode_colon')}</span></td>
  <td class="td1">{include file='BBCodes.tpl' targetBoxID='news'}</td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top;"><span class="norm">{Language::getInstance()->getString('news_colon')}</span><br /><br />{include file='Smilies.tpl' targetBoxID='news'}</td>
  <td class="td1"><textarea cols="80" rows="7" id="news" name="news">{$newsText}</textarea><br /><span class="small">{Language::getInstance()->getString('write_news_hint')}</span></td>
 </tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_NEWS_FORM_END}
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('edit_forum_news')}" />{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_NEWS_BUTTONS}</p>
</form>