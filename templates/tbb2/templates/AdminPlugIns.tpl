{include file='AdminMenu.tpl'}
<!-- AdminPlugIns -->
<form action="{$smarty.const.INDEXFILE}?faction=adminPlugIns&amp;mode=delete{$smarty.const.SID_AMPER}" method="post">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="5"><span class="fontTitle">{Language::getInstance()->getString('manage_plug_ins')}</span></th></tr>
 <tr>
  <td class="cellCat" colspan="2"><span class="fontCat">{Language::getInstance()->getString('name_of_plug_in')}</span></td>
  <td class="cellCat"><span class="fontCat">{Language::getInstance()->getString('author')}</span></td>
  <td class="cellCat"><span class="fontCat">{Language::getInstance()->getString('description')}</span></td>
  <td class="cellCat"><span class="fontCat">{Language::getInstance()->getString('version')}</span></td>
 </tr>{foreach $plugIns as $curPlugInId => $curPlugIn}
 <tr>
  <td class="cellStd" style="width:1%;"><input type="radio" id="{$curPlugInId}" name="plugIn" value="{$curPlugInId}" /></td>
  <td class="cellStd"><label for="{$curPlugInId}" class="fontNorm">{$curPlugIn.name|escape}</label></td>
  <td class="cellAlt"><span class="fontNorm"><a href="{$curPlugIn.website}" target="_blank">{$curPlugIn.author}</a></span></td>
  <td class="cellStd"><span class="fontSmall">{$curPlugIn.description}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$curPlugIn.version}</span></td>
 </tr>
{foreachelse}
 <tr><td colspan="5" class="cellStd" style="text-align:center;"><span class="fontNorm" style="font-weight:bold;">{Language::getInstance()->getString('no_plug_ins_available')}</span></td></tr>
{/foreach}
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('delete_plug_in')}" /></p>
</form>
{include file='AdminMenuTail.tpl'}