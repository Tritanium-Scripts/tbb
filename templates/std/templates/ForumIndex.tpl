<script type="text/javascript">/* <![CDATA[ */

templateDir = "{$modules.Template->getTemplateDir()}";
closedCatIDs = new Array();

{literal}
function switchCatStatus(catID) {
	var i;

	if(document.getElementById("CatForums"+catID).style.display == "none") {
		document.getElementById("CatForums"+catID).style.display = "";
		document.getElementById("CatPic"+catID).src = templateDir+"/images/minus.gif";

		for(i = 0; i < closedCatIDs.length; i++) {
			if(closedCatIDs[i] == catID) {
				closedCatIDs.splice(i,1);
				setCookieValue("closedCatIDs",closedCatIDs.join("."));
				break;
			}
		}
	} else {
		document.getElementById("CatForums"+catID).style.display= "none";
		document.getElementById("CatPic"+catID).src = templateDir+"/images/plus.gif";

		closedCatIDs.push(catID);
		setCookieValue("closedCatIDs",closedCatIDs.join("."));
	}
}

function initializeClosedCatIDs() {
	var cookieValue;

	if(cookieValue = getCookieValue("closedCatIDs"))
		closedCatIDs = cookieValue.split(".");
}

initializeClosedCatIDs();

{/literal}

/* ]]> */</script>
{if $newsData != FALSE}
 <table class="TableStd" width="100%">
 <tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('latest_news')}</span></td></tr>
 <tr><td class="CellStd"><span class="FontNorm"><b>{$news_data.news_title}</b><br/><br/>{$news_data.news_text}</span><br/><br/><span class="FontSmall">{$news_comments_link}</span></td></tr>
 </table>
 <br/>
{/if}
<table class="TableStd" width="100%">
<thead>
<tr>
 <td class="CellTitle">&nbsp;</td>
 <td class="CellTitle" align="center"><span class="FontTitleSmall">{$modules.Language->getString('forum')}</span></td>
 <td class="CellTitle" align="center"><span class="FontTitleSmall">{$modules.Language->getString('topics')}</span></td>
 <td class="CellTitle" align="center"><span class="FontTitleSmall">{$modules.Language->getString('posts')}</span></td>
 <td class="CellTitle" align="center"><span class="FontTitleSmall">{$modules.Language->getString('last_post')}</span></td>
 <td class="CellTitle" align="center"><span class="FontTitleSmall">{$modules.Language->getString('moderators')}</span></td>
</tr>
</thead>
{foreach from=$catsData item=curCat}
 {if $curCat.catID != $catID}
  <tbody>
  <tr><td class="CellCat" colspan="6"><a href="javascript:switchCatStatus({$curCat.catID});"><img src="{$modules.Template->getTemplateDir()}/images/{if $curCat.catIsOpen == 1}minus{else}plus{/if}.gif" alt="" id="CatPic{$curCat.catID}"/></a>&nbsp;<span class="FontCat"><a class="FontCat" href="{$smarty.const.INDEXFILE}?catID={$curCat.catID}&amp;{$smarty.const.MYSID}">{$curCat.catName}</a></span></td></tr>
  </tbody>
 {/if}
 <tbody id="CatForums{$curCat.catID}" style="{if $curCat.catIsOpen == 1}{else}display:none;{/if}">
 {foreach from=$forumsData item=curForum}
  {if $curForum.catID == $curCat.catID && ($curForum.forumIsAccessible == 1 || $modules.Config->getValue('hide_not_accessible_forums') == 0)}
  <tr class="RowToHighlight" onmouseover="setRowCellsClass(this,'CellHighlight');" onmouseout="restoreRowCellsClass(this);">
   <td class="CellAlt" align="center"><img src="{$modules.Template->getTD()}/images/forum_{if $curForum._newPostsAvailable == 1}on{else}off{/if}.gif" alt=""/></td>
   <td class="CellStd" width="50%">
    <table border="0" cellspacing="0" cellpadding="0">
     <tr><td><span class="ForumLink"><a class="ForumLink" href="{$smarty.const.INDEXFILE}?action=ViewForum&amp;forumID={$curForum.forumID}&amp;{$smarty.const.MYSID}">{$curForum.forumName}</a></span></td></tr>
     <tr><td><span class="FontSmall">{$curForum.forumDescription}</span></td></tr>
    </table>
   </td>
   <td class="CellAlt" align="center"><span class="FontSmall">{$curForum.forumTopicsCounter}</span></td>
   <td class="CellAlt" align="center"><span class="FontSmall">{$curForum.forumPostsCounter}</span></td>
   <td class="CellStd">
    <table border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td align="center">{$curForum.forumLastPostPic}</td>
     <td align="left" style="padding-left:4px;"><span class="FontSmall">{$curForum.forumLastPostText}</span></td>
    </tr>
    </table>
   </td>
   <td class="CellAlt" align="center"><span class="FontSmall">{$curForum.forumMods}</span></td>
  </tr>
  {/if}
 {/foreach}
 </tbody>
{/foreach}
</table>
<br/>
{if !is_null($latestPostsData)}
	<table class="TableStd" width="100%">
		<tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('latest_posts')}</span></td></tr>
		{foreach from=$latestPostsData item=curPost}
			<tr><td class="CellStd"><span class="FontSmall">{$curPost}</span></td></tr>
		{/foreach}
	</table>
	<br/>
{/if}
{if $boardStatsData != FALSE}
 <table class="TableStd" width="100%">
 <tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('board_statistics')}</span></td></tr>
 <tr><td class="CellStd"><span class="FontSmall">{$boardStatsData.text}</span></td></tr>
 </table>
 <br/>
{/if}
{if $wioData != FALSE}
 <table class="TableStd" width="100%">
 <tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('who_is_online')}</span></td></tr>
 <tr><td class="CellStd"><span class="FontSmall">{$wioData.text}</span><hr /><span class="FontSmall">{$wioData.members}</span></td></tr>
 </table>
 <br/>
{/if}