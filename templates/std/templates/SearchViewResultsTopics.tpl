<form method="post" action="{$indexFile}?action=Search&amp;mode=ViewResults&amp;searchID={$searchID}&amp;{$mySID}">
	<table class="TableStd" width="100%">
		<tr>
			<td class="CellTitle" align="center"><span class="FontTitleSmall">{$modules.Language->getString('topic')}</span></td>
			<td class="CellTitle" align="center"><span class="FontTitleSmall">{$modules.Language->getString('author')}</span></td>
			<td class="CellTitle" align="center"><span class="FontTitleSmall">{$modules.Language->getString('replies')}</span></td>
			<td class="CellTitle" align="center"><span class="FontTitleSmall">{$modules.Language->getString('views')}</span></td>
			<td class="CellTitle" align="center"><span class="FontTitleSmall">{$modules.Language->getString('last_post')}</span></td>
		</tr>
		{foreach from=$topicsData item=curTopic}
			<tr>
				<td class="CellStd"><span class="FontNorm">{$curTopic._topicPrefix}</span><span class="FontNorm"><a class="TopicLink" href="{$indexFile}?action=ViewTopic&amp;topicID={$curTopic.topicID}&amp;{$mySID}">{$curTopic.topicTitle}</a></span></td>
				<td class="CellAlt" align="center"><span class="FontNorm">{$curTopic._topicPoster}</span></td>
				<td class="CellStd" align="center"><span class="FontSmall">{$curTopic.topicRepliesCounter}</span></td>
				<td class="CellAlt" align="center"><span class="FontSmall">{$curTopic.topicViewsCounter}</span></td>
				<td class="CellStd" align="right"><span class="FontSmall">{$curTopic._topicLastPost}</span></td>
			</tr>
		{/foreach}
		<tr>
			<td colspan="5" class="CellButtons">
				<span class="FontSmall">
					<b>{$modules.Language->getString('display_options')}:</b>
					{$modules.Language->getString('results')} <select class="FormSelect" name="displayResults"><option value="topics"{if $displayResults == 'topics'} selected="selected"{/if}>{$modules.Language->getString('display_as_topics')}</option><option value="posts"{if $displayResults == 'posts'} selected="selected"{/if}>{$modules.Language->getString('display_as_posts')}</option></select>;
					{$modules.Language->getString('sort_by')} <select class="FormSelect" name="sortType"><option value="time"{if $sortType == 'time'} selected="selected"{/if}>{$modules.Language->getString('date_last_post')}</option><option value="timeCreation"{if $sortType == 'timeCreation'} selected="selected"{/if}>{$modules.Language->getString('topic_creation_date')}</option><option value="title"{if $sortType == 'title'} selected="selected"{/if}>{$modules.Language->getString('post_title')}</option><option value="author"{if $sortType == 'author'} selected="selected"{/if}>{$modules.Language->getString('author')}</option></select>
					<select class="FormSelect" name="sortMethod"><option value="DESC"{if $sortMethod == 'DESC'} selected="selected"{/if}>{$modules.Language->getString('descending')}</option><option value="ASC"{if $sortMethod == 'ASC'} selected="selected"{/if}>{$modules.Language->getString('ascending')}</option></select>; {$modules.Language->getString('results_per_page')}
					<select class="FormSelect" name="resultsPerPage"><option value="10"{if $resultsPerPage == 10} selected="selected"{/if}>10</option><option value="20"{if $resultsPerPage == 20} selected="selected"{/if}>20</option><option value="50"{if $resultsPerPage == 50} selected="selected"{/if}>50</option><option value="100"{if $resultsPerPage == 100} selected="selected"{/if}>100</option></select>&nbsp;&nbsp;&nbsp;<input class="FormBButton" type="submit" value="{$modules.Language->getString('go')}"/>
				</span>
			</td>
		</tr>
	</table>
</form>
