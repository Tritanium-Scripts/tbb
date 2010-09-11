var openendTags = new Object();

/**
 * Negates the checked state of stated "delete" checkboxes.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 */
function negateBoxes(id)
{
	for(var i=0,boxes; i<(boxes = document.getElementsByTagName('input')).length; i++)
		if(boxes[i].name.substring(0, 6+id.length) == 'delete' + id)
			boxes[i].checked = boxes[i].checked == false ? true : false;
};

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

/**
 * Sets new style class for stated table row.
 *
 * @author Julian Backes <julian@tritanium-scripts.com>
 */
function setRowCellsClass(row, newClass)
{
	for(var i=0; i<row.cells.length; i++)
	{
		row.cells[i].setAttribute('tbbOldClassName', row.cells[i].className);
		row.cells[i].className = newClass;
	}
}

/**
 * Restores the original style class of stated table row.
 *
 * @author Julian Backes <julian@tritanium-scripts.com>
 */
function restoreRowCellsClass(row, newClass)
{
	for(var i = 0; i < row.cells.length; i++)
	{
		row.cells[i].className = row.cells[i].getAttribute('tbbOldClassName');
		row.cells[i].removeAttribute('tbbOldClassName');
	}
}

/**
 * Inserts BBCode or smiley.
 * Modifications by Chrissyx.
 *
 * @author Torsten Anacker <torsten@anaboe.net>
 * @link http://aktuell.de.selfhtml.org/artikel/javascript/bbcode/
 */
function setTag(openingTag, closingTag)
{
 var curBox = document.getElementById(activeBox);
 curBox.focus();
 if(typeof document.selection != 'undefined') //IE
 {
  var range = document.selection.createRange();
  var selectedText = range.text;
  range.text = openingTag + selectedText + closingTag;
  range = document.selection.createRange();
  selectedText.length == 0 ? range.move('character', -closingTag.length) : range.findText(selectedText);
  range.select();  
 }
 else if(typeof curBox.selectionStart != 'undefined') //Gecko
 {
  var start = curBox.selectionStart;
  var end = curBox.selectionEnd;
  var selectedText = curBox.value.substring(start, end);
  curBox.value = curBox.value.substr(0, start) + openingTag + selectedText + closingTag + curBox.value.substr(end);
  if(selectedText.length == 0) curBox.selectionStart = curBox.selectionEnd = start + openingTag.length;
  else
  {
   curBox.selectionStart = start + openingTag.length;
   curBox.selectionEnd = start + openingTag.length + selectedText.length;
  }
 }
 else //Other
 {
  if(typeof openendTags[openingTag] == 'undefined')
  {
   curBox.value += openingTag;
   openendTags[openingTag] = true;
  }
  else
  {
   curBox.value += closingTag;
   delete(openendTags[openingTag]);
  }
 }
}