var layer,Tab;
layui.use(['element', 'laydate', 'upload', 'layer', 'form','table','tab'], function() {
		var $ = layui.jquery,
			element = layui.element,
			upload = layui.upload,
			layer = layui.layer,
			table = layui.table,
			laydate = layui.laydate,
			form = layui.form; //Tab的切换功能，切换事件监听等，需要依赖element模块
		//执行实例
		//普通图片上传
	Tab = layui.tab({ 
    	elem: '.layui-tab' ,
    	maxSetting: {
    		max: 20,
    		tipMsg: '最多只能开启20个'
    	},
    	contextMenu:true,
        autoRefresh:true
    });
		var uploadInst = upload.render({
			elem: '#test1',
			url: upfileurl,
			before: function(obj) {
				//预读本地文件示例，不支持ie8
				obj.preview(function(index, file, result) {
					$('#demo1').attr('src', result); //图片链接（base64）			       
				});
			},
			done: function(res) {
				//如果上传失败
				if(res.code > 0) {
					return layer.msg('上传失败');
				}
				$('#img').val(res.src);
				//上传成功
			},
			error: function() {
				//演示失败状态，并实现重传
				var demoText = $('#demoText');
				demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-mini demo-reload">重试</a>');
				demoText.find('.demo-reload').on('click', function() {
					uploadInst.upload();
				});
			}
		});

		element.on('tab(docDemoTabBrief)', function(data) {
			var group = data.index;
			var text = this.innerHTML;
			$.ajax({
				type: "get",
				url: taburl,
				data: {
					group: group
				},

				success: function(result) {

					$('.layui-show').html(result.html);

				},
				error: function() {
					layer.msg('切到到了' + taburl+group + '：' + text);
				}
			});

			//layer.msg('切到到了'+ data.index + '：' + this.innerHTML);
		});
		//监听提交
		form.on('submit(senddata)', function(data) {
			var url = $(this).attr('ajaxurl');
			//alert(url);

			$.ajax({
				type: "post",
				url: url,
				data: data.field,
				beforeSend: function() {
					layer.msg('正在处理数据...');
				},
				success: function(data) {
					//document.getElementById('tip').innerHTML= JSON.stringify(data);
					//return;
					
					layer.msg(data.msg, {
						icon: data.result,
						time: 2000
					}, function() {
						var index = parent.layer.getFrameIndex(window.name);
						parent.layer.close(index);
					});
				},
				error: function(data) {
					layer.msg('网络错误', {
						icon: 2
					}); //document.getElementById('tip').innerHTML="网络错误";
				}
			});

		});

		form.on('select(moduleid)', function(data) {
			showtemplist(data.value, 0, form);
		});
		laydate.render({
			elem: '#create_time',
			type: 'datetime'
		});
		form.on('switch(status)', function(obj) {
			var url = $(this).attr('ajaxurl');
			
			loading = layer.load(1, {
				shade: [0.1, '#fff']
			});
			var id = this.value;
			var status = obj.elem.checked === true ? 1 : 0;
			$.post(url, {
				'id': id,
				'status': status
			}, function(res) {
				layer.close(loading);
				if(res.result == 1) {
					tableIn.reload();
				} else {
					layer.msg(res.msg, {
						time: 1000,
						icon: 2
					});
					return false;
				}
			})
		});
		
		form.on('checkbox(allck)', function(data){
		    var child = $(data.elem).parents('table').find('tbody input[name="allck"]');
		    child.each(function(index, item){
		        item.checked = data.elem.checked;
		    });
		    form.render('checkbox');
		});
		form.on('submit(delall)', function(data){
			var cked=$('tbody').find('input[name="allck"]:checked');			
			var id='',url=$(this).attr('ajaxurl');
			cked.each(function(i){
			   id += $(this).val()+",";
			});
			if(cked.length<=0){
				layer.msg('至少选择一项！',{icon:0});
				return false;
			}
			layer.confirm('删除后将无法恢复！您确定要删除所选吗？',function(data){
				$.ajax({
					type:"post",
					url:url,
					data:{id:id},
					success:function(result){
						if(result.result==1){	
							window.location.reload();
							layer.msg(result.msg,{icon:result.result});
						}else{
							layer.msg(result.msg,{icon:result.result});						
						}
					},
					error:function(result){
						layer.msg('网络错误！',{icon:0});
					}
				});				
			});
		});
		$('body').on('click','.tagtip',function(){
			var tip=this.id;
			
			layer.tips(tip,this,{ tips: [1, '#FF7D08']});
		});

		
	});
	
$('#gloMenu>li').each(function(){
    var childLen  = $(this).find('.navC').find('li').length;
    if(childLen) {
        var html = $(this).find('.navT').find('a').html();
         $(this).find('.navT').html('<span>'+html+'</span>') ;
    }
})

$('#gloMenu').on('click','.navT',function(){
    var parent  = $(this).closest('li');
    var index   = parent.index();
    if(parent.find('.navC').find('li').length){
        if(parent.hasClass('open')){
            parent.find('.navC').stop(true).slideUp(300,function(){ parent.removeClass('open')}) ;
        }else{
            var openLi  = $('#gloMenu').find('li.open') ;
            openLi.removeClass('open').find('.navC').stop(true).slideUp(300) ;
            parent.addClass('open').find('.navC').stop(true).slideDown(300) ;
        }
        
    }
})

