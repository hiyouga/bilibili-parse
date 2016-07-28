<?php
//V4.0final last modified in 2016/07/12
if(isset($_GET['p'])){
	$p = $_GET['p'];
}else{
	$p = 1;
}
if(isset($_GET['aid'])){
	$aid = $_GET['aid'];
	$cid = hy_getinfo($aid,$p);
}elseif(isset($_GET['cid'])){
	$cid = $_GET['cid'];
}else{
	echo '{"code":-1,"message":"请输入av号.AID_ERROR"}';
	exit;
}
$cdata = file_get_contents('http://interface.bilibili.com/playurl?appkey=452d3958f048c02a&otype=json&cid='.$cid);
if(!!strpos($cdata,'error_code')){
	echo $cdata;
	exit;
}
$cdata = json_decode($cdata,true);
foreach($cdata['durl']['0']['backup_url'] as $v){
	if(!!strpos($v,'hd.mp4')){
		$mp4 = $v;
		break;
	}elseif(!!strpos($v,'.mp4')){
		$mp4 = $v;
	}else{
		continue;
	}
}
if(empty($mp4)){
	$url = 'http://livevip.hiyouga.tk/api.php?cid='.$cid;
	header("Location:$url");
}else{
	header("Location:$mp4");
}
function hy_getinfo($aid,$p){
	$cookie = dirname(__FILE__)."/cookie.tmp";
	$url = 'http://api.bilibili.com/view?appkey=12737ff7776f1ade&id='.$aid.'&page='.$p;
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_COOKIEFILE,$cookie);
	curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; rv:37.0) Gecko/20100101 Firefox/37.0');
	curl_setopt($ch,CURLOPT_HEADER,0); 
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	$result = curl_exec($ch);
	curl_close($ch);
	if(!!strpos($info,'code')){
		echo $info;
		exit;
	}
	$result = json_decode($result,true);
	//Debug bili_info_api
	//var_dump($result);exit;
	$cid = $result['cid'];
	return $cid;
}