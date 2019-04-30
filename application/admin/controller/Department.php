<?php

namespace app\admin\controller;

use app\admin\model\Menu;
use menu\MenuTools;
use rbacm\RBACTools;
use think\Controller;
use think\Request;

class Department extends Controller
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
        if (!RBACTools::can('/dept')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }


        // 左侧菜单
        $menus = MenuTools::get_all_menus() ;
        // 获取试题列表
        $pagesize = \think\facade\Config::get('pagesize');

        $list = \app\admin\model\Department::paginate($pagesize) ;


//        $url = \think\facade\Request::baseUrl() ;
//        dump(dirname($_SERVER['SCRIPT_NAME'])) ;
//        dump($url) ;
//        exit() ;

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
        if (!RBACTools::can('/dept/create')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }


        $menus = MenuTools::get_all_menus() ;
        return view('dept_add',[
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

        // 判断权限
        if (!RBACTools::can('/dept/create')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }

        $data = input('post.') ;
        if ($data){
            $dept = new \app\admin\model\Department() ;
            $data['id'] = uniqid() ;
            $flag = $dept->allowField(true)->save($data) ;

            if ($flag){
                $this->success('添加成功', url('/dept'), '', '2' ) ;
                exit();
            }else{
                // 可以记录日志
                $this->error('添加失败', url('/dept'), '', '2' ) ;
            }
        }else{
            $this->error('数据有误', url('/dept'), '', '2' ) ;
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
        if (!RBACTools::can('/dept/edit')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }

        // 该id下的院系信息
        $dept = \app\admin\model\Department::find($id) ;


        $menus = MenuTools::get_all_menus() ;
        return view('dept_edit',[
            'menus' => $menus,
            'dept' =>$dept

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
        if (!RBACTools::can('/dept/edit')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }

        $data = input() ;
        if ($data){
            $dept = new \app\admin\model\Department() ;
            $flag = $dept->allowField(true)->save($data, ['id'=>$id]);
            if ($flag){
                $this->success('更新成功', url('/dept'), '', '2' ) ;
                exit();
            }else{
                // 可以记录日志
                $this->error('更新失败', url('/dept'), '', '2' ) ;
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
        if (!RBACTools::can('/dept/delete')){

            $user = session('user') ;
            if ($user['user_type'] == 3) {
                return $this->error('您不具备该权限，请联系管理员', url('/'), '', '2' ) ;
            }else{
                return $this->error('您不具备该权限，请联系管理员', url('/admin'), '', '2' ) ;
            }


        }
        $clz = \app\admin\model\Classz::where("deptno", $id)->select() ;

        if (count($clz)>0) {

            $this->error('删除失败,原因院系有班级', url('/dept'), '', '2' ) ;
            exit() ;
        }





        $flag = \app\admin\model\Department::destroy($id) ;

        if ($flag){
            $this->success('删除成功', url('/dept'), '', '2' ) ;
            exit();
        }else{
            // 可以记录日志
            $this->error('删除失败', url('/dept'), '', '2' ) ;
//            \think\facade\Log::write('error') ;
        }
    }


    public function check($name){
        if ($name){


            $name = str_replace('_', '-' , $name);


            $data = \app\admin\model\Department::where('name', $name)->find() ;

            if ($data){
                return 1;
            }else{
                return 0 ;
            }


        }else{
            return -1;
        }

    }
}
