<!-- BBCodes -->
<input class="forumcode" style="font-weight:bold;" type="button" value="B" onclick="setTag('[b]','[/b]');" />
<input class="forumcode" style="font-style:italic;" type="button" value="I" onclick="setTag('[i]','[/i]');" />
<input class="forumcode" style="text-decoration:underline;" type="button" value="U" onclick="setTag('[u]','[/u]');" />
<input class="forumcode" style="text-decoration:line-through;" type="button" value="S" onclick="setTag('[s]','[/s]');" />
<input class="forumcode" type="button" value="{Language::getInstance()->getString('center', 'BBCode')}" onclick="setTag('[center]','[/center]');" />
<button class="forumcode" type="button" onclick="setTag('[sup]','[/sup]');"><span style="position:relative; top:-0.3em;">{Language::getInstance()->getString('superscript')}</span></button>
<button class="forumcode" type="button" onclick="setTag('[sub]', '[/sub]');"><span style="position:relative; bottom:-0.3em;">{Language::getInstance()->getString('subscript')}</span></button>
<input class="forumcode" type="button" value="{Language::getInstance()->getString('quote_quoted')}" onclick="setTag('[quote]','[/quote]');" />
<input class="forumcode" style="font-family:monospace;" type="button" value="{Language::getInstance()->getString('code')}" onclick="setTag('[code]','[/code]');" />
<input class="forumcode" type="button" value="PHP" onclick="setTag('[php]','[/php]');" />
<input class="forumcode" type="button" value="[noparse]" onclick="setTag('[noparse]','[/noparse]');" /><br />

<select class="colorselect" onchange="if(this.options.selectedIndex != 0) setTag('[color=' + this.options[this.options.selectedIndex].value + ']', '[/color]'); this.options.selectedIndex = 0;">
 <option>{Language::getInstance()->getString('font_color')}</option>
 <option value="#000000" style="background-color:#000000; color:#000000;">{Language::getInstance()->getString('black')}</option>
 <option value="#808080" style="background-color:#808080; color:#808080;">{Language::getInstance()->getString('dark_grey')}</option>
 <option value="#800000" style="background-color:#800000; color:#800000;">{Language::getInstance()->getString('dark_red')}</option>
 <option value="#FF0000" style="background-color:#FF0000; color:#FF0000;">{Language::getInstance()->getString('red')}</option>
 <option value="#008000" style="background-color:#008000; color:#008000;">{Language::getInstance()->getString('dark_green')}</option>
 <option value="#00FF00" style="background-color:#00FF00; color:#00FF00;">{Language::getInstance()->getString('light_green')}</option>
 <option value="#808000" style="background-color:#808000; color:#808000;">{Language::getInstance()->getString('ochre')}</option>
 <option value="#FFFF00" style="background-color:#FFFF00; color:#FFFF00;">{Language::getInstance()->getString('yellow')}</option>
 <option value="#000080" style="background-color:#000080; color:#000080;">{Language::getInstance()->getString('dark_blue')}</option>
 <option value="#0000FF" style="background-color:#0000FF; color:#0000FF;">{Language::getInstance()->getString('blue')}</option>
 <option value="#800080" style="background-color:#800080; color:#800080;">{Language::getInstance()->getString('dark_purple')}</option>
 <option value="#FF00FF" style="background-color:#FF00FF; color:#FF00FF;">{Language::getInstance()->getString('purple')}</option>
 <option value="#008080" style="background-color:#008080; color:#008080;">{Language::getInstance()->getString('dark_turquoise')}</option>
 <option value="#00FFFF" style="background-color:#00FFFF; color:#00FFFF;">{Language::getInstance()->getString('turquoise')}</option>
 <option value="#C0C0C0" style="background-color:#C0C0C0; color:#C0C0C0;">{Language::getInstance()->getString('grey')}</option>
 <option value="#FFFFFF" style="background-color:#FFFFFF; color:#FFFFFF;">{Language::getInstance()->getString('white')}</option>
</select>
<select class="colorselect" onchange="if(this.options.selectedIndex != 0) setTag('[size=' + this.options[this.options.selectedIndex].value + ']', '[/size]'); this.options.selectedIndex = 0;">
 <option>{Language::getInstance()->getString('font_size')}</option>
 {html_options values=array('-2', '-1', '+1', '+2', '+3', '+4') output=array(Language::getInstance()->getString('size_down2'), Language::getInstance()->getString('size_down1'), Language::getInstance()->getString('size_up1'), Language::getInstance()->getString('size_up2'), Language::getInstance()->getString('size_up3'), Language::getInstance()->getString('size_up4'))}
</select>
<select class="colorselect" onchange="if(this.options.selectedIndex != 0) setTag('[glow=' + this.options[this.options.selectedIndex].value + ']', '[/glow]'); this.options.selectedIndex = 0;">
 <option>{Language::getInstance()->getString('font_glow')}</option>
 <option value="#FF0000" style="background-color:#FF0000; color:#FF0000;">{Language::getInstance()->getString('red')}</option>
 <option value="#FFFF00" style="background-color:#FFFF00; color:#FFFF00;">{Language::getInstance()->getString('yellow')}</option>
 <option value="#008000" style="background-color:#008000; color:#008000;">{Language::getInstance()->getString('green')}</option>
 <option value="#0000FF" style="background-color:#0000FF; color:#0000FF;">{Language::getInstance()->getString('blue')}</option>
</select>
<select class="colorselect" onchange="if(this.options.selectedIndex != 0) setTag('[shadow=' + this.options[this.options.selectedIndex].value + ']', '[/shadow]'); this.options.selectedIndex = 0;">
 <option>{Language::getInstance()->getString('font_shadow')}</option>
 <option value="#FF0000" style="background-color:#FF0000; color:#FF0000;">{Language::getInstance()->getString('red')}</option>
 <option value="#FFFF00" style="background-color:#FFFF00; color:#FFFF00;">{Language::getInstance()->getString('yellow')}</option>
 <option value="#008000" style="background-color:#008000; color:#008000;">{Language::getInstance()->getString('green')}</option>
 <option value="#0000FF" style="background-color:#0000FF; color:#0000FF;">{Language::getInstance()->getString('blue')}</option>
</select>{if Config::getInstance()->getCfgVal('enable_uploads') == 1 && Auth::getInstance()->isLoggedIn()}

<input class="forumcode" type="button" value="{Language::getInstance()->getString('upload_file')}" onclick="window.open('{$smarty.const.INDEXFILE}?faction=uploadFile&amp;targetBoxID={$targetBoxID}', '_blank', 'width=500,height=400, status');" />{/if}<br />

<input class="forumcode" type="button" value="URL" onclick="setTag('[url]','[/url]');" />
<input class="forumcode" type="button" value="IMG" onclick="setTag('[img]','[/img]');" />
<input class="forumcode" type="button" value="{Language::getInstance()->getString('email')}" onclick="setTag('[email]','[/email]');" />
<input class="forumcode" type="button" value="{Language::getInstance()->getString('bullet_list')}" onclick="setTag('[list]\n[*]','\n[/list]');" />
<input class="forumcode" type="button" value="IFRAME" onclick="setTag('[iframe]','[/iframe]');" />
<input class="forumcode" type="button" value="{Language::getInstance()->getString('hidden_text')}" onclick="setTag('[hide]','[/hide]');" />
<input class="forumcode" type="button" value="{Language::getInstance()->getString('locked_text')}" onclick="setTag('[lock]','[/lock]');" />