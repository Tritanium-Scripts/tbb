<!-- TopicSmilies -->
{foreach Functions::getTSmilies() as $curTSmiley}<input type="radio" name="tsmilie" value="{$curTSmiley[0]}"{if $curTSmiley[0] == $checked}checked="checked"{/if} />&nbsp;<img src="{$curTSmiley[1]}" alt="" />&nbsp;&nbsp; {if $curTSmiley@iteration % 7 == 0}<br />{/if}{/foreach}