<!-- Smilies -->
<script type="text/javascript">activeBox = '{$targetBoxID}';</script>
<div style="max-height:170px; overflow:auto; padding:0; width:190px;">
{if $modules.Auth->isAdmin() || $isMod}{foreach Main::getModule('BBCode')->getAdminSmilies() as $curSynonym => $curSmiley} <a href="javascript:setTag(' {$curSynonym}', '');">{$curSmiley}</a>{if $curSmiley@last}<hr />{/if}{/foreach}{/if}
{foreach Main::getModule('BBCode')->getSmilies() as $curSynonym => $curSmiley} <a href="javascript:setTag(' {$curSynonym}', '');">{$curSmiley}</a>{/foreach}
</div>