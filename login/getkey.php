<?php
$url = "https://passport.bilibili.com/login?".$_SERVER["QUERY_STRING"];
$cookie = dirname(__FILE__)."/cookie.tmp";
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_COOKIEFILE,$cookie);
curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; rv:37.0) Gecko/20100101 Firefox/37.0');
curl_setopt($ch,CURLOPT_HEADER,0); 
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
echo curl_exec($ch);
curl_close($ch);
