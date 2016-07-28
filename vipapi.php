<?php
//V1.1 last modified in 2016/07/12
$cid = $_GET['cid'];
$cdata = file_get_contents('http://interface.bilibili.com/playurl?appkey=452d3958f048c02a&otype=json&cid='.$cid);
if(!!strpos($cdata,'error_code')){
	echo $cdata;
	exit;
}else{
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
		echo '{"code":-1,"message":"无法获取视频.VIDEO_ERROR"}';
		exit;
	}
	header("Location:$mp4");
}