function ajaxGetInstance(FunctionName) {
	var AjaxConnection = false;

	if (window.XMLHttpRequest) {
		AjaxConnection = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		AjaxConnection = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if(AjaxConnection) {
		AjaxConnection.onreadystatechange = function() {
			eval(FunctionName+'(AjaxConnection)');
		}
		return AjaxConnection;
	} else {
		alert("Fehler!");
	}
}

function ajaxGetValue(XMLObject,ValueName) {
	var Result = null;

	for(var i = 0; i < XMLObject.getElementsByTagName('ajaxresult')[0].getElementsByTagName('values')[0].getElementsByTagName('value').length; i++) {
		if(XMLObject.getElementsByTagName('ajaxresult')[0].getElementsByTagName('values')[0].getElementsByTagName('value')[i].getAttribute('name') == ValueName) {
			Result = XMLUnescapeString(XMLObject.getElementsByTagName('ajaxresult')[0].getElementsByTagName('values')[0].getElementsByTagName('value')[i].firstChild.data);
			break;
		}
	}

	return Result;
}

function ajaxGetStatus(XMLObject) {
	return XMLObject.getElementsByTagName('ajaxresult')[0].getElementsByTagName('status')[0].firstChild.data;
}

function ajaxGetMode(XMLObject) {
	return XMLObject.getElementsByTagName('ajaxresult')[0].getElementsByTagName('mode')[0].firstChild.data;
}

function XMLUnescapeString(Value) {
	Value = Value.replace(/&lt;/g,"<");
	Value = Value.replace(/&gt;/g,">");
	Value = Value.replace(/&amp;/g,"&");
	Value = Value.replace(/&quot;/g,"\"");
	Value = Value.replace(/&apos;/g,"'");

	return Value;
}