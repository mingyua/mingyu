<?php
// +----------------------------------------------------------------------
// | Tplay [ WE ONLY DO WHAT IS NECESSARY ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017 http://tplay.pengyichen.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 听雨 < 389625819@qq.com >
// +----------------------------------------------------------------------


namespace app\manage\controller;

use \think\Db;
use \think\Cookie;
use app\manage\model\Manage as ManageModel;//管理员模型
use app\manage\model\ManageMenu;
use app\manage\controller\User;
class Manage extends User
{
    /**
     * 管理员列表
     * @return [type] [description]
     */
    public function index()
    {
        $model = new ManageModel();

        $post = $this->request->param();
    	foreach( $post as $k=>$v){
			if( $v==null )
			unset( $post[$k] );
		}
        if (isset($post['keywords'])) {
            $where['nickname'] = ['like', '%' . $post['keywords'] . '%'];
        }
        if (isset($post['manage_cate_id']) and $post['manage_cate_id'] > 0) {
            $where['manage_cate_id'] = ['eq',$post['manage_cate_id']];
        }
 
        if(isset($post['create_time'])) {
            $min_time = strtotime($post['create_time']);
            $max_time = $min_time + 24 * 60 * 60;
            $where['create_time'] =['between time', [$min_time,$max_time]];
        }
        
      
          
        $admin = empty($where) ? $model->order('create_time desc')->paginate(18) : $model->where($where)->order('create_time desc')->paginate(18,false,['query'=>array_filter($post)]);
        
        $this->assign('manage',$admin);
        $info['cate'] = Db::name('manage_cate')->select();
        $this->assign('info',$info);
        return $this->fetch();
    }

    
    /**
     * 管理员个人资料修改，属于无权限操作，仅能修改昵称和头像，后续可增加其他字段
     * @return [type] [description]
     */
    public function personal()
    {
        //获取管理员id
        $id = Cookie::get('manage');
        $model = new ManageModel();
        if($id > 0) {
            //是修改操作
            if($this->request->isPost()) {
                //是提交操作
                $post = $this->request->post();
                //验证昵称是否存在
                $post['update_time']=time();
                $nickname = $model->where(['nickname'=>$post['nickname'],'id'=>['neq',$post['id']]])->select();
                if(!empty($nickname)) {
                	return ['msg'=>'提交失败：该昵称已被占用','result'=>2];
                    
                }
                if(false == $model->allowField(true)->save($post,['id'=>$id])) {
                    return ['msg'=>'修改失败','result'=>2];
                } else {
                    addlog($model->id);//写入日志
                     return ['msg'=>'修改个人信息成功','result'=>1];
                    
                }
            } else {
                //非提交操作
                $info['manage'] = $model->where('id',$id)->find();
                $this->assign('info',$info);
                return $this->fetch();
            }
        } else {
        	return ['msg'=>'id不正确','result'=>2];
            
        }
    }


