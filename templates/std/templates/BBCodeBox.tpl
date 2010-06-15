<input class="FormBBCodeButton" style="font-weight:bold;" type="button" value="B" onclick="insert('[b]','[/b]');"/>
<input class="FormBBCodeButton" style="font-style:italic;" type="button" value="I" onclick="insert('[i]','[/i]');"/>
<input class="FormBBCodeButton" style="text-decoration:underline;" type="button" value="U" onclick="insert('[u]','[/u]');"/>
<input class="FormBBCodeButton" style="text-decoration:line-through;" type="button" value="S" onclick="insert('[s]','[/s]');"/>
<input class="FormBBCodeButton" type="button" value="{$modules.Language->getString('center')}" onclick="insert('[center]','[/center]');"/>
<button class="FormBBCodeButton" type="button" onclick="insert('[sup]','[/sup]');"><span style="position:relative; top:-0.3em;">{$modules.Language->getString('superscript')}</span></button>
<button class="FormBBCodeButton" type="button" onclick="insert('[sub]', '[/sub]');"><span style="position:relative; bottom:-0.3em;">{$modules.Language->getString('subscript')}</span></button>
<input class="FormBBCodeButton" type="button" value="{$modules.Language->getString('quote_button')}" onclick="insert('[quote]','[/quote]');"/>
<input class="FormBBCodeButton" style="font-family:monospace; position:relative; top:-0.1em;" type="button" value="{$modules.Language->getString('code')}" onclick="insert('[code]','[/code]');"/>
<input class="FormBBCodeButton" type="button" value="PHP" onclick="insert('[php]','[/php]');"/>
<input class="FormBBCodeButton" type="button" value="[noparse]" onclick="insert('[noparse]','[/noparse]');"/><br />
<select class="FormSelect" onchange="if(this.options[this.options.selectedIndex].value != '') insert('[color=' + this.options[this.options.selectedIndex].value + ']', '[/color]');">
 <option>{$modules.Language->getString('font_color')}</option>
 <option value="#000000" style="background-color:#000000; color:#000000;">{$modules.Language->getString('black')}</option>
 <option value="#808080" style="background-color:#808080; color:#808080;">{$modules.Language->getString('dark_grey')}</option>
 <option value="#800000" style="background-color:#800000; color:#800000;">{$modules.Language->getString('dark_red')}</option>
 <option value="#FF0000" style="background-color:#FF0000; color:#FF0000;">{$modules.Language->getString('red')}</option>
 <option value="#008000" style="background-color:#008000; color:#008000;">{$modules.Language->getString('dark_green')}</option>
 <option value="#00FF00" style="background-color:#00FF00; color:#00FF00;">{$modules.Language->getString('light_green')}</option>
 <option value="#808000" style="background-color:#808000; color:#808000;">{$modules.Language->getString('ochre')}</option>
 <option value="#FFFF00" style="background-color:#FFFF00; color:#FFFF00;">{$modules.Language->getString('yellow')}</option>
 <option value="#000080" style="background-color:#000080; color:#000080;">{$modules.Language->getString('dark_blue')}</option>
 <option value="#0000FF" style="background-color:#0000FF; color:#0000FF;">{$modules.Language->getString('blue')}</option>
 <option value="#800080" style="background-color:#800080; color:#800080;">{$modules.Language->getString('dark_purple')}</option>
 <option value="#FF00FF" style="background-color:#FF00FF; color:#FF00FF;">{$modules.Language->getString('purple')}</option>
 <option value="#008080" style="background-color:#008080; color:#008080;">{$modules.Language->getString('dark_turquoise')}</option>
 <option value="#00FFFF" style="background-color:#00FFFF; color:#00FFFF;">{$modules.Language->getString('turquoise')}</option>
 <option value="#C0C0C0" style="background-color:#C0C0C0; color:#C0C0C0;">{$modules.Language->getString('grey')}</option>
 <option value="#FFFFFF" style="background-color:#FFFFFF; color:#FFFFFF;">{$modules.Language->getString('white')}</option>
</select>
<select class="FormSelect" onchange="if(this.options[this.options.selectedIndex].value != '') insert('[size=' + this.options[this.options.selectedIndex].value + ']', '[/size]');">
 <option>{$modules.Language->getString('font_size')}</option>
 <option value="-2">{$modules.Language->getString('size_down2')}</option>
 <option value="-1">{$modules.Language->getString('size_down1')}</option>
 <option value="+1">{$modules.Language->getString('size_up1')}</option>
 <option value="+2">{$modules.Language->getString('size_up2')}</option>
 <option value="+3">{$modules.Language->getString('size_up3')}</option>
 <option value="+4">{$modules.Language->getString('size_up4')}</option>
</select>
<!-- CSS3 is needed for glow and shadow to keep a valid document -->
<select class="FormSelect" onchange="if(this.options[this.options.selectedIndex].value != '') insert('[glow=' + this.options[this.options.selectedIndex].value + ']', '[/glow]');" disabled="disabled">
 <option>{$modules.Language->getString('font_glow')}</option>
 <option value="#FF0000" style="background-color:#FF0000; color:#FF0000;">{$modules.Language->getString('red')}</option>
 <option value="#FFFF00" style="background-color:#FFFF00; color:#FFFF00;">{$modules.Language->getString('yellow')}</option>
 <option value="#008000" style="background-color:#008000; color:#008000;">{$modules.Language->getString('green')}</option>
 <option value="#0000FF" style="background-color:#0000FF; color:#0000FF;">{$modules.Language->getString('blue')}</option>
</select>
<select class="FormSelect" onchange="if(this.options[this.options.selectedIndex].value != '') insert('[shadow=' + this.options[this.options.selectedIndex].value + ']', '[/shadow]');" disabled="disabled">
 <option>{$modules.Language->getString('font_shadow')}</option>
 <option value="#FF0000" style="background-color:#FF0000; color:#FF0000;">{$modules.Language->getString('red')}</option>
 <option value="#FFFF00" style="background-color:#FFFF00; color:#FFFF00;">{$modules.Language->getString('yellow')}</option>
 <option value="#008000" style="background-color:#008000; color:#008000;">{$modules.Language->getString('green')}</option>
 <option value="#0000FF" style="background-color:#0000FF; color:#0000FF;">{$modules.Language->getString('blue')}</option>
</select><br />
<input class="FormBBCodeButton" type="button" value="URL" onclick="insert('[url]','[/url]');"/>
<input class="FormBBCodeButton" type="button" value="IMG" onclick="insert('[img]','[/img]');"/>
<input class="FormBBCodeButton" type="button" value="{$modules.Language->getString('email')}" onclick="insert('[email]','[/email]');"/>
<input class="FormBBCodeButton" type="button" value="{$modules.Language->getString('list')}" onclick="insert('[list]\n[*]','\n[/list]');"/>
<input class="FormBBCodeButton" type="button" value="{$modules.Language->getString('flash')}" onclick="insert('[flash]','[/flash]');"/>
<input class="FormBBCodeButton" type="button" value="{$modules.Language->getString('hidden_text')}" onclick="insert('[hide]','[/hide]');"/>
<input class="FormBBCodeButton" type="button" value="{$modules.Language->getString('locked_text')}" onclick="insert('[lock]','[/lock]');"/>