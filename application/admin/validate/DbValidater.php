<?php
/**
 * Created by PhpStorm.
 * User: zrlee
 * Date: 19-2-3
 * Time: 上午11:42
 */

namespace app\admin\validate;


use think\Validate;


/**
 * 题库表单验证器
 * Class DbValidater
 * @package app\admin\validate
 */
class DbValidater extends Validate{

    protected $rule = [
        'name' => 'require',
//        '__token__'=>'require|token', //这里__token__不能去改.

    ] ;

    protected $message = [
        'name.require' => '名称必须填写' ,
    ];

}