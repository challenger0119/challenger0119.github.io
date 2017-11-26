var xmlHTTP;
function showLocation() {
	var textValue = document.getElementById("macinfoinput").value;
	if(textValue == ""){
		var selectobj = document.getElementById("macinfoselect");
		textValue = selectobj.options[selectobj.selectedIndex].value;
	}
	if (textValue != "") {
		xmlHTTP = createXMLHttpRequest();
		var url = "http://localhost:8080/TomcatTest/HelloWorld?mac="+textValue;
		xmlHTTP.open("GET",url,true);
		xmlHTTP.onreadystatechange = doResult;
		xmlHTTP.setRequestHeader("Content-type","application/x-www-form-urlencoded;");
		xmlHTTP.send();
	}		
}

function doResult(){
	if (xmlHTTP.readyState == 4) {
		if (xmlHTTP.status == 200) {
			result = "<p> 位置是：<br/>" + xmlHTTP.responseText + "</p>";
			document.getElementById("output").innerHTML =  result;
		}
	}
}
function createXMLHttpRequest(){
	var xmlHTTP;
	if (window.XMLHttpRequest) {
		xmlHTTP = new XMLHttpRequest();
		if (xmlHTTP.overrideMimeType) {
			xmlHTTP.overrideMimeType("text/xml");
		}
	}else if(window.ActiveXObject){
		try{
			xmlHTTP = new ActiveXObject("Msxml2.XMLHTTP");
		}catch(e){
			try{
				xmlHTTP = new ActiveXObject("Microsoft.XMLHTTP");
			}catch(e){

			}
		}
	}
	return xmlHTTP;
}

