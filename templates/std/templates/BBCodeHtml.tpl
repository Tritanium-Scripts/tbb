{if $b.bbCodeType == $smarty.const.BBCODE_QUOTE}
 	<div align="center" style="width:100%;">
	 	<div align="left" style="width:90%;">
	 		<table style="border:1px #000000 solid;" width="100%">
	 			<tr><td style="background-color:#000000; padding:6px 3px 6px 3px;"><span style="font:10px verdana; color:#FFFFFF; font-weight:bold;">{$b.quoteTitle}:</span></td></tr>
	 			<tr><td style="background-color:#FFFFFF; padding:3px;"><span class="FontSmall">{$b.quoteText}</span></td></tr>
	 		</table>
	 	</div>
 	</div>
{elseif $b.bbCodeType == $smarty.const.BBCODE_CODE}
	<table class="TableStd" width="800px">
		<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('code')}</span></td></tr>
		<td class="CellBlank">
			<div style="overflow:auto; width:800px; min-height:37px; max-height:400px; padding:2px;">
				<table style="height:100%;">
					{counter start=$b.startLine print=false}
					{foreach from=$b.codeLines item=curCodeLine}
						<tr onmouseover="setRowBackground(this,'lightblue');" onmouseout="restoreRowBackground(this);">
							<td style="background-color:#FFFFFF; border-right:1px black dotted;"><pre style="padding:1px; margin:0px;"><span style="font-size:12px;">{counter}</span></pre></td>
							<td style="background-color:#FFFFFF;" valign="top"><pre style="padding:1px; margin:0px;"><span style="font-size:12px;">{$curCodeLine}</span></pre></td>
						</tr>
					{/foreach}
				</table>
			</div>
		</td>
	</table>
{elseif $b.bbCodeType == $smarty.const.BBCODE_LIST}
 <ul>
 {foreach from=$b.listElements item=curElem}
  <li>{$curElem}</li>
 {/foreach}
 </ul>
{elseif $b.bbCodeType == $smarty.const.BBCODE_BOLD}
 <span style="font-weight:bold;">{$b.boldText}</span>
{elseif $b.bbCodeType == $smarty.const.BBCODE_ITALIC}
 <span style="font-style:italic;">{$b.italicText}</span>
{elseif $b.bbCodeType == $smarty.const.BBCODE_UNDERLINE}
 <span style="text-decoration:underline;">{$b.underlineText}</span>
{elseif $b.bbCodeType == $smarty.const.BBCODE_STRIKE}
 <span style="text-decoration:line-through;">{$b.strikeText}</span>
{elseif $b.bbCodeType == $smarty.const.BBCODE_SUPERSCRIPT}
 <sup>{$b.superText}</sup>
{elseif $b.bbCodeType == $smarty.const.BBCODE_SUBSCRIPT}
 <sub>{$b.subText}</sub>
{elseif $b.bbCodeType == $smarty.const.BBCODE_EMAIL}
 <a href="mailto:{$b.emailAddress}">{$b.emailAddress}</a>
{elseif $b.bbCodeType == $smarty.const.BBCODE_CENTER}
 <p style="text-align:center;">{$b.centerText}</p>
{elseif $b.bbCodeType == $smarty.const.BBCODE_IMAGE}
 <img src="{$b.imageAddress}" alt="{$b.imageText}" title="{$b.imageText}"/>
{elseif $b.bbCodeType == $smarty.const.BBCODE_LINK}
 <a href="{$b.linkAddress}" target="_blank">{$b.linkText}</a>
{elseif $b.bbCodeType == $smarty.const.BBCODE_COLOR}
 <span style="color:{$b.colorCode};">{$b.colorText}</span>
{elseif $b.bbCodeType == $smarty.const.BBCODE_SIZE}
 <span style="font-size:{$b.sizeFont};">{$b.sizeText}</span>
{elseif $b.bbCodeType == $smarty.const.BBCODE_GLOW}
 <div style="width:100%; filter:Glow(color={$b.glowColor}, strength=4);">{$b.glowText}</div>{* <span> doesn't work?! *}
{elseif $b.bbCodeType == $smarty.const.BBCODE_SHADOW}
 <div style="width:100%; filter:DropShadow(color={$b.shadowColor}, offx=2, offy=2);">{$b.shadowText}</div>{* <span> doesn't work?! *}
{elseif $b.bbCodeType == $smarty.const.BBCODE_FLASH}
 <object data="{$b.flashLink}" type="application/x-shockwave-flash" width="{$b.flashWidth}" height="{$b.flashHeight}">
  <param name="allowscriptaccess" value="samedomain"/>
  <param name="movie" value="{$b.flashLink}"/>
  <param name="quality" value="autohigh"/>
  <param name="wmode" value="transparent"/>
  <noscript>{$modules.Language->getString('no_flash')}</noscript>
 </object>
{/if}