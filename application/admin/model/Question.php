<?php

namespace app\admin\model;

use think\Model;

class Question extends Model
{
    //



    // 模型关联
    public function dbinfo(){
        return $this->hasOne('QuestionDb','id', 'dbid') ;
    }
}
