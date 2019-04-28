<?php

namespace app\admin\controller;

use gmars\rbac\Rbac;
use think\Controller;
use think\facade\Session;
use think\Request;

class Login extends Controller
{


    public function index(){
        return view('login') ;
    }


    /**
     * 用户登录
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function login(){

       $method = \think\facade\Request::method() ;

       if (strtolower( $method ) == 'post'){

           $data = input() ;
           // 查询用户
           if ($data['user_type'] == 1){
               $user_name = $data['user_name'] ;
               $password = $data['password'] ;
               if ($user_name && $password) {


                   // 按照用户查找
                   $user = \app\admin\model\User::where('user_name', $user_name)->find() ;
                   if ($user){
                       // 对比密码
                       if ($user['password'] == crypt($password, 'exam')){
                           Session::delete('user') ;
                           // 保存session
                           session('user', $user) ;
                           $rbacObj = new Rbac();
                           $rbacObj->cachePermission($user['id']) ;
                           $this->success('登录成功', url('/admin'), '', '1') ;
                       }else{
                           $this->error('用户名或者密码错误', url('/login'), '', '1') ;
                       }

                   }
               }

           }

       }


    }



    public function logout(){
        Session::delete('user') ;

        $this->success('已经退出登录', url('/login'), '' , '2') ;
    }

}
