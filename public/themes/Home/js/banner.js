$(function(){
	var n = $(".banner li").length
	$(".banner li").each(function(){
		var _this = $(this),
			src = _this.attr("data-img"),
			img = new Image();
		img.src = src;
		//处理ff下会自动读取缓存图片
		if(img.complete || img.width){
			_this.attr("style","background:url("+src+") no-repeat center");
			n -- ;
			if(n == 0){
				banner();
			}
		    return;
		}
		$(img).load(function(){
			_this.attr("style","background:url("+src+") no-repeat center");
			n -- ;
			if(n == 0){
				banner();
			}
		});
	})
})
function banner(){
	//初始化banner样式
	var listN = $(".banner li").length;
	$(".loader").stop().fadeOut(5000);
	for (var i = 0; i < listN; i++) {
		$(".banner li").eq(i).css('z-index', i+1);
		$(".banner .btns").append('<span class="span'+i+' fl"></span>');
	};
	$(".banner .btns span").eq(0).addClass("cur");
	
	var Media = document.getElementById("i-ban-video");
	var Vidnum = $(".banner li.video").index();
	if (Vidnum == 0 && $(window).width() >= 1050) {
		Media.play();
	};
	// if ($(window).width() >= 1050) {
	// 	Media.play();
	// }

	// 执行效果
	var sw = 0;
	$(".banner li").eq(0).css('left', '0');
	sw = 1;
	$(".banner li").eq(0).addClass('active');
	Fullvideo($(".i-ban"), $(".i-ban .video-sign"));


	$(".banner .btns span").on('click', function() {
		sw = $(this).index();
		myShow(sw);
	});
	function myShow(i){
		var Media = document.getElementById("i-ban-video");
		var Vidnum = $(".banner li.video").index();
		if (i == Vidnum && $(window).width() >= 1050) {
			Media.play();
		}else{
			Media.pause();
		}
		for (var a = i; a < listN; a++) {
			$(".banner li").eq(a).css('opacity', '1');
			$(".banner li").eq(a).stop().animate({left: "100%"},1500,"easeOutCubic");
		};
		// $(".banner .btns span").eq(i).addClass("cur").siblings("span").removeClass("cur");
		
		$(".banner li").eq(i).stop().animate({left: '0'},1500,"easeOutCubic",function(){
			$(".banner li").stop().removeClass('active');
			$(".banner li").eq(i).stop().addClass('active');
		});
		// var lis = $(".banner li").eq(i).siblings();
		// lis.stop().fadeOut(1000);
		if (i == 0) {
			$(".banner .btns span i").stop().attr("style","");
			$(".banner .btns span").eq(i).children('i').stop().animate({width: '100%'}, 8999, "linear");
		}else{
			$(".banner .btns span").eq(i).children('i').stop().animate({width: '100%'}, 8999, "linear");
		}
		for (var a = 0; a < i; a++) {
			// $(".banner li").eq(a).stop().animate({left: -100},1000,"easeOutCubic");
			$(".banner .btns span").eq(a).children('i').stop().width("100%");
		};
	}
	// 滑入停止动画，滑出开始动画
	// $(".banner").hover(function(){
	// 	if(myTime){
	// 	   clearInterval(myTime);
	// 	}
	// },function(){
	// 	clearInterval(myTime);
	// 	myTime = setInterval(function(){
	// 		myShow(sw);
	// 		sw++;
	// 		if(sw == listN){
	// 			sw = 0;
	// 		}
	// 	}, 9000);
	// });
	$(".banner .btns span").eq(0).children('i').stop().animate({width: '100%'}, 5000, "linear");
	//自动开始, 创建定时器
	var myTime = setInterval(function(){
		myShow(sw);
		sw++;
		if(sw == listN){
			sw = 0;
		}
	}, 5000);
}
function Fullvideo(box, obj){
	obj.eq(0).stop().fadeIn(5000)
	function resizeBg() {
		obj.removeClass("w-f").removeClass("h-f").css("margin", 0)
		var boxR = $(window).width() / ($(window).height()-70),
			objR = obj.width() / obj.height();
		if( objR < boxR ) {
		    obj.addClass('w-f').css("margin-top", -(obj.height() - ($(window).height()-70)) / 2);
		}else{
		    obj.addClass('h-f').css("margin-left", -(obj.width() - $(window).width()) / 2);
		}
	}
	$(window).resize(resizeBg).trigger("resize");
}