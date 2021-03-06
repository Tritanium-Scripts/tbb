/**
 * Fades a span element.
 * Span support, looping, duration and fade out added by Chrissyx.
 *
 * @author Felix Riesterer <Felix.Riesterer@gmx.net>
 * @link http://aktuell.de.selfhtml.org/artikel/javascript/fader-framework/bilderslideshow.htm
 */
function fade(duration, loop, step)
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
		window.setTimeout(function() { fade(duration, loop, step); }, 1);
	}
	else
	{
		window.setTimeout(function() { next(duration, loop); }, duration);
	}
};

/**
 * Loads next span element to fade to.
 * Looping added by Chrissyx.
 *
 * @author Felix Riesterer <Felix.Riesterer@gmx.net>
 * @link http://aktuell.de.selfhtml.org/artikel/javascript/fader-framework/bilderslideshow.htm
 */
function next(duration, loop)
{
	var spans = document.getElementById('fader').getElementsByTagName('span');
	var duration = duration || 2000;
	var loop = loop || false;
	if(typeof(counter) != 'number')
	{
		counter = 0;
	}
	counter++;
	if(counter < spans.length)
	{
		fade(duration, loop);
	}
	else
		if(loop)
		{
			//Reset and start again
			counter = 1;
			spans[spans.length-1].style.opacity = 0.0;
			spans[spans.length-1].style.filter = 'alpha(opacity=0)';
			fade(duration, loop);
		}
};