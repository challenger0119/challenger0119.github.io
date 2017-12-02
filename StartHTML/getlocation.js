var longtitude;
var latitude;

function getLocation() {
    //获取GPS坐标
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(setCoord, handleError, { enableHighAccuracy: true, maximumAge: 1000 });
    } else {
        alert("您的浏览器不支持使用HTML 5来获取地理位置服务");
    }
}

function setCoord(value) {
    longtitude = value.coords.longitude;
    latitude = value.coords.latitude;
    showLocation();
}

function handleError(value) {
    switch (value.code) {
        case 1:
            alert("位置服务被拒绝");
            break;
        case 2:
            alert("暂时获取不到位置信息");
            break;
        case 3:
            alert("获取信息超时");
            break;
        case 4:
            alert("未知错误");
            break;
    }
}



var xmlHTTP;

function showLocation() {
	xmlHTTP = createXMLHttpRequest();
	var url = "https://restapi.amap.com/v3/geocode/regeo?key=b62433a0d53d3b34eb8118264934f700&location="+longtitude+","+latitude+"&output=JSON";
	xmlHTTP.open("GET",url,true);
	xmlHTTP.onreadystatechange = doResult;
	xmlHTTP.setRequestHeader("Content-type","application/x-www-form-urlencoded;");
	xmlHTTP.send();		
}

function doResult(){
	if (xmlHTTP.readyState == 4) {
		if (xmlHTTP.status == 200) {
			var regeocode = JSON.parse(xmlHTTP.response);
			var address = regeocode.regeocode.formatted_address;
			document.getElementById("location").value =  address;
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

