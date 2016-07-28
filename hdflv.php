<!DOCTYPE html>
<html lang="zh">
<!-- V3.0final last modified in 2016/05/21 -->
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<meta name="author" content="hoshi_hiyouga,admin@hiyouga.tk" />
<title>Flash高清 - 冰河动漫 - 直播</title>
<link rel="icon" type="image/ico" href="http://www.bilibili.com/favicon.ico" />
<link rel="stylesheet" href="main.css" />
</head>
<body onload="fixsize()">
<div style="text-align:center;">
<?php
//获取分p
if(!empty($_GET['p'])){
	$p = $_GET['p'];
}else{
	$p = '1';
}
//获取aid
if(!empty($_GET['aid'])){
	$aid = $_GET['aid'];
	$cid = hy_getinfo($aid,$p);
}elseif(!empty($_GET['cid'])){
	$cid = $_GET['cid'];
}else{
	echo '<span class="warn">{"code":-1,"message":"请输入av号.AID_ERROR"}</span></div></body></html>';
	exit;
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
		echo '<span class="warn">'.$info.'</span></div><script src="main.js"></script></body></html>';
		exit;
	}
	$result = json_decode($result,true);
	return $result['cid'];
}
?>
<!-- API调用接口：第三方接口 -->
<iframe width="0" height="0" frameborder="0" src="http://www.bilibili.com/plus/widget/missionQixi.php?mission=9&act=zan&id=54304"></iframe>
<iframe width="0" height="0" frameborder="0" src="http://www.bilibili.com/plus/widget/missionQixi.php?mission=9&act=zan&id=54302"></iframe>
<iframe width="0" height="0" frameborder="0" src="http://www.bilibili.com/plus/widget/missionQixi.php?mission=9&act=zan&id=54306"></iframe>
<iframe width="0" height="0" frameborder="0" src="http://www.bilibili.com/m/mission_vote?aid=4763201&msid=48&vote=10"></iframe>
<iframe id="bili" style="border:none;" src="javascript:'<embed id=&quot;bofqi_embed&quot; style=&quot;width:100%;height:100%;position:absolute;top:0;left:0;&quot; pluginspage=&quot;http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash&quot;allowfullscreeninteractive=&quot;true&quot;flashvars=&quot;cid=<?php echo $cid;?>&aid=1921223&quot;src=&quot;https://static-s.bilibili.com/play.swf&quot;type=&quot;application/x-shockwave-flash&quot;allowscriptaccess=&quot;always&quot;allowfullscreen=&quot;true&quot;quality=&quot;high&quot;>'"></iframe>
<!-- &as_wide=1 -->
</div>
<footer style="text-align:right;">
<a href="index.php?<?php echo $_SERVER["QUERY_STRING"];?>" style="color:#00F;">返回观看</a> | 
Copyright © 2014-2016 <a href="https://www.hiyouga.tk" style="color:#000;">冰河动漫</a>. All rights reserved.
</footer>
<script>
function fixsize(){
	var fl = document.getElementById("bili")
	var w = window.innerWidth*0.9;
	var h = window.innerHeight*0.9;
	fl.width  = w;
	fl.height = h;
}
</script>
</body>
</html>