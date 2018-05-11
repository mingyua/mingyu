$(document).ready(function(){
	$('#dbPwd').blur();
	$(".table tr").each(function(){ $(this).children("td").eq(0).addClass("on");});
	$("input[type='text']").addClass("input_blur").mouseover(function(){ $(this).addClass("input_focus");}).mouseout(function(){$(this).removeClass("input_focus");});
	$(".table tr").mouseover(function(){ $(this).addClass("mouseover");}).mouseout(function(){$(this).removeClass("mouseover");	});
});
$.ajaxSetup ({ cache: false });
function TestDbPwd(){
	var dbHost = $('#dbHost').val();
	var dbUser = $('#dbUser').val();
	var dbPwd = $('#dbPwd').val();
	var dbName = $('#dbName').val();
	var dbPort = $('#dbPort').val();
	data={'dbHost':dbHost,'dbUser':dbUser,'dbPwd':dbPwd,'dbName':dbName,'dbPort':dbPort};
	var url =  "./index.php?step=3&testdbpwd=1";
	$.ajax({
		 type: "POST",
		 url: url,
		 data: data,
		 beforeSend:function(){				 
		 },
		 success: function(msg){
			 if(msg){
				$('#pwd_msg').html("数据库配置正确");
				$('#dosubmit').attr("disabled",false);
				$('#dosubmit').removeAttr("disabled");				
				$('#dosubmit').removeClass("nonext");
			}else{
				$('#dosubmit').attr("disabled",true);
				$('#dosubmit').addClass("nonext");
				$('#pwd_msg').html("数据库链接配置失败");	
			}
		},
		complete:function(){
		},
		error:function(){
			$('#pwd_msg').html("数据库链接配置失败");						
		}
	});
}