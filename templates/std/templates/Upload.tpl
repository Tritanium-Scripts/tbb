<!-- Upload -->
<?xml version="1.0" encoding="{$modules.Language->getString('html_encoding')}" standalone="no" ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{$modules.Language->getString('html_direction')}" lang="{$modules.Language->getLangCode()}" xml:lang="{$modules.Language->getLangCode()}">
 <head>
  <title>{$smarty.config.navBarDelim|implode:$modules.NavBar->getNavBar(false)}</title>
  <meta name="author" content="Chrissyx" />
  <meta name="copyright" content="&copy; 2010&ndash;2014 Tritanium Scripts" />
  <meta name="keywords" content="TBB,Tritanium,Tritanium Scripts,TBB {$smarty.const.VERSION_PUBLIC},Tritanium Bulletin Board,{$modules.Config->getCfgVal('site_name')},{','|implode:$modules.NavBar->getNavBar(false)}" />
  <meta name="description" content="{sprintf($modules.Language->getString('html_description'), $modules.Config->getCfgVal('site_name'), $smarty.const.VERSION_PUBLIC)}" />
  <meta name="revisit-after" content="7 days" />
  <meta name="generator" content="Notepad 4.10.1998" />
  <meta http-equiv="Content-Language" content="{$modules.Language->getLangCode()}" />
  <meta http-equiv="Content-Type" content="application/xhtml+xml; charset={$modules.Language->getString('html_encoding')}" />
  <meta http-equiv="Content-Style-Type" content="text/css" />
  <meta http-equiv="Content-Script-Type" content="text/javascript" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <link rel="stylesheet" media="all" href="{$modules.Template->getTplDir()}{$modules.Auth->getUserStyle()}" />
  <link rel="shortcut icon" type="image/x-icon" href="{$modules.Template->getTplDir()}images/favicon.ico" />
 </head>
 <body style="padding-top:1em;"{if $isUploaded} onload="opener.document.getElementById('{$targetBoxID}').value += '{$bbCode}';">
  <p class="norm" style="color:green; font-weight:bold; text-align:center;">{$modules.Language->getString('file_uploaded_and_linked')}</p>{else}">{/if}

{include file='Errors.tpl'}
<form action="{$smarty.const.INDEXFILE}?faction=uploadFile&amp;targetBoxID={$targetBoxID}{$smarty.const.SID_AMPER}" method="post" enctype="multipart/form-data">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{$modules.Language->getString('upload_file')}</span></th></tr>
 <tr><td class="td1"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('allowed_extensions_colon')}</span></td><td class="td1"><span class="norm">{if $allowedExtensions == false}<span style="font-style:italic;">{$modules.Language->getString('no_limitation')}</span>{else}{', '|implode:$allowedExtensions}{/if}</span></td></tr>
 <tr><td class="td1"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('maximal_filesize_colon')}</span></td><td class="td1"><span class="norm">{if empty($maxFilesize)}<span style="font-style:italic;">{$modules.Language->getString('no_limitation')}</span>{else}{$maxFilesize|string_format:$modules.Language->getString('x_kib')}{/if}</span></td></tr>
 <tr><td class="td1" colspan="2" style="text-align:center;"><input type="file" name="uploadedFile" /></td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('upload_file')}" onclick="this.style.display='none'; (spinImg = document.getElementById('spinner')).src='{$modules.Template->getTplDir()}images/spinner.gif'; spinImg.style.display='';" /><img src="" alt="" id="spinner" style="display:none; vertical-align:middle;" /></p>
<input type="hidden" name="mode" value="upload" />
</form>

  <br />
  <p class="copyr" style="text-align:center;">
   Tritanium Bulletin Board {$smarty.const.VERSION_PUBLIC}<br />
   &copy; 2010&ndash;2014 <a class="copyr" href="http://www.tritanium-scripts.com/" target="_blank">Tritanium Scripts</a>
  </p>
 </body>
</html>