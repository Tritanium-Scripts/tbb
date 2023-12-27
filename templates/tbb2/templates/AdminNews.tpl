{include file='AdminMenu.tpl'}
<!-- AdminNews -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_news&amp;save=yes{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <colgroup>
  <col width="20%" />
  <col width="80%" />
 </colgroup>
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('edit_forum_news')}</span></th></tr>
 <tr><td class="cellCat" colspan="2"><span class="fontCat">{Language::getInstance()->getString('options')}</span></td></tr>
 <tr>
  <td class="cellStd" rowspan="2" style="vertical-align:top;"><span class="fontNorm">{Language::getInstance()->getString('news_type_colon')}</span></td>
  <td class="cellAlt"><input type="radio" id="newsType1" name="typ" value="1"{if $newsType == 1} checked="checked"{/if} /> <label for="newsType1" class="fontNorm">{Language::getInstance()->getString('static')}</label><br /><span class="fontSmall">{Language::getInstance()->getString('static_description')}</span></td>
 </tr>
 <tr>
  <td class="cellAlt"><input type="radio" id="newsType2" name="typ" value="2"{if $newsType == 2} checked="checked"{/if} /> <label for="newsType2" class="fontNorm">{Language::getInstance()->getString('fader')}</label><br /><span class="fontSmall">{Language::getInstance()->getString('fader_description')}</span></td>
 </tr>
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{Language::getInstance()->getString('display_duration_colon')}</span></td>
  <td class="cellAlt"><select class="formSelect" name="expiredate"><option value="-1"{if $newsDuration == -1} selected="selected"{/if}>{Language::getInstance()->getString('forever')}</option><option value="60"{if $newsDuration == 60} selected="selected"{/if}>{Language::getInstance()->getString('one_hour')}</option><option value="120"{if $newsDuration == 120} selected="selected"{/if}>{2|string_format:Language::getInstance()->getString('x_hours')}</option><option value="300"{if $newsDuration == 300} selected="selected"{/if}>{5|string_format:Language::getInstance()->getString('x_hours')}</option><option value="1440"{if $newsDuration == 1440} selected="selected"{/if}>{Language::getInstance()->getString('one_day')}</option><option value="2880"{if $newsDuration == 2880} selected="selected"{/if}>{2|string_format:Language::getInstance()->getString('x_days')}</option><option value="7200"{if $newsDuration == 7200} selected="selected"{/if}>{5|string_format:Language::getInstance()->getString('x_days')}</option><option value="14400"{if $newsDuration == 14400} selected="selected"{/if}>{10|string_format:Language::getInstance()->getString('x_days')}</option><option value="43200"{if $newsDuration == 43200} selected="selected"{/if}>{30|string_format:Language::getInstance()->getString('x_days')}</option></select></td>
 </tr>
 <tr><td class="cellCat" colspan="2"><span class="fontCat">{Language::getInstance()->getString('current_news')}</span></td></tr>{if empty($newsText)}
 <tr><td class="cellStd" colspan="2" style="font-weight:bold; text-align:center;"><span class="fontNorm">{Language::getInstance()->getString('no_news_available')}</span></td></tr>{elseif $newsType == 1}
 <tr><td class="cellStd" colspan="2"><div class="fontNorm">{$newsPreview}</div></td></tr>{elseif $newsType == 2}
 <script type="text/javascript">onload = function(){ next(5000, true); };</script>
 <tr>
  <td class="cellStd" colspan="2" style="text-align:center;">
   <div class="news" id="fader" style="position:relative;">
    <span class="fade"></span>{foreach $newsPreview as $curNews}<span class="fade">{$curNews}</span>{/foreach}
   </div>
  </td>
 </tr>{/if}
 <tr><td class="cellCat" colspan="2"><span class="fontCat">{Language::getInstance()->getString('write_news')}</span></td></tr>
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{Language::getInstance()->getString('bbcode_colon')}</span></td>
  <td class="cellAlt">{include file='BBCodes.tpl' targetBoxID='news'}</td>
 </tr>
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{Language::getInstance()->getString('news_colon')}</span><br /><br />{include file='Smilies.tpl' targetBoxID='news'}</td>
  <td class="cellAlt"><textarea class="formTextArea" cols="80" rows="11" id="news" name="news">{$newsText}</textarea><br /><span class="fontSmall">{Language::getInstance()->getString('write_news_hint')}</span></td>
 </tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('edit_forum_news')}" /></p>
</form>
{include file='AdminMenuTail.tpl'}