<?php
namespace app\manage\model;

use think\Model;

class Field extends Model
{
    protected $pk = 'id';
    
    /*
     * $moduleid 模型分类
     * 返回listorder 最大值
     */
    public function getmaxorder($moduleid){
    	$result=$this->where("moduleid=".$moduleid)->max('listorder');
    	return $result+1;
    }
    /*
     * $moduleid 模型分类
     * 返回   数据表名称 
     */
  public function gettable($moduleid){
    	//$result=$this->where("id=".$moduleid)->field('id,name')->find();
    	return 2;
    }
}
?>