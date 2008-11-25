<table class="TableStd" width="100%">
<tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('user_administration')}</span></td></tr>
<tr><td class="CellAlt" style="background-color:#DCDCDC;">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
 <td valign="top" width="200">
 <table class="TableStd" width="100%">
  <tr><td class="CellCat"><span class="FontCat">{$modules.Language->getString('overview')}</span></td></tr>
  <tr><td class="CellNav" onclick="goTo('{$indexFile}?action=EditProfile&amp;mode=GeneralProfile&amp;{$mySID}');"><a class="FontNav" href="{$indexFile}?action=EditProfile&amp;mode=GeneralProfile&amp;{$mySID}">{$modules.Language->getString('general_profile')}</a></td></tr>
  <tr><td class="CellNav" onclick="goTo('{$indexFile}?action=EditProfile&amp;mode=ExtendedProfile&amp;{$mySID}');"><a class="FontNav" href="{$indexFile}?action=EditProfile&amp;mode=ExtendedProfile&amp;{$mySID}">{$modules.Language->getString('extended_profile')}</a></td></tr>
  <tr><td class="CellNav" onclick="goTo('{$indexFile}?action=EditProfile&amp;mode=Avatar&amp;{$mySID}');"><a class="FontNav" href="{$indexFile}?action=EditProfile&amp;mode=Avatar&amp;{$mySID}">{$modules.Language->getString('avatar')}</a></td></tr>
  <tr><td class="CellNav" onclick="goTo('{$indexFile}?action=EditProfile&amp;mode=ProfileSettings&amp;{$mySID}');"><a class="FontNav" href="{$indexFile}?action=EditProfile&amp;mode=ProfileSettings&amp;{$mySID}">{$modules.Language->getString('settings')}</a></td></tr>
  <tr><td class="CellNav" onclick="goTo('{$indexFile}?action=EditProfile&amp;mode=TopicSubscriptions&amp;{$mySID}');"><a class="FontNav" href="{$indexFile}?action=EditProfile&amp;mode=TopicSubscriptions&amp;{$mySID}">{$modules.Language->getString('topic_subscriptions')}</a></td></tr>
  <tr><td class="CellNav" onclick="goTo('{$indexFile}?action=EditProfile&amp;mode=Memo&amp;{$mySID}');"><a class="FontNav" href="{$indexFile}?action=EditProfile&amp;mode=Memo&amp;{$mySID}">{$modules.Language->getString('memo')}</a></td></tr>
 </table>
 </td>
 <td valign="top" width="10">&nbsp;</td>
 <td valign="top">