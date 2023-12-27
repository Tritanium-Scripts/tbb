<!-- Smilies -->
<script src="{Template::getInstance()->getTplDir()}scripts/bbcode.js" type="text/javascript"></script>
<script type="text/javascript">activeBox = '{$targetBoxID}';</script>
<div style="max-height:170px; overflow:auto; padding:0; width:100%;">
{if Auth::getInstance()->isAdmin() || $isMod}{foreach BBCode::getInstance()->getAdminSmilies() as $curSynonym => $curSmiley} <a href="javascript:setTag(' {$curSynonym}', '');">{$curSmiley}</a>{if $curSmiley@last}<hr />{/if}{/foreach}{/if}
{foreach BBCode::getInstance()->getSmilies() as $curSynonym => $curSmiley} <a href="javascript:setTag(' {$curSynonym}', '');">{$curSmiley}</a>{/foreach}
</div>