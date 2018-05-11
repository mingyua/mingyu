<?php
namespace app\manage\controller;
use think\Controller;
use think\Request;
use think\Db;
use app\manage\controller\User;
use app\manage\model\Config as Cmodel;
use app\manage\model\Manage as managemodel;
use \think\Cookie;
class Index extends User 
{
    public function Index(){
       $menu = Db::name('manage_menu')->where(['is_display'=>1])->order('orders asc')->select();
        //添加url
        foreach ($menu as $key => $value) {
        	if(empty($value['parameter'])) {
        		$url = url($value['module'].'/'.$value['controller'].'/'.$value['function']);
        	} else {
                $url = url($value['module'].'/'.$value['controller'].'/'.$value['function'].','.$value['parameter']);
        	}
        	$menu[$key]['url'] = $url;
        }
        $menus = $this->menulist($menu);
        $this->assign('menus',$menus); 
        $maid=Cookie::get('manage');        
		$manage = managemodel::hasWhere('attachment',['manage_id'=>$maid])->field('filepath')->find();      
       $this->assign('info',$manage);   	

	 return $this->fetch();
    }    

    public function System(){
    	
		// 查询数据集
		$list=db('config')->where('group','1')->order('sort ASC')->select();
		
		
		foreach($list as $k=>$v){
			$extra=$v['extra'];
			if($extra){
				$array = preg_split('/[,;\r\n]+/', trim($extra, ",;\r\n"));
				if(strpos($extra,':')){
	            $value  =   array();
	            foreach ($array as $val) {
	                list($k1, $v1) = explode(':', $val);
	                $value[$k1]   = $v1;
	            }
		        }else{
		            $value  =   $array;
		        }
		        
		       $list[$k]['attr']=$value;
			}			
		}
		
    	
    	$this->assign('list', $list);
    	
	
	 return $this->fetch();
    }
    public function Systempage(){
		$this->view->engine->layout(false); 
		$group=input('group')+1;		
		$list=db('config')->where('group',$group)->order('sort ASC')->select();   
		foreach($list as $k=>$v){
			$extra=$v['extra'];
			if($extra){
				$array = preg_split('/[,;\r\n]+/', trim($extra, ",;\r\n"));
				if(strpos($extra,':')){
	            $value  =   array();
	            foreach ($array as $val) {
	                list($k1, $v1) = explode(':', $val);
	                $value[$k1]   = $v1;
	            }
		        }else{
		            $value  =   $array;
		        }
		        
		       $list[$k]['attr']=$value;
			}			
		}		 	
    	$this->assign('list',$list);
    	$htmls = $this->fetch('index/systempage');
	 return ['msg'=>'success','html'=>$htmls];
    }    
    public function Systemadd(){
    	$id = input('id', 0);
    	$typeMsg = $id?'编辑':'添加';
 
    	if (Request::instance()->isPost()){
			if($this->validate($this->request->post(false), $this->_configRule)){ 
				$model = new Cmodel(); 
				$data = $this->request->post(false);  		
	    		if($id){//修改
	                $data['update_time'] = time();	    			
	                $ret =$model->allowField(true)->save($data,['id'=>$id]);
	                addlog();
	    		}else{//添加
	                $data['create_time'] = time();
	                $data['update_time'] = $data['create_time'];
	                $data['status'] = 1;
	                $ret = $model->insert($data);
	    		}
	    		if($ret){
	    				$arr=array('msg'=>$typeMsg.'成功','url'=>$ret,'result'=>1);
	    				addlog();
	            }else{
	    				$arr=array('msg'=>$typeMsg.'失败','url'=>$ret,'result'=>2);
	            }
    		}else{
    			$arr=array('msg'=>'验证失败','result'=>2);
            }
            return json($arr);
    	}else{
    		if($id){
                $info = Cmodel::get($id)->toArray();
                $this->assign('info', $info);
            }
          
    	 return $this->fetch();	
    	}
    }
    /**
     * 保存分组配置
     * @author baiyouwen
     */
    public function save()
    {    	
    	$config=$this->request->post(false);
    	//dump($config['config']);die();
        if ($config['config'] && is_array($config['config'])) {
            $db = db('Config');
            foreach ($config['config'] as $name => $value) {
                $map = array('name' => $name);
                $db->where($map)->setField('value', $value);
            }
        }
        $arr=array('msg'=>'保存成功!','result'=>1);
        addlog();
	    return json($arr);       
    }
     /**
     * 分组列表
     * @author baiyouwen
     */
    public function lists()
    {    	
		$list=db('config')->order("sort ASC")->select();
		$this->assign('list', $list);
		return $this->fetch();    
    }
     
     /**
     * 分组删除
     * @author baiyouwen
     */
    public function del()
    {    	
    	if($this->request->isAjax()) {
    		$id = $this->request->has('id') ? $this->request->param('id', 0, 'intval') : 0;
            if(false == Db::name('config')->where('id',$id)->delete()) {
                return ['msg'=>'删除失败','result'=>2];              
            } else {
                addlog($id);//写入日志
                return ['msg'=>'删除成功','result'=>1];
            }
    	}
    }

/*****
 * 清除缓存
 * 
 * *******/
	public function clearcache(){
        $R = RUNTIME_PATH;
        if ($this->_deleteDir($R)) {
        	$this->clearhtmlcache();
            $result['msg'] = '清除缓存成功!';
            $result['result'] = 1;                 
        } else {
            $result['msg'] = '清除缓存失败!';
            $result['result'] = 2;
        }
        return json($result);		
	}   
	
 private function _deleteDir($R)
    {
        $handle = opendir($R);
        while (($item = readdir($handle)) !== false) {
            if ($item != '.' and $item != '..') {
                if (is_dir($R . '/' . $item)) {
                    $this->_deleteDir($R . '/' . $item);
                } else {
                    if (!unlink($R . '/' . $item))
                        die('error!');
                }
            }
        }
        closedir($handle);
        return rmdir($R);
    }
    
 private function clearhtmlcache()
    {
		$dir = ROOT_PATH;//获取当前文件目录
		$delfiles = scandir($dir);//获取当前文件目录下的所有文件
		$delarr = preg_grep("/(.*).html$/",$delfiles);//匹配类型，这里匹配的是*.zip文件
		foreach($delarr as $value){
			unlink($value);
		}
    }    	  
private $_configRule = [
    'name' => 'required',
    'title' => 'required',
];
    
    
    protected function menulist($menu){
		$menus = array();
		//先找出顶级菜单
		foreach ($menu as $k => $val) {
			if($val['pid'] == 0) {
				$menus[$k] = $val;
			}
		}
		//通过顶级菜单找到下属的子菜单
		foreach ($menus as $k => $val) {
			foreach ($menu as $key => $value) {
				if($value['pid'] == $val['id']) {
					$menus[$k]['list'][] = $value;
				}
			}
		}
		return $menus;
	}
 public function tt(){
 	
 	return $this->fetch();
 }		
}
