<table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="CellTitle"><span class="FontTitle">{$Modules.Language->getString('User_administration')}</span></td></tr>
<tr><td class="CellAlt" style="background-color:#DCDCDC;">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
 <td valign="top" width="200">
 <table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
  <tr><td class="CellCat"><span class="FontCat">{$Modules.Language->getString('Overview')}</span></td></tr>
  <tr><td class="CellNav" onclick="goTo('{$IndexFile}?Action=EditProfile&amp;Mode=GeneralProfile&amp;{$MySID}');"><a class="FontNav" href="{$IndexFile}?Action=EditProfile&amp;Mode=GeneralProfile&amp;{$MySID}">{$Modules.Language->getString('General_profile')}</a></td></tr>
  <tr><td class="CellNav" onclick="goTo('{$IndexFile}?Action=EditProfile&amp;Mode=ExtendedProfile&amp;{$MySID}');"><a class="FontNav" href="{$IndexFile}?Action=EditProfile&amp;Mode=ExtendedProfile&amp;{$MySID}">{$Modules.Language->getString('Extended_profile')}</td></tr>
  <tr><td class="CellNav" onclick="goTo('{$IndexFile}?Action=EditProfile&amp;Mode=Avatar&amp;{$MySID}');"><a class="FontNav" href="{$IndexFile}?Action=EditProfile&amp;Mode=Avatar&amp;{$MySID}">{$Modules.Language->getString('Avatar')}</td></tr>
  <tr><td class="CellNav" onclick="goTo('{$IndexFile}?Action=EditProfile&amp;Mode=ProfileSettings&amp;{$MySID}');"><a class="FontNav" href="{$IndexFile}?Action=EditProfile&amp;Mode=ProfileSettings&amp;{$MySID}">{$Modules.Language->getString('Settings')}</td></tr>
  <tr><td class="CellNav" onclick="goTo('{$IndexFile}?Action=EditProfile&amp;Mode=TopicSubscriptions&amp;{$MySID}');"><a class="FontNav" href="{$IndexFile}?Action=EditProfile&amp;Mode=TopicSubscriptions&amp;{$MySID}">{$Modules.Language->getString('Topic_subscriptions')}</td></tr>
  <tr><td class="CellNav" onclick="goTo('{$IndexFile}?Action=EditProfile&amp;Mode=Memo&amp;{$MySID}');"><a class="FontNav" href="{$IndexFile}?Action=EditProfile&amp;Mode=Memo&amp;{$MySID}">{$Modules.Language->getString('Memo')}</td></tr>
 </table>
 </td>
 <td valign="top" width="10">&nbsp;</td>
 <td valign="top">
