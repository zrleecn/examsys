<?php

namespace app\admin\model;

use think\Model;

class Major extends Model
{
    //

    public function dept(){
        return $this->hasOne('Department', 'id', 'dept_id') ;
    }
}