$('#gloMenu').on('click','a',function(){
    //if(!$(this).hasClass('isNav')) return false ;
    var href  = $(this).attr('href');
    var title = $(this).attr('data-title') || $(this).attr('title');
    if(!title)  title=$(this).text();        
    var icon  = $(this).attr('data-icon') || $(this).find('i.fa').attr('data-icon');        
    
    
    $('#gloMenu').find('a.current').removeClass('current');
    $(this).addClass('current') ;
    
    
    Tab.tabAdd({
        title: title,
        href : href,
        icon : icon
    })
    return false ;
})

    
$('#gloTop').find('.menuBar').click(function(){
    if($('#gloBox').hasClass('menu_close')){
        $('#gloBox').removeClass('menu_close') ;
    }else{
        $('#gloBox').addClass('menu_close') ;
    }
})

$('.skin-down').hover(function(){
    $(this).find('.skin-show').stop(true,true).slideDown(300);
},function(){
    $(this).find('.skin-show').stop(true,true).slideUp(300);
})



function newTime(){
    var now  = new Date();
    var year = now.getFullYear() ;
    var month = (now.getMonth()+1) >=10 ? (now.getMonth()+1): '0' + (now.getMonth()+1);
    var date  = now.getDate() >=10 ? now.getDate(): '0' + now.getDate();
    var hour = now.getHours() >=10 ? now.getHours(): '0' + now.getHours();
    var minute = now.getMinutes() >=10 ? now.getMinutes(): '0' + now.getMinutes();
    var second = now.getSeconds() >=10 ? now.getSeconds(): '0' + now.getSeconds();
    var datetime = year + '-' + month + '-' + date + ' ' + hour + ':' + minute + ':' + second;
    $('.showtime').html(datetime);
    $('.lockTime').html(hour + ':' + minute + ':' + second)
}
newTime();
setInterval(newTime,1000);






//同时按下alt+L锁屏
document.onkeydown = function(event){
    if (event.keyCode == 76 && event.altKey){
        lockScreen()
    }
}


//锁屏
function lockScreen(){
    if($('#lockScreen').is(':visible')) return false ; 
    $('#screenPwd').val('');   
    $('#lockScreen').fadeIn(300, function(){
        $('#closeLock').addClass('shake');
    })
    var url = "/run/tool/lock_screen.html";
    HKUC.ajax_request.call(this,url,null,
    	{
    		'success':function(msg,data){
  		        layer.closeAll();
    		},
    		'error':function(msg,data){
                  layer.closeAll();
                  layer.msg(msg)
    		}
    	}
    );
}

$('#screenPwd').keyup(function(event){
    if (event.keyCode == 13) {
        $('#closeLock').trigger('click');
    }
})

$('#closeLock').click(function(){
    var url = "/run/tool/relieve_screen.html";
    var pwd = $.trim($('#screenPwd').val());
    if (!pwd) {
       layer.msg('请先输入密码'); 
       return false;
    }
    HKUC.ajax_request.call(this,url,{
            pwd : pwd
        },
    	{
    		'success':function(msg,data){
    		    layer.closeAll();
  		        $('#lockScreen').fadeOut(300);
    		},
    		'error':function(msg,data){
                  layer.closeAll();
                  layer.msg(msg);
    		}
    	}
    );
})

//resize
$(window).resize(function(){
    winWidth = $(window).width();
    heiHeght = $(window).height();
    $('#gloRght').height(heiHeght - 51);
    $('#gloLeft,#gloSLeft').css('height',(heiHeght - 51) + 'px')
    $('.layui-tab-content').height(heiHeght - 51 - 40);
    
}).trigger('resize')

//Tab
$(window).resize(function(){
    if(typeof(Tab) != 'undefined') Tab.resize();
})

$('.tab-prev').unbind('click').bind('click',function(){
    var left    = $('.layui-tab-title').position().left ;
    left  = left+117 <0 ? left+117 :0 ;
    $('.layui-tab-title').stop(true).animate({ left : left },500);
})

$('.tab-next').unbind('click').bind('click',function(){
    var left    = $('.layui-tab-title').position().left ;
    var boxWid  = $('.layui-tab-title').width() ;
    var liWid   = 0;
    $('.layui-tab-title').children('span').remove().end().find('li').each(function(){
        liWid += $(this).outerWidth() ;
    })
    left  = left-117 > -(liWid-boxWid) ? left-117 :-(liWid-boxWid);
    if(left>0)left =  0;
    $('.layui-tab-title').stop(true).animate({ left : left },500);
})

function full_screen(){
    var docElm = document.documentElement;
    //W3C
    if (docElm.requestFullscreen) {
    docElm.requestFullscreen();
    }
    //FireFox
    else if (docElm.mozRequestFullScreen) {
    docElm.mozRequestFullScreen();
    }
    //Chrome等
    else if (docElm.webkitRequestFullScreen) {
    docElm.webkitRequestFullScreen();
    }
    //IE11
    else if (docElm.msRequestFullscreen) {
    docElm.msRequestFullscreen();
    }
}