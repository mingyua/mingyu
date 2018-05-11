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


namespace app\manage\model;

use \think\Model;
class Manage extends Model
{
	public function managecate()
    {
        //关联角色表
        return $this->belongsTo('ManageCate');
    }

    public function article()
    {
        //关联文章表
        return $this->hasOne('Article');
    }

    public function log()
    {
        //关联日志表
        return $this->hasOne('manage_log');
    }

    public function attachment()
    {
        //关联附件表
        return $this->hasOne('Attachment');
    }
}
