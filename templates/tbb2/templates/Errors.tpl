{if !empty($errors)}<table cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><td class="cellError"><ul class="fontError" style="list-style-image:url({$modules.Template->getTplDir()}images/icons/bullet_error.png);">
{foreach $errors as $curError}  <li>{$curError}</li>{/foreach}
</ul></td></tr>
</table>
<br />{/if}