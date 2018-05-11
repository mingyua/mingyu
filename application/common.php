<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/*
 * 系统配置项数组转化
 * $arr 系统配置数组
 * $newarr 返回新数组
 */
function texttoarr($arr){
		$newarr=array();
		foreach($arr as $k=>$v){
			$newarr[$v['name']]=$v['value'];
		}
		return $newarr;		
}
/*
 * php截取指定两个字符之间字符串
 * */

function strbetween($srcstr,$str1,$str2)
 {
  $i=strrpos($srcstr,$str1); //第一个字符串在源字符串中出现的位置
  $j=strrpos($srcstr,$str2);//第二个字符串在源字符串中出现的位置
  $b=substr($srcstr,$i+strlen($str1),$j-$i-strlen($str1));//截取字符串，其中$i+strlen($str1)计算起始位置，$j-$i-strlen($str1)计算截取字符串的长度
  return $b;//返回值
}

/*
 *获取分类名称 
 * 
 * *****/
function catname($catid,$field='null'){
	$cate=\think\Db::name("category")->where(['id' => $catid])->find();
	if($field=='null'){
		$cname=$cate['catname'];
	}else{
		$cname=$cate[$field];
	}
	
	return $cname;
}
/*
 *获取模型名称 
 * 
 * *****/

function getmdname($mdid,$filed){
		$md=\think\Db::name("module")->where(['id' => $mdid])->find();
		return $md[$filed];

}
/**
 * 根据附件表的id返回url地址
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
function geturl($id)
{
	if ($id) {
		$geturl = \think\Db::name("attachment")->where(['id' => $id])->find();
		if($geturl['status'] == 1) {
			//审核通过
			return $geturl['filepath'];
		} elseif($geturl['status'] == 0) {
			//待审核
			return './uploads/picture.jpg';
		} else {
			//不通过
			return './uploads/picture.jpg';
		} 
    }
    return false;
}
//判断图片的类型从而设置图片路径
function imgUrl($img,$defaul=''){
    if($img){
        if(substr($img,0,4)=='http'){
            $imgUrl = $img;
        }else{
            $imgUrl = '__PUBLIC__'.$img;
        }
    }else{
        if($defaul){
            $imgUrl = $defaul;
        }else{
            $imgUrl = '__ADMIN__/images/tong.png';
        }

    }
    return $imgUrl;
}
/*
 * 获取顶级父类id 
 * @param  [type] $id [description] 
 * @return [type]     [description] 
 */  
function get_top_parentid($cate,$id){
	$arr=array();
	foreach($cate as $v){
		if($v['id']==$id){
			$arr[]=$v['parentid'];// $arr[$v['id']]=$v['name'];
			$arr=array_merge(get_top_parentid($cate,$v['parentid']),$arr);
		}
	}
	return $arr;

} 
/*
 *计算指定时间到现在有多少天 
 * $starttime  指定开始时间
 * $endtime  指定结束时间
 * */
function countdays($starttime,$endtime=''){
	if(!$endtime){
		$now=date('Y-m-d H:i:s');		
	}else{
		$now=$endtime;		
	}
	$s=strtotime($starttime);
	$e=strtotime($now);
	$return['day'] = round(($e-$s)/3600/24);	
	$second=$e-$s;	
	$sytime=$second%(3600*24);//		
	$return['hours'] = round($sytime/3600);	//小时	
	$sym=$sytime%3600;	
	$return['min'] = round($sym/60);	
	$syi=$sym%60;
	return $return['day']."天".$return['hours']."小时".$return['min']."分钟".$syi."秒";
}

function parse_config_attr($string)
    {
        $array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
        if(strpos($string,':')){
            $value  =   array();
            foreach ($array as $val) {
                list($k, $v) = explode(':', $val);
                $value[$k]   = $v;
            }
        }else{
            $value  =   $array;
        }
        return $value;
    }
