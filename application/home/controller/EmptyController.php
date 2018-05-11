<?php
namespace app\home\controller;
use think\Db;
use \think\Cache;
use \think\Cookie;
use think\Request;
use \think\Controller;
use cate\Cate;
class EmptyController extends Common{

    public function _initialize()
    {
        parent::_initialize();
        $this->htmlname=ltrim($_SERVER['REQUEST_URI'],'/');
        
    }
    public function index(){
		if($this->dbname=='page'){
			$info=Db::name($this->dbname)->where('catid','eq',input('catid'))->find(); 
			$this->assign('info',$info);
			$tempelte=preg_replace('/_/', '/', $this->cate['template_list'], 1); //只替换一次
			if($this->sys['HTML_CACHE']==1){
				$this->buildHtml($this->htmlname,'./',$tempelte);
			}
			return $this->fetch($tempelte);
		} 
		if($this->dbname=='article'){
			$id=input('id');
			if(isset($id)){
				$list=Db::name($this->dbname)->where('id','eq',$id)->find();
				$tempelte=preg_replace('/_/', '/', $this->cate['template_show'], 1); //只替换一次	
				$this->assign('atitle',$list['title']);						
			}else{
				$list=Db::name($this->dbname)->where('catid','eq',input('catid'))->paginate($this->pagesize);
				$tempelte=preg_replace('/_/', '/', $this->cate['template_list'], 1); //只替换一次						
			}
			$this->assign('info',$list);
			$this->assign('list',$list);
			if($this->sys['HTML_CACHE']==1){
				$this->buildHtml($this->htmlname,'./',$tempelte);	
			}	
			return $this->fetch($tempelte);
		} 		 	

    }
    
 

}
