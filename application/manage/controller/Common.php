<?php
namespace app\manage\controller;
use \think\Cache;
use \think\Controller;
use think\Loader;
use think\Db;
use \think\Cookie;
class Common extends Controller
{
    /**
     * 清除全部缓存
     * @return [type] [description]
     */
    public function clear()
    {
        if(false == Cache::clear()) {
        	return $this->error('清除缓存失败');
        } else {
        	return $this->success('清除缓存成功');
        }
    }
    /**
     * 登录
     * @return [type] [description]
     */
    public function login()
    {
    	$this->view->engine->layout(false); 
        if(Cookie::has('manage') == false) { 
            if($this->request->isPost()) {
                //是登录操作
                $post = $this->request->post();
                //验证  唯一规则： 表名，字段名，排除主键值，主键名
                $validate = new \think\Validate([
                    ['name', 'require|alphaDash', '用户名不能为空|用户名格式只能是字母、数字、——或_'],
                    ['password', 'require', '密码不能为空'],
                    ['captcha','require|captcha','验证码不能为空|验证码不正确'],
                ]);
                //验证部分数据合法性
                if (!$validate->check($post)) {
                    $this->error('提交失败：' . $validate->getError());
                }
                $name = Db::name('manage')->where('name',$post['name'])->find();
                if(empty($name)) {
                    //不存在该用户名
                    return $this->error('用户名不存在');
                } else {
                    //验证密码
                    $post['password'] = password($post['password']);
                    if($name['password'] != $post['password']) {                    	
                        return $this->error('密码错误');
                    } else {
                        //是否记住账号
                        if(!empty($post['remember']) and $post['remember'] == 1) {
                            //检查当前有没有记住的账号
                            if(Cookie::has('usermember')) {
                                Cookie::delete('usermember');
                            }
                            //保存新的
                            Cookie::forever('usermember',$post['name']);
                        } else {
                            //未选择记住账号，或属于取消操作
                            if(Cookie::has('usermember')) {
                                Cookie::delete('usermember');
                            }
                        }
                        Cookie::set("manage",$name['id'],7200); //保存新的,最长为2小时
                        Cookie::set("username",$name['name'],7200); //保存新的,最长为2小时
  
                        //记录登录时间和ip
                        Db::name('manage')->where('id',$name['id'])->update(['login_ip' =>  $this->request->ip(),'login_time' => time()]);
                        //记录操作日志
                        addlog($post['name']);
                        //Db::name('manage_log')->data(['ip' =>  $this->request->ip(),'create_time' => time(),'name'=>'管理员登录','manage_id'=>$name['id']])->insert();
                        return $this->success('登录成功,正在跳转...','manage/index/index');
                        //$this->redirect('admin/index/index');
                    }
                }
            } else {
                if(Cookie::has('usermember')) {
                    $this->assign('usermember',Cookie::get('usermember'));
                }
                return $this->fetch();
            }
        } else {
            $this->redirect('manage/index/index');
        }   
    }

    /**
     * 管理员退出，清除名字为admin的cookie
     * @return [type] [description]
     */
    public function logout()
    {
        Cookie::delete('manage');
        if(!empty(Cookie::get('manage'))) {
            return ['msg'=>'退出失败','result'=>2];
        } else {
        	return ['msg'=>'正在退出...','result'=>1,'url'=>'manage/common/login'];
           
        }
    }
}
