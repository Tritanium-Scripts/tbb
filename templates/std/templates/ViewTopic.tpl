<script>
	indexFile = "{$indexFile}";
	mySID = "{$mySID}";
{literal}
	function toggleFastEdit(postID) {
		if(document.getElementById("post"+postID+"Text").style.display == "none") {
			document.getElementById("post"+postID+"Text").style.display = "";
			document.getElementById("post"+postID+"EditBox").style.display = "none"
		} else {
			document.getElementById("post"+postID+"Text").style.display = "none";
			document.getElementById("post"+postID+"EditBox").style.display = ""
		}
	}

	function ajaxUpdatePost(postID) {
		var ajaxConnection = ajaxGetInstance("ajaxUpdatePostHandle");
		ajaxConnection.open("GET", indexFile+"?action=Ajax&mode=EditPost&postID="+postID+"&postText="+encodeURIComponent(document.getElementsByName('postData'+postID)[0].value)+"&"+mySID, true);
		ajaxConnection.send(null);
	}

	function ajaxUpdatePostHandle(ajaxConnection) {
		if(ajaxConnection.readyState == 4) {
			if(ajaxGetStatus(ajaxConnection.responseXML) != 'SUCC') {
				alert(ajaxGetValue(ajaxConnection.responseXML,'error'));
			} else {
				document.getElementById("post"+ajaxGetValue(ajaxConnection.responseXML,'postID')+"Text").innerHTML = ajaxGetValue(ajaxConnection.responseXML,'postTextHTMLReady');
				document.getElementById("post"+ajaxGetValue(ajaxConnection.responseXML,'postID')+"Text").style.display = "";
				document.getElementById("post"+ajaxGetValue(ajaxConnection.responseXML,'postID')+"EditBox").style.display = "none";
			}
			delete ajaxConnection;
		}
	}
{/literal}
</script>
<table class="TableNavbar" width="100%">
<tr><td class="CellNavbarBig">
 <table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <td><span class="FontNavbar">{$pageListing}</span></td>
  <td align="right"><span class="FontNavbar"><a href="{$indexFile}?action=Posting&amp;mode=Reply&amp;topicID={$topicID}&amp;{$mySID}"><img src="{$modules.Template->getTD()}/images/buttons/{$modules.Language->getLC()}/AddReply.png" class="ImageButton" alt="{$modules.Language->getString('Post_new_reply')}"/></a><a href="{$indexFile}?action=Posting&amp;mode=Topic&amp;forumID={$forumID}&amp;{$mySID}"><img src="{$modules.Template->getTD()}/images/buttons/{$modules.Language->getLC()}/AddTopic.png" class="ImageButton" alt="{$modules.Language->getString('Post_new_topic')}"/></a></span></td>
 </tr>
 </table>
</td></tr>
</table>
<br/>
{if $topicData.topicHasPoll == 1}
<form method="post" action="{$indexFile}?action=Vote&amp;pollID={$pollData.pollID}&amp;{$mySID}">
<table class="TableStd" width="100%">
 <tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('Poll')}: {$pollData.pollTitle}</td></tr>
  {if $userAlreadyVoted || $modules.Auth->isloggedIn() != 1 && $pollData.pollGuestsVote != 1 || $pollHasEnded}
   {if $modules.Auth->isloggedIn() != 1 && $pollData.pollGuestsVote != 1 && !$userAlreadyVoted && !$pollHasEnded}
    <tr><td class="CellMessageBox"><span class="FontNorm">{$modules.Language->getString('Must_be_logged_in_vote')}</span></td></tr>
   {elseif $modules.Auth->isloggedIn() != 1 && $pollData.pollGuestsViewResults == 0}
    <tr><td class="CellMessageBox"><span class="FontNorm">{$modules.Language->getString('Must_be_logged_in_view_results')}</span></td></tr>
   {elseif $pollData.pollShowResultsAfterEnd && !$pollHasEnded}
    <tr><td class="CellMessageBox"><span class="FontNorm">{$modules.Language->getString('Results_after_end_of_poll')}</span></td></tr>
   {else}
    <tr><td class="CellStd">
    <table border="0" cellpadding="2" cellspacing="0">
    {foreach from=$pollOptionsData item=curOption}
     <tr>
      <td style="padding:3px;"><span class="FontNorm">{$curOption.optionTitle}</span></td>
      <td style="padding:3px;"><span class="FontNorm"><img src="{$modules.Template->getTD()}/images/poll.gif" alt="" width="{$curOption._optionPercent}" height="15"/></span></td>
      <td style="padding:3px;"><span class="FontSmall">({$curOption._optionPercent} %, {$curOption._optionVotesCounterText})</span></td>
     </tr>
    {/foreach}
    </table>
    </tr></td>
   {/if}
  {else}
   <tr><td class="CellStd">
   <table border="0" cellpadding="2" cellspacing="0">
   {foreach from=$pollOptionsData item=curOption}
    <tr><td style="padding:3px;"><span class="FontNorm"><label><input type="radio" name="p[optionID]" value="{$curOption.optionID}"/>&nbsp;{$curOption.optionTitle}</label></span></td></tr>
   {/foreach}
   </table>
   </td></tr>
   <tr><td class="CellButtons"><input type="submit" class="FormBButton" value="{$modules.Language->getString('Vote')}"/></td></tr>
  {/if}
 </table>
 </form>
 <br/>
{/if}
<table class="TableStd" width="100%">
<tr>
 <td class="CellTitle" align="left" width="15%"><span class="FontTitleSmall">{$modules.Language->getString('Author')}</span></td>
 <td class="CellTitle" align="left" width="85%"><span class="FontTitleSmall">{$modules.Language->getString('Topic')}: {$topicData.topicTitle}</span></td>
