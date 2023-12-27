{if !empty($errors)}<table cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><td class="cellError"><ul class="fontError" style="list-style-image:url({Template::getInstance()->getTplDir()}images/icons/bullet_error.png);">
{foreach $errors as $curError}  <li>{$curError}</li>{/foreach}
</ul></td></tr>
</table>
<br />{/if}