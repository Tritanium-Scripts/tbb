<table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('User_administration')}</span></td></tr>
<tr><td class="CellAlt" style="background-color:#DCDCDC;">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
 <td valign="top" width="200">
 <table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
  <tr><td class="CellCat"><span class="FontCat">{$modules.Language->getString('Overview')}</span></td></tr>
  <tr><td class="CellNav" onclick="goTo('{$indexFile}?action=EditProfile&amp;mode=GeneralProfile&amp;{$mySID}');"><a class="FontNav" href="{$indexFile}?action=EditProfile&amp;mode=GeneralProfile&amp;{$mySID}">{$modules.Language->getString('General_profile')}</a></td></tr>
  <tr><td class="CellNav" onclick="goTo('{$indexFile}?action=EditProfile&amp;mode=ExtendedProfile&amp;{$mySID}');"><a class="FontNav" href="{$indexFile}?action=EditProfile&amp;mode=ExtendedProfile&amp;{$mySID}">{$modules.Language->getString('Extended_profile')}</td></tr>
  <tr><td class="CellNav" onclick="goTo('{$indexFile}?action=EditProfile&amp;mode=Avatar&amp;{$mySID}');"><a class="FontNav" href="{$indexFile}?action=EditProfile&amp;mode=Avatar&amp;{$mySID}">{$modules.Language->getString('Avatar')}</td></tr>
  <tr><td class="CellNav" onclick="goTo('{$indexFile}?action=EditProfile&amp;mode=ProfileSettings&amp;{$mySID}');"><a class="FontNav" href="{$indexFile}?action=EditProfile&amp;mode=ProfileSettings&amp;{$mySID}">{$modules.Language->getString('Settings')}</td></tr>
  <tr><td class="CellNav" onclick="goTo('{$indexFile}?action=EditProfile&amp;mode=TopicSubscriptions&amp;{$mySID}');"><a class="FontNav" href="{$indexFile}?action=EditProfile&amp;mode=TopicSubscriptions&amp;{$mySID}">{$modules.Language->getString('Topic_subscriptions')}</td></tr>
  <tr><td class="CellNav" onclick="goTo('{$indexFile}?action=EditProfile&amp;mode=Memo&amp;{$mySID}');"><a class="FontNav" href="{$indexFile}?action=EditProfile&amp;mode=Memo&amp;{$mySID}">{$modules.Language->getString('Memo')}</td></tr>
 </table>
 </td>
 <td valign="top" width="10">&nbsp;</td>
 <td valign="top">
