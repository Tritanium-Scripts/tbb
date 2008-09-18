<div style="overflow:auto; width:190px; max-height:170px; padding:0px;">
	{foreach from=$smiliesData item=curSmiley name=smiliesLoop}
		<a href="javascript:insert(' {$curSmiley.smileySynonym} ','')"><img src="{$curSmiley.smileyFileName}" alt="{$curSmiley.smileySynonym}"/></a>
		{*if $smarty.foreach.smiliesLoop.iteration % 7 == 0 && $smarty.foreach.smiliesLoop.iteration != $smarty.foreach.smiliesLoop.total}<br/>{/if*}
	{/foreach}
</div>