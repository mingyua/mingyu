//鼠标悬停div边框动画
//<div class="num num1">num1</div>
//<div class="num num2">num2</div>
//<div class="num num3">num3</div>
//<div class="num num4">num4</div>
//<div class="num num5">num5</div>
//<div class="num num6">num6</div>
//<script type="text/javascript">
//$(".num1").num1();
//$(".num2").num2();
//$(".num3").num3();
//$(".num4").num4();
//$(".num5").num5();
//$(".num6").num6();
//</script>

(function($){
	$.fn.num1 = function(){
		n1 = $(this).width();
		h1 = $(this).height();

		
		$(this).hover(function(){
			var becurr = "background:#ff0000;position:absolute;"
			// top边框
			var divTop ="<div style='"+becurr+"top:-2px;left:-2px;width:0px;height:2px'></div>";

			// right边框
			var divRight ="<div style='"+becurr+"bottom:-2px;right:-2px;width:2px;height:0px;'></div>";

			// Bottom边框
			var divBottom ="<div style='"+becurr+"bottom:-2px;right:-2px;width:0px;height:2px'></div>";

			// Left边框
			var divLeft ="<div style='"+becurr+"top:-2px;left:-2px;width:2px;height:0px;'></div>"; 

			$(this).append(divTop,divRight,divBottom,divLeft);

			$(this).find("div:nth-child(odd)").stop().animate({width:n1+3.5},300);
			$(this).find("div:nth-child(even)").stop().animate({height:h1+3.5},300);
		},function(){
			$(this).find("div:nth-child(odd)").stop().animate({width:0},300);
			$(this).find("div:nth-child(even)").stop().animate({height:0},300);
		})
	}
})(jQuery);

	
(function($){
	$.fn.num2 = function(){
		$(this).hover(function(){
			// 通用Class
			var becurr = "background:#F95F5F;position:absolute;border-radius:10px;opacity:0;"
			// top边框
			var divTop = "<div style='"+becurr+"height:2px;width:50px;top:-2px;left:-80px;'></div";

			// right边框
			var divRight = "<div style='"+becurr+"height:50px;width:2px;top:-80px;right:-2px;'></div";

			// Bottom边框
			var divBottom = "<div style='"+becurr+"height:2px;width:50px;bottom:-2px;right:-80px;'></div";

			// Left边框
			var divLeft = "<div style='"+becurr+"height:50px;width:2px;bottom:-80px;left:-2px;'></div";
			
			$(this).append(divTop,divRight,divBottom,divLeft);

			$(this).find("div:nth-child(1)").stop().show().animate({left:-2,opacity:1},250).fadeOut(0);
			$(this).find("div:nth-child(2)").stop().show().animate({top:-2,opacity:1},250).fadeOut(0);
			$(this).find("div:nth-child(3)").stop().show().animate({right:-2,opacity:1},250).fadeOut(0);
			$(this).find("div:nth-child(4)").stop().show().animate({bottom:-2,opacity:1},250).fadeOut(0);
		},function(){
			$(this).find("div:nth-child(1)").stop().show().animate({left:-80,opacity:0},250).hide(0);
			$(this).find("div:nth-child(2)").stop().show().animate({top:-80,opacity:0},250).hide(0);
			$(this).find("div:nth-child(3)").stop().show().animate({right:-80,opacity:0},250).hide(0);
			$(this).find("div:nth-child(4)").stop().show().animate({bottom:-80,opacity:0},250).hide(0);
		})
	}
})(jQuery);


(function($){
	$.fn.num3 = function(){
		n1 = $(this).width();
		h1 = $(this).height();

		
		$(this).hover(function(){
			var becurr = "background:#ff0000;position:absolute;"
			// top边框
			var divTop ="<div style='"+becurr+"top:-2px;left:-2px;width:0px;height:2px'></div>";

			// right边框
			var divRight ="<div style='"+becurr+"top:-2px;right:-2px;width:2px;height:0px;'></div>";

			// Bottom边框
			var divBottom ="<div style='"+becurr+"bottom:-2px;right:-2px;width:0px;height:2px'></div>";

			// Left边框
			var divLeft ="<div style='"+becurr+"bottom:-2px;left:-2px;width:2px;height:0px;'></div>"; 

			$(this).append(divTop,divRight,divBottom,divLeft);

			$(this).find("div:nth-child(odd)").stop().animate({width:n1+3.5},300);
			$(this).find("div:nth-child(even)").stop().animate({height:h1+3.5},300);
		},function(){
			$(this).find("div:nth-child(odd)").stop().animate({width:0},300);
			$(this).find("div:nth-child(even)").stop().animate({height:0},300);
		})
	}
})(jQuery);



