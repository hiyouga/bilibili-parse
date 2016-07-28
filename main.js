/*JS - by hoshi_hiyouga*/
function copy(){
var code=document.getElementById("code");
code.select();
document.execCommand("Copy");
alert("复制成功!");
}
function jump(){
var o = random();
	if (o>6000000){
		jump();
	}else{
		var u = "http://live.hiyouga.tk/?aid="+o;
		//alert(u);
		window.location.href = u;
	}
}
function random(){
var i = Math.random()*10000000;
i = parseInt(i);
return i;
}