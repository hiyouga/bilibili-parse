<?php
//V1.0 last modified in 2016/07/12
//error_reporting(0);
$cookieVerify = dirname(__FILE__)."/verify.tmp";
$cookieSuccess = dirname(__FILE__)."/cookie.tmp";
if(!$_POST){
	//获取验证码
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://passport.bilibili.com/login");
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true); 
	curl_setopt($ch, CURLOPT_COOKIEJAR,$cookieVerify);
	$rs = curl_exec($ch);
	curl_close($ch);
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, "https://passport.bilibili.com/captcha");
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieVerify);
	$rs = curl_exec($ch);
	@file_put_contents("verify.jpg",$rs);
	curl_close($ch); 
	?>
	<!DOCTYPE html>
	<html>
	<head>
	<title>hiyouga bili login system</title>
	<link rel="icon" type="image/ico" href="https://static.hdslb.com/images/favicon.ico" /> 
	</head>
	<body>
	<h1>hiyouga bili login system</h1><br /><br />
	<div id="form">
	<form action="" method="post">
		USERID：<input id="userid" type="text" name="userid" /><br /><br />
		Password：<input id="pwd" type="text" name="pwd" /><br /><br />
		Verify code：<input id="captcha" type="text" name="vdcode" /><img style="height:40px;width:auto;" src="verify.jpg" /><br /><br />
		Key：<input id="key" type="text" name="key" /><br /><br />
		<input type="button" id="submit" value="ok" />
	</form>
	</div>
	<div id="result">
	</div>
	<script src="https://static-s.bilibili.com/js/jquery.min.js"></script>
	<script src="https://static-s.bilibili.com/passport/seajs/plugin/autocomplete/jquery-ui.min.js"></script>
	<script src="https://static-s.bilibili.com/js/jsencrypt.min.js"></script>
	<script>
	$("#submit").click(function(){
		userid = $("#userid").val();
		passwd = $("#pwd").val();
		captcha = $("#captcha").val();
		key = $("#key").val();
		$.getJSON("getkey.php?act=getkey&_=" + new Date().getTime(), function (rs) {
				if (rs && rs.error) {
					$("#login .message[for=passwd]").text("服务端出现异常，请稍后重试")
				} else {
					var jscrypt = new JSEncrypt();
					jscrypt.setPublicKey(rs.key);
					passwd = jscrypt.encrypt(rs.hash + passwd);
					$.post("", {
						"userid": userid,
						"pwd": passwd,
						"vdcode": captcha,
						"key": key
					},function(data){
					  //$("#result").html(data);
					  alert(data);
					});
				}
			})
	});
	</script>
	</body>
	</html>
	<?php
}else{
	//登录
	if(md5($_POST['key']) != '367a7c5ec3690820ef53648bb028157b'){
		echo 'Wrong key!';
		exit;
	}
	$ch = curl_init();
	$data = array(
		'act' => 'login',
		'gourl' => '',
		'keeptime' => '2592000',
		'userid' => $_POST["userid"],
		'pwd' => $_POST["pwd"],
		'vdcode' => $_POST["vdcode"]
	);
	$url = "https://passport.bilibili.com/login/dologin"; 
	$data = http_build_query($data);
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_REFERER,'https://passport.bilibili.com/login/dologin');
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieVerify);
	curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; rv:37.0) Gecko/20100101 Firefox/37.0');
	curl_setopt($ch,CURLOPT_HEADER,0);
	curl_setopt($ch,CURLOPT_POST,1);
	curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);  
	curl_setopt($ch,CURLOPT_COOKIEJAR,$cookieSuccess);
	echo curl_exec($ch);
	curl_close($ch);
}
