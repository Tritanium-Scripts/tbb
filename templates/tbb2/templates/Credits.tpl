{*

****


	Ich empfehle jedem, jetzt sofort die Datei zu schließen, da sonst die Augen ausfallen!
	Nein, war nur ein Witz (echt?), aber wenn ihr euch das ganze im Browser anseht, sieht es viel cooler aus!!!


****


































































*}<?xml version="1.0" encoding="{Language::getInstance()->getString('html_encoding')}" standalone="no" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{Language::getInstance()->getString('html_direction')}" lang="{Language::getInstance()->getLangCode()}" xml:lang="{Language::getInstance()->getLangCode()}">
 <head>
  <meta http-equiv="Content-Type" content="application/xhtml+xml; charset={Language::getInstance()->getString('html_encoding')}" />
  <meta http-equiv="Content-Language" content="{Language::getInstance()->getLangCode()}" />
  <meta http-equiv="Content-Script-Type" content="text/javascript" />
  <meta http-equiv="Content-Style-Type" content="text/css" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="author" content="Tritanium Scripts" />
  <meta name="copyright" content="&copy; 2010&ndash;{$smarty.const.COPYRIGHT_YEAR} Tritanium Scripts" />
  <meta name="generator" content="Notepad 4.10.1998" />
  <meta name="robots" content="all" />
  <link href="{Template::getInstance()->getTplDir()}images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
  <script src="{Template::getInstance()->getTplDir()}scripts/scripts.js" type="text/javascript"></script>
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
  <title>{Language::getInstance()->getString('credits')}</title>
 </head>
 <body onload="next();">
  <!-- Credits -->
  <p id="fader" style="position:relative; width:100%;">
    {foreach $credits as $curCredit}<span class="fade">{$curCredit}</span>{/foreach}
  </p>
  <iframe src="https://www.youtube-nocookie.com/embed/8Af372EQLck?rel=0&amp;autoplay=1" width="1" height="1" frameborder="0"></iframe>
 </body>
</html>