function template_file($module=''){
    $tempfiles = dir_list(ROOT_PATH.'/themes/moban1','html');
    foreach ($tempfiles as $key=>$file){
        $dirname = basename($file);
        if($module){
            if(strstr($dirname,$module.'_')) {
                $arr[$key]['value'] =  substr($dirname,0,strrpos($dirname, '.'));
                $arr[$key]['filename'] = $dirname;
                $arr[$key]['filepath'] = $file;
            }
        }else{
            $arr[$key]['value'] = substr($dirname,0,strrpos($dirname, '.'));
            $arr[$key]['filename'] = $dirname;
            $arr[$key]['filepath'] = $file;
        }
    }
    return  $arr;
}
function dir_list($path, $exts = '', $list= array()) {
    $path = dir_path($path);
    $files = glob($path.'*');
    foreach($files as $v) {
        $fileext = fileext($v);
        if (!$exts || preg_match("/\.($exts)/i", $v)) {
            $list[] = $v;
            if (is_dir($v)) {
                $list = dir_list($v, $exts, $list);
            }
        }
    }
    return $list;
}
function dir_path($path) {
    $path = str_replace('\\', '/', $path);
    if(substr($path, -1) != '/') $path = $path.'/';
    return $path;
}
function fileext($filename) {
    return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
}
function checkField($table,$value,$field){
    $count = db($table)->where(array($field=>$value))->count();
    if($count>0){
        return true;
    }else{
        return false;
    }
}
//文件单位换算
function byte_format($input, $dec=0){
    $prefix_arr = array("B", "KB", "MB", "GB", "TB");
    $value = round($input, $dec);
    $i=0;
    while ($value>1024) {
        $value /= 1024;
        $i++;
    }
    $return_str = round($value, $dec).$prefix_arr[$i];
    return $return_str;
}
//时间日期转换
function toDate($time, $format = 'Y-m-d H:i:s') {
    if (empty ( $time )) {
        return '';
    }
    $format = str_replace ( '#', ':', $format );
    return date($format, $time );
}

/*
 * 大写数字
 * 
 */
function numberwords($number,$type=''){
	$array=['0'=>'零','1'=>'一','2'=>'二','3'=>'三','4'=>'四','5'=>'五','6'=>'六','7'=>'七','8'=>'八','9'=>'九','10'=>'十','11'=>'十一','12'=>'十二'];
	
	return $array[intval($number)];
}

function string2array($info) {
    if($info == '') return array();
    eval("\$r = $info;");
    return $r;
}
function array2string($info) {
    if($info == '') return '';
    if(!is_array($info)){
        $string = stripslashes($info);
    }
    foreach($info as $key => $val){
        $string[$key] = stripslashes($val);
    }
    $setup = var_export($string, TRUE);
    return $setup;
}
function radio($setup,$name='',$req='',$val=''){
	$info = is_array($setup) ? $setup : string2array($setup);
	$option=isset($info['options']) ? $info['options']:'';
	$options = explode("\r\n", $option);    		 
	$optionsarr='';
	if(!empty($option)){		
        foreach ($options as $r) {
            $v = explode("|", $r);
            $k = trim($v[1]);
            if($k==$val){$ck="checked";}else{$ck="";}
            
            $optionsarr .='<input name="'.$name.'" lay-verify="'.$req.'" autocomplete="off" placeholder="请输入标题" id="" class="layui-input" type="radio" title="'.$v[0].'"  '.$ck.' value="'.$k.'">';
        }	
	}	
	return $optionsarr;
}
function kinds($kinds,$mdid,$field){
	
	$module=\think\Db::name("module")->where(['name' => $mdid])->find();
	$setup=\think\Db::name("field")->where(['moduleid'=>$module['id'],'field' => $field])->find();
	
	$info=is_array($setup['setup']) ? $setup['setup']: string2array($setup['setup']);
	$option=isset($info['options']) ? $info['options']:'';
	$options = explode("\r\n", $option);    		 
	$optionsarr='';
	if(!empty($option)){		
        foreach ($options as $r) {
            $v = explode("|", $r);
            $k = trim($v[1]);
			$optionsarr[$k] = $v[0];
        }	
	}			
	return $optionsarr[$kinds];
}


function uppage($catid,$id){
	$module=\think\Db::name("category")->where(['id' => $catid])->find();
	$dbname=$module['module'];
	$mod=$module['catdir'];
	$data=\think\Db::name($dbname)->where('id','lt',$id)->where('catid','eq',$catid)->order('id DESC')->find();	
	if($data){
	$html='<p><a href="__ROOT__/'.$mod.'-'.$catid.'-'.$data['id'].'.html"><em>上一篇：</em>'.$data['title'].'</a></p>';
		
	}else{
		$html="";
	}

	return $html;
}
function downpage($catid,$id){
	$module=\think\Db::name("category")->where(['id' => $catid])->find();
	$dbname=$module['module'];
	$mod=$module['catdir'];
	$data=\think\Db::name($dbname)->where('id','gt',$id)->where('catid','eq',$catid)->order('id ASC')->find();	
	if($data){
	$html='<p><a href="__ROOT__/'.$mod.'-'.$catid.'-'.$data['id'].'.html"><em>下一篇：</em>'.$data['title'].'</a></p>';
		
	}else{
		$html="";
	}

	return $html;
}


function _mysql_version()
    {
	$mysqlinfo = db()->query("select VERSION()");
	return $mysqlinfo[0]['VERSION()'];
    }
