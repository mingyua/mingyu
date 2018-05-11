<?php
namespace app\home\controller;
use think\Controller;
use think\Input;
use fields\Fields;
use think\Db;
use think\Request;
class Index extends Common{
    public function _initialize(){
        parent::_initialize();
        
    }
    public function index(){
    	
    	$newcat=Db::name('category')->where("parentid='1'")->order('listorder ASC')->select();    	
    	$id=implode(',',array_column($newcat,'id'));
    	$data=Db::name('article')->where('catid','in',$id)->order("id DESC")->limit(4)->select();
    	foreach($data as $kk=>$vv){
    		$cid=$vv['catid'];
    		foreach($newcat as $k=>$v){
     			if($cid==$v['id']){
    				$newcat[$k]['arr'][]=$vv;
    			}
   			
    		}
    			
    	} 
    	$this->assign('newlist',$newcat);
    	if($this->sys['HTML_CACHE']==1){
    		$this->buildHtml('index.html','./','index/index');	
    	}
        return $this->fetch();
    }
  
    public function backpage(){
    	
    	return $this->fetch('index/404');
    }

}