<!-- AdminSmileyNewSmiley -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=new{if $smileyType == $smarty.const.SMILEY_TOPIC}t{elseif $smileyType == $smarty.const.SMILEY_ADMIN}a{/if}{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{$modules.Language->getString('add_new_smiley')}</span></th></tr>
 <tr>
  <td class="td1" style="width:20%;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('address_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="smadress" value="{$newAddress}" /> <span class="small">{$modules.Language->getString('url_or_path')}</span></td>
 </tr>{if $smileyType != $smarty.const.SMILEY_TOPIC}
 <tr>
  <td class="td1" style="width:20%;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('synonym_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" name="synonym" value="{$newSynonym}" /></td>
 </tr>{/if}
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('add_new_smiley')}" /></p>
<input type="hidden" name="save" value="yes" />
</form>