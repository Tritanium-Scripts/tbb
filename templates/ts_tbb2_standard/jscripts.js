function storecaret() {
	if(document.forms['tbb_form'].elements['p_message_text'].createTextRange) 
		document.forms['tbb_form'].elements['p_message_text'].caretPos = document.selection.createRange().duplicate();
}

function insertatcaret(text) {
	if(document.forms['tbb_form'].elements['p_message_text'].createTextRange && document.forms['tbb_form'].elements['p_message_text'].caretPos) {
		var caretPos = document.forms['tbb_form'].elements['p_message_text'].caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
		document.forms['tbb_form'].elements['p_message_text'].focus();
	}
	else document.forms['tbb_form'].elements['p_message_text'].value += text;
}

function popup(url,name,features) {
	window.open(url,name,features);
}

function change_class(element,newClassName) {
	element.className = newClassName;
}