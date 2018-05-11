<?php
use think\Route;
// 注册路由到index模块的News控制器的read操作
Route::pattern([
			'name' 						=> 		'\w+',
			'id'						=> 		'\d+',
			'catid' 					=> 		'\d+',
		]);
		


//首页
Route::get('/','home/index/index');		
Route::get('index','home/index/index');	
//关于我们
Route::get('about/','home/about/index?catid=34');		
Route::get('about-<catid>','home/about/index',[],['catid'=>'\d+']);	
//新闻中心	
Route::get('news/','home/news/index?catid=6');		
Route::get('news-<catid>','home/news/index',[],['catid'=>'\d+']);	
Route::get('news-<catid>-<id?>','home/news/index',[],['catid'=>'\d+','id'=>'\d+']);	
//主营业务
Route::get('services/','home/services/index?catid=40');		
Route::get('services-<catid>','home/services/index',[],['catid'=>'\d+']);	
Route::get('services-<catid>-<id?>','home/services/index',[],['catid'=>'\d+','id'=>'\d+']);	
//党群工作	
Route::get('worker/','home/worker/index?catid=47');		
Route::get('worker-<catid>','home/worker/index',[],['catid'=>'\d+']);	
Route::get('worker-<catid>-<id?>','home/worker/index',[],['catid'=>'\d+','id'=>'\d+']);	
//联系我们	
Route::get('contact/','home/contact/index?catid=50');		
Route::get('contact-<catid>','home/contact/index',[],['catid'=>'\d+']);	
//采购平台	
Route::get('product/','home/product/index?catid=43');		
Route::get('product-<catid>','home/product/index',[],['catid'=>'\d+']);	

Route::rule('404','home/index/backpage');	
	
		



