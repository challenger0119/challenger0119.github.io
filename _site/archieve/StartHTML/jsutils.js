function setHitokotoToID(hitokoto){
	var xmlHTTP = new XMLHttpRequest();
    var url = "https://sslapi.hitokoto.cn/?encode=text";
    xmlHTTP.open("GET",url,true);
    xmlHTTP.onreadystatechange = updateHitokoto;
    xmlHTTP.send();
    function updateHitokoto(){
        if (xmlHTTP.readyState == 4) {
            if (xmlHTTP.status == 200) {
                document.getElementById(hitokoto).innerHTML = xmlHTTP.response;
            }
        }
    }
}

function setRTimeToID(onlinecountdown){ 
    var EndTime= new Date('2018/11/01 00:00:00'); //截止时间 
    var NowTime = new Date(); 
    var t = EndTime.getTime() - NowTime.getTime(); 
    var d=Math.floor(t/1000/60/60/24); 
    var h=Math.floor(t/1000/60/60%24); 
    var m=Math.floor(t/1000/60%60); 

    document.getElementById(onlinecountdown).innerHTML = "距离关闭还有：" + d + " 天 " +h + " 时 "+m + " 分"; 
}