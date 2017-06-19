<?php
if($_GET['type'] == 'info'){
	echo getinfo($_GET['aid'],$_GET['p']);
}
if($_GET['type'] == 'cdata'){
	$cdata = getvideo($_GET['cid'],'mp4');
	$cdata = json_decode($cdata,true);
	//Debug bili_cdata_api
	//var_dump($cdata);exit;
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
		$mp4info = 'low';
	}else{
		$mp4info = 'high';
	}
	$cdata = array(
		"hmp4" => $hmp4,
		"lmp4" => $lmp4,
		"quality" => $mp4info
	);
	echo json_encode($cdata);
}
if($_GET['type'] == 'sdlink'){
	$sd = getsdlink($_GET['aid'],$_GET['p']);
	if(empty($sd)){
		$sd = null;
	}
	echo $sd;
}
if($_GET['type'] == 'dl'){
	$dl = getvideo($_GET['cid'],'flv');
	$dl = json_decode($dl,true);
	//Debug bili_dl_api
	//var_dump($dl);exit;
	$newarr = array();
	foreach($dl['durl'] as $k => $v){
		$newarr[$k] = $v['url'];
	}
	echo json_encode($newarr);
}
/**
	* @param $aid 视频av号
	* @param $p 视频part序数
	* @return array cid:视频cid,img:视频封面,title:视频标题,up:UP主昵称,$count:视频播放量,$intro:视频简介,$tag:视频标签
**/
function getinfo($aid,$p){
	$info = curl('http://api.bilibili.com/view?appkey=12737ff7776f1ade&id='.$aid.'&page='.$p);
	if(!!strpos($info,'code')){
		return $info;
	}else{
		$result = json_decode($info,true);
		//Debug bili_info_api
		//var_dump($result);exit;
		$cid = $result['cid'];
		$img = $result['pic'];
		$title = $result['title'];
		$up = $result['author'];
		$count = $result['play'];
		$intro = $result['description'];
		$tag = $result['tag'];
		$info = array(
			"cid" => $cid,
			"img" => $img,
			"title" => $title,
			"up" => $up,
			"count" => $count,
			"intro" => $intro,
			"tag" => $tag
		);
		return json_encode($info);
	}
	
}
/**
	* @param $params array 参数列表
	* @param $key 加密密钥
	* @return array sign:加密校验串,params:参数拼接串
**/
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
/**
	* @param $cid 视频cid
	* @param $type 视频类型
	* @return string 视频地址
**/
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
	$cdata = str_replace("http://","https://",$cdata);
	return $cdata;
}
/**
	* @param $aid 视频av号
	* @param $p 视频part序数
	* @return string 低清视频地址
**/
function getsdlink($aid,$p){
	$link = file_get_contents('http://api.bilibili.com/playurl?aid='.$aid.'&page='.$p);
	$link = json_decode($link,true);
	//Debug bili_cdata_api
	//var_dump($link);exit;
	$link = $link['durl']['0']['url'];
	//$link = str_replace("http://","https://",$link);
	return $link;
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
