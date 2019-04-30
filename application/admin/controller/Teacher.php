<?php

namespace app\admin\controller;

use gmars\rbac\Rbac;
use menu\MenuTools;
use rbacm\RBACTools;
use think\Controller;
use think\Request;

class Teacher extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //

        // 判断权限
        if (!RBACTools::can('/teacher')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('没有该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('没有该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }
        //
        // 左侧菜单
        $menus = MenuTools::get_all_menus() ;
        // 获取试题列表
        $pagesize = \think\facade\Config::get('pagesize');

        $list = \app\admin\model\User::where("user_type", 2)->paginate($pagesize) ;

        return view('index',[
            'menus' => $menus,
            'list' => $list,
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



        // 判断权限
        if (!RBACTools::can('/teacher/create')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('没有该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('没有该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }

        $menus = MenuTools::get_all_menus() ;


        return view('create',[
            'menus' => $menus,
        ]) ;
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


        // 判断权限
        if (!RBACTools::can('/teacher/create')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('没有该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('没有该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }

        $data = input('put.') ;

        $data['create_time'] = time() ;
        $data['password'] = crypt($data['password'], 'exam') ;
        $data['user_type'] = 2 ;
        $data['status'] = 1 ;
        $data['id'] = uniqid() ;
        $rbacObj = new Rbac();


        // 两步操作 最好加上事务回滚 时间上问题 就不加了
        // 创建用户
        $flag = $rbacObj->createUser($data) ;
        if ($flag){
            // 添加默认用户组
            $rbacObj = new Rbac();
            if ($rbacObj->assignUserRole($data['id'], [3])){
                return $this->success('注册成功', url('/teacher'), '', '2' ) ;
            }

        }else{
            return $this->error('注册失败', url('/teacher'), '', '2' ) ;

        }




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


        // 判断权限
        if (!RBACTools::can('/teacher/edit')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('没有该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('没有该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }

        $user = \app\admin\model\User::where('id', $id)->find() ;

        // 左侧菜单
        $menus = MenuTools::get_all_menus() ;


        return view('edit',[
            'menus' => $menus,
            'user' => $user,

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

        // 判断权限
        if (!RBACTools::can('/teacher/edit')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('没有该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('没有该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }
        $data = input('put.') ;

        if ($data){
            $dept = new \app\admin\model\User() ;
            $flag = $dept->allowField(true)->save($data, ['id'=>$id]);
            if ($flag){
                $this->success('更新成功', url('/teacher'), '', '2' ) ;
                exit();
            }else{
                // 可以记录日志
                $this->error('更新失败', url('/teacher'), '', '2' ) ;
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


        // 判断权限
        if (!RBACTools::can('/teacher/delete')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }

        $data = \app\admin\model\User::get($id) ;

        if (!$data){
            $this->error('信息不存在', url('/teacher'), '', '2' ) ;
            exit();
        }

        // 删除用户 同时会删除用户的角色
        $rbac = new Rbac() ;
        $flag = $rbac->delUser($id) ; ;
        if ($flag){
            $this->success('删除成功', url('/teacher'), '', '2' ) ;
            exit();
        }else{
            // 可以记录日志
            $this->error('删除失败', url('/teacher'), '', '2' ) ;
//            \think\facade\Log::write('error') ;
        }

    }
}
