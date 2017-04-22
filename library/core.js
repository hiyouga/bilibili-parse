/*JS - by hoshi_hiyouga*/
function copy(){
var code=document.getElementById("code");
code.select();
document.execCommand("Copy");
alert("复制成功!");
}
var GetUrlValue = function(name) {
    var reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)', 'i');
    var r = window.location.search.substr(1).match(reg);
    if (r != null) {
        try {
            return decodeURIComponent(r[2]);
        } catch (e) {
            return null;
        }
    }
    return null;
}
$(document).ready(function(){
	var OriginTitle = document.title;
	$("#hdflv").colorbox({iframe:true,width:"60%",height:"90%"});
	var aid = GetUrlValue('aid');
	if(aid == null || aid == 0){
		$("#noaid").removeClass("hidden");
	}else{
		var p = GetUrlValue('p');
		if(p == null || p == 0){
			p = 1;
		}
	}
	$("#aid").text(aid);
	$("#part").text(p);
	$("#inputaid").val(aid);
	$("#inputp").val(p);
	$("#bilibili").attr("href","https://www.bilibili.com/video/av" + aid + "/index_" + p + ".html");
	$("#bilibilijj").attr("href","http://www.jijidown.com/video/av" + aid + "/");
	$.get("core.php?type=info&aid=" + aid + "&p=" + p, function(data){
		if(data.indexOf("code") != -1){
			$("#warn").removeClass("hidden");
			$("#warninfo").text(data);
		}
		info = JSON.parse(data);
		$("#cid").text(info.cid);
		$("#title").text(info.title);
		$("#up").text(info.up);
		$("#count").text(info.count);
		String.prototype.httpHtml = function(){
			var reg = /(http:\/\/|https:\/\/)((\w|=|\?|\.|\/|&|-)+)/g;
			return this.replace(reg, '<a rel="nofollow" target="_blank" href="$1$2">$1$2</a>');
		};
		$("#intro").html(info.intro.httpHtml());
		document.title = info.title + ' - ' + info.up + ' - ' + OriginTitle;
		$("#hdflv").attr("href","https://www.bilibili.com/html/html5player.html?as_wide=1&enable_ssl=1&cid=" + info.cid);
		//$("#my-video").attr("poster",info.img);
		var tag = info.tag.split(",");
		$.each(tag,function(index,item){
			$("#tag").append('<li>'+item+'</li>');
		});
		$.get("core.php?type=cdata&cid=" + info.cid, function(data){
			if(data.indexOf("code") != -1){
				$("#warn").removeClass("hidden");
				$("#warninfo").text(data);
			}
			cdata = JSON.parse(data);
			$("#quality").text(cdata.quality);
			var player = videojs('my-video');
			player.poster(info.img);
			if(cdata.hmp4 != null){
				player.src(cdata.hmp4);
			}else{
				player.src(cdata.lmp4);
			}
			$("#xml").html("<a download style=\"margin:1%;\" class=\"btn btn-primary\" href=\"https://comment.bilibili.com/" + info.cid + ".xml\" role=\"button\">" + info.cid + ".xml</a>");
			$("#img").html("<a download style=\"margin:1%;\" class=\"btn btn-warning\" href=\"" + info.img + "\" role=\"button\">" + aid + ".jpg</a>");
			$("#mp4").html("<a download style=\"margin:1%;\" class=\"btn btn-info\" href=\"" + cdata.hmp4 + "\" role=\"button\">" + info.cid + "-hd.mp4</a>");
			var lmp4 = cdata.lmp4;
			if(cdata.lmp4 == null){
				$.get("core.php?type=sdlink&aid=" + aid + "&p=" + p, function(data){
					lmp4 = data;
				});
			}
			if(lmp4 != null){
				$("#mp4").append("<a download style=\"margin:1%;\" class=\"btn btn-info\" href=\"" + lmp4 + "\" role=\"button\">" + info.cid + ".mp4</a>");
				player.qualityselector({
					sources:[
						{format:'hd1080',src:cdata.hmp4,type:'video/mp4'},
						{format:'sd360',src:lmp4,type:'video/mp4'}
					],
					formats:[
						{code:'hd1080',name:'1080i'},
						{code:'sd360',name:'360p'}
					],
					onFormatSelected:function(format){
						console.log(format);
					}
				});
			}
		});
	});
});
$("#dl_btn").click(function(){
	cid = $("#cid").text();
	$.get("core.php?type=dl&cid=" + cid, function(data){
		dl = JSON.parse(data);
		var flv = '';
		$.each(dl,function(index,item){
			var page = index + 1;
			flv += "<a download style=\"margin:1%;\" class=\"btn btn-success\" href=\"" + item + "\" role=\"button\">" + cid + "-" + page + ".flv</a>";
		});
		$("#flv").html(flv);
	});
});