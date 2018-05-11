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
use \think\Request;
class Module extends User
{
	protected $dao;
    function _initialize()
    {        
    	parent::_initialize();
    	$this->field = model('Field'); 
        $this->module = model('Module'); 
        $field_type = [
            ['name'=>'catid','title'=>'栏目选择'],
            ['name'=>'text','title'=>'单行文本'],
            ['name'=>'textarea','title'=>'多行文本'],
            ['name'=>'select','title'=>'下拉列表'],
            ['name'=>'radio','title'=>'单选按钮'],
            ['name'=>'open','title'=>'开关'],
            ['name'=>'checkbox','title'=>'复选框'],
            ['name'=>'color','title'=>'颜色选择'],
            ['name'=>'file','title'=>'文件上传'],
            ['name'=>'datetime','title'=>'日期和时间'],
        ];
        $fieldtype = [
            ['name'=>'varchar','title'=>'字符 varchar'],
            ['name'=>'int','title'=>'整数 int'],
            ['name'=>'tinyint','title'=>'整数 tinyint'],
            ['name'=>'smallint','title'=>'整数 smallint'],
            ['name'=>'text','title'=>'文本text'],
            ['name'=>'mediumtext','title'=>'整数 MediumText'],
        ];
       
         $field_pattern = [
            ['name'=>'defaul','title'=>'默认'],
            ['name'=>'email','title'=>'电子邮件'],
            ['name'=>'url','title'=>'网址'],
            ['name'=>'date','title'=>'日期'],
            ['name'=>'number','title'=>'有效的数值'],
            ['name'=>'digits','title'=>'数字'],
            ['name'=>'creditcard','title'=>'信用卡号码'],
            ['name'=>'equalTo','title'=>'再次输入相同的值'],
            ['name'=>'ip4','title'=>'IP'],
            ['name'=>'mobile','title'=>'手机号码'],
            ['name'=>'zipcode','title'=>'邮编'],
            ['name'=>'qq','title'=>'QQ'],
            ['name'=>'idcard','title'=>'身份证号'],
            ['name'=>'chinese','title'=>'中文字符'],
            ['name'=>'cn_username','title'=>'中文英文数字和下划线'],
            ['name'=>'tel','title'=>'电话号码'],
            ['name'=>'english','title'=>'英文'],
            ['name'=>'en_num','title'=>'英文数字和下划线'],
        ];
        $this->assign('type', $field_type);
        $this->assign('fieldtype', $fieldtype);
        $this->assign('pattern', $field_pattern);
    }
	
    public function index()
    {
        
        	//creatmodel('text');
        	$md=$this->module->order("id ASC")->cache(request()->controller(),0)->select();
        	$this->assign('data',$md);
            return $this->fetch();
   

		
       
    }
    
    public function add(){
    	if(Request::instance()->post()){
 			$post=Request::instance()->post();
			$tables = Db::getTables();
			$prefix = config('database.prefix');
			$tablename = $prefix.input('name');
			if(!input('name')){
			    $result['result'] = 0;
				$result['msg'] = '表名不能为空！';
			    return json($result);
			}	
			
			if(isset($post['id'])){//修改
				if($this->module->update($post)!==false){
	                return array('result'=>1,'msg'=>'修改成功!');
	            }else{
	                return array('result'=>0,'msg'=>'修改失败!');
	            }				
				
			}else{//添加
						
				if(in_array($tablename,$tables)){
				    $result['result'] = 0;
					$result['msg'] = '该表已经存在！';
				    return json($result);
				}else{
					if($post['type']==1){
						$this->module->allowField(true)->save($post);
					    $result['result'] = 1;
					    $result['msg'] = '模型添加成功！';
					    return json($result);
					}else{
					  	$sql = "CREATE TABLE ".$tablename." (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY)";
						if(Db::execute($sql)!==false){
							$this->module->allowField(true)->save($post);
						    $result['result'] = 1;
						    $result['msg'] = '该表创建成功！';
						    creatmodel(ucfirst(input('name')));
						    return json($result);
				  			
						}else{
						    $result['result'] = 0;
						    $result['msg'] = '该表创建失败！';
						    return json($result);					
						}    		
					}
				}
			}

		}else{
			$id=input('id');
			if($id){
            $map['id'] = input('param.id');
            $info = $this->module->field('id,title,name,description,listfields')->where($map)->find();
				
			}else{
				 $info='null';
			}			
			 $this->assign('title','添加模型');
			 $this->assign('info',$info);
	         return $this->fetch('form');	
		}
    	
    }

    
    