</tr>
{foreach from=$postsData item=curPost}
 <tr>
  <td class="CellAlt" width="15%" valign="top" rowspan="3"><span class="FontNorm"><b>{$curPost._postPosterNick}</b></span><br/><span class="FontSmall">{$curPost._postPosterRankText}<br/>{$curPost._postPosterRankPic}<br/>{$curPost._postPosterIDText}<br/><br/>{$curPost._postPosterAvatar}<br/><br/></span></td>
  <td class="CellAlt" width="85%" valign="middle">
   <table border="0" cellspacing="0" cellpadding="0" width="100%">
   <tr>
    <td>{if $curPost.postSmileyFileName != ''}<span style="margin-right:4px;"><img src="{$curPost.postSmileyFileName}" alt=""/></span>{/if}<span class="FontSmall"><a id="post{$curPost.postID}" name="post{$curPost.postID}"></a><b>{$curPost.postTitle}</b></span></td>
    <td align="right">
     <table border="0" cellpadding="0" cellspacing="0">
      <tr>
       {if $curPost.show.deleteButton}<td><a href="{$indexFile}?action=Posting&amp;mode=Delete&amp;postID={$curPost.postID}&amp;returnPage={$page}&amp;{$mySID}"><img src="{$modules.Template->getTD()}/images/buttons/de/delete.png" class="ImageButton" alt=""/></a></td>{/if}
       {if $curPost.show.editButton}<td><a href="javascript:toggleFastEdit('{$curPost.postID}');"><img src="{$modules.Template->getTD()}/images/buttons/de/test.png" alt="" class="ImageButton"/></a></td>{/if}
       {if $curPost.show.editButton}<td><a href="{$indexFile}?action=Posting&amp;mode=Edit&amp;postID={$curPost.postID}&amp;{$mySID}"><img src="{$modules.Template->getTD()}/images/buttons/de/test.png" class="ImageButton" alt=""/></a></td>{/if}
       {if $curPost.postPosterHideEmailAddress != 1 && $curPost.postPosterEmailAddress != ''}<td><a href="mailto:{$curPost.postPosterEmailAddress}"><img src="{$modules.Template->getTD()}/images/buttons/de/email.png" class="ImageButton" alt="{$curPost.postPosterEmailAddress}"/></a>{else}<td>{if $curPost.postPosterReceiveEmails == 1}<a href="{$indexFile}?action=ViewProfile&amp;profileID={$curPost.posterID}&amp;mode=SendMail&amp;{$mySID}"><img src="{$modules.Template->getTemplateDir()}/images/email.png" alt="{$modules.Language->getString('Send_email')}"/></a></td>{/if}{/if}
       <td><a href="{$indexFile}?action=Posting&amp;mode=Reply&amp;topicID={$topicID}&amp;postIDQuote={$curPost.postID}&amp;{$mySID}"><img src="{$modules.Template->getTD()}/images/buttons/de/quote.png" class="ImageButton" alt=""/></a></td>
      </tr>
     </table>
    </td>
   </tr>
   </table>
  </td>
 </tr>
 <tr>
  <td class="CellStd">
   <div id="post{$curPost.postID}Text" class="FontNorm"{if $curPost.show.editButton} ondblclick="toggleFastEdit('{$curPost.postID}');"{/if}>{$curPost._postText}</div>
   <div id="post{$curPost.postID}EditBox" style="display:none;">
    <table class="TableStd" cellpadding="0"width="100%">
    <tr><td class="CellCat"><span class="FontCat">Fast Edit</span></td></tr>
    <tr><td class="CellNone" align="center"><textarea class="FormTextArea" rows="14" style="width:99%;" name="postData{$curPost.postID}">{$curPost._postEditBoxText}</textarea></td></tr>
    <tr><td class="CellButtons" align="center"><input class="FormBButton" type="button" value="{$modules.Language->getString('Edit_post')}" onclick="ajaxUpdatePost({$curPost.postID});"/></td></tr>
    </table>
   </div>
   {if $curPost._postSignature != ''}<br/><span class="signature">-----------<br/>{$curPost._postSignature}</span>{/if}
   {if $curPost._postEditedText != ''}<br/><br/><span class="FontSmall">{$curPost._postEditedText}</span>{/if}
  </td>
 </tr>
 <tr><td class="CellStd" width="85%"><span class="FontSmall">{$modules.Language->getString('Posted')}: {$curPost._postDateTime}</span></td></tr>
 {/foreach}
</table>
<br/>
<table class="TableNavbar" width="100%">
<tr><td class="CellNavbarBig">
 <table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <td><span class="FontNavbar">{$pageListing}</span></td>
  <td align="right"><span class="FontNavbar"><a href="{$indexFile}?action=Posting&amp;mode=Reply&amp;topicID={$topicID}&amp;{$mySID}"><img src="{$modules.Template->getTD()}/images/buttons/{$modules.Language->getLC()}/AddReply.png" class="ImageButton" alt="{$modules.Language->getString('Post_new_reply')}"/></a><a href="{$indexFile}?action=Posting&amp;mode=Topic&amp;forumID={$forumID}&amp;{$mySID}"><img src="{$modules.Template->getTD()}/images/buttons/{$modules.Language->getLC()}/AddTopic.png" class="ImageButton" alt="{$modules.Language->getString('Post_new_topic')}"/></a></span></td>
 </tr>
 </table>
</td></tr>
</table>
{if $modTools != ''}
 <br/>
 <table class="TableNavbar" width="100%">
 <tr><td class="CellNavbar" align="center"><span class="FontNavbar">{$modTools}</span></td></tr>
 </table>
{/if}
