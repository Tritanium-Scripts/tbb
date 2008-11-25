<table class="TableStd" width="100%">
	<tr>
		<td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('template_name')}</span></td>
		<td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('template_author')}</span></td>
		<td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('author_comment')}</span></td>
	</tr>
	{foreach from=$templatesData item=curTemplate}
		<tr>
			<td class="CellStd" valign="top"><span class="FontNorm">{$curTemplate.templateInfo.templateName}</span></td>
			<td class="CellAlt" valign="top"><span class="FontNorm">{if $curTemplate.templateInfo.authorUrl neq ''}<a href="{$curTemplate.templateInfo.authorUrl}">{$curTemplate.templateInfo.authorName}</a>{else}{$curTemplate.templateInfo.authorName}{/if}</span></td>
			<td class="CellStd" width="50%"><span class="FontSmall">{$curTemplate.templateInfo.authorComment}</span></td>
		</tr>
	{/foreach}
</table>
<br/>
<form method="post" action="{$indexFile}?action=AdminTemplates&amp;doit=1&amp;{$mySID}">
	<table class="TableStd" width="100%">
		<colgroup>
			<col width="25%"/>
			<col width="75%"/>
		</colgroup>
		<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('template_settings')}</span></td></tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('standard_template')}</span></td>
			<td class="CellAlt">
				<select onchange="document.tbb2form.submit();" class="FormSelect" name="p[standardTemplate]">
					{foreach from=$templatesData item=curTemplate}
						<option value="{$curTemplate.templateDir}"{if $curTemplate.templateDir == $p.standardTemplate} selected="selected"{/if}>{$curTemplate.templateInfo.templateName}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('allow_select_template')}</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[allowSelectTemplate]" value="1"{if $p.allowSelectTemplate == 1} checked="checked"{/if}/> {$modules.Language->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[allowSelectTemplate]" value="0"{if $p.allowSelectTemplate == 0} checked="checked"{/if}/> {$modules.Language->getString('negative')}</label></span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('template_standard_style')}</span></td>
			<td class="CellAlt">
				<select class="FormSelect" name="p[standardStyle]">
					{foreach from=$stylesData item=curStyle}
						<option value="{$curStyle}"{if $curStyle == $p.standardStyle} selected="selected"{/if}>{$curStyle}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('allow_select_style')}</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[allowSelectStyle]" value="1"{if $p.allowSelectStyle == 1} checked="checked"{/if}/> {$modules.Language->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[allowSelectStyle]" value="0"{if $p.allowSelectStyle == 0} checked="checked"{/if}/> {$modules.Language->getString('negative')}</label></span></td>
		</tr>
		<tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('update_template_config')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('reset')}"/></td></tr>
	</table>
</form>
