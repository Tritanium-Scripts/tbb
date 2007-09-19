{if $b.bbCodeType == $smarty.const.BBCODE_QUOTE}
 <div align="center"><table style="border:1px #000000 solid;" width="90%"><tr><td style="background-color:#000000; padding:10px;"><span style="font:10px verdana; color:#FFFFFF;"><b>{$b.quoteTitle}:</b></span></td></tr><tr><td style="background-color:#FFFFFF; padding:3px;"><span class="FontSmall">{$b.quoteText}</span></td></tr></table></div>
{elseif $b.bbCodeType == $smarty.const.BBCODE_CODE}
 <div style="overflow:auto; width:800px; height:{$b.height}px;"><table style="background-color:#000000; height:100%;" border="0" cellpadding="2" cellspacing="1" width="100%"><tr><td class="cellcat" colspan="2"><span class="fontcat">{$modules.Language->getString('Code')}</span></td></tr><tr><td style="background-color:#FFFFFF;" valign="top"><pre><span style="font-size:12px;">{$b.lines}</span></pre></td><td style="background-color:#FFFFFF;" valign="top"><pre><span style="font-size:12px;">{$b.codeText}</span></pre></td></tr></table></div>
{elseif $b.bbCodeType == $smarty.const.BBCODE_BOLD}
 <span style="font-weight:bold;">{$b.boldText}</span>
{elseif $b.bbCodeType == $smarty.const.BBCODE_ITALIC}
 <span style="font-style:italic;">{$b.italicText}</span>
{elseif $b.bbCodeType == $smarty.const.BBCODE_UNDERLINE}
 <span style="text-decoration:underline;">{$b.underlineText}</span>
{elseif $b.bbCodeType == $smarty.const.BBCODE_STRIKE}
 <span style="text-decoration:line-through;">{$b.strikeText}</span>
{elseif $b.bbCodeType == $smarty.const.BBCODE_EMAIL}
 <a href="mailto:{$b.emailAddress}">{$b.emailAddress}</a>
{elseif $b.bbCodeType == $smarty.const.BBCODE_CENTER}
 <p style="text-align:center;">{$b.centerText}</p>
{elseif $b.bbCodeType == $smarty.const.BBCODE_IMAGE}
 <img src="{$b.imageAddress}" alt="" border="0"/>
{elseif $b.bbCodeType == $smarty.const.BBCODE_LINK}
 <a href="{$b.linkAddress}" target="_blank">{$b.linkText}</a>
{elseif $b.bbCodeType == $smarty.const.BBCODE_COLOR}
 <span style="color:{$b.colorCode}">{$b.colorText}</span>
{/if}