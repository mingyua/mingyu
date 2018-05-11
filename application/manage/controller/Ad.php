<?php
namespace app\manage\controller;

use \think\Cache;
use \think\Controller;
use think\Loader;
use think\Db;
use \think\Cookie;
use app\manage\controller\User;
use fields\Fields;
class Ad extends User
{
	function _initialize(){        
    	parent::_initialize();
        $this->ad = model('Ad');        
        $this->assign('md','ad'); 
    }
    public function index(){		
		$list=$this->ad->where("id>0")->paginate(18);
		$this->assign('title','广告');
		$test=new Fields();	 
		
 
		$this->assign('list',$list);
		return $this->fetch();
	}
	public function add(){
		if($this->request->isPost()){
			$post=$this->request->post();
			
			
			$post['addtime']=strtotime(input('addtime'));
			if(input('id')){//修改
				if( $this->ad->allowField(true)->save($post,['id' => input('id')])!==false){
	                return ['result'=>1,'msg'=>'修改成功!'];
	            }else{
	                return ['result'=>0,'msg'=>'修改失败!'];
	            }				
				
			}else{//添加
				if(false == $this->ad->allowField(true)->save($post)) {
	    			return ['msg'=>'数据添加失败！','result'=>2];
    			}else{
	    			return ['msg'=>'数据添加成功！','result'=>1];
    				
    			}							
			
			}
			
		}else{	
			$id=input('id');
			if($id){
				$value=Db('ad')->where("id=".$id)->find();
			}else{
				$value='';
			}		
	    	$test=new Fields();	    	
	    	$ff=Db('field')->where("moduleid='11'")->order("listorder ASC")->select();
	    	
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
		                $optionsarr[$k] = $v[0];
		            }	
		    	}

	    	}
	    	
	    	$this->assign('info',$value);			
	    	$this->assign('html',$data);			
			return $this->fetch();
		}
		
	}
    public function status(){
        $id=input('post.id');
        $status=input('post.status');
        if($this->ad->where('id='.$id)->update(['status'=>$status])!==false){
            return ['result'=>1,'msg'=>'设置成功!'];
        }else{
            return ['result'=>0,'msg'=>'设置失败!'];
        }
    }		
	public function del(){
		
		$id=input('id');
		$r = $this->ad->where("id=".$id)->find();
		//return ['msg'=>$id,'result'=>1];
        if(!empty($r)){
            $m = Db::name('ad')->delete($id);
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
			if($this->ad->destroy($id)!==false){
				return ['result'=>1,'msg'=>'删除成功！'];
			}else{
				return ['result'=>0,'msg'=>'删除失败！'];
			}
			
			
		}else{
			return ['result'=>0,'msg'=>'非法提交！'];
		}
		
	}	
}
?>