    /**
     * 管理员的添加及修改
     * @return [type] [description]
     */
    public function publish()
    {
    	//获取管理员id
    	$id = $this->request->has('id') ? $this->request->param('id', 0, 'intval') : 0;
    	$model = new ManageModel();
    	if($id > 0) {
    		//是修改操作
    		if($this->request->isPost()) {
    			//是提交操作
    			$post = $this->request->post();
    			$post['update_time']=time();
    			//验证  唯一规则： 表名，字段名，排除主键值，主键名
	            $validate = new \think\Validate([
	                ['name', 'require|alphaDash', '管理员名称不能为空|用户名格式只能是字母、数组、——或_'],
	                ['manage_cate_id', 'require', '请选择管理员分组'],
	            ]);
	            //验证部分数据合法性
	            if (!$validate->check($post)) {
	            	return ['msg'=>'提交失败：' . $validate->getError(),'result'=>2];
	                
	            }
	            //验证用户名是否存在
	            $name = $model->where(['name'=>$post['name'],'id'=>['neq',$post['id']]])->select();
	            if(!empty($name)) {
	            	return ['msg'=>'提交失败：该用户名已被注册：','result'=>2];
	            
	            }
	            //验证昵称是否存在
	            $nickname = $model->where(['nickname'=>$post['nickname'],'id'=>['neq',$post['id']]])->select();
	            if(!empty($nickname)) {
	            	return ['msg'=>'提交失败：该昵称已被占用','result'=>2];
	            	
	            }
	            if(false == $model->allowField(true)->save($post,['id'=>$id])) {
	            	return ['msg'=>'修改失败','result'=>2];
	            	
	            } else {
                    addlog($model->id);//写入日志
                    return ['msg'=>'修改管理员信息成功','result'=>1];
	            	
	            }
    		} else {
    			//非提交操作
    			
    			$info['manage'] = $model->where('id',$id)->find();
    			$info['manage_cate'] = Db::name('manage_cate')->select();
    			$this->assign('info',$info);
    			$this->assign('id',input('id'));
    			return $this->fetch();
    		}
    	} else {
    		//是新增操作
    		if($this->request->isPost()) {
    			//是提交操作
    			$post = $this->request->post();
    			$post['create_time']=time();
    			$post['update_time']=time();
    			//验证  唯一规则： 表名，字段名，排除主键值，主键名
	            $validate = new \think\Validate([
	                ['name', 'require|alphaDash', '用户名不能为空|用户名格式只能是字母、数组、——或_'],
	                ['password', 'require|confirm', '密码不能为空|两次密码不一致'],
	                ['password_confirm', 'require', '重复密码不能为空'],
	                ['manage_cate_id', 'require', '请选择管理员分组'],
	            ]);
	            //验证部分数据合法性
	            if (!$validate->check($post)) {
	            	$arr=array('msg'=>'提交失败：' . $validate->getError());
	                return json($arr);
	            }
	            //验证用户名是否存在
	            $name = $model->where('name',$post['name'])->select();
	            if(!empty($name)) {
	            	$arr=array('msg'=>'提交失败：该用户名已被注册');
	            	return json($arr);
	            }
	            //验证昵称是否存在
	            $nickname = $model->where('nickname',$post['nickname'])->select();
	            if(!empty($nickname)) {
	            	$arr=array('msg'=>'提交失败：该昵称已被占用');
	            	return json($arr);
	            }
	            //密码处理
	            $post['password'] = password($post['password']);
	            if(false == $model->allowField(true)->save($post)) {
	            	$arr=array('msg'=>'添加管理员失败');
	            	return json($arr);
	            } else {
	            	$mangesId = Db::name('manage')->getLastInsID();
	            	Db::name('attachment')->where("id=".input('thumb'))->update(['user_id' => $mangesId, 'manage_id' => $mangesId]);
                    addlog($model->id);//写入日志
                    $arr=array('msg'=>'添加管理员成功');	 
                    return json($arr);           	
	            }
	            
    		} else {
    			//非提交操作
    			
    			$info['manage_cate'] = Db::name('manage_cate')->select();
    			$this->assign('info',$info);
    			$this->assign('id',0);
    			return $this->fetch();
    		}
    	}
    }

    /**
     * 修改密码
     * @return [type] [description]
     */
    public function editPassword()
    {
    	$id = $this->request->has('id') ? $this->request->param('id', 0, 'intval') : 0;
    	if($this->request->isPost()) {
    		if($id == Cookie::get('manage')) {
    			$post = $this->request->post();
    			//验证  唯一规则： 表名，字段名，排除主键值，主键名
	            $validate = new \think\Validate([
	                ['password', 'require|confirm', '密码不能为空|两次密码不一致'],
	                ['password_confirm', 'require', '重复密码不能为空'],
	            ]);
	            //验证部分数据合法性
	            if (!$validate->check($post)) {
	                $this->error('提交失败：' . $validate->getError());
	            }
    			$admin = Db::name('manage')->where('id',$id)->find();
    			if(password($post['password_old']) == $admin['password']) {
    				if(false == Db::name('manage')->where('id',$id)->update(['password'=>password($post['password'])])) {
    					return $this->error('修改失败');
    				} else {
                       // addlog();//写入日志
    					return $this->success('修改成功','manage/manage/index');
    				}
    			} else {
    				return $this->error('原密码错误');
    			}
    		} else {
    			return $this->error('不能修改别人的密码');
    		}
    	}else{
    		return $this->fetch();
    		
    	}
 
    			
    			
    		

    	
    }


