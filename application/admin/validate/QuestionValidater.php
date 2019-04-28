<?php
/**
 * Created by PhpStorm.
 * User: zrlee
 * Date: 19-2-4
 * Time: 下午6:40
 */

namespace app\admin\validate;


use think\Validate;

class QuestionValidater extends Validate {

    protected $rule =[
        'dbid' => 'require',
        'from' => 'require' ,
        'title' => 'require' ,
        'item' => 'require' ,
        'resolve' => 'require' ,
    ] ;
}