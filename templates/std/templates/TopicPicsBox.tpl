<table border="0" cellpadding="0" cellspacing="4">
 <tr>
{foreach from=$topicPicsData item=curTopicPic name=topicPicsLoop}
  <td><input type="radio" name="p[smileyID]" value="{$curTopicPic.smileyID}"/><img border="0" src="{$curTopicPic.smileyFileName}" alt=""/></td>
 {if $smarty.foreach.topicPicsLoop.iteration % 7 == 0 && $smarty.foreach.topicPicsLoop.iteration != $smarty.foreach.topicPicsLoop.total}</tr><tr>{/if}
{/foreach}
 </tr>
</table>