    /**
     * 管理员删除
     * @return [type] [description]
     */
    public function delete()
    {
    	if($this->request->isAjax()) {
    		$id = $this->request->has('id') ? $this->request->param('id', 0, 'intval') : 0;
    		if($id == 1) {
    			return ['msg'=>'网站所有者不能被删除','result'=>2];
    		}
    		if($id == Cookie::get('manage')) {
    			return ['msg'=>'自己不能删除自己','result'=>2];
    		}
    		if(false == Db::name('manage')->where('id',$id)->delete()) {
    			return ['msg'=>'删除失败','result'=>2];
    		} else {
                addlog($id);//写入日志
    			return ['msg'=>'删除成功','result'=>1];
    		}
    	}
    }

    
    /**
     * 管理员权限分组列表
     * @return [type] [description]
     */
    public function manageCate()
    {
    	$model = new \app\manage\model\ManageCate;

        $post = $this->request->post();
        if (isset($post['keywords']) and !empty($post['keywords'])) {
            $where['name'] = ['like', '%' . $post['keywords'] . '%'];
        }
 
        if(isset($post['create_time']) and !empty($post['create_time'])) {
            $min_time = strtotime($post['create_time']);
            $max_time = $min_time + 24 * 60 * 60;
            $where['create_time'] = [['>=',$min_time],['<=',$max_time]];
        }
        
        $cate = empty($where) ? $model->order('create_time desc')->paginate(20) : $model->where($where)->order('create_time desc')->paginate(20);
        
    	$this->assign('cate',$cate);
    	return $this->fetch();

    }


    /**
     * 管理员角色添加和修改操作
     * @return [type] [description]
     */
    public function manageCatePublish()
    {
        //获取角色id
        $id = $this->request->has('id') ? $this->request->param('id', 0, 'intval') : 0;
        $model = new \app\manage\model\ManageCate();
        $menuModel = new ManageMenu();
        if($id > 0) {
            //是修改操作
            if($this->request->isPost()) {
                //是提交操作
                $post = $this->request->post();
                //验证  唯一规则： 表名，字段名，排除主键值，主键名
                $validate = new \think\Validate([
                    ['name', 'require', '角色名称不能为空'],
                ]);
                //验证部分数据合法性
                if (!$validate->check($post)) {
                	return ['msg'=>'提交失败：' . $validate->getError(),'result'=>2];
                    
                }
                //验证用户名是否存在
                $name = $model->where(['name'=>$post['name'],'id'=>['neq',$post['id']]])->select();
                if(!empty($name)) {
                	return ['msg'=>'提交失败：该角色名已存在','result'=>2];
                    
                }
   
                if(false == $model->allowField(true)->save($post,['id'=>$id])) {
                	return ['msg'=>'修改失败','result'=>2];
                    
                } else {
                    addlog($model->id);//写入日志
                    return ['msg'=>'修改角色信息成功','result'=>1];
                   
                }
            } else {
                //非提交操作
                $title="修改";
                $permissions=$model->where('id',$id)->find();
                if(!empty($permissions['permissions'])) {
                    //将菜单id字符串拆分成数组
                    $arr= explode(',',$permissions['permissions']);
                }else{
                	$arr=array();
                }
                
                $menus = Db::name('manage_menu')->field('id,pid as pId,name')->select();
                foreach($menus as $k=>$v){
                	if (in_array($v['id'], $arr)) {
					  $menus[$k]['checked']=true;
					}else{
						$menus[$k]['checked']=false;
					}               	                	
                }
                
              
                
                $this->assign('title',$title); 
                $this->assign('menus',json_encode($menus));
                $this->assign('permissions',$permissions);

                
                return $this->fetch();
            }
        } else {
            //是新增操作
            if($this->request->isPost()) {
                //是提交操作
                $post = $this->request->post();
                //验证  唯一规则： 表名，字段名，排除主键值，主键名
                $validate = new \think\Validate([
                    ['name', 'require', '角色名称不能为空'],
                ]);
                //验证部分数据合法性
                if (!$validate->check($post)) {
                	
                    return ['msg'=>'提交失败：' . $validate->getError(),'result'=>2];
                }
                //验证用户名是否存在
                $name = $model->where('name',$post['name'])->find();
                if(!empty($name)) {
                	return ['msg'=>'提交失败：该角色名已存在','result'=>2];
                    
                }
                //处理选中的权限菜单id，转为字符串
                if(!empty($post['admin_menu_id'])) {
                    $post['permissions'] = implode(',',$post['admin_menu_id']);
                }
                if(false == $model->allowField(true)->save($post)) {
                	return ['msg'=>'添加角色失败','result'=>2];
                   
                } else {
                    addlog($model->id);//写入日志
                    return ['msg'=>'添加角色成功','result'=>1];
                    
                }
            } else {
            	
                //非提交操作
                $title="添加新";
                $menus = Db::name('manage_menu')->field('id,pid as pId,name')->select();
                foreach($menus as $k=>$v){
                	$menus[$k]['checked']=false;
                	$menus[$k]['open']=false;                	
                }
                $permissions=array('id'=>'','name'=>'','permissions'=>'','desc'=>'');
                $this->assign('title',$title); 
                $this->assign('permissions',$permissions); 
                $this->assign('menus',json_encode($menus));
                return $this->fetch();
            }
        }
    }


