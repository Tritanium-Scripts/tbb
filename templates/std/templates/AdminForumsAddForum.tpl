<form method="post" action="{$indexFile}?action=AdminForums&amp;mode=AddForum&amp;doit=1&amp;{$mySID}">
<table class="TableStd" cellspacing="0" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('Add_forum')}</span></td></tr>
{if $error != ''}<tr><td class="CellError" colspan="2"><span class="FontError">{$error}</span></td></tr>{/if}
<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('General_information')}</span></td></tr>
<tr>
 <td class="CellStd" width="15%"><span class="FontNorm">{$modules.Language->getString('Name')}:</span></td>
 <td class="CellAlt" width="85%"><input class="FormText" type="text" size="35" name="p[forumName]" value="{$p.forumName}"/></td>
</tr>
<tr>
 <td class="CellStd" width="15%"><span class="FontNorm">{$modules.Language->getString('Description')}:</span></td>
 <td class="CellAlt" width="85%"><input class="FormText" type="text" size="45" name="p[forumDescription]" value="{$p.forumDescription}"/></td>
</tr>
<tr>
 <td class="CellStd" width="15%"><span class="FontNorm">{$modules.Language->getString('Category')}:</span></td>
 <td class="CellAlt" width="85%"><select class="FormSelect" name="p[catID]">
 {foreach from=$catsData item=curCat}
  <option value="{$curCat.catID}"{if $curCat.catID == $p.catID} selected="selected"{/if}>{$curCat._catPrefix} {$curCat.catName}</option>
 {/foreach}
 </select></td>
</tr>
<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('General_rights')}</span></td></tr>
<tr><td colspan="2" class="CellStd"><span class="FontNorm">
 <label><input type="checkbox" value="1" name="c[authViewForumMembers]"{if $c.authViewForumMembers == 1} checked="checked"{/if}/> {$modules.Language->getString('Members_view_forum')}</label><br/>
 <label><input type="checkbox" value="1" name="c[authPostTopicMembers]"{if $c.authPostTopicMembers == 1} checked="checked"{/if}/> {$modules.Language->getString('Members_post_topic')}</label><br/>
 <label><input type="checkbox" value="1" name="c[authPostReplyMembers]"{if $c.authPostReplyMembers == 1} checked="checked"{/if}/> {$modules.Language->getString('Members_post_reply')}</label><br/>
 <label><input type="checkbox" value="1" name="c[authPostPollMembers]"{if $c.authPostPollMembers == 1} checked="checked"{/if}/> {$modules.Language->getString('Members_post_poll')}</label><br/>
 <label><input type="checkbox" value="1" name="c[authEditPostsMembers]"{if $c.authEditPostsMembers == 1} checked="checked"{/if}/> {$modules.Language->getString('Members_edit_posts')}</label><br/>
 <label><input type="checkbox" value="1" name="c[authViewForumGuests]"{if $c.authViewForumGuests == 1} checked="checked"{/if}/> {$modules.Language->getString('Guests_view_forum')}</label><br/>
 <label><input type="checkbox" value="1" name="c[authPostTopicGuests]"{if $c.authPostTopicGuests == 1} checked="checked"{/if}/> {$modules.Language->getString('Guests_post_topic')}</label><br/>
 <label><input type="checkbox" value="1" name="c[authPostReplyGuests]"{if $c.authPostReplyGuests == 1} checked="checked"{/if}/> {$modules.Language->getString('Guests_post_reply')}</label><br/>
 <label><input type="checkbox" value="1" name="c[authPostPollGuests]"{if $c.authPostPollGuests == 1} checked="checked"{/if}/> {$modules.Language->getString('Guests_post_poll')}</label><br/>
</span></td></tr>
<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('Other_options')}</span></td></tr>
<tr><td colspan="2" class="CellStd"><span class="FontNorm">
 <label><input type="checkbox" value="1" name="c[forumShowLatestPosts]"{if $c.forumShowLatestPosts == 1} checked="checked"{/if}/> {$modules.Language->getString('Show_latest_posts')}</label><br/>
 <label><input type="checkbox" value="1" name="c[forumIsModerated]"{if $c.forumIsModerated == 1} checked="checked"{/if}/> {$modules.Language->getString('Moderate_forum')}</label><br/>
 <label><input type="checkbox" value="1" name="c[forumEnableBBCode]"{if $c.forumEnableBBCode == 1} checked="checked"{/if}/> {$modules.Language->getString('Enable_bbcode')}</label><br/>
 <label><input type="checkbox" value="1" name="c[forumEnableSmilies]"{if $c.forumEnableSmilies == 1} checked="checked"{/if}/> {$modules.Language->getString('Enable_smilies')}</label><br/>
 <label><input type="checkbox" value="1" name="c[forumEnableHtmlCode]"{if $c.forumEnableHtmlCode == 1} checked="checked"{/if}/> {$modules.Language->getString('Enable_html_code')}</label><br/>
</span></td></tr>
<tr><td colspan="2" class="CellButtons" align="center"><input type="submit" class="FormBButton" value="{$modules.Language->getString('Add_forum')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('Reset')}"/></td></tr>
</table>
</form>