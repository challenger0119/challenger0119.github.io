function showLocation() {
	var textValue = document.getElementById("macinfoinput").value;
	if(textValue == ""){
		var selectobj = document.getElementById("macinfoselect");
		textValue = selectobj.options[selectobj.selectedIndex].value;
	}
	result = "<p> 位置是：<br/>" + textValue + "</p>";
	document.getElementById("output").innerHTML =  result;
}