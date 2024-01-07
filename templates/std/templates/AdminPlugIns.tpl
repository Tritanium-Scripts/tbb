<!-- AdminPlugIns -->
<form action="{$smarty.const.INDEXFILE}?faction=adminPlugIns&amp;mode=delete{$smarty.const.SID_AMPER}" method="post">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="4"><span class="thnorm">{Language::getInstance()->getString('manage_plug_ins')}</span></th></tr>
 <tr>
  <td class="kat" colspan="2"><span class="kat">{Language::getInstance()->getString('name_of_plug_in')}</span></td>
  <td class="kat"><span class="kat">{Language::getInstance()->getString('author')}</span></td>
  <td class="kat"><span class="kat">{Language::getInstance()->getString('description')}</span></td>
 </tr>{foreach $plugIns as $curPlugInId => $curPlugIn}
 <tr>
  <td class="td1" style="width:1%;"><input type="radio" id="{$curPlugInId}" name="plugIn" value="{$curPlugInId}" /></td>
  <td class="td1"><label for="{$curPlugInId}" class="norm">{$curPlugIn.name|escape}</label></td>
  <td class="td2"><span class="norm"><a href="{$curPlugIn.website}" target="_blank">{$curPlugIn.author}</a></span></td>
  <td class="td1"><span class="small">{$curPlugIn.description}</span></td>
 </tr>
{foreachelse}
 <tr><td class="td1" colspan="4" style="text-align:center;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('no_plug_ins_available')}</span></td></tr>
{/foreach}
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('delete_plug_in')}" /></p>
</form>