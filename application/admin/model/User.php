<?php

namespace app\admin\model;

use think\Model;

class User extends Model
{
    //


    public function clz(){
        return $this->hasOne('Classz', 'id', 'class_id') ;
    }

    public function majorinfo(){
        return $this->hasOne('Major', 'id', 'major') ;
    }


}
