<script>
	IndexFile = "{$IndexFile}";
	MySID = "{$MySID}";
{literal}
	function toggleFastEdit(PostID) {
		if(document.getElementById("Post"+PostID+"Text").style.display == "none") {
			document.getElementById("Post"+PostID+"Text").style.display = "";
			document.getElementById("Post"+PostID+"EditBox").style.display = "none"
		} else {
			document.getElementById("Post"+PostID+"Text").style.display = "none";
			document.getElementById("Post"+PostID+"EditBox").style.display = ""
		}
	}

	function ajaxUpdatePost(PostID) {
		var AjaxConnection = ajaxGetInstance("ajaxUpdatePostHandle");
		AjaxConnection.open("GET", IndexFile+"?Action=Ajax&Mode=EditPost&PostID="+PostID+"&PostText="+encodeURIComponent(document.getElementsByName('PostData'+PostID)[0].value)+"&"+MySID, true);
		AjaxConnection.send(null);
	}

	function ajaxUpdatePostHandle(AjaxConnection) {
		if(AjaxConnection.readyState == 4) {
			if(ajaxGetStatus(AjaxConnection.responseXML) != 'SUCC') {
				alert(ajaxGetValue(AjaxConnection.responseXML,'Error'));
			} else {
				document.getElementById("Post"+ajaxGetValue(AjaxConnection.responseXML,'PostID')+"Text").innerHTML = ajaxGetValue(AjaxConnection.responseXML,'PostTextHTMLReady');
				document.getElementById("Post"+ajaxGetValue(AjaxConnection.responseXML,'PostID')+"Text").style.display = "";
				document.getElementById("Post"+ajaxGetValue(AjaxConnection.responseXML,'PostID')+"EditBox").style.display = "none";
			}
			delete AjaxConnection;
		}
	}
{/literal}
</script>
<table class="TableNavbar" width="100%">
<tr><td class="CellNavbarBig">
 <table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <td><span class="FontNavbar">{$PageListing}</span></td>
  <td align="right"><span class="FontNavbar"><a href="{$IndexFile}?Action=Posting&amp;Mode=Reply&amp;TopicID={$TopicID}&amp;{$MySID}"><img src="{$Modules.Template->getTD()}/images/buttons/{$Modules.Language->getLC()}/AddReply.png" class="ImageButton" border="0" alt="{$Modules.Language->getString('Post_new_reply')}"/></a><a href="{$IndexFile}?Action=Posting&amp;Mode=Topic&amp;ForumID={$ForumID}&amp;{$MySID}"><img src="{$Modules.Template->getTD()}/images/buttons/{$Modules.Language->getLC()}/AddTopic.png" class="ImageButton" border="0" alt="{$Modules.Language->getString('Post_new_topic')}"/></a></span></td>
 </tr>
 </table>
</td></tr>
</table>
<br/>
{$poll_box}
<table class="TableStd" width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
 <td class="CellTitle" align="left" width="15%"><span class="FontTitleSmall">{$Modules.Language->getString('Author')}</span></td>
 <td class="CellTitle" align="left" width="85%"><span class="FontTitleSmall">{$Modules.Language->getString('Topic')}: {$TopicData.TopicTitle}</span></td>
