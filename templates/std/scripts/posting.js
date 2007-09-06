// inspired by http://aktuell.de.selfhtml.org/artikel/javascript/bbcode/
openendTags = new Object();

function insert(openingTag, closingTag) {
	var messageBox = document.getElementById('messageBox');
	messageBox.focus();

	// gecko
	if(typeof messageBox.selectionStart != 'undefined') {
		var selectionStart = messageBox.selectionStart;
		var selectionEnd = messageBox.selectionEnd;
		var selectedText = messageBox.value.substring(selectionStart,selectionEnd);

		messageBox.value = messageBox.value.substr(0,selectionStart) + openingTag + selectedText + closingTag + messageBox.value.substr(selectionEnd);

		if(selectedText.length == 0) {
			messageBox.selectionStart = messageBox.selectionEnd = selectionStart + openingTag.length;
		} else {
			messageBox.selectionStart = selectionStart + openingTag.length;
			messageBox.selectionEnd = selectionStart + openingTag.length + selectedText.length;
		}
	}
	// ie
	else if(typeof document.selection != 'undefined') {
		var selectionRange = document.selection.createRange();
		var selectedText = selectionRange.text;
		selectionRange.text = openingTag + selectedText + closingTag;

		selectionRange = document.selection.createRange();
		if(selectedText.length == 0) selectionRange.move('character', -closingTag.length);
		else {
			// following doesn't work (bug?)
			//selectionRange.moveStart('character', openingTag.length);
			//selectionRange.moveEnd('character', -closingTag.length);

			// works
			selectionRange.findText(selectedText);
		}

		selectionRange.select();
	}
	// other
	else {
		if(closingTag != '') {
			if(typeof openendTags[openingTag] != 'undefined') {
				messageBox.value += closingTag;
				delete(openendTags[openingTag]);
			} else {
				messageBox.value += openingTag;
				openendTags[openingTag] = 1;
			}
		} else {
			messageBox.value += openingTag;
		}
	}
}