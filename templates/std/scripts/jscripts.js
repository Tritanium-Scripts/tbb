Entities = new Array(
	new Array('szlig',223),
	new Array('Auml',196),
	new Array('Ouml',214),
	new Array('Uuml',220),
	new Array('auml',228),
	new Array('ouml',246),
	new Array('uuml',252)
);

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

function popUp(url,windowName,width,height) {
	window.open(url,windowName,"width="+width+", height="+height+", resizable=no, location=no, menubar=no, scrollbars=yes, status=no, toolbar=no");
}

function setClass(element,newClassName) {
	element.className = newClassName;
}

function goTo(Url) {
	window.location.href = Url;
}

function getCookieValue(Name) {
	var Cookies, i, curCookie;

	if(Cookies = document.cookie) {
		Cookies = Cookies.split("; ");
		for(i = 0; i < Cookies.length; i++) {
			curCookie = Cookies[i].split("=");
			if(curCookie[0] == Name)
				return decodeURIComponent(curCookie[1]);
		}
	}
	return false;
}

function setCookieValue(Name,Value) {
	var Expiration;

	Expiration = new Date();
	Expiration.setTime(Expiration.getTime()+31536000000);

	document.cookie = Name+"="+encodeURIComponent(Value)+"; expires="+Expiration.toGMTString()+"; ";

	return true;
}

function EntitiesToUnicode(Text) {
	var x;
	for(i = 0; i < Entities.length; i++) {
		x = new RegExp('&'+Entities[i][0]+';');
		Text = Text.replace(x,String.fromCharCode(Entities[i][1]));
	}

	return Text;
}

function setRowCellsClass(row,newClass) {
	for(var i = 0; i < row.cells.length; i++) {
		row.cells[i].setAttribute("tbbOldClassName",row.cells[i].className);
		row.cells[i].className = newClass;
	}
}

function removeRowCellsClass(row) {
	for(var i = 0; i < row.cells.length; i++) {
		row.cells[i].setAttribute("tbbOldClassName",row.cells[i].className);
		row.cells[i].className = '';
	}
}

function restoreRowCellsClass(row,newClass) {
	for(var i = 0; i < row.cells.length; i++) {
		row.cells[i].className = row.cells[i].getAttribute("tbbOldClassName");
		row.cells[i].removeAttribute("tbbOldClassName");
	}
}