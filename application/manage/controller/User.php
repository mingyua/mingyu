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

use \think\Cache;
use \think\Controller;
use think\Loader;
use think\Db;
use \think\Cookie;
class User extends Controller
{

    /**
     * 检查操作用户的ip是否在黑名单里
     * 检查用户是否登录
     * 检查用户访问的url在不在其角色组的权限范围内
     * @return [type] [description]
     */
    protected function _initialize()
    {
		$site= cache('site');
        if(!$site){
			$site=Db::name('config')->field('id,name,value')->cache(0)->select();
			$system=array();
			foreach($site as $k=>$v){
				$system[$v['name']]=$v['value'];
			}
			$black_ip =$system['DENY_IP'];
			 $this->assign('adminsite',$system);
			cache('site', $system, 0);
        }else{
        	$black_ip =$site['DENY_IP'];
        	 $this->assign('adminsite',$site);
        }
       
		
		
        //检查当前ip是不是在黑名单
         //Db::name('config')->where('name','ADMIN_ALLOW_IP')->value('value');
        
        if(!empty($black_ip)) {
            //转化成数组
            $black_ip = explode(',',$black_ip);
            //获取当前访问的ip
            $ip = $this->request->ip();
            if(in_array($ip,$black_ip)) {
                //清空cookie
                if(!empty(Cookie::get('manage'))) {
                    Cookie::delete('manage');
                }
                return $this->error('你的ip在黑名单里','manage/common/login');
            }
        }
        
        //检查是否登录
        $admin_id = Cookie::get('manage');
        if(!empty($admin_id)) {
            $cookie = Db::name('manage')->where('id',$admin_id)->find();
            $this->assign('cookie',$cookie);
        } else {
            $this->redirect('manage/common/login');
        }
		
        //检查权限,这里如果是超级管理员则直接跳过权限检查
        if($cookie['manage_cate_id'] != 1) {
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
                    if(!is_array($value)) {
                        //这里不完美，param()会接收到页面表单的数据，数据里有字段的值是数组，所以会出错，这里过滤掉值为数组的参数
                        $string[] = $key.'='.$value;
                    }
                }
                $string = implode('&',$string);
            }
            
            //得到用户的权限菜单
            $menus = Db::name('manage_cate')->where('id',$cookie['manage_cate_id'])->value('permissions');
           
            //将得到的菜单id集成的字符串拆分成数组
            $menus = explode(',',$menus);
            if(!empty($string)) {
                //检查该带参数的url是否设置了权限
                $param_menu = Db::name('manage_menu')->where($where)->where('parameter',$string)->find();
                //$data['name'] = $param_menu['name'];
                if(!empty($param_menu)) {
                    //该url的参数设置了权限，检查用户有没有权限
                    if(false == in_array($param_menu['id'],$menus)) {//dump($param_menu);die;
                        return $this->error('你没有权限');
                    }
                } else {
                    //该url带参数状态未设置权限，检查该url去掉参数时，用户有无权限
                    $menu = Db::name('manage_menu')->where($where)->find();
                    if(!empty($menu)) {
                        if(empty($menu['parameter'])) {
                            if(!in_array($menu['id'],$menus)) {
                                return $this->error('你没有权限');
                            }
                        }
                    }
                }
            } else {
                //用户访问的url里没有参数
                $menu = Db::name('manage_menu')->where($where)->find();
                //$data['name'] = $menu['name'];
                if(!empty($menu)) {
                    if(empty($menu['parameter'])) {
                        if(!in_array($menu['id'],$menus)) {
                            return $this->error('你没有权限');
                        }
                    }
                }  
            }
        }
    }
}
