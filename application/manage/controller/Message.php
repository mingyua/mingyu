<?php
// +----------------------------------------------------------------------
// | Tplay [ WE ONLY DO WHAT IS NECESSARY ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.wxappjz.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: mingyu < 285412937@qq.com >
// +----------------------------------------------------------------------


namespace app\manage\controller;

use \think\Cache;
use \think\Controller;
use think\Loader;
use think\Db;
use \think\Cookie;
use fields\Fields;
class Message extends User
{
	protected $dao;
    function _initialize()
    {        
    	parent::_initialize();

    }
	
    public function index()
    {
    	$test=new Fields();
    	
    	$ff=Db('field')->where("moduleid='3'")->order("listorder ASC")->select();
    	$value=Db('clt_article')->where("id='1'")->find();
    	$data=$test::textcontent($ff,$value);   
    	
    	foreach($ff as $k=>$v){
    		 $info = is_array($v['setup']) ? $v['setup'] : string2array($v['setup']);
    		 $option=isset($info['options']) ? $info['options']:'';
    		 $options = explode("\n", $option);    		 
    		 $optionsarr=array();
    		if(!empty($option)){
    			
	            foreach ($options as $r) {
	                $v = explode("|", $r);
	                $k = trim($v[1]);
	                
	                //$cc='<input name="'.$v["field"].'" lay-verify="'.$req.'" autocomplete="off" placeholder="请输入标题" id="'.$v["class"].'" class="layui-input" type="radio" value="">';
	                $optionsarr[$k] = $v[0];
	            }	
	    	}
    		//dump($optionsarr);
    	}
    	
    	//dump($ff);
    	$this->assign('html',$data);
      return $this->fetch();
       

		
       
    }

}