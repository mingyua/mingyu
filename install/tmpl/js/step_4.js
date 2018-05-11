$(document).ready(function(){
	$(".table tr").each(function(){ $(this).children("td").eq(0).addClass("on");});
	$("input[type='text']").addClass("input_blur").mouseover(function(){ $(this).addClass("input_focus");}).mouseout(function(){$(this).removeClass("input_focus");});
	$(".table tr").mouseover(function(){ $(this).addClass("mouseover");}).mouseout(function(){$(this).removeClass("mouseover");	});
});