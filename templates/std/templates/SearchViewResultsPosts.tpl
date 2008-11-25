<form method="post" action="{$indexFile}?action=Search&amp;mode=ViewResults&amp;searchID={$searchID}&amp;{$mySID}">
	<table class="TableStd" width="100%">
		<colgroup>
			<col width="15%"/>
			<col width="85%"/>
		</colgroup>
		<tr>
			<td class="CellTitle"><span class="FontTitleSmall">{$modules.Language->getString('author')}</span></td>
			<td class="CellTitle"><span class="FontTitleSmall">{$modules.Language->getString('post')}</span></td>
		</tr>
		{foreach from=$postsData item=curPost}
			<tr>
				<td class="CellAlt" valign="top" rowspan="3"><span class="FontNorm"><b>{$curPost._postPoster}</b></span></td>
				<td class="CellAlt" valign="middle"><span class="FontSmall"><a id="post{$curPost.postID}" name="post{$curPost.postID}"></a><b>{$curPost.postTitle}</b></span></td>
			</tr>
			<tr><td class="CellStd"><div class="FontNorm">{$curPost._postText}</div></td></tr>
			<tr><td class="CellStd" width="85%"><span class="FontSmall">{$modules.Language->getString('posted')}: {$curPost._postDateTime}</span></td></tr>
		{/foreach}
		<tr>
			<td colspan="2" class="CellButtons">
				<span class="FontSmall">
					<b>{$modules.Language->getString('display_options')}:</b>
					{$modules.Language->getString('results')} <select class="FormSelect" name="displayResults"><option value="topics"{if $displayResults == 'topics'} selected="selected"{/if}>{$modules.Language->getString('display_as_topics')}</option><option value="posts"{if $displayResults == 'posts'} selected="selected"{/if}>{$modules.Language->getString('display_as_posts')}</option></select>;
					{$modules.Language->getString('sort_by')} <select class="FormSelect" name="sortType"><option value="time"{if $sortType == 'time' || $sortType == 'timeCreation'} selected="selected"{/if}>{$modules.Language->getString('post_age')}</option><option value="title"{if $sortType == 'title'} selected="selected"{/if}>{$modules.Language->getString('post_title')}</option><option value="author"{if $sortType == 'author'} selected="selected"{/if}>{$modules.Language->getString('author')}</option></select>
					<select class="FormSelect" name="sortMethod"><option value="DESC"{if $sortMethod == 'DESC'} selected="selected"{/if}>{$modules.Language->getString('descending')}</option><option value="ASC"{if $sortMethod == 'ASC'} selected="selected"{/if}>{$modules.Language->getString('ascending')}</option></select>; {$modules.Language->getString('results_per_page')}
					<select class="FormSelect" name="resultsPerPage"><option value="10"{if $resultsPerPage == 10} selected="selected"{/if}>10</option><option value="20"{if $resultsPerPage == 20} selected="selected"{/if}>20</option><option value="50"{if $resultsPerPage == 50} selected="selected"{/if}>50</option><option value="100"{if $resultsPerPage == 100} selected="selected"{/if}>100</option></select>&nbsp;&nbsp;&nbsp;<input class="FormBButton" type="submit" value="{$modules.Language->getString('go')}"/>
				</span>
			</td>
		</tr>
	</table>
</form>
