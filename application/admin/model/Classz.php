<?php

namespace app\admin\model;

use think\Model;

class Classz extends Model
{
    //

    public function dept(){
        return $this->hasOne('Department', 'id', 'deptno') ;
    }
}
