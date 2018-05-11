$(function(){
	$(".i-ban").height($(window).height() - 70);

	$(".float-right-box").removeClass('on');

	$(".i-ban li .link").on('click', function() {
		$("body,html").stop().animate({scrollTop: $(window).height() - 70}, 500, "easeOutCubic");
	});
	$(".i-m-ban .link").on('click', function() {
		$("body,html").stop().animate({scrollTop: $(".i-fir-wrap").offset().top - 60}, 500, "easeOutCubic");
	});

	scrollactive();
	$(window).scroll(function() {
		scrollactive();
	});

    var swiper = new Swiper('.i-m-ban', {
        loop : true,
        slidesPerView: 1,
        autoplay : 5000,
        speed: 5000,
        pagination : '.i-m-ban .page-btns',
        onAutoplay: function(swiper){
			// console.log(swiper.activeIndex) //切换结束时，告诉我现在是第几个slide
			var q = swiper.activeIndex;
			var s;
			if (q == $(".i-m-ban .page-btns span").length+1) {
				s = 0;
			}else{
				s = swiper.activeIndex-1;
			}
			$(".i-m-ban .page-btns span i").stop().attr("style","");
			if (s == 0) {
				$(".i-m-ban .page-btns span").eq(s).children('i').stop().width("70%");
			}else{
				$(".i-m-ban .page-btns span").eq(s).children('i').stop().width("70%");
			}
			for (var a = 0; a <= s-1; a++) {
				$(".i-m-ban .page-btns span").eq(a).children('i').stop().width("70%");
			};
	    },
	    onSlideNextEnd: function(swiper, event){
	      	var q = swiper.activeIndex;
			var s;
			if (q == $(".i-m-ban .page-btns span").length+1) {
				s = 0;
			}else{
				s = swiper.activeIndex-1;
			}
			// console.log(s)
			$(".i-m-ban .page-btns span i").stop().attr("style","");
			for (var a = 0; a <= s; a++) {
				$(".i-m-ban .page-btns span").eq(a).children('i').stop().width("70%");
			};
	    },
	    onSlidePrevEnd: function(swiper, event){
	      	var q = swiper.activeIndex;
			var s;
			if (q == 0) {
				s = $(".i-m-ban .page-btns span").length;
			}else{
				s = swiper.activeIndex-1;
			}
			// console.log(s)
			$(".i-m-ban .page-btns span i").stop().attr("style","");
			for (var a = 0; a <= s; a++) {
				$(".i-m-ban .page-btns span").eq(a).children('i').stop().width("70%");
			};
	    }
    });
    for (var i = 0; i < $(".i-m-ban .page-btns span").length; i++) {
    	$(".i-m-ban .page-btns span").eq(i).append("<i></i>");
    };
    $(".i-m-ban .page-btns span").eq(0).children('i').stop().width("70%");

	var swiper1 = new Swiper('.i-thi-wrap .mobile-scroll', {
        loop : true,
        slidesPerView: 1,
        paginationClickable : true,
        autoplay : 5000,
        pagination : '.i-thi-wrap .pagination'
    });

	var hudongvideo = document.getElementById("hudong-video");
    $(".i-fiv-wrap .img").hover(function() {
    	hudongvideo.play();
    }, function() {
    	hudongvideo.pause();
    });
    

	$(".i-thi-wrap .nav-box .left-link span").eq(0).addClass('cur');
})
window.onload = function(){
	FullBg($(".i-fiv-wrap .img"), $(".i-fiv-wrap .video-lab"));
}
