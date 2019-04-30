<?php

namespace app\index\controller;

use think\Controller;
use think\facade\Session;
use think\Request;

class User extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        // 获取个人信息
        $user = session('user') ;
        $id = session('user')['id'] ;
        if (!$user){
            $this->error('请先登录', url('/login'), '', '1') ;
        }




        return view('index',[
            'user' => $user,
            'id' => $id
        ]) ;
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
        $user = \app\admin\model\User::find($id) ;
        if (!$user){
            exit() ;
        }
        return view('edit',[
            'user' => $user,
            'id' => $user['id']
        ]) ;



    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //


        $data = input('put.') ;


        if ($data){
            $model = new \app\admin\model\User() ;
            $flag = $model->allowField(true)->save($data, ['id'=>$id]);
            if ($flag){

                Session::delete('user') ;
                session('user', \app\admin\model\User::find($id)) ;

                $this->success('更新成功', url('/student'), '', '2' ) ;
                exit();
            }else{
                // 可以记录日志
                $this->error('更新失败', url('/student'), '', '2' ) ;
            }
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }

    public function passwdedit($id){


        $method = \think\facade\Request::method() ;
        if (strtolower($method) == 'get'){
            return view('passwd/index',[

                'id' => $id
            ]) ;
        }else if (strtolower($method) == 'put'){

            // 更新密码
            $data = input('put.') ;
            $password = $data['password'] ;
            $newpwd = $data['newpwd'] ;
            $formpwd = $data['formpwd'] ;


            if ($newpwd != $formpwd) {
                $this->error('俩次密码不一致', url("student/passwd/{$id}/edit"), '', '1') ;
                exit() ;
            }

            $user = \app\index\model\User::where('id', $id)->find() ;
            // 后面可以改为ajax验证
            if (crypt($password, 'exam') != $user['password']){
                $this->error('原密码不正确', url("student/passwd/{$id}/edit"), '', '1') ;
                exit() ;
            }else{
                $model = new \app\index\model\User() ;
                $flag = $model->save([
                    'password' => crypt($newpwd, 'exam')
                ], ['id'=> $id]) ;

                if ($flag){
                    session(null);

                    $this->success('success', url('/student'),'', '1') ;
                }
            }



        }

    }



}
