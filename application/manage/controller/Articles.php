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
use app\manage\controller\User;
use app\manage\model\Article as articleModel;
use app\manage\model\Category as cateModel;
class Article extends User
{
    public function index()
    {
    	$post = $this->request->param();
    	foreach( $post as $k=>$v){
			if( $v==null )
			unset( $post[$k] );
		}
		if (isset($post['keywords'])) {
            $where['title'] = ['like', '%' . $post['keywords'] . '%'];
        } 
		if (isset($post['status'])) {
            $where['status'] = $post['status'];
        } 
        if(isset($post['create_time'])) {
            $min_time = strtotime($post['create_time']);
            $max_time = $min_time + 24 * 60 * 60;
            $where['createtime'] =['between time', [$min_time,$max_time]];
        }
		if (isset($post['cate_id'])) {
			$cate=Db::name('category')->where('id='.$post['cate_id'])->find();
	        $where['catid']=['in',$cate['arrchildid']];       
	    }else{
            $cate=Db::name('category')->where('id='.$post['catid'])->find();      	  	
	    	$where['catid']=['in',$cate['arrchildid']];        		    	
	    }	    
    	$list=empty($where)?Db::name('article')->where($where)->order('id DESC')->paginate(18) : Db::name('article')->where($where)->order('id DESC')->paginate(18,false,['query'=>$post]);
    	$model = new cateModel();
		$cates = $model->select();
		$category = $model->catelist($cates);			
	 	foreach($category as $k=>$v){
	 		if(strpos($v['module'],'page')!==false){
	 			unset($category[$k]);
	 		}
	 	}	    	
    	$this->assign('category',$category);
    	$this->assign('cate',$cate);
    	$this->assign('list',$list);
		
       return $this->fetch();
    }
    public function page()
    {
    	if($this->request->isPost()) {
    		$data = input('post.');
    		$id=isset($data['id']) ? $data['id']:0;
    		
    		if($data['style_color']){
    			$color="color:".$data['style_color'].";";
    		}else{
    			$color='';
    		}
    		if(isset($data['style_bold'])){
    			$bold="font-weight:".$data['style_bold'].";";
    		}else{
    			$bold="font-weight:normal;";
    		}
    		$data['title_style']=$color.$bold;
    		$data['username']=Cookie::get('username'); 	
    			
    		$model = new articleModel();
    		if($id>0){//修改
    			$data['updatetime']=strtotime($data['addtime']);
    			if(false == $model->allowField(true)->save($data,['id'=>$id])) {
	            	return ['msg'=>'修改失败','result'=>2];
	            } else {
                    //addlog($model->id);//写入日志
	            	return ['msg'=>'修改成功','result'=>1];
	            }
    			
    		}else{//新增
    			$data['userid']=Cookie::get('manage'); 
    			$data['createtime']=strtotime($data['addtime']);
    			if(false == $model->allowField(true)->save($data)) {
	            	return ['msg'=>'添加失败','result'=>2];
	            } else {
                    //addlog($model->id);//写入日志
	            	return ['msg'=>'添加成功','result'=>1];
	            }

    			
    		}
    		
    	}else{
		    $id=input('param.id');
		    $info=Db::name('category')->where("id=".$id)->find();	    
		 	$article=Db::name('article')->where("catid=".$id)->find();
		 	$templates= template_file();
		 	$name = request()->action();
		 	foreach($templates as $k=>$v){
		 		if(strpos($v['value'],$name)===false){
		 			unset($templates[$k]);
		 		}
		 	}	 	
	        $this->assign ('templates',$templates );
		    $this->assign('title','修改');
		    $this->assign('cate',null);
		    $this->assign('model','page');
		    $this->assign('info',$info);
		    $this->assign('article',$article);
	    return $this->fetch('publish');   		
    	}
    }


    public function publish()
    {
    	if($this->request->isPost()){
    		$model = new articleModel();
    		$post=$this->request->post();
    		$id=isset($post['id']) ? $post['id']:0;
    		if($post['style_color']){
    			$color="color:".$post['style_color'].";";
    		}else{
    			$color='';
    		}
    		if(isset($post['style_bold'])){
    			$bold="font-weight:".$post['style_bold'].";";
    		}else{
    			$bold="font-weight:normal;";
    		}
    		$post['title_style']=$color.$bold;    		
    		$time=isset($post['addtime']) ? $post['addtime']:date('Y-m-d H:i:s',time()); 
    		 		
    		 $post['username']=Cookie::get('username'); 		
    		
    		if($id>0){//修改
    			$post['updatetime']=strtotime($time);
    			if(false == $model->allowField(true)->save($post,['id'=>$id])) {
	    			return ['msg'=>'数据修改失败！','result'=>2];
    			}else{
	    			return ['msg'=>'数据修改成功！','result'=>1];
    				
    			}
    			
    		}else{//增加
    			$post['createtime']=strtotime($time);
    			$post['updatetime']=strtotime($time);
    			 $post['userid']=Cookie::get('manage'); 		
    			if(false == $model->allowField(true)->save($post)) {
	    			return ['msg'=>'数据添加失败！','result'=>2];
    			}else{
	    			return ['msg'=>'数据添加成功！','result'=>1];
    				
    			}
    		}
    		
    	}else{
     		$module = db('module')->where('status',1)->field('id,title,name')->select();
       		$this->assign('modulelist',$module);
			$model = new cateModel();
			//$cate = $model->where('id',$id)->find();
			$cates = $model->select();
    		$cate = $model->catelist($cates);			
		 	$templates= template_file();
		 	$name = request()->action();
		 	foreach($templates as $k=>$v){
		 		if(strpos($v['value'],'_show')===false){
		 			unset($templates[$k]);
		 		}
		 	}
		 	foreach($cate as $k=>$v){
		 		if(strpos($v['module'],'page')!==false){
		 			unset($cate[$k]);
		 		}
		 	}	
		 	$amodel=new articleModel(); 
		 	$id=input('id');
		 	if($id){
		 		$article=$amodel->where('id='.$id)->find();		
		 	}else{
		 		$article=null;
		 	}
		 		 		 		 	
	        $this->assign ('templates',$templates );
		    $this->assign('title','添加');
		    $this->assign('cate',$cate);
		    $this->assign('model','publish');
		    $this->assign('info',null);
		    $this->assign('article',$article);
		    
		   

    	return $this->fetch();
   		
    	}	
    	
    }


    public function delete()
    {
    	if($this->request->isAjax()) {
    		$id = $this->request->has('id') ? $this->request->param('id', 0, 'intval') : 0;
            if(false == Db::name('article')->where('id',$id)->delete()) {
                return ['msg'=>'删除失败','result'=>2];
            } else {
                //addlog($id);//写入日志
                return ['msg'=>'删除成功','result'=>1];
                
            }
    	}
    }


    public function is_top()
    {
        if($this->request->isPost()){
            $post = $this->request->post();
            if(false == Db::name('article')->where('id',$post['id'])->update(['is_top'=>$post['is_top']])) {
                return $this->error('设置失败');
            } else {
                addlog($post['id']);//写入日志
                return $this->success('设置成功','admin/article/index');
            }
        }
    }


    public function status()
    {
        if($this->request->isPost()){
            $post = $this->request->post();
            if(false == Db::name('article')->where('id',$post['id'])->update(['status'=>$post['status']])) {
                return $this->error('设置失败');
            } else {
                addlog($post['id']);//写入日志
                return $this->success('设置成功','admin/article/index');
            }
        }
    }
}
