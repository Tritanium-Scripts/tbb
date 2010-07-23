<!-- Smilies -->
<script src="{$modules.Template->getTplDir()}scripts/bbcode.js" type="text/javascript"></script>
<script type="text/javascript">activeBox = '{$targetBoxID}';</script>
<div style="max-height:170px; overflow:auto; padding:0; width:190px;">
{foreach $smilies as $curSynonym => $curSmiley} <a href="javascript:setTag(' {$curSynonym}', '');">{$curSmiley}</a>{/foreach}
</div>