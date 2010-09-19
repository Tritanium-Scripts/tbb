{include file='AdminMenu.tpl'}
<!-- AdminSmileyNewSmiley -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=new{if $smileyType == $smarty.const.SMILEY_TOPIC}t{elseif $smileyType == $smarty.const.SMILEY_ADMIN}a{/if}{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <colgroup>
  <col width="20%" />
  <col width="80%" />
 </colgroup>
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{$modules.Language->getString('add_new_smiley')}</span></th></tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('address_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="smadress" value="{$newAddress}" size="50" /> <span class="fontSmall">{$modules.Language->getString('url_or_path')}</span></td>
 </tr>{if $smileyType != $smarty.const.SMILEY_TOPIC}
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('synonym_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="synonym" value="{$newSynonym}" /></td>
 </tr>{/if}
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{$modules.Language->getString('add_new_smiley')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" /></p>
<input type="hidden" name="save" value="yes" />
</form>
{include file='AdminMenuTail.tpl'}