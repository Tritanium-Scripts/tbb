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
  <meta name="copyright" content="&copy; 2010 Tritanium Scripts" />
  <meta name="generator" content="Notepad 4.10.1998" />
  <meta http-equiv="Content-Language" content="{$modules.Language->getLangCode()}" />
  <meta http-equiv="Content-Type" content="application/xhtml+xml; charset={$modules.Language->getString('html_encoding')}" />
  <meta http-equiv="Content-Style-Type" content="text/css" />
  <meta http-equiv="Content-Script-Type" content="text/javascript" />
  <meta http-equiv="X-UA-Compatible" content="IE=8" />
  <link rel="shortcut icon" type="image/x-icon" href="{$modules.Template->getTplDir()}images/favicon.ico" />
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
    font-family:Verdana;
    /*font-size:x-small;*/
   }

   img
   {
    border:none;
   }

   .fade
   {
    filter:alpha(opacity=0);
    left:0;
    opacity:0;
    position:absolute;
    text-align:center;
    top:0;
   }
  </style>
  <script type="text/javascript">
  	/**
  	 * Fades a span element.
  	 * Fade out added by Chrissyx.
  	 *
  	 * @author Felix Riesterer <Felix.Riesterer@gmx.net>
  	 * @link http://aktuell.de.selfhtml.org/artikel/javascript/fader-framework/bilderslideshow.htm
  	 */
  	function fade(step)
  	{
		var spans = document.getElementById('fader').getElementsByTagName('span');
		step = step || 0;
		spans[counter].style.opacity = step/100;
		spans[counter-1].style.opacity = 100-spans[counter].style.opacity; //Fade out
		spans[counter].style.filter = 'alpha(opacity=' + step + ')'; // IE
		spans[counter-1].style.filter = 'alpha(opacity=' + (100-step) + ')'; //IE fade out
		step = step + 2;
		if(step <= 100)
		{
			window.setTimeout(function() { fade(step); }, 1);
        }
        else
        {
			window.setTimeout(next, 2000);
		}
	};

  	/**
  	 * Loads next span element to fade to.
  	 *
  	 * @author Felix Riesterer <Felix.Riesterer@gmx.net>
  	 * @link http://aktuell.de.selfhtml.org/artikel/javascript/fader-framework/bilderslideshow.htm
  	 */
	function next()
	{
		var spans = document.getElementById('fader').getElementsByTagName('span');
		if(typeof(counter) != 'number')
		{
			counter = 1;
		}
		counter++;
		if(counter < spans.length)
		{
			fade();
		}
    };
  </script>
 </head>
 <body onload="next();">
  <p id="fader" style="position:relative; width:99%;">
    <span class="fade">&nbsp;</span>
    {foreach $credits as $curCredit}<span class="fade">{$curCredit}</span>{/foreach}
  </p>
 </body>
</html>