	public function field(){
		$id=input('id');
		$table=$this->module->where("id=".$id)->find();
		$data=$this->field->where("moduleid=".$id)->order("listorder ASC")->select();
		foreach($data as $k=>$v){
			$setup=string2array($v['setup']);
			$data[$k]['setup']=string2array($v['setup']);
		}
		$this->assign('id',$id);
		$this->assign('data',$data);
		$this->assign('table',$table['name']);
		$this->assign('title',$table['title']);
		return $this->fetch();	
	}
    public function addfield(){
    	if(Request::instance()->post()){
    		$post=Request::instance()->post();    		    		
    		$prefix = config('database.prefix');
			$tablename = $prefix.input('tablename');			
			$arr=array('int','smallint','tinyint');
			if(input('default')){
				$def=" DEFAULT '".input('default')."'";
			}else{
				$def='';
			}
			if(in_array(input('fieldtype'),$arr)){
				$fh=' unsigned';
			}else{
				$fh=' ';
			}
			if(input('fieldtype')=='mediumtext' || input('fieldtype')=='text'){
				$varchar=input('fieldtype');
			}else{				
				$varchar=input('fieldtype')."(".input('fieldlength').") ".$fh.$def;
			}

			  
		    $text=str_replace("\n","\r\n",$post['options']); 
	    	if($post['options']){
	    		$post['setup']="array('options'=>'".$text."','fieldlength'=>'".$post['fieldlength']."','fieldtype'=>'".$post['fieldtype']."','default' => '".$post['default']."',)";
	    	}else{
	    		$post['setup']="array('fieldlength'=>'".$post['fieldlength']."', 'fieldtype'=>'".$post['fieldtype']."', 'default' => '".$post['default']."')";
	    		
	    	}

				
			if(isset($post['id'])){//修改
				
					unset($post['tablename']);
					unset($post['options']);
					unset($post['fieldlength']);
					unset($post['fieldtype']);
					unset($post['default']);
					if($this->field->update($post)==true){
					    $result['result'] = 1;
					    $result['msg'] ='修改成功！';
					    return json($result);
						
					}else{
					    $result['result'] = 0;
					    $result['msg'] ='修改失败！';
					    return json($result);
						
					}
				
				
			}else{//添加
					  
		    	$sql="alter table ".$tablename." add ".input('field')." ".$varchar." not null ";
		    	$sqls="SHOW FULL COLUMNS FROM ".$tablename;
		    	$fields=Db::query($sqls);
		    	$field_arr=array_column($fields, 'Field');
				if(in_array(input('field'),$field_arr)){
				    $result['result'] = 0;
				    $result['msg'] ='字段已经存在！';
				    return json($result);
				}
				
				$post['listorder']=$this->field->getmaxorder($post['moduleid']);			    	

				if($this->field->allowField(true)->save($post)){
					if(Db::execute($sql)!==false){
					    $result['result'] = 1;
					    $result['msg'] = '字段添加成功！';
					    return json($result);
			  			
					}else{
					    $result['result'] = 0;
					    $result['msg'] = '字段添加失败！';
					    return json($result);
						
					}
				} else{
					    $result['result'] = 0;
					    $result['msg'] = '数据添加失败！';
					    return json($result);
					
				}			
			} 
				
		}else{
			
			if(input('id')){
				$info=$this->field->where("id=".input('id'))->find();
				$setup=string2array($info['setup']);
				$info['options']=isset($setup['options'])?$setup['options']:'';
				$info['fieldlength']=$setup['fieldlength'];
				$info['fieldtype']=$setup['fieldtype'];
			}else{
				$info='';
			}
			$tables=input('tables');			
			$this->assign('tabname',$tables);
			$this->assign('moduleid',input('moduleid'));
			$this->assign('info',$info);
			$this->assign('title','添加字段');			
			return $this->fetch();	
		}
	}
	public function order(){
		
		if(Request::instance()->post()){
			$prefix = config('database.prefix');
    		$post=Request::instance()->post();
			$tablename = $prefix.$post['tablename'];
			$data['listorder']=$post['listorder'];
			if($this->field->where("id=".$post['id'])->update($data)==true){
				
				$info=$this->field->where("id=".$post['id'])->find();
				
				$map['moduleid']=array('eq',$info['moduleid']);
				$map['listorder']=array('lt',$post['listorder']);
				
				$upone=$this->field->where($map)->order("listorder DESC")->limit(1)->find();				
				$up=$this->field->where("moduleid","eq",$info['moduleid'])->where("listorder","lt",$info['listorder'])->order("listorder DESC")->limit(1)->find();
				$down=$this->field->where("moduleid","eq",$info['moduleid'])->where("listorder","gt",$info['listorder'])->order("listorder ASC")->limit(1)->find();
				
				$setup=string2array($info['setup']);
				
				if($setup['fieldtype']=='text' || $setup['fieldtype']=='mediumtext'){
					$var='';
				}else{
					$var=$setup['fieldtype']."(".$setup['fieldlength'].")";
				}				
				
				//是否数据表字段位置有变化
				if($post['listorder']>=$up['listorder'] && $post['listorder']<=$down['listorder'] ){//位置没有变化
					    $result['result'] = 1;
					    $result['msg'] = '操作成功！';
					    return json($result);		  			
					
				}else{
					$sql="ALTER TABLE ".$tablename." MODIFY COLUMN ".$info['field']." ".$var."  CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '".$setup['default']."' AFTER `".$upone['field']."`";
					if(empty(Db::execute($sql))){
					    $result['result'] = 1;
					    $result['msg'] = '操作成功！';
					    return json($result);		  			
					}else{
					    $result['result'] = 0;
					    $result['msg'] = '操作失败！';
					    return json($result);					
					} 
					
				}
				
			}else{
			    $result['result'] = 0;
			    $result['msg'] = '操作失败！';
			    return json($result);					
				
			}
			
		
		
		}
	}
	public function delfield(){
		if(Request::instance()->post()){
			$prefix = config('database.prefix');
    		$post=Request::instance()->post();
			$tablename = $prefix.$post['tablename'];
			if($post['sys']==1){
				    $result['result'] = 0;
				    $result['msg'] = '系统字段不能删除';
				    return json($result);		  							
			}else{
				if($this->field->where("id=".$post['id'])->delete()==true){
		    		$sql="ALTER TABLE ". $tablename ." DROP COLUMN ". $post['field'];
		    		
					if(empty(Db::execute($sql))){
					    $result['result'] = 1;
					    $result['msg'] = '字段删除成功！';
					    return json($result);		  			
					}else{
					    $result['result'] = 0;
					    $result['msg'] = '字段删除失败！';
					    return json($result);					
					} 
				}else{
					    $result['result'] = 0;
					    $result['msg'] = '字段删除失败！';
					    return json($result);									
				}  				
			}
    	}
		
		
	}
    public function moduleState(){
        $id=input('post.id');
        $status=input('post.status');
        if($this->module->where('id='.$id)->update(['status'=>$status])!==false){
            return ['result'=>1,'msg'=>'设置成功!'];
        }else{
            return ['result'=>0,'msg'=>'设置失败!'];
        }
    }	
    //删除模型
    function del() {
        $id =input('param.id');
        $r = db('module')->find($id);
        if(!empty($r)){
            $tablename = config('database.prefix').$r['name'];

            $m = db('module')->delete($id);
            if($m){
                Db::execute("DROP TABLE IF EXISTS `".$tablename."`");
                db('Field')->where(array('moduleid'=>$id))->delete();
            }
        }
        
        return ['result'=>1,'msg'=>'删除成功！'];
    }	
	
}