    public function preview()
    {
        $id = $this->request->has('id') ? $this->request->param('id', 0, 'intval') : 0;
        $model = new \app\manage\model\ManageCate();
        $info['cate'] = $model->where('id',$id)->find();
        if(!empty($info['cate']['permissions'])) {
            //将菜单id字符串拆分成数组
            $info['cate']['permissions'] = explode(',',$info['cate']['permissions']);
        }
        $menus = Db::name('manage_menu')->select();
        $info['menu'] = $this->menulist($menus);
        $this->assign('info',$info);
        return $this->fetch();
    }


    protected function menulist($menu,$id=0,$level=0){
        
        static $menus = array();
        $size = count($menus)-1;
        foreach ($menu as $value) {
            if ($value['pid']==$id) {
                $value['level'] = $level+1;
                if($level == 0)
                {
                    $value['str'] = str_repeat('',$value['level']);
                    $menus[] = $value;
                }
                elseif($level == 2)
                {
                    $value['str'] = '&emsp;&emsp;&emsp;&emsp;'.'└ ';
                    $menus[$size]['list'][] = $value;
                }
                else
                {
                    $value['str'] = '&emsp;&emsp;'.'└ ';
                    $menus[$size]['list'][] = $value;
                }
                
                $this->menulist($menu,$value['id'],$value['level']);
            }
        }
        return $menus;
    }


    /**
     * 管理员角色删除
     * @return [type] [description]
     */
    public function manageCateDelete()
    {
        if($this->request->isAjax()) {
            $id = $this->request->has('id') ? $this->request->param('id', 0, 'intval') : 0;
            if($id > 0) {
                if($id == 1) {
                	return ['msg'=>'超级管理员角色不能删除','result'=>2];
                }
                if(false == Db::name('manage_cate')->where('id',$id)->delete()) {
                	return ['msg'=>'删除失败','result'=>2];
                } else {
                    addlog($id);//写入日志
                    return ['msg'=>'删除成功','result'=>1];
                }
            } else {
                return ['msg'=>'id不正确','result'=>2];
            }
        }
    }


    public function log()
    {
        $model = new \app\manage\model\ManageLog();
		
        if (input('manage_menu_id')) {
            $where['manage_menu_id'] = input('manage_menu_id');
        }
        
        if (input('manage_id')) {
            $where['manage_id'] = input('manage_id');
        }
 
        if(input('create_time')) {
            $min_time = strtotime(input('create_time'));
            $max_time = $min_time + 24 * 60 * 60;
            $where['create_time'] = [['>=',$min_time],['<=',$max_time]];
        }    
           
        $log = empty($where) ? $model->order('create_time desc')->paginate(18) : $model->where($where)->order('create_time desc')->paginate(18);       
        $this->assign('log',$log);
        //身份列表
        $admin_cate = Db::name('manage_cate')->select();
        $this->assign('admin_cate',$admin_cate);
        $info['menu'] = Db::name('manage_menu')->select();
        $info['manage'] = Db::name('manage')->select();
        $this->assign('info',$info);
        return $this->fetch();
    }

