<?php
//判断手机端
$agent = hy_chwap();
if($agent){
	header('Location:wap.php?'.$_SERVER["QUERY_STRING"]);
	exit;
}
//获取分p
if(!empty($_GET['p'])){
	$p = $_GET['p'];
}else{
	$p = '1';
}
//获取aid
if(!empty($_GET['aid'])){
	$aid = $_GET['aid'];
	//获取cid及视频信息
	list($cid,$img,$title,$up) = hy_getinfo($aid,$p);
}elseif(!empty($_GET['cid'])){
	//进行cid请求
	$cid = $_GET['cid'];
}else{
	echo '<html><head><title>错误 - 冰河动漫 - 直播</title><link rel="stylesheet" href="main.css" /></head><body><form method="get" action="">av号：<input class="text" type="text" name="aid" size="6" maxlength="9" />Part：<input class="text" type="text" name="p" size="2" maxlength="3" value="1" /><input class="submit" type="submit" value="Go!" /> <a class="jump" onClick="jump()">试试手气</a></form><hr /><div style="text-align:center;"><span class="warn">{"code":-1,"message":"请输入av号.AID_ERROR"}</span></div><script src="main.js"></script></body></html>';
	exit;
}
function hy_chwap(){
	if(stristr($_SERVER['HTTP_VIA'],"wap")){
		return true;
	}elseif(strpos(strtoupper($_SERVER['HTTP_ACCEPT']),"VND.WAP.WML") > 0){
		return true;
	}elseif(preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera |Googlebot-Mobile|YahooSeeker\/M1A1-R2D2|android|iphone|ipod|mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i', $_SERVER['HTTP_USER_AGENT'])){
		return true;
	}else{
		return false;
	}
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
		echo '<html><head><title>错误 - 冰河动漫 - 直播</title><link rel="stylesheet" href="main.css" /></head><body><form method="get" action="">av号：<input class="text" type="text" name="aid" size="6" maxlength="9" />Part：<input class="text" type="text" name="p" size="2" maxlength="3" value="1" /><input class="submit" type="submit" value="Go!" /> <a class="jump" onClick="jump()">试试手气</a></form><hr /><div style="text-align:center;"><span class="warn">'.$info.'</span></div><script src="main.js"></script></body></html>';
		exit;
	}
	$result = json_decode($result,true);
	//Debug bili_info_api
	//var_dump($result);exit;
	$cid = $result['cid'];
	$img = $result['pic'];
	$title = $result['title'];
	$up = $result['author'];
	$info = array($cid,$img,$title,$up);
	return $info;
}
?>
<!DOCTYPE html>
<!-- V7.0final last modified in 2016/07/12 -->
<html lang="zh">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<meta name="Description" content="冰河动漫直播模块，支持bilibili视频在线观看及下载" />
<meta name="keywords" content="冰河动漫,bilibili,hiyouga" />
<meta name="author" content="hoshi_hiyouga,admin@hiyouga.tk" />
<title><?php echo $title.' - '.$up; ?> - 冰河动漫 - 直播</title>
<link rel="icon" type="image/ico" href="https://www.bilibili.com/favicon.ico" />
<link rel="stylesheet" href="https://vjs.zencdn.net/5.8.8/video-js.css" />
<link rel="stylesheet" href="main.css" />
<script src="main.js"></script>
</head>
<body>
<form method="get" action="">
av号：<input id="text" type="text" name="aid" size="6" maxlength="9" value="<?php echo $aid;?>" />
Part：<input id="text" type="text" name="p" size="2" maxlength="3" value="<?php echo $p;?>" />
<input class="submit" type="submit" value="Go!" />
<a class="jump" onClick="jump()">试试手气</a>
</form><hr />
<div style="text-align:center;">
<?php
//获取mp4
$cdata = file_get_contents('http://interface.bilibili.com/playurl?appkey=452d3958f048c02a&otype=json&cid='.$cid);
if(!!strpos($cdata,'error_code')){
	echo '<span class="warn">'.$cdata.'</span></div></body></html>';
	exit;
}else{
	$cdata = json_decode($cdata,true);
	//Debug bili_cdata_api
	//var_dump($cdata);exit;
	$type = $cdata['accept_format'];
	foreach($cdata['durl']['0']['backup_url'] as $v){
		if(!!strpos($v,'hd.mp4')){
			$mp4 = $v;
			$hmp4 = true;
			$mp4info = '高清mp4';
			break;
		}elseif(!!strpos($v,'.mp4')){
			$mp4 = $v;
			$hmp4 = false;
			$mp4info = '低清mp4';
		}else{
			continue;
		}
	}
	if(empty($mp4)){
		//'http://bilibili.cloudmoe.com/m/hd_html5/?aid='.$aid.'&page='.$p;
		$mp4 = 'http://livevip.hiyouga.tk/vipapi.php?appkey=hiyouga-live&cid='.$cid;
		$hmp4 = true;
		$mp4info = '高清mp4';
	}
	echo '<video id="my-video" class="video-js" style="width:100%;" poster="'.$img.'" preload="auto" data-setup="{}" controls><source src="'.$mp4.'" type="video/mp4"></video>';
}
class runtime{  
	var $StartTime = 0;  
	var $StopTime = 0;  
	function get_microtime(){list($usec, $sec) = explode(' ', microtime());return ((float)$usec + (float)$sec);}  
	function start(){$this->StartTime = $this->get_microtime();}  
	function stop(){$this->StopTime = $this->get_microtime();}  
	function spent(){return round(($this->StopTime - $this->StartTime) * 1000, 1);}
}
$runtime= new runtime;$runtime->start();$a = 0;for($i=0; $i<1000000; $i++){$a += $i;}$runtime->stop();
$time = '执行时间: '.$runtime->spent().' 毫秒';
//appkey
//452d3958f048c02a 12737ff7776f1ade | 85eb6835b0a1034e 876fe0ebd0e67a0f 95acd7f6cc3392f3 ed77450d77370a3d 03fc8eb101b091fb
?>
<span class="info1">
视频标题：<?php echo $title;?><br />
Up主：<?php echo $up;?>
</span>
<span class="info2">
视频信息：aid:<?php echo $aid;?> <i>|</i> part:<?php echo $p;?> <i>|</i> cid:<?php echo $cid;?><br />
视频类型：<?php echo $type;?> <i>|</i> 正在播放：<?php echo $mp4info;?>
</span>
</div>
<div style="text-align:left;float:left;">
<input id="code" value="<?php echo 'http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];?>" size="20" readonly /><button onClick="copy()">分享链接!</button>
</div>
<footer style="text-align:right;">
<a rel="nofollow" target="_black" href="http://www.bilibili.com/video/av<?php echo $aid;?>/index_<?php echo $p;?>.html" style="color:#090;">前往B站♂</a> | 
<a href="download.php?<?php echo $_SERVER["QUERY_STRING"];?>" style="color:#00F;">下载视频</a> | 
<a href="hdflv.php?<?php echo $_SERVER["QUERY_STRING"];?>" style="color:#666;">高清flv(PConly)</a> | 
<?php echo $time;?> | 
Copyright © 2014-2016 <a href="https://www.hiyouga.tk" style="color:#000;">冰河动漫</a>. All rights reserved.
</footer>
<script src="https://vjs.zencdn.net/5.8.8/video.js"></script>
</body>
</html>
