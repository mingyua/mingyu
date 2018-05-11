<?php
namespace app\manage\controller;
use think\Controller;
use think\Request;
use think\Db;
use wx\Tree;
use cate\Cate;
use app\manage\controller\User;
class Category extends User
{
    protected $dao, $categorys , $module;
    function _initialize()
    {
        parent::_initialize();

		$this->cate=new Cate();
		$this->categorys=model('category');
		$this->module=model('module');
    }
    public function index()
    {
		$category=$this->categorys->order('listorder ASC')->select();		
		$arr=$this->cate->Tree($category,0);
		//dump($arr);die();
        $this->assign('categorys', $arr);
        $this->assign('title','栏目列表');
        return $this->fetch();
    }

    public function add(){
    	
    	if($this->request->isPost()){//添加
    		$data = input('post.');
    		$data['module']=getmdname($data['moduleid'],'name');
    		$data['parentdir']=$this->parentdir($data['parentid']);
			if($data['parentid']==null){
	        	 $data['arrparentid']=0;
	        }else{
		        $data['arrparentid']=implode(',',$this->arrparentid($data['parentid'])) .",".$data['parentid'];	        	
	        }  	        
	        if($this->categorys->allowField(true)->save($data)!==false){
	        	$id=Db::name('category')->max('id');
	        	$this->categorys->save(['arrchildid'=>$id,'id'=>$id]);
	        	return ['msg'=>'添加成功','result'=>1];
	        }else{
	        	return ['msg'=>'添加失败','result'=>2];
	        }    		
    	}else{
	        $parentid =	input('param.parentid');
	        //模型列表
	        $module = $this->module->where('status',1)->field('id,title,name')->select();
	        $this->assign('modulelist',$module);
	        //父级模型ID        
	        if($parentid){
		        $modid=$this->categorys->where("id='$parentid'")->find();
		        //$vo['moduleid'] =$modid['moduleid'];
		        $this->assign('module', $modid);        	
	        }else{
	        	 $this->assign('module', null);
	        }		
	        //栏目选择列表
			$category=$this->categorys->order('listorder ASC')->select();		
			$arr=$this->cate->Tree($category,0);		
	        $this->assign('categorys', $arr);
	        //模版
	        $templates= template_file();
	        $this->assign ( 'templates',$templates );
	        $this->assign('title','添加栏目');
	        return $this->fetch();    		
    	}
    	
    }


    public function edit(){
    	if($this->request->isPost()){//修改
    		$data = input('post.');
	        $data['module'] = getmdname($data['moduleid'],'name');	        
	        $data['parentdir']=$this->parentdir($data['parentid']);
	        if($data['parentid']==null){
	        	
	        	$data['arrparentid']=0;
	        	
	        }else{
	        	$data['arrparentid']=implode(',',$this->arrparentid($data['parentid'])) .",".$data['parentid'];
	        }
	        
	        $data['arrchildid']=$data['id'];
	        
	        
	        
	        if($this->categorys->allowField(true)->save($data,['id' => $data['id']])!==false){
	        	if($data['parentid']>0){
	        		$arr=Db::name('category')->where("parentid=".$data['parentid'])->select();
					$arrchildid['arrchildid']=$data['parentid'].",".implode(array_column($arr,'id'),',');
	        		$this->categorys->allowField(true)->save($arrchildid,['id' => $data['parentid']]);
	        		
	        	}else{
	        		$arr=Db::name('category')->where("parentid=".$data['id'])->select();	
	        		if($arr){
						$arrchildid['arrchildid']=$data['id'].",".implode(array_column($arr,'id'),',');
		        		$this->categorys->allowField(true)->save($arrchildid,['id' => $data['id']]);	        				
	        		}
	        	}
	        		
	        	return ['msg'=>'修改成功','result'=>1];
	        } else{
	        	return ['msg'=>'修改失败','result'=>2];
	        }  		
    	}else{    	
    	
        $id = input('id');       
        $modid=$this->categorys->where("id='$id'")->find();
        $this->assign('module',$modid['moduleid']);
        $module = $this->module->field('id,title,name')->select();
        $this->assign('modulelist',$module);

        $record = $modid;
        $record['imgUrl'] = imgUrl($record['image']);
        $record['readgroup'] = explode(',',$record['readgroup']);
        $parentid =	intval($record['parentid']);
        //栏目选择列表
		$category=$this->categorys->order('listorder ASC')->select();		
		$arr=$this->cate->Tree($category,0);		
        $this->assign('categorys', $arr);
        $this->assign('record', $record);
        $this->assign('title','编辑栏目');
        //模版
        $templates= template_file();
        $this->assign ( 'templates',$templates );
        return $this->fetch();
       }
    }

	public function parentdir($id){
		if($id==null){
			return '';
		}else{
			$catdir=Db::name('category')->where("id='$id'")->find();
			return $catdir['catdir']."/";
		}
	}
	public function arrparentid($id){
		if($id==null){
			return '0';
		}else{
			
			$cate=Db('category')->select();
			return get_top_parentid($cate,$id);
			
		}
	}
    public function del() {
        $catid = input('id');
		$parentid=Db::name('category')->where("parentid=".$catid)->find();
		if($parentid){
			return ['msg'=>'请先删除子栏目！','result'=>2];
		}else{
			if(false == Db::name('category')->where('id',$catid)->delete()) {
    			return ['msg'=>'删除失败','result'=>2];
    		} else {
               // addlog($id);//写入日志
    			return ['msg'=>'删除成功','result'=>1];
    		}			
			
		}
		
		
    }

    public function cOrder(){
        $data = input('post.');
        $this->categorys->update($data);
        $result = ['msg' => '排序成功！', 'code' => 1];
        return $result;
    }
    public function status(){
        $id=input('post.id');
        $status=input('post.status');
        if($this->categorys->where('id='.$id)->update(['ismenu'=>$status])!==false){
            return ['result'=>1,'msg'=>'设置成功!'];
        }else{
            return ['result'=>0,'msg'=>'设置失败!'];
        }
    }		
    
    
    
}