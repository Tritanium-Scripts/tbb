<form method="post" action="{$indexFile}?action=Search&amp;doit=1&amp;{$mySID}">
	<table class="TableStd" width="100%">
		<colgroup>
			<col width="30%"/>
			<col width="70%"/>
		</colgroup>
		<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('Search')}</span></td></tr>
		{if $error != ''}<tr><td class="CellError" colspan="2"><span class="FontError">{$error}</span></td></tr>{/if}
		<tr>
			<td class="CellStd" valign="top"><span class="FontNorm"><b>{$modules.Language->getString('Search_for_keywords')}:</b></span><br/><span class="FontSmall">{$modules.Language->getString('search_keywords_info')}</span></td>
			<td class="CellAlt" valign="top"><input class="FormText" type="text" name="p[searchWords]" value="{$p.searchWords}" size="50"/><br/><span class="FontSmall"><label><input type="checkbox" name="p[searchWordsExact]" value="1"{if $p.searchWordsExact == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('Exact_search')}</label></span></td>
		</tr>
		<tr>
			<td class="CellStd" valign="top"><span class="FontNorm"><b>{$modules.Language->getString('Search_for_author')}:</b></span><br/><span class="FontSmall">{$modules.Language->getString('search_author_info')}</span></td>
			<td class="CellAlt" valign="top"><input class="FormText" type="text" name="p[searchAuthor]" value="{$p.searchAuthor}" size="20"/></td>
		</tr>
		<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('Advanced_search_options')}</span></td></tr>
		<tr>
			<td class="CellStd" colspan="2">
				<table border="0" cellpadding="3" cellspacing="0">
					<tr>
						<td valign="top">
							<fieldset style="padding:3px; margin-right:5px;">
								<legend><span class="FontSmall"><b>{$modules.Language->getString('Forums')}</b></span></legend>
								<select class="FormSelect" name="p[searchForums][]" size="10" multiple="multiple">
									<option value="all">{$modules.Language->getString('Search_all_forums')}</option>
									<option value=""></option>
									{foreach from=$catsData item=curCat}
										<option value="" style="background-color:gray;">{repeat text=-- cycles=$curCat.catDepth} {$curCat.catName}</option>
										{foreach from=$forumsData item=curForum}
											{if $curForum.catID == $curCat.catID}
												<option value="{$curForum.forumID}">--{repeat text=-- cycles=$curCat.catDepth} {$curForum.forumName}</option>
											{/if}
										{/foreach}
									{/foreach}
								</select></fieldset>
						</td>
						<td valign="top">
							<fieldset style="padding:3px">
								<legend><span class="FontSmall"><b>{$modules.Language->getString('Sort_by')}</b></span></legend>
								<select class="FormSelect" name="p_search_sort_by"><option value="0">{$modules.Language->getString('Post_age')}</option></select><br/>
								<span class="FontSmall"><label><input type="radio" name="p[searchSortMethod]" value="0"{if $p.searchSortMethod == 0} checked="checked"{/if}/> {$modules.Language->getString('Descending')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[searchSortMethod]" value="1"{if $p.searchSortMethod == 1} checked="checked"{/if}/> {$modules.Language->getString('Ascending')}</label></span>
							</fieldset>     
							<fieldset style="padding:3px">
								<legend><span class="FontSmall"><b>{$modules.Language->getString('Results')}</b></span></legend>
								<select class="FormSelect" name="p[displayResults]"><option value="0">{$modules.Language->getString('Display_as_topics')}</option><option value="1">{$modules.Language->getString('Display_as_posts')}</option></select>
							</fieldset>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('Start_search')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('Reset')}"/></td></tr>
	</table>
</form>