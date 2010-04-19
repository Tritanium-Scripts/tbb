function storecaret() {
	if(document.forms['tbb_form'].elements['p_post'].createTextRange) 
		document.forms['tbb_form'].elements['p_post'].caretPos = document.selection.createRange().duplicate();
}

function insertatcaret(text) {
	if(document.forms['tbb_form'].elements['p_post'].createTextRange && document.forms['tbb_form'].elements['p_post'].caretPos) {
		var caretPos = document.forms['tbb_form'].elements['p_post'].caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
		document.forms['tbb_form'].elements['p_post'].focus();
	}
	else document.forms['tbb_form'].elements['p_post'].value += text;
}

function popup(url,name,features) {
	window.open(url,name,features);
}