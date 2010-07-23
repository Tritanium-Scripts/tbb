/**
 * Fades a span element.
 * Span support and fade out added by Chrissyx.
 *
 * @author Felix Riesterer <Felix.Riesterer@gmx.net>
 * @link http://aktuell.de.selfhtml.org/artikel/javascript/fader-framework/bilderslideshow.htm
 */
function fade(step)
{
	var spans = document.getElementById('fader').getElementsByTagName('span');
	step = step || 0;
	spans[counter].style.opacity = step/100;
	spans[counter-1].style.opacity = 1-spans[counter].style.opacity; //Fade out
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
		counter = 0;
	}
	counter++;
	if(counter < spans.length)
	{
		fade();
	}
};