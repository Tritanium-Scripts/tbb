<input class="FormBBCodeButton" style="font-weight:bold;" type="button" value="B" onclick="insert('[b]','[/b]');"/>
<input class="FormBBCodeButton" style="font-style:italic;" type="button" value="I" onclick="insert('[i]','[/i]');"/>
<input class="FormBBCodeButton" style="text-decoration:underline;" type="button" value="U" onclick="insert('[u]','[/u]');"/>
<input class="FormBBCodeButton" style="text-decoration:line-through;" type="button" value="S" onclick="insert('[s]','[/s]');"/>
<input class="FormBBCodeButton" type="button" value="URL" onclick="insert('[url]','[/url]');"/>
<input class="FormBBCodeButton" type="button" value="IMG" onclick="insert('[img]','[/img]');"/>
<input class="FormBBCodeButton" type="button" value="E-Mail" onclick="insert('[email]','[/email]');"/>
<select class="FormSelect" onchange="if(this.options[this.options.selectedIndex].value != '') insert('[color=' + this.options[this.options.selectedIndex].value + ']', '[/color]');">
 <option value="">{$modules.Language->getString('Font_color')}</option>
 <option value="#000000" style="background-color:#000000; color:#000000;">{$modules.Language->getString('Black')}</option>
 <option value="#808080" style="background-color:#808080; color:#808080;">{$modules.Language->getString('Dark_grey')}</option>
 <option value="#800000" style="background-color:#800000; color:#800000;">{$modules.Language->getString('Dark_red')}</option>
 <option value="#FF0000" style="background-color:#FF0000; color:#FF0000;">{$modules.Language->getString('Red')}</option>
 <option value="#008000" style="background-color:#008000; color:#008000;">{$modules.Language->getString('Dark_green')}</option>
 <option value="#00FF00" style="background-color:#00FF00; color:#00FF00;">{$modules.Language->getString('Light_green')}</option>
 <option value="#808000" style="background-color:#808000; color:#808000;">{$modules.Language->getString('Ochre')}</option>
 <option value="#FFFF00" style="background-color:#FFFF00; color:#FFFF00;">{$modules.Language->getString('Yellow')}</option>
 <option value="#000080" style="background-color:#000080; color:#000080;">{$modules.Language->getString('Dark_blue')}</option>
 <option value="#0000FF" style="background-color:#0000FF; color:#0000FF;">{$modules.Language->getString('Blue')}</option>
 <option value="#800080" style="background-color:#800080; color:#800080;">{$modules.Language->getString('Dark_purple')}</option>
 <option value="#FF00FF" style="background-color:#FF00FF; color:#FF00FF;">{$modules.Language->getString('Purple')}</option>
 <option value="#008080" style="background-color:#008080; color:#008080;">{$modules.Language->getString('Dark_turquoise')}</option>
 <option value="#00FFFF" style="background-color:#00FFFF; color:#00FFFF;">{$modules.Language->getString('Turquoise')}</option>
 <option value="#C0C0C0" style="background-color:#C0C0C0; color:#C0C0C0;">{$modules.Language->getString('Grey')}</option>
 <option value="#FFFFFF" style="background-color:#FFFFFF; color:#FFFFFF;">{$modules.Language->getString('White')}</option>
</select>
<select class="FormSelect" onchange="if(this.options[this.options.selectedIndex].value != '') insert('[size=' + this.options[this.options.selectedIndex].value + ']', '[/size]');">
 <option value="">{$modules.Language->getString('Font_size')}</option>
 <option value="-2">{$modules.Language->getString('Size_down2')}</option>
 <option value="-1">{$modules.Language->getString('Size_down1')}</option>
 <option value="+1">{$modules.Language->getString('Size_up1')}</option>
 <option value="+2">{$modules.Language->getString('Size_up2')}</option>
 <option value="+3">{$modules.Language->getString('Size_up3')}</option>
 <option value="+4">{$modules.Language->getString('Size_up4')}</option>
</select><br />
<input class="FormBBCodeButton" type="button" value="{$modules.Language->getString('Quote')}" onclick="insert('[quote]','[/quote]');"/>
<input class="FormBBCodeButton" type="button" value="Code" onclick="insert('[code]','[/code]');"/>
<input class="FormBBCodeButton" type="button" value="PHP" onclick="insert('[php]','[/php]');"/>
<input class="FormBBCodeButton" type="button" value="Center" onclick="insert('[center]','[/center]');"/>
<input class="FormBBCodeButton" type="button" value="{$modules.Language->getString('List')}" onclick="insert('[list]\n[*]','\n[/list]');"/>
<input class="FormBBCodeButton" type="button" value="Flash" onclick="insert('[flash]','[/flash]');"/>
<select class="FormSelect" onchange="if(this.options[this.options.selectedIndex].value != '') insert('[glow=' + this.options[this.options.selectedIndex].value + ']', '[/glow]');">
 <option value="">{$modules.Language->getString('Font_glow')}</option>
 <option value="#FF0000" style="background-color:#FF0000; color:#FF0000;">{$modules.Language->getString('Red')}</option>
 <option value="#FFFF00" style="background-color:#FFFF00; color:#FFFF00;">{$modules.Language->getString('Yellow')}</option>
 <option value="#008000" style="background-color:#008000; color:#008000;">{$modules.Language->getString('Green')}</option>
 <option value="#0000FF" style="background-color:#0000FF; color:#0000FF;">{$modules.Language->getString('Blue')}</option>
</select>
<select class="FormSelect" onchange="if(this.options[this.options.selectedIndex].value != '') insert('[shadow=' + this.options[this.options.selectedIndex].value + ']', '[/shadow]');">
 <option value="">{$modules.Language->getString('Font_shadow')}</option>
 <option value="#FF0000" style="background-color:#FF0000; color:#FF0000;">{$modules.Language->getString('Red')}</option>
 <option value="#FFFF00" style="background-color:#FFFF00; color:#FFFF00;">{$modules.Language->getString('Yellow')}</option>
 <option value="#008000" style="background-color:#008000; color:#008000;">{$modules.Language->getString('Green')}</option>
 <option value="#0000FF" style="background-color:#0000FF; color:#0000FF;">{$modules.Language->getString('Blue')}</option>
</select>