<?php
namespace app\manage\controller;
use think\Db;
use \think\Cache;
use \think\Cookie;
use think\Request;
use app\manage\controller\User;
use fields\Fields;
use \think\Controller;
use cate\Cate;
class EmptyController extends User{
    protected $fields;
    public function _initialize()
    {
        parent::_initialize();
        
        $this->CONTROLLER_NAME=strtolower(request()->controller());
        $this->ACTION=strtolower(request()->action());        
        $this->m = model(request()->controller());
        
        $modle=Db::name('module')->where("name='".$this->CONTROLLER_NAME."'")->find();
        $this->mdid=$modle['id'];
        $this->field=Db::name('field')->where("moduleid=".$modle['id'])->order('listorder ASC')->select();        
        $this->assign('md',$this->CONTROLLER_NAME); 
    }
    public function index(){
		$catid=input('catid');   	
		$cat=Db::name('category')->where("id='$catid'")->field('id,arrchildid')->find();
		

		$list=$list=$this->m->where('catid','in',$cat['arrchildid'])->paginate(13);
		$this->assign('title','广告');
    	$this->assign('list',$list);

    	return $this->fetch('content/index');
    }
    public function add(){
    	if($this->request->isPost()){
    		
    		$data=input('post.'); 
    		if(!empty($data['posid'])){
    			$data['posid']=implode(',',$data['posid']);
    		}else{
    			$data['posid']='';
    		}    		
    		$time=isset($data['addtime'])?$data['addtime']:date('Y-m-d H:i:s',time());
    		$data['addtime']=strtotime($time);  		
    			
    		if(isset($data['id'])){
				if( $this->m ->allowField(true)->save($data,['id' => $data['id']])!==false){
	                return ['result'=>1,'msg'=>'修改成功!'];
	            }else{
	                return ['result'=>0,'msg'=>'修改失败!'];
	            }    		
    		
			}else{//添加
				
				if(false == $this->m ->allowField(true)->save($data)) {
	    			return ['msg'=>'数据添加失败！','result'=>2];
    			}else{
	    			return ['msg'=>'数据添加成功！','result'=>1];
    				
    			}							
			
			}    		
    		
    	}else{
    		$catename=null;
    		if($this->CONTROLLER_NAME=='page'){
    			$id=input('catid');
    			$catename=Db::name('category')->where("id='$id'")->field('id,catname')->find();
				$where="catid='".$id."'";    			    			
    		}else{
    			$id=input('id');
				$where="id='".$id."'";       			
    		}
			if($id){				
				$value=Db($this->CONTROLLER_NAME)->where($where)->find();
			}else{
				$value='';
			}		
	    	$test=new Fields();	    	

	    	$data=$test::textcontent($this->field,$value,$catename);   	    	
	    	$this->assign('info',$value);			
	    	$this->assign('html',$data);			
    	return $this->fetch('content/add');
    		
    	}
    	
    }
    public function status(){
        $id=input('post.id');
        $status=input('post.status');
        if($this->m->where('id='.$id)->update(['status'=>$status])!==false){
            return ['result'=>1,'msg'=>'设置成功!'];
        }else{
            return ['result'=>0,'msg'=>'设置失败!'];
        }
    }		
	public function del(){
		
		$id=input('id');
		$r = $this->m->where("id=".$id)->find();
		//return ['msg'=>$id,'result'=>1];
        if(!empty($r)){
            $m = Db::name($this->CONTROLLER_NAME)->delete($id);
            if($m){
               return ['result'=>1,'msg'=>'删除成功！'];		
            }else{
               return ['result'=>0,'msg'=>'删除失败！'];		
            }
        }else{
        	return ['result'=>1,'msg'=>'非法提交！'];
        }		
	}
	
	public function delall(){
		$id=rtrim(input('post.id'),',');
		if($id){
			if($this->m->destroy($id)!==false){
				return ['result'=>1,'msg'=>'删除成功！'];
			}else{
				return ['result'=>0,'msg'=>'删除失败！'];
			}
			
			
		}else{
			return ['result'=>0,'msg'=>'非法提交！'];
		}
		
	}	    
    
}
