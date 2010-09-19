{include file='AdminMenu.tpl'}
<!-- AdminCensorEditWord -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_censor&amp;mode=edit{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{$modules.Language->getString('edit_censorship')}</span></th></tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('word_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="word" value="{$editWord}" /> <span class="fontSmall">{$modules.Language->getString('case_insensitivity_hint')}</span></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('replacement_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="replacement" value="{$editReplacement}" /></td>
 </tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{$modules.Language->getString('edit_censorship')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" /></p>
<input type="hidden" name="update" value="1" />
<input type="hidden" name="id" value="{$censorshipID}" />
</form>
{include file='AdminMenuTail.tpl'}