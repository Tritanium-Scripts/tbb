{* NOTE: Most if-elseif and their BBCodes need to be in one single line to avoid additional spaces in generated XHTML! *}
{if $type == $smarty.const.BBCODE_QUOTE}<table style="border:1px #000000 solid; margin:auto; width:90%;">
 <tr><td style="background-color:#000000; padding:6px 3px;"><span style="color:#FFFFFF; font:10px Verdana; font-weight:bold;">{$quoteTitle}</span></td></tr>
 <tr><td style="background-color:#FFFFFF; padding:3px;"><span class="fontSmall">{$quoteText}</span></td></tr>
</table>{elseif $type == $smarty.const.BBCODE_LIST}<ul>
{foreach $listEntries as $curEntry}
 <li>{$curEntry}</li>
{/foreach}
</ul>{elseif $type == $smarty.const.BBCODE_BOLD}<span style="font-weight:bold;">{$boldText}</span>{elseif $type == $smarty.const.BBCODE_ITALIC}<span style="font-style:italic;">{$italicText}</span>{elseif $type == $smarty.const.BBCODE_UNDERLINE}<span style="text-decoration:underline;">{$underlineText}</span>{elseif $type == $smarty.const.BBCODE_STRIKE}<span style="text-decoration:line-through;">{$strikeText}</span>{elseif $type == $smarty.const.BBCODE_SUPERSCRIPT}<sup>{$superText}</sup>{elseif $type == $smarty.const.BBCODE_SUBSCRIPT}<sub>{$subText}</sub>{elseif $type == $smarty.const.BBCODE_HIDE}<div>
 <div style="padding:3px;"><b>{$modules.Language->getString('hidden_text', 'BBCode')}:</b> <input class="formBBCodeButton" type="button" value="{$modules.Language->getString('uncover')}" onclick="(s = this.parentNode.parentNode.getElementsByTagName('div')[1].style).display = s.display == 'none' ? '' : 'none'; (s = this.parentNode.style).backgroundColor = s.backgroundColor == '' ? '#000000' : ''; s.color = s.color == '' ? '#FFFFFF' : ''; this.value = s.color == '' ? '{$modules.Language->getString('uncover')}' : '{$modules.Language->getString('hide')}';" /></div>
 <div style="background-color:#F0F8FF; border:1px #000000 solid; display:none; padding:10px;">{$hideText}</div>
</div>{elseif $type == $smarty.const.BBCODE_LOCK}<div>
 <div style="background-color:#000000; color:#FFFFFF; font-weight:bold; padding:3px;">{$modules.Language->getString('locked_text', 'BBCode')}:</div>
 <div style="border:1px #000000 solid; padding:10px; background-color:#{if $lockText != ''}F0F8FF;">{$lockText}{else}000000;"><span style="color:red; font-weight:bold;">{$modules.Language->getString('locked_text_hint')}</span>{/if}</div>
</div>{elseif $type == $smarty.const.BBCODE_CENTER}<p style="text-align:center;">{$centerText}</p>{elseif $type == $smarty.const.BBCODE_EMAIL}{mailto address=$eMailAddress text=$eMailText encode="javascript"}{elseif $type == $smarty.const.BBCODE_IMAGE}<img src="{$imageAddress}" alt="{$imageText}" title="{$imageText}" />{elseif $type == $smarty.const.BBCODE_LINK}<a href="{$linkAddress}" target="_blank">{$linkText}</a>{elseif $type == $smarty.const.BBCODE_COLOR}<span style="color:{$colorCode};">{$colorText}</span>{elseif $type == $smarty.const.BBCODE_IFRAME}<iframe src="{$iFrameLink}" width="{$iFrameWidth}" height="{$iFrameHeight}" frameborder="0"><a href="{$iFrameLink}" target="_blank">{$modules.Language->getString('no_iframe_supported', 'BBCode')}</a></iframe>{elseif $type == $smarty.const.BBCODE_SIZE}<span style="font-size:{$sizeFont};">{$sizeText}</span>{elseif $type == $smarty.const.BBCODE_GLOW}<div style="width:100%; filter:Glow(color={$glowColor}, strength=4);">{$glowText}</div>{* <span> doesn't work?! *}{elseif $type == $smarty.const.BBCODE_SHADOW}<div style="width:100%; filter:DropShadow(color={$shadowColor}, offx=2, offy=2);">{$shadowText}</div>{* <span> doesn't work?! *}{elseif $type == $smarty.const.BBCODE_FLASH}<object data="{$flashLink}" type="application/x-shockwave-flash" width="{$flashWidth}" height="{$flashHeight}">
 <param name="allowFullScreen" value="true "/>
 <param name="allowScriptAccess" value="sameDomain" />
 <param name="movie" value="{$flashLink}" />
 <param name="quality" value="autohigh" />
 <param name="wmode" value="transparent" />
 <p><a href="https://get.adobe.com/flashplayer/" target="_blank">{$modules.Language->getString('no_flash_installed', 'BBCode')}</a></p>
</object>{elseif $type == $smarty.const.BBCODE_CODE}<code>{$codeLines}</code>{/if}