</tr>
{foreach from=$PostsData item=curPost}
 <tr>
  <td class="CellAlt" width="15%" valign="top" rowspan="3"><span class="FontNorm"><b>{$curPost._PostPosterNick}</b></span><br/><span class="FontSmall">{$curPost._PostPosterRankText}<br/>{$curPost._PostPosterRankPic}<br/>{$curPost._PostPosterIDText}<br/><br/>{$curPost._PostPosterAvatar}<br/><br/></span></td>
  <td class="CellAlt" width="85%" valign="middle">
   <table border="0" cellspacing="0" cellpadding="0" width="100%">
   <tr>
    <td>{if $curPost.PostSmileyFileName != ''}<span style="margin-right:4px;"><img src="{$curPost.PostSmileyFileName}" border="0" alt=""/></span>{/if}<span class="FontSmall"><a id="Post{$curPost.PostID}" name="Post{$curPost.PostID}"></a><b>{$curPost.PostTitle}</b></span></td>
    <td align="right">
     <table border="0" cellpadding="0" cellspacing="0">
      <tr>
       {if $curPost.Show.DeleteButton}<td><a href="{$IndexFile}?Action=DeletePost&amp;PostID={$curPost.PostID}&amp;{$MySID}"><img src="delete.png" class="ImageButton" alt="" border="0"/></a></td>{/if}
       {if $curPost.Show.EditButton}<td><a href="javascript:toggleFastEdit('{$curPost.PostID}');"><img src="test.png" alt="" class="ImageButton" border="0"/></a></td>{/if}
       {if $curPost.Show.EditButton}<td><a href="{$IndexFile}?Action=Posting&amp;Mode=Edit&amp;PostID={$curPost.PostID}&amp;{$MySID}"><img src="test.png" class="ImageButton" alt="" border="0"/></a></td>{/if}
       {if $curPost.PostPosterHideEmail != 1 && $curPost.PostPosterEmail != ''}<td><a href="mailto:{$curPost.PostPosterEmail}"><img src="email.png" class="ImageButton" alt="{$curPost.PostPosterEmail}" border="0"/></a>{else}<td>{if $curPost.PostPosterReceiveEmails == 1}<a href="{$IndexFile}?Action=ViewProfile&amp;ProfileID={$curPost.UserID}&amp;mode=SendMail&amp;{$MySID}"><img src="{$Modules.Template->getTemplateDir()}/images/button_user_email.gif" alt="{$Modules.Language->getString('Send_email')}" border="0"/></a></td>{/if}{/if}
       <td><a href="{$IndexFile}?Action=Posting&amp;Mode=Reply&amp;TopicID={$TopicID}&amp;Quote={$curPost.PostID}&amp;{$MySID}"><img src="quote.png" class="ImageButton" alt="" border="0"/></a></td>
      </tr>
     </table>
    </td>
   </tr>
   </table>
  </td>
 </tr>
 <tr>
  <td class="CellStd">
   <div id="Post{$curPost.PostID}Text" class="FontNorm"{if $curPost.Show.EditButton} ondblclick="toggleFastEdit('{$curPost.PostID}');"{/if}>{$curPost._PostText}</div>
   <div id="Post{$curPost.PostID}EditBox" style="display:none;">
    <table class="TableStd" cellpadding="0"width="100%">
    <tr><td class="CellCat"><span class="FontCat">Fast Edit</span></td></tr>
    <tr><td class="CellNone" align="center"><textarea class="FormTextArea" rows="14" style="width:99%;" name="PostData{$curPost.PostID}">{$curPost._PostEditBoxText}</textarea></td></tr>
    <tr><td class="CellButtons" align="center"><input class="FormBButton" type="button" value="{$Modules.Language->getString('Edit_post')}" onclick="ajaxUpdatePost({$curPost.PostID});"/></td></tr>
    </table>
   </div>
   {if $curPost._PostSignature != ''}<br/><span class="signature">-----------<br/>{$curPost._PostSignature}</span>{/if}
   {if $curPost._PostEditedText != ''}<br/><br/><span class="FontSmall">{$curPost._PostEditedText}</span>{/if}
  </td>
 </tr>
 <tr><td class="CellStd" width="85%"><span class="FontSmall">{$Modules.Language->getString('Posted')}: {$curPost._PostDateTime}</span></td></tr>
 {/foreach}
</table>
<br/>
<table class="TableNavbar" width="100%">
<tr><td class="CellNavbarBig">
 <table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <td><span class="FontNavbar">{$PageListing}</span></td>
  <td align="right"><span class="FontNavbar"><a href="{$IndexFile}?Action=Posting&amp;Mode=Reply&amp;TopicID={$TopicID}&amp;{$MySID}"><img src="{$Modules.Template->getTD()}/images/buttons/{$Modules.Language->getLC()}/AddReply.png" class="ImageButton" border="0" alt="{$Modules.Language->getString('Post_new_reply')}"/></a><a href="{$IndexFile}?Action=Posting&amp;Mode=Topic&amp;ForumID={$ForumID}&amp;{$MySID}"><img src="{$Modules.Template->getTD()}/images/buttons/{$Modules.Language->getLC()}/AddTopic.png" class="ImageButton" border="0" alt="{$Modules.Language->getString('Post_new_topic')}"/></a></span></td>
 </tr>
 </table>
</td></tr>
</table>
{if $ModTools != ''}
 <br/>
 <table class="TableNavbar" width="100%">
 <tr><td class="CellNavbar" align="center"><span class="FontNavbar">{$ModTools}</span></td></tr>
 </table>
{/if}
