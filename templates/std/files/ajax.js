function ajaxGetInstance(functionName) {
	var ajaxConnection = false;

	if (window.XMLHttpRequest) {
		ajaxConnection = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		ajaxConnection = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if(ajaxConnection) {
		ajaxConnection.onreadystatechange = function() {
			eval(functionName+'(ajaxConnection)');
		}
		return ajaxConnection;
	} else {
		alert("Fehler!");
	}
}

function ajaxGetValue(xmlObject,valueName) {
	var result = null;

	for(var i = 0; i < xmlObject.getElementsByTagName('ajaxresult')[0].getElementsByTagName('values')[0].getElementsByTagName('value').length; i++) {
		if(xmlObject.getElementsByTagName('ajaxresult')[0].getElementsByTagName('values')[0].getElementsByTagName('value')[i].getAttribute('name') == valueName) {
			result = xmlUnescapeString(xmlObject.getElementsByTagName('ajaxresult')[0].getElementsByTagName('values')[0].getElementsByTagName('value')[i].firstChild.data);
			break;
		}
	}

	return result;
}

function ajaxGetStatus(xmlObject) {
	return xmlObject.getElementsByTagName('ajaxresult')[0].getElementsByTagName('status')[0].firstChild.data;
}

function ajaxGetMode(xmlObject) {
	return xmlObject.getElementsByTagName('ajaxresult')[0].getElementsByTagName('mode')[0].firstChild.data;
}

function xmlUnescapeString(value) {
	value = value.replace(/&lt;/g,"<");
	value = value.replace(/&gt;/g,">");
	value = value.replace(/&amp;/g,"&");
	value = value.replace(/&quot;/g,"\"");
	value = value.replace(/&apos;/g,"'");

	return value;
}