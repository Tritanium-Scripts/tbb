{if !empty($errors)}<table cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="background-color:#FFD1D1; border:2px solid #FF0000; color:#FF0000; padding:3px; width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><td><ul class="small" style="color:#FF0000;">
{foreach $errors as $curError}  <li>{$curError}</li>{/foreach}
</ul></td></tr>
</table>
<br />{/if}