$(function(){

	//内页banner
	if ($(window).width() > 800) {
		$(".ins-ban").height($(window).height() - 70);
	};
	$(".ins-ban .down-ico").on('click', function() {
		$("body,html").stop().animate({scrollTop: $(window).height() - 70}, 500, "easeOutQuint");
	});

	$(".wechat-info-ban .up-cont .down-ico").on('click', function() {
		$("body,html").stop().animate({scrollTop: $(window).height() - 70}, 500, "easeOutQuint");
	});

	
	var src = $(".ins-ban .bgimg").attr("src");
	var insbanimg = $(".ins-ban img.bgimg").attr("src");
	console.log(insbanimg)
	if (insbanimg != undefined || insbanimg != null) {
		_PreLoadImg([
			src
		],function(){
			$(".ins-ban .loader").fadeOut();
			if ($(window).width() > 800) {
				Fullinsbg($(".ins-ban"), $(".ins-ban .bgimg"));
			}
			$(".ins-ban .bgimg").stop().animate({opacity: 1}, 500,function(){
				$(".ins-ban").addClass('active');
			});
		})
	}else{
		$(".ins-ban .loader").fadeOut();
		if ($(window).width() > 800) {
			Fullvideo($(".ins-ban"), $(".ins-ban .bgimg"));
		}
		$(".ins-ban .bgimg").stop().animate({opacity: 1}, 500,function(){
			$(".ins-ban").addClass('active');
		});
	}
	window.onresize = function(){
		$(".i-ban").height($(window).height() - 70);

		if ($(window).width() < 1030) {
			$(".i-fir-wrap .about-recom").attr("style","");
		};

		if ($(window).width() > 800) {
			$(".ins-ban").height($(window).height() - 70);
		}else{
			$(".ins-ban").attr("style","");
			$(".ins-ban .bgimg").attr("style","").removeClass('h-f');
		}
		$(".ins-ban .bgimg").css('opacity', '1');
	}

	
});
function Fullvideo(box, obj){
	function resizeBg() {
		obj.removeClass("w-f").removeClass("h-f").css("margin", 0)
		var boxR = $(window).width() / ($(window).height()-70),
			objR = $(".ins-ban .bgimg").width() / $(".ins-ban .bgimg").height();
		if( objR < boxR ) {
			if ($(".ins-ban .bgimg").height() > 0) {
		    	obj.addClass('w-f').css("margin-top", -($(".ins-ban .bgimg").height() - ($(window).height()-70)) / 2);
		   	};
		}else{
			if ($(".ins-ban .bgimg").width() > 0) {
		    	obj.addClass('h-f').css("margin-left", -($(".ins-ban .bgimg").width() - $(window).width()) / 2);
		    }
		}
	}
	$(window).resize(resizeBg).trigger("resize");
}
function Fullinsbg(box, obj){
	function resizeBg() {
		obj.removeClass("w-f").removeClass("h-f").css("margin", 0)
		var boxR = $(window).width() / ($(window).height()-70),
			objR = 1920 / 906;
		if( objR < boxR ) {
			if ($(".ins-ban .bgimg").height() > 0) {
		    	obj.addClass('w-f').css("margin-top", -($(".ins-ban .bgimg").height() - ($(window).height()-70)) / 2);
		   	};
		}else{
			if ($(".ins-ban .bgimg").width() > 0) {
		    	obj.addClass('h-f').css("margin-left", -($(".ins-ban .bgimg").width() - $(window).width()) / 2);
		    }
		}
	}
	$(window).resize(resizeBg).trigger("resize");
}

var isIE = function(){
	if ((navigator.appName == "Microsoft Internet Explorer" && navigator.appVersion.match(/7./i)=="7.") || (navigator.appName == "Microsoft Internet Explorer" && navigator.appVersion.match(/8./i)=="8.") || (navigator.appName == "Microsoft Internet Explorer" && navigator.appVersion.match(/9./i)=="9.")) {
		return true;
	}else{
		return false;
	}
}