(function($){
	$.fn.num4 = function(){
		n1 = $(this).width();
		h1 = $(this).height();

		
		$(this).hover(function(){
			var becurr = "background:#ff0000;position:absolute;"
			// top边框
			var divTop ="<div style='"+becurr+"top:-2px;left:-2px;width:0px;height:2px'></div>";

			// right边框
			var divRight ="<div style='"+becurr+"top:-2px;right:-2px;width:2px;height:0px;'></div>";

			// Bottom边框
			var divBottom ="<div style='"+becurr+"bottom:-2px;right:-2px;width:0px;height:2px'></div>";

			// Left边框
			var divLeft ="<div style='"+becurr+"bottom:-2px;left:-2px;width:2px;height:0px;'></div>"; 

			$(this).append(divTop,divRight,divBottom,divLeft);

			$(this).find("div").eq(0).stop().animate({width:n1+3.5},200,function(){
				$(this).parent().find("div").eq(1).stop().animate({height:h1+3.5},200,function(){
					$(this).parent().find("div").eq(2).stop().animate({width:n1+3.5},200,function(){
						$(this).parent().find("div").eq(3).stop().animate({height:h1+3.5},200)
					});
				});
			})
		},function(){
			$(this).find("div").stop();
			$(this).find("div").eq(3).stop().animate({height:0},200,function(){
				$(this).parent().find("div").eq(2).stop().animate({width:0},200,function(){
					$(this).parent().find("div").eq(1).stop().animate({height:0},200,function(){
						$(this).parent().find("div").eq(0).stop().animate({width:0},200)
					});
				});
			})

		})
	}
})(jQuery);




(function($){
	$.fn.num5 = function(){
		n1 = $(this).width();
		h1 = $(this).height();

		
		$(this).hover(function(){
			var becurr = "background:#ff0000;position:absolute;"
			// top边框
			var divTop ="<div style='"+becurr+"top:-2px;left:-2px;width:0px;height:2px'></div>";

			// right边框
			var divRight ="<div style='"+becurr+"top:-2px;right:-2px;width:2px;height:0px;'></div>";

			// Bottom边框
			var divBottom ="<div style='"+becurr+"bottom:-2px;right:-2px;width:0px;height:2px'></div>";

			// Left边框
			var divLeft ="<div style='"+becurr+"bottom:-2px;left:-2px;width:2px;height:0px;'></div>"; 

			$(this).append(divTop,divRight,divBottom,divLeft);

			$(this).find("div:nth-child(odd)").stop().animate({width:n1+3.5},300,function(){
				$(this).parent().find("div:nth-child(even)").stop().animate({height:h1+3.5},300);
			});
			
		},function(){
			$(this).find("div:nth-child(odd)").stop().animate({width:0},300,function(){
				$(this).parent().find("div:nth-child(even)").stop().animate({height:0},300);
			});
			
		})
	}
})(jQuery);



(function($){
	$.fn.num6 = function(){
		n1 = $(this).width();
		h1 = $(this).height();

		var becurr = "background:#ff0000;position:absolute;"
			// top边框
			var divTop ="<div style='"+becurr+"top:-2px;left:"+n1/2+"px;width:0;height:2px'></div>";

			// right边框
			var divRight ="<div style='"+becurr+"top:"+h1/2+"px;right:-2px;width:2px;height:0;'></div>";

			// Bottom边框
			var divBottom ="<div style='"+becurr+"bottom:-2px;right:"+n1/2+"px;width:0;height:2px'></div>";

			// Left边框
			var divLeft ="<div style='"+becurr+"bottom:"+h1/2+"px;left:-2px;width:2px;height:0;'></div>"; 

			$(this).append(divTop,divRight,divBottom,divLeft);
		$(this).hover(function(){
			

			$(this).find("div:nth-child(odd)").stop().animate({width:n1+3.5,left:-2},300);
			$(this).find("div:nth-child(even)").stop().animate({height:h1+3.5,top:-2},300);
		},function(){
			$(this).find("div:nth-child(odd)").stop().animate({width:0,left:n1/2},300);
			$(this).find("div:nth-child(even)").stop().animate({height:0,top:h1/2},300);
		})
	}
})(jQuery);