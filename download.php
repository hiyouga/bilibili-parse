<!DOCTYPE html>
<!-- V4.0final last modified in 2016/07/12 -->
<html lang="zh">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<meta name="Description" content="冰河动漫直播模块，支持bilibili视频在线观看及下载" />
<meta name="keywords" content="冰河动漫,bilibili,hiyouga" />
<meta name="author" content="hoshi_hiyouga,admin@hiyouga.tk" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<title>下载 - 冰河动漫 - 直播</title>
<link rel="icon" type="image/ico" href="https://www.bilibili.com/favicon.ico" />
<link rel="stylesheet" href="main.css" />
</head>
<body>
<div style="text-align:center;font-size:1.2em;line-height:2.5em;">
点击链接下载：<br />
<?php
//获取分p
if(isset($_GET['p'])){
	$p = $_GET['p'];
}else{
	$p = 1;
}
//获取aid
if(isset($_GET['aid'])){
	$aid = $_GET['aid'];
	list($cid,$img) = hy_getinfo($aid,$p);
}elseif(isset($_GET['cid'])){
	$cid = $_GET['cid'];
}else{
	echo '<span class="warn">{"code":-1,"message":"请输入av号.AID_ERROR"}</span></div></body></html>';
	exit;
}
//获取mp4
$cdata = file_get_contents('http://interface.bilibili.com/playurl?appkey=452d3958f048c02a&otype=json&cid='.$cid);
if(!!strpos($cdata,'error_code')){
	echo '<span class="warn">'.$cdata.'</span></div></body></html>';
	exit;
}else{
	$cdata = json_decode($cdata,true);
	//Debug bili_cdata_api
	//echo '<h1>Debuging</h1>';var_dump($cdata);exit;
	foreach($cdata['durl']['0']['backup_url'] as $v){
		if(!!strpos($v,'.flv')){
			$n = strpos($v,'.flv');
			$part = substr($v,$n-1,1);
			echo '<a download href="'.$v.'">flv-part'.$part.' (1280i)</a><br />';
		}elseif(!!strpos($v,'hd.mp4')){
			$hmp4 = $v;
		}elseif(!!strpos($v,'.mp4')){
			$lmp4 = $v;
		}
	}
	foreach($cdata['durl'] as $v){
		if(!!strpos($v['url'],'.flv')){
			$v = $v['url'];
			$n = strpos($v,'.flv');
			$part = substr($v,$n-1,1);
			echo '<a download href="'.$v.'">flv-part'.$part.' (1280i)</a><br />';
		}elseif(!!strpos($v['url'],'hd.mp4')){
			if(empty($hmp4)){
				$hmp4 = $v['url'];
			}
		}else{
			continue;
		}
	}
	if(!isset($hmp4)){
		$hmp4 = 'http://livevip.hiyouga.tk/vipapi.php?cid='.$cid;
	}
	if(!!$hmp4){
		echo '<a download href="'.$hmp4.'">高清mp4 (1080i)</a><br />';
	}
	if(!!$lmp4){
		echo '<a download href="'.$lmp4.'">低清mp4 (640i)</a><br />';
	}
}
echo '<a download href="http://comment.bilibili.com/'.$cid.'.xml">XML弹幕</a><br /><a download href="http://www.bilibilijj.com/ashx/Barrage.ashx?f=true&av=&p=&s=ass&cid='.$cid.'&n='.$cid.'-'.$p.'hd">ASS弹幕</a><br />';
if(!!$img){
	echo '<a download href="'.$img.'">封面图</a>';
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
		echo '<html><head><title>错误 - 冰河动漫 - 直播</title><style>body{font-family:Microsoft Yahei;background:#ddd url(http://r6.loli.io/2ABnYz.jpg) no-repeat fixed;text-shadow:1px 0 0 rgba(255,255,255,0.7);}form{text-align:center;}.warn{color:#F00;}</style></head><body><form method="get" action="">av号：<input type="text" name="aid" size="6" maxlength="9" />Part：<input type="text" name="p" size="2" maxlength="3" value="1" /><input type="submit" value="Go!" /></form><hr /><div style="text-align:center;"><span class="warn">'.$info.'</span></div></body></html>';
		exit;
	}
	$result = json_decode($result,true);
	//Debug bili_info_api
	//var_dump($result);exit;
	$cid = $result['cid'];
	$img = $result['pic'];
	$title = $result['title'];
	$up = $result['author'];
	$info = array($cid,$img);
	return $info;
}
?>
</div>
<footer style="text-align:right;">
<a href="index.php?<?php echo $_SERVER["QUERY_STRING"];?>" style="color:#00F;">返回观看</a> | 
Copyright © 2014-2016 <a href="https://www.hiyouga.tk" style="color:#000;">冰河动漫</a>. All rights reserved.
</footer>
</body>
</html>