{* NOTE: Most if-elseif and their BBCodes need to be in one single line to avoid additional spaces in generated XHTML! *}
{if $type == BBCode::BBCODE_QUOTE}<table cellspacing="0" cellpadding="0" style="width:85%; margin:auto;">
 <tr>
  <td><span class="small" style="font-weight:bold;">{$quoteTitle}</span><hr noshade="noshade" />
   <table cellspacing="0" cellpadding="0" style="width:95%; margin:auto;">
    <tr><td><span class="quote">{$quoteText}</span></td></tr>
   </table>
   <hr noshade="noshade" />
  </td>
 </tr>
</table>{elseif $type == BBCode::BBCODE_LIST}<ul>
{foreach $listEntries as $curEntry}
 <li>{$curEntry}</li>
{/foreach}
</ul>{elseif $type == BBCode::BBCODE_BOLD}<span style="font-weight:bold;">{$boldText}</span>{elseif $type == BBCode::BBCODE_ITALIC}<span style="font-style:italic;">{$italicText}</span>{elseif $type == BBCode::BBCODE_UNDERLINE}<span style="text-decoration:underline;">{$underlineText}</span>{elseif $type == BBCode::BBCODE_STRIKE}<span style="text-decoration:line-through;">{$strikeText}</span>{elseif $type == BBCode::BBCODE_SUPERSCRIPT}<sup>{$superText}</sup>{elseif $type == BBCode::BBCODE_SUBSCRIPT}<sub>{$subText}</sub>{elseif $type == BBCode::BBCODE_HIDE}<div>
 <div style="padding:3px;"><b>{Language::getInstance()->getString('hidden_text', 'BBCode')}:</b> <input class="forumcode" type="button" value="{Language::getInstance()->getString('uncover')}" onclick="(s = this.parentNode.parentNode.getElementsByTagName('div')[1].style).display = s.display == 'none' ? '' : 'none'; (s = this.parentNode.style).backgroundColor = s.backgroundColor == '' ? '#666699' : ''; s.color = s.color == '' ? '#FFFFFF' : ''; this.value = s.color == '' ? '{Language::getInstance()->getString('uncover')}' : '{Language::getInstance()->getString('hide')}';" /></div>
 <div style="background-color:#F0F8FF; border:1px #000000 solid; display:none; padding:10px;">{$hideText}</div>
</div>{elseif $type == BBCode::BBCODE_LOCK}<div>
 <div style="background-color:#666699; color:#FFFFFF; font-weight:bold; padding:3px;">{Language::getInstance()->getString('locked_text', 'BBCode')}:</div>
 <div style="border:1px #000000 solid; padding:10px; background-color:#{if $lockText != ''}F0FFF0;">{$lockText}{else}FFD1D1;"><span style="color:red; font-weight:bold;">{Language::getInstance()->getString('locked_text_hint')}</span>{/if}</div>
</div>{elseif $type == BBCode::BBCODE_CENTER}<p style="text-align:center;">{$centerText}</p>{elseif $type == BBCode::BBCODE_EMAIL}{mailto address=$eMailAddress text=$eMailText encode="javascript"}{elseif $type == BBCode::BBCODE_IMAGE}<img src="{$imageAddress}" alt="{$imageText}" title="{$imageText}" style="max-width:100%;" />{elseif $type == BBCode::BBCODE_LINK}<a href="{$linkAddress}" target="_blank">{$linkText}</a>{elseif $type == BBCode::BBCODE_COLOR}<span style="color:{$colorCode};">{$colorText}</span>{elseif $type == BBCode::BBCODE_IFRAME}<iframe src="{$iFrameLink}" width="{$iFrameWidth}" height="{$iFrameHeight}" frameborder="0"><a href="{$iFrameLink}" target="_blank">{Language::getInstance()->getString('no_iframe_supported', 'BBCode')}</a></iframe>{elseif $type == BBCode::BBCODE_SIZE}<span style="font-size:{$sizeFont};">{$sizeText}</span>{elseif $type == BBCode::BBCODE_GLOW}<div style="width:100%; filter:Glow(color={$glowColor}, strength=4);">{$glowText}</div>{* <span> doesn't work?! *}{elseif $type == BBCode::BBCODE_SHADOW}<div style="width:100%; filter:DropShadow(color={$shadowColor}, offx=2, offy=2);">{$shadowText}</div>{* <span> doesn't work?! *}{elseif $type == BBCode::BBCODE_FLASH}<object data="{$flashLink}" type="application/x-shockwave-flash" width="{$flashWidth}" height="{$flashHeight}">
 <param name="allowFullScreen" value="true "/>
 <param name="allowScriptAccess" value="sameDomain" />
 <param name="movie" value="{$flashLink}" />
 <param name="quality" value="autohigh" />
 <param name="wmode" value="transparent" />
 <p><a href="https://get.adobe.com/flashplayer/" target="_blank">{Language::getInstance()->getString('no_flash_installed', 'BBCode')}</a></p>
</object>{elseif $type == BBCode::BBCODE_CODE}<code>{$codeLines}</code>{/if}