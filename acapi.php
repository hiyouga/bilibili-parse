<?php
//V0.5 last modified in 2016/04/30
if(isset($_GET['aid'])){
	$aid = $_GET['aid'];
}else{
	echo '{"code":-1,"message":"请输入ac号.AID_ERROR"}';
	exit;
}
$code = file_get_contents('http://www.acfun.tv/v/ac'.$aid);
preg_match('#data-vid="(.+?)"#',$code,$cvid);
$vid = $cvid[1];
//$data = file_get_contents('http://m.acfun.tv/ykplayer?date=undefined#vid='.$aid);
$url = 'https://ssl.acfun.tv/block-player-homura.html?token=z2gytn37hstkpgb9;vid='.$vid.';postMessage=0;autoplay=1;fullscreen=1;from=http://www.acfun.tv;';
header("Location:$url"); 
exit;