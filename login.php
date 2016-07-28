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
	echo '<h1>hiyouga bili login system</h1><form action="" method="post"><img src="verify.jpg" /><br /><br />Verify code:<input type="text" name="vdcode" /><br /><br />Key:<input type="text" name="key" /><input type="submit" value="ok"></form>';
}else{
	//登录
	if(!$_POST['key'] == 'hiyouga-login'){
		echo 'Wrong key!';
		exit;
	}
	$ch = curl_init();
	$data = array( 
	'act' => 'login',
	'gourl' => '',
	'keeptime' => '2592000',
    'userid' => '13935479776',
    'pwd' => 'Zyw87913',
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