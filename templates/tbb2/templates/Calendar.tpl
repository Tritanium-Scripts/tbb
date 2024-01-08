<!-- Calendar -->
<p class="fontNorm" style="float:left;"><a href="index.php?faction=calendar&amp;year={$year}&amp;month={$month-1}">{Language::getInstance()->getString('previous_month')}</a></p>
<p class="fontNorm" style="float:right;"><a href="index.php?faction=calendar&amp;year={$year}&amp;month={$month+1}">{Language::getInstance()->getString('next_month')}</a></p>
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <caption class="fontNorm" style="font-weight:bold;">{$date|date_format:Language::getInstance()->getString('DATE_MONTH_LONG')}</caption>
 <tr>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{Language::getInstance()->getString('week')}</span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{'-259201'|date_format:'%A'}</span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{'-172801'|date_format:'%A'}</span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{'-86401'|date_format:'%A'}</span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{'1'|date_format:'%A'}</span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{'86401'|date_format:'%A'}</span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{'172801'|date_format:'%A'}</span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{'259201'|date_format:'%A'}</span></th>
 </tr>{foreach $calendar as $curWeek => $curDays}
 <tr>
  <td class="cellAlt" style="font-weight:bold; text-align:right;"><span class="fontNorm">{$curWeek}</span></td>{foreach $curDays as $curDay => $curEvents}{if $curEvents === false}
  <td />{else}
  <td class="cell{if $curDay == $currentTime|date_format:'%d' && $month == $currentTime|date_format:'%m' && $year == $currentTime|date_format:'%Y'}Highlight{else}Std{/if}">
   <p class="fontNorm" style="float:right;">{$curDay}</p>{if !empty($curEvents)}
   <ul>{foreach $curEvents as $curEvent}{if $curEvent.type == 'member'}
    <li style="list-style-image:url({Template::getInstance()->getTplDir()}images/icons/{if $curEvent.icon == 'registration'}user_add{else}cake{/if}.png); list-style-position:inside;" class="fontNorm" title="{$curEvent.description}">{$curEvent.member}</li>{else}
    <li style="list-style-image:url({$curEvent.icon}); list-style-position:inside;" class="fontNorm" title="{$curEvent.description}">{$curEvent.name}</li>{/if}{/foreach}
   </ul>{/if}
  </td>{/if}{/foreach}
 </tr>{/foreach}
</table>