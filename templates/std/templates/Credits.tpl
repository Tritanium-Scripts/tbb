{*

****


	Ich empfehle jedem, jetzt sofort die Datei zu schließen, da sonst die Augen ausfallen!
	Nein, war nur ein Witz (echt?), aber wenn ihr euch das ganze im Browser anseht, sieht es viel cooler aus!!!


****


































































*}<?xml version="1.0" encoding="{$modules.Language->getString('html_encoding')}" standalone="no" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{$modules.Language->getString('html_direction')}" lang="{$modules.Language->getLangCode()}" xml:lang="{$modules.Language->getLangCode()}">
 <head>
  <title>{$modules.Language->getString('credits')}</title>
  <meta name="author" content="Chrissyx" />
  <meta name="copyright" content="&copy; 2010&ndash;2014 Tritanium Scripts" />
  <meta name="generator" content="Notepad 4.10.1998" />
  <meta http-equiv="Content-Language" content="{$modules.Language->getLangCode()}" />
  <meta http-equiv="Content-Type" content="application/xhtml+xml; charset={$modules.Language->getString('html_encoding')}" />
  <meta http-equiv="Content-Style-Type" content="text/css" />
  <meta http-equiv="Content-Script-Type" content="text/javascript" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <link rel="shortcut icon" type="image/x-icon" href="{$modules.Template->getTplDir()}images/favicon.ico" />
  <script src="{$modules.Template->getTplDir()}scripts/fader.js" type="text/javascript"></script>
  <style type="text/css">
   a:link
   {
    color:gray;
    text-decoration:none;
   }

   a:visited
   {
    color:gray;
    text-decoration:none;
   }

   a:active
   {
    color:gray;
    text-decoration:none;
   }

   a:hover
   {
    text-decoration:underline;
   }

   body
   {
    background-color:#000000;
    color:#FFFFFF;
    font-weight:bold;
    font-family:Verdana;
    font-size:small;
   }

   img
   {
    border:none;
   }

   .fade
   {
    filter:alpha(opacity=0);
    left:0;
    margin-top:15%;
    opacity:0.0;
    position:absolute;
    text-align:center;
    top:0;
    width:100%;
   }
  </style>
 </head>
 <body onload="next();">
  <!-- Credits -->
  <p id="fader" style="position:relative; width:100%;">
    {foreach $credits as $curCredit}<span class="fade">{$curCredit}</span>{/foreach}
  </p>
  <object data="http://www.youtube-nocookie.com/v/8Af372EQLck?rel=0&amp;autoplay=1&amp;hd=1" type="application/x-shockwave-flash" width="1" height="1">
   <param name="allowFullScreen" value="false "/>
   <param name="allowScriptAccess" value="sameDomain" />
   <param name="movie" value="http://www.youtube-nocookie.com/v/8Af372EQLck?rel=0&amp;autoplay=1&amp;hd=1" />
   <param name="quality" value="autohigh" />
   <param name="wmode" value="transparent" />
  </object>
 </body>
</html>