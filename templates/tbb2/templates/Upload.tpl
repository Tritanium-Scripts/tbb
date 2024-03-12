<?xml version="1.0" encoding="{Language::getInstance()->getString('html_encoding')}" standalone="no" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{Language::getInstance()->getString('html_direction')}" lang="{Language::getInstance()->getLangCode()}" xml:lang="{Language::getInstance()->getLangCode()}">
 <head>
  <meta http-equiv="Content-Type" content="application/xhtml+xml; charset={Language::getInstance()->getString('html_encoding')}" />
  <meta http-equiv="Content-Language" content="{Language::getInstance()->getLangCode()}" />
  <meta http-equiv="Content-Script-Type" content="text/javascript" />
  <meta http-equiv="Content-Style-Type" content="text/css" />
  <meta name="author" content="Tritanium Scripts" />
  <meta name="copyright" content="&copy; 2010&ndash;{$smarty.const.COPYRIGHT_YEAR} Tritanium Scripts" />
  <meta name="description" content="{sprintf(Language::getInstance()->getString('html_description'), Config::getInstance()->getCfgVal('site_name'), $smarty.const.VERSION_PUBLIC)}" />
  <meta name="generator" content="Notepad 4.10.1998" />
  <meta name="keywords" content="TBB,Tritanium,Tritanium Scripts,TBB {$smarty.const.VERSION_PUBLIC},Tritanium Bulletin Board,{Config::getInstance()->getCfgVal('site_name')},{','|implode:NavBar::getInstance()->getNavBar(false)}" />
  <meta name="revisit-after" content="7 days" />
  <meta name="robots" content="all" />
  <link href="{Template::getInstance()->getTplDir()}images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
  <link href="{Template::getInstance()->getTplDir()}{Auth::getInstance()->getUserStyle()}" media="all" rel="stylesheet" />
  <title>{$smarty.config.navBarDelim|implode:NavBar::getInstance()->getNavBar(false)}</title>
 </head>
 <body style="padding-top:1em;"{if $isUploaded} onload="opener.document.getElementById('{$targetBoxID}').value += '{$bbCode}';">
  <!-- Upload -->
  <p class="fontNorm" style="color:green; font-weight:bold; text-align:center;">{Language::getInstance()->getString('file_uploaded_and_linked')}</p>{else}">{/if}
  <div id="mainBox" style="width:{Config::getInstance()->getCfgVal('twidth')};">

{include file='Errors.tpl'}
<form action="{$smarty.const.INDEXFILE}?faction=uploadFile&amp;targetBoxID={$targetBoxID}{$smarty.const.SID_AMPER}" method="post" enctype="multipart/form-data">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('upload_file')}</span></th></tr>
 <tr><td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('allowed_extensions_colon')}</span></td><td class="cellAlt"><span class="fontNorm">{if $allowedExtensions == false}<span style="font-style:italic;">{Language::getInstance()->getString('no_limitation')}</span>{else}{', '|implode:$allowedExtensions}{/if}</span></td></tr>
 <tr><td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('maximal_filesize_colon')}</span></td><td class="cellAlt"><span class="fontNorm">{if empty($maxFilesize)}<span style="font-style:italic;">{Language::getInstance()->getString('no_limitation')}</span>{else}{$maxFilesize|string_format:Language::getInstance()->getString('x_kib')}{/if}</span></td></tr>
 <tr><td class="cellAlt" colspan="2" style="text-align:center;"><input class="formText" type="file" name="uploadedFile" /></td></tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('upload_file')}" onclick="this.style.display='none'; (spinImg = document.getElementById('spinner')).src='{Template::getInstance()->getTplDir()}images/spinner.gif'; spinImg.style.display='';" /><img src="" alt="" id="spinner" style="display:none; vertical-align:middle;" /></p>
<input type="hidden" name="mode" value="upload" />
</form>

  <br />
  <p id="copyrightBox">
   Tritanium Bulletin Board {$smarty.const.VERSION_PUBLIC}<br />
   &copy; 2010&ndash;{$smarty.const.COPYRIGHT_YEAR} <a href="http://www.tritanium-scripts.com/" target="_blank">Tritanium Scripts</a>
  </p>
  </div>
 </body>
</html>