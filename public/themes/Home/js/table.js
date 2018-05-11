$(function () {
   $('.tab2 ul.menu li').click(function(){
        //获得当前被点击的元素索引值
         var Index = $(this).index();
		//给菜单添加选择样式
	    $(this).addClass('active').siblings().removeClass('active');
	
		//显示对应的div
		$('.tab2').children('div').eq(Index).show().siblings('div').hide();
   
   });
});


$(function () {
   $('.tab3 ul.menu li').click(function(){
        //获得当前被点击的元素索引值
         var Index = $(this).index();
		//给菜单添加选择样式
	    $(this).addClass('active').siblings().removeClass('active');
	
		//显示对应的div
		$('.tab3').children('div').eq(Index).show().siblings('div').hide();
   
   });
});



$(function () {
   $('.tab4 ul.menu li').click(function(){
        //获得当前被点击的元素索引值
         var Index = $(this).index();
		//给菜单添加选择样式
	    $(this).addClass('active').siblings().removeClass('active');
	
		//显示对应的div
		$('.tab4').children('div').eq(Index).show().siblings('div').hide();
   
   });
});

	$(document).ready(
	function() 
	{
		$(".menuTitle3").click(function(){
			$(this).next("div").slideToggle("slow")
			.siblings(".menuContent3:visible").slideUp("slow");
			$(this).toggleClass("activeTitle3");
			$(this).siblings(".activeTitle3").removeClass("activeTitle3");
		});
	});




	$(document).ready(
	function() 
	{
		$(".menuTitle5").click(function(){
			$(this).next("div").slideToggle("slow")
			.siblings(".menuContent5:visible").slideUp("slow");
			$(this).toggleClass("activeTitle5");
			$(this).siblings(".activeTitle5").removeClass("activeTitle5");
		});
	});

