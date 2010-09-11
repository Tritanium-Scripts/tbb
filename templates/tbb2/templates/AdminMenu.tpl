<!-- AdminMenu -->
<table border="0" cellpadding="0" cellspacing="0" style="background-color:#DCDCDC; width:100%;">
 <tr>
  <td style="vertical-align:top; width:200px;">
   <table class="tableStd" style="width:100%;">
    <tr><th class="cellTitle"><span class="fontTitle">{$smarty.config.langNavigation}</span></th></tr>
    <tr><td class="cellNav{if $subAction == 'AdminIndex'}Active{/if}"><a class="fontNav" href="{$smarty.const.INDEXFILE}?faction=adminpanel{$smarty.const.SID_AMPER}">{$modules.Language->getString('overview', 'Help')}</a></td></tr>
    <tr><td class="cellNav"><a class="fontNav" href="{$smarty.const.INDEXFILE}?faction=ad_forum{$smarty.const.SID_AMPER}">{$modules.Language->getString('forums_categories')}</a></td></tr>
    <tr><td class="cellNav"><a class="fontNav" href="{$smarty.const.INDEXFILE}?faction=ad_user{$smarty.const.SID_AMPER}">{$modules.Language->getString('members')}</a></td></tr>
    <tr><td class="cellNav"><a class="fontNav" href="{$smarty.const.INDEXFILE}?faction=ad_groups{$smarty.const.SID_AMPER}">{$modules.Language->getString('groups')}</a></td></tr>
    <tr><td class="cellNav"><a class="fontNav" href="{$smarty.const.INDEXFILE}?faction=ad_rank{$smarty.const.SID_AMPER}">{$modules.Language->getString('user_ranks')}</a></td></tr>
    <tr><td class="cellNav"><a class="fontNav" href="{$smarty.const.INDEXFILE}?faction=ad_censor{$smarty.const.SID_AMPER}">{$modules.Language->getString('censorships')}</a></td></tr>
    <tr><td class="cellNav"><a class="fontNav" href="{$smarty.const.INDEXFILE}?faction=ad_ip{$smarty.const.SID_AMPER}">{$modules.Language->getString('ip_blocks')}</a></td></tr>
    <tr><td class="cellNav"><a class="fontNav" href="{$smarty.const.INDEXFILE}?faction=ad_smilies{$smarty.const.SID_AMPER}">{$modules.Language->getString('smilies')}</a></td></tr>
    <tr><td class="cellNav"><a class="fontNav" href="{$smarty.const.INDEXFILE}?faction=ad_settings{$smarty.const.SID_AMPER}">{$modules.Language->getString('board_settings')}</a></td></tr>
    <tr><td class="cellNav"><a class="fontNav" href="{$smarty.const.INDEXFILE}?faction=adminTemplate{$smarty.const.SID_AMPER}">{$modules.Language->getString('templates')}</a></td></tr>
    <tr><td class="cellNav"><a class="fontNav" href="{$smarty.const.INDEXFILE}?faction=adminLogfile{$smarty.const.SID_AMPER}">{$modules.Language->getString('logfiles')}</a></td></tr>
    <tr><td class="cellNav"><a class="fontNav" href="{$smarty.const.INDEXFILE}?faction=ad_news{$smarty.const.SID_AMPER}">{$modules.Language->getString('forum_news')}</a></td></tr>
    <tr><td class="cellNav"><a class="fontNav" href="{$smarty.const.INDEXFILE}?faction=ad_newsletter{$smarty.const.SID_AMPER}">{$modules.Language->getString('newsletter')}</a></td></tr>
    <tr><td class="cellNav"><a class="fontNav" href="{$smarty.const.INDEXFILE}?faction=ad_emailist{$smarty.const.SID_AMPER}">{$modules.Language->getString('email_list')}</a></td></tr>
    <tr><td class="cellNav"><a class="fontNav" href="{$smarty.const.INDEXFILE}?faction=ad_killposts{$smarty.const.SID_AMPER}">{$modules.Language->getString('delete_old_topics')}</a></td></tr>
    <tr><td class="cellNavNone"><hr class="lineNav" /></td></tr>
   </table>
  </td>
  <td style="width:10px;">&nbsp;</td>
  <td style="vertical-align:top;">
