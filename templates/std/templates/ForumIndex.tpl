<script>

TemplateDir = "{$Modules.Template->getTemplateDir()}";
ClosedCatIDs = new Array();

{literal}
function switchCatStatus(CatID) {
	var i;

	if(document.getElementById("CatForums"+CatID).style.display == "none") {
		document.getElementById("CatForums"+CatID).style.display = "";
		document.getElementById("CatPic"+CatID).src = TemplateDir+"/images/minus.gif";

		for(i = 0; i < ClosedCatIDs.length; i++) {
			if(ClosedCatIDs[i] == CatID) {
				ClosedCatIDs.splice(i,1);
				setCookieValue("ClosedCatIDs",ClosedCatIDs.join("."));
				break;
			}
		}
	} else {
		document.getElementById("CatForums"+CatID).style.display= "none";
		document.getElementById("CatPic"+CatID).src = TemplateDir+"/images/plus.gif";

		ClosedCatIDs.push(CatID);
		setCookieValue("ClosedCatIDs",ClosedCatIDs.join("."));
	}
}

function initializeClosedCatIDs() {
	var CookieValue;

	if(CookieValue = getCookieValue("ClosedCatIDs"))
		ClosedCatIDs = CookieValue.split(".");
}

initializeClosedCatIDs();

{/literal}

</script>
{if $NewsData != FALSE}
 <table class="TableStd" width="100%">
 <tr><td class="CellTitle"><span class="FontTitle">{$Modules.Language->getString('Latest_news')}</span></td></tr>
 <tr><td class="CellStd"><span class="FontNorm"><b>{$news_data.news_title}</b><br/><br/>{$news_data.news_text}</span><br/><br/><span class="FontSmall">{$news_comments_link}</span></td></tr>
 </table>
 <br/>
{/if}
<table class="TableStd" width="100%">
<thead>
<tr>
 <td class="CellTitle">&nbsp;</td>
 <td class="CellTitle" align="center"><span class="FontTitleSmall">{$Modules.Language->getString('Forum')}</span></td>
 <td class="CellTitle" align="center"><span class="FontTitleSmall">{$Modules.Language->getString('Topics')}</span></td>
 <td class="CellTitle" align="center"><span class="FontTitleSmall">{$Modules.Language->getString('Posts')}</span></td>
 <td class="CellTitle" align="center"><span class="FontTitleSmall">{$Modules.Language->getString('Last_post')}</span></td>
 <td class="CellTitle" align="center"><span class="FontTitleSmall">{$Modules.Language->getString('Moderators')}</span></td>
</tr>
</thead>
{foreach from=$CatsData item=curCat}
 {if $curCat.CatID != $CatID}
  <tbody>
  <tr><td class="CellCat" colspan="6"><a href="javascript:switchCatStatus({$curCat.CatID});"><img border="0" src="{$Modules.Template->getTemplateDir()}/images/{if $curCat.CatIsOpen == 1}minus{else}plus{/if}.gif" id="CatPic{$curCat.CatID}"/></a>&nbsp;<span class="FontCat"><a class="FontCat" href="{$IndexFile}?CatID={$curCat.CatID}&amp;{$MySID}">{$curCat.CatName}</a></span></td></tr>
  </tbody>
 {/if}
 <tbody id="CatForums{$curCat.CatID}" style="{if $curCat.CatIsOpen == 1}{else}display:none;{/if}">
 {foreach from=$ForumsData item=curForum}
  {if $curForum.CatID == $curCat.CatID}
  <tr>
   <td class="CellAlt" align="center">{$akt_new_post_status}</td>
   <td class="CellStd" width="50%">
    <table border="0" cellspacing="0" cellpadding="0">
     <tr><td><span class="ForumLink"><a class="ForumLink" href="{$IndexFile}?Action=ViewForum&amp;ForumID={$curForum.ForumID}&amp;{$MySID}">{$curForum.ForumName}</a></span></td></tr>
     <tr><td><span class="FontSmall">{$curForum.ForumDescription}</span></td></tr>
    </table>
   </td>
   <td class="CellAlt" align="center"><span class="FontSmall">{$curForum.ForumTopicsCounter}</span></td>
   <td class="CellAlt" align="center"><span class="FontSmall">{$curForum.ForumPostsCounter}</span></td>
   <td class="CellStd">
    <table border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td align="center">{$curForum.ForumLastPostPic}</td>
     <td align="left"><span class="FontSmall">{$curForum.ForumLastPostText}</span></td>
    </tr>
    </table>
   </td>
   <td class="CellAlt" align="center"><span class="FontSmall">{$curForum.ForumMods}</span></td>
  </tr>
  {/if}
 {/foreach}
 </tbody>
{/foreach}
</table>
<br/>
{if $BoardStatsData != FALSE}
 <table class="TableStd" width="100%">
 <tr><td class="CellTitle"><span class="FontTitle">{$Modules.Language->getString('Board_statistics')}</span></td></tr>
 <tr><td class="CellStd"><span class="FontSmall">{$BoardStatsData.Text}</span></td></tr>
 </table>
 <br/>
{/if}
{if $WIOData != FALSE}
 <table class="TableStd" width="100%">
 <tr><td class="CellTitle"><span class="FontTitle">{$Modules.Language->getString('Who_is_online')}</span></td></tr>
 <tr><td class="CellStd"><span class="FontSmall">{$WIOData.Text}</span><hr /><span class="FontSmall">{$WIOData.Members}</span></td></tr>
 </table>
 <br/>
{/if}
<!--<template:latestpostsbox>
 <table class="TableStd" width="100%">
 <tr><td class="CellTitle"><span class="FontTitle">{$Modules.Language->getString('Latest_posts')}</span></td></tr>
 <template:postrow>
  <tr><td class="CellStd"><span class="FontSmall">{$akt_latest_post_text}</span></td></tr>
 </template>
 </table>
 <br/>
</template>
-->