    public function ceshi()
    {
        //不是超级管理员，获取访问的url结构
        $where['module'] = $this->request->module();
        $where['controller'] = $this->request->controller();
        $where['function'] = $this->request->action();
        $where['type'] = 1;
        //获取除了域名和后缀外的url，是字符串
        $parameter = $this->request->path() ? $this->request->path() : null;
        //将字符串转化为数组
        $parameter = explode('/',$parameter);
        //剔除url中的模块、控制器和方法
        foreach ($parameter as $key => $value) {
            if($value != $where['module'] and $value != $where['controller'] and $value != $where['function']) {
                $param[] = $value;
            }
        }

        if(isset($param) and !empty($param)) {
            //确定有参数
            $string = '';
            foreach ($param as $key => $value) {
                //奇数为参数的参数名，偶数为参数的值
                if($key%2 !== 0) {
                    //过滤只有一个参数和最后一个参数的情况
                    if(count($param) > 2 and $key < count($param)-1) {
                        $string.=$value.'&';
                    } else {
                        $string.=$value;
                    }
                } else {
                    $string.=$value.'=';
                }
            } 
        } else {
            $string = [];
            $param = $this->request->param();
            foreach ($param as $key => $value) {
                $string[] = $key.'='.$value;
            }
            $string = implode('&',$string);
        } 
    }

	public function avatar(){
		 $this->view->engine->layout(false); 
		if ($this->request->post(false)) {		
		        header('Content-type:text/html;charset=utf-8');
		        $base64_image_content = input('dataurl');
		        //将base64编码转换为图片保存
		        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
		            $type = $result[2];
		            $size = strlen(file_get_contents($base64_image_content))/1024;					
		            $new_file = "./uploads/avatar/";
		            if (!file_exists($new_file)) {
		                //检查是否有该文件夹，如果没有就创建，并给予最高权限
		                mkdir($new_file, 0700);
		            }
		            $img=time() . ".".$type;
		            $new_file = $new_file . $img;
		            //return ['msg'=>'头像上传成功！','result'=>1];
		            //将图片保存到指定的位置
		            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
		            		$data = [];
				            $data['module'] = 'manage';//模块
				            $data['filename'] = $img;//文件名
				            $data['filepath'] = $new_file;//文件路径
				            $data['fileext'] = $type;//文件后缀
				            $data['filesize'] = $size;//文件大小
				            $data['create_time'] = time();//时间
				            $data['uploadip'] = $this->request->ip();//IP
			                $data['status'] = 1;
			                $data['audit_time'] = time();
				            $data['use'] = 'manage_thumb';//用处
						if(input('id')==0){//新增
							 $data['user_id'] = 0;
							 $data['manage_id']=0;
							 $res['id'] = Db::name('attachment')->insertGetId($data);
						}else{//写入到附件表 修改

				            $data['user_id'] = input('id');
			                $data['manage_id'] = $data['user_id'];
					        $thumb=Db::name('attachment')->where("user_id=".$data['user_id'])->find();
				            if($thumb){
				            	$res['id']=$thumb['id'];
				            	$data['id']=$thumb['id'];
				            	Db::name('attachment')->update($data);
				            }else{
					            $res['id'] = Db::name('attachment')->insertGetId($data);
					            Db::name('manage')->update(['id' => $data['user_id'], 'thumb' => $res['id']]);
					            				            	
				            }
				        }

				            //addlog($res['id']);//记录日志
		                return ['msg'=>'头像上传成功！','result'=>1,'imgurl'=>$new_file,'thumbid'=>$res['id']];
		            }else{
		                return ['msg'=>'头像上传失败！','result'=>2];
		            }
		        }else{
		            return ['msg'=>'上传图片不存在！','result'=>2];
		        }
		
		    }else{
				return $this->fetch();
		    	
		    }
	
		

	}
    
}
