<!-- Calendar -->
<p class="norm" style="float:left;"><a href="index.php?faction=calendar&amp;year={$year}&amp;month={$month-1}">{Language::getInstance()->getString('previous_month')}</a></p>
<p class="norm" style="float:right;"><a href="index.php?faction=calendar&amp;year={$year}&amp;month={$month+1}">{Language::getInstance()->getString('next_month')}</a></p>
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="1" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <caption class="norm" style="font-weight:bold;">{$date|date_format:Language::getInstance()->getString('DATE_MONTH_LONG')|utf8_encode}</caption>
 <tr class="thsmall">
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('week')}</span></th>
  <th class="thsmall"><span class="thsmall">{'-259201'|date_format:'%A'}</span></th>
  <th class="thsmall"><span class="thsmall">{'-172801'|date_format:'%A'}</span></th>
  <th class="thsmall"><span class="thsmall">{'-86401'|date_format:'%A'}</span></th>
  <th class="thsmall"><span class="thsmall">{'1'|date_format:'%A'}</span></th>
  <th class="thsmall"><span class="thsmall">{'86401'|date_format:'%A'}</span></th>
  <th class="thsmall"><span class="thsmall">{'172801'|date_format:'%A'}</span></th>
  <th class="thsmall"><span class="thsmall">{'259201'|date_format:'%A'}</span></th>
 </tr>
{foreach $calendar as $curWeek => $curDays}
 <tr>
  <td class="td1" style="font-weight:bold; text-align:right;"><span class="norm">{$curWeek}</span></td>
{foreach $curDays as $curDay => $curEvents}
{if $curEvents === false}
  <td class="kat" />
{else}
  <td class="td{if $curDay == $currentTime|date_format:'%d' && $month == $currentTime|date_format:'%m' && $year == $currentTime|date_format:'%Y'}2{else}1{/if}">
   <p class="norm" style="float:right;">{$curDay}</p>
{if !empty($curEvents)}
   <ul>
{foreach $curEvents as $curEvent}
{if $curEvent.type == 'member'}
    <li style="list-style-position:inside;" class="norm" title="{$curEvent.description}">{$curEvent.member}</li>
{else}
    <div id="event{$curDay}{$curEvent@iteration}" class="td2" style="border:thin solid #000000; cursor:pointer; display:none; height:60%; left:20%; padding:2em; position:fixed; top:20%; overflow:scroll; width:60%; z-index:1;" onclick="this.style.display='none';">
     <h2>{$curEvent.name}</h2>
     <p>{$curEvent.description}</p>
    </div>
    <li style="list-style-image:url('{$curEvent.icon}'); list-style-position:inside;" class="norm" title="{$curEvent.startDate|date_format:Language::getInstance()->getString('DATE_MINUTES_SHORT')}-{$curEvent.endDate|date_format:Language::getInstance()->getString('DATE_MINUTES_SHORT')}"><a style="cursor:help;" onclick="document.getElementById('event{$curDay}{$curEvent@iteration}').style.display='';">{$curEvent.name}</a></li>
{/if}
{/foreach}
   </ul>
{/if}
  </td>
{/if}
{/foreach}
 </tr>
{/foreach}
</table>