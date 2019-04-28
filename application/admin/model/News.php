<?php

namespace app\admin\model;

use think\Model;

class News extends Model
{
    //
    public function cate(){
        return $this->hasOne('NewsCategory', 'id', 'cate_id') ;
    }
}
