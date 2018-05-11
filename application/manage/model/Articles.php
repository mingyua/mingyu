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
class Article extends Model
{

	public function category()
    {
        //关联分类表
        return $this->belongsTo('category');
    }

    public function manage()
    {
        //关联角色表
        return $this->belongsTo('Manage');
    }
}
