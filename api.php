<?php
//retuen json:videolink,poster,quality
header('Content-type: application/json');
$info = curl('http://api.bilibili.com/view?appkey=12737ff7776f1ade&id='.$_GET['aid'].'&page='.$_GET['p']);
if(!!strpos($info,'code')){
	echo $info;
	exit;
}
$result = json_decode($info,true);
$cid = $result['cid'];
$img = $result['pic'];
//
$cdata = getvideo($cid,'mp4');
$cdata = json_decode($cdata,true);
if(!!strpos($cdata['durl']['0']['url'],'hd.mp4')){
	$hmp4 = $cdata['durl']['0']['url'];
}else{
	$lmp4 = $cdata['durl']['0']['url'];
}
foreach($cdata['durl']['0']['backup_url'] as $v){
	if(!!strpos($v,'hd.mp4')){
		$hmp4 = $v;
		continue;
	}elseif(!!strpos($v,'.mp4')){
		$lmp4 = $v;
		continue;
	}else{
		continue;
	}
}
if(empty($hmp4)){
	$mp4 = $lmp4;
	$mp4info = '低清mp4';
}else{
	$mp4 = $hmp4;
	$mp4info = '高清mp4';
}
$data = array("mp4"=>$mp4,"img"=>$img,"info"=>$mp4info);
echo json_encode($data);
function getsign($params,$key){
	$_data = array();
	ksort($params);
	reset($params);
	foreach ($params as $k => $v) {
	// rawurlencode 返回的转义数字必须为大写( 如%2F )
	$_data[] = $k . '=' . rawurlencode($v);
	}
	$_sign = implode('&', $_data);
	return array(
	'sign' => strtolower(md5($_sign . $key)),
	'params' => $_sign,
	);
}
function getvideo($cid,$type){
	define("APP_SECRET","1c15888dc316e05a15fdd0a02ed6584f");
	$back = getsign(array(
	"cid"=>$cid,
	"from"=>"local",
	"player"=>"1",
	"otype"=>"json",
	"type"=>$type,
	"quality"=>"3",
	"appkey"=>"f3bb208b3d081dc8",
	),APP_SECRET);
	$cdata = curl('https://interface.bilibili.com/playurl?'.$back['params'].'&sign='.$back['sign']);
	//$cdata = str_replace("http://","https://",$cdata);
	return $cdata;
}
function curl($url){
	$cookie = dirname(__FILE__)."/login/cookie.tmp";
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_COOKIEFILE,$cookie);
	curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; rv:37.0) Gecko/20100101 Firefox/37.0');
	curl_setopt($ch,CURLOPT_HEADER,0); 
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	return curl_exec($ch);
	curl_close($ch);
}
