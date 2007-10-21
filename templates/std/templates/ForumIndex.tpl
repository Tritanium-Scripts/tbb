<script type="text/javascript"><![CDATA[

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

]]></script>
{if $newsData != FALSE}
 <table class="TableStd" width="100%">
 <tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('Latest_news')}</span></td></tr>
 <tr><td class="CellStd"><span class="FontNorm"><b>{$news_data.news_title}</b><br/><br/>{$news_data.news_text}</span><br/><br/><span class="FontSmall">{$news_comments_link}</span></td></tr>
 </table>
 <br/>
{/if}
<table class="TableStd" width="100%">
<thead>
<tr>
 <td class="CellTitle">&nbsp;</td>
 <td class="CellTitle" align="center"><span class="FontTitleSmall">{$modules.Language->getString('Forum')}</span></td>
 <td class="CellTitle" align="center"><span class="FontTitleSmall">{$modules.Language->getString('Topics')}</span></td>
 <td class="CellTitle" align="center"><span class="FontTitleSmall">{$modules.Language->getString('Posts')}</span></td>
 <td class="CellTitle" align="center"><span class="FontTitleSmall">{$modules.Language->getString('Last_post')}</span></td>
 <td class="CellTitle" align="center"><span class="FontTitleSmall">{$modules.Language->getString('Moderators')}</span></td>
</tr>
</thead>
{foreach from=$catsData item=curCat}
 {if $curCat.catID != $catID}
  <tbody>
  <tr><td class="CellCat" colspan="6"><a href="javascript:switchCatStatus({$curCat.catID});"><img border="0" src="{$modules.Template->getTemplateDir()}/images/{if $curCat.catIsOpen == 1}minus{else}plus{/if}.gif" id="CatPic{$curCat.catID}"/></a>&nbsp;<span class="FontCat"><a class="FontCat" href="{$indexFile}?catID={$curCat.catID}&amp;{$mySID}">{$curCat.catName}</a></span></td></tr>
  </tbody>
 {/if}
 <tbody id="CatForums{$curCat.catID}" style="{if $curCat.catIsOpen == 1}{else}display:none;{/if}">
 {foreach from=$forumsData item=curForum}
  {if $curForum.catID == $curCat.catID}
  <tr class="RowToHighlight" onmouseover="setRowCellsClass(this,'CellHighlight');" onmouseout="restoreRowCellsClass(this);">
   <td class="CellAlt" align="center"><img src="{$modules.Template->getTD()}/images/forum_{if $curForum._newPostsAvailable == 1}on{else}off{/if}.gif" alt=""/></td>
   <td class="CellStd" width="50%">
    <table border="0" cellspacing="0" cellpadding="0">
     <tr><td><span class="ForumLink"><a class="ForumLink" href="{$indexFile}?action=ViewForum&amp;forumID={$curForum.forumID}&amp;{$mySID}">{$curForum.forumName}</a></span></td></tr>
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
{if $boardStatsData != FALSE}
 <table class="TableStd" width="100%">
 <tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('Board_statistics')}</span></td></tr>
 <tr><td class="CellStd"><span class="FontSmall">{$boardStatsData.text}</span></td></tr>
 </table>
 <br/>
{/if}
{if $wioData != FALSE}
 <table class="TableStd" width="100%">
 <tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('Who_is_online')}</span></td></tr>
 <tr><td class="CellStd"><span class="FontSmall">{$wioData.text}</span><hr /><span class="FontSmall">{$wioData.members}</span></td></tr>
 </table>
 <br/>
{/if}
<!--<template:latestpostsbox>
 <table class="TableStd" width="100%">
 <tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('Latest_posts')}</span></td></tr>
 <template:postrow>
  <tr><td class="CellStd"><span class="FontSmall">{$akt_latest_post_text}</span></td></tr>
 </template>
 </table>
 <br/>
</template>
-->