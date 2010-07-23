var openendTags = new Object();

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