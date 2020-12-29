<!-- Smilies -->
<script src="{$modules.Template->getTplDir()}scripts/bbcode.js" type="text/javascript"></script>
<script type="text/javascript">activeBox = '{$targetBoxID}';</script>
<div style="max-height:170px; overflow:auto; padding:0; width:100%;">
{if $modules.Auth->isAdmin() || $isMod}{foreach Main::getModule('BBCode')->getAdminSmilies() as $curSynonym => $curSmiley} <a href="javascript:setTag(' {$curSynonym}', '');">{$curSmiley}</a>{if $curSmiley@last}<hr />{/if}{/foreach}{/if}
{foreach Main::getModule('BBCode')->getSmilies() as $curSynonym => $curSmiley} <a href="javascript:setTag(' {$curSynonym}', '');">{$curSmiley}</a>{/foreach}
</div>