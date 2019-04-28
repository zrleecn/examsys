<?php

namespace app\admin\model;

use think\Model;

class Paper extends Model
{
    //

    // 模型关联
    public function category(){
        return $this->hasOne('PaperCategory','id', 'cid') ;
    }

    public function test(){
        return 1111;
    }
}
