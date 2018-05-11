<?php
namespace app\home\controller;
use think\Input;
use think\Db;
use cate\Cate;
use think\Request;
use think\Controller;
class Common extends Controller{
    protected $pagesize;
    public function _initialize(){
    	
        $this->CONTROLLER_NAME=strtolower(request()->controller());
        $this->ACTION=strtolower(request()->action());        

		$this->cate=new Cate();
		//系统信息
		$system=Db::name('config')->where('group','2')->field('id,name,value')->cache(0)->select();
		$this->sys=texttoarr($system);
		
		//站点信息
		$site=Db::name('config')->where('group','1')->field('id,name,value')->cache(0)->select();	
		$siteinfo=texttoarr($site);	
        $this->assign('site',$siteinfo);
		//首页导航
		$nav=Db::name('category')->where("ismenu='1'")->field('id,catname,catdir,parentid')->order('listorder ASC')->cache(0)->select();
		$navs=$this->cate->getchild($nav,'childarr','0');
		$this->assign('nav',$navs);
		//首页幻灯片
		$slide=Db::name('ad')->where("kinds='1'")->where("status='1'")->cache(0)->select();
		$this->assign('slide',$slide);
		
    	   	
    	$this->cate= Db::name('category')->where('id','eq',input('catid'))->find(); 
    	//要读取的数据库
		$this->dbname=$this->cate['module'];
		//分页显示数量
		$this->pagesize=$this->cate['pagesize'];
		//栏目标题、图片
		$banner= Db::name('category')->where('id','eq',$this->cate['parentid'])->find(); 
		if(!empty($this->cate['image'])){
			$catbanner=$this->cate['image'];
		}else{
			
			$catbanner=$banner['image'];
		}
		
		$count=Db::name('category')->where('parentid','eq',$this->cate['parentid'])->count(); 
		if($count>=4){
			$class="";
		}elseif($count==3){
			$class="2";
		}else{
			$class="3";
		}
		
		if(input('id')){
			$ar=Db::name($this->dbname)->where('id','eq',input('id'))->find();			
			$this->assign('seotitle',$ar['title']);
			$this->assign('seokeywords',$ar['keywords']);
			$this->assign('seodescription',$ar['description']);
		}elseif(input('catid')){
			$cate=Db::name('category')->where('id','eq',input('catid'))->find();
			$this->assign('seotitle',$cate['catname']);
			$this->assign('seokeywords',$siteinfo['WEB_SITE_KEYWORD']);
			$this->assign('seodescription',$siteinfo['WEB_SITE_DESCRIPTION']);
			
		}else{
			$this->assign('seotitle',$siteinfo['WEB_SITE_TITLE']);
			$this->assign('seokeywords',$siteinfo['WEB_SITE_KEYWORD']);
			$this->assign('seodescription',$siteinfo['WEB_SITE_DESCRIPTION']);
			
		}
		
		$this->assign('cattitle',$banner);
		$this->assign('banner',$catbanner);
		$this->assign('catid',input('catid'));
		$this->assign('class',$class);
		$this->assign('pid',$this->cate['parentid']);
		
		
    }
    public function _empty(){
        return $this->error('访问出错了，返回上次访问页面中...');
    }
}